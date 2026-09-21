<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notifikasi_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function add($id_user, $pesan, $url = NULL)
    {
        $id_user = (int) $id_user;
        if (!$id_user) {
            return FALSE;
        }
        return $this->db->insert('notifikasi', array(
            'id_user'    => $id_user,
            'pesan'      => $pesan,
            'url'        => $url,
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function count_unread($id_user)
    {
        return (int) $this->db->where('id_user', (int)$id_user)
            ->where('is_read', 0)
            ->count_all_results('notifikasi');
    }

    public function latest($id_user, $limit = 5)
    {
        return $this->db->where('id_user', (int)$id_user)
            ->order_by('id_notif', 'DESC')
            ->limit((int)$limit)
            ->get('notifikasi')->result();
    }

    public function list_all($id_user, $limit = 100)
    {
        return $this->db->where('id_user', (int)$id_user)
            ->order_by('id_notif', 'DESC')
            ->limit((int)$limit)
            ->get('notifikasi')->result();
    }

    public function mark_read($id_user)
    {
        return $this->db->where('id_user', (int)$id_user)
            ->where('is_read', 0)
            ->update('notifikasi', array('is_read' => 1));
    }

    public function hapus_semua($id_user)
    {
        return $this->db->where('id_user', (int)$id_user)->delete('notifikasi');
    }
}
/* End of file Notifikasi_model.php */