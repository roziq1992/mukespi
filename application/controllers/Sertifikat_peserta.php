<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sertifikat_peserta extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sertifikat_peserta_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        $idsertifikat = urldecode($this->input->get('id_sertifikat', TRUE));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'index.php/sertifikat_peserta?q=' . urlencode($q).'&id_sertifikat='.urlencode($idsertifikat);
            $config['first_url'] = base_url() . 'index.php/sertifikat_peserta??q=' . urlencode($q).'&id_sertifikat='.urlencode($idsertifikat);
        } else {
            $config['base_url'] = base_url() . 'index.php/sertifikat_peserta?'.'&id_sertifikat='.urlencode($idsertifikat);
            $config['first_url'] = base_url() . 'index.php/sertifikat_peserta?'.'&id_sertifikat='.urlencode($idsertifikat);
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sertifikat_peserta_model->total_rows($q,$idsertifikat);
        $sertifikat_peserta = $this->Sertifikat_peserta_model->get_limit_data($config['per_page'], $start, $q,$idsertifikat);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sertifikat_peserta_data' => $sertifikat_peserta,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
         $this->load->view('template/header', $data);
        $this->load->view('sertifikat_peserta/sertifikat_peserta_list');
        $this->load->view('template/footer');
    }

    public function read($id) 
    {
        $row = $this->Sertifikat_peserta_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_peserta' => $row->id_peserta,
		'id_sertifikat' => $row->id_sertifikat,
		'nm_peserta' => $row->nm_peserta,
		'no_peserta' => $row->no_peserta,
	    );
            $this->load->view('sertifikat_peserta/sertifikat_peserta_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat_peserta'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sertifikat_peserta/create_action'),
	    'id_peserta' => set_value('id_peserta'),
	    'id_sertifikat' => set_value('id_sertifikat'),
	    'nm_peserta' => set_value('nm_peserta'),
	    'no_peserta' => set_value('no_peserta'),
	);
           $this->load->view('template/header', $data);
        $this->load->view('sertifikat_peserta/sertifikat_peserta_form');
        $this->load->view('template/footer');
        
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'id_sertifikat' => $this->input->post('id_sertifikat',TRUE),
		'nm_peserta' => $this->input->post('nm_peserta',TRUE),
		'no_peserta' => $this->input->post('no_peserta',TRUE),
	    );

            $this->Sertifikat_peserta_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sertifikat_peserta?id_sertifikat='.$this->input->post('id_sertifikat')));
        }
    }
    
    public function update() 
    {
        
        $id=$this->input->get('id');
        $row = $this->Sertifikat_peserta_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sertifikat_peserta/update_action'),
		'id_peserta' => set_value('id_peserta', $row->id_peserta),
		'id_sertifikat' => set_value('id_sertifikat', $row->id_sertifikat),
		'nm_peserta' => set_value('nm_peserta', $row->nm_peserta),
		'no_peserta' => set_value('no_peserta', $row->no_peserta),
	    );
           
              $this->load->view('template/header', $data);
        $this->load->view('sertifikat_peserta/sertifikat_peserta_form');
        $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat_peserta?id_sertifikat='.$this->input->get('id_sertifikat')));
            
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_peserta', TRUE));
        } else {
            $data = array(
		'id_sertifikat' => $this->input->post('id_sertifikat',TRUE),
		'nm_peserta' => $this->input->post('nm_peserta',TRUE),
		'no_peserta' => $this->input->post('no_peserta',TRUE),
	    );

            $this->Sertifikat_peserta_model->update($this->input->post('id_peserta', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sertifikat_peserta?id_sertifikat='.$this->input->post('id_sertifikat')));
        }
    }
    
    public function delete() 
    {
        $id=$this->input->get('id');
        $row = $this->Sertifikat_peserta_model->get_by_id($id);

        if ($row) {
            $this->Sertifikat_peserta_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sertifikat_peserta?id_sertifikat='.$this->input->get('id_sertifikat')));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat_peserta?id_sertifikat='.$this->input->get('id_sertifikat')));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('id_sertifikat', 'id sertifikat', 'trim|required');
	$this->form_validation->set_rules('nm_peserta', 'nm peserta', 'trim|required');
	$this->form_validation->set_rules('no_peserta', 'no peserta', 'trim|required');

	$this->form_validation->set_rules('id_peserta', 'id_peserta', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $id=$this->input->get('id');
        $this->load->helper('exportexcel');
        $namaFile = "sertifikat_peserta.xls";
        $judul = "sertifikat_peserta";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Id Sertifikat");
	xlsWriteLabel($tablehead, $kolomhead++, "Nm Peserta");
	xlsWriteLabel($tablehead, $kolomhead++, "No Seri");

	foreach ($this->Sertifikat_peserta_model->get_allfilter($id) as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->id_sertifikat);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nm_peserta);
	    xlsWriteLabel($tablebody, $kolombody++, $data->no_peserta);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
    public function cek_sertifikat() 
    {

            $this->load->view('sertifikat_peserta/cek_sertifikat');
      
    }
     public function certificate() 
    {

            $this->load->view('sertifikat_peserta/certificate');
      
    }

}

/* End of file Sertifikat_peserta.php */
/* Location: ./application/controllers/Sertifikat_peserta.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2023-01-21 09:34:49 */
/* http://harviacode.com */