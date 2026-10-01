<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_pokja_ep_model extends CI_Model
{
    public $table = 'user_pokja_ep';

    function __construct()
    {
        parent::__construct();
    }

    // ================= DAFTAR POKJA =================

    function get_all_pokja()
    {
        return $this->db->where('active', 'Y')->order_by('id', 'ASC')->get('pokja')->result();
    }

    function get_pokja_by_id($id_pokja)
    {
        return $this->db->where('id', $id_pokja)->get('pokja')->row();
    }

    // ================= AKSES USER =================

    // daftar id_pokja yang di-set untuk user (semua, baik penilai maupun readonly)
    function get_pokja_ids_by_user($id_user)
    {
        $this->db->select('id_pokja')->where('id_user', $id_user);
        $rows = $this->db->get($this->table)->result();
        return array_map(function ($r) { return intval($r->id_pokja); }, $rows);
    }

    // daftar id_pokja di mana user ini bertindak sebagai PENILAI (boleh menilai)
    function get_penilai_pokja_ids_by_user($id_user)
    {
        $this->db->select('id_pokja')->where('id_user', $id_user)->where('is_penilai', 1);
        $rows = $this->db->get($this->table)->result();
        return array_map(function ($r) { return intval($r->id_pokja); }, $rows);
    }

    // detail pokja milik seorang user - untuk badge di halaman list
    function get_pokja_by_user($id_user)
    {
        $this->db->select('p.id, p.bab, p.ket, upe.is_penilai');
        $this->db->from($this->table . ' upe');
        $this->db->join('pokja p', 'p.id = upe.id_pokja');
        $this->db->where('upe.id_user', $id_user);
        $this->db->order_by('p.id', 'ASC');
        return $this->db->get()->result();
    }

    // user mana saja yang punya akses ke sebuah pokja
    function get_users_by_pokja($id_pokja)
    {
        $this->db->select('u.id, u.name, u.email, u.role_id, upe.is_penilai');
        $this->db->from($this->table . ' upe');
        $this->db->join('users u', 'u.id = upe.id_user');
        $this->db->where('upe.id_pokja', $id_pokja);
        $this->db->order_by('u.name', 'ASC');
        return $this->db->get()->result();
    }

    // user yang sudah punya minimal satu assignment pokja (untuk ringkasan di list)
    function get_user_ids_with_access()
    {
        $this->db->select('id_user')->group_by('id_user');
        $rows = $this->db->get($this->table)->result();
        return array_map(function ($r) { return intval($r->id_user); }, $rows);
    }

    // ================= SIMPAN =================

    // $pokja_penilai = array id_pokja yang ditandai sebagai Penilai
    // $pokja_readonly = array id_pokja lain yang hanya boleh lihat dokumen
    function sync_pokja($id_user, $pokja_penilai = array(), $pokja_readonly = array())
    {
        $this->db->where('id_user', $id_user);
        $this->db->delete($this->table);

        $insert = array();
        foreach ($pokja_penilai as $id_pokja) {
            $insert[] = array('id_user' => $id_user, 'id_pokja' => intval($id_pokja), 'is_penilai' => 1);
        }
        foreach ($pokja_readonly as $id_pokja) {
            $insert[] = array('id_user' => $id_user, 'id_pokja' => intval($id_pokja), 'is_penilai' => 0);
        }

        if (!empty($insert)) {
            $this->db->insert_batch($this->table, $insert);
        }
    }

    function remove_pokja($id_user, $id_pokja)
    {
        $this->db->where('id_user', $id_user);
        $this->db->where('id_pokja', $id_pokja);
        $this->db->delete($this->table);
    }

    // ================= DAFTAR USER =================

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

    function get_user_by_id($id_user)
    {
        return $this->db->select('id, name, email, role_id')
            ->where('id', $id_user)
            ->get('users')
            ->row();
    }
}
/* End of file User_pokja_ep_model.php */
/* Location: ./application/models/User_pokja_ep_model.php */
