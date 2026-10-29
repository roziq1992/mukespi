<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_sekretaris extends CI_Controller
{
    /** Alasan kegagalan terakhir penempelan QR (ditampilkan ke pengguna). */
    private $qr_error = '';

    private function _qr_err($pesan)
    {
        $this->qr_error = $pesan;
        log_message('error', 'QR: ' . $pesan);
    }

    private function _qr_alasan()
    {
        return $this->qr_error !== '' ? $this->qr_error : 'tidak diketahui';
    }

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

    /**
     * Gambar QR sebagai kotak-kotak vektor langsung di PDF (tanpa GD/cURL/internet).
     */
    private function _gambar_qr($pdf, $matrix, $x, $y, $size)
    {
        $n = count($matrix);
        $quiet = 2; // margin putih (dalam satuan modul)
        $mod = $size / ($n + 2 * $quiet);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect($x, $y, $size, $size, 'F');
        $pdf->SetFillColor(0, 0, 0);
        for ($r = 0; $r < $n; $r++) {
            $c = 0;
            while ($c < $n) {
                if ($matrix[$r][$c]) {
                    $start = $c;
                    while ($c < $n && $matrix[$r][$c]) $c++;
                    // +0.02 mm tumpang tindih tipis agar tidak muncul garis halus antar kotak
                    $pdf->Rect($x + ($quiet + $start) * $mod, $y + ($quiet + $r) * $mod, ($c - $start) * $mod + 0.02, $mod + 0.02, 'F');
                } else {
                    $c++;
                }
            }
        }
        $pdf->SetFillColor(0, 0, 0);
    }

    /**
     * Tempel QR verifikasi di kanan bawah halaman terakhir PDF (cara yang sama dengan Surat_direktur).
     * Hasilnya disimpan sebagai file BARU, file asli tidak ditimpa.
     * Return path relatif file baru, atau FALSE jika gagal (alasan ada di $this->qr_error).
     */
    private function _stempel_qr($id_surat, $file_path_relative, $kode_verifikasi)
    {
        $this->qr_error = '';
        $source = FCPATH . ltrim($file_path_relative, '/\\');
        if (!is_file($source)) {
            $this->_qr_err('file sumber tidak ditemukan: ' . $source);
            return FALSE;
        }

        $autoload = FCPATH . 'vendor/autoload.php';
        if (!is_file($autoload)) {
            $this->_qr_err('vendor/autoload.php tidak ada (jalankan composer install)');
            return FALSE;
        }
        require_once $autoload;
        if (!class_exists('setasign\\Fpdi\\Fpdi')) {
            $this->_qr_err('class setasign\\Fpdi\\Fpdi tidak ditemukan di vendor');
            return FALSE;
        }

        $url = site_url('surat/verifikasi/' . (int) $id_surat . '/' . $kode_verifikasi);
        try {
            $matrix = Surat_qr_encoder::matrix($url);
        } catch (\Throwable $e) {
            $this->_qr_err('gagal membuat QR: ' . $e->getMessage());
            return FALSE;
        }

        $output_dir = FCPATH . 'uploads/surat/' . (int) $id_surat . '/final/';
        if (!is_dir($output_dir) && !mkdir($output_dir, 0755, TRUE) && !is_dir($output_dir)) {
            $this->_qr_err('gagal membuat folder ' . $output_dir);
            return FALSE;
        }
        $relative = 'uploads/surat/' . (int) $id_surat . '/final/' . bin2hex(random_bytes(16)) . '.pdf';
        $output_path = FCPATH . $relative;

        $ok = FALSE;
        try {
            $pdf = new \setasign\Fpdi\Fpdi();
            // Matikan page break otomatis agar teks di dekat tepi bawah tidak menambah halaman baru
            $pdf->SetAutoPageBreak(FALSE);
            $page_count = $pdf->setSourceFile($source);
            if ($page_count < 1) throw new \RuntimeException('PDF tidak memiliki halaman.');

            for ($page_number = 1; $page_number <= $page_count; $page_number++) {
                $template = $pdf->importPage($page_number);
                $size = $pdf->getTemplateSize($template);
                $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));
                $pdf->useTemplate($template);

                if ($page_number === $page_count) {
                    $qr_size = 26.0;
                    $margin = 10.0;
                    if ($qr_size + 2 * $margin > $size['width']) $qr_size = max(12.0, $size['width'] - 2 * $margin);
                    if ($qr_size + 2 * $margin > $size['height']) $qr_size = max(12.0, $size['height'] - 2 * $margin);
                    $qr_x = $size['width'] - $margin - $qr_size;
                    $qr_y = $size['height'] - $margin - $qr_size - 5.0;
                    $this->_gambar_qr($pdf, $matrix, $qr_x, $qr_y, $qr_size);
                    $pdf->SetFont('Helvetica', '', 6);
                    $pdf->SetTextColor(90, 90, 90);
                    $pdf->SetXY($qr_x - 6.0, $qr_y + $qr_size);
                    $pdf->Cell($qr_size + 6.0, 5.0, 'Pindai untuk verifikasi keaslian', 0, 0, 'C');
                    $pdf->SetTextColor(0, 0, 0);
                }
            }
            $pdf->Output('F', $output_path);
            $ok = TRUE;
        } catch (\Throwable $e) {
            $this->_qr_err('gagal tempel QR ke PDF surat ' . (int) $id_surat . ': ' . $e->getMessage());
            if (is_file($output_path)) @unlink($output_path);
        }
        return $ok ? $relative : FALSE;
    }

    /**
     * Halaman diagnosa penempelan QR. Buka: surat_sekretaris/tes_qr
     */
    public function tes_qr()
    {
        $baris = array();
        $baris[] = 'PHP versi            : ' . PHP_VERSION;
        $autoload = FCPATH . 'vendor/autoload.php';
        $baris[] = 'vendor/autoload.php  : ' . (is_file($autoload) ? 'ADA' : 'TIDAK ADA  -> jalankan: composer install');
        if (is_file($autoload)) require_once $autoload;
        $baris[] = 'FPDI v2 (Fpdi)       : ' . (class_exists('setasign\\Fpdi\\Fpdi') ? 'ADA' : 'TIDAK ADA');
        $baris[] = 'Folder uploads/surat : ' . (is_writable(FCPATH . 'uploads/surat') ? 'BISA DITULIS' : 'TIDAK BISA DITULIS / TIDAK ADA');
        try {
            $m = Surat_qr_encoder::matrix(site_url('surat/verifikasi/0/tes'));
            $baris[] = 'Pembuat QR internal  : OK (' . count($m) . 'x' . count($m) . ' modul)';
        } catch (\Throwable $e) {
            $baris[] = 'Pembuat QR internal  : GAGAL - ' . $e->getMessage();
        }
        header('Content-Type: text/plain; charset=utf-8');
        echo implode("\n", $baris) . "\n";
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

        // Tempel QR verifikasi ke PDF (hasil berupa file baru), lalu pakai file baru itu
        $file_asli = FCPATH . $file_path;
        $stamped = $this->_stempel_qr($id, $file_path, $kode_verif);
        $stempel_ok = ($stamped !== FALSE);
        if ($stempel_ok) {
            $file_path = $stamped;
            $this->Surat_model->update($id, array('file_ber_nomor' => $file_path, 'file_final' => $file_path, 'updated_at' => date('Y-m-d H:i:s')));
        }

        if ($stempel_ok) {
            log_surat($id, 'Registrasi dokumen lama', 'Dokumen lama (sudah ditandatangani) diregistrasi dengan nomor surat manual: ' . $nomor . '. Langsung berkode tanpa proses nomor. QR verifikasi ditempel di file PDF.');
        } else {
            log_surat($id, 'Registrasi dokumen lama', 'Dokumen lama (sudah ditandatangani) diregistrasi dengan nomor surat manual: ' . $nomor . '. Langsung berkode tanpa proses nomor. PERINGATAN: QR verifikasi GAGAL ditempel di file PDF. Penyebab: ' . $this->_qr_alasan());
        }

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
            if ($stempel_ok) @unlink(FCPATH . $file_path);
            show_error('Gagal meregistrasi dokumen lama.', 500);
        }
        if ($stempel_ok) @unlink($file_asli); // file asli tidak dipakai lagi

        $pesan = 'Dokumen lama berhasil diregistrasi. Nomor Surat: ' . $nomor . ' (Sudah berkode)';
        if (!$stempel_ok) {
            $pesan .= ' - TETAPI QR gagal ditempel ke PDF. Penyebab: ' . $this->_qr_alasan();
        }
        $this->session->set_flashdata('message', $pesan);
        redirect('surat/detail/' . $id);
    }
}


