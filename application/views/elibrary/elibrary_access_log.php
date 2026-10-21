<?php
/**
 * E-Library — Log akses (admin).
 * Render oleh E_library::access_log().
 */
$aksi_label = array(
    'view' => 'Dilihat',
    'buka_sumber' => 'Buka di Sumber',
    'search' => 'Pencarian',
);
?>
<link rel="stylesheet" href="<?= base_url('assets/css/elibrary.css'); ?>">

<div class="el-page">

    <div class="el-adminbar">
        <h1 class="el-adminbar__title"><i class="fas fa-chart-line"></i> Log Akses Literatur</h1>
        <div class="el-adminbar__actions">
            <a href="<?= site_url('e_library/manage'); ?>" class="el-btn el-btn--ghost el-btn--sm">
                <i class="fas fa-arrow-left"></i> Kelola Entri
            </a>
        </div>
    </div>

    <nav class="el-tabs">
        <a href="<?= site_url('e_library'); ?>"><i class="fas fa-book-open"></i> Katalog Publik</a>
        <a href="<?= site_url('e_library/manage'); ?>"><i class="fas fa-layer-group"></i> Kelola Entri</a>
        <a href="<?= site_url('e_library/source'); ?>"><i class="fas fa-bookmark"></i> Sumber</a>
        <a href="<?= site_url('e_library/subject'); ?>"><i class="fas fa-tags"></i> Subjek</a>
        <a href="<?= site_url('e_library/import'); ?>"><i class="fas fa-file-import"></i> Impor CSV</a>
        <a class="is-active" href="<?= site_url('e_library/access_log'); ?>"><i class="fas fa-chart-line"></i> Log Akses</a>
    </nav>

    <?php
        $flash = $this->session->flashdata('message');
        if ($flash <> '') {
            echo $flash;
        }
    ?>

    <div class="row">
        <div class="col-xl-9 col-lg-8">
            <div class="el-panel mb-3">
                <div class="el-panel__head">
                    <h2 class="el-panel__title"><i class="fas fa-filter"></i> Filter</h2>
                    <span class="el-badge"><?= number_format($total_rows, 0, ',', '.'); ?> catatan</span>
                </div>
                <div class="el-panel__body">
                    <form action="<?= site_url('e_library/access_log'); ?>" method="get" autocomplete="off">
                        <div class="el-search">
                            <input type="text" name="q" class="el-input" placeholder="Judul, nama, atau email pengguna"
                                   value="<?= html_escape($filters['q']); ?>">
                            <select name="aksi" class="el-select" style="max-width:220px;">
                                <option value="">Semua aksi</option>
                                <option value="view" <?= ($filters['aksi'] === 'view') ? 'selected' : ''; ?>>Dilihat</option>
                                <option value="buka_sumber" <?= ($filters['aksi'] === 'buka_sumber') ? 'selected' : ''; ?>>Buka di Sumber</option>
                            </select>
                            <button type="submit" class="el-btn el-btn--primary"><i class="fas fa-search"></i> Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="el-panel">
                <div class="el-table-wrap">
                    <table class="el-table">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Pengguna</th>
                                <th>Aksi</th>
                                <th>Entri</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="el-empty">
                                            <i class="fas fa-history"></i>
                                            <div class="el-empty__title">Belum ada catatan akses</div>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><span class="el-table__meta"><?= html_escape(elibrary_tanggal($log->created_at)); ?></span></td>
                                        <td>
                                            <span class="el-table__title"><?= html_escape($log->nama_user !== NULL && $log->nama_user !== '' ? $log->nama_user : 'Pengguna dihapus'); ?></span>
                                            <?php if (!empty($log->email_user)): ?>
                                                <span class="el-table__meta"><?= html_escape($log->email_user); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="el-badge <?= ($log->aksi === 'buka_sumber') ? 'el-badge--gold' : ''; ?>">
                                                <?= html_escape(isset($aksi_label[$log->aksi]) ? $aksi_label[$log->aksi] : $log->aksi); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($log->entry_id)): ?>
                                                <a href="<?= site_url('e_library/detail/' . (int) $log->entry_id); ?>">
                                                    <?= html_escape(elibrary_excerpt($log->judul, 60)); ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="el-table__meta">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (!empty($pagination)): ?>
                <nav class="el-pagination">
                    <?php echo $pagination; ?>
                </nav>
            <?php endif; ?>
        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="el-panel">
                <div class="el-panel__head">
                    <h2 class="el-panel__title"><i class="fas fa-fire"></i> Paling Banyak Diakses</h2>
                </div>
                <div class="el-panel__body">
                    <?php if (empty($top)): ?>
                        <div class="el-table__meta">Belum ada data.</div>
                    <?php else: ?>
                        <?php foreach ($top as $t): ?>
                            <div style="margin-bottom:12px;">
                                <div style="font-size:.83rem;font-weight:700;">
                                    <a href="<?= site_url('e_library/detail/' . (int) $t->id); ?>">
                                        <?= html_escape(elibrary_excerpt($t->judul, 70)); ?>
                                    </a>
                                </div>
                                <span class="el-chip el-chip--accent"><?= (int) $t->jumlah; ?> klik</span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
