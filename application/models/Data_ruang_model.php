<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_ruang_model extends CI_Model
{
    public $table = 'data_ruang';
    public $id = 'id_ruang';
    public $order = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // get data by kode ruang
    function get_by_kode($kode)
    {
        $this->db->where('kode_ruang', $kode);
        return $this->db->get($this->table)->row();
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->db->like('id_ruang', $q);
        $this->db->or_like('nm_ruang', $q);
        $this->db->or_like('nama_ruang', $q);
        $this->db->or_like('kode_ruang', $q);
        $this->db->or_like('gedung', $q);
        $this->db->or_like('lantai', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->select('data_ruang.*');
        $this->db->select('(SELECT COUNT(*) FROM data_inventaris WHERE data_inventaris.id_ruang = data_ruang.id_ruang) AS jumlah_inventaris', FALSE);
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id_ruang', $q);
        $this->db->or_like('nm_ruang', $q);
        $this->db->or_like('nama_ruang', $q);
        $this->db->or_like('kode_ruang', $q);
        $this->db->or_like('gedung', $q);
        $this->db->or_like('lantai', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows();
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

    // hitung jumlah inventaris yang memakai ruang ini
    function count_inventaris($id_ruang)
    {
        $this->db->where('id_ruang', $id_ruang);
        return $this->db->count_all_results('data_inventaris');
    }

    /**
     * Generate id_ruang berikutnya dengan format R001, R002, ...
     * id_ruang pada tabel data_ruang adalah varchar (bukan auto increment)
     * sehingga harus di-generate oleh aplikasi.
     */
    function generate_id()
    {
        $this->db->select('id_ruang');
        $this->db->from($this->table);
        $rows = $this->db->get()->result();

        $max = 0;
        foreach ($rows as $row) {
            if (preg_match('/^R(\d+)$/i', $row->id_ruang, $m)) {
                $num = (int) $m[1];
                if ($num > $max) {
                    $max = $num;
                }
            }
        }

        return sprintf('R%03d', $max + 1);
    }

    /**
     * Normalisasi data ruang sebelum disimpan.
     * nm_ruang & nama_ruang kept in sync karena sama-sama dipakai
     * oleh aplikasi (dropdown form inventaris & grafik/rekap).
     */
    function normalize(array $data)
    {
        $nama = trim((string) $data['nama_ruang']);
        $kode = trim((string) $data['kode_ruang']);

        $data['nama_ruang'] = $nama;
        $data['nm_ruang']   = $nama;
        $data['kode_ruang'] = $kode !== '' ? $kode : $nama;
        $data['gedung']     = trim((string) $data['gedung']);
        $data['lantai']     = (int) $data['lantai'];

        return $data;
    }
}
/* End of file Data_ruang_model.php */
/* Location: ./application/models/Data_ruang_model.php */
