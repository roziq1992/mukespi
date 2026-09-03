<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controller Penilaian_ep
 * -------------------------------------------------------------
 * Alur:
 *  1. index()    -> daftar Pokja + progres penilaian (sesuai jenis penilaian user login)
 *  2. pokja($bab) -> daftar EP milik pokja tsb, tampil 2 track skor berdampingan
 *                    (Internal = tim RS, Surveior = akun role Surveior) untuk verifikasi/banding
 *  3. summary()  -> rekap perbandingan skor Internal vs Surveior per pokja
 *
 * ROLE SURVEIOR
 * -------------------------------------------------------------
 * Role disimpan lewat kolom role_id di tabel user (session userdata 'role_id'),
 * sama seperti pola ROLE_ID_ADMIN di controller Dokumen_unit. SESUAIKAN angka
 * $ROLE_ID_SURVEIOR di bawah dengan role_id yang kamu pakai untuk akun Surveior.
 * Lihat migrasi_jenis_penilaian.sql untuk contoh query set role_id-nya.
 *
 * Setiap user (internal biasa/admin ATAU surveior) mengisi skornya SENDIRI-SENDIRI
 * ke track yang berbeda (kolom jenis_penilaian di tabel penilaian_ep), supaya bisa
 * dibandingkan tanpa saling menimpa.
 *
 * Master table (pokja, standar, elemen_penilaian, periode_akreditasi)
 * TIDAK dibuatkan CRUD di sini — sudah diisi manual lewat query.
 * Pastikan minimal ada 1 baris di periode_akreditasi dengan status = 'aktif'
 * supaya modul ini bisa dites, dan sudah jalankan migrasi_jenis_penilaian.sql.
 */
class Penilaian_ep extends CI_Controller
{
    private $upload_folder = 'uploads/bukti_ep/';

