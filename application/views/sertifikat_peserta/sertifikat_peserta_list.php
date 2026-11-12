<?php
$tempalte=$this->db->query('SELECT 
	*
FROM
	sertifikat 
where id_sertifikat = "'.$this->input->get('id_sertifikat').'"')->row();
                                    



$judul=$tempalte->judul;

if ($tanggal==""){
$tanggal = date("Y-m-d");
}
?>

<div class="container-fluid">

    <!-- Page Heading -->
    <!-- <h1 class="h3 mb-2 text-gray-800">Tables</h1>
    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
        For more information about DataTables, please visit the <a target="_blank"
            href="https://datatables.net">official DataTables documentation</a>.</p> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Peserta Seminar</h6>
             <h6 class="m-0 font-weight-bold text-primary">Judul Seminar: <?php echo $judul; ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">
                            <div class="row" style="margin-bottom: 10px">
                                <div class="col-md-4">
                <?php echo anchor(site_url('sertifikat_peserta/create?id_sertifikat='.$this->input->get('id_sertifikat')),'Tambah', 'class="btn btn-primary"'); ?>
                                <?php echo anchor(site_url('sertifikat_peserta/export_csv?id_sertifikat='.$this->input->get('id_sertifikat')), 'Export CSV', 'class="btn btn-success"'); ?>
                                <?php echo anchor(site_url('sertifikat_peserta/template_csv?id_sertifikat='.$this->input->get('id_sertifikat')), 'Template CSV', 'class="btn btn-info"'); ?>
                                <form action="<?php echo site_url('sertifikat_peserta/import_csv'); ?>" method="post" enctype="multipart/form-data" class="form-inline" style="margin-top: 10px">
                                    <input type="hidden" name="id_sertifikat" value="<?php echo html_escape($this->input->get('id_sertifikat')); ?>">
                                    <input type="file" name="berkas" accept=".csv,.txt" required>
                                    <button type="submit" class="btn btn-primary">Import CSV</button>
                                </form>
                            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->flashdata('message') ?: ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('sertifikat_peserta/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                         <input type="hidden" class="form-control" name="id_sertifikat" value="<?php echo $this->input->get('id_sertifikat'); ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('sertifikat_peserta?id_sertifikat='.$this->input->get('id_sertifikat')); ?>" class="btn btn-default">Reset</a>
                                    <?php
                                }
                            ?>
                          <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered" style="margin-bottom: 10px">
            <tr>
        <th>No</th>
		<th>Nm Peserta</th>
		<th>No Seri</th>
		<th>Action</th>
            </tr><?php
            foreach ($sertifikat_peserta_data as $sertifikat_peserta)
            {
                ?>
                <tr>
			<td width="10px"><?php echo ++$start ?></td>
			<td><?php echo $sertifikat_peserta->nm_peserta ?></td>
			<td><?php echo $sertifikat_peserta->no_peserta ?></td>
			<td style="text-align:center" width="200px">
				
				<a href="<?=site_url('sertifikat_peserta/certificate_front/' . $sertifikat_peserta->id_peserta)?>" target="_blank" rel="noopener">Sertifikat Depan</a>
				<a href="<?=site_url('sertifikat_peserta/certificate_back/' . $sertifikat_peserta->id_peserta)?>" target="_blank" rel="noopener">Sertifikat Belakang</a>
				<?=anchor(site_url('sertifikat_peserta/download_pdf/' . $sertifikat_peserta->id_peserta), 'Download PDF 2 Sisi', 'class="btn btn-sm btn-success"')?>
				<?php 
				echo ' | '; 
				echo anchor(site_url('sertifikat_peserta/update?id='.$sertifikat_peserta->id_peserta.'&id_sertifikat='.$this->input->get('id_sertifikat')),'Update'); 
				echo ' | '; 
				echo anchor(site_url('sertifikat_peserta/delete?id='.$sertifikat_peserta->id_peserta.'&id_sertifikat='.$this->input->get('id_sertifikat')),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
				?>
			</td>
		</tr>
                <?php
            }
            ?>
        </table>
        <div class="row">
            <div class="col-md-6">
                <a href="#" class="btn btn-primary">Total Record : <?php echo $total_rows ?></a>
		<?php echo anchor(site_url('sertifikat_peserta/excel?id_sertifikat='.$this->input->get('id_sertifikat')), 'Excel', 'class="btn btn-primary"'); ?>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
                     </div>
                            <!-- </div> -->
                        </div>
                    </div>