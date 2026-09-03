<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operan_model extends CI_Model
{
    public $table = 'serah_terima_pasien';
    public $id = 'id_operan';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $this->db->order_by('hari_tanggal', $this->order);
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    function total_rows($q = NULL)
    {
        $this->db->like('hari_tanggal', $q);
        $this->db->or_like('departemen', $q);
        $this->db->or_like('perawat_shift1', $q);
        $this->db->or_like('perawat_shift2', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by('hari_tanggal', $this->order);
        $this->db->order_by($this->id, $this->order);
        $this->db->like('hari_tanggal', $q);
        $this->db->or_like('departemen', $q);
        $this->db->or_like('perawat_shift1', $q);
        $this->db->or_like('perawat_shift2', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
    
    // Dashboard: Statistik bulanan
    function get_dashboard_stats($bulan, $tahun)
    {
        $this->db->where('MONTH(hari_tanggal)', $bulan);
        $this->db->where('YEAR(hari_tanggal)', $tahun);
        $total = $this->db->count_all_results($this->table);
        
        // Rata-rata pasien per hari
        $this->db->select('AVG(jumlah_pasien_ranap) as rata');
        $this->db->where('MONTH(hari_tanggal)', $bulan);
        $this->db->where('YEAR(hari_tanggal)', $tahun);
        $query = $this->db->get($this->table);
        $rata = $query->row()->rata ?? 0;
        
        return array('total' => $total, 'rata' => round($rata, 1));
    }
}