    // >>> SESUAIKAN angka ini dengan role_id akun Surveior di tabel user kamu <<<
    private $ROLE_ID_SURVEIOR = 3;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Penilaian_ep_model');
    }

    private function _active_periode()
    {
        return $this->Penilaian_ep_model->get_active_periode();
    }

    // track skor milik user yang sedang login: 'internal' atau 'surveior'
    private function _jenis_penilaian_saya()
    {
        $role_id = $this->session->userdata('role_id');
        return ($role_id == $this->ROLE_ID_SURVEIOR) ? 'surveior' : 'internal';
    }

    private function _is_surveior()
    {
        return $this->_jenis_penilaian_saya() === 'surveior';
    }

    // ================= HALAMAN =================

    public function index()
    {
        $periode = $this->_active_periode();
        $jenis   = $this->_jenis_penilaian_saya();

        $data = array(
            'periode'     => $periode,
            'pokja_list'  => $periode ? $this->Penilaian_ep_model->get_pokja_progress($periode->id_periode, $jenis) : array(),
            'is_surveior' => $this->_is_surveior(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_ep/penilaian_ep_pokja');
        $this->load->view('template/footer');
    }

    public function pokja($bab = NULL)
    {
        if ($bab === NULL) {
            redirect(site_url('penilaian_ep'));
            return;
        }

        $periode = $this->_active_periode();
        if (!$periode) {
            $this->session->set_flashdata('message', 'Tidak ada periode akreditasi berstatus aktif. Set salah satu baris periode_akreditasi.status = "aktif" terlebih dahulu.');
            redirect(site_url('penilaian_ep'));
            return;
        }

        $pokja = $this->Penilaian_ep_model->get_pokja_by_bab($bab);
        if (!$pokja) {
            $this->session->set_flashdata('message', 'Pokja tidak ditemukan');
            redirect(site_url('penilaian_ep'));
            return;
        }

        $data = array(
            'periode'     => $periode,
            'pokja'       => $pokja,
            'ep_list'     => $this->Penilaian_ep_model->get_ep_by_pokja($bab, $periode->id_periode),
            'jenis_saya'  => $this->_jenis_penilaian_saya(),
            'is_surveior' => $this->_is_surveior(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_ep/penilaian_ep_ep', $data);
        $this->load->view('template/footer');
    }

    // rekap perbandingan skor Internal vs Surveior per pokja
    public function summary()
    {
        $periode = $this->_active_periode();

        $data = array(
            'periode'      => $periode,
            'summary_list' => $periode ? $this->Penilaian_ep_model->get_summary($periode->id_periode) : array(),
        );

        $this->load->view('template/header', $data);
        $this->load->view('penilaian_ep/penilaian_ep_summary');
        $this->load->view('template/footer');
    }

    // ================= AJAX: SIMPAN SKOR =================

    public function save_skor()
    {
        header('Content-Type: application/json');

        $periode = $this->_active_periode();
        if (!$periode) {
            echo json_encode(array('status' => FALSE, 'message' => 'Tidak ada periode aktif'));
            return;
        }

        $id_ep      = intval($this->input->post('id_ep', TRUE));
        $skor       = $this->input->post('skor', TRUE);
        $keterangan = $this->input->post('keterangan', TRUE);
        $jenis      = $this->_jenis_penilaian_saya(); // selalu diambil dari role login, BUKAN dari input

        if (!$id_ep) {
            echo json_encode(array('status' => FALSE, 'message' => 'EP tidak valid'));
            return;
        }

        $id_penilaian = $this->Penilaian_ep_model->upsert_skor(
            $periode->id_periode,
            $id_ep,
            $jenis,
            ($skor === '' || $skor === NULL) ? NULL : intval($skor),
            $keterangan,
            $this->session->userdata('email')
        );

        echo json_encode(array(
            'status'          => TRUE,
            'message'         => 'Skor tersimpan',
            'id_penilaian'    => $id_penilaian,
            'jenis_penilaian' => $jenis,
        ));
    }

    // ================= AJAX: LIST BUKTI PER EP =================
    // Menampilkan bukti dari KEDUA track (internal & surveior) sekaligus, dilabeli
    // sumbernya, supaya surveior bisa lihat bukti tim & tim bisa lihat temuan surveior.

    public function list_bukti($id_ep)
    {
        header('Content-Type: application/json');

        $id_ep   = intval($id_ep);
        $periode = $this->_active_periode();

        if (!$periode) {
            echo json_encode(array('status' => FALSE, 'message' => 'Tidak ada periode aktif'));
            return;
        }

        $items = array();
        foreach (array('internal', 'surveior') as $jenis) {
            $penilaian = $this->Penilaian_ep_model->get_penilaian($periode->id_periode, $id_ep, $jenis);
            if (!$penilaian) continue;

            $files = $this->Penilaian_ep_model->get_bukti_by_penilaian($penilaian->id_penilaian);
            foreach ($files as $f) {
                $items[] = array(
                    'id_upload'       => $f->id_upload,
                    'nama_file'       => $f->nama_file,
                    'url'             => base_url($f->path_file),
                    'keterangan'      => $f->keterangan,
                    'uploaded_by'     => $f->uploaded_by,
                    'uploaded_at'     => $f->uploaded_at,
                    'jenis_penilaian' => $jenis,
                );
            }
        }

        echo json_encode(array('status' => TRUE, 'files' => $items));
    }

    // ================= AJAX: UPLOAD BUKTI (MULTI FILE) =================
    // File yang diupload nempel ke track milik role user yang sedang login.

    public function upload_bukti()
    {
        header('Content-Type: application/json');

        $periode = $this->_active_periode();
        if (!$periode) {
            echo json_encode(array('status' => FALSE, 'message' => 'Tidak ada periode aktif'));
            return;
        }

        $id_ep      = intval($this->input->post('id_ep', TRUE));
        $keterangan = $this->input->post('keterangan', TRUE);
        $jenis      = $this->_jenis_penilaian_saya();

        if (!$id_ep) {
            echo json_encode(array('status' => FALSE, 'message' => 'EP tidak valid'));
            return;
        }

        if (empty($_FILES['file_bukti']['name'][0])) {
            echo json_encode(array('status' => FALSE, 'message' => 'Pilih minimal 1 file'));
            return;
        }

        // pastikan baris penilaian_ep untuk track milik user ini sudah ada (skor boleh NULL dulu)
        $id_penilaian = $this->Penilaian_ep_model->get_or_create_penilaian(
            $periode->id_periode,
            $id_ep,
            $jenis,
            $this->session->userdata('email')
        );

        $upload_path = FCPATH . $this->upload_folder;
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png';
        $config['max_size']      = 5120; // 5 MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        $total  = count($_FILES['file_bukti']['name']);
        $sukses = 0;
        $gagal  = array();

        for ($i = 0; $i < $total; $i++) {
            if (empty($_FILES['file_bukti']['name'][$i])) continue;

            $_FILES['file_single']['name']     = $_FILES['file_bukti']['name'][$i];
            $_FILES['file_single']['type']     = $_FILES['file_bukti']['type'][$i];
            $_FILES['file_single']['tmp_name'] = $_FILES['file_bukti']['tmp_name'][$i];
            $_FILES['file_single']['error']    = $_FILES['file_bukti']['error'][$i];
            $_FILES['file_single']['size']     = $_FILES['file_bukti']['size'][$i];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('file_single')) {
                $file = $this->upload->data();

                $this->Penilaian_ep_model->insert_bukti(array(
                    'id_penilaian' => $id_penilaian,
                    'nama_file'    => $file['orig_name'],
                    'path_file'    => $this->upload_folder . $file['file_name'],
                    'keterangan'   => $keterangan,
                    'uploaded_by'  => $this->session->userdata('email'),
                    'uploaded_at'  => date('Y-m-d H:i:s'),
                ));
                $sukses++;
            } else {
                $gagal[] = $_FILES['file_single']['name'] . ': ' . strip_tags($this->upload->display_errors('', ''));
            }
        }

        echo json_encode(array(
            'status'  => $sukses > 0,
            'message' => $sukses . ' file berhasil diupload' . (!empty($gagal) ? '. Gagal: ' . implode(' | ', $gagal) : ''),
            'sukses'  => $sukses,
            'gagal'   => $gagal,
        ));
    }

    // ================= AJAX: HAPUS BUKTI =================
    // Hanya boleh hapus bukti dari track milik sendiri (internal tidak bisa hapus
    // bukti surveior, dan sebaliknya) — kecuali admin, yang boleh hapus semua.

    public function delete_bukti()
    {
        header('Content-Type: application/json');

        $id_upload = intval($this->input->post('id_upload', TRUE));
        $bukti     = $this->Penilaian_ep_model->get_bukti_by_id($id_upload);

        if (!$bukti) {
            echo json_encode(array('status' => FALSE, 'message' => 'File tidak ditemukan'));
            return;
        }

        $penilaian   = $this->Penilaian_ep_model->get_penilaian_by_id($bukti->id_penilaian);
        $jenis_saya  = $this->_jenis_penilaian_saya();
        $is_admin    = ($this->session->userdata('email') == 'admin@mail.com');

        if ($penilaian && $penilaian->jenis_penilaian !== $jenis_saya && !$is_admin) {
            echo json_encode(array('status' => FALSE, 'message' => 'Anda tidak bisa menghapus bukti milik track ' . ($penilaian->jenis_penilaian == 'surveior' ? 'Surveior' : 'Internal') . ' ini'));
            return;
        }

        if (!empty($bukti->path_file) && file_exists(FCPATH . $bukti->path_file)) {
            @unlink(FCPATH . $bukti->path_file);
        }

        $this->Penilaian_ep_model->delete_bukti($id_upload);
        echo json_encode(array('status' => TRUE));
    }
}

/* End of file Penilaian_ep.php */