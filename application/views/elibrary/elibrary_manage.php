<?php
/**
 * E-Library — Kelola entri (admin).
 * Render oleh E_library::manage().
 */
$el_has_filter = ($filters['q'] !== '' || $filters['source_id'] > 0 || $filters['tahun'] > 0 || $filters['subject'] !== '' || $filters['jenis'] !== '');
?>
<link rel="stylesheet" href="<?= base_url('assets/css/elibrary.css'); ?>">

<div class="el-page">

    <div class="el-adminbar">
        <h1 class="el-adminbar__title"><i class="fas fa-layer-group"></i> Kelola Katalog E-Library</h1>
        <div class="el-adminbar__actions">
            <a href="<?= site_url('e_library/import'); ?>" class="el-btn el-btn--soft el-btn--sm">
                <i class="fas fa-file-import"></i> Impor CSV
            </a>
            <a href="<?= site_url('e_library/source'); ?>" class="el-btn el-btn--ghost el-btn--sm">
                <i class="fas fa-bookmark"></i> Sumber
            </a>
            <a href="<?= site_url('e_library/subject'); ?>" class="el-btn el-btn--ghost el-btn--sm">
                <i class="fas fa-tags"></i> Subjek
            </a>
            <a href="<?= site_url('e_library/access_log'); ?>" class="el-btn el-btn--ghost el-btn--sm">
                <i class="fas fa-chart-line"></i> Log Akses
            </a>
            <a href="<?= site_url('e_library/create'); ?>" class="el-btn el-btn--primary el-btn--sm">
                <i class="fas fa-plus"></i> Entri Baru
            </a>
        </div>
    </div>

    <nav class="el-tabs">
        <a href="<?= site_url('e_library'); ?>"><i class="fas fa-book-open"></i> Katalog Publik</a>
        <a class="is-active" href="<?= site_url('e_library/manage'); ?>"><i class="fas fa-layer-group"></i> Kelola Entri</a>
        <a href="<?= site_url('e_library/source'); ?>"><i class="fas fa-bookmark"></i> Sumber</a>
        <a href="<?= site_url('e_library/subject'); ?>"><i class="fas fa-tags"></i> Subjek</a>
        <a href="<?= site_url('e_library/import'); ?>"><i class="fas fa-file-import"></i> Impor CSV</a>
        <a href="<?= site_url('e_library/access_log'); ?>"><i class="fas fa-chart-line"></i> Log Akses</a>
    </nav>

    <?php
        $flash = $this->session->flashdata('message');
        if ($flash <> '') {
            echo $flash;
        }
    ?>

    <div class="row">
        <div class="col-xl-3 col-lg-4 mb-4">
            <div class="el-panel">
                <div class="el-panel__head">
                    <h2 class="el-panel__title"><i class="fas fa-filter"></i> Filter</h2>
                </div>
                <div class="el-panel__body">
                    <form action="<?= site_url('e_library/manage'); ?>" method="get" autocomplete="off">
                        <div class="el-field">
                            <label class="el-label" for="el_mq">Kata Kunci</label>
                            <input type="text" id="el_mq" name="q" class="el-input"
                                   placeholder="Judul, penulis, DOI…" value="<?= html_escape($filters['q']); ?>">
                        </div>
                        <div class="el-field">
                            <label class="el-label" for="el_msumber">Sumber</label>
                            <select id="el_msumber" name="sumber" class="el-select">
                                <option value="">Semua sumber</option>
                                <?php foreach ($sources as $src): ?>
                                    <option value="<?= (int) $src->id; ?>" <?= ((int) $filters['source_id'] === (int) $src->id) ? 'selected' : ''; ?>>
                                        <?= html_escape($src->nama); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="el-field">
                            <label class="el-label" for="el_mtahun">Tahun</label>
                            <select id="el_mtahun" name="tahun" class="el-select">
                                <option value="">Semua tahun</option>
                                <?php foreach ($years as $th): ?>
                                    <option value="<?= (int) $th; ?>" <?= ((int) $filters['tahun'] === (int) $th) ? 'selected' : ''; ?>>
                                        <?= (int) $th; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="el-field">
                            <label class="el-label" for="el_msubjek">Subjek</label>
                            <select id="el_msubjek" name="subjek" class="el-select">
                                <option value="">Semua subjek</option>
                                <?php foreach ($subjects as $sub): ?>
                                    <option value="<?= html_escape($sub->slug); ?>" <?= ($filters['subject'] === $sub->slug) ? 'selected' : ''; ?>>
                                        <?= html_escape($sub->nama); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="el-btn el-btn--primary el-btn--block">
                            <i class="fas fa-search"></i> Terapkan
                        </button>
                        <a href="<?= site_url('e_library/manage'); ?>" class="el-btn el-btn--ghost el-btn--block mt-2">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8">
            <div class="el-resultbar">
                <div>
                    <strong style="color:var(--el-ink);"><?= number_format($total_rows, 0, ',', '.'); ?></strong>
                    entri dalam katalog
                    <?php if ($el_has_filter): ?>
                        &middot; halaman <?= (int) $page; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (empty($entries)): ?>
                <div class="el-panel">
                    <div class="el-empty">
                        <i class="fas fa-inbox"></i>
                        <div class="el-empty__title">Belum ada entri</div>
                        <div>Tambahkan manual atau impor dari berkas CSV.</div>
                        <div class="mt-3">
                            <a href="<?= site_url('e_library/create'); ?>" class="el-btn el-btn--primary el-btn--sm">
                                <i class="fas fa-plus"></i> Tambah Entri
                            </a>
                            <a href="<?= site_url('e_library/import'); ?>" class="el-btn el-btn--soft el-btn--sm">
                                <i class="fas fa-file-import"></i> Impor CSV
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="el-panel">
                    <div class="el-table-wrap">
                        <table class="el-table">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Sumber</th>
                                    <th>Tahun</th>
                                    <th>Jenis</th>
                                    <th style="text-align:right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($entries as $row): ?>
                                    <tr>
                                        <td>
                                            <span class="el-table__title">
                                                <a href="<?= site_url('e_library/detail/' . (int) $row->id); ?>">
                                                    <?= html_escape(elibrary_excerpt($row->judul, 90)); ?>
                                                </a>
                                            </span>
                                            <span class="el-table__meta">
                                                <?= html_escape(elibrary_excerpt($row->penulis, 50)); ?>
                                                <?php if (!empty($row->doi)): ?>
                                                    &middot; DOI: <?= html_escape($row->doi); ?>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td><?= html_escape(elibrary_excerpt(elibrary_nama_source($row), 32)); ?></td>
                                        <td><?= $row->tahun ? (int) $row->tahun : '-'; ?></td>
                                        <td><span class="el-badge"><?= html_escape(elibrary_jenis_label($row->jenis)); ?></span></td>
                                        <td>
                                            <div class="el-table__actions" style="justify-content:flex-end;">
                                                <a href="<?= site_url('e_library/update/' . (int) $row->id); ?>" class="el-btn el-btn--ghost el-btn--sm">
                                                    <i class="fas fa-pen"></i> Ubah
                                                </a>
                                                <form action="<?= site_url('e_library/delete_action/' . (int) $row->id); ?>" method="post"
                                                      onsubmit="return confirm('Hapus entri ini beserta log aksesnya?');" style="display:inline;">
                                                    <?= elibrary_csrf_field(); ?>
                                                    <button type="submit" class="el-btn el-btn--danger el-btn--sm">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($pagination)): ?>
                <nav class="el-pagination">
                    <?php echo $pagination; ?>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
