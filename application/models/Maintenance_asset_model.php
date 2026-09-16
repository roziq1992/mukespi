<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Maintenance_asset_model extends CI_Model
{
    public $table_schedule = 'tr_maintenance_schedule';
    public $table_history = 'tr_maintenance_history';
    public $table_jenis = 'ms_jenis_pemeliharaan';
    public $id = 'id_schedule';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // ==================== JENIS PEMELIHARAAN ====================
    
    function get_all_jenis()
    {
        $this->db->where('is_active', 1);
        $this->db->order_by('nama_jenis', 'ASC');
        return $this->db->get($this->table_jenis)->result();
    }

    function get_jenis_by_id($id)
    {
        $this->db->where('id_jenis', $id);
        return $this->db->get($this->table_jenis)->row();
    }

    // ==================== SCHEDULE / JADWAL ====================
    
    function get_all_schedule($limit = null, $start = 0, $q = null)
    {
        $this->db->select('s.*, i.kode_inven, i.nm_barang, i.merek, j.nama_jenis, j.warna');
        $this->db->from($this->table_schedule . ' s');
        $this->db->join('data_inventaris i', 's.id_inven = i.id_inven', 'left');
        $this->db->join('ms_jenis_pemeliharaan j', 's.id_jenis = j.id_jenis', 'left');
        
        if ($q != null) {
            $this->db->like('s.judul', $q);
            $this->db->or_like('i.kode_inven', $q);
            $this->db->or_like('i.nm_barang', $q);
            $this->db->or_like('s.status', $q);
        }
        
        $this->db->order_by('s.tanggal_mulai', 'ASC');
        
        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get()->result();
    }

    function total_rows_schedule($q = null)
    {
        $this->db->from($this->table_schedule . ' s');
        $this->db->join('data_inventaris i', 's.id_inven = i.id_inven', 'left');
        
        if ($q != null) {
            $this->db->like('s.judul', $q);
            $this->db->or_like('i.kode_inven', $q);
            $this->db->or_like('i.nm_barang', $q);
            $this->db->or_like('s.status', $q);
        }
        
        return $this->db->count_all_results();
    }

    function get_schedule_by_id($id)
    {
        $this->db->select('s.*, i.kode_inven, i.nm_barang, i.merek, i.jenis, j.nama_jenis, j.warna');
        $this->db->from($this->table_schedule . ' s');
        $this->db->join('data_inventaris i', 's.id_inven = i.id_inven', 'left');
        $this->db->join('ms_jenis_pemeliharaan j', 's.id_jenis = j.id_jenis', 'left');
        $this->db->where('s.' . $this->id, $id);
        return $this->db->get()->row();
    }

    function insert_schedule($data)
    {
        $this->db->insert($this->table_schedule, $data);
        return $this->db->insert_id();
    }

    function update_schedule($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table_schedule, $data);
    }

    function delete_schedule($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table_schedule);
    }

    // ==================== HISTORY / RIWAYAT ====================
    
    function get_all_history($limit = null, $start = 0, $q = null)
    {
        $this->db->select('h.*, i.kode_inven, i.nm_barang, i.merek, j.nama_jenis, j.warna, s.judul as schedule_judul');
        $this->db->from($this->table_history . ' h');
        $this->db->join('data_inventaris i', 'h.id_inven = i.id_inven', 'left');
        $this->db->join('ms_jenis_pemeliharaan j', 'h.id_jenis = j.id_jenis', 'left');
        $this->db->join('tr_maintenance_schedule s', 'h.id_schedule = s.id_schedule', 'left');
        
        if ($q != null) {
            $this->db->like('i.kode_inven', $q);
            $this->db->or_like('i.nm_barang', $q);
            $this->db->or_like('h.keterangan', $q);
            $this->db->or_like('h.petugas', $q);
        }
        
        $this->db->order_by('h.tanggal', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get()->result();
    }

    function total_rows_history($q = null)
    {
        $this->db->from($this->table_history . ' h');
        $this->db->join('data_inventaris i', 'h.id_inven = i.id_inven', 'left');
        
        if ($q != null) {
            $this->db->like('i.kode_inven', $q);
            $this->db->or_like('i.nm_barang', $q);
            $this->db->or_like('h.keterangan', $q);
            $this->db->or_like('h.petugas', $q);
        }
        
        return $this->db->count_all_results();
    }

    function get_history_by_id($id)
    {
        $this->db->select('h.*, i.kode_inven, i.nm_barang, i.merek, j.nama_jenis, j.warna');
        $this->db->from($this->table_history . ' h');
        $this->db->join('data_inventaris i', 'h.id_inven = i.id_inven', 'left');
        $this->db->join('ms_jenis_pemeliharaan j', 'h.id_jenis = j.id_jenis', 'left');
        $this->db->where('h.id_history', $id);
        return $this->db->get()->row();
    }

    function insert_history($data)
    {
        $this->db->insert($this->table_history, $data);
        return $this->db->insert_id();
    }

    function update_history($id, $data)
    {
        $this->db->where('id_history', $id);
        $this->db->update($this->table_history, $data);
    }

    function delete_history($id)
    {
        $this->db->where('id_history', $id);
        $this->db->delete($this->table_history);
    }

    function get_history_by_inventory($id_inven)
    {
        $this->db->select('h.*, j.nama_jenis, j.warna');
        $this->db->from($this->table_history . ' h');
        $this->db->join('ms_jenis_pemeliharaan j', 'h.id_jenis = j.id_jenis', 'left');
        $this->db->where('h.id_inven', $id_inven);
        $this->db->order_by('h.tanggal', 'DESC');
        return $this->db->get()->result();
    }

    // ==================== SPAREPART ====================
    
    function insert_sparepart($data)
    {
        $this->db->insert('tr_maintenance_sparepart', $data);
        return $this->db->insert_id();
    }

    function get_sparepart_by_history($id_history)
{
    $this->db->where('id_history', $id_history);
    $query = $this->db->get('tr_maintenance_sparepart');
    if ($query->num_rows() > 0) {
        return $query->result();
    }
    return array();
}

    function delete_sparepart($id)
    {
        $this->db->where('id_sparepart', $id);
        $this->db->delete('tr_maintenance_sparepart');
    }

    // ==================== DASHBOARD ====================
    
    function get_dashboard_stats()
    {
        $data = array();
        
        // Total schedule
        $data['total_schedule'] = $this->db->count_all($this->table_schedule);
        
        // Schedule by status
        $this->db->select('status, COUNT(*) as total');
        $this->db->from($this->table_schedule);
        $this->db->group_by('status');
        $data['schedule_by_status'] = $this->db->get()->result();
        
        // Total history
        $data['total_history'] = $this->db->count_all($this->table_history);
        
        // Total biaya maintenance
        $this->db->select('SUM(biaya) as total_biaya');
        $this->db->from($this->table_history);
        $data['total_biaya'] = $this->db->get()->row()->total_biaya ?? 0;
        
        // History by jenis
        $this->db->select('j.nama_jenis, COUNT(h.id_history) as total');
        $this->db->from($this->table_history . ' h');
        $this->db->join('ms_jenis_pemeliharaan j', 'h.id_jenis = j.id_jenis', 'left');
        $this->db->group_by('h.id_jenis');
        $data['history_by_jenis'] = $this->db->get()->result();
        
        // Recent maintenance
        $this->db->select('h.*, i.kode_inven, i.nm_barang, j.nama_jenis');
        $this->db->from($this->table_history . ' h');
        $this->db->join('data_inventaris i', 'h.id_inven = i.id_inven', 'left');
        $this->db->join('ms_jenis_pemeliharaan j', 'h.id_jenis = j.id_jenis', 'left');
        $this->db->order_by('h.tanggal', 'DESC');
        $this->db->limit(5);
        $data['recent_history'] = $this->db->get()->result();
        
        // Upcoming schedules
        $this->db->select('s.*, i.kode_inven, i.nm_barang, j.nama_jenis');
        $this->db->from($this->table_schedule . ' s');
        $this->db->join('data_inventaris i', 's.id_inven = i.id_inven', 'left');
        $this->db->join('ms_jenis_pemeliharaan j', 's.id_jenis = j.id_jenis', 'left');
        $this->db->where('s.status', 'Scheduled');
        $this->db->where('s.tanggal_mulai >=', date('Y-m-d'));
        $this->db->order_by('s.tanggal_mulai', 'ASC');
        $this->db->limit(5);
        $data['upcoming_schedules'] = $this->db->get()->result();
        
        return $data;
    }

    function get_maintenance_cost_by_month($year = null)
    {
        if ($year == null) $year = date('Y');
        
        $this->db->select('MONTH(tanggal) as bulan, SUM(biaya) as total_biaya');
        $this->db->from($this->table_history);
        $this->db->where('YEAR(tanggal)', $year);
        $this->db->group_by('MONTH(tanggal)');
        $this->db->order_by('bulan', 'ASC');
        return $this->db->get()->result();
    }
}