<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Pegawai_qr
 *
 * QR Code data pegawai. NIK TIDAK lagi muncul di URL hasil pemindaian:
 * setiap pegawai memiliki token acak (tabel `pegawai_qr_token`) dan token
 * itulah yang di-encode menjadi QR sekaligus menjadi kunci pencarian.
 *
 * - t($token)         : PUBLIK. Hasil pemindaian QR, menampilkan data
 *                       pegawai TANPA login. Token 32 hex mustahil ditebak,
 *                       jadi NIK tidak lagi bisa dienumerasi lewat URL.
 * - cetak($nik)       : login admin/HRD. Cetak + unduh PNG satu pegawai.
 * - cetak_semua()     : login admin/HRD. Grid QR massal, bisa difilter.
 * - token_baru($nik)  : login admin/HRD. Membatalkan QR lama, buat token baru.
 *
 * Data yang ditampilkan pada halaman publik dibatasi pada kolom identitas &
 * kepegawaian (tanpa NPWP, keluarga, anak, dan alamat lengkap).
 */
class Pegawai_qr extends CI_Controller
{
    // jumlah token yang dibuat otomatis dalam satu siklon permintaan
    private $_token_baru = 0;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pegawai_model');
    }

    public function index()
    {
        redirect('pegawai');
    }

    // ================= PUBLIK =================

    public function t($token = '')
    {
        $this->_anti_index();

        $token = $this->_bersihkan_token($token);
        $pegawai = NULL;
        $error = NULL;

        if ($token === '') {
            $error = 'Tautan QR tidak lengkap. Silakan pindai ulang QR code pegawai.';
        } else {
            $row = $this->db->get_where('pegawai_qr_token', array('token' => $token))->row();
            if (!$row) {
                $error = 'Data pegawai tidak ditemukan. QR code ini mungkin sudah tidak berlaku.';
            } else {
                $data = $this->Pegawai_model->get_by_nik($row->nik);
                if (!$data) {
                    // token masih ada, tetapi data pegawai sudah hilang
                    $error = 'Data pegawai tidak ditemukan. Hubungi HRD.';
                } else {
                    $pegawai = (object) $data;
                }
            }
        }

        $this->load->view('pegawai_qr/detail', array(
            'pegawai' => $pegawai,
            'error'   => $error,
            'tgl'     => date('d/m/Y H:i'),
        ));
    }

    // ================= ADMIN / HRD =================

    public function cetak($nik = '')
    {
        $this->_require_admin();

        $pegawai = $this->_cari_pegawai($nik);
        $error = NULL;
        $token = '';

        if ($pegawai === FALSE) {
            $error = 'Data pegawai tidak ditemukan.';
        } elseif ($pegawai === 'ganda') {
            $error = 'NIK ini tercatat lebih dari satu kali. Perbaiki data pegawai terlebih dahulu.';
        } else {
            $token = $this->_token_untuk($pegawai->nik);
            if ($token === '') {
                $error = 'Gagal membuat token QR untuk pegawai ini.';
            }
        }

        $this->load->view('pegawai_qr/cetak', array(
            'pegawai'  => is_object($pegawai) ? $pegawai : NULL,
            'error'    => $error,
            'token'    => $token,
            'qr_text'  => $token !== '' ? site_url('pegawai_qr/t/' . $token) : '',
        ));
    }

    public function cetak_semua()
    {
        $this->_require_admin();

        $q = urldecode($this->input->get('q', TRUE));
        $status = $this->input->get('status', TRUE);
        if ($status === NULL || $status === '') {
            $status = 'aktif';
        }

        $daftar = array();
        $lewati = 0;
        foreach ($this->Pegawai_model->get_all() as $p) {
            if (!$this->_boleh_qr($p)) {
                $lewati++;
                continue;
            }
            if ($status !== 'semua' && $p->status !== $status) {
                continue;
            }
            if ($q !== '' && stripos($p->nama . ' ' . $p->nip . ' ' . $p->unit_kerja . ' ' . $p->jabatan, $q) === FALSE) {
                continue;
            }
            // token dibuat otomatis bila belum pernah ada
            $daftar[] = array(
                'pegawai' => $p,
                'token'   => $this->_token_untuk($p->nik),
            );
        }

        $this->load->view('pegawai_qr/cetak_semua', array(
            'daftar'   => $daftar,
            'q'        => $q,
            'status'   => $status,
            'lewati'   => $lewati,
        ));
    }

    // Batalkan QR lama (token dihapus) lalu buat token baru.
    public function token_baru($nik = '')
    {
        $this->_require_admin();

        $pegawai = $this->_cari_pegawai($nik);
        if ($pegawai === FALSE || $pegawai === 'ganda') {
            $this->session->set_flashdata('message', 'Data pegawai tidak ditemukan.');
            redirect(site_url('pegawai'));
            return;
        }

        $this->db->where('nik', $pegawai->nik)->delete('pegawai_qr_token');
        $baru = $this->_token_untuk($pegawai->nik, TRUE);

        $this->session->set_flashdata('message', $baru !== ''
            ? 'QR code baru berhasil dibuat. QR yang lama sudah tidak berlaku.'
            : 'Gagal membuat QR code baru.');

        redirect(site_url('pegawai_qr/cetak/' . $pegawai->nik));
    }

    // ================= helper =================

    /**
     * Normalisasi NIK (16 digit) lalu ambil data pegawai.
     * Mengembalikan object, FALSE (tidak ketemu), atau string 'ganda'.
     * Dipakai oleh halaman internal yang sudah/login saja.
     */
    private function _cari_pegawai($nik)
    {
        $nik = preg_replace('/\D/', '', (string) $nik);
        if (strlen($nik) !== 16) {
            return FALSE;
        }

        $row = $this->Pegawai_model->get_by_nik($nik);
        if (!$row) {
            return FALSE;
        }

        // Pengaman bila (seandainya) NIK ganda: jangan tampilkan data ambigu.
        if ($this->db->where('nik', $nik)->count_all_results('pegawai') > 1) {
            return 'ganda';
        }

        return (object) $row;
    }

    /**
     * Ambil token milik seorang pegawai; buat bila belum ada.
     * Mengembalikan string 32 hex, atau '' bila gagal.
     */
    private function _token_untuk($nik, $paksa_baru = FALSE)
    {
        $nik = preg_replace('/\D/', '', (string) $nik);
        if (strlen($nik) !== 16) {
            return '';
        }

        if ($paksa_baru) {
            $this->db->where('nik', $nik)->delete('pegawai_qr_token');
        } else {
            $ada = $this->db->get_where('pegawai_qr_token', array('nik' => $nik))->row();
            if ($ada) {
                return $ada->token;
            }
        }

        // token acak 16 byte -> 32 hex, mustahil ditebak.
        for ($i = 0; $i < 5; $i++) {
            $token = bin2hex(random_bytes(16));
            $bentrok = $this->db->get_where('pegawai_qr_token', array('token' => $token))->row();
            if ($bentrok) {
                continue;
            }
            $ok = $this->db->insert('pegawai_qr_token', array(
                'nik'        => $nik,
                'token'      => $token,
                'created_at' => date('Y-m-d H:i:s'),
            ));
            if ($ok) {
                $this->_token_baru++;
                return $token;
            }
        }

        return '';
    }

    // Hanya terima token 32 hex.
    private function _bersihkan_token($token)
    {
        $token = strtolower(trim((string) $token));
        return preg_match('/^[0-9a-f]{32}$/', $token) ? $token : '';
    }

    // QR hanya dibuat bila NIK ada (16 digit).
    private function _boleh_qr($p)
    {
        return strlen(preg_replace('/\D/', '', (string) $p->nik)) === 16;
    }

    private function _require_admin()
    {
        is_logged_in();
        if (!in_array((int) $this->session->userdata('role_id'), array(1, 6), true)) {
            redirect('auth/blocked');
        }
    }

    // Cegah halaman publik di indeks mesin pencari.
    private function _anti_index()
    {
        header('X-Robots-Tag: noindex, nofollow', true);
    }
}
/* End of file Pegawai_qr.php */
