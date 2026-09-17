<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data_inventaris extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Data_inventaris_model');
        $this->load->model('Pemeliharaan_model'); // Load pemeliharaan model
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'index.php/data_inventaris/index?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/data_inventaris/index?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/data_inventaris/index';
            $config['first_url'] = base_url() . 'index.php/data_inventaris/index';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Data_inventaris_model->total_rows($q);
        $data_inventaris = $this->Data_inventaris_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'data_inventaris_data' => $data_inventaris,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $this->load->view('template/header', $data);
        $this->load->view('data_inventaris/data_inventaris_list', $data);
        $this->load->view('template/footer');
    }

    public function read($id)
    {
        $row = $this->Data_inventaris_model->get_by_id($id);
        if ($row) {
            // Get maintenance history for this inventory
            $history = $this->Pemeliharaan_model->get_history_by_inventory($id);
            
            $data = array(
                'id_inven' => $row->id_inven,
                'kode_inven' => $row->kode_inven,
                'nm_barang' => $row->nm_barang,
                'merek' => $row->merek,
                'tipe' => $row->tipe,
                'sn' => $row->sn,
                'jenis' => $row->jenis,
                'kondisi' => $row->kondisi,
                'id_ruang' => $row->id_ruang,
                'harga' => $row->harga,
                'stts' => $row->stts,
                'maintenance_history' => $history,
            );

            $this->load->view('template/header', $data);
            $this->load->view('data_inventaris/data_inventaris_read', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('data_inventaris'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('data_inventaris/create_action'),
            'id_inven' => set_value('id_inven'),
            'kode_inven' => set_value('kode_inven'),
            'nm_barang' => set_value('nm_barang'),
            'merek' => set_value('merek'),
            'tipe' => set_value('tipe'),
            'sn' => set_value('sn'),
            'jenis' => set_value('jenis'),
            'kondisi' => set_value('kondisi'),
            'id_ruang' => set_value('id_ruang'),
            'harga' => set_value('harga'),
            'stts' => set_value('stts'),
        );

        $this->load->view('template/header', $data);
        $this->load->view('data_inventaris/data_inventaris_form', $data);
        $this->load->view('template/footer');
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'kode_inven' => $this->input->post('kode_inven', TRUE),
                'nm_barang' => $this->input->post('nm_barang', TRUE),
                'merek' => $this->input->post('merek', TRUE),
                'tipe' => $this->input->post('tipe', TRUE),
                'sn' => $this->input->post('sn', TRUE),
                'jenis' => $this->input->post('jenis', TRUE),
                'kondisi' => $this->input->post('kondisi', TRUE),
                'id_ruang' => $this->input->post('id_ruang', TRUE),
                'harga' => $this->input->post('harga', TRUE),
                'stts' => $this->input->post('stts', TRUE),
            );

            $this->Data_inventaris_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('data_inventaris'));
        }
    }

    public function update($id)
    {
        $row = $this->Data_inventaris_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('data_inventaris/update_action'),
                'id_inven' => set_value('id_inven', $row->id_inven),
                'kode_inven' => set_value('kode_inven', $row->kode_inven),
                'nm_barang' => set_value('nm_barang', $row->nm_barang),
                'merek' => set_value('merek', $row->merek),
                'tipe' => set_value('tipe', $row->tipe),
                'sn' => set_value('sn', $row->sn),
                'jenis' => set_value('jenis', $row->jenis),
                'kondisi' => set_value('kondisi', $row->kondisi),
                'id_ruang' => set_value('id_ruang', $row->id_ruang),
                'harga' => set_value('harga', $row->harga),
                'stts' => set_value('stts', $row->stts),
            );

            $this->load->view('template/header', $data);
            $this->load->view('data_inventaris/data_inventaris_form', $data);
            $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('data_inventaris'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_inven', TRUE));
        } else {
            $data = array(
                'kode_inven' => $this->input->post('kode_inven', TRUE),
                'nm_barang' => $this->input->post('nm_barang', TRUE),
                'merek' => $this->input->post('merek', TRUE),
                'tipe' => $this->input->post('tipe', TRUE),
                'sn' => $this->input->post('sn', TRUE),
                'jenis' => $this->input->post('jenis', TRUE),
                'kondisi' => $this->input->post('kondisi', TRUE),
                'id_ruang' => $this->input->post('id_ruang', TRUE),
                'harga' => $this->input->post('harga', TRUE),
                'stts' => $this->input->post('stts', TRUE),
            );

            $this->Data_inventaris_model->update($this->input->post('id_inven', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('data_inventaris'));
        }
    }

    public function delete($id)
    {
        $row = $this->Data_inventaris_model->get_by_id($id);

        if ($row) {
            $this->Data_inventaris_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('data_inventaris'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('data_inventaris'));
        }
    }

    // ==================== PEMELIHARAAN METHODS ====================

    public function pemeliharaan()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = base_url() . 'index.php/data_inventaris/pemeliharaan?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'index.php/data_inventaris/pemeliharaan?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'index.php/data_inventaris/pemeliharaan';
            $config['first_url'] = base_url() . 'index.php/data_inventaris/pemeliharaan';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Pemeliharaan_model->total_rows($q);
        $data_pemeliharaan = $this->Pemeliharaan_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'data_pemeliharaan' => $data_pemeliharaan,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $this->load->view('template/header', $data);
        $this->load->view('data_inventaris/pemeliharaan_list', $data);
        $this->load->view('template/footer');
    }

 public function pemeliharaan_form($id = null)
{
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Get inventory list for dropdown
    $inventaris_list = $this->Data_inventaris_model->get_all();
    
    // Check if data exists
    if (!$inventaris_list) {
        $inventaris_list = array();
    }
    
    // Get inventory id from GET parameter (from tracking modal)
    $inven_id = $this->input->get('inven_id');
    
    if ($id == null) {
        // Create new - check if inven_id is provided
        $selected_inven = $inven_id ?? '';
        
        // If inven_id is provided, verify it exists
        if ($selected_inven != '') {
            $check = $this->Data_inventaris_model->get_by_id($selected_inven);
            if (!$check) {
                $selected_inven = '';
            }
        }
        
        $data = array(
            'button' => 'Tambah',
            'action' => site_url('data_inventaris/pemeliharaan_action'),
            'id_pemeliharaan' => '',
            'id_inven' => $selected_inven,
            'tanggal' => date('Y-m-d'),
            'keterangan' => '',
            'biaya' => '',
            'petugas' => '',
            'status' => 'Proses',
            'inventaris_list' => $inventaris_list,
        );
    } else {
        // Edit existing
        $row = $this->Pemeliharaan_model->get_by_id($id);
        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('data_inventaris/pemeliharaan_action'),
                'id_pemeliharaan' => $row->id_pemeliharaan,
                'id_inven' => $row->id_inven,
                'tanggal' => $row->tanggal,
                'keterangan' => $row->keterangan,
                'biaya' => $row->biaya,
                'petugas' => $row->petugas,
                'status' => $row->status,
                'inventaris_list' => $inventaris_list,
            );
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('data_inventaris/pemeliharaan'));
            return;
        }
    }
    
    // Load views
    $this->load->view('template/header', $data);
    $this->load->view('data_inventaris/pemeliharaan_form', $data);
    $this->load->view('template/footer');
}

    public function pemeliharaan_action()
    {
        $this->form_validation->set_rules('id_inven', 'Inventaris', 'trim|required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
        $this->form_validation->set_rules('biaya', 'Biaya', 'trim|required|numeric');
        $this->form_validation->set_rules('petugas', 'Petugas', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->pemeliharaan_form($this->input->post('id_pemeliharaan'));
        } else {
            $data = array(
                'id_inven' => $this->input->post('id_inven', TRUE),
                'tanggal' => $this->input->post('tanggal', TRUE),
                'keterangan' => $this->input->post('keterangan', TRUE),
                'biaya' => $this->input->post('biaya', TRUE),
                'petugas' => $this->input->post('petugas', TRUE),
                'status' => $this->input->post('status', TRUE),
            );

            $id_pemeliharaan = $this->input->post('id_pemeliharaan', TRUE);
            
            if ($id_pemeliharaan == '') {
                $this->Pemeliharaan_model->insert($data);
                $this->session->set_flashdata('message', 'Data Pemeliharaan Berhasil Disimpan');
            } else {
                $this->Pemeliharaan_model->update($id_pemeliharaan, $data);
                $this->session->set_flashdata('message', 'Data Pemeliharaan Berhasil Diupdate');
            }
            
            redirect(site_url('data_inventaris/pemeliharaan'));
        }
    }

    public function pemeliharaan_delete($id)
    {
        $row = $this->Pemeliharaan_model->get_by_id($id);

        if ($row) {
            $this->Pemeliharaan_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('data_inventaris/pemeliharaan'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('data_inventaris/pemeliharaan'));
        }
    }

    // ==================== BARCODE METHODS ====================

    public function print_barcode($id)
    {
        $row = $this->Data_inventaris_model->get_by_id($id);
        if ($row) {
            $data = array(
                'inventaris' => $row,
            );
            $this->load->view('data_inventaris/print_barcode', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('data_inventaris'));
        }
    }

    public function print_barcode_local($id)
{
    $row = $this->Data_inventaris_model->get_by_id($id);
    if ($row) {
        $data = array(
            'inventaris' => $row,
        );
        $this->load->view('data_inventaris/print_barcode_local', $data);
    } else {
        $this->session->set_flashdata('message', 'Record Not Found');
        redirect(site_url('data_inventaris'));
    }
}

    public function print_all_barcodes()
    {
        $items = $this->Data_inventaris_model->get_all();
        $data = array(
            'items' => $items,
        );
        $this->load->view('data_inventaris/print_all_barcodes', $data);
    }

    public function _rules()
    {
        $this->form_validation->set_rules('kode_inven', 'kode inven', 'trim|required');
        $this->form_validation->set_rules('nm_barang', 'nm barang', 'trim|required');
        $this->form_validation->set_rules('merek', 'merek', 'trim|required');
        $this->form_validation->set_rules('tipe', 'tipe', 'trim|required');
        $this->form_validation->set_rules('sn', 'sn', 'trim|required');
        $this->form_validation->set_rules('jenis', 'jenis', 'trim|required');
        $this->form_validation->set_rules('kondisi', 'kondisi', 'trim|required');
        $this->form_validation->set_rules('id_ruang', 'id ruang', 'trim|required');
        $this->form_validation->set_rules('harga', 'harga', 'trim|required|numeric');
        $this->form_validation->set_rules('stts', 'stts', 'trim|required');

        $this->form_validation->set_rules('id_inven', 'id_inven', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    // ==================== MAINTENANCE ASSET ====================

public function maintenance_dashboard()
{
    $this->load->model('Maintenance_asset_model');
    $stats = $this->Maintenance_asset_model->get_dashboard_stats();
    
    $data = array(
        'stats' => $stats,
        'page_title' => 'Dashboard Maintenance Asset',
    );
    
    $this->load->view('template/header', $data);
    $this->load->view('data_inventaris/maintenance_dashboard', $data);
    $this->load->view('template/footer');
}

// ==================== SCHEDULE ====================

public function maintenance_schedule()
{
    $this->load->model('Maintenance_asset_model');
    
    $q = urldecode($this->input->get('q', TRUE));
    $start = intval($this->input->get('start'));
    $status = $this->input->get('status');
    
    $config['per_page'] = 10;
    $config['page_query_string'] = TRUE;
    $config['total_rows'] = $this->Maintenance_asset_model->total_rows_schedule($q);
    $data_schedule = $this->Maintenance_asset_model->get_all_schedule($config['per_page'], $start, $q);
    
    $this->load->library('pagination');
    $config['base_url'] = base_url() . 'index.php/data_inventaris/maintenance_schedule';
    if ($q <> '') {
        $config['base_url'] = base_url() . 'index.php/data_inventaris/maintenance_schedule?q=' . urlencode($q);
        $config['first_url'] = base_url() . 'index.php/data_inventaris/maintenance_schedule?q=' . urlencode($q);
    } else {
        $config['first_url'] = base_url() . 'index.php/data_inventaris/maintenance_schedule';
    }
    $this->pagination->initialize($config);
    
    $data = array(
        'data_schedule' => $data_schedule,
        'q' => $q,
        'pagination' => $this->pagination->create_links(),
        'total_rows' => $config['total_rows'],
        'start' => $start,
        'status_filter' => $status,
    );
    
    $this->load->view('template/header', $data);
    $this->load->view('data_inventaris/maintenance_schedule_list', $data);
    $this->load->view('template/footer');
}

public function maintenance_schedule_form($id = null)
{
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $this->load->model('Maintenance_asset_model');
    
    // Get data for dropdowns
    $inventaris_list = $this->Data_inventaris_model->get_all();
    $jenis_list = $this->Maintenance_asset_model->get_all_jenis();
    
    // Check if data exists
    if (!$inventaris_list) {
        $inventaris_list = array();
    }
    if (!$jenis_list) {
        $jenis_list = array();
    }
    
    // Get inventory id from GET parameter
    $inven_id = $this->input->get('inven_id');
    
    if ($id == null) {
        // Create new
        $data = array(
            'button' => 'Tambah',
            'action' => site_url('data_inventaris/maintenance_schedule_action'),
            'id_schedule' => '',
            'id_inven' => $inven_id ?? '',
            'id_jenis' => '',
            'judul' => '',
            'deskripsi' => '',
            'tanggal_mulai' => date('Y-m-d'),
            'tanggal_selesai' => date('Y-m-d', strtotime('+7 days')),
            'prioritas' => 'Sedang',
            'status' => 'Scheduled',
            'petugas' => '',
            'estimasi_biaya' => '',
            'inventaris_list' => $inventaris_list,
            'jenis_list' => $jenis_list,
        );
    } else {
        // Edit existing
        $row = $this->Maintenance_asset_model->get_schedule_by_id($id);
        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('data_inventaris/maintenance_schedule_action'),
                'id_schedule' => $row->id_schedule,
                'id_inven' => $row->id_inven,
                'id_jenis' => $row->id_jenis,
                'judul' => $row->judul,
                'deskripsi' => $row->deskripsi,
                'tanggal_mulai' => $row->tanggal_mulai,
                'tanggal_selesai' => $row->tanggal_selesai,
                'prioritas' => $row->prioritas,
                'status' => $row->status,
                'petugas' => $row->petugas,
                'estimasi_biaya' => $row->estimasi_biaya,
                'inventaris_list' => $inventaris_list,
                'jenis_list' => $jenis_list,
            );
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('data_inventaris/maintenance_schedule'));
            return;
        }
    }
    
    // Load views
    $this->load->view('template/header', $data);
    $this->load->view('data_inventaris/maintenance_schedule_form', $data);
    $this->load->view('template/footer');
}

