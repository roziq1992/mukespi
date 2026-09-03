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
</style>

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
            <?php else: ?>
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
            <?php endif; ?>
        </div>
    </div>
</div>