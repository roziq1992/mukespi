<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_sekretaris extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        if (!in_array((int) $this->session->userdata('role_id'), array(1, 5), TRUE)) redirect('auth/blocked');
        $this->load->helper(array('surat', 'download'));
        $this->load->model(array('Surat_model', 'Surat_log_model', 'Surat_lampiran_model'));
        $this->load->library(array('upload', 'email', 'form_validation'));
    }

    private function filters()
    {
        return array('q' => trim($this->input->get('q', TRUE)), 'status' => $this->input->get('status', TRUE), 'jenis' => $this->input->get('jenis', TRUE), 'mulai' => $this->input->get('mulai', TRUE), 'sampai' => $this->input->get('sampai', TRUE));
    }

    public function index()
    {
        $data = array('title' => 'Antrian Sekretaris', 'surat' => $this->Surat_model->list_data('sekretaris', $this->session->userdata('id'), $this->filters()), 'filters' => $this->filters(), 'pending' => $this->Surat_model->pending_count('sekretaris', $this->session->userdata('id')));
        $this->load->view('template/header', $data);
        $this->load->view('surat/list_sekretaris', $data);
        $this->load->view('template/footer');
    }

    public function proses($id)
    {
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        if ($surat->jenis === 'disposisi') {
            $this->session->set_flashdata('message', 'Surat disposisi langsung diproses oleh Direktur dan tidak perlu diberi nomor oleh sekretaris.');
            redirect('surat_sekretaris'); return;
        }
        if ($surat->status === 'Diajukan') {
            $this->Surat_model->update($id, array('status' => 'Diproses Sekretaris', 'updated_at' => date('Y-m-d H:i:s')));
            log_surat($id, 'Surat diproses sekretaris', 'Surat masuk ke antrian sekretaris.');
            $surat = $this->Surat_model->get($id);
        }
        $data = array('title' => 'Proses Nomor Surat', 'surat' => $surat, 'lampiran' => $this->Surat_lampiran_model->by_surat($id));
        $this->load->view('template/header', $data);
        $this->load->view('surat/proses_nomor', $data);
        $this->load->view('template/footer');
    }

    public function simpan($id)
    {
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        if ($surat->jenis === 'disposisi') {
            $this->session->set_flashdata('message', 'Surat disposisi langsung diproses oleh Direktur dan tidak perlu diberi nomor oleh sekretaris.');
            redirect('surat_sekretaris'); return;
        }
        $nomor = trim($this->input->post('no_surat', TRUE));
        if ($this->input->post('generate')) $nomor = $this->_generate($surat->jenis);
        if ($nomor === '') { $this->session->set_flashdata('message', 'Nomor surat wajib diisi.'); redirect('surat_sekretaris/proses/' . $id); return; }
        $teruskan = (bool) $this->input->post('teruskan');
        $ada_upload = !empty($_FILES['file_ber_nomor']['name']);
        if ($teruskan && $ada_upload && strtolower(pathinfo($_FILES['file_ber_nomor']['name'], PATHINFO_EXTENSION)) !== 'pdf') {
            $this->session->set_flashdata('message', 'Dokumen harus berformat PDF agar dapat dipratinjau dan ditandatangani Direktur.');
            redirect('surat_sekretaris/proses/' . $id);
            return;
        }
        if ($teruskan && !$ada_upload && (empty($surat->file_ber_nomor) || strtolower(pathinfo($surat->file_ber_nomor, PATHINFO_EXTENSION)) !== 'pdf')) {
            $this->session->set_flashdata('message', 'Unggah dokumen bernomor PDF sebelum meneruskan surat ke Direktur.');
            redirect('surat_sekretaris/proses/' . $id);
            return;
        }
        $status = $teruskan ? 'Diteruskan ke Direktur' : 'Sudah Diberi Nomor';
        $update = array('no_surat' => $nomor, 'status' => $status, 'updated_at' => date('Y-m-d H:i:s'));
        if ($ada_upload) {
            $path = FCPATH . 'uploads/surat/' . $id . '/final/';
            if (!is_dir($path)) mkdir($path, 0755, TRUE);
            $this->upload->initialize(array('upload_path' => $path, 'allowed_types' => $teruskan ? 'pdf' : 'doc|docx|pdf', 'max_size' => 5120, 'encrypt_name' => TRUE));
            if (!$this->upload->do_upload('file_ber_nomor')) { $this->session->set_flashdata('message', $this->upload->display_errors()); redirect('surat_sekretaris/proses/' . $id); return; }
            $update['file_ber_nomor'] = 'uploads/surat/' . $id . '/final/' . $this->upload->data('file_name');
        }
        if (!$this->Surat_model->update($id, $update)) show_error('Surat gagal diperbarui.', 500);
        log_surat($id, 'Nomor surat diproses', 'Nomor: ' . $nomor . '. Status: ' . $status);
        if ($teruskan) {
            $direktur = $this->db->where('role_id', 4)->order_by('id', 'ASC')->get('users')->result();
            $pesan = 'Terdapat surat yang telah diberi nomor dan diteruskan untuk ditindaklanjuti.<br><br><strong>Nomor:</strong> ' . htmlspecialchars($nomor, ENT_QUOTES, 'UTF-8') . '<br><strong>Perihal:</strong> ' . htmlspecialchars($surat->perihal, ENT_QUOTES, 'UTF-8') . '<br><strong>Tujuan:</strong> ' . htmlspecialchars($surat->tujuan, ENT_QUOTES, 'UTF-8');
            foreach ($direktur as $user) surat_kirim_notifikasi($user->email, $user->name, 'Surat Masuk untuk Direktur', $pesan, $id);
        }
        $this->session->set_flashdata('message', 'Nomor surat berhasil diproses.');
        redirect((int) $this->session->userdata('role_id') === 1 ? 'surat' : 'surat_sekretaris');
    }

    private function _generate($jenis)
    {
        $year = date('Y');
        $count = $this->db->where('jenis', $jenis)->where('YEAR(created_at)', $year)->where('no_surat IS NOT NULL', NULL, FALSE)->count_all_results('surat') + 1;
        $romawi = array(1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII');
        $kode_jenis = $jenis === 'internal' ? 'INT' : 'EXT';
        return str_pad($count, 3, '0', STR_PAD_LEFT) . '/RSA/' . $kode_jenis . '/' . $romawi[(int) date('n')] . '/' . $year;
    }

    private function _stempel_qr($id_surat, $file_path_relative, $kode_verifikasi)
    {
        $full_path = FCPATH . ltrim($file_path_relative, '/\\');
        if (!is_file($full_path)) {
            return FALSE;
        }

        $url = site_url('surat/verifikasi/' . (int)$id_surat . '/' . $kode_verifikasi);

        $qr_dir = FCPATH . 'uploads/surat/' . (int)$id_surat . '/qr/';
        if (!is_dir($qr_dir)) {
            @mkdir($qr_dir, 0755, TRUE);
        }
        $qr_file = $qr_dir . 'qr_' . (int)$id_surat . '_' . time() . '.png';

        $qr_data = @file_get_contents('https://api.qrserver.com/v1/create-qr-code/?size=160x160&format=png&data=' . rawurlencode($url));
        if ($qr_data === FALSE) {
            $qr_data = @file_get_contents('https://chart.googleapis.com/chart?chs=160x160&cht=qr&chl=' . rawurlencode($url) . '&choe=UTF-8');
        }
        if ($qr_data === FALSE) {
            return FALSE;
        }
        file_put_contents($qr_file, $qr_data);

        $fpdi_loaded = FALSE;
        if (class_exists('FPDI')) {
            $fpdi_loaded = TRUE;
        } else {
            $paths = array(
                APPPATH . 'third_party/fpdi/src/autoload.php',
                APPPATH . 'third_party/fpdi/autoload.php',
                APPPATH . 'libraries/fpdi.php',
                APPPATH . 'third_party/FPDI/fpdi.php'
            );
            foreach ($paths as $p) {
                if (is_file($p)) {
                    @include_once $p;
                    break;
                }
            }
            if (class_exists('FPDI')) {
                $fpdi_loaded = TRUE;
            }
        }

        if (!$fpdi_loaded) {
            @unlink($qr_file);
            return FALSE;
        }

        try {
            $pdf = new FPDI();
            $pageCount = $pdf->setSourceFile($full_path);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $tplId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($tplId);
                $orientation = isset($size['orientation']) ? $size['orientation'] : 'P';
                if ($orientation == 'L' || $size['width'] > $size['height']) {
                    $pdf->AddPage('L', array($size['width'], $size['height']));
                } else {
                    $pdf->AddPage('P', array($size['width'], $size['height']));
                }
                $pdf->useTemplate($tplId);
                if ($pageNo == 1 && is_file($qr_file)) {
                    $x = $size['width'] - 38;
                    $y = $size['height'] - 38;
                    if ($x < 5) $x = 5;
                    if ($y < 5) $y = 5;
                    $pdf->Image($qr_file, $x, $y, 32, 32);
                    $pdf->SetFont('Helvetica', '', 5);
                    $pdf->SetTextColor(80, 80, 80);
                    $pdf->SetXY($x, $y + 33);
                    $pdf->Cell(32, 4, 'VERIFIKASI RSA', 0, 0, 'C');
                }
            }
            $pdf->Output($full_path, 'F');
            @unlink($qr_file);
            return TRUE;
        } catch (Exception $e) {
            @unlink($qr_file);
            log_message('error', 'Gagal tempel QR ke PDF registrasi lama: ' . $e->getMessage());
            return FALSE;
        }
    }

    public function registrasi_lama()
    {
        $data = array('title' => 'Registrasi Dokumen Lama (Sudah Ditandatangani)');
        $this->load->view('template/header', $data);
        $this->load->view('surat/registrasi_lama', $data);
        $this->load->view('template/footer');
    }

    public function registrasi_lama_simpan()
    {
        $this->form_validation->set_rules('jenis', 'Jenis surat', 'required|in_list[internal,eksternal]');
        $this->form_validation->set_rules('no_surat', 'Nomor Surat', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('perihal', 'Perihal', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('tanggal_pengajuan', 'Tanggal surat', 'required');
        if (!$this->form_validation->run()) {
            $this->registrasi_lama();
            return;
        }
        if (empty($_FILES['file_ttd']['name'])) {
            $this->session->set_flashdata('message', 'File PDF dokumen yang sudah ditandatangani wajib diunggah.');
            redirect('surat_sekretaris/registrasi_lama');
            return;
        }
        $ext = strtolower(pathinfo($_FILES['file_ttd']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            $this->session->set_flashdata('message', 'Dokumen yang sudah ditandatangani wajib berformat PDF.');
            redirect('surat_sekretaris/registrasi_lama');
            return;
        }

        $jenis = $this->input->post('jenis', TRUE);
        $nomor = trim($this->input->post('no_surat', TRUE));
        $base = FCPATH . 'uploads/surat/';

        $cek = $this->db->where('no_surat', $nomor)->get('surat')->num_rows();
        if ($cek > 0) {
            $this->session->set_flashdata('message', 'Nomor surat tersebut sudah terdaftar di sistem.');
            redirect('surat_sekretaris/registrasi_lama');
            return;
        }

        $kode_verif = bin2hex(random_bytes(16));

        $data_surat = array(
            'jenis' => $jenis,
            'perihal' => $this->input->post('perihal', TRUE),
            'tujuan' => $this->input->post('tujuan', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE),
            'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan', TRUE),
            'id_pemohon' => $this->session->userdata('id'),
            'no_surat' => $nomor,
            'kode_verifikasi' => $kode_verif,
            'status' => 'Ditandatangani',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->trans_start();
        $id = $this->Surat_model->insert($data_surat);

        $path_final_dir = $base . $id . '/final/';
        if (!is_dir($path_final_dir)) mkdir($path_final_dir, 0755, TRUE);
        $config = array('upload_path' => $path_final_dir, 'allowed_types' => 'pdf', 'max_size' => 10240, 'encrypt_name' => TRUE);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('file_ttd')) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', $this->upload->display_errors());
            redirect('surat_sekretaris/registrasi_lama');
            return;
        }
        $file_name = $this->upload->data('file_name');
        $file_path = 'uploads/surat/' . $id . '/final/' . $file_name;
        $this->Surat_model->update($id, array('file_ber_nomor' => $file_path, 'file_final' => $file_path, 'updated_at' => date('Y-m-d H:i:s')));

        $this->_stempel_qr($id, $file_path, $kode_verif);

        log_surat($id, 'Registrasi dokumen lama', 'Dokumen lama (sudah ditandatangani) diregistrasi dengan nomor surat manual: ' . $nomor . '. Langsung berkode tanpa proses nomor. QR verifikasi ditempel di file PDF.');

        $folder_lamp = $base . $id . '/lampiran/';
        if (!is_dir($folder_lamp)) mkdir($folder_lamp, 0755, TRUE);
        if (!empty($_FILES['lampiran']['name'][0])) {
            foreach ($_FILES['lampiran']['name'] as $key => $name) {
                if (empty($name)) continue;
                $_FILES['one_lampiran'] = array('name' => $_FILES['lampiran']['name'][$key], 'type' => $_FILES['lampiran']['type'][$key], 'tmp_name' => $_FILES['lampiran']['tmp_name'][$key], 'error' => $_FILES['lampiran']['error'][$key], 'size' => $_FILES['lampiran']['size'][$key]);
                $this->upload->initialize(array('upload_path' => $folder_lamp, 'allowed_types' => 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png', 'max_size' => 10240, 'encrypt_name' => TRUE));
                if ($this->upload->do_upload('one_lampiran')) {
                    $f = $this->upload->data();
                    $this->Surat_lampiran_model->insert(array('id_surat' => $id, 'nama_file' => $name, 'path_file' => 'uploads/surat/' . $id . '/lampiran/' . $f['file_name'], 'uploaded_by' => $this->session->userdata('id'), 'created_at' => date('Y-m-d H:i:s')));
                }
            }
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            show_error('Gagal meregistrasi dokumen lama.', 500);
        }

        $this->session->set_flashdata('message', 'Dokumen lama berhasil diregistrasi. Nomor Surat: ' . $nomor . ' (Sudah berkode)');
        redirect('surat/detail/' . $id);
    }
}
