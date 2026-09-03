<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mutu_indikator extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mutu_indikator_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $idindikator = urldecode($this->input->get('id', TRUE));
        $judul = urldecode($this->input->get('judul', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'index.php/mutu_indikator/?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
            $config['first_url'] = base_url() . 'index.php/mutu_indikator/?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
        } else {
            $config['base_url'] = base_url() . 'index.php/mutu_indikator/?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
            $config['first_url'] = base_url() . 'index.php/mutu_indikator/?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
        }

        $config['per_page'] = 31;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Mutu_indikator_model->total_rows($q,$idindikator);
        $mutu_indikator = $this->Mutu_indikator_model->get_limit_data($config['per_page'], $start, $q, $idindikator);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'mutu_indikator_data' => $mutu_indikator,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
             'button' => 'Save',
            'action' => site_url('mutu_indikator/create_action'),
    	    'id_mutu' => set_value('id_mutu'),
    	    'tanggal' => set_value('tanggal'),
    	    'id_indikator' => set_value('id_indikator'),
    	    'num' => set_value('num'),
    	    'demu' => set_value('demu'),
        );
   
        
        $this->load->view('template/header',$data);
        $this->load->view('mutu_indikator/mutu_indikator_list');
        $this->load->view('template/footer');
    }

    public function read($id) 
    {
        $row = $this->Mutu_indikator_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_mutu' => $row->id_mutu,
		'tanggal' => $row->tanggal,
		'id_indikator' => $row->id_indikator,
		'num' => $row->num,
		'demu' => $row->demu,
	    );
            $this->load->view('mutu_indikator/mutu_indikator_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_indikator'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('mutu_indikator/create_action'),
	    'id_mutu' => set_value('id_mutu'),
	    'tanggal' => set_value('tanggal'),
	    'id_indikator' => set_value('id_indikator'),
	    'num' => set_value('num'),
	    'demu' => set_value('demu'),
	);
        $this->load->view('mutu_indikator/mutu_indikator_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'tanggal' => $this->input->post('tanggal',TRUE),
		'id_indikator' => $this->input->post('id_indikator',TRUE),
		'num' => $this->input->post('num',TRUE),
		'demu' => $this->input->post('demu',TRUE),
		'target' => $this->input->post('target',TRUE),
	    );

            $this->Mutu_indikator_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('mutu_indikator?id='.$this->input->post('id_indikator',TRUE).'&judul='.$this->input->post('judul',TRUE).'&tanggal='.$this->input->post('tanggal',TRUE)));
        }
    }
    
    public function update() 
    {
        $id=$this->input->get('idmutu',TRUE);
        $row = $this->Mutu_indikator_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('mutu_indikator/update_action'),
		'id_mutu' => set_value('id_mutu', $row->id_mutu),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'id_indikator' => set_value('id_indikator', $row->id_indikator),
		'num' => set_value('num', $row->num),
		'demu' => set_value('demu', $row->demu),
	    );
	     $this->load->view('template/header',$data);
         $this->load->view('mutu_indikator/mutu_indikator_form', $data);
         $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_indikator'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_mutu', TRUE));
        } else {
            $data = array(
		'tanggal' => $this->input->post('tanggal',TRUE),
		'id_indikator' => $this->input->post('id_indikator',TRUE),
		'num' => $this->input->post('num',TRUE),
		'demu' => $this->input->post('demu',TRUE),
	    );

            $this->Mutu_indikator_model->update($this->input->post('id_mutu', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('mutu_indikator?id='.$this->input->post('id_indikator',TRUE).'&judul='.$this->input->post('judul',TRUE).'&tanggal='.$this->input->post('tanggal',TRUE)));
        }
    }
    
    public function delete() 
    {
         $id=$this->input->get('idmutu',TRUE);
        $row = $this->Mutu_indikator_model->get_by_id($id);

        if ($row) {
            $this->Mutu_indikator_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('mutu_indikator?id='.$this->input->get('id',TRUE).'&judul='.$this->input->get('judul',TRUE).'&tanggal='.$this->input->get('tanggal',TRUE)));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_indikator?id='.$this->input->get('id',TRUE).'&judul='.$this->input->get('judul',TRUE).'&tanggal='.$this->input->get('tanggal',TRUE)));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('id_indikator', 'id indikator', 'trim|required');
	$this->form_validation->set_rules('num', 'num', 'trim|required|numeric');
	$this->form_validation->set_rules('demu', 'demu', 'trim|required|numeric');

	$this->form_validation->set_rules('id_mutu', 'id_mutu', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "mutu_indikator.xls";
        $judul = "mutu_indikator";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
	xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
	xlsWriteLabel($tablehead, $kolomhead++, "Id Indikator");
	xlsWriteLabel($tablehead, $kolomhead++, "Num");
	xlsWriteLabel($tablehead, $kolomhead++, "Demu");

	foreach ($this->Mutu_indikator_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
	    xlsWriteNumber($tablebody, $kolombody++, $data->id_indikator);
	    xlsWriteNumber($tablebody, $kolombody++, $data->num);
	    xlsWriteNumber($tablebody, $kolombody++, $data->demu);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Mutu_indikator.php */
/* Location: ./application/controllers/Mutu_indikator.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2023-01-07 02:04:16 */
/* http://harviacode.com */