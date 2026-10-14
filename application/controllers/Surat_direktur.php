<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_direktur extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        if (!in_array((int) $this->session->userdata('role_id'), array(1, 4), TRUE)) redirect('auth/blocked');
        $this->load->helper(array('surat', 'download'));
        $this->load->model(array('Surat_model', 'Surat_disposisi_model'));
        $this->load->library(array('upload', 'email'));
    }

    public function index()
    {
        $filters = array('q' => trim($this->input->get('q', TRUE)), 'status' => $this->input->get('status', TRUE), 'jenis' => $this->input->get('jenis', TRUE), 'mulai' => $this->input->get('mulai', TRUE), 'sampai' => $this->input->get('sampai', TRUE));
        $data = array('title' => 'Antrian Direktur', 'surat' => $this->Surat_model->list_data('direktur', $this->session->userdata('id'), $filters), 'filters' => $filters, 'pending' => $this->Surat_model->pending_count('direktur', $this->session->userdata('id')));
        $this->load->view('template/header', $data);
        $this->load->view('surat/list_direktur', $data);
        $this->load->view('template/footer');
    }

    public function proses($id)
    {
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        $data = array(
            'title' => 'Aksi Direktur',
            'surat' => $surat,
            'users' => $this->Surat_model->users(),
            'signature_preview' => $this->_pdf_source($surat) !== FALSE
        );
        $this->load->view('template/header', $data);
        $this->load->view('surat/proses_direktur', $data);
        $this->load->view('template/footer');
    }

    public function preview($id)
    {
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        $source = $this->_pdf_source($surat);
        if ($source === FALSE) show_404();

        while (ob_get_level()) ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="surat-' . (int) $id . '.pdf"');
        header('Content-Length: ' . filesize($source));
        header('Cache-Control: private, no-store, no-cache, must-revalidate');
        header('X-Content-Type-Options: nosniff');
        readfile($source);
        exit;
    }

    public function simpan($id)
    {
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        $aksi = $this->input->post('aksi', TRUE);
        if ($aksi === 'ttd') {
            if (!in_array($surat->status, array('Sudah Diberi Nomor', 'Diteruskan ke Direktur'), TRUE)) {
                $this->session->set_flashdata('message', 'Surat belum berada pada tahap yang dapat ditandatangani.');
                redirect('surat_direktur/proses/' . $id);
                return;
            }
            if ($this->input->post('ttd_luar_sistem', TRUE) === '1') {
                $user = $this->db->select('name')->where('id', (int) $this->session->userdata('id'))->get('users')->row();
                if (!$user) show_error('Data pengguna Direktur tidak ditemukan.', 500);
                $update = array('status' => 'Ditandatangani', 'updated_at' => date('Y-m-d H:i:s'));
                $this->Surat_model->update($id, $update);
                $catatan = trim($this->input->post('catatan', TRUE));
                log_surat($id, 'Surat ditandatangani di luar sistem', 'Ditandai selesai oleh ' . $user->name . ' tanpa tanda tangan pada sistem (ditandatangani di luar sistem).' . ($catatan !== '' ? ' ' . $catatan : ''));
                $pemohon = $this->db->where('id', $surat->id_pemohon)->get('users')->row();
                if ($pemohon) surat_kirim_notifikasi($pemohon->email, $pemohon->name, 'Surat Anda telah ditandatangani', 'Surat dengan perihal <strong>' . htmlspecialchars($surat->perihal, ENT_QUOTES, 'UTF-8') . '</strong> telah ditandatangani Direktur RS Airlangga.', $id);
                redirect((int) $this->session->userdata('role_id') === 1 ? 'surat' : 'surat_direktur');
                return;
            }
            $source = $this->_pdf_source($surat);
            if ($source === FALSE) {
                $this->session->set_flashdata('message', 'Dokumen untuk tanda tangan harus berupa PDF. Minta sekretaris mengunggah dokumen bernomor PDF.');
                redirect('surat_direktur/proses/' . $id);
                return;
            }
            $signature = $this->input->post('signature_data', FALSE);
            if (!is_string($signature) || strpos($signature, 'data:image/png;base64,') !== 0) {
                $this->session->set_flashdata('message', 'Tanda tangan wajib dibubuhkan langsung pada halaman PDF.');
                redirect('surat_direktur/proses/' . $id);
                return;
            }
            $page = (string) $this->input->post('signature_page', TRUE);
            $signature_x = $this->input->post('signature_x', TRUE);
            $signature_y = $this->input->post('signature_y', TRUE);
            $signature_width = $this->input->post('signature_width', TRUE);
            if (!ctype_digit($page) || !is_numeric($signature_x) || !is_numeric($signature_y) || !is_numeric($signature_width)
                || (float) $signature_x < 0 || (float) $signature_x > 1
                || (float) $signature_y < 0 || (float) $signature_y > 1
                || (float) $signature_width < 0.01 || (float) $signature_width > 0.8) {
                $this->session->set_flashdata('message', 'Posisi tanda tangan pada dokumen tidak valid. Silakan bubuhkan ulang tanda tangan.');
                redirect('surat_direktur/proses/' . $id);
                return;
            }
            $user = $this->db->select('name')->where('id', (int) $this->session->userdata('id'))->get('users')->row();
            if (!$user) show_error('Data pengguna Direktur tidak ditemukan.', 500);
            $relative_path = $this->_create_signed_pdf($id, $source, $signature, (int) $page, (float) $signature_x, (float) $signature_y, (float) $signature_width);
            if ($relative_path === FALSE) return;
            $update = array('status' => 'Ditandatangani', 'file_final' => $relative_path, 'updated_at' => date('Y-m-d H:i:s'));
            if (!$this->Surat_model->update($id, $update)) {
                @unlink(FCPATH . $relative_path);
                show_error('Dokumen tanda tangan dibuat, tetapi status surat gagal diperbarui.', 500);
            }
            $catatan = trim($this->input->post('catatan', TRUE));
            log_surat($id, 'Surat ditandatangani', 'Ditandatangani secara visual di sistem oleh ' . $user->name . '.' . ($catatan !== '' ? ' ' . $catatan : ''));
            $pemohon = $this->db->where('id', $surat->id_pemohon)->get('users')->row();
            if ($pemohon) surat_kirim_notifikasi($pemohon->email, $pemohon->name, 'Surat Anda telah ditandatangani', 'Surat dengan perihal <strong>' . htmlspecialchars($surat->perihal, ENT_QUOTES, 'UTF-8') . '</strong> telah ditandatangani Direktur RS Airlangga.', $id);
        } elseif ($aksi === 'disposisi') {
            $posted_users = $this->input->post('ke_user');
            if (!is_array($posted_users)) $posted_users = array();
            $ke_users = array();
            foreach ($posted_users as $user_id) {
                if (!is_scalar($user_id) || !ctype_digit((string) $user_id) || (int) $user_id < 1) {
                    $this->session->set_flashdata('message', 'Satu atau lebih tujuan disposisi tidak valid.');
                    redirect('surat_direktur/proses/' . $id);
                    return;
                }
                $ke_users[] = (int) $user_id;
            }
            $ke_users = array_values(array_unique($ke_users));
            $catatan = trim($this->input->post('catatan', TRUE));
            if (!$ke_users) {
                $this->session->set_flashdata('message', 'Pilih minimal satu tujuan disposisi.');
                redirect('surat_direktur/proses/' . $id);
                return;
            }
            if ($catatan === '') {
                $this->session->set_flashdata('message', 'Catatan / instruksi disposisi wajib diisi.');
                redirect('surat_direktur/proses/' . $id);
                return;
            }
            $penerima = $this->db->select('id, name, email')->where_in('id', $ke_users)
                ->where('id !=', (int) $this->session->userdata('id'))->get('users')->result();
            if (count($penerima) !== count($ke_users)) {
                $this->session->set_flashdata('message', 'Satu atau lebih tujuan disposisi tidak valid.');
                redirect('surat_direktur/proses/' . $id);
                return;
            }
            $update = array('status' => 'Didisposisikan', 'updated_at' => date('Y-m-d H:i:s'));
            if (!$this->_upload_final($id, $update)) return;
            $this->db->trans_start();
            foreach ($penerima as $user) {
                $this->Surat_disposisi_model->insert(array('id_surat' => $id, 'dari_user' => $this->session->userdata('id'), 'ke_user' => $user->id, 'catatan' => $catatan, 'status' => 'Menunggu Tindak Lanjut', 'created_at' => date('Y-m-d H:i:s')));
            }
            $this->Surat_model->update($id, $update);
            log_surat($id, 'Surat didisposisikan', $catatan);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) show_error('Disposisi dan status surat gagal disimpan.', 500);
            surat_kirim_notifikasi_massal($penerima, 'Pemberitahuan Disposisi Surat', 'Dengan hormat, Direktur RS Airlangga telah memberikan disposisi surat dengan perihal <strong>' . htmlspecialchars($surat->perihal, ENT_QUOTES, 'UTF-8') . '</strong>.<br><br>Catatan disposisi:<br>' . htmlspecialchars($catatan, ENT_QUOTES, 'UTF-8'));
        }
        redirect((int) $this->session->userdata('role_id') === 1 ? 'surat' : 'surat_direktur');
    }

    private function _pdf_source($surat)
    {
        $relative_path = $surat->file_ber_nomor ?: $surat->file_draft;
        if (!$relative_path || strtolower(pathinfo($relative_path, PATHINFO_EXTENSION)) !== 'pdf') return FALSE;

        $root = realpath(FCPATH . 'uploads/surat/' . (int) $surat->id);
        $source = realpath(FCPATH . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, ltrim($relative_path, '/\\')));
        if (!$root || !$source || !is_file($source)) return FALSE;

        $root_prefix = strtolower($root . DIRECTORY_SEPARATOR);
        if (strpos(strtolower($source), $root_prefix) !== 0) return FALSE;
        return $source;
    }

    private function _create_signed_pdf($id, $source, $signature_data, $signature_page, $signature_x, $signature_y, $signature_width)
    {
        $autoload = FCPATH . 'vendor/autoload.php';
        if (!is_file($autoload)) show_error('Library PDF belum tersedia. Jalankan composer install pada server aplikasi.', 500);
        require_once $autoload;

        $encoded_signature = substr($signature_data, strlen('data:image/png;base64,'));
        if (strlen($encoded_signature) > 1400000) {
            $this->session->set_flashdata('message', 'Ukuran gambar tanda tangan terlalu besar. Silakan hapus dan buat ulang tanda tangan.');
            redirect('surat_direktur/proses/' . $id);
            exit;
        }
        $signature_bytes = base64_decode($encoded_signature, TRUE);
        $image_info = $signature_bytes === FALSE ? FALSE : getimagesizefromstring($signature_bytes);
        if ($image_info === FALSE || $image_info[2] !== IMAGETYPE_PNG || $image_info[0] > 2000 || $image_info[1] > 1000) {
            $this->session->set_flashdata('message', 'Gambar tanda tangan tidak valid. Silakan buat ulang tanda tangan.');
            redirect('surat_direktur/proses/' . $id);
            exit;
        }

        $output_dir = FCPATH . 'uploads/surat/' . (int) $id . '/final/';
        if (!is_dir($output_dir) && !mkdir($output_dir, 0755, TRUE) && !is_dir($output_dir)) {
            show_error('Folder penyimpanan dokumen tanda tangan tidak dapat dibuat.', 500);
        }
        $relative_path = 'uploads/surat/' . (int) $id . '/final/' . bin2hex(random_bytes(16)) . '.pdf';
        $output_path = FCPATH . $relative_path;
        $signature_path = tempnam(sys_get_temp_dir(), 'surat-ttd-');
        if ($signature_path === FALSE || file_put_contents($signature_path, $signature_bytes) === FALSE) {
            if ($signature_path !== FALSE && is_file($signature_path)) unlink($signature_path);
            show_error('Gambar tanda tangan tidak dapat diproses.', 500);
        }

        $processing_error = NULL;
        try {
            $pdf = new \setasign\Fpdi\Fpdi();
            $page_count = $pdf->setSourceFile($source);
            if ($page_count < 1) throw new \RuntimeException('PDF tidak memiliki halaman.');
            if ($signature_page < 1 || $signature_page > $page_count) {
                throw new \RuntimeException('Halaman tanda tangan tidak ditemukan di dokumen PDF.');
            }
            for ($page_number = 1; $page_number <= $page_count; $page_number++) {
                $template = $pdf->importPage($page_number);
                $size = $pdf->getTemplateSize($template);
                $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));
                $pdf->useTemplate($template);

                if ($page_number === $signature_page) {
                    $image_width = $signature_width * $size['width'];
                    $image_height = $image_width * $image_info[1] / $image_info[0];
                    $x = $signature_x * $size['width'];
                    $y = $signature_y * $size['height'];
                    if ($x + $image_width > $size['width'] || $y + $image_height > $size['height']) {
                        throw new \RuntimeException('Posisi tanda tangan keluar dari batas halaman PDF.');
                    }
                    $pdf->Image($signature_path, $x, $y, $image_width, $image_height, 'PNG');
                }
            }
            $pdf->Output('F', $output_path);
        } catch (\Throwable $error) {
            if (is_file($output_path)) unlink($output_path);
            log_message('error', 'Gagal membuat dokumen tanda tangan surat ' . (int) $id . ': ' . $error->getMessage());
            $processing_error = $error->getMessage();
        } finally {
            if (is_file($signature_path)) unlink($signature_path);
        }

        if ($processing_error !== NULL) {
            if (in_array($processing_error, array('Halaman tanda tangan tidak ditemukan di dokumen PDF.', 'Posisi tanda tangan keluar dari batas halaman PDF.'), TRUE)) {
                $this->session->set_flashdata('message', $processing_error . ' Silakan atur ulang tanda tangan.');
                redirect('surat_direktur/proses/' . $id);
                return FALSE;
            }
            show_error('Dokumen PDF gagal diproses untuk tanda tangan. Pastikan PDF tidak rusak atau dilindungi kata sandi.', 500);
        }

        return $relative_path;
    }

    private function _upload_final($id, &$update)
    {
        if (empty($_FILES['file_final']['name'])) return TRUE;
        $path = FCPATH . 'uploads/surat/' . $id . '/final/';
        if (!is_dir($path)) mkdir($path, 0755, TRUE);
        $this->upload->initialize(array('upload_path' => $path, 'allowed_types' => 'pdf|doc|docx|jpg|png', 'max_size' => 10240, 'encrypt_name' => TRUE));
        if (!$this->upload->do_upload('file_final')) { $this->session->set_flashdata('message', $this->upload->display_errors()); redirect('surat_direktur/proses/' . $id); return FALSE; }
        $update['file_final'] = 'uploads/surat/' . $id . '/final/' . $this->upload->data('file_name');
        return TRUE;
    }

    public function selesai_disposisi($id)
    {
        $row = $this->db->where('id', (int) $id)->get('surat_disposisi')->row();
        if (!$row || ((int) $row->ke_user !== (int) $this->session->userdata('id') && (int) $this->session->userdata('role_id') !== 1)) show_error('Akses ditolak.', 403);
        $this->Surat_disposisi_model->update_status($id, 'Selesai');
        $belum_selesai = $this->db->where('id_surat', $row->id_surat)->where('status !=', 'Selesai')->count_all_results('surat_disposisi');
        if (!$belum_selesai) $this->Surat_model->update($row->id_surat, array('status' => 'Selesai', 'updated_at' => date('Y-m-d H:i:s')));
        log_surat($row->id_surat, 'Disposisi diselesaikan', $this->input->post('catatan', TRUE));
        redirect('surat/detail/' . $row->id_surat);
    }
}
