<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">
<style>
    .du-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .du-card .card-header-custom {
        background: linear-gradient(135deg, #2c5f8a 0%, #1b3a5c 100%);
        color: #fff;
        padding: 22px 24px;
    }
    .du-card .card-header-custom h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
    }
    .du-card .card-header-custom p {
        margin: 4px 0 0;
        font-size: 0.85rem;
        opacity: 0.85;
    }
    .du-body {
        padding: 24px;
    }
    @media (max-width: 576px) {
        .du-body { padding: 16px; }
        .du-card .card-header-custom { padding: 16px; }
    }
    .du-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #33475b;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .du-label-btn {
        font-size: 0.72rem;
        font-weight: 700;
        border-radius: 20px;
        padding: 2px 10px;
        line-height: 1.6;
    }
    .du-section-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #8a94a6;
        font-weight: 700;
        margin: 22px 0 12px;
        border-bottom: 1px solid #eef0f3;
        padding-bottom: 6px;
    }
    .du-section-title:first-of-type { margin-top: 0; }
    .select2-container .select2-selection--single {
        height: 42px !important;
        border-radius: 8px !important;
        border: 1px solid #ced4da !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px !important;
        padding-left: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }
    .du-upload-box {
        border: 2px dashed #c7d2e0;
        border-radius: 10px;
        padding: 18px;
        text-align: center;
        background: #f8fafc;
        transition: border-color .15s ease;
        cursor: pointer;
        position: relative;
    }
    .du-upload-box:hover { border-color: #2c5f8a; }
    .du-upload-box input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }
    .du-upload-box .du-upload-icon {
        font-size: 1.8rem;
        margin-bottom: 6px;
        color: #2c5f8a;
    }
    .du-upload-box .du-upload-text {
        font-size: 0.9rem;
        color: #33475b;
        font-weight: 600;
    }
    .du-upload-box .du-upload-hint {
        font-size: 0.75rem;
        color: #8a94a6;
        margin-top: 4px;
    }
    .du-filename {
        margin-top: 10px;
        font-size: 0.85rem;
        padding: 8px 12px;
        background: #eaf3fb;
        border-radius: 6px;
        color: #1b3a5c;
        display: none;
    }
    .du-current-file {
        font-size: 0.8rem;
        margin-top: 8px;
        color: #556;
    }
    .du-current-file a {
        color: #2c5f8a;
        font-weight: 600;
    }
    .du-badges span {
        display: inline-block;
        background: #eef2f7;
        color: #556;
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 20px;
        margin: 2px 3px 0 0;
    }
    .du-actions {
        margin-top: 26px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .du-actions .btn {
        border-radius: 8px;
        padding: 10px 22px;
        font-weight: 600;
    }
</style>

<div class="container-fluid">
    <div class="du-card bg-white">
        <div class="card-header-custom">
            <h2>📄 <?php echo $button ?> Dokumen Unit</h2>
            <p>Upload dan kelola dokumen per unit rumah sakit</p>
        </div>

        <div class="du-body">
            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <?php
                $flash = $this->session->flashdata('message');
                if ($flash) {
                    echo '<div class="alert alert-warning">' . $flash . '</div>';
                }
            ?>

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="formDokumenUnit">

               <div class="du-section-title">Informasi Unit &amp; Jenis Dokumen</div>
<div class="row">

    <!-- Unit (tabel unit, tidak berubah) -->
    <div class="col-12 col-md-6">
        <label class="du-label"><span>Unit</span></label>
        <select class="form-control du-select2-ajax" id="id_unit" name="id_unit" style="width:100%"
                data-ajax-url="<?php echo site_url('dokumen_unit/select2_unit'); ?>"
                data-placeholder="Ketik untuk cari unit...">
            <option value="">-- Cari / Pilih Unit --</option>
            <?php if (!empty($unit2)): foreach ($unit2 as $u): ?>
                <option value="<?php echo $u->id_unit ?>"
                    <?php echo ($u->id_unit == $id_unit) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($u->nm_unit) ?>
                </option>
            <?php endforeach; endif; ?>
        </select>
        <?php echo form_error('id_unit') ?>
    </div>

    <!-- Unit Dokumen (tabel unit_dokumen) -->
    <div class="col-12 col-md-6 mt-3 mt-md-0">
        <label class="du-label"><span>Unit Dokumen</span></label>
        <select class="form-control" id="id_unit_doc_ref" name="id_unit_doc_ref" style="width:100%">
            <option value="">-- Pilih Unit Dokumen --</option>
            <?php if (!empty($unit_dok2)): foreach ($unit_dok2 as $u): ?>
                <option value="<?php echo $u->id_unit_doc ?>"
                    <?php echo ($u->id_unit_doc == $id_unit_doc_ref) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($u->nm_unit_doc) ?>
                </option>
            <?php endforeach; endif; ?>
        </select>
    </div>

    <!-- Jenis Dokumen (AJAX, difilter by unit_dokumen) -->
    <div class="col-12 col-md-12 mt-3">
        <label class="du-label">
            <span>Jenis Dokumen</span>
            <button type="button" class="btn btn-primary du-label-btn"
                    id="btnTambahJenisDokumen"
                    data-toggle="modal" data-target="#modalJenisDokumen">
                + Tambah Jenis
            </button>
        </label>
        <select class="form-control" id="id_jenis_dokumen" name="id_jenis_dokumen" style="width:100%"
                data-ajax-url="<?php echo site_url('dokumen_unit/select2_jenis_dokumen'); ?>"
                data-placeholder="Pilih Unit Dokumen dulu...">
            <option value="">-- Pilih Jenis Dokumen --</option>
            <?php if (!empty($jenis_dokumen_selected)): ?>
                <option value="<?php echo $jenis_dokumen_selected->id_jenis_dokumen ?>" selected>
                    <?php echo htmlspecialchars($jenis_dokumen_selected->nm_jenis_dokumen) ?>
                </option>
            <?php endif; ?>
        </select>
        <small class="text-muted" id="hintJenisDokumen">Pilih Unit Dokumen terlebih dahulu.</small>
        <?php echo form_error('id_jenis_dokumen') ?>
    </div>

</div>

                <div class="du-section-title">Detail Dokumen</div>
                <div class="row">
                    <div class="col-12">
                        <label class="du-label"><span>Judul Dokumen</span></label>
                        <input type="text" class="form-control" name="judul_dokumen" id="judul_dokumen"
                               placeholder="Contoh: SOP Penanganan Pasien IGD" value="<?php echo $judul_dokumen; ?>" />
                        <?php echo form_error('judul_dokumen') ?>
                    </div>
                    <div class="col-12 mt-3">
                        <label class="du-label"><span>Keterangan</span></label>
                        <textarea class="form-control" rows="3" name="keterangan" id="keterangan"
                                  placeholder="Catatan tambahan tentang dokumen ini (opsional)"><?php echo $keterangan; ?></textarea>
                    </div>
                    <div class="col-12 col-md-6 mt-3">
                        <label class="du-label"><span>Status</span></label>
                        <select class="form-control" id="status" name="status">
                            <option value="draft" <?php echo ($status == 'draft') ? 'selected' : '' ?>>Draft</option>
                            <option value="aktif" <?php echo ($status == 'aktif') ? 'selected' : '' ?>>Aktif</option>
                            <option value="arsip" <?php echo ($status == 'arsip') ? 'selected' : '' ?>>Arsip</option>
                            <option value="kadaluarsa" <?php echo ($status == 'kadaluarsa') ? 'selected' : '' ?>>Kadaluarsa</option>
                        </select>
                        <?php echo form_error('status') ?>
                    </div>
                    <div class="col-6 col-md-3 mt-3">
                        <label class="du-label"><span>Tgl Berlaku</span></label>
                        <input type="date" class="form-control" name="tgl_berlaku" id="tgl_berlaku" value="<?php echo $tgl_berlaku; ?>" />
                    </div>
                    <div class="col-6 col-md-3 mt-3">
                        <label class="du-label"><span>Tgl Kadaluarsa</span></label>
                        <input type="date" class="form-control" name="tgl_kadaluarsa" id="tgl_kadaluarsa" value="<?php echo $tgl_kadaluarsa; ?>" />
                    </div>
                </div>

                <div class="du-section-title">File Dokumen</div>
                <div class="row">
                    <div class="col-12">
                        <label class="du-upload-box" id="uploadBox">
                            <input type="file" name="file_dokumen" id="file_dokumen"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx" />
                            <div class="du-upload-icon">⬆️</div>
                            <div class="du-upload-text">Ketuk untuk pilih file / tarik file ke sini</div>
                            <div class="du-upload-hint">Format PDF, Word (.doc/.docx), Excel (.xls/.xlsx) — maksimal 5 MB</div>
                        </label>
                        <div class="du-filename" id="fileNamePreview"></div>

                        <?php if (!empty($nama_file)): ?>
                            <div class="du-current-file">
                                File saat ini:
                                <a href="<?php echo base_url($path_file); ?>" target="_blank"><?php echo $nama_file; ?></a>
                                — kosongkan pilihan di atas jika tidak ingin menggantinya.
                            </div>
                        <?php endif; ?>

                        <div class="du-badges mt-2">
                            <span>PDF</span><span>DOC</span><span>DOCX</span><span>XLS</span><span>XLSX</span><span>Maks 5MB</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="id_dokumen" value="<?php echo $id_dokumen; ?>" />

                <div class="du-actions">
                    <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                    <a href="<?php echo site_url('dokumen_unit') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODAL TAMBAH JENIS DOKUMEN ================= -->
<div class="modal fade" id="modalJenisDokumen" tabindex="-1" role="dialog" aria-labelledby="modalJenisDokumenLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background:#2c5f8a; color:#fff;">
                <h5 class="modal-title" id="modalJenisDokumenLabel">➕ Tambah Jenis Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="alertJenisDokumen" class="alert alert-danger" style="display:none;"></div>

                <!-- BARU: pilih Unit Dokumen langsung di modal -->
                <div class="form-group">
                    <label class="du-label"><span>Unit Dokumen</span></label>
                    <select class="form-control" id="id_unit_doc_ref_modal" style="width:100%">
                        <option value="">-- Pilih Unit Dokumen --</option>
                        <?php if (!empty($unit_dok2)): foreach ($unit_dok2 as $u): ?>
                            <option value="<?php echo $u->id_unit_doc ?>">
                                <?php echo htmlspecialchars($u->nm_unit_doc) ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                    <small class="text-muted">Jenis dokumen ini akan terikat ke Unit Dokumen yang dipilih.</small>
                </div>

                <div class="form-group mb-0">
                    <label class="du-label"><span>Nama Jenis Dokumen</span></label>
                    <input type="text" class="form-control" id="nm_jenis_dokumen_baru"
                           placeholder="Contoh: SOP, Kebijakan, Panduan, Instruksi Kerja" autocomplete="off" />
                    <small class="text-muted">Setelah disimpan, jenis dokumen ini langsung terpilih otomatis di form.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanJenisDokumen">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
// NOTE: sebelumnya ada 2 blok <script> duplikat di file ini (masing-masing
// init Select2 & bind modal sendiri-sendiri) sehingga tombol "Simpan" di
// modal terkirim AJAX-nya dobel. Sudah digabung jadi satu blok bersih.
window.addEventListener('load', function () {
    var $ = window.jQuery;
    if (!$) { console.error('jQuery tidak tersedia — pastikan jQuery sudah dimuat di footer.'); return; }

    if (!$.fn.select2) {
        var s2 = document.createElement('script');
        s2.src = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js';
        s2.onload = function () { initAll($); };
        document.head.appendChild(s2);
    } else {
        initAll($);
    }

    function initAll($) {

        var AJAX_URL_JENIS = $('#id_jenis_dokumen').data('ajax-url');
        var AJAX_URL_UNIT  = $('#id_unit').data('ajax-url');

        // ---------- Select2 AJAX: Unit ----------
        $('#id_unit').select2({
            placeholder: 'Ketik untuk cari unit...',
            allowClear: true,
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: AJAX_URL_UNIT,
                dataType: 'json',
                delay: 300,
                data: function (p) { return { q: p.term || '', page: p.page || 1 }; },
                processResults: function (d) {
                    return { results: d.items || [], pagination: { more: !!d.more } };
                },
                cache: true
            }
        });

        // ---------- Select2 biasa: Unit Dokumen (form utama) ----------
        $('#id_unit_doc_ref').select2({
            placeholder: '-- Pilih Unit Dokumen --',
            allowClear: true,
            width: '100%'
        });

        // ---------- Select2 biasa: Unit Dokumen (di dalam modal) ----------
        $('#id_unit_doc_ref_modal').select2({
            placeholder: '-- Pilih Unit Dokumen --',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#modalJenisDokumen')
        });

        // ---------- Init / reinit Select2 AJAX Jenis Dokumen ----------
        // Dipanggil ulang setiap Unit Dokumen (form utama) berubah agar
        // id_unit_doc_ref selalu fresh.
        function initJenisDokumenSelect2(id_unit_doc_ref) {
            var $sel    = $('#id_jenis_dokumen');
            var enabled = !!id_unit_doc_ref;

            if ($sel.hasClass('select2-hidden-accessible')) {
                $sel.select2('destroy');
            }

            $sel.prop('disabled', !enabled);
            $('#hintJenisDokumen').text(
                enabled ? 'Ketik untuk mencari jenis dokumen.' : 'Pilih Unit Dokumen terlebih dahulu.'
            );

            $sel.select2({
                placeholder : enabled ? 'Ketik untuk cari jenis dokumen...' : 'Pilih Unit Dokumen dulu...',
                allowClear  : true,
                width       : '100%',
                minimumInputLength: 0,
                ajax: !enabled ? undefined : {
                    url: AJAX_URL_JENIS,
                    dataType: 'json',
                    delay: 300,
                    data: function (p) {
                        return {
                            q               : p.term || '',
                            page            : p.page || 1,
                            id_unit_doc_ref : id_unit_doc_ref
                        };
                    },
                    processResults: function (d) {
                        return { results: d.items || [], pagination: { more: !!d.more } };
                    },
                    cache: false
                }
            });
        }

        // ---------- Event: Unit Dokumen (form utama) berubah ----------
        $('#id_unit_doc_ref').on('change', function () {
            var $jd = $('#id_jenis_dokumen');
            if ($jd.hasClass('select2-hidden-accessible')) {
                $jd.val(null).trigger('change');
            }
            initJenisDokumenSelect2($(this).val());
        });

        // ---------- Init awal (mode edit: unit_doc_ref sudah terpilih) ----------
        initJenisDokumenSelect2($('#id_unit_doc_ref').val());

        // ---------- Preview file & validasi ukuran max 5MB ----------
        var fileInput = document.getElementById('file_dokumen');
        var preview   = document.getElementById('fileNamePreview');
        fileInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                var file = this.files[0];
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file melebihi 5 MB. Silakan pilih file lain.');
                    this.value = '';
                    preview.style.display = 'none';
                    return;
                }
                preview.innerHTML     = '📎 ' + file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
                preview.style.display = 'block';
            }
        });

        // ---------- Modal: Tambah Jenis Dokumen ----------
        var $modalJD    = $('#modalJenisDokumen');
        var $inputJD    = $('#nm_jenis_dokumen_baru');
        var $selUnitDoc = $('#id_unit_doc_ref_modal');
        var $alertJD    = $('#alertJenisDokumen');
        var $btnSimpan  = $('#btnSimpanJenisDokumen');

        // Setiap modal dibuka, prefill Unit Dokumen dari form utama (kalau sudah dipilih)
        $modalJD.on('show.bs.modal', function () {
            var currentUnitDoc = $('#id_unit_doc_ref').val();
            if (currentUnitDoc) {
                $selUnitDoc.val(currentUnitDoc).trigger('change');
            }
        });

        $modalJD.on('shown.bs.modal', function () { $inputJD.trigger('focus'); });

        $modalJD.on('hidden.bs.modal', function () {
            $inputJD.val('');
            $selUnitDoc.val(null).trigger('change');
            $alertJD.hide().text('');
        });

        $btnSimpan.on('click', simpanJenisDokumenBaru);
        $inputJD.on('keydown', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault();
                simpanJenisDokumenBaru();
            }
        });

        function simpanJenisDokumenBaru() {
            var nama            = $.trim($inputJD.val());
            var id_unit_doc_ref = $selUnitDoc.val();
            $alertJD.hide().text('');

            if (!nama) {
                $alertJD.text('Nama jenis dokumen wajib diisi.').show();
                return;
            }
            if (!id_unit_doc_ref) {
                $alertJD.text('Unit Dokumen wajib dipilih.').show();
                return;
            }

            $btnSimpan.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url     : '<?php echo site_url('jenis_dokumen/add_ajax'); ?>',
                method  : 'POST',
                dataType: 'json',
                data    : { nm_jenis_dokumen: nama, id_unit_doc_ref: id_unit_doc_ref },
                success : function (res) {
                    if (res && res.status) {
                        // Sinkronkan Unit Dokumen di form utama dengan yang dipilih di modal,
                        // lalu reinit Jenis Dokumen supaya opsi baru muncul & langsung terpilih.
                        var $unitDocMain = $('#id_unit_doc_ref');
                        if ($unitDocMain.val() != id_unit_doc_ref) {
                            $unitDocMain.val(id_unit_doc_ref).trigger('change.select2');
                        }
                        initJenisDokumenSelect2(id_unit_doc_ref);

                        var $selJD = $('#id_jenis_dokumen');
                        var newOpt = new Option(res.nm_jenis_dokumen, res.id_jenis_dokumen, true, true);
                        $selJD.append(newOpt).trigger('change');

                        $modalJD.modal('hide');
                    } else {
                        $alertJD.text((res && res.message) || 'Gagal menyimpan data.').show();
                    }
                },
                error   : function (xhr) {
                    var msg = 'Terjadi kesalahan pada server';
                    if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    $alertJD.text(msg).show();
                },
                complete: function () { $btnSimpan.prop('disabled', false).text('Simpan'); }
            });
        }

    } // end initAll
});
</script>