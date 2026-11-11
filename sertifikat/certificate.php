<?php
$nama = isset($_GET['nama']) ? trim($_GET['nama']) : '';
$noseri = isset($_GET['noseri']) ? trim($_GET['noseri']) : '';

if ($noseri === '') {
    http_response_code(400);
    exit('Nomor seri sertifikat tidak ditemukan.');
}

if (!extension_loaded('gd') || !function_exists('imagecreatefromjpeg')) {
    http_response_code(500);
    exit('Ekstensi PHP GD belum aktif. Aktifkan extension=gd di php.ini lalu restart Apache.');
}

$gambar = __DIR__ . '/sertifikat.jpg';
$font = __DIR__ . '/OpenSans-Italic.ttf';
$font2 = __DIR__ . '/fre3of9x.ttf';

if (!is_file($gambar) || !is_file($font) || !is_file($font2)) {
    http_response_code(500);
    exit('Template atau font sertifikat tidak ditemukan.');
}

$image = imagecreatefromjpeg($gambar);
if ($image === FALSE) {
    http_response_code(500);
    exit('Template gambar sertifikat tidak dapat dibuka.');
}

$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$size = 50;
$text_box = imagettfbbox($size, 0, $font, $nama);
$text_width = $text_box[2] - $text_box[0];
$x = (imagesx($image) / 2) - ($text_width / 2);
$noseri_barcode = '*' . $noseri . '*';

imagettftext($image, $size, 0, (int) $x, 600, $black, $font, $nama);
imagettftext($image, $size, 0, 720, 880, $black, $font, 'IN HOUSE TRAINING');
imagettftext($image, 35, 0, 800, 950, $black, $font, '30 - 31 Januari 2023');
imagettftext($image, 30, 0, 340, 70, $black, $font, 'Nomor Seri : ' . $noseri);
imagettftext($image, 100, 0, 340, 190, $black, $font2, $noseri_barcode);

header('Content-Type: image/jpeg');
imagejpeg($image);
imagedestroy($image);
