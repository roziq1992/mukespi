<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * E-Library — Portal Penemuan Literatur.
 *
 * Modul katalog literatur: seluruh staff (role apa pun) dapat menjelajah,
 * mencari, dan membuka tautan sumber eksternal. Modul ini hanya menyimpan
 * metadata; berkas asli (PDF) tetap berada di sumber aslinya.
 *
 * Pengelolaan (CRUD entri, sumber, subjek, impor CSV) hanya untuk admin (role_id 1).
 */
class E_library extends CI_Controller
{
    /** Jumlah entri per halaman */
    protected $per_page = 12;

    /** Jumlah baris log akses per halaman */
    protected $log_per_page = 25;

    /** Kolom template CSV impor */
    protected $csv_columns = array(
        'judul', 'penulis', 'abstrak', 'tahun', 'nama_jurnal', 'doi',
        'url_asli', 'kata_kunci', 'volume', 'nomor', 'halaman', 'jenis',
        'bahasa', 'sumber', 'subjek',
    );

    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        $this->load->model('E_library_model');
        $this->load->helper('elibrary');
        $this->load->library('form_validation');
    }

    /* =====================================================================
     * KATALOG (semua user yang sudah login)
     * ================================================================== */

    public function index()
    {
        $filters = $this->_filters();

        $config = array();
        $config['base_url'] = base_url('index.php/e_library');
        $config['first_url'] = $config['base_url'];

        $qs = $this->_query_string($filters);
        if ($qs !== '') {
            $config['base_url'] .= '?' . $qs;
            $config['first_url'] = $config['base_url'];
        }

        $config['per_page'] = $this->per_page;
        $config['page_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $this->E_library_model->count_entries($filters);

        // Pagination memakai query string "start" sebagai nomor halaman
        // (application/config/pagination.php).
        $page = (int) $this->input->get('start');
        if ($page < 1) {
            $page = 1;
        }
        $start = ($page - 1) * $this->per_page;

        $entries = $this->E_library_model->get_entries($filters, $this->per_page, $start);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'entries' => $entries,
            'filters' => $filters,
            'sources' => $this->E_library_model->get_sources(TRUE),
            'subjects' => $this->E_library_model->get_subjects(),
            'years' => $this->E_library_model->get_years(),
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'page' => $page,
            'per_page' => $this->per_page,
            'total_entries' => $this->E_library_model->count_entries(array()),
            'total_sources' => count($this->E_library_model->get_sources(TRUE)),
            'recent' => $this->E_library_model->get_recent_entries((int) $this->session->userdata('id'), 5),
            'is_admin' => $this->_is_admin(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('elibrary/elibrary_list', $data);
        $this->load->view('template/footer');
    }

    public function detail($id)
    {
        $row = $this->E_library_model->get_by_id($id);

        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data literatur tidak ditemukan.</div>');
            redirect(site_url('e_library'));
        }

        $this->E_library_model->log_access($row->id, 'view');

        $data = array(
            'entry' => $row,
            'subjects' => $this->E_library_model->get_entry_subjects($row->id),
            'is_admin' => $this->_is_admin(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('elibrary/elibrary_read', $data);
        $this->load->view('template/footer');
    }

    /**
     * Buka literatur di sumber aslinya (log klik lalu arahkan ke URL sumber).
     */
    public function buka($id)
    {
        $row = $this->E_library_model->get_by_id($id);

        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data literatur tidak ditemukan.</div>');
            redirect(site_url('e_library'));
        }

        $url = elibrary_safe_url($row->url_asli);

        if ($url === '') {
            $this->session->set_flashdata('message', '<div class="alert alert-warning">Entri ini belum memiliki tautan sumber yang valid.</div>');
            redirect(site_url('e_library/detail/' . $row->id));
        }

        $this->E_library_model->log_access($row->id, 'buka_sumber');

        redirect($url);
    }

    /* =====================================================================
     * ADMIN - KELOLA ENTRI
     * ================================================================== */

    /**
     * Daftar entri untuk dikelola admin.
     */
    public function manage()
    {
        $this->_must_be_admin();

        $filters = $this->_filters();

        $config = array();
        $config['base_url'] = base_url('index.php/e_library/manage');
        $config['first_url'] = $config['base_url'];

        $qs = $this->_query_string($filters);
        if ($qs !== '') {
            $config['base_url'] .= '?' . $qs;
            $config['first_url'] = $config['base_url'];
        }

        $config['per_page'] = 20;
        $config['page_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $this->E_library_model->count_entries($filters);

        $page = (int) $this->input->get('start');
        if ($page < 1) {
            $page = 1;
        }
        $start = ($page - 1) * $config['per_page'];

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'entries' => $this->E_library_model->get_entries($filters, $config['per_page'], $start),
            'filters' => $filters,
            'sources' => $this->E_library_model->get_sources(TRUE),
            'subjects' => $this->E_library_model->get_subjects(),
            'years' => $this->E_library_model->get_years(),
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'page' => $page,
            'is_admin' => TRUE,
        );

        $this->load->view('template/header', $data);
        $this->load->view('elibrary/elibrary_manage', $data);
        $this->load->view('template/footer');
    }

    /**
     * Form entri baru.
     */
    public function create()
    {
        $this->_must_be_admin();

        $data = array(
            'entry' => NULL,
            'subjects' => $this->E_library_model->get_subjects(),
            'sources' => $this->E_library_model->get_sources(),
            'selected_subjects' => array(),
            'action' => site_url('e_library/create_action'),
            'cancel_url' => site_url('e_library/manage'),
            'is_admin' => TRUE,
        );

        $this->load->view('template/header', $data);
        $this->load->view('elibrary/elibrary_form', $data);
        $this->load->view('template/footer');
    }

    /**
     * Simpan entri baru.
     */
    public function create_action()
    {
        $this->_must_be_admin();
        $this->_csrf_guard();

        $this->_entry_rules();

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'entry' => NULL,
                'subjects' => $this->E_library_model->get_subjects(),
                'sources' => $this->E_library_model->get_sources(),
                'selected_subjects' => (array) $this->input->post('subject_ids'),
                'action' => site_url('e_library/create_action'),
                'cancel_url' => site_url('e_library/manage'),
                'is_admin' => TRUE,
            );
            $this->load->view('template/header', $data);
            $this->load->view('elibrary/elibrary_form', $data);
            $this->load->view('template/footer');
            return;
        }

        // DOI harus unik dalam katalog.
        $doi = trim((string) $this->input->post('doi'));
        if ($doi !== '' && $this->E_library_model->get_entry_by_doi($doi)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">DOI sudah dipakai entri lain. Gunakan DOI yang berbeda atau kosongkan.</div>');
            redirect(site_url('e_library/create'));
        }

        $payload = $this->_entry_payload();
        $payload['created_by'] = (int) $this->session->userdata('id');
        $payload['created_at'] = date('Y-m-d H:i:s');

        $id = $this->E_library_model->insert_entry($payload);
        $this->E_library_model->set_entry_subjects($id, (array) $this->input->post('subject_ids'));

        $this->session->set_flashdata('message', '<div class="alert alert-success">Entri literatur berhasil disimpan.</div>');
        redirect(site_url('e_library/manage'));
    }

    /**
     * Form ubah entri.
     */
    public function update($id)
    {
        $this->_must_be_admin();

        $row = $this->E_library_model->get_by_id($id);

        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data literatur tidak ditemukan.</div>');
            redirect(site_url('e_library/manage'));
        }

        $selected = array();
        foreach ($this->E_library_model->get_entry_subjects($row->id) as $sub) {
            $selected[] = (int) $sub->id;
        }

        $data = array(
            'entry' => $row,
            'subjects' => $this->E_library_model->get_subjects(),
            'sources' => $this->E_library_model->get_sources(),
            'selected_subjects' => $selected,
            'action' => site_url('e_library/update_action/' . $row->id),
            'cancel_url' => site_url('e_library/manage'),
            'is_admin' => TRUE,
        );

        $this->load->view('template/header', $data);
        $this->load->view('elibrary/elibrary_form', $data);
        $this->load->view('template/footer');
    }

    /**
     * Simpan perubahan entri.
     */
    public function update_action($id)
    {
        $this->_must_be_admin();
        $this->_csrf_guard();

        $row = $this->E_library_model->get_by_id($id);

        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data literatur tidak ditemukan.</div>');
            redirect(site_url('e_library/manage'));
        }

        $this->_entry_rules();

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'entry' => $row,
                'subjects' => $this->E_library_model->get_subjects(),
                'sources' => $this->E_library_model->get_sources(),
                'selected_subjects' => (array) $this->input->post('subject_ids'),
                'action' => site_url('e_library/update_action/' . $row->id),
                'cancel_url' => site_url('e_library/manage'),
                'is_admin' => TRUE,
            );
            $this->load->view('template/header', $data);
            $this->load->view('elibrary/elibrary_form', $data);
            $this->load->view('template/footer');
            return;
        }

        // DOI harus unik dalam katalog (kecuali entri ini sendiri).
        $doi = trim((string) $this->input->post('doi'));
        if ($doi !== '') {
            $existing = $this->E_library_model->get_entry_by_doi($doi);
            if ($existing && (int) $existing->id !== (int) $row->id) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">DOI sudah dipakai entri lain. Gunakan DOI yang berbeda atau kosongkan.</div>');
                redirect(site_url('e_library/update/' . $row->id));
            }
        }

        $this->E_library_model->update_entry($row->id, $this->_entry_payload());
        $this->E_library_model->set_entry_subjects($row->id, (array) $this->input->post('subject_ids'));

        $this->session->set_flashdata('message', '<div class="alert alert-success">Entri literatur berhasil diperbarui.</div>');
        redirect(site_url('e_library/manage'));
    }

    /**
     * Hapus entri.
     */
    public function delete_action($id)
    {
        $this->_must_be_admin();
        $this->_csrf_guard();

        $row = $this->E_library_model->get_by_id($id);

        if ($row) {
            $this->E_library_model->delete_entry($row->id);
            $this->session->set_flashdata('message', '<div class="alert alert-success">Entri literatur berhasil dihapus.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data literatur tidak ditemukan.</div>');
        }

        redirect(site_url('e_library/manage'));
    }

    /* =====================================================================
     * ADMIN - SUMBER
     * ================================================================== */

    public function source()
    {
        $this->_must_be_admin();

        $data = array(
            'sources' => $this->E_library_model->get_sources(),
            'is_admin' => TRUE,
        );

        $this->load->view('template/header', $data);
        $this->load->view('elibrary/elibrary_source_list', $data);
        $this->load->view('template/footer');
    }

    public function source_form($id = 0)
    {
        $this->_must_be_admin();

        $source = NULL;

        if ((int) $id > 0) {
            $source = $this->E_library_model->get_source_by_id($id);

            if (!$source) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Sumber tidak ditemukan.</div>');
                redirect(site_url('e_library/source'));
            }
        }

        $data = array(
            'source' => $source,
            'action' => site_url('e_library/source_action/' . (int) $id),
            'cancel_url' => site_url('e_library/source'),
            'is_admin' => TRUE,
        );

        $this->load->view('template/header', $data);
        $this->load->view('elibrary/elibrary_source_form', $data);
        $this->load->view('template/footer');
    }

    public function source_action($id = 0)
    {
        $this->_must_be_admin();
        $this->_csrf_guard();

        $this->form_validation->set_rules('nama', 'Nama sumber', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('penerbit', 'Penerbit', 'trim|max_length[255]');
        $this->form_validation->set_rules('homepage_url', 'Halaman utama', 'trim|max_length[500]');
        $this->form_validation->set_rules('oai_pmh_url', 'URL OAI-PMH', 'trim|max_length[500]');
        $this->form_validation->set_rules('aktif', 'Status', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $source = NULL;
            if ((int) $id > 0) {
                $source = $this->E_library_model->get_source_by_id($id);
            }
            $data = array(
                'source' => $source,
                'action' => site_url('e_library/source_action/' . (int) $id),
                'cancel_url' => site_url('e_library/source'),
                'is_admin' => TRUE,
            );
            $this->load->view('template/header', $data);
            $this->load->view('elibrary/elibrary_source_form', $data);
            $this->load->view('template/footer');
            return;
        }

        $payload = array(
            'nama' => $this->input->post('nama'),
            'penerbit' => $this->input->post('penerbit'),
            'deskripsi' => $this->input->post('deskripsi'),
            'homepage_url' => $this->input->post('homepage_url'),
            'oai_pmh_url' => $this->input->post('oai_pmh_url'),
            'aktif' => $this->input->post('aktif') ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        );

        // Nama sumber harus unik (ada UNIQUE key di tabel el_sources).
        $dup = $this->db->where('nama', $payload['nama']);
        if ((int) $id > 0) {
            $dup->where('id !=', (int) $id);
        }
        $dup = $dup->limit(1)->get('el_sources')->row();

        if ($dup) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Nama sumber sudah dipakai. Gunakan nama lain.</div>');
            redirect(site_url('e_library/source_form/' . (int) $id));
        }

        if ((int) $id > 0) {
            $this->E_library_model->update_source($id, $payload);
            $msg = 'Sumber berhasil diperbarui.';
        } else {
            $payload['created_at'] = date('Y-m-d H:i:s');
            $this->E_library_model->insert_source($payload);
            $msg = 'Sumber berhasil ditambahkan.';
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success">' . $msg . '</div>');
        redirect(site_url('e_library/source'));
    }

    public function source_delete_action($id)
    {
        $this->_must_be_admin();
        $this->_csrf_guard();

        $this->E_library_model->delete_source($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Sumber berhasil dihapus.</div>');

        redirect(site_url('e_library/source'));
    }

    /* =====================================================================
     * ADMIN - SUBJEK
     * ================================================================== */

    public function subject()
    {
        $this->_must_be_admin();

        $data = array(
            'subjects' => $this->E_library_model->get_subjects(),
            'is_admin' => TRUE,
        );

        $this->load->view('template/header', $data);
        $this->load->view('elibrary/elibrary_subject_list', $data);
        $this->load->view('template/footer');
    }

    public function subject_action()
    {
        $this->_must_be_admin();
        $this->_csrf_guard();

        $nama = trim((string) $this->input->post('nama'));

        if ($nama === '') {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Nama subjek tidak boleh kosong.</div>');
            redirect(site_url('e_library/subject'));
        }

        $id = (int) $this->input->post('id');

        if ($id > 0) {
            $this->E_library_model->update_subject($id, $nama);
            $msg = 'Subjek berhasil diperbarui.';
        } else {
            $this->E_library_model->ensure_subject($nama);
            $msg = 'Subjek berhasil ditambahkan.';
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success">' . $msg . '</div>');
        redirect(site_url('e_library/subject'));
    }

    public function subject_delete_action($id)
    {
        $this->_must_be_admin();
        $this->_csrf_guard();

        $this->E_library_model->delete_subject($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Subjek berhasil dihapus.</div>');

        redirect(site_url('e_library/subject'));
    }

    /* =====================================================================
     * ADMIN - IMPOR CSV
     * ================================================================== */

    public function import()
    {
        $this->_must_be_admin();

        $data = array(
            'report' => NULL,
            'is_admin' => TRUE,
        );

        $this->load->view('template/header', $data);
        $this->load->view('elibrary/elibrary_import', $data);
        $this->load->view('template/footer');
    }

    /**
     * Proses impor CSV (validasi per baris + laporan error).
     */
    public function import_action()
    {
        $this->_must_be_admin();
        $this->_csrf_guard();

        $this->load->library('upload');

        // CI 3.1: do_upload() hanya menerima nama field, konfigurasi lewat initialize().
        $this->upload->initialize(array(
            'upload_path' => FCPATH . 'uploads/',
            'allowed_types' => 'csv|txt',
            'encrypt_name' => TRUE,
            'max_size' => 4096,
        ));

        if (!$this->upload->do_upload('berkas')) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal mengunggah berkas: ' . $this->upload->display_errors('', '') . '</div>');
            redirect(site_url('e_library/import'));
        }

        $upload = $this->upload->data();
        $path = $upload['full_path'];

        $report = array('ok' => array(), 'error' => array(), 'total' => 0);

        $handle = fopen($path, 'r');

        if ($handle === FALSE) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Berkas tidak dapat dibaca.</div>');
            redirect(site_url('e_library/import'));
        }

        $baris = 0;
        $header = fgetcsv($handle);

        if ($header === FALSE) {
            fclose($handle);
            @unlink($path);
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Berkas kosong atau tidak terbaca.</div>');
            redirect(site_url('e_library/import'));
        }

        // Samakan nama kolom dengan template (abaikan urutan, huruf besar,
        // spasi, dan garis bawah).
        $norm = function ($s) {
            $s = strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', (string) $s)));
            return str_replace(array(' ', '_', '-'), '', $s);
        };

        $map = array();
        foreach ($header as $idx => $col) {
            $map[$norm($col)] = $idx;
        }

        $ada_kolom_wajib = isset($map['judul']) && (isset($map['urlasli']) || isset($map['url']) || isset($map['link']));

        if (!$ada_kolom_wajib) {
            fclose($handle);
            @unlink($path);
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Header CSV tidak sesuai. Wajib ada kolom <strong>judul</strong> dan <strong>url_asli</strong>.</div>');
            redirect(site_url('e_library/import'));
        }

        while (($row = fgetcsv($handle)) !== FALSE) {
            // lewati baris kosong
            if (is_array($row) && count(array_filter($row, function ($v) { return trim((string) $v) !== ''; })) === 0) {
                continue;
            }

            $baris++;

            $get = function ($aliases) use ($row, $map, $norm) {
                foreach ((array) $aliases as $alias) {
                    $key = $norm($alias);
                    if (isset($map[$key]) && isset($row[$map[$key]])) {
                        return trim((string) $row[$map[$key]]);
                    }
                }
                return '';
            };

            $judul = $get(array('judul', 'title'));
            $url = $get(array('url_asli', 'url', 'link', 'tautan'));
            $tahun = $get(array('tahun', 'year'));
            $doi = $get(array('doi'));
            $sumber = $get(array('sumber', 'source'));
            $subjek = $get(array('subjek', 'subject', 'keywords_subyek'));
            $jenis = strtolower($get(array('jenis', 'type')));

            $errors = array();

            if ($judul === '') {
                $errors[] = 'kolom "judul" wajib diisi';
            }
            if (strlen($judul) > 500) {
                $errors[] = 'kolom "judul" melebihi 500 karakter';
            }
            if ($url === '') {
                $errors[] = 'kolom "url_asli" wajib diisi';
            } elseif (elibrary_safe_url($url) === '') {
                $errors[] = 'kolom "url_asli" bukan URL http/https yang valid';
            }
            if ($tahun !== '' && (!preg_match('/^\d{4}$/', $tahun) || (int) $tahun < 1900 || (int) $tahun > 2099)) {
                $errors[] = 'kolom "tahun" harus berupa 4 digit (1900-2099)';
            }
            if ($doi !== '' && $this->E_library_model->get_entry_by_doi($doi)) {
                $errors[] = 'DOI sudah terdaftar pada katalog';
            }
            if ($jenis === '') {
                $jenis = 'artikel';
            }
            if (!in_array($jenis, array('artikel', 'buku', 'tesis', 'laporan', 'conference', 'lainnya'), TRUE)) {
                $errors[] = 'kolom "jenis" tidak dikenal (' . $jenis . ')';
            }

            if (!empty($errors)) {
                $report['error'][] = array(
                    'baris' => $baris,
                    'judul' => $judul !== '' ? $judul : '(kosong)',
                    'pesan' => implode('; ', $errors),
                );
                continue;
            }

            $source_id = NULL;
            if ($sumber !== '') {
                $source_id = $this->_ensure_source($sumber);
            }

            $subject_ids = array();
            foreach (preg_split('/[,;]+/u', $subjek) as $sub) {
                $sub = trim($sub);
                if ($sub !== '') {
                    $subject_ids[] = $this->E_library_model->ensure_subject($sub);
                }
            }

            $id = $this->E_library_model->insert_entry(array(
                'source_id' => $source_id,
                'judul' => $judul,
                'penulis' => $get(array('penulis', 'author')),
                'abstrak' => $get(array('abstrak', 'abstract')),
                'tahun' => $tahun !== '' ? (int) $tahun : NULL,
                'volume' => $get(array('volume')),
                'nomor' => $get(array('nomor', 'number', 'issue')),
                'halaman' => $get(array('halaman', 'pages')),
                'doi' => $doi !== '' ? $doi : NULL,
                'nama_jurnal' => $get(array('nama_jurnal', 'jurnal', 'journal')) !== '' ? $get(array('nama_jurnal', 'jurnal', 'journal')) : NULL,
                'url_asli' => $url,
                'kata_kunci' => $get(array('kata_kunci', 'keywords', 'keyword')) !== '' ? $get(array('kata_kunci', 'keywords', 'keyword')) : NULL,
                'jenis' => $jenis,
                'bahasa' => $get(array('bahasa', 'language')),
                'sitasi' => 0,
                'tanggal_masuk' => date('Y-m-d'),
                'created_by' => (int) $this->session->userdata('id'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ));

            if (!empty($subject_ids)) {
                $this->E_library_model->set_entry_subjects($id, $subject_ids);
            }

            $report['ok'][] = array(
                'baris' => $baris,
                'judul' => $judul,
                'id' => $id,
            );
        }

        fclose($handle);
        @unlink($path);

        $report['total'] = $baris;

        $data = array(
            'report' => $report,
            'is_admin' => TRUE,
        );

        $this->load->view('template/header', $data);
        $this->load->view('elibrary/elibrary_import', $data);
        $this->load->view('template/footer');
    }

    /**
     * Unduh template CSV.
     */
    public function template_csv()
    {
        $this->_must_be_admin();

        $filename = 'template_e_library.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, $this->csv_columns);
        fputcsv($out, array(
            'Judul artikel contoh',
            'Ahmad, B.; Siti, C.',
            'Abstrak ringkas artikel.',
            '2024',
            'Jurnal Manajemen Mutu Kesehatan',
            '10.1234/contoh',
            'https://jurnal.example.org/artikel/1',
            'mutu; keselamatan pasien',
            '12',
            '3',
            '45-52',
            'artikel',
            'Indonesia',
            'Jurnal Manajemen Mutu Kesehatan',
            'Manajemen Mutu Kesehatan; Keselamatan Pasien',
        ));
        fclose($out);
        exit;
    }

    /* =====================================================================
     * ADMIN - LOG AKSES
     * ================================================================== */

    public function access_log()
    {
        $this->_must_be_admin();

        $filters = array(
            'q' => trim((string) $this->input->get('q', TRUE)),
            'aksi' => trim((string) $this->input->get('aksi', TRUE)),
        );

        $config = array();
        $config['base_url'] = base_url('index.php/e_library/access_log');
        $config['first_url'] = $config['base_url'];

        $qs = array();
        if ($filters['q'] !== '') {
            $qs['q'] = $filters['q'];
        }
        if ($filters['aksi'] !== '') {
            $qs['aksi'] = $filters['aksi'];
        }
        if (!empty($qs)) {
            $config['base_url'] .= '?' . http_build_query($qs);
            $config['first_url'] = $config['base_url'];
        }

        $config['per_page'] = $this->log_per_page;
        $config['page_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $this->E_library_model->count_access_logs($filters);

        $page = (int) $this->input->get('start');
        if ($page < 1) {
            $page = 1;
        }
        $start = ($page - 1) * $this->log_per_page;

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'logs' => $this->E_library_model->get_access_logs($this->log_per_page, $start, $filters),
            'filters' => $filters,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'top' => $this->E_library_model->top_accessed(10),
            'is_admin' => TRUE,
        );

        $this->load->view('template/header', $data);
        $this->load->view('elibrary/elibrary_access_log', $data);
        $this->load->view('template/footer');
    }

    /* =====================================================================
     * BANTUAN
     * ================================================================== */

    /**
     * Admin = role_id 1.
     */
    protected function _is_admin()
    {
        return (int) $this->session->userdata('role_id') === 1;
    }

    /**
     * Halaman khusus admin.
     */
    protected function _must_be_admin()
    {
        if (!$this->_is_admin()) {
            redirect('auth/blocked');
            exit;
        }
    }

    /**
     * Verifikasi token CSRF modul.
     */
    protected function _csrf_guard()
    {
        if (!elibrary_csrf_check()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Token keamanan tidak valid atau kedaluwarsa. Silakan ulangi.</div>');
            redirect($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : site_url('e_library/manage'));
            exit;
        }
    }

    /**
     * Aturan validasi form entri.
     */
    protected function _entry_rules()
    {
        $this->form_validation->set_rules('judul', 'Judul', 'required|trim|max_length[500]');
        $this->form_validation->set_rules('penulis', 'Penulis', 'trim|max_length[500]');
        $this->form_validation->set_rules('abstrak', 'Abstrak', 'trim');
        $this->form_validation->set_rules('tahun', 'Tahun', 'trim|integer|min_length[4]|max_length[4]');
        $this->form_validation->set_rules('volume', 'Volume', 'trim|max_length[50]');
        $this->form_validation->set_rules('nomor', 'Nomor', 'trim|max_length[50]');
        $this->form_validation->set_rules('halaman', 'Halaman', 'trim|max_length[50]');
        $this->form_validation->set_rules('doi', 'DOI', 'trim|max_length[255]');
        $this->form_validation->set_rules('nama_jurnal', 'Nama Jurnal', 'trim|max_length[255]');
        $this->form_validation->set_rules('url_asli', 'URL Sumber', 'required|trim|max_length[1000]');
        $this->form_validation->set_rules('kata_kunci', 'Kata Kunci', 'trim|max_length[500]');
        $this->form_validation->set_rules('bahasa', 'Bahasa', 'trim|max_length[50]');
        $this->form_validation->set_rules('sitasi', 'Sitasi', 'trim|integer|greater_than_equal_to[0]');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required|trim');
        $this->form_validation->set_rules('source_id', 'Sumber', 'trim|integer');
        $this->form_validation->set_rules('jenis', 'Jenis', 'trim|max_length[50]');

        $this->form_validation->set_message('required', '%s wajib diisi.');
        $this->form_validation->set_message('max_length', '%s maksimal %s karakter.');
        $this->form_validation->set_message('integer', '%s harus berupa angka.');
    }

    /**
     * Data entri siap simpan.
     */
    protected function _entry_payload()
    {
        $user_id = (int) $this->session->userdata('id');
        $source_id = (int) $this->input->post('source_id');
        $jenis = strtolower(trim((string) $this->input->post('jenis')));
        $tahun = trim((string) $this->input->post('tahun'));
        $tanggal = trim((string) $this->input->post('tanggal_masuk'));

        if ($tanggal === '') {
            $tanggal = date('Y-m-d');
        }

        if (!in_array($jenis, array('artikel', 'buku', 'tesis', 'laporan', 'conference', 'lainnya'), TRUE)) {
            $jenis = 'artikel';
        }

        return array(
            'source_id' => $source_id > 0 ? $source_id : NULL,
            'judul' => $this->input->post('judul'),
            'penulis' => $this->input->post('penulis'),
            'abstrak' => $this->input->post('abstrak'),
            'tahun' => $tahun !== '' ? (int) $tahun : NULL,
            'volume' => $this->input->post('volume'),
            'nomor' => $this->input->post('nomor'),
            'halaman' => $this->input->post('halaman'),
            'doi' => $this->input->post('doi') !== '' ? $this->input->post('doi') : NULL,
            'nama_jurnal' => $this->input->post('nama_jurnal') !== '' ? $this->input->post('nama_jurnal') : NULL,
            'url_asli' => $this->input->post('url_asli'),
            'kata_kunci' => $this->input->post('kata_kunci') !== '' ? $this->input->post('kata_kunci') : NULL,
            'jenis' => $jenis,
            'bahasa' => $this->input->post('bahasa') !== '' ? $this->input->post('bahasa') : NULL,
            'sitasi' => (int) $this->input->post('sitasi'),
            'tanggal_masuk' => $tanggal,
            'updated_by' => $user_id,
            'updated_at' => date('Y-m-d H:i:s'),
        );
    }

    /**
     * Cari / buat sumber berdasarkan nama.
     */
    protected function _ensure_source($nama)
    {
        $nama = trim((string) $nama);

        if ($nama === '') {
            return NULL;
        }

        $existing = $this->db->where('nama', $nama)->limit(1)->get('el_sources')->row();

        if ($existing) {
            return (int) $existing->id;
        }

        return (int) $this->E_library_model->insert_source(array(
            'nama' => $nama,
            'aktif' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ));
    }

    /**
     * Ambil filter dari query string.
     */
    protected function _filters()
    {
        return array(
            'q' => trim((string) $this->input->get('q', TRUE)),
            'source_id' => (int) $this->input->get('sumber'),
            'tahun' => (int) $this->input->get('tahun'),
            'subject' => trim((string) $this->input->get('subjek', TRUE)),
            'jenis' => trim((string) $this->input->get('jenis', TRUE)),
            'sort' => trim((string) $this->input->get('sort', TRUE)),
        );
    }

    /**
     * Susun query string filter (tanpa parameter kosong) untuk link pagination.
     */
    protected function _query_string($filters)
    {
        $params = array();

        if ($filters['q'] !== '') {
            $params['q'] = $filters['q'];
        }
        if ($filters['source_id'] > 0) {
            $params['sumber'] = $filters['source_id'];
        }
        if ($filters['tahun'] > 0) {
            $params['tahun'] = $filters['tahun'];
        }
        if ($filters['subject'] !== '') {
            $params['subjek'] = $filters['subject'];
        }
        if ($filters['jenis'] !== '') {
            $params['jenis'] = $filters['jenis'];
        }
        if ($filters['sort'] !== '') {
            $params['sort'] = $filters['sort'];
        }

        return http_build_query($params);
    }
}
