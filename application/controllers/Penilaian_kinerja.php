<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Penilaian_kinerja
 * ---------------------------------------------------------
 * Penilaian Kinerja Pegawai per unit.
 *
 * ALUR:
 *  - Admin/HRD mengelola periode, kriteria+bobot per unit, kepala unit, grading, dan rekap.
 *  - Kepala unit (penanda pegawai.is_kepala = 1) menilai bawahan pada unit yang dipimpin.
 *  - Bawahan = pegawai lain pada unit tsb (pegawai.id_unit), selain kepala sendiri,
 *    kepala lain, dan role manajemen (admin/direktur/sekretaris).
 *  - Skala skor 1-5, total nilai dihitung:
 *        total = SUM( (skor / 5) * (bobot / total_bobot_unit) * 100 )
 */
class Penilaian_kinerja extends CI_Controller
{
    private $ROLE_ID_ADMIN = 1;
    private $ROLE_ID_HRD   = 6;
    private $ROLE_ID_DIREKTUR = 4;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
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

    private function _is_direktur()
    {
        return (int) $this->session->userdata('role_id') === $this->ROLE_ID_DIREKTUR;
    }

    /**
     * Boleh menilai Kepala Unit: admin, direktur, atau penilai tambahan
     * yang didaftarkan admin (mis. Kabid Yanmed).
     */
    private function _can_assess_kepala()
    {
        if ($this->_is_admin() || $this->_is_direktur()) {
            return TRUE;
        }
        return $this->Penilaian_kinerja_model->is_registered_penilai_kepala($this->_user_id());
    }

