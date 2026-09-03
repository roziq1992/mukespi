<div class="container-fluid">

    <!-- Page Heading -->
    <!-- <h1 class="h3 mb-2 text-gray-800">Tables</h1>
    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
        For more information about DataTables, please visit the <a target="_blank"
            href="https://datatables.net">official DataTables documentation</a>.</p> -->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Analisa : <?php echo $this->input->get('judul'); ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php echo anchor(site_url('mutu_fmea/create?id='.$this->input->get('id').'&judul='.$this->input->get('judul')),'Tambah', 'class="btn btn-primary"'); ?>
                 <?php echo anchor(site_url('Mutu_indikator?id='.$this->input->get('id').'&judul='.$this->input->get('judul')),'Kembali', 'class="btn btn-primary"'); ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('mutu_fmea/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('mutu_fmea'); ?>" class="btn btn-default">Reset</a>
                                    <?php
                                }
                            ?>
                          <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
                         </div>
                            <!-- </div> -->
                        </div>
                    </div>
        <table class="table table-bordered" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Tanggal Awal</th>
		<th>Tanggal Akhir</th>
		<!--<th>Id Indikator</th>-->
		<th>Tahun Periode</th>
		<th>Periode Lapor</th>
		<th>Target</th>
		<th>Analisa</th>
		<th>Action</th>
		<th>Rekomendasi</th>
            </tr><?php
            foreach ($mutu_fmea_data as $mutu_fmea)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $mutu_fmea->tanggal1 ?></td>
			<td><?php echo $mutu_fmea->tanggal2 ?></td>
			<!--<td><?php echo $mutu_fmea->id_indikator ?></td>-->
			<td><?php echo $mutu_fmea->tahun_periode ?></td>
			<td><?php echo $mutu_fmea->periode_lapor ?></td>
			<td><?php echo $mutu_fmea->target ?></td>
			<td><?php echo $mutu_fmea->analisa ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('mutu_fmea/read/'.$mutu_fmea->id_fmea),'Read'); 
				echo ' | '; 
				echo anchor(site_url('mutu_fmea/update?idfmea='.$mutu_fmea->id_fmea.'&id='.$mutu_fmea->id_indikator),'Update'); 
				echo ' | '; 
				echo anchor(site_url('mutu_fmea/delete?idfmea='.$mutu_fmea->id_fmea.'&id='.$mutu_fmea->id_indikator.'&judul='.$this->input->get('judul')),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
				?>
			</td>
			<td style="text-align:center" width="200px">
			    	<?php if($mutu_fmea->rekomendasi=='') {?>
			    	<?php 
				  echo anchor(site_url('mutu_fmea/updaterekomendasi?idfmea='.$mutu_fmea->id_fmea.'&id='.$mutu_fmea->id_indikator),'Rekomendasi'); 
			
			}else{	?>
			    <?php 
			 //   echo $mutu_fmea->rekomendasi ;
			    	echo anchor(site_url('mutu_fmea/updaterekomendasi?idfmea='.$mutu_fmea->id_fmea.'&id='.$mutu_fmea->id_indikator),''.$mutu_fmea->rekomendasi.''); 
			    
			    ?>
			    <?php } ?>
				
			</td>
		</tr>
                <?php
            }
            ?>
        </table>
        <div class="row">
            <div class="col-md-6">
                <a href="#" class="btn btn-primary">Total Record : <?php echo $total_rows ?></a>
		<?php echo anchor(site_url('mutu_fmea/excel'), 'Excel', 'class="btn btn-primary"'); ?>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </body>
</html>