<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $user_id = $this->session->userdata('id');
        $user = $this->db->get_where('users', ['id' => $user_id])->row_array();

        if (!$user) {
            $this->session->set_flashdata('message', 'Data user tidak ditemukan.');
            redirect('portal');
        }

        $data = [
            'user' => $user,
        ];

        $this->load->view('template/header', $data);
        $this->load->view('profile/profile_edit', $data);
        $this->load->view('template/footer');
    }

    public function update_action()
    {
        $user_id = $this->session->userdata('id');

        $this->form_validation->set_rules('name', 'Nama', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $data = [
                'name'  => htmlspecialchars($this->input->post('name', TRUE)),
                'email' => $this->input->post('email', TRUE),
            ];

            // cek email unik (kecuali user sendiri)
            $existing = $this->db->get_where('users', [
                'email' => $data['email'],
                'id !=' => $user_id
            ])->num_rows();

            if ($existing > 0) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email sudah digunakan oleh user lain.</div>');
                redirect('profile');
                return;
            }

            $this->db->where('id', $user_id);
            $this->db->update('users', $data);

            // sync session name
            $this->session->set_userdata('name', $data['name']);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Profil berhasil diperbarui.</div>');
            redirect('profile');
        }
    }

    public function upload_avatar()
    {
        $user_id = $this->session->userdata('id');
        $user = $this->db->get_where('users', ['id' => $user_id])->row_array();

        $upload_path = FCPATH . 'uploads/avatars/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;
        $config['max_width']     = 1024;
        $config['max_height']    = 1024;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('avatar')) {
            $error = strip_tags($this->upload->display_errors());
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal upload avatar: ' . $error . '</div>');
            redirect('profile');
            return;
        }

        // hapus avatar lama jika ada
        if (!empty($user['avatar']) && file_exists(FCPATH . $user['avatar'])) {
            @unlink(FCPATH . $user['avatar']);
        }

        $file = $this->upload->data();
        $avatar_path = 'uploads/avatars/' . $file['file_name'];

        $this->db->where('id', $user_id);
        $this->db->update('users', ['avatar' => $avatar_path]);

        $this->session->set_userdata('avatar', $avatar_path);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Avatar berhasil diperbarui.</div>');
        redirect('profile');
    }

    public function change_password()
    {
        $user_id = $this->session->userdata('id');

        $this->form_validation->set_rules('password_lama', 'Password Lama', 'trim|required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('password_konfirmasi', 'Konfirmasi Password', 'trim|required|matches[password_baru]');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $user = $this->db->get_where('users', ['id' => $user_id])->row_array();

            if (!password_verify($this->input->post('password_lama', TRUE), $user['password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Password lama salah.</div>');
                redirect('profile');
                return;
            }

            $new_hash = password_hash($this->input->post('password_baru', TRUE), PASSWORD_DEFAULT);
            $this->db->where('id', $user_id);
            $this->db->update('users', ['password' => $new_hash]);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password berhasil diubah.</div>');
            redirect('profile');
        }
    }
}