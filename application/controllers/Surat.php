<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat extends CI_Controller
{
    private $role_sekretaris = 5;
    private $role_direktur = 4;
    private $status_bisa_edit = array('Diajukan', 'Ditolak');

    public function __construct()
    {
        parent::__construct();
        // Halaman verifikasi QR dokumen bersifat publik (hasil pindai tanpa login).
        if (strtolower($this->router->fetch_method()) !== 'verifikasi') {
            is_logged_in();
        }
        $this->load->helper(array('surat', 'download'));
        $this->load->model(array('Surat_model', 'Surat_lampiran_model', 'Surat_disposisi_model', 'Surat_log_model', 'Surat_hapus_log_model'));
        $this->load->library(array('form_validation', 'upload'));
    }

    public function verifikasi($id = 0, $kode = '')
    {
        header('X-Robots-Tag: noindex, nofollow', true);
        $surat = $this->Surat_model->get((int) $id);
        $kode_valid = $surat && !empty($surat->kode_verifikasi)
            && is_string($kode) && $kode !== ''
            && hash_equals((string) $surat->kode_verifikasi, (string) $kode);
        $sah = $kode_valid && in_array($surat->status, array('Ditandatangani', 'Didisposisikan', 'Selesai'), TRUE);

        $penandatangan = NULL;
        $tanggal_ttd = NULL;
        if ($kode_valid) {
            $log = $this->db->select('l.created_at, u.name AS user_name')
                ->from('surat_log l')
                ->join('users u', 'u.id = l.id_user', 'left')
                ->where('l.id_surat', (int) $id)
                ->where('l.aksi', 'Surat ditandatangani')
                ->order_by('l.id', 'DESC')
                ->limit(1)
                ->get()->row();
            if ($log) {
                $penandatangan = $log->user_name;
                $tanggal_ttd = $log->created_at;
            }
        }

        $this->load->view('surat/verifikasi', array(
            'surat' => $kode_valid ? $surat : NULL,
            'sah' => $sah,
            'penandatangan' => $penandatangan,
            'tanggal_ttd' => $tanggal_ttd,
            'tgl' => date('d/m/Y H:i'),
        ));
    }

    private function role()
    {
        $id = (int) $this->session->userdata('role_id');
        return $id === 1 ? 'admin' : ($id === $this->role_sekretaris ? 'sekretaris' : ($id === $this->role_direktur ? 'direktur' : 'user'));
    }

    private function filters()
    {
        return array('q' => trim($this->input->get('q', TRUE)), 'status' => $this->input->get('status', TRUE), 'jenis' => $this->input->get('jenis', TRUE), 'mulai' => $this->input->get('mulai', TRUE), 'sampai' => $this->input->get('sampai', TRUE));
    }

    public function index()
    {
        $role = $this->role();
        if ($role === 'sekretaris') redirect('surat_sekretaris');
        if ($role === 'direktur') redirect('surat_direktur');
        $list_role = $role === 'admin' ? 'admin' : 'user';
        $data = array('title' => $role === 'admin' ? 'Kelola Semua Surat' : 'Surat Saya', 'surat' => $this->Surat_model->list_data($list_role, $this->session->userdata('id'), $this->filters()), 'filters' => $this->filters(), 'pending' => $this->Surat_model->pending_count($list_role, $this->session->userdata('id')), 'is_admin' => $role === 'admin', 'action' => $role === 'admin' ? 'admin' : '');
        $this->load->view('template/header', $data); $this->load->view('surat/list_user', $data); $this->load->view('template/footer');
    }

    public function create()
    {
        $data = array('title' => 'Pengajuan Surat Baru', 'can_disposisi' => in_array((int) $this->session->userdata('role_id'), array(1, $this->role_sekretaris), TRUE));
        $this->load->view('template/header', $data); $this->load->view('surat/form_pengajuan', $data); $this->load->view('template/footer');
    }

    /**
     * Proses otomatis surat disposisi (pengganti langkah sekretaris).
     * Dokumen PDF dari pengaju disalin ke folder final surat dan dicatat sebagai file_ber_nomor,
     * sehingga halaman Direktur membacanya persis seperti surat yang diproses sekretaris.
     * File draft asli tidak diubah. Return path relatif file salinan, atau FALSE bila gagal.
     */
    private function _salin_ke_final($id, $draft_relatif)
    {
        if (!$draft_relatif) return FALSE;
        $sumber = FCPATH . ltrim(str_replace('\\', '/', $draft_relatif), '/');
        if (!is_file($sumber)) return FALSE;
        $dir = FCPATH . 'uploads/surat/' . (int) $id . '/final/';
        if (!is_dir($dir) && !mkdir($dir, 0755, TRUE) && !is_dir($dir)) return FALSE;
        $nama = bin2hex(random_bytes(16)) . '.pdf';
        if (!copy($sumber, $dir . $nama)) return FALSE;
        return 'uploads/surat/' . (int) $id . '/final/' . $nama;
    }

    public function store()
    {
        $this->form_validation->set_rules('jenis', 'Jenis surat', 'required|in_list[internal,eksternal,disposisi]');
        $this->form_validation->set_rules('perihal', 'Perihal', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('tanggal_pengajuan', 'Tanggal', 'required');
        if (!$this->form_validation->run()) { $this->create(); return; }
        $jenis = $this->input->post('jenis', TRUE);
        if ($jenis === 'disposisi' && !in_array((int) $this->session->userdata('role_id'), array(1, $this->role_sekretaris), TRUE)) {
            $this->session->set_flashdata('message', 'Hanya sekretaris dan admin yang dapat mengajukan surat disposisi.');
            redirect('surat/create'); return;
        }
        if (empty($_FILES['file_draft']['name'])) { $this->session->set_flashdata('message', 'Draft surat wajib diunggah.'); redirect('surat/create'); return; }
        // Disposisi langsung ke Direktur tanpa sekretaris, jadi dokumennya harus PDF agar bisa dipratinjau dan ditandatangani.
        if ($jenis === 'disposisi' && strtolower(pathinfo($_FILES['file_draft']['name'], PATHINFO_EXTENSION)) !== 'pdf') {
            $this->session->set_flashdata('message', 'Dokumen surat disposisi harus berformat PDF agar dapat dipratinjau dan ditandatangani Direktur.');
            redirect('surat/create'); return;
        }
        $base = FCPATH . 'uploads/surat/';
        $status_awal = $jenis === 'disposisi' ? 'Diteruskan ke Direktur' : 'Diajukan';
        $data = array('jenis' => $jenis, 'perihal' => $this->input->post('perihal', TRUE), 'tujuan' => $this->input->post('tujuan', TRUE), 'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan', TRUE), 'keterangan' => $this->input->post('keterangan', TRUE), 'id_pemohon' => $this->session->userdata('id'), 'status' => $status_awal, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'));
        // Surat disposisi (undangan eksternal) tidak diberi nomor surat.
        if ($jenis === 'disposisi') $data['no_surat'] = '-';
        if (!empty($_FILES['file_draft']['name'])) {
            $config = array('upload_path' => $base . 'pending/', 'allowed_types' => $jenis === 'disposisi' ? 'pdf' : 'doc|docx|pdf', 'max_size' => 5120, 'encrypt_name' => TRUE);
            if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0755, TRUE);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('file_draft')) { $this->session->set_flashdata('message', $this->upload->display_errors()); redirect('surat/create'); return; }
            $data['file_draft'] = 'uploads/surat/pending/' . $this->upload->data('file_name');
        }
        $this->db->trans_start(); $id = $this->Surat_model->insert($data);
        log_surat($id, 'Pengajuan dibuat', $jenis === 'disposisi' ? 'Surat disposisi diajukan dan langsung diteruskan ke Direktur.' : 'Surat diajukan oleh pemohon.');
        $folder = $base . $id . '/lampiran/'; if (!is_dir($folder)) mkdir($folder, 0755, TRUE);
        if (!empty($_FILES['lampiran']['name'][0])) foreach ($_FILES['lampiran']['name'] as $key => $name) { $_FILES['one_lampiran'] = array('name' => $_FILES['lampiran']['name'][$key], 'type' => $_FILES['lampiran']['type'][$key], 'tmp_name' => $_FILES['lampiran']['tmp_name'][$key], 'error' => $_FILES['lampiran']['error'][$key], 'size' => $_FILES['lampiran']['size'][$key]); $this->upload->initialize(array('upload_path' => $folder, 'allowed_types' => 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png', 'max_size' => 10240, 'encrypt_name' => TRUE)); if ($this->upload->do_upload('one_lampiran')) { $f = $this->upload->data(); $this->Surat_lampiran_model->insert(array('id_surat' => $id, 'nama_file' => $name, 'path_file' => 'uploads/surat/' . $id . '/lampiran/' . $f['file_name'], 'uploaded_by' => $this->session->userdata('id'), 'created_at' => date('Y-m-d H:i:s'))); } }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) show_error('Pengajuan surat gagal disimpan.', 500);

        // Proses otomatis disposisi: dokumen diteruskan ke Direktur seperti hasil kerja sekretaris.
        $peringatan = '';
        if ($jenis === 'disposisi') {
            $ber_nomor = $this->_salin_ke_final($id, $data['file_draft']);
            if ($ber_nomor !== FALSE) {
                $this->Surat_model->update($id, array('file_ber_nomor' => $ber_nomor, 'updated_at' => date('Y-m-d H:i:s')));
                log_surat($id, 'Diteruskan otomatis', 'Surat disposisi tanpa nomor. Dokumen disiapkan otomatis untuk ditinjau Direktur.');
            } else {
                log_message('error', 'Disposisi ' . (int) $id . ': gagal menyalin dokumen ke folder final.');
                log_surat($id, 'Peringatan', 'Dokumen disposisi gagal disiapkan otomatis untuk Direktur.');
                $peringatan = ' Namun dokumen gagal disiapkan otomatis untuk Direktur, hubungi admin.';
            }
        }

        $penerima = $this->db->where_in('role_id', array(4, 5))->order_by('id', 'ASC')->get('users')->result();
        // Tanda kurung pada ternary supaya detail surat tampil untuk kedua jenis.
        $pesan = ($jenis === 'disposisi'
                ? 'Terdapat pengajuan surat disposisi baru yang langsung diteruskan ke Direktur untuk ditindaklanjuti.<br><br>'
                : 'Terdapat pengajuan surat baru yang perlu ditindaklanjuti.<br><br>')
            . '<strong>Perihal:</strong> ' . htmlspecialchars($data['perihal'], ENT_QUOTES, 'UTF-8') . '<br>'
            . '<strong>Jenis:</strong> ' . htmlspecialchars(ucfirst($data['jenis']), ENT_QUOTES, 'UTF-8') . '<br>'
            . '<strong>Tujuan:</strong> ' . htmlspecialchars($data['tujuan'], ENT_QUOTES, 'UTF-8');
        surat_kirim_notifikasi_massal($penerima, 'Pemberitahuan Pengajuan Surat Baru', $pesan);
        $this->session->set_flashdata('message', 'Pengajuan surat berhasil dibuat.' . $peringatan); redirect('surat/detail/' . $id);
    }

    private function _boleh_edit($surat)
    {
        return (int) $surat->id_pemohon === (int) $this->session->userdata('id')
            && in_array($surat->status, $this->status_bisa_edit, TRUE);
    }

    public function edit($id)
    {
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        if (!$this->_boleh_edit($surat)) {
            show_error('Surat ini tidak dapat diedit. Pengajuan hanya dapat diperbaiki sebelum diproses atau setelah ditolak.', 403);
        }
        $data = array(
            'title' => 'Edit Pengajuan Surat',
            'surat' => $surat,
            'lampiran' => $this->Surat_lampiran_model->by_surat($id),
            'can_disposisi' => in_array((int) $this->session->userdata('role_id'), array(1, $this->role_sekretaris), TRUE)
        );
        $this->load->view('template/header', $data);
        $this->load->view('surat/form_pengajuan', $data);
        $this->load->view('template/footer');
    }

    public function update($id)
    {
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        if (!$this->_boleh_edit($surat)) {
            show_error('Surat ini tidak dapat diedit. Pengajuan hanya dapat diperbaiki sebelum diproses atau setelah ditolak.', 403);
        }
        if (strtolower($this->input->method()) !== 'post') show_error('Metode request tidak valid.', 405);

        $this->form_validation->set_rules('jenis', 'Jenis surat', 'required|in_list[internal,eksternal,disposisi]');
        $this->form_validation->set_rules('perihal', 'Perihal', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('tanggal_pengajuan', 'Tanggal', 'required');
        if (!$this->form_validation->run()) { $this->edit($id); return; }

        $jenis = $this->input->post('jenis', TRUE);
        if ($jenis === 'disposisi' && !in_array((int) $this->session->userdata('role_id'), array(1, $this->role_sekretaris), TRUE)) {
            $this->session->set_flashdata('message', 'Hanya sekretaris dan admin yang dapat mengajukan surat disposisi.');
            redirect('surat/edit/' . $id); return;
        }

        $draft_baru = !empty($_FILES['file_draft']['name']);
        $old_draft = $surat->file_draft;
        if (!$draft_baru && empty($old_draft)) {
            $this->session->set_flashdata('message', 'Draft surat wajib diunggah.');
            redirect('surat/edit/' . $id); return;
        }
        // Disposisi wajib PDF (langsung ke Direktur tanpa sekretaris).
        if ($jenis === 'disposisi') {
            $nama_cek = $draft_baru ? $_FILES['file_draft']['name'] : (string) $old_draft;
            if (strtolower(pathinfo($nama_cek, PATHINFO_EXTENSION)) !== 'pdf') {
                $this->session->set_flashdata('message', 'Dokumen surat disposisi harus berformat PDF. Unggah ulang dokumen dalam format PDF.');
                redirect('surat/edit/' . $id); return;
            }
        }

        $ditolak = $surat->status === 'Ditolak';
        $status_awal = $jenis === 'disposisi' ? 'Diteruskan ke Direktur' : 'Diajukan';
        $data = array(
            'jenis' => $jenis,
            'perihal' => $this->input->post('perihal', TRUE),
            'tujuan' => $this->input->post('tujuan', TRUE),
            'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE),
            'status' => $status_awal,
            'updated_at' => date('Y-m-d H:i:s')
        );
        if ($ditolak) {
            $data['alasan_tolak'] = NULL;
            $data['no_surat'] = NULL;
            $data['file_ber_nomor'] = NULL;
            $data['file_final'] = NULL;
            $data['kode_verifikasi'] = NULL;
        }
        // Disposisi selalu bernomor "-". Bila jenis diganti dari disposisi ke jenis biasa,
        // tanda "-" dan salinan dokumen otomatis dibersihkan agar sekretaris memprosesnya dari awal.
        $salin_ulang = FALSE;
        $ber_nomor_dibuang = NULL;
        if ($jenis === 'disposisi') {
            $data['no_surat'] = '-';
            $salin_ulang = $draft_baru || $ditolak || empty($surat->file_ber_nomor);
        } else {
            if ($ditolak || $surat->no_surat === '-') $data['no_surat'] = NULL;
            if ($surat->jenis === 'disposisi' && !$ditolak && !empty($surat->file_ber_nomor)) {
                $data['file_ber_nomor'] = NULL;
                $ber_nomor_dibuang = $surat->file_ber_nomor;
            }
        }

        $base = FCPATH . 'uploads/surat/';
        if ($draft_baru) {
            $config = array('upload_path' => $base . 'pending/', 'allowed_types' => $jenis === 'disposisi' ? 'pdf' : 'doc|docx|pdf', 'max_size' => 5120, 'encrypt_name' => TRUE);
            if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0755, TRUE);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('file_draft')) { $this->session->set_flashdata('message', $this->upload->display_errors()); redirect('surat/edit/' . $id); return; }
            $data['file_draft'] = 'uploads/surat/pending/' . $this->upload->data('file_name');
        }

        // Proses otomatis disposisi: salin dokumen ke folder final sebagai file_ber_nomor.
        $ber_nomor_baru = FALSE;
        if ($salin_ulang) {
            $ber_nomor_baru = $this->_salin_ke_final($id, $draft_baru ? $data['file_draft'] : $old_draft);
            if ($ber_nomor_baru === FALSE) {
                if ($draft_baru) $this->_delete_upload_file($data['file_draft']);
                $this->session->set_flashdata('message', 'Dokumen disposisi gagal disiapkan untuk Direktur. Silakan coba lagi.');
                redirect('surat/edit/' . $id); return;
            }
            $data['file_ber_nomor'] = $ber_nomor_baru;
        }

        $folder = $base . (int) $id . '/lampiran/';
        if (!is_dir($folder)) mkdir($folder, 0755, TRUE);
        $lampiran_baru = array();
        if (!empty($_FILES['lampiran']['name'][0])) {
            foreach ($_FILES['lampiran']['name'] as $key => $name) {
                $_FILES['one_lampiran'] = array('name' => $_FILES['lampiran']['name'][$key], 'type' => $_FILES['lampiran']['type'][$key], 'tmp_name' => $_FILES['lampiran']['tmp_name'][$key], 'error' => $_FILES['lampiran']['error'][$key], 'size' => $_FILES['lampiran']['size'][$key]);
                $this->upload->initialize(array('upload_path' => $folder, 'allowed_types' => 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png', 'max_size' => 10240, 'encrypt_name' => TRUE));
                if ($this->upload->do_upload('one_lampiran')) {
                    $f = $this->upload->data();
                    $lampiran_baru[] = array('id_surat' => (int) $id, 'nama_file' => $name, 'path_file' => 'uploads/surat/' . (int) $id . '/lampiran/' . $f['file_name'], 'uploaded_by' => $this->session->userdata('id'), 'created_at' => date('Y-m-d H:i:s'));
                }
            }
        }

        $lampiran_hapus = array();
        $hapus_ids = $this->input->post('hapus_lampiran');
        if (is_array($hapus_ids)) {
            foreach ($hapus_ids as $lampiran_id) {
                if (!ctype_digit((string) $lampiran_id)) continue;
                $row = $this->Surat_lampiran_model->get((int) $lampiran_id);
                if ($row && (int) $row->id_surat === (int) $id) $lampiran_hapus[] = $row;
            }
        }

        $this->db->trans_start();
        $this->Surat_model->update($id, $data);
        foreach ($lampiran_hapus as $row) $this->Surat_lampiran_model->delete($row->id);
        foreach ($lampiran_baru as $row) $this->Surat_lampiran_model->insert($row);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            foreach ($lampiran_baru as $row) $this->_delete_upload_file($row['path_file']);
            if ($draft_baru) $this->_delete_upload_file($data['file_draft']);
            if ($ber_nomor_baru !== FALSE) $this->_delete_upload_file($ber_nomor_baru);
            show_error('Perubahan pengajuan surat gagal disimpan.', 500);
        }

        if ($draft_baru && $old_draft && ($ditolak || ($old_draft !== $surat->file_final && $old_draft !== $surat->file_ber_nomor))) {
            $this->_delete_upload_file($old_draft);
        }
        if ($ditolak) {
            $keep = array(isset($data['file_draft']) ? $data['file_draft'] : $old_draft, $old_draft);
            foreach (array($surat->file_ber_nomor, $surat->file_final) as $file) {
                if ($file && !in_array($file, $keep, TRUE)) $this->_delete_upload_file($file);
            }
        } elseif ($salin_ulang && !empty($surat->file_ber_nomor)) {
            // Salinan otomatis lama diganti salinan baru.
            $this->_delete_upload_file($surat->file_ber_nomor);
        }
        if ($ber_nomor_dibuang) $this->_delete_upload_file($ber_nomor_dibuang);
        foreach ($lampiran_hapus as $row) $this->_delete_upload_file($row->path_file);

        $catatan_log = array('Data pengajuan diperbarui oleh pemohon.');
        if ($draft_baru) $catatan_log[] = 'Draft surat diganti.';
        if ($lampiran_baru) $catatan_log[] = count($lampiran_baru) . ' lampiran ditambahkan.';
        if ($lampiran_hapus) $catatan_log[] = count($lampiran_hapus) . ' lampiran dihapus.';
        if ($salin_ulang) $catatan_log[] = 'Dokumen disposisi disiapkan otomatis untuk Direktur.';
        if ($ditolak) $catatan_log[] = 'Surat diajukan ulang setelah ditolak.';
        log_surat($id, $ditolak ? 'Pengajuan diajukan ulang' : 'Pengajuan diperbarui', implode(' ', $catatan_log));

        if ($ditolak) {
            $penerima = $this->db->where_in('role_id', array(4, 5))->order_by('id', 'ASC')->get('users')->result();
            $pesan = 'Terdapat pengajuan surat yang telah diperbaiki dan diajukan ulang untuk ditindaklanjuti.<br><br>'
                . '<strong>Perihal:</strong> ' . htmlspecialchars($data['perihal'], ENT_QUOTES, 'UTF-8') . '<br>'
                . '<strong>Jenis:</strong> ' . htmlspecialchars(ucfirst($data['jenis']), ENT_QUOTES, 'UTF-8') . '<br>'
                . '<strong>Tujuan:</strong> ' . htmlspecialchars($data['tujuan'], ENT_QUOTES, 'UTF-8');
            surat_kirim_notifikasi_massal($penerima, 'Pengajuan Surat Diperbaiki', $pesan);
        }

        $this->session->set_flashdata('message', 'Pengajuan surat berhasil diperbarui.');
        redirect('surat/detail/' . $id);
    }

    /**
     * Sekali jalan: perbaiki surat disposisi LAMA (dibuat sebelum alur otomatis ini).
     * Hanya admin. Buka: surat/perbaiki_disposisi
     */
    public function perbaiki_disposisi()
    {
        if ((int) $this->session->userdata('role_id') !== 1) show_error('Akses ditolak.', 403);
        $rows = $this->db->where('jenis', 'disposisi')
            ->group_start()->where('file_ber_nomor IS NULL', NULL, FALSE)->or_where('file_ber_nomor', '')->group_end()
            ->get('surat')->result();
        $baris = array();
        foreach ($rows as $s) {
            $draft = (string) $s->file_draft;
            if ($draft === '' || strtolower(pathinfo($draft, PATHINFO_EXTENSION)) !== 'pdf') {
                $baris[] = '#' . $s->id . ' dilewati (draft kosong / bukan PDF)';
                continue;
            }
            $ber = $this->_salin_ke_final($s->id, $draft);
            if ($ber === FALSE) { $baris[] = '#' . $s->id . ' GAGAL menyalin'; continue; }
            $upd = array('file_ber_nomor' => $ber, 'updated_at' => date('Y-m-d H:i:s'));
            if ($s->no_surat === NULL || $s->no_surat === '') $upd['no_surat'] = '-';
            $this->Surat_model->update($s->id, $upd);
            $baris[] = '#' . $s->id . ' OK';
        }
        header('Content-Type: text/plain; charset=utf-8');
        echo count($rows) . " surat disposisi diperiksa\n" . implode("\n", $baris) . "\n";
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

    public function detail($id)
    {
        $surat = $this->Surat_model->get($id); if (!$surat) show_404();
        $role = $this->role(); $uid = (int) $this->session->userdata('id'); $allowed = $role !== 'user' || (int) $surat->id_pemohon === $uid || $this->db->where('id_surat', $id)->where('ke_user', $uid)->count_all_results('surat_disposisi') > 0; if (!$allowed) show_error('Anda tidak memiliki akses ke surat ini.', 403);
        $pemohon = $this->db->select('name')->where('id', $surat->id_pemohon)->get('users')->row();
        $surat->pemohon = $pemohon ? $pemohon->name : '-';
        $data = array('title' => 'Tracking Surat', 'surat' => $surat, 'lampiran' => $this->Surat_lampiran_model->by_surat($id), 'logs' => $this->Surat_log_model->by_surat($id), 'disposisi' => $this->Surat_disposisi_model->by_surat($id), 'role' => $role);
        $this->load->view('template/header', $data); $this->load->view('surat/detail', $data); $this->load->view('template/footer');
    }

    public function download($id, $type = 'draft', $file = '')
    {
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        if ($this->role() === 'user') {
            $uid = (int) $this->session->userdata('id');
            $boleh_akses = (int) $surat->id_pemohon === $uid
                || $this->db->where('id_surat', (int) $id)->where('ke_user', $uid)->count_all_results('surat_disposisi') > 0;
            if (!$boleh_akses) show_error('Anda tidak memiliki akses ke dokumen ini.', 403);
            if ($type !== 'final') show_error('Anda hanya dapat mengunduh dokumen final.', 403);
            if (!in_array($surat->status, array('Ditandatangani', 'Didisposisikan', 'Selesai'), TRUE)) {
                show_error('Dokumen final belum tersedia. Surat masih dalam proses atau ditolak.', 403);
            }
            $path = $surat->file_final ?: ($surat->file_ber_nomor ?: $surat->file_draft);
        } else {
            $path = $type === 'draft' ? $surat->file_draft : ($type === 'numbered' ? $surat->file_ber_nomor : ($surat->file_final ?: ($surat->file_ber_nomor ?: $surat->file_draft)));
        }
        if (!$path) show_404();
        if (!is_file(FCPATH . $path)) show_404();
        force_download(FCPATH . $path, NULL);
    }

    public function download_lampiran($id)
    {
        $row = $this->db->where('id', (int) $id)->get('surat_lampiran')->row();
        if (!$row || !is_file(FCPATH . $row->path_file)) show_404();
        $surat = $this->Surat_model->get($row->id_surat);
        if (!$surat) show_404();
        $role = $this->role(); $uid = (int) $this->session->userdata('id');
        if ($role === 'user' && (int) $surat->id_pemohon !== $uid) show_error('Akses ditolak.', 403);
        $this->_stream_lampiran($row, FALSE);
    }

    public function lihat_lampiran($id)
    {
        $row = $this->db->where('id', (int) $id)->get('surat_lampiran')->row();
        if (!$row || !is_file(FCPATH . $row->path_file)) show_404();
        $surat = $this->Surat_model->get($row->id_surat);
        if (!$surat) show_404();
        $role = $this->role(); $uid = (int) $this->session->userdata('id');
        if ($role === 'user' && (int) $surat->id_pemohon !== $uid) show_error('Akses ditolak.', 403);

        $ext = strtolower(pathinfo($row->nama_file, PATHINFO_EXTENSION));
        $this->_stream_lampiran($row, in_array($ext, array('pdf', 'jpg', 'jpeg', 'png'), TRUE));
    }

    private function _stream_lampiran($row, $inline)
    {
        $path = FCPATH . $row->path_file;
        $ext = strtolower(pathinfo($row->nama_file, PATHINFO_EXTENSION));
        $mimes = array('pdf' => 'application/pdf', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png');
        $nama = str_replace(array("\r", "\n", '"'), '', basename($row->nama_file));
        header('Content-Type: ' . ($inline && isset($mimes[$ext]) ? $mimes[$ext] : 'application/octet-stream'));
        header('Content-Disposition: ' . ($inline ? 'inline' : 'attachment') . '; filename="' . $nama . '"');
        header('Content-Length: ' . filesize($path));
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
    }

    public function hapus($id)
    {
        $role_id = (int) $this->session->userdata('role_id');
        if (!in_array($role_id, array(1, $this->role_sekretaris), TRUE)) show_error('Hanya admin dan sekretaris yang dapat menghapus surat.', 403);
        if (strtolower($this->input->method()) !== 'post') show_error('Metode request tidak valid.', 405);
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        $pemohon = $this->db->select('name')->where('id', (int) $surat->id_pemohon)->get('users')->row();
        $this->db->trans_start();
        $this->Surat_hapus_log_model->insert(array(
            'id_surat' => (int) $id,
            'perihal' => $surat->perihal,
            'jenis' => $surat->jenis,
            'no_surat' => $surat->no_surat,
            'status' => $surat->status,
            'id_pemohon' => (int) $surat->id_pemohon,
            'nama_pemohon' => $pemohon ? $pemohon->name : '-',
            'id_user' => (int) $this->session->userdata('id'),
            'nama_user' => (string) $this->session->userdata('name'),
            'role_id' => $role_id,
            'role_nama' => $role_id === 1 ? 'Admin' : 'Sekretaris',
            'keterangan' => 'Surat dihapus permanen beserta lampiran, disposisi, dan riwayatnya.',
            'created_at' => date('Y-m-d H:i:s')
        ));
        $this->db->where('id_surat', (int) $id)->delete('surat_lampiran');
        $this->db->where('id_surat', (int) $id)->delete('surat_disposisi');
        $this->db->where('id_surat', (int) $id)->delete('surat_log');
        $this->db->where('id', (int) $id)->delete('surat');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) show_error('Surat gagal dihapus.', 500);
        $folder = FCPATH . 'uploads/surat/' . (int) $id;
        if (is_dir($folder)) $this->_remove_folder($folder);
        $this->session->set_flashdata('message', 'Surat berhasil dihapus dan dicatat atas nama ' . html_escape($this->session->userdata('name')) . '.');
        redirect('surat');
    }

    public function hapus_log()
    {
        $role_id = (int) $this->session->userdata('role_id');
        if (!in_array($role_id, array(1, $this->role_sekretaris), TRUE)) show_error('Akses ditolak.', 403);
        $data = array('title' => 'Riwayat Hapus Surat', 'logs' => $this->Surat_hapus_log_model->list_all());
        $this->load->view('template/header', $data);
        $this->load->view('surat/hapus_log', $data);
        $this->load->view('template/footer');
    }

    private function _remove_folder($folder)
    {
        foreach (scandir($folder) as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $folder . DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->_remove_folder($path) : @unlink($path);
        }
        @rmdir($folder);
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