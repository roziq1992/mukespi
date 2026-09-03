<?php
$mutuindikator=$this->db->query('SELECT 
                                	year( mutu_indikator.tanggal ) AS tahun,
                                	MONTHNAME( mutu_indikator.tanggal ) AS bulan,
                                	SUM(mutu_indikator.num) as num,
                                	sum(mutu_indikator.demu) as demu,
                                	case when list_indikator.jenis <> "PPI"  then SUM(mutu_indikator.num)/SUM(mutu_indikator.demu)*100
                                	else SUM(mutu_indikator.num)/SUM(mutu_indikator.demu)*1000 end
                                	as capaian,
                                	mutu_indikator.target
                                	from mutu_indikator inner join list_indikator on mutu_indikator.id_indikator=list_indikator.id_indikator
                                	where  mutu_indikator.tanggal  between  "'.$tanggal1.'"  and "'.$tanggal2.'" and mutu_indikator.id_indikator="'.$id_indikator.'"
                                	group by month( mutu_indikator.tanggal ) 
                                    ORDER BY
                                	MONTH ( mutu_indikator.tanggal ) ASC')->result();
                                    

$indikator=$this->db->query('SELECT judul,
	target,ket_num,ket_denum,ket_judul
	
FROM
	list_indikator 
where id_indikator = "'.$id_indikator.'"')->row();
                                    



$target=$indikator->target;
$ketnum=$indikator->ket_num;
$ketdenum=$indikator->ket_denum;
$ketjudul=$indikator->ket_judul;
$judul=$indikator->judul;


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
        <h2 style="margin-top:0px">Laporan Analisa</h2>
        <table class="table">
	    <tr><td>Tanggal Awal</td><td><?php echo $tanggal1; ?></td></tr>
	    <tr><td>Tanggal Akhir</td><td><?php echo $tanggal2; ?></td></tr>
	    <tr><td>Indikator</td><td><?php echo $judul; ?></td></tr>
	    <tr><td>Keterangan Indikator</td><td><?php echo $ketjudul; ?></td></tr>
	    <tr><td>Tahun Periode</td><td><?php echo $tahun_periode; ?></td></tr>
	    <tr><td>Periode Lapor</td><td><?php echo $periode_lapor; ?></td></tr>
	    <tr><td>Target</td><td><?php echo $target; ?></td></tr>
	    <tr><td>Analisa</td><td><?php echo $analisa; ?></td></tr>
	    <!--<tr><td></td><td><button onclick="window.print()">Cetak Halaman Web</button></td></tr>-->
	</table>
	<table class="table table-bordered" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Bulan</th>
		<th>Capaian</th>
		<th>Target</th>
	
            </tr><?php
            $start=0;
            foreach ($mutuindikator as $mutu_indikator)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $mutu_indikator->bulan ?></td>
			<td><?php echo number_format($mutu_indikator->capaian) ?></td>
			<td><?php echo $mutu_indikator->target ?></td>
			
		</tr>
                <?php
            }
            ?>
        </table>
    </div> 
    </div> 
    </div> 
   
    <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Grafik Capaian Mutu Per Bulan</h6>
                  <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
         
          </div>
    </div> 
    
</div> 
 <?php
                                   
                                    




?>
				</div>
				<!-- /.container-fluid -->

			</div>
			<!-- End of Main Content -->

			<!-- Footer -->
		
			<!-- End of Footer -->

		</div>
		<!-- End of Content Wrapper -->

	</div>
	<!-- End of Page Wrapper -->

	<!-- Scroll to Top Button-->
	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>

	<!-- Logout Modal-->
	<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
					<a class="btn btn-primary" href="<?=base_url('logout');?>">Logout</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Bootstrap core JavaScript-->
	<script src="<?=base_url('assets/');?>vendor/jquery/jquery.min.js"></script>
	<script src="<?=base_url('assets/');?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="<?=base_url('assets/');?>vendor/jquery-easing/jquery.easing.min.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="<?=base_url('assets/');?>js/sb-admin-2.min.js"></script>
	
	
	 <script src="<?=base_url('assets/');?>vendor/jquery/jquery.min.js"></script>
  <script src="<?=base_url('assets/');?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?=base_url('assets/');?>vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?=base_url('assets/');?>vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <!--<script src="<?=base_url('assets/');?>js/demo/chart-area-demo.js"></script>-->
  <!--<script src="<?=base_url('assets/');?>js/demo/chart-pie-demo.js"></script>-->
<script>
            // Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: [<?php foreach ($mutuindikator as $mutuindikatorx) {
         echo '"' . $mutuindikatorx->bulan . '",';
    } ?>],
    datasets: [{
      label: "Capaian Indikator",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.05)",
      borderColor: "rgba(78, 115, 223, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: [<?php foreach ($mutuindikator as $mutuindikatorxx) {
         echo '"' . $mutuindikatorxx->capaian . '",';
    } ?>],
      fill:false,
        
    },{
      label: "Target Indikator",
      lineTension: 0.3,
      backgroundColor: "rgba(255, 0, 46, 1)",
      borderColor: "rgba(255, 0, 46, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(255, 0, 46, 1)",
      pointBorderColor: "rrgba(255, 0, 46, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(255, 0, 46, 1)",
      pointHoverBorderColor: "rgba(255, 0, 46, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: [<?php foreach ($mutuindikator as $mutuindikatorxxx) {
         echo '"' . $mutuindikatorxxx->target . '",';
    } ?>],
     fill:false,
        
    }],
     
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return '' + number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ':' + number_format(tooltipItem.yLabel);
        }
      }
    }
  }
});
$(document).on('click','body *',function(){
   window.print()
});
        </script>