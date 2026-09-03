<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dokumen_unit extends CI_Controller
{
    private $upload_folder = 'uploads/dokumen_unit/';
    private $ROLE_ID_ADMIN = 1;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Dokumen_unit_model');
        $this->load->model('User_unit_model');
        $this->load->library('form_validation');
    }

    // NULL = admin (tidak dibatasi), array = daftar id_unit yang boleh diakses
    private function _get_allowed_units()
    {
        $role_id = $this->session->userdata('role_id');
        if ($role_id == $this->ROLE_ID_ADMIN) {
            return NULL;
        }
        return $this->User_unit_model->get_unit_ids_by_user($this->session->userdata('id'));
    }

    private function _get_unit_options($allowed_units)
    {
        if ($allowed_units === NULL) {
            return $this->Dokumen_unit_model->unit();
        }
        return $this->User_unit_model->get_units_by_user($this->session->userdata('id'));
    }

    private function _deny_if_not_allowed($id_unit_dokumen)
    {
        $allowed = $this->_get_allowed_units();
        if (is_array($allowed) && !in_array($id_unit_dokumen, $allowed)) {
            $this->session->set_flashdata('message', 'Anda tidak punya akses ke dokumen unit ini');
            redirect(site_url('dokumen_unit'));
        }
    }

   public function index()
{
    $allowed_units = $this->_get_allowed_units();

    $q = urldecode($this->input->get('q', TRUE));
    $id_unit = $this->input->get('id_unit', TRUE);
    $id_unit_doc_ref = $this->input->get('id_unit_doc_ref', TRUE); // BARU
    $id_jenis_dokumen = $this->input->get('id_jenis_dokumen', TRUE);
    $start = intval($this->input->get('start'));

    $status_berlaku = $this->input->get('status_berlaku', TRUE);
    if ($status_berlaku === NULL) {
        $status_berlaku = 'berlaku';
    }

    // non-admin tidak boleh filter ke unit di luar miliknya
    if (is_array($allowed_units) && $id_unit <> '' && !in_array($id_unit, $allowed_units)) {
        $id_unit = '';
    }

    // BARU: kalau jenis dokumen yang dipilih ternyata bukan milik unit_doc_ref
    // yang sedang difilter, kosongkan supaya tidak nyasar hasil kosong senyap
    if ($id_unit_doc_ref <> '' && $id_jenis_dokumen <> '') {
        $jd_check = $this->Dokumen_unit_model->get_jenis_dokumen_by_id($id_jenis_dokumen);
        if (!$jd_check || $jd_check->id_unit_doc_ref != $id_unit_doc_ref) {
            $id_jenis_dokumen = '';
        }
    }

    $filter_qs = array();
    if ($q <> '') $filter_qs['q'] = $q;
    if ($id_unit <> '') $filter_qs['id_unit'] = $id_unit;
    if ($id_unit_doc_ref <> '') $filter_qs['id_unit_doc_ref'] = $id_unit_doc_ref; // BARU
    if ($id_jenis_dokumen <> '') $filter_qs['id_jenis_dokumen'] = $id_jenis_dokumen;
    if ($status_berlaku <> '') $filter_qs['status_berlaku'] = $status_berlaku;

    $base_url = base_url() . 'index.php/dokumen_unit/';
    if (!empty($filter_qs)) {
        $base_url .= '?' . http_build_query($filter_qs);
    }

    $config['base_url'] = $base_url;
    $config['first_url'] = $base_url;
    $config['per_page'] = 10;
    $config['page_query_string'] = TRUE;
    $config['total_rows'] = $this->Dokumen_unit_model->total_rows($q, $id_unit, $id_jenis_dokumen, $allowed_units, $status_berlaku, $id_unit_doc_ref);
    $dokumen_unit = $this->Dokumen_unit_model->get_limit_data($config['per_page'], $start, $q, $id_unit, $id_jenis_dokumen, $allowed_units, $status_berlaku, $id_unit_doc_ref);

    $this->load->library('pagination');
    $this->pagination->initialize($config);

    $data = array(
        'dokumen_unit_data' => $dokumen_unit,
        'q' => $q,
        'id_unit' => $id_unit,
        'id_unit_doc_ref' => $id_unit_doc_ref, // BARU
        'id_jenis_dokumen' => $id_jenis_dokumen,
        'status_berlaku' => $status_berlaku,
        'unit2' => $this->_get_unit_options($allowed_units),
        'unit_dok2' => $this->Dokumen_unit_model->unit_dokumen_all(), // BARU
        'jenis_dokumen2' => ($id_unit_doc_ref <> '')
            ? $this->Dokumen_unit_model->jenis_dokumen_by_unit_doc($id_unit_doc_ref)
            : array(), // BARU: kosong sampai Unit Dokumen dipilih
        'pagination' => $this->pagination->create_links(),
        'total_rows' => $config['total_rows'],
        'start' => $start,
    );
    $this->load->view('template/header', $data);
    $this->load->view('dokumen_unit/dokumen_unit_list');
    $this->load->view('template/footer');
}

    /**
     * ================== BARU ==================
     * Dashboard laporan dokumen unit: ringkasan angka, komposisi per unit
     * dan per jenis dokumen, serta daftar dokumen yang akan kadaluarsa.
     * Menghormati batasan _get_allowed_units() untuk user non-admin.
     */
    public function dashboard()
    {
        $allowed_units = $this->_get_allowed_units();

        $hari = intval($this->input->get('hari', TRUE));
        if ($hari <= 0) {
            $hari = 30;
        }

        $data = array(
            'stats'         => $this->Dokumen_unit_model->get_stats($allowed_units, $hari),
            'by_unit'       => $this->Dokumen_unit_model->get_count_by_unit($allowed_units),
            'by_jenis'      => $this->Dokumen_unit_model->get_count_by_jenis($allowed_units),
            'expiring_soon' => $this->Dokumen_unit_model->get_expiring_soon($allowed_units, $hari, 15),
            'hari'          => $hari,
        );

        $this->load->view('template/header', $data);
        $this->load->view('dokumen_unit/dokumen_unit_dashboard', $data);
        $this->load->view('template/footer');
    }

    public function read($id)
    {
        $row = $this->Dokumen_unit_model->get_by_id($id);
        if ($row) {
            $this->_deny_if_not_allowed($row->id_unit);
            $data = array(
                'id_dokumen' => $row->id_dokumen,
                'nm_unit' => $row->nm_unit,
                'nm_jenis_dokumen' => $row->nm_jenis_dokumen,
                'judul_dokumen' => $row->judul_dokumen,
                'keterangan' => $row->keterangan,
                'nama_file' => $row->nama_file,
                'path_file' => $row->path_file,
                'tipe_file' => $row->tipe_file,
                'ukuran_file' => $row->ukuran_file,
                'versi' => $row->versi,
                'is_current' => $row->is_current,
                'status' => $row->status,
                'tgl_berlaku' => $row->tgl_berlaku,
                'tgl_kadaluarsa' => $row->tgl_kadaluarsa,
                'diupload_oleh' => $row->diupload_oleh,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            );
            $this->load->view('template/header');
            $this->load->view('dokumen_unit/dokumen_unit_read', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('dokumen_unit'));
        }
    }

    // Ganti method select2_jenis_dokumen yang lama
