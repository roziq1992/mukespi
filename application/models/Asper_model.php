<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asper_model extends CI_Model
{
    public $table = 'serah_terima_asper';
    public $id = 'id_asper';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        $this->db->order_by('hari_tanggal', $this->order);
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->like('unit_divisi', $q);
        $this->db->or_like('shift', $q);
        $this->db->or_like('ke_shift', $q);
        $this->db->or_like('hari_tanggal', $q);
        $this->db->or_like('permasalahan', $q);
        $this->db->or_like('catatan_lain', $q);
        $this->db->or_like('yang_mengoperkan', $q);
        $this->db->or_like('yang_menerima_operan', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by('hari_tanggal', $this->order);
        $this->db->order_by($this->id, $this->order);
        $this->db->like('unit_divisi', $q);
        $this->db->or_like('shift', $q);
        $this->db->or_like('ke_shift', $q);
        $this->db->or_like('hari_tanggal', $q);
        $this->db->or_like('permasalahan', $q);
        $this->db->or_like('catatan_lain', $q);
        $this->db->or_like('yang_mengoperkan', $q);
        $this->db->or_like('yang_menerima_operan', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
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
    
// =============================================
// TAMBAHKAN method-method ini ke dalam class Asper_model
// File: application/models/Asper_model.php
// =============================================

// -----------------------------------------------
// 1. Statistik ringkas
// -----------------------------------------------
public function get_dashboard_stats($bulan, $tahun)
{
    $this->db->where('MONTH(hari_tanggal)', $bulan);
    $this->db->where('YEAR(hari_tanggal)',  $tahun);
    $total = $this->db->count_all_results('serah_terima_asper');

    $this->db->select('shift, COUNT(*) as jml');
    $this->db->where('MONTH(hari_tanggal)', $bulan);
    $this->db->where('YEAR(hari_tanggal)',  $tahun);
    $this->db->group_by('shift');
    $rows = $this->db->get('serah_terima_asper')->result();

    $shift_map = array('Pagi' => 0, 'Siang' => 0, 'Malam' => 0);
    foreach ($rows as $r) {
        $shift_map[$r->shift] = (int)$r->jml;
    }

    return array('total' => $total, 'shifts' => $shift_map);
}

// -----------------------------------------------
// 2. Tren pasien harian (ambil semua baris, parse TEXT)
// -----------------------------------------------
public function get_pasien_harian($bulan, $tahun)
{
    $this->db->select('hari_tanggal, jumlah_pasien_ranap');
    $this->db->where('MONTH(hari_tanggal)', $bulan);
    $this->db->where('YEAR(hari_tanggal)',  $tahun);
    $this->db->order_by('hari_tanggal', 'ASC');
    $rows = $this->db->get('serah_terima_asper')->result();

    $bucket = array();
    foreach ($rows as $r) {
        $tgl   = date('d', strtotime($r->hari_tanggal));
        $total = $this->_sum_text_field($r->jumlah_pasien_ranap);
        if (!isset($bucket[$tgl])) $bucket[$tgl] = array('total' => 0, 'count' => 0);
        $bucket[$tgl]['total'] += $total;
        $bucket[$tgl]['count']++;
    }

    $result = array();
    foreach ($bucket as $tgl => $v) {
        $result[] = array(
            'tgl'  => $tgl,
            'rata' => $v['count'] > 0 ? round($v['total'] / $v['count'], 1) : 0,
        );
    }
    return $result;
}

// -----------------------------------------------
// 3. Jumlah entri per shift
// -----------------------------------------------
public function get_by_shift($bulan, $tahun)
{
    $this->db->select('shift, COUNT(*) as jml');
    $this->db->where('MONTH(hari_tanggal)', $bulan);
    $this->db->where('YEAR(hari_tanggal)',  $tahun);
    $this->db->group_by('shift');
    return $this->db->get('serah_terima_asper')->result();
}

// -----------------------------------------------
// 4. Total MRS/KRS per zona (parse TEXT)
// -----------------------------------------------
public function get_zona_summary($bulan, $tahun)
{
    $this->db->select('kamar_zona_a, kamar_zona_b, kamar_zona_c, kamar_zona_d, kamar_zona_e');
    $this->db->where('MONTH(hari_tanggal)', $bulan);
    $this->db->where('YEAR(hari_tanggal)',  $tahun);
    $rows = $this->db->get('serah_terima_asper')->result();

    $zonas = array('a' => 0, 'b' => 0, 'c' => 0, 'd' => 0, 'e' => 0);
    foreach ($rows as $r) {
        foreach (array_keys($zonas) as $z) {
            $col = 'kamar_zona_' . $z;
            $zonas[$z] += $this->_sum_text_field($r->$col);
        }
    }
    return $zonas;
}

// -----------------------------------------------
// 5. Total verbed per zona (parse TEXT)
// -----------------------------------------------
public function get_verbed_summary($bulan, $tahun)
{
    $this->db->select('verbed_zona_a, verbed_zona_b, verbed_zona_c, verbed_zona_d, verbed_zona_e');
    $this->db->where('MONTH(hari_tanggal)', $bulan);
    $this->db->where('YEAR(hari_tanggal)',  $tahun);
    $rows = $this->db->get('serah_terima_asper')->result();

    $zonas = array('a' => 0, 'b' => 0, 'c' => 0, 'd' => 0, 'e' => 0);
    foreach ($rows as $r) {
        foreach (array_keys($zonas) as $z) {
            $col = 'verbed_zona_' . $z;
            $zonas[$z] += $this->_sum_text_field($r->$col);
        }
    }
    return $zonas;
}

// -----------------------------------------------
// 6. Jumlah entri per unit_divisi
// -----------------------------------------------
public function get_by_unit($bulan, $tahun)
{
    $this->db->select('unit_divisi, COUNT(*) as jml');
    $this->db->where('MONTH(hari_tanggal)', $bulan);
    $this->db->where('YEAR(hari_tanggal)',  $tahun);
    $this->db->group_by('unit_divisi');
    return $this->db->get('serah_terima_asper')->result();
}

// -----------------------------------------------
// 7. Rata-rata pasien per ruang (NS1, NS2, HCU, dll)
// -----------------------------------------------
public function get_pasien_per_ruang($bulan, $tahun)
{
    $this->db->select('jumlah_pasien_ranap');
    $this->db->where('MONTH(hari_tanggal)', $bulan);
    $this->db->where('YEAR(hari_tanggal)',  $tahun);
    $rows = $this->db->get('serah_terima_asper')->result();

    $ruang_total = array();
    $ruang_count = array();

    foreach ($rows as $r) {
        if (empty($r->jumlah_pasien_ranap)) continue;
        $lines = explode("\n", trim($r->jumlah_pasien_ranap));
        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) !== 2) continue;
            $label = trim($parts[0]);
            if ($label === '') continue;
            preg_match('/\d+/', trim($parts[1]), $m);
            $angka = isset($m[0]) ? (int)$m[0] : 0;

            if (!isset($ruang_total[$label])) {
                $ruang_total[$label] = 0;
                $ruang_count[$label] = 0;
            }
            $ruang_total[$label] += $angka;
            $ruang_count[$label]++;
        }
    }

    // Urutkan sesuai urutan field asli
    $urutan = array('NS1','NS2','NS3','HCU','PHCU','NICU','VK','R.Bayi','Lainnya');
    $result  = array();
    foreach ($urutan as $label) {
        if (isset($ruang_total[$label])) {
            $result[] = array(
                'ruang' => $label,
                'total' => $ruang_total[$label],
                'rata'  => round($ruang_total[$label] / $ruang_count[$label], 1),
            );
        }
    }
    return $result;
}

// -----------------------------------------------
// 8. Rekap 7 hari terakhir (untuk summary card)
// -----------------------------------------------
public function get_recent_7days()
{
    $this->db->select('hari_tanggal, shift, unit_divisi, jumlah_pasien_ranap');
    $this->db->where('hari_tanggal >=', date('Y-m-d', strtotime('-6 days')));
    $this->db->order_by('hari_tanggal', 'DESC');
    return $this->db->get('serah_terima_asper')->result();
}

// -----------------------------------------------
// Helper: jumlahkan semua angka dalam satu kolom TEXT
// Format: "NS1: 10\nNS2: 8\n..." → 18
// -----------------------------------------------
private function _sum_text_field($text)
{
    $total = 0;
    if (empty($text)) return $total;
    $lines = explode("\n", trim($text));
    foreach ($lines as $line) {
        $parts = explode(':', $line, 2);
        if (count($parts) === 2) {
            preg_match('/\d+/', trim($parts[1]), $m);
            $total += isset($m[0]) ? (int)$m[0] : 0;
        }
    }
    return $total;
}
}

/* End of file Asper_model.php */
/* Location: ./application/models/Asper_model.php */
