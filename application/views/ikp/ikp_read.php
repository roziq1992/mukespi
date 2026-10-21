<?php
/**
 * Halaman detail / baca IKP (Insiden Keselamatan Pasien).
 * Render oleh Ikp::read() dan Insiden::read().
 */
$ikp_risk      = ikp_risk_class($grading_resiko);
$ikp_risk_label = trim((string) $grading_resiko) !== '' ? $grading_resiko : 'Belum Digrading';

$ikp_kln = strtolower((string) $kelamin);
$ikp_kln_label = ($ikp_kln === 'l' || $ikp_kln === 'laki-laki') ? 'Laki-laki' : (($ikp_kln === 'p' || $ikp_kln === 'perempuan') ? 'Perempuan' : $kelamin);

$ikp_jns = strtolower((string) $jns_insiden);
$ikp_jns_badge = 'netral';
if (strpos($ikp_jns, 'knc') !== false || strpos($ikp_jns, 'near miss') !== false) {
    $ikp_jns_badge = 'hijau';
} elseif (strpos($ikp_jns, 'sentinel') !== false || strpos($ikp_jns, 'ktd') !== false) {
    $ikp_jns_badge = 'merah';
} elseif (strpos($ikp_jns, 'tidak cidera') !== false) {
    $ikp_jns_badge = 'biru';
} elseif (strpos($ikp_jns, 'potensian') !== false) {
    $ikp_jns_badge = 'kuning';
}

$ikp_ulang = strtolower((string) $kejadian_terulang) === 'ya';
$ikp_ulang_badge = $ikp_ulang ? 'kuning' : 'netral';

$ikp_modul = (isset($modul) && $modul !== '') ? $modul : 'ikp';

// Selisih hari antara masuk RS dan kejadian insiden
$ikp_gap = NULL;
if (!empty($tgl_masuk) && !empty($tgl_kejadian)) {
    $ikp_masuk_ts = strtotime($tgl_masuk);
    $ikp_kejadian_ts = strtotime($tgl_kejadian);
    if ($ikp_masuk_ts && $ikp_kejadian_ts) {
        $ikp_gap = (int) floor(($ikp_kejadian_ts - $ikp_masuk_ts) / 86400);
    }
}
?>
<link rel="stylesheet" href="<?= base_url('assets/css/ikp.css'); ?>">

