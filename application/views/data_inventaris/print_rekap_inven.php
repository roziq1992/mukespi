<?php
$dataruang=$this->db->query('SELECT * FROM `data_ruang`;')->result();

                                
?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title><?= $title ?? "" ;?></title>

	<!-- Custom fonts for this template-->
	<!--<link href="<?=base_url('assets/');?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">-->
	<!--<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">-->

	<!-- Custom styles for this template-->
	<link href="<?=base_url('assets/');?>css/sb-admin-2.min.css" rel="stylesheet">

</head>
<style>
    .shadow {
    box-shadow: 0 33.15rem 2.75rem 0 rgba(255, 99, 71, 0)!important;
}
</style>
<body id="page-top">

	<!-- Page Wrapper -->
	
		<!-- End of Sidebar -->

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">

		
				<!-- End of Topbar -->

				<!-- Begin Page Content -->
				<div class="container-fluid">	

 <div class="card shadow mb-4">
     <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">
    <body>
        <h2 style="margin-top:0px">Laporan Inventaris RS Airlangga Jombang</h2>

	<table class="table table-bordered" style="margin-bottom: 10px">
	    <tr>
        <th>Kode Invent</th>
		<th>Nama Barang</th>
		<th>Merek</th>
		<th>Tipe</th>
		<th>Sn</th>
		<th>Jenis</th>
		<th>Kondisi</th>
        </tr>
	    <?php
	     $start1=1;
	    foreach ($dataruang as $dataruang)
            {
        ?>
        <tr>
        <td colspan="8"><div class="card-header py-1">
        <h6 class="m-0 font-weight-bold text-primary"><?=$start1.'. '.$dataruang->nm_ruang?></h6>
        </div>
        </td>
        </tr>
  <?php
            $datainvent=$this->db->query('SELECT * FROM `data_inventaris` where id_ruang = "'.$dataruang->id_ruang.'" order by `id_ruang`,`nm_barang`  ;')->result();
            $start=0;
            foreach ($datainvent as $datainvent)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $datainvent->nm_barang ?></td>
			<td><?php echo $datainvent->merek ?></td>
			<td><?php echo $datainvent->tipe ?></td>
			<td><?php echo $datainvent->sn ?></td>
			<td><?php echo $datainvent->jenis ?></td>
			<td><?php echo $datainvent->kondisi ?></td>
			
		</tr>
                <?php
            }
        $start1=$start1+1;
        }
            ?>
        </table>
