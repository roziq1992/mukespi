<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Barcode - <?php echo isset($inventaris) ? $inventaris->kode_inven : 'Unknown'; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
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
        
        /* Header */
        .barcode-header {
            margin-bottom: 12px;
        }
        .barcode-header .hospital-name {
            font-size: 11px;
            font-weight: 700;
            color: #2c5f8a;
            text-transform: uppercase;
            letter-spacing: 2px;
            background: #eef4fa;
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
        }
        .barcode-header .divider-line {
            height: 2px;
            background: linear-gradient(90deg, transparent, #2c5f8a, transparent);
            margin: 8px 0 12px;
        }
        
        /* Barcode Code */
        .barcode-code {
            font-size: 26px;
            font-weight: 700;
            color: #1a2a3a;
            letter-spacing: 3px;
            margin: 8px 0 12px;
            font-family: 'Courier New', monospace;
        }
        
        /* Barcode Image */
        .barcode-image {
            background: #ffffff;
            padding: 15px 10px;
            border: 2px dashed #dce3eb;
            border-radius: 10px;
            margin: 10px 0 15px;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .barcode-image img {
            max-width: 100%;
            height: auto;
        }
        .barcode-image .barcode-fallback {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            letter-spacing: 2px;
            color: #1a2a3a;
            background: #f8fafc;
            padding: 15px 20px;
            border-radius: 8px;
            width: 100%;
            overflow: hidden;
            word-break: break-all;
        }
        
        /* Info Details */
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
        .barcode-details table tr {
            border-bottom: 1px solid #f5f7fa;
        }
        .barcode-details table tr:last-child {
            border-bottom: none;
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
            letter-spacing: 0.03em;
        }
        .barcode-details table td:last-child {
            font-weight: 500;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-badge.aktif { background: #d4edda; color: #1e7e34; }
        .status-badge.proses { background: #fff3cd; color: #856404; }
        .status-badge.nonaktif { background: #e2e3e5; color: #495057; }
        .status-badge.rusak { background: #f8d7da; color: #a71d2a; }
        
        /* Footer */
        .barcode-footer {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid #eef0f3;
            font-size: 9px;
            color: #8a94a6;
            letter-spacing: 0.05em;
        }
        .barcode-footer .date-printed {
            font-size: 9px;
        }
        
        /* Print Actions */
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
            display: inline-block;
        }
        .print-actions .btn-print {
            background: #2c5f8a;
            color: #fff;
        }
        .print-actions .btn-print:hover {
            background: #1b3a5c;
            transform: translateY(-2px);
        }
        .print-actions .btn-close {
            background: #e9ecef;
            color: #495057;
        }
        .print-actions .btn-close:hover {
            background: #dee2e6;
            transform: translateY(-2px);
        }
        .print-actions .btn-barcode {
            background: #28a745;
            color: #fff;
        }
        .print-actions .btn-barcode:hover {
            background: #1e7e34;
            transform: translateY(-2px);
        }
        
        /* Print Styles */
        @media print {
            body {
                background: #ffffff !important;
                padding: 10px !important;
                min-height: auto !important;
                display: block !important;
            }
            .barcode-container {
                max-width: 100% !important;
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                border-radius: 8px !important;
                padding: 20px !important;
                margin: 0 auto !important;
            }
            .print-actions {
                display: none !important;
            }
            .barcode-image {
                border: 1px solid #ddd !important;
                min-height: 80px !important;
            }
            .barcode-header .divider-line {
                background: #333 !important;
            }
            .no-print {
                display: none !important;
            }
            .barcode-container {
                page-break-inside: avoid;
            }
        }
        
        /* Responsive */
        @media (max-width: 400px) {
            .barcode-container {
                padding: 20px 15px;
            }
            .barcode-code {
                font-size: 20px;
            }
            .barcode-details table td {
                font-size: 11px;
                padding: 4px 2px;
            }
            .print-actions .btn {
                padding: 8px 18px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

<div class="barcode-container" id="barcodeContainer">
    
    <!-- Header -->
    <div class="barcode-header">
        <div class="hospital-name">🏥 RUMAH SAKIT</div>
        <div class="divider-line"></div>
    </div>
    
    <!-- Title -->
    <div style="font-size: 10px; color: #8a94a6; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">
        LABEL INVENTARIS
    </div>
    
    <!-- Barcode Code -->
    <div class="barcode-code" id="barcodeCode">
        <?php echo isset($inventaris) ? $inventaris->kode_inven : 'INV-0000'; ?>
    </div>
    
    <!-- Barcode Image -->
    <div class="barcode-image" id="barcodeImage">
        <?php if (isset($inventaris) && $inventaris->kode_inven): ?>
            <!-- Using external barcode API -->
            <img src="https://barcode.tec-it.com/barcode.ashx?data=<?php echo urlencode($inventaris->kode_inven); ?>&code=Code128&dpi=96&width=250&height=80" 
                 alt="Barcode <?php echo $inventaris->kode_inven; ?>"
                 onerror="this.style.display='none'; document.getElementById('barcodeFallback').style.display='block';" />
            <div id="barcodeFallback" class="barcode-fallback" style="display:none;">
                <?php echo $inventaris->kode_inven; ?>
            </div>
        <?php else: ?>
            <div class="barcode-fallback">INV-0000</div>
        <?php endif; ?>
    </div>
    
    <!-- Details -->
    <div class="barcode-details">
        <table>
            <tr>
                <td>Nama Barang</td>
                <td><?php echo isset($inventaris) ? $inventaris->nm_barang : '-'; ?></td>
            </tr>
            <tr>
                <td>Merek</td>
                <td><?php echo isset($inventaris) ? $inventaris->merek : '-'; ?></td>
            </tr>
            <tr>
                <td>Tipe / Model</td>
                <td><?php echo isset($inventaris) ? $inventaris->tipe : '-'; ?></td>
            </tr>
            <tr>
                <td>Serial Number</td>
                <td><?php echo isset($inventaris) ? $inventaris->sn : '-'; ?></td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td><?php echo isset($inventaris) ? $inventaris->jenis : '-'; ?></td>
            </tr>
            <tr>
                <td>Kondisi</td>
                <td><?php echo isset($inventaris) ? $inventaris->kondisi : '-'; ?></td>
            </tr>
            <tr>
                <td>Ruang</td>
                <td><?php echo isset($inventaris) ? $inventaris->id_ruang : '-'; ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    <?php if (isset($inventaris)): ?>
                        <span class="status-badge <?php echo strtolower(trim($inventaris->stts)); ?>">
                            <?php echo $inventaris->stts; ?>
                        </span>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Harga</td>
                <td>Rp <?php echo isset($inventaris) ? number_format((float) $inventaris->harga, 0, ',', '.') : '0'; ?></td>
            </tr>
        </table>
    </div>
    
    <!-- Footer -->
    <div class="barcode-footer">
        <div class="date-printed">
            Dicetak: <?php echo date('d/m/Y H:i'); ?>
        </div>
        <div style="margin-top: 2px;">
            <?php echo isset($inventaris) ? $inventaris->kode_inven : 'INV-0000'; ?> | Inventaris RS
        </div>
    </div>
    
</div>

<!-- Print Actions -->
<div class="print-actions no-print">
    <button class="btn btn-print" onclick="window.print()">🖨️ Cetak</button>
    <button class="btn btn-barcode" onclick="generateBarcodeImage()">📱 Generate Barcode</button>
    <button class="btn btn-close" onclick="window.close()">✕ Tutup</button>
</div>

<!-- ============================================ -->
<!-- JAVASCRIPT -->
<!-- ============================================ -->
<script>
/**
 * Generate barcode using canvas (fallback if external image fails)
 */
function generateBarcodeImage() {
    var code = document.getElementById('barcodeCode').textContent || 'INV-0000';
    var container = document.getElementById('barcodeImage');
    
    // Try to reload image
    var img = container.querySelector('img');
    if (img) {
        var src = img.src;
        img.src = '';
        setTimeout(function() {
            img.src = src;
        }, 100);
    }
    
    console.log('Generating barcode for:', code);
}

/**
 * Auto print if requested
 */
(function() {
    // Check if auto print parameter is set
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === '1') {
        setTimeout(function() {
            window.print();
        }, 1000);
    }
})();

/**
 * Keyboard shortcuts
 */
document.addEventListener('keydown', function(e) {
    // Ctrl+P = Print
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        window.print();
    }
    // Escape = Close
    if (e.key === 'Escape') {
        window.close();
    }
});

console.log('Print Barcode Local loaded successfully');
console.log('Barcode Code:', document.getElementById('barcodeCode')?.textContent);
</script>

</body>
</html>