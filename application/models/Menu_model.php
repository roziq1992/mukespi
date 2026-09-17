<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    /* ================= MENUS ================= */

    public function get_menus($q = '')
    {
        if ($q !== '') {
            $this->db->group_start();
            $this->db->like('nama_menu', $q);
            $this->db->or_like('url', $q);
            $this->db->group_end();
        }
        return $this->db->order_by('sequence', 'ASC')
            ->order_by('id', 'ASC')
            ->get('menus')->result();
    }

    public function get_menu($id)
    {
        return $this->db->get_where('menus', ['id' => (int) $id])->row_array();
    }

    public function is_menu_url_taken($url, $except_id = 0)
    {
        $this->db->where('url', $url);
        if ($except_id > 0) {
            $this->db->where('id !=', (int) $except_id);
        }
        return $this->db->get('menus')->num_rows() > 0;
    }

    public function insert_menu($data)
    {
        $this->db->insert('menus', $data);
        return $this->db->insert_id();
    }

    public function update_menu($id, $data)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update('menus', $data);
    }

    public function delete_menu($id)
    {
        // lepas juga dari tabel akses
        $this->db->where('menu_id', (int) $id)->delete('user_access_menu');
        $this->db->where('id', (int) $id)->delete('menus');
    }

    /* ================= ROLES ================= */

    public function get_roles()
    {
        return $this->db->order_by('id', 'ASC')->get('roles')->result();
    }

    public function get_role($id)
    {
        return $this->db->get_where('roles', ['id' => (int) $id])->row_array();
    }

    public function is_role_name_taken($name, $except_id = 0)
    {
        $this->db->where('name', $name);
        if ($except_id > 0) {
            $this->db->where('id !=', (int) $except_id);
        }
        return $this->db->get('roles')->num_rows() > 0;
    }

    public function insert_role($data)
    {
        $this->db->insert('roles', $data);
        return $this->db->insert_id();
    }

    public function update_role($id, $data)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update('roles', $data);
    }

    public function delete_role($id)
    {
        // jangan hapus role yang masih dipakai user
        $used = $this->db->where('role_id', (int) $id)->from('users')->count_all_results();
        if ($used > 0) {
            return FALSE;
        }
        $this->db->where('role_id', (int) $id)->delete('user_access_menu');
        $this->db->where('id', (int) $id)->delete('roles');
        return TRUE;
    }

    /* ================= ROLE ACCESS ================= */

    public function access_of_role($role_id)
    {
        $result = $this->db->select('menu_id')
            ->where('role_id', (int) $role_id)
            ->get('user_access_menu');
        $ids = [];
        foreach ($result->result() as $row) {
            $ids[] = (int) $row->menu_id;
        }
        return $ids;
    }

    public function sync_access($role_id, $menu_ids)
    {
        $this->db->where('role_id', (int) $role_id)->delete('user_access_menu');

        $menu_ids = array_filter(array_map('intval', (array) $menu_ids));
        foreach ($menu_ids as $mid) {
            $this->db->insert('user_access_menu', [
                'role_id' => (int) $role_id,
                'menu_id' => $mid,
            ]);
        }
    }
}