<?php
/**
 * E-Library — Daftar sumber (admin).
 * Render oleh E_library::source().
 */
?>
<link rel="stylesheet" href="<?= base_url('assets/css/elibrary.css'); ?>">

<div class="el-page">

    <div class="el-adminbar">
        <h1 class="el-adminbar__title"><i class="fas fa-bookmark"></i> Sumber Literatur</h1>
        <div class="el-adminbar__actions">
            <a href="<?= site_url('e_library/source_form'); ?>" class="el-btn el-btn--primary el-btn--sm">
                <i class="fas fa-plus"></i> Tambah Sumber
            </a>
        </div>
    </div>

    <nav class="el-tabs">
        <a href="<?= site_url('e_library'); ?>"><i class="fas fa-book-open"></i> Katalog Publik</a>
        <a href="<?= site_url('e_library/manage'); ?>"><i class="fas fa-layer-group"></i> Kelola Entri</a>
        <a class="is-active" href="<?= site_url('e_library/source'); ?>"><i class="fas fa-bookmark"></i> Sumber</a>
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

    <div class="el-panel">
        <div class="el-table-wrap">
            <table class="el-table">
                <thead>
                    <tr>
                        <th>Nama Sumber</th>
                        <th>Penerbit</th>
                        <th>OAI-PMH</th>
                        <th>Status</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sources)): ?>
                        <tr>
                            <td colspan="5">
                                <div class="el-empty">
                                    <i class="fas fa-bookmark"></i>
                                    <div class="el-empty__title">Belum ada sumber</div>
                                    <div>Tambahkan jurnal, repositori, atau basisdata yang menjadi rujukan.</div>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sources as $src): ?>
                            <tr>
                                <td>
                                    <span class="el-table__title"><?= html_escape($src->nama); ?></span>
                                    <?php if (!empty($src->homepage_url)): ?>
                                        <span class="el-table__meta">
                                            <a href="<?= html_escape($src->homepage_url); ?>" target="_blank" rel="noopener">
                                                <?= html_escape($src->homepage_url); ?>
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= html_escape($src->penerbit !== '' && $src->penerbit !== NULL ? $src->penerbit : '-'); ?></td>
                                <td>
                                    <?php if (!empty($src->oai_pmh_url)): ?>
                                        <a href="<?= html_escape($src->oai_pmh_url); ?>" target="_blank" rel="noopener" class="el-table__meta">
                                            <i class="fas fa-sync-alt"></i> endpoint
                                        </a>
                                    <?php else: ?>
                                        <span class="el-table__meta">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($src->aktif): ?>
                                        <span class="el-badge">Aktif</span>
                                    <?php else: ?>
                                        <span class="el-badge el-badge--gold">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="el-table__actions" style="justify-content:flex-end;">
                                        <a href="<?= site_url('e_library/source_form/' . (int) $src->id); ?>" class="el-btn el-btn--ghost el-btn--sm">
                                            <i class="fas fa-pen"></i> Ubah
                                        </a>
                                        <form action="<?= site_url('e_library/source_delete_action/' . (int) $src->id); ?>" method="post"
                                              onsubmit="return confirm('Hapus sumber ini? Entri yang memakainya tetap tersimpan tanpa sumber.');" style="display:inline;">
                                            <?= elibrary_csrf_field(); ?>
                                            <button type="submit" class="el-btn el-btn--danger el-btn--sm">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
