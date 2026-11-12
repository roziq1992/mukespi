<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Budaya_keselamatan_model extends CI_Model
{
    private $table = 'budaya_keselamatan';
    private $primary_key = 'id_budaya_keselamatan';

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_by_id($id)
    {
        return $this->db->where($this->primary_key, (int) $id)->get($this->table)->row();
    }

    public function get_limit_data($limit, $start, $query = '')
    {
        $this->_apply_search($query);
        return $this->db->order_by($this->primary_key, 'DESC')
            ->limit((int) $limit, (int) $start)
            ->get($this->table)
            ->result();
    }

    public function total_rows($query = '')
    {
        $this->_apply_search($query);
        return $this->db->count_all_results($this->table);
    }

    public function delete($id)
    {
        return $this->db->where($this->primary_key, (int) $id)->delete($this->table);
    }

    private function _apply_search($query)
    {
        $query = trim((string) $query);
        if ($query === '') {
            return;
        }

        $this->db->group_start()
            ->like('nama_pelapor', $query)
            ->or_like('tanggal_kejadian', $query)
            ->or_like('lokasi', $query)
            ->or_like('nama_staf_pengunjung', $query)
            ->or_like('uraian_kejadian', $query)
            ->group_end();
    }
}
