 <?php
                                        $start = 0;
                                    $tanggal1=$this->input->get('tanggal');
                                     $idindikator=$this->input->get('id');
                                    $tanggal2=$this->input->get('tanggal2');
                                    $jml=0;
                                    if ($tanggal1==""){
                                    $tanggal1 = date("Y-m-d");
                                    $tanggal2 = date("Y-m-d");
                                    }
                                    if ($tanggal1==""){
                                    $tanggal1 = date("Y-m-d");
                                    $tanggal2 = date("Y-m-d");
                                    }
                                    //  echo  $tahun ;
                                    //  echo  $bulan ;
                                     
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
                                	where  mutu_indikator.tanggal  between  "'.$tanggal1.'"  and "'.$tanggal2.'" and mutu_indikator.id_indikator="'.$idindikator.'"
                                	group by month( mutu_indikator.tanggal ) 
                                    ORDER BY
                                	MONTH ( mutu_indikator.tanggal ) ASC')->result();
                                	$tnum=0;
                                	$tdenum=0;
                                	$rcapaian=0;
                                	$no=0;
                                	foreach ($mutuindikator as $data) {
                                	   $tnum=$data->num+$tnum;
                                       $tdenum=$data->demu+$tdenum;
                                       $rcapaian=$data->capaian+$rcapaian;
                                       $no++;
                                    } 
                                    if($rcapaian>0){
                                    $rata2=$rcapaian/$no;
                                    }else{
                                        $rata2=0;
                                    }
                                	



?>
				</div>
				<!-- /.container-fluid -->

			</div>
			<!-- End of Main Content -->

			<!-- Footer -->
			<footer class="sticky-footer bg-white">
				<div class="container my-auto">
					<div class="copyright text-center my-auto">
						<span>Copyright &copy; Much Roziq 2022</span>
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
  type: 'bar',
  data: {
    labels: [<?php foreach ($mutuindikator as $mutuindikatorx) {
         echo '"' . $mutuindikatorx->bulan . '",';
    } ?>],
    datasets: [{
      label: "Capaian Indikator",
      lineTension: 0.3,
      backgroundColor: "rgba(97, 175, 239, 1)",
      borderColor: "rgba(97, 175, 239, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(97, 175, 239, 1)",
      pointBorderColor: "rgba(97, 175, 239, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(97, 175, 239, 1)",
      pointHoverBorderColor: "rgba(97, 175, 239, 1)",
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

        </script>
</body>

</html>
