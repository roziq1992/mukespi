<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pegawai extends CI_Controller
{
    // id pegawai yang diabaikan saat cek keunikan email (0 = tanpa kecuali, untuk insert)
    private $_email_ignore_id = 0;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Pegawai_model');
        $this->load->library('form_validation');
    }

    private function is_admin()
    {
        return in_array((int) $this->session->userdata('role_id'), array(1, 6), true);
    }

    private function is_pegawai_login()
    {
        return $this->session->userdata('is_pegawai') === TRUE || (int) $this->session->userdata('role_id') === 7;
    }

    private function edit_mode()
    {
        $s = $this->db->get_where('settings', array('nama' => 'pegawai_edit_mode'))->row_array();
        return $s && $s['nilai'] === 'aktif';
    }

    public function index()
    {
        // Karyawan yang login via NIK / role pegawai langsung diarahkan ke datanya sendiri
        if ($this->is_pegawai_login()) {
            redirect('pegawai/detail/' . current_pegawai_id());
        }

        if (!$this->is_admin()) {
            redirect('auth/blocked');
        }

        $q = urldecode($this->input->get('q', TRUE));
        $status = $this->input->get('status', TRUE);
        $start = intval($this->input->get('start'));

        $config['base_url'] = base_url() . 'index.php/pegawai/';
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;

        // gabungkan q & status ke base_url supaya pagination tidak kehilangan filter
        $params = array();
        if ($q <> '') $params['q'] = $q;
        if ($status && $status !== 'semua') $params['status'] = $status;
        if (!empty($params)) {
            $config['base_url'] .= '?' . http_build_query($params);
            $config['first_url'] = $config['base_url'];
        }

        $config['total_rows'] = $this->Pegawai_model->total_rows($q, $status);
        $pegawai_data = $this->Pegawai_model->get_limit_data($config['per_page'], $start, $q, $status);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'pegawai_data' => $pegawai_data,
            'q' => $q,
            'status_filter' => $status,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'count_aktif' => $this->Pegawai_model->count_status('aktif'),
            'count_nonaktif' => $this->Pegawai_model->count_status('nonaktif'),
            'is_admin' => $this->is_admin(),
            'edit_mode' => $this->edit_mode(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('pegawai/pegawai_list', $data);
        $this->load->view('template/footer');
    }

    public function create()
    {
        if (!$this->is_admin()) {
            redirect('auth/blocked');
        }

        $data = array(
            'button' => 'Simpan',
            'action' => site_url('pegawai/create_action'),
            'id_pegawai' => set_value('id_pegawai'),
            'nik' => set_value('nik'),
            'nip' => set_value('nip'),
            'nama' => set_value('nama'),
            'jenis_kelamin' => set_value('jenis_kelamin', 'Laki-laki'),
            'tempat_lahir' => set_value('tempat_lahir'),
            'tanggal_lahir' => set_value('tanggal_lahir'),
            'alamat' => set_value('alamat'),
            'no_hp' => set_value('no_hp'),
            'email' => set_value('email'),
            'jabatan' => set_value('jabatan'),
            'unit_kerja' => set_value('unit_kerja'),
            'tanggal_masuk' => set_value('tanggal_masuk'),
            'password' => set_value('password'),
            'units' => $this->Pegawai_model->units(),
            'is_pegawai_view' => FALSE,
        );

        $this->load->view('template/header', $data);
        $this->load->view('pegawai/pegawai_form', $data);
        $this->load->view('template/footer');
    }

    public function create_action()
    {
        if (!$this->is_admin()) {
            redirect('auth/blocked');
        }

        $this->_email_ignore_id = 0;
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $password = $this->input->post('password', TRUE);
            $email = $this->input->post('email', TRUE);
            $data = array(
                'nik' => $this->input->post('nik', TRUE),
                'nip' => $this->input->post('nip', TRUE),
                'nama' => $this->input->post('nama', TRUE),
                'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
                'tempat_lahir' => $this->input->post('tempat_lahir', TRUE),
                'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
                'alamat' => $this->input->post('alamat', TRUE),
                'no_hp' => $this->input->post('no_hp', TRUE),
                'email' => ($email !== '') ? $email : NULL,
                'jabatan' => $this->input->post('jabatan', TRUE),
                'unit_kerja' => $this->input->post('unit_kerja', TRUE),
                'id_unit' => $this->Pegawai_model->unit_id_by_name($this->input->post('unit_kerja', TRUE)),
                'tanggal_masuk' => $this->input->post('tanggal_masuk', TRUE),
                'password' => ($password !== '') ? password_hash($password, PASSWORD_DEFAULT) : NULL,
                'status' => 'aktif',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $id = $this->Pegawai_model->insert($data);

            // catat riwayat awal: penambahan pegawai
            $this->Pegawai_model->insert_mutasi(array(
                'id_pegawai' => $id,
                'jenis' => 'masuk',
                'unit_tujuan' => $data['unit_kerja'],
                'jabatan_tujuan' => $data['jabatan'],
                'tanggal_mutasi' => $data['tanggal_masuk'] ?: date('Y-m-d'),
                'keterangan' => 'Pendataan pegawai baru.',
                'user_id' => $this->session->userdata('id'),
                'created_at' => $data['created_at'],
            ));

            $this->session->set_flashdata('message', 'Data pegawai berhasil ditambahkan.');
            redirect(site_url('pegawai'));
        }
    }

    public function update($id)
    {
        $row = $this->Pegawai_model->get_by_id($id);

        if ($row) {
            // Admin/HRD boleh edit semua; pegawai hanya datanya sendiri & saat mode edit aktif
            $is_pegawai_view = FALSE;
            if (!$this->is_admin()) {
                if (!$this->is_pegawai_login() || current_pegawai_id() !== (int) $id) {
                    $this->session->set_flashdata('message', 'Anda tidak berhak mengubah data ini.');
                    redirect('portal');
                }
                if (!$this->edit_mode()) {
                    $this->session->set_flashdata('message', 'Mode edit data pegawai sedang nonaktif. Hubungi HRD/Admin.');
                    redirect('pegawai/detail/' . $id);
                }
                $is_pegawai_view = TRUE;
            }

            $data = array(
                'button' => 'Perbarui',
                'action' => site_url('pegawai/update_action'),
                'id_pegawai' => set_value('id_pegawai', $row->id_pegawai),
                'nik' => set_value('nik', $row->nik),
                'nip' => set_value('nip', $row->nip),
                'nama' => set_value('nama', $row->nama),
                'jenis_kelamin' => set_value('jenis_kelamin', $row->jenis_kelamin),
                'tempat_lahir' => set_value('tempat_lahir', $row->tempat_lahir),
                'tanggal_lahir' => set_value('tanggal_lahir', $row->tanggal_lahir),
                'alamat' => set_value('alamat', $row->alamat),
                'no_hp' => set_value('no_hp', $row->no_hp),
                'email' => set_value('email', $row->email),
                'jabatan' => set_value('jabatan', $row->jabatan),
                'unit_kerja' => set_value('unit_kerja', $row->unit_kerja),
                'tanggal_masuk' => set_value('tanggal_masuk', $row->tanggal_masuk),
                'password' => '',
                'units' => $this->Pegawai_model->units(),
                'is_pegawai_view' => $is_pegawai_view,
            );

            $this->load->view('template/header', $data);
            $this->load->view('pegawai/pegawai_form', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Data pegawai tidak ditemukan.');
            redirect(site_url('pegawai'));
        }
    }

    public function update_action()
    {
        $id = $this->input->post('id_pegawai', TRUE);
        $is_pegawai_view = FALSE;

        if (!$this->is_admin()) {
            if (!$this->is_pegawai_login() || current_pegawai_id() !== (int) $id || !$this->edit_mode()) {
                $this->session->set_flashdata('message', 'Anda tidak berhak mengubah data ini.');
                redirect('portal');
            }
            $is_pegawai_view = TRUE;
        }

        $this->_email_ignore_id = (int) $id;

        if ($is_pegawai_view) {
            $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
            $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_email_available');
            $this->form_validation->set_rules('password', 'Password', 'trim|min_length[5]');
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
        } else {
            $this->_rules();
        }

        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $email = $this->input->post('email', TRUE);
            $data = array(
                'nama' => $this->input->post('nama', TRUE),
                'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
                'tempat_lahir' => $this->input->post('tempat_lahir', TRUE),
                'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
                'alamat' => $this->input->post('alamat', TRUE),
                'no_hp' => $this->input->post('no_hp', TRUE),
                'email' => ($email !== '') ? $email : NULL,
            );

            // hanya admin/HRD yang boleh mengubah identitas kepegawaian
            if (!$is_pegawai_view) {
                $data['nik'] = $this->input->post('nik', TRUE);
                $data['nip'] = $this->input->post('nip', TRUE);
                $data['jabatan'] = $this->input->post('jabatan', TRUE);
                $data['unit_kerja'] = $this->input->post('unit_kerja', TRUE);
                $data['id_unit'] = $this->Pegawai_model->unit_id_by_name($this->input->post('unit_kerja', TRUE));
                $data['tanggal_masuk'] = $this->input->post('tanggal_masuk', TRUE);
            }

            $password = $this->input->post('password', TRUE);
            if ($password !== '') {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $data['updated_at'] = date('Y-m-d H:i:s');

            $this->Pegawai_model->update($id, $data);
            $this->session->set_flashdata('message', 'Data pegawai berhasil diperbarui.');
            redirect($is_pegawai_view ? site_url('pegawai/detail/' . $id) : site_url('pegawai'));
        }
    }

    // detail + riwayat tracking
    public function detail($id)
    {
        $row = $this->Pegawai_model->get_by_id($id);

        if ($row) {
            $is_pegawai_view = FALSE;
            if (!$this->is_admin()) {
                if (!$this->is_pegawai_login() || current_pegawai_id() !== (int) $id) {
                    $this->session->set_flashdata('message', 'Anda tidak berhak melihat data ini.');
                    redirect('portal');
                }
                $is_pegawai_view = TRUE;
            }

            $data = array(
                'pegawai' => $row,
                'history' => $this->Pegawai_model->get_history($id),
                'units' => $this->Pegawai_model->units(),
                'is_pegawai_view' => $is_pegawai_view,
                'can_edit' => $this->is_admin() || ($is_pegawai_view && $this->edit_mode()),
                'edit_mode' => $this->edit_mode(),
            );
            $this->load->view('template/header', $data);
            $this->load->view('pegawai/pegawai_detail', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Data pegawai tidak ditemukan.');
            redirect(site_url('pegawai'));
        }
    }

    // proses mutasi pegawai (POST dari modal)
    public function mutasi_action()
    {
        if (!$this->is_admin()) {
            redirect('auth/blocked');
        }

        $id = $this->input->post('id_pegawai', TRUE);
        $row = $this->Pegawai_model->get_by_id($id);

        if (!$row) {
            $this->session->set_flashdata('message', 'Data pegawai tidak ditemukan.');
            redirect(site_url('pegawai'));
        }

        $this->form_validation->set_rules('unit_tujuan', 'Unit Tujuan', 'trim|required');
        $this->form_validation->set_rules('jabatan_tujuan', 'Jabatan Tujuan', 'trim|required');
        $this->form_validation->set_rules('tanggal_mutasi', 'Tanggal Mutasi', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', 'Form mutasi belum lengkap. Periksa kembali isian Anda.');
            redirect(site_url('pegawai/detail/' . $id));
            return;
        }

        $unit_tujuan = $this->input->post('unit_tujuan', TRUE);
        $jabatan_tujuan = $this->input->post('jabatan_tujuan', TRUE);
        $tanggal_mutasi = $this->input->post('tanggal_mutasi', TRUE);

        // simpan riwayat
        $this->Pegawai_model->insert_mutasi(array(
            'id_pegawai' => $id,
            'jenis' => 'mutasi',
            'unit_asal' => $row->unit_kerja,
            'unit_tujuan' => $unit_tujuan,
            'jabatan_asal' => $row->jabatan,
            'jabatan_tujuan' => $jabatan_tujuan,
            'tanggal_mutasi' => $tanggal_mutasi,
            'keterangan' => $this->input->post('keterangan', TRUE),
            'user_id' => $this->session->userdata('id'),
            'created_at' => date('Y-m-d H:i:s'),
        ));

        // perbarui data pegawai
        $this->Pegawai_model->update($id, array(
            'unit_kerja' => $unit_tujuan,
            'id_unit' => $this->Pegawai_model->unit_id_by_name($unit_tujuan),
            'jabatan' => $jabatan_tujuan,
            'updated_at' => date('Y-m-d H:i:s'),
        ));

        $this->session->set_flashdata('message', 'Mutasi pegawai berhasil dicatat.');
        redirect(site_url('pegawai/detail/' . $id));
    }

    // nonaktifkan pegawai (POST dari modal)
    public function nonaktif_action()
    {
        if (!$this->is_admin()) {
            redirect('auth/blocked');
        }

        $id = $this->input->post('id_pegawai', TRUE);
        $row = $this->Pegawai_model->get_by_id($id);

        if (!$row) {
            $this->session->set_flashdata('message', 'Data pegawai tidak ditemukan.');
            redirect(site_url('pegawai'));
        }

        if ($row->status === 'nonaktif') {
            $this->session->set_flashdata('message', 'Pegawai sudah berstatus nonaktif.');
            redirect(site_url('pegawai/detail/' . $id));
            return;
        }

        $this->Pegawai_model->update($id, array(
            'status' => 'nonaktif',
            'updated_at' => date('Y-m-d H:i:s'),
        ));

        $this->Pegawai_model->insert_mutasi(array(
            'id_pegawai' => $id,
            'jenis' => 'nonaktif',
            'unit_asal' => $row->unit_kerja,
            'jabatan_asal' => $row->jabatan,
            'tanggal_mutasi' => date('Y-m-d'),
            'keterangan' => $this->input->post('keterangan', TRUE) ?: 'Pegawai dinonaktifkan.',
            'user_id' => $this->session->userdata('id'),
            'created_at' => date('Y-m-d H:i:s'),
        ));

        $this->session->set_flashdata('message', 'Pegawai berhasil dinonaktifkan.');
        redirect(site_url('pegawai/detail/' . $id));
    }

    // aktifkan kembali pegawai nonaktif (POST dari modal)
    public function aktifkan_action()
    {
        if (!$this->is_admin()) {
            redirect('auth/blocked');
        }

        $id = $this->input->post('id_pegawai', TRUE);
        $row = $this->Pegawai_model->get_by_id($id);

        if (!$row) {
            $this->session->set_flashdata('message', 'Data pegawai tidak ditemukan.');
            redirect(site_url('pegawai'));
        }

        if ($row->status === 'aktif') {
            $this->session->set_flashdata('message', 'Pegawai sudah berstatus aktif.');
            redirect(site_url('pegawai/detail/' . $id));
            return;
        }

        $this->Pegawai_model->update($id, array(
            'status' => 'aktif',
            'updated_at' => date('Y-m-d H:i:s'),
        ));

        $this->Pegawai_model->insert_mutasi(array(
            'id_pegawai' => $id,
            'jenis' => 'aktif',
            'unit_tujuan' => $row->unit_kerja,
            'jabatan_tujuan' => $row->jabatan,
            'tanggal_mutasi' => date('Y-m-d'),
            'keterangan' => $this->input->post('keterangan', TRUE) ?: 'Pegawai diaktifkan kembali.',
            'user_id' => $this->session->userdata('id'),
            'created_at' => date('Y-m-d H:i:s'),
        ));

        $this->session->set_flashdata('message', 'Pegawai berhasil diaktifkan kembali.');
        redirect(site_url('pegawai/detail/' . $id));
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required');
        $this->form_validation->set_rules('unit_kerja', 'Unit Kerja', 'trim|required');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_email_available');
        $this->form_validation->set_rules('password', 'Password', 'trim|min_length[5]');

        $this->form_validation->set_rules('id_pegawai', 'id_pegawai', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    // callback: pastikan email pegawai belum dipakai pegawai lain (unik)
    public function email_available($email)
    {
        $email = trim((string) $email);
        if ($email === '') {
            return TRUE;
        }
        if ($this->Pegawai_model->email_exists($email, $this->_email_ignore_id)) {
            $this->form_validation->set_message('email_available', 'Email <strong>%s</strong> sudah digunakan pegawai lain.');
            return FALSE;
        }
        return TRUE;
    }

    // toggle mode edit dari data pegawai (admin/HRD)
    public function toggle_edit_mode()
    {
        if (!$this->is_admin()) {
            redirect('auth/blocked');
        }

        $s = $this->db->get_where('settings', array('nama' => 'pegawai_edit_mode'))->row_array();
        $baru = ($s && $s['nilai'] === 'aktif') ? 'nonaktif' : 'aktif';

        if ($s) {
            $this->db->where('nama', 'pegawai_edit_mode');
            $this->db->update('settings', array('nilai' => $baru));
        } else {
            $this->db->insert('settings', array('nama' => 'pegawai_edit_mode', 'nilai' => $baru));
        }

        $label = ($baru === 'aktif') ? 'AKTIF' : 'NONAKTIF';
        $this->session->set_flashdata('message', 'Mode edit data pegawai sekarang: ' . $label . '.');
        redirect(site_url('pegawai'));
    }

    // ================= EXPORT / IMPORT EXCEL =================

    // Judul kolom standar (urut) untuk export & template
    private function _pegawai_headers()
    {
        return array(
            'No', 'NIK', 'NIP', 'Nama', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
            'Alamat', 'No HP', 'Email', 'Jabatan', 'Unit Kerja', 'Tanggal Masuk', 'Status',
        );
    }

    // Petakan judul kolom -> field database (null = diabaikan)
    private function _import_header_map($label)
    {
        $key = strtolower(trim((string) $label));
        $key = str_replace(array('.', '_'), ' ', $key);
        $key = preg_replace('/\s+/', ' ', $key);

        $map = array(
            'no' => NULL,
            'nik' => 'nik',
            'nip' => 'nip',
            'nama' => 'nama',
            'jenis kelamin' => 'jenis_kelamin',
            'tempat lahir' => 'tempat_lahir',
            'tanggal lahir' => 'tanggal_lahir',
            'alamat' => 'alamat',
            'no hp' => 'no_hp',
            'nohp' => 'no_hp',
            'hp' => 'no_hp',
            'email' => 'email',
            'jabatan' => 'jabatan',
            'unit kerja' => 'unit_kerja',
            'unit' => 'unit_kerja',
            'tanggal masuk' => 'tanggal_masuk',
            'status' => 'status',
        );
        return isset($map[$key]) ? $map[$key] : NULL;
    }

    private function _normalize_gender($value)
    {
        $v = strtolower(trim((string) $value));
        if ($v === '') {
            return 'Laki-laki';
        }
        if (strpos($v, 'perempuan') !== FALSE || strpos($v, 'wanita') !== FALSE || $v === 'p') {
            return 'Perempuan';
        }
        return 'Laki-laki';
    }

    private function _normalize_status($value)
    {
        $v = strtolower(trim((string) $value));
        if ($v === '') {
            return NULL;
        }
        if (strpos($v, 'non') !== FALSE || strpos($v, 'tidak') !== FALSE || $v === '0') {
            return 'nonaktif';
        }
        return 'aktif';
    }

    private function _require_admin()
    {
        if (!$this->is_admin()) {
            redirect('auth/blocked');
            exit;
        }
    }

    public function export_excel()
    {
        $this->_require_admin();
        $this->load->library('excel_io');

        $rows = array();
        $no = 1;
        foreach ($this->Pegawai_model->get_all() as $p) {
            $rows[] = array(
                $no++,
                (string) $p->nik,
                (string) $p->nip,
                (string) $p->nama,
                (string) $p->jenis_kelamin,
                (string) $p->tempat_lahir,
                (string) $p->tanggal_lahir,
                (string) $p->alamat,
                (string) $p->no_hp,
                (string) $p->email,
                (string) $p->jabatan,
                (string) $p->unit_kerja,
                (string) $p->tanggal_masuk,
                (string) $p->status,
            );
        }

        $this->excel_io->export('data_pegawai_' . date('Ymd_His') . '.xlsx', $this->_pegawai_headers(), $rows, 'Data Pegawai');
    }

    public function template_excel()
    {
        $this->_require_admin();
        $this->load->library('excel_io');

        $contoh = array(
            1, '0031234', '19870102001', 'Nama Pegawai Contoh', 'Laki-laki', 'Surabaya', '1987-05-01',
            'Jl. Contoh No. 1', '081234567890', 'pegawai@email.com', 'Staf', 'IT', '2019-12-17', 'aktif',
        );

        $this->excel_io->export('template_import_pegawai.xlsx', $this->_pegawai_headers(), array($contoh), 'Template');
    }

    public function import_excel()
    {
        $this->_require_admin();

        if (strtoupper($this->input->server('REQUEST_METHOD')) !== 'POST' || empty($_FILES['file']['name'])) {
            $this->session->set_flashdata('message', '<div class="mt-3 alert alert-danger">Pilih berkas Excel (.xlsx) atau CSV terlebih dahulu.</div>');
            redirect(site_url('pegawai'));
            return;
        }

        $file = $_FILES['file'];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!is_uploaded_file($file['tmp_name']) || (int) $file['error'] !== UPLOAD_ERR_OK) {
            $this->session->set_flashdata('message', '<div class="mt-3 alert alert-danger">Gagal mengunggah berkas. Coba lagi.</div>');
            redirect(site_url('pegawai'));
            return;
        }
        if (!in_array($ext, array('xlsx', 'csv'), TRUE)) {
            $this->session->set_flashdata('message', '<div class="mt-3 alert alert-danger">Format berkas harus .xlsx atau .csv.</div>');
            redirect(site_url('pegawai'));
            return;
        }

        $this->load->library('excel_io');
        $rows = $this->excel_io->read_file($file['tmp_name'], $ext);
        if ($rows === FALSE || count($rows) < 2) {
            $this->session->set_flashdata('message', '<div class="mt-3 alert alert-warning">Berkas tidak terbaca atau tidak berisi data.</div>');
            redirect(site_url('pegawai'));
            return;
        }

        $header = array_shift($rows);
        $map = array();
        foreach ($header as $i => $h) {
            $field = $this->_import_header_map($h);
            if ($field !== NULL && !isset($map[$field])) {
                $map[$field] = $i;
            }
        }
        if (!isset($map['nama'])) {
            $this->session->set_flashdata('message', '<div class="mt-3 alert alert-danger">Kolom <strong>Nama</strong> tidak ditemukan pada baris pertama. Gunakan template import yang disediakan.</div>');
            redirect(site_url('pegawai'));
            return;
        }

        $inserted = 0;
        $updated  = 0;
        $skipped  = 0;
        $errors   = array();
        $now      = date('Y-m-d H:i:s');
        $user_id  = $this->session->userdata('id');

        foreach ($rows as $idx => $row) {
            $line = $idx + 2;
            $val = function ($field) use ($map, $row) {
                return (isset($map[$field]) && isset($row[$map[$field]])) ? trim((string) $row[$map[$field]]) : '';
            };

            // lewati baris kosong
            $isEmpty = TRUE;
            foreach ($map as $field => $col) {
                if (isset($row[$col]) && trim((string) $row[$col]) !== '') { $isEmpty = FALSE; break; }
            }
            if ($isEmpty) {
                continue;
            }

            $nama = $val('nama');
            if ($nama === '') {
                $skipped++;
                $errors[] = 'Baris ' . $line . ': Nama kosong.';
                continue;
            }

            $jenis   = $this->_normalize_gender($val('jenis_kelamin'));
            $status  = $this->_normalize_status($val('status'));
            $tgl_lahir = $val('tanggal_lahir') !== '' ? $this->excel_io->parse_date($val('tanggal_lahir')) : NULL;
            $tgl_masuk = $val('tanggal_masuk') !== '' ? $this->excel_io->parse_date($val('tanggal_masuk')) : NULL;
            $unit_kerja = $val('unit_kerja');

            $data = array(
                'nik'           => $val('nik') ?: NULL,
                'nip'           => $val('nip') ?: NULL,
                'nama'          => $nama,
                'jenis_kelamin' => $jenis,
                'tempat_lahir'  => $val('tempat_lahir') ?: NULL,
                'tanggal_lahir' => $tgl_lahir,
                'alamat'        => $val('alamat') ?: NULL,
                'no_hp'         => $val('no_hp') ?: NULL,
                'email'         => $val('email') ?: NULL,
                'jabatan'       => $val('jabatan') ?: NULL,
                'unit_kerja'    => $unit_kerja ?: NULL,
                'id_unit'       => $this->Pegawai_model->unit_id_by_name($unit_kerja),
                'tanggal_masuk' => $tgl_masuk,
            );

            $existing = $this->Pegawai_model->find_identity($data['nik'], $data['nip'], $data['email']);

            // jaga keunikan email: jangan pakai email yang sudah dimiliki pegawai lain
            if ($data['email'] !== NULL) {
                $owner = $this->Pegawai_model->get_by_email($data['email']);
                $owner_id  = $owner ? (int) $owner['id_pegawai'] : 0;
                $target_id = $existing ? (int) $existing->id_pegawai : 0;
                if ($owner_id && $owner_id !== $target_id) {
                    $errors[] = 'Baris ' . $line . ': email ' . $data['email'] . ' sudah dipakai pegawai lain, email diabaikan.';
                    $data['email'] = NULL;
                }
            }

            if ($existing) {
                // hanya timpa field yang diisi agar data lama tidak hilang
                $update = array();
                foreach ($data as $k => $v) {
                    if ($v !== NULL && $v !== '') {
                        $update[$k] = $v;
                    }
                }
                if ($status !== NULL) {
                    $update['status'] = $status;
                }
                $update['updated_at'] = $now;

                $unit_berubah = isset($update['unit_kerja']) && $update['unit_kerja'] !== $existing->unit_kerja;
                $this->Pegawai_model->update($existing->id_pegawai, $update);
                $updated++;

                if ($unit_berubah) {
                    $this->Pegawai_model->insert_mutasi(array(
                        'id_pegawai'      => $existing->id_pegawai,
                        'jenis'           => 'mutasi',
                        'unit_asal'       => $existing->unit_kerja,
                        'unit_tujuan'     => $update['unit_kerja'],
                        'jabatan_asal'    => $existing->jabatan,
                        'jabatan_tujuan'  => isset($update['jabatan']) ? $update['jabatan'] : $existing->jabatan,
                        'tanggal_mutasi'  => date('Y-m-d'),
                        'keterangan'      => 'Diperbarui lewat import Excel.',
                        'user_id'         => $user_id,
                        'created_at'      => $now,
                    ));
                }
            } else {
                $data['status']     = ($status !== NULL) ? $status : 'aktif';
                $data['created_at'] = $now;
                $data['updated_at'] = $now;
                $id = $this->Pegawai_model->insert($data);

                $this->Pegawai_model->insert_mutasi(array(
                    'id_pegawai'     => $id,
                    'jenis'          => 'masuk',
                    'unit_tujuan'    => $data['unit_kerja'],
                    'jabatan_tujuan' => $data['jabatan'],
                    'tanggal_mutasi' => $data['tanggal_masuk'] ?: date('Y-m-d'),
                    'keterangan'     => 'Pendataan pegawai lewat import Excel.',
                    'user_id'        => $user_id,
                    'created_at'     => $now,
                ));
                $inserted++;
            }
        }

        $msg = '<div class="mt-3 alert alert-success"><strong>Import selesai.</strong> '
            . 'Ditambahkan: <strong>' . $inserted . '</strong>, '
            . 'Diperbarui: <strong>' . $updated . '</strong>, '
            . 'Dilewati: <strong>' . $skipped . '</strong>.</div>';
        if (!empty($errors)) {
            $msg .= '<div class="mt-2 alert alert-warning"><ul class="mb-0 pl-3">';
            foreach (array_slice($errors, 0, 20) as $e) {
                $msg .= '<li>' . html_escape($e) . '</li>';
            }
            $msg .= '</ul></div>';
        }
        $this->session->set_flashdata('message', $msg);
        redirect(site_url('pegawai'));
    }
}