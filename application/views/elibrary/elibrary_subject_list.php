<?php
/**
 * E-Library — Daftar subjek (admin).
 * Render oleh E_library::subject().
 */
?>
<link rel="stylesheet" href="<?= base_url('assets/css/elibrary.css'); ?>">

<div class="el-page">

    <div class="el-adminbar">
        <h1 class="el-adminbar__title"><i class="fas fa-tags"></i> Subjek Literatur</h1>
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
        <a class="is-active" href="<?= site_url('e_library/subject'); ?>"><i class="fas fa-tags"></i> Subjek</a>
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
        <div class="col-xl-4 col-lg-4 mb-3">
            <div class="el-panel">
                <div class="el-panel__head">
                    <h2 class="el-panel__title"><i class="fas fa-plus-circle"></i> Tambah / Ubah Subjek</h2>
                </div>
                <div class="el-panel__body">
                    <form action="<?= site_url('e_library/subject_action'); ?>" method="post" autocomplete="off">
                        <?= elibrary_csrf_field(); ?>
                        <input type="hidden" name="id" id="el_subid" value="">
                        <div class="el-field">
                            <label class="el-label" for="el_subnama">Nama Subjek</label>
                            <input type="text" id="el_subnama" name="nama" class="el-input" maxlength="255"
                                   placeholder="Manajemen Mutu Kesehatan">
                        </div>
                        <button type="submit" class="el-btn el-btn--primary el-btn--block">
                            <i class="fas fa-save"></i> Simpan Subjek
                        </button>
                        <div class="el-table__meta mt-2">
                            Subjek dengan nama sama tidak akan dibuat dua kali.
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-8">
            <div class="el-panel">
                <div class="el-table-wrap">
                    <table class="el-table">
                        <thead>
                            <tr>
                                <th>Subjek</th>
                                <th>Slug</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($subjects)): ?>
                                <tr>
                                    <td colspan="3">
                                        <div class="el-empty">
                                            <i class="fas fa-tags"></i>
                                            <div class="el-empty__title">Belum ada subjek</div>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($subjects as $sub): ?>
                                    <tr>
                                        <td><span class="el-table__title"><?= html_escape($sub->nama); ?></span></td>
                                        <td><span class="el-table__meta"><?= html_escape($sub->slug); ?></span></td>
                                        <td>
                                            <div class="el-table__actions" style="justify-content:flex-end;">
                                                <button type="button" class="el-btn el-btn--ghost el-btn--sm el-edit-subject"
                                                        data-id="<?= (int) $sub->id; ?>" data-nama="<?= html_escape($sub->nama); ?>">
                                                    <i class="fas fa-pen"></i> Ubah
                                                </button>
                                                <form action="<?= site_url('e_library/subject_delete_action/' . (int) $sub->id); ?>" method="post"
                                                      onsubmit="return confirm('Hapus subjek ini? Entri yang memakainya tetap tersimpan.');" style="display:inline;">
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
    </div>
</div>

<script>
(function () {
    var form = document.getElementById('el_subnama');
    var idField = document.getElementById('el_subid');

    document.querySelectorAll('.el-edit-subject').forEach(function (btn) {
        btn.addEventListener('click', function () {
            form.value = btn.getAttribute('data-nama') || '';
            idField.value = btn.getAttribute('data-id') || '';
            form.focus();
        });
    });
})();
</script>
