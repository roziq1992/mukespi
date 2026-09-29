<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Survei
 * ---------------------------------------------------------
 * Formulir Survei Kepuasan Pasien yang diisi PASIEN secara
 * publik (TANPA login) — bintang 1..5 per aspek layanan.
 *
 * Rute:  /survei            -> formulir
 *        /survei/kirim      -> proses simpan (POST)
 *        /survei/terima     -> halaman terima kasih
 *
 * Hasilnya dipantau admin/petalang lewat /survei_admin.
 */
class Survei extends CI_Controller
{
    // Skala predikat dari rata-rata bintang
    private static $predikat = array(
        'Sangat Baik' => 4.01,
        'Baik'        => 3.01,
        'Cukup'       => 2.01,
        'Buruk'       => 0.00,
    );

    public function __construct()
    {
        parent::__construct();
        // Sengaja TIDAK memakai is_logged_in(): pasien tidak punya akun.
        $this->load->library('form_validation');
        $this->load->model('Survei_model');
    }

    // ===================== FORMULIR =====================
    public function index()
    {
        $data = array(
            'aspek'  => $this->Survei_model->aspek_all(true),
            'unit'   => $this->Survei_model->unit_pelayanan(),
            'label'  => Survei_model::$label_skor,
        );

        $this->load->view('survei/form', $data);
    }

    // ===================== PROSES KIRIM =====================
    public function kirim()
    {
        $aspek = $this->Survei_model->aspek_all(true);

        // Honeypot — bot biasanya mengisi field tersembunyi ini.
        if (trim((string) $this->input->post('website', TRUE)) !== '') {
            redirect('survei/terima');
            return;
        }

        // Anti-spam: satu perangkat hanya boleh 1 survei per hari.
        $fp = $this->_fingerprint();
        if ($this->Survei_model->sudah_survei_hari_ini($fp)) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning">Anda sudah mengisi survei hari ini. Terima kasih atas masukan Anda.</div>');
            redirect('survei');
            return;
        }

        $this->form_validation->set_rules('nama', 'Nama', 'trim|max_length[150]');
        $this->form_validation->set_rules('nik', 'NIK', 'trim|max_length[30]|numeric');
        $this->form_validation->set_rules('no_rm', 'No. Rekam Medis', 'trim|max_length[50]');
        $this->form_validation->set_rules('tanggal_kunjungan', 'Tanggal Kunjungan', 'trim|required');
        $this->form_validation->set_rules('umur', 'Umur', 'trim|integer|min_length[1]|max_length[3]');
        $this->form_validation->set_rules('id_unit', 'Unit', 'trim|integer');
        $this->form_validation->set_rules('rekomendasi', 'Rekomendasi', 'trim|integer|min_length[0]|max_length[10]');
        $this->form_validation->set_rules('saran', 'Saran', 'trim|max_length[1000]');
        $this->form_validation->set_error_delimiters('<span class="sv-error">', '</span>');

        // Setiap aspek aktif wajib diberi bintang 1..5.
        foreach ($aspek as $a) {
            $this->form_validation->set_rules(
                'skor[' . $a->id . ']',
                $a->nama_aspek,
                'trim|required|integer|min_length[1]|max_length[5]'
            );
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Mohon lengkapi penilaian bintang pada setiap aspek layanan.</div>');
            $this->index();
            return;
        }

        // Kumpulkan jawaban
        $jawaban = array();
        $total = 0;
        $kritik = 0;
        $jumlah = 0;
        $id_aspek_valid = array();
        foreach ($aspek as $a) {
            $id_aspek_valid[] = (int) $a->id;
        }

        foreach ((array) $this->input->post('skor', TRUE) as $id_aspek => $skor) {
            $id_aspek = (int) $id_aspek;
            $skor = (int) $skor;
            // Hanya aspek aktif yang sah, dan skor 1..5
            if (!in_array($id_aspek, $id_aspek_valid, true) || $skor < 1 || $skor > 5) {
                continue;
            }
            $jawaban[] = array('id_aspek' => $id_aspek, 'skor' => $skor);
            $total += $skor;
            $jumlah++;
            if ($skor <= 2) {
                $kritik = 1;
            }
        }

