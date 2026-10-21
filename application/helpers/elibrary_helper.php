<?php
/**
 * Helper E-Library (Portal Penemuan Literatur).
 *
 * Berisi token CSRF lokal modul + utilitas format tampilan.
 * Token CSRF lokal dipilih karena aplikasi ini belum mengaktifkan
 * csrf_protection secara global dan seluruh modul lain memakai form tanpa
 * token, sehingga menyalakan CSRF global akan merusak modul yang ada.
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Token CSRF modul (disimpan di session, satu token per sesi login).
 */
function elibrary_csrf_token()
{
    $ci = get_instance();
    $token = $ci->session->userdata('elibrary_csrf_token');

    if (empty($token)) {
        $token = bin2hex(random_bytes(32));
        $ci->session->set_userdata('elibrary_csrf_token', $token);
    }

    return $token;
}

/**
 * Input hidden token CSRF untuk disisipkan di dalam <form>.
 */
function elibrary_csrf_field()
{
    return '<input type="hidden" name="elibrary_token" value="' . html_escape(elibrary_csrf_token()) . '">';
}

/**
 * Verifikasi token CSRF yang dikirim dari form.
 */
function elibrary_csrf_check()
{
    $ci = get_instance();
    $session_token = (string) $ci->session->userdata('elibrary_csrf_token');
    $posted_token = (string) $ci->input->post('elibrary_token');

    if ($session_token === '' || $posted_token === '') {
        return FALSE;
    }

    return hash_equals($session_token, $posted_token);
}

/**
 * Potong teks panjang menjadi ringkasan (tanpa memotong di tengah kata).
 */
function elibrary_excerpt($text, $limit = 220)
{
    $text = trim(preg_replace('/\s+/u', ' ', (string) $text));

    if ($text === '') {
        return '';
    }

    if (mb_strlen($text, 'UTF-8') <= $limit) {
        return $text;
    }

    $potongan = mb_substr($text, 0, $limit, 'UTF-8');
    $spasi = mb_strrpos($potongan, ' ', 0, 'UTF-8');

    if ($spasi !== false && $spasi > 40) {
        $potongan = mb_substr($potongan, 0, $spasi, 'UTF-8');
    }

    return rtrim($potongan, " ,.;:-") . '…';
}

/**
 * Label jenis publikasi.
 */
function elibrary_jenis_label($jenis)
{
    $map = array(
        'artikel' => 'Artikel Jurnal',
        'buku' => 'Buku',
        'tesis' => 'Tesis',
        'laporan' => 'Laporan',
        'conference' => 'Konferensi',
        'lainnya' => 'Lainnya',
    );

    $jenis = strtolower(trim((string) $jenis));

    return isset($map[$jenis]) ? $map[$jenis] : ucfirst($jenis === '' ? 'Lainnya' : $jenis);
}

/**
 * Tautan DOI resmi.
 */
function elibrary_doi_url($doi)
{
    $doi = trim((string) $doi);

    if ($doi === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $doi)) {
        return $doi;
    }

    return 'https://doi.org/' . ltrim($doi, '/');
}

/**
 * Format tanggal Indonesia (01 Januari 2024).
 */
function elibrary_tanggal($tanggal)
{
    $tanggal = trim((string) $tanggal);

    if ($tanggal === '' || $tanggal === '0000-00-00') {
        return '';
    }

    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    );

    $timestamp = strtotime($tanggal);

    if (!$timestamp) {
        return $tanggal;
    }

    return date('d', $timestamp) . ' ' . $bulan[(int) date('n', $timestamp)] . ' ' . date('Y', $timestamp);
}

/**
 * Pecah daftar kata kunci menjadi array (dipisah koma/semicolon).
 */
function elibrary_kata_kunci($kata_kunci)
{
    $parts = preg_split('/[,;]+/u', (string) $kata_kunci);
    $result = array();

    foreach ($parts as $part) {
        $part = trim($part);
        if ($part !== '') {
            $result[] = $part;
        }
    }

    return $result;
}

/**
 * Nama sumber tampilan: pakai sumber terdaftar, fallback ke nama jurnal.
 */
function elibrary_nama_source($row)
{
    if (!empty($row->nama_source)) {
        return $row->nama_source;
    }

    if (!empty($row->nama_jurnal)) {
        return $row->nama_jurnal;
    }

    return 'Tanpa Sumber';
}

/**
 * Ensure URL eksternal aman untuk dipakai pada atribut href.
 */
function elibrary_safe_url($url)
{
    $url = trim((string) $url);

    if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
        return '';
    }

    $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

    if ($scheme !== 'http' && $scheme !== 'https') {
        return '';
    }

    return $url;
}
