<?php
/**
 * Formulir Entry / Ubah Data IKP (Insiden Keselamatan Pasien).
 * Render oleh Ikp::create(), Ikp::update() dan Insiden::update().
 */

/* ------------------------------------------------------------------ */
/*  Daftar pilihan (memuat alias untuk menyelaraskan data lama)        */
/* ------------------------------------------------------------------ */
$opt_kelamin = array(
    array('v' => 'Laki-laki', 'alias' => array('L', 'Laki-Laki')),
    array('v' => 'Perempuan', 'alias' => array('P')),
);

$opt_penanggung = array(
    array('v' => 'Pribadi/Umum', 'alias' => array('Pribadi')),
    array('v' => 'ASKES Pemerintah'),
    array('v' => 'Asuransi Swasta'),
    array('v' => 'JAMKESMAS'),
    array('v' => 'Perusahaan'),
);

$opt_jns_insiden = array(
    array('v' => 'Kejadian Nyaris Cedera / KNC (Near miss)'),
    array('v' => 'Kejadian Tidak diharapkan / KTD (Adverse Event) / Kejadian Sentinel (Sentinel Event)'),
    array('v' => 'Kejadian tidak cidera'),
    array('v' => 'Kejadian potensian seknifikas cidera'),
);

$opt_pelapor_pertama = array(
    array('v' => 'Karyawan : Dokter / Perawat / Petugas lainnya'),
    array('v' => 'Pasien'),
    array('v' => 'Keluarga/Pendamping pasien'),
    array('v' => 'Pengunjung'),
    array('v' => 'Lain-lain'),
);

$opt_terjadipd = array(
    array('v' => 'Pasien'),
    array('v' => 'Lain-lain'),
);

$opt_meyangkut = array(
    array('v' => 'Pasien rawat inap'),
    array('v' => 'Pasien rawat jalan'),
    array('v' => 'Pasien IGD'),
    array('v' => 'Lain-lain'),
);

$opt_spesialisasi = array(
    array('v' => 'Penyakit Dalam dan Subspesialisasinya'),
    array('v' => 'Anak dan Subspesialisasinya'),
    array('v' => 'Bedah dan Subspesialisasinya'),
    array('v' => 'Obstetri Gynekologi dan Subspesialisasinya', 'alias' => array('bstetri Gynekologi dan Subspesialisasinya')),
    array('v' => 'THT dan Subspesialisasinya'),
    array('v' => 'Mata dan Subspesialisasinya'),
    array('v' => 'Saraf dan Subspesialisasinya'),
    array('v' => 'Anastesi dan Subspesialisasinya'),
    array('v' => 'Jantung dan Subspesialisasinya'),
    array('v' => 'Paru dan Subspesialisasinya'),
    array('v' => 'Kulit & Kelamin dan Subspesialisasinya'),
    array('v' => 'Jiwa dan Subspesialisasinya'),
    array('v' => 'Lain-lain'),
);

$opt_akibat = array(
    array('v' => 'Kematian'),
    array('v' => 'Cedera Irreversibel/Cedera Berat Cedera Reversibel/Cedera Sedang Cedera Ringan'),
    array('v' => 'Tidak ada cedera', 'alias' => array('Pasien IGD')),
);

$opt_tindakan_oleh = array(
    array('v' => 'Dokter'),
    array('v' => 'Perawat'),
    array('v' => 'Petugas lainnya'),
);

$opt_kejadian_terulang = array(
    array('v' => 'Ya'),
    array('v' => 'Tidak'),
);

$opt_grading = array(
    array('v' => 'Biru'),
    array('v' => 'Hijau', 'alias' => array('Hjau')),
    array('v' => 'Kuning'),
    array('v' => 'Merah'),
);

/* ------------------------------------------------------------------ */
/*  Helper render field                                                */
/* ------------------------------------------------------------------ */
$f_label = function ($label, $hint = '') {
    $html  = '<div class="ikp-field__label">';
    $html .= '<span class="ikp-field__name">' . html_escape($label) . '</span>';
    $html .= '<span class="ikp-field__req">*</span>';
    if ($hint !== '') {
        $html .= '<span class="ikp-field__hint">' . $hint . '</span>';
    }
    $html .= '</div>';
    return $html;
};

