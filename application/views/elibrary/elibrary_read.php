<?php
/**
 * Detail literatur E-Library.
 * Render oleh E_library::detail().
 */
$el_url   = elibrary_safe_url($entry->url_asli);
$el_doi   = elibrary_doi_url($entry->doi);
$el_kunci = elibrary_kata_kunci($entry->kata_kunci);
$el_home  = elibrary_safe_url($entry->source_homepage);
?>
<link rel="stylesheet" href="<?= base_url('assets/css/elibrary.css'); ?>">

<div class="el-page">

    <!-- ============================== HERO ============================== -->
    <section class="el-hero">
        <div class="el-hero__inner">
            <div style="min-width:0;flex:1 1 420px;">
                <span class="el-eyebrow">
                    <i class="fas fa-book-open"></i> <?= html_escape(elibrary_jenis_label($entry->jenis)); ?>
                </span>
                <h1 class="el-hero__title"><?= html_escape($entry->judul); ?></h1>
                <p class="el-hero__subtitle">
                    <?= html_escape($entry->penulis !== '' && $entry->penulis !== NULL ? $entry->penulis : 'Penulis tidak dicantumkan'); ?>
                </p>

                <div class="el-chips mt-3">
                    <?php if ($entry->tahun): ?>
                        <span class="el-chip"><i class="fas fa-calendar"></i> <?= (int) $entry->tahun; ?></span>
                    <?php endif; ?>
                    <span class="el-chip el-chip--accent"><i class="fas fa-bookmark"></i> <?= html_escape(elibrary_nama_source($entry)); ?></span>
                    <?php if (!empty($entry->doi)): ?>
                        <span class="el-chip el-chip--gold"><i class="fas fa-fingerprint"></i> <?= html_escape($entry->doi); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($entry->bahasa)): ?>
                        <span class="el-chip el-chip--blue"><i class="fas fa-language"></i> <?= html_escape($entry->bahasa); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="el-stats" style="flex-direction:column;">
                <?php if ($el_url !== ''): ?>
                    <a href="<?= site_url('e_library/buka/' . (int) $entry->id); ?>"
                       class="el-btn el-btn--primary" target="_blank" rel="noopener" style="background:#fff;color:var(--el-accent-2);">
                        <i class="fas fa-external-link-alt"></i> Buka di Sumber
                    </a>
                <?php else: ?>
                    <span class="el-btn el-btn--ghost" style="opacity:.7;cursor:default;">
                        <i class="fas fa-link-slash"></i> Tanpa Tautan Sumber
                    </span>
                <?php endif; ?>
                <a href="<?= site_url('e_library'); ?>" class="el-btn el-btn--ghost" style="background:transparent;border-color:rgba(255,255,255,.35);color:#fff;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                </a>
                <?php if (!empty($is_admin)): ?>
                    <a href="<?= site_url('e_library/update/' . (int) $entry->id); ?>" class="el-btn el-btn--ghost" style="background:transparent;border-color:rgba(255,255,255,.35);color:#fff;">
                        <i class="fas fa-pen"></i> Ubah Entri
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="row">

        <!-- ========================== INFORMASI ========================== -->
        <div class="col-lg-8 mb-4">
            <div class="el-panel mb-3">
                <div class="el-panel__head">
                    <h2 class="el-panel__title"><i class="fas fa-file-lines"></i> Informasi Terperinci</h2>
                </div>
                <div class="el-panel__body">
                    <div class="el-meta">
                        <div class="el-meta__item">
                            <div class="el-meta__label">Penulis</div>
                            <div class="el-meta__value"><?= html_escape($entry->penulis !== '' && $entry->penulis !== NULL ? $entry->penulis : '-'); ?></div>
                        </div>
                        <div class="el-meta__item">
                            <div class="el-meta__label">Tahun Terbit</div>
                            <div class="el-meta__value"><?= $entry->tahun ? (int) $entry->tahun : '-'; ?></div>
                        </div>
                        <div class="el-meta__item">
                            <div class="el-meta__label">Jurnal / Buku</div>
                            <div class="el-meta__value"><?= html_escape($entry->nama_jurnal !== '' && $entry->nama_jurnal !== NULL ? $entry->nama_jurnal : '-'); ?></div>
                        </div>
                        <div class="el-meta__item">
                            <div class="el-meta__label">Volume / Nomor / Halaman</div>
                            <div class="el-meta__value">
                                <?php
                                    $el_vol  = trim(($entry->volume ?: '') . ($entry->nomor ? ' No. ' . $entry->nomor : ''));
                                    $el_hal  = trim((string) $entry->halaman);
                                    echo html_escape(($el_vol !== '' ? $el_vol : '-') . ' / ' . ($el_hal !== '' ? $el_hal : '-'));
                                ?>
                            </div>
                        </div>
                        <div class="el-meta__item">
                            <div class="el-meta__label">DOI</div>
                            <div class="el-meta__value">
                                <?php if ($el_doi !== ''): ?>
                                    <a href="<?= html_escape($el_doi); ?>" target="_blank" rel="noopener"><?= html_escape($entry->doi); ?></a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="el-meta__item">
                            <div class="el-meta__label">Bahasa</div>
                            <div class="el-meta__value"><?= html_escape($entry->bahasa !== '' && $entry->bahasa !== NULL ? $entry->bahasa : '-'); ?></div>
                        </div>
                        <div class="el-meta__item">
                            <div class="el-meta__label">Tanggal Masuk Katalog</div>
                            <div class="el-meta__value"><?= html_escape(elibrary_tanggal($entry->tanggal_masuk)); ?></div>
                        </div>
                        <div class="el-meta__item">
                            <div class="el-meta__label">Sitasi</div>
                            <div class="el-meta__value"><?= (int) $entry->sitasi; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="el-panel mb-3">
                <div class="el-panel__head">
                    <h2 class="el-panel__title"><i class="fas fa-align-left"></i> Abstrak</h2>
                </div>
                <div class="el-panel__body">
                    <?php if (!empty($entry->abstrak)): ?>
                        <div class="el-abstract"><?= html_escape($entry->abstrak); ?></div>
                    <?php else: ?>
                        <div style="color:var(--el-muted);font-size:.88rem;">Abstrak belum tersedia pada entri ini.</div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($el_kunci)): ?>
                <div class="el-panel mb-3">
                    <div class="el-panel__head">
                        <h2 class="el-panel__title"><i class="fas fa-key"></i> Kata Kunci</h2>
                    </div>
                    <div class="el-panel__body">
                        <div class="el-chips">
                            <?php foreach ($el_kunci as $kk): ?>
                                <span class="el-chip el-chip--gold"><?= html_escape($kk); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($subjects)): ?>
                <div class="el-panel mb-3">
                    <div class="el-panel__head">
                        <h2 class="el-panel__title"><i class="fas fa-tags"></i> Subjek</h2>
                    </div>
                    <div class="el-panel__body">
                        <div class="el-chips">
                            <?php foreach ($subjects as $sub): ?>
                                <a href="<?= site_url('e_library?subjek=' . urlencode($sub->slug)); ?>" class="el-chip el-chip--accent" style="text-decoration:none;">
                                    <?= html_escape($sub->nama); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- =========================== SUMBER =========================== -->
        <div class="col-lg-4 mb-4">
            <div class="el-source mb-3">
                <div class="el-source__name">
                    <i class="fas fa-bookmark"></i> <?= html_escape(elibrary_nama_source($entry)); ?>
                </div>
                <?php if (!empty($entry->source_penerbit)): ?>
                    <div style="font-size:.83rem;color:var(--el-muted);margin-bottom:6px;">
                        Penerbit: <?= html_escape($entry->source_penerbit); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($entry->source_deskripsi)): ?>
                    <div style="font-size:.83rem;color:#3E525A;line-height:1.6;margin-bottom:8px;">
                        <?= html_escape(elibrary_excerpt($entry->source_deskripsi, 160)); ?>
                    </div>
                <?php endif; ?>
                <?php if ($el_home !== ''): ?>
                    <a href="<?= html_escape($el_home); ?>" class="el-btn el-btn--ghost el-btn--sm" target="_blank" rel="noopener">
                        <i class="fas fa-globe"></i> Halaman Sumber
                    </a>
                <?php endif; ?>
                <?php if ($el_url !== ''): ?>
                    <a href="<?= site_url('e_library/buka/' . (int) $entry->id); ?>" class="el-btn el-btn--primary el-btn--sm mt-2" target="_blank" rel="noopener">
                        <i class="fas fa-external-link-alt"></i> Buka di Sumber
                    </a>
                <?php endif; ?>
            </div>

            <div class="el-note mb-3">
                <i class="fas fa-shield-alt"></i>
                <div>
                    Berkas asli (PDF) tidak disimpan pada sistem ini. Pembacaan
                    dilakukan melalui tautan sumber asli, dan setiap akses
                    tercatat pada log E-Library.
                </div>
            </div>

            <a href="<?= site_url('e_library'); ?>" class="el-btn el-btn--ghost el-btn--block">
                <i class="fas fa-arrow-left"></i> Kembali ke Katalog
            </a>
        </div>
    </div>
</div>
