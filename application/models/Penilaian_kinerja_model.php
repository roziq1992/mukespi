<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Penilaian_kinerja_model
 * ---------------------------------------------------------
 * Modul Penilaian Kinerja Pegawai (RS Airlangga).
 * - Kriteria penilaian tersimpan per unit (pk_kriteria) lengkap dengan bobot.
 * - Penilai = kepala unit (penanda is_kepala pada tabel pegawai).
 * - Dinilai = pegawai yang terdaftar pada unit tsb (pegawai.id_unit).
 * - Unit & status kepala diatur langsung pada data pegawai, tanpa menu Akses User Unit.
 * - Skor per kriteria memakai skala 1-5.
 *   total = SUM( (skor / 5) * (bobot / total_bobot_unit) * 100 )  -> maks 100.
 */
class Penilaian_kinerja_model extends CI_Model
{
    // role yang TIDAK bisa menjadi target penilaian (manajemen/administrator)
    public $exclude_target_roles = array(1, 4, 5);

    function __construct()
    {
        parent::__construct();
    }

    // ================= PERIODE =================

    public function get_active_periode()
    {
        return $this->db->where('status', 'aktif')->get('pk_periode')->row();
    }

    public function get_periode_by_id($id_periode)
    {
        return $this->db->where('id_periode', (int)$id_periode)->get('pk_periode')->row();
    }

    public function get_periodes()
    {
        return $this->db->order_by('tahun', 'DESC')->order_by('id_periode', 'DESC')->get('pk_periode')->result();
    }

    public function insert_periode($data)
    {
        $this->db->insert('pk_periode', $data);
        return $this->db->insert_id();
    }

    public function update_periode($id_periode, $data)
    {
        $this->db->where('id_periode', (int)$id_periode)->update('pk_periode', $data);
    }

    public function delete_periode($id_periode)
    {
        $this->db->where('id_periode', (int)$id_periode)->delete('pk_periode');
    }

    public function set_active_periode($id_periode)
    {
        $this->db->where('status', 'aktif')->update('pk_periode', array('status' => 'selesai'));
        $this->db->where('id_periode', (int)$id_periode)->update('pk_periode', array('status' => 'aktif'));
    }

    public function set_active_periode_current_to_selesai($id_periode)
    {
        $this->db->where('id_periode', (int)$id_periode)->update('pk_periode', array('status' => 'selesai'));
    }

    // ================= UNIT =================

    public function get_units()
    {
        return $this->db->where('status', 'aktif')->order_by('nm_unit', 'ASC')->get('unit')->result();
    }

    public function get_unit($id_unit)
    {
        return $this->db->where('id_unit', (int)$id_unit)->get('unit')->row();
    }

    /**
     * Daftar unit yang dipimpin user login.
     * Penanda kepala unit tersimpan pada data pegawai (pegawai.is_kepala=1),
     * dicocokkan ke akun login via email users <-> pegawai.
     */
    public function get_kepala_units($user_id)
    {
        $peg = $this->get_pegawai_by_user($user_id);
        if (!$peg || !$peg->is_kepala || !$peg->id_unit) {
            return array();
        }
        return $this->db->where('id_unit', (int)$peg->id_unit)
            ->order_by('nm_unit', 'ASC')
            ->get('unit')->result();
    }

    public function is_kepala_unit($user_id, $id_unit)
    {
        $peg = $this->get_pegawai_by_user($user_id);
        if (!$peg) {
            return FALSE;
        }
        return (int)$peg->id_unit === (int)$id_unit && (int)$peg->is_kepala === 1;
    }

