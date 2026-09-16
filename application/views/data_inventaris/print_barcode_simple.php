<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Barcode - <?php echo isset($inventaris) ? $inventaris->kode_inven : 'Unknown'; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            background: #f0f2f5;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .barcode-container {
            max-width: 350px;
            width: 100%;
            background: #ffffff;
            border-radius: 16px;
            padding: 30px 25px 25px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            text-align: center;
            border: 1px solid #e8ecf0;
        }
        .barcode-header .hospital-name {
            font-size: 11px;
            font-weight: 700;
            color: #2c5f8a;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .barcode-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #2c5f8a, transparent);
            margin: 8px 0 12px;
        }
        .barcode-code {
            font-size: 26px;
            font-weight: 700;
            color: #1a2a3a;
            letter-spacing: 3px;
            margin: 8px 0 12px;
        }
        .barcode-svg {
            background: #ffffff;
            padding: 15px 10px;
            border: 2px dashed #dce3eb;
            border-radius: 10px;
            margin: 10px 0 15px;
            min-height: 80px;
        }
        .barcode-svg svg {
            max-width: 100%;
            height: auto;
        }
        .barcode-details {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #eef0f3;
        }
        .barcode-details table {
            width: 100%;
            font-size: 12px;
            text-align: left;
            border-collapse: collapse;
        }
        .barcode-details table td {
            padding: 5px 4px;
            color: #33475b;
        }
        .barcode-details table td:first-child {
            color: #8a94a6;
            font-weight: 600;
            width: 38%;
            font-size: 11px;
            text-transform: uppercase;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-badge.aktif { background: #d4edda; color: #1e7e34; }
        .status-badge.proses { background: #fff3cd; color: #856404; }
        .status-badge.nonaktif { background: #e2e3e5; color: #495057; }
        .status-badge.rusak { background: #f8d7da; color: #a71d2a; }
        .barcode-footer {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid #eef0f3;
            font-size: 9px;
            color: #8a94a6;
        }
        .print-actions {
            margin-top: 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .print-actions .btn {
            padding: 10px 28px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        .btn-print { background: #2c5f8a; color: #fff; }
        .btn-print:hover { background: #1b3a5c; }
        .btn-close { background: #e9ecef; color: #495057; }
        .btn-close:hover { background: #dee2e6; }
        @media print {
            body { background: #fff; padding: 10px; display: block; }
            .barcode-container { box-shadow: none; border: 1px solid #ddd; }
            .print-actions { display: none; }
            .barcode-svg { border: 1px solid #ddd; }
        }
    </style>
</head>
<body>

<div class="barcode-container">
    <div class="barcode-header">
        <div class="hospital-name">🏥 RUMAH SAKIT</div>
        <div class="barcode-divider"></div>
    </div>
    
    <div style="font-size:10px;color:#8a94a6;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">
        LABEL INVENTARIS
    </div>
    
    <div class="barcode-code">
        <?php echo isset($inventaris) ? $inventaris->kode_inven : 'INV-0000'; ?>
    </div>
    
    <div class="barcode-svg" id="barcodeContainer">
        <svg id="barcode" width="280" height="80" xmlns="http://www.w3.org/2000/svg">
            <!-- Simple barcode representation -->
            <rect x="0" y="0" width="280" height="80" fill="white" rx="4"/>
            <?php 
            $code = isset($inventaris) ? $inventaris->kode_inven : 'INV-0000';
            $seed = 0;
            for ($i = 0; $i < strlen($code); $i++) {
                $seed += ord($code[$i]);
            }
            mt_srand($seed);
            $x = 20;
            for ($i = 0; $i < 40; $i++) {
                $width = mt_rand(1, 4);
                $height = mt_rand(40, 60);
                $color = mt_rand(0, 1) ? '#000' : '#fff';
                echo '<rect x="' . $x . '" y="' . (80 - $height) . '" width="' . $width . '" height="' . $height . '" fill="' . $color . '"/>';
                $x += $width + mt_rand(1, 3);
            }
            mt_srand();
            ?>
            <!-- Human readable text -->
            <text x="140" y="72" text-anchor="middle" font-family="'Courier New', monospace" font-size="12" font-weight="bold" fill="#333">
                <?php echo $code; ?>
            </text>
        </svg>
    </div>
    
    <div class="barcode-details">
        <table>
            <tr><td>Nama Barang</td><td><?php echo isset($inventaris) ? $inventaris->nm_barang : '-'; ?></td></tr>
            <tr><td>Merek</td><td><?php echo isset($inventaris) ? $inventaris->merek : '-'; ?></td></tr>
            <tr><td>Jenis</td><td><?php echo isset($inventaris) ? $inventaris->jenis : '-'; ?></td></tr>
            <tr><td>Ruang</td><td><?php echo isset($inventaris) ? $inventaris->id_ruang : '-'; ?></td></tr>
            <tr><td>Status</td>
                <td>
                    <?php if (isset($inventaris)): ?>
                        <span class="status-badge <?php echo strtolower(trim($inventaris->stts)); ?>">
                            <?php echo $inventaris->stts; ?>
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="barcode-footer">
        Dicetak: <?php echo date('d/m/Y H:i'); ?>
    </div>
</div>

<div class="print-actions no-print">
    <button class="btn btn-print" onclick="window.print()">🖨️ Cetak</button>
    <button class="btn btn-close" onclick="window.close()">✕ Tutup</button>
</div>

<script>
// Keyboard shortcut Ctrl+P
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