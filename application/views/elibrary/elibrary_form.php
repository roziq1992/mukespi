<?php
/**
 * E-Library — Form entri (admin).
 * Render oleh E_library::create() & E_library::update().
 *
 * Variabel:
 *  - $entry            : object entri (NULL saat membuat baru)
 *  - $subjects         : daftar subjek
 *  - $sources          : daftar sumber
 *  - $selected_subjects: array id subjek yang terpasang
 *  - $action           : URL form
 */
$entry = isset($entry) ? $entry : NULL;
$selected_subjects = isset($selected_subjects) ? array_map('intval', (array) $selected_subjects) : array();

$val = function ($field, $default = '') use ($entry) {
    if ($entry && isset($entry->$field)) {
        return $entry->$field;
    }
    return $default;
};
?>
<link rel="stylesheet" href="<?= base_url('assets/css/elibrary.css'); ?>">

<div class="el-page">

    <div class="el-adminbar">
        <h1 class="el-adminbar__title">
            <i class="fas fa-<?= $entry ? 'pen' : 'plus'; ?>"></i>
            <?= $entry ? 'Ubah Entri Literatur' : 'Tambah Entri Literatur'; ?>
        </h1>
        <div class="el-adminbar__actions">
            <a href="<?= site_url('e_library/manage'); ?>" class="el-btn el-btn--ghost el-btn--sm">
                <i class="fas fa-arrow-left"></i> Kembali
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

    <form action="<?= $action; ?>" method="post" autocomplete="off">
        <?= elibrary_csrf_field(); ?>

        <div class="row">
            <div class="col-xl-8 col-lg-8">
                <div class="el-panel mb-3">
                    <div class="el-panel__head">
                        <h2 class="el-panel__title"><i class="fas fa-book"></i> Informasi Dasar</h2>
                    </div>
                    <div class="el-panel__body">
                        <div class="el-field">
                            <label class="el-label" for="el_judul">Judul <span style="color:var(--el-danger);">*</span></label>
                            <input type="text" id="el_judul" name="judul" class="el-input" maxlength="500"
                                   value="<?= html_escape($val('judul')); ?>"
                                   placeholder="Judul lengkap artikel/buku/laporan">
                            <?= form_error('judul', '<div class="el-table__meta" style="color:var(--el-danger);">', '</div>'); ?>
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_penulis">Penulis</label>
                            <input type="text" id="el_penulis" name="penulis" class="el-input" maxlength="500"
                                   value="<?= html_escape($val('penulis')); ?>"
                                   placeholder="Nama penulis (pisahkan dengan tanda titik koma)">
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_abstrak">Abstrak / Ringkasan</label>
                            <textarea id="el_abstrak" name="abstrak" class="el-input" rows="6"
                                      placeholder="Ringkasan singkat untuk membantu Defined-keyword pencarian"><?= html_escape($val('abstrak')); ?></textarea>
                        </div>

                        <div class="el-grid">
                            <div class="el-field">
                                <label class="el-label" for="el_tahun">Tahun Terbit</label>
                                <input type="text" id="el_tahun" name="tahun" class="el-input" maxlength="4"
                                       value="<?= html_escape($val('tahun')); ?>" placeholder="2024">
                                <?= form_error('tahun', '<div class="el-table__meta" style="color:var(--el-danger);">', '</div>'); ?>
                            </div>
                            <div class="el-field">
                                <label class="el-label" for="el_jenis">Jenis</label>
                                <select id="el_jenis" name="jenis" class="el-select">
                                    <?php
                                    $jenis_now = $val('jenis', 'artikel');
                                    $jenis_ops = array(
                                        'artikel' => 'Artikel Jurnal',
                                        'buku' => 'Buku',
                                        'tesis' => 'Tesis',
                                        'laporan' => 'Laporan',
                                        'conference' => 'Konferensi',
                                        'lainnya' => 'Lainnya',
                                    );
                                    foreach ($jenis_ops as $k => $v): ?>
                                        <option value="<?= $k; ?>" <?= ($jenis_now === $k) ? 'selected' : ''; ?>><?= $v; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="el-field">
                                <label class="el-label" for="el_volume">Volume</label>
                                <input type="text" id="el_volume" name="volume" class="el-input" maxlength="50"
                                       value="<?= html_escape($val('volume')); ?>">
                            </div>
                            <div class="el-field">
                                <label class="el-label" for="el_nomor">Nomor</label>
                                <input type="text" id="el_nomor" name="nomor" class="el-input" maxlength="50"
                                       value="<?= html_escape($val('nomor')); ?>">
                            </div>
                            <div class="el-field">
                                <label class="el-label" for="el_halaman">Halaman</label>
                                <input type="text" id="el_halaman" name="halaman" class="el-input" maxlength="50"
                                       value="<?= html_escape($val('halaman')); ?>" placeholder="45-52">
                            </div>
                            <div class="el-field">
                                <label class="el-label" for="el_bahasa">Bahasa</label>
                                <input type="text" id="el_bahasa" name="bahasa" class="el-input" maxlength="50"
                                       value="<?= html_escape($val('bahasa')); ?>" placeholder="Indonesia">
                            </div>
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_kunci">Kata Kunci</label>
                            <input type="text" id="el_kunci" name="kata_kunci" class="el-input" maxlength="500"
                                   value="<?= html_escape($val('kata_kunci')); ?>"
                                   placeholder="Pisahkan dengan titik koma, mis. mutu; keselamatan pasien">
                        </div>
                    </div>
                </div>

                <div class="el-panel mb-3">
                    <div class="el-panel__head">
                        <h2 class="el-panel__title"><i class="fas fa-link"></i> Sumber &amp; Tautan</h2>
                    </div>
                    <div class="el-panel__body">
                        <div class="el-field">
                            <label class="el-label" for="el_url">URL Sumber Asli <span style="color:var(--el-danger);">*</span></label>
                            <input type="url" id="el_url" name="url_asli" class="el-input" maxlength="1000"
                                   value="<?= html_escape($val('url_asli')); ?>"
                                   placeholder="https://jurnal.example.org/artikel/1">
                            <?= form_error('url_asli', '<div class="el-table__meta" style="color:var(--el-danger);">', '</div>'); ?>
                            <div class="el-table__meta">Tautan tujuan ketika pengguna menekan tombol &ldquo;Buka di Sumber&rdquo;.</div>
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_doi">DOI</label>
                            <input type="text" id="el_doi" name="doi" class="el-input" maxlength="255"
                                   value="<?= html_escape($val('doi')); ?>" placeholder="10.1234/contoh">
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_nama_jurnal">Nama Jurnal / Seri</label>
                            <input type="text" id="el_nama_jurnal" name="nama_jurnal" class="el-input" maxlength="255"
                                   value="<?= html_escape($val('nama_jurnal')); ?>">
                        </div>

                        <div class="el-field">
                            <label class="el-label" for="el_source">Sumber Katalog</label>
                            <select id="el_source" name="source_id" class="el-select">
                                <option value="0">— Tanpa sumber —</option>
                                <?php foreach ($sources as $src): ?>
                                    <option value="<?= (int) $src->id; ?>" <?= ((int) $val('source_id') === (int) $src->id) ? 'selected' : ''; ?>>
                                        <?= html_escape($src->nama); ?><?= $src->aktif ? '' : ' (nonaktif)'; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="el-table__meta">
                                <a href="<?= site_url('e_library/source'); ?>">Kelola daftar sumber &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4">
                <div class="el-panel mb-3">
                    <div class="el-panel__head">
                        <h2 class="el-panel__title"><i class="fas fa-tags"></i> Subjek</h2>
                    </div>
                    <div class="el-panel__body">
                        <div class="el-checklist">
                            <?php if (empty($subjects)): ?>
                                <div class="el-table__meta">Belum ada subjek. Tambahkan lewat menu Subjek.</div>
                            <?php else: ?>
                                <?php foreach ($subjects as $sub): ?>
                                    <label>
                                        <input type="checkbox" name="subject_ids[]" value="<?= (int) $sub->id; ?>"
                                            <?= in_array((int) $sub->id, $selected_subjects, TRUE) ? 'checked' : ''; ?>>
                                        <?= html_escape($sub->nama); ?>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="el-table__meta mt-2">
                            <a href="<?= site_url('e_library/subject'); ?>">Kelola subjek &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="el-panel mb-3">
                    <div class="el-panel__head">
                        <h2 class="el-panel__title"><i class="fas fa-calendar-check"></i> Metadata Katalog</h2>
                    </div>
                    <div class="el-panel__body">
                        <div class="el-field">
                            <label class="el-label" for="el_tgl">Tanggal Masuk Katalog</label>
                            <input type="date" id="el_tgl" name="tanggal_masuk" class="el-input"
                                   value="<?= html_escape($val('tanggal_masuk', date('Y-m-d'))); ?>">
                            <?= form_error('tanggal_masuk', '<div class="el-table__meta" style="color:var(--el-danger);">', '</div>'); ?>
                        </div>
                        <div class="el-field">
                            <label class="el-label" for="el_sitasi">Jumlah Sitasi</label>
                            <input type="number" id="el_sitasi" name="sitasi" class="el-input" min="0"
                                   value="<?= (int) $val('sitasi', 0); ?>">
                            <?= form_error('sitasi', '<div class="el-table__meta" style="color:var(--el-danger);">', '</div>'); ?>
                        </div>
                    </div>
                </div>

                <div class="el-panel">
                    <div class="el-panel__body">
                        <button type="submit" class="el-btn el-btn--primary el-btn--block">
                            <i class="fas fa-save"></i> <?= $entry ? 'Simpan Perubahan' : 'Simpan Entri'; ?>
                        </button>
                        <a href="<?= $cancel_url; ?>" class="el-btn el-btn--ghost el-btn--block mt-2">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
