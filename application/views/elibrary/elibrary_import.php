<?php
/**
 * E-Library — Impor CSV (admin).
 * Render oleh E_library::import() & E_library::import_action().
 *
 * Variabel:
 *  - $report : NULL (belum impor) atau array(
 *       'total' => int,
 *       'ok'    => array(array('baris','judul','id')),
 *       'error' => array(array('baris','judul','pesan')),
 *     )
 */
$report = isset($report) ? $report : NULL;
?>
<link rel="stylesheet" href="<?= base_url('assets/css/elibrary.css'); ?>">

<div class="el-page">

    <div class="el-adminbar">
        <h1 class="el-adminbar__title"><i class="fas fa-file-import"></i> Impor Literatur dari CSV</h1>
        <div class="el-adminbar__actions">
            <a href="<?= site_url('e_library/template_csv'); ?>" class="el-btn el-btn--soft el-btn--sm">
                <i class="fas fa-download"></i> Unduh Template CSV
            </a>
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
        <a class="is-active" href="<?= site_url('e_library/import'); ?>"><i class="fas fa-file-import"></i> Impor CSV</a>
        <a href="<?= site_url('e_library/access_log'); ?>"><i class="fas fa-chart-line"></i> Log Akses</a>
    </nav>

    <?php
        $flash = $this->session->flashdata('message');
        if ($flash <> '') {
            echo $flash;
        }
    ?>

    <div class="row">
        <div class="col-xl-5 col-lg-5 mb-3">
            <div class="el-panel">
                <div class="el-panel__head">
                    <h2 class="el-panel__title"><i class="fas fa-upload"></i> Unggah Berkas</h2>
                </div>
                <div class="el-panel__body">
                    <form action="<?= site_url('e_library/import_action'); ?>" method="post"
                          enctype="multipart/form-data" autocomplete="off">
                        <?= elibrary_csrf_field(); ?>

                        <div class="el-field">
                            <label class="el-label" for="el_csv">Berkas CSV</label>
                            <input type="file" id="el_csv" name="berkas" class="el-input" accept=".csv,text/csv" required>
                            <div class="el-table__meta">Maksimal 4 MB. Format: CSV dengan baris header.</div>
                        </div>

                        <button type="submit" class="el-btn el-btn--primary el-btn--block">
                            <i class="fas fa-file-import"></i> Proses Impor
                        </button>
                    </form>

                    <div class="el-note mt-3">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            Setiap baris divalidasi satu per satu. Baris bermasalah
                            <strong>tidak membatalkan impor</strong> &mdash; hanya baris itu
                            yang dilewati, dan rinciannya tampil di laporan.
                        </div>
                    </div>

                    <div class="el-note mt-2" style="background:var(--el-gold-soft);color:var(--el-gold);">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            Sumber dan subjek yang belum terdaftar akan
                            <strong>dibuat otomatis</strong> dari kolom
                            <code>sumber</code> dan <code>subjek</code>.
                        </div>
                    </div>
                </div>
            </div>

            <div class="el-panel mt-3">
                <div class="el-panel__head">
                    <h2 class="el-panel__title"><i class="fas fa-list-ol"></i> Format Kolom</h2>
                </div>
                <div class="el-panel__body">
                    <table class="el-table">
                        <thead>
                            <tr><th>Kolom</th><th>Wajib</th><th>Keterangan</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>judul</td><td>Ya</td><td>Judul literatur (maks 500 karakter)</td></tr>
                            <tr><td>url_asli</td><td>Ya</td><td>Tautan sumber, harus http/https</td></tr>
                            <tr><td>penulis</td><td>Tidak</td><td>Pisahkan dengan titik koma</td></tr>
                            <tr><td>abstrak</td><td>Tidak</td><td>Berguna untuk pencarian</td></tr>
                            <tr><td>tahun</td><td>Tidak</td><td>4 digit (1900&ndash;2099)</td></tr>
                            <tr><td>nama_jurnal</td><td>Tidak</td><td>Judul jurnal/seri</td></tr>
                            <tr><td>doi</td><td>Tidak</td><td>Harus unik dalam katalog</td></tr>
                            <tr><td>kata_kunci</td><td>Tidak</td><td>Pisahkan dengan titik koma</td></tr>
                            <tr><td>volume</td><td>Tidak</td><td>Volume terbitan</td></tr>
                            <tr><td>nomor</td><td>Tidak</td><td>Nomor terbitan</td></tr>
                            <tr><td>halaman</td><td>Tidak</td><td>Contoh: 45-52</td></tr>
                            <tr><td>jenis</td><td>Tidak</td><td>artikel, buku, tesis, laporan, conference, lainnya</td></tr>
                            <tr><td>bahasa</td><td>Tidak</td><td>Contoh: Indonesia</td></tr>
                            <tr><td>sumber</td><td>Tidak</td><td>Dibuat otomatis bila belum ada</td></tr>
                            <tr><td>subjek</td><td>Tidak</td><td>Pisahkan dengan koma atau titik koma</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-xl-7 col-lg-7">
            <?php if ($report === NULL): ?>
                <div class="el-panel">
                    <div class="el-empty">
                        <i class="fas fa-file-csv"></i>
                        <div class="el-empty__title">Belum ada laporan impor</div>
                        <div>Unggah berkas CSV untuk melihat ringkasan baris tersimpan dan baris bermasalah.</div>
                    </div>
                </div>
            <?php else: ?>
                <div class="el-report">
                    <div class="el-report__head el-report__head--ok">
                        <i class="fas fa-check-circle"></i>
                        <?= count($report['ok']); ?> baris berhasil disimpan
                    </div>
                </div>

                <div class="el-report">
                    <div class="el-report__head el-report__head--err">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= count($report['error']); ?> baris bermasalah (dilewati)
                    </div>
                    <?php if (!empty($report['error'])): ?>
                        <div class="el-table-wrap">
                            <table class="el-table">
                                <thead>
                                    <tr>
                                        <th>Baris</th>
                                        <th>Judul</th>
                                        <th>Pesan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($report['error'] as $err): ?>
                                        <tr>
                                            <td><?= (int) $err['baris']; ?></td>
                                            <td><span class="el-table__title"><?= html_escape($err['judul']); ?></span></td>
                                            <td><span style="color:var(--el-danger);font-size:.82rem;"><?= html_escape($err['pesan']); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($report['ok'])): ?>
                    <div class="el-panel">
                        <div class="el-panel__head">
                            <h2 class="el-panel__title"><i class="fas fa-list-check"></i> Baris Tersimpan</h2>
                            <span class="el-badge"><?= count($report['ok']); ?></span>
                        </div>
                        <div class="el-table-wrap">
                            <table class="el-table">
                                <thead>
                                    <tr>
                                        <th>Baris</th>
                                        <th>Judul</th>
                                        <th style="text-align:right;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($report['ok'] as $row): ?>
                                        <tr>
                                            <td><?= (int) $row['baris']; ?></td>
                                            <td><span class="el-table__title"><?= html_escape($row['judul']); ?></span></td>
                                            <td>
                                                <div class="el-table__actions" style="justify-content:flex-end;">
                                                    <a href="<?= site_url('e_library/detail/' . (int) $row['id']); ?>" class="el-btn el-btn--ghost el-btn--sm">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="<?= site_url('e_library/manage'); ?>" class="el-btn el-btn--primary">
                        <i class="fas fa-layer-group"></i> Lihat Semua Entri
                    </a>
                    <a href="<?= site_url('e_library/import'); ?>" class="el-btn el-btn--ghost">
                        <i class="fas fa-redo"></i> Impor Lagi
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