public function maintenance_schedule_action()
{
    $this->load->model('Maintenance_asset_model');
    
    $this->form_validation->set_rules('id_inven', 'Inventaris', 'trim|required');
    $this->form_validation->set_rules('id_jenis', 'Jenis Pemeliharaan', 'trim|required');
    $this->form_validation->set_rules('judul', 'Judul', 'trim|required');
    $this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'trim|required');
    $this->form_validation->set_rules('prioritas', 'Prioritas', 'trim|required');
    $this->form_validation->set_rules('status', 'Status', 'trim|required');
    
    if ($this->form_validation->run() == FALSE) {
        $this->maintenance_schedule_form($this->input->post('id_schedule'));
    } else {
        $data = array(
            'id_inven' => $this->input->post('id_inven', TRUE),
            'id_jenis' => $this->input->post('id_jenis', TRUE),
            'judul' => $this->input->post('judul', TRUE),
            'deskripsi' => $this->input->post('deskripsi', TRUE),
            'tanggal_mulai' => $this->input->post('tanggal_mulai', TRUE),
            'tanggal_selesai' => $this->input->post('tanggal_selesai', TRUE),
            'prioritas' => $this->input->post('prioritas', TRUE),
            'status' => $this->input->post('status', TRUE),
            'petugas' => $this->input->post('petugas', TRUE),
            'estimasi_biaya' => $this->input->post('estimasi_biaya', TRUE) ?? 0,
        );
        
        $id_schedule = $this->input->post('id_schedule', TRUE);
        
        if ($id_schedule == '') {
            $this->Maintenance_asset_model->insert_schedule($data);
            $this->session->set_flashdata('message', 'Jadwal Pemeliharaan Berhasil Disimpan');
        } else {
            $this->Maintenance_asset_model->update_schedule($id_schedule, $data);
            $this->session->set_flashdata('message', 'Jadwal Pemeliharaan Berhasil Diupdate');
        }
        
        redirect(site_url('data_inventaris/maintenance_schedule'));
    }
}