/**
 * Pembuat QR Code sederhana (mode byte, koreksi error level M, versi 1-10 = maks 213 byte).
 * Mandiri, tanpa library eksternal. Dipakai untuk menggambar QR verifikasi di PDF.
 * Mengikuti algoritma baku QR Code (model 2).
 */
class Surat_qr_encoder
{
    private static $ECC_PER_BLOCK = array(10, 16, 26, 18, 24, 16, 18, 22, 22, 26);
    private static $NUM_BLOCKS    = array(1, 1, 1, 2, 2, 4, 4, 4, 5, 5);

    private $version;
    private $size;
    private $modules;
    private $isFn;

    /** Return array 2 dimensi [baris][kolom] berisi TRUE (gelap) / FALSE (terang). */
    public static function matrix($text)
    {
        $data = ($text === '') ? array() : array_values(unpack('C*', $text));
        for ($ver = 1; $ver <= 10; $ver++) {
            $capBits = self::dataCodewords($ver) * 8;
            $ccBits = $ver <= 9 ? 8 : 16;
            if (4 + $ccBits + count($data) * 8 <= $capBits) {
                $enc = new self($ver, $data);
                return $enc->modules;
            }
        }
        throw new RuntimeException('Teks terlalu panjang untuk QR (maks 213 byte).');
    }

