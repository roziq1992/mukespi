 <?php
                                    $start = 0;
                                    $tahun=$this->input->get('tahun');
                                    $bulan=$this->input->get('bulan');
                                     if ($bulan==""){
                                    $bulan = date("m");
                                    }
                                    if ($tahun==""){
                                    $tahun = date("Y");
                                    }
                                    $cuci_tangan_data=$this->db->query('SELECT 
	month( tanggal) AS bulan,
	SUM(case when month(tanggal) = 1 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N1,
SUM(case when month(tanggal) = 1 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D1,
	SUM(case when month(tanggal) = 2 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N2,
SUM(case when month(tanggal) = 2 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D2,
		SUM(case when month(tanggal) = 3 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N3,
SUM(case when month(tanggal) = 3 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D3,
		SUM(case when month(tanggal) = 4 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N4,
SUM(case when month(tanggal) = 4 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D4,
	SUM(case when month(tanggal) = 5 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N5,
SUM(case when month(tanggal) = 5 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D5,
SUM(case when month(tanggal) = 6 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N6,
SUM(case when month(tanggal) = 6 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D6,
SUM(case when month(tanggal) = 7 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N7,
SUM(case when month(tanggal) = 7 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D7,
SUM(case when month(tanggal) = 8 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N8,
SUM(case when month(tanggal) = 8 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D8,
SUM(case when month(tanggal) = 9 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N9,
SUM(case when month(tanggal) = 9 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D9,
SUM(case when month(tanggal) = 10 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N10,
SUM(case when month(tanggal) = 10 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D10,
SUM(case when month(tanggal) = 11 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N11,
SUM(case when month(tanggal) = 11 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D11,
SUM(case when month(tanggal) = 12 and cuci_tangan.cucitangan = "Ya" then 1 else 0 end) as N12,
SUM(case when month(tanggal) = 12 and cuci_tangan.cucitangan = "Tidak" then 1 else 0 end) as D12
FROM
	cuci_tangan 
where year(cuci_tangan.tanggal) = "'.$tahun.'"
ORDER BY
	MONTH ( tanggal ) ASC')->row();
                                    



$jan=$cuci_tangan_data->N1;
$feb=$cuci_tangan_data->N2;
$mar=$cuci_tangan_data->N3;
$apr=$cuci_tangan_data->N4;
$mei=$cuci_tangan_data->N5;
$jun=$cuci_tangan_data->N6;
$jul=$cuci_tangan_data->N7;
$ags=$cuci_tangan_data->N8;
$sep=$cuci_tangan_data->N9;
$okt=$cuci_tangan_data->N10;
$nov=$cuci_tangan_data->N11;
$des=$cuci_tangan_data->N12;

$tjan=$cuci_tangan_data->D1;
$tfeb=$cuci_tangan_data->D2;
$tmar=$cuci_tangan_data->D3;
$tapr=$cuci_tangan_data->D4;
$tmei=$cuci_tangan_data->D5;
$tjun=$cuci_tangan_data->D6;
$tjul=$cuci_tangan_data->D7;
$tags=$cuci_tangan_data->D8;
$tsep=$cuci_tangan_data->D9;
$tokt=$cuci_tangan_data->D10;
$tnov=$cuci_tangan_data->D11;
$tdes=$cuci_tangan_data->D12;
?>
				</div>
				<!-- /.container-fluid -->

			</div>
			<!-- End of Main Content -->

			<!-- Footer -->
			<footer class="sticky-footer bg-white">
				<div class="container my-auto">
					<div class="copyright text-center my-auto">
						<span>Copyright &copy; Much Roziq 2023</span>
					</div>
				</div>
			</footer>
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
					<a class="btn btn-primary" href="<?=base_url();?>index.php/Auth/logout">Logout</a>
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
	

  <!-- Custom scripts for all pages-->

  <!-- Page level plugins -->
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
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
      label: "Cuci Tangan",
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
      data: [<?=$jan?>,<?=$feb ?>,<?=$mar ?>,<?=$apr ?>, <?=$mei ?>,<?=$jun ?>, <?=$jul ?>, <?=$ags ?>, <?=$sep ?>, <?=$okt ?>, <?=$nov ?>,<?=$des ?>],
      fill:false,
        
    },{
      label: "Tidak Cuci Tangan",
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
      data: [<?=$tjan ?>,<?=$tfeb ?>,<?=$tmar ?>,<?=$tapr ?>, <?=$tmei ?>,<?=$tjun ?>, <?=$tjul ?>, <?=$tags ?>, <?=$tsep ?>, <?=$tokt ?>, <?=$tnov ?>,<?=$tdes ?>],
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

        </script>
</body>

</html>
