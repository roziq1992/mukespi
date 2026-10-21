<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class E_library_model extends CI_Model
{
    public $table = 'el_entries';
    public $id = 'id';

    /** Kolom yang dicakup indeks FULLTEXT */
    protected $ft_fields = 'el_entries.judul, el_entries.abstrak, el_entries.penulis';

    public function __construct()
    {
        parent::__construct();
    }

    /* =====================================================================
     * KATALOG
     * ================================================================== */

    /**
     * Ambil entri katalog sesuai filter.
     *
     * @param array $filters q, source_id, tahun, subject, jenis, sort
     * @param int|null $limit
     * @param int $offset
     */
    public function get_entries($filters = array(), $limit = NULL, $offset = 0)
    {
        $this->db->select('el_entries.*, el_sources.nama AS nama_source, el_sources.homepage_url AS source_homepage');
        $this->db->from('el_entries');
        $this->db->join('el_sources', 'el_sources.id = el_entries.source_id', 'left');

        $this->_apply_filters($filters);
        $this->_apply_order($filters);

        if ($limit !== NULL) {
            $this->db->limit((int) $limit, (int) $offset);
        }

        return $this->db->get()->result();
    }

    /**
     * Total entri sesuai filter (untuk pagination).
     */
    public function count_entries($filters = array())
    {
        $this->db->from('el_entries');
        $this->db->join('el_sources', 'el_sources.id = el_entries.source_id', 'left');

        $this->_apply_filters($filters);

        return $this->db->count_all_results();
    }

    /**
     * Ambil satu entri beserta sumbernya.
     */
    public function get_by_id($id)
    {
        $this->db->select('el_entries.*, el_sources.nama AS nama_source, el_sources.homepage_url AS source_homepage, el_sources.penerbit AS source_penerbit, el_sources.deskripsi AS source_deskripsi');
        $this->db->from('el_entries');
        $this->db->join('el_sources', 'el_sources.id = el_entries.source_id', 'left');
        $this->db->where('el_entries.id', (int) $id);

        return $this->db->get()->row();
    }

    /**
     * Subjek yang menempel pada sebuah entri.
     */
    public function get_entry_subjects($entry_id)
    {
        $this->db->select('el_subjects.id, el_subjects.nama, el_subjects.slug');
        $this->db->from('el_entry_subjects');
        $this->db->join('el_subjects', 'el_subjects.id = el_entry_subjects.subject_id');
        $this->db->where('el_entry_subjects.entry_id', (int) $entry_id);
        $this->db->order_by('el_subjects.nama', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Daftar tahun terbit yang tersedia (untuk filter).
     */
    public function get_years()
    {
        $this->db->distinct();
        $this->db->select('tahun');
        $this->db->from('el_entries');
        $this->db->where('tahun IS NOT NULL');
        $this->db->where('tahun >', 0);
        $this->db->order_by('tahun', 'DESC');

        return array_column($this->db->get()->result_array(), 'tahun');
    }

    /**
     * Daftar sumber (untuk filter & pengelolaan admin).
     */
    public function get_sources($only_active = FALSE)
    {
        $this->db->from('el_sources');

        if ($only_active) {
            $this->db->where('aktif', 1);
        }

        $this->db->order_by('nama', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Daftar subjek (untuk filter).
     */
    public function get_subjects()
    {
        $this->db->from('el_subjects');
        $this->db->order_by('nama', 'ASC');

        return $this->db->get()->result();
    }

    /**
     * Entri yang baru saja dibuka oleh user (kartu "terakhir dilihat").
     */
    public function get_recent_entries($user_id, $limit = 5)
    {
        $this->db->select('el_entries.id, el_entries.judul, el_entries.tahun, el_entries.nama_jurnal, MAX(el_access_logs.created_at) AS terakhir_dibuka');
        $this->db->from('el_access_logs');
        $this->db->join('el_entries', 'el_entries.id = el_access_logs.entry_id');
        $this->db->where('el_access_logs.user_id', (int) $user_id);
        $this->db->where('el_access_logs.aksi', 'view');
        $this->db->group_by('el_entries.id');
        $this->db->order_by('terakhir_dibuka', 'DESC');
        $this->db->limit((int) $limit);

        return $this->db->get()->result();
    }

    /* =====================================================================
     * LOG AKSES
     * ================================================================== */

    /**
     * Catat akses entri (view / buka_sumber).
     */
    public function log_access($entry_id, $aksi = 'view')
    {
        $ci = get_instance();

        $this->db->insert('el_access_logs', array(
            'entry_id' => (int) $entry_id,
            'user_id' => (int) $ci->session->userdata('id'),
            'aksi' => $aksi,
            'ip' => $ci->input->ip_address(),
            'user_agent' => mb_substr((string) $ci->input->user_agent(), 0, 255, 'UTF-8'),
            'created_at' => date('Y-m-d H:i:s'),
        ));
    }

    /* =====================================================================
     * ENTRI - TULIS
     * ================================================================== */

    /**
     * Simpan entri baru.
     */
    public function insert_entry($data)
    {
        $this->db->insert('el_entries', $data);
        return $this->db->insert_id();
    }

    /**
     * Ubah entri.
     */
    public function update_entry($id, $data)
    {
        $this->db->where('id', (int) $id);
        $this->db->update('el_entries', $data);
        return $this->db->affected_rows();
    }

    /**
     * Hapus entri (beserta subjek & log aksesnya).
     */
    public function delete_entry($id)
    {
        $id = (int) $id;

        $this->db->where('entry_id', $id)->delete('el_entry_subjects');
        $this->db->where('entry_id', $id)->delete('el_access_logs');
        $this->db->where('id', $id)->delete('el_entries');

        return $this->db->affected_rows();
    }

    /**
     * Cari entri berdasarkan DOI (untuk deteksi duplikat saat impor).
     */
    public function get_entry_by_doi($doi)
    {
        $doi = trim((string) $doi);

        if ($doi === '') {
            return NULL;
        }

        return $this->db->where('doi', $doi)->limit(1)->get('el_entries')->row();
    }

    /**
     * Sinkronkan subjek yang menempel pada entri.
     */
    public function set_entry_subjects($entry_id, $subject_ids)
    {
        $entry_id = (int) $entry_id;

        $this->db->where('entry_id', $entry_id)->delete('el_entry_subjects');

        $ids = array_values(array_unique(array_filter(array_map('intval', (array) $subject_ids))));

        if (empty($ids)) {
            return;
        }

        foreach ($ids as $sid) {
            $this->db->insert('el_entry_subjects', array(
                'entry_id' => $entry_id,
                'subject_id' => $sid,
            ));
        }
    }

    /* =====================================================================
     * SUMBER - TULIS
     * ================================================================== */

    public function get_source_by_id($id)
    {
        return $this->db->where('id', (int) $id)->get('el_sources')->row();
    }

    public function insert_source($data)
    {
        $this->db->insert('el_sources', $data);
        return $this->db->insert_id();
    }

    public function update_source($id, $data)
    {
        $this->db->where('id', (int) $id);
        $this->db->update('el_sources', $data);
        return $this->db->affected_rows();
    }

    /**
     * Hapus sumber; entri yang memakai sumber ini tidak ikut terhapus,
     * kolom source_id-nya menjadi NULL (sumber tetap tampil sebagai "-").
     */
    public function delete_source($id)
    {
        $id = (int) $id;

        $this->db->where('source_id', $id)->update('el_entries', array('source_id' => NULL));
        $this->db->where('id', $id)->delete('el_sources');

        return $this->db->affected_rows();
    }

    /* =====================================================================
     * SUBJEK - TULIS
     * ================================================================== */

    public function get_subject_by_slug($slug)
    {
        return $this->db->where('slug', $slug)->limit(1)->get('el_subjects')->row();
    }

    /**
     * Buat subjek bila belum ada (dikembalikan sebagai id).
     */
    public function ensure_subject($nama)
    {
        $nama = trim((string) $nama);

        if ($nama === '') {
            return 0;
        }

        $slug = $this->_slugify($nama);
        $existing = $this->get_subject_by_slug($slug);

        if ($existing) {
            return (int) $existing->id;
        }

        $this->db->insert('el_subjects', array(
            'nama' => $nama,
            'slug' => $slug,
            'created_at' => date('Y-m-d H:i:s'),
        ));

        return (int) $this->db->insert_id();
    }

    public function delete_subject($id)
    {
        $id = (int) $id;

        $this->db->where('subject_id', $id)->delete('el_entry_subjects');
        $this->db->where('id', $id)->delete('el_subjects');

        return $this->db->affected_rows();
    }

    /**
     * Ubah nama subjek (slug ikut diperbarui bila berubah).
     */
    public function update_subject($id, $nama)
    {
        $nama = trim((string) $nama);

        if ($nama === '') {
            return FALSE;
        }

        $this->db->where('id', (int) $id);
        $this->db->update('el_subjects', array(
            'nama' => $nama,
            'slug' => $this->_slugify($nama),
        ));

        return $this->db->affected_rows();
    }

    /**
     * Slug URL-safe dari nama subjek.
     */
    public function _slugify($text)
    {
        $text = strtolower(trim((string) $text));
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text);
        $text = trim($text, '-');

        return $text === '' ? 'subjek' : $text;
    }

    /* =====================================================================
     * LOG AKSES - PEMBACAAN (admin)
     * ================================================================== */

    /**
     * Daftar log akses (paling baru dulu).
     */
    public function get_access_logs($limit = 50, $offset = 0, $filters = array())
    {
        $this->db->select('el_access_logs.*, el_entries.judul, users.name AS nama_user, users.email AS email_user');
        $this->db->from('el_access_logs');
        $this->db->join('el_entries', 'el_entries.id = el_access_logs.entry_id', 'left');
        $this->db->join('users', 'users.id = el_access_logs.user_id', 'left');

        if (!empty($filters['aksi'])) {
            $this->db->where('el_access_logs.aksi', $filters['aksi']);
        }

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $this->db->group_start();
            $this->db->like('el_entries.judul', $q);
            $this->db->or_like('users.name', $q);
            $this->db->or_like('users.email', $q);
            $this->db->group_end();
        }

        $this->db->order_by('el_access_logs.created_at', 'DESC');
        $this->db->limit((int) $limit, (int) $offset);

        return $this->db->get()->result();
    }

    public function count_access_logs($filters = array())
    {
        $this->db->from('el_access_logs');
        $this->db->join('el_entries', 'el_entries.id = el_access_logs.entry_id', 'left');
        $this->db->join('users', 'users.id = el_access_logs.user_id', 'left');

        if (!empty($filters['aksi'])) {
            $this->db->where('el_access_logs.aksi', $filters['aksi']);
        }

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $this->db->group_start();
            $this->db->like('el_entries.judul', $q);
            $this->db->or_like('users.name', $q);
            $this->db->or_like('users.email', $q);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    /**
     * Ringkasan jumlah klik per entri (10 teratas).
     */
    public function top_accessed($limit = 10)
    {
        $this->db->select('el_entries.id, el_entries.judul, COUNT(el_access_logs.id) AS jumlah');
        $this->db->from('el_access_logs');
        $this->db->join('el_entries', 'el_entries.id = el_access_logs.entry_id');
        $this->db->group_by('el_entries.id');
        $this->db->order_by('jumlah', 'DESC');
        $this->db->limit((int) $limit);

        return $this->db->get()->result();
    }

    /* =====================================================================
     * FILTER & PENGURATAN
     * ================================================================== */

    /**
     * Susun kondisi WHERE berdasarkan filter.
     */
    protected function _apply_filters($filters = array())
    {
        $filters = array_merge(array(
            'q' => '',
            'source_id' => '',
            'tahun' => '',
            'subject' => '',
            'jenis' => '',
        ), $filters);

        if ($filters['q'] !== '') {
            $this->_apply_search($filters['q']);
        }

        if ($filters['source_id'] !== '' && (int) $filters['source_id'] > 0) {
            $this->db->where('el_entries.source_id', (int) $filters['source_id']);
        }

        if ($filters['tahun'] !== '' && (int) $filters['tahun'] > 0) {
            $this->db->where('el_entries.tahun', (int) $filters['tahun']);
        }

        if ($filters['jenis'] !== '') {
            $this->db->where('el_entries.jenis', $filters['jenis']);
        }

        if ($filters['subject'] !== '') {
            $this->db->join('el_entry_subjects', 'el_entry_subjects.entry_id = el_entries.id', 'inner');
            $this->db->join('el_subjects', 'el_subjects.id = el_entry_subjects.subject_id', 'inner');
            $this->db->where('el_subjects.slug', $filters['subject']);
        }
    }

    /**
     * Pencarian bebas: judul, penulis, abstrak, DOI, nama jurnal, nama sumber.
     * Memakai indeks FULLTEXT + pel pencocokan sebagian (LIKE) agar pencarian
     * dengan kata pendek / kata umum tetap menemukan hasil.
     */
    protected function _apply_search($q)
    {
        $tokens = preg_split('/\s+/u', trim($q), -1, PREG_SPLIT_NO_EMPTY);

        if (empty($tokens)) {
            return;
        }

        foreach ($tokens as $token) {
            if (mb_strlen($token, 'UTF-8') < 2) {
                continue;
            }

            $ft = $this->_fulltext_token($token);

            $this->db->group_start();

            if ($ft !== '') {
                $this->db->where(
                    'MATCH(' . $this->ft_fields . ') AGAINST(' . $this->db->escape($ft) . ' IN BOOLEAN MODE)',
                    NULL,
                    FALSE
                );
            }

            $this->db->or_like('el_entries.judul', $token);
            $this->db->or_like('el_entries.penulis', $token);
            $this->db->or_like('el_entries.abstrak', $token);
            $this->db->or_like('el_entries.doi', $token);
            $this->db->or_like('el_entries.nama_jurnal', $token);
            $this->db->or_like('el_entries.kata_kunci', $token);
            $this->db->or_like('el_sources.nama', $token);

            $this->db->group_end();
        }
    }

    /**
     * Bersihkan kata kunci menjadi token boolean FULLTEXT.
     * Hanya huruf & angka yang diizinkan agar sintaks boolean mode
     * (mis. "1=1" atau "--") tidak merusak query.
     */
    protected function _fulltext_token($token)
    {
        $token = preg_replace('/[^\p{L}\p{N} ]+/u', ' ', (string) $token);
        $token = trim(preg_replace('/\s+/u', ' ', $token));

        if ($token === '') {
            return '';
        }

        return '+' . $token . '*';
    }

    /**
     * Urutan hasil.
     */
    protected function _apply_order($filters = array())
    {
        $sort = isset($filters['sort']) ? $filters['sort'] : '';

        switch ($sort) {
            case 'tahun':
                $this->db->order_by('el_entries.tahun', 'DESC');
                $this->db->order_by('el_entries.id', 'DESC');
                break;

            case 'judul':
                $this->db->order_by('el_entries.judul', 'ASC');
                break;

            case 'sitasi':
                $this->db->order_by('el_entries.sitasi', 'DESC');
                $this->db->order_by('el_entries.tahun', 'DESC');
                break;

            case 'terlama':
                $this->db->order_by('el_entries.tanggal_masuk', 'ASC');
                break;

            default:
                $this->db->order_by('el_entries.tanggal_masuk', 'DESC');
                $this->db->order_by('el_entries.id', 'DESC');
                break;
        }
    }
}
