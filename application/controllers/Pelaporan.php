<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pelaporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Pelaporan_model');
        $this->load->model('Pegawai_model');
        $this->load->library('form_validation');
    }

    private function is_hrd()
    {
        return in_array((int) $this->session->userdata('role_id'), array(1, 6), true);
    }

    private function is_pegawai_login()
    {
        return $this->session->userdata('is_pegawai') === TRUE || (int) $this->session->userdata('role_id') === 7;
    }

    // petunjuk laporan yang boleh dilihat pengguna saat ini: HRD = semua, pegawai = laporannya sendiri + laporan tentang dirinya
    public function index()
    {
        $my_pid = current_pegawai_id();
        $my_uid = (int) $this->session->userdata('id');
        $status = trim((string) $this->input->get('status', TRUE));
        if (!in_array($status, array('menunggu', 'divalidasi', 'ditolak'), true)) {
            $status = 'semua';
        }

        if ($this->is_hrd()) {
            $raw = $this->Pelaporan_model->list_all($status);
        } elseif ($this->is_pegawai_login()) {
            $raw = array_merge(
                $this->Pelaporan_model->list_by_pegawai($my_pid, $status),
                $my_pid ? $this->Pelaporan_model->list_by_terlapor($my_pid, $status) : array()
            );
        } else {
            $raw = $this->Pelaporan_model->list_by_user($my_uid, $status);
        }

        // dedupe + tambah flag per baris untuk view
        $seen = array();
        $list = array();
        foreach ($raw as $l) {
            if (isset($seen[$l->id_laporan])) continue;
            $seen[$l->id_laporan] = TRUE;
            $viewer_is_pelapor = ($l->id_pelapor && (int)$l->id_pelapor === $my_pid)
                || ($l->id_user_pelapor && (int)$l->id_user_pelapor === $my_uid);
            $viewer_is_terlapor = $my_pid && (int)$l->id_terlapor === $my_pid;
            $l->viewer_is_pelapor  = $viewer_is_pelapor;
            $l->viewer_is_terlapor = $viewer_is_terlapor;
            $l->can_delete = $this->is_hrd() || $viewer_is_pelapor;
            // terlapor maupun pelapor boleh melihat detail; identitas pelapor disembunyikan utk terlapor
            $list[] = $l;
        }

        $data = array(
            'laporan' => $list,
            'is_hrd' => $this->is_hrd(),
            'my_pegawai_id' => $my_pid,
            'status_filter' => $status,
        );

        $this->load->view('template/header', $data);
        $this->load->view('pelaporan/pelaporan_list', $data);
        $this->load->view('template/footer');
    }

    public function create()
    {
        $data = array(
            'action' => site_url('pelaporan/create_action'),
            'pegawai_list' => $this->Pegawai_model->list_aktif(),
            'set_laporan_id' => (int) $this->input->get('id_terlapor'),
            'is_pegawai_login' => $this->is_pegawai_login(),
            'my_pegawai_id' => current_pegawai_id(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('pelaporan/pelaporan_form', $data);
        $this->load->view('template/footer');
    }

    public function create_action()
    {
        $this->form_validation->set_rules('id_terlapor', 'Karyawan yang dinilai', 'trim|required|integer');
        $this->form_validation->set_rules('bintang', 'Bintang', 'trim|required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('alasan', 'Alasan / Pengaduan', 'trim|required|max_length[2000]');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $id_terlapor = (int) $this->input->post('id_terlapor', TRUE);
            $terlapor = $this->Pegawai_model->get_by_id($id_terlapor);

            if (!$terlapor) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Karyawan yang dinilai tidak ditemukan.</div>');
                redirect('pelaporan/create');
            }

            if ($this->is_pegawai_login() && current_pegawai_id() === $id_terlapor) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda tidak dapat menilai diri sendiri.</div>');
                redirect('pelaporan/create');
            }

            $data = array(
                'id_terlapor' => $id_terlapor,
                'bintang' => (int) $this->input->post('bintang', TRUE),
                'alasan' => htmlspecialchars($this->input->post('alasan', TRUE)),
                'jam' => date('H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );

            if ($this->is_pegawai_login()) {
                $data['id_pelapor'] = current_pegawai_id();
                $data['id_user_pelapor'] = NULL;
            } else {
                $data['id_user_pelapor'] = (int) $this->session->userdata('id');
                $data['id_pelapor'] = NULL;
            }

            $id_laporan = $this->Pelaporan_model->insert($data);

            // notifikasi ke terlapor: ada pelaporan baru tentang dirinya
            $this->load->model('Notifikasi_model');
            $peg = $this->Pegawai_model->get_by_id($id_terlapor);
            if ($peg && !empty($peg->email)) {
                $this->db->select('id');
                $users = $this->db->where('email', $peg->email)->get('users')->result();
                $pesan = 'Ada laporan / pengaduan baru atas nama Anda. Silakan buka dan berikan sanggahan bila perlu.';
                foreach ($users as $u) {
                    $this->Notifikasi_model->add($u->id, $pesan, 'pelaporan/detail/' . $id_laporan);
                }

                // kirim email pemberitahuan ke karyawan yang dilaporkan
                $this->_kirim_email_terlapor($peg->email, trim($peg->nama), $id_laporan, array(
                    'bintang' => (int) $data['bintang'],
                    'alasan'  => $data['alasan'],
                ));
            }

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Laporan / penilaian berhasil dikirim.</div>');
            redirect('pelaporan');
        }
    }

    public function detail($id)
    {
        $row = $this->Pelaporan_model->data($id);

        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Laporan tidak ditemukan.</div>');
            redirect('pelaporan');
        }

        if (!$this->_can_access($row)) {
            show_error('Anda tidak memiliki akses ke laporan ini.', 403);
        }

        $is_hrd_oleh = $this->is_hrd() && !$this->is_pegawai_login();
        $is_terlapor = current_pegawai_id() && (int) $row->id_terlapor === (int) current_pegawai_id() && !$this->is_hrd();

        $data = array(
            'row' => $row,
            'is_hrd' => $this->is_hrd(),
            'can_validasi' => $is_hrd_oleh,
            'is_pelapor' => $this->_is_pelapor($row),
            // identitas pelapor HANYA untuk Admin/HRD; baik terlapor maupun pelapor sendiri tidak melihatnya
            'show_pelapor_identitas' => $this->is_hrd(),
            'is_terlapor' => $is_terlapor,
            // sanggahan hanya bisa dikirim 1x oleh terlapor; Admin/HRD dapat ubah atas nama terlapor
            'can_sanggah' => $is_hrd_oleh || ($is_terlapor && empty(trim((string) $row->sanggahan))),
        );

        $this->load->view('template/header', $data);
        $this->load->view('pelaporan/pelaporan_detail', $data);
        $this->load->view('template/footer');
    }

    public function delete($id)
    {
        $row = $this->Pelaporan_model->data($id);

        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Laporan tidak ditemukan.</div>');
            redirect('pelaporan');
        }

        // hanya HRD/Admin atau pelapor sendiri yang boleh menghapus (terlapor tidak)
        if (!($this->is_hrd() && !$this->is_pegawai_login()) && !$this->_is_pelapor($row)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda tidak memiliki akses untuk menghapus laporan ini.</div>');
            redirect('pelaporan');
        }

        $this->Pelaporan_model->delete($id);

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Laporan berhasil dihapus.</div>');
        redirect('pelaporan');
    }

    // kirim email pemberitahuan ke karyawan yang dilaporkan
    private function _kirim_email_terlapor($email_tujuan, $nama_tujuan, $id_laporan, $data)
    {
        if (!filter_var($email_tujuan, FILTER_VALIDATE_EMAIL)) {
            log_message('error', 'Email pelaporan tidak dikirim: alamat email terlapor tidak valid (' . $email_tujuan . ').');
            return FALSE;
        }

        $this->load->library('email');
        $this->email->clear(TRUE);
        $this->email->from(config_item('email_from_address'), config_item('email_from_name'));
        $this->email->to($email_tujuan, $nama_tujuan);
        $this->email->subject('Pemberitahuan Laporan / Penilaian Karyawan — RS Airlangga');

        $link = rtrim((string) config_item('email_app_url'), '/') . '/index.php/pelaporan/detail/' . (int) $id_laporan;
        $pesan = '<p>Yth. ' . htmlspecialchars($nama_tujuan, ENT_QUOTES, 'UTF-8') . ',</p>'
            . '<p>Ada laporan / penilaian baru atas nama Anda pada Sistem PPI RS Airlangga:</p>'
            . '<p>Bintang: <strong>' . str_repeat('&#9733;', (int) $data['bintang']) . '</strong></p>'
            . '<p>Alasan: ' . nl2br($data['alasan']) . '</p>'
            . '<p>Silakan buka detail laporan untuk menjelaskan / menyanggah (sanggahan hanya dapat dikirim satu kali).</p>'
            . '<p><a href="' . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . '">Lihat Detail Laporan</a></p>';

        $this->email->message($pesan);

        if (!$this->email->send()) {
            log_message('error', 'Email pelaporan gagal dikirim ke ' . $email_tujuan . ': ' . $this->email->print_debugger(array('headers')));
            return FALSE;
        }
        return TRUE;
    }

    // simpan sanggahan (terlapor) — identitas pelapor tetap disembunyikan
    public function sanggah_action()
    {
        $id_laporan = (int) $this->input->post('id_laporan', TRUE);
        $row = $this->Pelaporan_model->data($id_laporan);

        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Laporan tidak ditemukan.</div>');
            redirect('pelaporan');
        }

        // pelapor / admin-HRD / terlapor yang bersangkutan boleh menyanggah
        $is_hrd_oleh = $this->is_hrd() && !$this->is_pegawai_login();
        if (!$is_hrd_oleh && (!current_pegawai_id() || (int) $row->id_terlapor !== (int) current_pegawai_id())) {
            show_error('Anda tidak berhak menyanggah laporan ini.', 403);
        }

        // sanggahan hanya boleh dikirim SATU kali oleh terlapor
        if (!$is_hrd_oleh && !empty(trim((string) $row->sanggahan))) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Sanggahan hanya dapat dikirim satu kali dan sudah pernah dikirim sebelumnya. Anda tidak dapat menyanggah lagi.</div>');
            redirect('pelaporan/detail/' . $id_laporan);
        }

        $sanggahan = trim($this->input->post('sanggahan', TRUE));
        if ($sanggahan === '') {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Sanggahan tidak boleh kosong.</div>');
            redirect('pelaporan/detail/' . $id_laporan);
        }

        $this->Pelaporan_model->sanggah($id_laporan, array(
            'sanggahan'     => htmlspecialchars($sanggahan),
            'sanggahan_at'  => date('Y-m-d H:i:s'),
            'sanggahan_oleh'=> $is_hrd_oleh ? 'Admin / HRD' : trim($row->nama_terlapor ?: 'Terlapor'),
        ));

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Sanggahan berhasil disimpan.</div>');
        redirect('pelaporan/detail/' . $id_laporan);
    }

    // penentuan status oleh Admin/HRD: divalidasi atau ditolak (wajib alasan)
    public function validasi_action()
    {
        if (!$this->is_hrd() || $this->is_pegawai_login()) {
            show_error('Anda tidak berhak menentukan validasi laporan ini.', 403);
        }

        $id_laporan = (int) $this->input->post('id_laporan', TRUE);
        $row = $this->Pelaporan_model->data($id_laporan);

        if (!$row) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Laporan tidak ditemukan.</div>');
            redirect('pelaporan');
        }

        $keputusan = $this->input->post('keputusan', TRUE);
        if (!in_array($keputusan, array('divalidasi', 'ditolak'), true)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Keputusan tidak valid.</div>');
            redirect('pelaporan/detail/' . $id_laporan);
        }

        $alasan = trim($this->input->post('alasan_validasi', TRUE));
        if ($alasan === '') {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Alasan wajib diisi untuk ' . ($keputusan === 'divalidasi' ? 'memvalidasi' : 'menolak') . ' laporan.</div>');
            redirect('pelaporan/detail/' . $id_laporan);
        }

        $label = ($keputusan === 'divalidasi') ? 'divalidasi' : 'ditolak';
        $nama_oleh = trim($this->session->userdata('name'));
        $this->Pelaporan_model->validasi($id_laporan, array(
            'status'          => $keputusan,
            'validasi_alasan' => htmlspecialchars($alasan),
            'validasi_oleh'   => $nama_oleh,
            'validasi_at'     => date('Y-m-d H:i:s'),
        ));

        // notifikasi status baru ke terlapor & pelapor (jika ada akun users)
        $this->load->model('Notifikasi_model');
        $pesan = 'Laporan / penilaian Anda ' . $label . ' oleh HRD/Admin. Lihat alasan pada detail laporan.';
        if (!empty($row->id_terlapor)) {
            $peg_t = $this->Pegawai_model->get_by_id($row->id_terlapor);
            if ($peg_t && !empty($peg_t->email)) {
                $users_t = $this->db->select('id')->where('email', $peg_t->email)->get('users')->result();
                foreach ($users_t as $u) {
                    $this->Notifikasi_model->add($u->id, $pesan, 'pelaporan/detail/' . $id_laporan);
                }
            }
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Laporan berhasil <strong>' . $label . '</strong>.</div>');
        redirect('pelaporan/detail/' . $id_laporan);
    }

    private function _is_pelapor($row)
    {
        if ($this->is_pegawai_login()) {
            return (int) $row->id_pelapor === current_pegawai_id();
        }
        return (int) $row->id_user_pelapor === (int) $this->session->userdata('id');
    }

    private function _can_access($row)
    {
        // HRD/Admin melihat semua
        if ($this->is_hrd() && !$this->is_pegawai_login()) {
            return TRUE;
        }
        // pelapor laporannya sendiri
        if ($this->_is_pelapor($row)) {
            return TRUE;
        }
        // terlapor melihat laporan tentang dirinya (identitas pelapor disembunyikan)
        $my_pid = current_pegawai_id();
        return $my_pid && (int) $row->id_terlapor === (int) $my_pid;
    }
}