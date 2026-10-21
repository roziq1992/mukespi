<?php
/**
 * E-Library — Form sumber (admin).
 * Render oleh E_library::source_form().
 */
$source = isset($source) ? $source : NULL;
$sv = function ($field, $default = '') use ($source) {
    if ($source && isset($source->$field) && $source->$field !== NULL) {
        return $source->$field;
    }
    return $default;
};
?>
<link rel="stylesheet" href="<?= base_url('assets/css/elibrary.css'); ?>">

<div class="el-page">

    <div class="el-adminbar">
        <h1 class="el-adminbar__title">
            <i class="fas fa-<?= $source ? 'pen' : 'plus'; ?>"></i>
            <?= $source ? 'Ubah Sumber' : 'Tambah Sumber'; ?>
        </h1>
        <div class="el-adminbar__actions">
            <a href="<?= site_url('e_library/source'); ?>" class="el-btn el-btn--ghost el-btn--sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <?php
        $flash = $this->session->flashdata('message');
        if ($flash <> '') {
            echo $flash;
        }
    ?>

    <div class="row">
        <div class="col-xl-8 col-lg-8">
            <div class="el-panel">
                <div class="el-panel__head">
                    <h2 class="el-panel__title"><i class="fas fa-bookmark"></i> Identitas Sumber</h2>
                </div>
                <div class="el-panel__body">
                    <form action="<?= $action; ?>" method="post" autocomplete="off">
                        <?= elibrary_csrf_field(); ?>

                        <div class="el-field">
                            <label class="el-label" for="el_nama">Nama Sumber <span style="color:var(--el-danger);">*</span></label>
                            <input type="text" id="el_nama" name="nama" class="el-input" maxlength="255"
                                   value="<?= html_escape($sv('nama')); ?>"
                                   placeholder="Jurnal Manajemen Mutu Kesehatan">
                            <?= form_error('nama', '<div class="el-table__meta" style="color:var(--el-danger);">', '</div>'); ?>
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_penerbit">Penerbit / Institusi</label>
                            <input type="text" id="el_penerbit" name="penerbit" class="el-input" maxlength="255"
                                   value="<?= html_escape($sv('penerbit')); ?>">
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_deskripsi">Deskripsi</label>
                            <textarea id="el_deskripsi" name="deskripsi" class="el-input" rows="4"
                                      placeholder="Keterangan singkat tentang cakupan sumber"><?= html_escape($sv('deskripsi')); ?></textarea>
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_home">Halaman Utama</label>
                            <input type="url" id="el_home" name="homepage_url" class="el-input" maxlength="500"
                                   value="<?= html_escape($sv('homepage_url')); ?>" placeholder="https://jurnal.example.org">
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_oai">URL OAI-PMH</label>
                            <input type="url" id="el_oai" name="oai_pmh_url" class="el-input" maxlength="500"
                                   value="<?= html_escape($sv('oai_pmh_url')); ?>" placeholder="https://jurnal.example.org/oai">
                            <div class="el-table__meta">Opsional. Dipakai harvester otomatis pada fase berikutnya.</div>
                        </div>

                        <div class="el-field">
                            <label class="el-label" style="display:flex;align-items:center;gap:8px;text-transform:none;letter-spacing:0;">
                                <input type="checkbox" name="aktif" value="1" <?= ($sv('aktif', 1) ? 'checked' : ''); ?>>
                                Aktif (dapat dipilih saat menambah entri)
                            </label>
                        </div>

                        <button type="submit" class="el-btn el-btn--primary">
                            <i class="fas fa-save"></i> <?= $source ? 'Simpan Perubahan' : 'Simpan Sumber'; ?>
                        </button>
                        <a href="<?= $cancel_url; ?>" class="el-btn el-btn--ghost">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
