<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pegawai_model extends CI_Model
{
    public $table = 'pegawai';
    public $id = 'id_pegawai';
    public $order = 'DESC';

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

    // get data by NIK (untuk login pegawai)
    function get_by_nik($nik)
    {
        $this->db->where('nik', $nik);
        return $this->db->get($this->table)->row_array();
    }

    // get data pegawai berdasarkan email (untuk role pegawai login via akun users)
    function get_by_email($email)
    {
        $this->db->where('email', trim($email));
        return $this->db->get($this->table)->row_array();
    }

    // cari pegawai berdasarkan NIK / NIP / email (untuk cegah duplikat saat import)
    function find_identity($nik = NULL, $nip = NULL, $email = NULL)
    {
        $found = FALSE;
        $this->db->group_start();
        if ($nik !== NULL && $nik !== '') {
            $this->db->where('nik', $nik);
            $found = TRUE;
        }
        if ($nip !== NULL && $nip !== '') {
            $found ? $this->db->or_where('nip', $nip) : $this->db->where('nip', $nip);
            $found = TRUE;
        }
        if ($email !== NULL && $email !== '') {
            $found ? $this->db->or_where('email', $email) : $this->db->where('email', $email);
            $found = TRUE;
        }
        $this->db->group_end();
        if (!$found) {
            return NULL;
        }
        return $this->db->order_by('id_pegawai', 'ASC')->get($this->table)->row();
    }

    // cek keunikan email (abaikan $except_id saat edit)
    function email_exists($email, $except_id = 0)
    {
        $email = trim((string) $email);
        if ($email === '') {
            return FALSE;
        }
        $this->db->where('email', $email);
        if ((int) $except_id > 0) {
            $this->db->where('id_pegawai !=', (int) $except_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    // daftar pegawai aktif (sebagai opsi target penilaian/pelaporan)
    function list_aktif()
    {
        $this->db->where('status', 'aktif');
        $this->db->order_by('nama', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // get total rows (opsi filter status: aktif/nonaktif/semua)
    function total_rows($q = NULL, $status = NULL)
    {
        if ($status !== NULL && $status !== '' && $status !== 'semua') {
            $this->db->where('status', $status);
        }
        if ($q !== NULL && $q !== '') {
            $this->db->group_start();
            $this->db->like('nik', $q);
            $this->db->or_like('nip', $q);
            $this->db->or_like('nama', $q);
            $this->db->or_like('jabatan', $q);
            $this->db->or_like('unit_kerja', $q);
            $this->db->or_like('email', $q);
            $this->db->group_end();
        }
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL, $status = NULL)
    {
        if ($status !== NULL && $status !== '' && $status !== 'semua') {
            $this->db->where('status', $status);
        }
        if ($q !== NULL && $q !== '') {
            $this->db->group_start();
            $this->db->like('nik', $q);
            $this->db->or_like('nip', $q);
            $this->db->or_like('nama', $q);
            $this->db->or_like('jabatan', $q);
            $this->db->or_like('unit_kerja', $q);
            $this->db->or_like('email', $q);
            $this->db->group_end();
        }
        $this->db->order_by($this->id, $this->order);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // statistik ringkas untuk header
    function count_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results($this->table);
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // ---- Riwayat Mutasi ----

    // ambil seluruh riwayat milik satu pegawai (termasuk nonaktif/aktif kembali)
    function get_history($id_pegawai)
    {
        $this->db->where('id_pegawai', $id_pegawai);
        $this->db->order_by('tanggal_mutasi', 'DESC');
        $this->db->order_by('id_mutasi', 'DESC');
        return $this->db->get('pegawai_mutasi')->result();
    }

    // simpan catatan riwayat
    function insert_mutasi($data)
    {
        $this->db->insert('pegawai_mutasi', $data);
    }

    // ---- Data pendukung ----

    // daftar unit kerja dari tabel unit (id_unit, nm_unit, jns_unit)
    function units()
    {
        $this->db->order_by('nm_unit', 'ASC');
        return $this->db->get('unit')->result();
    }

    // id_unit dari nama unit kerja (menjaga sinkronisasi pegawai.id_unit <-> pegawai.unit_kerja)
    function unit_id_by_name($nm_unit)
    {
        $nm_unit = trim((string) $nm_unit);
        if ($nm_unit === '') {
            return NULL;
        }
        $row = $this->db->select('id_unit')->where('nm_unit', $nm_unit)->get('unit')->row();
        return $row ? (int) $row->id_unit : NULL;
    }
}