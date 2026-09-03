<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mutu_fmea extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
         is_logged_in();
        $this->load->model('Mutu_fmea_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
         $idindikator = urldecode($this->input->get('id', TRUE));
        $judul = urldecode($this->input->get('judul', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'index.php/mutu_fmea?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
            $config['first_url'] = base_url() . 'index.php/mutu_fmea?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
        } else {
            $config['base_url'] = base_url() . 'index.php/mutu_fmea?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
            $config['first_url'] = base_url() . 'index.php/mutu_fmea?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Mutu_fmea_model->total_rows($q,$idindikator);
        $mutu_fmea = $this->Mutu_fmea_model->get_limit_data($config['per_page'], $start, $q,$idindikator);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'mutu_fmea_data' => $mutu_fmea,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
       
         $this->load->view('template/header',$data);
        $this->load->view('mutu_fmea/mutu_fmea_list');
        $this->load->view('template/footer');
    }

    public function read($id) 
    {
        $row = $this->Mutu_fmea_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_fmea' => $row->id_fmea,
		'tanggal1' => $row->tanggal1,
		'tanggal2' => $row->tanggal2,
		'id_indikator' => $row->id_indikator,
		'tahun_periode' => $row->tahun_periode,
		'periode_lapor' => $row->periode_lapor,
		'target' => $row->target,
		'analisa' => $row->analisa,
	    );
        $this->load->view('template/header',$data);
        $this->load->view('mutu_fmea/mutu_fmea_read');
     
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_fmea'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Tambah',
            'action' => site_url('mutu_fmea/create_action'),
	    'id_fmea' => set_value('id_fmea'),
	    'tanggal1' => set_value('tanggal1'),
	    'tanggal2' => set_value('tanggal2'),
	    'id_indikator' => set_value('id_indikator'),
	    'tahun_periode' => set_value('tahun_periode'),
	    'periode_lapor' => set_value('periode_lapor'),
	    'target' => set_value('target'),
	    'analisa' => set_value('analisa'),
	);
       
        $this->load->view('template/header',$data);
        $this->load->view('mutu_fmea/mutu_fmea_form');
        $this->load->view('template/footer');
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'tanggal1' => $this->input->post('tanggal1',TRUE),
		'tanggal2' => $this->input->post('tanggal2',TRUE),
		'id_indikator' => $this->input->post('id_indikator',TRUE),
		'tahun_periode' => $this->input->post('tahun_periode',TRUE),
		'periode_lapor' => $this->input->post('periode_lapor',TRUE),
		'target' => $this->input->post('target',TRUE),
		'analisa' => $this->input->post('analisa',TRUE),
	    );

            $this->Mutu_fmea_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('mutu_fmea?id='.$this->input->post('id_indikator').'&judul='.$this->input->post('judul')));
        }
    }
    
    public function update() 
    {
        $id=$this->input->get('idfmea',TRUE);
        $row = $this->Mutu_fmea_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('mutu_fmea/update_action'),
		'id_fmea' => set_value('id_fmea', $row->id_fmea),
		'tanggal1' => set_value('tanggal1', $row->tanggal1),
		'tanggal2' => set_value('tanggal2', $row->tanggal2),
		'id_indikator' => set_value('id_indikator', $row->id_indikator),
		'tahun_periode' => set_value('tahun_periode', $row->tahun_periode),
		'periode_lapor' => set_value('periode_lapor', $row->periode_lapor),
		'target' => set_value('target', $row->target),
		'analisa' => set_value('analisa', $row->analisa),
	    );
        $this->load->view('template/header',$data);
        $this->load->view('mutu_fmea/mutu_fmea_form');
        $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_fmea'));
        }
    }
     public function updaterekomendasi() 
    {
        $id=$this->input->get('idfmea',TRUE);
        $row = $this->Mutu_fmea_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('mutu_fmea/update_action_rekom'),
        'id_fmea' => set_value('id_fmea', $row->id_fmea),
		'rekomendasi' => set_value('rekomendasi', $row->rekomendasi),
	    );
        $this->load->view('template/header',$data);
        $this->load->view('mutu_fmea/mutu_fmea_form_rekom');
        $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_fmea'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_fmea', TRUE));
        } else {
            $data = array(
		'tanggal1' => $this->input->post('tanggal1',TRUE),
		'tanggal2' => $this->input->post('tanggal2',TRUE),
		'id_indikator' => $this->input->post('id_indikator',TRUE),
		'tahun_periode' => $this->input->post('tahun_periode',TRUE),
		'periode_lapor' => $this->input->post('periode_lapor',TRUE),
		'target' => $this->input->post('target',TRUE),
		'analisa' => $this->input->post('analisa',TRUE),
	    );
            $this->Mutu_fmea_model->update($this->input->post('id_fmea', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
           redirect(site_url('mutu_fmea?id='.$this->input->post('id_indikator').'&judul='.$this->input->post('judul')));
        }
    }
     public function update_action_rekom() 
    {
        $this->_rules();
        $data = array(
		'rekomendasi' => $this->input->post('rekomendasi',TRUE),
	    );
            $this->Mutu_fmea_model->update($this->input->post('id_fmea', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
           redirect(site_url('mutu_fmea?id='.$this->input->post('id_indikator').'&judul='.$this->input->post('judul')));
        
    }
    
    public function delete() 
    {
        $id=$this->input->get('idfmea',TRUE);
        $row = $this->Mutu_fmea_model->get_by_id($id);

        if ($row) {
            $this->Mutu_fmea_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('mutu_fmea?id='.$this->input->get('id').'&judul='.$this->input->get('judul')));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_fmea'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('tanggal1', 'tanggal1', 'trim|required');
	$this->form_validation->set_rules('tanggal2', 'tanggal2', 'trim|required');
	$this->form_validation->set_rules('id_indikator', 'id indikator', 'trim|required');
	$this->form_validation->set_rules('tahun_periode', 'tahun periode', 'trim|required');
	$this->form_validation->set_rules('periode_lapor', 'periode lapor', 'trim|required');
	$this->form_validation->set_rules('target', 'target', 'trim|required');
	$this->form_validation->set_rules('analisa', 'analisa', 'trim|required');

	$this->form_validation->set_rules('id_fmea', 'id_fmea', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "mutu_fmea.xls";
        $judul = "mutu_fmea";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Tanggal1");
	xlsWriteLabel($tablehead, $kolomhead++, "Tanggal2");
	xlsWriteLabel($tablehead, $kolomhead++, "Id Indikator");
	xlsWriteLabel($tablehead, $kolomhead++, "Tahun Periode");
	xlsWriteLabel($tablehead, $kolomhead++, "Periode Lapor");
	xlsWriteLabel($tablehead, $kolomhead++, "Target");
	xlsWriteLabel($tablehead, $kolomhead++, "Analisa");

	foreach ($this->Mutu_fmea_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal1);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal2);
	    xlsWriteNumber($tablebody, $kolombody++, $data->id_indikator);
	    xlsWriteNumber($tablebody, $kolombody++, $data->tahun_periode);
	    xlsWriteNumber($tablebody, $kolombody++, $data->periode_lapor);
	    xlsWriteNumber($tablebody, $kolombody++, $data->target);
	    xlsWriteLabel($tablebody, $kolombody++, $data->analisa);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
    public function cetak($id) 
    {
        $row = $this->Mutu_fmea_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_fmea' => $row->id_fmea,
		'tanggal1' => $row->tanggal1,
		'tanggal2' => $row->tanggal2,
		'id_indikator' => $row->id_indikator,
		'tahun_periode' => $row->tahun_periode,
		'periode_lapor' => $row->periode_lapor,
		'target' => $row->target,
		'analisa' => $row->analisa,
	    );
        // $this->load->view('template/header',$data);
        $this->load->view('mutu_fmea/print_fmea',$data);
     
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_fmea'));
        }
    }

}

/* End of file Mutu_fmea.php */
/* Location: ./application/controllers/Mutu_fmea.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2023-01-09 00:47:08 */
/* http://harviacode.com */