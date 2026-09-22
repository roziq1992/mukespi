<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mutu_indikator extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Mutu_indikator_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $idindikator = urldecode($this->input->get('id', TRUE));
        $judul = urldecode($this->input->get('judul', TRUE));

        if ($idindikator === '' || $idindikator === '0' || $idindikator === NULL) {
            $this->session->set_flashdata('message', 'Pilih indikator terlebih dahulu.');
            redirect(site_url('list_indikator'));
            return;
        }

        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'index.php/mutu_indikator/?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
            $config['first_url'] = base_url() . 'index.php/mutu_indikator/?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
        } else {
            $config['base_url'] = base_url() . 'index.php/mutu_indikator/?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
            $config['first_url'] = base_url() . 'index.php/mutu_indikator/?q=' . urlencode($q).'&id='.urlencode($idindikator).'&judul='.urlencode($judul);
        }

        $config['per_page'] = 31;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Mutu_indikator_model->total_rows($q,$idindikator);
        $mutu_indikator = $this->Mutu_indikator_model->get_limit_data($config['per_page'], $start, $q, $idindikator);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'mutu_indikator_data' => $mutu_indikator,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
             'button' => 'Save',
            'action' => site_url('mutu_indikator/create_action'),
    	    'id_mutu' => set_value('id_mutu'),
    	    'tanggal' => set_value('tanggal'),
    	    'id_indikator' => set_value('id_indikator'),
    	    'num' => set_value('num'),
    	    'demu' => set_value('demu'),
            'validasi' => $idindikator ? $this->Mutu_indikator_model->get_validasi($idindikator) : array(),
            'users' => $this->Mutu_indikator_model->users(),
        );
   
        
        $this->load->view('template/header',$data);
        $this->load->view('mutu_indikator/mutu_indikator_list');
        $this->load->view('template/footer');
    }

    public function read($id) 
    {
        $row = $this->Mutu_indikator_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_mutu' => $row->id_mutu,
		'tanggal' => $row->tanggal,
		'id_indikator' => $row->id_indikator,
		'num' => $row->num,
		'demu' => $row->demu,
	    );
            $this->load->view('mutu_indikator/mutu_indikator_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_indikator'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('mutu_indikator/create_action'),
	    'id_mutu' => set_value('id_mutu'),
	    'tanggal' => set_value('tanggal'),
	    'id_indikator' => set_value('id_indikator'),
	    'num' => set_value('num'),
	    'demu' => set_value('demu'),
	);
        $this->load->view('mutu_indikator/mutu_indikator_form', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'tanggal' => $this->input->post('tanggal',TRUE),
		'id_indikator' => $this->input->post('id_indikator',TRUE),
		'num' => $this->input->post('num',TRUE),
		'demu' => $this->input->post('demu',TRUE),
		'target' => $this->input->post('target',TRUE),
	    );

            $this->Mutu_indikator_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('mutu_indikator?id='.$this->input->post('id_indikator',TRUE).'&judul='.$this->input->post('judul',TRUE).'&tanggal='.$this->input->post('tanggal',TRUE)));
        }
    }
    
    public function update() 
    {
        $id=$this->input->get('idmutu',TRUE);
        $row = $this->Mutu_indikator_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('mutu_indikator/update_action'),
		'id_mutu' => set_value('id_mutu', $row->id_mutu),
		'tanggal' => set_value('tanggal', $row->tanggal),
		'id_indikator' => set_value('id_indikator', $row->id_indikator),
		'num' => set_value('num', $row->num),
		'demu' => set_value('demu', $row->demu),
	    );
	     $this->load->view('template/header',$data);
         $this->load->view('mutu_indikator/mutu_indikator_form', $data);
         $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_indikator'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_mutu', TRUE));
        } else {
            $data = array(
		'tanggal' => $this->input->post('tanggal',TRUE),
		'id_indikator' => $this->input->post('id_indikator',TRUE),
		'num' => $this->input->post('num',TRUE),
		'demu' => $this->input->post('demu',TRUE),
	    );

            $this->Mutu_indikator_model->update($this->input->post('id_mutu', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('mutu_indikator?id='.$this->input->post('id_indikator',TRUE).'&judul='.$this->input->post('judul',TRUE).'&tanggal='.$this->input->post('tanggal',TRUE)));
        }
    }
    
    public function delete() 
    {
         $id=$this->input->get('idmutu',TRUE);
        $row = $this->Mutu_indikator_model->get_by_id($id);

        if ($row) {
            $this->Mutu_indikator_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('mutu_indikator?id='.$this->input->get('id',TRUE).'&judul='.$this->input->get('judul',TRUE).'&tanggal='.$this->input->get('tanggal',TRUE)));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_indikator?id='.$this->input->get('id',TRUE).'&judul='.$this->input->get('judul',TRUE).'&tanggal='.$this->input->get('tanggal',TRUE)));
        }
    }

    // ================= VALIDASI =================

    // simpan hasil validasi dari modal
    public function validasi_action()
    {
        $id_indikator = (int) $this->input->post('id_indikator', TRUE);
        $judul = $this->input->post('judul', TRUE);

        $this->form_validation->set_rules('id_indikator', 'Indikator', 'trim|required|integer');
        $this->form_validation->set_rules('tanggal_awal', 'Tanggal Awal', 'trim|required');
        $this->form_validation->set_rules('tanggal_akhir', 'Tanggal Akhir', 'trim|required');
        $this->form_validation->set_rules('num', 'Numerator', 'trim|required|numeric');
        $this->form_validation->set_rules('demu', 'Denumerator', 'trim|required|numeric');
        $this->form_validation->set_rules('userid', 'Validator', 'trim|required|integer');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', 'Form validasi belum lengkap. Periksa kembali isian Anda.');
        } else {
            $this->Mutu_indikator_model->insert_validasi(array(
                'id_indikator' => $id_indikator,
                'tanggal_awal' => $this->input->post('tanggal_awal', TRUE),
                'tanggal_akhir' => $this->input->post('tanggal_akhir', TRUE),
                'num' => (float) $this->input->post('num', TRUE),
                'demu' => (float) $this->input->post('demu', TRUE),
                'userid' => (int) $this->input->post('userid', TRUE),
                'created_at' => date('Y-m-d H:i:s'),
            ));
            $this->session->set_flashdata('message', 'Validasi berhasil disimpan.');
        }
        redirect(site_url('mutu_indikator?id=' . $id_indikator . '&judul=' . urlencode($judul)));
    }

    // hapus catatan validasi
    public function delete_validasi()
    {
        $id_validasi = (int) $this->input->get('idvalidasi', TRUE);
        $id_indikator = (int) $this->input->get('id', TRUE);
        $judul = $this->input->get('judul', TRUE);

        $row = $this->Mutu_indikator_model->get_validasi_by_id($id_validasi);
        if ($row) {
            $this->Mutu_indikator_model->delete_validasi($id_validasi);
            $this->session->set_flashdata('message', 'Catatan validasi berhasil dihapus.');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
        }
        redirect(site_url('mutu_indikator?id=' . $id_indikator . '&judul=' . urlencode($judul)));
    }

    // lihat detail hasil validasi + data mutu pada periode tsb
    public function validasi_detail()
    {
        $id_validasi = (int) $this->input->get('idvalidasi', TRUE);
        $id_indikator = (int) $this->input->get('id', TRUE);
        $judul = $this->input->get('judul', TRUE);

        $validasi = $this->Mutu_indikator_model->get_validasi_by_id($id_validasi);

        if (!$validasi) {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('mutu_indikator?id=' . $id_indikator . '&judul=' . urlencode($judul)));
            return;
        }

        $indikator = $this->db->get_where('list_indikator', array('id_indikator' => $validasi->id_indikator))->row();
        $validator = $this->db->select('id, name')->where('id', $validasi->userid)->get('users')->row();

        $data = array(
            'title' => 'Detail Validasi',
            'validasi' => $validasi,
            'indikator' => $indikator,
            'validator' => $validator,
            'mutu_data' => $this->Mutu_indikator_model->get_in_range($validasi->id_indikator, $validasi->tanggal_awal, $validasi->tanggal_akhir),
            'target' => $indikator ? $indikator->target : NULL,
            'jenis' => $indikator ? $indikator->jenis : '',
            'judul' => $indikator ? $indikator->judul : $judul,
        );

        $this->load->view('template/header', $data);
        $this->load->view('mutu_indikator/validasi_detail', $data);
        $this->load->view('template/footer');
    }

    // total num & denum dalam rentang tanggal (AJAX untuk isi otomatis modal validasi)
    public function sum_range()
    {
        $id_indikator = (int) $this->input->post('id_indikator', TRUE);
        $awal = $this->input->post('tanggal_awal', TRUE);
        $akhir = $this->input->post('tanggal_akhir', TRUE);
        header('Content-Type: application/json');
        echo json_encode($this->Mutu_indikator_model->sum_range($id_indikator, $awal, $akhir));
        exit;
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');
	$this->form_validation->set_rules('id_indikator', 'id indikator', 'trim|required');
	$this->form_validation->set_rules('num', 'num', 'trim|required|numeric');
	$this->form_validation->set_rules('demu', 'demu', 'trim|required|numeric');

	$this->form_validation->set_rules('id_mutu', 'id_mutu', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "mutu_indikator.xls";
        $judul = "mutu_indikator";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");
	xlsWriteLabel($tablehead, $kolomhead++, "Id Indikator");
	xlsWriteLabel($tablehead, $kolomhead++, "Num");
	xlsWriteLabel($tablehead, $kolomhead++, "Demu");

	foreach ($this->Mutu_indikator_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);
	    xlsWriteNumber($tablebody, $kolombody++, $data->id_indikator);
	    xlsWriteNumber($tablebody, $kolombody++, $data->num);
	    xlsWriteNumber($tablebody, $kolombody++, $data->demu);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Mutu_indikator.php */
/* Location: ./application/controllers/Mutu_indikator.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2023-01-07 02:04:16 */
/* http://harviacode.com */