$f_error = function ($name) {
    $err = form_error($name, '<span class="ikp-error">', '</span>');
    return $err ? $err : '';
};

$f_text = function ($name, $label, $value, $type = 'text', $hint = '', $placeholder = '') use ($f_label, $f_error) {
    $html  = '<div class="ikp-field">';
    $html .= $f_label($label, $hint);
    $html .= '<input type="' . $type . '" class="ikp-control" id="' . $name . '" name="' . $name . '" value="' . html_escape((string) $value) . '" placeholder="' . html_escape($placeholder !== '' ? $placeholder : $label) . '" />';
    $html .= $f_error($name);
    $html .= '</div>';
    return $html;
};

/**
 * Select. Bila nilai lama tidak ada di daftar option, tetap ditampilkan
 * sebagai option terpilih agar data tidak hilang saat disimpan.
 */
$f_select = function ($name, $label, $current, array $options, $hint = '') use ($f_label, $f_error) {
    $current  = trim((string) $current);
    $selected = ikp_opt_match($current, $options);

    if ($selected === NULL && $current !== '') {
        $options[] = array('v' => $current, 'label' => $current . ' (data lama)');
        $selected  = $current;
    }

    $html  = '<div class="ikp-field">';
    $html .= $f_label($label, $hint);
    $html .= '<select class="ikp-control" id="' . $name . '" name="' . $name . '">';
    $html .= '<option value="">-- Pilih ' . html_escape($label) . ' --</option>';
    foreach ($options as $opt) {
        $html .= '<option value="' . html_escape($opt['v']) . '"' . ikp_selected($selected, $opt['v']) . '>'
            . html_escape(isset($opt['label']) ? $opt['label'] : $opt['v']) . '</option>';
    }
    $html .= '</select>';
    $html .= $f_error($name);
    $html .= '</div>';
    return $html;
};

$f_area = function ($name, $label, $value, $rows = 3, $hint = '', $placeholder = '') use ($f_label, $f_error) {
    $html  = '<div class="ikp-field">';
    $html .= $f_label($label, $hint);
    $html .= '<textarea class="ikp-control" rows="' . (int) $rows . '" id="' . $name . '" name="' . $name . '" placeholder="' . html_escape($placeholder !== '' ? $placeholder : $label) . '">' . html_escape((string) $value) . '</textarea>';
    $html .= $f_error($name);
    $html .= '</div>';
    return $html;
};

/**
 * Radio button bergaya pill.
 */
$f_pills = function ($name, $label, $current, array $options, $hint = '', $classes = array()) use ($f_label, $f_error) {
    $current  = trim((string) $current);
    $selected = ikp_opt_match($current, $options);

    if ($selected === NULL && $current !== '') {
        $options[] = array('v' => $current, 'label' => $current . ' (data lama)');
        $selected  = $current;
    }

    $html  = '<div class="ikp-field">';
    $html .= $f_label($label, $hint);
    $html .= '<div class="ikp-pills">';
    foreach ($options as $opt) {
        $value  = $opt['v'];
        $cls    = 'ikp-pill';
        if (isset($classes[$value]) && $classes[$value] !== '') {
            $cls .= ' ikp-pill--' . $classes[$value];
        }
        $active = ($selected !== NULL && $selected === $value);
        $html  .= '<label class="' . $cls . ($active ? ' is-active' : '') . '">';
        $html  .= '<input type="radio" name="' . $name . '" value="' . html_escape($value) . '"' . ($active ? ' checked' : '') . ' />';
        $html  .= '<span class="ikp-pill__dot"></span>';
        $html  .= html_escape(isset($opt['label']) ? $opt['label'] : $value);
        $html  .= '</label>';
    }
    $html .= '</div>';
    $html .= $f_error($name);
    $html .= '</div>';
    return $html;
};

