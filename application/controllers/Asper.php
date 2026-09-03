<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asper extends CI_Controller
{
    // Mapping kode kamar per zona: key = nama field HTML (aman, tanpa titik),
    // value = label yang ditampilkan & disimpan (persis seperti di form kertas).
    private $zona_a = array(
        'UGD' => 'UGD', 'HCU' => 'HCU', 'PHCU' => 'PHCU', 'NICU' => 'NICU',
        'A1' => 'A1', 'A2' => 'A2', 'A3' => 'A3', 'A4' => 'A4',
    );
    private $zona_b = array(
        'A5' => 'A5', 'A6' => 'A6', 'A7' => 'A7', 'A8' => 'A8', 'A9' => 'A9',
        'MZ1' => 'MZ1', 'MZ2' => 'MZ2', 'MZ3' => 'MZ3', 'MZ4' => 'MZ4',
    );
    private $zona_c = array(
        'VK' => 'VK', 'M1' => 'M1', 'M2' => 'M2', 'M3' => 'M3', 'M4' => 'M4',
        'M5' => 'M5', 'M6' => 'M6', 'M7' => 'M7', 'M8' => 'M8',
    );
    private $zona_d = array(
        'M9' => 'M9', 'M10' => 'M10', 'M11' => 'M11', 'M12' => 'M12',
        'ML1_1' => 'ML1.1', 'ML1_2' => 'ML1.2', 'ML2_1' => 'ML2.1', 'ML2_2' => 'ML2.2',
        'R_Bayi' => 'R.Bayi',
    );
    private $zona_e = array(
        'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3', 'S4' => 'S4', 'S5' => 'S5', 'S6' => 'S6',
    );
    private $jumlah_pasien = array(
        'NS1' => 'NS1', 'NS2' => 'NS2', 'NS3' => 'NS3',
        'HCU' => 'HCU', 'PHCU' => 'PHCU', 'NICU' => 'NICU',
        'VK' => 'VK', 'R_Bayi' => 'R.Bayi', 'Lainnya' => 'Lainnya',
    );

    function __construct()
    {
        parent::__construct();
        $this->load->model('Asper_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'index.php/asper?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/asper?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/asper';
            $config['first_url'] = base_url() . 'index.php/asper';
        }

        $config['per_page'] = 5;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Asper_model->total_rows($q);
        $asper = $this->Asper_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'asper_data' => $asper,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->load->view('template/header_public', $data);
        $this->load->view('asper/asper_list',$data);
        $this->load->view('template/footer');
    }

    public function read($id)
    {
        $row = $this->Asper_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id_asper' => $row->id_asper,
                'hari_tanggal' => $row->hari_tanggal,
                'shift' => $row->shift,
                'ke_shift' => $row->ke_shift,
                'unit_divisi' => $row->unit_divisi,
                'jumlah_pasien_ranap' => $row->jumlah_pasien_ranap,
                'kamar_zona_a' => $row->kamar_zona_a,
                'kamar_zona_b' => $row->kamar_zona_b,
                'kamar_zona_c' => $row->kamar_zona_c,
                'kamar_zona_d' => $row->kamar_zona_d,
                'kamar_zona_e' => $row->kamar_zona_e,
                'kamar_keterangan' => $row->kamar_keterangan,
                'verbed_zona_a' => $row->verbed_zona_a,
                'verbed_zona_b' => $row->verbed_zona_b,
                'verbed_zona_c' => $row->verbed_zona_c,
                'verbed_zona_d' => $row->verbed_zona_d,
                'verbed_zona_e' => $row->verbed_zona_e,
                'verbed_keterangan' => $row->verbed_keterangan,
                'pengadaan_linen' => $row->pengadaan_linen,
                'check_unit' => $row->check_unit,
                'check_stock_bhp' => $row->check_stock_bhp,
                'permasalahan' => $row->permasalahan,
                'rencana_tindak_lanjut' => $row->rencana_tindak_lanjut,
                'catatan_lain' => $row->catatan_lain,
                'yang_mengoperkan' => $row->yang_mengoperkan,
                'yang_menerima_operan' => $row->yang_menerima_operan,
                'mengetahui' => $row->mengetahui,
            );
            $this->load->view('template/header_public', $data);
            $this->load->view('asper/asper_read');
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('asper'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('asper/create_action'),
            'id_asper' => set_value('id_asper'),
            'hari_tanggal' => set_value('hari_tanggal', date('Y-m-d')),
            'shift' => set_value('shift'),
            'ke_shift' => set_value('ke_shift'),
            'unit_divisi' => set_value('unit_divisi'),
            'kamar_keterangan' => set_value('kamar_keterangan'),
            'verbed_keterangan' => set_value('verbed_keterangan'),
            'pengadaan_linen' => set_value('pengadaan_linen'),
            'check_unit' => set_value('check_unit'),
            'check_stock_bhp' => set_value('check_stock_bhp'),
            'permasalahan' => set_value('permasalahan'),
            'rencana_tindak_lanjut' => set_value('rencana_tindak_lanjut'),
            'catatan_lain' => set_value('catatan_lain'),
            'yang_mengoperkan' => set_value('yang_mengoperkan'),
            'yang_menerima_operan' => set_value('yang_menerima_operan'),
            'mengetahui' => set_value('mengetahui'),
            // Mapping kode dikirim ke view supaya field angka per-kode di-render dari sini
            'zona_a' => $this->zona_a,
            'zona_b' => $this->zona_b,
            'zona_c' => $this->zona_c,
            'zona_d' => $this->zona_d,
            'zona_e' => $this->zona_e,
            'jumlah_pasien' => $this->jumlah_pasien,
        );

        $this->load->view('template/header_public', $data);
        $this->load->view('asper/asper_form_public');
        $this->load->view('template/footer');
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = $this->_post_data();
            $this->Asper_model->insert($data);
            $this->session->set_flashdata('message', 'Simpan Data Berhasil');
            redirect(site_url('asper/create'));
        }
    }

  public function update($id) 
    {
        $row = $this->Asper_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button'                => 'Ubah',
                'action'                => site_url('asper/update_action'),
                'id_asper'              => set_value('id_asper', $row->id_asper),
                'hari_tanggal'          => set_value('hari_tanggal', $row->hari_tanggal),
                'shift'                 => set_value('shift', $row->shift),
                'ke_shift'              => set_value('ke_shift', $row->ke_shift),
                'unit_divisi'           => set_value('unit_divisi', $row->unit_divisi),
                
                // Parse string database menjadi array agar input terisi otomatis
                'jumlah_pasien_vals'    => $this->_parse_zona($row->jumlah_pasien_ranap, $this->jumlah_pasien),
                'kamar_zona_a_vals'     => $this->_parse_zona($row->kamar_zona_a, $this->zona_a),
                'kamar_zona_b_vals'     => $this->_parse_zona($row->kamar_zona_b, $this->zona_b),
                'kamar_zona_c_vals'     => $this->_parse_zona($row->kamar_zona_c, $this->zona_c),
                'kamar_zona_d_vals'     => $this->_parse_zona($row->kamar_zona_d, $this->zona_d),
                'kamar_zona_e_vals'     => $this->_parse_zona($row->kamar_zona_e, $this->zona_e),
                'verbed_zona_a_vals'    => $this->_parse_zona($row->verbed_zona_a, $this->zona_a),
                'verbed_zona_b_vals'    => $this->_parse_zona($row->verbed_zona_b, $this->zona_b),
                'verbed_zona_c_vals'    => $this->_parse_zona($row->verbed_zona_c, $this->zona_c),
                'verbed_zona_d_vals'    => $this->_parse_zona($row->verbed_zona_d, $this->zona_d),
                'verbed_zona_e_vals'    => $this->_parse_zona($row->verbed_zona_e, $this->zona_e),
                
                'kamar_keterangan'      => set_value('kamar_keterangan', $row->kamar_keterangan),
                'verbed_keterangan'     => set_value('verbed_keterangan', $row->verbed_keterangan),
                'pengadaan_linen'       => set_value('pengadaan_linen', $row->pengadaan_linen),
                'check_unit'            => set_value('check_unit', $row->check_unit),
                'check_stock_bhp'       => set_value('check_stock_bhp', $row->check_stock_bhp),
                'permasalahan'          => set_value('permasalahan', $row->permasalahan),
                'rencana_tindak_lanjut' => set_value('rencana_tindak_lanjut', $row->rencana_tindak_lanjut),
                'catatan_lain'          => set_value('catatan_lain', $row->catatan_lain),
                
                'yang_mengoperkan'      => set_value('yang_mengoperkan', $row->yang_mengoperkan),
                'yang_menerima_operan'  => set_value('yang_menerima_operan', $row->yang_menerima_operan),
                'mengetahui'            => set_value('mengetahui', $row->mengetahui),
                
                'zona_a'                => $this->zona_a,
                'zona_b'                => $this->zona_b,
                'zona_c'                => $this->zona_c,
                'zona_d'                => $this->zona_d,
                'zona_e'                => $this->zona_e,
                'jumlah_pasien'         => $this->jumlah_pasien,
            );
            
            $this->load->view('template/header_public', $data);
            $this->load->view('asper/asper_form_public', $data); // Menggunakan asper_form_public.php
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Data tidak ditemukan');
            redirect(site_url('asper'));
        }
    }

    // Fungsi Eksekusi Update Action
  // Fungsi Eksekusi Update Action
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_asper', TRUE));
        } else {
            // Gunakan _post_data() agar array input zona & jumlah pasien otomatis diringkas menjadi string rapi
            $data = $this->_post_data();

            $this->Asper_model->update($this->input->post('id_asper', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Data Berhasil');
            redirect(site_url('asper'));
        }
    }

    public function delete($id)
    {
        $row = $this->Asper_model->get_by_id($id);

        if ($row) {
            $this->Asper_model->delete($id);
            $this->session->set_flashdata('message', 'Hapus Data Berhasil');
            redirect(site_url('asper'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('asper'));
        }
    }

    /**
     * Gabungkan input angka per kode (array dari form, mis. kamar_zona_a[UGD])
     * menjadi satu string "Label: nilai" per baris, sesuai kolom di DB (TEXT).
     * Baris dengan nilai kosong dilewati.
     * 
     * CATATAN: Method ini hanya digunakan oleh form create public yang menggunakan
     * input number per kode. Form update menggunakan textarea sehingga data sudah
     * dalam format "Label: nilai" dan dikirim langsung ke _post_data().
     */
    private function _compact_zona($field_name, $fields_map)
    {
        $post = $this->input->post($field_name, TRUE);
        $lines = array();

        if (is_array($post)) {
            foreach ($fields_map as $key => $label) {
                if (isset($post[$key]) && $post[$key] !== '') {
                    $lines[] = $label . ': ' . $post[$key];
                }
            }
        }

        return implode("\n", $lines);
    }

   private function _post_data()
    {
        return array(
            'hari_tanggal'          => $this->input->post('hari_tanggal', TRUE),
            'shift'                 => $this->input->post('shift', TRUE),
            'ke_shift'              => $this->input->post('ke_shift', TRUE),
            'unit_divisi'           => $this->input->post('unit_divisi', TRUE),
            
            // Ubah array input number menjadi string rapi menggunakan _compact_zona()
            'jumlah_pasien_ranap'   => $this->_compact_zona('jumlah_pasien_ranap', $this->jumlah_pasien),
            
            'kamar_zona_a'          => $this->_compact_zona('kamar_zona_a', $this->zona_a),
            'kamar_zona_b'          => $this->_compact_zona('kamar_zona_b', $this->zona_b),
            'kamar_zona_c'          => $this->_compact_zona('kamar_zona_c', $this->zona_c),
            'kamar_zona_d'          => $this->_compact_zona('kamar_zona_d', $this->zona_d),
            'kamar_zona_e'          => $this->_compact_zona('kamar_zona_e', $this->zona_e),
            'kamar_keterangan'      => $this->input->post('kamar_keterangan', TRUE),
            
            'verbed_zona_a'         => $this->_compact_zona('verbed_zona_a', $this->zona_a),
            'verbed_zona_b'         => $this->_compact_zona('verbed_zona_b', $this->zona_b),
            'verbed_zona_c'         => $this->_compact_zona('verbed_zona_c', $this->zona_c),
            'verbed_zona_d'         => $this->_compact_zona('verbed_zona_d', $this->zona_d),
            'verbed_zona_e'         => $this->_compact_zona('verbed_zona_e', $this->zona_e),
            'verbed_keterangan'     => $this->input->post('verbed_keterangan', TRUE),
            
            'pengadaan_linen'       => $this->input->post('pengadaan_linen', TRUE),
            'check_unit'            => $this->input->post('check_unit', TRUE),
            'check_stock_bhp'       => $this->input->post('check_stock_bhp', TRUE),
            'permasalahan'          => $this->input->post('permasalahan', TRUE),
            'rencana_tindak_lanjut' => $this->input->post('rencana_tindak_lanjut', TRUE),
            'catatan_lain'          => $this->input->post('catatan_lain', TRUE),
            
            'yang_mengoperkan'      => $this->input->post('yang_mengoperkan', TRUE),
            'yang_menerima_operan'  => $this->input->post('yang_menerima_operan', TRUE),
            'mengetahui'            => $this->input->post('mengetahui', TRUE),
        );
    }

    public function _rules()
    {
        $this->form_validation->set_rules('hari_tanggal', 'Hari/Tanggal', 'trim|required');
        $this->form_validation->set_rules('shift', 'Shift', 'trim|required');
        $this->form_validation->set_rules('ke_shift', 'Ke Shift', 'trim|required');
        $this->form_validation->set_rules('unit_divisi', 'Unit/Divisi', 'trim|required');
        $this->form_validation->set_rules('yang_mengoperkan', 'Yang Mengoperkan', 'trim|required');
        $this->form_validation->set_rules('yang_menerima_operan', 'Yang Menerima Operan', 'trim|required');

        $this->form_validation->set_rules('id_asper', 'id_asper', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "serah_terima_asper.xls";

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
        xlsWriteLabel($tablehead, $kolomhead++, "Hari/Tanggal");
        xlsWriteLabel($tablehead, $kolomhead++, "Shift");
        xlsWriteLabel($tablehead, $kolomhead++, "Ke Shift");
        xlsWriteLabel($tablehead, $kolomhead++, "Unit/Divisi");
        xlsWriteLabel($tablehead, $kolomhead++, "Jumlah Pasien Ranap");
        xlsWriteLabel($tablehead, $kolomhead++, "Kamar MRS/KRS - Zona A");
        xlsWriteLabel($tablehead, $kolomhead++, "Kamar MRS/KRS - Zona B");
        xlsWriteLabel($tablehead, $kolomhead++, "Kamar MRS/KRS - Zona C");
        xlsWriteLabel($tablehead, $kolomhead++, "Kamar MRS/KRS - Zona D");
        xlsWriteLabel($tablehead, $kolomhead++, "Kamar MRS/KRS - Zona E");
        xlsWriteLabel($tablehead, $kolomhead++, "Kamar MRS/KRS - Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Verbed - Zona A");
        xlsWriteLabel($tablehead, $kolomhead++, "Verbed - Zona B");
        xlsWriteLabel($tablehead, $kolomhead++, "Verbed - Zona C");
        xlsWriteLabel($tablehead, $kolomhead++, "Verbed - Zona D");
        xlsWriteLabel($tablehead, $kolomhead++, "Verbed - Zona E");
        xlsWriteLabel($tablehead, $kolomhead++, "Verbed - Keterangan");
        xlsWriteLabel($tablehead, $kolomhead++, "Pengadaan Linen");
        xlsWriteLabel($tablehead, $kolomhead++, "Check Unit-Unit");
        xlsWriteLabel($tablehead, $kolomhead++, "Check Stock BHP");
        xlsWriteLabel($tablehead, $kolomhead++, "Permasalahan");
        xlsWriteLabel($tablehead, $kolomhead++, "Rencana Tindak Lanjut");
        xlsWriteLabel($tablehead, $kolomhead++, "Catatan Lain-lain");
        xlsWriteLabel($tablehead, $kolomhead++, "Yang Mengoperkan");
        xlsWriteLabel($tablehead, $kolomhead++, "Yang Menerima Operan");
        xlsWriteLabel($tablehead, $kolomhead++, "Mengetahui");

        foreach ($this->Asper_model->get_all() as $data) {
            $kolombody = 0;
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->hari_tanggal);
            xlsWriteLabel($tablebody, $kolombody++, $data->shift);
            xlsWriteLabel($tablebody, $kolombody++, $data->ke_shift);
            xlsWriteLabel($tablebody, $kolombody++, $data->unit_divisi);
            xlsWriteLabel($tablebody, $kolombody++, $data->jumlah_pasien_ranap);
            xlsWriteLabel($tablebody, $kolombody++, $data->kamar_zona_a);
            xlsWriteLabel($tablebody, $kolombody++, $data->kamar_zona_b);
            xlsWriteLabel($tablebody, $kolombody++, $data->kamar_zona_c);
            xlsWriteLabel($tablebody, $kolombody++, $data->kamar_zona_d);
            xlsWriteLabel($tablebody, $kolombody++, $data->kamar_zona_e);
            xlsWriteLabel($tablebody, $kolombody++, $data->kamar_keterangan);
            xlsWriteLabel($tablebody, $kolombody++, $data->verbed_zona_a);
            xlsWriteLabel($tablebody, $kolombody++, $data->verbed_zona_b);
            xlsWriteLabel($tablebody, $kolombody++, $data->verbed_zona_c);
            xlsWriteLabel($tablebody, $kolombody++, $data->verbed_zona_d);
            xlsWriteLabel($tablebody, $kolombody++, $data->verbed_zona_e);
            xlsWriteLabel($tablebody, $kolombody++, $data->verbed_keterangan);
            xlsWriteLabel($tablebody, $kolombody++, $data->pengadaan_linen);
            xlsWriteLabel($tablebody, $kolombody++, $data->check_unit);
            xlsWriteLabel($tablebody, $kolombody++, $data->check_stock_bhp);
            xlsWriteLabel($tablebody, $kolombody++, $data->permasalahan);
            xlsWriteLabel($tablebody, $kolombody++, $data->rencana_tindak_lanjut);
            xlsWriteLabel($tablebody, $kolombody++, $data->catatan_lain);
            xlsWriteLabel($tablebody, $kolombody++, $data->yang_mengoperkan);
            xlsWriteLabel($tablebody, $kolombody++, $data->yang_menerima_operan);
            xlsWriteLabel($tablebody, $kolombody++, $data->mengetahui);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
    private function _parse_zona($text_data, $fields_map)
    {
        $result = array();
        if (empty($text_data)) {
            return $result;
        }

        $lines = explode("\n", $text_data);
        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);
            if (count($parts) == 2) {
                $label = trim($parts[0]);
                $value = trim($parts[1]);
                
                // Cari key aslinya berdasarkan label
                $key = array_search($label, $fields_map);
                if ($key !== false) {
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }
    

// =============================================
// TAMBAHKAN method ini ke dalam class Asper
// File: application/controllers/Asper.php
// =============================================

public function dashboard()
{
    $bulan = $this->input->get('bulan', TRUE) ?: date('m');
    $tahun = $this->input->get('tahun', TRUE) ?: date('Y');

    $data = array(
        'bulan'  => $bulan,
        'tahun'  => $tahun,
        'stats'  => $this->Asper_model->get_dashboard_stats($bulan, $tahun),
        'harian' => $this->Asper_model->get_pasien_harian($bulan, $tahun),
        'shift'  => $this->Asper_model->get_by_shift($bulan, $tahun),
        'zona'   => $this->Asper_model->get_zona_summary($bulan, $tahun),
        'verbed' => $this->Asper_model->get_verbed_summary($bulan, $tahun),
        'unit'   => $this->Asper_model->get_by_unit($bulan, $tahun),
        'ruang'  => $this->Asper_model->get_pasien_per_ruang($bulan, $tahun),
    );

    $this->load->view('template/header_public', $data);
    $this->load->view('asper/asper_dashboard', $data);
    $this->load->view('template/footer');
}

}

/* End of file Asper.php */
/* Location: ./application/controllers/Asper.php */