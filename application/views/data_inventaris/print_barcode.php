<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Barcode - <?php echo $inventaris->kode_inven; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8fafc;
        }
        .barcode-container {
            max-width: 400px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            padding: 30px 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
            text-align: center;
        }
        .barcode-title {
            font-size: 14px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .barcode-code {
            font-size: 28px;
            font-weight: bold;
            color: #2c5f8a;
            margin: 15px 0 5px;
            letter-spacing: 2px;
        }
        .barcode-image {
            margin: 15px 0;
            padding: 15px;
            background: #fff;
            border: 1px solid #eef0f3;
            border-radius: 8px;
        }
        .barcode-image img {
            max-width: 100%;
            height: auto;
        }
        .barcode-details {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eef0f3;
        }
        .barcode-details table {
            width: 100%;
            text-align: left;
            font-size: 13px;
        }
        .barcode-details table td {
            padding: 4px 0;
        }
        .barcode-details table td:first-child {
            color: #8a94a6;
            font-weight: 600;
            width: 40%;
        }
        .barcode-details table td:last-child {
            color: #33475b;
        }
        .print-btn {
            margin-top: 20px;
            padding: 10px 30px;
            background: #2c5f8a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }
        .print-btn:hover {
            background: #1b3a5c;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .barcode-container { box-shadow: none; border: none; padding: 20px; }
            .print-btn { display: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="barcode-container">
    <div class="barcode-title">Inventaris Rumah Sakit</div>

    <div class="barcode-code"><?php echo $inventaris->kode_inven; ?></div>

    <div class="barcode-image">
        <!-- Barcode image generated from code -->
        <img src="https://barcode.tec-it.com/barcode.ashx?data=<?php echo urlencode($inventaris->kode_inven); ?>&code=Code128&dpi=96" 
             alt="Barcode <?php echo $inventaris->kode_inven; ?>" />
    </div>

    <div class="barcode-details">
        <table>
            <tr>
                <td>Nama Barang</td>
                <td>: <?php echo $inventaris->nm_barang; ?></td>
            </tr>
            <tr>
                <td>Merek</td>
                <td>: <?php echo $inventaris->merek; ?></td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td>: <?php echo $inventaris->jenis; ?></td>
            </tr>
            <tr>
                <td>Kondisi</td>
                <td>: <?php echo $inventaris->kondisi; ?></td>
            </tr>
            <tr>
                <td>Ruang</td>
                <td>: <?php echo $inventaris->id_ruang; ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>: <?php echo $inventaris->stts; ?></td>
            </tr>
        </table>
    </div>

    <button class="print-btn no-print" onclick="window.print()">🖨️ Cetak Barcode</button>
    <button class="print-btn no-print" style="background: #6c757d; margin-left: 10px;" onclick="window.close()">✕ Tutup</button>
</div>

</body>
</html>