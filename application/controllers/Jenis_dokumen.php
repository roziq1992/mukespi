<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jenis_dokumen extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Jenis_dokumen_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'jenis_dokumen/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'jenis_dokumen/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'jenis_dokumen/index.html';
            $config['first_url'] = base_url() . 'jenis_dokumen/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Jenis_dokumen_model->total_rows($q);
        $jenis_dokumen = $this->Jenis_dokumen_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'jenis_dokumen_data' => $jenis_dokumen,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('jenis_dokumen/jenis_dokumen_list', $data);
    }

    public function read($id) 
    {
        $row = $this->Jenis_dokumen_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_jenis_dokumen' => $row->id_jenis_dokumen,
		'nm_jenis_dokumen' => $row->nm_jenis_dokumen,
		'is_active' => $row->is_active,
		'created_at' => $row->created_at,
		'updated_at' => $row->updated_at,
	    );
            $this->load->view('jenis_dokumen/jenis_dokumen_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jenis_dokumen'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('jenis_dokumen/create_action'),
	    'id_jenis_dokumen' => set_value('id_jenis_dokumen'),
	    'nm_jenis_dokumen' => set_value('nm_jenis_dokumen'),
	    'is_active' => set_value('is_active'),
	    'created_at' => set_value('created_at'),
	    'updated_at' => set_value('updated_at'),
	);
        $this->load->view('jenis_dokumen/jenis_dokumen_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'nm_jenis_dokumen' => $this->input->post('nm_jenis_dokumen',TRUE),
		'is_active' => $this->input->post('is_active',TRUE),
		'created_at' => $this->input->post('created_at',TRUE),
		'updated_at' => $this->input->post('updated_at',TRUE),
	    );

            $this->Jenis_dokumen_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('jenis_dokumen'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Jenis_dokumen_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('jenis_dokumen/update_action'),
		'id_jenis_dokumen' => set_value('id_jenis_dokumen', $row->id_jenis_dokumen),
		'nm_jenis_dokumen' => set_value('nm_jenis_dokumen', $row->nm_jenis_dokumen),
		'is_active' => set_value('is_active', $row->is_active),
		'created_at' => set_value('created_at', $row->created_at),
		'updated_at' => set_value('updated_at', $row->updated_at),
	    );
            $this->load->view('jenis_dokumen/jenis_dokumen_form', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jenis_dokumen'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_jenis_dokumen', TRUE));
        } else {
            $data = array(
		'nm_jenis_dokumen' => $this->input->post('nm_jenis_dokumen',TRUE),
		'is_active' => $this->input->post('is_active',TRUE),
		'created_at' => $this->input->post('created_at',TRUE),
		'updated_at' => $this->input->post('updated_at',TRUE),
	    );

            $this->Jenis_dokumen_model->update($this->input->post('id_jenis_dokumen', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('jenis_dokumen'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Jenis_dokumen_model->get_by_id($id);

        if ($row) {
            $this->Jenis_dokumen_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('jenis_dokumen'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('jenis_dokumen'));
        }
    }

    /**
     * ================== BARU ==================
     * Endpoint AJAX untuk modal popup "+ Tambah Jenis" di form Dokumen Unit.
     * Dipanggil via POST dari dokumen_unit_form.php (nm_jenis_dokumen +
     * id_unit_doc_ref). Mengembalikan JSON supaya opsi baru bisa langsung
     * disisipkan & dipilih di select2 Jenis Dokumen tanpa reload halaman.
     *
     * BARU: sekarang juga menerima & menyimpan id_unit_doc_ref, supaya
     * jenis dokumen yang dibuat langsung terikat ke Unit Dokumen yang
     * dipilih user di modal.
     */
    public function add_ajax()
    {
        header('Content-Type: application/json');

        // Hanya menerima request AJAX/POST
        if (!$this->input->method() === 'post') {
            echo json_encode(array('status' => FALSE, 'message' => 'Metode tidak diizinkan'));
            return;
        }

        $this->form_validation->set_rules('nm_jenis_dokumen', 'nama jenis dokumen', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('id_unit_doc_ref', 'unit dokumen', 'trim|required|numeric');
        // hapus delimiter default supaya pesan error bersih untuk ditampilkan di modal
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array(
                'status' => FALSE,
                'message' => strip_tags(validation_errors()),
            ));
            return;
        }

        $nama            = $this->input->post('nm_jenis_dokumen', TRUE);
        $id_unit_doc_ref = intval($this->input->post('id_unit_doc_ref', TRUE));

        // cegah duplikat nama jenis dokumen dalam Unit Dokumen yang sama
        if (method_exists($this->Jenis_dokumen_model, 'get_by_nama_and_unit')) {
            $existing = $this->Jenis_dokumen_model->get_by_nama_and_unit($nama, $id_unit_doc_ref);
            if ($existing) {
                echo json_encode(array(
                    'status' => TRUE,
                    'message' => 'Jenis dokumen sudah ada di Unit Dokumen ini, dipilih otomatis',
                    'id_jenis_dokumen' => $existing->id_jenis_dokumen,
                    'nm_jenis_dokumen' => $existing->nm_jenis_dokumen,
                ));
                return;
            }
        } elseif (method_exists($this->Jenis_dokumen_model, 'get_by_nama')) {
            // fallback lama: cek nama saja (tanpa memperhatikan unit)
            $existing = $this->Jenis_dokumen_model->get_by_nama($nama);
            if ($existing) {
                echo json_encode(array(
                    'status' => TRUE,
                    'message' => 'Jenis dokumen sudah ada, dipilih otomatis',
                    'id_jenis_dokumen' => $existing->id_jenis_dokumen,
                    'nm_jenis_dokumen' => $existing->nm_jenis_dokumen,
                ));
                return;
            }
        }

        $data = array(
            'nm_jenis_dokumen' => $nama,
            'id_unit_doc_ref'  => $id_unit_doc_ref,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->Jenis_dokumen_model->insert($data);
        $id_baru = $this->db->insert_id();

        echo json_encode(array(
            'status' => TRUE,
            'message' => 'Jenis dokumen berhasil ditambahkan',
            'id_jenis_dokumen' => $id_baru,
            'nm_jenis_dokumen' => $data['nm_jenis_dokumen'],
            'id_unit_doc_ref'  => $data['id_unit_doc_ref'],
        ));
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('nm_jenis_dokumen', 'nm jenis dokumen', 'trim|required');
	$this->form_validation->set_rules('is_active', 'is active', 'trim|required');
	$this->form_validation->set_rules('created_at', 'created at', 'trim|required');
	$this->form_validation->set_rules('updated_at', 'updated at', 'trim|required');

	$this->form_validation->set_rules('id_jenis_dokumen', 'id_jenis_dokumen', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Jenis_dokumen.php */
/* Location: ./application/controllers/Jenis_dokumen.php */