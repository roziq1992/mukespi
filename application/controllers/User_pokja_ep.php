<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller User_pokja_ep
 * -------------------------------------------------------------
 * Halaman admin untuk mengatur user mana saja yang boleh menilai EP
 * pada setiap Pokja di modul Penilaian EP (SIPARDI).
 *
 *Dua level akses per (user, pokja):
 *   Penilai  -> boleh isi skor, upload bukti, hapus bukti, download dokumen
 *   Lihat    -> hanya boleh membuka dokumen di browser (tanpa download,
 *               tanpa menilai, tanpa upload/hapus)
 *
 * User yang tidak di-set sama sekali tetap bisa membuka penilaian_ep dan
 * melihat dokumen (read-only), supaya semua orang punya gambaran progres.
 *
 * Admin (role_id = 1) dan Surveior (role_id = 3) tidak dibatasi oleh tabel ini.
 */
class User_pokja_ep extends CI_Controller
{
    private $ROLE_ID_ADMIN = 1;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('User_pokja_ep_model');

        if ((int) $this->session->userdata('role_id') != $this->ROLE_ID_ADMIN) {
            $this->session->set_flashdata('message', 'Anda tidak punya akses ke halaman ini');
            redirect(site_url('penilaian_ep'));
        }
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        $config['base_url'] = ($q <> '')
            ? base_url() . 'index.php/user_pokja_ep/?q=' . urlencode($q)
            : base_url() . 'index.php/user_pokja_ep/';
        $config['first_url'] = $config['base_url'];
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->User_pokja_ep_model->count_all_users($q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $users = $this->User_pokja_ep_model->get_all_users($q);
        $users_data = array();
        foreach ($users as $u) {
            $u->pokja = $this->User_pokja_ep_model->get_pokja_by_user($u->id);
            $users_data[] = $u;
        }

        $data = array(
            'users_data' => $users_data,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $this->load->view('template/header', $data);
        $this->load->view('user_pokja_ep/user_pokja_ep_list');
        $this->load->view('template/footer');
    }

    public function manage($id_user)
    {
        $user = $this->User_pokja_ep_model->get_user_by_id($id_user);
        if (!$user) {
            $this->session->set_flashdata('message', 'User tidak ditemukan');
            redirect(site_url('user_pokja_ep'));
            return;
        }

        $assigned = array();
        $penilai  = array();
        foreach ($this->User_pokja_ep_model->get_pokja_by_user($id_user) as $p) {
            $assigned[] = intval($p->id);
            if (intval($p->is_penilai) === 1) {
                $penilai[] = intval($p->id);
            }
        }

        $data = array(
            'user'       => $user,
            'pokja_list' => $this->User_pokja_ep_model->get_all_pokja(),
            'assigned'   => $assigned,
            'penilai'    => $penilai,
        );

        $this->load->view('template/header', $data);
        $this->load->view('user_pokja_ep/user_pokja_ep_form');
        $this->load->view('template/footer');
    }

    public function manage_action()
    {
        $id_user = intval($this->input->post('id_user', TRUE));

        $user = $this->User_pokja_ep_model->get_user_by_id($id_user);
        if (!$user) {
            $this->session->set_flashdata('message', 'User tidak ditemukan');
            redirect(site_url('user_pokja_ep'));
            return;
        }

        $pokja_penilai = (array) $this->input->post('pokja_penilai', TRUE);
        $pokja_readonly = (array) $this->input->post('pokja_readonly', TRUE);

        // sanitize: hanya id_pokja yang benar-benar ada di tabel pokja
        $valid = array();
        foreach ($this->User_pokja_ep_model->get_all_pokja() as $p) {
            $valid[] = intval($p->id);
        }

        $clean_penilai = array();
        foreach ($pokja_penilai as $id_pokja) {
            $id_pokja = intval($id_pokja);
            if (in_array($id_pokja, $valid, TRUE)) {
                $clean_penilai[] = $id_pokja;
            }
        }

        $clean_readonly = array();
        foreach ($pokja_readonly as $id_pokja) {
            $id_pokja = intval($id_pokja);
            // Penilai selalu menang kalau somehow dua-duanya tercentang
            if (in_array($id_pokja, $valid, TRUE) && !in_array($id_pokja, $clean_penilai, TRUE)) {
                $clean_readonly[] = $id_pokja;
            }
        }

        $this->User_pokja_ep_model->sync_pokja($id_user, $clean_penilai, $clean_readonly);

        $this->session->set_flashdata(
            'message',
            'Akses Pokja untuk ' . $user->name . ' berhasil disimpan (' .
            count($clean_penilai) . ' penilai, ' . count($clean_readonly) . ' lihat saja)'
        );
        redirect(site_url('user_pokja_ep'));
    }

    // hapus 1 assignment pokja langsung dari halaman list
    public function remove_pokja($id_user, $id_pokja)
    {
        $this->User_pokja_ep_model->remove_pokja($id_user, $id_pokja);
        $this->session->set_flashdata('message', 'Akses pokja berhasil dihapus');
        redirect(site_url('user_pokja_ep'));
    }
}
/* End of file User_pokja_ep.php */
/* Location: ./application/controllers/User_pokja_ep.php */
