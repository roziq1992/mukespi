<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class insiden extends CI_Controller
{
    private $k3rs_errors = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('Ikp_model');
        $this->load->model('Budaya_keselamatan_model');
        $this->load->model('Laporan_pelanggaran_model');
        $this->load->model('Laporan_insiden_k3rs_model');
        $this->load->helper('ikp');
        $this->load->library('form_validation');
        $this->k3rs_errors = array();
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'index.php/insiden?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/insiden?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/insiden';
            $config['first_url'] = base_url() . 'index.php/insiden';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Ikp_model->total_rows($q);
        $ikp = $this->Ikp_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'ikp_data' => $ikp,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'modul' => 'insiden',
        );
         $this->load->view('template/header',$data);
         $this->load->view('ikp/ikp_list');
        $this->load->view('template/footer');
    }

    public function read($id) 
    {
        $row = $this->Ikp_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_ikp' => $row->id_ikp,
		'nm_pasien' => $row->nm_pasien,
		'rm' => $row->rm,
		'ruang' => $row->ruang,
		'kelamin' => $row->kelamin,
		'penangung_jawab' => $row->penangung_jawab,
		'tgl_masuk' => $row->tgl_masuk,
		'jam_masuk' => $row->jam_masuk,
		'tgl_kejadian' => $row->tgl_kejadian,
		'jam_kejadian' => $row->jam_kejadian,
		'insiden' => $row->insiden,
		'krologis' => $row->krologis,
		'jns_insiden' => $row->jns_insiden,
		'pelapor_pertama' => $row->pelapor_pertama,
		'insiden_terjadipd' => $row->insiden_terjadipd,
		'insiden_meyangkut' => $row->insiden_meyangkut,
		'tempat_insiden' => $row->tempat_insiden,
		'insiden_terjadipd2' => $row->insiden_terjadipd2,
		'unit_penyebab' => $row->unit_penyebab,
		'akibat_insiden' => $row->akibat_insiden,
		'tindakan' => $row->tindakan,
		'tindakan_oleh' => $row->tindakan_oleh,
		'kejadian_terulang' => $row->kejadian_terulang,
		'ket_kejadian_terulang' => $row->ket_kejadian_terulang,
		'pelapor' => $row->pelapor,
		'penerima' => $row->penerima,
		'tgl_lapor' => $row->tgl_lapor,
		'grading_resiko' => $row->grading_resiko,
	    );
            $data['modul'] = 'insiden';
            $this->load->view('template/header',$data);
            $this->load->view('ikp/ikp_read', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ikp'));
        }
    }

    public function create() 
    {
        $jenis_pelaporan = $this->input->get('jenis_pelaporan', TRUE);
        if (!in_array($jenis_pelaporan, array('ikp', 'budaya_keselamatan', 'pelanggaran', 'insiden_k3rs'), TRUE)) {
            $jenis_pelaporan = 'ikp';
        }
        $korban_rows = $this->input->post('korban', TRUE);
        $tindakan_rows = $this->input->post('tindakan_k3rs', TRUE);
        $k3rs_equipment = $this->input->post('peralatan_k3rs', TRUE);
        $k3rs_factors = $this->input->post('faktor_k3rs', TRUE);
        $k3rs_root_causes = $this->input->post('dasar_k3rs', TRUE);
        if (!is_array($korban_rows) || !$korban_rows) $korban_rows = array(array());
        if (!is_array($tindakan_rows) || !$tindakan_rows) $tindakan_rows = array(array(), array());
        if (!is_array($k3rs_equipment)) $k3rs_equipment = array();
        if (!is_array($k3rs_factors)) $k3rs_factors = array();
        if (!is_array($k3rs_root_causes)) $k3rs_root_causes = array();
        $data = array(
            'button' => 'Save',
            'action' => site_url('insiden/create_action'),
            'jenis_pelaporan' => set_value('jenis_pelaporan', $jenis_pelaporan),
            'nama_pelapor_budaya' => set_value('nama_pelapor_budaya'),
            'tanggal_kejadian_budaya' => set_value('tanggal_kejadian_budaya'),
            'lokasi_budaya' => set_value('lokasi_budaya'),
            'terlibat_budaya' => set_value('terlibat_budaya'),
            'uraian_budaya' => set_value('uraian_budaya'),
            'nama_pelapor_pelanggaran' => set_value('nama_pelapor_pelanggaran'),
            'tanggal_kejadian_pelanggaran' => set_value('tanggal_kejadian_pelanggaran'),
            'lokasi_pelanggaran' => set_value('lokasi_pelanggaran'),
            'staf_terlibat_pelanggaran' => set_value('staf_terlibat_pelanggaran'),
            'uraian_pelanggaran' => set_value('uraian_pelanggaran'),
            'k3rs_errors' => $this->k3rs_errors,
            'korban_rows' => $korban_rows,
            'tindakan_rows' => $tindakan_rows,
            'k3rs_equipment' => $k3rs_equipment,
            'k3rs_factors' => $k3rs_factors,
            'k3rs_root_causes' => $k3rs_root_causes,
	    'id_ikp' => set_value('id_ikp'),
	    'nm_pasien' => set_value('nm_pasien'),
	    'rm' => set_value('rm'),
	    'ruang' => set_value('ruang'),
	    'kelamin' => set_value('kelamin'),
	    'penangung_jawab' => set_value('penangung_jawab'),
	    'tgl_masuk' => set_value('tgl_masuk'),
	    'jam_masuk' => set_value('jam_masuk'),
	    'tgl_kejadian' => set_value('tgl_kejadian'),
	    'jam_kejadian' => set_value('jam_kejadian'),
	    'insiden' => set_value('insiden'),
	    'krologis' => set_value('krologis'),
	    'jns_insiden' => set_value('jns_insiden'),
	    'pelapor_pertama' => set_value('pelapor_pertama'),
	    'insiden_terjadipd' => set_value('insiden_terjadipd'),
	    'insiden_meyangkut' => set_value('insiden_meyangkut'),
	    'tempat_insiden' => set_value('tempat_insiden'),
	    'insiden_terjadipd2' => set_value('insiden_terjadipd2'),
	    'unit_penyebab' => set_value('unit_penyebab'),
	    'akibat_insiden' => set_value('akibat_insiden'),
	    'tindakan' => set_value('tindakan'),
	    'tindakan_oleh' => set_value('tindakan_oleh'),
	    'kejadian_terulang' => set_value('kejadian_terulang'),
	    'ket_kejadian_terulang' => set_value('ket_kejadian_terulang'),
	    'pelapor' => set_value('pelapor'),
	    'penerima' => set_value('penerima'),
	    'tgl_lapor' => set_value('tgl_lapor'),
	    'grading_resiko' => set_value('grading_resiko'),
	);
      
        
         $this->load->view('template/header_public',$data);
           $this->load->view('ikp/ikp_form_public');
        $this->load->view('template/footer');
    }
    
    public function create_action() 
    {
        $jenis_pelaporan = $this->input->post('jenis_pelaporan', TRUE);
        if ($jenis_pelaporan === 'budaya_keselamatan') {
            $this->_budaya_keselamatan_rules();
            if ($this->form_validation->run() == FALSE) {
                $this->create();
                return;
            }

            $saved = $this->Budaya_keselamatan_model->insert(array(
                'nama_pelapor' => trim($this->input->post('nama_pelapor_budaya', TRUE)),
                'tanggal_kejadian' => $this->input->post('tanggal_kejadian_budaya', TRUE),
                'lokasi' => trim($this->input->post('lokasi_budaya', TRUE)),
                'nama_staf_pengunjung' => trim($this->input->post('terlibat_budaya', TRUE)),
                'uraian_kejadian' => trim($this->input->post('uraian_budaya', TRUE)),
                'created_at' => date('Y-m-d H:i:s'),
            ));
            if (!$saved) {
                show_error('Laporan budaya keselamatan gagal disimpan.', 500);
                return;
            }
            $this->session->set_flashdata('message', 'Laporan budaya keselamatan berhasil dikirim.');
            redirect(site_url('insiden/create?jenis_pelaporan=budaya_keselamatan'));
            return;
        }

        if ($jenis_pelaporan === 'pelanggaran') {
            $this->_pelanggaran_rules();
            if ($this->form_validation->run() == FALSE) {
                $this->create();
                return;
            }

            $saved = $this->Laporan_pelanggaran_model->insert(array(
                'nama_pelapor' => trim($this->input->post('nama_pelapor_pelanggaran', TRUE)),
                'tanggal_kejadian' => $this->input->post('tanggal_kejadian_pelanggaran', TRUE),
                'lokasi' => trim($this->input->post('lokasi_pelanggaran', TRUE)),
                'nama_staf_terlibat' => trim($this->input->post('staf_terlibat_pelanggaran', TRUE)),
                'uraian_kejadian' => trim($this->input->post('uraian_pelanggaran', TRUE)),
                'created_at' => date('Y-m-d H:i:s'),
            ));
            if (!$saved) {
                show_error('Laporan pelanggaran gagal disimpan.', 500);
                return;
            }
            $this->session->set_flashdata('message', 'Laporan pelanggaran berhasil dikirim.');
            redirect(site_url('insiden/create?jenis_pelaporan=pelanggaran'));
            return;
        }

        if ($jenis_pelaporan === 'insiden_k3rs') {
            $this->_insiden_k3rs_rules();
            if ($this->form_validation->run() == FALSE) {
                $this->create();
                return;
            }

            $korban = $this->_normalize_k3rs_rows('korban', array('nama', 'kelamin', 'usia', 'jabatan', 'bagian', 'cedera', 'penanganan', 'lt', 'kategori'));
            $tindakan = $this->_normalize_k3rs_rows('tindakan_k3rs', array('jenis', 'rencana', 'target', 'wewenang'));
            if ($this->k3rs_errors) {
                $this->create();
                return;
            }

            $equipment = $this->input->post('peralatan_k3rs', TRUE);
            $factors = $this->input->post('faktor_k3rs', TRUE);
            $root_causes = $this->input->post('dasar_k3rs', TRUE);
            if ($equipment === NULL) $equipment = array();
            if ($factors === NULL) $factors = array();
            if ($root_causes === NULL) $root_causes = array();
            $allowed_equipment = array('Mesin', 'Material', 'Alat Berat');
            $allowed_factors = array('Kondisi Bahaya', 'Tindakan Bahaya', 'Pribadi', 'Pekerjaan');
            $allowed_root_causes = array('Kurang Prosedur', 'Kurang Sarana', 'Kurang Taat');
            if (!$this->_values_are_allowed($equipment, $allowed_equipment)
                || !$this->_values_are_allowed($factors, $allowed_factors)
                || !$this->_values_are_allowed($root_causes, $allowed_root_causes)) {
                show_error('Pilihan pada formulir investigasi tidak valid.', 400);
                return;
            }

            $saved = $this->Laporan_insiden_k3rs_model->insert(array(
                'tanggal' => $this->input->post('tanggal_k3rs', TRUE),
                'waktu' => $this->input->post('waktu_k3rs', TRUE),
                'pekerjaan' => trim($this->input->post('pekerjaan_k3rs', TRUE)),
                'lokasi' => trim($this->input->post('lokasi_k3rs', TRUE)),
                'peralatan_kerja' => implode(', ', $equipment),
                'kerugian_aset' => trim($this->input->post('kerugian_aset_k3rs', TRUE)),
                'kerugian_lingkungan' => trim($this->input->post('kerugian_lingkungan_k3rs', TRUE)),
                'kronologi' => trim($this->input->post('kronologi_k3rs', TRUE)),
                'penyebab_langsung' => trim($this->input->post('penyebab_langsung_k3rs', TRUE)),
                'penyebab_tidak_langsung' => trim($this->input->post('penyebab_tidak_langsung_k3rs', TRUE)),
                'kondisi_bahaya' => in_array('Kondisi Bahaya', $factors, TRUE) ? 1 : 0,
                'tindakan_bahaya' => in_array('Tindakan Bahaya', $factors, TRUE) ? 1 : 0,
                'pribadi' => in_array('Pribadi', $factors, TRUE) ? 1 : 0,
                'pekerjaan_faktor' => in_array('Pekerjaan', $factors, TRUE) ? 1 : 0,
                'kurang_prosedur' => in_array('Kurang Prosedur', $root_causes, TRUE) ? 1 : 0,
                'kurang_sarana' => in_array('Kurang Sarana', $root_causes, TRUE) ? 1 : 0,
                'kurang_taat' => in_array('Kurang Taat', $root_causes, TRUE) ? 1 : 0,
                'saksi' => trim($this->input->post('saksi_k3rs', TRUE)),
                'ketua_tim' => trim($this->input->post('ketua_tim_k3rs', TRUE)),
                'tanggal_disusun' => $this->input->post('tanggal_disusun_k3rs', TRUE) ?: NULL,
                'created_at' => date('Y-m-d H:i:s'),
            ), $korban, $tindakan);
            if (!$saved) {
                show_error('Laporan insiden K3RS gagal disimpan.', 500);
                return;
            }
            $this->session->set_flashdata('message', 'Laporan insiden K3RS berhasil dikirim.');
            redirect(site_url('insiden/create?jenis_pelaporan=insiden_k3rs'));
            return;
        }

        if ($jenis_pelaporan !== 'ikp') {
            $this->session->set_flashdata('message', 'Pilih jenis pelaporan yang valid.');
            redirect(site_url('insiden/create'));
            return;
        }

        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'nm_pasien' => $this->input->post('nm_pasien',TRUE),
		'rm' => $this->input->post('rm',TRUE),
		'ruang' => $this->input->post('ruang',TRUE),
		'kelamin' => $this->input->post('kelamin',TRUE),
		'penangung_jawab' => $this->input->post('penangung_jawab',TRUE),
		'tgl_masuk' => $this->input->post('tgl_masuk',TRUE),
		'jam_masuk' => $this->input->post('jam_masuk',TRUE),
		'tgl_kejadian' => $this->input->post('tgl_kejadian',TRUE),
		'jam_kejadian' => $this->input->post('jam_kejadian',TRUE),
		'insiden' => $this->input->post('insiden',TRUE),
		'krologis' => $this->input->post('krologis',TRUE),
		'jns_insiden' => $this->input->post('jns_insiden',TRUE),
		'pelapor_pertama' => $this->input->post('pelapor_pertama',TRUE),
		'insiden_terjadipd' => $this->input->post('insiden_terjadipd',TRUE),
		'insiden_meyangkut' => $this->input->post('insiden_meyangkut',TRUE),
		'tempat_insiden' => $this->input->post('tempat_insiden',TRUE),
		'insiden_terjadipd2' => $this->input->post('insiden_terjadipd2',TRUE),
		'unit_penyebab' => $this->input->post('unit_penyebab',TRUE),
		'akibat_insiden' => $this->input->post('akibat_insiden',TRUE),
		'tindakan' => $this->input->post('tindakan',TRUE),
		'tindakan_oleh' => $this->input->post('tindakan_oleh',TRUE),
		'kejadian_terulang' => $this->input->post('kejadian_terulang',TRUE),
		'ket_kejadian_terulang' => $this->input->post('ket_kejadian_terulang',TRUE),
		'pelapor' => $this->input->post('pelapor',TRUE),
		'penerima' => $this->input->post('penerima',TRUE),
		'tgl_lapor' => $this->input->post('tgl_lapor',TRUE),
		'grading_resiko' => $this->input->post('grading_resiko',TRUE),
	    );

            $this->Ikp_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('insiden/create'));
        }
    }

    public function budaya_keselamatan()
    {
        if (!$this->_ensure_mukespi_access()) return;
        $q = trim((string) $this->input->get('q', TRUE));
        $start = max(0, (int) $this->input->get('start'));
        $per_page = 20;
        $total_rows = $this->Budaya_keselamatan_model->total_rows($q);

        $this->load->library('pagination');
        $this->pagination->initialize(array(
            'base_url' => site_url('insiden/budaya_keselamatan'),
            'first_url' => site_url('insiden/budaya_keselamatan') . '?q=' . rawurlencode($q),
            'total_rows' => $total_rows,
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'reuse_query_string' => TRUE,
        ));

        $data = array(
            'laporan' => $this->Budaya_keselamatan_model->get_limit_data($per_page, $start, $q),
            'q' => $q,
            'start' => $start,
            'total_rows' => $total_rows,
            'pagination' => $this->pagination->create_links(),
        );
        $this->load->view('template/header', array('title' => 'Pelaporan Budaya Keselamatan'));
        $this->load->view('ikp/budaya_keselamatan_list', $data);
        $this->load->view('template/footer');
    }

    public function budaya_keselamatan_detail($id)
    {
        if (!$this->_ensure_mukespi_access()) return;
        $laporan = $this->Budaya_keselamatan_model->get_by_id((int) $id);
        if (!$laporan) {
            show_404();
            return;
        }
        $this->load->view('template/header', array('title' => 'Detail Budaya Keselamatan'));
        $this->load->view('ikp/budaya_keselamatan_detail', array('laporan' => $laporan));
        $this->load->view('template/footer');
    }

    public function hapus_budaya_keselamatan($id)
    {
        if (!$this->_ensure_mukespi_access()) return;
        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Metode permintaan tidak diizinkan.', 405);
            return;
        }
        if (!$this->Budaya_keselamatan_model->get_by_id((int) $id)) {
            show_404();
            return;
        }
        if (!$this->Budaya_keselamatan_model->delete((int) $id)) {
            show_error('Laporan budaya keselamatan gagal dihapus.', 500);
            return;
        }
        $this->session->set_flashdata('message', 'Laporan budaya keselamatan berhasil dihapus.');
        redirect(site_url('insiden/budaya_keselamatan'));
    }

    private function _budaya_keselamatan_rules()
    {
        $this->form_validation->set_rules('nama_pelapor_budaya', 'Nama', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('tanggal_kejadian_budaya', 'Tanggal kejadian', 'trim|required|callback_valid_tanggal_budaya');
        $this->form_validation->set_rules('lokasi_budaya', 'Lokasi', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('terlibat_budaya', 'Nama staf terlibat/pengunjung', 'trim|required|max_length[1000]');
        $this->form_validation->set_rules('uraian_budaya', 'Uraian kejadian', 'trim|required|max_length[10000]');
        $this->form_validation->set_error_delimiters('<span class="ikp-error">', '</span>');
    }

    public function valid_tanggal_budaya($value)
    {
        $date = \DateTime::createFromFormat('!Y-m-d', (string) $value);
        if ($date && $date->format('Y-m-d') === $value) {
            return TRUE;
        }
        $this->form_validation->set_message('valid_tanggal_budaya', 'Tanggal kejadian tidak valid.');
        return FALSE;
    }

    public function pelanggaran()
    {
        if (!$this->_ensure_mukespi_access()) return;
        $q = trim((string) $this->input->get('q', TRUE));
        $start = max(0, (int) $this->input->get('start'));
        $per_page = 20;
        $total_rows = $this->Laporan_pelanggaran_model->total_rows($q);

        $this->load->library('pagination');
        $this->pagination->initialize(array(
            'base_url' => site_url('insiden/pelanggaran'),
            'first_url' => site_url('insiden/pelanggaran') . '?q=' . rawurlencode($q),
            'total_rows' => $total_rows,
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'reuse_query_string' => TRUE,
        ));

        $data = array(
            'laporan' => $this->Laporan_pelanggaran_model->get_limit_data($per_page, $start, $q),
            'q' => $q,
            'start' => $start,
            'total_rows' => $total_rows,
            'pagination' => $this->pagination->create_links(),
        );
        $this->load->view('template/header', array('title' => 'Laporan Pelanggaran'));
        $this->load->view('ikp/laporan_pelanggaran_list', $data);
        $this->load->view('template/footer');
    }

    public function pelanggaran_detail($id)
    {
        if (!$this->_ensure_mukespi_access()) return;
        $laporan = $this->Laporan_pelanggaran_model->get_by_id((int) $id);
        if (!$laporan) {
            show_404();
            return;
        }
        $this->load->view('template/header', array('title' => 'Detail Laporan Pelanggaran'));
        $this->load->view('ikp/laporan_pelanggaran_detail', array('laporan' => $laporan));
        $this->load->view('template/footer');
    }

    public function hapus_pelanggaran($id)
    {
        if (!$this->_ensure_mukespi_access()) return;
        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Metode permintaan tidak diizinkan.', 405);
            return;
        }
        if (!$this->Laporan_pelanggaran_model->get_by_id((int) $id)) {
            show_404();
            return;
        }
        if (!$this->Laporan_pelanggaran_model->delete((int) $id)) {
            show_error('Laporan pelanggaran gagal dihapus.', 500);
            return;
        }
        $this->session->set_flashdata('message', 'Laporan pelanggaran berhasil dihapus.');
        redirect(site_url('insiden/pelanggaran'));
    }

    public function insiden_k3rs()
    {
        if (!$this->_ensure_mukespi_access()) return;
        $q = trim((string) $this->input->get('q', TRUE));
        $start = max(0, (int) $this->input->get('start'));
        $per_page = 20;
        $total_rows = $this->Laporan_insiden_k3rs_model->total_rows($q);

        $this->load->library('pagination');
        $this->pagination->initialize(array(
            'base_url' => site_url('insiden/insiden_k3rs'),
            'first_url' => site_url('insiden/insiden_k3rs') . '?q=' . rawurlencode($q),
            'total_rows' => $total_rows,
            'per_page' => $per_page,
            'page_query_string' => TRUE,
            'reuse_query_string' => TRUE,
        ));

        $data = array(
            'laporan' => $this->Laporan_insiden_k3rs_model->get_limit_data($per_page, $start, $q),
            'q' => $q,
            'start' => $start,
            'total_rows' => $total_rows,
            'pagination' => $this->pagination->create_links(),
        );
        $this->load->view('template/header', array('title' => 'Laporan Insiden K3RS'));
        $this->load->view('ikp/laporan_insiden_k3rs_list', $data);
        $this->load->view('template/footer');
    }

    public function insiden_k3rs_detail($id)
    {
        if (!$this->_ensure_mukespi_access()) return;
        $laporan = $this->Laporan_insiden_k3rs_model->get_by_id((int) $id);
        if (!$laporan) {
            show_404();
            return;
        }
        $this->load->view('template/header', array('title' => 'Detail Laporan Insiden K3RS'));
        $this->load->view('ikp/laporan_insiden_k3rs_detail', array('laporan' => $laporan));
        $this->load->view('template/footer');
    }

    public function hapus_insiden_k3rs($id)
    {
        if (!$this->_ensure_mukespi_access()) return;
        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Metode permintaan tidak diizinkan.', 405);
            return;
        }
        if (!$this->Laporan_insiden_k3rs_model->get_by_id((int) $id)) {
            show_404();
            return;
        }
        if (!$this->Laporan_insiden_k3rs_model->delete((int) $id)) {
            show_error('Laporan insiden K3RS gagal dihapus.', 500);
            return;
        }
        $this->session->set_flashdata('message', 'Laporan insiden K3RS berhasil dihapus.');
        redirect(site_url('insiden/insiden_k3rs'));
    }

    private function _insiden_k3rs_rules()
    {
        $this->form_validation->set_rules('tanggal_k3rs', 'Tanggal', 'trim|required|callback_valid_tanggal_k3rs');
        $this->form_validation->set_rules('waktu_k3rs', 'Waktu', 'trim|required|callback_valid_waktu_k3rs');
        $this->form_validation->set_rules('pekerjaan_k3rs', 'Pekerjaan', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('lokasi_k3rs', 'Lokasi', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('kerugian_aset_k3rs', 'Kerugian aset/material', 'trim|max_length[2000]');
        $this->form_validation->set_rules('kerugian_lingkungan_k3rs', 'Kerugian lingkungan', 'trim|max_length[2000]');
        $this->form_validation->set_rules('kronologi_k3rs', 'Kronologi', 'trim|required|max_length[10000]');
        $this->form_validation->set_rules('penyebab_langsung_k3rs', 'Penyebab langsung', 'trim|max_length[5000]');
        $this->form_validation->set_rules('penyebab_tidak_langsung_k3rs', 'Penyebab tidak langsung', 'trim|max_length[5000]');
        $this->form_validation->set_rules('saksi_k3rs', 'Saksi', 'trim|max_length[255]');
        $this->form_validation->set_rules('ketua_tim_k3rs', 'Ketua Tim K3RS', 'trim|max_length[255]');
        $this->form_validation->set_rules('tanggal_disusun_k3rs', 'Tanggal disusun', 'trim|callback_valid_tanggal_disusun_k3rs');
        $this->form_validation->set_error_delimiters('<span class="ikp-error">', '</span>');
    }

    public function valid_tanggal_k3rs($value)
    {
        if ($this->_is_valid_date($value)) return TRUE;
        $this->form_validation->set_message('valid_tanggal_k3rs', 'Tanggal kejadian tidak valid.');
        return FALSE;
    }

    public function valid_tanggal_disusun_k3rs($value)
    {
        if (trim((string) $value) === '' || $this->_is_valid_date($value)) return TRUE;
        $this->form_validation->set_message('valid_tanggal_disusun_k3rs', 'Tanggal disusun tidak valid.');
        return FALSE;
    }

    public function valid_waktu_k3rs($value)
    {
        if (preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/', (string) $value)) return TRUE;
        $this->form_validation->set_message('valid_waktu_k3rs', 'Waktu kejadian tidak valid.');
        return FALSE;
    }

    private function _is_valid_date($value)
    {
        $date = \DateTime::createFromFormat('!Y-m-d', (string) $value);
        return $date && $date->format('Y-m-d') === $value;
    }

    private function _normalize_k3rs_rows($post_key, $fields)
    {
        $rows = $this->input->post($post_key, TRUE);
        if ($rows === NULL) return array();
        if (!is_array($rows) || count($rows) > 20) {
            $this->k3rs_errors[] = 'Maksimal 20 baris data dapat dikirim pada setiap tabel.';
            return array();
        }

        $normalized = array();
        foreach ($rows as $row) {
            if (!is_array($row)) {
                $this->k3rs_errors[] = 'Format data tabel tidak valid.';
                continue;
            }
            $item = array();
            $has_value = FALSE;
            foreach ($fields as $field) {
                $value = isset($row[$field]) && is_scalar($row[$field]) ? trim((string) $row[$field]) : '';
                if ($value !== '') $has_value = TRUE;
                $item[$field] = $value;
                if (strlen($value) > 1000) {
                    $this->k3rs_errors[] = 'Isian tabel tidak boleh melebihi 1.000 karakter.';
                }
            }
            if (!$has_value) continue;

            if ($post_key === 'korban') {
                if ($item['nama'] === '') {
                    $this->k3rs_errors[] = 'Nama wajib diisi untuk setiap data korban.';
                    continue;
                }
                if (!in_array($item['kelamin'], array('', 'L', 'P'), TRUE)
                    || !in_array($item['kategori'], array('', 'Ringan', 'Sedang', 'Berat', 'Fatal'), TRUE)
                    || ($item['lt'] !== '' && (!ctype_digit($item['lt']) || (int) $item['lt'] > 3650))) {
                    $this->k3rs_errors[] = 'Data jenis kelamin, kategori, atau LT korban tidak valid.';
                    continue;
                }
                $item['lt'] = $item['lt'] === '' ? NULL : (int) $item['lt'];
            } else {
                if (!in_array($item['jenis'], array('Perbaikan', 'Pencegahan'), TRUE)
                    || $item['rencana'] === '' || !$this->_is_valid_date($item['target']) || $item['wewenang'] === '') {
                    $this->k3rs_errors[] = 'Setiap rencana tindakan harus memiliki jenis, rencana, target tanggal, dan wewenang yang valid.';
                    continue;
                }
            }
            $max_lengths = $post_key === 'korban'
                ? array('nama' => 255, 'kelamin' => 1, 'usia' => 30, 'jabatan' => 255, 'bagian' => 255, 'cedera' => 1000, 'penanganan' => 1000, 'lt' => 4, 'kategori' => 20)
                : array('jenis' => 20, 'rencana' => 1000, 'target' => 10, 'wewenang' => 255);
            foreach ($max_lengths as $field => $max_length) {
                if (strlen((string) $item[$field]) > $max_length) {
                    $this->k3rs_errors[] = 'Panjang salah satu isian tabel melebihi batas yang diperbolehkan.';
                    continue 2;
                }
            }
            $normalized[] = $item;
        }
        return $normalized;
    }

    private function _values_are_allowed($values, $allowed)
    {
        if (!is_array($values)) return FALSE;
        foreach ($values as $value) {
            if (!is_string($value) || !in_array($value, $allowed, TRUE)) return FALSE;
        }
        return TRUE;
    }

    private function _pelanggaran_rules()
    {
        $this->form_validation->set_rules('nama_pelapor_pelanggaran', 'Nama', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('tanggal_kejadian_pelanggaran', 'Tanggal kejadian', 'trim|required|callback_valid_tanggal_budaya');
        $this->form_validation->set_rules('lokasi_pelanggaran', 'Lokasi', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('staf_terlibat_pelanggaran', 'Nama staf terlibat', 'trim|required|max_length[1000]');
        $this->form_validation->set_rules('uraian_pelanggaran', 'Uraian kejadian', 'trim|required|max_length[10000]');
        $this->form_validation->set_error_delimiters('<span class="ikp-error">', '</span>');
    }

    private function _ensure_mukespi_access()
    {
        is_logged_in();
        if (!is_menu_accessible('list_indikator')) {
            $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Anda tidak memiliki akses ke laporan MUKESPI.</div>');
            redirect('portal');
            return FALSE;
        }
        return TRUE;
    }
    
    public function update($id) 
    {
        $row = $this->Ikp_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('insiden/update_action'),
                'cancel_url' => site_url('insiden'),
		'id_ikp' => set_value('id_ikp', $row->id_ikp),
		'nm_pasien' => set_value('nm_pasien', $row->nm_pasien),
		'rm' => set_value('rm', $row->rm),
		'ruang' => set_value('ruang', $row->ruang),
		'kelamin' => set_value('kelamin', $row->kelamin),
		'penangung_jawab' => set_value('penangung_jawab', $row->penangung_jawab),
		'tgl_masuk' => set_value('tgl_masuk', $row->tgl_masuk),
		'jam_masuk' => set_value('jam_masuk', $row->jam_masuk),
		'tgl_kejadian' => set_value('tgl_kejadian', $row->tgl_kejadian),
		'jam_kejadian' => set_value('jam_kejadian', $row->jam_kejadian),
		'insiden' => set_value('insiden', $row->insiden),
		'krologis' => set_value('krologis', $row->krologis),
		'jns_insiden' => set_value('jns_insiden', $row->jns_insiden),
		'pelapor_pertama' => set_value('pelapor_pertama', $row->pelapor_pertama),
		'insiden_terjadipd' => set_value('insiden_terjadipd', $row->insiden_terjadipd),
		'insiden_meyangkut' => set_value('insiden_meyangkut', $row->insiden_meyangkut),
		'tempat_insiden' => set_value('tempat_insiden', $row->tempat_insiden),
		'insiden_terjadipd2' => set_value('insiden_terjadipd2', $row->insiden_terjadipd2),
		'unit_penyebab' => set_value('unit_penyebab', $row->unit_penyebab),
		'akibat_insiden' => set_value('akibat_insiden', $row->akibat_insiden),
		'tindakan' => set_value('tindakan', $row->tindakan),
		'tindakan_oleh' => set_value('tindakan_oleh', $row->tindakan_oleh),
		'kejadian_terulang' => set_value('kejadian_terulang', $row->kejadian_terulang),
		'ket_kejadian_terulang' => set_value('ket_kejadian_terulang', $row->ket_kejadian_terulang),
		'pelapor' => set_value('pelapor', $row->pelapor),
		'penerima' => set_value('penerima', $row->penerima),
		'tgl_lapor' => set_value('tgl_lapor', $row->tgl_lapor),
		'grading_resiko' => set_value('grading_resiko', $row->grading_resiko),
	    );
    	     $this->load->view('template/header',$data);
             $this->load->view('ikp/ikp_form');
            $this->load->view('template/footer');
           
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ikp'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_ikp', TRUE));
        } else {
            $data = array(
		'nm_pasien' => $this->input->post('nm_pasien',TRUE),
		'rm' => $this->input->post('rm',TRUE),
		'ruang' => $this->input->post('ruang',TRUE),
		'kelamin' => $this->input->post('kelamin',TRUE),
		'penangung_jawab' => $this->input->post('penangung_jawab',TRUE),
		'tgl_masuk' => $this->input->post('tgl_masuk',TRUE),
		'jam_masuk' => $this->input->post('jam_masuk',TRUE),
		'tgl_kejadian' => $this->input->post('tgl_kejadian',TRUE),
		'jam_kejadian' => $this->input->post('jam_kejadian',TRUE),
		'insiden' => $this->input->post('insiden',TRUE),
		'krologis' => $this->input->post('krologis',TRUE),
		'jns_insiden' => $this->input->post('jns_insiden',TRUE),
		'pelapor_pertama' => $this->input->post('pelapor_pertama',TRUE),
		'insiden_terjadipd' => $this->input->post('insiden_terjadipd',TRUE),
		'insiden_meyangkut' => $this->input->post('insiden_meyangkut',TRUE),
		'tempat_insiden' => $this->input->post('tempat_insiden',TRUE),
		'insiden_terjadipd2' => $this->input->post('insiden_terjadipd2',TRUE),
		'unit_penyebab' => $this->input->post('unit_penyebab',TRUE),
		'akibat_insiden' => $this->input->post('akibat_insiden',TRUE),
		'tindakan' => $this->input->post('tindakan',TRUE),
		'tindakan_oleh' => $this->input->post('tindakan_oleh',TRUE),
		'kejadian_terulang' => $this->input->post('kejadian_terulang',TRUE),
		'ket_kejadian_terulang' => $this->input->post('ket_kejadian_terulang',TRUE),
		'pelapor' => $this->input->post('pelapor',TRUE),
		'penerima' => $this->input->post('penerima',TRUE),
		'tgl_lapor' => $this->input->post('tgl_lapor',TRUE),
		'grading_resiko' => $this->input->post('grading_resiko',TRUE),
	    );

            $this->Ikp_model->update($this->input->post('id_ikp', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('ikp'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Ikp_model->get_by_id($id);

        if ($row) {
            $this->Ikp_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('ikp'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ikp'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('nm_pasien', 'nm pasien', 'trim|required');
	$this->form_validation->set_rules('rm', 'rm', 'trim|required');
	$this->form_validation->set_rules('ruang', 'ruang', 'trim|required');
	$this->form_validation->set_rules('kelamin', 'kelamin', 'trim|required');
	$this->form_validation->set_rules('penangung_jawab', 'penangung jawab', 'trim|required');
	$this->form_validation->set_rules('tgl_masuk', 'tgl masuk', 'trim|required');
	$this->form_validation->set_rules('jam_masuk', 'jam masuk', 'trim|required');
	$this->form_validation->set_rules('tgl_kejadian', 'tgl kejadian', 'trim|required');
	$this->form_validation->set_rules('jam_kejadian', 'jam kejadian', 'trim|required');
	$this->form_validation->set_rules('insiden', 'insiden', 'trim|required');
	$this->form_validation->set_rules('krologis', 'krologis', 'trim|required');
	$this->form_validation->set_rules('jns_insiden', 'jns insiden', 'trim|required');
	$this->form_validation->set_rules('pelapor_pertama', 'pelapor pertama', 'trim|required');
	$this->form_validation->set_rules('insiden_terjadipd', 'insiden terjadipd', 'trim|required');
	$this->form_validation->set_rules('insiden_meyangkut', 'insiden meyangkut', 'trim|required');
	$this->form_validation->set_rules('tempat_insiden', 'tempat insiden', 'trim|required');
	$this->form_validation->set_rules('insiden_terjadipd2', 'insiden terjadipd2', 'trim|required');
	$this->form_validation->set_rules('unit_penyebab', 'unit penyebab', 'trim|required');
	$this->form_validation->set_rules('akibat_insiden', 'akibat insiden', 'trim|required');
	$this->form_validation->set_rules('tindakan', 'tindakan', 'trim|required');
	$this->form_validation->set_rules('tindakan_oleh', 'tindakan oleh', 'trim|required');
	$this->form_validation->set_rules('kejadian_terulang', 'kejadian terulang', 'trim|required');
	$this->form_validation->set_rules('ket_kejadian_terulang', 'ket kejadian terulang', 'trim|callback_cek_ket_kejadian_terulang');
	$this->form_validation->set_rules('pelapor', 'pelapor', 'trim|required');
	$this->form_validation->set_rules('penerima', 'penerima', 'trim|required');
	$this->form_validation->set_rules('tgl_lapor', 'tgl lapor', 'trim|required');
	$this->form_validation->set_rules('grading_resiko', 'grading resiko', 'trim|required');

	$this->form_validation->set_rules('id_ikp', 'id_ikp', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    /**
     * Keterangan kejadian terulang hanya wajib diisi bila kejadian terulang = Ya.
     */
    public function cek_ket_kejadian_terulang($value)
    {
        if ($this->input->post('kejadian_terulang', TRUE) === 'Ya' && trim((string) $value) === '') {
            $this->form_validation->set_message(
                'cek_ket_kejadian_terulang',
                'Keterangan kejadian terulang wajib diisi bila kejadian terulang = Ya.'
            );
            return FALSE;
        }
        return TRUE;
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "ikp.xls";
        $judul = "ikp";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
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
	xlsWriteLabel($tablehead, $kolomhead++, "Nm Pasien");
	xlsWriteLabel($tablehead, $kolomhead++, "Rm");
	xlsWriteLabel($tablehead, $kolomhead++, "Ruang");
	xlsWriteLabel($tablehead, $kolomhead++, "Kelamin");
	xlsWriteLabel($tablehead, $kolomhead++, "Penangung Jawab");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Masuk");
	xlsWriteLabel($tablehead, $kolomhead++, "Jam Masuk");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Kejadian");
	xlsWriteLabel($tablehead, $kolomhead++, "Jam Kejadian");
	xlsWriteLabel($tablehead, $kolomhead++, "Insiden");
	xlsWriteLabel($tablehead, $kolomhead++, "Krologis");
	xlsWriteLabel($tablehead, $kolomhead++, "Jns Insiden");
	xlsWriteLabel($tablehead, $kolomhead++, "Pelapor Pertama");
	xlsWriteLabel($tablehead, $kolomhead++, "Insiden Terjadipd");
	xlsWriteLabel($tablehead, $kolomhead++, "Insiden Meyangkut");
	xlsWriteLabel($tablehead, $kolomhead++, "Tempat Insiden");
	xlsWriteLabel($tablehead, $kolomhead++, "Insiden Terjadipd2");
	xlsWriteLabel($tablehead, $kolomhead++, "Unit Penyebab");
	xlsWriteLabel($tablehead, $kolomhead++, "Akibat Insiden");
	xlsWriteLabel($tablehead, $kolomhead++, "Tindakan");
	xlsWriteLabel($tablehead, $kolomhead++, "Tindakan Oleh");
	xlsWriteLabel($tablehead, $kolomhead++, "Kejadian Terulang");
	xlsWriteLabel($tablehead, $kolomhead++, "Ket Kejadian Terulang");
	xlsWriteLabel($tablehead, $kolomhead++, "Pelapor");
	xlsWriteLabel($tablehead, $kolomhead++, "Penerima");
	xlsWriteLabel($tablehead, $kolomhead++, "Tgl Lapor");
	xlsWriteLabel($tablehead, $kolomhead++, "Grading Resiko");

	foreach ($this->Ikp_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nm_pasien);
	    xlsWriteLabel($tablebody, $kolombody++, $data->rm);
	    xlsWriteLabel($tablebody, $kolombody++, $data->ruang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kelamin);
	    xlsWriteLabel($tablebody, $kolombody++, $data->penangung_jawab);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_masuk);
	    xlsWriteLabel($tablebody, $kolombody++, $data->jam_masuk);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_kejadian);
	    xlsWriteLabel($tablebody, $kolombody++, $data->jam_kejadian);
	    xlsWriteLabel($tablebody, $kolombody++, $data->insiden);
	    xlsWriteLabel($tablebody, $kolombody++, $data->krologis);
	    xlsWriteLabel($tablebody, $kolombody++, $data->jns_insiden);
	    xlsWriteLabel($tablebody, $kolombody++, $data->pelapor_pertama);
	    xlsWriteLabel($tablebody, $kolombody++, $data->insiden_terjadipd);
	    xlsWriteLabel($tablebody, $kolombody++, $data->insiden_meyangkut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tempat_insiden);
	    xlsWriteLabel($tablebody, $kolombody++, $data->insiden_terjadipd2);
	    xlsWriteLabel($tablebody, $kolombody++, $data->unit_penyebab);
	    xlsWriteLabel($tablebody, $kolombody++, $data->akibat_insiden);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tindakan);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tindakan_oleh);
	    xlsWriteLabel($tablebody, $kolombody++, $data->kejadian_terulang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->ket_kejadian_terulang);
	    xlsWriteLabel($tablebody, $kolombody++, $data->pelapor);
	    xlsWriteLabel($tablebody, $kolombody++, $data->penerima);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tgl_lapor);
	    xlsWriteLabel($tablebody, $kolombody++, $data->grading_resiko);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Ikp.php */
/* Location: ./application/controllers/Ikp.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2023-01-16 03:23:02 */
/* http://harviacode.com */