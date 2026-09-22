<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class List_indikator extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
         is_logged_in();
        $this->load->model('List_indikator_model');
        $this->load->model('User_list_indikator_model');
        $this->load->library('form_validation');
    }

    /**
     * Indikator yang boleh diakses user (Akses Indikator User).
     * NULL = tanpa batasan untuk admin (role 1) & direktur (role 4).
     */
    private function _allowed_indikators()
    {
        $role_id = (int) $this->session->userdata('role_id');
        if ($role_id === 1 || $role_id === 4) {
            return NULL;
        }
        return $this->User_list_indikator_model->get_indikator_ids_by_user((int) $this->session->userdata('id'));
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $status = $this->input->get('status', TRUE);
        if ($status === NULL || $status === '') {
            // Default: tampilkan indikator aktif saja
            $status = 'aktif';
        }
        $start = intval($this->input->get('start'));
        
        // gabungkan q & status ke base_url supaya pagination tidak kehilangan filter
        $params = array();
        if ($q <> '') $params['q'] = $q;
        if ($status && $status !== 'semua') $params['status'] = $status;
        if (!empty($params)) {
            $config['base_url'] = base_url() . 'index.php/list_indikator/?' . http_build_query($params);
            $config['first_url'] = $config['base_url'];
        } else {
            $config['base_url'] = base_url() . 'index.php/list_indikator/';
            $config['first_url'] = base_url() . 'index.php/list_indikator/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $allowed_units = $this->_allowed_indikators();
        $config['total_rows'] = $this->List_indikator_model->total_rows($q, $allowed_units, $status);
        $list_indikator = $this->List_indikator_model->get_limit_data($config['per_page'], $start, $q, $allowed_units, $status);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'list_indikator_data' => $list_indikator,
            'q' => $q,
            'status_filter' => $status,
            'count_aktif' => $this->List_indikator_model->count_status('aktif'),
            'count_nonaktif' => $this->List_indikator_model->count_status('nonaktif'),
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        
         $this->load->view('template/header',$data);
        $this->load->view('list_indikator/list_indikator_list');
        $this->load->view('template/footer');
    }

    public function read($id) 
    {
        $row = $this->List_indikator_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_indikator' => $row->id_indikator,
		'kelompok' => $row->kelompok,
		'jenis' => $row->jenis,
		'judul' => $row->judul,
		'id_unit' => $row->id_unit,
		'nm_unit' => $row->nm_unit,
	    );
            $this->load->view('list_indikator/list_indikator_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('list_indikator'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('list_indikator/create_action'),
	    'id_indikator' => set_value('id_indikator'),
	    'kelompok' => set_value('kelompok'),
	    'jenis' => set_value('jenis'),
	    'judul' => set_value('judul'),
	    'target' => set_value('target'),
	    'num' => set_value('num'),
	    'denum' => set_value('denum'),
	    'ket_judul' => set_value('ketjudul'),
	    'id_unit' => set_value('id_unit'),
	    'userid' => set_value('user', $this->session->userdata('id')),
	    'users' => $this->List_indikator_model->users(),
	    'units' => $this->List_indikator_model->units()
	);
	
        $this->load->view('template/header',$data);
        $this->load->view('list_indikator/list_indikator_form');
        $this->load->view('template/footer');
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'kelompok' => $this->input->post('kelompok',TRUE),
		'jenis' => $this->input->post('jenis',TRUE),
		'judul' => $this->input->post('judul',TRUE),
		'target' => $this->input->post('target',TRUE),
		'ket_num' => $this->input->post('num',TRUE),
		'ket_denum' => $this->input->post('denum',TRUE),
		'ket_judul' => $this->input->post('ketjudul',TRUE),
		'id_unit' => (int) $this->input->post('id_unit',TRUE),
		'userid' => (int) $this->input->post('user',TRUE) ?: (int) $this->session->userdata('id'),
	    );

            $this->List_indikator_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('list_indikator'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->List_indikator_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('list_indikator/update_action'),
		'id_indikator' => set_value('id_indikator', $row->id_indikator),
		'kelompok' => set_value('kelompok', $row->kelompok),
		'jenis' => set_value('jenis', $row->jenis),
		'judul' => set_value('judul', $row->judul),
		'target' => set_value('target', $row->target),
		'num' => set_value('num', $row->ket_num),
		'denum' => set_value('denum', $row->ket_denum),
		'ket_judul' => set_value('ketjudul', $row->ket_judul),
		'id_unit' => set_value('id_unit', $row->id_unit),
		'userid' => set_value('user', $row->userid),
		'users' => $this->List_indikator_model->users(),
		'units' => $this->List_indikator_model->units()
	    );
        $this->load->view('template/header',$data);
        $this->load->view('list_indikator/list_indikator_form');
        $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('list_indikator'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_indikator', TRUE));
        } else {
            $data = array(
		'kelompok' => $this->input->post('kelompok',TRUE),
		'jenis' => $this->input->post('jenis',TRUE),
		'judul' => $this->input->post('judul',TRUE),
		'target' => $this->input->post('target',TRUE),
		'ket_num' => $this->input->post('num',TRUE),
		'ket_denum' => $this->input->post('denum',TRUE),
		'ket_judul' => $this->input->post('ketjudul',TRUE),
		'id_unit' => (int) $this->input->post('id_unit',TRUE),
	    );

            $this->List_indikator_model->update($this->input->post('id_indikator', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('list_indikator'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->List_indikator_model->get_by_id($id);

        if ($row) {
            $this->List_indikator_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('list_indikator'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('list_indikator'));
        }
    }

    // aktifkan / nonaktifkan indikator (toggle status)
    public function toggle_status($id)
    {
        $row = $this->List_indikator_model->get_by_id($id);

        if ($row) {
            $baru = ($row->status === 'nonaktif') ? 'aktif' : 'nonaktif';
            $this->List_indikator_model->update($id, array('status' => $baru));
            $label = ($baru === 'aktif') ? 'diaktifkan' : 'dinonaktifkan';
            $this->session->set_flashdata('message', 'Indikator berhasil ' . $label . '.');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
        }
        redirect(site_url('list_indikator'));
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('kelompok', 'kelompok', 'trim|required');
	$this->form_validation->set_rules('jenis', 'jenis', 'trim|required');
	$this->form_validation->set_rules('judul', 'judul', 'trim|required');
	$this->form_validation->set_rules('id_unit', 'Unit', 'trim|required|integer');

	$this->form_validation->set_rules('id_indikator', 'id_indikator', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $status = $this->input->get('status', TRUE);
        if ($status === NULL || $status === '') {
            $status = 'aktif';
        }

        $this->load->helper('exportexcel');
        $namaFile = "list_indikator.xls";
        $judul = "list_indikator";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Kelompok");
	xlsWriteLabel($tablehead, $kolomhead++, "Jenis");
	xlsWriteLabel($tablehead, $kolomhead++, "Unit");
	xlsWriteLabel($tablehead, $kolomhead++, "Judul");
	xlsWriteLabel($tablehead, $kolomhead++, "Target");
	xlsWriteLabel($tablehead, $kolomhead++, "Status");

	foreach ($this->List_indikator_model->get_all($this->_allowed_indikators(), $status) as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kelompok);
	    xlsWriteLabel($tablebody, $kolombody++, $data->jenis);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nm_unit);
	    xlsWriteLabel($tablebody, $kolombody++, $data->judul);
	    xlsWriteNumber($tablebody, $kolombody++, $data->target);
	    $status_label = (!isset($data->status) || $data->status === 'aktif') ? 'Aktif' : 'Nonaktif';
	    xlsWriteLabel($tablebody, $kolombody++, $status_label);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
    public function vapassword() 
    {
      $data = array(
                'button' => 'Update',
                'action' => site_url('list_indikator/edit_passsword')
                );

        $this->load->view('template/header');
        $this->load->view('template/va_password',$data);
        $this->load->view('template/footer');
      
    }
    	public function edit_passsword()
    {
            $data = [
                'password' => password_hash($this->input->post("password"), PASSWORD_DEFAULT),
            ];
             $id=$this->session->userdata('id');
             $this->db->where('id', $id);
            $this->db->update('users', $data);
             $this->session->set_flashdata('message', '<div class="alert alert-success" 
            role="alert"> Password Kamu Berhasil Di Update</div>');
            redirect(site_url('List_indikator/vapassword'));
        
    }

}

/* End of file List_indikator.php */
/* Location: ./application/controllers/List_indikator.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2023-01-07 01:29:01 */
/* http://harviacode.com */