<style>
    .pe2-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
    }
    .pe2-header {
        background: linear-gradient(135deg, #6a3fa0 0%, #3d2266 100%);
        color: #fff;
        padding: 20px 24px;
    }
    .pe2-header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        flex-wrap: wrap;
    }
    .pe2-header .pe2-breadcrumb {
        font-size: 0.78rem;
        opacity: 0.85;
        margin-bottom: 6px;
    }
    .pe2-header .pe2-breadcrumb a { color: #fff; text-decoration: underline; }
    .pe2-header h2 { margin: 0; font-size: 1.2rem; font-weight: 700; }
    .pe2-header p { margin: 4px 0 0; font-size: 0.82rem; opacity: 0.9; }
    .pe2-btn-summary {
        background: rgba(255,255,255,0.16);
        border: 1px solid rgba(255,255,255,0.35);
        color: #fff;
        border-radius: 8px;
        padding: 7px 14px;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .pe2-btn-summary:hover { background: rgba(255,255,255,0.28); color: #fff; text-decoration: none; }

    .pe2-stats {
        display: flex;
        gap: 10px;
        margin-top: 14px;
        flex-wrap: wrap;
    }
    .pe2-stat-chip {
        background: rgba(255,255,255,0.16);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .pe2-body { padding: 22px; }
    @media (max-width: 576px) { .pe2-body { padding: 14px; } }

    .pe2-flash {
        background: #eaf3fb;
        color: #1b3a5c;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 18px;
    }
    .pe2-surveior-banner {
        background: #fff8e6;
        color: #8a6100;
        border-left: 4px solid #d4a017;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 18px;
    }

    .pe2-standar-title {
        font-size: 0.8rem;
        font-weight: 800;
        color: #6a3fa0;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin: 26px 0 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #efe6fa;
    }
    .pe2-standar-title:first-of-type { margin-top: 0; }
    .pe2-standar-isi {
        font-size: 0.82rem;
        font-weight: 400;
        color: #8a94a6;
        text-transform: none;
        letter-spacing: normal;
        display: block;
        margin-top: 3px;
    }

    .pe2-ep-card {
        border: 1px solid #eef0f3;
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 14px;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .pe2-ep-card.pe2-saved { border-color: #cfe8d8; background: #fbfdfc; }
    .pe2-ep-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        flex-wrap: wrap;
    }
    .pe2-ep-no {
        display: inline-block;
        background: #eef2f7;
        color: #33475b;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 2px 9px;
        border-radius: 20px;
        margin-bottom: 6px;
    }
    .pe2-ep-isi { font-size: 0.88rem; color: #33475b; font-weight: 600; margin-bottom: 2px; }
    .pe2-ep-maks { font-size: 0.75rem; color: #8a94a6; }

    .pe2-status-badge {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .pe2-status-belum { background: #fff3cd; color: #856404; }
    .pe2-status-sudah { background: #d4edda; color: #1e7e34; }

    .pe2-track-label {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #6a3fa0;
        margin: 14px 0 6px;
    }

    .pe2-skor-pills {
        display: flex;
        gap: 8px;
        margin: 6px 0 10px;
        flex-wrap: wrap;
    }
    .pe2-skor-pill { position: relative; }
    .pe2-skor-pill input { position: absolute; opacity: 0; pointer-events: none; }
    .pe2-skor-pill label {
        display: inline-block;
        padding: 7px 16px;
        border-radius: 20px;
        border: 1.5px solid #dde3ea;
        font-size: 0.82rem;
        font-weight: 700;
        color: #556;
        cursor: pointer;
        margin: 0;
        transition: all .12s ease;
        user-select: none;
    }
    .pe2-skor-pill input:checked + label {
        border-color: #6a3fa0;
        background: #6a3fa0;
        color: #fff;
    }
    .pe2-skor-pill.pe2-pill-0 input:checked + label { border-color: #c0392b; background: #c0392b; }
    .pe2-skor-pill.pe2-pill-half input:checked + label { border-color: #b8860b; background: #b8860b; }
    .pe2-skor-pill.pe2-pill-full input:checked + label { border-color: #1e8449; background: #1e8449; }

    .pe2-keterangan {
        width: 100%;
        border: 1px solid #dde3ea;
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 0.82rem;
        resize: vertical;
        min-height: 44px;
    }

    /* ---- Banding skor track lain (read-only) ---- */
    .pe2-banding-box {
        margin-top: 12px;
        padding: 10px 12px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #eef0f3;
        font-size: 0.8rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .pe2-banding-box .pe2-banding-label { color: #8a94a6; font-weight: 700; }
    .pe2-banding-box .pe2-banding-skor { font-weight: 800; color: #33475b; }
    .pe2-selisih-badge {
        font-size: 0.68rem;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .pe2-selisih-sama { background: #d4edda; color: #1e7e34; }
    .pe2-selisih-beda { background: #fdecea; color: #c0392b; }
    .pe2-belum-dinilai-track { color: #8a94a6; font-style: italic; }

    .pe2-ep-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 12px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .pe2-btn-simpan {
        background: #6a3fa0;
        border: none;
        color: #fff;
        border-radius: 8px;
        padding: 7px 18px;
        font-size: 0.82rem;
        font-weight: 700;
    }
    .pe2-btn-simpan:hover { background: #4f2f7a; }
    .pe2-btn-simpan:disabled { opacity: 0.6; }

    .pe2-btn-bukti {
        background: #f3eefb;
        border: 1px solid #dcc9f2;
        color: #6a3fa0;
        border-radius: 8px;
        padding: 7px 16px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .pe2-btn-bukti:hover { background: #e9dbf7; }
    .pe2-bukti-count {
        background: #6a3fa0;
        color: #fff;
        font-size: 0.68rem;
        font-weight: 800;
        border-radius: 50%;
        min-width: 18px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
    }

    .pe2-save-tick { font-size: 0.75rem; color: #1e8449; font-weight: 700; opacity: 0; transition: opacity .2s ease; }
    .pe2-save-tick.show { opacity: 1; }

    /* ===== Modal Bukti ===== */
    #modalBukti .modal-header { background: #6a3fa0; color: #fff; }
    #modalBukti .modal-header .close { color: #fff; opacity: 0.9; }
    .pe2-upload-box {
        border: 2px dashed #d8c7ec;
        border-radius: 10px;
        padding: 16px;
        text-align: center;
        background: #faf7fd;
        cursor: pointer;
        position: relative;
        margin-bottom: 14px;
    }
    .pe2-upload-box:hover { border-color: #6a3fa0; }
    .pe2-upload-box input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
    .pe2-upload-box .pe2-upload-text { font-size: 0.85rem; font-weight: 700; color: #6a3fa0; }
    .pe2-upload-box .pe2-upload-hint { font-size: 0.72rem; color: #8a94a6; margin-top: 3px; }
    .pe2-selected-files { font-size: 0.78rem; color: #556; margin-bottom: 10px; }

    .pe2-file-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 10px;
        border: 1px solid #eef0f3;
        border-radius: 8px;
        margin-bottom: 8px;
    }
    .pe2-file-icon-sm {
        width: 28px; height: 28px;
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.55rem; font-weight: 800; color: #fff; flex-shrink: 0;
        background: #8a94a6;
    }
    .pe2-file-icon-sm.pdf { background: #e74c3c; }
    .pe2-file-icon-sm.doc { background: #2c5f8a; }
    .pe2-file-icon-sm.xls { background: #1e8449; }
    .pe2-file-icon-sm.img { background: #b8860b; }
    .pe2-file-name { flex: 1; font-size: 0.82rem; color: #33475b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pe2-file-meta { font-size: 0.7rem; color: #8a94a6; }
    .pe2-file-jenis {
        font-size: 0.62rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 1px 7px;
        border-radius: 20px;
        margin-left: 6px;
    }
    .pe2-file-jenis.internal { background: #eef2f7; color: #33475b; }
    .pe2-file-jenis.surveior { background: #fff3cd; color: #856404; }
    .pe2-file-del { color: #c0392b; border: none; background: none; font-size: 0.9rem; cursor: pointer; }
    .pe2-empty-file { text-align: center; color: #8a94a6; font-size: 0.82rem; padding: 14px; }
</style>

<div class="container-fluid">
    <div class="pe2-card">
        <div class="pe2-header">
            <div class="pe2-header-top">
                <div>
                    <div class="pe2-breadcrumb">
                        <a href="<?php echo site_url('penilaian_ep') ?>">← Daftar Pokja</a>
                    </div>
                    <h2><?php echo $pokja->bab ?> — <?php echo $pokja->ket ?></h2>
                    <p>Isi skor tiap EP lalu lampirkan bukti dokumen (boleh lebih dari 1 file per EP)</p>
                </div>
                <a href="<?php echo site_url('penilaian_ep/summary') ?>" class="pe2-btn-summary">📊 Summary</a>
            </div>
            <div class="pe2-stats">
                <span class="pe2-stat-chip">📅 <?php echo $periode->nama_periode ?></span>
                <span class="pe2-stat-chip" id="statTotalEp">📋 <?php echo count($ep_list) ?> EP</span>
                <span class="pe2-stat-chip" id="statDinilai">
                    ✅ <?php echo count(array_filter($ep_list, function($e) use ($jenis_saya) {
                        return $jenis_saya === 'surveior' ? ($e->skor_surveior !== NULL) : ($e->skor_internal !== NULL);
                    })) ?> sudah dinilai (<?php echo $jenis_saya === 'surveior' ? 'Surveior' : 'Internal' ?>)
                </span>
            </div>
        </div>

        <div class="pe2-body">
            <?php
                $flash = $this->session->flashdata('message');
                if ($flash) {
                    echo '<div class="pe2-flash">' . $flash . '</div>';
                }
            ?>

            <?php if (!empty($is_surveior)): ?>
                <div class="pe2-surveior-banner">
                    🧑‍💼 Anda mengisi skor sebagai <strong>Surveior</strong> — terpisah dari skor tim internal.
                </div>
            <?php endif; ?>

            <?php if (empty($ep_list)): ?>
                <div class="pe2-empty-file">Belum ada elemen penilaian untuk pokja ini.</div>
            <?php else: ?>
                <?php $standar_aktif = NULL; ?>
                <?php foreach ($ep_list as $ep):
                    if ($standar_aktif !== $ep->no_standar) {
                        $standar_aktif = $ep->no_standar;
                        echo '<div class="pe2-standar-title">Standar ' . $ep->no_standar . '<span class="pe2-standar-isi">' . $ep->isi_standar . '</span></div>';
                    }

                    $skor_maks = intval($ep->skor_maks);
                    $skor_half = (int) round($skor_maks / 2);

                    // skor & keterangan milik user yang sedang login (track yang bisa diedit)
                    $skor_saya       = ($jenis_saya === 'surveior') ? $ep->skor_surveior : $ep->skor_internal;
                    $keterangan_saya = ($jenis_saya === 'surveior') ? $ep->keterangan_surveior : $ep->keterangan_internal;
                    $sudah_dinilai   = ($skor_saya !== NULL);

                    // skor track SEBALAH (buat dibandingkan, read-only)
                    $skor_lain      = ($jenis_saya === 'surveior') ? $ep->skor_internal : $ep->skor_surveior;
                    $label_lain     = ($jenis_saya === 'surveior') ? 'Skor Internal (Tim RS)' : 'Skor Surveior';
                    $ada_selisih    = ($skor_saya !== NULL && $skor_lain !== NULL && intval($skor_saya) !== intval($skor_lain));

                    $jml_bukti_total = intval($ep->jml_bukti_internal) + intval($ep->jml_bukti_surveior);
                ?>
                <div class="pe2-ep-card <?php echo $sudah_dinilai ? 'pe2-saved' : '' ?>" id="ep-card-<?php echo $ep->id_ep ?>" data-id-ep="<?php echo $ep->id_ep ?>">
                    <div class="pe2-ep-top">
                        <div>
                            <span class="pe2-ep-no">EP <?php echo $ep->no_ep ?></span>
                            <div class="pe2-ep-isi"><?php echo $ep->isi_ep ?></div>
                            <div class="pe2-ep-maks">Skor maksimal: <?php echo $skor_maks ?></div>
                        </div>
                        <span class="pe2-status-badge <?php echo $sudah_dinilai ? 'pe2-status-sudah' : 'pe2-status-belum' ?>" id="status-badge-<?php echo $ep->id_ep ?>">
                            <?php echo $sudah_dinilai ? 'Sudah Dinilai' : 'Belum Dinilai' ?>
                        </span>
                    </div>

                    <div class="pe2-track-label"><?php echo $jenis_saya === 'surveior' ? 'Skor Anda (Surveior)' : 'Skor Anda (Internal)' ?></div>

                    <div class="pe2-skor-pills">
                        <div class="pe2-skor-pill pe2-pill-0">
                            <input type="radio" name="skor_<?php echo $ep->id_ep ?>" id="skor_<?php echo $ep->id_ep ?>_0" value="0" <?php echo ($sudah_dinilai && intval($skor_saya) === 0) ? 'checked' : '' ?>>
                            <label for="skor_<?php echo $ep->id_ep ?>_0">Tidak Terpenuhi (0)</label>
                        </div>
                        <div class="pe2-skor-pill pe2-pill-half">
                            <input type="radio" name="skor_<?php echo $ep->id_ep ?>" id="skor_<?php echo $ep->id_ep ?>_half" value="<?php echo $skor_half ?>" <?php echo ($sudah_dinilai && intval($skor_saya) === $skor_half) ? 'checked' : '' ?>>
                            <label for="skor_<?php echo $ep->id_ep ?>_half">Sebagian (<?php echo $skor_half ?>)</label>
                        </div>
                        <div class="pe2-skor-pill pe2-pill-full">
                            <input type="radio" name="skor_<?php echo $ep->id_ep ?>" id="skor_<?php echo $ep->id_ep ?>_full" value="<?php echo $skor_maks ?>" <?php echo ($sudah_dinilai && intval($skor_saya) === $skor_maks) ? 'checked' : '' ?>>
                            <label for="skor_<?php echo $ep->id_ep ?>_full">Terpenuhi Penuh (<?php echo $skor_maks ?>)</label>
                        </div>
                    </div>

                    <textarea class="pe2-keterangan" placeholder="Catatan / keterangan (opsional)"><?php echo $keterangan_saya ?></textarea>

                   <div class="pe2-banding-box" data-skor-lain="<?php echo ($skor_lain !== NULL) ? intval($skor_lain) : '' ?>">
    <span class="pe2-banding-label"><?php echo $label_lain ?>:</span>
                        <?php if ($skor_lain !== NULL): ?>
                            <span class="pe2-banding-skor"><?php echo intval($skor_lain) ?> / <?php echo $skor_maks ?></span>
                            <span class="pe2-selisih-badge <?php echo $ada_selisih ? 'pe2-selisih-beda' : 'pe2-selisih-sama' ?>">
                                <?php echo $ada_selisih ? '⚠ Beda Skor' : '✔ Sama' ?>
                            </span>
                        <?php else: ?>
                            <span class="pe2-belum-dinilai-track">Belum dinilai</span>
                        <?php endif; ?>
                    </div>

                    <div class="pe2-ep-actions">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <button type="button" class="pe2-btn-simpan" onclick="peSimpanSkor(<?php echo $ep->id_ep ?>)">💾 Simpan Skor</button>
                            <span class="pe2-save-tick" id="tick-<?php echo $ep->id_ep ?>">✔ Tersimpan</span>
                        </div>
                        <button type="button" class="pe2-btn-bukti" onclick='peBukaModalBukti(<?php echo $ep->id_ep ?>, <?php echo json_encode((string) $ep->no_ep) ?>)'>
                            📎 Kelola Bukti
                            <span class="pe2-bukti-count" id="badge-bukti-<?php echo $ep->id_ep ?>"><?php echo $jml_bukti_total ?></span>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ================= MODAL BUKTI ================= -->
<div class="modal fade" id="modalBukti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title">📎 Bukti Dokumen — EP <span id="modalBuktiNoEp"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label class="pe2-upload-box" id="peUploadBox">
                    <input type="file" id="peFileBukti" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" />
                    <div class="pe2-upload-text">⬆️ Ketuk untuk pilih file (boleh lebih dari 1)</div>
                    <div class="pe2-upload-hint">PDF, Word, Excel, atau gambar — maksimal 5 MB per file. File akan tercatat sebagai bukti dari track Anda.</div>
                </label>
                <div class="pe2-selected-files" id="peSelectedFiles"></div>

                <div class="form-group">
                    <input type="text" class="form-control" id="peKeteranganBukti" placeholder="Keterangan file (opsional)" style="font-size:0.85rem;">
                </div>

                <button type="button" class="btn btn-primary btn-block" id="peBtnUpload" onclick="peUploadBukti()" style="background:#6a3fa0; border:none;">Upload File</button>

                <hr>

                <div id="peDaftarBukti">
                    <div class="pe2-empty-file">Memuat...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.addEventListener('load', function() {
    var $ = window.jQuery;
    if (!$) { console.error('jQuery tidak tersedia'); return; }

    var CI_BASE = '<?php echo site_url('penilaian_ep') ?>';
    var currentIdEp = null;
    var pickedFiles = [];

    // ---------- Simpan skor per EP ----------
window.peSimpanSkor = function(idEp) {
    var $card = $('#ep-card-' + idEp);
    var skor = $card.find('input[type="radio"]:checked').val();
    var keterangan = $card.find('.pe2-keterangan').val();
    var $btn = $card.find('.pe2-btn-simpan');

    $btn.prop('disabled', true).text('Menyimpan...');

    $.ajax({
        url: CI_BASE + '/save_skor',
        method: 'POST',
        dataType: 'json',
        data: { id_ep: idEp, skor: skor || '', keterangan: keterangan },
        success: function(res) {
            if (res && res.status) {
                peUpdateTampilanSetelahSimpan($card, idEp, skor);

                var $tick = $('#tick-' + idEp);
                $tick.addClass('show');
                setTimeout(function() { $tick.removeClass('show'); }, 1800);
            } else {
                alert((res && res.message) ? res.message : 'Gagal menyimpan skor');
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat menyimpan skor');
        },
        complete: function() {
            $btn.prop('disabled', false).text('💾 Simpan Skor');
        }
    });
};

// Update badge status + kotak banding (Sama/Beda) + ringkasan header, tanpa reload
function peUpdateTampilanSetelahSimpan($card, idEp, skorBaru) {
    var sudahDinilai = (skorBaru !== undefined && skorBaru !== '');

    // 1) badge "Sudah/Belum Dinilai" + style card
    $card.toggleClass('pe2-saved', sudahDinilai);
    $('#status-badge-' + idEp)
        .toggleClass('pe2-status-sudah', sudahDinilai)
        .toggleClass('pe2-status-belum', !sudahDinilai)
        .text(sudahDinilai ? 'Sudah Dinilai' : 'Belum Dinilai');

    // 2) kotak banding: badge Sama / Beda dihitung ulang pakai skor track sebelah
    //    yang sudah dititip di data-skor-lain (skor track sebelah sendiri tidak berubah)
    var $banding    = $card.find('.pe2-banding-box');
    var skorLainRaw = $banding.attr('data-skor-lain');
    var $selisih    = $banding.find('.pe2-selisih-badge');

    if (skorLainRaw !== '' && skorLainRaw !== undefined && $selisih.length) {
        var adaSelisih = sudahDinilai
            ? (parseInt(skorBaru, 10) !== parseInt(skorLainRaw, 10))
            : false; // skor saya kosong -> anggap "Sama" (samakan dgn logika PHP di server)

        $selisih
            .toggleClass('pe2-selisih-beda', adaSelisih)
            .toggleClass('pe2-selisih-sama', !adaSelisih)
            .text(adaSelisih ? '⚠ Beda Skor' : '✔ Sama');
    }
    // kalau data-skor-lain kosong (track sebelah "Belum dinilai"), tidak perlu diubah

    // 3) hitung ulang chip "✅ X sudah dinilai" di header, tanpa call server lagi
    var totalSudah = $('.pe2-status-badge.pe2-status-sudah').length;
    $('#statDinilai').text('✅ ' + totalSudah + ' sudah dinilai (<?php echo $jenis_saya === "surveior" ? "Surveior" : "Internal" ?>)');
}

    // ---------- Modal bukti ----------
    window.peBukaModalBukti = function(idEp, noEp) {
        currentIdEp = idEp;
        pickedFiles = [];
        $('#modalBuktiNoEp').text(noEp);
        $('#peSelectedFiles').text('');
        $('#peKeteranganBukti').val('');
        $('#peFileBukti').val('');
        $('#modalBukti').modal('show');
        peMuatDaftarBukti();
    };

    function iconClassFor(namaFile) {
        var ext = (namaFile.split('.').pop() || '').toLowerCase();
        if (ext === 'pdf') return ['pdf', 'PDF'];
        if (['doc', 'docx'].indexOf(ext) > -1) return ['doc', 'DOC'];
        if (['xls', 'xlsx'].indexOf(ext) > -1) return ['xls', 'XLS'];
        if (['jpg', 'jpeg', 'png'].indexOf(ext) > -1) return ['img', 'IMG'];
        return ['', 'FILE'];
    }

    function peMuatDaftarBukti() {
        $('#peDaftarBukti').html('<div class="pe2-empty-file">Memuat...</div>');

        $.ajax({
            url: CI_BASE + '/list_bukti/' + currentIdEp,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (!res || !res.status) {
                    $('#peDaftarBukti').html('<div class="pe2-empty-file">Gagal memuat data</div>');
                    return;
                }

                if (!res.files.length) {
                    $('#peDaftarBukti').html('<div class="pe2-empty-file">Belum ada bukti diupload untuk EP ini</div>');
                    return;
                }

                var html = '';
                res.files.forEach(function(f) {
                    var ic = iconClassFor(f.nama_file);
                    var jenisLabel = f.jenis_penilaian === 'surveior' ? 'Surveior' : 'Internal';
                    html += '<div class="pe2-file-item" data-id-upload="' + f.id_upload + '">' +
                        '<div class="pe2-file-icon-sm ' + ic[0] + '">' + ic[1] + '</div>' +
                        '<div style="flex:1; min-width:0;">' +
                        '<div class="pe2-file-name"><a href="' + f.url + '" target="_blank">' + f.nama_file + '</a><span class="pe2-file-jenis ' + f.jenis_penilaian + '">' + jenisLabel + '</span></div>' +
                        '<div class="pe2-file-meta">' + (f.uploaded_by || '') + ' • ' + (f.uploaded_at || '') + '</div>' +
                        '</div>' +
                        '<button type="button" class="pe2-file-del" title="Hapus" onclick="peHapusBukti(' + f.id_upload + ')">🗑️</button>' +
                        '</div>';
                });
                $('#peDaftarBukti').html(html);
            },
            error: function() {
                $('#peDaftarBukti').html('<div class="pe2-empty-file">Terjadi kesalahan saat memuat data</div>');
            }
        });
    }

    $('#peFileBukti').on('change', function() {
        pickedFiles = Array.prototype.slice.call(this.files);
        if (pickedFiles.length) {
            $('#peSelectedFiles').text(pickedFiles.length + ' file dipilih: ' + pickedFiles.map(function(f){ return f.name; }).join(', '));
        } else {
            $('#peSelectedFiles').text('');
        }
    });

    window.peUploadBukti = function() {
        if (!currentIdEp) return;
        if (!pickedFiles.length) { alert('Pilih minimal 1 file terlebih dahulu'); return; }

        var fd = new FormData();
        fd.append('id_ep', currentIdEp);
        fd.append('keterangan', $('#peKeteranganBukti').val());
        pickedFiles.forEach(function(f) { fd.append('file_bukti[]', f); });

        var $btn = $('#peBtnUpload');
        $btn.prop('disabled', true).text('Mengupload...');

        $.ajax({
            url: CI_BASE + '/upload_bukti',
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res && res.status) {
                    pickedFiles = [];
                    $('#peFileBukti').val('');
                    $('#peSelectedFiles').text('');
                    $('#peKeteranganBukti').val('');
                    peMuatDaftarBukti();
                    peSegarkanBadge(currentIdEp);
                } else {
                    alert((res && res.message) ? res.message : 'Gagal upload file');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat upload file');
            },
            complete: function() {
                $btn.prop('disabled', false).text('Upload File');
            }
        });
    };

    window.peHapusBukti = function(idUpload) {
        if (!confirm('Hapus file bukti ini?')) return;

        $.ajax({
            url: CI_BASE + '/delete_bukti',
            method: 'POST',
            dataType: 'json',
            data: { id_upload: idUpload },
            success: function(res) {
                if (res && res.status) {
                    peMuatDaftarBukti();
                    peSegarkanBadge(currentIdEp);
                } else {
                    alert((res && res.message) ? res.message : 'Gagal menghapus file');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus file');
            }
        });
    };

    function peSegarkanBadge(idEp) {
        $.ajax({
            url: CI_BASE + '/list_bukti/' + idEp,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res && res.status) {
                    $('#badge-bukti-' + idEp).text(res.files.length);
                }
            }
        });
    }
});
</script>