    private function _user_id()
    {
        return (int) $this->session->userdata('id');
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
        $user_id  = $this->_user_id();
        $periode  = $this->Penilaian_kinerja_model->get_active_periode();

        $kepala_units = array();
        if (!$this->_is_admin()) {
            $kepala_units = $this->Penilaian_kinerja_model->get_kepala_units($user_id);
        }

        $data = array(
            'title'          => 'Penilaian Kinerja',
            'periode'        => $periode,
            'kepala_units'   => $kepala_units,
            'penilaian_saya' => $this->Penilaian_kinerja_model->get_penilaian_saya($user_id),
            'is_admin'       => $this->_is_admin(),
            'is_hrd'         => $this->_is_hrd(),
            'can_assess_kepala' => $this->_can_assess_kepala(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_kinerja/index', $data);
        $this->load->view('template/footer');
    }

    // ================= KEPALA UNIT: HALAMAN UNIT =================

    public function unit($id_unit = 0, $id_periode = 0)
    {
        $id_unit  = (int) $id_unit;
        $user_id  = $this->_user_id();

        if (!$this->_is_admin() && !$this->Penilaian_kinerja_model->is_kepala_unit($user_id, $id_unit)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda bukan kepala unit ini.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }

        $unit = $this->Penilaian_kinerja_model->get_unit($id_unit);
        if (!$unit) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Unit tidak ditemukan.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }

        if (!$id_periode) {
            $id_periode = (int) $this->input->get('periode', TRUE);
        }
        if (!$id_periode) {
            $periode = $this->Penilaian_kinerja_model->get_active_periode();
            $id_periode = $periode ? (int) $periode->id_periode : 0;
        }
        $periode = $this->Penilaian_kinerja_model->get_periode_by_id($id_periode);

        $subs  = $this->Penilaian_kinerja_model->get_subordinates($id_unit, $user_id);
        $items = array();
        foreach ($subs as $s) {
            $existing = $id_periode ? $this->Penilaian_kinerja_model->get_penilaian($id_periode, $id_unit, $user_id, $s->id_pegawai) : NULL;
            $items[] = array(
                'target'      => $s,
                'penilaian'   => $existing,
                'id_detail'   => $existing ? $existing->id_penilaian : 0,
            );
        }

        $data = array(
            'title'      => 'Penilaian Kinerja - ' . $unit->nm_unit,
            'unit'       => $unit,
            'periode'    => $periode,
            'periodes'   => $this->Penilaian_kinerja_model->get_periodes(),
            'items'      => $items,
            'is_admin'   => $this->_is_admin(),
            'kepala_list' => $this->Penilaian_kinerja_model->get_kepala_users_unit($id_unit),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_kinerja/unit', $data);
        $this->load->view('template/footer');
    }

    // ================= PENILAI KEPALA UNIT: HALAMAN DAFTAR KEPALA =================

    /**
     * Daftar Kepala Unit untuk dinilai oleh Direktur/Kabid/Admin.
     * Kriteria mengikuti unit masing-masing kepala.
     */
    public function kepala($id_periode = 0)
    {
        $user_id = $this->_user_id();

        if (!$this->_can_assess_kepala()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak berhak menilai Kepala Unit.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }

        $id_periode = (int) $id_periode;
        if (!$id_periode) {
            $id_periode = (int) $this->input->get('periode', TRUE);
        }
        if (!$id_periode) {
            $periode = $this->Penilaian_kinerja_model->get_active_periode();
            $id_periode = $periode ? (int) $periode->id_periode : 0;
        }
        $periode = $this->Penilaian_kinerja_model->get_periode_by_id($id_periode);

        $me_peg = $this->Penilaian_kinerja_model->get_pegawai_by_user($user_id);
        $targets = $this->Penilaian_kinerja_model->get_kepala_unit_targets(
            $id_periode,
            $user_id,
            $me_peg ? (int) $me_peg->id_pegawai : 0
        );

        $data = array(
            'title'      => 'Penilaian Kepala Unit',
            'periode'    => $periode,
            'periodes'   => $this->Penilaian_kinerja_model->get_periodes(),
            'targets'    => $targets,
            'is_admin'   => $this->_is_admin(),
            'is_direktur' => $this->_is_direktur(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_kinerja/kepala', $data);
        $this->load->view('template/footer');
    }

    // ================= ADMIN: PENILAI KEPALA UNIT =================

    public function penilai()
    {
        if (!$this->_is_admin()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Halaman ini khusus administrator.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }
        $data = array(
            'title'          => 'Penilai Kepala Unit',
            'penilai_list'   => $this->Penilaian_kinerja_model->get_penilai_kepala(),
            'user_options'   => $this->Penilaian_kinerja_model->get_users_for_penilai(),
        );
        $this->load->view('template/header', $data);
        $this->load->view('penilaian_kinerja/penilai', $data);
        $this->load->view('template/footer');
    }

    public function penilai_add()
    {
        if (!$this->_is_admin()) { redirect(site_url('penilaian_kinerja')); return; }
        $id_user = (int) $this->input->post('id_user', TRUE);
        $catatan = trim($this->input->post('catatan', TRUE));
        if ($id_user && $this->Penilaian_kinerja_model->add_penilai_kepala($id_user, $catatan ?: NULL)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Penilai Kepala Unit berhasil ditambahkan.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-warning">Gagal menambahkan penilai (mungkin sudah terdaftar).</div>');
        }
        redirect(site_url('penilaian_kinerja/penilai'));
    }

    public function penilai_delete($id_penilai_kepala = 0)
    {
        if (!$this->_is_admin()) { redirect(site_url('penilaian_kinerja')); return; }
        $this->Penilaian_kinerja_model->delete_penilai_kepala((int) $id_penilai_kepala);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Penilai berhasil dihapus.</div>');
        redirect(site_url('penilaian_kinerja/penilai'));
    }

    // ================= FORM PENILAIAN =================

    public function form($id_dinilai = 0, $id_unit = 0, $id_periode = 0)
    {
        $id_dinilai = (int) $id_dinilai;
        $id_unit    = (int) $id_unit;
        $id_periode = (int) $id_periode;
        $user_id    = $this->_user_id();

        if (!$id_periode) {
            $periode = $this->Penilaian_kinerja_model->get_active_periode();
            $id_periode = $periode ? (int) $periode->id_periode : 0;
        }

        $unit    = $this->Penilaian_kinerja_model->get_unit($id_unit);
        $periode = $this->Penilaian_kinerja_model->get_periode_by_id($id_periode);
        $target  = $this->Penilaian_kinerja_model->get_pegawai($id_dinilai);

        if (!$unit || !$periode || !$target) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak valid.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }

        // otorisasi: admin; ATAU kepala unit yang sah (menilai bawahan); ATAU penilai kepala unit (direktur/kabid)
        $is_admin  = $this->_is_admin();
        $is_kepala = $this->Penilaian_kinerja_model->is_kepala_unit($user_id, $id_unit);

        // penilai kepala unit: target harus KEPALA UNIT pada unit tsb
        $is_kepala_assessor = (!$is_admin && !$is_kepala)
            && $this->_can_assess_kepala()
            && (int) $target->is_kepala === 1
            && (int) $target->id_unit === $id_unit;

        if (!$is_admin && !$is_kepala && !$is_kepala_assessor) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak berhak menilai unit ini.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }
        // kepala unit menilai bawahan: pastikan target memang bawahan di unit tsb
        if (!$is_admin && !$is_kepala_assessor) {
            $ok = FALSE;
            foreach ($this->Penilaian_kinerja_model->get_subordinates($id_unit, $user_id) as $s) {
                if ((int) $s->id_pegawai === $id_dinilai) { $ok = TRUE; break; }
            }
            if (!$ok) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Pegawai ini bukan bawahan Anda di unit tersebut.</div>');
                redirect(site_url('penilaian_kinerja/unit/' . $id_unit));
                return;
            }
        }

        $kriteria = $this->Penilaian_kinerja_model->get_kriteria_grouped($id_unit);
        $penilaian = $this->Penilaian_kinerja_model->get_penilaian($id_periode, $id_unit, $user_id, $id_dinilai);
        $skor_map  = array();
        if ($penilaian) {
            $skor_map = $this->Penilaian_kinerja_model->get_skor_by_penilaian($penilaian->id_penilaian);
        }

        $data = array(
            'title'      => 'Form Penilaian - ' . $target->nama,
            'unit'       => $unit,
            'periode'    => $periode,
            'target'     => $target,
            'kriteria'   => $kriteria,
            'total_bobot'=> (float) $kriteria['total_bobot'],
            'penilaian'  => $penilaian,
            'skor_map'   => $skor_map,
            'is_admin'   => $is_admin,
            'leafless'   => TRUE,
            'mode'       => $is_kepala_assessor ? 'kepala' : 'unit',
            'penilai_label' => $is_kepala_assessor ? 'Pejabat Penilai (Direktur / Kabid)' : 'Penilai (Kepala Unit)',
            'back_url'   => $is_kepala_assessor
                ? site_url('penilaian_kinerja/kepala' . ($id_periode ? '/' . $id_periode : ''))
                : site_url('penilaian_kinerja/unit/' . $id_unit . ($id_periode ? '/' . $id_periode : '')),
            'has_kriteria'  => $this->Penilaian_kinerja_model->has_kriteria($id_unit),
            'dalam_jadwal'  => $this->_periode_terbuka($periode),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_kinerja/form', $data);
        $this->load->view('template/footer');
    }

    public function save()
    {
        $id_dinilai = (int) $this->input->post('id_dinilai', TRUE);
        $id_unit    = (int) $this->input->post('id_unit', TRUE);
        $id_periode = (int) $this->input->post('id_periode', TRUE);
        $user_id    = $this->_user_id();
        $status     = $this->input->post('status', TRUE) === 'selesai' ? 'selesai' : 'draft';
        $catatan    = trim($this->input->post('catatan', TRUE));

        $skors = $this->input->post('skor');
        $skor_map = array();
        if (is_array($skors)) {
            foreach ($skors as $id_kriteria => $skor) {
                $skor_map[(int) $id_kriteria] = ($skor !== '' && $skor !== NULL) ? (int) $skor : 0;
            }
        }

        $unit    = $this->Penilaian_kinerja_model->get_unit($id_unit);
        $periode = $this->Penilaian_kinerja_model->get_periode_by_id($id_periode);
        $target  = $this->Penilaian_kinerja_model->get_pegawai($id_dinilai);
        if (!$unit || !$periode || !$target) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data tidak valid.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }

        $is_admin  = $this->_is_admin();
        $is_kepala = $this->Penilaian_kinerja_model->is_kepala_unit($user_id, $id_unit);
        $is_kepala_assessor = (!$is_admin && !$is_kepala)
            && $this->_can_assess_kepala()
            && (int) $target->is_kepala === 1
            && (int) $target->id_unit === $id_unit;

        if (!$is_admin && !$is_kepala && !$is_kepala_assessor) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak berhak menilai unit ini.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }

        // Jika status selesai, wajib semua kriteria terisi
        if ($status === 'selesai') {
            $rows = $this->Penilaian_kinerja_model->get_kriteria_by_unit($id_unit, TRUE);
            $lengkap = !empty($rows);
            foreach ($rows as $r) {
                if (empty($skor_map[$r->id_kriteria]) || (int) $skor_map[$r->id_kriteria] <= 0) {
                    $lengkap = FALSE; break;
                }
            }
            if (!$lengkap) {
                $this->session->set_flashdata('message', '<div class="alert alert-warning">Semua kriteria wajib diisi untuk menyelesaikan penilaian.</div>');
                redirect(site_url('penilaian_kinerja/form/' . $id_dinilai . '/' . $id_unit . '/' . $id_periode));
                return;
            }
        }

        // Hapus skor yang tidak ada di form (kriteria dihapus admin dsb.)

        // Batasan jadwal periode: di luar tanggal mulai-selesai tidak bisa input,
        // kecuali ada izin bypass (tombol pada form) atau user admin.
        if (!$this->_periode_terbuka($periode)) {
            $bypass = $this->input->post('bypass', TRUE) === '1';
            if (!$bypass && !$this->_is_admin()) {
                $tgl = ($periode->tanggal_mulai ? date('d M Y', strtotime($periode->tanggal_mulai)) : '-')
                    . ' s.d. '
                    . ($periode->tanggal_selesai ? date('d M Y', strtotime($periode->tanggal_selesai)) : '-');
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Saat ini di luar jadwal periode penilaian (<strong>' . $tgl . '</strong>). Input ditutup. Centang <strong>"Izinkan input di luar jadwal"</strong> pada form jika tetap ingin mengisi.</div>');
                redirect(site_url('penilaian_kinerja/form/' . $id_dinilai . '/' . $id_unit . '/' . $id_periode));
                return;
            }
        }

        $this->Penilaian_kinerja_model->save_penilaian($id_periode, $id_unit, $user_id, $id_dinilai, $skor_map, $catatan, $status);

        $this->session->set_flashdata('message', '<div class="alert alert-success">Penilaian untuk <strong>' . html_escape($target->nama) . '</strong> berhasil disimpan.</div>');
        redirect($is_kepala_assessor
            ? site_url('penilaian_kinerja/kepala' . ($id_periode ? '/' . $id_periode : ''))
            : site_url('penilaian_kinerja/unit/' . $id_unit . ($id_periode ? '/' . $id_periode : '')));
    }

