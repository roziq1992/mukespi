<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Unit
 * ---------------------------------------------------------
 * Manajemen master unit kerja (tabel unit):
 * daftar, tambah, ubah, dan ubah status aktif/nonaktif.
 * Hanya admin (1) dan HRD (6) yang boleh mengelola.
 */
class Unit extends CI_Controller
{
    private $ROLE_ID_ADMIN = 1;
    private $ROLE_ID_HRD   = 6;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Unit_model');
        $this->load->helper('form');
    }

    private function _is_admin()
    {
        return (int) $this->session->userdata('role_id') === $this->ROLE_ID_ADMIN;
    }

    private function _is_hrd()
    {
        return (int) $this->session->userdata('role_id') === $this->ROLE_ID_HRD;
    }

    private function _is_allowed()
    {
        return $this->_is_admin() || $this->_is_hrd();
    }

    // ================= DAFTAR UNIT =================

    public function index($status = '')
    {
        if (!$this->_is_allowed()) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Halaman ini khusus administrator / HRD.</div>');
            redirect(site_url('portal'));
            return;
        }

        $status = (string) $status;
        if ($status !== 'aktif' && $status !== 'nonaktif') {
            $status = '';
        }
        $q = trim($this->input->get('q', TRUE));

        $data = array(
            'title'   => 'Manajemen Unit Kerja',
            'units'   => $this->Unit_model->get_all($status, $q),
            'status'  => $status,
            'q'       => $q,
            'is_admin' => $this->_is_admin(),
            'stat'    => array(
                'total'     => $this->Unit_model->count_all(),
                'aktif'     => $this->Unit_model->count_by_status('aktif'),
                'nonaktif'  => $this->Unit_model->count_by_status('nonaktif'),
            ),
        );

        $this->load->view('template/header', $data);
        $this->load->view('unit/unit_list', $data);
        $this->load->view('template/footer');
    }

    // ================= TAMBAH UNIT =================

    public function create()
    {
        if (!$this->_is_allowed()) {
            redirect(site_url('unit'));
            return;
        }

        $this->_form_validation();
        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => 'Tambah Unit Kerja',
                'unit'  => NULL,
                'is_admin' => $this->_is_admin(),
            );
            $this->load->view('template/header', $data);
            $this->load->view('unit/unit_form', $data);
            $this->load->view('template/footer');
            return;
        }

        $nm_unit = trim($this->input->post('nm_unit', TRUE));
        if ($this->Unit_model->exists_name($nm_unit)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Unit <strong>' . html_escape($nm_unit) . '</strong> sudah ada.</div>');
            redirect(site_url('unit/create'));
            return;
        }

        $id = $this->Unit_model->insert(array(
            'nm_unit' => $nm_unit,
            'jns_unit' => $this->input->post('jns_unit', TRUE),
            'status'   => $this->input->post('status', TRUE) === 'nonaktif' ? 'nonaktif' : 'aktif',
        ));

        $this->session->set_flashdata('message', '<div class="alert alert-success">Unit <strong>' . html_escape($nm_unit) . '</strong> berhasil dibuat.</div>');
        redirect(site_url('unit/edit/' . $id));
    }

    // ================= UBAH UNIT =================

    public function edit($id = 0)
    {
        if (!$this->_is_allowed()) {
            redirect(site_url('unit'));
            return;
        }

        $id = (int) $id;
        $unit = $this->Unit_model->get_by_id($id);
        if (!$unit) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Unit tidak ditemukan.</div>');
            redirect(site_url('unit'));
            return;
        }

        $this->_form_validation();
        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'title' => 'Ubah Unit Kerja',
                'unit'  => $unit,
                'is_admin' => $this->_is_admin(),
            );
            $this->load->view('template/header', $data);
            $this->load->view('unit/unit_form', $data);
            $this->load->view('template/footer');
            return;
        }

        $nm_unit = trim($this->input->post('nm_unit', TRUE));
        if ($this->Unit_model->exists_name($nm_unit, $id)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Unit <strong>' . html_escape($nm_unit) . '</strong> sudah ada.</div>');
            redirect(site_url('unit/edit/' . $id));
            return;
        }

        $this->Unit_model->update($id, array(
            'nm_unit' => $nm_unit,
            'jns_unit' => $this->input->post('jns_unit', TRUE),
            'status'   => $this->input->post('status', TRUE) === 'nonaktif' ? 'nonaktif' : 'aktif',
        ));

        $this->session->set_flashdata('message', '<div class="alert alert-success">Unit <strong>' . html_escape($nm_unit) . '</strong> berhasil diperbarui.</div>');
        redirect(site_url('unit/edit/' . $id));
    }

    // ================= UBAH STATUS (AKTIF / NONAKTIF) =================

    public function status($id = 0, $status = '')
    {
        if (!$this->_is_allowed()) {
            redirect(site_url('unit'));
            return;
        }

        $id = (int) $id;
        $unit = $this->Unit_model->get_by_id($id);
        if (!$unit) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Unit tidak ditemukan.</div>');
            redirect(site_url('unit'));
            return;
        }

        $status = $status === 'nonaktif' ? 'nonaktif' : 'aktif';
        $this->Unit_model->set_status($id, $status);

        $this->session->set_flashdata('message', '<div class="alert alert-success">Unit <strong>' . html_escape($unit->nm_unit) . '</strong> kini berstatus <strong>' . $status . '</strong>.</div>');
        redirect(site_url('unit'));
    }

    // ================= VALIDASI FORM =================

    private function _form_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nm_unit', 'Nama Unit', 'trim|required');
        $this->form_validation->set_rules('jns_unit', 'Jenis Unit', 'trim|required|in_list[Medis,Non_Medis,Management,Penunjang_Medis]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[aktif,nonaktif]');
    }
}
/* End of file Unit.php */