public function maintenance_schedule_delete($id)
{
    $this->load->model('Maintenance_asset_model');
    $row = $this->Maintenance_asset_model->get_schedule_by_id($id);
    
    if ($row) {
        $this->Maintenance_asset_model->delete_schedule($id);
        $this->session->set_flashdata('message', 'Delete Record Success');
        redirect(site_url('data_inventaris/maintenance_schedule'));
    } else {
        $this->session->set_flashdata('message', 'Record Not Found');
        redirect(site_url('data_inventaris/maintenance_schedule'));
    }
}

// ==================== HISTORY ====================

public function maintenance_history()
{
    $this->load->model('Maintenance_asset_model');
    
    $q = urldecode($this->input->get('q', TRUE));
    $start = intval($this->input->get('start'));
    
    $config['per_page'] = 10;
    $config['page_query_string'] = TRUE;
    $config['total_rows'] = $this->Maintenance_asset_model->total_rows_history($q);
    $data_history = $this->Maintenance_asset_model->get_all_history($config['per_page'], $start, $q);
    
    $this->load->library('pagination');
    $config['base_url'] = base_url() . 'index.php/data_inventaris/maintenance_history';
    if ($q <> '') {
        $config['base_url'] = base_url() . 'index.php/data_inventaris/maintenance_history?q=' . urlencode($q);
        $config['first_url'] = base_url() . 'index.php/data_inventaris/maintenance_history?q=' . urlencode($q);
    } else {
        $config['first_url'] = base_url() . 'index.php/data_inventaris/maintenance_history';
    }
    $this->pagination->initialize($config);
    
    $data = array(
        'data_history' => $data_history,
        'q' => $q,
        'pagination' => $this->pagination->create_links(),
        'total_rows' => $config['total_rows'],
        'start' => $start,
    );
    
    $this->load->view('template/header', $data);
    $this->load->view('data_inventaris/maintenance_history_list', $data);
    $this->load->view('template/footer');
}

