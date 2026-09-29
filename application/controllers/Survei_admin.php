<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Survei_admin
 * ---------------------------------------------------------
 * Monitoring hasil Survei Kepuasan Pasien untuk admin/surveyor/HRD:
 * - dashboard   : KPI, tren bulanan, sebaran skor, rata-rata aspek & unit
 * - data        : daftar seluruh respon (filter + pencarian + paging)
 * - detail      : rincian jawaban per aspek + riwayat tindak lanjut
 * - tindak_lanjut: catat progress keluhan / saran pasien
 * - ekspor      : unduh rekap ke Excel
 * - aspek       : master aspek penilaian (CRUD)
 */
class Survei_admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        if (!is_menu_accessible('survei_admin')) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Anda tidak memiliki akses ke menu Survei Kepuasan Pasien.</div>');
            redirect('portal');
        }

        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->model('Survei_model');
    }

    // ===================== DASHBOARD =====================
    public function index()
    {
        $f = $this->_filter_input();

        $data = array(
            'title'         => 'Monitoring Survei Kepuasan Pasien',
            'f'             => $f,
            'bulan'         => $this->_bulan_list(),
            'tahun'         => $this->_tahun_list(),
            'unit'          => $this->Survei_model->unit_pelayanan(),
            'statistik'     => $this->Survei_model->statistik($f),
            'sebaran_skor'  => $this->Survei_model->sebaran_skor($f),
            'sebaran_pred'  => $this->Survei_model->sebaran_predikat($f),
            'rekap_aspek'   => $this->Survei_model->rekap_aspek($f),
            'rekap_unit'    => $this->Survei_model->rekap_unit($f, 10),
            'tren'          => $this->Survei_model->tren_bulanan(isset($f['tahun']) ? $f['tahun'] : date('Y')),
            'label_skor'    => Survei_model::$label_skor,
            'rekap_tl'     => $this->Survei_model->rekap_tindak_lanjut($f),
        );

        $this->load->view('template/header', $data);
        $this->load->view('survei_admin/dashboard', $data);
        $this->load->view('template/footer');
    }

    // ===================== DAFTAR RESPON =====================
    public function data()
    {
        $f = $this->_filter_input();
        $q = urldecode($this->input->get('q', TRUE));
        $f['q'] = trim($q);
        $start = (int) $this->input->get('start');

        $config['base_url'] = site_url('survei_admin/data') . '?' . http_build_query(array(
            'q'     => $f['q'],
            'bulan' => isset($f['bulan']) ? $f['bulan'] : '',
            'tahun' => isset($f['tahun']) ? $f['tahun'] : '',
            'unit'  => isset($f['id_unit']) ? $f['id_unit'] : '',
            'predikat' => isset($f['predikat']) ? $f['predikat'] : '',
            'kritik'   => isset($f['is_kritik']) ? $f['is_kritik'] : '',
        ));
        $config['first_url']  = $config['base_url'];
        $config['per_page']   = 15;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Survei_model->count_responden($f);

        $this->pagination->initialize($config);

        $data = array(
            'title'      => 'Data Survei Kepuasan Pasien',
            'f'          => $f,
            'q'          => $f['q'],
            'start'      => $start,
            'bulan'      => $this->_bulan_list(),
            'tahun'      => $this->_tahun_list(),
            'unit'       => $this->Survei_model->unit_pelayanan(),
            'responden'  => $this->Survei_model->list_responden($f, $config['per_page'], $start),
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'label_skor' => Survei_model::$label_skor,
        );

        $this->load->view('template/header', $data);
        $this->load->view('survei_admin/data', $data);
        $this->load->view('template/footer');
    }

    // ===================== DETAIL =====================
    public function detail($id)
    {
        $row = $this->Survei_model->get_responden($id);
        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data survei tidak ditemukan.</div>');
            redirect(site_url('survei_admin/data'));
            return;
        }

        $data = array(
            'title'      => 'Detail Survei ' . $row->kode,
            'row'        => $row,
            'jawaban'    => $this->Survei_model->jawaban_responden($id),
            'tindak'     => $this->Survei_model->tindak_lanjut($id),
            'label_skor' => Survei_model::$label_skor,
        );

        $this->load->view('template/header', $data);
        $this->load->view('survei_admin/detail', $data);
        $this->load->view('template/footer');
    }

    public function detail_responden($kode)
    {
        $row = $this->Survei_model->get_responden_by_kode($kode);
        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data survei tidak ditemukan.</div>');
            redirect(site_url('survei_admin/data'));
            return;
        }
        return $this->detail($row->id);
    }

    // ===================== TINDAK LANJUT =====================
    public function tindak_lanjut()
    {
        $id = (int) $this->input->post('id_responden');
        $row = $this->Survei_model->get_responden($id);
        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data survei tidak ditemukan.</div>');
            redirect(site_url('survei_admin/data'));
            return;
        }

        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[Diproses,Selesai]');
        $this->form_validation->set_rules('catatan', 'Catatan', 'trim|required|max_length[1000]');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Status dan catatan tindak lanjut wajib diisi.</div>');
            redirect(site_url('survei_admin/detail/' . $id));
            return;
        }

        $this->Survei_model->tambah_tindak_lanjut(
            $id,
            (int) $this->session->userdata('id'),
            $this->input->post('status', TRUE),
            $this->input->post('catatan', TRUE)
        );

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Tindak lanjut tersimpan.</div>');
        redirect(site_url('survei_admin/detail/' . $id));
    }

    // ===================== HAPUS RESPON =====================
    public function hapus($id)
    {
        $row = $this->Survei_model->get_responden($id);
        if ($row) {
            $this->Survei_model->delete_responden($id);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data survei ' . html_escape($row->kode) . ' berhasil dihapus.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data survei tidak ditemukan.</div>');
        }
        redirect(site_url('survei_admin/data'));
    }

    // ===================== MASTER ASPEK =====================
    public function aspek()
    {
        $data = array(
            'title' => 'Master Aspek Penilaian',
            'aspek' => $this->Survei_model->aspek_all(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('survei_admin/aspek', $data);
        $this->load->view('template/footer');
    }

    public function aspek_simpan()
    {
        $id = (int) $this->input->post('id');

        $this->form_validation->set_rules('kode', 'Kode', 'trim|required|max_length[30]|alpha_dash');
        $this->form_validation->set_rules('nama_aspek', 'Nama Aspek', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim|max_length[255]');
        $this->form_validation->set_rules('icon', 'Ikon', 'trim|max_length[100]');
        $this->form_validation->set_rules('urutan', 'Urutan', 'trim|required|integer');
        $this->form_validation->set_rules('bobot', 'Bobot', 'trim|required|integer|min_length[1]|max_length[5]');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Periksa kembali isian aspek.</div>');
            redirect(site_url('survei_admin/aspek'));
            return;
        }

        $kode = strtolower($this->input->post('kode', TRUE));
        if ($this->Survei_model->aspek_exists_kode($kode, $id)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Kode aspek "' . html_escape($kode) . '" sudah dipakai.</div>');
            redirect(site_url('survei_admin/aspek'));
            return;
        }

        $data = array(
            'kode'       => $kode,
            'nama_aspek' => $this->input->post('nama_aspek', TRUE),
            'deskripsi'  => $this->input->post('deskripsi', TRUE) ?: NULL,
            'icon'       => $this->input->post('icon', TRUE) ?: 'fas fa-star',
            'urutan'     => (int) $this->input->post('urutan'),
            'bobot'      => (int) $this->input->post('bobot'),
            'is_active'  => (int) $this->input->post('is_active') ? 1 : 0,
        );

        if ($id > 0) {
            $this->Survei_model->aspek_update($id, $data);
            $pesan = 'Aspek penilaian berhasil diperbarui.';
        } else {
            $data['is_active'] = 1;
            $this->Survei_model->aspek_insert($data);
            $pesan = 'Aspek penilaian berhasil ditambahkan.';
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">' . $pesan . '</div>');
        redirect(site_url('survei_admin/aspek'));
    }

    public function aspek_hapus($id)
    {
        $aspek = $this->Survei_model->aspek_get($id);
        if (!$aspek) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Aspek tidak ditemukan.</div>');
            redirect(site_url('survei_admin/aspek'));
            return;
        }

        $dipakai = $this->Survei_model->aspek_count_jawaban($id);
        if ($dipakai > 0) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Aspek "<strong>' . html_escape($aspek->nama_aspek) . '</strong>" sudah dipakai pada ' . $dipakai . ' jawaban survei, jadi tidak bisa dihapus — nonaktifkan saja.</div>');
            redirect(site_url('survei_admin/aspek'));
            return;
        }

        $this->Survei_model->aspek_delete($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Aspek berhasil dihapus.</div>');
        redirect(site_url('survei_admin/aspek'));
    }

    public function aspek_toggle($id)
    {
        $aspek = $this->Survei_model->aspek_get($id);
        if ($aspek) {
            $baru = (int) $aspek->is_active === 1 ? 0 : 1;
            $this->Survei_model->aspek_update($id, array('is_active' => $baru));
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Aspek "<strong>' . html_escape($aspek->nama_aspek) . '</strong>" berhasil ' . ($baru ? 'diaktifkan' : 'dinonaktifkan') . '.</div>');
        }
        redirect(site_url('survei_admin/aspek'));
    }

    // ===================== EKSPOR EXCEL =====================
    public function ekspor()
    {
        $this->load->helper('exportexcel');
        $f = $this->_filter_input();
        $rows = $this->Survei_model->list_responden($f);
        $aspek = $this->Survei_model->aspek_all();

        $namaFile = 'survei_kepuasan_' . date('Ymd_His') . '.xls';

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=" . $namaFile);
        header("Content-Transfer-Encoding: binary");

        xlsBOF();

        $baris = 1;
        $kolom = 0;
        xlsWriteLabel($baris, $kolom++, 'No');
        xlsWriteLabel($baris, $kolom++, 'Kode Survei');
        xlsWriteLabel($baris, $kolom++, 'Nama');
        xlsWriteLabel($baris, $kolom++, 'No. RM');
        xlsWriteLabel($baris, $kolom++, 'Unit');
        xlsWriteLabel($baris, $kolom++, 'Tanggal Kunjungan');
        xlsWriteLabel($baris, $kolom++, 'Tanggal Survei');
        xlsWriteLabel($baris, $kolom++, 'Anonim');
        foreach ($aspek as $a) {
            xlsWriteLabel($baris, $kolom++, $a->nama_aspek);
        }
        xlsWriteLabel($baris, $kolom++, 'Rata-rata');
        xlsWriteLabel($baris, $kolom++, 'Predikat');
        xlsWriteLabel($baris, $kolom++, 'Rekomendasi (0-10)');
        xlsWriteLabel($baris, $kolom++, 'Saran / Keluhan');
        xlsWriteLabel($baris, $kolom++, 'Sudah Ditindak Lanjut');

        $no = 0;
        foreach ($rows as $r) {
            $no++;
            $baris = $no + 1;
            $kolom = 0;

            // map jawaban per aspek supaya bisa ditulis sesuai kolom master
            $peta = array();
            foreach ($this->Survei_model->jawaban_responden($r->id) as $j) {
                $peta[$j->id_aspek] = (int) $j->skor;
            }

            xlsWriteNumber($baris, $kolom++, $no);
            xlsWriteLabel($baris, $kolom++, $r->kode);
            xlsWriteLabel($baris, $kolom++, $r->is_anonim ? '(Anonim)' : ($r->nama ?: '-'));
            xlsWriteLabel($baris, $kolom++, $r->no_rm ?: '-');
            xlsWriteLabel($baris, $kolom++, $r->nm_unit ?: '-');
            xlsWriteLabel($baris, $kolom++, $r->tanggal_kunjungan);
            xlsWriteLabel($baris, $kolom++, date('d-m-Y H:i', strtotime($r->tanggal_survei)));
            xlsWriteLabel($baris, $kolom++, $r->is_anonim ? 'Ya' : 'Tidak');
            foreach ($aspek as $a) {
                xlsWriteLabel($baris, $kolom++, isset($peta[$a->id]) ? (string) $peta[$a->id] : '');
            }
            xlsWriteNumber($baris, $kolom++, (float) $r->skor_rata);
            xlsWriteLabel($baris, $kolom++, $r->predikat);
            xlsWriteLabel($baris, $kolom++, $r->rekomendasi === NULL ? '-' : (string) $r->rekomendasi);
            xlsWriteLabel($baris, $kolom++, $r->saran ?: '-');
            xlsWriteLabel($baris, $kolom++, $this->Survei_model->sudah_ditindak_lanjut($r->id) ? 'Ya' : 'Belum');
        }

        xlsEOF();
        exit;
    }

    // ===================== BANTUAN =====================
    /**
     * Ambil & bersihkan parameter filter dari GET.
     */
    private function _filter_input()
    {
        $f = array();

        $bulan = (int) $this->input->get('bulan');
        if ($bulan >= 1 && $bulan <= 12) {
            $f['bulan'] = $bulan;
        }

        $tahun = (int) $this->input->get('tahun');
        if ($tahun >= 2010 && $tahun <= (int) date('Y') + 1) {
            $f['tahun'] = $tahun;
        }

        $unit = (int) $this->input->get('unit');
        if ($unit > 0) {
            $f['id_unit'] = $unit;
        }

        $predikat = $this->input->get('predikat', TRUE);
        if (in_array($predikat, array('Sangat Baik', 'Baik', 'Cukup', 'Buruk'), true)) {
            $f['predikat'] = $predikat;
        }

        $kritik = $this->input->get('kritik', TRUE);
        if ($kritik === '0' || $kritik === '1') {
            $f['is_kritik'] = (int) $kritik;
        }

        return $f;
    }

    private function _bulan_list()
    {
        return array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',  4 => 'April',
            5 => 'Mei',     6 => 'Juni',     7 => 'Juli',   8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        );
    }

    private function _tahun_list()
    {
        $awal = (int) date('Y') - 4;
        $out = array();
        for ($t = (int) date('Y') + 1; $t >= $awal; $t--) {
            $out[$t] = $t;
        }
        return $out;
    }
}
/* End of Survei_admin.php */
