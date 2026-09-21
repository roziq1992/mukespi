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

    // ================= HALAMAN UTAMA =================

    public function index()
    {
        $id_pegawai   = current_pegawai_id();
        $periode      = $this->Penilaian_kinerja_model->get_active_periode();
        $id_periode   = $periode ? (int)$periode->id_periode : 0;

        $my_assignments = array();
        $penilaian_saya = array();
        if ($id_pegawai) {
            // penilaian yang ditugaskan ke login sebagai penilai
            $my_assignments = $this->Penilaian_otk_model->get_assignments($id_periode, $id_pegawai);
            // penilaian yang menyasar login sebagai yang dinilai
            $penilaian_saya = $this->Penilaian_otk_model->get_penilaian_saya($id_pegawai);
        }

        $data = array(
            'title'           => 'Penilaian Kinerja OTK',
            'periode'         => $periode,
            'is_admin'        => $this->_is_admin(),
            'is_hrd'          => $this->_is_hrd(),
            'is_manager'      => $this->_is_manager(),
            'id_pegawai'      => $id_pegawai,
            'pekerjaan_saya'  => $my_assignments,
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
}
/* End of file Penilaian_otk.php */