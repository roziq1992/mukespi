<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Dokumen_unit_model extends CI_Model
{
    public $table = 'dokumen_unit';
    public $id = 'id_dokumen';
    public $order = 'DESC';
    function __construct()
    {
        parent::__construct();
    }

    // helper: select + join ke unit & jenis_dokumen
    // supaya nm_unit, nm_jenis_dokumen, dan id_unit_doc_ref ikut terambil
    private function _join()
    {
        // FIX: tambahkan jd.id_unit_doc_ref supaya tidak "Undefined property"
        // saat controller membaca $row->id_unit_doc_ref (mis. untuk filter
        // dropdown jenis dokumen berdasarkan unit_dokumen di form edit).
        $this->db->select('dokumen_unit.*, unit.nm_unit, jd.nm_jenis_dokumen, jd.id_unit_doc_ref');
        $this->db->from($this->table);
        $this->db->join('unit', 'unit.id_unit = dokumen_unit.id_unit', 'left');
        $this->db->join('jenis_dokumen jd', 'jd.id_jenis_dokumen = dokumen_unit.id_jenis_dokumen', 'left');
    }

    /**
     * ================== BARU (helper untuk dashboard) ==================
     * Terapkan batasan allowed_units ke query yang sedang aktif.
     * $column diisi 'dokumen_unit.id_unit' kalau query pakai join/alias,
     * atau 'id_unit' kalau query langsung ke tabel dokumen_unit tanpa alias.
     */
    private function _apply_allowed_units($allowed_units, $column = 'id_unit')
    {
        if (is_array($allowed_units)) {
            if (empty($allowed_units)) {
                $this->db->where('1 = 0', NULL, FALSE);
            } else {
                $this->db->where_in($column, $allowed_units);
            }
        }
    }

    // get all
    function get_all()
    {
        $this->_join();
        $this->db->order_by($this->id, $this->order);
        return $this->db->get()->result();
    }
    // get data by id
    function get_by_id($id)
    {
        $this->_join();
        $this->db->where('dokumen_unit.' . $this->id, $id);
        return $this->db->get()->row();
    }

// get total rows
function total_rows($q = NULL, $id_unit = NULL, $id_jenis_dokumen = NULL, $allowed_units = NULL, $status_berlaku = 'berlaku', $id_unit_doc_ref = NULL) {
    $this->_join();

    if ($q <> '') {
        $this->db->group_start();
        $this->db->like('dokumen_unit.judul_dokumen', $q);
        $this->db->or_like('dokumen_unit.keterangan', $q);
        $this->db->or_like('dokumen_unit.nama_file', $q);
        $this->db->or_like('dokumen_unit.tipe_file', $q);
        $this->db->or_like('dokumen_unit.status', $q);
        $this->db->or_like('dokumen_unit.tgl_berlaku', $q);
        $this->db->or_like('dokumen_unit.tgl_kadaluarsa', $q);
        $this->db->or_like('unit.nm_unit', $q);
        $this->db->or_like('jd.nm_jenis_dokumen', $q);
        $this->db->group_end();
    }

    if ($id_unit <> '') {
        $this->db->where('dokumen_unit.id_unit', $id_unit);
    } elseif (is_array($allowed_units)) {
        if (empty($allowed_units)) {
            $this->db->where('1 = 0', NULL, FALSE);
        } else {
            $this->db->where_in('dokumen_unit.id_unit', $allowed_units);
        }
    }

    // BARU: filter berdasarkan Unit Dokumen (jd.id_unit_doc_ref)
    if ($id_jenis_dokumen <> '') {
        $this->db->where('dokumen_unit.id_jenis_dokumen', $id_jenis_dokumen);
    } elseif ($id_unit_doc_ref <> '') {
        $this->db->where('jd.id_unit_doc_ref', $id_unit_doc_ref);
    }

    $this->_apply_status_berlaku_filter($status_berlaku);

    return $this->db->get()->num_rows();
}

function get_limit_data($limit, $start = 0, $q = NULL, $id_unit = NULL, $id_jenis_dokumen = NULL, $allowed_units = NULL, $status_berlaku = 'berlaku', $id_unit_doc_ref = NULL) {
    $this->_join();

    if ($q <> '') {
        $this->db->group_start();
        $this->db->like('dokumen_unit.judul_dokumen', $q);
        $this->db->or_like('dokumen_unit.keterangan', $q);
        $this->db->or_like('dokumen_unit.nama_file', $q);
        $this->db->or_like('dokumen_unit.tipe_file', $q);
        $this->db->or_like('dokumen_unit.status', $q);
        $this->db->or_like('dokumen_unit.tgl_berlaku', $q);
        $this->db->or_like('dokumen_unit.tgl_kadaluarsa', $q);
        $this->db->or_like('unit.nm_unit', $q);
        $this->db->or_like('jd.nm_jenis_dokumen', $q);
        $this->db->group_end();
    }

    if ($id_unit <> '') {
        $this->db->where('dokumen_unit.id_unit', $id_unit);
    } elseif (is_array($allowed_units)) {
        if (empty($allowed_units)) {
            $this->db->where('1 = 0', NULL, FALSE);
        } else {
            $this->db->where_in('dokumen_unit.id_unit', $allowed_units);
        }
    }

    // BARU: filter berdasarkan Unit Dokumen (jd.id_unit_doc_ref)
    if ($id_jenis_dokumen <> '') {
        $this->db->where('dokumen_unit.id_jenis_dokumen', $id_jenis_dokumen);
    } elseif ($id_unit_doc_ref <> '') {
        $this->db->where('jd.id_unit_doc_ref', $id_unit_doc_ref);
    }

    $this->_apply_status_berlaku_filter($status_berlaku);

    $this->db->order_by($this->id, $this->order);
    $this->db->limit($limit, $start);
    return $this->db->get()->result();
}

    /**
     * ================== BARU ==================
     * Terapkan filter status keberlakuan dokumen ke query builder yang sedang aktif.
     * 'berlaku'       => status aktif DAN belum lewat tanggal kadaluarsa (atau tanpa tgl kadaluarsa)
     * 'tidak_berlaku' => sudah lewat tanggal kadaluarsa, ATAU status memang 'kadaluarsa'
     * '' / lainnya    => tidak difilter (tampilkan semua)
     */
    private function _apply_status_berlaku_filter($status_berlaku)
    {
        $today = date('Y-m-d');

        if ($status_berlaku == 'berlaku') {
            $this->db->where('dokumen_unit.status', 'aktif');
            $this->db->group_start();
            $this->db->where('dokumen_unit.tgl_kadaluarsa IS NULL', NULL, FALSE);
            $this->db->or_where('dokumen_unit.tgl_kadaluarsa >=', $today);
            $this->db->group_end();
        } elseif ($status_berlaku == 'tidak_berlaku') {
            $this->db->group_start();
            $this->db->where('dokumen_unit.status', 'kadaluarsa');
            $this->db->or_group_start();
            $this->db->where('dokumen_unit.tgl_kadaluarsa IS NOT NULL', NULL, FALSE);
            $this->db->where('dokumen_unit.tgl_kadaluarsa <', $today);
            $this->db->group_end();
            $this->db->group_end();
        }
        // status_berlaku == '' (Semua) -> tidak ada filter tambahan
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }
    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

    // dropdown pilihan Unit (untuk form create/update)
    public function unit()
    {
        return $this->db->order_by('nm_unit', 'ASC')->get('unit')->result();
    }

    // dropdown pilihan Jenis Dokumen (untuk form create/update)
    public function jenis_dokumen()
    {
        return $this->db->order_by('nm_jenis_dokumen', 'ASC')->get('jenis_dokumen')->result();
    }

    public function search_unit($q = '', $limit = 20, $offset = 0, $allowed_units = NULL)
    {
        $this->db->from('unit');
        if (!empty($q)) {
            $this->db->like('nm_unit', $q);
        }
        if (is_array($allowed_units)) {
            if (empty($allowed_units)) {
                $this->db->where('1 = 0', NULL, FALSE);
            } else {
                $this->db->where_in('id_unit', $allowed_units);
            }
        }
        $count_all = $this->db->count_all_results('', FALSE);
        $this->db->order_by('nm_unit', 'ASC');
        $this->db->limit($limit, $offset);
        $data = $this->db->get()->result();

        return array(
            'data' => $data,
            'more' => ($offset + count($data)) < $count_all
        );
    }

    public function search_jenis_dokumen($q = '', $limit = 20, $offset = 0)
    {
        $this->db->from('jenis_dokumen');
        if (!empty($q)) {
            $this->db->like('nm_jenis_dokumen', $q);
        }
        $count_all = $this->db->count_all_results('', FALSE);
        $this->db->order_by('nm_jenis_dokumen', 'ASC');
        $this->db->limit($limit, $offset);
        $data = $this->db->get()->result();

        return array(
            'data' => $data,
            'more' => ($offset + count($data)) < $count_all
        );
    }

    /**
     * ================== BARU (untuk Dashboard) ==================
     * Ringkasan angka: total dokumen, per status, dan yang akan kadaluarsa
     * dalam $days hari ke depan (default 30 hari, hanya status aktif).
     */
    public function get_stats($allowed_units = NULL, $days = 30)
    {
        $this->db->select("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'aktif' THEN 1 ELSE 0 END) as aktif,
            SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft,
            SUM(CASE WHEN status = 'arsip' THEN 1 ELSE 0 END) as arsip,
            SUM(CASE WHEN status = 'kadaluarsa' THEN 1 ELSE 0 END) as kadaluarsa
        ", FALSE);
        $this->db->from($this->table);
        $this->_apply_allowed_units($allowed_units, 'id_unit');
        $row = $this->db->get()->row();

        $today = date('Y-m-d');
        $limit_date = date('Y-m-d', strtotime('+' . intval($days) . ' days'));

        $this->db->select('COUNT(*) as jml', FALSE);
        $this->db->from($this->table);
        $this->db->where('status', 'aktif');
        $this->db->where('tgl_kadaluarsa IS NOT NULL', NULL, FALSE);
        $this->db->where('tgl_kadaluarsa >=', $today);
        $this->db->where('tgl_kadaluarsa <=', $limit_date);
        $this->_apply_allowed_units($allowed_units, 'id_unit');
        $expiring = $this->db->get()->row();

        return array(
            'total'          => intval($row->total),
            'aktif'          => intval($row->aktif),
            'draft'          => intval($row->draft),
            'arsip'          => intval($row->arsip),
            'kadaluarsa'     => intval($row->kadaluarsa),
            'akan_kadaluarsa'=> intval($expiring->jml),
        );
    }

    /**
     * ================== BARU (untuk Dashboard) ==================
     * Jumlah dokumen per Unit, diurutkan dari yang paling banyak.
     */
    public function get_count_by_unit($allowed_units = NULL)
    {
        $this->db->select('unit.nm_unit as label, COUNT(dokumen_unit.id_dokumen) as jumlah', FALSE);
        $this->db->from($this->table);
        $this->db->join('unit', 'unit.id_unit = dokumen_unit.id_unit', 'left');
        $this->_apply_allowed_units($allowed_units, 'dokumen_unit.id_unit');
        $this->db->group_by('unit.id_unit, unit.nm_unit');
        $this->db->order_by('jumlah', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * ================== BARU (untuk Dashboard) ==================
     * Jumlah dokumen per Jenis Dokumen, diurutkan dari yang paling banyak.
     */
    public function get_count_by_jenis($allowed_units = NULL)
    {
        $this->db->select('jd.nm_jenis_dokumen as label, COUNT(dokumen_unit.id_dokumen) as jumlah', FALSE);
        $this->db->from($this->table);
        $this->db->join('jenis_dokumen jd', 'jd.id_jenis_dokumen = dokumen_unit.id_jenis_dokumen', 'left');
        $this->_apply_allowed_units($allowed_units, 'dokumen_unit.id_unit');
        $this->db->group_by('jd.id_jenis_dokumen, jd.nm_jenis_dokumen');
        $this->db->order_by('jumlah', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * ================== BARU (untuk Dashboard) ==================
     * Daftar dokumen aktif yang akan kadaluarsa dalam $days hari ke depan,
     * diurutkan dari yang paling dekat kadaluarsanya.
     */
    public function get_expiring_soon($allowed_units = NULL, $days = 30, $limit = 10)
    {
        $today = date('Y-m-d');
        $limit_date = date('Y-m-d', strtotime('+' . intval($days) . ' days'));

        $this->_join();
        $this->db->where('dokumen_unit.status', 'aktif');
        $this->db->where('dokumen_unit.tgl_kadaluarsa IS NOT NULL', NULL, FALSE);
        $this->db->where('dokumen_unit.tgl_kadaluarsa >=', $today);
        $this->db->where('dokumen_unit.tgl_kadaluarsa <=', $limit_date);
        $this->_apply_allowed_units($allowed_units, 'dokumen_unit.id_unit');
        $this->db->order_by('dokumen_unit.tgl_kadaluarsa', 'ASC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
    // Semua unit_dokumen untuk dropdown
public function unit_dokumen_all()
{
    return $this->db
        ->order_by('nm_unit_doc', 'ASC')
        ->get('unit_dokumen')
        ->result();
}
// BARU: daftar Jenis Dokumen yang aktif untuk 1 Unit Dokumen tertentu
// dipakai untuk isi dropdown filter Jenis Dokumen di halaman list
public function jenis_dokumen_by_unit_doc($id_unit_doc_ref)
{
    if (empty($id_unit_doc_ref)) {
        return array();
    }
    $this->db->where('id_unit_doc_ref', $id_unit_doc_ref);
    $this->db->where('is_active', 1);
    $this->db->order_by('nm_jenis_dokumen', 'ASC');
    return $this->db->get('jenis_dokumen')->result();
}

// Ambil satu jenis_dokumen by id (untuk render opsi terpilih di mode edit)
public function get_jenis_dokumen_by_id($id)
{
    return $this->db
        ->where('id_jenis_dokumen', $id)
        ->get('jenis_dokumen')
        ->row();
}

// Live search jenis dokumen, difilter by id_unit_doc_ref
public function search_jenis_dokumen_by_unit($q = '', $limit = 20, $offset = 0, $id_unit_doc_ref = 0)
{
    if (empty($id_unit_doc_ref)) {
        return ['data' => [], 'more' => false];
    }

    $this->db->from('jenis_dokumen');
    $this->db->where('id_unit_doc_ref', $id_unit_doc_ref);
    $this->db->where('is_active', 1);

    if (!empty($q)) {
        $this->db->like('nm_jenis_dokumen', $q);
    }

    $count_all = $this->db->count_all_results('', FALSE);

    $this->db->order_by('nm_jenis_dokumen', 'ASC');
    $this->db->limit($limit, $offset);
    $data = $this->db->get()->result();

    return [
        'data' => $data,
        'more' => ($offset + count($data)) < $count_all,
    ];
}
}
/* End of file Dokumen_unit_model.php */
/* Location: ./application/models/Dokumen_unit_model.php */