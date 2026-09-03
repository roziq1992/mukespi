<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function log_surat($id_surat, $aksi, $keterangan = '')
{
    $ci = get_instance();
    $ci->db->insert('surat_log', array(
        'id_surat' => (int) $id_surat,
        'id_user' => (int) $ci->session->userdata('id'),
        'aksi' => $aksi,
        'keterangan' => $keterangan,
        'created_at' => date('Y-m-d H:i:s')
    ));
}

function surat_status_class($status)
{
    $map = array(
        'Diajukan' => 'primary',
        'Diproses Sekretaris' => 'warning',
        'Sudah Diberi Nomor' => 'info',
        'Diteruskan ke Direktur' => 'secondary',
        'Ditandatangani' => 'success',
        'Didisposisikan' => 'danger',
        'Selesai' => 'success'
    );
    return isset($map[$status]) ? $map[$status] : 'dark';
}

function surat_kirim_notifikasi($email_tujuan, $nama_tujuan, $subjek, $pesan, $id_surat)
{
    if (!filter_var($email_tujuan, FILTER_VALIDATE_EMAIL)) {
        log_message('error', 'Notifikasi surat tidak dikirim: alamat email tidak valid.');
        return FALSE;
    }

    $ci = get_instance();
    $ci->load->library('email');
    $ci->email->clear(TRUE);
    $ci->email->from(config_item('email_from_address'), config_item('email_from_name'));
    $ci->email->to($email_tujuan, $nama_tujuan);
    $ci->email->subject($subjek);
    $link = rtrim(config_item('email_app_url'), '/') . '/index.php/surat/detail/' . (int) $id_surat;
    $ci->email->message('<p>Yth. ' . htmlspecialchars($nama_tujuan, ENT_QUOTES, 'UTF-8') . ',</p><p>' . $pesan . '</p><p><a href="' . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . '">Buka detail dan tracking surat</a></p>');

    if (!$ci->email->send()) {
        log_message('error', 'Notifikasi surat gagal dikirim ke ' . $email_tujuan . ': ' . $ci->email->print_debugger(array('headers')));
        return FALSE;
    }
    return TRUE;
}