public function maintenance_history_form($id = null)
{
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $this->load->model('Maintenance_asset_model');
    
    // Get data for dropdowns
    $inventaris_list = $this->Data_inventaris_model->get_all();
    $jenis_list = $this->Maintenance_asset_model->get_all_jenis();
    $schedule_list = $this->Maintenance_asset_model->get_all_schedule();
    
    // Check if data exists
    if (!$inventaris_list) {
        $inventaris_list = array();
    }
    if (!$jenis_list) {
        $jenis_list = array();
    }
    if (!$schedule_list) {
        $schedule_list = array();
    }
    
    // Get inventory id from GET parameter
    $inven_id = $this->input->get('inven_id');
    
    if ($id == null) {
        // Create new
        $data = array(
            'button' => 'Tambah',
            'action' => site_url('data_inventaris/maintenance_history_action'),
            'id_history' => '',
            'id_inven' => $inven_id ?? '',
            'id_schedule' => '',
            'id_jenis' => '',
            'tanggal' => date('Y-m-d'),
            'keterangan' => '',
            'biaya' => '',
            'petugas' => '',
            'status' => 'Proses',
            'durasi_jam' => '',
            'catatan' => '',
            'sparepart' => array(),
            'inventaris_list' => $inventaris_list,
            'jenis_list' => $jenis_list,
            'schedule_list' => $schedule_list,
        );
    } else {
        // Edit existing
        $row = $this->Maintenance_asset_model->get_history_by_id($id);
        if ($row) {
            $sparepart = $this->Maintenance_asset_model->get_sparepart_by_history($id);
            $data = array(
                'button' => 'Update',
                'action' => site_url('data_inventaris/maintenance_history_action'),
                'id_history' => $row->id_history,
                'id_inven' => $row->id_inven,
                'id_schedule' => $row->id_schedule,
                'id_jenis' => $row->id_jenis,
                'tanggal' => $row->tanggal,
                'keterangan' => $row->keterangan,
                'biaya' => $row->biaya,
                'petugas' => $row->petugas,
                'status' => $row->status,
                'durasi_jam' => $row->durasi_jam,
                'catatan' => $row->catatan,
                'sparepart' => $sparepart,
                'inventaris_list' => $inventaris_list,
                'jenis_list' => $jenis_list,
                'schedule_list' => $schedule_list,
            );
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('data_inventaris/maintenance_history'));
            return;
        }
    }
    
    // Load views
    $this->load->view('template/header', $data);
    $this->load->view('data_inventaris/maintenance_history_form', $data);
    $this->load->view('template/footer');
}

