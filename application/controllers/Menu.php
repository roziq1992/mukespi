<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller
{
    private $ROLE_ID_ADMIN = 1;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
        $this->load->model('Menu_model');

        if ((int) $this->session->userdata('role_id') !== $this->ROLE_ID_ADMIN) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda tidak punya akses ke halaman ini.</div>');
            redirect(site_url('portal'));
        }
    }

    /* ================= MENUS ================= */

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));

        $data = array(
            'menus' => $this->Menu_model->get_menus($q),
            'q'     => $q,
        );

        $this->load->view('template/header', $data);
        $this->load->view('menu/menu_list', $data);
        $this->load->view('template/footer');
    }

    public function create()
    {
        $data = array(
            'action' => site_url('menu/store'),
            'menu'   => null,
            'jenis'  => 'Tambah Menu',
        );

        $this->load->view('template/header', $data);
        $this->load->view('menu/menu_form', $data);
        $this->load->view('template/footer');
    }

    public function store()
    {
        $this->_menu_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
            return;
        }

        $data = array(
            'nama_menu' => htmlspecialchars($this->input->post('nama_menu', TRUE)),
            'url'       => trim($this->input->post('url', TRUE)),
            'icon'      => trim($this->input->post('icon', TRUE)),
            'sequence'  => (int) $this->input->post('sequence'),
            'is_active' => (int) $this->input->post('is_active'),
        );
        $this->Menu_model->insert_menu($data);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu berhasil ditambahkan.</div>');
        redirect(site_url('menu'));
    }

    public function edit($id)
    {
        $menu = $this->Menu_model->get_menu($id);
        if (!$menu) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Menu tidak ditemukan.</div>');
            redirect(site_url('menu'));
            return;
        }

        $data = array(
            'action' => site_url('menu/update'),
            'menu'   => $menu,
            'jenis'  => 'Edit Menu',
        );

        $this->load->view('template/header', $data);
        $this->load->view('menu/menu_form', $data);
        $this->load->view('template/footer');
    }

    public function update()
    {
        $id = (int) $this->input->post('id');
        $this->_menu_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id);
            return;
        }

        $data = array(
            'nama_menu' => htmlspecialchars($this->input->post('nama_menu', TRUE)),
            'url'       => trim($this->input->post('url', TRUE)),
            'icon'      => trim($this->input->post('icon', TRUE)),
            'sequence'  => (int) $this->input->post('sequence'),
            'is_active' => (int) $this->input->post('is_active'),
        );
        $this->Menu_model->update_menu($id, $data);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu berhasil diperbarui.</div>');
        redirect(site_url('menu'));
    }

    public function delete($id)
    {
        $this->Menu_model->delete_menu($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Menu berhasil dihapus.</div>');
        redirect(site_url('menu'));
    }

    private function _menu_rules()
    {
        $this->form_validation->set_rules('nama_menu', 'Nama Menu', 'trim|required');
        $this->form_validation->set_rules('url', 'URL', 'trim|required');
        $this->form_validation->set_rules('sequence', 'Urutan', 'integer');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    /* ================= ROLES ================= */

    public function roles()
    {
        $data = array(
            'roles' => $this->Menu_model->get_roles(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('menu/role_list', $data);
        $this->load->view('template/footer');
    }

    public function role_create()
    {
        $data = array(
            'action' => site_url('menu/role_store'),
            'role'   => null,
            'jenis'  => 'Tambah Role',
        );

        $this->load->view('template/header', $data);
        $this->load->view('menu/role_form', $data);
        $this->load->view('template/footer');
    }

    public function role_store()
    {
        $this->form_validation->set_rules('name', 'Nama Role', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $this->role_create();
            return;
        }

        if ($this->Menu_model->is_role_name_taken($this->input->post('name', TRUE))) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama role sudah ada.</div>');
            redirect(site_url('menu/roles'));
            return;
        }

        $this->Menu_model->insert_role(['name' => htmlspecialchars($this->input->post('name', TRUE))]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Role berhasil ditambahkan.</div>');
        redirect(site_url('menu/roles'));
    }

    public function role_edit($id)
    {
        $role = $this->Menu_model->get_role($id);
        if (!$role) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Role tidak ditemukan.</div>');
            redirect(site_url('menu/roles'));
            return;
        }

        $data = array(
            'action' => site_url('menu/role_update'),
            'role'   => $role,
            'jenis'  => 'Edit Role',
        );

        $this->load->view('template/header', $data);
        $this->load->view('menu/role_form', $data);
        $this->load->view('template/footer');
    }

    public function role_update()
    {
        $id = (int) $this->input->post('id');
        $this->form_validation->set_rules('name', 'Nama Role', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $this->role_edit($id);
            return;
        }

        if ($this->Menu_model->is_role_name_taken($this->input->post('name', TRUE), $id)) {
            // biarkan nama yang sama (untuk role itu sendiri) — dicek manual
            $existing = $this->Menu_model->get_role($id);
            if (strtolower($existing['name']) !== strtolower($this->input->post('name', TRUE))) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Nama role sudah ada.</div>');
                redirect(site_url('menu/roles'));
                return;
            }
        }

        $this->Menu_model->update_role($id, ['name' => htmlspecialchars($this->input->post('name', TRUE))]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Role berhasil diperbarui.</div>');
        redirect(site_url('menu/roles'));
    }

    public function role_delete($id)
    {
        $role = $this->Menu_model->get_role($id);
        if (!$role) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Role tidak ditemukan.</div>');
            redirect(site_url('menu/roles'));
            return;
        }

        if ($this->Menu_model->delete_role($id)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Role berhasil dihapus.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Role masih dipakai oleh user — tidak bisa dihapus.</div>');
        }
        redirect(site_url('menu/roles'));
    }

    /* ================= ROLE ACCESS ================= */

    public function role_access($role_id)
    {
        $role = $this->Menu_model->get_role($role_id);
        if (!$role) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Role tidak ditemukan.</div>');
            redirect(site_url('menu/roles'));
            return;
        }

        $data = array(
            'roles'          => $this->Menu_model->get_roles(),
            'role'           => $role,
            'active_role_id' => (int) $role_id,
            'menus'          => $this->Menu_model->get_menus(),
            'access_ids'     => $this->Menu_model->access_of_role($role_id),
        );

        $this->load->view('template/header', $data);
        $this->load->view('menu/role_access', $data);
        $this->load->view('template/footer');
    }

    public function role_access_save()
    {
        $role_id = (int) $this->input->post('role_id');
        $menu_ids = $this->input->post('menu_ids') ?: [];

        $role = $this->Menu_model->get_role($role_id);
        if (!$role) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Role tidak ditemukan.</div>');
            redirect(site_url('menu/roles'));
            return;
        }

        $this->Menu_model->sync_access($role_id, $menu_ids);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Hak akses role "' . html_escape($role['name']) . '" berhasil disimpan.</div>');
        redirect(site_url('menu/role_access/' . $role_id));
    }
}