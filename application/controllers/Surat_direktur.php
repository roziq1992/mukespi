<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_direktur extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in(4);
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
        $data = array('title' => 'Aksi Direktur', 'surat' => $surat, 'users' => $this->Surat_model->users());
        $this->load->view('template/header', $data);
        $this->load->view('surat/proses_direktur', $data);
        $this->load->view('template/footer');
    }

    public function simpan($id)
    {
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        $aksi = $this->input->post('aksi', TRUE);
        if ($aksi === 'ttd') {
            $update = array('status' => 'Ditandatangani', 'updated_at' => date('Y-m-d H:i:s'));
            if (!$this->_upload_final($id, $update)) return;
            if (!$this->Surat_model->update($id, $update)) show_error('Status surat gagal diperbarui.', 500);
            log_surat($id, 'Surat ditandatangani', $this->input->post('catatan', TRUE));
            $pemohon = $this->db->where('id', $surat->id_pemohon)->get('users')->row();
            if ($pemohon) surat_kirim_notifikasi($pemohon->email, $pemohon->name, 'Surat Anda telah ditandatangani', 'Surat dengan perihal <strong>' . htmlspecialchars($surat->perihal, ENT_QUOTES, 'UTF-8') . '</strong> telah ditandatangani Direktur RS Airlangga.', $id);
        } elseif ($aksi === 'disposisi') {
            $ke_user = (int) $this->input->post('ke_user');
            $catatan = trim($this->input->post('catatan', TRUE));
            if (!$ke_user || $catatan === '') { $this->session->set_flashdata('message', 'Tujuan dan catatan disposisi wajib diisi.'); redirect('surat_direktur/proses/' . $id); return; }
            $update = array('status' => 'Didisposisikan', 'updated_at' => date('Y-m-d H:i:s'));
            if (!$this->_upload_final($id, $update)) return;
            $this->db->trans_start();
            $this->Surat_disposisi_model->insert(array('id_surat' => $id, 'dari_user' => $this->session->userdata('id'), 'ke_user' => $ke_user, 'catatan' => $catatan, 'status' => 'Menunggu Tindak Lanjut', 'created_at' => date('Y-m-d H:i:s')));
            $this->Surat_model->update($id, $update);
            log_surat($id, 'Surat didisposisikan', $catatan);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) show_error('Disposisi dan status surat gagal disimpan.', 500);
            $penerima = $this->db->where('id', $ke_user)->get('users')->row();
            if ($penerima) surat_kirim_notifikasi($penerima->email, $penerima->name, 'Pemberitahuan Disposisi Surat', 'Dengan hormat, Direktur RS Airlangga telah memberikan disposisi surat dengan perihal <strong>' . htmlspecialchars($surat->perihal, ENT_QUOTES, 'UTF-8') . '</strong> kepada Anda.<br><br>Catatan disposisi:<br>' . htmlspecialchars($catatan, ENT_QUOTES, 'UTF-8'), $id);
        }
        redirect('surat_direktur');
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
        if (!$row || (int) $row->ke_user !== (int) $this->session->userdata('id')) show_error('Akses ditolak.', 403);
        $this->Surat_disposisi_model->update_status($id, 'Selesai');
        $this->Surat_model->update($row->id_surat, array('status' => 'Selesai', 'updated_at' => date('Y-m-d H:i:s')));
        log_surat($row->id_surat, 'Disposisi diselesaikan', $this->input->post('catatan', TRUE));
        redirect('surat/detail/' . $row->id_surat);
    }
}
