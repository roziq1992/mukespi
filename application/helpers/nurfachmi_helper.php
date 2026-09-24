<?php


function is_logged_in($role = false, $skip_force = false)
{
    $ci = get_instance();
    if (!$ci->session->userdata('email') && !$ci->session->userdata('is_pegawai')) {
        redirect('auth');
    }

    if ($role) {
        $role_id = $ci->session->userdata('role_id');
        if ($role_id != $role) {
            redirect('auth/blocked');
        }
    }

    // Password masih "admin" -> paksa ganti password (kecuali di halaman ganti password itu sendiri)
    if (!$skip_force && $ci->session->userdata('must_change_password')) {
        redirect('pegawai/ganti_password');
    }

    // Data wajib pegawai belum lengkap -> paksa lengkapi lewat "Edit Data Saya".
    // Mengikuti pola yang sama seperti "harus ganti password": penanda (flag)
    // `must_complete_data` dipasang saat login dan dilepas setelah data lengkap,
    // sehingga sistem selalu mengarahkan pegawai untuk melengkapi datanya
    // sebelum bisa memakai aplikasi lain.
    // Berlaku untuk akun yang mewakili seorang pegawai: login via NIK (is_pegawai),
    // role pegawai (7), atau akun apa pun yang email/NIK-nya terhubung ke data pegawai
    // (mis. akun role 2/4/5 milik pegawai). Admin (1) & HRD (6) tidak dipaksa melengkapi.
    $role_id = (int) $ci->session->userdata('role_id');
    $pid = current_pegawai_id();
    $is_pegawai_acct = $ci->session->userdata('is_pegawai') === TRUE || $role_id === 7 || $pid > 0;
    if (!in_array($role_id, array(1, 6), true) && $is_pegawai_acct && $pid > 0) { // admin & HRD tidak dipaksa melengkapi
        $ci->load->model('Pegawai_model');
            $row = $ci->Pegawai_model->get_by_id($pid);
            if ($row) {
                $missing = pegawai_missing_wajib($row);
                if (empty($missing)) {
                    // data sudah lengkap -> lepas penanda (seperti setelah ganti password "admin")
                    $ci->session->unset_userdata('must_complete_data');
                } else {
                    // pasang penanda agar semua permintaan berikutnya diarahkan ke form kelengkapan
                    $ci->session->set_userdata('must_complete_data', $pid);
                    if (!_pegawai_gate_allowed($ci, $pid)) {
                        // jangan ganggu permintaan AJAX (endpoint JS)
                        if (!$ci->input->is_ajax_request()) {
                            $daftar = implode(', ', $missing);
                            $ci->session->set_flashdata(
                                'message',
                                '<div class="alert alert-warning"><strong>Data wajib Anda belum lengkap.</strong><br>'
                                    . 'Sebelum dapat menggunakan sistem, silakan lengkapi data berikut pada formulir "Edit Data Saya": '
                                    . '<strong>' . html_escape($daftar) . '</strong>.</div>'
                            );
                            redirect('pegawai/update/' . $pid);
                        }
                    }
                }
            } else {
                // record pegawai tidak ditemukan -> jangan memaksa
                $ci->session->unset_userdata('must_complete_data');
            }
    } else {
        $ci->session->unset_userdata('must_complete_data');
    }
}

/**
 * Daftar field wajib data pegawai (dipakai form input & pengecekan kelengkapan saat login).
 */
function pegawai_wajib_fields()
{
    return array(
        'nik'                    => 'NIK',
        'nip'                    => 'NIP',
        'nama'                   => 'Nama Lengkap',
        'jenis_kelamin'          => 'Jenis Kelamin',
        'tempat_lahir'           => 'Tempat Lahir',
        'tanggal_lahir'          => 'Tanggal Lahir',
        'alamat'                 => 'Alamat',
        'no_hp'                  => 'No. HP',
        'email'                  => 'Email',
        'nama_keluarga'          => 'Nama Suami/Istri/Orang Tua',
        'no_hp_keluarga'         => 'No. HP Suami/Istri/Orang Tua',
        'nama_anak'              => 'Nama Anak',
        'kualifikasi_pendidikan' => 'Kualifikasi Pendidikan',
        'jabatan'                => 'Jabatan',
        'unit_kerja'             => 'Unit Kerja',
        'status_kepegawaian'     => 'Status Kepegawaian',
        'tanggal_masuk'          => 'Tanggal Masuk',
    );
}