    // ================= DETAIL PENILAIAN =================

    public function detail($id_penilaian = 0)
    {
        $id_penilaian = (int) $id_penilaian;
        $penilaian = $this->Penilaian_kinerja_model->get_penilaian_by_id($id_penilaian);
        if (!$penilaian) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Penilaian tidak ditemukan.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }

        $user_id = $this->_user_id();
        $is_kepala = $this->Penilaian_kinerja_model->is_kepala_unit($user_id, $penilaian->id_unit);
        $my_peg    = $this->Penilaian_kinerja_model->get_pegawai_by_user($user_id);
        $is_dinilai = ($my_peg && (int) $penilaian->id_dinilai === (int) $my_peg->id_pegawai);
        $is_penilai = ((int) $penilaian->id_penilai === $user_id);
        if (!$this->_is_admin() && !$this->_is_hrd() && !$is_kepala && !$is_dinilai && !$is_penilai) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak berhak melihat penilaian ini.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }

        $kriteria = $this->Penilaian_kinerja_model->get_kriteria_by_unit($penilaian->id_unit, TRUE);
        $skor_map = $this->Penilaian_kinerja_model->get_skor_by_penilaian($id_penilaian);

        // bangun array hasil detail per kelompok (untuk print/cetak)
        $groups = array();
        $total  = 0;
        $tb     = $this->Penilaian_kinerja_model->total_bobot_unit($penilaian->id_unit);
        foreach ($kriteria as $r) {
            if (!isset($groups[$r->kelompok])) {
                $groups[$r->kelompok] = array('bobot' => 0, 'items' => array());
            }
            $skor = isset($skor_map[$r->id_kriteria]) ? (int) $skor_map[$r->id_kriteria] : 0;
            $bobot_norm = $tb > 0 ? ((float) $r->bobot / $tb) * 100 : 0;
            $kontribusi = ($skor / 5) * $bobot_norm;
            $total += $kontribusi;
            $groups[$r->kelompok]['bobot'] += (float) $r->bobot;
            $groups[$r->kelompok]['items'][] = array(
                'kriteria'   => $r->kriteria,
                'bobot'      => $r->bobot,
                'skor'       => $skor,
                'kontribusi' => round($kontribusi, 2),
            );
        }
        $total = round($total, 2);

        // riwayat penilaian bintang (pelaporan) untuk pegawai yang dinilai
        $tahun_p = (int) ($penilaian->tahun ?: date('Y'));
        $bintang_history_all = $this->Penilaian_kinerja_model->get_bintang_history($penilaian->id_dinilai, NULL);
        $bintang_history_thn = $this->Penilaian_kinerja_model->get_bintang_history($penilaian->id_dinilai, $tahun_p);

        $data = array(
            'title'      => 'Detail Penilaian Kinerja',
            'penilaian'  => $penilaian,
            'groups'     => $groups,
            'total'      => $total,
            'predikat'   => $this->Penilaian_kinerja_model->get_predikat($penilaian->id_unit, $total),
            'gradings'   => $this->Penilaian_kinerja_model->get_gradings($penilaian->id_unit),
            'is_kepala'  => $is_kepala,
            'is_dinilai' => $is_dinilai,
            'is_admin'   => $this->_is_admin(),
            'penilai_label' => !empty($penilaian->is_kepala_dinilai)
                ? 'Pejabat Penilai (Direktur / Kabid)'
                : 'Penilai (Kepala Unit)',
            'bintang_history'     => $bintang_history_all,
            'bintang_tahun'       => $tahun_p,
            'bintang_summary_all' => $this->Penilaian_kinerja_model->summarize_bintang($bintang_history_all),
            'bintang_summary_thn' => $this->Penilaian_kinerja_model->summarize_bintang($bintang_history_thn),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_kinerja/detail', $data);
        $this->load->view('template/footer');
    }

    public function hapus($id_penilaian = 0)
    {
        $id_penilaian = (int) $id_penilaian;
        $penilaian = $this->Penilaian_kinerja_model->get_penilaian_by_id($id_penilaian);
        if ($this->_is_admin() && $penilaian) {
            $this->Penilaian_kinerja_model->delete_penilaian($id_penilaian);
            $this->session->set_flashdata('message', '<div class="alert alert-success">Penilaian berhasil dihapus.</div>');
        }
        redirect(site_url('penilaian_kinerja'));
    }

    // ================= REKAP / LAPORAN =================

    public function rekap($id_periode = 0, $id_unit = 0)
    {
        if (!$this->_is_admin() && !$this->_is_hrd()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak berhak mengakses halaman ini.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }

        $id_periode = (int) $id_periode;
        if (!$id_periode) {
            $id_periode = (int) $this->input->get('periode', TRUE);
        }
        if (!$id_periode) {
            $periode = $this->Penilaian_kinerja_model->get_active_periode();
            $id_periode = $periode ? (int) $periode->id_periode : 0;
        }
        if (!$id_unit) {
            $id_unit = (int) $this->input->get('unit', TRUE);
        }

        $periode = $this->Penilaian_kinerja_model->get_periode_by_id($id_periode);
        $rekap   = $this->Penilaian_kinerja_model->get_rekap_grouped($id_periode, (int) $id_unit);

        // tambahkan predikat pada setiap item
        foreach ($rekap as $id_u => &$g) {
            foreach ($g['items'] as &$it) {
                $it->predikat = $this->Penilaian_kinerja_model->get_predikat($id_u, $it->total_nilai);
            }
        }

        $data = array(
            'title'      => 'Rekap Penilaian Kinerja',
            'periode'    => $periode,
            'periodes'   => $this->Penilaian_kinerja_model->get_periodes(),
            'units'      => $this->Penilaian_kinerja_model->get_units(),
            'rekap'      => $rekap,
            'id_unit'    => (int) $id_unit,
            'is_admin'   => $this->_is_admin(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_kinerja/rekap', $data);
        $this->load->view('template/footer');
    }

    // ================= ADMIN: KEPALA UNIT =================

    public function kepala_unit($id_unit = 0)
    {
        if (!$this->_is_admin()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Halaman ini khusus administrator.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }
        $id_unit = (int) $id_unit;
        if (!$id_unit) {
            $id_unit = (int) $this->input->get('unit', TRUE);
        }
        $unit = $id_unit ? $this->Penilaian_kinerja_model->get_unit($id_unit) : NULL;
        $data = array(
            'title'    => 'Kelola Kepala Unit',
            'units'    => $this->Penilaian_kinerja_model->get_units(),
            'id_unit'  => $id_unit,
            'unit'     => $unit,
            'members'  => $id_unit ? $this->Penilaian_kinerja_model->get_pegawai_in_unit($id_unit) : array(),
        );
        $this->load->view('template/header', $data);
        $this->load->view('penilaian_kinerja/kepala_unit', $data);
        $this->load->view('template/footer');
    }

    public function kepala_unit_save()
    {
        if (!$this->_is_admin()) {
            redirect(site_url('penilaian_kinerja'));
            return;
        }
        $id_unit = (int) $this->input->post('id_unit', TRUE);
        $kepalas = $this->input->post('kepala'); // array pegawai.id_pegawai yang jadi kepala
        $kepalas = is_array($kepalas) ? array_map('intval', $kepalas) : array();

        $this->Penilaian_kinerja_model->reset_kepala_unit($id_unit);
        foreach ($kepalas as $pid) {
            $this->Penilaian_kinerja_model->set_kepala($pid, 1);
        }

        $unit = $this->Penilaian_kinerja_model->get_unit($id_unit);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Kepala unit untuk <strong>' . html_escape($unit ? $unit->nm_unit : '') . '</strong> berhasil diperbarui.</div>');
        redirect(site_url('penilaian_kinerja/kepala_unit/' . $id_unit));
    }

    // ================= ADMIN: KRITERIA & BOBOT =================

    public function kriteria($id_unit = 0)
    {
        if (!$this->_is_admin()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Halaman ini khusus administrator.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }
        $id_unit = (int) $id_unit;
        if (!$id_unit) {
            $id_unit = (int) $this->input->get('unit', TRUE);
        }
        $kriteria = $this->Penilaian_kinerja_model->get_kriteria_grouped($id_unit);
        $data = array(
            'title'     => 'Kriteria & Bobot Penilaian',
            'units'     => $this->Penilaian_kinerja_model->get_units(),
            'id_unit'   => $id_unit,
            'kriteria'  => $kriteria,
            'gradings'  => $id_unit ? $this->Penilaian_kinerja_model->get_gradings($id_unit) : array(),
        );
        $this->load->view('template/header', $data);
        $this->load->view('penilaian_kinerja/kriteria', $data);
        $this->load->view('template/footer');
    }

    public function kriteria_add()
    {
        if (!$this->_is_admin()) { redirect(site_url('penilaian_kinerja')); return; }
        $id_unit = (int) $this->input->post('id_unit', TRUE);
        $kelompok = trim($this->input->post('kelompok', TRUE));
        $item     = trim($this->input->post('kriteria', TRUE));
        $bobot    = (float) $this->input->post('bobot', TRUE);
        if ($id_unit && $kelompok !== '' && $item !== '' && $bobot > 0) {
            $this->Penilaian_kinerja_model->insert_kriteria(array(
                'id_unit' => $id_unit,
                'kelompok' => $kelompok,
                'kriteria' => $item,
                'bobot'    => $bobot,
                'urut'     => (int) $this->input->post('urut', TRUE) ?: 0,
            ));
            $this->session->set_flashdata('message', '<div class="alert alert-success">Kriteria berhasil ditambahkan.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data kriteria tidak lengkap.</div>');
        }
        redirect(site_url('penilaian_kinerja/kriteria/' . $id_unit));
    }

    public function kriteria_edit()
    {
        if (!$this->_is_admin()) { redirect(site_url('penilaian_kinerja')); return; }
        $id_kriteria = (int) $this->input->post('id_kriteria', TRUE);
        $k = $this->Penilaian_kinerja_model->get_kriteria_by_id($id_kriteria);
        $id_unit = $k ? $k->id_unit : 0;
        $kelompok = trim($this->input->post('kelompok', TRUE));
        $item     = trim($this->input->post('kriteria', TRUE));
        $bobot    = (float) $this->input->post('bobot', TRUE);
        if ($k && $kelompok !== '' && $item !== '' && $bobot > 0) {
            $this->Penilaian_kinerja_model->update_kriteria($id_kriteria, array(
                'kelompok' => $kelompok,
                'kriteria' => $item,
                'bobot'    => $bobot,
                'urut'     => (int) $this->input->post('urut', TRUE) ?: 0,
            ));
        }
        redirect(site_url('penilaian_kinerja/kriteria/' . $id_unit));
    }

    public function kriteria_delete($id_kriteria = 0)
    {
        if (!$this->_is_admin()) { redirect(site_url('penilaian_kinerja')); return; }
        $k = $this->Penilaian_kinerja_model->get_kriteria_by_id((int) $id_kriteria);
        if ($k) {
            $this->Penilaian_kinerja_model->delete_kriteria((int) $id_kriteria);
        }
        redirect(site_url('penilaian_kinerja/kriteria/' . ($k ? $k->id_unit : 0)));
    }

    public function grading_save()
    {
        if (!$this->_is_admin()) { redirect(site_url('penilaian_kinerja')); return; }
        $id_unit = (int) $this->input->post('id_unit', TRUE);
        $labels  = $this->input->post('label');
        $mins    = $this->input->post('nilai_min');
        $maxs    = $this->input->post('nilai_max');
        if (is_array($labels)) {
            foreach ($this->Penilaian_kinerja_model->get_gradings($id_unit) as $g) {
                $this->Penilaian_kinerja_model->delete_grading((int) $g->id_grading);
            }
            foreach ($labels as $i => $label) {
                if (trim($label) === '') continue;
                $this->Penilaian_kinerja_model->insert_grading(array(
                    'id_unit' => $id_unit,
                    'label'   => trim($label),
                    'nilai_min' => isset($mins[$i]) ? (float) $mins[$i] : 0,
                    'nilai_max' => isset($maxs[$i]) ? (float) $maxs[$i] : 0,
                    'urut'    => $i + 1,
                ));
            }
            $this->session->set_flashdata('message', '<div class="alert alert-success">Predikat/grading berhasil diperbarui.</div>');
        }
        redirect(site_url('penilaian_kinerja/kriteria/' . $id_unit));
    }

    // ================= ADMIN: PERIODE =================

    public function periode()
    {
        if (!$this->_is_admin() && !$this->_is_hrd()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak berhak mengakses halaman ini.</div>');
            redirect(site_url('penilaian_kinerja'));
            return;
        }
        $data = array(
            'title'    => 'Kelola Periode Penilaian',
            'periodes' => $this->Penilaian_kinerja_model->get_periodes(),
            'is_admin' => $this->_is_admin(),
        );
        $this->load->view('template/header', $data);
        $this->load->view('penilaian_kinerja/periode', $data);
        $this->load->view('template/footer');
    }

    public function periode_add()
    {
        if (!$this->_is_admin()) { redirect(site_url('penilaian_kinerja/periode')); return; }
        $nama   = trim($this->input->post('nama', TRUE));
        $tahun  = (int) $this->input->post('tahun', TRUE);
        $status = $this->input->post('status', TRUE) === 'aktif' ? 'aktif' : 'draft';
        if ($nama !== '' && $tahun > 0) {
            if ($status === 'aktif' || $this->input->post('set_aktif', TRUE)) {
                $current = $this->Penilaian_kinerja_model->get_active_periode();
                if ($current) {
                    $this->Penilaian_kinerja_model->set_active_periode_current_to_selesai($current->id_periode);
                }
            }
            $this->Penilaian_kinerja_model->insert_periode(array(
                'nama'   => $nama,
                'tahun'  => $tahun,
                'tanggal_mulai' => $this->input->post('tanggal_mulai', TRUE) ?: NULL,
                'tanggal_selesai' => $this->input->post('tanggal_selesai', TRUE) ?: NULL,
                'input_mulai' => $this->input->post('input_mulai', TRUE) ?: NULL,
                'input_selesai' => $this->input->post('input_selesai', TRUE) ?: NULL,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ));
            $this->session->set_flashdata('message', '<div class="alert alert-success">Periode penilaian berhasil ditambahkan.</div>');
        }
        redirect(site_url('penilaian_kinerja/periode'));
    }

    public function periode_delete($id_periode = 0)
    {
        if (!$this->_is_admin()) { redirect(site_url('penilaian_kinerja/periode')); return; }
        $this->Penilaian_kinerja_model->delete_periode((int) $id_periode);
        redirect(site_url('penilaian_kinerja/periode'));
    }

    public function periode_setaktif($id_periode = 0)
    {
        if (!$this->_is_admin()) { redirect(site_url('penilaian_kinerja/periode')); return; }
        $this->Penilaian_kinerja_model->set_active_periode((int) $id_periode);
        redirect(site_url('penilaian_kinerja/periode'));
    }

    public function periode_edit()
    {
        if (!$this->_is_admin()) { redirect(site_url('penilaian_kinerja/periode')); return; }
        $id_periode = (int) $this->input->post('id_periode', TRUE);
        $this->Penilaian_kinerja_model->update_periode($id_periode, array(
            'nama'            => trim($this->input->post('nama', TRUE)),
            'tahun'           => (int) $this->input->post('tahun', TRUE),
            'tanggal_mulai'   => $this->input->post('tanggal_mulai', TRUE) ?: NULL,
            'tanggal_selesai' => $this->input->post('tanggal_selesai', TRUE) ?: NULL,
            'input_mulai'     => $this->input->post('input_mulai', TRUE) ?: NULL,
            'input_selesai'   => $this->input->post('input_selesai', TRUE) ?: NULL,
            'status'          => $this->input->post('status', TRUE) === 'aktif' ? 'aktif' : $this->input->post('status', TRUE),
            'updated_at'      => date('Y-m-d H:i:s'),
        ));
        redirect(site_url('penilaian_kinerja/periode'));
    }
}
/* End of file Penilaian_kinerja.php */