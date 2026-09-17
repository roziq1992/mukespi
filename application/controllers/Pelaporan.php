<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pelaporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Pelaporan_model');
        $this->load->model('Pegawai_model');
        $this->load->library('form_validation');
    }

    private function is_hrd()
    {
        return in_array((int) $this->session->userdata('role_id'), array(1, 6), true);
    }

    private function is_pegawai_login()
    {
        return $this->session->userdata('is_pegawai') === TRUE || (int) $this->session->userdata('role_id') === 7;
    }

    // petunjuk laporan yang boleh dilihat pengguna saat ini: HRD = semua, pegawai = laporannya sendiri
    public function index()
    {
        if ($this->is_pegawai_login()) {
            $list = $this->Pelaporan_model->list_by_pegawai(current_pegawai_id());
        } elseif ($this->is_hrd()) {
            $list = $this->Pelaporan_model->list_all();
        } else {
            // user biasa (login email) dilihat berdasarkan user yang melaporkan
            $list = $this->Pelaporan_model->list_by_user((int) $this->session->userdata('id'));
        }

        $data = array(
            'laporan' => $list,
            'is_hrd' => $this->is_hrd(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('pelaporan/pelaporan_list', $data);
        $this->load->view('template/footer');
    }

    public function create()
    {
        $data = array(
            'action' => site_url('pelaporan/create_action'),
            'pegawai_list' => $this->Pegawai_model->list_aktif(),
            'set_laporan_id' => (int) $this->input->get('id_terlapor'),
            'is_pegawai_login' => $this->is_pegawai_login(),
            'my_pegawai_id' => current_pegawai_id(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('pelaporan/pelaporan_form', $data);
        $this->load->view('template/footer');
    }

    public function create_action()
    {
        $this->form_validation->set_rules('id_terlapor', 'Karyawan yang dinilai', 'trim|required|integer');
        $this->form_validation->set_rules('bintang', 'Bintang', 'trim|required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('alasan', 'Alasan / Pengaduan', 'trim|required|max_length[2000]');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $id_terlapor = (int) $this->input->post('id_terlapor', TRUE);
            $terlapor = $this->Pegawai_model->get_by_id($id_terlapor);

            if (!$terlapor) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Karyawan yang dinilai tidak ditemukan.</div>');
                redirect('pelaporan/create');
            }

            if ($this->is_pegawai_login() && current_pegawai_id() === $id_terlapor) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda tidak dapat menilai diri sendiri.</div>');
                redirect('pelaporan/create');
            }

            $data = array(
                'id_terlapor' => $id_terlapor,
                'bintang' => (int) $this->input->post('bintang', TRUE),
                'alasan' => htmlspecialchars($this->input->post('alasan', TRUE)),
                'created_at' => date('Y-m-d H:i:s'),
            );

            if ($this->is_pegawai_login()) {
                $data['id_pelapor'] = current_pegawai_id();
                $data['id_user_pelapor'] = NULL;
            } else {
                $data['id_user_pelapor'] = (int) $this->session->userdata('id');
                $data['id_pelapor'] = NULL;
            }

            $this->Pelaporan_model->insert($data);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Laporan / penilaian berhasil dikirim.</div>');
            redirect('pelaporan');
        }
    }

    public function detail($id)
    {
        $row = $this->Pelaporan_model->data($id);

        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Laporan tidak ditemukan.</div>');
            redirect('pelaporan');
        }

        if (!$this->_can_access($row)) {
            show_error('Anda tidak memiliki akses ke laporan ini.', 403);
        }

        $data = array(
            'row' => $row,
            'is_hrd' => $this->is_hrd(),
            'is_pelapor' => $this->_is_pelapor($row),
        );

        $this->load->view('template/header', $data);
        $this->load->view('pelaporan/pelaporan_detail', $data);
        $this->load->view('template/footer');
    }

    public function delete($id)
    {
        $row = $this->Pelaporan_model->data($id);

        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Laporan tidak ditemukan.</div>');
            redirect('pelaporan');
        }

        if (!$this->_can_access($row)) {
            show_error('Anda tidak memiliki akses ke laporan ini.', 403);
        }

        $this->Pelaporan_model->delete($id);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Laporan berhasil dihapus.</div>');
        redirect('pelaporan');
    }

    private function _is_pelapor($row)
    {
        if ($this->is_pegawai_login()) {
            return (int) $row->id_pelapor === current_pegawai_id();
        }
        return (int) $row->id_user_pelapor === (int) $this->session->userdata('id');
    }

    private function _can_access($row)
    {
        // HRD/Admin melihat semua
        if ($this->is_hrd() && !$this->is_pegawai_login()) {
            return TRUE;
        }
        // selain HRD, hanya pelapor yang bisa melihat laporannya sendiri
        return $this->_is_pelapor($row);
    }
}