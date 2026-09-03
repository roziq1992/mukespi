<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
    <style>
        :root {
            --primary: #0d6efd;
            --border-color: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-card: #ffffff;
            --bg-body: #f8f9fa;
        }

        .asp-read-wrap {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            color: var(--text-main);
        }

        .asp-read-header-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .asp-read-header-title h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .asp-read-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .asp-read-card h4 {
            margin: 0 0 14px 0;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--primary);
            font-weight: 700;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 8px;
        }

        .asp-read-row {
            display: flex;
            flex-direction: column;
            padding: 8px 0;
            border-bottom: 1px solid #f8fafc;
            font-size: 0.9rem;
        }

        .asp-read-row:last-child {
            border-bottom: none;
        }

        @media (min-width: 640px) {
            .asp-read-row {
                flex-direction: row;
                align-items: flex-start;
            }
        }

        .asp-read-row .k {
            width: 200px;
            color: var(--text-muted);
            font-weight: 500;
            flex-shrink: 0;
            margin-bottom: 2px;
        }

        @media (min-width: 640px) {
            .asp-read-row .k {
                margin-bottom: 0;
            }
        }

        .asp-read-row .v {
            flex: 1;
            color: var(--text-main);
            white-space: pre-wrap;
            word-break: break-word;
            font-weight: 500;
        }

        .asp-read-row .v-empty {
            color: var(--text-muted);
            font-style: italic;
        }

        .asp-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
            text-decoration: none;
            color: #fff;
            background: #6c757d;
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: opacity 0.2s;
        }

        .asp-back:hover {
            opacity: 0.9;
            color: #fff;
        }

        .badge-shift {
            background: #e2e8f0;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-count {
            background: #0d6efd;
            color: #fff;
            padding: 2px 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .pasien-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            margin-top: 6px;
        }

        .pasien-table th {
            background: #f1f5f9;
            color: var(--text-muted);
            font-weight: 600;
            padding: 6px 10px;
            border: 1px solid #e2e8f0;
            text-align: left;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .pasien-table td {
            padding: 6px 10px;
            border: 1px solid #e2e8f0;
            color: var(--text-main);
        }

        .pasien-table tr:nth-child(even) td {
            background: #fafbfc;
        }

        .bed-number {
            font-weight: 700;
            color: var(--primary);
            text-align: center;
        }

        .asp-accordion {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 6px;
            overflow: hidden;
        }

        .asp-accordion:last-child {
            margin-bottom: 0;
        }

        .asp-accordion > summary {
            list-style: none;
            cursor: pointer;
            padding: 10px 14px;
            font-weight: 700;
            font-size: 0.85rem;
            background: #f8fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            -webkit-tap-highlight-color: transparent;
        }

        .asp-accordion > summary::-webkit-details-marker {
            display: none;
        }

        .asp-accordion > summary::after {
            content: '+';
            font-size: 1.1rem;
            color: var(--primary);
            font-weight: 700;
        }

        .asp-accordion[open] > summary::after {
            content: '−';
        }

        .asp-accordion-body {
            padding: 10px 14px;
        }

        .asp-accordion .badge-empty {
            color: var(--text-muted);
            font-weight: 400;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>

<div class="asp-read-wrap">
    <div class="asp-read-header-title">
        <h3>Detail Serah Terima Pasien</h3>
        <a href="<?php echo site_url('operan') ?>" class="asp-back">&larr; Kembali</a>
    </div>

    <!-- Informasi Umum -->
    <div class="asp-read-card">
        <h4>Informasi Umum</h4>
        <div class="asp-read-row">
            <div class="k">Hari/Tanggal</div>
            <div class="v"><strong><?php echo date('d-m-Y', strtotime($row->hari_tanggal)); ?></strong></div>
        </div>
        <div class="asp-read-row">
            <div class="k">Shift</div>
            <div class="v">
                <span class="badge-shift">Shift <?php echo htmlspecialchars($row->shift_dari); ?></span>
                &rarr;
                <span class="badge-shift">Shift <?php echo htmlspecialchars($row->shift_ke); ?></span>
            </div>
        </div>
        <div class="asp-read-row">
            <div class="k">Departemen / Divisi</div>
            <div class="v"><?php echo htmlspecialchars($row->departemen); ?></div>
        </div>
        <div class="asp-read-row">
            <div class="k">Total Pasien Ranap</div>
            <div class="v"><span class="badge-count"><?php echo $row->jumlah_pasien_ranap; ?></span></div>
        </div>
    </div>

    <!-- 1. Jumlah Pasien Per Ruang -->
    <div class="asp-read-card">
        <h4>1. Jumlah Pasien Per Ruang</h4>
        <div class="asp-read-row">
            <div class="v" style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid #f1f5f9;">
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:6px; font-size:0.85rem;">
                    <div><strong>NS 1:</strong> <?php echo $row->jp_ns1; ?></div>
                    <div><strong>NS 2:</strong> <?php echo $row->jp_ns2; ?></div>
                    <div><strong>NS 3:</strong> <?php echo $row->jp_ns3; ?></div>
                    <div><strong>ICU:</strong> <?php echo $row->jp_icu; ?></div>
                    <div><strong>PICU:</strong> <?php echo $row->jp_picu; ?></div>
                    <div><strong>NICU:</strong> <?php echo $row->jp_nicu; ?></div>
                    <div><strong>VK:</strong> <?php echo $row->jp_vk; ?></div>
                    <div><strong>R. Bayi:</strong> <?php echo $row->jp_r_bayi; ?></div>
                    <div><strong>IGD:</strong> <?php echo $row->jp_igd; ?></div>
                    <div><strong>OK:</strong> <?php echo $row->jp_ok; ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Data Pasien Per Ruang -->
    <div class="asp-read-card">
        <h4>2. Data Pasien Per Ruang</h4>
        
        <?php
        $ruang_data = [
            'icu' => 'ICU',
            'picu' => 'PICU',
            'nicu' => 'NICU',
            'arofah' => 'Arofah',
            'muzd' => 'Muzd',
            'mina' => 'Mina',
            'marwah' => 'Marwah',
            'safa' => 'Safa',
            'multazam' => 'Multazam',
            'vk' => 'VK',
            'r_bayi' => 'R.Bayi',
            'ok' => 'OK',
            'igd' => 'IGD'
        ];
        
        foreach ($ruang_data as $key => $label):
            $data_key = 'data_' . $key;
            $pasien = json_decode($row->$data_key, true);
            
            // Cek apakah ada data pasien
            $has_data = false;
            if (is_array($pasien)) {
                foreach ($pasien as $p) {
                    if (!empty($p['nama_pasien'])) {
                        $has_data = true;
                        break;
                    }
                }
            }
            
            if (!$has_data) continue;
        ?>
        <details class="asp-accordion" open>
            <summary>
                <?php echo $label; ?>
                <span class="badge-empty">
                    <?php 
                        $count = 0;
                        if (is_array($pasien)) {
                            foreach ($pasien as $p) {
                                if (!empty($p['nama_pasien'])) $count++;
                            }
                        }
                        echo $count . ' pasien';
                    ?>
                </span>
            </summary>
            <div class="asp-accordion-body">
                <table class="pasien-table">
                    <thead>
                        <tr>
                            <th style="width:50px; text-align:center;">Bed</th>
                            <th>Nama Pasien</th>
                            <th>Diagnosa</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (is_array($pasien)):
                        for ($i = 1; $i <= 8; $i++): 
                            if (empty($pasien[$i]['nama_pasien'])) continue;
                    ?>
                        <tr>
                            <td class="bed-number"><?php echo $i; ?></td>
                            <td><strong><?php echo htmlspecialchars($pasien[$i]['nama_pasien']); ?></strong></td>
                            <td><?php echo htmlspecialchars($pasien[$i]['diagnosa']); ?></td>
                            <td><?php echo htmlspecialchars($pasien[$i]['keterangan']); ?></td>
                        </tr>
                    <?php 
                        endfor;
                    endif; 
                    ?>
                    </tbody>
                </table>
            </div>
        </details>
        <?php endforeach; ?>
        
        <?php
        // Cek apakah semua ruang kosong
        $all_empty = true;
        foreach ($ruang_data as $key => $label) {
            $data_key = 'data_' . $key;
            $pasien = json_decode($row->$data_key, true);
            if (is_array($pasien)) {
                foreach ($pasien as $p) {
                    if (!empty($p['nama_pasien'])) {
                        $all_empty = false;
                        break 2;
                    }
                }
            }
        }
        if ($all_empty): 
        ?>
            <div class="asp-read-row">
                <div class="v v-empty">Tidak ada data pasien.</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- 3. Rekomendasi & Catatan -->
    <div class="asp-read-card">
        <h4>3. Rekomendasi &amp; Catatan</h4>
        <div class="asp-read-row">
            <div class="k">Rekomendasi</div>
            <div class="v"><?php echo !empty($row->rekomendasi) ? nl2br(htmlspecialchars($row->rekomendasi)) : '<span class="v-empty">-</span>'; ?></div>
        </div>
        <div class="asp-read-row">
            <div class="k">Catatan Khusus</div>
            <div class="v"><?php echo !empty($row->catatan_khusus) ? nl2br(htmlspecialchars($row->catatan_khusus)) : '<span class="v-empty">-</span>'; ?></div>
        </div>
    </div>

    <!-- 4. Petugas -->
    <div class="asp-read-card">
        <h4>4. Tanda Tangan</h4>
        <div class="asp-read-row">
            <div class="k">Perawat Shift 1</div>
            <div class="v"><strong><?php echo htmlspecialchars($row->perawat_shift1); ?></strong></div>
        </div>
        <div class="asp-read-row">
            <div class="k">Perawat Shift 2</div>
            <div class="v"><strong><?php echo htmlspecialchars($row->perawat_shift2); ?></strong></div>
        </div>
        <div class="asp-read-row">
            <div class="k">Mengetahui</div>
            <div class="v"><strong><?php echo !empty($row->mengetahui) ? htmlspecialchars($row->mengetahui) : '<span class="v-empty">-</span>'; ?></strong></div>
        </div>
    </div>

    <a href="<?php echo site_url('operan') ?>" class="asp-back">&larr; Kembali ke daftar</a>
</div>

</body>
</html>