/**
 * Kembalikan daftar label field wajib yang masih kosong.
 * Baris NULL/empty -> seluruh field dianggap belum terisi.
 */
function pegawai_missing_wajib($row)
{
    $missing = array();
    if (empty($row)) {
        return array_values(pegawai_wajib_fields());
    }
    foreach (pegawai_wajib_fields() as $field => $label) {
        $val = isset($row->$field) ? $row->$field : '';
        if (trim((string) $val) === '' || strtolower(trim((string) $val)) === 'null') {
            $missing[] = $label;
        }
    }
    return $missing;
}

/**
 * Halaman yang tetap boleh diakses ketika data wajib pegawai belum lengkap
 * (mencegah redirect berulang di halaman edit itu sendiri).
 */
function _pegawai_gate_allowed($ci, $pid)
{
    $class  = $ci->router->fetch_class();
    $method = $ci->router->fetch_method();

    // keluar akun & ganti password tetap diizinkan
    if ($class === 'auth' && $method === 'logout') {
        return TRUE;
    }
    if (in_array($method, array('ganti_password', 'ganti_password_action'), true)) {
        return TRUE;
    }

    // halaman penyuntingan data (hanya untuk dirinya sendiri) & proses simpan
    if ($class === 'pegawai' && in_array($method, array('update', 'update_action', 'detail'), true)) {
        if (in_array($method, array('update', 'detail'), true)) {
            $seg = (int) $ci->uri->segment(3);
            if ($seg !== (int) $pid) {
                return FALSE;
            }
        }
        return TRUE;
    }

    return FALSE;
}

function check_access($role_id, $menu_id)
{
    $ci = get_instance();

    $ci->db->where('role_id', $role_id);
    $ci->db->where('menu_id', $menu_id);
    $result = $ci->db->get('user_access_menu');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

/**
 * Cek apakah menu (berdasarkan url) boleh diakses role yang sedang login.
 * Admin (role 1) selalu punya akses penuh.
 * Menu yang tidak terdaftar di tabel menus dianggap boleh diakses (fail-open)
 * sehingga sidebar lama yang belum di-set tetap muncul.
 */
/**
 * ID pegawai milik pengguna yang sedang login.
 * - Login via NIK : id_pegawai sudah ada di session.
 * - Login via email : dicocokkan lewat email ke tabel pegawai (email unik),
 *   berlaku untuk role apa pun (pegawai, User KA Unit, dsb) agar penilai
 *   yang memakai akun users tetap dikenali sebagai pegawai.
 */
function current_pegawai_id()
{
    $ci = get_instance();

    $id = $ci->session->userdata('id_pegawai');
    if ($id) {
        return (int) $id;
    }

    $email = $ci->session->userdata('email');
    if ($email) {
        $ci->load->model('Pegawai_model');
        $row = $ci->Pegawai_model->get_by_email($email);
        if ($row && isset($row['id_pegawai'])) {
            return (int) $row['id_pegawai'];
        }
    }

    return 0;
}

function is_menu_accessible($url)
{
    $ci = get_instance();

    $role_id = (int) $ci->session->userdata('role_id');
    if ($role_id === 1) {
        return TRUE;
    }

    if (!$role_id || $url === '' || $url === '#') {
        return TRUE;
    }

    $menu = $ci->db->select('id')
        ->where('url', $url)
        ->where('is_active', 1)
        ->get('menus')
        ->row();

    if (!$menu) {
        return TRUE;
    }

    $access = $ci->db->where('role_id', $role_id)
        ->where('menu_id', $menu->id)
        ->from('user_access_menu')
        ->count_all_results();

    return $access > 0;
}
