<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sertifikat extends CI_Controller
{
    protected $front_templates = array(
        'sertifikat.jpg' => 'Template RS Airlangga',
        'sertifikatnew1.jpg' => 'Template Pelatihan Alternatif',
        'temp1.jpg' => 'Template Polos',
    );

    protected $back_templates = array(
        'sertifikatbelakang.jpg' => 'Belakang Standar',
        'sertifikatbelakang.png' => 'Belakang Alternatif',
    );

    function __construct()
    {
        parent::__construct();
        $this->load->model('Sertifikat_model');
        $this->load->library('form_validation');
    }

    private function _is_valid_template_selection($selection, $defaults, $current, $side)
    {
        return (is_string($selection) && isset($defaults[$selection])) ||
            ($selection === $current && $this->_resolve_template_path($selection, $side) !== FALSE);
    }

    private function _resolve_template_path($stored_path, $side)
    {
        $defaults = $side === 'front' ? $this->front_templates : $this->back_templates;
        if (isset($defaults[$stored_path])) {
            return FCPATH . 'sertifikat' . DIRECTORY_SEPARATOR . $stored_path;
        }
        if (!preg_match('#^uploads/sertifikat/[A-Za-z0-9_-]+\.(?:jpg|jpeg|png)$#i', (string) $stored_path)) {
            return FALSE;
        }

        $upload_root = realpath(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'sertifikat');
        $path = realpath(FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $stored_path));
        if ($upload_root === FALSE || $path === FALSE ||
            strpos(strtolower($path), strtolower($upload_root . DIRECTORY_SEPARATOR)) !== 0) {
            return FALSE;
        }
        return $path;
    }

    private function _remove_uploaded_template($stored_path)
    {
        $path = $this->_resolve_template_path($stored_path, 'front');
        if ($path === FALSE) {
            $path = $this->_resolve_template_path($stored_path, 'back');
        }
        if ($path !== FALSE && is_file($path)) {
            unlink($path);
        }
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'sertifikat/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'sertifikat/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'sertifikat/index.html';
            $config['first_url'] = base_url() . 'sertifikat/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sertifikat_model->total_rows($q);
        $sertifikat = $this->Sertifikat_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sertifikat_data' => $sertifikat,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
       
           $this->load->view('template/header', $data);
        $this->load->view('sertifikat/sertifikat_list');
        $this->load->view('template/footer');
    }

    public function read($id) 
    {
        $row = $this->Sertifikat_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_sertifikat' => $row->id_sertifikat,
		'judul' => $row->judul,
		'ket' => $row->ket,
		'tanggal' => $row->tanggal,
	    );
          
              $this->load->view('template/header', $data);
        $this->load->view('sertifikat/sertifikat_read');
        $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Tambah',
            'action' => site_url('sertifikat/create_action'),
	    'id_sertifikat' => set_value('id_sertifikat'),
	    'judul' => set_value('judul'),
	    'ket' => set_value('ket'),
	    'tanggal' => set_value('tanggal'),
            'front_templates' => $this->front_templates,
            'back_templates' => $this->back_templates,
	    'selected_front' => set_value('template_depan', 'sertifikat.jpg'),
	    'selected_back' => set_value('template_belakang', 'sertifikatbelakang.jpg'),
	    'is_edit' => FALSE,
	);
      
           $this->load->view('template/header', $data);
        $this->load->view('sertifikat/sertifikat_form');
        $this->load->view('template/footer');
    }
    
    public function create_action() 
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
            return;
        }

        $template_front = $this->input->post('template_depan', TRUE);
        $template_back = $this->input->post('template_belakang', TRUE);
        if (!isset($this->front_templates[$template_front]) || !isset($this->back_templates[$template_back])) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Pilihan template tidak valid.</div>');
            redirect(site_url('sertifikat/create'));
            return;
        }

        $uploaded_files = array();
        $uploaded_front = $this->_store_template_upload('file1', $uploaded_files);
        if ($uploaded_front === FALSE) {
            $this->_remove_uploaded_templates($uploaded_files);
            redirect(site_url('sertifikat/create'));
            return;
        }
        $uploaded_back = $this->_store_template_upload('file2', $uploaded_files);
        if ($uploaded_back === FALSE) {
            $this->_remove_uploaded_templates($uploaded_files);
            redirect(site_url('sertifikat/create'));
            return;
        }

        $data = array(
            'judul' => $this->input->post('judul', TRUE),
            'ket' => $this->input->post('ket', TRUE),
            'tanggal' => $this->input->post('tanggal', TRUE),
            'sertifikat_depan' => $uploaded_front !== NULL ? $uploaded_front : $template_front,
            'sertifikat_belakang' => $uploaded_back !== NULL ? $uploaded_back : $template_back,
        );

        if (!$this->Sertifikat_model->insert($data)) {
            $this->_remove_uploaded_templates($uploaded_files);
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Sertifikat gagal disimpan.</div>');
            redirect(site_url('sertifikat/create'));
            return;
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success">Sertifikat berhasil dibuat.</div>');
            redirect(site_url('sertifikat'));
    }

    private function _store_template_upload($field, &$uploaded_files)
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return NULL;
        }
        if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Unggahan template gagal. Periksa ukuran file dan konfigurasi upload PHP.</div>');
            return FALSE;
        }

        $upload_dir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'sertifikat' . DIRECTORY_SEPARATOR;
        if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, TRUE) && !is_dir($upload_dir)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Folder penyimpanan template tidak dapat dibuat.</div>');
            return FALSE;
        }

        $this->load->library('upload');
        $this->upload->initialize(array(
            'upload_path' => $upload_dir,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size' => 8192,
            'max_width' => 10000,
            'max_height' => 10000,
            'encrypt_name' => TRUE,
        ));

        if (!$this->upload->do_upload($field)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Template gagal diunggah: ' .
                html_escape($this->upload->display_errors('', '')) . '</div>');
            return FALSE;
        }

        $upload = $this->upload->data();
        $image_info = getimagesize($upload['full_path']);
        if ($image_info === FALSE || !in_array($image_info['mime'], array('image/jpeg', 'image/png'), TRUE)) {
            unlink($upload['full_path']);
            $this->session->set_flashdata('message', '<div class="alert alert-danger">File template harus berupa gambar JPG atau PNG yang valid.</div>');
            return FALSE;
        }

        $relative_path = 'uploads/sertifikat/' . $upload['file_name'];
        $uploaded_files[] = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $relative_path);
        return $relative_path;
    }

    private function _remove_uploaded_templates($files)
    {
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
    
    public function update($id) 
    {
        $row = $this->Sertifikat_model->get_by_id($id);

        if ($row) {
            $front_value = $row->sertifikat_depan !== '' ? $row->sertifikat_depan : 'sertifikat.jpg';
            $back_value = $row->sertifikat_belakang !== '' ? $row->sertifikat_belakang : 'sertifikatbelakang.jpg';
            $front_templates = $this->front_templates;
            $back_templates = $this->back_templates;
            if (!isset($front_templates[$front_value])) {
                $front_templates[$front_value] = 'Template yang sedang digunakan';
            }
            if (!isset($back_templates[$back_value])) {
                $back_templates[$back_value] = 'Template yang sedang digunakan';
            }
            $data = array(
                'button' => 'Update',
                'action' => site_url('sertifikat/update_action'),
                'id_sertifikat' => set_value('id_sertifikat', $row->id_sertifikat),
		'judul' => set_value('judul', $row->judul),
		'ket' => set_value('ket', $row->ket),
		'tanggal' => set_value('tanggal', $row->tanggal),
                'front_templates' => $front_templates,
                'back_templates' => $back_templates,
                'selected_front' => $front_value,
                'selected_back' => $back_value,
                'is_edit' => TRUE,
	    );
       
               $this->load->view('template/header', $data);
        $this->load->view('sertifikat/sertifikat_form');
        $this->load->view('template/footer');
            
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_sertifikat', TRUE));
            return;
        }

        $id = $this->input->post('id_sertifikat', TRUE);
        $row = $this->Sertifikat_model->get_by_id($id);
        if (!$row) {
            show_404();
            return;
        }

        $template_front = $this->input->post('template_depan', TRUE);
        $template_back = $this->input->post('template_belakang', TRUE);
        if (!$this->_is_valid_template_selection($template_front, $this->front_templates, $row->sertifikat_depan, 'front') ||
            !$this->_is_valid_template_selection($template_back, $this->back_templates, $row->sertifikat_belakang, 'back')) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Pilihan template tidak valid.</div>');
            redirect(site_url('sertifikat/update/' . $id));
            return;
        }

        $uploaded_files = array();
        $uploaded_front = $this->_store_template_upload('file1', $uploaded_files);
        if ($uploaded_front === FALSE) {
            redirect(site_url('sertifikat/update/' . $id));
            return;
        }
        $uploaded_back = $this->_store_template_upload('file2', $uploaded_files);
        if ($uploaded_back === FALSE) {
            $this->_remove_uploaded_templates($uploaded_files);
            redirect(site_url('sertifikat/update/' . $id));
            return;
        }

        $data = array(
            'judul' => $this->input->post('judul', TRUE),
            'ket' => $this->input->post('ket', TRUE),
            'tanggal' => $this->input->post('tanggal', TRUE),
            'sertifikat_depan' => $uploaded_front !== NULL ? $uploaded_front : $template_front,
            'sertifikat_belakang' => $uploaded_back !== NULL ? $uploaded_back : $template_back,
        );
        if (!$this->Sertifikat_model->update($id, $data)) {
            $this->_remove_uploaded_templates($uploaded_files);
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Sertifikat gagal diperbarui.</div>');
            redirect(site_url('sertifikat/update/' . $id));
            return;
        }

        foreach (array($row->sertifikat_depan, $row->sertifikat_belakang) as $old_template) {
            if ($old_template !== $data['sertifikat_depan'] && $old_template !== $data['sertifikat_belakang']) {
                $this->_remove_uploaded_template($old_template);
            }
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success">Sertifikat berhasil diperbarui.</div>');
        redirect(site_url('sertifikat'));
    }
    
    public function delete($id) 
    {
        $row = $this->Sertifikat_model->get_by_id($id);

        if ($row) {
            if ($this->Sertifikat_model->delete($id)) {
                $this->_remove_uploaded_template($row->sertifikat_depan);
                $this->_remove_uploaded_template($row->sertifikat_belakang);
                $this->session->set_flashdata('message', 'Delete Record Success');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Sertifikat gagal dihapus.</div>');
            }
            redirect(site_url('sertifikat'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('judul', 'judul', 'trim|required');
	$this->form_validation->set_rules('ket', 'ket', 'trim|required');
	$this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required');

	$this->form_validation->set_rules('id_sertifikat', 'id_sertifikat', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $this->load->helper('exportexcel');
        $namaFile = "sertifikat.xls";
        $judul = "sertifikat";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
	xlsWriteLabel($tablehead, $kolomhead++, "Judul");
	xlsWriteLabel($tablehead, $kolomhead++, "Ket");
	xlsWriteLabel($tablehead, $kolomhead++, "Tanggal");

	foreach ($this->Sertifikat_model->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->judul);
	    xlsWriteLabel($tablebody, $kolombody++, $data->ket);
	    xlsWriteLabel($tablebody, $kolombody++, $data->tanggal);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

}

/* End of file Sertifikat.php */
/* Location: ./application/controllers/Sertifikat.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2023-01-21 09:32:43 */
/* http://harviacode.com */