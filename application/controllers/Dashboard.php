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
		$data['title'] = "Dashboard";
		$data['konten'] = "dashboard";
		$this->load->view('template/header', $data);
		$this->load->view('template/grafikmutu');
		$this->load->view('template/dtgrafikmutu');
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
