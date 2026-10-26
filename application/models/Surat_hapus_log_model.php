<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_hapus_log_model extends CI_Model
{
    public function insert($data) { return $this->db->insert('surat_hapus_log', $data); }
    public function list_all() { return $this->db->order_by('id', 'DESC')->get('surat_hapus_log')->result(); }
}