    /**
     * Bawahan yang bisa dinilai pada sebuah unit:
     * semua PEGAWAI yang terdaftar pada unit tsb (pegawai.id_unit), kecuali:
     * - yang sekaligus kepala unit pada unit tsb (pegawai.is_kepala=1),
     * - diri sendiri (kepala yang login),
     * - role manajemen/administrator (jika pegawai punya akun users).
     * id_dinilai memakai pegawai.id_pegawai.
     */
    public function get_subordinates($id_unit, $me = 0)
    {
        // resolved lebih dulu sebelum membangun query utama agar state query builder tidak tercampur
        $me_id_pegawai = 0;
        if ($me) {
            $me_peg = $this->get_pegawai_by_user($me);
            if ($me_peg) {
                $me_id_pegawai = (int) $me_peg->id_pegawai;
            }
        }

        $this->db->select("
                p.id_pegawai,
                p.nama,
                p.email,
                p.nip,
                p.jabatan,
                u.id AS id_user,
                r.name AS role_name
            ", FALSE);
        $this->db->from('pegawai p');
        $this->db->join('users u', 'u.email = p.email', 'left');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->where('p.id_unit', (int)$id_unit);
        $this->db->where('p.status', 'aktif');
        $this->db->where('p.is_kepala', 0);
        if ($me_id_pegawai) {
            $this->db->where('p.id_pegawai !=', $me_id_pegawai);
        }
        if (count($this->exclude_target_roles)) {
            $this->db->where('(u.role_id IS NULL OR u.role_id NOT IN (' . implode(',', array_map('intval', $this->exclude_target_roles)) . '))', NULL, FALSE);
        }
        $this->db->order_by('p.nama', 'ASC');
        return $this->db->get()->result();
    }

    public function get_pegawai($id_pegawai)
    {
        return $this->db->where('id_pegawai', (int)$id_pegawai)->get('pegawai')->row();
    }

    // id_pegawai milik pengguna (via pencocokan email users <-> pegawai)
    public function get_pegawai_by_user($user_id)
    {
        $user = $this->db->select('email')->where('id', (int)$user_id)->get('users')->row();
        if (!$user || !$user->email) {
            return NULL;
        }
        return $this->db->where('email', $user->email)
            ->order_by('is_kepala', 'DESC')
            ->order_by('id_pegawai', 'ASC')
            ->get('pegawai')->row();
    }

    // Daftar pegawai pada sebuah unit (lengkap dengan penanda kepala & role) untuk halaman admin
    public function get_pegawai_in_unit($id_unit)
    {
        $this->db->select('
                p.id_pegawai, p.nama, p.email, p.nip, p.jabatan, p.is_kepala,
                u.id AS id_user, r.name AS role_name
            ', FALSE);
        $this->db->from('pegawai p');
        $this->db->join('users u', 'u.email = p.email', 'left');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->where('p.id_unit', (int)$id_unit);
        $this->db->order_by('p.nama', 'ASC');
        return $this->db->get()->result();
    }

    public function set_kepala($id_pegawai, $is_kepala = 1)
    {
        $this->db->where('id_pegawai', (int)$id_pegawai);
        $this->db->update('pegawai', array('is_kepala' => $is_kepala ? 1 : 0));
    }

    public function reset_kepala_unit($id_unit)
    {
        $this->db->where('id_unit', (int)$id_unit)->update('pegawai', array('is_kepala' => 0));
    }

    // Nama kepala unit pada unit tsb (dari data pegawai)
    public function get_kepala_users_unit($id_unit)
    {
        $this->db->select('p.id_pegawai, p.nama, p.email');
        $this->db->from('pegawai p');
        $this->db->where('p.id_unit', (int)$id_unit);
        $this->db->where('p.is_kepala', 1);
        $this->db->where('p.status', 'aktif');
        return $this->db->get()->result();
    }

    // ================= KRITERIA =================

    public function get_kriteria_by_unit($id_unit, $active_only = TRUE)
    {
        $this->db->from('pk_kriteria');
        $this->db->where('id_unit', (int)$id_unit);
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        $this->db->order_by('kelompok', 'ASC');
        $this->db->order_by('urut', 'ASC');
        $this->db->order_by('id_kriteria', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Kriteria dikelompokkan: [ kelompok => [ items ... ] ] + total bobot.
     */
    public function get_kriteria_grouped($id_unit)
    {
        $rows   = $this->get_kriteria_by_unit($id_unit);
        $groups = array();
        $total  = 0;
        foreach ($rows as $r) {
            if (!isset($groups[$r->kelompok])) {
                $groups[$r->kelompok] = array('bobot' => 0, 'items' => array());
            }
            $groups[$r->kelompok]['bobot'] += (float)$r->bobot;
            $groups[$r->kelompok]['items'][] = $r;
            $total += (float)$r->bobot;
        }
        return array('groups' => $groups, 'total_bobot' => $total, 'unit' => $this->get_unit($id_unit));
    }

    public function total_bobot_unit($id_unit)
    {
        $this->db->select('COALESCE(SUM(bobot),0) AS tb', FALSE);
        $this->db->where('id_unit', (int)$id_unit);
        $this->db->where('is_active', 1);
        $r = $this->db->get('pk_kriteria')->row();
        return $r ? (float)$r->tb : 0;
    }

    public function get_kriteria_by_id($id_kriteria)
    {
        return $this->db->where('id_kriteria', (int)$id_kriteria)->get('pk_kriteria')->row();
    }

    public function insert_kriteria($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert('pk_kriteria', $data);
        return $this->db->insert_id();
    }

    public function update_kriteria($id_kriteria, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id_kriteria', (int)$id_kriteria)->update('pk_kriteria', $data);
    }

    public function delete_kriteria($id_kriteria)
    {
        $this->db->where('id_kriteria', (int)$id_kriteria)->delete('pk_kriteria');
    }

    // ================= GRADING =================

    public function get_gradings($id_unit)
    {
        return $this->db->where('id_unit', (int)$id_unit)->order_by('nilai_min', 'ASC')->get('pk_grading')->result();
    }

    public function get_predikat($id_unit, $nilai)
    {
        if ($nilai === NULL || $nilai === '') return '-';
        $this->db->where('id_unit', (int)$id_unit);
        $this->db->where('nilai_min <=', (float)$nilai);
        $this->db->where('nilai_max >=', (float)$nilai);
        $this->db->limit(1);
        $r = $this->db->get('pk_grading')->row();
        return $r ? $r->label : '-';
    }

    public function insert_grading($data)
    {
        $this->db->insert('pk_grading', $data);
        return $this->db->insert_id();
    }

    public function update_grading($id_grading, $data)
    {
        $this->db->where('id_grading', (int)$id_grading)->update('pk_grading', $data);
    }

    public function delete_grading($id_grading)
    {
        $this->db->where('id_grading', (int)$id_grading)->delete('pk_grading');
    }

    // ================= PENILAIAN =================

    public function get_penilaian($id_periode, $id_unit, $id_penilai, $id_dinilai)
    {
        $this->db->where('id_periode', (int)$id_periode);
        $this->db->where('id_unit', (int)$id_unit);
        $this->db->where('id_penilai', (int)$id_penilai);
        $this->db->where('id_dinilai', (int)$id_dinilai);
        return $this->db->get('pk_penilaian')->row();
    }

    public function get_penilaian_by_id($id_penilaian)
    {
        $this->db->select('pk_penilaian.*, unit.nm_unit, p.name AS nama_penilai, p.email AS email_penilai, d.nama AS nama_dinilai, d.email AS email_dinilai, d.jabatan AS jabatan_dinilai, d.is_kepala AS is_kepala_dinilai, pkp.nama AS nama_periode, pkp.tahun');
        $this->db->from('pk_penilaian');
        $this->db->join('unit', 'unit.id_unit = pk_penilaian.id_unit');
        $this->db->join('users p', 'p.id = pk_penilaian.id_penilai');
        $this->db->join('pegawai d', 'd.id_pegawai = pk_penilaian.id_dinilai');
        $this->db->join('pk_periode pkp', 'pkp.id_periode = pk_penilaian.id_periode');
        $this->db->where('pk_penilaian.id_penilaian', (int)$id_penilaian);
        return $this->db->get()->row();
    }

    public function get_skor_by_penilaian($id_penilaian)
    {
        $rows = $this->db->where('id_penilaian', (int)$id_penilaian)->get('pk_skor')->result();
        $map  = array();
        foreach ($rows as $r) {
            $map[$r->id_kriteria] = (int)$r->skor;
        }
        return $map;
    }

    /**
     * Hitung total nilai: SUM( (skor/5) * (bobot/total_bobot) * 100 )
     * $skor_map : [id_kriteria => skor]
     */
    public function hitung_total($id_unit, $skor_map)
    {
        $rows = $this->get_kriteria_by_unit($id_unit, TRUE);
        $tb   = 0;
        foreach ($rows as $r) $tb += (float)$r->bobot;
        if ($tb <= 0) return 0;
        $total = 0;
        foreach ($rows as $r) {
            $skor = isset($skor_map[$r->id_kriteria]) ? (int)$skor_map[$r->id_kriteria] : 0;
            $skor = max(0, min(5, $skor));
            $total += ($skor / 5) * ((float)$r->bobot / $tb) * 100;
        }
        return round($total, 2);
    }

    /**
     * Simpan (insert/update) penilaian beserta skor tiap kriteria.
     * $skor_map: [id_kriteria => skor]. Jika semua kriteria bernilai > 0 status = selesai.
     */
    public function save_penilaian($id_periode, $id_unit, $id_penilai, $id_dinilai, $skor_map, $catatan = NULL, $status = NULL)
    {
        $penilaian = $this->get_penilaian($id_periode, $id_unit, $id_penilai, $id_dinilai);

        $total = $this->hitung_total($id_unit, $skor_map);
        if ($status === NULL) {
            $rows = $this->get_kriteria_by_unit($id_unit, TRUE);
            $all  = count($rows) > 0;
            foreach ($rows as $r) {
                $s = isset($skor_map[$r->id_kriteria]) ? (int)$skor_map[$r->id_kriteria] : 0;
                if ($s <= 0) { $all = FALSE; break; }
            }
            $status = $all ? 'selesai' : 'draft';
        }
        $data = array(
            'id_periode'        => (int)$id_periode,
            'id_unit'           => (int)$id_unit,
            'id_penilai'        => (int)$id_penilai,
            'id_dinilai'        => (int)$id_dinilai,
            'tanggal_penilaian' => date('Y-m-d'),
            'total_nilai'       => $total,
            'status'            => $status,
            'catatan'           => $catatan,
            'updated_at'        => date('Y-m-d H:i:s'),
        );

        if ($penilaian) {
            $id_penilaian = (int)$penilaian->id_penilaian;
            $this->db->where('id_penilaian', $id_penilaian)->update('pk_penilaian', $data);
        } else {
            $data['created_at']    = date('Y-m-d H:i:s');
            $data['status']        = $status;
            $this->db->insert('pk_penilaian', $data);
            $id_penilaian = (int)$this->db->insert_id();
        }

        // simpan skor
        foreach ($skor_map as $id_kriteria => $skor) {
            $skor = max(0, min(5, (int)$skor));
            $existing = $this->db->where('id_penilaian', $id_penilaian)
                                 ->where('id_kriteria', (int)$id_kriteria)
                                 ->get('pk_skor')->row();
            if ($existing) {
                $this->db->where('id_skor', (int)$existing->id_skor)->update('pk_skor', array('skor' => $skor));
            } else {
                $this->db->insert('pk_skor', array(
                    'id_penilaian' => $id_penilaian,
                    'id_kriteria'  => (int)$id_kriteria,
                    'skor'         => $skor,
                ));
            }
        }

        return $id_penilaian;
    }

    public function delete_penilaian($id_penilaian)
    {
        $this->db->where('id_penilaian', (int)$id_penilaian)->delete('pk_skor');
        $this->db->where('id_penilaian', (int)$id_penilaian)->delete('pk_penilaian');
    }

    // Penilaian yang dilakukan oleh penilai di unit tsb pada periode tsb
    public function get_penilaian_by_unit($id_periode, $id_unit, $id_penilai = 0)
    {
        $this->db->select('pk_penilaian.*, d.nama AS nama_dinilai, d.email, d.jabatan');
        $this->db->from('pk_penilaian');
        $this->db->join('pegawai d', 'd.id_pegawai = pk_penilaian.id_dinilai');
        $this->db->where('pk_penilaian.id_periode', (int)$id_periode);
        $this->db->where('pk_penilaian.id_unit', (int)$id_unit);
        if ($id_penilai) {
            $this->db->where('pk_penilaian.id_penilai', (int)$id_penilai);
        }
        $this->db->order_by('pk_penilaian.status', 'ASC');
        $this->db->order_by('d.nama', 'ASC');
        return $this->db->get()->result();
    }

    // Penilaian yang menyasar pengguna login (sebagai pegawai yang dinilai)
    public function get_penilaian_saya($user_id)
    {
        $peg = $this->get_pegawai_by_user($user_id);
        if (!$peg) {
            return array();
        }
        $this->db->select('pk_penilaian.*, unit.nm_unit, pkp.nama AS nama_periode, pkp.tahun, p.name AS nama_penilai');
        $this->db->from('pk_penilaian');
        $this->db->join('unit', 'unit.id_unit = pk_penilaian.id_unit');
        $this->db->join('pk_periode pkp', 'pkp.id_periode = pk_penilaian.id_periode');
        $this->db->join('users p', 'p.id = pk_penilaian.id_penilai');
        $this->db->where('pk_penilaian.id_dinilai', (int)$peg->id_pegawai);
        $this->db->order_by('pk_penilaian.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // ================= REKAP ADMIN =================

    // Rekap per periode & unit: daftar dinilai + hasil
    public function get_rekap($id_periode, $id_unit = 0)
    {
        $this->db->select("
                pk_penilaian.id_penilaian, pk_penilaian.id_unit, pk_penilaian.id_dinilai,
                pk_penilaian.total_nilai, pk_penilaian.status, pk_penilaian.tanggal_penilaian,
                unit.nm_unit,
                d.nama AS nama_dinilai, d.email,
                p.name AS nama_penilai,
                d.jabatan
            ", FALSE);
        $this->db->from('pk_penilaian');
        $this->db->join('unit', 'unit.id_unit = pk_penilaian.id_unit');
        $this->db->join('pegawai d', 'd.id_pegawai = pk_penilaian.id_dinilai');
        $this->db->join('users p', 'p.id = pk_penilaian.id_penilai');
        $this->db->where('pk_penilaian.id_periode', (int)$id_periode);
        if ($id_unit) {
            $this->db->where('pk_penilaian.id_unit', (int)$id_unit);
        }
        $this->db->order_by('unit.nm_unit', 'ASC');
        $this->db->order_by('d.nama', 'ASC');
        return $this->db->get()->result();
    }

    public function get_rekap_grouped($id_periode, $id_unit = 0)
    {
        $rows  = $this->get_rekap($id_periode, $id_unit);
        $group = array();
        foreach ($rows as $r) {
            $key = $r->id_unit;
            if (!isset($group[$key])) {
                $group[$key] = array('nm_unit' => $r->nm_unit, 'items' => array());
            }
            $group[$key]['items'][] = $r;
        }
        return $group;
    }

    // ================= PENILAI KEPALA UNIT (Direktur/Kabid/Admin) =================

    // Apakah unit sudah punya kriteria aktif
    public function has_kriteria($id_unit)
    {
        return (int) $this->db->where('id_unit', (int)$id_unit)
            ->where('is_active', 1)
            ->count_all_results('pk_kriteria') > 0;
    }

    // Penilai tambahan yang didaftarkan admin (selain admin & direktur)
    public function is_registered_penilai_kepala($user_id)
    {
        return (int) $this->db->where('id_user', (int)$user_id)->count_all_results('pk_penilai_kepala') > 0;
    }

    public function get_penilai_kepala()
    {
        $this->db->select('pk.id_penilai_kepala, pk.id_user, pk.catatan, u.name, u.email, r.name AS role_name', FALSE);
        $this->db->from('pk_penilai_kepala pk');
        $this->db->join('users u', 'u.id = pk.id_user', 'left');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->order_by('u.name', 'ASC');
        return $this->db->get()->result();
    }

    public function add_penilai_kepala($id_user, $catatan = NULL)
    {
        $id_user = (int)$id_user;
        if (!$id_user) return FALSE;
        if ($this->is_registered_penilai_kepala($id_user)) return FALSE;
        $this->db->insert('pk_penilai_kepala', array(
            'id_user'    => $id_user,
            'catatan'    => $catatan,
            'created_at' => date('Y-m-d H:i:s'),
        ));
        return $this->db->insert_id();
    }

    public function delete_penilai_kepala($id_penilai_kepala)
    {
        $this->db->where('id_penilai_kepala', (int)$id_penilai_kepala)->delete('pk_penilai_kepala');
    }

    // Daftar user yang belum terdaftar sebagai penilai tambahan (untuk pilihan admin)
    public function get_users_for_penilai()
    {
        $this->db->select('u.id, u.name, u.email, r.name AS role_name', FALSE);
        $this->db->from('users u');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->where('u.is_active', 1);
        $this->db->where('NOT EXISTS (SELECT 1 FROM pk_penilai_kepala pk WHERE pk.id_user = u.id)', NULL, FALSE);
        $this->db->order_by('u.name', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Target penilaian kepala unit: seluruh pegawai berstatus KEPALA UNIT (is_kepala=1).
     * Kriteria mengikuti unit masing-masing (pk_kriteria.id_unit).
     * $exclude_id_pegawai dipakai agar penilai tidak menilai dirinya sendiri.
     */
    public function get_kepala_unit_targets($id_periode, $id_penilai, $exclude_id_pegawai = 0)
    {
        $this->db->select('p.id_pegawai, p.nama, p.email, p.nip, p.jabatan, p.id_unit, u.nm_unit');
        $this->db->from('pegawai p');
        $this->db->join('unit u', 'u.id_unit = p.id_unit', 'left');
        $this->db->where('p.is_kepala', 1);
        $this->db->where('p.status', 'aktif');
        $this->db->where('p.id_unit IS NOT NULL', NULL, FALSE);
        if ($exclude_id_pegawai) {
            $this->db->where('p.id_pegawai !=', (int)$exclude_id_pegawai);
        }
        $this->db->order_by('u.nm_unit', 'ASC');
        $this->db->order_by('p.nama', 'ASC');
        $rows = $this->db->get()->result();

        foreach ($rows as $r) {
            $r->penilaian = $id_periode
                ? $this->get_penilaian($id_periode, $r->id_unit, $id_penilai, $r->id_pegawai)
                : NULL;
            $r->has_kriteria = $this->has_kriteria($r->id_unit);
        }
        return $rows;
    }

    // ================= UTIL =================

    public function get_user($id_user)
    {
        return $this->db->where('id', (int)$id_user)->get('users')->row();
    }

    public function get_role_id()
    {
        return (int)$this->session->userdata('role_id');
    }

    // ================= RIWAYAT BINTANG (PELAPORAN) =================

    /**
     * Riwayat penilaian bintang milik pegawai (dari pelaporan_karyawan).
     * Hanya laporan berstatus DIVALIDASI yang dihitung.
     */
    public function get_bintang_history($id_pegawai = 0, $tahun = NULL)
    {
        $id_pegawai = (int) $id_pegawai;
        if (!$id_pegawai) {
            return array();
        }
        $this->db->select('id_laporan, bintang, alasan, status, created_at');
        $this->db->where('id_terlapor', $id_pegawai);
        $this->db->where('status', 'divalidasi');
        if ($tahun !== NULL && (int) $tahun > 0) {
            $this->db->where('YEAR(created_at)', (int) $tahun, FALSE);
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('pelaporan_karyawan')->result();
    }

    /**
     * Ringkasan bintang dari daftar laporan: jumlah, akumulasi, rata-rata.
     */
    public function summarize_bintang(array $rows)
    {
        $total = 0;
        $n = 0;
        foreach ($rows as $r) {
            $total += (int) $r->bintang;
            $n++;
        }
        return array(
            'count' => $n,
            'total' => $total,
            'avg'   => $n ? round($total / $n, 2) : 0,
        );
    }
}
/* End of file Penilaian_kinerja_model.php */