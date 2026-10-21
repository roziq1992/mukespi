<?php
/**
 * Katalog E-Library — daftar literatur dengan pencarian & filter.
 * Render oleh E_library::index().
 */
$el_total   = isset($total_rows) ? (int) $total_rows : 0;
$el_perpage = isset($per_page) ? (int) $per_page : 12;
$el_page    = isset($page) ? (int) $page : 1;
$el_total_pages = $el_perpage > 0 ? (int) ceil($el_total / $el_perpage) : 1;
$el_has_filter = ($filters['q'] !== '' || $filters['source_id'] > 0 || $filters['tahun'] > 0 || $filters['subject'] !== '' || $filters['jenis'] !== '');
?>
<link rel="stylesheet" href="<?= base_url('assets/css/elibrary.css'); ?>">

<div class="el-page">

    <!-- ============================== HERO ============================== -->
    <section class="el-hero">
        <div class="el-hero__inner">
            <div>
                <span class="el-eyebrow">
                    <i class="fas fa-book-open"></i> Portal Penemuan Literatur
                </span>
                <h1 class="el-hero__title">E-Library RS Airlangga</h1>
                <p class="el-hero__subtitle">
                    Katalog metadata literatur untuk mendukung telaah,iji, dan
                    pengembangan mutu pelayanan. Berkas penuh tetap berada di
                    sumber aslinya &mdash; modul ini hanya menyimpan metadata
                    dan tautannya.
                </p>
            </div>

            <div class="el-stats">
                <div class="el-stat">
                    <div class="el-stat__value"><?php echo number_format($total_entries, 0, ',', '.'); ?></div>
                    <div class="el-stat__label">Entri</div>
                </div>
                <div class="el-stat">
                    <div class="el-stat__value"><?php echo number_format($total_sources, 0, ',', '.'); ?></div>
                    <div class="el-stat__label">Sumber</div>
                </div>
                <div class="el-stat">
                    <div class="el-stat__value"><?php echo number_format(count($subjects), 0, ',', '.'); ?></div>
                    <div class="el-stat__label">Subjek</div>
                </div>
            </div>
        </div>
    </section>

    <div class="row">

        <?php if (!empty($is_admin)): ?>
            <div class="el-adminbar">
                <h2 class="el-adminbar__title"><i class="fas fa-user-shield"></i> Mode Admin</h2>
                <div class="el-adminbar__actions">
                    <a href="<?= site_url('e_library/manage'); ?>" class="el-btn el-btn--soft el-btn--sm">
                        <i class="fas fa-layer-group"></i> Kelola Entri
                    </a>
                    <a href="<?= site_url('e_library/import'); ?>" class="el-btn el-btn--ghost el-btn--sm">
                        <i class="fas fa-file-import"></i> Impor CSV
                    </a>
                    <a href="<?= site_url('e_library/access_log'); ?>" class="el-btn el-btn--ghost el-btn--sm">
                        <i class="fas fa-chart-line"></i> Log Akses
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- ============================ FILTER ============================ -->
        <div class="col-xl-3 col-lg-4 mb-4">
            <div class="el-panel mb-3">
                <div class="el-panel__head">
                    <h2 class="el-panel__title"><i class="fas fa-filter"></i> Pencarian &amp; Filter</h2>
                </div>
                <div class="el-panel__body">
                    <form action="<?= site_url('e_library'); ?>" method="get" autocomplete="off">
                        <div class="el-field">
                            <label class="el-label" for="el_q">Kata Kunci</label>
                            <input type="text" id="el_q" name="q" class="el-input"
                                   placeholder="Judul, penulis, abstrak, DOI…"
                                   value="<?= html_escape($filters['q']); ?>">
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_sumber">Sumber</label>
                            <select id="el_sumber" name="sumber" class="el-select">
                                <option value="">Semua sumber</option>
                                <?php foreach ($sources as $src): ?>
                                    <option value="<?= (int) $src->id; ?>" <?= ((int) $filters['source_id'] === (int) $src->id) ? 'selected' : ''; ?>>
                                        <?= html_escape($src->nama); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_tahun">Tahun Terbit</label>
                            <select id="el_tahun" name="tahun" class="el-select">
                                <option value="">Semua tahun</option>
                                <?php foreach ($years as $th): ?>
                                    <option value="<?= (int) $th; ?>" <?= ((int) $filters['tahun'] === (int) $th) ? 'selected' : ''; ?>>
                                        <?= (int) $th; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_subjek">Subjek</label>
                            <select id="el_subjek" name="subjek" class="el-select">
                                <option value="">Semua subjek</option>
                                <?php foreach ($subjects as $sub): ?>
                                    <option value="<?= html_escape($sub->slug); ?>" <?= ($filters['subject'] === $sub->slug) ? 'selected' : ''; ?>>
                                        <?= html_escape($sub->nama); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_jenis">Jenis</label>
                            <select id="el_jenis" name="jenis" class="el-select">
                                <option value="">Semua jenis</option>
                                <option value="artikel" <?= ($filters['jenis'] === 'artikel') ? 'selected' : ''; ?>>Artikel Jurnal</option>
                                <option value="buku" <?= ($filters['jenis'] === 'buku') ? 'selected' : ''; ?>>Buku</option>
                                <option value="tesis" <?= ($filters['jenis'] === 'tesis') ? 'selected' : ''; ?>>Tesis</option>
                                <option value="laporan" <?= ($filters['jenis'] === 'laporan') ? 'selected' : ''; ?>>Laporan</option>
                                <option value="conference" <?= ($filters['jenis'] === 'conference') ? 'selected' : ''; ?>>Konferensi</option>
                                <option value="lainnya" <?= ($filters['jenis'] === 'lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                            </select>
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_sort">Urutkan</label>
                            <select id="el_sort" name="sort" class="el-select">
                                <option value="" <?= ($filters['sort'] === '') ? 'selected' : ''; ?>>Terbaru masuk</option>
                                <option value="tahun" <?= ($filters['sort'] === 'tahun') ? 'selected' : ''; ?>>Tahun terbit</option>
                                <option value="judul" <?= ($filters['sort'] === 'judul') ? 'selected' : ''; ?>>Judul (A-Z)</option>
                                <option value="sitasi" <?= ($filters['sort'] === 'sitasi') ? 'selected' : ''; ?>>Sitasi terbanyak</option>
                                <option value="terlama" <?= ($filters['sort'] === 'terlama') ? 'selected' : ''; ?>>Terlama masuk</option>
                            </select>
                        </div>

                        <button type="submit" class="el-btn el-btn--primary el-btn--block">
                            <i class="fas fa-search"></i> Cari Literatur
                        </button>
                        <a href="<?= site_url('e_library'); ?>" class="el-btn el-btn--ghost el-btn--block mt-2">
                            <i class="fas fa-undo"></i> Atur Ulang
                        </a>
                    </form>
                </div>
            </div>

            <?php if (!empty($recent)): ?>
                <div class="el-panel">
                    <div class="el-panel__head">
                        <h2 class="el-panel__title"><i class="fas fa-history"></i> Terakhir Anda Buka</h2>
                    </div>
                    <div class="el-panel__body">
                        <?php foreach ($recent as $rec): ?>
                            <div style="margin-bottom:10px;">
                                <a href="<?= site_url('e_library/detail/' . (int) $rec->id); ?>"
                                   style="font-size:.83rem;font-weight:700;color:var(--el-ink);">
                                    <?= html_escape(elibrary_excerpt($rec->judul, 70)); ?>
                                </a>
                                <div style="font-size:.72rem;color:var(--el-muted);">
                                    <?= $rec->tahun ? (int) $rec->tahun : 'Tanpa tahun'; ?>
                                    &middot; <?= html_escape(elibrary_excerpt($rec->nama_jurnal, 28)); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- ============================ HASIL ============================ -->
        <div class="col-xl-9 col-lg-8">

            <?php
                $flash = $this->session->flashdata('message');
                if ($flash <> '') {
                    echo $flash;
                }
            ?>

            <div class="el-resultbar">
                <div>
                    <strong style="color:var(--el-ink);"><?php echo number_format($el_total, 0, ',', '.'); ?></strong>
                    literatur ditemukan
                    <?php if ($el_has_filter): ?>
                        &middot; halaman <?= (int) $el_page; ?> dari <?= (int) $el_total_pages; ?>
                    <?php endif; ?>
                </div>
                <?php if ($el_has_filter): ?>
                    <div class="el-chips">
                        <?php if ($filters['q'] !== ''): ?>
                            <span class="el-chip el-chip--accent"><i class="fas fa-quote-left"></i> <?= html_escape($filters['q']); ?></span>
                        <?php endif; ?>
                        <?php if ($filters['source_id'] > 0): ?>
                            <?php foreach ($sources as $src): ?>
                                <?php if ((int) $src->id === (int) $filters['source_id']): ?>
                                    <span class="el-chip el-chip--blue"><i class="fas fa-bookmark"></i> <?= html_escape($src->nama); ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($filters['tahun'] > 0): ?>
                            <span class="el-chip el-chip--gold"><i class="fas fa-calendar"></i> <?= (int) $filters['tahun']; ?></span>
                        <?php endif; ?>
                        <?php if ($filters['subject'] !== ''): ?>
                            <?php foreach ($subjects as $sub): ?>
                                <?php if ($sub->slug === $filters['subject']): ?>
                                    <span class="el-chip"><i class="fas fa-tag"></i> <?= html_escape($sub->nama); ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (empty($entries)): ?>
                <div class="el-panel">
                    <div class="el-empty">
                        <i class="fas fa-book-open"></i>
                        <?php if ($el_has_filter): ?>
                            <div class="el-empty__title">Tidak ada literatur yang cocok</div>
                            <div>Ubah kata kunci atau longgarkan filter yang dipakai.</div>
                            <a href="<?= site_url('e_library'); ?>" class="el-btn el-btn--ghost mt-3">
                                <i class="fas fa-undo"></i> Atur Ulang Pencarian
                            </a>
                        <?php elseif (!empty($is_admin)): ?>
                            <div class="el-empty__title">Katalog masih kosong</div>
                            <div>Mulai isi katalog dengan menambahkan entri satu per satu, atau impor sekalian dari berkas CSV.</div>
                            <div class="mt-3">
                                <a href="<?= site_url('e_library/create'); ?>" class="el-btn el-btn--primary el-btn--sm">
                                    <i class="fas fa-plus"></i> Tambah Entri Pertama
                                </a>
                                <a href="<?= site_url('e_library/import'); ?>" class="el-btn el-btn--soft el-btn--sm">
                                    <i class="fas fa-file-import"></i> Impor CSV
                                </a>
                                <a href="<?= site_url('e_library/template_csv'); ?>" class="el-btn el-btn--ghost el-btn--sm">
                                    <i class="fas fa-download"></i> Unduh Template
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="el-empty__title">Belum ada literatur</div>
                            <div>Katalog sedang diisi oleh pengelola. Silakan kembali beberapa saat lagi.</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($entries as $row): ?>
                    <article class="el-entry">
                        <div style="display:flex;gap:10px;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;">
                            <div style="min-width:0;flex:1 1 320px;">
                                <h3 class="el-entry__title">
                                    <a href="<?= site_url('e_library/detail/' . (int) $row->id); ?>">
                                        <?= html_escape($row->judul); ?>
                                    </a>
                                </h3>
                                <div class="el-entry__authors">
                                    <i class="fas fa-user-alt"></i>
                                    <?= html_escape($row->penulis !== '' && $row->penulis !== NULL ? $row->penulis : 'Penulis tidak dicantumkan'); ?>
                                    &nbsp;&middot;&nbsp;
                                    <i class="fas fa-bookmark"></i>
                                    <?= html_escape(elibrary_nama_source($row)); ?>
                                    <?php if ($row->tahun): ?>
                                        &nbsp;&middot;&nbsp;
                                        <i class="fas fa-calendar-alt"></i> <?= (int) $row->tahun; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <span class="el-badge"><?= html_escape(elibrary_jenis_label($row->jenis)); ?></span>
                        </div>

                        <?php if (!empty($row->abstrak)): ?>
                            <p class="el-entry__abstract"><?= html_escape(elibrary_excerpt($row->abstrak, 240)); ?></p>
                        <?php endif; ?>

                        <div class="el-entry__foot">
                            <div class="el-chips">
                                <?php if (!empty($row->doi)): ?>
                                    <span class="el-chip"><i class="fas fa-fingerprint"></i> DOI: <?= html_escape($row->doi); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($row->volume)): ?>
                                    <span class="el-chip">Vol. <?= html_escape($row->volume); ?><?= !empty($row->nomor) ? ' No. ' . html_escape($row->nomor) : ''; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($row->kata_kunci)): ?>
                                    <span class="el-chip el-chip--gold"><i class="fas fa-key"></i> <?= html_escape(elibrary_excerpt($row->kata_kunci, 40)); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="el-entry__actions">
                                <a href="<?= site_url('e_library/detail/' . (int) $row->id); ?>" class="el-btn el-btn--ghost el-btn--sm">
                                    <i class="fas fa-info-circle"></i> Detail
                                </a>
                                <?php if (elibrary_safe_url($row->url_asli) !== ''): ?>
                                    <a href="<?= site_url('e_library/buka/' . (int) $row->id); ?>" class="el-btn el-btn--primary el-btn--sm" target="_blank" rel="noopener">
                                        <i class="fas fa-external-link-alt"></i> Buka di Sumber
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($pagination)): ?>
                <nav class="el-pagination">
                    <?php echo $pagination; ?>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</div>
