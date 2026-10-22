<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_inventaris_model extends CI_Model
{
    public $table = 'data_inventaris';
    public $id = 'id_inven';
    public $order = 'DESC';
    
    function __construct()
    {
        parent::__construct();
    }
    
    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }
    
    // get data by id
    function get_by_id($id)
    {
        $this->db->select('i.*');
        $this->db->select('r.nm_ruang AS ruang_nm, r.nama_ruang AS ruang_nama, r.kode_ruang AS ruang_kode');
        $this->db->from('data_inventaris i');
        $this->db->join('data_ruang r', 'r.id_ruang = i.id_ruang', 'left');
        $this->db->where('i.' . $this->id, $id);
        return $this->db->get()->row();
    }

    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('i.id_inven', $q);
        $this->db->or_like('i.kode_inven', $q);
        $this->db->or_like('i.nm_barang', $q);
        $this->db->or_like('i.merek', $q);
        $this->db->or_like('i.tipe', $q);
        $this->db->or_like('i.sn', $q);
        $this->db->or_like('i.jenis', $q);
        $this->db->or_like('i.kondisi', $q);
        $this->db->or_like('i.id_ruang', $q);
        $this->db->or_like('i.harga', $q);
        $this->db->or_like('i.stts', $q);
        $this->db->or_like('r.nm_ruang', $q);
        $this->db->or_like('r.nama_ruang', $q);
        $this->db->or_like('r.kode_ruang', $q);
        $this->db->from('data_inventaris i');
        $this->db->join('data_ruang r', 'r.id_ruang = i.id_ruang', 'left');
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->select('i.*');
        $this->db->select('r.nm_ruang AS ruang_nm, r.nama_ruang AS ruang_nama, r.kode_ruang AS ruang_kode');
        $this->db->from('data_inventaris i');
        $this->db->join('data_ruang r', 'r.id_ruang = i.id_ruang', 'left');
        $this->db->order_by('i.' . $this->id, $this->order);
        $this->db->like('i.id_inven', $q);
        $this->db->or_like('i.kode_inven', $q);
        $this->db->or_like('i.nm_barang', $q);
        $this->db->or_like('i.merek', $q);
        $this->db->or_like('i.tipe', $q);
        $this->db->or_like('i.sn', $q);
        $this->db->or_like('i.jenis', $q);
        $this->db->or_like('i.kondisi', $q);
        $this->db->or_like('i.id_ruang', $q);
        $this->db->or_like('i.harga', $q);
        $this->db->or_like('i.stts', $q);
        $this->db->or_like('r.nm_ruang', $q);
        $this->db->or_like('r.nama_ruang', $q);
        $this->db->or_like('r.kode_ruang', $q);
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }
    
    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
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
    
    // Get all ruang for dropdown
    function get_all_ruang()
    {
        $this->load->model('Data_ruang_model');
        return $this->Data_ruang_model->get_all();
    }
    
    // Get ruang by id
    function get_ruang_by_id($id)
    {
        $this->load->model('Data_ruang_model');
        return $this->Data_ruang_model->get_by_id($id);
    }
}
/* End of file Data_inventaris_model.php */
/* Location: ./application/models/Data_inventaris_model.php */