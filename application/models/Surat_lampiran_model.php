<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Surat_lampiran_model extends CI_Model
{
    public function by_surat($id) { return $this->db->where('id_surat', (int) $id)->order_by('id')->get('surat_lampiran')->result(); }
    public function insert($data) { return $this->db->insert('surat_lampiran', $data); }
}
