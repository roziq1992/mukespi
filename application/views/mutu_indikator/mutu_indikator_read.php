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
        <h2 style="margin-top:0px">Mutu_indikator Read</h2>
        <table class="table">
	    <tr><td>Tanggal</td><td><?php echo $tanggal; ?></td></tr>
	    <tr><td>Id Indikator</td><td><?php echo $id_indikator; ?></td></tr>
	    <tr><td>Num</td><td><?php echo $num; ?></td></tr>
	    <tr><td>Demu</td><td><?php echo $demu; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('mutu_indikator') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>