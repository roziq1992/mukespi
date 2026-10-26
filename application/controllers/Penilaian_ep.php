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
    // Hanya PDF dan JPG yang boleh jadi bukti. Daftar ini WAJIB sama dengan
    // $ekstensi_bukti dan atribut accept di view penilaian_ep_ep.php.
    private $ekstensi_bukti = array('pdf', 'jpg', 'jpeg');
    private $upload_folder = 'uploads/bukti_ep/';

    // >>> SESUAIKAN angka ini dengan role_id akun Surveior di tabel user kamu <<<
    private $ROLE_ID_SURVEIOR = 3;

    // role yang otomatis punya akses penuh ke semua pokja
    private $ROLE_ID_ADMIN = 1;

    function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Penilaian_ep_model');
        $this->load->model('User_pokja_ep_model');
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

    private function _is_admin()
    {
        return (int) $this->session->userdata('role_id') == $this->ROLE_ID_ADMIN;
    }

    // ================= AKSES PER POKJA =================
    // Admin & Surveior: akses penuh ke semua pokja.
    // User lain: akses diatur lewat tabel user_pokja_ep.
    //   - is_penilai = 1  -> boleh menilai skor + upload/hapus bukti + download
    //   - is_penilai = 0  -> hanya boleh membuka dokumen (tidak bisa download)
    //   - tidak di-set     -> hanya boleh melihat halaman dalam mode baca saja

    // daftar id_pokja tempat user ini boleh MENILAI (isi skor)
    private function _pokja_penilai_saya()
    {
        if ($this->_is_admin() || $this->_is_surveior()) {
            return NULL; // NULL = semua pokja
        }
        return $this->User_pokja_ep_model->get_penilai_pokja_ids_by_user(
            (int) $this->session->userdata('id')
        );
    }

    // daftar id_pokja tempat user ini di-set (baik Penilai maupun Lihat saja)
    private function _pokja_boleh_akses()
    {
        if ($this->_is_admin() || $this->_is_surveior()) {
            return NULL; // NULL = semua pokja
        }
        return $this->User_pokja_ep_model->get_pokja_ids_by_user(
            (int) $this->session->userdata('id')
        );
    }

    // user boleh menilai pokja $id_pokja?
    private function _boleh_nilai_pokja($id_pokja)
    {
        $list = $this->_pokja_penilai_saya();
        if ($list === NULL) return TRUE;
        return in_array(intval($id_pokja), $list, TRUE);
    }

    // user boleh DOWNLOAD dokumen pokja $id_pokja?
    // Hanya penilai pokja tsb yang boleh. User yang cuma di-set "lihat"
    // (atau tidak di-set sama sekali) tetap bisa membuka dokumen di browser,
    // tapi tidak bisa mengunduhnya.
    private function _boleh_download_pokja($id_pokja)
    {
        $list = $this->_pokja_penilai_saya();
        if ($list === NULL) return TRUE;
        return in_array(intval($id_pokja), $list, TRUE);
    }

    // Susun data untuk 2 grafik batang di halaman daftar pokja:
    //   1. Bukti Terupload — EP yang punya minimal 1 file
    //   2. Sudah Dinilai   — EP yang sudah punya skor (track mana pun)
    // Persentase dihitung terhadap total EP pokja tsb. Warna batang ditentukan
    // di frontend: >= 80% hijau, >= 30% kuning, di bawah 30% merah.
    private function _siapkan_data_grafik($periode)
    {
        $kosong = array('label' => array(), 'total_ep' => array(), 'upload' => array(), 'nilai' => array());
        if (!$periode) {
            return $kosong;
        }

        $rows = $this->Penilaian_ep_model->get_pokja_statistik_grafik($periode->id_periode);
        if (empty($rows)) {
            return $kosong;
        }

        $out = $kosong;
        foreach ($rows as $r) {
            $total_ep   = intval($r->total_ep);
            $upload     = intval($r->ep_ada_bukti);
            $nilai      = intval($r->ep_dinilai);

            $out['label'][]    = $r->bab;
            $out['total_ep'][] = $total_ep;
            $out['upload'][]   = $total_ep > 0 ? round($upload / $total_ep * 100, 1) : 0;
            $out['nilai'][]    = $total_ep > 0 ? round($nilai / $total_ep * 100, 1) : 0;
        }
        return $out;
    }

    // ==============================================================
    //  Cari id_pokja dari id_ep, dipakai endpoint AJAX supaya user
    //  tidak bisa bypass hak akses lewat crafted request.
    // ==============================================================
    private function _id_pokja_dari_ep($id_ep)
    {
        $row = $this->db
            ->select('p.id')
            ->from('elemen_penilaian ep')
            ->join('standar s', 's.id_standar = ep.id_standar')
            ->join('pokja p', 'p.bab = s.bab')
            ->where('ep.id_ep', intval($id_ep))
            ->limit(1)
            ->get()
            ->row();
        return $row ? intval($row->id) : 0;
    }

    // Ket. Dokumen: admin untuk semua pokja, pokja MRMIK untuk semua user.
    private function _boleh_dok_dukung($id_pokja)
    {
        if ($this->_is_admin()) return TRUE;

        $row = $this->db
            ->select('bab')
            ->from('pokja')
            ->where('id', intval($id_pokja))
            ->limit(1)
            ->get()
            ->row();

        return $row && strtoupper(trim((string) $row->bab)) === 'MRMIK';
    }

    // EP yang sudah dinonaktifkan (dan EP dari standar nonaktif) tidak boleh
    // bisa dibuka lewat crafted URL, misalnya penilaian_ep/pokja/MFK lalu
    // dipaksa akses id_ep yang nonaktif. Query daftar sudah menyaringnya,
    // tapi endpoint ini tetap perlu dijaga sendiri.
    private function _ep_tersedia($id_ep)
    {
        $row = $this->db
            ->select('ep.id_ep')
            ->from('elemen_penilaian ep')
            ->join('standar s', 's.id_standar = ep.id_standar')
            ->where('ep.id_ep', intval($id_ep))
            ->where('ep.active', 'Y')
            ->where('ep.tdd', 'N')
            ->where('s.active', 'Y')
            ->limit(1)
            ->get()
            ->row();
        return $row ? TRUE : FALSE;
    }

    // ================= HALAMAN =================

    public function index()
    {
        $periode = $this->_active_periode();
        $jenis   = $this->_jenis_penilaian_saya();

        $pokja_list = array();
        if ($periode) {
            $pokja_list = $this->Penilaian_ep_model->get_pokja_progress($periode->id_periode, $jenis);
            $pokja_nilai = $this->_pokja_penilai_saya();
            $pokja_akses = $this->_pokja_boleh_akses();
            $jml_nilai   = 0;
            $jml_akses   = 0;
            foreach ($pokja_list as $p) {
                $id_p = intval($p->id_pokja);
                $p->boleh_nilai    = ($pokja_nilai === NULL) ? TRUE : in_array($id_p, $pokja_nilai, TRUE);
                $p->boleh_akses    = ($pokja_akses === NULL) ? TRUE : in_array($id_p, $pokja_akses, TRUE);
                $p->boleh_download = ($pokja_nilai === NULL) ? TRUE : in_array($id_p, $pokja_nilai, TRUE);
                if ($p->boleh_nilai) $jml_nilai++;
                if ($p->boleh_akses) $jml_akses++;
            }
        }

        $data = array(
            'periode'         => $periode,
            'pokja_list'      => $pokja_list,
            'grafik_data'     => $this->_siapkan_data_grafik($periode),
            'is_surveior'     => $this->_is_surveior(),
            'is_admin'        => $this->_is_admin(),
            'jml_pokja_nilai' => $jml_nilai,
            'jml_pokja_akses' => $jml_akses,
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

        $id_pokja    = intval($pokja->id);
        $boleh_nilai = $this->_boleh_nilai_pokja($id_pokja);

        $data = array(
            'periode'         => $periode,
            'pokja'           => $pokja,
            'ep_list'         => $this->Penilaian_ep_model->get_ep_by_pokja($bab, $periode->id_periode),
            'jenis_saya'      => $this->_jenis_penilaian_saya(),
            'is_surveior'     => $this->_is_surveior(),
            'is_admin'        => $this->_is_admin(),
            'boleh_nilai'     => $boleh_nilai,
            'boleh_akses'     => TRUE,
            'boleh_download'  => $this->_boleh_download_pokja($id_pokja),
            'boleh_dok'       => $this->_boleh_dok_dukung($id_pokja),
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

    // ================= AJAX: KET. DOKUMEN / DATA DUKUNG =================
    // Menghasilkan saran data dukung per EP. Kalau API key Gemini tersedia,
    // saran dibuat oleh AI lalu di-cache. Kalau tidak, dipakai template
    // otomatis berbasis jenis bukti + kata kunci pada isi EP.

    public function data_dukung($id_ep = 0)
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-store');

        $id_ep = intval($id_ep);
        if (!$this->_ep_tersedia($id_ep)) {
            echo json_encode(array('status' => FALSE, 'message' => 'EP tidak tersedia'));
            return;
        }

        $konteks = $this->Penilaian_ep_model->get_ep_konteks($id_ep);
        if (!$konteks) {
            echo json_encode(array('status' => FALSE, 'message' => 'Data EP tidak ditemukan'));
            return;
        }

        $id_pokja   = $this->_id_pokja_dari_ep($id_ep);
        if (!$this->_boleh_dok_dukung($id_pokja)) {
            http_response_code(403);
            echo json_encode(array('status' => FALSE, 'message' => 'Fitur Ket. Dokumen hanya untuk admin atau pokja MRMIK.'));
            return;
        }

        $boleh_nilai = $this->_boleh_nilai_pokja($id_pokja);
        $refresh    = ($this->input->get('refresh') === '1') && $boleh_nilai;

        $this->config->load('gemini', FALSE, TRUE);
        $api_key = trim((string) $this->config->item('gemini_api_key'));

        $hash = sha1(implode('|', array(
            (string) $konteks->isi_ep,
            (string) $konteks->no_standar,
            (string) $konteks->isi_standar,
            (string) $konteks->jenis_bukti,
            (string) $konteks->skor_maks,
        )));

        $cache = $this->Penilaian_ep_model->get_data_dukung($id_ep);
        if (!$refresh && $cache && $cache->hash_ep === $hash) {
            $payload = json_decode($cache->konten, TRUE);
            // Cache template lama boleh di-upgrade sekali kalau API key
            // sekarang sudah diisi. Cache Gemini langsung dipakai.
            $upgrade = ($cache->sumber !== 'gemini' && $api_key !== '');
            if (is_array($payload) && !$upgrade) {
                echo json_encode(array_merge(array(
                    'status'     => TRUE,
                    'sumber'     => $cache->sumber,
                    'model'      => $cache->model,
                    'diperbarui' => $cache->updated_at,
                    'dari_cache' => TRUE,
                    'ai_aktif'   => $api_key !== '',
                ), $payload));
                return;
            }
        }

        $payload = $this->_generate_data_dukung_gemini($konteks);
        $sumber  = 'gemini';
        $model   = (string) $this->config->item('gemini_model');

        if ($payload === FALSE) {
            // Saat minta saran ulang tetapi AI tidak tersedia/gagal,
            // tampilkan saran tersimpan agar isi tidak hilang.
            if ($refresh && $cache && $cache->hash_ep === $hash) {
                $cached = json_decode($cache->konten, TRUE);
                if (is_array($cached)) {
                    echo json_encode(array_merge(array(
                        'status'     => TRUE,
                        'sumber'     => $cache->sumber,
                        'model'      => $cache->model,
                        'diperbarui' => $cache->updated_at,
                        'dari_cache' => TRUE,
                        'ai_aktif'   => $api_key !== '',
                    ), $cached));
                    return;
                }
            }

            $payload = $this->_data_dukung_template($konteks);
            $sumber  = 'template';
            $model   = NULL;
        } else {
            $this->Penilaian_ep_model->save_data_dukung($id_ep, array(
                'hash_ep'    => $hash,
                'konten'     => json_encode($payload, JSON_UNESCAPED_UNICODE),
                'sumber'     => $sumber,
                'model'      => $model,
                'updated_at' => date('Y-m-d H:i:s'),
            ));
        }

        echo json_encode(array_merge(array(
            'status'     => TRUE,
            'sumber'     => $sumber,
            'model'      => $model,
            'diperbarui' => date('Y-m-d H:i:s'),
            'dari_cache' => FALSE,
            'ai_aktif'   => $api_key !== '',
        ), $payload));
    }

    private function _label_jenis_bukti($kode)
    {
        $map = array(
            'R' => 'Regulasi/Kebijakan',
            'D' => 'Dokumen',
            'O' => 'Observasi',
            'W' => 'Wawancara',
            'S' => 'Simulasi',
        );
        $kode = strtoupper((string) $kode);
        return isset($map[$kode]) ? $map[$kode] : 'Dokumen';
    }

    private function _prompt_data_dukung($konteks)
    {
        $isi_ep   = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $konteks->isi_ep)));
        $standar  = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $konteks->isi_standar)));
        $maksud   = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $konteks->maksud_tujuan)));
        $jenis    = $this->_label_jenis_bukti($konteks->jenis_bukti);

        $prompt  = "Anda asisten akreditasi rumah sakit di Indonesia. Bantu tim menyiapkan data dukung yang konkret dan mudah diaudit.\n\n";
        $prompt .= "Pokja: {$konteks->bab} - {$konteks->pokja_ket}\n";
        $prompt .= "Standar {$konteks->no_standar}: {$standar}\n";
        if ($maksud !== '') $prompt .= "Maksud/tujuan standar: {$maksud}\n";
        $prompt .= "EP {$konteks->no_ep} (skor maksimal {$konteks->skor_maks}): {$isi_ep}\n";
        $prompt .= "Jenis bukti yang diminta pada EP ini: {$jenis}.\n\n";
        $prompt .= "Sebutkan dokumen/data dukung spesifik yang perlu disiapkan rumah sakit untuk membuktikan EP ini terpenuhi. ";
        $prompt .= "Gunakan bahasa Indonesia, praktis, dan lazim dipakai di rumah sakit. ";
        $prompt .= "Jangan mengarang nomor regulasi atau dokumen yang tidak pasti. Maksimal 8 dokumen.\n\n";
        $prompt .= "Balas HANYA JSON valid tanpa markdown dengan skema:\n";
        $prompt .= '{"ringkasan":"1-2 kalimat","dokumen":["..."],"observasi":["..."],"catatan":"1 kalimat tips"}';

        return $prompt;
    }

    // Return array payload atau FALSE kalau AI tidak tersedia/gagal.
    private function _generate_data_dukung_gemini($konteks)
    {
        $api_key = trim((string) $this->config->item('gemini_api_key'));
        if ($api_key === '') return FALSE;

        $model = trim((string) $this->config->item('gemini_model'));
        if ($model === '') $model = 'gemini-2.5-flash';
        $timeout = (int) $this->config->item('gemini_timeout');
        if ($timeout <= 0) $timeout = 25;

        $url  = 'https://generativelanguage.googleapis.com/v1beta/models/'
              . rawurlencode($model) . ':generateContent?key=' . rawurlencode($api_key);
        $body = json_encode(array(
            'contents' => array(array(
                'role'  => 'user',
                'parts' => array(array('text' => $this->_prompt_data_dukung($konteks))),
            )),
            'generationConfig' => array(
                'temperature'      => 0.2,
                'maxOutputTokens'  => 4096,
                'responseMimeType' => 'application/json',
            ),
        ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $raw       = FALSE;
        $http_code = 0;
        $curl_err  = '';

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_POST           => TRUE,
                CURLOPT_HTTPHEADER     => array('Content-Type: application/json'),
                CURLOPT_POSTFIELDS     => $body,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT        => $timeout,
                CURLOPT_USERAGENT      => 'mukespi-data-dukung/1.0',
            ));
            $raw       = curl_exec($ch);
            $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_err  = curl_error($ch);
            curl_close($ch);
        } else {
            // Sebagian instalasi XAMPP tidak memuat ekstensi curl di Apache.
            // Gunakan stream HTTPS sebagai cadangan (allow_url_fopen=On).
            $ctx = stream_context_create(array(
                'http' => array(
                    'method'        => 'POST',
                    'header'        => "Content-Type: application/json\r\n",
                    'content'       => $body,
                    'timeout'       => $timeout,
                    'ignore_errors' => TRUE,
                    'user_agent'    => 'mukespi-data-dukung/1.0',
                ),
            ));
            $raw = @file_get_contents($url, FALSE, $ctx);
            if (!empty($http_response_header[0])
                && preg_match('#\s(\d{3})\s#', $http_response_header[0], $match)) {
                $http_code = (int) $match[1];
            }
            if ($raw === FALSE) $curl_err = 'stream HTTPS gagal';
        }

        if ($raw === FALSE || $http_code < 200 || $http_code >= 300) {
            log_message('error', 'Gemini data dukung EP ' . (int) $konteks->id_ep
                . ' gagal (HTTP ' . $http_code . ')' . ($curl_err ? ': ' . $curl_err : ''));
            return FALSE;
        }

        $json = json_decode($raw, TRUE);
        $text = '';
        if (isset($json['candidates'][0]['content']['parts']) && is_array($json['candidates'][0]['content']['parts'])) {
            foreach ($json['candidates'][0]['content']['parts'] as $part) {
                if (isset($part['text'])) $text .= $part['text'];
            }
        }
        $text = trim($text);
        if ($text === '') return FALSE;

        $data = json_decode($text, TRUE);
        if (!is_array($data) && preg_match('/\{.*\}/s', $text, $match)) {
            $data = json_decode($match[0], TRUE);
        }
        if (!is_array($data) || empty($data['dokumen']) || !is_array($data['dokumen'])) {
            log_message('error', 'Gemini data dukung EP ' . (int) $konteks->id_ep . ': format JSON tidak valid');
            return FALSE;
        }

        $dokumen = array();
        foreach ($data['dokumen'] as $item) {
            $teks = $this->_bersihkan_teks_ai($item, 300);
            if ($teks !== '') $dokumen[] = $teks;
        }
        $observasi = array();
        if (isset($data['observasi']) && is_array($data['observasi'])) {
            foreach ($data['observasi'] as $item) {
                $teks = $this->_bersihkan_teks_ai($item, 300);
                if ($teks !== '') $observasi[] = $teks;
            }
        }
        if (empty($dokumen)) return FALSE;

        return array(
            'ringkasan' => $this->_bersihkan_teks_ai(isset($data['ringkasan']) ? $data['ringkasan'] : '', 500),
            'dokumen'   => array_slice(array_values(array_unique($dokumen)), 0, 8),
            'observasi' => array_slice(array_values(array_unique($observasi)), 0, 4),
            'catatan'   => $this->_bersihkan_teks_ai(isset($data['catatan']) ? $data['catatan'] : '', 500),
        );
    }

    private function _bersihkan_teks_ai($teks, $max = 500)
    {
        $teks = trim(strip_tags((string) $teks));
        $teks = preg_replace('/\s+/u', ' ', $teks);
        if (function_exists('mb_substr')) return mb_substr($teks, 0, $max);
        return substr($teks, 0, $max);
    }

    private function _data_dukung_template($konteks)
    {
        $jenis    = strtoupper((string) $konteks->jenis_bukti);
        $label    = $this->_label_jenis_bukti($jenis);
        $isi_ep   = function_exists('mb_strtolower') ? mb_strtolower((string) $konteks->isi_ep) : strtolower((string) $konteks->isi_ep);
        $dokumen  = array();

        if ($jenis === 'R') {
            $dokumen[] = 'Salinan kebijakan/SK yang masih berlaku dan mengatur isi EP ini';
            $dokumen[] = 'SOP/prosedur pelaksanaan beserta bukti penerapannya';
            $dokumen[] = 'Bukti sosialisasi kebijakan/SOP (daftar hadir, materi, notulen)';
        } elseif ($jenis === 'O') {
            $dokumen[] = 'Checklist/daftar tilik observasi sesuai isi EP';
            $dokumen[] = 'Dokumentasi foto/kondisi nyata di unit terkait';
            $dokumen[] = 'Catatan hasil observasi dan tindak lanjut';
        } elseif ($jenis === 'W') {
            $dokumen[] = 'Daftar narasumber dan jadwal wawancara';
            $dokumen[] = 'Panduan/daftar pertanyaan wawancara';
            $dokumen[] = 'Notulen atau ringkasan hasil wawancara';
        } elseif ($jenis === 'S') {
            $dokumen[] = 'Skenario simulasi sesuai isi EP';
            $dokumen[] = 'Berita acara dan dokumentasi pelaksanaan simulasi';
            $dokumen[] = 'Hasil evaluasi dan tindak lanjut simulasi';
        } else {
            $dokumen[] = 'Dokumen perencanaan/pelaksanaan kegiatan sesuai isi EP';
            $dokumen[] = 'Laporan/rekapitulasi hasil pelaksanaan';
            $dokumen[] = 'Daftar hadir/berita acara kegiatan';
            $dokumen[] = 'Dokumen evaluasi dan tindak lanjut';
        }

        $kata_kunci = array(
            'kebijakan'       => 'Salinan kebijakan/SK Direktur yang masih berlaku',
            'sop'             => 'SOP/prosedur terkait dan bukti penerapannya',
            'panduan'         => 'Panduan/daftar tilik yang digunakan',
            'evaluasi'        => 'Instrumen evaluasi dan laporan hasil evaluasi',
            'monitoring'      => 'Laporan monitoring dan bukti tindak lanjut',
            'sosialisasi'     => 'Daftar hadir, materi, dan notulen sosialisasi',
            'pelatihan'       => 'Sertifikat pelatihan, daftar hadir, dan materi',
            'rapat'           => 'Notulen rapat dan daftar hadir',
            'laporan'         => 'Laporan tertulis terbaru yang relevan',
            'kontrak'         => 'Dokumen kontrak/perjanjian yang berlaku',
            'perjanjian'      => 'Dokumen perjanjian kerja sama yang berlaku',
            'register'        => 'Register terbaru yang relevan',
            'audit'           => 'Laporan audit internal dan bukti tindak lanjut',
            'insiden'         => 'Laporan insiden dan analisis/tindak lanjut',
            'kompetensi'      => 'Bukti kompetensi (sertifikat, uji kompetensi, kredensial)',
            'kredensial'      => 'Dokumen kredensial staf terkait',
            'pemilihan'       => 'Dokumen proses pemilihan dan berita acara',
            'ditetapkan'      => 'SK/dokumen penetapan yang berlaku',
            'didokumentasikan'=> 'Dokumen/bukti yang terdokumentasi',
            'dilaporkan'      => 'Laporan kepada pihak terkait',
            'ditindaklanjuti' => 'Bukti tindak lanjut atas hasil evaluasi',
        );

        foreach ($kata_kunci as $kata => $saran) {
            if (strpos($isi_ep, $kata) !== FALSE) $dokumen[] = $saran;
        }

        $dokumen = array_slice(array_values(array_unique($dokumen)), 0, 10);

        $observasi = array();
        if (in_array($jenis, array('O', 'W', 'S'), TRUE)) {
            $observasi[] = 'Siapkan unit/area dan narasumber yang akan dilihat atau diwawancarai.';
            $observasi[] = 'Pastikan bukti pelaksanaan mudah ditelusuri saat observasi/simulasi.';
        }

        return array(
            'ringkasan' => 'Saran data dukung untuk EP ' . $konteks->no_ep . ' (Standar ' . $konteks->no_standar . ', jenis bukti: ' . $label . ').',
            'dokumen'   => $dokumen,
            'observasi' => $observasi,
            'catatan'   => 'Saran ini disusun otomatis dari jenis bukti dan kata kunci pada EP. Untuk saran yang lebih spesifik, isi API key Gemini di application/config/gemini.php.',
        );
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

        if (!$this->_ep_tersedia($id_ep)) {
            echo json_encode(array('status' => FALSE, 'message' => 'EP tidak tersedia'));
            return;
        }

        if (!$this->_boleh_nilai_pokja($this->_id_pokja_dari_ep($id_ep))) {
            echo json_encode(array('status' => FALSE, 'message' => 'Anda tidak punya hak untuk menilai EP ini'));
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

        if (!$this->_ep_tersedia($id_ep)) {
            echo json_encode(array('status' => FALSE, 'message' => 'EP tidak tersedia'));
            return;
        }

        $id_pokja      = $this->_id_pokja_dari_ep($id_ep);
        $boleh_nilai   = $this->_boleh_nilai_pokja($id_pokja);
        $boleh_download = $this->_boleh_download_pokja($id_pokja);

        $items = array();
        foreach (array('internal', 'surveior') as $jenis) {
            $penilaian = $this->Penilaian_ep_model->get_penilaian($periode->id_periode, $id_ep, $jenis);
            if (!$penilaian) continue;

            $files = $this->Penilaian_ep_model->get_bukti_by_penilaian($penilaian->id_penilaian);
            foreach ($files as $f) {
                $items[] = array(
                    'id_upload'       => $f->id_upload,
                    'nama_file'       => $f->nama_file,
                    // dibuka lewat endpoint controller supaya hak akses tetap dijaga
                    'url'             => site_url('penilaian_ep/lihat_bukti/' . $f->id_upload),
                    'url_unduh'       => $boleh_download
                                             ? site_url('penilaian_ep/unduh_bukti/' . $f->id_upload)
                                             : NULL,
                    'boleh_download'  => $boleh_download,
                    'boleh_nilai'     => $boleh_nilai,
                    'keterangan'      => $f->keterangan,
                    'uploaded_by'     => $f->uploaded_by,
                    'uploaded_at'     => $f->uploaded_at,
                    'jenis_penilaian' => $jenis,
                );
            }
        }

        echo json_encode(array(
            'status'          => TRUE,
            'files'           => $items,
            'boleh_nilai'     => $boleh_nilai,
            'boleh_download'  => $boleh_download,
        ));
    }

    // ================= LIHAT BUKTI (stream di browser) =================
    // Semua user yang punya akses pokja boleh membuka dokumen di tab baru.
    // Sengaja TIDAK pakai Content-Disposition: attachment supaya tidak terunduh otomatis.

    public function lihat_bukti($id_upload)
    {
        $file    = $this->_ambil_bukti_terotorisasi($id_upload);
        if ($file === FALSE) {
            show_error('Dokumen tidak ditemukan atau Anda tidak punya akses.', 403);
            return;
        }

        $this->_kirim_file($file->path_file, $file->nama_file, 'inline');
    }

    // ================= UNDUH BUKTI =================
    // Hanya untuk user yang pokja-nya di-set di tabel user_pokja_ep
    // (atau admin/surveior). Selain itu ditolak 403.

    public function unduh_bukti($id_upload)
    {
        $file = $this->_ambil_bukti_terotorisasi($id_upload);
        if ($file === FALSE) {
            show_error('Dokumen tidak ditemukan atau Anda tidak punya akses.', 403);
            return;
        }

        if (!$file->boleh_download) {
            show_error('Anda tidak punya hak download dokumen pada pokja ini.', 403);
            return;
        }

        $this->_kirim_file($file->path_file, $file->nama_file, 'attachment');
    }

    // Ambil data bukti + cek user punya akses ke pokja tempat file ini berada.
    // Return object dengan properti tambahan path_full & boleh_download, atau FALSE.
    private function _ambil_bukti_terotorisasi($id_upload)
    {
        $bukti = $this->Penilaian_ep_model->get_bukti_by_id(intval($id_upload));
        if (!$bukti) {
            return FALSE;
        }

        $penilaian = $this->Penilaian_ep_model->get_penilaian_by_id($bukti->id_penilaian);
        if (!$penilaian) {
            return FALSE;
        }

        $ep       = $this->Penilaian_ep_model->get_ep_by_id($penilaian->id_ep);
        if (!$ep) {
            return FALSE;
        }

        $id_pokja = $this->_id_pokja_dari_ep($penilaian->id_ep);
        if (!$id_pokja) {
            return FALSE;
        }

        $path_full = FCPATH . $bukti->path_file;
        if (empty($bukti->path_file) || !is_file($path_full)) {
            return FALSE;
        }

        $bukti->path_full      = $path_full;
        $bukti->id_pokja       = $id_pokja;
        $bukti->boleh_download = $this->_boleh_download_pokja($id_pokja);
        $bukti->boleh_nilai    = $this->_boleh_nilai_pokja($id_pokja);
        return $bukti;
    }

    private function _kirim_file($path_full, $nama_file, $disposisi)
    {
        $ext = strtolower(pathinfo($path_full, PATHINFO_EXTENSION));

        switch ($ext) {
            case 'pdf': $mime = 'application/pdf'; break;
            case 'jpg':
            case 'jpeg': $mime = 'image/jpeg'; break;
            default:     $mime = 'application/octet-stream'; break;
        }

        $nama = str_replace(array("\r", "\n", '"'), '', $nama_file);

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path_full));
        header('Content-Disposition: ' . $disposisi . '; filename="' . $nama . '"');
        header('Content-Transfer-Encoding: binary');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        readfile($path_full);
        exit;
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

        if (!$this->_ep_tersedia($id_ep)) {
            echo json_encode(array('status' => FALSE, 'message' => 'EP tidak tersedia'));
            return;
        }

        if (!$this->_boleh_nilai_pokja($this->_id_pokja_dari_ep($id_ep))) {
            echo json_encode(array('status' => FALSE, 'message' => 'Anda tidak punya hak upload bukti di pokja ini'));
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
        $config['allowed_types'] = implode('|', $this->ekstensi_bukti); // pdf|jpg|jpeg
        $config['max_size']      = 5120; // 5 MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        $total  = count($_FILES['file_bukti']['name']);
        $sukses = 0;
        $gagal  = array();

        for ($i = 0; $i < $total; $i++) {
            if (empty($_FILES['file_bukti']['name'][$i])) continue;

            // Cek ekstensi dulu supaya pesan error-nya jelas, tidak perlu
            // menunggu lib Upload menolaknya dengan pesan bawaan Bahasa Inggris.
            $ext_asli = strtolower(pathinfo($_FILES['file_bukti']['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($ext_asli, $this->ekstensi_bukti, TRUE)) {
                $gagal[] = $_FILES['file_bukti']['name'][$i] . ': format .' . $ext_asli . ' tidak diizinkan, hanya PDF atau JPG';
                continue;
            }

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

        if (!$penilaian) {
            echo json_encode(array('status' => FALSE, 'message' => 'Data penilaian tidak ditemukan'));
            return;
        }

        if (!$this->_boleh_nilai_pokja($this->_id_pokja_dari_ep($penilaian->id_ep))) {
            echo json_encode(array('status' => FALSE, 'message' => 'Anda tidak punya hak menghapus bukti di pokja ini'));
            return;
        }

        if ($penilaian->jenis_penilaian !== $jenis_saya && !$is_admin) {
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