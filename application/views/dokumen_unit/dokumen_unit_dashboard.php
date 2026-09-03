<style>
    .dd-wrap { display: flex; flex-direction: column; gap: 20px; }

    .dd-header-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        background: linear-gradient(135deg, #2c5f8a 0%, #1b3a5c 100%);
        color: #fff;
        padding: 22px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .dd-header-card h2 { margin: 0; font-size: 1.2rem; font-weight: 700; }
    .dd-header-card p { margin: 4px 0 0; font-size: 0.8rem; opacity: 0.85; }
    .dd-header-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .dd-range-form { display: flex; align-items: center; gap: 8px; }
    .dd-range-form select {
        border: 1px solid rgba(255,255,255,0.35);
        background: rgba(255,255,255,0.12);
        color: #fff;
        border-radius: 8px;
        padding: 7px 10px;
        font-size: 0.82rem;
    }
    .dd-range-form select option { color: #33475b; }
    .dd-btn-list {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.35);
        color: #fff;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }
    .dd-btn-list:hover { background: rgba(255,255,255,0.25); color: #fff; text-decoration: none; }

    .dd-stat-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 16px;
    }
    @media (max-width: 992px) { .dd-stat-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 576px) { .dd-stat-grid { grid-template-columns: repeat(2, 1fr); } }

    .dd-stat-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        padding: 16px;
        border-left: 4px solid #2c5f8a;
    }
    .dd-stat-card .num { font-size: 1.6rem; font-weight: 800; color: #1b3a5c; line-height: 1; }
    .dd-stat-card .lbl { font-size: 0.75rem; color: #8a94a6; font-weight: 600; margin-top: 6px; text-transform: uppercase; letter-spacing: 0.02em; }
    .dd-stat-card.total { border-left-color: #2c5f8a; }
    .dd-stat-card.aktif { border-left-color: #1e8449; }
    .dd-stat-card.draft { border-left-color: #b8860b; }
    .dd-stat-card.arsip { border-left-color: #8a94a6; }
    .dd-stat-card.kadaluarsa { border-left-color: #c0392b; }
    .dd-stat-card.warning { border-left-color: #e67e22; }
    .dd-stat-card.warning .num { color: #e67e22; }

    .dd-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 992px) { .dd-row { grid-template-columns: 1fr; } }

    .dd-panel {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        padding: 20px;
    }
    .dd-panel h5 { margin: 0 0 14px; font-size: 0.95rem; font-weight: 700; color: #1b3a5c; }
    .dd-panel .dd-empty-mini { text-align: center; padding: 30px 10px; color: #8a94a6; font-size: 0.85rem; }
    .dd-chart-box { position: relative; height: 280px; }

    .dd-expiring-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .dd-expiring-table thead th {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #8a94a6;
        font-weight: 700;
        border-bottom: 2px solid #eef0f3;
        padding: 8px 10px;
        text-align: left;
        white-space: nowrap;
    }
    .dd-expiring-table tbody td {
        padding: 10px;
        border-bottom: 1px solid #f1f3f6;
        font-size: 0.84rem;
        color: #33475b;
        vertical-align: middle;
    }
    .dd-days-chip {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .dd-days-chip.urgent { background: #f8d7da; color: #a71d2a; }
    .dd-days-chip.soon { background: #fff3cd; color: #856404; }
    .dd-days-chip.ok { background: #d4edda; color: #1e7e34; }
</style>

<div class="container-fluid">
    <div class="dd-wrap">

        <div class="dd-header-card">
            <div>
                <h2>📊 Dashboard Laporan Dokumen Unit</h2>
                <p>Ringkasan kondisi dokumen di seluruh unit rumah sakit</p>
            </div>
            <div class="dd-header-actions">
                <form action="<?php echo site_url('dokumen_unit/dashboard'); ?>" method="get" class="dd-range-form">
                    <span style="font-size:0.8rem;">Prediksi kadaluarsa:</span>
                    <select name="hari" onchange="this.form.submit()">
                        <option value="7" <?php echo ($hari == 7) ? 'selected' : ''; ?>>7 hari</option>
                        <option value="30" <?php echo ($hari == 30) ? 'selected' : ''; ?>>30 hari</option>
                        <option value="60" <?php echo ($hari == 60) ? 'selected' : ''; ?>>60 hari</option>
                        <option value="90" <?php echo ($hari == 90) ? 'selected' : ''; ?>>90 hari</option>
                    </select>
                </form>
                <a href="<?php echo site_url('dokumen_unit'); ?>" class="dd-btn-list">📁 Lihat Daftar Dokumen</a>
            </div>
        </div>

        <div class="dd-stat-grid">
            <div class="dd-stat-card total">
                <div class="num"><?php echo $stats['total']; ?></div>
                <div class="lbl">Total Dokumen</div>
            </div>
            <div class="dd-stat-card aktif">
                <div class="num"><?php echo $stats['aktif']; ?></div>
                <div class="lbl">Aktif</div>
            </div>
            <div class="dd-stat-card draft">
                <div class="num"><?php echo $stats['draft']; ?></div>
                <div class="lbl">Draft</div>
            </div>
            <div class="dd-stat-card arsip">
                <div class="num"><?php echo $stats['arsip']; ?></div>
                <div class="lbl">Arsip</div>
            </div>
            <div class="dd-stat-card kadaluarsa">
                <div class="num"><?php echo $stats['kadaluarsa']; ?></div>
                <div class="lbl">Kadaluarsa</div>
            </div>
            <div class="dd-stat-card warning">
                <div class="num"><?php echo $stats['akan_kadaluarsa']; ?></div>
                <div class="lbl">Akan Kadaluarsa (<?php echo $hari; ?>h)</div>
            </div>
        </div>

        <div class="dd-row">
            <div class="dd-panel">
                <h5>Dokumen per Unit</h5>
                <?php if (count($by_unit) == 0): ?>
                    <div class="dd-empty-mini">Belum ada data.</div>
                <?php else: ?>
                    <div class="dd-chart-box"><canvas id="chartByUnit"></canvas></div>
                <?php endif; ?>
            </div>
            <div class="dd-panel">
                <h5>Dokumen per Jenis Dokumen</h5>
                <?php if (count($by_jenis) == 0): ?>
                    <div class="dd-empty-mini">Belum ada data.</div>
                <?php else: ?>
                    <div class="dd-chart-box"><canvas id="chartByJenis"></canvas></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="dd-panel">
            <h5>⏰ Dokumen Akan Kadaluarsa dalam <?php echo $hari; ?> Hari</h5>
            <?php if (count($expiring_soon) == 0): ?>
                <div class="dd-empty-mini">🎉 Tidak ada dokumen yang akan kadaluarsa dalam rentang ini.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="dd-expiring-table">
                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th>Jenis Dokumen</th>
                                <th>Judul Dokumen</th>
                                <th>Tgl Kadaluarsa</th>
                                <th>Sisa Waktu</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($expiring_soon as $doc):
                                $sisa_hari = ceil((strtotime($doc->tgl_kadaluarsa) - strtotime(date('Y-m-d'))) / 86400);
                                $chip_class = 'ok';
                                if ($sisa_hari <= 7) $chip_class = 'urgent';
                                elseif ($sisa_hari <= 14) $chip_class = 'soon';
                            ?>
                            <tr>
                                <td><?php echo $doc->nm_unit; ?></td>
                                <td><?php echo $doc->nm_jenis_dokumen; ?></td>
                                <td><?php echo $doc->judul_dokumen; ?></td>
                                <td><?php echo date('d M Y', strtotime($doc->tgl_kadaluarsa)); ?></td>
                                <td><span class="dd-days-chip <?php echo $chip_class; ?>"><?php echo $sisa_hari; ?> hari lagi</span></td>
                                <td><a href="<?php echo site_url('dokumen_unit/read/'.$doc->id_dokumen); ?>" title="Lihat">👁️</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
(function () {
    var byUnitLabels = <?php echo json_encode(array_map(function($r){ return $r->label ?: '(Tanpa Unit)'; }, $by_unit)); ?>;
    var byUnitData   = <?php echo json_encode(array_map(function($r){ return intval($r->jumlah); }, $by_unit)); ?>;

    var byJenisLabels = <?php echo json_encode(array_map(function($r){ return $r->label ?: '(Tanpa Jenis)'; }, $by_jenis)); ?>;
    var byJenisData   = <?php echo json_encode(array_map(function($r){ return intval($r->jumlah); }, $by_jenis)); ?>;

    var palette = ['#2c5f8a', '#1e8449', '#e67e22', '#8e44ad', '#c0392b', '#16a085', '#b8860b', '#34495e', '#2980b9', '#7f8c8d'];

    var elUnit = document.getElementById('chartByUnit');
    if (elUnit) {
        new Chart(elUnit, {
            type: 'bar',
            data: {
                labels: byUnitLabels,
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: byUnitData,
                    backgroundColor: '#2c5f8a',
                    borderRadius: 6,
                    maxBarThickness: 34
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }

    var elJenis = document.getElementById('chartByJenis');
    if (elJenis) {
        new Chart(elJenis, {
            type: 'doughnut',
            data: {
                labels: byJenisLabels,
                datasets: [{
                    data: byJenisData,
                    backgroundColor: byJenisLabels.map(function (_, i) { return palette[i % palette.length]; }),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
            }
        });
    }
})();
</script>
