<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notifikasi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Notifikasi_model');
    }

    public function index()
    {
        $uid = (int) $this->session->userdata('id');
        $this->Notifikasi_model->mark_read($uid);

        $data = array(
            'title'       => 'Notifikasi',
            'notifikasi'  => $this->Notifikasi_model->list_all($uid),
        );

        $this->load->view('template/header', $data);
        $this->load->view('notifikasi/index', $data);
        $this->load->view('template/footer');
    }

    public function baca($id_notif = 0)
    {
        $uid = (int) $this->session->userdata('id');
        $row = $this->db->where('id_notif', (int)$id_notif)
            ->where('id_user', $uid)
            ->get('notifikasi')->row();
        if (!$row) {
            redirect('notifikasi');
        }
        // tandai dibaca lalu ke halaman tujuan
        $this->db->where('id_notif', (int)$id_notif)->update('notifikasi', array('is_read' => 1));
        redirect($row->url ? $row->url : 'notifikasi');
    }
}
/* End of file Notifikasi.php */