    private static function numRawDataModules($ver)
    {
        $r = (16 * $ver + 128) * $ver + 64;
        if ($ver >= 2) {
            $numAlign = (int) ($ver / 7) + 2;
            $r -= (25 * $numAlign - 10) * $numAlign - 55;
            if ($ver >= 7) $r -= 36;
        }
        return $r;
    }

    private static function dataCodewords($ver)
    {
        return (int) (self::numRawDataModules($ver) / 8) - self::$ECC_PER_BLOCK[$ver - 1] * self::$NUM_BLOCKS[$ver - 1];
    }

    private function __construct($ver, $data)
    {
        $this->version = $ver;
        $this->size = $ver * 4 + 17;
        $this->modules = array_fill(0, $this->size, array_fill(0, $this->size, FALSE));
        $this->isFn = array_fill(0, $this->size, array_fill(0, $this->size, FALSE));
        $this->drawFunctionPatterns();
        $all = $this->addEccAndInterleave($this->makeDataCodewords($data));
        $this->drawCodewords($all);

        $best = 0;
        $min = PHP_INT_MAX;
        for ($m = 0; $m < 8; $m++) {
            $this->applyMask($m);
            $this->drawFormatBits($m);
            $p = $this->penalty();
            if ($p < $min) { $min = $p; $best = $m; }
            $this->applyMask($m); // batalkan
        }
        $this->applyMask($best);
        $this->drawFormatBits($best);
    }

    private function setFn($x, $y, $dark)
    {
        $this->modules[$y][$x] = (bool) $dark;
        $this->isFn[$y][$x] = TRUE;
    }

