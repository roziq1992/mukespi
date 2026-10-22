<?php
/**
 * Cetak label barcode (80 x 30 mm) untuk 1 unit inventaris.
 *
 * Penting: tinggi isi label dibuat jauh lebih kecil daripada tinggi halaman
 * (label ~20mm di halaman 30mm) supaya tetap 1 halaman walaupun di dialog
 * print Chrome Anda menyalakan "Headers and footers" / "Footer" dan memakai
 * kertas thermal yang lebih kecil.
 *
 * Ukuran label bisa diganti lewat query string, contoh:
 *   print_barcode_local/12?w=62&h=29
 *   print_barcode_local/12?w=100&h=50
 *   print_barcode_local/12?paper=a4
 */
$w = isset($_GET['w']) ? (float) $_GET['w'] : 80;
$h = isset($_GET['h']) ? (float) $_GET['h'] : 30;
if ($w < 25)  { $w = 25; }
if ($w > 300) { $w = 300; }
if ($h < 12)  { $h = 12; }
if ($h > 300) { $h = 300; }

$paper = isset($_GET['paper']) ? $_GET['paper'] : 'custom';
if ($paper === 'a4') {
    $w = 210;
    $h = 297;
}

// tinggi area barcode & lebar gambar (mm) mengikuti ukuran label.
// Disisakan ruang kosong agar label tetap muat 1 halaman walau dialog print
// memakai header/footer (Chrome memotong area cetak ~8mm).
$barH = max(8, min(14, $h - 16));
$barW = max(20, min(56, $w - 6));

$codeInv = isset($inventaris) && $inventaris->kode_inven ? $inventaris->kode_inven : 'INV-0000';

