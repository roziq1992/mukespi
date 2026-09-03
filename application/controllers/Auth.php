<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    protected $title = "Your App";

    // Whitelist: key di URL portal => controller tujuan setelah login
    protected $redirect_map = [
        'mukespi'    => 'list_indikator',
        'sidokta'    => 'dokumen_unit',
        'sipardi'    => 'penilaian_ep',
        'simonika'   => 'monitoring_pj',
        'sertifikat' => 'sertifikat',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = $this->title;

        // Ambil parameter ?tujuan=mukespi dari URL portal, validasi lewat whitelist
        $tujuan = $this->input->get('tujuan', true);
        if ($tujuan && array_key_exists($tujuan, $this->redirect_map)) {
            $this->session->set_userdata('login_redirect', $tujuan);
        }

        if ($this->session->userdata('email')) {
            // kalau sudah login tapi klik kartu portal, langsung arahkan juga
            $tujuan_existing = $this->session->userdata('login_redirect');
            if ($tujuan_existing && isset($this->redirect_map[$tujuan_existing])) {
                $this->session->unset_userdata('login_redirect');
                redirect($this->redirect_map[$tujuan_existing]);
                return;
            }
            redirect('dashboard');
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->load->view('auth/login', $data);
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        // Mapping kode singkat ke email asli
        $email_map = [
            'DIR01' => 'DIR01@dir.com',
        ];

        if (isset($email_map[$email])) {
            $email = $email_map[$email];
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" 
                role="alert"> The Email field must contain a valid email address. </div>');
            redirect('auth');
            return;
        }

        $user = $this->db->get_where('users', ['email' => $email])->row_array();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $data = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id']
                ];
                $this->session->set_userdata($data);

                // --- Cek apakah user datang dari portal dengan tujuan tertentu ---
                $tujuan = $this->session->userdata('login_redirect');
                if ($tujuan && isset($this->redirect_map[$tujuan])) {
                    $this->session->unset_userdata('login_redirect');
                    redirect($this->redirect_map[$tujuan]);
                    return;
                }

                // --- Kalau tidak ada tujuan khusus, pakai logika lama ---
                if ($email == 'admin@mail.com') {
                    redirect('dashboard');
                } else {
                    redirect('list_indikator');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" 
                    role="alert"> Wrong password </div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" 
                role="alert"> Email is not registered </div>');
            redirect('auth');
        }
    }

    public function registerx()
    {
        if ($this->session->userdata('email')) {
            redirect('dashboard');
        }
        $data['title'] = $this->title;
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', [
            'is_unique' => 'This email has already registered!'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[5]|matches[password2]', [
            'matches' => 'Password tidak sesuai!',
            'min_length' => 'Password terlalu pendek!'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('auth/register', $data);
        } else {
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'password' => password_hash($this->input->post("password"), PASSWORD_DEFAULT),
                'role_id' => 2

            ];
            $this->db->insert('users', $data);

            $this->session->set_flashdata('message', '<div class="alert alert-success" 
            role="alert"> Congratulation! your account has been created. Please Login</div>');
            redirect('auth');
        }
    }

    public function edit_passsword()
    {
        $data = [
            'password' => password_hash($this->input->post("password"), PASSWORD_DEFAULT),
        ];

        $this->db->where('id', $id);
        $this->db->update('users', $data);
        redirect(site_url('list_indikator'));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('message', '<div class="alert alert-success" 
        role="alert"> You have been logout!</div>');
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');
    }
}