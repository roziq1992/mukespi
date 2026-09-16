<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pemeliharaan_model extends CI_Model
{
    public $table = 'data_pemeliharaan';
    public $id = 'id_pemeliharaan';
    public $order = 'DESC';
    
    function __construct()
    {
        parent::__construct();
    }
    
    // get all with join to inventaris
    function get_all()
    {
        $this->db->select('p.*, i.kode_inven, i.nm_barang, i.merek, i.jenis');
        $this->db->from($this->table . ' p');
        $this->db->join('data_inventaris i', 'p.id_inven = i.id_inven', 'left');
        $this->db->order_by('p.' . $this->id, $this->order);
        return $this->db->get()->result();
    }
    
    // get data by id
    function get_by_id($id)
    {
        $this->db->select('p.*, i.kode_inven, i.nm_barang, i.merek, i.jenis');
        $this->db->from($this->table . ' p');
        $this->db->join('data_inventaris i', 'p.id_inven = i.id_inven', 'left');
        $this->db->where('p.' . $this->id, $id);
        return $this->db->get()->row();
    }
    
    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->from($this->table . ' p');
        $this->db->join('data_inventaris i', 'p.id_inven = i.id_inven', 'left');
        if ($q != NULL) {
            $this->db->like('p.id_inven', $q);
            $this->db->or_like('i.kode_inven', $q);
            $this->db->or_like('i.nm_barang', $q);
            $this->db->or_like('p.keterangan', $q);
            $this->db->or_like('p.petugas', $q);
            $this->db->or_like('p.status', $q);
        }
        return $this->db->count_all_results();
    }
    
    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->select('p.*, i.kode_inven, i.nm_barang, i.merek, i.jenis');
        $this->db->from($this->table . ' p');
        $this->db->join('data_inventaris i', 'p.id_inven = i.id_inven', 'left');
        if ($q != NULL) {
            $this->db->like('p.id_inven', $q);
            $this->db->or_like('i.kode_inven', $q);
            $this->db->or_like('i.nm_barang', $q);
            $this->db->or_like('p.keterangan', $q);
            $this->db->or_like('p.petugas', $q);
            $this->db->or_like('p.status', $q);
        }
        $this->db->order_by('p.' . $this->id, $this->order);
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
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
    
    // get maintenance history by inventory id
    function get_history_by_inventory2($id_inven)
    {
        $this->db->select('p.*, i.kode_inven, i.nm_barang');
        $this->db->from($this->table . ' p');
        $this->db->join('data_inventaris i', 'p.id_inven = i.id_inven', 'left');
        $this->db->where('p.id_inven', $id_inven);
        $this->db->order_by('p.tanggal', 'DESC');
        return $this->db->get()->result();
    }
    // Get maintenance by inventory id
function get_by_inventory2($id_inven)
{
    $this->db->select('p.*, i.kode_inven, i.nm_barang, i.merek, i.jenis');
    $this->db->from($this->table . ' p');
    $this->db->join('data_inventaris i', 'p.id_inven = i.id_inven', 'left');
    $this->db->where('p.id_inven', $id_inven);
    $this->db->order_by('p.tanggal', 'DESC');
    $query = $this->db->get();
    
    if ($query->num_rows() > 0) {
        return $query->result();
    }
    return array();
}
// Get maintenance by inventory id (alias untuk konsistensi)
function get_by_inventory($id_inven)
{
    return $this->get_history_by_inventory($id_inven);
}

// Get history by inventory
function get_history_by_inventory($id_inven)
{
    $this->db->where('id_inven', $id_inven);
    $this->db->order_by('tanggal', 'DESC');
    $query = $this->db->get($this->table);
    
    if ($query->num_rows() > 0) {
        return $query->result();
    }
    return array();
}
}