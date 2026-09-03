<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Portal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Portal Sistem RS Airlangga';
        $this->load->view('template/header', $data);
        $this->load->view('portal/index', $data);
        $this->load->view('template/footer');
    }
}
