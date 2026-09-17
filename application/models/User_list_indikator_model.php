<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User_list_indikator_model
 * ---------------------------------------------------------
 * Akses indikator per user (analog dengan User_unit_model).
 * Admin menetapkan indikator mana saja yang boleh diakses tiap user.
 */
class User_list_indikator_model extends CI_Model
{
    public $table = 'user_list_indikator';

    function __construct()
    {
        parent::__construct();
    }

    // daftar id_indikator milik seorang user (array angka) - untuk filter query
    function get_indikator_ids_by_user($user_id)
    {
        $this->db->select('id_indikator');
        $this->db->where('user_id', $user_id);
        $rows = $this->db->get($this->table)->result();
        return array_map(function ($r) { return (int) $r->id_indikator; }, $rows);
    }

    // detail indikator (judul, unit) milik seorang user - untuk badge di list
    function get_indikators_by_user($user_id)
    {
        $this->db->select('list_indikator.id_indikator, list_indikator.judul, list_indikator.jenis, list_indikator.kelompok, unit.nm_unit');
        $this->db->from($this->table);
        $this->db->join('list_indikator', 'list_indikator.id_indikator = ' . $this->table . '.id_indikator');
        $this->db->join('unit', 'unit.id_unit = list_indikator.id_unit', 'left');
        $this->db->where($this->table . '.user_id', $user_id);
        $this->db->order_by('unit.nm_unit', 'ASC');
        $this->db->order_by('list_indikator.judul', 'ASC');
        return $this->db->get()->result();
    }

    // simpan assignment indikator untuk user (replace semua)
    function sync_indikators($user_id, $id_indikators = array())
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete($this->table);

        if (!empty($id_indikators)) {
            $insert_data = array();
            foreach ($id_indikators as $id_indikator) {
                $insert_data[] = array(
                    'user_id'      => (int) $user_id,
                    'id_indikator' => (int) $id_indikator,
                );
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

    // hapus satu assignment indikator saja (tombol x pada badge)
    function remove_indikator($user_id, $id_indikator)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('id_indikator', $id_indikator);
        $this->db->delete($this->table);
    }

    /**
     * Semua indikator dikelompokkan per unit (untuk checkbox di form kelola).
     * Indikator tanpa unit masuk grup "(Tanpa Unit)".
     * Return: array( 'LABORAT' => array(rows...), '(Tanpa Unit)' => array(rows...) )
     */
    function get_indikators_grouped()
    {
        $this->db->select('list_indikator.id_indikator, list_indikator.judul, list_indikator.jenis, list_indikator.kelompok, unit.nm_unit');
        $this->db->from('list_indikator');
        $this->db->join('unit', 'unit.id_unit = list_indikator.id_unit', 'left');
        $this->db->order_by('unit.nm_unit', 'ASC');
        $this->db->order_by('list_indikator.kelompok', 'ASC');
        $this->db->order_by('list_indikator.judul', 'ASC');
        $rows = $this->db->get()->result();

        $grouped = array();
        foreach ($rows as $r) {
            $group = !empty($r->nm_unit) ? $r->nm_unit : '(Tanpa Unit)';
            if (!isset($grouped[$group])) {
                $grouped[$group] = array();
            }
            $grouped[$group][] = $r;
        }
        return $grouped;
    }
}
/* End of file User_list_indikator_model.php */
