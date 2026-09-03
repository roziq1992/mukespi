<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring_pj_model extends CI_Model
{
    public $table = 'monitoring_pj';
    public $id = 'id_monitoring';
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
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // get total rows
    function total_rows($q = NULL)
    {
        if ($this->session->userdata('email') == 'admin@mail.com' || $this->session->userdata('email') == 'DIR01@dir.com') {
            $this->db->like('nm_pj', $q);
            $this->db->or_like('nama_aplikasi', $q);
            $this->db->or_like('bulan', $q);
            $this->db->or_like('tahun', $q);
            $this->db->from($this->table);
        } else {
            $iduser = $this->session->userdata('id');
            $this->db->where('userid', $iduser);
            $this->db->group_start();
            $this->db->like('nm_pj', $q);
            $this->db->or_like('nama_aplikasi', $q);
            $this->db->or_like('bulan', $q);
            $this->db->or_like('tahun', $q);
            $this->db->group_end();
            $this->db->from($this->table);
        }
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        if ($this->session->userdata('email') == 'admin@mail.com' || $this->session->userdata('email') == 'DIR01@dir.com') {
            $this->db->order_by($this->id, $this->order);
            $this->db->like('nm_pj', $q);
            $this->db->or_like('nama_aplikasi', $q);
            $this->db->or_like('bulan', $q);
            $this->db->or_like('tahun', $q);
            $this->db->limit($limit, $start);
        } else {
            $iduser = $this->session->userdata('id');
            $this->db->order_by($this->id, $this->order);
            $this->db->where('userid', $iduser);
            $this->db->group_start();
            $this->db->like('nm_pj', $q);
            $this->db->or_like('nama_aplikasi', $q);
            $this->db->or_like('bulan', $q);
            $this->db->or_like('tahun', $q);
            $this->db->group_end();
            $this->db->limit($limit, $start);
        }
        return $this->db->get($this->table)->result();
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
        // ambil data untuk grafik berdasarkan filter bulan & tahun
    function get_by_bulan_tahun($bulan = NULL, $tahun = NULL)
    {
        if ($this->session->userdata('email') == 'admin@mail.com' || $this->session->userdata('email') == 'DIR01@dir.com') {
            if ($bulan) $this->db->where('bulan', $bulan);
            if ($tahun) $this->db->where('tahun', $tahun);
        } else {
            $iduser = $this->session->userdata('id');
            $this->db->where('userid', $iduser);
            if ($bulan) $this->db->where('bulan', $bulan);
            if ($tahun) $this->db->where('tahun', $tahun);
        }
        $this->db->order_by('nama_aplikasi', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // daftar tahun yang tersedia di data (untuk dropdown filter)
    function get_tahun_list()
    {
        $this->db->distinct();
        $this->db->select('tahun');
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get($this->table)->result();
    }
}
/* End of file Monitoring_pj_model.php */
/* Location: ./application/models/Monitoring_pj_model.php */