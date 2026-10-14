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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* Label 8cm x 3cm */
        .barcode-label {
            width: 80mm;
            height: 30mm;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
            padding: 2mm;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            border: 1px solid #e8ecf0;
        }

        .barcode-label .barcode-image {
            width: 100%;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .barcode-label .barcode-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .barcode-label .barcode-code {
            font-size: 14px;
            font-weight: 700;
            color: #000000;
            letter-spacing: 1px;
            line-height: 1;
            margin-top: 1mm;
            white-space: nowrap;
        }

        .print-actions {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }
        .print-actions .btn {
            padding: 10px 28px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .print-actions .btn-print { background: #2c5f8a; color: #fff; }
        .print-actions .btn-close { background: #e9ecef; color: #495057; }

        @page {
            size: 80mm 30mm;
            margin: 0;
        }

        @media print {
            html, body {
                width: 80mm;
                height: 30mm;
                margin: 0;
                padding: 0;
                background: #ffffff !important;
            }
            body {
                display: block !important;
                min-height: auto !important;
            }
            .barcode-label {
                width: 80mm;
                height: 30mm;
                box-shadow: none !important;
                border: none !important;
                page-break-inside: avoid;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="barcode-label">
    <div class="barcode-image">
        <?php if (isset($inventaris) && $inventaris->kode_inven): ?>
            <img src="https://barcode.tec-it.com/barcode.ashx?data=<?php echo urlencode($inventaris->kode_inven); ?>&code=Code128&dpi=96&width=250&height=80"
                 alt="Barcode <?php echo $inventaris->kode_inven; ?>"
                 onerror="this.style.display='none'; document.getElementById('barcodeFallback').style.display='block';" />
            <div id="barcodeFallback" class="barcode-fallback" style="display:none;">
                <?php echo $inventaris->kode_inven; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="barcode-code">
        <?php echo isset($inventaris) ? $inventaris->kode_inven : 'INV-0000'; ?>
    </div>
</div>

<div class="print-actions no-print">
    <button class="btn btn-print" onclick="window.print()">&#128424; Cetak</button>
    <button class="btn btn-close" onclick="window.close()">&#10005; Tutup</button>
</div>

<script>
(function() {
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === '1') {
        setTimeout(function() { window.print(); }, 1000);
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
