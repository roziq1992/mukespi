<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Laporan_insiden_k3rs_model extends CI_Model
{
    private $table = 'laporan_insiden_k3rs';
    private $primary_key = 'id_laporan_insiden_k3rs';

    public function insert($data, $korban, $tindakan)
    {
        $this->db->trans_begin();
        $inserted = $this->db->insert($this->table, $data);
        $id = $inserted ? $this->db->insert_id() : 0;

        if ($id && $korban) {
            foreach ($korban as $row) {
                $row['id_laporan_insiden_k3rs'] = $id;
                $this->db->insert('laporan_insiden_k3rs_korban', $row);
            }
        }
        if ($id && $tindakan) {
            foreach ($tindakan as $row) {
                $row['id_laporan_insiden_k3rs'] = $id;
                $this->db->insert('laporan_insiden_k3rs_tindakan', $row);
            }
        }

        if (!$id || $this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }
        return $this->db->trans_commit();
    }

    public function get_by_id($id)
    {
        $laporan = $this->db->where($this->primary_key, (int) $id)->get($this->table)->row();
        if (!$laporan) return NULL;

        $laporan->korban = $this->db->where($this->primary_key, (int) $id)
            ->order_by('id_korban', 'ASC')
            ->get('laporan_insiden_k3rs_korban')
            ->result();
        $laporan->tindakan = $this->db->where($this->primary_key, (int) $id)
            ->order_by('id_tindakan', 'ASC')
            ->get('laporan_insiden_k3rs_tindakan')
            ->result();
        return $laporan;
    }

    public function get_limit_data($limit, $start, $query = '')
    {
        $this->_apply_search($query);
        return $this->db->order_by($this->primary_key, 'DESC')
            ->limit((int) $limit, (int) $start)
            ->get($this->table)
            ->result();
    }

    public function total_rows($query = '')
    {
        $this->_apply_search($query);
        return $this->db->count_all_results($this->table);
    }

    public function delete($id)
    {
        return $this->db->where($this->primary_key, (int) $id)->delete($this->table);
    }

    private function _apply_search($query)
    {
        $query = trim((string) $query);
        if ($query === '') return;

        $this->db->group_start()
            ->like('tanggal', $query)
            ->or_like('pekerjaan', $query)
            ->or_like('lokasi', $query)
            ->or_like('kronologi', $query)
            ->or_like('peralatan_kerja', $query)
            ->group_end();
    }
}