<div class="ikp-page">

    <!-- ============================== HERO ============================== -->
    <section class="ikp-hero">
        <div class="ikp-hero__inner">
            <div>
                <span class="ikp-eyebrow">
                    <i class="fas fa-notes-medical"></i> Insiden Keselamatan Pasien
                </span>
                <h1 class="ikp-hero__title"><?php echo html_escape($nm_pasien); ?></h1>
                <p class="ikp-hero__subtitle">
                    <?php echo html_escape($insiden); ?>
                </p>

                <div class="ikp-chips">
                    <span class="ikp-chip"><i class="fas fa-id-card"></i> No. RM: <?php echo html_escape($rm); ?></span>
                    <span class="ikp-chip"><i class="fas fa-hospital"></i> <?php echo html_escape($ruang); ?></span>
                    <span class="ikp-chip"><i class="fas fa-venus-mars"></i> <?php echo html_escape($ikp_kln_label); ?></span>
                    <span class="ikp-chip"><i class="fas fa-wallet"></i> <?php echo html_escape($penangung_jawab); ?></span>
                    <span class="ikp-chip ikp-chip--accent"><i class="fas fa-hashtag"></i> ID IKP: <?php echo html_escape($id_ikp); ?></span>
                </div>
            </div>

            <div class="ikp-risk ikp-risk--<?php echo $ikp_risk; ?>">
                <span class="ikp-risk__label">Grading Risiko</span>
                <span class="ikp-risk__value">
                    <span class="ikp-risk__dot"></span><?php echo html_escape($ikp_risk_label); ?>
                </span>
            </div>
        </div>

        <!-- Timeline -->
        <div class="ikp-timeline">
            <div class="ikp-tl">
                <span class="ikp-tl__icon"><i class="fas fa-sign-in-alt"></i></span>
                <span class="ikp-tl__label">Masuk RS</span>
                <span class="ikp-tl__value"><?php echo ikp_date($tgl_masuk) ?: '&mdash;'; ?></span>
                <span class="ikp-tl__note"><?php echo ikp_time($jam_masuk) ?: 'Jam tidak tercatat'; ?></span>
            </div>
            <div class="ikp-tl">
                <span class="ikp-tl__icon"><i class="fas fa-exclamation-triangle"></i></span>
                <span class="ikp-tl__label">Kejadian Insiden</span>
                <span class="ikp-tl__value"><?php echo ikp_date($tgl_kejadian) ?: '&mdash;'; ?></span>
                <span class="ikp-tl__note">
                    <?php echo ikp_time($jam_kejadian) ?: 'Jam tidak tercatat'; ?>
                    <?php if ($ikp_gap !== NULL): ?>
                        &nbsp;&middot;&nbsp; H+<?php echo $ikp_gap; ?> sejak masuk
                    <?php endif; ?>
                </span>
            </div>
            <div class="ikp-tl">
                <span class="ikp-tl__icon"><i class="fas fa-file-signature"></i></span>
                <span class="ikp-tl__label">Dilaporkan</span>
                <span class="ikp-tl__value"><?php echo ikp_date($tgl_lapor) ?: '&mdash;'; ?></span>
                <span class="ikp-tl__note"><?php echo ikp_time($jam_lapor) ?: 'Jam tidak tercatat'; ?></span>
            </div>
        </div>
    </section>

    <!-- ============================ ACTION BAR ========================== -->
    <div class="ikp-actions">
        <div class="ikp-actions__group">
            <a href="<?= site_url($ikp_modul); ?>" class="ikp-btn ikp-btn--ghost">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <a href="<?= site_url($ikp_modul . '/update/' . $id_ikp); ?>" class="ikp-btn ikp-btn--primary">
                <i class="fas fa-pen"></i> Ubah Data
            </a>
        </div>
        <div class="ikp-actions__group">
            <button type="button" class="ikp-btn ikp-btn--ghost" onclick="window.print();">
                <i class="fas fa-print"></i> Cetak
            </button>
            <a href="<?= site_url($ikp_modul . '/delete/' . $id_ikp); ?>"
               class="ikp-btn ikp-btn--danger"
               onclick="return confirm('Hapus data IKP ini? Tindakan ini tidak dapat dibatalkan.');">
                <i class="fas fa-trash"></i> Hapus
            </a>
        </div>
    </div>

    <!-- ============================== GRID ============================== -->
    <div class="ikp-grid">

        <!-- Data Pasien -->
        <section class="ikp-card">
            <div class="ikp-card__head">
                <span class="ikp-card__icon"><i class="fas fa-user-injured"></i></span>
                <div>
                    <h6 class="ikp-card__title">Data Pasien</h6>
                    <p class="ikp-card__desc">Identitas dan informasi admisi pasien</p>
                </div>
            </div>
            <div class="ikp-card__body">
                <div class="ikp-rows">
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-user"></i> Nama Pasien</div>
                        <div class="ikp-row__value"><?php echo ikp_val($nm_pasien); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-id-card"></i> No. RM</div>
                        <div class="ikp-row__value"><?php echo ikp_val($rm); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-hospital"></i> Ruangan</div>
                        <div class="ikp-row__value"><?php echo ikp_val($ruang); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-venus-mars"></i> Kelamin</div>
                        <div class="ikp-row__value"><?php echo ikp_val($ikp_kln_label); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-wallet"></i> Penanggung Jawab</div>
                        <div class="ikp-row__value"><?php echo ikp_val($penangung_jawab); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-calendar-check"></i> Tanggal &amp; Jam Masuk</div>
                        <div class="ikp-row__value"><?php echo ikp_val(ikp_datetime($tgl_masuk, $jam_masuk)); ?></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Klasifikasi Insiden -->
        <section class="ikp-card">
            <div class="ikp-card__head">
                <span class="ikp-card__icon"><i class="fas fa-tags"></i></span>
                <div>
                    <h6 class="ikp-card__title">Klasifikasi Insiden</h6>
                    <p class="ikp-card__desc">Jenis insiden, lokasi dan unit penyebab</p>
                </div>
            </div>
            <div class="ikp-card__body">
                <div class="ikp-rows">
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-layer-group"></i> Jenis Insiden</div>
                        <div class="ikp-row__value">
                            <span class="ikp-badge ikp-badge--<?php echo $ikp_jns_badge; ?>"><?php echo ikp_val($jns_insiden); ?></span>
                        </div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-map-marker-alt"></i> Tempat Insiden</div>
                        <div class="ikp-row__value"><?php echo ikp_val($tempat_insiden); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-user"></i> Insiden Terjadi Pada</div>
                        <div class="ikp-row__value"><?php echo ikp_val($insiden_terjadipd); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-procedures"></i> Insiden Meyangkut</div>
                        <div class="ikp-row__value"><?php echo ikp_val($insiden_meyangkut); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-stethoscope"></i> Sesuai Kasus Penyakit</div>
                        <div class="ikp-row__value"><?php echo ikp_val($insiden_terjadipd2); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-building"></i> Unit Penyebab</div>
                        <div class="ikp-row__value"><?php echo ikp_val($unit_penyebab); ?></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Kronologis -->
        <section class="ikp-card">
            <div class="ikp-card__head">
                <span class="ikp-card__icon"><i class="fas fa-clock-rotate-left"></i></span>
                <div>
                    <h6 class="ikp-card__title">Kronologis Insiden</h6>
                    <p class="ikp-card__desc">Uraian kejadian dan kronologi insiden</p>
                </div>
            </div>
            <div class="ikp-card__body">
                <div class="ikp-row" style="padding-top:0;">
                    <div class="ikp-row__label"><i class="fas fa-bolt"></i> Insiden</div>
                    <div class="ikp-row__value"><?php echo ikp_val($insiden); ?></div>
                </div>
                <div class="ikp-row">
                    <div class="ikp-row__label"><i class="fas fa-align-left"></i> Kronologis</div>
                    <div class="ikp-row__value">
                        <?php if (trim((string) $krologis) === ''): ?>
                            <span class="ikp-row__value--muted">&mdash;</span>
                        <?php else: ?>
                            <div class="ikp-text"><?php echo html_escape($krologis); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dampak & Tindak Lanjut -->
        <section class="ikp-card">
            <div class="ikp-card__head">
                <span class="ikp-card__icon"><i class="fas fa-heartbeat"></i></span>
                <div>
                    <h6 class="ikp-card__title">Dampak &amp; Tindak Lanjut</h6>
                    <p class="ikp-card__desc">Akibat insiden dan penanganan yang dilakukan</p>
                </div>
            </div>
            <div class="ikp-card__body">
                <div class="ikp-rows">
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-heart-in-pulse"></i> Akibat Insiden</div>
                        <div class="ikp-row__value"><?php echo ikp_val($akibat_insiden); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-hand-holding-medical"></i> Tindakan</div>
                        <div class="ikp-row__value">
                            <?php if (trim((string) $tindakan) === ''): ?>
                                <span class="ikp-row__value--muted">&mdash;</span>
                            <?php else: ?>
                                <div class="ikp-text"><?php echo html_escape($tindakan); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-user-md"></i> Tindakan Dilakukan Oleh</div>
                        <div class="ikp-row__value"><?php echo ikp_val($tindakan_oleh); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-repeat"></i> Kejadian Terulang</div>
                        <div class="ikp-row__value">
                            <span class="ikp-badge ikp-badge--<?php echo $ikp_ulang_badge; ?>"><?php echo ikp_val($kejadian_terulang); ?></span>
                        </div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-comment-dots"></i> Keterangan Terulang</div>
                        <div class="ikp-row__value">
                            <?php if (trim((string) $ket_kejadian_terulang) === ''): ?>
                                <span class="ikp-row__value--muted">&mdash;</span>
                            <?php else: ?>
                                <div class="ikp-text"><?php echo html_escape($ket_kejadian_terulang); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pelaporan -->
        <section class="ikp-card">
            <div class="ikp-card__head">
                <span class="ikp-card__icon"><i class="fas fa-clipboard-check"></i></span>
                <div>
                    <h6 class="ikp-card__title">Data Pelaporan</h6>
                    <p class="ikp-card__desc">Pelapor, penerima dan status grading</p>
                </div>
            </div>
            <div class="ikp-card__body">
                <div class="ikp-rows">
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-bullhorn"></i> Pelapor Pertama</div>
                        <div class="ikp-row__value"><?php echo ikp_val($pelapor_pertama); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-user-check"></i> Pelapor</div>
                        <div class="ikp-row__value"><?php echo ikp_val($pelapor); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-user-shield"></i> Penerima Laporan</div>
                        <div class="ikp-row__value"><?php echo ikp_val($penerima); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-paper-plane"></i> Tanggal &amp; Jam Lapor</div>
                        <div class="ikp-row__value"><?php echo ikp_val(ikp_datetime($tgl_lapor, $jam_lapor)); ?></div>
                    </div>
                    <div class="ikp-row">
                        <div class="ikp-row__label"><i class="fas fa-shield-halved"></i> Grading Risiko</div>
                        <div class="ikp-row__value">
                            <span class="ikp-badge ikp-badge--<?php echo $ikp_risk; ?>"><?php echo ikp_val($ikp_risk_label); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>
