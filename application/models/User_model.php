<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function count_all($q = '')
    {
        if ($q !== '') {
            $this->db->group_start();
            $this->db->like('u.name', $q);
            $this->db->or_like('u.email', $q);
            $this->db->group_end();
        }
        return $this->db->count_all_results('users u');
    }

    public function get_all($q = '', $limit = 0, $start = 0)
    {
        $this->db->select('u.*, r.name AS role_name');
        $this->db->from('users u');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        if ($q !== '') {
            $this->db->group_start();
            $this->db->like('u.name', $q);
            $this->db->or_like('u.email', $q);
            $this->db->group_end();
        }
        $this->db->order_by('u.id', 'ASC');
        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('users', ['id' => (int) $id])->row_array();
    }

    public function is_email_taken($email, $except_id = 0)
    {
        $this->db->where('email', $email);
        if ($except_id > 0) {
            $this->db->where('id !=', (int) $except_id);
        }
        return $this->db->get('users')->num_rows() > 0;
    }

    public function insert_user($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function update_user($id, $data)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id)
    {
        $this->db->where('id', (int) $id);
        return $this->db->delete('users');
    }

    // ada surat yang me-refer user? (FK restrict => tidak boleh di-delete)
    public function is_referenced($id)
    {
        $surat = $this->db->where('id_pemohon', (int) $id)->from('surat')->count_all_results();
        $unit  = $this->db->where('user_id', (int) $id)->from('user_unit')->count_all_results();
        return intval($surat + $unit);
    }

    public function roles()
    {
        return $this->db->order_by('id', 'ASC')->get('roles')->result();
    }
}