$presets = array(
    array('qs' => 'w=58&h=40',  'label' => '58 x 40 mm'),
    array('qs' => 'w=62&h=29',  'label' => '62 x 29 mm'),
    array('qs' => 'w=80&h=30',  'label' => '80 x 30 mm'),
    array('qs' => 'w=100&h=50', 'label' => '100 x 50 mm'),
    array('qs' => 'paper=a4',   'label' => 'A4 (210 x 297 mm)'),
);
$currentQs = ($paper === 'a4') ? 'paper=a4' : ('w=' . (int) $w . '&h=' . (int) $h);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print Barcode - <?php echo htmlspecialchars($codeInv); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            padding: 16px;
        }

        /* ---------- Ukuran label ---------- */
        .barcode-label {
            width: <?php echo $w; ?>mm;
            max-width: 100%;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            overflow: hidden;
            padding: 1mm 2mm;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            border: 1px solid #e8ecf0;
        }

        /* Area barcode: tinggi FIX (mm), tidak ikut mengikuti tinggi gambar asli */
        .barcode-label .barcode-image {
            flex: 0 0 <?php echo $barH; ?>mm;
            height: <?php echo $barH; ?>mm;
            max-height: <?php echo $barH; ?>mm;
            min-height: 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .barcode-label .barcode-image img {
            display: block;
            max-width: 100%;
            max-height: <?php echo $barH; ?>mm;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        /* Tulisan kode kecil supaya tidak melebar / melompati halaman */
        .barcode-label .barcode-code {
            font-size: 10px;
            font-weight: 700;
            color: #000000;
            letter-spacing: 0.5px;
            line-height: 1.1;
            margin-top: 0.8mm;
            white-space: nowrap;
            overflow: hidden;
            max-width: 100%;
            text-overflow: ellipsis;
        }

        /* Fallback bila gambar barcode gagal dimuat (mis. tanpa internet) */
        .barcode-fallback {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            border: 1px dashed #adb5bd;
            border-radius: 4px;
            padding: 1mm 2mm;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ---------- Kontrol di layar (tidak ikut dicetak) ---------- */
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            align-items: center;
            max-width: 560px;
        }
        .toolbar .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-print { background: #2c5f8a; color: #fff; }
        .btn-print:hover { background: #1b3a5c; }
        .btn-close { background: #e9ecef; color: #495057; }
        .btn-size { background: #fff; color: #2c5f8a; border: 1px solid #cfd8e3; }
        .btn-size.active { background: #2c5f8a; color: #fff; border-color: #2c5f8a; }

        .print-hint {
            max-width: 560px;
            background: #fff8e1;
            border: 1px solid #ffe08a;
            color: #7a5b00;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
            line-height: 1.6;
            text-align: center;
        }
        .print-hint strong { color: #5c4400; }
        .print-hint ul { margin: 4px 0 0 18px; text-align: left; display: inline-block; }

        /* ---------- Aturan cetak ---------- */
        @page {
            size: <?php echo $w; ?>mm <?php echo $h; ?>mm;
            margin: 0;
        }

        @media print {
            html, body {
                width: <?php echo $w; ?>mm;
                max-width: 100%;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;   /* cegah luapan ke halaman 2 */
                background: #ffffff !important;
            }
            body {
                display: block !important;
                min-height: 0 !important;
            }
            .barcode-label {
                width: <?php echo $w; ?>mm;
                max-width: 100%;
                box-shadow: none !important;
                border: none !important;
                margin: 0 auto;
                page-break-inside: avoid;
                break-inside: avoid;
                page-break-after: avoid;
            }
            .barcode-label .barcode-image {
                flex: 0 0 <?php echo $barH; ?>mm;
                height: <?php echo $barH; ?>mm;
                max-height: <?php echo $barH; ?>mm;
            }
            .barcode-label .barcode-image img {
                max-height: <?php echo $barH; ?>mm;
                width: auto;
                height: auto;
            }
            .barcode-code {
                font-size: 10px;
                letter-spacing: 0.5px;
            }
            .no-print {
                display: none !important;
            }
            /* pastikan gambar barcode tidak dihapus saat print */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

<div class="barcode-label">
    <div class="barcode-image">
        <img src="https://barcode.tec-it.com/barcode.ashx?data=<?php echo urlencode($codeInv); ?>&amp;code=Code128&amp;dpi=300&amp;width=<?php echo (int) $barW; ?>&amp;height=<?php echo (int) $barH; ?>"
             alt="Barcode <?php echo htmlspecialchars($codeInv); ?>"
             onerror="this.style.display='none'; document.getElementById('barcodeFallback').style.display='block';" />
        <div id="barcodeFallback" class="barcode-fallback" style="display:none;">
            <?php echo htmlspecialchars($codeInv); ?>
        </div>
    </div>
    <div class="barcode-code">
        <?php echo htmlspecialchars($codeInv); ?>
    </div>
</div>

<div class="toolbar no-print">
    <?php foreach ($presets as $p): ?>
        <a class="btn btn-size<?php echo $p['qs'] === $currentQs ? ' active' : ''; ?>"
           href="?<?php echo $p['qs']; ?>"><?php echo $p['label']; ?></a>
    <?php endforeach; ?>
    <button class="btn btn-print" onclick="window.print()">&#128424; Cetak</button>
    <button class="btn btn-close" onclick="window.close()">&#10005; Tutup</button>
</div>

<div class="print-hint no-print">
    <strong>Supaya label cuma 1 halaman:</strong>
    <ul>
        <li>Ukuran kertas di dialog print = ukuran label yang dipilih di atas</li>
        <li><strong>Margin = None</strong>, <strong>Scale = 100%</strong></li>
        <li><strong>Uncentang "Headers and footers"</strong> (Chrome: <em>More settings &rsaquo; Headers and footers</em>)</li>
        <li>Ukuran <em>font</em> di dialog print biarkan 100%</li>
    </ul>
</div>

<script>
(function() {
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === '1') {
        setTimeout(function() { window.print(); }, 1200);
    }
})();

document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        window.print();
    }
    if (e.key === 'Escape') {
        window.close();
    }
});
</script>

</body>
</html>
