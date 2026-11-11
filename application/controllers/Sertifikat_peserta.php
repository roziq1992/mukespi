<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sertifikat_peserta extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Sertifikat_peserta_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        $idsertifikat = urldecode($this->input->get('id_sertifikat', TRUE));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'index.php/sertifikat_peserta?q=' . urlencode($q).'&id_sertifikat='.urlencode($idsertifikat);
            $config['first_url'] = base_url() . 'index.php/sertifikat_peserta??q=' . urlencode($q).'&id_sertifikat='.urlencode($idsertifikat);
        } else {
            $config['base_url'] = base_url() . 'index.php/sertifikat_peserta?'.'&id_sertifikat='.urlencode($idsertifikat);
            $config['first_url'] = base_url() . 'index.php/sertifikat_peserta?'.'&id_sertifikat='.urlencode($idsertifikat);
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Sertifikat_peserta_model->total_rows($q,$idsertifikat);
        $sertifikat_peserta = $this->Sertifikat_peserta_model->get_limit_data($config['per_page'], $start, $q,$idsertifikat);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'sertifikat_peserta_data' => $sertifikat_peserta,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
         $this->load->view('template/header', $data);
        $this->load->view('sertifikat_peserta/sertifikat_peserta_list');
        $this->load->view('template/footer');
    }

    public function read($id) 
    {
        $row = $this->Sertifikat_peserta_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id_peserta' => $row->id_peserta,
		'id_sertifikat' => $row->id_sertifikat,
		'nm_peserta' => $row->nm_peserta,
		'no_peserta' => $row->no_peserta,
	    );
            $this->load->view('sertifikat_peserta/sertifikat_peserta_read', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat_peserta'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('sertifikat_peserta/create_action'),
	    'id_peserta' => set_value('id_peserta'),
	    'id_sertifikat' => set_value('id_sertifikat'),
	    'nm_peserta' => set_value('nm_peserta'),
	    'no_peserta' => set_value('no_peserta'),
	);
           $this->load->view('template/header', $data);
        $this->load->view('sertifikat_peserta/sertifikat_peserta_form');
        $this->load->view('template/footer');
        
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'id_sertifikat' => $this->input->post('id_sertifikat',TRUE),
		'nm_peserta' => $this->input->post('nm_peserta',TRUE),
		'no_peserta' => $this->input->post('no_peserta',TRUE),
	    );

            $this->Sertifikat_peserta_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('sertifikat_peserta?id_sertifikat='.$this->input->post('id_sertifikat')));
        }
    }
    
    public function update() 
    {
        
        $id=$this->input->get('id');
        $row = $this->Sertifikat_peserta_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('sertifikat_peserta/update_action'),
		'id_peserta' => set_value('id_peserta', $row->id_peserta),
		'id_sertifikat' => set_value('id_sertifikat', $row->id_sertifikat),
		'nm_peserta' => set_value('nm_peserta', $row->nm_peserta),
		'no_peserta' => set_value('no_peserta', $row->no_peserta),
	    );
           
              $this->load->view('template/header', $data);
        $this->load->view('sertifikat_peserta/sertifikat_peserta_form');
        $this->load->view('template/footer');
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat_peserta?id_sertifikat='.$this->input->get('id_sertifikat')));
            
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id_peserta', TRUE));
        } else {
            $data = array(
		'id_sertifikat' => $this->input->post('id_sertifikat',TRUE),
		'nm_peserta' => $this->input->post('nm_peserta',TRUE),
		'no_peserta' => $this->input->post('no_peserta',TRUE),
	    );

            $this->Sertifikat_peserta_model->update($this->input->post('id_peserta', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('sertifikat_peserta?id_sertifikat='.$this->input->post('id_sertifikat')));
        }
    }
    
    public function delete() 
    {
        $id=$this->input->get('id');
        $row = $this->Sertifikat_peserta_model->get_by_id($id);

        if ($row) {
            $this->Sertifikat_peserta_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('sertifikat_peserta?id_sertifikat='.$this->input->get('id_sertifikat')));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('sertifikat_peserta?id_sertifikat='.$this->input->get('id_sertifikat')));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('id_sertifikat', 'id sertifikat', 'trim|required');
	$this->form_validation->set_rules('nm_peserta', 'nm peserta', 'trim|required');
	$this->form_validation->set_rules('no_peserta', 'no peserta', 'trim|required');

	$this->form_validation->set_rules('id_peserta', 'id_peserta', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel()
    {
        $id = $this->input->get('id_sertifikat', TRUE);
        if ($id === NULL) {
            $id = $this->input->get('id', TRUE);
        }
        $this->load->helper('exportexcel');
        $namaFile = "sertifikat_peserta.xls";
        $judul = "sertifikat_peserta";
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
	xlsWriteLabel($tablehead, $kolomhead++, "Id Sertifikat");
	xlsWriteLabel($tablehead, $kolomhead++, "Nm Peserta");
	xlsWriteLabel($tablehead, $kolomhead++, "No Seri");

	foreach ($this->Sertifikat_peserta_model->get_allfilter($id) as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
	    xlsWriteLabel($tablebody, $kolombody++, $data->id_sertifikat);
	    xlsWriteLabel($tablebody, $kolombody++, $data->nm_peserta);
	    xlsWriteLabel($tablebody, $kolombody++, $data->no_peserta);

	    $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    public function export_csv()
    {
        $id = $this->input->get('id_sertifikat', TRUE);
        if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
            show_error('Sertifikat tidak valid.', 400);
            return;
        }

        $filename = 'peserta_sertifikat_' . (int) $id . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, array('nama_peserta', 'no_peserta'));
        foreach ($this->Sertifikat_peserta_model->get_allfilter((int) $id) as $peserta) {
            fputcsv($out, array(
                $this->_csv_safe_value($peserta->nm_peserta),
                $this->_csv_safe_value($peserta->no_peserta),
            ));
        }
        fclose($out);
        exit;
    }

    public function template_csv()
    {
        $filename = 'template_peserta_sertifikat.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, array('nama_peserta', 'no_peserta'));
        fclose($out);
        exit;
    }

    public function import_csv()
    {
        $id = $this->input->post('id_sertifikat', TRUE);
        if (!is_string($id) || !ctype_digit($id) || (int) $id < 1 ||
            $this->db->where('id_sertifikat', (int) $id)->count_all_results('sertifikat') === 0) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Sertifikat tidak valid.</div>');
            redirect(site_url('sertifikat'));
            return;
        }

        $redirect_url = site_url('sertifikat_peserta?id_sertifikat=' . (int) $id);
        $this->load->library('upload');
        $this->upload->initialize(array(
            'upload_path' => FCPATH . 'uploads/',
            'allowed_types' => 'csv|txt',
            'encrypt_name' => TRUE,
            'max_size' => 4096,
        ));

        if (!$this->upload->do_upload('berkas')) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal mengunggah CSV: ' .
                html_escape($this->upload->display_errors('', '')) . '</div>');
            redirect($redirect_url);
            return;
        }

        $upload = $this->upload->data();
        $path = $upload['full_path'];
        $handle = fopen($path, 'r');
        if ($handle === FALSE) {
            @unlink($path);
            $this->session->set_flashdata('message', '<div class="alert alert-danger">CSV tidak dapat dibaca.</div>');
            redirect($redirect_url);
            return;
        }

        $first_line = fgets($handle);
        if ($first_line === FALSE) {
            fclose($handle);
            @unlink($path);
            $this->session->set_flashdata('message', '<div class="alert alert-danger">CSV kosong atau tidak terbaca.</div>');
            redirect($redirect_url);
            return;
        }
        $comma_columns = str_getcsv($first_line, ',');
        $semicolon_columns = str_getcsv($first_line, ';');
        $delimiter = count($semicolon_columns) > count($comma_columns) ? ';' : ',';
        rewind($handle);

        $header = fgetcsv($handle, 0, $delimiter);
        if ($header === FALSE) {
            fclose($handle);
            @unlink($path);
            $this->session->set_flashdata('message', '<div class="alert alert-danger">CSV kosong atau tidak terbaca.</div>');
            redirect($redirect_url);
            return;
        }

        $normalize = function ($value) {
            $value = strtolower(trim(preg_replace('/^\xEF\xBB\xBF/', '', (string) $value)));
            return str_replace(array(' ', '_', '-'), '', $value);
        };
        $columns = array();
        foreach ($header as $index => $column) {
            $columns[$normalize($column)] = $index;
        }

        $name_column = NULL;
        foreach (array('namapeserta', 'nmpeserta', 'nama') as $alias) {
            if (isset($columns[$alias])) {
                $name_column = $columns[$alias];
                break;
            }
        }
        $serial_column = NULL;
        foreach (array('nopeserta', 'noseri', 'nomorseri', 'serial') as $alias) {
            if (isset($columns[$alias])) {
                $serial_column = $columns[$alias];
                break;
            }
        }

        if ($name_column === NULL) {
            fclose($handle);
            @unlink($path);
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Header CSV harus memiliki kolom <strong>nama_peserta</strong>. Kolom <strong>no_peserta</strong> boleh dikosongkan agar nomor dibuat otomatis.</div>');
            redirect($redirect_url);
            return;
        }

        $rows = array();
        $errors = array();
        $used_serials = array();
        $line = 1;
        while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
            $line++;
            if (count(array_filter($row, function ($value) {
                return trim((string) $value) !== '';
            })) === 0) {
                continue;
            }

            $name = isset($row[$name_column]) ? trim((string) $row[$name_column]) : '';
            $serial = ($serial_column !== NULL && isset($row[$serial_column]))
                ? trim((string) $row[$serial_column]) : '';
            if ($name === '') {
                $errors[] = 'Baris ' . $line . ': nama peserta wajib diisi.';
                continue;
            }
            if (strlen($name) > 255) {
                $errors[] = 'Baris ' . $line . ': nama peserta melebihi 255 karakter.';
                continue;
            }
            if ($serial === '') {
                $serial = $this->_generate_no_peserta($used_serials);
            } elseif (strlen($serial) > 255) {
                $errors[] = 'Baris ' . $line . ': nomor seri melebihi 255 karakter.';
                continue;
            }
            if (isset($used_serials[$serial]) || $this->Sertifikat_peserta_model->serial_exists($serial)) {
                $errors[] = 'Baris ' . $line . ': nomor seri ' . html_escape($serial) . ' sudah digunakan.';
                continue;
            }

            $used_serials[$serial] = TRUE;
            $rows[] = array(
                'id_sertifikat' => (int) $id,
                'nm_peserta' => $name,
                'no_peserta' => $serial,
            );
        }
        fclose($handle);
        @unlink($path);

        $inserted = 0;
        if (!empty($rows)) {
            $this->db->trans_start();
            $result = $this->Sertifikat_peserta_model->insert_batch($rows);
            $this->db->trans_complete();
            if ($result === FALSE || $this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Impor gagal disimpan. Tidak ada peserta yang ditambahkan.</div>');
                redirect($redirect_url);
                return;
            }
            $inserted = count($rows);
        }

        if (empty($rows) && empty($errors)) {
            $message = '<div class="alert alert-warning">Tidak ada data peserta untuk diimpor.</div>';
        } elseif (!empty($errors)) {
            $message = '<div class="alert alert-warning">Berhasil mengimpor ' . $inserted .
                ' peserta. Baris yang dilewati: ' . html_escape(implode(' ', $errors));
            $message .= '</div>';
        } else {
            $message = '<div class="alert alert-success">Berhasil mengimpor ' . $inserted . ' peserta.</div>';
        }
        $this->session->set_flashdata('message', $message);
        redirect($redirect_url);
    }

    private function _generate_no_peserta($used_serials)
    {
        do {
            $serial = (string) random_int(1000000000, 9999999999);
        } while (isset($used_serials[$serial]) || $this->Sertifikat_peserta_model->serial_exists($serial));

        return $serial;
    }

    private function _csv_safe_value($value)
    {
        $value = (string) $value;
        if (preg_match('/^[=+\-@\t\r]/', $value)) {
            return "'" . $value;
        }
        return $value;
    }

    public function cek_sertifikat() 
    {

            $this->load->view('sertifikat_peserta/cek_sertifikat');
      
    }
     public function certificate() 
    {

            $this->load->view('sertifikat_peserta/certificate');
      
    }

}

/* End of file Sertifikat_peserta.php */
/* Location: ./application/controllers/Sertifikat_peserta.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2023-01-21 09:34:49 */
/* http://harviacode.com */