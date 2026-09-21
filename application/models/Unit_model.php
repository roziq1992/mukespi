<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Unit_model
 * ---------------------------------------------------------
 * Manajemen master unit kerja (tabel unit):
 * - Create unit baru, update nama/jenis, ubah status aktif/nonaktif.
 */
class Unit_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_all($status = '', $q = '')
    {
        $this->db->from('unit');
        if ($status === 'aktif' || $status === 'nonaktif') {
            $this->db->where('status', $status);
        }
        if (trim($q) !== '') {
            $this->db->group_start();
            $this->db->like('nm_unit', $q);
            $this->db->or_like('jns_unit', $q);
            $this->db->group_end();
        }
        $this->db->order_by('status', 'ASC');
        $this->db->order_by('nm_unit', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id_unit', (int)$id)->get('unit')->row();
    }

    public function exists_name($nm_unit, $except_id = 0)
    {
        $this->db->where('nm_unit', trim($nm_unit));
        if ((int)$except_id > 0) {
            $this->db->where('id_unit !=', (int)$except_id);
        }
        return (int)$this->db->count_all_results('unit') > 0;
    }

    public function insert($data)
    {
        $this->db->insert('unit', $data);
        return (int)$this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id_unit', (int)$id)->update('unit', $data);
    }

    public function set_status($id, $status)
    {
        if ($status !== 'aktif' && $status !== 'nonaktif') {
            return FALSE;
        }
        $this->db->where('id_unit', (int)$id)->update('unit', array('status' => $status));
        return TRUE;
    }

    public function count_pegawai($id_unit)
    {
        return (int)$this->db->where('id_unit', (int)$id_unit)->count_all_results('pegawai');
    }

    public function count_by_status($status)
    {
        return (int)$this->db->where('status', $status)->count_all_results('unit');
    }

    public function count_all()
    {
        return (int)$this->db->count_all('unit');
    }
}
/* End of file Unit_model.php */