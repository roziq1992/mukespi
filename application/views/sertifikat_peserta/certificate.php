<?php
	$nama = "Muchammad Rozik,S.Kom";
// 	if (empty($nama)) {
// 		$gambar = base_url('assets/')."img/1.jpg";
// 	}
// 		else {
		$gambar = base_url('assets/')."application/views/sertifikat_peserta/sertifikat.jpg";
// 	}

	$image = imagecreatefromjpeg($gambar);
	$white = imageColorAllocate($image, 255, 255, 255);
	$black = imageColorAllocate($image, 19, 90, 19);
	$font = base_url('assets/')."img/OpenSans-Italic.ttf";
	$size = 200;
	//definisikan lebar gambar agar posisi teks selalu ditengah berapapun jumlah hurufnya
	$image_width = imagesx($image);  
	//membuat textbox agar text centered
	$text_box = imagettfbbox($size,0,$font,$nama);
	$text_width = $text_box[2]-$text_box[0]; // lower right corner - lower left corner
	$text_height = $text_box[2]-$text_box[1];
	$x = ($image_width/2) - ($text_width/2);
	//generate sertifikat beserta namanya
	imagettftext($image, $size, 0, $x, 1700, $black, $font, $nama);
	//tampilkan di browser
	header("Content-type:  image/jpeg");
	imagejpeg($image);
	imagedestroy($image);
?>
