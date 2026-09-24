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
        $this->load->model('Pegawai_model');
    }

    public function index()
    {
        $data['title'] = $this->title;

        // Ambil parameter ?tujuan=mukespi dari URL portal, validasi lewat whitelist
        $tujuan = $this->input->get('tujuan', true);
        if ($tujuan && array_key_exists($tujuan, $this->redirect_map)) {
            $this->session->set_userdata('login_redirect', $tujuan);
        }

        if ($this->session->userdata('email') || $this->session->userdata('is_pegawai')) {
            // kalau sudah login tapi klik kartu portal, langsung arahkan juga
            $tujuan_existing = $this->session->userdata('login_redirect');
            if ($tujuan_existing && isset($this->redirect_map[$tujuan_existing])) {
                $this->session->unset_userdata('login_redirect');
                redirect($this->redirect_map[$tujuan_existing]);
                return;
            }
            redirect('portal');
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
        $identifier = trim($this->input->post('email'));
        $password = $this->input->post('password');

        // Mapping kode singkat ke email asli
        $email_map = [
            'DIR01' => 'DIR01@dir.com',
        ];

        if (isset($email_map[$identifier])) {
            $identifier = $email_map[$identifier];
        }

        // Login Pegawai via NIK (username NIK, password dari data pegawai)
        if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $this->_login_pegawai($identifier, $password);
            return;
        }

        $user = $this->db->get_where('users', ['email' => $identifier])->row_array();

        if ($user) {
            if (isset($user['is_active']) && (int) $user['is_active'] !== 1) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger"
                    role="alert"> Akun Anda tidak aktif. Hubungi administrator. </div>');
                redirect('auth');
                return;
            }

            if (password_verify($password, $user['password'])) {
                $data = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'avatar' => isset($user['avatar']) ? $user['avatar'] : NULL
                ];
                $this->session->set_userdata($data);

                // Password masih "admin" -> wajib ganti password
                if (strtolower(trim($password)) === 'admin') {
                    $this->session->set_userdata(['must_change_password' => TRUE, 'pw_target' => 'users']);
                } else {
                    $this->session->unset_userdata(['must_change_password', 'pw_target']);
                }

                // Akun yang mewakili pegawai (email/NIK terhubung ke data pegawai) dengan data
                // wajib belum lengkap ditandai untuk dipaksa melengkapi. Admin & HRD tidak dipaksa.
                $role_login = (int) $user['role_id'];
                if (!in_array($role_login, array(1, 6), true)) {
                    $peg_id_now = current_pegawai_id();
                    if ($peg_id_now > 0) {
                        $pr = $this->Pegawai_model->get_by_id($peg_id_now);
                        $missingD = $pr ? pegawai_missing_wajib($pr) : array();
                        if (!empty($missingD)) {
                            $this->session->set_userdata('must_complete_data', $peg_id_now);
                        } else {
                            $this->session->unset_userdata('must_complete_data');
                        }
                    } else {
                        $this->session->unset_userdata('must_complete_data');
                    }
                } else {
                    $this->session->unset_userdata('must_complete_data');
                }

                // --- Cek apakah user datang dari portal dengan tujuan tertentu ---
                $tujuan = $this->session->userdata('login_redirect');
                if ($tujuan && isset($this->redirect_map[$tujuan])) {
                    $this->session->unset_userdata('login_redirect');
                    redirect($this->redirect_map[$tujuan]);
                    return;
                }

                // Semua login normal masuk ke portal layanan.
                redirect('portal');
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

    // Login karyawan via tabel pegawai: username = NIK, password dari data pegawai
    private function _login_pegawai($nik, $password)
    {
        $pegawai = $this->db->get_where('pegawai', ['nik' => $nik])->row_array();

        if (!$pegawai) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger"
                role="alert"> NIK tidak terdaftar sebagai pegawai. </div>');
            redirect('auth');
            return;
        }

        if ((isset($pegawai['status']) ? $pegawai['status'] : 'aktif') !== 'aktif') {
            $this->session->set_flashdata('message', '<div class="alert alert-danger"
                role="alert"> Akun pegawai tidak aktif. Hubungi HRD. </div>');
            redirect('auth');
            return;
        }

        if (empty($pegawai['password'])) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger"
                role="alert"> Password belum diatur untuk NIK ini. Hubungi HRD/Admin. </div>');
            redirect('auth');
            return;
        }

        if (!password_verify($password, $pegawai['password'])) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger"
                role="alert"> Password salah. </div>');
            redirect('auth');
            return;
        }

        // Cocokkan ke akun users (dibuat dari data pegawai) supaya role ikut terpakai.
        // Role diperoleh dari akun users; bila tidak ada akun, default role "pegawai" (7).
        $acc = NULL;
        if (!empty($pegawai['email'])) {
            $acc = $this->db->get_where('users', ['email' => $pegawai['email']])->row_array();
            if ($acc && (isset($acc['is_active']) && (int) $acc['is_active'] !== 1)) {
                $acc = NULL;
            }
        }

        $role_id = $acc ? (int) $acc['role_id'] : 7;

        $this->session->set_userdata([
            'is_pegawai' => TRUE,
            'id_pegawai' => (int) $pegawai['id_pegawai'],
            'nik'        => $pegawai['nik'],
            'name'       => $pegawai['nama'],
            'email'      => !empty($pegawai['email']) ? $pegawai['email'] : '',
            'avatar'     => NULL,
            'role_id'    => $role_id,
            'id'         => $acc ? (int) $acc['id'] : NULL,
        ]);

        $this->session->unset_userdata('login_redirect');

        // Password masih "admin" -> wajib ganti password
        if (strtolower(trim($password)) === 'admin') {
            $this->session->set_userdata(['must_change_password' => TRUE, 'pw_target' => 'pegawai']);
        } else {
            $this->session->unset_userdata(['must_change_password', 'pw_target']);
        }

        // Data wajib belum lengkap -> tandai agar dipaksa melengkapi dulu (pola seperti ganti password)
        $pegRow = $this->Pegawai_model->get_by_id((int) $pegawai['id_pegawai']);
        $missingData = $pegRow ? pegawai_missing_wajib($pegRow) : array();
        if (!empty($missingData)) {
            $this->session->set_userdata('must_complete_data', (int) $pegawai['id_pegawai']);
        } else {
            $this->session->unset_userdata('must_complete_data');
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success"
            role="alert"> Selamat datang, ' . html_escape($pegawai['nama']) . '! </div>');
        redirect('pegawai/detail/' . (int) $pegawai['id_pegawai']);
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