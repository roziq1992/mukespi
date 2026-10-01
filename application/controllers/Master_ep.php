<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master_ep extends CI_Controller
{
    private $ROLE_ID_ADMIN = 1;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Master_ep_model');
        $this->load->helper('form');
    }

    public function index()
    {
        $user_id = (int)$this->session->userdata('id');
        $is_admin = ((int)$this->session->userdata('role_id') === $this->ROLE_ID_ADMIN);

        $data = array(
            'title' => 'Master Data SIPARDI',
            'stats' => array(
                'pokja_aktif' => $this->Master_ep_model->count_pokja('aktif', $is_admin ? NULL : $user_id),
                'pokja_nonaktif' => $this->Master_ep_model->count_pokja('nonaktif', $is_admin ? NULL : $user_id),
                'standar_aktif' => $this->Master_ep_model->count_standar('aktif', $is_admin ? NULL : $user_id),
                'standar_nonaktif' => $this->Master_ep_model->count_standar('nonaktif', $is_admin ? NULL : $user_id),
                'ep_aktif' => $this->Master_ep_model->count_elemen('aktif', $is_admin ? NULL : $user_id),
                'ep_nonaktif' => $this->Master_ep_model->count_elemen('nonaktif', $is_admin ? NULL : $user_id),
            ),
        );

        $this->load->view('template/header', $data);
        $this->load->view('master_ep/index');
        $this->load->view('template/footer');
    }

    // ================= POKJA =================

    public function pokja($status = 'aktif')
    {
        $status = in_array($status, array('aktif', 'nonaktif'), TRUE) ? $status : 'aktif';
        $q = trim($this->input->get('q', TRUE));
        $user_id = (int)$this->session->userdata('id');
        $is_admin = ((int)$this->session->userdata('role_id') === $this->ROLE_ID_ADMIN);

        $data = array(
            'title' => 'Data Pokja',
            'status' => $status,
            'q' => $q,
            'rows' => $this->Master_ep_model->get_pokja($status, $q, $is_admin ? NULL : $user_id),
            'stats' => array(
                'aktif' => $this->Master_ep_model->count_pokja('aktif', $is_admin ? NULL : $user_id),
                'nonaktif' => $this->Master_ep_model->count_pokja('nonaktif', $is_admin ? NULL : $user_id),
            ),
        );

        $this->load->view('template/header', $data);
        $this->load->view('master_ep/pokja_list');
        $this->load->view('template/footer');
    }

    // Drill-down: daftar Standar milik satu Pokja, dan EP tiap Standar
    // expandable (accordion) di halaman yang sama.
    public function pokja_standar($id = 0)
    {
        $id = (int)$id;
        $user_id  = (int)$this->session->userdata('id');
        $is_admin = ((int)$this->session->userdata('role_id') === $this->ROLE_ID_ADMIN);

        // Gate akses: user tanpa record user_pokja_ep dan bukan admin
        // akan ditolak di sini, bukan hanya disembunyikan tombolnya.
        $pokja = $this->Master_ep_model->get_pokja_boleh_lihat($id, $user_id, $is_admin);
        if (!$pokja) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Pokja tidak ditemukan atau Anda tidak punya akses ke pokja tersebut.</div>');
            redirect(site_url('master_ep/pokja'));
            return;
        }

        $hanya_aktif = $this->input->get('semua', TRUE) !== '1';

        $standar_list = $this->Master_ep_model->get_standar_dengan_jumlah_ep($pokja->bab, $hanya_aktif);
        $ep_map = array();
        foreach ($standar_list as $s) {
            $ep_map[$s->id_standar] = $this->Master_ep_model->get_ep_dengan_jumlah_bukti($s->id_standar, $hanya_aktif);
        }

        $data = array(
            'title' => 'Standar & EP — ' . $pokja->bab,
            'pokja' => $pokja,
            'standar_list' => $standar_list,
            'ep_map' => $ep_map,
            'hanya_aktif' => $hanya_aktif,
            'jml_standar' => count($standar_list),
            'jml_ep' => array_sum(array_map(function ($s) { return (int)$s->jml_ep; }, $standar_list)),
        );

        $this->load->view('template/header', $data);
        $this->load->view('master_ep/pokja_standar');
        $this->load->view('template/footer');
    }

    public function pokja_form($id = 0)
    {
        $id = (int)$id;
        $row = $id > 0 ? $this->Master_ep_model->get_pokja_by_id($id) : NULL;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('bab', 'BAB', 'trim|required');
        $this->form_validation->set_rules('ket', 'Keterangan', 'trim|required');
        $this->form_validation->set_rules('active', 'Status', 'trim|required|in_list[Y,N]');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'title' => $id > 0 ? 'Ubah Pokja' : 'Tambah Pokja',
                'row' => $row,
            );
            $this->load->view('template/header', $data);
            $this->load->view('master_ep/pokja_form');
            $this->load->view('template/footer');
            return;
        }

        $data = array(
            'bab' => trim($this->input->post('bab', TRUE)),
            'ket' => trim($this->input->post('ket', TRUE)),
            'active' => $this->input->post('active', TRUE) === 'N' ? 'N' : 'Y',
        );

        $this->Master_ep_model->save_pokja($data, $id);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Data pokja berhasil disimpan.</div>');
        redirect(site_url('master_ep/pokja'));
    }

    public function pokja_delete($id)
    {
        $id = (int)$id;
        $row = $this->Master_ep_model->get_pokja_by_id($id);
        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data pokja tidak ditemukan.</div>');
            redirect(site_url('master_ep/pokja'));
            return;
        }

        $this->Master_ep_model->set_pokja_active($id, 'N');
        $this->session->set_flashdata('message', '<div class="alert alert-warning">Pokja <strong>' . html_escape($row->ket) . '</strong> dinonaktifkan, bukan dihapus permanen dari database.</div>');
        redirect(site_url('master_ep/pokja'));
    }

    public function pokja_toggle($id, $active)
    {
        $id = (int)$id;
        $active = $active === 'N' ? 'N' : 'Y';
        $this->Master_ep_model->set_pokja_active($id, $active);
        redirect(site_url('master_ep/pokja'));
    }

    // ================= STANDAR =================

    public function standar($status = 'aktif')
    {
        $status = in_array($status, array('aktif', 'nonaktif'), TRUE) ? $status : 'aktif';
        $q = trim($this->input->get('q', TRUE));
        $user_id = (int)$this->session->userdata('id');
        $is_admin = ((int)$this->session->userdata('role_id') === $this->ROLE_ID_ADMIN);

        $data = array(
            'title' => 'Data Standar',
            'status' => $status,
            'q' => $q,
            'rows' => $this->Master_ep_model->get_standar($status, $q, $is_admin ? NULL : $user_id),
            'stats' => array(
                'aktif' => $this->Master_ep_model->count_standar('aktif', $is_admin ? NULL : $user_id),
                'nonaktif' => $this->Master_ep_model->count_standar('nonaktif', $is_admin ? NULL : $user_id),
            ),
        );

        $this->load->view('template/header', $data);
        $this->load->view('master_ep/standar_list');
        $this->load->view('template/footer');
    }

    public function standar_form($id = 0)
    {
        $id = (int)$id;
        $row = $id > 0 ? $this->Master_ep_model->get_standar_by_id($id) : NULL;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('bab', 'BAB', 'trim|required');
        $this->form_validation->set_rules('no_standar', 'Nomor Standar', 'trim|required');
        $this->form_validation->set_rules('isi_standar', 'Isi Standar', 'trim|required');
        $this->form_validation->set_rules('active', 'Status', 'trim|required|in_list[Y,N]');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'title' => $id > 0 ? 'Ubah Standar' : 'Tambah Standar',
                'row' => $row,
                'pokja_list' => $this->Master_ep_model->get_pokja_options((int)$this->session->userdata('id')),
            );
            $this->load->view('template/header', $data);
            $this->load->view('master_ep/standar_form');
            $this->load->view('template/footer');
            return;
        }

        $data = array(
            'bab' => trim($this->input->post('bab', TRUE)),
            'no_standar' => trim($this->input->post('no_standar', TRUE)),
            'isi_standar' => trim($this->input->post('isi_standar', TRUE)),
            'maksud_tujuan' => trim($this->input->post('maksud_tujuan', TRUE)),
            'active' => $this->input->post('active', TRUE) === 'N' ? 'N' : 'Y',
        );

        $this->Master_ep_model->save_standar($data, $id);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Data standar berhasil disimpan.</div>');
        redirect(site_url('master_ep/standar'));
    }

    public function standar_delete($id)
    {
        $id = (int)$id;
        $row = $this->Master_ep_model->get_standar_by_id($id);
        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data standar tidak ditemukan.</div>');
            redirect(site_url('master_ep/standar'));
            return;
        }

        $this->Master_ep_model->set_standar_active($id, 'N');
        $this->session->set_flashdata('message', '<div class="alert alert-warning">Standar <strong>' . html_escape($row->no_standar) . '</strong> dinonaktifkan, bukan dihapus permanen dari database.</div>');
        redirect(site_url('master_ep/standar'));
    }

    public function standar_toggle($id, $active)
    {
        $id = (int)$id;
        $active = $active === 'N' ? 'N' : 'Y';
        $this->Master_ep_model->set_standar_active($id, $active);
        redirect(site_url('master_ep/standar'));
    }

    // ================= NOMOR URUT TAMPILAN =================

    // Merapikan no_urut (nomor TAMPILAN) supaya 1, 2, 3, ... tanpa celah.
    // no_ep (nomor RESMI dokumen) tidak pernah disentuh.
    //
    // GET  = tampilkan pratinjau rencana
    // POST = jalankan
    public function rapikan_urutan($id_standar = 0)
    {
        $id_standar = (int)$id_standar;
        $user_id  = (int)$this->session->userdata('id');
        $is_admin = ((int)$this->session->userdata('role_id') === $this->ROLE_ID_ADMIN);

        $standar = $this->Master_ep_model->get_standar_by_id($id_standar);
        if (!$standar) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Standar tidak ditemukan.</div>');
            redirect(site_url('master_ep/standar'));
            return;
        }

        // Sama seperti halaman pokja_standar: user hanya boleh merapikan
        // standar dari pokja yang dia punya aksesnya.
        $pokja = $this->Master_ep_model->get_pokja_boleh_lihat_by_bab($standar->bab, $user_id, $is_admin);
        if (!$pokja) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak punya akses ke BAB ' . html_escape($standar->bab) . '.</div>');
            redirect(site_url('master_ep/standar'));
            return;
        }

        $rencana = $this->Master_ep_model->rencana_rapikan_no_urut($id_standar);

        // Hanya yang benar-benar berubah
        $berubah = array();
        foreach ($rencana as $r) {
            if ($r['no_urut_lama'] !== $r['no_urut_baru']) {
                $berubah[] = $r;
            }
        }

        if ($this->input->method() === 'post') {
            if (empty($berubah)) {
                $this->session->set_flashdata('message', '<div class="alert alert-info">Tidak ada nomor yang perlu dirapikan — urutannya sudah berurutan.</div>');
                redirect(site_url('master_ep/pokja_standar/' . $pokja->id));
                return;
            }

            $ok = $this->Master_ep_model->jalankan_rapikan_no_urut($rencana);
            if ($ok === FALSE) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal menyimpan. Tidak ada nomor yang berubah.</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-success">Nomor tampilan ' . count($berubah) . ' EP sudah dirapikan. <strong>Nomor resmi (no. dokumen) tidak diubah.</strong></div>');
            }
            redirect(site_url('master_ep/pokja_standar/' . $pokja->id));
            return;
        }

        $data = array(
            'title' => 'Rapikan Nomor EP',
            'pokja' => $pokja,
            'standar' => $standar,
            'rencana' => $rencana,
            'berubah' => $berubah,
        );

        $this->load->view('template/header', $data);
        $this->load->view('master_ep/rapikan_urutan');
        $this->load->view('template/footer');
    }

    // ================= ELEMEN PENILAIAN =================

    public function elemen($status = 'aktif')
    {
        $status = in_array($status, array('aktif', 'nonaktif'), TRUE) ? $status : 'aktif';
        $q = trim($this->input->get('q', TRUE));
        $user_id = (int)$this->session->userdata('id');
        $is_admin = ((int)$this->session->userdata('role_id') === $this->ROLE_ID_ADMIN);

        $data = array(
            'title' => 'Data Elemen Penilaian',
            'status' => $status,
            'q' => $q,
            'rows' => $this->Master_ep_model->get_elemen($status, $q, $is_admin ? NULL : $user_id),
            'stats' => array(
                'aktif' => $this->Master_ep_model->count_elemen('aktif', $is_admin ? NULL : $user_id),
                'nonaktif' => $this->Master_ep_model->count_elemen('nonaktif', $is_admin ? NULL : $user_id),
            ),
        );

        $this->load->view('template/header', $data);
        $this->load->view('master_ep/elemen_list');
        $this->load->view('template/footer');
    }

    public function elemen_form($id = 0)
    {
        $id = (int)$id;
        $row = $id > 0 ? $this->Master_ep_model->get_elemen_by_id($id) : NULL;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_standar', 'Standar', 'trim|required|integer');
        $this->form_validation->set_rules('no_ep', 'Nomor EP', 'trim|required|integer');
        $this->form_validation->set_rules('isi_ep', 'Isi EP', 'trim|required');
        $this->form_validation->set_rules('jenis_bukti', 'Jenis Bukti', 'trim|required|in_list[R,D,O,W,S]');
        $this->form_validation->set_rules('skor_maks', 'Skor Maksimal', 'trim|required|integer');
        $this->form_validation->set_rules('active', 'Status', 'trim|required|in_list[Y,N]');

        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'title' => $id > 0 ? 'Ubah Elemen Penilaian' : 'Tambah Elemen Penilaian',
                'row' => $row,
                'standar_list' => $this->Master_ep_model->get_standar('aktif', '', (int)$this->session->userdata('id')),
            );
            $this->load->view('template/header', $data);
            $this->load->view('master_ep/elemen_form');
            $this->load->view('template/footer');
            return;
        }

        $data = array(
            'id_standar' => (int)$this->input->post('id_standar', TRUE),
            'no_ep' => (int)$this->input->post('no_ep', TRUE),
            'isi_ep' => trim($this->input->post('isi_ep', TRUE)),
            'jenis_bukti' => trim($this->input->post('jenis_bukti', TRUE)),
            'skor_maks' => (int)$this->input->post('skor_maks', TRUE),
            'active' => $this->input->post('active', TRUE) === 'N' ? 'N' : 'Y',
        );

        $this->Master_ep_model->save_elemen($data, $id);
        if ((int)$id === 0) {
            // EP baru: nomor tampilan awalnya sama dengan nomor resmi.
            $this->Master_ep_model->set_no_urut_sama_dengan_no_ep($data['id_standar']);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success">Data elemen penilaian berhasil disimpan.</div>');
        redirect(site_url('master_ep/elemen'));
    }

    public function elemen_delete($id)
    {
        $id = (int)$id;
        $row = $this->Master_ep_model->get_elemen_by_id($id);
        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data elemen tidak ditemukan.</div>');
            redirect(site_url('master_ep/elemen'));
            return;
        }

        $this->Master_ep_model->set_elemen_active($id, 'N');
        $this->session->set_flashdata('message', '<div class="alert alert-warning">Elemen penilaian <strong>' . html_escape($row->no_ep) . '</strong> dinonaktifkan, bukan dihapus permanen dari database.</div>');
        redirect(site_url('master_ep/elemen'));
    }

    public function elemen_toggle($id, $active)
    {
        $id = (int)$id;
        $active = $active === 'N' ? 'N' : 'Y';
        $this->Master_ep_model->set_elemen_active($id, $active);
        redirect(site_url('master_ep/elemen'));
    }
}

/* End of file Master_ep.php */
