<style>
    .psx-head {
        background: linear-gradient(135deg, #6a3ea1 0%, #3d2266 100%);
        color: #fff;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    }
    .psx-crumb { font-size: 0.78rem; opacity: 0.85; margin-bottom: 8px; }
    .psx-crumb a { color: #fff; text-decoration: underline; }
    .psx-crumb a:hover { color: #fff; opacity: 0.8; }
    .psx-title { margin: 0; font-size: 1.2rem; font-weight: 700; }
    .psx-sub { margin: 4px 0 0; font-size: 0.82rem; opacity: 0.9; }

    .psx-stats { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
    .psx-chip {
        background: rgba(255,255,255,0.16);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .psx-toolbar { display: flex; flex-wrap: wrap; gap: 8px; justify-content: space-between; margin-bottom: 16px; }

    /* ===== Accordion ===== */
    .psx-acc { display: flex; flex-direction: column; gap: 10px; }

    .psx-item {
        position: relative;
        border: 1px solid #e6e8ee;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    /* Tombol aksi duduk di atas header-row, karena header-row itu <button>
       dan tidak boleh dalamnya ada <button> lain. */
    .psx-aksi { position: absolute; top: 12px; right: 14px; z-index: 3; }
    .psx-aksi a {
        display: inline-block;
        background: #f3eefb;
        border: 1px solid #dcc9f2;
        color: #6a3ea1;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        white-space: nowrap;
        text-decoration: none;
    }
    .psx-aksi a:hover { background: #e9dbf7; color: #6a3ea1; text-decoration: none; }
    @media (max-width: 768px) {
        .psx-head-row { padding-right: 16px; padding-top: 44px; }
        .psx-aksi { top: 10px; right: 12px; }
    }
    .psx-item.nonaktif { background: #fbfbfc; }
    .psx-item.nonaktif .psx-head-row { opacity: 0.65; }

    .psx-head-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        width: 100%;
        padding: 14px 16px;
        background: none;
        border: none;
        text-align: left;
        cursor: pointer;
        font-family: inherit;
    }
    .psx-head-row:hover { background: #f8fafc; }
    .psx-head-row:focus { outline: 2px solid #6a3ea1; outline-offset: -2px; }

    .psx-caret {
        flex: 0 0 auto;
        margin-top: 3px;
        width: 20px; height: 20px;
        border-radius: 50%;
        background: #f3eefb;
        color: #6a3ea1;
        font-size: 0.7rem;
        font-weight: 800;
        display: inline-flex; align-items: center; justify-content: center;
        transition: transform 0.2s ease;
    }
    .psx-item.open .psx-caret { transform: rotate(90deg); }

    .psx-head-body { flex: 1; min-width: 0; }
    .psx-no {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 3px;
        flex-wrap: wrap;
    }
    .psx-nomor {
        background: #6a3ea1;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 2px 10px;
        border-radius: 20px;
    }
    .psx-badge {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .psx-badge.ok    { background: #d4edda; color: #1e7e34; }
    .psx-badge.off   { background: #e9ecef; color: #6c757d; }
    .psx-badge.ep    { background: #e8f0fd; color: #1a4fa0; }

    .psx-isi {
        font-size: 0.87rem;
        color: #23324a;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .psx-item.open .psx-isi { -webkit-line-clamp: unset; display: block; }

    .psx-maksud {
        margin-top: 8px;
        padding: 10px 12px;
        background: #f8f7fb;
        border-left: 3px solid #cfc3e6;
        border-radius: 0 8px 8px 0;
        font-size: 0.8rem;
        color: #5b6b80;
        line-height: 1.5;
    }
    .psx-maksud b { color: #3d2266; }

    /* ===== Isi EP ===== */
    .psx-panel { display: none; border-top: 1px dashed #e6e8ee; background: #fcfcfd; }
    .psx-item.open .psx-panel { display: block; }
    .psx-panel-inner { padding: 14px 16px 16px 44px; }
    @media (max-width: 576px) { .psx-panel-inner { padding-left: 16px; } }

    .psx-panel-judul {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #8a94a6;
        margin-bottom: 10px;
    }

    .psx-ep {
        border: 1px solid #eef0f3;
        border-radius: 10px;
        background: #fff;
        padding: 12px 14px;
        margin-bottom: 8px;
    }
    .psx-ep:last-child { margin-bottom: 0; }
    .psx-ep-top { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 5px; }
    .psx-ep-no {
        background: #eef2f7;
        color: #3d2266;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 2px 9px;
        border-radius: 20px;
    }
    .psx-resmi {
        background: #f8f9fa;
        border: 1px dashed #cfd4dc;
        color: #6c757d;
        font-size: 0.68rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
    }
    .psx-ep-isi { font-size: 0.84rem; color: #33475b; line-height: 1.5; }
    .psx-ep-meta {
        display: flex; flex-wrap: wrap; gap: 12px;
        margin-top: 8px; padding-top: 8px;
        border-top: 1px solid #f4f5f7;
        font-size: 0.73rem; color: #8a94a6;
    }
    .psx-ep-meta b { color: #33475b; font-weight: 700; }
    .psx-ep.off { background: #fbfbfc; }
    .psx-ep.off .psx-ep-isi { color: #8a94a6; }

    .psx-empty {
        text-align: center;
        padding: 14px;
        color: #b6bcc7;
        font-size: 0.82rem;
        font-style: italic;
    }

    .psx-legend {
        font-size: 0.75rem;
        color: #8a94a6;
        margin-top: 14px;
        line-height: 1.6;
    }
    .psx-legend code {
        background: #f1f3f6; color: #3d2266;
        padding: 1px 6px; border-radius: 4px; font-size: 0.72rem;
    }
</style>

<div class="container-fluid">

    <div class="psx-head">
        <div class="psx-crumb">
            <a href="<?php echo site_url('master_ep'); ?>">Master Data SIPARDI</a> &rsaquo;
            <a href="<?php echo site_url('master_ep/pokja'); ?>">Data Pokja</a> &rsaquo;
            Standar &amp; EP
        </div>
        <h1 class="psx-title"><?php echo html_escape($pokja->ket); ?></h1>
        <p class="psx-sub">Klik salah satu standar untuk melihat EP di dalamnya.</p>
        <div class="psx-stats">
            <span class="psx-chip">BAB: <?php echo html_escape($pokja->bab); ?></span>
            <span class="psx-chip"><?php echo (int)$jml_standar; ?> standar</span>
            <span class="psx-chip"><?php echo (int)$jml_ep; ?> EP</span>
        </div>
    </div>

    <div class="psx-toolbar">
        <div>
            <?php if (!$hanya_aktif): ?>
                <a href="<?php echo site_url('master_ep/pokja_standar/' . $pokja->id); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i> Tampilkan yang aktif saja
                </a>
            <?php endif; ?>
        </div>
        <div>
            <a href="<?php echo site_url('master_ep/pokja_form/' . $pokja->id); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-edit"></i> Edit Pokja
            </a>
            <a href="<?php echo site_url('master_ep/pokja'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><?php echo $flash; ?><?php endif; ?>

    <?php if (empty($standar_list)): ?>
        <div class="card shadow">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-folder-open fa-2x mb-3"></i>
                <div>Belum ada standar untuk pokja ini<?php echo $hanya_aktif ? ' yang aktif' : ''; ?>.</div>
                <?php if ($hanya_aktif): ?>
                    <a href="<?php echo site_url('master_ep/pokja_standar/' . $pokja->id . '/?semua=1'); ?>" class="btn btn-sm btn-outline-primary mt-3">
                        Lihat termasuk yang nonaktif
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="psx-acc">
            <?php $idx = 0; foreach ($standar_list as $s):
                $is_open  = ($idx === 0);
                $ep_list  = isset($ep_map[$s->id_standar]) ? $ep_map[$s->id_standar] : array();
                $jml_ep   = (int)$s->jml_ep;
            ?>
                <div class="psx-item<?php echo $is_open ? ' open' : ''; ?><?php echo $s->active !== 'Y' ? ' nonaktif' : ''; ?>">
                    <?php if ($jml_ep > 0): ?>
                        <span class="psx-aksi">
                            <a href="<?php echo site_url('master_ep/rapikan_urutan/' . $s->id_standar); ?>"
                               title="Rapikan nomor tampilan EP jadi 1, 2, 3, ... tanpa celah. Nomor resmi tidak diubah.">
                                ✨ Rapikan nomor
                            </a>
                        </span>
                    <?php endif; ?>
                    <button type="button" class="psx-head-row" data-psx-toggle>
                        <span class="psx-caret">&#9656;</span>
                        <span class="psx-head-body">
                            <span class="psx-no">
                                <span class="psx-nomor">Standar <?php echo html_escape($s->no_standar); ?></span>
                                <?php if ($s->active !== 'Y'): ?>
                                    <span class="psx-badge off">Nonaktif</span>
                                <?php endif; ?>
                                <span class="psx-badge ep"><?php echo $jml_ep; ?> EP</span>
                            </span>
                            <span class="psx-isi"><?php echo html_escape($s->isi_standar); ?></span>
                            <?php if (!empty($s->maksud_tujuan)): ?>
                                <span class="psx-maksud">
                                    <b>Maksud &amp; tujuan:</b> <?php echo html_escape($s->maksud_tujuan); ?>
                                </span>
                            <?php endif; ?>
                        </span>
                    </button>

                    <div class="psx-panel">
                        <div class="psx-panel-inner">
                            <div class="psx-panel-judul">Elemen Penilaian (EP)</div>
                            <?php if (empty($ep_list)): ?>
                                <div class="psx-empty">Belum ada EP pada standar ini<?php echo $hanya_aktif ? ' yang aktif' : ''; ?>.</div>
                            <?php else: ?>
                                <?php foreach ($ep_list as $ep): ?>
                                    <div class="psx-ep<?php echo $ep->active !== 'Y' ? ' off' : ''; ?>">
                                        <div class="psx-ep-top">
                                            <div class="psx-ep-no">EP <?php echo $ep->no_urut !== NULL ? (int)$ep->no_urut : (int)$ep->no_ep; ?></div>
                                            <?php if ((int)$ep->no_urut !== (int)$ep->no_ep): ?>
                                                <span class="psx-resmi" title="Nomor resmi dari dokumen SIPARDI/Kemenkes">no. resmi: <?php echo (int)$ep->no_ep; ?></span>
                                            <?php endif; ?>
                                            <?php if ($ep->active !== 'Y'): ?>
                                                <span class="psx-badge off">Nonaktif</span>
                                            <?php endif; ?>
                                            <?php if ($ep->tdd === 'Y'): ?>
                                                <span class="psx-badge ok">Ditunda</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="psx-ep-isi"><?php echo html_escape($ep->isi_ep); ?></div>
                                        <div class="psx-ep-meta">
                                            <span>Jenis bukti: <b><?php echo !empty($ep->jenis_bukti) ? html_escape($ep->jenis_bukti) : '-' ?></b></span>
                                            <span>Skor maks: <b><?php echo (int)$ep->skor_maks; ?></b></span>
                                            <span>Bukti terupload: <b><?php echo (int)$ep->jml_bukti; ?></b> <i>(semua periode)</i></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php $idx++; endforeach; ?>
        </div>

        <div class="psx-legend">
            <b>Keterangan:</b>
            Standar &amp; EP yang dinonaktifkan ditampilkan dengan badge abu-abu.
            <code>TDD = Y</code> berarti elemen tersebut ditunda dan tidak ikut dihitung di penilaian.
            Halaman ini hanya untuk melihat data — ubah lewat menu
            <a href="<?php echo site_url('master_ep/standar'); ?>">Data Standar</a> atau
            <a href="<?php echo site_url('master_ep/elemen'); ?>">Data Elemen Penilaian</a>.
        </div>
    <?php endif; ?>
</div>

<script>
// jQuery dimuat di template/footer, jadi script ini baru jalan setelah load.
window.addEventListener('load', function () {
    var $ = window.jQuery;
    if (typeof $ === 'undefined') return;

    // Accordion satu panel terbuka pada satu waktu, klik yang sama untuk tutup.
    $(document).on('click', '[data-psx-toggle]', function () {
        var $item = $(this).closest('.psx-item');
        var wasOpen = $item.hasClass('open');

        $('.psx-item.open').removeClass('open');

        if (!wasOpen) $item.addClass('open');
    });
});
</script>