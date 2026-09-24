<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pelaporan_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private function _base_query()
    {
        $this->db->select('l.*,
            t.nama AS nama_terlapor,
            t.jabatan AS jabatan_terlapor,
            t.unit_kerja AS unit_terlapor,
            COALESCE(p.nama, u.name) AS nama_pelapor,
            COALESCE(p.nik, u.email) AS identitas_pelapor');
        $this->db->from('pelaporan_karyawan l');
        $this->db->join('pegawai t', 't.id_pegawai = l.id_terlapor', 'left');
        $this->db->join('pegawai p', 'p.id_pegawai = l.id_pelapor', 'left');
        $this->db->join('users u', 'u.id = l.id_user_pelapor', 'left');
    }

    // semua laporan (HRD/Admin), opsional filter status
    function list_all($status = NULL)
    {
        $this->_base_query();
        $this->_filter_status($status);
        $this->db->order_by('l.id_laporan', 'DESC');
        return $this->db->get()->result();
    }

    // laporan milik pelapor yang login via NIK (id_pegawai)
    function list_by_pegawai($id_pegawai, $status = NULL)
    {
        $this->_base_query();
        $this->db->where('l.id_pelapor', (int) $id_pegawai);
        $this->_filter_status($status);
        $this->db->order_by('l.id_laporan', 'DESC');
        return $this->db->get()->result();
    }

    // laporan milik pelapor yang login via email (id users)
    function list_by_user($id_user, $status = NULL)
    {
        $this->_base_query();
        $this->db->where('l.id_user_pelapor', (int) $id_user);
        $this->_filter_status($status);
        $this->db->order_by('l.id_laporan', 'DESC');
        return $this->db->get()->result();
    }

    // laporan milik pegawai yang dinilai (terlapor)
    function list_by_terlapor($id_pegawai, $status = NULL)
    {
        $this->_base_query();
        $this->db->where('l.id_terlapor', (int) $id_pegawai);
        $this->_filter_status($status);
        $this->db->order_by('l.id_laporan', 'DESC');
        return $this->db->get()->result();
    }

    private function _filter_status($status)
    {
        $status = trim((string) $status);
        if ($status !== '' && $status !== 'semua' && in_array($status, array('menunggu', 'divalidasi', 'ditolak'), true)) {
            $this->db->where('l.status', $status);
        }
    }

    function data($id)
    {
        $this->_base_query();
        $this->db->where('l.id_laporan', (int) $id);
        return $this->db->get()->row();
    }

    function insert($data)
    {
        $this->db->insert('pelaporan_karyawan', $data);
        return $this->db->insert_id();
    }

    // simpan / ubah sanggahan terlapor
    function sanggah($id, $data)
    {
        $this->db->where('id_laporan', (int) $id);
        return $this->db->update('pelaporan_karyawan', $data);
    }

    // set status validasi / tolak oleh Admin/HRD (dengan alasan)
    function validasi($id, $data)
    {
        $this->db->where('id_laporan', (int) $id);
        return $this->db->update('pelaporan_karyawan', $data);
    }

    function delete($id)
    {
        $this->db->where('id_laporan', (int) $id);
        return $this->db->delete('pelaporan_karyawan');
    }
}