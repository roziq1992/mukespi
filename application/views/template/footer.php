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
						<span>Copyright &copy; Much Roziq 2026</span>
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
<!-- Logout Modal-->
	<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
				<div class="modal-header bg-danger text-white">
					<h5 class="modal-title font-weight-bold" id="logoutModalLabel">
						<i class="fas fa-sign-out-alt mr-2"></i>Ingin Mengakhiri Sesi?
					</h5>
					<button class="close text-white" type="button" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body text-secondary py-4" style="font-size: 14.5px;">
					Klik <strong>"Keluar"</strong> di bawah ini jika Anda ingin mengakhiri sesi kerja saat ini dan kembali ke halaman login.
				</div>
				<div class="modal-footer bg-light border-0">
					<button class="btn btn-secondary px-4 style-radius" type="button" data-dismiss="modal" style="border-radius: 8px;">
						Batal
					</button>
					<a class="btn btn-danger px-4 font-weight-bold" href="<?=base_url();?>index.php/logout" style="border-radius: 8px;">
						Keluar
					</a>
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

</body>

</html>