public function select2_jenis_dokumen()
{
    header('Content-Type: application/json');

    $q               = trim($this->input->get('q', TRUE));
    $page            = intval($this->input->get('page'));
    $id_unit_doc_ref = intval($this->input->get('id_unit_doc_ref', TRUE));

    if ($page < 1) $page = 1;
    $limit  = 20;
    $offset = ($page - 1) * $limit;

    $result = $this->Dokumen_unit_model
                   ->search_jenis_dokumen_by_unit($q, $limit, $offset, $id_unit_doc_ref);

    $items = [];
    foreach ($result['data'] as $j) {
        $items[] = ['id' => $j->id_jenis_dokumen, 'text' => $j->nm_jenis_dokumen];
    }

    echo json_encode(['items' => $items, 'more' => $result['more']]);
}

// Tambah 'unit_dok2', 'id_unit_doc_ref', 'jenis_dokumen_selected' di create()
public function create()
{
    $allowed_units = $this->_get_allowed_units();
    $data = array(
        'button'                 => 'Simpan',
        'action'                 => site_url('dokumen_unit/create_action'),
        'id_dokumen'             => set_value('id_dokumen'),
        'id_unit'                => set_value('id_unit'),
        'id_unit_doc_ref'        => set_value('id_unit_doc_ref'),  // BARU
        'id_jenis_dokumen'       => set_value('id_jenis_dokumen'),
        'judul_dokumen'          => set_value('judul_dokumen'),
        'keterangan'             => set_value('keterangan'),
        'nama_file'              => set_value('nama_file'),
        'path_file'              => set_value('path_file'),
        'status'                 => set_value('status', 'aktif'),
        'tgl_berlaku'            => set_value('tgl_berlaku'),
        'tgl_kadaluarsa'         => set_value('tgl_kadaluarsa'),
        'unit2'                  => $this->_get_unit_options($allowed_units),
        'unit_dok2'              => $this->Dokumen_unit_model->unit_dokumen_all(), // BARU
        'jenis_dokumen_selected' => NULL,  // BARU
    );
    $this->load->view('template/header', $data);
    $this->load->view('dokumen_unit/dokumen_unit_form');
    $this->load->view('template/footer');
}



    public function create_action()
    {
        $this->_rules();

        $file_kosong = empty($_FILES['file_dokumen']['name']);
        if ($file_kosong) {
            $this->session->set_flashdata('message', 'File dokumen wajib diupload');
        }

        if ($this->form_validation->run() == FALSE || $file_kosong) {
            $this->create();
            return;
        }

        // cegah user submit unit di luar akses miliknya
        $id_unit_posted = $this->input->post('id_unit', TRUE);
        $allowed_units = $this->_get_allowed_units();
        if (is_array($allowed_units) && !in_array($id_unit_posted, $allowed_units)) {
            $this->session->set_flashdata('message', 'Anda tidak punya akses ke unit tersebut');
            $this->create();
            return;
        }

        $upload = $this->_upload_file();

        if ($upload['error']) {
            $this->session->set_flashdata('message', $upload['message']);
            $this->create();
            return;
        }

        $data = array(
            'id_unit' => $id_unit_posted,
            'id_jenis_dokumen' => $this->input->post('id_jenis_dokumen', TRUE),
            'judul_dokumen' => $this->input->post('judul_dokumen', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE),
            'nama_file' => $upload['nama_file'],
            'path_file' => $upload['path_file'],
            'tipe_file' => $upload['tipe_file'],
            'ukuran_file' => $upload['ukuran_file'],
            'versi' => 1,
            'is_current' => 1,
            'status' => $this->input->post('status', TRUE),
            'tgl_berlaku' => $this->input->post('tgl_berlaku', TRUE) ?: NULL,
            'tgl_kadaluarsa' => $this->input->post('tgl_kadaluarsa', TRUE) ?: NULL,
            'diupload_oleh' => $this->session->userdata('email'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $this->Dokumen_unit_model->insert($data);
        $this->session->set_flashdata('message', 'Dokumen berhasil diupload');
        redirect(site_url('dokumen_unit'));
    }

    // Tambah hal yang sama di update()
public function update($id)
{
    $row = $this->Dokumen_unit_model->get_by_id($id);

    if ($row) {
        $this->_deny_if_not_allowed($row->id_unit);
        $allowed_units = $this->_get_allowed_units();

        $jd_selected = NULL;
        if (!empty($row->id_jenis_dokumen)) {
            $jd_selected = $this->Dokumen_unit_model->get_jenis_dokumen_by_id($row->id_jenis_dokumen);
        }

        $data = array(
            'button'                 => 'Update',
            'action'                 => site_url('dokumen_unit/update_action'),
            'id_dokumen'             => set_value('id_dokumen', $row->id_dokumen),
            'id_unit'                => set_value('id_unit', $row->id_unit),
            'id_unit_doc_ref'        => set_value('id_unit_doc_ref', $row->id_unit_doc_ref), // BARU
            'id_jenis_dokumen'       => set_value('id_jenis_dokumen', $row->id_jenis_dokumen),
            'judul_dokumen'          => set_value('judul_dokumen', $row->judul_dokumen),
            'keterangan'             => set_value('keterangan', $row->keterangan),
            'nama_file'              => set_value('nama_file', $row->nama_file),
            'path_file'              => set_value('path_file', $row->path_file),
            'status'                 => set_value('status', $row->status),
            'tgl_berlaku'            => set_value('tgl_berlaku', $row->tgl_berlaku),
            'tgl_kadaluarsa'         => set_value('tgl_kadaluarsa', $row->tgl_kadaluarsa),
            'unit2'                  => $this->_get_unit_options($allowed_units),
            'unit_dok2'              => $this->Dokumen_unit_model->unit_dokumen_all(), // BARU
            'jenis_dokumen_selected' => $jd_selected,  // BARU
        );
        $this->load->view('template/header', $data);
        $this->load->view('dokumen_unit/dokumen_unit_form', $data);
        $this->load->view('template/footer');
    } else {
        $this->session->set_flashdata('message', 'Record Not Found');
        redirect(site_url('dokumen_unit'));
    }
}

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_dokumen', TRUE));
            return;
        }

        $id = $this->input->post('id_dokumen', TRUE);
        $row = $this->Dokumen_unit_model->get_by_id($id);

        if (!$row) {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('dokumen_unit'));
            return;
        }

        $this->_deny_if_not_allowed($row->id_unit);

        $id_unit_posted = $this->input->post('id_unit', TRUE);
        $allowed_units = $this->_get_allowed_units();
        if (is_array($allowed_units) && !in_array($id_unit_posted, $allowed_units)) {
            $this->session->set_flashdata('message', 'Anda tidak punya akses ke unit tersebut');
            $this->update($id);
            return;
        }

        $data = array(
            'id_unit' => $id_unit_posted,
            'id_jenis_dokumen' => $this->input->post('id_jenis_dokumen', TRUE),
            'judul_dokumen' => $this->input->post('judul_dokumen', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE),
            'status' => $this->input->post('status', TRUE),
            'tgl_berlaku' => $this->input->post('tgl_berlaku', TRUE) ?: NULL,
            'tgl_kadaluarsa' => $this->input->post('tgl_kadaluarsa', TRUE) ?: NULL,
            'updated_at' => date('Y-m-d H:i:s'),
        );

        if (!empty($_FILES['file_dokumen']['name'])) {
            $upload = $this->_upload_file();

            if ($upload['error']) {
                $this->session->set_flashdata('message', $upload['message']);
                $this->update($id);
                return;
            }

            $data['nama_file'] = $upload['nama_file'];
            $data['path_file'] = $upload['path_file'];
            $data['tipe_file'] = $upload['tipe_file'];
            $data['ukuran_file'] = $upload['ukuran_file'];
            $data['versi'] = intval($row->versi) + 1;

            if (!empty($row->path_file) && file_exists(FCPATH . $row->path_file)) {
                @unlink(FCPATH . $row->path_file);
            }
        }

        $this->Dokumen_unit_model->update($id, $data);
        $this->session->set_flashdata('message', 'Dokumen berhasil diupdate');
        redirect(site_url('dokumen_unit'));
    }

    public function delete($id)
    {
        $row = $this->Dokumen_unit_model->get_by_id($id);

        if ($row) {
            $this->_deny_if_not_allowed($row->id_unit);

            if (!empty($row->path_file) && file_exists(FCPATH . $row->path_file)) {
                @unlink(FCPATH . $row->path_file);
            }
            $this->Dokumen_unit_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('dokumen_unit'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('dokumen_unit'));
        }
    }

    /**
     * ================== BARU ==================
     * Endpoint AJAX untuk live search select2 pada dropdown Unit.
     * Dipanggil oleh view dokumen_unit_form.php via GET ?q=...&page=...
     * Menghormati batasan _get_allowed_units() untuk user non-admin.
     */
    public function select2_unit()
    {
        header('Content-Type: application/json');

        $q = trim($this->input->get('q', TRUE));
        $page = intval($this->input->get('page'));
        if ($page < 1) $page = 1;

        $limit = 20;
        $offset = ($page - 1) * $limit;

        $allowed_units = $this->_get_allowed_units();

        $result = $this->Dokumen_unit_model->search_unit($q, $limit, $offset, $allowed_units);

        $items = array();
        foreach ($result['data'] as $u) {
            $items[] = array(
                'id' => $u->id_unit,
                'text' => $u->nm_unit,
            );
        }

        echo json_encode(array(
            'items' => $items,
            'more' => $result['more'],
        ));
    }

    /**
     * ================== BARU ==================
     * Endpoint AJAX untuk live search select2 pada dropdown Jenis Dokumen.
     */
    // public function select2_jenis_dokumen()
    // {
    //     header('Content-Type: application/json');

    //     $q = trim($this->input->get('q', TRUE));
    //     $page = intval($this->input->get('page'));
    //     if ($page < 1) $page = 1;

    //     $limit = 20;
    //     $offset = ($page - 1) * $limit;

    //     $result = $this->Dokumen_unit_model->search_jenis_dokumen($q, $limit, $offset);

    //     $items = array();
    //     foreach ($result['data'] as $j) {
    //         $items[] = array(
    //             'id' => $j->id_jenis_dokumen,
    //             'text' => $j->nm_jenis_dokumen,
    //         );
    //     }

    //     echo json_encode(array(
    //         'items' => $items,
    //         'more' => $result['more'],
    //     ));
    // }

    public function _rules()
    {
        $this->form_validation->set_rules('id_unit', 'unit', 'trim|required');
        $this->form_validation->set_rules('id_jenis_dokumen', 'jenis dokumen', 'trim|required');
        $this->form_validation->set_rules('judul_dokumen', 'judul dokumen', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'trim');
        $this->form_validation->set_rules('status', 'status', 'trim|required');
        $this->form_validation->set_rules('tgl_berlaku', 'tgl berlaku', 'trim');
        $this->form_validation->set_rules('tgl_kadaluarsa', 'tgl kadaluarsa', 'trim');

        $this->form_validation->set_rules('id_dokumen', 'id_dokumen', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    private function _upload_file()
    {
        $upload_path = FCPATH . $this->upload_folder;

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
        $config['max_size'] = 5120;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_dokumen')) {
            return array(
                'error' => TRUE,
                'message' => strip_tags($this->upload->display_errors()),
            );
        }

        $file = $this->upload->data();

        return array(
            'error' => FALSE,
            'nama_file' => $file['orig_name'],
            'path_file' => $this->upload_folder . $file['file_name'],
            'tipe_file' => strtolower($file['file_ext']),
            'ukuran_file' => intval($file['file_size'] * 1024),
        );
    }

    public function excel()
    {
        $allowed_units = $this->_get_allowed_units();

        $this->load->helper('exportexcel');
        $namaFile = "dokumen_unit.xls";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
        xlsWriteLabel($tablehead, $kolomhead++, "Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Jenis Dokumen");
        xlsWriteLabel($tablehead, $kolomhead++, "Judul Dokumen");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama File");
        xlsWriteLabel($tablehead, $kolomhead++, "Versi");
        xlsWriteLabel($tablehead, $kolomhead++, "Status");
        xlsWriteLabel($tablehead, $kolomhead++, "Tgl Berlaku");
        xlsWriteLabel($tablehead, $kolomhead++, "Tgl Kadaluarsa");

        // filter data export sesuai allowed_units juga
        $all_data = $this->Dokumen_unit_model->get_all();
        foreach ($all_data as $data) {
            if (is_array($allowed_units) && !in_array($data->id_unit, $allowed_units)) {
                continue;
            }

            $kolombody = 0;
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->nm_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->nm_jenis_dokumen);
            xlsWriteLabel($tablebody, $kolombody++, $data->judul_dokumen);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_file);
            xlsWriteNumber($tablebody, $kolombody++, $data->versi);
            xlsWriteLabel($tablebody, $kolombody++, $data->status);
            xlsWriteLabel($tablebody, $kolombody++, $data->tgl_berlaku);
            xlsWriteLabel($tablebody, $kolombody++, $data->tgl_kadaluarsa);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Dokumen_unit.php */