<?php
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
// require 'db.php';
?>
<?php
$servername = 'localhost';
$username = 'rumahs16_ppi';
$password = 'Y2K&v9**';
$dbname = 'rumahs16_ppi';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Certificate Online RS AIRLANGGA JOMBANG</title>
		<link rel="stylesheet" type="text/css" href="<?=base_url('assets/');?>css/bootstrap.min.css">
	</head>
	<body>
		<div class="container">
		    <br>
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-primary">
		  			<div class="panel-heading"><h2>Sertifikat Online RS AIRLANGGA</h2></div>
		 				<div class="panel-body">
							<div class="konten">
								<form action="cek_sertifikat" method="post">
								    <strong>Nomor Seri</strong><br>
									<input type="text" class="form-control" id="keyword" name="keyword" placeholder="Nomor Seri" ></input>
									<input type="submit" class="btn btn-primary" value="Check" name="certificate"></input><br><br><br>
								</form>
							<?php
									if ($_SERVER['REQUEST_METHOD'] === 'POST') {
										$keyword = $_POST["keyword"];

										$sql = "SELECT * FROM sertifikat_peserta WHERE no_peserta = '".$keyword."'";
										$result = $conn->query($sql);
										if ($result->num_rows > 0) {
											while ($row = $result->fetch_assoc()){
												$hasil = $row['nm_peserta'];
												$noseri = $row['no_peserta'];
											}
										} else {
											echo 'no result';
										}
									}
									$conn->close();
								?>
								<form action="hasil.php" method="post">
									<strong>Hasil Pencarian</strong><br>
									<input type="text" class="form-control" name="namadisable" disabled="yes" value="<?php echo (isset($keyword))?$hasil:'your name will shown automatic';?>" >
									<input type="hidden" name="noseri" value="<?php echo (isset($keyword))?$hasil:'your name will shown automatic';?>">
									<input type="hidden" name="noseri" value="<?php echo (isset($keyword))?$noseri:'0';?>">
									<input id="get" type="submit" class="btn btn-primary" value="Get Certificate Now" name="certificate"></input>
								</form>
								<br><h4><strong>Notes:</strong> After certificate generated please save as file.jpg</h4>
								<!--<span>https://github.com/ahmadbagwi/sertifikat-online</span>-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