public function maintenance_history_action()
{
    $this->load->model('Maintenance_asset_model');
    
    $this->form_validation->set_rules('id_inven', 'Inventaris', 'trim|required');
    $this->form_validation->set_rules('id_jenis', 'Jenis Pemeliharaan', 'trim|required');
    $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
    $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
    $this->form_validation->set_rules('petugas', 'Petugas', 'trim|required');
    $this->form_validation->set_rules('status', 'Status', 'trim|required');
    
    if ($this->form_validation->run() == FALSE) {
        $this->maintenance_history_form($this->input->post('id_history'));
    } else {
        $data = array(
            'id_inven' => $this->input->post('id_inven', TRUE),
            'id_schedule' => $this->input->post('id_schedule', TRUE),
            'id_jenis' => $this->input->post('id_jenis', TRUE),
            'tanggal' => $this->input->post('tanggal', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE),
            'biaya' => $this->input->post('biaya', TRUE) ?? 0,
            'petugas' => $this->input->post('petugas', TRUE),
            'status' => $this->input->post('status', TRUE),
            'durasi_jam' => $this->input->post('durasi_jam', TRUE) ?? 0,
            'catatan' => $this->input->post('catatan', TRUE),
        );
        
        $id_history = $this->input->post('id_history', TRUE);
        
        if ($id_history == '') {
            $new_id = $this->Maintenance_asset_model->insert_history($data);
            $this->session->set_flashdata('message', 'Riwayat Pemeliharaan Berhasil Disimpan');
        } else {
            $this->Maintenance_asset_model->update_history($id_history, $data);
            $this->session->set_flashdata('message', 'Riwayat Pemeliharaan Berhasil Diupdate');
        }
        
        redirect(site_url('data_inventaris/maintenance_history'));
    }
}

