<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_list_indikator extends CI_Controller
{
    private $ROLE_ID_ADMIN = 1;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('User_list_indikator_model');

        // hanya admin yang boleh kelola halaman ini
        if ($this->session->userdata('role_id') != $this->ROLE_ID_ADMIN) {
            $this->session->set_flashdata('message', 'Anda tidak punya akses ke halaman ini');
            redirect(site_url('list_indikator'));
        }
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        $config['base_url'] = ($q <> '')
            ? base_url() . 'index.php/user_list_indikator/?q=' . urlencode($q)
            : base_url() . 'index.php/user_list_indikator/';
        $config['first_url'] = $config['base_url'];
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->User_list_indikator_model->count_all_users($q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $users = $this->User_list_indikator_model->get_all_users($q);
        $users_data = array();
        foreach ($users as $u) {
            $u->indikators = $this->User_list_indikator_model->get_indikators_by_user($u->id);
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
        $this->load->view('user_list_indikator/user_list_indikator_list');
        $this->load->view('template/footer');
    }

    public function manage($user_id)
    {
        $user = $this->User_list_indikator_model->get_user_by_id($user_id);
        if (!$user) {
            $this->session->set_flashdata('message', 'User tidak ditemukan');
            redirect(site_url('user_list_indikator'));
            return;
        }

        $data = array(
            'user' => $user,
            'indikators_grouped' => $this->User_list_indikator_model->get_indikators_grouped(),
            'selected_indikators' => $this->User_list_indikator_model->get_indikator_ids_by_user($user_id),
        );

        $this->load->view('template/header', $data);
        $this->load->view('user_list_indikator/user_list_indikator_form');
        $this->load->view('template/footer');
    }

    public function manage_action()
    {
        $user_id = $this->input->post('user_id', TRUE);
        $id_indikators = $this->input->post('id_indikators');

        $user = $this->User_list_indikator_model->get_user_by_id($user_id);
        if (!$user) {
            $this->session->set_flashdata('message', 'User tidak ditemukan');
            redirect(site_url('user_list_indikator'));
            return;
        }

        $this->User_list_indikator_model->sync_indikators($user_id, $id_indikators ?: array());
        $this->session->set_flashdata('message', 'Akses indikator untuk ' . $user->name . ' berhasil disimpan');
        redirect(site_url('user_list_indikator'));
    }

    // hapus 1 assignment indikator langsung dari list (tombol x pada badge)
    public function remove_indikator($user_id, $id_indikator)
    {
        $this->User_list_indikator_model->remove_indikator($user_id, $id_indikator);
        $this->session->set_flashdata('message', 'Akses indikator berhasil dihapus');
        redirect(site_url('user_list_indikator'));
    }
}
/* End of file User_list_indikator.php */
