<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Operan_model');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    // ==================== LIST DATA ====================
    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        $config['base_url'] = base_url() . 'operan/index.html';
        $config['first_url'] = base_url() . 'operan/index.html';
        if ($q <> '') {
            $config['base_url'] = base_url() . 'operan/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'operan/index.html?q=' . urlencode($q);
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Operan_model->total_rows($q);
        $data['operan_data'] = $this->Operan_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data['q'] = $q;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start'] = $start;

        $this->load->view('template/header_public', $data);
        $this->load->view('operan/operan_list', $data);
        $this->load->view('template/footer');
    }

    // ==================== READ / DETAIL ====================
    public function read($id)
    {
        $row = $this->Operan_model->get_by_id($id);
        if ($row) {
            $data['row'] = $row;
            
            $this->load->view('template/header_public', $data);
            $this->load->view('operan/operan_read', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('operan'));
        }
    }

    // ==================== CREATE ====================
    public function create()
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('operan/create_action'),
            'id_operan' => set_value('id_operan'),
            'hari_tanggal' => set_value('hari_tanggal', date('Y-m-d')),
            'shift_dari' => set_value('shift_dari'),
            'shift_ke' => set_value('shift_ke'),
            'departemen' => set_value('departemen'),
            'jumlah_pasien_ranap' => set_value('jumlah_pasien_ranap', 0),
            'jp_ns1' => set_value('jp_ns1', 0),
            'jp_ns2' => set_value('jp_ns2', 0),
            'jp_ns3' => set_value('jp_ns3', 0),
            'jp_vk' => set_value('jp_vk', 0),
            'jp_icu' => set_value('jp_icu', 0),
            'jp_picu' => set_value('jp_picu', 0),
            'jp_nicu' => set_value('jp_nicu', 0),
            'jp_r_bayi' => set_value('jp_r_bayi', 0),
            'jp_igd' => set_value('jp_igd', 0),
            'jp_ok' => set_value('jp_ok', 0),
            'rekomendasi' => set_value('rekomendasi'),
            'catatan_khusus' => set_value('catatan_khusus'),
            'perawat_shift1' => set_value('perawat_shift1'),
            'perawat_shift2' => set_value('perawat_shift2'),
            'mengetahui' => set_value('mengetahui'),
        );
        
        // Data pasien kosong untuk form
        $data['data_ruang'] = $this->_get_empty_pasien_data();

        $this->load->view('template/header_public', $data);
        $this->load->view('operan/operan_form', $data);
        $this->load->view('template/footer');
    }

    public function create_action()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = $this->_post_data();
            $this->Operan_model->insert($data);
            $this->session->set_flashdata('message', 'Simpan Data Berhasil');
            redirect(site_url('operan'));
        }
    }

    // ==================== UPDATE ====================
    public function update($id)
    {
        $row = $this->Operan_model->get_by_id($id);
        if ($row) {
            $data = array(
                'button' => 'Ubah',
                'action' => site_url('operan/update_action'),
                'id_operan' => set_value('id_operan', $row->id_operan),
                'hari_tanggal' => set_value('hari_tanggal', $row->hari_tanggal),
                'shift_dari' => set_value('shift_dari', $row->shift_dari),
                'shift_ke' => set_value('shift_ke', $row->shift_ke),
                'departemen' => set_value('departemen', $row->departemen),
                'jumlah_pasien_ranap' => set_value('jumlah_pasien_ranap', $row->jumlah_pasien_ranap),
                'jp_ns1' => set_value('jp_ns1', $row->jp_ns1),
                'jp_ns2' => set_value('jp_ns2', $row->jp_ns2),
                'jp_ns3' => set_value('jp_ns3', $row->jp_ns3),
                'jp_vk' => set_value('jp_vk', $row->jp_vk),
                'jp_icu' => set_value('jp_icu', $row->jp_icu),
                'jp_picu' => set_value('jp_picu', $row->jp_picu),
                'jp_nicu' => set_value('jp_nicu', $row->jp_nicu),
                'jp_r_bayi' => set_value('jp_r_bayi', $row->jp_r_bayi),
                'jp_igd' => set_value('jp_igd', $row->jp_igd),
                'jp_ok' => set_value('jp_ok', $row->jp_ok),
                'rekomendasi' => set_value('rekomendasi', $row->rekomendasi),
                'catatan_khusus' => set_value('catatan_khusus', $row->catatan_khusus),
                'perawat_shift1' => set_value('perawat_shift1', $row->perawat_shift1),
                'perawat_shift2' => set_value('perawat_shift2', $row->perawat_shift2),
                'mengetahui' => set_value('mengetahui', $row->mengetahui),
            );
            
            // ===== PERBAIKAN: Parse data pasien dari JSON untuk form =====
            $ruang_list = ['icu', 'picu', 'nicu', 'arofah', 'muzd', 'mina', 'marwah', 'safa', 'multazam', 'vk', 'r_bayi', 'ok', 'igd'];
            $data['data_ruang'] = array();
            
            foreach ($ruang_list as $ruang) {
                $field_name = 'data_' . $ruang;
                $decoded = json_decode($row->$field_name, true);
                
                // Jika data kosong atau tidak valid, gunakan data kosong
                if (!is_array($decoded) || empty($decoded)) {
                    $decoded = $this->_get_empty_ruang(ucfirst($ruang));
                }
                
                // Pastikan semua bed (1-8) ada
                for ($i = 1; $i <= 8; $i++) {
                    if (!isset($decoded[$i])) {
                        $decoded[$i] = ['nama_pasien' => '', 'diagnosa' => '', 'keterangan' => ''];
                    }
                }
                
                $data['data_ruang'][$ruang] = $decoded;
            }

            $this->load->view('template/header_public', $data);
            $this->load->view('operan/operan_form', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Data tidak ditemukan');
            redirect(site_url('operan'));
        }
    }

    public function update_action()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_operan', TRUE));
        } else {
            $data = $this->_post_data();
            $this->Operan_model->update($this->input->post('id_operan', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Data Berhasil');
            redirect(site_url('operan'));
        }
    }

    // ==================== DELETE ====================
    public function delete($id)
    {
        $row = $this->Operan_model->get_by_id($id);
        if ($row) {
            $this->Operan_model->delete($id);
            $this->session->set_flashdata('message', 'Hapus Data Berhasil');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
        }
        redirect(site_url('operan'));
    }

    // ==================== DASHBOARD ====================
    public function dashboard()
    {
        $bulan = $this->input->get('bulan', TRUE) ?: date('m');
        $tahun = $this->input->get('tahun', TRUE) ?: date('Y');

        $data = array(
            'bulan' => $bulan,
            'tahun' => $tahun,
            'stats' => $this->Operan_model->get_dashboard_stats($bulan, $tahun),
        );

        $this->load->view('template/header_public', $data);
        $this->load->view('operan/operan_dashboard', $data);
        $this->load->view('template/footer');
    }

    // ==================== HELPER FUNCTIONS ====================
    
    private function _get_empty_pasien_data()
    {
        return array(
            'icu' => $this->_get_empty_ruang('ICU'),
            'picu' => $this->_get_empty_ruang('PICU'),
            'nicu' => $this->_get_empty_ruang('NICU'),
            'arofah' => $this->_get_empty_ruang('Arofah'),
            'muzd' => $this->_get_empty_ruang('Muzd'),
            'mina' => $this->_get_empty_ruang('Mina'),
            'marwah' => $this->_get_empty_ruang('Marwah'),
            'safa' => $this->_get_empty_ruang('Safa'),
            'multazam' => $this->_get_empty_ruang('Multazam'),
            'vk' => $this->_get_empty_ruang('VK'),
            'r_bayi' => $this->_get_empty_ruang('R.Bayi'),
            'ok' => $this->_get_empty_ruang('OK'),
            'igd' => $this->_get_empty_ruang('IGD'),
        );
    }

    private function _get_empty_ruang($nama)
    {
        $data = array();
        for ($i = 1; $i <= 8; $i++) {
            $data[$i] = array(
                'nama_pasien' => '',
                'diagnosa' => '',
                'keterangan' => '',
            );
        }
        return $data;
    }

    private function _post_data()
    {
        // Ambil data pasien dari POST dan encode ke JSON
        $ruang_list = array('icu', 'picu', 'nicu', 'arofah', 'muzd', 'mina', 'marwah', 'safa', 'multazam', 'vk', 'r_bayi', 'ok', 'igd');
        $data_ruang = array();
        
        foreach ($ruang_list as $ruang) {
            $post_key = 'data_' . $ruang;
            $post_data = $this->input->post($post_key, TRUE);
            
            // Jika tidak ada data POST, gunakan array kosong
            if (!is_array($post_data)) {
                $post_data = array();
            }
            
            // Pastikan semua bed (1-8) ada di array
            for ($i = 1; $i <= 8; $i++) {
                if (!isset($post_data[$i])) {
                    $post_data[$i] = array('nama_pasien' => '', 'diagnosa' => '', 'keterangan' => '');
                }
            }
            
            $data_ruang[$post_key] = json_encode($post_data);
        }

        return array_merge(array(
            'hari_tanggal' => $this->input->post('hari_tanggal', TRUE),
            'shift_dari' => $this->input->post('shift_dari', TRUE),
            'shift_ke' => $this->input->post('shift_ke', TRUE),
            'departemen' => $this->input->post('departemen', TRUE),
            'jumlah_pasien_ranap' => $this->input->post('jumlah_pasien_ranap', TRUE),
            'jp_ns1' => $this->input->post('jp_ns1', TRUE),
            'jp_ns2' => $this->input->post('jp_ns2', TRUE),
            'jp_ns3' => $this->input->post('jp_ns3', TRUE),
            'jp_vk' => $this->input->post('jp_vk', TRUE),
            'jp_icu' => $this->input->post('jp_icu', TRUE),
            'jp_picu' => $this->input->post('jp_picu', TRUE),
            'jp_nicu' => $this->input->post('jp_nicu', TRUE),
            'jp_r_bayi' => $this->input->post('jp_r_bayi', TRUE),
            'jp_igd' => $this->input->post('jp_igd', TRUE),
            'jp_ok' => $this->input->post('jp_ok', TRUE),
            'rekomendasi' => $this->input->post('rekomendasi', TRUE),
            'catatan_khusus' => $this->input->post('catatan_khusus', TRUE),
            'perawat_shift1' => $this->input->post('perawat_shift1', TRUE),
            'perawat_shift2' => $this->input->post('perawat_shift2', TRUE),
            'mengetahui' => $this->input->post('mengetahui', TRUE),
        ), $data_ruang);
    }

    public function _rules()
    {
        $this->form_validation->set_rules('hari_tanggal', 'Hari/Tanggal', 'trim|required');
        $this->form_validation->set_rules('shift_dari', 'Shift Dari', 'trim|required');
        $this->form_validation->set_rules('shift_ke', 'Shift Ke', 'trim|required');
        $this->form_validation->set_rules('departemen', 'Departemen', 'trim|required');
        $this->form_validation->set_rules('perawat_shift1', 'Perawat Shift 1', 'trim|required');
        $this->form_validation->set_rules('perawat_shift2', 'Perawat Shift 2', 'trim|required');

        $this->form_validation->set_rules('id_operan', 'id_operan', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}