<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Surat_log_model extends CI_Model
{
    public function by_surat($id) { return $this->db->select('l.*, u.name AS user_name')->from('surat_log l')->join('users u', 'u.id=l.id_user', 'left')->where('l.id_surat', (int) $id)->order_by('l.id', 'DESC')->get()->result(); }
}
