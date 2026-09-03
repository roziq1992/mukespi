<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Data_inventaris List</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-4">
                <?php echo anchor(site_url('data_inventaris/create'),'Create', 'class="btn btn-primary"'); ?>
            </div>
            <div class="col-md-4 text-center">
                <div style="margin-top: 8px" id="message">
                    <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                </div>
            </div>
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('data_inventaris/index'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('data_inventaris'); ?>" class="btn btn-default">Reset</a>
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
		<th>Kode Inven</th>
		<th>Nm Barang</th>
		<th>Merek</th>
		<th>Tipe</th>
		<th>Sn</th>
		<th>Jenis</th>
		<th>Kondisi</th>
		<th>Id Ruang</th>
		<th>Harga</th>
		<th>Stts</th>
		<th>Action</th>
            </tr><?php
            foreach ($data_inventaris_data as $data_inventaris)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $data_inventaris->kode_inven ?></td>
			<td><?php echo $data_inventaris->nm_barang ?></td>
			<td><?php echo $data_inventaris->merek ?></td>
			<td><?php echo $data_inventaris->tipe ?></td>
			<td><?php echo $data_inventaris->sn ?></td>
			<td><?php echo $data_inventaris->jenis ?></td>
			<td><?php echo $data_inventaris->kondisi ?></td>
			<td><?php echo $data_inventaris->id_ruang ?></td>
			<td><?php echo $data_inventaris->harga ?></td>
			<td><?php echo $data_inventaris->stts ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('data_inventaris/read/'.$data_inventaris->id_inven),'Read'); 
				echo ' | '; 
				echo anchor(site_url('data_inventaris/update/'.$data_inventaris->id_inven),'Update'); 
				echo ' | '; 
				echo anchor(site_url('data_inventaris/delete/'.$data_inventaris->id_inven),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
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
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </body>
</html>