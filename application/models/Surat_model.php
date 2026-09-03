<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_model extends CI_Model
{
    public function list_data($role, $user_id, $filters = array())
    {
        $this->db->select('s.*, u.name AS pemohon, l.created_at AS last_update, lu.name AS last_update_by');
        $this->db->from('surat s');
        $this->db->join('users u', 'u.id = s.id_pemohon');
        $this->db->join('(SELECT x.id_surat, x.created_at, x.id_user FROM surat_log x INNER JOIN (SELECT id_surat, MAX(id) AS max_id FROM surat_log GROUP BY id_surat) y ON y.max_id=x.id) l', 'l.id_surat=s.id', 'left', false);
        $this->db->join('users lu', 'lu.id = l.id_user', 'left');
        if ($role === 'user') $this->db->where('s.id_pemohon', (int) $user_id);
        // Sekretaris melihat antrian aktif sekaligus riwayat surat yang sudah diteruskan.
        // Direktur melihat antrian aktif sekaligus riwayat surat yang pernah diterima.
        if (!empty($filters['status'])) $this->db->where('s.status', $filters['status']);
        if (!empty($filters['jenis'])) $this->db->where('s.jenis', $filters['jenis']);
        if (!empty($filters['mulai'])) $this->db->where('s.tanggal_pengajuan >=', $filters['mulai']);
        if (!empty($filters['sampai'])) $this->db->where('s.tanggal_pengajuan <=', $filters['sampai']);
        if (!empty($filters['q'])) $this->db->group_start()->like('s.perihal', $filters['q'])->or_like('s.no_surat', $filters['q'])->or_like('s.tujuan', $filters['q'])->group_end();
        return $this->db->order_by('s.updated_at', 'DESC')->get()->result();
    }

    public function get($id) { return $this->db->get_where('surat', array('id' => (int) $id))->row(); }
    public function insert($data) { $this->db->insert('surat', $data); return $this->db->insert_id(); }
    public function update($id, $data) { return $this->db->where('id', (int) $id)->update('surat', $data); }
    public function pending_count($role, $user_id) {
        $this->db->from('surat');
        if ($role === 'user') $this->db->where('id_pemohon', $user_id)->where_not_in('status', array('Selesai', 'Ditandatangani'));
        if ($role === 'admin') $this->db->where_not_in('status', array('Selesai', 'Ditandatangani'));
        if ($role === 'sekretaris') $this->db->where_in('status', array('Diajukan', 'Diproses Sekretaris'));
        if ($role === 'direktur') $this->db->where_in('status', array('Sudah Diberi Nomor', 'Diteruskan ke Direktur'));
        return $this->db->count_all_results();
    }
    public function users() { return $this->db->where('id !=', (int) $this->session->userdata('id'))->order_by('name')->get('users')->result(); }
}
