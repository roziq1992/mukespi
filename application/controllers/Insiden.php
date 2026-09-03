<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class insiden extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Ikp_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'ikp/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'ikp/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'ikp/index.html';
            $config['first_url'] = base_url() . 'ikp/index.html';
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
            $this->load->view('ikp/ikp_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('ikp'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Save',
            'action' => site_url('insiden/create_action'),
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
    
    public function update($id) 
    {
        $row = $this->Ikp_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('ikp/update_action'),
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
	$this->form_validation->set_rules('ket_kejadian_terulang', 'ket kejadian terulang', 'trim|required');
	$this->form_validation->set_rules('pelapor', 'pelapor', 'trim|required');
	$this->form_validation->set_rules('penerima', 'penerima', 'trim|required');
	$this->form_validation->set_rules('tgl_lapor', 'tgl lapor', 'trim|required');
	$this->form_validation->set_rules('grading_resiko', 'grading resiko', 'trim|required');

	$this->form_validation->set_rules('id_ikp', 'id_ikp', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
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