    private function drawFunctionPatterns()
    {
        $size = $this->size;
        for ($i = 0; $i < $size; $i++) {
            $this->setFn(6, $i, $i % 2 == 0);
            $this->setFn($i, 6, $i % 2 == 0);
        }
        $this->drawFinder(3, 3);
        $this->drawFinder($size - 4, 3);
        $this->drawFinder(3, $size - 4);

        $pos = $this->alignPositions();
        $n = count($pos);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if (($i == 0 && $j == 0) || ($i == 0 && $j == $n - 1) || ($i == $n - 1 && $j == 0)) continue;
                $this->drawAlign($pos[$i], $pos[$j]);
            }
        }
        $this->drawFormatBits(0);
        $this->drawVersion();
    }

    private function drawFinder($cx, $cy)
    {
        for ($dy = -4; $dy <= 4; $dy++) {
            for ($dx = -4; $dx <= 4; $dx++) {
                $dist = max(abs($dx), abs($dy));
                $xx = $cx + $dx;
                $yy = $cy + $dy;
                if ($xx >= 0 && $xx < $this->size && $yy >= 0 && $yy < $this->size) {
                    $this->setFn($xx, $yy, $dist != 2 && $dist != 4);
                }
            }
        }
    }

    private function drawAlign($cx, $cy)
    {
        for ($dy = -2; $dy <= 2; $dy++) {
            for ($dx = -2; $dx <= 2; $dx++) {
                $this->setFn($cx + $dx, $cy + $dy, max(abs($dx), abs($dy)) != 1);
            }
        }
    }

    private function alignPositions()
    {
        $ver = $this->version;
        if ($ver == 1) return array();
        $num = (int) ($ver / 7) + 2;
        $step = (int) (($ver * 8 + $num * 3 + 5) / ($num * 4 - 4)) * 2;
        $res = array(6);
        for ($pos = $this->size - 7; count($res) < $num; $pos -= $step) {
            array_splice($res, 1, 0, array($pos));
        }
        return $res;
    }

    private function drawFormatBits($mask)
    {
        $size = $this->size;
        $data = $mask; // level M = 0
        $rem = $data;
        for ($i = 0; $i < 10; $i++) $rem = ($rem << 1) ^ (($rem >> 9) * 0x537);
        $bits = (($data << 10) | $rem) ^ 0x5412;

        for ($i = 0; $i <= 5; $i++) $this->setFn(8, $i, (($bits >> $i) & 1) == 1);
        $this->setFn(8, 7, (($bits >> 6) & 1) == 1);
        $this->setFn(8, 8, (($bits >> 7) & 1) == 1);
        $this->setFn(7, 8, (($bits >> 8) & 1) == 1);
        for ($i = 9; $i < 15; $i++) $this->setFn(14 - $i, 8, (($bits >> $i) & 1) == 1);

        for ($i = 0; $i < 8; $i++) $this->setFn($size - 1 - $i, 8, (($bits >> $i) & 1) == 1);
        for ($i = 8; $i < 15; $i++) $this->setFn(8, $size - 15 + $i, (($bits >> $i) & 1) == 1);
        $this->setFn(8, $size - 8, TRUE);
    }

    private function drawVersion()
    {
        $ver = $this->version;
        if ($ver < 7) return;
        $rem = $ver;
        for ($i = 0; $i < 12; $i++) $rem = ($rem << 1) ^ (($rem >> 11) * 0x1F25);
        $bits = ($ver << 12) | $rem;
        for ($i = 0; $i < 18; $i++) {
            $bit = (($bits >> $i) & 1) == 1;
            $a = $this->size - 11 + $i % 3;
            $b = (int) ($i / 3);
            $this->setFn($a, $b, $bit);
            $this->setFn($b, $a, $bit);
        }
    }

    private function appendBits(&$bits, $val, $len)
    {
        for ($i = $len - 1; $i >= 0; $i--) $bits[] = ($val >> $i) & 1;
    }

    private function makeDataCodewords($data)
    {
        $ver = $this->version;
        $bits = array();
        $this->appendBits($bits, 4, 4); // mode byte
        $this->appendBits($bits, count($data), $ver <= 9 ? 8 : 16);
        foreach ($data as $b) $this->appendBits($bits, $b, 8);

        $cap = self::dataCodewords($ver) * 8;
        $this->appendBits($bits, 0, min(4, $cap - count($bits)));
        $this->appendBits($bits, 0, (8 - count($bits) % 8) % 8);
        for ($pad = 0xEC; count($bits) < $cap; $pad ^= 0xEC ^ 0x11) $this->appendBits($bits, $pad, 8);

        $bytes = array();
        for ($i = 0; $i < count($bits); $i += 8) {
            $v = 0;
            for ($j = 0; $j < 8; $j++) $v = ($v << 1) | $bits[$i + $j];
            $bytes[] = $v;
        }
        return $bytes;
    }

    private function addEccAndInterleave($data)
    {
        $ver = $this->version;
        $numBlocks = self::$NUM_BLOCKS[$ver - 1];
        $eccLen = self::$ECC_PER_BLOCK[$ver - 1];
        $raw = (int) (self::numRawDataModules($ver) / 8);
        $numShort = $numBlocks - $raw % $numBlocks;
        $shortLen = (int) ($raw / $numBlocks);

        $div = $this->rsDivisor($eccLen);
        $blocks = array();
        $k = 0;
        for ($i = 0; $i < $numBlocks; $i++) {
            $len = $shortLen - $eccLen + ($i < $numShort ? 0 : 1);
            $dat = array_slice($data, $k, $len);
            $k += $len;
            $ecc = $this->rsRemainder($dat, $div);
            if ($i < $numShort) $dat[] = 0;
            $blocks[] = array_merge($dat, $ecc);
        }
        $result = array();
        $total = count($blocks[0]);
        for ($i = 0; $i < $total; $i++) {
            foreach ($blocks as $j => $block) {
                if ($i != $shortLen - $eccLen || $j >= $numShort) $result[] = $block[$i];
            }
        }
        return $result;
    }

    private function rsMul($x, $y)
    {
        $z = 0;
        for ($i = 7; $i >= 0; $i--) {
            $z = ($z << 1) ^ (($z >> 7) * 0x11D);
            $z ^= (($y >> $i) & 1) * $x;
        }
        return $z;
    }

    private function rsDivisor($degree)
    {
        $result = array_fill(0, $degree, 0);
        $result[$degree - 1] = 1;
        $root = 1;
        for ($i = 0; $i < $degree; $i++) {
            for ($j = 0; $j < $degree; $j++) {
                $result[$j] = $this->rsMul($result[$j], $root);
                if ($j + 1 < $degree) $result[$j] ^= $result[$j + 1];
            }
            $root = $this->rsMul($root, 0x02);
        }
        return $result;
    }

    private function rsRemainder($data, $div)
    {
        $result = array_fill(0, count($div), 0);
        foreach ($data as $b) {
            $factor = $b ^ array_shift($result);
            $result[] = 0;
            foreach ($div as $i => $coef) $result[$i] ^= $this->rsMul($coef, $factor);
        }
        return $result;
    }

    private function drawCodewords($data)
    {
        $size = $this->size;
        $total = count($data) * 8;
        $i = 0;
        for ($right = $size - 1; $right >= 1; $right -= 2) {
            if ($right == 6) $right = 5;
            for ($vert = 0; $vert < $size; $vert++) {
                for ($j = 0; $j < 2; $j++) {
                    $x = $right - $j;
                    $upward = (($right + 1) & 2) == 0;
                    $y = $upward ? $size - 1 - $vert : $vert;
                    if (!$this->isFn[$y][$x] && $i < $total) {
                        $this->modules[$y][$x] = ((($data[$i >> 3] >> (7 - ($i & 7))) & 1) == 1);
                        $i++;
                    }
                }
            }
        }
    }

    private function applyMask($mask)
    {
        $size = $this->size;
        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                switch ($mask) {
                    case 0: $inv = ($x + $y) % 2 == 0; break;
                    case 1: $inv = $y % 2 == 0; break;
                    case 2: $inv = $x % 3 == 0; break;
                    case 3: $inv = ($x + $y) % 3 == 0; break;
                    case 4: $inv = ((int) ($x / 3) + (int) ($y / 2)) % 2 == 0; break;
                    case 5: $inv = $x * $y % 2 + $x * $y % 3 == 0; break;
                    case 6: $inv = ($x * $y % 2 + $x * $y % 3) % 2 == 0; break;
                    default: $inv = (($x + $y) % 2 + $x * $y % 3) % 2 == 0; break;
                }
                if (!$this->isFn[$y][$x] && $inv) $this->modules[$y][$x] = !$this->modules[$y][$x];
            }
        }
    }

    private function penalty()
    {
        $size = $this->size;
        $m = $this->modules;
        $res = 0;

        for ($y = 0; $y < $size; $y++) {
            $runColor = FALSE;
            $runX = 0;
            $h = array_fill(0, 7, 0);
            for ($x = 0; $x < $size; $x++) {
                if ($m[$y][$x] == $runColor) {
                    $runX++;
                    if ($runX == 5) $res += 3;
                    elseif ($runX > 5) $res++;
                } else {
                    $this->fpAdd($runX, $h);
                    if (!$runColor) $res += $this->fpCount($h) * 40;
                    $runColor = $m[$y][$x];
                    $runX = 1;
                }
            }
            $res += $this->fpEnd($runColor, $runX, $h) * 40;
        }
        for ($x = 0; $x < $size; $x++) {
            $runColor = FALSE;
            $runY = 0;
            $h = array_fill(0, 7, 0);
            for ($y = 0; $y < $size; $y++) {
                if ($m[$y][$x] == $runColor) {
                    $runY++;
                    if ($runY == 5) $res += 3;
                    elseif ($runY > 5) $res++;
                } else {
                    $this->fpAdd($runY, $h);
                    if (!$runColor) $res += $this->fpCount($h) * 40;
                    $runColor = $m[$y][$x];
                    $runY = 1;
                }
            }
            $res += $this->fpEnd($runColor, $runY, $h) * 40;
        }
        for ($y = 0; $y < $size - 1; $y++) {
            for ($x = 0; $x < $size - 1; $x++) {
                $c = $m[$y][$x];
                if ($c == $m[$y][$x + 1] && $c == $m[$y + 1][$x] && $c == $m[$y + 1][$x + 1]) $res += 3;
            }
        }
        $dark = 0;
        foreach ($m as $row) foreach ($row as $v) if ($v) $dark++;
        $total = $size * $size;
        $k = (int) ceil(abs($dark * 20 - $total * 10) / $total) - 1;
        $res += $k * 10;
        return $res;
    }

    private function fpCount($h)
    {
        $n = $h[1];
        $core = $n > 0 && $h[2] == $n && $h[3] == $n * 3 && $h[4] == $n && $h[5] == $n;
        return (($core && $h[0] >= $n * 4 && $h[6] >= $n) ? 1 : 0) + (($core && $h[6] >= $n * 4 && $h[0] >= $n) ? 1 : 0);
    }

    private function fpEnd($color, $len, &$h)
    {
        if ($color) {
            $this->fpAdd($len, $h);
            $len = 0;
        }
        $len += $this->size;
        $this->fpAdd($len, $h);
        return $this->fpCount($h);
    }

    private function fpAdd($len, &$h)
    {
        if ($h[0] == 0) $len += $this->size;
        array_pop($h);
        array_unshift($h, $len);
    }
}