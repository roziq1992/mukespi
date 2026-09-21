<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Penilaian_otk
 * ---------------------------------------------------------
 * Penilaian Kinerja OTK — penilai -> yang dinilai (RS Airlangga).
 *
 * ALUR:
 *  - Admin/HRD menetapkan penilai lalu memilih pegawai yang dinilai (pk_otk).
 *  - Penilai (identifikasi via current_pegawai_id) mengisi form untuk tiap yang dinilai.
 *  - Kriteria mengikuti unit pegawai yang dinilai (pk_kriteria.id_unit),
 *    periode memakai pk_periode yang aktif.
 *  - Skala skor 1-5; total = SUM( (skor/5) * (bobot/total_bobot_unit) * 100 ).
 */
class Penilaian_otk extends CI_Controller
{
    private $ROLE_ID_ADMIN = 1;
    private $ROLE_ID_HRD   = 6;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Penilaian_otk_model');
        $this->load->model('Penilaian_kinerja_model');
        $this->load->helper('form');
    }

    private function _is_admin()
    {
        return (int) $this->session->userdata('role_id') === $this->ROLE_ID_ADMIN;
    }

    private function _is_hrd()
    {
        return (int) $this->session->userdata('role_id') === $this->ROLE_ID_HRD;
    }

    private function _is_manager()
    {
        return $this->_is_admin() || $this->_is_hrd();
    }

    /**
     * Apakah periode boleh diisi hari ini (tanggal hari ini dalam jadwal input periode).
     * Kalau tanggal input mulai/selesai belum di-set, dianggap terbuka.
     */
    private function _periode_terbuka($periode)
    {
        if (!$periode || !$periode->input_mulai || !$periode->input_selesai) {
            return TRUE;
        }
        $tgl = date('Y-m-d');
        return $tgl >= $periode->input_mulai && $tgl <= $periode->input_selesai;
    }

    // ================= HALAMAN UTAMA =================

    public function index()
    {
        $id_pegawai = current_pegawai_id();

        // SEMUA periode ditampilkan ke penilai (aktif, draft, selesai).
        // Yang menentukan boleh tidaknya input adalah jadwal input periode.
        $periodes  = $this->Penilaian_kinerja_model->get_periodes();
        $tugas_per_periode = array();
        $total_tugas = 0;
        $penilaian_saya = array();

        if ($id_pegawai) {
            foreach ($periodes as $pr) {
                $items = $this->Penilaian_otk_model->get_assignments((int)$pr->id_periode, $id_pegawai);
                $selesai = 0;
                foreach ($items as $it) {
                    if ($it->status === 'selesai') $selesai++;
                }
                $total_tugas += count($items);
                $tugas_per_periode[] = array(
                    'periode' => $pr,
                    'buka'    => $this->_periode_terbuka($pr),
                    'items'   => $items,
                    'total'   => count($items),
                    'selesai' => $selesai,
                );
            }
            // penilaian yang menyasar login sebagai yang dinilai
            $penilaian_saya = $this->Penilaian_otk_model->get_penilaian_saya($id_pegawai);
        }

        $data = array(
            'title'           => 'Penilaian Kinerja OTK',
            'periode'         => $this->Penilaian_kinerja_model->get_active_periode(),
            'is_admin'        => $this->_is_admin(),
            'is_hrd'          => $this->_is_hrd(),
            'is_manager'      => $this->_is_manager(),
            'id_pegawai'      => $id_pegawai,
            'tugas_per_periode' => $tugas_per_periode,
            'total_tugas'     => $total_tugas,
            'penilaian_saya'  => $penilaian_saya,
            'stat'            => array(
                'total_push' => $this->Penilaian_otk_model->count_assignments(),
                'total_penilai' => $this->Penilaian_otk_model->count_penilai(),
            ),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_otk/index', $data);
        $this->load->view('template/footer');
    }

    // ================= KELOLA PENILAI -> DINILAI =================

    public function kelola($id_penilai = 0)
    {
        if (!$this->_is_manager()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Halaman ini khusus administrator / HRD.</div>');
            redirect(site_url('penilaian_otk'));
            return;
        }

        $id_penilai = (int) $id_penilai;
        if (!$id_penilai) {
            $id_penilai = (int) $this->input->get('penilai', TRUE);
        }

        $data = array(
            'title'        => 'Kelola Penilai OTK',
            'penilai_list' => $this->Penilaian_otk_model->get_penilai_list(),
            'all_pegawai'  => $this->Penilaian_otk_model->get_all_pegawai(),
            'id_penilai'   => $id_penilai,
            'penilai'      => $id_penilai ? $this->Penilaian_kinerja_model->get_pegawai($id_penilai) : NULL,
            'assignment_map' => $id_penilai
                ? $this->Penilaian_otk_model->get_assignment_map($id_penilai)
                : array(),
            'is_admin'     => $this->_is_admin(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_otk/kelola', $data);
        $this->load->view('template/footer');
    }

    public function kelola_save()
    {
        if (!$this->_is_manager()) {
            redirect(site_url('penilaian_otk'));
            return;
        }
        $id_penilai = (int) $this->input->post('id_penilai', TRUE);
        $dinilai = $this->input->post('dinilai');
        $dinilai_ids = array();
        if (is_array($dinilai)) {
            foreach ($dinilai as $v) {
                $dinilai_ids[] = (int) $v;
            }
        }

        if ($id_penilai) {
            $this->Penilaian_otk_model->replace_assignments($id_penilai, $dinilai_ids);
            $peg = $this->Penilaian_kinerja_model->get_pegawai($id_penilai);
            $this->session->set_flashdata('message', '<div class="alert alert-success">Penilai <strong>' . html_escape($peg ? $peg->nama : '') . '</strong> &mdash; ' . count($dinilai_ids) . ' pegawai yang dinilai berhasil disimpan.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Pilih penilai terlebih dahulu.</div>');
        }
        redirect(site_url('penilaian_otk/kelola/' . $id_penilai));
    }

    /**
     * Reset seluruh tanggungan penilaian milik satu penilai
     * (hapus semua baris pk_otk untuk id_penilai tersebut).
     */
    public function kelola_reset($id_penilai = 0)
    {
        if (!$this->_is_manager()) {
            redirect(site_url('penilaian_otk'));
            return;
        }
        $id_penilai = (int) $id_penilai;
        if ($id_penilai) {
            $this->Penilaian_otk_model->replace_assignments($id_penilai, array());
            $peg = $this->Penilaian_kinerja_model->get_pegawai($id_penilai);
            $this->session->set_flashdata('message', '<div class="alert alert-success">Semua tanggungan penilaian untuk <strong>' . html_escape($peg ? $peg->nama : '') . '</strong> berhasil direset.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Pilih penilai terlebih dahulu.</div>');
        }
        redirect(site_url('penilaian_otk/kelola/' . $id_penilai));
    }

    // ================= FORM PENILAIAN =================

    public function form($id_penilai = 0, $id_dinilai = 0, $id_periode = 0)
    {
        $id_penilai = (int) $id_penilai;
        $id_dinilai = (int) $id_dinilai;
        $id_periode = (int) $id_periode;

        $my_id = current_pegawai_id();

        if (!$id_penilai && $my_id) {
            $id_penilai = $my_id;
        }

        if (!$id_periode) {
            $periode = $this->Penilaian_kinerja_model->get_active_periode();
            $id_periode = $periode ? (int)$periode->id_periode : 0;
        }

        $periode = $this->Penilaian_kinerja_model->get_periode_by_id($id_periode);
        $target  = $this->Penilaian_kinerja_model->get_pegawai($id_dinilai);
        $penilai = $this->Penilaian_kinerja_model->get_pegawai($id_penilai);

        if (!$periode || !$target || !$penilai) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak valid.</div>');
            redirect(site_url('penilaian_otk'));
            return;
        }

        // otorisasi: penilai login harus sama dengan penilai assignment; admin/hrd bebas
        if (!$this->_is_manager() && (int)$my_id !== $id_penilai) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak berhak mengisi penilaian atas nama penilai ini.</div>');
            redirect(site_url('penilaian_otk'));
            return;
        }

        // pastikan pasangan ter-assign (penilai boleh menilai hanya yang di-assign)
        if (!$this->Penilaian_otk_model->is_assigned($id_penilai, $id_dinilai)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Pegawai ini tidak terdaftar sebagai tanggungan penilaian Anda.</div>');
            redirect(site_url('penilaian_otk' . (!$this->_is_manager() ? '' : '/kelola/' . $id_penilai)));
            return;
        }

        $id_unit = $target->id_unit ? (int)$target->id_unit : 0;
        $kriteria = array('groups' => array(), 'total_bobot' => 0, 'unit' => NULL);
        if ($id_unit) {
            $kriteria = $this->Penilaian_kinerja_model->get_kriteria_grouped($id_unit);
        }

        $penilaian = $this->Penilaian_otk_model->get_penilaian($id_periode, $id_penilai, $id_dinilai);
        $skor_map = array();
        if ($penilaian) {
            $skor_map = $this->Penilaian_otk_model->get_skor_by_penilaian($penilaian->id_penilaian);
        }

        $unit = $id_unit ? $this->Penilaian_kinerja_model->get_unit($id_unit) : NULL;

        $data = array(
            'title'      => 'Form Penilaian OTK - ' . $target->nama,
            'periode'    => $periode,
            'target'     => $target,
            'penilai'    => $penilai,
            'unit'       => $unit,
            'kriteria'   => $kriteria,
            'total_bobot'=> (float) $kriteria['total_bobot'],
            'penilaian'  => $penilaian,
            'skor_map'   => $skor_map,
            'is_manager' => $this->_is_manager(),
            'back_url'   => site_url('penilaian_otk'),
            'has_kriteria' => $id_unit ? $this->Penilaian_kinerja_model->has_kriteria($id_unit) : FALSE,
            'dalam_jadwal' => $this->_periode_terbuka($periode),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_otk/form', $data);
        $this->load->view('template/footer');
    }

    public function save()
    {
        $id_penilai = (int) $this->input->post('id_penilai', TRUE);
        $id_dinilai = (int) $this->input->post('id_dinilai', TRUE);
        $id_periode = (int) $this->input->post('id_periode', TRUE);
        $status     = $this->input->post('status', TRUE) === 'selesai' ? 'selesai' : 'draft';
        $catatan    = trim($this->input->post('catatan', TRUE));

        $skors = $this->input->post('skor');
        $skor_map = array();
        if (is_array($skors)) {
            foreach ($skors as $id_kriteria => $skor) {
                $skor_map[(int) $id_kriteria] = ($skor !== '' && $skor !== NULL) ? (int) $skor : 0;
            }
        }

        $my_id = current_pegawai_id();
        if (!$id_penilai && $my_id) {
            $id_penilai = $my_id;
        }

        $periode = $this->Penilaian_kinerja_model->get_periode_by_id($id_periode);
        $target  = $this->Penilaian_kinerja_model->get_pegawai($id_dinilai);
        $penilai = $this->Penilaian_kinerja_model->get_pegawai($id_penilai);
        if (!$periode || !$target || !$penilai) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak valid.</div>');
            redirect(site_url('penilaian_otk'));
            return;
        }

        if (!$this->_is_manager() && (int)$my_id !== $id_penilai) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak berhak mengirim penilaian ini.</div>');
            redirect(site_url('penilaian_otk'));
            return;
        }

        if (!$this->Penilaian_otk_model->is_assigned($id_penilai, $id_dinilai)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Pasangan penilai-dinilai tidak valid.</div>');
            redirect(site_url('penilaian_otk'));
            return;
        }

        // batas jadwal input periode (non-admin non-manager harus dalam tanggal input periode, kecuali bypass)
        $bypass = $this->input->post('bypass', TRUE) === '1';
        if (!$bypass && !$this->_is_manager() && !$this->_periode_terbuka($periode)) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning">Input penilaian periode ini di luar jadwal input (' . date('d M Y', strtotime($periode->input_mulai)) . ' s/d ' . date('d M Y', strtotime($periode->input_selesai)) . ').</div>');
            redirect(site_url('penilaian_otk/form/' . $id_penilai . '/' . $id_dinilai . '/' . $id_periode));
            return;
        }

        $id_unit = $target->id_unit ? (int)$target->id_unit : 0;

        // status selesai: semua kriteria unit harus terisi
        if ($status === 'selesai' && $id_unit) {
            $rows = $this->Penilaian_kinerja_model->get_kriteria_by_unit($id_unit, TRUE);
            $lengkap = !empty($rows);
            foreach ($rows as $r) {
                if (empty($skor_map[$r->id_kriteria]) || (int) $skor_map[$r->id_kriteria] <= 0) {
                    $lengkap = FALSE; break;
                }
            }
            if (!$lengkap) {
                $this->session->set_flashdata('message', '<div class="alert alert-warning">Semua kriteria wajib diisi untuk menyelesaikan penilaian.</div>');
                redirect(site_url('penilaian_otk/form/' . $id_penilai . '/' . $id_dinilai . '/' . $id_periode));
                return;
            }
        }

        $this->Penilaian_otk_model->save_penilaian($id_periode, $id_unit, $id_penilai, $id_dinilai, $skor_map, $catatan, $status);

        $this->session->set_flashdata('message', '<div class="alert alert-success">Penilaian untuk <strong>' . html_escape($target->nama) . '</strong> berhasil disimpan.</div>');
        redirect(site_url('penilaian_otk'));
    }

    // ================= DETAIL =================

    public function detail($id_penilaian = 0)
    {
        $penilaian = $this->Penilaian_otk_model->get_penilaian_by_id((int)$id_penilaian);
        if (!$penilaian) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Penilaian tidak ditemukan.</div>');
            redirect(site_url('penilaian_otk'));
            return;
        }

        $my_id  = current_pegawai_id();
        $is_penilai = (int)$penilaian->id_penilai === (int)$my_id;
        $is_dinilai = (int)$penilaian->id_dinilai === (int)$my_id;
        if (!$this->_is_manager() && !$is_penilai && !$is_dinilai) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak berhak melihat penilaian ini.</div>');
            redirect(site_url('penilaian_otk'));
            return;
        }

        $kriteria = $this->Penilaian_kinerja_model->get_kriteria_by_unit((int)$penilaian->id_unit_dinilai, TRUE);
        $skor_map = $this->Penilaian_otk_model->get_skor_by_penilaian((int)$id_penilaian);

        $groups = array();
        $total  = 0;
        $tb     = $this->Penilaian_kinerja_model->total_bobot_unit((int)$penilaian->id_unit_dinilai);
        foreach ($kriteria as $r) {
            if (!isset($groups[$r->kelompok])) {
                $groups[$r->kelompok] = array('bobot' => 0, 'items' => array());
            }
            $skor = isset($skor_map[$r->id_kriteria]) ? (int)$skor_map[$r->id_kriteria] : 0;
            $bobot_norm = $tb > 0 ? ((float)$r->bobot / $tb) * 100 : 0;
            $kontribusi = ($skor / 5) * $bobot_norm;
            $total += $kontribusi;
            $groups[$r->kelompok]['bobot'] += (float)$r->bobot;
            $groups[$r->kelompok]['items'][] = array(
                'kriteria'   => $r->kriteria,
                'bobot'      => $r->bobot,
                'skor'       => $skor,
                'kontribusi' => round($kontribusi, 2),
            );
        }
        $total = round($total, 2);

        $data = array(
            'title'      => 'Detail Penilaian OTK',
            'penilaian'  => $penilaian,
            'groups'     => $groups,
            'total'      => $total,
            'predikat'   => $this->Penilaian_kinerja_model->get_predikat((int)$penilaian->id_unit_dinilai, $total),
            'gradings'   => $this->Penilaian_kinerja_model->get_gradings((int)$penilaian->id_unit_dinilai),
            'is_manager' => $this->_is_manager(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_otk/detail', $data);
        $this->load->view('template/footer');
    }

    public function hapus($id_penilaian = 0)
    {
        if (!$this->_is_admin()) {
            redirect(site_url('penilaian_otk'));
            return;
        }
        $penilaian = $this->Penilaian_otk_model->get_penilaian_by_id((int)$id_penilaian);
        if ($penilaian) {
            $this->Penilaian_otk_model->delete_penilaian((int)$id_penilaian);
            $this->session->set_flashdata('message', '<div class="alert alert-success">Penilaian berhasil dihapus.</div>');
        }
        redirect(site_url('penilaian_otk/rekap'));
    }

    // ================= REKAP =================

    public function rekap($id_periode = 0)
    {
        if (!$this->_is_manager()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak berhak mengakses halaman ini.</div>');
            redirect(site_url('penilaian_otk'));
            return;
        }

        $id_periode = (int) $id_periode;
        if (!$id_periode) {
            $id_periode = (int) $this->input->get('periode', TRUE);
        }
        if (!$id_periode) {
            $periode = $this->Penilaian_kinerja_model->get_active_periode();
            $id_periode = $periode ? (int)$periode->id_periode : 0;
        }
        $periode = $this->Penilaian_kinerja_model->get_periode_by_id($id_periode);

        $rekap = $this->Penilaian_otk_model->get_rekap_grouped($id_periode);
        foreach ($rekap as &$g) {
            foreach ($g['items'] as &$it) {
                $it->predikat = $this->Penilaian_kinerja_model->get_predikat((int)$it->id_unit_dinilai, $it->total_nilai);
            }
        }

        $data = array(
            'title'    => 'Rekap Penilaian OTK',
            'periode'  => $periode,
            'periodes' => $this->Penilaian_kinerja_model->get_periodes(),
            'rekap'    => $rekap,
            'is_admin' => $this->_is_admin(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_otk/rekap', $data);
        $this->load->view('template/footer');
    }

    /**
     * Export rekap OTK ke file Excel (.xls) tanpa library tambahan
     * (menggunakan tabel HTML yang dibuka langsung oleh Excel).
     */
    public function export_excel($id_periode = 0)
    {
        if (!$this->_is_manager()) {
            redirect(site_url('penilaian_otk'));
            return;
        }

        $id_periode = (int) $id_periode;
        if (!$id_periode) {
            $id_periode = (int) $this->input->get('periode', TRUE);
        }
        if (!$id_periode) {
            $periode = $this->Penilaian_kinerja_model->get_active_periode();
            $id_periode = $periode ? (int)$periode->id_periode : 0;
        }
        $periode = $this->Penilaian_kinerja_model->get_periode_by_id($id_periode);
        if (!$periode) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Periode tidak valid.</div>');
            redirect(site_url('penilaian_otk/rekap'));
            return;
        }

        $rekap = $this->Penilaian_otk_model->get_rekap_grouped($id_periode);
        foreach ($rekap as &$g) {
            foreach ($g['items'] as &$it) {
                $it->predikat = $this->Penilaian_kinerja_model->get_predikat((int)$it->id_unit_dinilai, $it->total_nilai);
            }
        }

        $nama_file = 'rekap_otk_' . strtolower(str_replace(' ', '_', $periode->nama)) . '_' . (int)$periode->tahun . '.xls';

        $rows_html = '';
        $no = 0;
        foreach ($rekap as $g) {
            foreach ($g['items'] as $it) {
                $no++;
                $status = $it->status === 'selesai' ? 'Selesai' : ($it->status === 'draft' ? 'Draft' : ($it->status === 'ditolak' ? 'Ditolak' : 'Belum'));
                $total  = $it->total_nilai !== NULL ? number_format((float)$it->total_nilai, 2) : '-';
                $rows_html .= '<tr>'
                    . '<td>' . $no . '</td>'
                    . '<td>' . html_escape($it->nama_penilai) . '</td>'
                    . '<td>' . html_escape($it->jabatan_penilai) . '</td>'
                    . '<td>' . html_escape($it->unit_penilai) . '</td>'
                    . '<td>' . html_escape($it->nama_dinilai) . '</td>'
                    . '<td>' . html_escape($it->jabatan_dinilai) . '</td>'
                    . '<td>' . html_escape($it->unit_dinilai) . '</td>'
                    . '<td>' . $status . '</td>'
                    . '<td>' . $total . '</td>'
                    . '<td>' . html_escape($it->predikat) . '</td>'
                    . '</tr>';
            }
        }

        $html = '<html><head><meta charset="UTF-8"><title>Rekap OTK</title></head><body>'
            . '<h3>REKAP PENILAIAN KINERJA OTK</h3>'
            . '<p>Periode: <strong>' . html_escape($periode->nama) . '</strong> (Tahun ' . (int)$periode->tahun . ')</p>'
            . '<p>Periode Penilaian: ' . ($periode->tanggal_mulai ? date('d M Y', strtotime($periode->tanggal_mulai)) : '-')
            . ' s/d ' . ($periode->tanggal_selesai ? date('d M Y', strtotime($periode->tanggal_selesai)) : '-')
            . ' | Input: ' . ($periode->input_mulai ? date('d M Y', strtotime($periode->input_mulai)) : '-')
            . ' s/d ' . ($periode->input_selesai ? date('d M Y', strtotime($periode->input_selesai)) : '-') . '</p>'
            . '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">'
            . '<thead><tr style="background:#f0f0f0;font-weight:bold;">'
            . '<td>No</td><td>Penilai</td><td>Jabatan Penilai</td><td>Unit Penilai</td>'
            . '<td>Pegawai Dinilai</td><td>Jabatan Dinilai</td><td>Unit Dinilai</td>'
            . '<td>Status</td><td>Total Nilai</td><td>Predikat</td>'
            . '</tr></thead><tbody>' . $rows_html . '</tbody></table>'
            . '</body></html>';

        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $nama_file . '"');
        header('Cache-Control: max-age=0');
        // BOM supaya karakter UTF-8 (mis. tanda dash) terbaca Excel
        echo "\xEF\xBB\xBF" . $html;
    }
}
/* End of file Penilaian_otk.php */