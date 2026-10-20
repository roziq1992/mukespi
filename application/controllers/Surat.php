<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat extends CI_Controller
{
    private $role_sekretaris = 5;
    private $role_direktur = 4;

    public function __construct()
    {
        parent::__construct();
        // Halaman verifikasi QR dokumen bersifat publik (hasil pindai tanpa login).
        if (strtolower($this->router->fetch_method()) !== 'verifikasi') {
            is_logged_in();
        }
        $this->load->helper(array('surat', 'download'));
        $this->load->model(array('Surat_model', 'Surat_lampiran_model', 'Surat_disposisi_model', 'Surat_log_model'));
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
        $base = FCPATH . 'uploads/surat/';
        $status_awal = $jenis === 'disposisi' ? 'Diteruskan ke Direktur' : 'Diajukan';
        $data = array('jenis' => $jenis, 'perihal' => $this->input->post('perihal', TRUE), 'tujuan' => $this->input->post('tujuan', TRUE), 'tanggal_pengajuan' => $this->input->post('tanggal_pengajuan', TRUE), 'keterangan' => $this->input->post('keterangan', TRUE), 'id_pemohon' => $this->session->userdata('id'), 'status' => $status_awal, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'));
        if (!empty($_FILES['file_draft']['name'])) {
            $config = array('upload_path' => $base . 'pending/', 'allowed_types' => 'doc|docx|pdf', 'max_size' => 5120, 'encrypt_name' => TRUE);
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
        $penerima = $this->db->where_in('role_id', array(4, 5))->order_by('id', 'ASC')->get('users')->result();
        $pesan = $jenis === 'disposisi'
            ? 'Terdapat pengajuan surat disposisi baru yang langsung diteruskan ke Direktur untuk ditindaklanjuti.<br><br>'
            : 'Terdapat pengajuan surat baru yang perlu ditindaklanjuti.<br><br>'
            . '<strong>Perihal:</strong> ' . htmlspecialchars($data['perihal'], ENT_QUOTES, 'UTF-8') . '<br>'
            . '<strong>Jenis:</strong> ' . htmlspecialchars(ucfirst($data['jenis']), ENT_QUOTES, 'UTF-8') . '<br>'
            . '<strong>Tujuan:</strong> ' . htmlspecialchars($data['tujuan'], ENT_QUOTES, 'UTF-8');
        surat_kirim_notifikasi_massal($penerima, 'Pemberitahuan Pengajuan Surat Baru', $pesan);
        $this->session->set_flashdata('message', 'Pengajuan surat berhasil dibuat.'); redirect('surat/detail/' . $id);
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
            $path = $surat->file_final ?: $surat->file_ber_nomor;
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
        force_download(FCPATH . $row->path_file, NULL);
    }

    public function hapus($id)
    {
        if ((int) $this->session->userdata('role_id') !== 1) show_error('Hanya admin yang dapat menghapus surat.', 403);
        if (strtolower($this->input->method()) !== 'post') show_error('Metode request tidak valid.', 405);
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        $this->db->trans_start();
        $this->db->where('id', (int) $id)->delete('surat');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) show_error('Surat gagal dihapus.', 500);
        $folder = FCPATH . 'uploads/surat/' . (int) $id;
        if (is_dir($folder)) $this->_remove_folder($folder);
        $this->session->set_flashdata('message', 'Surat berhasil dihapus.');
        redirect('surat');
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
