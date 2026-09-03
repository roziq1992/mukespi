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
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
										$keyword = $_POST["noseri"];

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
								
	$nama = $hasil;
	$noseri = $_POST['noseri'];
	if (empty($noseri)) {
		$gambar = "./1.jpg";
	}
	else {
		$gambar = "./sertifikat.jpg";
	}
    $noseri2='*'.$noseri.'*';
	 $judul="IN HOUSE TRAINING";
    $tanggal="30 - 31 Januari 2023";
	$gambar = "./sertifikat.jpg";
	$image = imagecreatefromjpeg($gambar);
	$gambar2 = "./bercode.jpg";
	$image2 = imagecreatefromjpeg($gambar2);
	$white = imageColorAllocate($image, 255, 255, 255);
	$black = imageColorAllocate($image, 0, 0, 0);
	$font = "./OpenSans-Italic.ttf";
	$font2 = "./fre3of9x.ttf";
	$size = 50;
	//definisikan lebar gambar agar posisis teks selalu ditengah berapapun jumlah hurufnya
	$image_width = imagesx($image);  
	//membuat textbox agar text centered
	$text_box = imagettfbbox($size,0,$font,$nama);
	$text_width = $text_box[2]-$text_box[0]; // lower right corner - lower left corner
	$text_height = $text_box[2]-$text_box[1];
	$x = ($image_width/2) - ($text_width/2);
	
	
	//generate sertifikat beserta namanya
	imagettftext($image, $size, 0, $x, 600, $black, $font, $nama);
	imagettftext($image, $size, 0, 720, 880, $black, $font, $judul);
		imagettftext($image, 35, 0, 800, 950, $black, $font, $tanggal);
	imagettftext($image, 30, 0, 340, 70, $black, $font, 'Nomor Seri : '.$noseri);
// 	imagettftext($image, 50, 0, 1000, 480, $black, $font, $noseri2);
	imagettftext($image, 100, 0, 340, 190, $black, $font2,$noseri2);
	//tampilkan di browser
	header("Content-type:  image/jpeg");
	imagejpeg($image);
	imagedestroy($image);

?>