public function maintenance_history_delete($id)
{
    $this->load->model('Maintenance_asset_model');
    $row = $this->Maintenance_asset_model->get_history_by_id($id);
    
    if ($row) {
        $this->Maintenance_asset_model->delete_history($id);
        $this->session->set_flashdata('message', 'Delete Record Success');
        redirect(site_url('data_inventaris/maintenance_history'));
    } else {
        $this->session->set_flashdata('message', 'Record Not Found');
        redirect(site_url('data_inventaris/maintenance_history'));
    }
}

// ==================== SPAREPART ====================

public function sparepart_add()
{
    $this->load->model('Maintenance_asset_model');
    $id_history = $this->input->post('id_history');
    $nama_part = $this->input->post('nama_part');
    $qty = $this->input->post('qty');
    $harga = $this->input->post('harga_satuan');
    $keterangan = $this->input->post('keterangan');
    
    if ($id_history && $nama_part) {
        $data = array(
            'id_history' => $id_history,
            'nama_part' => $nama_part,
            'qty' => $qty ?? 1,
            'harga_satuan' => $harga ?? 0,
            'total_harga' => ($qty ?? 1) * ($harga ?? 0),
            'keterangan' => $keterangan,
        );
        $this->Maintenance_asset_model->insert_sparepart($data);
        echo json_encode(array('status' => 'success'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Data tidak lengkap'));
    }
}

public function sparepart_delete($id)
{
    $this->load->model('Maintenance_asset_model');
    $this->Maintenance_asset_model->delete_sparepart($id);
    echo json_encode(array('status' => 'success'));
}
// ==================== TRACKING MAINTENANCE ====================

public function get_maintenance_tracking($id)
{
    $this->load->model('Maintenance_asset_model');
    $this->load->model('Pemeliharaan_model'); // Load model pemeliharaan cepat
    
    // Get inventory data
    $inventaris = $this->Data_inventaris_model->get_by_id($id);
    
    if (!$inventaris) {
        echo json_encode(array('status' => 'error', 'message' => 'Data tidak ditemukan'));
        return;
    }
    
    // Get maintenance history from tr_maintenance_history
    $history = $this->Maintenance_asset_model->get_history_by_inventory($id);
    
    // Get quick maintenance from data_pemeliharaan
    $quick_maintenance = $this->Pemeliharaan_model->get_by_inventory($id);
    
    // Get schedules
    $schedules = $this->Maintenance_asset_model->get_all_schedule(null, 0, null);
    $schedules_filtered = array();
    foreach ($schedules as $s) {
        if ($s->id_inven == $id) {
            $schedules_filtered[] = $s;
        }
    }
    
    // Combine all maintenance data for statistics
    $all_maintenance = array_merge($history, $quick_maintenance);
    $total_maintenance = count($all_maintenance);
    $total_cost = 0;
    $status_counts = array('Proses' => 0, 'Selesai' => 0, 'Batal' => 0);
    $last_maintenance = null;
    
    // Process history
    foreach ($history as $h) {
        $total_cost += floatval($h->biaya);
        if (isset($status_counts[$h->status])) {
            $status_counts[$h->status]++;
        }
        if (!$last_maintenance || strtotime($h->tanggal) > strtotime($last_maintenance->tanggal)) {
            $last_maintenance = $h;
        }
    }
    
    // Process quick maintenance
    foreach ($quick_maintenance as $qm) {
        $total_cost += floatval($qm->biaya);
        $status = $qm->status ?? 'Proses';
        if (isset($status_counts[$status])) {
            $status_counts[$status]++;
        } else {
            $status_counts[$status] = 1;
        }
        if (!$last_maintenance || strtotime($qm->tanggal) > strtotime($last_maintenance->tanggal)) {
            $last_maintenance = $qm;
        }
    }
    
    // Get upcoming schedules
    $upcoming_schedules = array();
    $today = date('Y-m-d');
    foreach ($schedules_filtered as $s) {
        if ($s->tanggal_mulai >= $today && $s->status != 'Completed' && $s->status != 'Cancelled') {
            $upcoming_schedules[] = $s;
        }
    }
    
    // Sort history by date
    usort($history, function($a, $b) {
        return strtotime($b->tanggal) - strtotime($a->tanggal);
    });
    
    // Sort quick maintenance by date
    usort($quick_maintenance, function($a, $b) {
        return strtotime($b->tanggal) - strtotime($a->tanggal);
    });
    
    // Sort schedules by date
    usort($schedules_filtered, function($a, $b) {
        return strtotime($a->tanggal_mulai) - strtotime($b->tanggal_mulai);
    });
    
    $data = array(
        'status' => 'success',
        'inventaris' => array(
            'id_inven' => $inventaris->id_inven,
            'kode_inven' => $inventaris->kode_inven,
            'nm_barang' => $inventaris->nm_barang,
            'merek' => $inventaris->merek,
            'tipe' => $inventaris->tipe,
            'sn' => $inventaris->sn,
            'jenis' => $inventaris->jenis,
            'kondisi' => $inventaris->kondisi,
            'id_ruang' => $inventaris->id_ruang,
            'harga' => $inventaris->harga,
            'stts' => $inventaris->stts,
        ),
        'stats' => array(
            'total_maintenance' => $total_maintenance,
            'total_cost' => $total_cost,
            'status_counts' => $status_counts,
            'last_maintenance' => $last_maintenance,
            'upcoming_count' => count($upcoming_schedules),
        ),
        'history' => $history,
        'quick_maintenance' => $quick_maintenance,
        'schedules' => $schedules_filtered,
        'upcoming_schedules' => $upcoming_schedules,
    );
    
    // Set header JSON
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($data));
}

public function maintenance_tracking()
{
    $id = $this->input->get('id');
    if (!$id) {
        show_404();
    }
    
    $this->load->model('Maintenance_asset_model');
    
    $inventaris = $this->Data_inventaris_model->get_by_id($id);
    if (!$inventaris) {
        show_404();
    }
    
    $history = $this->Maintenance_asset_model->get_history_by_inventory($id);
    $schedules = $this->Maintenance_asset_model->get_all_schedule(null, 0, null);
    $schedules_filtered = array();
    foreach ($schedules as $s) {
        if ($s->id_inven == $id) {
            $schedules_filtered[] = $s;
        }
    }
    
    $data = array(
        'inventaris' => $inventaris,
        'history' => $history,
        'schedules' => $schedules_filtered,
    );
    
    $this->load->view('data_inventaris/maintenance_tracking_modal', $data);
}

}