        if (!$jumlah) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Belum ada penilaian bintang yang terisi.</div>');
            $this->index();
            return;
        }

        $is_anonim = (int) $this->input->post('is_anonim') ? 1 : 0;
        $kode = $this->Survei_model->kode_responden();

        $header = array(
            'kode'              => $kode,
            'nama'              => $is_anonim ? NULL : $this->_bersih($this->input->post('nama', TRUE)),
            'nik'               => $is_anonim ? NULL : $this->_bersih($this->input->post('nik', TRUE)),
            'no_rm'             => $is_anonim ? NULL : $this->_bersih($this->input->post('no_rm', TRUE)),
            'id_unit'           => (int) $this->input->post('id_unit') ?: NULL,
            'tanggal_kunjungan' => $this->input->post('tanggal_kunjungan', TRUE),
            'tanggal_survei'    => date('Y-m-d H:i:s'),
            'umur'              => (int) $this->input->post('umur') ?: NULL,
            'jenis_kelamin'     => in_array($this->input->post('jenis_kelamin', TRUE), array('L', 'P'), true)
                                     ? $this->input->post('jenis_kelamin', TRUE) : NULL,
            'metode'            => 'Mandiri',
            'is_anonim'         => $is_anonim,
            'jumlah_aspek'      => $jumlah,
            'skor_total'        => $total,
            'skor_rata'         => round($total / $jumlah, 2),
            'predikat'          => $this->_predikat($total / $jumlah),
            'rekomendasi'       => $this->input->post('rekomendasi', TRUE) === NULL || $this->input->post('rekomendasi', TRUE) === ''
                                     ? NULL : (int) $this->input->post('rekomendasi', TRUE),
            'saran'             => $this->_bersih($this->input->post('saran', TRUE)),
            'is_kritik'         => $kritik,
            'created_at'        => date('Y-m-d H:i:s'),
        );

        $simpan = $this->Survei_model->simpan_responden($header, $jawaban);
        if (!$simpan) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Terjadi kesalahan saat menyimpan 데이터를. Silakan coba lagi.</div>');
            $this->index();
            return;
        }

        $this->Survei_model->catat_fingerprint($fp);

        // Kabari admin & HRD bila ada keluhan bermasalah
        if ($kritik) {
            $this->_kabar_admin($simpan['kode']);
        }

        $this->session->set_flashdata('sv_kode', $simpan['kode']);
        $this->session->set_flashdata('sv_rata', $header['skor_rata']);
        $this->session->set_flashdata('sv_predikat', $header['predikat']);
        redirect('survei/terima');
    }

    // ===================== TERIMA KASIH =====================
    public function terima()
    {
        $data = array(
            'kode'     => $this->session->flashdata('sv_kode'),
            'rata'     => $this->session->flashdata('sv_rata'),
            'predikat' => $this->session->flashdata('sv_predikat'),
            'label'    => Survei_model::$label_skor,
        );

        $this->load->view('survei/terima', $data);
    }

    // ===================== BANTUAN =====================
    private function _predikat($rata)
    {
        if ($rata >= self::$predikat['Sangat Baik']) return 'Sangat Baik';
        if ($rata >= self::$predikat['Baik'])        return 'Baik';
        if ($rata >= self::$predikat['Cukup'])       return 'Cukup';
        return 'Buruk';
    }

    private function _bersih($teks)
    {
        $teks = trim((string) $teks);
        return $teks === '' ? NULL : $teks;
    }

    /**
     * Sidik jari perangkat sederhana untuk pembatas spam harian.
     */
    private function _fingerprint()
    {
        $ip = $this->input->ip_address();
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        return hash('sha256', $ip . '|' . $ua);
    }

    /**
     * Kirim notifikasi ke seluruh akun admin & HRD saat ada aspek bernilai 1..2.
     */
    private function _kabar_admin($kode)
    {
        $this->load->model('Notifikasi_model', 'notifikasi');

        $penerima = $this->db->where_in('role_id', array(1, 3, 6))
            ->where('is_active', 1)
            ->get('users')
            ->result();

        foreach ($penerima as $u) {
            $this->notifikasi->add(
                $u->id,
                'Survei kepuasan baru (' . $kode . ') berisi penilaian yang perlu ditindaklanjuti.',
                site_url('survei_admin/detail_responden/' . $kode)
            );
        }
    }
}
/* End of Survei.php */
