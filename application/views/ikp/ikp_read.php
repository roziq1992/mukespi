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
        <h2 style="margin-top:0px">Ikp Read</h2>
        <table class="table">
	    <tr><td>Nm Pasien</td><td><?php echo $nm_pasien; ?></td></tr>
	    <tr><td>Rm</td><td><?php echo $rm; ?></td></tr>
	    <tr><td>Ruang</td><td><?php echo $ruang; ?></td></tr>
	    <tr><td>Kelamin</td><td><?php echo $kelamin; ?></td></tr>
	    <tr><td>Penangung Jawab</td><td><?php echo $penangung_jawab; ?></td></tr>
	    <tr><td>Tgl Masuk</td><td><?php echo $tgl_masuk; ?></td></tr>
	    <tr><td>Jam Masuk</td><td><?php echo $jam_masuk; ?></td></tr>
	    <tr><td>Tgl Kejadian</td><td><?php echo $tgl_kejadian; ?></td></tr>
	    <tr><td>Jam Kejadian</td><td><?php echo $jam_kejadian; ?></td></tr>
	    <tr><td>Insiden</td><td><?php echo $insiden; ?></td></tr>
	    <tr><td>Krologis</td><td><?php echo $krologis; ?></td></tr>
	    <tr><td>Jns Insiden</td><td><?php echo $jns_insiden; ?></td></tr>
	    <tr><td>Pelapor Pertama</td><td><?php echo $pelapor_pertama; ?></td></tr>
	    <tr><td>Insiden Terjadipd</td><td><?php echo $insiden_terjadipd; ?></td></tr>
	    <tr><td>Insiden Meyangkut</td><td><?php echo $insiden_meyangkut; ?></td></tr>
	    <tr><td>Tempat Insiden</td><td><?php echo $tempat_insiden; ?></td></tr>
	    <tr><td>Insiden Terjadipd2</td><td><?php echo $insiden_terjadipd2; ?></td></tr>
	    <tr><td>Unit Penyebab</td><td><?php echo $unit_penyebab; ?></td></tr>
	    <tr><td>Akibat Insiden</td><td><?php echo $akibat_insiden; ?></td></tr>
	    <tr><td>Tindakan</td><td><?php echo $tindakan; ?></td></tr>
	    <tr><td>Tindakan Oleh</td><td><?php echo $tindakan_oleh; ?></td></tr>
	    <tr><td>Kejadian Terulang</td><td><?php echo $kejadian_terulang; ?></td></tr>
	    <tr><td>Ket Kejadian Terulang</td><td><?php echo $ket_kejadian_terulang; ?></td></tr>
	    <tr><td>Pelapor</td><td><?php echo $pelapor; ?></td></tr>
	    <tr><td>Penerima</td><td><?php echo $penerima; ?></td></tr>
	    <tr><td>Tgl Lapor</td><td><?php echo $tgl_lapor; ?></td></tr>
	    <tr><td>Grading Resiko</td><td><?php echo $grading_resiko; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('ikp') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>
        </body>
</html>