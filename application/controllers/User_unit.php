<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_unit extends CI_Controller
{
    private $ROLE_ID_ADMIN = 1;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('User_unit_model');
        $this->load->model('Dokumen_unit_model'); // pakai method unit()

        // hanya admin yang boleh kelola halaman ini
        if ($this->session->userdata('role_id') != $this->ROLE_ID_ADMIN) {
            $this->session->set_flashdata('message', 'Anda tidak punya akses ke halaman ini');
            redirect(site_url('dokumen_unit'));
        }
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        $config['base_url'] = ($q <> '')
            ? base_url() . 'index.php/user_unit/?q=' . urlencode($q)
            : base_url() . 'index.php/user_unit/';
        $config['first_url'] = $config['base_url'];
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->User_unit_model->count_all_users($q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $users = $this->User_unit_model->get_all_users($q);
        // ambil unit tiap user sekaligus (untuk badge)
        $users_data = array();
        foreach ($users as $u) {
            $u->units = $this->User_unit_model->get_units_by_user($u->id);
            $users_data[] = $u;
        }

        $data = array(
            'users_data' => $users_data,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $this->load->view('template/header', $data);
        $this->load->view('user_unit/user_unit_list');
        $this->load->view('template/footer');
    }

    public function manage($user_id)
    {
        $user = $this->User_unit_model->get_user_by_id($user_id);
        if (!$user) {
            $this->session->set_flashdata('message', 'User tidak ditemukan');
            redirect(site_url('user_unit'));
            return;
        }

        $data = array(
            'user' => $user,
            'unit_list' => $this->Dokumen_unit_model->unit(),
            'selected_units' => $this->User_unit_model->get_unit_ids_by_user($user_id),
        );

        $this->load->view('template/header', $data);
        $this->load->view('user_unit/user_unit_form');
        $this->load->view('template/footer');
    }

    public function manage_action()
    {
        $user_id = $this->input->post('user_id', TRUE);
        $unit_ids = $this->input->post('unit_ids');

        $user = $this->User_unit_model->get_user_by_id($user_id);
        if (!$user) {
            $this->session->set_flashdata('message', 'User tidak ditemukan');
            redirect(site_url('user_unit'));
            return;
        }

        $this->User_unit_model->sync_units($user_id, $unit_ids ?: array());
        $this->session->set_flashdata('message', 'Akses unit untuk ' . $user->name . ' berhasil disimpan');
        redirect(site_url('user_unit'));
    }

    // hapus 1 assignment unit langsung dari list (tombol x pada badge)
    public function remove_unit($user_id, $id_unit)
    {
        $this->User_unit_model->remove_unit($user_id, $id_unit);
        $this->session->set_flashdata('message', 'Akses unit berhasil dihapus');
        redirect(site_url('user_unit'));
    }
}
/* End of file User_unit.php */