$ikp_is_edit = ($button === 'Update' || (string) $id_ikp !== '');
$ikp_ulang_ya = (strtolower(trim((string) $kejadian_terulang)) === 'ya');
$ikp_cancel = isset($cancel_url) && $cancel_url !== '' ? $cancel_url : site_url('ikp');
?>
<link rel="stylesheet" href="<?= base_url('assets/css/ikp.css'); ?>">

<div class="ikp-page">

    <form action="<?= $action; ?>" method="post" class="ikp-form" id="ikpForm" autocomplete="off">

        <!-- ============================== HEADER ============================== -->
        <section class="ikp-hero ikp-hero--compact">
            <div class="ikp-hero__inner">
                <div>
                    <span class="ikp-eyebrow">
                        <i class="fas fa-notes-medical"></i> Formulir IKP
                    </span>
                    <h1 class="ikp-hero__title">
                        <?php echo $ikp_is_edit ? 'Ubah Data Insiden Keselamatan Pasien' : 'Entry Insiden Keselamatan Pasien'; ?>
                    </h1>
                    <p class="ikp-hero__subtitle">
                        <?php echo $ikp_is_edit
                            ? 'Perbarui data insiden #' . html_escape((string) $id_ikp) . ' — pastikan data terisi lengkap dan benar.'
                            : 'Lengkapi data insiden keselamatan pasien. Kolom bertanda * wajib diisi.'; ?>
                    </p>
                    <div class="ikp-chips">
                        <span class="ikp-chip"><i class="fas fa-shield-halved"></i> Keamanan Pasien</span>
                        <span class="ikp-chip"><i class="fas fa-clipboard-list"></i> <?php echo $ikp_is_edit ? 'Mode Ubah' : 'Mode Tambah'; ?></span>
                        <?php if ($ikp_is_edit): ?>
                            <span class="ikp-chip ikp-chip--accent"><i class="fas fa-hashtag"></i> ID: <?php echo html_escape((string) $id_ikp); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================== GRID ============================== -->
        <div class="ikp-grid">

            <!-- Data Pasien -->
            <section class="ikp-card">
                <div class="ikp-card__head">
                    <span class="ikp-card__icon"><i class="fas fa-user-injured"></i></span>
                    <div>
                        <h6 class="ikp-card__title">Data Pasien</h6>
                        <p class="ikp-card__desc">Identitas pasien dan informasi admisi</p>
                    </div>
                </div>
                <div class="ikp-card__body">
                    <div class="ikp-form-grid">
                        <?php
                        echo $f_text('nm_pasien', 'Nama Pasien', $nm_pasien, 'text', '', 'Nama lengkap pasien');
                        echo $f_text('rm', 'No. RM', $rm, 'text', '', 'Nomor rekam medis');
                        ?>
                    </div>
                    <div class="ikp-form-grid" style="margin-top:16px;">
                        <?php
                        echo $f_text('ruang', 'Ruangan', $ruang, 'text', 'Ruang perawatan pasien', 'Contoh: Ruang Melati 2');
                        echo $f_select('penangung_jawab', 'Penanggung Biaya', $penangung_jawab, $opt_penanggung, 'Pilih sumber biaya');
                        ?>
                    </div>
                    <div style="margin-top:16px;">
                        <?php
                        echo $f_pills('kelamin', 'Kelamin', $kelamin, $opt_kelamin, 'Pilih jenis kelamin pasien');
                        ?>
                    </div>
                    <div class="ikp-form-grid" style="margin-top:16px;">
                        <?php
                        echo $f_text('tgl_masuk', 'Tanggal Masuk RS', ikp_input_date($tgl_masuk), 'date');
                        echo $f_text('jam_masuk', 'Jam Masuk RS', ikp_input_time($jam_masuk), 'time');
                        ?>
                    </div>
                </div>
            </section>

            <!-- Identitas Insiden -->
            <section class="ikp-card">
                <div class="ikp-card__head">
                    <span class="ikp-card__icon"><i class="fas fa-tags"></i></span>
                    <div>
                        <h6 class="ikp-card__title">Identitas Insiden</h6>
                        <p class="ikp-card__desc">Jenis insiden, lokasi dan unit penyebab</p>
                    </div>
                </div>
                <div class="ikp-card__body">
                    <div class="ikp-form-grid ikp-form-grid--single">
                        <?php
                        echo $f_select('jns_insiden', 'Jenis Insiden', $jns_insiden, $opt_jns_insiden, 'Klasifikasi insiden');
                        echo $f_text('insiden', 'Nama / Jenis Insiden', $insiden, 'text', '', 'Contoh: Pipa Oksigen tersedak');
                        echo $f_text('tempat_insiden', 'Tempat Insiden', $tempat_insiden, 'text', 'Tempat pasien berada saat insiden terjadi');
                        ?>
                    </div>
                    <div class="ikp-form-grid" style="margin-top:16px;">
                        <?php
                        echo $f_select('insiden_terjadipd', 'Insiden Terjadi Pada', $insiden_terjadipd, $opt_terjadipd);
                        echo $f_select('insiden_meyangkut', 'Insiden Meyangkut', $insiden_meyangkut, $opt_meyangkut);
                        ?>
                    </div>
                    <div class="ikp-form-grid" style="margin-top:16px;">
                        <?php
                        echo $f_select('insiden_terjadipd2', 'Sesuai Kasus Penyakit', $insiden_terjadipd2, $opt_spesialisasi, 'Spesialisasi penyakit');
                        echo $f_text('unit_penyebab', 'Unit Penyebab', $unit_penyebab, 'text', '', 'Unit yang menyebabkan insiden');
                        ?>
                    </div>
                </div>
            </section>

            <!-- Kronologis -->
            <section class="ikp-card ikp-card--wide">
                <div class="ikp-card__head">
                    <span class="ikp-card__icon"><i class="fas fa-clock-rotate-left"></i></span>
                    <div>
                        <h6 class="ikp-card__title">Kronologis Kejadian</h6>
                        <p class="ikp-card__desc">Uraian kronologis insiden secara berurutan</p>
                    </div>
                </div>
                <div class="ikp-card__body">
                    <div class="ikp-form-grid">
                        <?php
                        echo $f_text('tgl_kejadian', 'Tanggal Kejadian', ikp_input_date($tgl_kejadian), 'date');
                        echo $f_text('jam_kejadian', 'Jam Kejadian', ikp_input_time($jam_kejadian), 'time');
                        ?>
                    </div>
                    <div style="margin-top:16px;">
                        <?php
                        echo $f_area('krologis', 'Kronologis', $krologis, 5, 'Tulis kronologi kejadian, tindakan yang telah dilakukan, dan kondisi pasien saat ini.');
                        ?>
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
                    <div class="ikp-form-grid ikp-form-grid--single">
                        <?php
                        echo $f_select('akibat_insiden', 'Akibat Insiden', $akibat_insiden, $opt_akibat);
                        echo $f_select('tindakan_oleh', 'Tindakan Dilakukan Oleh', $tindakan_oleh, $opt_tindakan_oleh);
                        echo $f_area('tindakan', 'Tindakan', $tindakan, 4, 'Tindakan yang telah dilakukan terhadap pasien.');
                        ?>
                    </div>
                </div>
            </section>

            <!-- Kejadian Berulang -->
            <section class="ikp-card">
                <div class="ikp-card__head">
                    <span class="ikp-card__icon"><i class="fas fa-repeat"></i></span>
                    <div>
                        <h6 class="ikp-card__title">Kejadian Berulang</h6>
                        <p class="ikp-card__desc">Apakah insiden serupa pernah terjadi di unit lain</p>
                    </div>
                </div>
                <div class="ikp-card__body">
                    <?php
                    echo $f_pills('kejadian_terulang', 'Kejadian Terulang', $kejadian_terulang, $opt_kejadian_terulang, 'Pilih Ya / Tidak');
                    ?>
                    <div id="wrapKetUlang" class="<?php echo $ikp_ulang_ya ? '' : 'ikp-hidden'; ?>" style="margin-top:16px;">
                        <?php
                        echo $f_area('ket_kejadian_terulang', 'Keterangan Kejadian Terulang', $ket_kejadian_terulang, 3, 'Wajib diisi bila kejadian terulang = Ya.');
                        ?>
                    </div>
                </div>
            </section>

            <!-- Data Pelaporan -->
            <section class="ikp-card ikp-card--wide">
                <div class="ikp-card__head">
                    <span class="ikp-card__icon"><i class="fas fa-clipboard-check"></i></span>
                    <div>
                        <h6 class="ikp-card__title">Data Pelaporan</h6>
                        <p class="ikp-card__desc">Pelapor, penerima laporan dan grading risiko</p>
                    </div>
                </div>
                <div class="ikp-card__body">
                    <div class="ikp-form-grid">
                        <?php
                        echo $f_select('pelapor_pertama', 'Pelapor Pertama', $pelapor_pertama, $opt_pelapor_pertama);
                        echo $f_text('pelapor', 'Pelapor', $pelapor, 'text', '', 'Nama pelapor');
                        ?>
                    </div>
                    <div class="ikp-form-grid" style="margin-top:16px;">
                        <?php
                        echo $f_text('penerima', 'Penerima Laporan', $penerima, 'text', '', 'Nama penerima laporan');
                        ?>
                    </div>
                    <div class="ikp-form-grid" style="margin-top:16px;">
                        <?php
                        echo $f_text('tgl_lapor', 'Tanggal Lapor', ikp_input_date($tgl_lapor), 'date');
                        echo $f_text('jam_lapor', 'Jam Lapor', ikp_input_time($jam_lapor), 'time');
                        ?>
                    </div>
                    <div style="margin-top:16px;">
                        <?php
                        echo $f_pills('grading_resiko', 'Grading Risiko', $grading_resiko, $opt_grading, 'Pilih tingkat risiko insiden', array(
                            'Merah'  => 'merah',
                            'Kuning' => 'kuning',
                            'Hijau'  => 'hijau',
                            'Biru'   => 'biru',
                        ));
                        ?>
                    </div>
                </div>
            </section>

        </div>

        <!-- =========================== SUBMIT BAR =========================== -->
        <div class="ikp-submitbar">
            <div class="ikp-submitbar__hint">
                <i class="fas fa-circle-info"></i>
                Pastikan seluruh kolom bertanda <span class="ikp-field__req">*</span> telah diisi sebelum disimpan.
            </div>
            <div class="ikp-submitbar__group">
                <a href="<?= $ikp_cancel; ?>" class="ikp-btn ikp-btn--ghost">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="ikp-btn ikp-btn--primary">
                    <i class="fas fa-save"></i> <?php echo $ikp_is_edit ? 'Simpan Perubahan' : 'Simpan Data'; ?>
                </button>
            </div>
        </div>

        <input type="hidden" name="id_ikp" value="<?php echo html_escape((string) $id_ikp); ?>" />
    </form>
</div>

<script>
(function () {
    // Samakan gaya pill dengan status radio tercentang
    function syncPills(scope) {
        (scope || document).querySelectorAll('.ikp-pill input[type="radio"]').forEach(function (input) {
            input.closest('.ikp-pill').classList.toggle('is-active', input.checked);
        });
    }

    document.addEventListener('change', function (e) {
        if (e.target.type === 'radio') {
            syncPills(e.target.closest('.ikp-pills') || document);
        }
    });

    syncPills(document);

    // Keterangan kejadian terulang hanya tampil bila dipilih "Ya"
    var wrap = document.getElementById('wrapKetUlang');
    var radios = document.querySelectorAll('input[name="kejadian_terulang"]');

    function toggleUlang() {
        var checked = document.querySelector('input[name="kejadian_terulang"]:checked');
        if (wrap) {
            wrap.classList.toggle('ikp-hidden', !checked || checked.value !== 'Ya');
        }
    }

    radios.forEach(function (r) { r.addEventListener('change', toggleUlang); });
    toggleUlang();
})();
</script>
