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
