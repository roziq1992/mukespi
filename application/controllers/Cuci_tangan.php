<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cuci_tangan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Cuci_tangan_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'index.php/cuci_tangan/?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/cuci_tangan/?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/cuci_tangan/';
            $config['first_url'] = base_url() . 'index.php/cuci_tangan/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Cuci_tangan_model->total_rows($q);
        $cuci_tangan = $this->Cuci_tangan_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'cuci_tangan_data' => $cuci_tangan,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('template/header',$data);
        $this->load->view('cuci_tangan/cuci_tangan_list');
        $this->load->view('template/footer');
       
    }
    public function lprtotal()
    {
        $this->load->view('template/header');
        $this->load->view('cuci_tangan/lprall');
        $this->load->view('template/footer');
    }
    public function lprtunit()
    {
        $this->load->view('template/header');
        $this->load->view('cuci_tangan/lprunit');
        $this->load->view('template/footer');
    }
     public function lprmoment()
    {
        $this->load->view('template/header');
        $this->load->view('cuci_tangan/lprmoment');
        $this->load->view('template/footer');
    }

    public function read($id) 
    {
        $row = $this->Cuci_tangan_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'nm' => $row->nm,
		'profesi' => $row->profesi,
		'unit' => $row->unit,
		'kesempatan' => $row->kesempatan,
		'cucitangan' => $row->cucitangan,
		'ketcuci' => $row->ketcuci,
		'tanggal' => $row->tanggal,
		'moment' => $row->moment
	    );
	        $this->load->view('template/header');
            $this->load->view('cuci_tangan/cuci_tangan_read', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('cuci_tangan'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('cuci_tangan/create_action'),
	    'id' => set_value('id'),
	    'nm' => set_value('nm'),
	    'profesi' => set_value('profesi'),
	    'unit' => set_value('unit'),
	    'kesempatan' => set_value('kesempatan'),
	    'cucitangan' => set_value('cucitangan'),
	    'ketcuci' => set_value('ketcuci'),
	    'tanggal' => set_value('tanggal'),
	    'moment' => set_value('moment'),
        'unit2' => $this->Cuci_tangan_model->unit(),
        'moment2' => $this->Cuci_tangan_model->moment()
	);
        $this->load->view('template/header',$data);
        $this->load->view('cuci_tangan/cuci_tangan_form');
        $this->load->view('template/footer');
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'nm' => $this->input->post('nm',TRUE),
		'profesi' => $this->input->post('profesi',TRUE),
		'unit' => $this->input->post('unit',TRUE),
		'kesempatan' => $this->input->post('kesempatan',TRUE),
		'cucitangan' => $this->input->post('cucitangan',TRUE),
		'ketcuci' => $this->input->post('ketcuci',TRUE),
		'tanggal' => $this->input->post('tanggal',TRUE),
		'moment' => $this->input->post('moment',TRUE),
	    );

            $this->Cuci_tangan_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('cuci_tangan'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Cuci_tangan_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('cuci_tangan/update_action'),
		'id' => set_value('id', $row->id),
		'nm' => set_value('nm', $row->nm),
		'profesi' => set_value('profesi', $row->profesi),
		'unit' => set_value('unit', $row->unit),
		'kesempatan' => set_value('kesempatan', $row->kesempatan),
		'cucitangan' => set_value('cucitangan', $row->cucitangan),
		'ketcuci' => set_value('ketcuci', $row->ketcuci),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'moment' => set_value('moment', $row->moment),
		'unit2' => $this->Cuci_tangan_model->unit(),
        'moment2' => $this->Cuci_tangan_model->moment()
	    );
	    $this->load->view('template/header',$data);
            $this->load->view('cuci_tangan/cuci_tangan_form', $data);
             $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('cuci_tangan'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'nm' => $this->input->post('nm',TRUE),
		'profesi' => $this->input->post('profesi',TRUE),
		'unit' => $this->input->post('unit',TRUE),
		'kesempatan' => $this->input->post('kesempatan',TRUE),
		'cucitangan' => $this->input->post('cucitangan',TRUE),
		'ketcuci' => $this->input->post('ketcuci',TRUE),
		'tanggal' => $this->input->post('tanggal',TRUE),
		'moment' => $this->input->post('moment',TRUE),
	    );
             $this->load->view('template/header',$data);
            $this->Cuci_tangan_model->update($this->input->post('id', TRUE), $data);
            
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('cuci_tangan'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Cuci_tangan_model->get_by_id($id);

        if ($row) {
            $this->Cuci_tangan_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('cuci_tangan'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('cuci_tangan'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('nm', 'nm', 'trim|required');
	$this->form_validation->set_rules('profesi', 'profesi', 'trim|required');
	$this->form_validation->set_rules('unit', 'unit', 'trim|required');
	$this->form_validation->set_rules('kesempatan', 'kesempatan', 'trim|required');
	$this->form_validation->set_rules('cucitangan', 'cucitangan', 'trim|required');
	$this->form_validation->set_rules('ketcuci', 'ketcuci', 'trim|required');
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('moment', 'moment', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "cuci_tangan.xls";
        $judul = "cuci_tangan";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Nm");
	xlsWriteLabel($tablehead, $kolomhead++, "Profesi");
	xlsWriteLabel($tablehead, $kolomhead++, "Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Kesempatan");
	xlsWriteLabel($tablehead, $kolomhead++, "Cucitangan");
	xlsWriteLabel($tablehead, $kolomhead++, "Ketcuci");
	xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
	xlsWriteLabel($tablehead, $kolomhead++, "Moment");

	foreach ($this->Cuci_tangan_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nm);
	    xlsWriteLabel($tablebody, $kolombody++, $data->profesi);
	    xlsWriteNumber($tablebody, $kolombody++, $data->unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kesempatan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->cucitangan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->ketcuci);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
	    xlsWriteNumber($tablebody, $kolombody++, $data->moment);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Cuci_tangan.php */
/* Location: ./application/controllers/Cuci_tangan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2022-12-13 01:30:14 */
/* http://harviacode.com */