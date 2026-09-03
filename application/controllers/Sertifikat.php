<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sertifikat extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sertifikat_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'sertifikat/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'sertifikat/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'sertifikat/index.html';
            $config['first_url'] = base_url() . 'sertifikat/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sertifikat_model->total_rows($q);
        $sertifikat = $this->Sertifikat_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sertifikat_data' => $sertifikat,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
       
           $this->load->view('template/header', $data);
        $this->load->view('sertifikat/sertifikat_list');
        $this->load->view('template/footer');
    }

    public function read($id) 
    {
        $row = $this->Sertifikat_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_sertifikat' => $row->id_sertifikat,
		'judul' => $row->judul,
		'ket' => $row->ket,
		'tanggal' => $row->tanggal,
	    );
          
              $this->load->view('template/header', $data);
        $this->load->view('sertifikat/sertifikat_read');
        $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Tambah',
            'action' => site_url('sertifikat/create_action'),
	    'id_sertifikat' => set_value('id_sertifikat'),
	    'judul' => set_value('judul'),
	    'ket' => set_value('ket'),
	    'tanggal' => set_value('tanggal'),
	);
      
           $this->load->view('template/header', $data);
        $this->load->view('sertifikat/sertifikat_form');
        $this->load->view('template/footer');
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'judul' => $this->input->post('judul',TRUE),
		'ket' => $this->input->post('ket',TRUE),
		'tanggal' => $this->input->post('tanggal',TRUE),
	    );

        }
        $config['upload_path']          = './sertifikat/';
    	$config['allowed_types']        = 'gif|jpg|png';
    	$config['max_size']             = 100;
    	$config['max_width']            = 1024;
    	$config['max_height']           = 768;
     
    	$this->load->library('upload', $config);
     
    	if ( ! $this->upload->do_upload('file1')){
    		$error = array('error' => $this->upload->display_errors());
    // 		$this->load->view('v_upload', $error);
    	}else{
    		$data = array('upload_data' => $this->upload->data());
    // 		$this->load->view('v_upload_sukses', $data);
    	}
    	if ( ! $this->upload->do_upload('file2')){
    		$error = array('error' => $this->upload->display_errors());
    // 		$this->load->view('v_upload', $error);
    	}else{
    		$data = array('upload_data' => $this->upload->data());
    // 		$this->load->view('v_upload_sukses', $data);
    	}
    // 	
            $this->Sertifikat_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sertifikat'));
    }
    
    public function update($id) 
    {
        $row = $this->Sertifikat_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sertifikat/update_action'),
		'id_sertifikat' => set_value('id_sertifikat', $row->id_sertifikat),
		'judul' => set_value('judul', $row->judul),
		'ket' => set_value('ket', $row->ket),
		'tanggal' => set_value('tanggal', $row->tanggal),
	    );
       
               $this->load->view('template/header', $data);
        $this->load->view('sertifikat/sertifikat_form');
        $this->load->view('template/footer');
            
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_sertifikat', TRUE));
        } else {
            $data = array(
		'judul' => $this->input->post('judul',TRUE),
		'ket' => $this->input->post('ket',TRUE),
		'tanggal' => $this->input->post('tanggal',TRUE),
	    );

            $this->Sertifikat_model->update($this->input->post('id_sertifikat', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sertifikat'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Sertifikat_model->get_by_id($id);

        if ($row) {
            $this->Sertifikat_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sertifikat'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('judul', 'judul', 'trim|required');
	$this->form_validation->set_rules('ket', 'ket', 'trim|required');
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');

	$this->form_validation->set_rules('id_sertifikat', 'id_sertifikat', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sertifikat.xls";
        $judul = "sertifikat";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Judul");
	xlsWriteLabel($tablehead, $kolomhead++, "Ket");
	xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");

	foreach ($this->Sertifikat_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->judul);
	    xlsWriteLabel($tablebody, $kolombody++, $data->ket);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Sertifikat.php */
/* Location: ./application/controllers/Sertifikat.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2023-01-21 09:32:43 */
/* http://harviacode.com */