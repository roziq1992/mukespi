<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Surat_disposisi_model extends CI_Model
{
    public function by_surat($id) { return $this->db->select('d.*, u1.name AS dari_nama, u2.name AS ke_nama')->from('surat_disposisi d')->join('users u1', 'u1.id=d.dari_user')->join('users u2', 'u2.id=d.ke_user', 'left')->where('d.id_surat', (int) $id)->order_by('d.id')->get()->result(); }
    public function insert($data) { return $this->db->insert('surat_disposisi', $data); }
    public function update_status($id, $status) { return $this->db->where('id', (int) $id)->update('surat_disposisi', array('status' => $status)); }
}
