<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        is_logged_in();
         $this->load->model('Cuci_tangan_model');
    }

	public function index()
	{
		$data['title'] = "Dashboard";
		$data['konten'] = "dashboard";
		$this->load->view('template/header', $data);
		$this->load->view('template/Dasboard');
		$this->load->view('template/footercuci');
	}
	public function mutugrafik()
	{
		$this->load->model('Mutu_indikator_model');

		$id_indikator = (int) $this->input->get('id');
		$tanggal_awal = trim((string) $this->input->get('tanggal'));
		$tanggal_akhir = trim((string) $this->input->get('tanggal2'));

		if ($tanggal_awal === '') {
			$tanggal_awal = date('Y-m-d');
		}
		if ($tanggal_akhir === '') {
			$tanggal_akhir = $tanggal_awal;
		}

		// Data grafik diambil satu kali di controller lalu dipakai oleh kedua view,
		// supaya kartu ringkasan dan grafik memakai sumber data yang sama.
		$mutuindikator = ($id_indikator > 0)
			? $this->Mutu_indikator_model->grafik_bulanan($id_indikator, $tanggal_awal, $tanggal_akhir)
			: array();

		$tnum = 0;
		$tdenum = 0;
		$rcapaian = 0;
		$no = 0;
		foreach ($mutuindikator as $row) {
			$tnum += $row->num;
			$tdenum += $row->demu;
			$rcapaian += $row->capaian;
			$no++;
		}
		$rata2 = ($no > 0 && $rcapaian > 0) ? $rcapaian / $no : 0;

		$data = array(
			'title'        => "Dashboard",
			'konten'       => "dashboard",
			'mutuindikator' => $mutuindikator,
			'tanggal_awal' => $tanggal_awal,
			'tanggal_akhir' => $tanggal_akhir,
			'tnum'         => $tnum,
			'tdenum'       => $tdenum,
			'rata2'        => $rata2,
		);

		$this->load->view('template/header', $data);
		$this->load->view('template/grafikmutu', $data);
		$this->load->view('template/dtgrafikmutu', $data);
	}
	public function allmutugrafik()
	{
		$data['title'] = "Dashboard";
		$data['konten'] = "dashboard";
		$data = array(	
		 'mutu' => $this->Cuci_tangan_model->mutu() 
		 );
	
		$this->load->view('template/header', $data);
		$this->load->view('template/grafikmutuall');
		$this->load->view('template/dtgrafikmutuall');
	}
		public function allmutugrafik2()
	{
		$data['title'] = "Dashboard";
		$data['konten'] = "dashboard";
		$data = array(	
		 'mutu' => $this->Cuci_tangan_model->mutu() 
		 );
	
		$this->load->view('template/header', $data);
		$this->load->view('template/grafikmutuall2');
		$this->load->view('template/dtgrafikmutuall2');
	}
}
