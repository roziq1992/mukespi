<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surat_sekretaris extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in(5);
        $this->load->helper(array('surat', 'download'));
        $this->load->model(array('Surat_model', 'Surat_log_model'));
        $this->load->library(array('upload', 'email'));
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
        if ($surat->status === 'Diajukan') {
            $this->Surat_model->update($id, array('status' => 'Diproses Sekretaris', 'updated_at' => date('Y-m-d H:i:s')));
            log_surat($id, 'Surat diproses sekretaris', 'Surat masuk ke antrian sekretaris.');
            $surat = $this->Surat_model->get($id);
        }
        $data = array('title' => 'Proses Nomor Surat', 'surat' => $surat);
        $this->load->view('template/header', $data);
        $this->load->view('surat/proses_nomor', $data);
        $this->load->view('template/footer');
    }

    public function simpan($id)
    {
        $surat = $this->Surat_model->get($id);
        if (!$surat) show_404();
        $nomor = trim($this->input->post('no_surat', TRUE));
        if ($this->input->post('generate')) $nomor = $this->_generate($surat->jenis);
        if ($nomor === '') { $this->session->set_flashdata('message', 'Nomor surat wajib diisi.'); redirect('surat_sekretaris/proses/' . $id); return; }
        $teruskan = (bool) $this->input->post('teruskan');
        $status = $teruskan ? 'Diteruskan ke Direktur' : 'Sudah Diberi Nomor';
        $update = array('no_surat' => $nomor, 'status' => $status, 'updated_at' => date('Y-m-d H:i:s'));
        if (!empty($_FILES['file_ber_nomor']['name'])) {
            $path = FCPATH . 'uploads/surat/' . $id . '/final/';
            if (!is_dir($path)) mkdir($path, 0755, TRUE);
            $this->upload->initialize(array('upload_path' => $path, 'allowed_types' => 'doc|docx|pdf', 'max_size' => 5120, 'encrypt_name' => TRUE));
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
        redirect('surat_sekretaris');
    }

    private function _generate($jenis)
    {
        $year = date('Y');
        $count = $this->db->where('jenis', $jenis)->where('YEAR(created_at)', $year)->where('no_surat IS NOT NULL', NULL, FALSE)->count_all_results('surat') + 1;
        $romawi = array(1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII');
        $kode_jenis = $jenis === 'internal' ? 'INT' : 'EXT';
        return str_pad($count, 3, '0', STR_PAD_LEFT) . '/RSA/' . $kode_jenis . '/' . $romawi[(int) date('n')] . '/' . $year;
    }
}
