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
        $this->load->model(array('Surat_model', 'Surat_disposisi_model', 'Surat_lampiran_model'));
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
        $kode_verifikasi = $this->_pastikan_kode_verifikasi($surat);
        $data = array(
            'title' => 'Aksi Direktur',
            'surat' => $surat,
            'users' => $this->Surat_model->users(),
            'signature_preview' => $this->_pdf_source($surat) !== FALSE,
            'verifikasi_url' => site_url('surat/verifikasi/' . (int) $surat->id . '/' . $kode_verifikasi),
            'lampiran' => $this->Surat_lampiran_model->by_surat($id)
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
        if (in_array($surat->status, array('Ditandatangani', 'Didisposisikan', 'Selesai', 'Ditolak'), TRUE)) {
            $this->session->set_flashdata('message', 'Surat sudah diproses. Gunakan tombol Proses Ulang untuk memperbaiki aksi sebelumnya.');
            redirect('surat_direktur/proses/' . $id);
            return;
        }
        if ($aksi === 'ttd') {
            if (!in_array($surat->status, array('Sudah Diberi Nomor', 'Diteruskan ke Direktur'), TRUE)) {
                $this->session->set_flashdata('message', 'Surat belum berada pada tahap yang dapat ditandatangani.');
                redirect('surat_direktur/proses/' . $id);
                return;
            }
            $user = $this->db->select('name')->where('id', (int) $this->session->userdata('id'))->get('users')->row();
            if (!$user) show_error('Data pengguna Direktur tidak ditemukan.', 500);
            $catatan = trim($this->input->post('catatan', TRUE));
            $signature = $this->input->post('signature_data', FALSE);
            $ada_tanda_tangan = is_string($signature) && strpos($signature, 'data:image/png;base64,') === 0;
            $qr_png = $this->_qr_bytes($this->input->post('verification_qr', FALSE));
            $kode_verifikasi = $this->_pastikan_kode_verifikasi($surat);
            $update = array('status' => 'Ditandatangani', 'kode_verifikasi' => $kode_verifikasi, 'updated_at' => date('Y-m-d H:i:s'));
            $sumber_final = 'dokumen bernomor';

            if ($ada_tanda_tangan) {
                $source = $this->_pdf_source($surat);
                if ($source === FALSE) {
                    $this->session->set_flashdata('message', 'Dokumen untuk tanda tangan harus berupa PDF. Minta sekretaris mengunggah dokumen bernomor PDF.');
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
                $relative_path = $this->_stamp_pdf($id, $source, $signature, (int) $page, (float) $signature_x, (float) $signature_y, (float) $signature_width, $qr_png);
                if ($relative_path === FALSE) return;
                $update['file_final'] = $relative_path;
                $sumber_final = 'tanda tangan pada sistem';
            } else {
                if (!$this->_upload_final($id, $update)) return;
                if (!empty($update['file_final'])) {
                    $sumber_final = 'dokumen hasil TTD yang diunggah';
                    $uploaded_abs = $this->_safe_pdf_path($update['file_final']);
                    if ($uploaded_abs !== FALSE && $qr_png !== NULL) {
                        $stamped = $this->_stamp_pdf($id, $uploaded_abs, NULL, 0, 0, 0, 0, $qr_png);
                        if ($stamped !== FALSE) { @unlink($uploaded_abs); $update['file_final'] = $stamped; }
                    }
                } else {
                    $source = $this->_pdf_source($surat);
                    if ($source !== FALSE && $qr_png !== NULL) {
                        $stamped = $this->_stamp_pdf($id, $source, NULL, 0, 0, 0, 0, $qr_png);
                        if ($stamped !== FALSE) $update['file_final'] = $stamped;
                    }
                    if (empty($update['file_final'])) {
                        $fallback = $surat->file_ber_nomor ?: $surat->file_draft;
                        if (!$fallback) {
                            $this->session->set_flashdata('message', 'Tidak ada dokumen yang dapat dijadikan dokumen final. Unggah dokumen hasil TTD atau minta sekretaris mengunggah dokumen bernomor.');
                            redirect('surat_direktur/proses/' . $id);
                            return;
                        }
                        $update['file_final'] = $fallback;
                    }
                }
            }

            if (!$this->Surat_model->update($id, $update)) {
                if ($ada_tanda_tangan && !empty($update['file_final'])) @unlink(FCPATH . $update['file_final']);
                show_error('Status surat gagal diperbarui.', 500);
            }
            log_surat($id, 'Surat ditandatangani', 'Ditandatangani oleh ' . $user->name . ' menggunakan ' . $sumber_final . '.' . ($catatan !== '' ? ' ' . $catatan : ''));
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
        } elseif ($aksi === 'tolak') {
            if (in_array($surat->status, array('Ditolak', 'Didisposisikan', 'Selesai'), TRUE)) {
                $this->session->set_flashdata('message', 'Surat ini tidak dapat ditolak pada tahap saat ini.');
                redirect('surat_direktur/proses/' . $id);
                return;
            }
            $alasan = trim($this->input->post('alasan_tolak', TRUE));
            if ($alasan === '') {
                $this->session->set_flashdata('message', 'Alasan penolakan surat wajib diisi.');
                redirect('surat_direktur/proses/' . $id);
                return;
            }
            if (!$this->Surat_model->update($id, array('status' => 'Ditolak', 'alasan_tolak' => $alasan, 'updated_at' => date('Y-m-d H:i:s')))) {
                show_error('Status surat gagal diperbarui.', 500);
            }
            log_surat($id, 'Surat ditolak', $alasan);
            $pemohon = $this->db->where('id', $surat->id_pemohon)->get('users')->row();
            if ($pemohon) surat_kirim_notifikasi($pemohon->email, $pemohon->name, 'Surat Anda ditolak', 'Surat dengan perihal <strong>' . htmlspecialchars($surat->perihal, ENT_QUOTES, 'UTF-8') . '</strong> ditolak oleh Direktur RS Airlangga.<br><br>Alasan penolakan:<br>' . htmlspecialchars($alasan, ENT_QUOTES, 'UTF-8'), $id);
        }
        redirect((int) $this->session->userdata('role_id') === 1 ? 'surat' : 'surat_direktur');
    }

    public function ulang($id)
    {
        $surat = $this->Surat_model->get((int) $id);
        if (!$surat) show_404();
        if (strtolower($this->input->method()) !== 'post') show_error('Metode request tidak valid.', 405);
        if (!in_array($surat->status, array('Ditandatangani', 'Didisposisikan', 'Selesai', 'Ditolak'), TRUE)) {
            $this->session->set_flashdata('message', 'Surat belum diproses final, tidak perlu diproses ulang.');
            redirect('surat_direktur/proses/' . (int) $id);
            return;
        }

        $old_final = $surat->file_final;
        $status_kembali = ($surat->jenis === 'disposisi' || $surat->file_ber_nomor) ? 'Diteruskan ke Direktur' : 'Sudah Diberi Nomor';
        $this->db->trans_start();
        $this->db->where('id_surat', (int) $id)->delete('surat_disposisi');
        $this->Surat_model->update((int) $id, array(
            'status' => $status_kembali,
            'file_final' => NULL,
            'kode_verifikasi' => NULL,
            'alasan_tolak' => NULL,
            'updated_at' => date('Y-m-d H:i:s')
        ));
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) show_error('Proses ulang surat gagal disimpan.', 500);

        if ($old_final && $old_final !== $surat->file_ber_nomor && $old_final !== $surat->file_draft) {
            $this->_delete_upload_file($old_final);
        }
        log_surat($id, 'Proses direktur diulang', 'Aksi sebelumnya dibatalkan. Status dikembalikan ke ' . $status_kembali . ', disposisi dan dokumen final lama dihapus.');
        $this->session->set_flashdata('message', 'Aksi direktur berhasil dibatalkan. Silakan lakukan proses ulang.');
        redirect('surat_direktur/proses/' . (int) $id);
    }

    private function _delete_upload_file($relative)
    {
        if (!$relative) return;
        $root = realpath(FCPATH . 'uploads/surat');
        $full = realpath(FCPATH . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, ltrim($relative, '/\\')));
        if ($root === FALSE || $full === FALSE || !is_file($full)) return;
        if (strpos(strtolower($full), strtolower($root . DIRECTORY_SEPARATOR)) !== 0) return;
        @unlink($full);
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

    /**
     * Ambil kode verifikasi surat; buat & simpan bila belum ada.
     * Kode ini di-encode menjadi QR yang menunjuk halaman verifikasi publik.
     */
    private function _pastikan_kode_verifikasi($surat)
    {
        if (!empty($surat->kode_verifikasi)) return $surat->kode_verifikasi;
        $kode = bin2hex(random_bytes(16));
        $this->Surat_model->update($surat->id, array('kode_verifikasi' => $kode));
        $surat->kode_verifikasi = $kode;
        return $kode;
    }

    /**
     * Validasi data URL PNG QR dari browser, kembalikan byte PNG atau NULL.
     */
    private function _qr_bytes($data)
    {
        if (!is_string($data) || strpos($data, 'data:image/png;base64,') !== 0) return NULL;
        $encoded = substr($data, strlen('data:image/png;base64,'));
        if (strlen($encoded) > 200000) return NULL;
        $bytes = base64_decode($encoded, TRUE);
        if ($bytes === FALSE) return NULL;
        $info = getimagesizefromstring($bytes);
        if ($info === FALSE || $info[2] !== IMAGETYPE_PNG || $info[0] > 1200 || $info[1] > 1200) return NULL;
        return $bytes;
    }

    /**
     * Kembalikan path absolut bila file relatif valid & berupa PDF di area upload.
     */
    private function _safe_pdf_path($relative_path)
    {
        if (!$relative_path || strtolower(pathinfo($relative_path, PATHINFO_EXTENSION)) !== 'pdf') return FALSE;
        $full = realpath(FCPATH . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, ltrim($relative_path, '/\\')));
        if ($full === FALSE || !is_file($full)) return FALSE;
        $root = realpath(FCPATH . 'uploads/surat');
        if (!$root || strpos(strtolower($full), strtolower($root . DIRECTORY_SEPARATOR)) !== 0) return FALSE;
        return $full;
    }

    /**
     * Buat PDF baru dari $source, tempel (opsional) tanda tangan dan (opsional) QR verifikasi.
     * QR ditempel di kanan bawah halaman terakhir.
     */
    private function _stamp_pdf($id, $source, $signature_data = NULL, $signature_page = 0, $signature_x = 0, $signature_y = 0, $signature_width = 0, $qr_png = NULL)
    {
        $autoload = FCPATH . 'vendor/autoload.php';
        if (!is_file($autoload)) show_error('Library PDF belum tersedia. Jalankan composer install pada server aplikasi.', 500);
        require_once $autoload;

        $signature_path = NULL;
        $signature_info = NULL;
        if ($signature_data !== NULL) {
            $encoded_signature = substr($signature_data, strlen('data:image/png;base64,'));
            if (strlen($encoded_signature) > 1400000) {
                $this->session->set_flashdata('message', 'Ukuran gambar tanda tangan terlalu besar. Silakan hapus dan buat ulang tanda tangan.');
                redirect('surat_direktur/proses/' . $id);
                exit;
            }
            $signature_bytes = base64_decode($encoded_signature, TRUE);
            $signature_info = $signature_bytes === FALSE ? FALSE : getimagesizefromstring($signature_bytes);
            if ($signature_info === FALSE || $signature_info[2] !== IMAGETYPE_PNG || $signature_info[0] > 2000 || $signature_info[1] > 1000) {
                $this->session->set_flashdata('message', 'Gambar tanda tangan tidak valid. Silakan buat ulang tanda tangan.');
                redirect('surat_direktur/proses/' . $id);
                exit;
            }
            $signature_path = tempnam(sys_get_temp_dir(), 'surat-ttd-');
            if ($signature_path === FALSE || file_put_contents($signature_path, $signature_bytes) === FALSE) {
                if ($signature_path !== FALSE && is_file($signature_path)) unlink($signature_path);
                show_error('Gambar tanda tangan tidak dapat diproses.', 500);
            }
        }

        $qr_path = NULL;
        if ($qr_png !== NULL) {
            $qr_path = tempnam(sys_get_temp_dir(), 'surat-qr-');
            if ($qr_path === FALSE || file_put_contents($qr_path, $qr_png) === FALSE) {
                if ($qr_path !== FALSE && is_file($qr_path)) unlink($qr_path);
                $qr_path = NULL;
            }
        }

        $output_dir = FCPATH . 'uploads/surat/' . (int) $id . '/final/';
        if (!is_dir($output_dir) && !mkdir($output_dir, 0755, TRUE) && !is_dir($output_dir)) {
            if ($signature_path !== NULL && is_file($signature_path)) unlink($signature_path);
            if ($qr_path !== NULL && is_file($qr_path)) unlink($qr_path);
            show_error('Folder penyimpanan dokumen tanda tangan tidak dapat dibuat.', 500);
        }
        $relative_path = 'uploads/surat/' . (int) $id . '/final/' . bin2hex(random_bytes(16)) . '.pdf';
        $output_path = FCPATH . $relative_path;

        $processing_error = NULL;
        try {
            $pdf = new \setasign\Fpdi\Fpdi();
            // Matikan page break otomatis agar teks QR di area bawah halaman tidak
            // memicu penambahan halaman baru oleh FPDF saat memanggil Cell().
            $pdf->SetAutoPageBreak(false);
            $page_count = $pdf->setSourceFile($source);
            if ($page_count < 1) throw new \RuntimeException('PDF tidak memiliki halaman.');
            if ($signature_data !== NULL && ($signature_page < 1 || $signature_page > $page_count)) {
                throw new \RuntimeException('Halaman tanda tangan tidak ditemukan di dokumen PDF.');
            }
            for ($page_number = 1; $page_number <= $page_count; $page_number++) {
                $template = $pdf->importPage($page_number);
                $size = $pdf->getTemplateSize($template);
                $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));
                $pdf->useTemplate($template);

                if ($signature_data !== NULL && $page_number === $signature_page) {
                    $image_width = $signature_width * $size['width'];
                    $image_height = $image_width * $signature_info[1] / $signature_info[0];
                    $x = $signature_x * $size['width'];
                    $y = $signature_y * $size['height'];
                    if ($x + $image_width > $size['width'] || $y + $image_height > $size['height']) {
                        throw new \RuntimeException('Posisi tanda tangan keluar dari batas halaman PDF.');
                    }
                    $pdf->Image($signature_path, $x, $y, $image_width, $image_height, 'PNG');
                }

                if ($qr_path !== NULL && $page_number === $page_count) {
                    $qr_size = 22.0;
                    $margin = 10.0;
                    if ($qr_size + 2 * $margin > $size['width']) $qr_size = max(12.0, $size['width'] - 2 * $margin);
                    if ($qr_size + 2 * $margin > $size['height']) $qr_size = max(12.0, $size['height'] - 2 * $margin);
                    $qr_x = $size['width'] - $margin - $qr_size;
                    $qr_y = $size['height'] - $margin - $qr_size - 5.0;
                    $pdf->Image($qr_path, $qr_x, $qr_y, $qr_size, $qr_size, 'PNG');
                    $pdf->SetFont('Helvetica', '', 6);
                    $pdf->SetTextColor(90, 90, 90);
                    $pdf->SetXY($qr_x - 6.0, $qr_y + $qr_size);
                    $pdf->Cell($qr_size + 6.0, 5.0, 'Pindai untuk verifikasi keaslian', 0, 0, 'C');
                    $pdf->SetTextColor(0, 0, 0);
                }
            }
            $pdf->Output('F', $output_path);
        } catch (\Throwable $error) {
            if (is_file($output_path)) unlink($output_path);
            log_message('error', 'Gagal membuat dokumen tanda tangan surat ' . (int) $id . ': ' . $error->getMessage());
            $processing_error = $error->getMessage();
        } finally {
            if ($signature_path !== NULL && is_file($signature_path)) unlink($signature_path);
            if ($qr_path !== NULL && is_file($qr_path)) unlink($qr_path);
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
