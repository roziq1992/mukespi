<style>
    /* Base Card Style */
    .pe-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        background: #fff;
    }

    /* Header Section */
    .pe-header {
        background: linear-gradient(135deg, #6a3fa0 0%, #3d2266 100%);
        color: #fff;
        padding: 22px 24px;
    }
    .pe-header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        flex-wrap: wrap;
    }
    .pe-header h2 { 
        margin: 0; 
        font-size: 1.25rem; 
        font-weight: 700; 
    }
    .pe-header p { 
        margin: 4px 0 0; 
        font-size: 0.82rem; 
        opacity: 0.9; 
    }
    .pe-header .pe-periode-badge {
        display: inline-block;
        margin-top: 10px;
        background: rgba(255, 255, 255, 0.16);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .pe-btn-summary {
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #fff;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.8rem;
        font-weight: 700;
        white-space: nowrap;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s ease;
    }
    .pe-btn-summary:hover { 
        background: rgba(255, 255, 255, 0.28); 
        color: #fff; 
        text-decoration: none; 
    }

    /* Body & Alerts */
    .pe-body { padding: 22px; }
    @media (max-width: 576px) { .pe-body { padding: 14px; } }

    .pe-flash {
        background: #fdeeee;
        color: #a71d2a;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 18px;
        border-left: 4px solid #e74c3c;
    }
    .pe-surveior-banner {
        background: #fff8e6;
        color: #8a6100;
        border-left: 4px solid #d4a017;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 18px;
    }

    .pe-warning {
        text-align: center;
        padding: 40px 20px;
        color: #8a94a6;
    }
    .pe-warning .icon { 
        font-size: 2.4rem; 
        margin-bottom: 10px; 
    }

    /* Pokja Grid Layout */
    .pe-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 260px), 1fr));
        gap: 16px;
    }

    .pe-pokja-card {
        border: 1px solid #eef0f3;
        border-radius: 12px;
        padding: 18px;
        text-decoration: none;
        color: inherit;
        display: block;
        transition: box-shadow 0.15s ease, transform 0.15s ease, border-color 0.15s ease;
        position: relative;
        overflow: hidden;
        background: #fff;
    }
    .pe-pokja-card:hover {
        box-shadow: 0 6px 20px rgba(106, 63, 160, 0.15);
        transform: translateY(-2px);
        border-color: #d8c7ec;
        text-decoration: none;
        color: inherit;
    }
    .pe-pokja-bab {
        display: inline-block;
        background: #efe6fa;
        color: #6a3fa0;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        padding: 3px 10px;
        border-radius: 20px;
        margin-bottom: 10px;
    }
    .pe-pokja-nama {
        font-size: 0.95rem;
        font-weight: 700;
        color: #33475b;
        margin-bottom: 14px;
        min-height: 44px;
        line-height: 1.4;
    }

    /* Progress & Indicators */
    .pe-progress-track {
        height: 7px;
        border-radius: 20px;
        background: #eef0f3;
        overflow: hidden;
        margin-bottom: 8px;
    }
    .pe-progress-fill {
        height: 100%;
        border-radius: 20px;
        background: linear-gradient(90deg, #6a3fa0, #9b6fd6);
        transition: width 0.4s ease;
    }
    .pe-progress-fill.pe-complete { 
        background: linear-gradient(90deg, #1e8449, #27ae60); 
    }
    .pe-pokja-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #8a94a6;
        font-weight: 600;
    }

    /* Badges untuk persentase skor */
    .pe-pokja-persen {
        position: absolute;
        top: 16px;
        right: 16px;
        font-size: 0.78rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 12px;
    }
    .pe-score-high { background: #e8f8f0; color: #1e8449; }
    .pe-score-mid  { background: #fef5e7; color: #d35400; }
    .pe-score-low  { background: #fdeeee; color: #c0392b; }

    /* ============ GRAFIK BATANG ============ */
    .pe-chart-box {
        border: 1px solid #eef0f3;
        border-radius: 16px;
        background: linear-gradient(180deg, #fbfafd 0%, #ffffff 100%);
        padding: 20px 22px;
        margin-bottom: 22px;
        box-shadow: 0 2px 10px rgba(106, 63, 160, 0.05);
    }
    .pe-chart-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding-bottom: 14px;
        margin-bottom: 16px;
        border-bottom: 1px dashed #eceaf2;
    }
    .pe-chart-title {
        margin: 0;
        font-size: 1.02rem;
        font-weight: 800;
        color: #3d2266;
    }
    .pe-chart-sub {
        margin: 3px 0 0;
        font-size: 0.76rem;
        color: #8a94a6;
    }
    .pe-chart-legend { display: flex; flex-wrap: wrap; gap: 12px; }
    .pe-lg-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #6b7280;
        white-space: nowrap;
    }
    .pe-lg-box {
        width: 13px;
        height: 13px;
        border-radius: 4px;
        display: inline-block;
    }
    .pe-lg-hijau  { background: linear-gradient(135deg, #2fbf71, #1e9e55); }
    .pe-lg-kuning { background: linear-gradient(135deg, #f7b731, #e08e0b); }
    .pe-lg-merah  { background: linear-gradient(135deg, #ec5f5f, #c0392b); }

    .pe-chart-kartu {
        border: 1px solid #f0eef7;
        border-radius: 14px;
        background: #fff;
        padding: 14px 16px;
        margin-bottom: 14px;
    }
    .pe-chart-kartu:last-child { margin-bottom: 0; }
    .pe-chart-kartu-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    .pe-chart-ikon {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f3eefb;
        font-size: 1rem;
    }
    .pe-chart-nama {
        font-size: 0.88rem;
        font-weight: 800;
        color: #33475b;
        line-height: 1.2;
    }
    .pe-chart-sub2 { font-size: 0.7rem; color: #8a94a6; }
    .pe-chart-total {
        margin-left: auto;
        font-size: 1.05rem;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 12px;
    }
    .pe-chart-total.ok   { background: #e8f8f0; color: #1e8449; }
    .pe-chart-total.mid  { background: #fef5e7; color: #d35400; }
    .pe-chart-total.low  { background: #fdeeee; color: #c0392b; }

    /* Wrapper tinggi tetap -> Chart.js (v2) butuh tinggi eksplisit agar responsif */
    .pe-chart-wrap { position: relative; height: 260px; }
    @media (max-width: 576px) {
        .pe-chart-wrap { height: 320px; }
    }

    /* Badge hak akses per pokja */
    .pe-akses {
        position: absolute;
        bottom: 14px;
        right: 16px;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 20px;
    }
    .pe-akses.penilai { background: #e8f8f0; color: #1e8449; }
    .pe-akses.lihat   { background: #eef2f7; color: #5b6b80; }
</style>

<?php if (!empty($grafik_data['total_ep'])): ?>
<script src="<?php echo base_url('assets/vendor/chart.js/Chart.min.js'); ?>"></script>
<?php endif; ?>

<div class="container-fluid">
    <div class="pe-card">
        <!-- Header -->
        <div class="pe-header">
            <div class="pe-header-top">
                <div>
                    <h2>✅ Penilaian Elemen (EP)<?php echo !empty($is_surveior) ? ' — Mode Surveior' : '' ?></h2>
                    <p>Pilih Pokja untuk mulai menilai skor EP dan mengunggah bukti dokumen</p>
                    <?php if (!empty($periode)): ?>
                        <span class="pe-periode-badge">📅 Periode aktif: <?php echo html_escape($periode->nama_periode) ?></span>
                    <?php endif; ?>
                </div>
                <?php if (!empty($periode)): ?>
                    <a href="<?php echo site_url('penilaian_ep/summary') ?>" class="pe-btn-summary">📊 Summary Internal vs Surveior</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Body -->
        <div class="pe-body">
            <?php
                $flash = $this->session->flashdata('message');
                if ($flash):
            ?>
                <div class="pe-flash"><?php echo html_escape($flash) ?></div>
            <?php endif; ?>

            <?php if (!empty($is_surveior)): ?>
                <div class="pe-surveior-banner">
                    🧑‍💼 Anda login sebagai <strong>Surveior</strong>. Skor yang Anda isi tersimpan terpisah dari skor tim internal, supaya bisa dibandingkan di halaman Summary.
                </div>
            <?php elseif (empty($is_admin)): ?>
                <div class="pe-flash">
                    Anda berwenang menilai <strong><?php echo $jml_pokja_nilai ?></strong> pokja
                    (bisa isi skor, upload bukti, dan download dokumen).
                    <?php echo $jml_pokja_akses ?> pokja lain yang di-set untuk Anda hanya bisa dibuka dalam mode lihat saja.
                </div>
            <?php endif; ?>

            <?php if (empty($periode)): ?>
                <div class="pe-warning">
                    <div class="icon">⚠️</div>
                    <strong>Belum ada periode akreditasi yang aktif</strong>
                    <p class="mb-0">Set salah satu baris pada tabel <code>periode_akreditasi</code> dengan <code>status = 'aktif'</code> agar penilaian bisa dimulai.</p>
                </div>
            <?php elseif (empty($pokja_list)): ?>
                <div class="pe-warning">
                    <div class="icon">🗂️</div>
                    <strong>Belum ada data pokja aktif.</strong>
                </div>
            <?php else:

                // Ringkasan untuk kartu di atas grafik
                $g_total_ep = 0; $g_upload = 0; $g_nilai = 0;
                if (!empty($grafik_data['total_ep'])) {
                    foreach ($grafik_data['total_ep'] as $i => $t) {
                        $g_total_ep += intval($t);
                        $g_upload   += round(floatval($t) * floatval($grafik_data['upload'][$i]) / 100);
                        $g_nilai    += round(floatval($t) * floatval($grafik_data['nilai'][$i]) / 100);
                    }
                }
                $g_p_upload = $g_total_ep > 0 ? round($g_upload / $g_total_ep * 100) : 0;
                $g_p_nilai  = $g_total_ep > 0 ? round($g_nilai / $g_total_ep * 100) : 0;
            ?>
                <!-- ============ GRAFIK BATANG ============ -->
                <div class="pe-chart-box">
                    <div class="pe-chart-head">
                        <div>
                            <h3 class="pe-chart-title">📈 Grafik Progres Penilaian per Pokja</h3>
                            <p class="pe-chart-sub">Persentase EP yang sudah punya bukti diupload dan yang sudah diberi skor</p>
                        </div>
                        <div class="pe-chart-legend">
                            <span class="pe-lg-item"><i class="pe-lg-box pe-lg-hijau"></i> ≥ 80% Bagus</span>
                            <span class="pe-lg-item"><i class="pe-lg-box pe-lg-kuning"></i> 30–79% Cukup</span>
                            <span class="pe-lg-item"><i class="pe-lg-box pe-lg-merah"></i> &lt; 30% Kurang</span>
                        </div>
                    </div>

                    <div class="pe-chart-kartu">
                        <div class="pe-chart-kartu-head">
                            <span class="pe-chart-ikon">📎</span>
                            <div>
                                <div class="pe-chart-nama">Bukti Terupload</div>
                                <div class="pe-chart-sub2">EP yang punya minimal 1 file bukti</div>
                            </div>
                            <span class="pe-chart-total <?php echo $g_p_upload >= 80 ? 'ok' : ($g_p_upload >= 30 ? 'mid' : 'low') ?>"><?php echo $g_p_upload ?>%</span>
                        </div>
                        <div class="pe-chart-wrap">
                            <canvas id="chartUpload"></canvas>
                        </div>
                    </div>

                    <div class="pe-chart-kartu">
                        <div class="pe-chart-kartu-head">
                            <span class="pe-chart-ikon">✅</span>
                            <div>
                                <div class="pe-chart-nama">Sudah Dinilai</div>
                                <div class="pe-chart-sub2">EP yang sudah punya skor</div>
                            </div>
                            <span class="pe-chart-total <?php echo $g_p_nilai >= 80 ? 'ok' : ($g_p_nilai >= 30 ? 'mid' : 'low') ?>"><?php echo $g_p_nilai ?>%</span>
                        </div>
                        <div class="pe-chart-wrap">
                            <canvas id="chartNilai"></canvas>
                        </div>
                    </div>
                </div>

                <div class="pe-grid">
                    <?php foreach ($pokja_list as $p):
                        $total_ep    = intval($p->total_ep);
                        $ep_dinilai  = intval($p->ep_dinilai);
                        $persen_isi  = $total_ep > 0 ? round(($ep_dinilai / $total_ep) * 100) : 0;
                        $skor_maks   = floatval($p->total_skor_maks);
                        $persen_skor = $skor_maks > 0 ? round((floatval($p->total_skor) / $skor_maks) * 100) : 0;
                        $complete    = ($total_ep > 0 && $ep_dinilai == $total_ep);

                        // Kelas warna badge persentase skor
                        $score_class = 'pe-score-low';
                        if ($persen_skor >= 80) {
                            $score_class = 'pe-score-high';
                        } elseif ($persen_skor >= 20) {
                            $score_class = 'pe-score-mid';
                        }
                    ?>
                    <a href="<?php echo site_url('penilaian_ep/pokja/' . urlencode($p->bab)) ?>" class="pe-pokja-card">
                        <span class="pe-pokja-persen <?php echo $score_class ?>"><?php echo $persen_skor ?>%</span>
                        <span class="pe-akses <?php echo !empty($p->boleh_nilai) ? 'penilai' : 'lihat' ?>">
                            <?php echo !empty($p->boleh_nilai) ? '✏️ Penilai' : '👁️ Lihat saja' ?>
                        </span>
                        <span class="pe-pokja-bab"><?php echo html_escape($p->bab) ?></span>
                        <div class="pe-pokja-nama"><?php echo html_escape($p->ket) ?></div>
                        
                        <div class="pe-progress-track">
                            <div class="pe-progress-fill <?php echo $complete ? 'pe-complete' : '' ?>" style="width: <?php echo $persen_isi ?>%;"></div>
                        </div>
                        
                        <div class="pe-pokja-meta">
                            <span><?php echo $ep_dinilai ?> / <?php echo $total_ep ?> EP dinilai</span>
                            <span><?php echo $complete ? '✅ Selesai' : $persen_isi . '%' ?></span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>

                <script>
                (function () {
                    if (typeof Chart === 'undefined') return;

                    var label     = <?php echo json_encode($grafik_data['label']); ?>;
                    var totalEP  = <?php echo json_encode($grafik_data['total_ep']); ?>;
                    var dataUp   = <?php echo json_encode($grafik_data['upload']); ?>;
                    var dataNil  = <?php echo json_encode($grafik_data['nilai']); ?>;

                    // Ambang warna sesuai permintaan:
                    //   >= 80%  hijau   (bagus)
                    //   >= 30%  kuning  (cukup, < 80%)
                    //   <  30%  merah   (kurang)
                    function warnaBatang(p) {
                        if (p >= 80) return { bg: 'rgba(47, 191, 113, 0.85)',  border: '#1e9e55', legend: '≥ 80% Bagus' };
                        if (p >= 30) return { bg: 'rgba(247, 183, 49, 0.85)',  border: '#e08e0b', legend: '30–79% Cukup' };
                        return          { bg: 'rgba(236, 95, 95, 0.85)',   border: '#c0392b', legend: '< 30% Kurang' };
                    }

                    // Chart.js v2 tidak punya property.color pada legend, jadi
                    // generate label legend manual mengikuti warna batang.
                    // Dipisah per dataset karena sebaran warna tiap grafik bisa beda.
                    function legendCustom(data) {
                        var urut = ['≥ 80% Bagus', '30–79% Cukup', '< 30% Kurang'];
                        var konv = {
                            '≥ 80% Bagus': '#1e9e55',
                            '30–79% Cukup': '#e08e0b',
                            '< 30% Kurang': '#c0392b'
                        };
                        var ada = {};
                        for (var i = 0; i < data.length; i++) ada[warnaBatang(data[i]).legend] = true;
                        return urut.filter(function (k) { return ada[k]; })
                                   .map(function (k) { return { label: k, color: konv[k] }; });
                    }

                    Chart.defaults.global.defaultFontFamily = "'Segoe UI', system-ui, -apple-system, sans-serif";
                    Chart.defaults.global.defaultFontSize = 11;
                    Chart.defaults.global.defaultFontColor = '#5b6b80';

                    var tooltip = {
                        backgroundColor: 'rgba(45, 34, 66, 0.94)',
                        titleFontSize: 12,
                        bodyFontSize: 12,
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            title: function (items) {
                                var i = items[0].index;
                                return label[i] + '  (' + totalEP[i] + ' EP)';
                            },
                            label: function (item) {
                                var i = item.index;
                                var jumlah = Math.round(totalEP[i] * item.y / 100);
                                return jumlah + ' dari ' + totalEP[i] + ' EP  (' + item.y + '%)';
                            },
                            afterLabel: function (item) {
                                var c = warnaBatang(item.y);
                                return c.legend + ' — klik bar untuk buka detail pokja';
                            }
                        }
                    };

                    function opsiGrafik(canvasId, data) {
                        var ctx = document.getElementById(canvasId);
                        if (!ctx) return null;

                        var leg = legendCustom(data);

                        return new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: label,
                                datasets: [{
                                    label: 'Persentase',
                                    data: data,
                                    backgroundColor: data.map(function (v) { return warnaBatang(v).bg; }),
                                    borderColor: data.map(function (v) { return warnaBatang(v).border; }),
                                    borderWidth: 1.5,
                                    borderSkipped: false,
                                    borderRadius: 5,
                                    hoverBackgroundColor: '#3d2266'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: { duration: 700, easing: 'easeOutQuart' },
                                layout: { padding: { top: 4, right: 4 } },
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12,
                                        boxHeight: 12,
                                        padding: 14,
                                        fontColor: '#5b6b80',
                                        fontSize: 11,
                                        usePointStyle: true,
                                        generateLabels: function (chart) {
                                            return leg.map(function (l) {
                                                return {
                                                    text: l.label,
                                                    fillStyle: l.color,
                                                    strokeStyle: l.color,
                                                    fontColor: '#5b6b80',
                                                    fontSize: 11,
                                                    lineWidth: 0
                                                };
                                            });
                                        }
                                    }
                                },
                                onClick: function (evt, elements) {
                                    if (!elements.length) return;
                                    var bab = label[elements[0].index];
                                    window.location = '<?php echo site_url('penilaian_ep/pokja/'); ?>' + encodeURIComponent(bab);
                                },
                                onHover: function (evt, elements) {
                                    evt.native.target.style.cursor = elements.length ? 'pointer' : 'default';
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            max: 100,
                                            stepSize: 25,
                                            callback: function (v) { return v + '%'; }
                                        },
                                        gridLines: { color: 'rgba(139, 148, 166, 0.16)', drawBorder: false },
                        scaleLabel: { display: true, labelString: 'Persentase EP' }
                                    }],
                                    xAxes: [{
                                        gridLines: { display: false, drawBorder: false },
                                        ticks: {
                                            autoSkip: false,
                                            maxRotation: 62,
                                            minRotation: 0,
                                            fontSize: window.innerWidth < 768 ? 9 : 11
                                        }
                                    }]
                                },
                                tooltips: tooltip,
                                hover: { mode: 'nearest', intersect: true }
                            }
                        });
                    }

                    opsiGrafik('chartUpload', dataUp);
                    opsiGrafik('chartNilai',  dataNil);
                })();
                </script>
            <?php endif; ?>
        </div>
    </div>
</div>