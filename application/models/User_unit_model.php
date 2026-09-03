<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_unit_model extends CI_Model
{
    public $table = 'user_unit';

    function __construct()
    {
        parent::__construct();
    }

    // daftar id_unit milik seorang user (array angka) - untuk filter query
    function get_unit_ids_by_user($user_id)
    {
        $this->db->select('id_unit');
        $this->db->where('user_id', $user_id);
        $rows = $this->db->get($this->table)->result();
        return array_map(function ($r) { return $r->id_unit; }, $rows);
    }

    // detail unit (nm_unit dll) milik seorang user - untuk isi dropdown
    function get_units_by_user($user_id)
    {
        $this->db->select('unit.*');
        $this->db->from($this->table);
        $this->db->join('unit', 'unit.id_unit = user_unit.id_unit');
        $this->db->where('user_unit.user_id', $user_id);
        $this->db->order_by('unit.nm_unit', 'ASC');
        return $this->db->get()->result();
    }

    // simpan assignment unit untuk user (replace semua) - dipanggil dari form edit user
    function sync_units($user_id, $unit_ids = array())
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete($this->table);

        if (!empty($unit_ids)) {
            $insert_data = array();
            foreach ($unit_ids as $id_unit) {
                $insert_data[] = array('user_id' => $user_id, 'id_unit' => $id_unit);
            }
            $this->db->insert_batch($this->table, $insert_data);
        }
    }
    // daftar user + search (untuk halaman list)
function get_all_users($q = NULL)
{
    $this->db->select('id, name, email, role_id');
    $this->db->from('users');
    if ($q <> '') {
        $this->db->group_start();
        $this->db->like('name', $q);
        $this->db->or_like('email', $q);
        $this->db->group_end();
    }
    $this->db->order_by('name', 'ASC');
    return $this->db->get()->result();
}

function count_all_users($q = NULL)
{
    $this->db->from('users');
    if ($q <> '') {
        $this->db->group_start();
        $this->db->like('name', $q);
        $this->db->or_like('email', $q);
        $this->db->group_end();
    }
    return $this->db->count_all_results();
}

function get_user_by_id($user_id)
{
    return $this->db->select('id, name, email, role_id')
        ->where('id', $user_id)
        ->get('users')
        ->row();
}

// hapus satu assignment unit saja (untuk tombol x cepat)
function remove_unit($user_id, $id_unit)
{
    $this->db->where('user_id', $user_id);
    $this->db->where('id_unit', $id_unit);
    $this->db->delete($this->table);
}
}
/* End of file User_unit_model.php */