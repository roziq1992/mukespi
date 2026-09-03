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
        <h2 style="margin-top:0px">Data_inventaris Read</h2>
        <table class="table">
	    <tr><td>Kode Inven</td><td><?php echo $kode_inven; ?></td></tr>
	    <tr><td>Nm Barang</td><td><?php echo $nm_barang; ?></td></tr>
	    <tr><td>Merek</td><td><?php echo $merek; ?></td></tr>
	    <tr><td>Tipe</td><td><?php echo $tipe; ?></td></tr>
	    <tr><td>Sn</td><td><?php echo $sn; ?></td></tr>
	    <tr><td>Jenis</td><td><?php echo $jenis; ?></td></tr>
	    <tr><td>Kondisi</td><td><?php echo $kondisi; ?></td></tr>
	    <tr><td>Id Ruang</td><td><?php echo $id_ruang; ?></td></tr>
	    <tr><td>Harga</td><td><?php echo $harga; ?></td></tr>
	    <tr><td>Stts</td><td><?php echo $stts; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('data_inventaris') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>