<?php
    $idsertifikat = $_GET['idsertifikat'];
    if ( $idsertifikat==1){
	$nama = $_GET['nama'];
	$noseri = $_GET['noseri'];
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
    }
    if ($idsertifikat==2){
        
        	$nama = $_GET['nama'];
	$noseri = $_GET['noseri'];
	if (empty($noseri)) {
		$gambar = "./1.jpg";
	}
	else {
		$gambar = "./sertifikat.jpg";
	}
    $noseri2='*'.$noseri.'*';
	 $judul="IN HOUSE TRAINING";
    $tanggal="30 - 31 Januari 2023";
	$gambar = "./ewsdepan.jpg";
	$image = imagecreatefromjpeg($gambar);

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
// 	imagettftext($image, $size, 0, 720, 880, $black, $font, $judul);
// 		imagettftext($image, 35, 0, 800, 950, $black, $font, $tanggal);
	imagettftext($image, 30, 0, 340, 70, $black, $font, 'Nomor Seri : '.$noseri);
// 	imagettftext($image, 50, 0, 1000, 480, $black, $font, $noseri2);
// 	imagettftext($image, 100, 0, 340, 190, $black, $font2,$noseri2);
	//tampilkan di browser
	header("Content-type:  image/jpeg");
	imagejpeg($image);
	imagedestroy($image);
        
    }
     if ($idsertifikat==3){
        
        	$nama = $_GET['nama'];
	$noseri = $_GET['noseri'];
	if (empty($noseri)) {
		$gambar = "./1.jpg";
	}
	else {
		$gambar = "./sertifikat.jpg";
	}
    $noseri2='*'.$noseri.'*';
	 $judul="IN HOUSE TRAINING";
    $tanggal="30 - 31 Januari 2023";
	$gambar = "./pmkpdepan.jpg";
	$image = imagecreatefromjpeg($gambar);

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
// 	imagettftext($image, $size, 0, 720, 880, $black, $font, $judul);
// 		imagettftext($image, 35, 0, 800, 950, $black, $font, $tanggal);
	imagettftext($image, 30, 0, 340, 70, $black, $font, 'Nomor Seri : '.$noseri);
// 	imagettftext($image, 50, 0, 1000, 480, $black, $font, $noseri2);
// 	imagettftext($image, 100, 0, 340, 190, $black, $font2,$noseri2);
	//tampilkan di browser
	header("Content-type:  image/jpeg");
	imagejpeg($image);
	imagedestroy($image);
        
    }
    
     if ($idsertifikat==5){
        
        	$nama = $_GET['nama'];
	$noseri = $_GET['noseri'];
	if (empty($noseri)) {
		$gambar = "./1.jpg";
	}
	else {
		$gambar = "./sertifikat.jpg";
	}
    $noseri2='*'.$noseri.'*';
	 $judul="IN HOUSE TRAINING";
    $tanggal="30 - 31 Januari 2023";
	$gambar = "./kdetik.jpg";
	$image = imagecreatefromjpeg($gambar);

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
// 	imagettftext($image, $size, 0, 720, 880, $black, $font, $judul);
// 		imagettftext($image, 35, 0, 800, 950, $black, $font, $tanggal);
	imagettftext($image, 30, 0, 340, 70, $black, $font, 'Nomor Seri : '.$noseri);
// 	imagettftext($image, 50, 0, 1000, 480, $black, $font, $noseri2);
// 	imagettftext($image, 100, 0, 340, 190, $black, $font2,$noseri2);
	//tampilkan di browser
	header("Content-type:  image/jpeg");
	imagejpeg($image);
	imagedestroy($image);
        
    }
     if ($idsertifikat==6){
        
        	$nama = $_GET['nama'];
	$noseri = $_GET['noseri'];
	if (empty($noseri)) {
		$gambar = "./1.jpg";
	}
	else {
		$gambar = "./sertifikat.jpg";
	}
    $noseri2='*'.$noseri.'*';
	 $judul="IN HOUSE TRAINING";
    $tanggal="30 - 31 Januari 2023";
	$gambar = "./downtime.jpg";
	$image = imagecreatefromjpeg($gambar);

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
// 	imagettftext($image, $size, 0, 720, 880, $black, $font, $judul);
// 		imagettftext($image, 35, 0, 800, 950, $black, $font, $tanggal);
	imagettftext($image, 30, 0, 340, 70, $black, $font, 'Nomor Seri : '.$noseri);
// 	imagettftext($image, 50, 0, 1000, 480, $black, $font, $noseri2);
// 	imagettftext($image, 100, 0, 340, 190, $black, $font2,$noseri2);
	//tampilkan di browser
	header("Content-type:  image/jpeg");
	imagejpeg($image);
	imagedestroy($image);
        
    }

?>
