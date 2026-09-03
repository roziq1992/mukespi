<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring_pj extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Monitoring_pj_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'index.php/monitoring_pj/?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/monitoring_pj/?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/monitoring_pj/';
            $config['first_url'] = base_url() . 'index.php/monitoring_pj/';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Monitoring_pj_model->total_rows($q);
        $monitoring_pj = $this->Monitoring_pj_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'monitoring_pj_data' => $monitoring_pj,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $this->load->view('template/header', $data);
        $this->load->view('monitoring_pj/monitoring_pj_list');
        $this->load->view('template/footer');
    }

    public function read($id)
    {
        $row = $this->Monitoring_pj_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id_monitoring' => $row->id_monitoring,
                'nm_pj' => $row->nm_pj,
                'nama_aplikasi' => $row->nama_aplikasi,
                'bulan' => $row->bulan,
                'tahun' => $row->tahun,
                'progres' => $row->progres,
                'keterangan' => $row->keterangan,
            );
            $this->load->view('monitoring_pj/monitoring_pj_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('monitoring_pj'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('monitoring_pj/create_action'),
            'id_monitoring' => set_value('id_monitoring'),
            'nm_pj' => set_value('nm_pj'),
            'nama_aplikasi' => set_value('nama_aplikasi'),
            'bulan' => set_value('bulan'),
            'tahun' => set_value('tahun'),
            'progres' => set_value('progres'),
            'keterangan' => set_value('keterangan'),
        );

        $this->load->view('template/header', $data);
        $this->load->view('monitoring_pj/monitoring_pj_form');
        $this->load->view('template/footer');
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'nm_pj' => $this->input->post('nm_pj', TRUE),
                'nama_aplikasi' => $this->input->post('nama_aplikasi', TRUE),
                'bulan' => $this->input->post('bulan', TRUE),
                'tahun' => $this->input->post('tahun', TRUE),
                'progres' => $this->input->post('progres', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'userid' => $this->session->userdata('id'),
            );

            $this->Monitoring_pj_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('monitoring_pj'));
        }
    }

    public function update($id)
    {
        $row = $this->Monitoring_pj_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('monitoring_pj/update_action'),
                'id_monitoring' => set_value('id_monitoring', $row->id_monitoring),
                'nm_pj' => set_value('nm_pj', $row->nm_pj),
                'nama_aplikasi' => set_value('nama_aplikasi', $row->nama_aplikasi),
                'bulan' => set_value('bulan', $row->bulan),
                'tahun' => set_value('tahun', $row->tahun),
                'progres' => set_value('progres', $row->progres),
                'keterangan' => set_value('keterangan', $row->keterangan),
            );
            $this->load->view('template/header', $data);
            $this->load->view('monitoring_pj/monitoring_pj_form');
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('monitoring_pj'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_monitoring', TRUE));
        } else {
            $data = array(
                'nm_pj' => $this->input->post('nm_pj', TRUE),
                'nama_aplikasi' => $this->input->post('nama_aplikasi', TRUE),
                'bulan' => $this->input->post('bulan', TRUE),
                'tahun' => $this->input->post('tahun', TRUE),
                'progres' => $this->input->post('progres', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
            );

            $this->Monitoring_pj_model->update($this->input->post('id_monitoring', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('monitoring_pj'));
        }
    }

    public function delete($id)
    {
        $row = $this->Monitoring_pj_model->get_by_id($id);

        if ($row) {
            $this->Monitoring_pj_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('monitoring_pj'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('monitoring_pj'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nm_pj', 'nm_pj', 'trim|required');
        $this->form_validation->set_rules('nama_aplikasi', 'nama_aplikasi', 'trim|required');
        $this->form_validation->set_rules('bulan', 'bulan', 'trim|required');
        $this->form_validation->set_rules('tahun', 'tahun', 'trim|required|numeric');
        $this->form_validation->set_rules('progres', 'progres', 'trim|required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');

        $this->form_validation->set_rules('id_monitoring', 'id_monitoring', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "monitoring_pj.xls";
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
        xlsWriteLabel($tablehead, $kolomhead++, "Nama PJ");
        xlsWriteLabel($tablehead, $kolomhead++, "Nama Aplikasi");
        xlsWriteLabel($tablehead, $kolomhead++, "Bulan");
        xlsWriteLabel($tablehead, $kolomhead++, "Tahun");
        xlsWriteLabel($tablehead, $kolomhead++, "Progres (%)");
        xlsWriteLabel($tablehead, $kolomhead++, "Keterangan");

        foreach ($this->Monitoring_pj_model->get_all() as $data) {
            $kolombody = 0;

            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->nm_pj);
            xlsWriteLabel($tablebody, $kolombody++, $data->nama_aplikasi);
            xlsWriteLabel($tablebody, $kolombody++, $data->bulan);
            xlsWriteLabel($tablebody, $kolombody++, $data->tahun);
            xlsWriteNumber($tablebody, $kolombody++, $data->progres);
            xlsWriteLabel($tablebody, $kolombody++, $data->keterangan);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }
        public function dashboard()
    {
        $bulan_list = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');

        $bulan = $this->input->get('bulan') ? $this->input->get('bulan') : $bulan_list[date('n') - 1];
        $tahun = $this->input->get('tahun') ? $this->input->get('tahun') : date('Y');

        $chart_data = $this->Monitoring_pj_model->get_by_bulan_tahun($bulan, $tahun);
        $tahun_list = $this->Monitoring_pj_model->get_tahun_list();

        $labels = array();
        $progres = array();
        foreach ($chart_data as $row) {
            $labels[] = $row->nama_aplikasi;
            $progres[] = (int) $row->progres;
        }

        $data = array(
            'bulan_list' => $bulan_list,
            'tahun_list' => $tahun_list,
            'bulan_selected' => $bulan,
            'tahun_selected' => $tahun,
            'chart_data' => $chart_data,
            'labels' => $labels,
            'progres' => $progres,
        );

        $this->load->view('template/header', $data);
        $this->load->view('monitoring_pj/monitoring_pj_dashboard', $data);
        $this->load->view('template/footer');
    }
}

/* End of file Monitoring_pj.php */
/* Location: ./application/controllers/Monitoring_pj.php */