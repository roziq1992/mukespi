<?php


function is_logged_in($role = false)
{
    $ci = get_instance();
    if (!$ci->session->userdata('email') && !$ci->session->userdata('is_pegawai')) {
        redirect('auth');
    }

    if ($role) {
        $role_id = $ci->session->userdata('role_id');

		if($role_id != $role) {
			redirect('auth/blocked');
		}
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
 * - Login via email dengan role "pegawai" (role 7): dicocokkan lewat email.
 */
function current_pegawai_id()
{
    $ci = get_instance();

    $id = $ci->session->userdata('id_pegawai');
    if ($id) {
        return (int) $id;
    }

    if ((int) $ci->session->userdata('role_id') === 7 && $ci->session->userdata('email')) {
        $ci->load->model('Pegawai_model');
        $row = $ci->Pegawai_model->get_by_email($ci->session->userdata('email'));
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
