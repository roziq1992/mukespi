<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller
{
    private $ROLE_ID_ADMIN = 1;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('User_model');

        if ((int) $this->session->userdata('role_id') !== $this->ROLE_ID_ADMIN) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda tidak punya akses ke halaman ini.</div>');
            redirect(site_url('portal'));
        }
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        $config['base_url']   = ($q <> '') ? site_url("users/?q=" . urlencode($q)) : site_url('users/');
        $config['first_url']  = $config['base_url'];
        $config['per_page']   = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->User_model->count_all($q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'users_data' => $this->User_model->get_all($q, $config['per_page'], $start),
            'q'          => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start'      => $start,
            'me'         => (int) $this->session->userdata('id'),
        );

        $this->load->view('template/header', $data);
        $this->load->view('users/user_list', $data);
        $this->load->view('template/footer');
    }

    public function create()
    {
        $data = array(
            'action' => site_url('users/store'),
            'user'   => null,
            'roles'  => $this->User_model->roles(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('users/user_form', $data);
        $this->load->view('template/footer');
    }

    public function create_action()
    {
        $password = $this->input->post('password', TRUE);
        $this->_form_rules(false, $password);

        if ($this->form_validation->run() == FALSE) {
            $this->create();
            return;
        }

        $data = array(
            'name'      => htmlspecialchars($this->input->post('name', TRUE)),
            'email'     => $this->input->post('email', TRUE),
            'role_id'   => (int) $this->input->post('role_id'),
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'is_active' => (int) $this->input->post('is_active'),
        );
        $this->User_model->insert_user($data);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User berhasil ditambahkan.</div>');
        redirect(site_url('users'));
    }

    public function update($id)
    {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">User tidak ditemukan.</div>');
            redirect(site_url('users'));
            return;
        }

        $data = array(
            'action' => site_url('users/update_action'),
            'user'   => $user,
            'roles'  => $this->User_model->roles(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('users/user_form', $data);
        $this->load->view('template/footer');
    }

    public function update_action()
    {
        $id = (int) $this->input->post('id');
        $password = $this->input->post('password', TRUE);
        $this->_form_rules(true, $password);

        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
            return;
        }

        $data = array(
            'name'      => htmlspecialchars($this->input->post('name', TRUE)),
            'email'     => $this->input->post('email', TRUE),
            'role_id'   => (int) $this->input->post('role_id'),
            'is_active' => (int) $this->input->post('is_active'),
        );

        // password hanya diubah bila diisi
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->User_model->update_user($id, $data);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User berhasil diperbarui.</div>');
        redirect(site_url('users'));
    }

    public function toggle_active($id)
    {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">User tidak ditemukan.</div>');
            redirect(site_url('users'));
            return;
        }

        // jangan biarkan admin menonaktifkan dirinya sendiri
        if ((int) $id === (int) $this->session->userdata('id')) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Anda tidak bisa menonaktifkan akun sendiri.</div>');
            redirect(site_url('users'));
            return;
        }

        $new_state = ((int) $user['is_active'] === 1) ? 0 : 1;
        $this->User_model->update_user($id, ['is_active' => $new_state]);

        $label = $new_state === 1 ? 'diaktifkan' : 'dinonaktifkan';
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User "' . html_escape($user['name']) . '" berhasil ' . $label . '.</div>');
        redirect(site_url('users'));
    }

    public function delete($id)
    {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">User tidak ditemukan.</div>');
            redirect(site_url('users'));
            return;
        }

        if ((int) $id === (int) $this->session->userdata('id')) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Anda tidak bisa menghapus akun sendiri.</div>');
            redirect(site_url('users'));
            return;
        }

        $ref = $this->User_model->is_referenced($id);
        if ($ref > 0) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">User ini dipakai di ' . $ref . ' data (surat/unit). Tidak bisa dihapus — nonaktifkan saja.</div>');
            redirect(site_url('users'));
            return;
        }

        $this->User_model->delete_user($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User "' . html_escape($user['name']) . '" berhasil dihapus.</div>');
        redirect(site_url('users'));
    }

    private function _form_rules($is_edit, $password)
    {
        $this->form_validation->set_rules('name', 'Nama', 'trim|required');
        $this->form_validation->set_rules('role_id', 'Role', 'required|integer');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        $email_rule = 'trim|required|valid_email'
            . ($is_edit ? '|callback__email_unik' : '|is_unique[users.email]');
        $this->form_validation->set_rules('email', 'Email', $email_rule);

        // password wajib saat create, opsional saat edit
        if (!$is_edit && empty($password)) {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        } elseif (!empty($password)) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[5]');
        }
    }

    public function _email_unik($email)
    {
        $id = (int) $this->input->post('id');
        if ($this->User_model->is_email_taken($email, $id)) {
            $this->form_validation->set_message('_email_unik', 'Email sudah digunakan user lain.');
            return FALSE;
        }
        return TRUE;
    }
}