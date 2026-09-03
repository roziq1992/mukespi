<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_inventaris extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Data_inventaris_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'data_inventaris/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'data_inventaris/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'data_inventaris/index.html';
            $config['first_url'] = base_url() . 'data_inventaris/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Data_inventaris_model->total_rows($q);
        $data_inventaris = $this->Data_inventaris_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'data_inventaris_data' => $data_inventaris,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('data_inventaris/data_inventaris_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Data_inventaris_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_inven' => $row->id_inven,
		'kode_inven' => $row->kode_inven,
		'nm_barang' => $row->nm_barang,
		'merek' => $row->merek,
		'tipe' => $row->tipe,
		'sn' => $row->sn,
		'jenis' => $row->jenis,
		'kondisi' => $row->kondisi,
		'id_ruang' => $row->id_ruang,
		'harga' => $row->harga,
		'stts' => $row->stts,
	    );
            $this->load->view('data_inventaris/data_inventaris_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('data_inventaris'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('data_inventaris/create_action'),
	    'id_inven' => set_value('id_inven'),
	    'kode_inven' => set_value('kode_inven'),
	    'nm_barang' => set_value('nm_barang'),
	    'merek' => set_value('merek'),
	    'tipe' => set_value('tipe'),
	    'sn' => set_value('sn'),
	    'jenis' => set_value('jenis'),
	    'kondisi' => set_value('kondisi'),
	    'id_ruang' => set_value('id_ruang'),
	    'harga' => set_value('harga'),
	    'stts' => set_value('stts'),
	);
        $this->load->view('data_inventaris/data_inventaris_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'kode_inven' => $this->input->post('kode_inven',TRUE),
		'nm_barang' => $this->input->post('nm_barang',TRUE),
		'merek' => $this->input->post('merek',TRUE),
		'tipe' => $this->input->post('tipe',TRUE),
		'sn' => $this->input->post('sn',TRUE),
		'jenis' => $this->input->post('jenis',TRUE),
		'kondisi' => $this->input->post('kondisi',TRUE),
		'id_ruang' => $this->input->post('id_ruang',TRUE),
		'harga' => $this->input->post('harga',TRUE),
		'stts' => $this->input->post('stts',TRUE),
	    );

            $this->Data_inventaris_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('data_inventaris'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Data_inventaris_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('data_inventaris/update_action'),
		'id_inven' => set_value('id_inven', $row->id_inven),
		'kode_inven' => set_value('kode_inven', $row->kode_inven),
		'nm_barang' => set_value('nm_barang', $row->nm_barang),
		'merek' => set_value('merek', $row->merek),
		'tipe' => set_value('tipe', $row->tipe),
		'sn' => set_value('sn', $row->sn),
		'jenis' => set_value('jenis', $row->jenis),
		'kondisi' => set_value('kondisi', $row->kondisi),
		'id_ruang' => set_value('id_ruang', $row->id_ruang),
		'harga' => set_value('harga', $row->harga),
		'stts' => set_value('stts', $row->stts),
	    );
            $this->load->view('data_inventaris/data_inventaris_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('data_inventaris'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_inven', TRUE));
        } else {
            $data = array(
		'kode_inven' => $this->input->post('kode_inven',TRUE),
		'nm_barang' => $this->input->post('nm_barang',TRUE),
		'merek' => $this->input->post('merek',TRUE),
		'tipe' => $this->input->post('tipe',TRUE),
		'sn' => $this->input->post('sn',TRUE),
		'jenis' => $this->input->post('jenis',TRUE),
		'kondisi' => $this->input->post('kondisi',TRUE),
		'id_ruang' => $this->input->post('id_ruang',TRUE),
		'harga' => $this->input->post('harga',TRUE),
		'stts' => $this->input->post('stts',TRUE),
	    );

            $this->Data_inventaris_model->update($this->input->post('id_inven', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('data_inventaris'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Data_inventaris_model->get_by_id($id);

        if ($row) {
            $this->Data_inventaris_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('data_inventaris'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('data_inventaris'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('kode_inven', 'kode inven', 'trim|required');
	$this->form_validation->set_rules('nm_barang', 'nm barang', 'trim|required');
	$this->form_validation->set_rules('merek', 'merek', 'trim|required');
	$this->form_validation->set_rules('tipe', 'tipe', 'trim|required');
	$this->form_validation->set_rules('sn', 'sn', 'trim|required');
	$this->form_validation->set_rules('jenis', 'jenis', 'trim|required');
	$this->form_validation->set_rules('kondisi', 'kondisi', 'trim|required');
	$this->form_validation->set_rules('id_ruang', 'id ruang', 'trim|required');
	$this->form_validation->set_rules('harga', 'harga', 'trim|required|numeric');
	$this->form_validation->set_rules('stts', 'stts', 'trim|required');

	$this->form_validation->set_rules('id_inven', 'id_inven', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Data_inventaris.php */
/* Location: ./application/controllers/Data_inventaris.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2026-08-06 13:21:49 */
/* http://harviacode.com */