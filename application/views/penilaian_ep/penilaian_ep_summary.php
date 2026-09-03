<style>
    .ps-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
    }
    .ps-header {
        background: linear-gradient(135deg, #6a3fa0 0%, #3d2266 100%);
        color: #fff;
        padding: 22px 24px;
    }
    .ps-header .ps-breadcrumb { font-size: 0.78rem; opacity: 0.85; margin-bottom: 6px; }
    .ps-header .ps-breadcrumb a { color: #fff; text-decoration: underline; }
    .ps-header h2 { margin: 0; font-size: 1.25rem; font-weight: 700; }
    .ps-header p { margin: 4px 0 0; font-size: 0.82rem; opacity: 0.9; }
    .ps-header .ps-periode-badge {
        display: inline-block;
        margin-top: 10px;
        background: rgba(255,255,255,0.16);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .ps-body { padding: 22px; }
    @media (max-width: 576px) { .ps-body { padding: 14px; } }

    .ps-warning { text-align: center; padding: 40px 20px; color: #8a94a6; }
    .ps-warning .icon { font-size: 2.4rem; margin-bottom: 10px; }

    .ps-summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 22px;
    }
    .ps-mini-card {
        border: 1px solid #eef0f3;
        border-radius: 12px;
        padding: 14px 16px;
    }
    .ps-mini-card .ps-mini-label { font-size: 0.72rem; color: #8a94a6; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; }
    .ps-mini-card .ps-mini-value { font-size: 1.4rem; font-weight: 800; color: #33475b; margin-top: 4px; }
    .ps-mini-card.ps-mini-internal .ps-mini-value { color: #2c5f8a; }
    .ps-mini-card.ps-mini-surveior .ps-mini-value { color: #b8860b; }
    .ps-mini-card.ps-mini-selisih .ps-mini-value { color: #c0392b; }

    .ps-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .ps-table thead th {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #8a94a6;
        font-weight: 700;
        border-bottom: 2px solid #eef0f3;
        padding: 10px 12px;
        white-space: nowrap;
    }
    .ps-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #f1f3f6;
        font-size: 0.85rem;
        color: #33475b;
        vertical-align: middle;
    }
    .ps-table tbody tr:hover { background: #f8fafc; }
    .ps-bab-chip {
        display: inline-block;
        background: #efe6fa;
        color: #6a3fa0;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
        margin-right: 6px;
    }
    .ps-bar-track {
        height: 7px;
        width: 100px;
        border-radius: 20px;
        background: #eef0f3;
        overflow: hidden;
        display: inline-block;
        vertical-align: middle;
        margin-right: 6px;
    }
    .ps-bar-fill { height: 100%; border-radius: 20px; }
    .ps-bar-fill.internal { background: linear-gradient(90deg, #2c5f8a, #5a9bd4); }
    .ps-bar-fill.surveior { background: linear-gradient(90deg, #b8860b, #e0b23f); }

    .ps-selisih-pill {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .ps-selisih-0 { background: #d4edda; color: #1e7e34; }
    .ps-selisih-ada { background: #fdecea; color: #c0392b; }

    .ps-btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f3eefb;
        border: 1px solid #dcc9f2;
        color: #6a3fa0;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
    }
    .ps-btn-detail:hover { background: #e9dbf7; color: #6a3fa0; text-decoration: none; }

    .ps-empty { text-align: center; padding: 40px 16px; color: #8a94a6; }
</style>

<div class="container-fluid">
    <div class="ps-card">
        <div class="ps-header">
            <div class="ps-breadcrumb">
                <a href="<?php echo site_url('penilaian_ep') ?>">← Daftar Pokja</a>
            </div>
            <h2>📊 Summary Penilaian — Internal vs Surveior</h2>
            <p>Rekap perbandingan skor tim internal dan skor Surveior per Pokja</p>
            <?php if (!empty($periode)): ?>
                <span class="ps-periode-badge">📅 Periode aktif: <?php echo $periode->nama_periode ?></span>
            <?php endif; ?>
        </div>

        <div class="ps-body">
            <?php if (empty($periode)): ?>
                <div class="ps-warning">
                    <div class="icon">⚠️</div>
                    <strong>Belum ada periode akreditasi yang aktif</strong>
                </div>
            <?php elseif (empty($summary_list)): ?>
                <div class="ps-warning">
                    <div class="icon">🗂️</div>
                    Belum ada data pokja aktif.
                </div>
            <?php else:
                $grand_ep = $grand_maks = $grand_internal = $grand_surveior = $grand_selisih = 0;
                foreach ($summary_list as $s) {
                    $grand_ep       += intval($s->total_ep);
                    $grand_maks     += floatval($s->total_skor_maks);
                    $grand_internal += floatval($s->total_skor_internal);
                    $grand_surveior += floatval($s->total_skor_surveior);
                    $grand_selisih  += intval($s->jml_selisih);
                }
                $grand_persen_internal = $grand_maks > 0 ? round($grand_internal / $grand_maks * 100, 1) : 0;
                $grand_persen_surveior = $grand_maks > 0 ? round($grand_surveior / $grand_maks * 100, 1) : 0;
            ?>

            <div class="ps-summary-cards">
                <div class="ps-mini-card ps-mini-internal">
                    <div class="ps-mini-label">Skor Internal</div>
                    <div class="ps-mini-value"><?php echo $grand_persen_internal ?>%</div>
                </div>
                <div class="ps-mini-card ps-mini-surveior">
                    <div class="ps-mini-label">Skor Surveior</div>
                    <div class="ps-mini-value"><?php echo $grand_persen_surveior ?>%</div>
                </div>
                <div class="ps-mini-card ps-mini-selisih">
                    <div class="ps-mini-label">EP Beda Skor</div>
                    <div class="ps-mini-value"><?php echo $grand_selisih ?></div>
                </div>
                <div class="ps-mini-card">
                    <div class="ps-mini-label">Total EP</div>
                    <div class="ps-mini-value"><?php echo $grand_ep ?></div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="ps-table">
                    <thead>
                        <tr>
                            <th>Pokja</th>
                            <th>Progres Internal</th>
                            <th>Progres Surveior</th>
                            <th>EP Beda Skor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($summary_list as $s):
                            $total_ep       = intval($s->total_ep);
                            $skor_maks      = floatval($s->total_skor_maks);
                            $persen_internal = $skor_maks > 0 ? round(floatval($s->total_skor_internal) / $skor_maks * 100) : 0;
                            $persen_surveior = $skor_maks > 0 ? round(floatval($s->total_skor_surveior) / $skor_maks * 100) : 0;
                            $jml_selisih    = intval($s->jml_selisih);
                        ?>
                        <tr>
                            <td>
                                <span class="ps-bab-chip"><?php echo $s->bab ?></span>
                                <strong><?php echo $s->ket ?></strong>
                                <div class="text-muted" style="font-size:0.75rem;"><?php echo $s->ep_dinilai_internal ?>/<?php echo $total_ep ?> EP dinilai internal</div>
                            </td>
                            <td>
                                <div class="ps-bar-track"><div class="ps-bar-fill internal" style="width: <?php echo $persen_internal ?>%;"></div></div>
                                <?php echo $persen_internal ?>%
                            </td>
                            <td>
                                <div class="ps-bar-track"><div class="ps-bar-fill surveior" style="width: <?php echo $persen_surveior ?>%;"></div></div>
                                <?php echo $persen_surveior ?>%
                            </td>
                            <td>
                                <span class="ps-selisih-pill <?php echo $jml_selisih > 0 ? 'ps-selisih-ada' : 'ps-selisih-0' ?>">
                                    <?php echo $jml_selisih > 0 ? '⚠ ' . $jml_selisih . ' EP' : '✔ Sama semua' ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo site_url('penilaian_ep/pokja/' . urlencode($s->bab)) ?>" class="ps-btn-detail">🔍 Lihat Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
