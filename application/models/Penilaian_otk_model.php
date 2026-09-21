<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Penilaian_otk_model
 * ---------------------------------------------------------
 * Modul Penilaian Kinerja OTK (One-To-Know / penilai -> yang dinilai).
 * - Mapping penilai -> yang dinilai tersimpan pada pk_otk.
 * - Penilai & yang dinilai memakai pegawai.id_pegawai (bukan users.id),
 *   karena mayoritas penilai/dinilai tidak punya akun users.
 * - Penilaian per periode pada pk_penilaian_otk, skor pada pk_skor_otk.
 * - Kriteria mengikuti unit pegawai yang dinilai (pk_kriteria.id_unit).
 * - Skala skor 1-5:
 *       total = SUM( (skor / 5) * (bobot / total_bobot_unit) * 100 )  -> maks 100
 */
class Penilaian_otk_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    // ================= ASSIGNMENT (PK_OTK) =================

    /**
     * Semua pasangan penilai -> dinilai untuk periode tertentu.
     * $id_periode dipakai untuk menggabungkan status penilaian.
     */
    public function get_assignments($id_periode = 0, $id_penilai = 0)
    {
        $this->db->select("
                o.id_otk, o.id_penilai, o.id_dinilai,
                p.nama AS nama_penilai, p.jabatan AS jabatan_penilai, up.nm_unit AS unit_penilai,
                d.nama AS nama_dinilai, d.jabatan AS jabatan_dinilai, ud.nm_unit AS unit_dinilai, ud.id_unit AS id_unit_dinilai,
                po.id_penilaian, po.status, po.total_nilai, po.tanggal_penilaian
            ", FALSE);
        $this->db->from('pk_otk o');
        $this->db->join('pegawai p', 'p.id_pegawai = o.id_penilai');
        $this->db->join('unit up', 'up.id_unit = p.id_unit', 'left');
        $this->db->join('pegawai d', 'd.id_pegawai = o.id_dinilai');
        $this->db->join('unit ud', 'ud.id_unit = d.id_unit', 'left');
        if ($id_periode) {
            $this->db->join('pk_penilaian_otk po', 'po.id_penilai = o.id_penilai AND po.id_dinilai = o.id_dinilai AND po.id_periode = ' . (int)$id_periode, 'left');
        } else {
            $this->db->join('pk_penilaian_otk po', 'po.id_penilai = o.id_penilai AND po.id_dinilai = o.id_dinilai', 'left');
        }
        if ($id_penilai) {
            $this->db->where('o.id_penilai', (int)$id_penilai);
        }
        $this->db->order_by('p.nama', 'ASC');
        $this->db->order_by('d.nama', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Daftar penilai unik (plus jumlah dinilai & progres).
     * $id_periode dipakai untuk menghitung progres per periode.
     */
    public function get_penilai_list($id_periode = 0)
    {
        $this->db->select("
                p.id_pegawai, p.nama, p.jabatan, u.nm_unit,
                COUNT(o.id_otk) AS jml_dinilai,
                SUM(CASE WHEN po.status = 'selesai' THEN 1 ELSE 0 END) AS jml_selesai
            ", FALSE);
        $this->db->from('pk_otk o');
        $this->db->join('pegawai p', 'p.id_pegawai = o.id_penilai');
        $this->db->join('unit u', 'u.id_unit = p.id_unit', 'left');
        if ($id_periode) {
            $this->db->join('pk_penilaian_otk po', 'po.id_penilai = o.id_penilai AND po.id_dinilai = o.id_dinilai AND po.id_periode = ' . (int)$id_periode, 'left');
        } else {
            $this->db->join('pk_penilaian_otk po', 'po.id_penilai = o.id_penilai AND po.id_dinilai = o.id_dinilai', 'left');
        }
        $this->db->group_by('p.id_pegawai, p.nama, p.jabatan, u.nm_unit');
        $this->db->order_by('p.nama', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Pasangan (id_penilai, id_dinilai) yang sudah ter-assign pada pk_otk.
     * Return: array[ 'penilai,dinilai' => 1 ].
     */
    public function get_assignment_map($id_penilai = 0)
    {
        $this->db->select('id_penilai, id_dinilai');
        if ($id_penilai) {
            $this->db->where('id_penilai', (int)$id_penilai);
        }
        $rows = $this->db->get('pk_otk')->result();
        $map  = array();
        foreach ($rows as $r) {
            $map[(int)$r->id_penilai . ',' . (int)$r->id_dinilai] = 1;
        }
        return $map;
    }

    // Semua pegawai aktif untuk opsi penilai & yang dinilai
    public function get_all_pegawai()
    {
        $this->db->select('p.id_pegawai, p.nama, p.jabatan, p.id_unit, u.nm_unit', FALSE);
        $this->db->from('pegawai p');
        $this->db->join('unit u', 'u.id_unit = p.id_unit', 'left');
        $this->db->where('p.status', 'aktif');
        $this->db->order_by('p.nama', 'ASC');
        return $this->db->get()->result();
    }

    public function count_assignments()
    {
        return (int)$this->db->count_all('pk_otk');
    }

    public function count_penilai()
    {
        $this->db->select('COUNT(DISTINCT id_penilai) AS n', FALSE);
        $r = $this->db->get('pk_otk')->row();
        return $r ? (int)$r->n : 0;
    }

    /**
     * Cabut semua assignment lama milik penilai lalu pasang yang baru.
     * Dinilai yang masih punya penilaian tidak dihapus datanya.
     */
    public function replace_assignments($id_penilai, array $dinilai_ids)
    {
        $id_penilai = (int)$id_penilai;
        if (!$id_penilai) {
            return FALSE;
        }
        $this->db->where('id_penilai', $id_penilai)->delete('pk_otk');
        $now = date('Y-m-d H:i:s');
        foreach (array_unique(array_map('intval', $dinilai_ids)) as $id_dinilai) {
            if (!$id_dinilai || $id_dinilai === $id_penilai) {
                continue;
            }
            $exists = (int)$this->db->where('id_penilai', $id_penilai)
                ->where('id_dinilai', $id_dinilai)
                ->count_all_results('pk_otk');
            if (!$exists) {
                $this->db->insert('pk_otk', array(
                    'id_penilai' => $id_penilai,
                    'id_dinilai' => $id_dinilai,
                    'created_at' => $now,
                    'updated_at' => $now,
                ));
            }
        }
        return TRUE;
    }

    public function is_assigned($id_penilai, $id_dinilai)
    {
        return (int)$this->db->where('id_penilai', (int)$id_penilai)
            ->where('id_dinilai', (int)$id_dinilai)
            ->count_all_results('pk_otk') > 0;
    }

    // ================= PENILAIAN (PK_PENILAIAN_OTK) =================

    public function get_penilaian($id_periode, $id_penilai, $id_dinilai)
    {
        $this->db->where('id_periode', (int)$id_periode);
        $this->db->where('id_penilai', (int)$id_penilai);
        $this->db->where('id_dinilai', (int)$id_dinilai);
        return $this->db->get('pk_penilaian_otk')->row();
    }

    public function get_penilaian_by_id($id_penilaian)
    {
        $this->db->select("
                po.*, pkp.nama AS nama_periode, pkp.tahun,
                p.nama AS nama_penilai, p.jabatan AS jabatan_penilai,
                d.nama AS nama_dinilai, d.jabatan AS jabatan_dinilai,
                d.id_unit AS id_unit_dinilai, u.nm_unit AS nm_unit
            ", FALSE);
        $this->db->from('pk_penilaian_otk po');
        $this->db->join('pk_periode pkp', 'pkp.id_periode = po.id_periode');
        $this->db->join('pegawai p', 'p.id_pegawai = po.id_penilai');
        $this->db->join('pegawai d', 'd.id_pegawai = po.id_dinilai');
        $this->db->join('unit u', 'u.id_unit = d.id_unit', 'left');
        $this->db->where('po.id_penilaian', (int)$id_penilaian);
        return $this->db->get()->row();
    }

    public function get_skor_by_penilaian($id_penilaian)
    {
        $rows = $this->db->where('id_penilaian', (int)$id_penilaian)->get('pk_skor_otk')->result();
        $map  = array();
        foreach ($rows as $r) {
            $map[$r->id_kriteria] = (int)$r->skor;
        }
        return $map;
    }

    /**
     * Simpan (insert/update) penilaian OTK beserta skor tiap kriteria.
     * $skor_map: [id_kriteria => skor]. -1 artinya "bukan kriteria yang harus dinilai"
     * (kriteria unit dinilai); digunakan untuk menghapus skor yang sudah tidak relevan.
     */
    public function save_penilaian($id_periode, $id_unit, $id_penilai, $id_dinilai, $skor_map, $catatan = NULL, $status = NULL)
    {
        // hitung total memakai kriteria unit yang dinilai
        $total = 0;
        $this->load->model('Penilaian_kinerja_model');
        $kriteria_rows = $this->Penilaian_kinerja_model->get_kriteria_by_unit($id_unit, TRUE);
        $tb = 0;
        foreach ($kriteria_rows as $r) $tb += (float)$r->bobot;
        if ($tb > 0) {
            foreach ($kriteria_rows as $r) {
                $skor = isset($skor_map[$r->id_kriteria]) ? (int)$skor_map[$r->id_kriteria] : 0;
                $skor = max(0, min(5, $skor));
                $total += ($skor / 5) * ((float)$r->bobot / $tb) * 100;
            }
        }
        $total = round($total, 2);

        $penilaian = $this->get_penilaian($id_periode, $id_penilai, $id_dinilai);

        if ($status === NULL) {
            $all = count($kriteria_rows) > 0;
            foreach ($kriteria_rows as $r) {
                $s = isset($skor_map[$r->id_kriteria]) ? (int)$skor_map[$r->id_kriteria] : 0;
                if ($s <= 0) { $all = FALSE; break; }
            }
            $status = $all ? 'selesai' : 'draft';
        }

        $data = array(
            'id_periode'        => (int)$id_periode,
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
            $this->db->where('id_penilaian', $id_penilaian)->update('pk_penilaian_otk', $data);
        } else {
            $data['created_at']    = date('Y-m-d H:i:s');
            $this->db->insert('pk_penilaian_otk', $data);
            $id_penilaian = (int)$this->db->insert_id();
        }

        // simpan skor (hapus dulu semua lalu tulis ulang agar konsisten)
        $this->db->where('id_penilaian', $id_penilaian)->delete('pk_skor_otk');
        foreach ($kriteria_rows as $r) {
            $skor = isset($skor_map[$r->id_kriteria]) ? (int)$skor_map[$r->id_kriteria] : 0;
            $skor = max(0, min(5, $skor));
            $this->db->insert('pk_skor_otk', array(
                'id_penilaian' => $id_penilaian,
                'id_kriteria'  => (int)$r->id_kriteria,
                'skor'         => $skor,
            ));
        }

        return $id_penilaian;
    }

    public function delete_penilaian($id_penilaian)
    {
        $this->db->where('id_penilaian', (int)$id_penilaian)->delete('pk_skor_otk');
        $this->db->where('id_penilaian', (int)$id_penilaian)->delete('pk_penilaian_otk');
    }

    // Penilaian OTK yang menyasar pengguna login (sebagai yang dinilai)
    public function get_penilaian_saya($id_pegawai)
    {
        $this->db->select("
                po.*, pkp.nama AS nama_periode, pkp.tahun,
                p.nama AS nama_penilai, p.jabatan AS jabatan_penilai,
                d.id_unit AS id_unit_dinilai, d.jabatan AS jabatan_dinilai,
                u.nm_unit
            ", FALSE);
        $this->db->from('pk_penilaian_otk po');
        $this->db->join('pk_periode pkp', 'pkp.id_periode = po.id_periode');
        $this->db->join('pegawai p', 'p.id_pegawai = po.id_penilai');
        $this->db->join('pegawai d', 'd.id_pegawai = po.id_dinilai');
        $this->db->join('unit u', 'u.id_unit = p.id_unit', 'left');
        $this->db->where('po.id_dinilai', (int)$id_pegawai);
        $this->db->order_by('po.created_at', 'DESC');
        return $this->db->get()->result();
    }

    // ================= REKAP =================

    /**
     * Rekap per penilai: baris per (penilai, dinilai) + nilai + status.
     */
    public function get_rekap($id_periode)
    {
        $this->db->select("
                o.id_penilai, p.nama AS nama_penilai, p.jabatan AS jabatan_penilai, up.nm_unit AS unit_penilai,
                o.id_dinilai, d.nama AS nama_dinilai, d.jabatan AS jabatan_dinilai, ud.nm_unit AS unit_dinilai, ud.id_unit AS id_unit_dinilai,
                po.id_penilaian, po.status, po.total_nilai, po.tanggal_penilaian, po.catatan
            ", FALSE);
        $this->db->from('pk_otk o');
        $this->db->join('pegawai p', 'p.id_pegawai = o.id_penilai');
        $this->db->join('unit up', 'up.id_unit = p.id_unit', 'left');
        $this->db->join('pegawai d', 'd.id_pegawai = o.id_dinilai');
        $this->db->join('unit ud', 'ud.id_unit = d.id_unit', 'left');
        $this->db->join('pk_penilaian_otk po', 'po.id_penilai = o.id_penilai AND po.id_dinilai = o.id_dinilai AND po.id_periode = ' . (int)$id_periode, 'left');
        $this->db->order_by('p.nama', 'ASC');
        $this->db->order_by('d.nama', 'ASC');
        return $this->db->get()->result();
    }

    public function get_rekap_grouped($id_periode)
    {
        $rows = $this->get_rekap($id_periode);
        $group = array();
        foreach ($rows as $r) {
            $key = (int)$r->id_penilai;
            if (!isset($group[$key])) {
                $group[$key] = array(
                    'nama'   => $r->nama_penilai,
                    'jabatan' => $r->jabatan_penilai,
                    'unit'   => $r->unit_penilai,
                    'items'  => array(),
                );
            }
            $group[$key]['items'][] = $r;
        }
        return $group;
    }
}
/* End of file Penilaian_otk_model.php */