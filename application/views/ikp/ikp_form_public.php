<style>
    #content nav.navbar.navbar-expand.navbar-light.bg-white.topbar.mb-4.static-top.shadow{
        display:none !important;
    }

    :root{
        --ikp-primary:#0d6efd;
        --ikp-primary-dark:#0b5ed7;
        --ikp-bg:#f4f6fb;
        --ikp-card:#ffffff;
        --ikp-border:#e2e6ee;
        --ikp-text:#1f2430;
        --ikp-muted:#6b7280;
        --ikp-danger:#e5484d;
        --ikp-radius:14px;
    }

    .ikp-wrap{
        background:var(--ikp-bg);
        padding:16px;
        border-radius:var(--ikp-radius);
        font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
        color:var(--ikp-text);
    }

    .ikp-header{
        text-align:center;
        margin-bottom:18px;
        padding:20px 16px;
        background:linear-gradient(135deg,var(--ikp-primary),#4f8cff);
        border-radius:var(--ikp-radius);
        color:#fff;
        box-shadow:0 6px 18px rgba(13,110,253,.25);
    }
    .ikp-header h2{
        margin:0;
        font-size:1.15rem;
        font-weight:700;
        line-height:1.4;
    }
    .ikp-header p{
        margin:6px 0 0;
        font-size:.8rem;
        opacity:.9;
    }
    .ikp-banner-lapor-row{
        display:flex;
        align-items:center;
        justify-content:center;
        gap:12px;
    }
    .ikp-share-button{
        flex:0 0 44px;
        width:44px;
        height:44px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        border:0;
        border-radius:50%;
        background:#16824b;
        color:#fff;
        font-family:inherit;
        font-size:1rem;
        font-weight:700;
        cursor:pointer;
        box-shadow:0 4px 12px rgba(22,130,75,.2);
    }
    .ikp-share-button:hover,
    .ikp-share-button:focus{
        background:#11683c;
        color:#fff;
        outline:3px solid rgba(22,130,75,.18);
    }
    /* ===== BANNER AYO BERANI LAPOR ===== */
    .ikp-banner{
        background:#fff;
        border:1px solid var(--ikp-border);
        border-radius:var(--ikp-radius);
        padding:20px 14px 14px;
        margin-bottom:14px;
        box-shadow:0 2px 8px rgba(20,20,43,.05);
    }
    .ikp-banner-title{text-align:center;margin-bottom:14px;}
    .ikp-banner-title .t1{
        display:block;font-size:1.9rem;font-weight:900;
        color:#1e2a4a;line-height:1;letter-spacing:.01em;text-transform:uppercase;
    }
    .ikp-banner-title .t2{
        display:inline-block;font-size:3rem;font-weight:900;line-height:1.05;
        color:#1f7a4d;text-transform:uppercase;padding:0 10px 2px;
        background:linear-gradient(transparent 72%, #f0c94a 72%);
    }
    .ikp-banner-sub{
        text-align:center;font-size:.85rem;font-weight:600;
        color:#374151;margin:10px 0 16px;
    }
    .ikp-bn-card{
        display:flex;gap:12px;align-items:flex-start;width:100%;
        text-align:left;background:#f7f9fc;border:1.5px solid var(--ikp-border);
        border-radius:12px;padding:12px;margin-bottom:10px;cursor:pointer;
        font-family:inherit;color:var(--ikp-text);
        transition:border-color .15s ease, transform .1s ease, box-shadow .15s ease;
    }
    .ikp-bn-card:hover,.ikp-bn-card:focus{
        outline:none;border-color:var(--ikp-primary);
        box-shadow:0 0 0 3px rgba(13,110,253,.12);
    }
    .ikp-bn-card:active{transform:scale(.99);}
    .ikp-bn-num{
        flex:0 0 28px;height:28px;border-radius:50%;
        background:#1e2a4a;color:#fff;font-size:.8rem;font-weight:800;
        display:flex;align-items:center;justify-content:center;
    }
    .ikp-bn-card:nth-of-type(1) .ikp-bn-num,
    .ikp-bn-card:nth-of-type(2) .ikp-bn-num{background:#1f7a4d;}
    .ikp-bn-body{flex:1;min-width:0;display:block;}
    .ikp-bn-head{
        display:flex;align-items:center;gap:8px;
        font-size:.9rem;font-weight:800;text-transform:uppercase;
        color:#1e2a4a;margin-bottom:4px;
    }
    .ikp-bn-ico{
        flex:0 0 26px;height:26px;border-radius:50%;
        background:#f5c84b;color:#1e2a4a;font-size:.72rem;
        display:flex;align-items:center;justify-content:center;
    }
    .ikp-bn-desc{font-size:.8rem;line-height:1.5;color:#4b5563;margin:0;}
    .ikp-bn-ex{
        margin-top:8px;padding:6px 10px;border-radius:8px;
        background:#fbf3d0;font-size:.74rem;font-weight:700;color:#3a3320;
    }

    #message:empty{display:none;}
    #message{margin-bottom:14px;}
    .ikp-save-error{
        margin-bottom:14px;
        padding:14px 16px;
        border:1px solid #f1b8b5;
        border-left:5px solid #dc3545;
        border-radius:12px;
        background:#fff4f3;
        color:#842029;
        font-size:.82rem;
        line-height:1.5;
        box-shadow:0 8px 22px rgba(220,53,69,.08);
    }
    .ikp-success-notification{
        position:relative;
        display:flex;
        align-items:flex-start;
        gap:12px;
        margin-bottom:14px;
        padding:16px 16px 16px 14px;
        border:1px solid #b8e3d1;
        border-left:5px solid #198754;
        border-radius:12px;
        background:linear-gradient(135deg,#f0fbf6,#ffffff);
        box-shadow:0 8px 22px rgba(25,135,84,.12);
        animation:ikpSuccessIn .35s ease-out;
    }
    .ikp-success-icon{
        flex:0 0 42px;
        width:42px;
        height:42px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:50%;
        background:#198754;
        color:#fff;
        font-size:20px;
        box-shadow:0 5px 14px rgba(25,135,84,.28);
    }
    .ikp-success-content{flex:1;min-width:0;}
    .ikp-success-title{
        margin:0 0 4px;
        color:#146c43;
        font-size:.92rem;
        font-weight:800;
        line-height:1.35;
    }
    .ikp-success-message{
        margin:0;
        color:#3f5e50;
        font-size:.82rem;
        line-height:1.55;
    }
    @keyframes ikpSuccessIn{
        from{opacity:0;transform:translateY(-8px) scale(.985);}
        to{opacity:1;transform:translateY(0) scale(1);}
    }

    .ikp-section{
        background:var(--ikp-card);
        border-radius:var(--ikp-radius);
        padding:18px 16px;
        margin-bottom:14px;
        box-shadow:0 2px 8px rgba(20,20,43,.05);
        border:1px solid var(--ikp-border);
    }
    .ikp-section-title{
        font-size:.75rem;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.04em;
        color:var(--ikp-primary);
        margin:0 0 14px;
        display:flex;
        align-items:center;
        gap:8px;
    }
    .ikp-section-title .ikp-dot{
        width:8px;height:8px;border-radius:50%;
        background:var(--ikp-primary);
        display:inline-block;
    }

    .ikp-field{
        margin-bottom:16px;
    }
    .ikp-field:last-child{margin-bottom:0;}

    .ikp-field label{
        display:block;
        font-size:.82rem;
        font-weight:600;
        margin-bottom:6px;
        color:var(--ikp-text);
    }
    .ikp-field label .req{color:var(--ikp-danger);}
    .ikp-field .ikp-error{
        color:var(--ikp-danger);
        font-size:.75rem;
        margin-top:4px;
        display:block;
    }
    .ikp-field small.help{
        display:block;
        font-size:.72rem;
        color:var(--ikp-muted);
        margin-top:4px;
    }

    .ikp-input,
    .ikp-select,
    .ikp-textarea{
        width:100%;
        box-sizing:border-box;
        padding:12px 14px;
        font-size:1rem;
        border:1.5px solid var(--ikp-border);
        border-radius:10px;
        background:#fbfcfe;
        color:var(--ikp-text);
        transition:border-color .15s ease, box-shadow .15s ease;
        appearance:none;
        -webkit-appearance:none;
    }
    .ikp-select{
        background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='9' viewBox='0 0 14 9'><path d='M1 1l6 6 6-6' stroke='%236b7280' stroke-width='1.6' fill='none' fill-rule='evenodd' stroke-linecap='round' stroke-linejoin='round'/></svg>");
        background-repeat:no-repeat;
        background-position:right 14px center;
        padding-right:38px;
    }
    .ikp-textarea{resize:vertical;min-height:90px;font-family:inherit;}

    .ikp-input:focus,
    .ikp-select:focus,
    .ikp-textarea:focus{
        outline:none;
        border-color:var(--ikp-primary);
        box-shadow:0 0 0 3px rgba(13,110,253,.15);
        background:#fff;
    }

    .ikp-row2{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:12px;
    }
    @media (max-width:480px){
        .ikp-row2{grid-template-columns:1fr;}
    }

    .ikp-radio-group{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
    }
    .ikp-radio-pill{
        flex:1 1 auto;
        min-width:calc(50% - 4px);
    }
    .ikp-radio-pill input{
        position:absolute;
        opacity:0;
        pointer-events:none;
    }
    .ikp-radio-pill label{
        display:block;
        text-align:center;
        padding:10px 8px;
        font-size:.8rem;
        font-weight:600;
        border:1.5px solid var(--ikp-border);
        border-radius:10px;
        background:#fbfcfe;
        color:var(--ikp-text);
        cursor:pointer;
        margin:0;
        transition:all .15s ease;
    }
    .ikp-radio-pill input:checked + label{
        border-color:var(--ikp-primary);
        background:rgba(13,110,253,.08);
        color:var(--ikp-primary-dark);
    }

    .ikp-grading{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:8px;
    }
    @media (max-width:420px){
        .ikp-grading{grid-template-columns:repeat(2,1fr);}
    }
    .ikp-grading input{
        position:absolute;opacity:0;pointer-events:none;
    }
    .ikp-grading label{
        display:flex;
        align-items:center;
        justify-content:center;
        gap:6px;
        padding:10px 6px;
        border-radius:10px;
        border:1.5px solid var(--ikp-border);
        font-size:.78rem;
        font-weight:700;
        cursor:pointer;
        margin:0;
        color:#fff;
        opacity:.55;
        transition:opacity .15s ease, transform .1s ease;
    }
    .ikp-grading input:checked + label{opacity:1;transform:scale(1.03);}
    .ikp-grading .g-biru label{background:#3b82f6;}
    .ikp-grading .g-hijau label{background:#22c55e;}
    .ikp-grading .g-kuning label{background:#eab308;color:#3a2e00;}
    .ikp-grading .g-merah label{background:#ef4444;}

    #ket_kejadian_terulang_wrap{
        margin-top:10px;
        transition:max-height .2s ease;
    }

    .ikp-actions{
        position:sticky;
        bottom:0;
        display:flex;
        gap:10px;
        padding:12px 16px calc(12px + env(safe-area-inset-bottom));
        background:var(--ikp-bg);
        margin:0 -16px -16px;
        border-top:1px solid var(--ikp-border);
    }
    .ikp-btn{
        flex:1;
        text-align:center;
        padding:14px 16px;
        border-radius:12px;
        font-size:1rem;
        font-weight:700;
        border:none;
        cursor:pointer;
        text-decoration:none;
        display:inline-block;
    }
    .ikp-btn-primary{
        background:var(--ikp-primary);
        color:#fff;
        box-shadow:0 4px 14px rgba(13,110,253,.3);
    }
    .ikp-btn-primary:active{background:var(--ikp-primary-dark);}
    .ikp-btn-secondary{
        background:#fff;
        color:var(--ikp-text);
        border:1.5px solid var(--ikp-border);
    }
</style>

<?php
$judul_pelaporan = array(
    'ikp' => 'Form Pelaporan Insiden Keselamatan Pasien',
    'budaya_keselamatan' => 'Form Pelaporan Budaya Keselamatan',
    'pelanggaran' => 'Form Pelaporan Pelanggaran',
    'insiden_k3rs' => 'Form Investigasi Kecelakaan Kerja (K3RS)',
);

// Banner tampil & form disembunyikan hanya saat buka form baru
// (bukan mode edit, bukan setelah error validasi, bukan setelah sukses simpan)
$success_notification = $this->session->flashdata('success_notification');
$save_error = $this->session->flashdata('save_error');
$hide_form = empty($id_ikp)
    && !validation_errors()
    && empty($k3rs_errors)
    && !is_array($success_notification)
    && $save_error == '';
?>
<div class="ikp-wrap">

    <div class="ikp-header" id="ikpHeader" style="<?php echo $hide_form ? 'display:none;' : ''; ?>">
        <h2 id="report-form-title"><?php echo html_escape($judul_pelaporan[$jenis_pelaporan]); ?></h2>
        <p>RS Airlangga Jombang</p>
    </div>

    <div id="message">
        <?php
        if (is_array($success_notification)) {
            echo '<div class="ikp-success-notification" role="alert">';
            echo '<div class="ikp-success-icon"><i class="fas fa-check"></i></div>';
            echo '<div class="ikp-success-content">';
            echo '<p class="ikp-success-title">'.html_escape($success_notification['title']).'</p>';
            echo '<p class="ikp-success-message">'.html_escape($success_notification['message']).'</p>';
            echo '</div>';
            echo '</div>';
        } else {
            if ($save_error <> '') {
                echo '<div class="ikp-save-error" role="alert"><i class="fas fa-exclamation-circle"></i> '.html_escape($save_error).'</div>';
            }
            $msg = $this->session->flashdata('message');
            if ($msg <> '') {
                echo '<div class="alert alert-info">'.html_escape($msg).'</div>';
            }
        }
        ?>
    </div>

    <!-- BANNER AYO BERANI LAPOR -->
    <div class="ikp-banner" id="ikpBanner" style="<?php echo $hide_form ? '' : 'display:none;'; ?>">
        <div class="ikp-banner-title">
            <span class="t1">Ayo Berani</span>
            <div class="ikp-banner-lapor-row">
                <span class="t2">Lapor!</span>
                <button type="button" class="ikp-share-button" id="ikpShareButton" aria-label="Bagikan formulir pelaporan" title="Bagikan formulir pelaporan">
                    <i class="fas fa-share-alt" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <p class="ikp-banner-sub">Bersama membangun rumah sakit yang aman dan saling menghargai.</p>

        <button type="button" class="ikp-bn-card" data-type="budaya_keselamatan">
            <span class="ikp-bn-num">1</span>
            <span class="ikp-bn-body">
                <span class="ikp-bn-head"><span class="ikp-bn-ico"><i class="fas fa-shield-alt"></i></span>Budaya Keselamatan</span>
                <p class="ikp-bn-desc">Utamakan keselamatan, saling mengingatkan, dan berani menyampaikan kondisi yang berisiko. Jadikan laporan sebagai bahan belajar dan perbaikan bersama.</p>
            </span>
        </button>

        <button type="button" class="ikp-bn-card" data-type="ikp">
            <span class="ikp-bn-num">2</span>
            <span class="ikp-bn-body">
                <span class="ikp-bn-head"><span class="ikp-bn-ico"><i class="fas fa-heartbeat"></i></span>Insiden Keselamatan Pasien</span>
                <p class="ikp-bn-desc">Laporkan kejadian yang membahayakan atau berpotensi mencederai pasien, termasuk kejadian yang hampir terjadi.</p>
                <span class="ikp-bn-ex" style="display:block;">Pasien jatuh • Kesalahan obat • Kesalahan identifikasi</span>
            </span>
        </button>

        <button type="button" class="ikp-bn-card" data-type="insiden_k3rs">
            <span class="ikp-bn-num">3</span>
            <span class="ikp-bn-body">
                <span class="ikp-bn-head"><span class="ikp-bn-ico"><i class="fas fa-exclamation-triangle"></i></span>Insiden K3RS</span>
                <p class="ikp-bn-desc">Laporkan kecelakaan kerja, paparan berbahaya, kejadian hampir celaka, dan kondisi kerja yang tidak aman.</p>
                <span class="ikp-bn-ex" style="display:block;">Tertusuk jarum • Terpeleset • Paparan bahan kimia</span>
            </span>
        </button>

        <button type="button" class="ikp-bn-card" data-type="pelanggaran">
            <span class="ikp-bn-num">4</span>
            <span class="ikp-bn-body">
                <span class="ikp-bn-head"><span class="ikp-bn-ico"><i class="fas fa-comment-dots"></i></span>Pelaporan Pengaduan Pelanggaran</span>
                <p class="ikp-bn-desc">Laporkan dugaan pelanggaran yang dilihat, diketahui, atau dialami, seperti perundungan, intimidasi, diskriminasi, pelecehan seksual, serta perilaku tidak layak yang mengganggu pelayanan.</p>
            </span>
        </button>
    </div>

    <form action="<?php echo $action; ?>" method="post" id="ikpForm" style="<?php echo $hide_form ? 'display:none;' : ''; ?>">
        <div class="ikp-section">
            <div class="ikp-field">
                <label for="jenis_pelaporan">Jenis Pelaporan <span class="req">*</span></label>
                <select class="ikp-select" name="jenis_pelaporan" id="jenis_pelaporan" required>
                    <option value="ikp" data-form-title="Formulir Laporan Insiden Sub Komite KP PMKP" <?php echo $jenis_pelaporan === 'ikp' ? 'selected' : ''; ?>>Insiden Keselamatan Pasien (IKP)</option>
                    <option value="budaya_keselamatan" data-form-title="Pelaporan Budaya Keselamatan" <?php echo $jenis_pelaporan === 'budaya_keselamatan' ? 'selected' : ''; ?>>Budaya Keselamatan</option>
                    <option value="pelanggaran" data-form-title="Pelaporan Pengaduan Pelanggaran" <?php echo $jenis_pelaporan === 'pelanggaran' ? 'selected' : ''; ?>>Laporan Pelanggaran</option>
                    <option value="insiden_k3rs" data-form-title="Form Investigasi Kecelakaan Kerja (K3RS)" <?php echo $jenis_pelaporan === 'insiden_k3rs' ? 'selected' : ''; ?>>Investigasi Kecelakaan Kerja (K3RS)</option>
                </select>
                <?php echo form_error('jenis_pelaporan', '<span class="ikp-error">', '</span>') ?>
            </div>
        </div>

        <div id="ikp-report-fields">
        <!-- DATA PASIEN -->
        <div class="ikp-section">
            <p class="ikp-section-title"><span class="ikp-dot"></span>Data Pasien</p>

            <div class="ikp-field">
                <label for="nm_pasien">Nama Pasien <span class="req">*</span></label>
                <input type="text" class="ikp-input" name="nm_pasien" id="nm_pasien" placeholder="Nama lengkap pasien" value="<?php echo $nm_pasien; ?>" />
                <?php echo form_error('nm_pasien', '<span class="ikp-error">', '</span>') ?>
            </div>

            <div class="ikp-field">
                <label for="rm">No. Rekam Medis (RM) <span class="req">*</span></label>
                <input type="text" class="ikp-input" name="rm" id="rm" placeholder="No RM" value="<?php echo $rm; ?>" />
                <?php echo form_error('rm', '<span class="ikp-error">', '</span>') ?>
            </div>

            <div class="ikp-field">
                <label for="ruang">Ruangan <span class="req">*</span></label>
                <input type="text" class="ikp-input" name="ruang" id="ruang" placeholder="Nama ruangan" value="<?php echo $ruang; ?>" />
                <?php echo form_error('ruang', '<span class="ikp-error">', '</span>') ?>
            </div>

            <div class="ikp-row2">
                <div class="ikp-field">
                    <label for="umur">Umur</label>
                    <select class="ikp-select" id="umur" name="umur">
                        <option <?php if($kelamin=="0-1 bulan") { echo "selected"; } ?> value="0-1 bulan">0–1 bulan</option>
                        <option <?php if($kelamin=="> 1 bulan – 1 tahun") { echo "selected"; } ?> value="> 1 bulan – 1 tahun">&gt;1 bln – 1 thn</option>
                        <option <?php if($kelamin=="> 1 tahun – 5 tahun") { echo "selected"; } ?> value="> 1 tahun – 5 tahun">&gt;1 – 5 thn</option>
                        <option <?php if($kelamin=="> 5 tahun – 15 tahun") { echo "selected"; } ?> value="> 5 tahun – 15 tahun">&gt;5 – 15 thn</option>
                        <option <?php if($kelamin=="> 15 tahun – 30 tahun") { echo "selected"; } ?> value="> 15 tahun – 30 tahun">&gt;15 – 30 thn</option>
                        <option <?php if($kelamin=="> 30 tahun – 65 tahun > 65 tahun") { echo "selected"; } ?> value="> 30 tahun – 65 tahun > 65 tahun">&gt;30 thn</option>
                    </select>
                </div>

                <div class="ikp-field">
                    <label>Kelamin</label>
                    <div class="ikp-radio-group">
                        <div class="ikp-radio-pill">
                            <input type="radio" name="kelamin" id="kelamin_l" value="Laki-laki" <?php if($kelamin=="Laki-laki") echo "checked"; ?>>
                            <label for="kelamin_l">Laki-laki</label>
                        </div>
                        <div class="ikp-radio-pill">
                            <input type="radio" name="kelamin" id="kelamin_p" value="Perempuan" <?php if($kelamin=="Perempuan") echo "checked"; ?>>
                            <label for="kelamin_p">Perempuan</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ikp-field">
                <label for="penangung_jawab">Penanggung Biaya Pasien</label>
                <select class="ikp-select" id="penangung_jawab" name="penangung_jawab">
                    <option <?php if($penangung_jawab=="Pribadi/Umum") { echo "selected"; } ?> value="Pribadi/Umum">Pribadi/Umum</option>
                    <option <?php if($penangung_jawab=="BPJS KES") { echo "selected"; } ?> value="BPJS KES">BPJS KES</option>
                    <option <?php if($penangung_jawab=="BPJS NAKES") { echo "selected"; } ?> value="BPJS NAKES">BPJS NAKES</option>
                    <option <?php if($penangung_jawab=="Asuransi Swasta") { echo "selected"; } ?> value="Asuransi Swasta">Asuransi Swasta</option>
                    <option <?php if($penangung_jawab=="Perusahaan") { echo "selected"; } ?> value="Perusahaan">Perusahaan</option>
                </select>
            </div>

            <div class="ikp-row2">
                <div class="ikp-field">
                    <label for="tgl_masuk">Tanggal Masuk RS</label>
                    <input type="date" class="ikp-input" name="tgl_masuk" id="tgl_masuk" value="<?php echo $tgl_masuk; ?>" />
                    <?php echo form_error('tgl_masuk', '<span class="ikp-error">', '</span>') ?>
                </div>
                <div class="ikp-field">
                    <label for="jam_masuk">Jam Masuk RS</label>
                    <input type="time" class="ikp-input" name="jam_masuk" id="jam_masuk" value="<?php echo $jam_masuk; ?>" />
                    <?php echo form_error('jam_masuk', '<span class="ikp-error">', '</span>') ?>
                </div>
            </div>
        </div>

        <!-- DETAIL KEJADIAN -->
        <div class="ikp-section">
            <p class="ikp-section-title"><span class="ikp-dot"></span>Detail Kejadian Insiden</p>

            <div class="ikp-row2">
                <div class="ikp-field">
                    <label for="tgl_kejadian">Tanggal Kejadian <span class="req">*</span></label>
                    <input type="date" class="ikp-input" name="tgl_kejadian" id="tgl_kejadian" value="<?php echo $tgl_kejadian; ?>" />
                    <?php echo form_error('tgl_kejadian', '<span class="ikp-error">', '</span>') ?>
                </div>
                <div class="ikp-field">
                    <label for="jam_kejadian">Jam Kejadian <span class="req">*</span></label>
                    <input type="time" class="ikp-input" name="jam_kejadian" id="jam_kejadian" value="<?php echo $jam_kejadian; ?>" />
                    <?php echo form_error('jam_kejadian', '<span class="ikp-error">', '</span>') ?>
                </div>
            </div>

            <div class="ikp-field">
                <label for="insiden">Insiden <span class="req">*</span></label>
                <input type="text" class="ikp-input" name="insiden" id="insiden" placeholder="Nama/jenis insiden" value="<?php echo $insiden; ?>" />
                <?php echo form_error('insiden', '<span class="ikp-error">', '</span>') ?>
            </div>

            <div class="ikp-field">
                <label for="krologis">Kronologis <span class="req">*</span></label>
                <textarea class="ikp-textarea" rows="4" name="krologis" id="krologis" placeholder="Ceritakan kronologis kejadian secara singkat dan jelas"><?php echo $krologis; ?></textarea>
                <?php echo form_error('krologis', '<span class="ikp-error">', '</span>') ?>
            </div>

            <div class="ikp-field">
                <label for="jns_insiden">Jenis Insiden</label>
                <select class="ikp-select" id="jns_insiden" name="jns_insiden">
                    <option <?php if($jns_insiden=="Kejadian Nyaris Cedera / KNC (Near miss)") { echo "selected"; } ?> value="Kejadian Nyaris Cedera / KNC (Near miss)">KNC (Near miss)</option>
                    <option <?php if($jns_insiden=="Kejadian Tidak Cedera / KTC") { echo "selected"; } ?> value="Kejadian Tidak Cedera / KTC">Kejadian Tidak Cedera / KTC</option>
                    <option <?php if($jns_insiden=="Kejadian Potensian Cedera Spesifik / KPCS") { echo "selected"; } ?> value="Kejadian Potensian Cedera Spesifik / KPCS">Kejadian Potensian Cedera Spesifik / KPCS</option>
                    <option <?php if($jns_insiden=="Kejadian Tidak diharapkan / KTD (Adverse Event)") { echo "selected"; } ?> value="Kejadian Tidak diharapkan / KTD (Adverse Event)">Kejadian Tidak diharapkan / KTD (Adverse Event)</option>
                    <option <?php if($jns_insiden=="Kejadian Sentinel (Sentinel Event)") { echo "selected"; } ?> value="Kejadian Sentinel (Sentinel Event)">Kejadian Sentinel (Sentinel Event)</option>
                </select>
            </div>

            <div class="ikp-field">
                <label for="pelapor_pertama">Pelapor Pertama</label>
                <select class="ikp-select" id="pelapor_pertama" name="pelapor_pertama">
                    <option <?php if($pelapor_pertama=="Karyawan : Dokter / Perawat / Petugas lainnya") { echo "selected"; } ?> value="Karyawan : Dokter / Perawat / Petugas lainnya">Karyawan (Dokter/Perawat/Petugas)</option>
                    <option <?php if($pelapor_pertama=="Pasien") { echo "selected"; } ?> value="Pasien">Pasien</option>
                    <option <?php if($pelapor_pertama=="Keluarga/Pendamping pasien") { echo "selected"; } ?> value="Keluarga/Pendamping pasien">Keluarga/Pendamping Pasien</option>
                    <option <?php if($pelapor_pertama=="Pengunjung") { echo "selected"; } ?> value="Pengunjung">Pengunjung</option>
                    <option <?php if($pelapor_pertama=="Lain-lain") { echo "selected"; } ?> value="Lain-lain">Lain-lain</option>
                </select>
            </div>

            <div class="ikp-field">
                <label for="insiden_terjadipd">Insiden Terjadi Pada</label>
                <select class="ikp-select" id="insiden_terjadipd" name="insiden_terjadipd">
                    <option <?php if($insiden_terjadipd=="Pasien") { echo "selected"; } ?> value="Pasien">Pasien</option>
                    <option <?php if($insiden_terjadipd=="Lain-lain") { echo "selected"; } ?> value="Lain-lain">Lain-lain</option>
                </select>
            </div>

            <div class="ikp-field">
                <label for="insiden_meyangkut">Insiden Menyangkut</label>
                <select class="ikp-select" id="insiden_meyangkut" name="insiden_meyangkut">
                    <option <?php if($insiden_meyangkut=="Pasien rawat inap") { echo "selected"; } ?> value="Pasien rawat inap">Pasien Rawat Inap</option>
                    <option <?php if($insiden_meyangkut=="Pasien rawat jalan") { echo "selected"; } ?> value="Pasien rawat jalan">Pasien Rawat Jalan</option>
                    <option <?php if($insiden_meyangkut=="Pasien IGD") { echo "selected"; } ?> value="Pasien IGD">Pasien IGD</option>
                    <option <?php if($insiden_meyangkut=="Lain-lain") { echo "selected"; } ?> value="Lain-lain">Lain-lain</option>
                </select>
            </div>

            <div class="ikp-field">
                <label for="tempat_insiden">Tempat Insiden <span class="req">*</span></label>
                <input type="text" class="ikp-input" name="tempat_insiden" id="tempat_insiden" placeholder="Lokasi tempat pasien berada" value="<?php echo $tempat_insiden; ?>" />
                <small class="help">Tempat pasien berada saat insiden terjadi</small>
                <?php echo form_error('tempat_insiden', '<span class="ikp-error">', '</span>') ?>
            </div>

            <div class="ikp-field">
                <label for="insiden_terjadipd2">Insiden Terjadi Pada Pasien (spesialisasi)</label>
                <select class="ikp-select" id="insiden_terjadipd2" name="insiden_terjadipd2">
                    <option <?php if($insiden_terjadipd2=="Penyakit Dalam dan Subspesialisasinya") { echo "selected"; } ?> value="Penyakit Dalam dan Subspesialisasinya">Penyakit Dalam</option>
                    <option <?php if($insiden_terjadipd2=="Anak dan Subspesialisasinya") { echo "selected"; } ?> value="Anak dan Subspesialisasinya">Anak</option>
                    <option <?php if($insiden_terjadipd2=="Bedah dan Subspesialisasinya") { echo "selected"; } ?> value="Bedah dan Subspesialisasinya">Bedah</option>
                    <option <?php if($insiden_terjadipd2=="Obstetri Gynekologi dan Subspesialisasinya") { echo "selected"; } ?> value="Obstetri Gynekologi dan Subspesialisasinya">Obstetri Gynekologi</option>
                    <option <?php if($insiden_terjadipd2=="THT dan Subspesialisasinya") { echo "selected"; } ?> value="THT dan Subspesialisasinya">THT</option>
                    <option <?php if($insiden_terjadipd2=="Mata dan Subspesialisasinya") { echo "selected"; } ?> value="Mata dan Subspesialisasinya">Mata</option>
                    <option <?php if($insiden_terjadipd2=="Saraf dan Subspesialisasinya") { echo "selected"; } ?> value="Saraf dan Subspesialisasinya">Saraf</option>
                    <option <?php if($insiden_terjadipd2=="Anastesi dan Subspesialisasinya") { echo "selected"; } ?> value="Anastesi dan Subspesialisasinya">Anastesi</option>
                    <option <?php if($insiden_terjadipd2=="Jantung dan Subspesialisasinya") { echo "selected"; } ?> value="Jantung dan Subspesialisasinya">Jantung</option>
                    <option <?php if($insiden_terjadipd2=="Paru dan Subspesialisasinya") { echo "selected"; } ?> value="Paru dan Subspesialisasinya">Paru</option>
                    <option <?php if($insiden_terjadipd2=="Kulit & Kelamin dan Subspesialisasinya") { echo "selected"; } ?> value="Kulit & Kelamin dan Subspesialisasinya">Kulit &amp; Kelamin</option>
                    <option <?php if($insiden_terjadipd2=="Jiwa dan Subspesialisasinya") { echo "selected"; } ?> value="Jiwa dan Subspesialisasinya">Jiwa</option>
                    <option <?php if($insiden_terjadipd2=="Rehabilitasi Medik") { echo "selected"; } ?> value="Rehabilitasi Medik">Rehabilitasi Medik</option>
                    <option <?php if($insiden_terjadipd2=="Lain-lain") { echo "selected"; } ?> value="Lain-lain">Lain-lain</option>
                </select>
            </div>

            <div class="ikp-field">
                <label for="unit_penyebab">Unit Penyebab</label>
                <input type="text" class="ikp-input" name="unit_penyebab" id="unit_penyebab" placeholder="Unit penyebab insiden" value="<?php echo $unit_penyebab; ?>" />
            </div>

            <div class="ikp-field">
                <label for="akibat_insiden">Akibat Insiden</label>
                <select class="ikp-select" id="akibat_insiden" name="akibat_insiden">
                    <option <?php if($akibat_insiden=="Kematian") { echo "selected"; } ?> value="Kematian">Kematian</option>
                    <option <?php if($akibat_insiden=="Cedera Irreversibel/Cedera Berat Cedera Reversibel") { echo "selected"; } ?> value="Cedera Irreversibel/Cedera Berat Cedera Reversibel/Cedera Sedang Cedera Ringan">Cedera Irreversibel/Cedera Berat Cedera Reversibel</option>
                    <option <?php if($akibat_insiden=="Cedera Sedang") { echo "selected"; } ?> value="Cedera Sedang">Cedera Sedang</option>
                    <option <?php if($akibat_insiden=="Cedera Ringan") { echo "selected"; } ?> value="Cedera Ringan">Cedera Ringan</option>
                    <option <?php if($akibat_insiden=="Tidak ada cedera") { echo "selected"; } ?> value="Tidak ada cedera">Tidak Ada Cedera</option>
                </select>
            </div>
        </div>

        <!-- TINDAK LANJUT -->
        <div class="ikp-section">
            <p class="ikp-section-title"><span class="ikp-dot"></span>Tindak Lanjut</p>

            <div class="ikp-field">
                <label for="tindakan">Tindakan</label>
                <textarea class="ikp-textarea" rows="3" name="tindakan" id="tindakan" placeholder="Tindakan yang sudah dilakukan"><?php echo $tindakan; ?></textarea>
            </div>

            <div class="ikp-field">
                <label for="tindakan_oleh">Tindakan Dilakukan Oleh</label>
                <select class="ikp-select" id="tindakan_oleh" name="tindakan_oleh">
                    <option <?php if($tindakan_oleh=="Dokter") { echo "selected"; } ?> value="Dokter">Dokter</option>
                    <option <?php if($tindakan_oleh=="Perawat") { echo "selected"; } ?> value="Perawat">Perawat</option>
                    <option <?php if($tindakan_oleh=="Petugas lainnya") { echo "selected"; } ?> value="Petugas lainnya">Petugas Lainnya</option>
                </select>
            </div>

            <div class="ikp-field">
                <label>Apakah kejadian yang sama pernah terjadi di unit kerja lain?</label>
                <div class="ikp-radio-group">
                    <div class="ikp-radio-pill">
                        <input type="radio" name="kejadian_terulang" id="terulang_ya" value="Ya" <?php if($kejadian_terulang=="Ya") echo "checked"; ?> onclick="document.getElementById('ket_kejadian_terulang_wrap').style.display='block';">
                        <label for="terulang_ya">Ya</label>
                    </div>
                    <div class="ikp-radio-pill">
                        <input type="radio" name="kejadian_terulang" id="terulang_tidak" value="Tidak" <?php if($kejadian_terulang=="Tidak" || $kejadian_terulang=="") echo "checked"; ?> onclick="document.getElementById('ket_kejadian_terulang_wrap').style.display='none';">
                        <label for="terulang_tidak">Tidak</label>
                    </div>
                </div>

                <div id="ket_kejadian_terulang_wrap" style="<?php echo ($kejadian_terulang=="Ya") ? 'display:block;' : 'display:none;'; ?>">
                    <label for="ket_kejadian_terulang" style="margin-top:10px;">Keterangan Kejadian Terulang</label>
                    <textarea class="ikp-textarea" rows="3" name="ket_kejadian_terulang" id="ket_kejadian_terulang" placeholder="Jelaskan kejadian serupa sebelumnya"><?php echo $ket_kejadian_terulang; ?></textarea>
                </div>
            </div>
        </div>

        <!-- PELAPORAN -->
        <div class="ikp-section">
            <p class="ikp-section-title"><span class="ikp-dot"></span>Data Pelaporan</p>

            <div class="ikp-field">
                <label for="pelapor">Pelapor <span class="req">*</span></label>
                <input type="text" class="ikp-input" name="pelapor" id="pelapor" placeholder="Nama pelapor" value="<?php echo $pelapor; ?>" />
                <?php echo form_error('pelapor', '<span class="ikp-error">', '</span>') ?>
            </div>

            <div class="ikp-field">
                <label for="penerima">Penerima</label>
                <input type="text" class="ikp-input" name="penerima" id="penerima" placeholder="Nama penerima laporan" value="<?php echo $penerima; ?>" />
            </div>

            <div class="ikp-field">
                <label for="tgl_lapor">Tanggal Lapor</label>
                <input type="date" class="ikp-input" name="tgl_lapor" id="tgl_lapor" value="<?php echo $tgl_lapor; ?>" />
            </div>

            <div class="ikp-field">
                <label>Grading Resiko</label>
                <div class="ikp-grading">
                    <div class="g-biru">
                        <input type="radio" name="grading_resiko" id="g_biru" value="Biru" <?php if($grading_resiko=="Biru") echo "checked"; ?>>
                        <label for="g_biru">Biru</label>
                    </div>
                    <div class="g-hijau">
                        <input type="radio" name="grading_resiko" id="g_hijau" value="Hijau" <?php if($grading_resiko=="Hijau") echo "checked"; ?>>
                        <label for="g_hijau">Hijau</label>
                    </div>
                    <div class="g-kuning">
                        <input type="radio" name="grading_resiko" id="g_kuning" value="Kuning" <?php if($grading_resiko=="Kuning") echo "checked"; ?>>
                        <label for="g_kuning">Kuning</label>
                    </div>
                    <div class="g-merah">
                        <input type="radio" name="grading_resiko" id="g_merah" value="Merah" <?php if($grading_resiko=="Merah") echo "checked"; ?>>
                        <label for="g_merah">Merah</label>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="id_ikp" value="<?php echo $id_ikp; ?>" />
        </div>

        <div id="budaya-report-fields" style="<?php echo $jenis_pelaporan === 'budaya_keselamatan' ? '' : 'display:none;'; ?>">
            <div class="ikp-section">
                <p class="ikp-section-title"><span class="ikp-dot"></span>Pelaporan Budaya Keselamatan</p>
                <div class="ikp-field">
                    <label for="nama_pelapor_budaya">Nama <span class="req">*</span></label>
                    <input type="text" class="ikp-input" name="nama_pelapor_budaya" id="nama_pelapor_budaya" maxlength="255" required value="<?php echo html_escape($nama_pelapor_budaya); ?>" />
                    <?php echo form_error('nama_pelapor_budaya', '<span class="ikp-error">', '</span>') ?>
                </div>
                <div class="ikp-field">
                    <label for="tanggal_kejadian_budaya">Tanggal Kejadian <span class="req">*</span></label>
                    <input type="date" class="ikp-input" name="tanggal_kejadian_budaya" id="tanggal_kejadian_budaya" required value="<?php echo html_escape($tanggal_kejadian_budaya); ?>" />
                    <?php echo form_error('tanggal_kejadian_budaya', '<span class="ikp-error">', '</span>') ?>
                </div>
                <div class="ikp-field">
                    <label for="lokasi_budaya">Lokasi <span class="req">*</span></label>
                    <input type="text" class="ikp-input" name="lokasi_budaya" id="lokasi_budaya" maxlength="255" required value="<?php echo html_escape($lokasi_budaya); ?>" />
                    <?php echo form_error('lokasi_budaya', '<span class="ikp-error">', '</span>') ?>
                </div>
                <div class="ikp-field">
                    <label for="terlibat_budaya">Nama Staf Terlibat / Pengunjung <span class="req">*</span></label>
                    <input type="text" class="ikp-input" name="terlibat_budaya" id="terlibat_budaya" maxlength="1000" required value="<?php echo html_escape($terlibat_budaya); ?>" />
                    <?php echo form_error('terlibat_budaya', '<span class="ikp-error">', '</span>') ?>
                </div>
                <div class="ikp-field">
                    <label for="uraian_budaya">Uraian Kejadian <span class="req">*</span></label>
                    <textarea class="ikp-textarea" rows="5" name="uraian_budaya" id="uraian_budaya" maxlength="10000" required><?php echo html_escape($uraian_budaya); ?></textarea>
                    <?php echo form_error('uraian_budaya', '<span class="ikp-error">', '</span>') ?>
                </div>
            </div>
        </div>

        <div id="pelanggaran-report-fields" style="<?php echo $jenis_pelaporan === 'pelanggaran' ? '' : 'display:none;'; ?>">
            <div class="ikp-section">
                <p class="ikp-section-title"><span class="ikp-dot"></span>Laporan Pelanggaran</p>
                <div class="ikp-field">
                    <label for="nama_pelapor_pelanggaran">Nama <span class="req">*</span></label>
                    <input type="text" class="ikp-input" name="nama_pelapor_pelanggaran" id="nama_pelapor_pelanggaran" maxlength="255" required value="<?php echo html_escape($nama_pelapor_pelanggaran); ?>" />
                    <?php echo form_error('nama_pelapor_pelanggaran', '<span class="ikp-error">', '</span>') ?>
                </div>
                <div class="ikp-field">
                    <label for="tanggal_kejadian_pelanggaran">Tanggal Kejadian <span class="req">*</span></label>
                    <input type="date" class="ikp-input" name="tanggal_kejadian_pelanggaran" id="tanggal_kejadian_pelanggaran" required value="<?php echo html_escape($tanggal_kejadian_pelanggaran); ?>" />
                    <?php echo form_error('tanggal_kejadian_pelanggaran', '<span class="ikp-error">', '</span>') ?>
                </div>
                <div class="ikp-field">
                    <label for="lokasi_pelanggaran">Lokasi <span class="req">*</span></label>
                    <input type="text" class="ikp-input" name="lokasi_pelanggaran" id="lokasi_pelanggaran" maxlength="255" required value="<?php echo html_escape($lokasi_pelanggaran); ?>" />
                    <?php echo form_error('lokasi_pelanggaran', '<span class="ikp-error">', '</span>') ?>
                </div>
                <div class="ikp-field">
                    <label for="staf_terlibat_pelanggaran">Nama Staf Terlibat <span class="req">*</span></label>
                    <input type="text" class="ikp-input" name="staf_terlibat_pelanggaran" id="staf_terlibat_pelanggaran" maxlength="1000" required value="<?php echo html_escape($staf_terlibat_pelanggaran); ?>" />
                    <?php echo form_error('staf_terlibat_pelanggaran', '<span class="ikp-error">', '</span>') ?>
                </div>
                <div class="ikp-field">
                    <label for="uraian_pelanggaran">Uraian Kejadian <span class="req">*</span></label>
                    <textarea class="ikp-textarea" rows="5" name="uraian_pelanggaran" id="uraian_pelanggaran" maxlength="10000" required><?php echo html_escape($uraian_pelanggaran); ?></textarea>
                    <?php echo form_error('uraian_pelanggaran', '<span class="ikp-error">', '</span>') ?>
                </div>
            </div>
            <div class="alert alert-success" role="note">
                <strong>Saluran aman:</strong> Kerahasiaan dijamin dan pelapor dilindungi dari pembalasan.
            </div>
        </div>

        <div id="k3rs-report-fields" style="<?php echo $jenis_pelaporan === 'insiden_k3rs' ? '' : 'display:none;'; ?>">
            <?php if (!empty($k3rs_errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($k3rs_errors as $error): ?>
                        <div><?php echo html_escape($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="ikp-section">
                <p class="ikp-section-title"><span class="ikp-dot"></span>A. Insiden</p>
                <div class="ikp-row2">
                    <div class="ikp-field">
                        <label for="tanggal_k3rs">Tanggal <span class="req">*</span></label>
                        <input type="date" class="ikp-input" name="tanggal_k3rs" id="tanggal_k3rs" required value="<?php echo html_escape(set_value('tanggal_k3rs')); ?>" />
                        <?php echo form_error('tanggal_k3rs', '<span class="ikp-error">', '</span>') ?>
                    </div>
                    <div class="ikp-field">
                        <label for="waktu_k3rs">Waktu <span class="req">*</span></label>
                        <input type="time" class="ikp-input" name="waktu_k3rs" id="waktu_k3rs" required value="<?php echo html_escape(set_value('waktu_k3rs')); ?>" />
                        <?php echo form_error('waktu_k3rs', '<span class="ikp-error">', '</span>') ?>
                    </div>
                </div>
                <div class="ikp-row2">
                    <div class="ikp-field">
                        <label for="pekerjaan_k3rs">Pekerjaan <span class="req">*</span></label>
                        <input type="text" class="ikp-input" name="pekerjaan_k3rs" id="pekerjaan_k3rs" maxlength="255" required value="<?php echo html_escape(set_value('pekerjaan_k3rs')); ?>" />
                        <?php echo form_error('pekerjaan_k3rs', '<span class="ikp-error">', '</span>') ?>
                    </div>
                    <div class="ikp-field">
                        <label for="lokasi_k3rs">Lokasi <span class="req">*</span></label>
                        <input type="text" class="ikp-input" name="lokasi_k3rs" id="lokasi_k3rs" maxlength="255" required value="<?php echo html_escape(set_value('lokasi_k3rs')); ?>" />
                        <?php echo form_error('lokasi_k3rs', '<span class="ikp-error">', '</span>') ?>
                    </div>
                </div>
                <div class="ikp-field">
                    <label>Peralatan Kerja</label>
                    <div class="ikp-radio-group">
                        <?php foreach (array('Mesin', 'Material', 'Alat Berat','Non Material') as $equipment): ?>
                            <label class="ikp-checkbox"><input type="checkbox" name="peralatan_k3rs[]" value="<?php echo html_escape($equipment); ?>" <?php echo in_array($equipment, $k3rs_equipment, TRUE) ? 'checked' : ''; ?> /> <?php echo html_escape($equipment); ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="ikp-row2">
                    <div class="ikp-field">
                        <label for="kerugian_aset_k3rs">Kerugian Aset/Material</label>
                        <textarea class="ikp-textarea" rows="3" name="kerugian_aset_k3rs" id="kerugian_aset_k3rs" maxlength="2000"><?php echo html_escape(set_value('kerugian_aset_k3rs')); ?></textarea>
                    </div>
                    <div class="ikp-field">
                        <label for="kerugian_lingkungan_k3rs">Kerugian Lingkungan (jika ada)</label>
                        <textarea class="ikp-textarea" rows="3" name="kerugian_lingkungan_k3rs" id="kerugian_lingkungan_k3rs" maxlength="2000"><?php echo html_escape(set_value('kerugian_lingkungan_k3rs')); ?></textarea>
                    </div>
                </div>
                <div class="ikp-field">
                    <label for="kronologi_k3rs">Kronologi <span class="req">*</span></label>
                    <textarea class="ikp-textarea" rows="5" name="kronologi_k3rs" id="kronologi_k3rs" maxlength="10000" required><?php echo html_escape(set_value('kronologi_k3rs')); ?></textarea>
                    <?php echo form_error('kronologi_k3rs', '<span class="ikp-error">', '</span>') ?>
                </div>
            </div>

            <div class="ikp-section">
                <p class="ikp-section-title"><span class="ikp-dot"></span>B. Korban</p>
                <div class="table-responsive">
                    <table class="table table-bordered k3rs-table">
                        <thead><tr><th>Nama</th><th>L/P</th><th>Usia</th><th>Jabatan</th><th>Bagian</th><th>Cedera</th><th>Penanganan</th><th>LT (hari)</th><th>Kategori</th><th></th></tr></thead>
                        <tbody id="k3rs-korban-rows">
                            <?php foreach ($korban_rows as $i => $row): ?>
                                <?php $row = is_array($row) ? $row : array(); ?>
                                <tr>
                                    <td><input class="ikp-input" name="korban[<?php echo (int) $i; ?>][nama]" maxlength="255" value="<?php echo html_escape(isset($row['nama']) ? $row['nama'] : ''); ?>" /></td>
                                    <td><select class="ikp-select" name="korban[<?php echo (int) $i; ?>][kelamin]"><option value="">-</option><option value="L" <?php echo isset($row['kelamin']) && $row['kelamin'] === 'L' ? 'selected' : ''; ?>>L</option><option value="P" <?php echo isset($row['kelamin']) && $row['kelamin'] === 'P' ? 'selected' : ''; ?>>P</option></select></td>
                                    <td><input class="ikp-input" name="korban[<?php echo (int) $i; ?>][usia]" maxlength="30" value="<?php echo html_escape(isset($row['usia']) ? $row['usia'] : ''); ?>" /></td>
                                    <td><input class="ikp-input" name="korban[<?php echo (int) $i; ?>][jabatan]" maxlength="255" value="<?php echo html_escape(isset($row['jabatan']) ? $row['jabatan'] : ''); ?>" /></td>
                                    <td><input class="ikp-input" name="korban[<?php echo (int) $i; ?>][bagian]" maxlength="255" value="<?php echo html_escape(isset($row['bagian']) ? $row['bagian'] : ''); ?>" /></td>
                                    <td><input class="ikp-input" name="korban[<?php echo (int) $i; ?>][cedera]" maxlength="1000" value="<?php echo html_escape(isset($row['cedera']) ? $row['cedera'] : ''); ?>" /></td>
                                    <td><input class="ikp-input" name="korban[<?php echo (int) $i; ?>][penanganan]" maxlength="1000" value="<?php echo html_escape(isset($row['penanganan']) ? $row['penanganan'] : ''); ?>" /></td>
                                    <td><input type="number" min="0" max="3650" class="ikp-input" name="korban[<?php echo (int) $i; ?>][lt]" value="<?php echo html_escape(isset($row['lt']) ? $row['lt'] : ''); ?>" /></td>
                                    <td><select class="ikp-select" name="korban[<?php echo (int) $i; ?>][kategori]"><option value="">-</option><?php foreach (array('Ringan', 'Sedang', 'Berat', 'Fatal') as $category): ?><option value="<?php echo $category; ?>" <?php echo isset($row['kategori']) && $row['kategori'] === $category ? 'selected' : ''; ?>><?php echo $category; ?></option><?php endforeach; ?></select></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger k3rs-remove-row" aria-label="Hapus korban">&times;</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="k3rs-add-korban">Tambah Korban</button>
                <small class="help">LT adalah jumlah hari kerja hilang lebih dari 1 × 24 jam. Kategori: Ringan, Sedang, Berat, atau Fatal.</small>
            </div>

            <div class="ikp-section">
                <p class="ikp-section-title"><span class="ikp-dot"></span>C. Investigasi Kecelakaan</p>
                <div class="ikp-row2">
                    <div class="ikp-field">
                        <label for="penyebab_langsung_k3rs">Penyebab Langsung</label>
                        <textarea class="ikp-textarea" rows="3" name="penyebab_langsung_k3rs" id="penyebab_langsung_k3rs" maxlength="5000"><?php echo html_escape(set_value('penyebab_langsung_k3rs')); ?></textarea>
                    </div>
                    <div class="ikp-field">
                        <label for="penyebab_tidak_langsung_k3rs">Penyebab Tidak Langsung</label>
                        <textarea class="ikp-textarea" rows="3" name="penyebab_tidak_langsung_k3rs" id="penyebab_tidak_langsung_k3rs" maxlength="5000"><?php echo html_escape(set_value('penyebab_tidak_langsung_k3rs')); ?></textarea>
                    </div>
                </div>
                <div class="ikp-field">
                    <label>Kategori penyebab langsung</label>
                    <div class="ikp-radio-group">
                        <?php foreach (array('Kondisi Bahaya', 'Tindakan Bahaya', 'Pribadi', 'Pekerjaan') as $factor): ?>
                            <label class="ikp-checkbox"><input type="checkbox" name="faktor_k3rs[]" value="<?php echo html_escape($factor); ?>" <?php echo in_array($factor, $k3rs_factors, TRUE) ? 'checked' : ''; ?> /> <?php echo html_escape($factor); ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="ikp-field">
                    <label>Penyebab Dasar</label>
                    <div class="ikp-radio-group">
                        <?php foreach (array('Kurang Prosedur', 'Kurang Sarana', 'Kurang Taat') as $cause): ?>
                            <label class="ikp-checkbox"><input type="checkbox" name="dasar_k3rs[]" value="<?php echo html_escape($cause); ?>" <?php echo in_array($cause, $k3rs_root_causes, TRUE) ? 'checked' : ''; ?> /> <?php echo html_escape($cause); ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="ikp-section">
                <p class="ikp-section-title"><span class="ikp-dot"></span>D. Perbaikan &amp; Pencegahan</p>
                <div class="table-responsive">
                    <table class="table table-bordered k3rs-table">
                        <thead><tr><th>Jenis Tindakan</th><th>Rencana Tindakan</th><th>Target (tanggal)</th><th>Wewenang (bagian/petugas)</th><th></th></tr></thead>
                        <tbody id="k3rs-tindakan-rows">
                            <?php foreach ($tindakan_rows as $i => $row): ?>
                                <?php $row = is_array($row) ? $row : array(); ?>
                                <tr>
                                    <td><select class="ikp-select" name="tindakan_k3rs[<?php echo (int) $i; ?>][jenis]"><option value="">Pilih</option><option value="Perbaikan" <?php echo isset($row['jenis']) && $row['jenis'] === 'Perbaikan' ? 'selected' : ''; ?>>Perbaikan</option><option value="Pencegahan" <?php echo isset($row['jenis']) && $row['jenis'] === 'Pencegahan' ? 'selected' : ''; ?>>Pencegahan</option></select></td>
                                    <td><input class="ikp-input" name="tindakan_k3rs[<?php echo (int) $i; ?>][rencana]" maxlength="1000" value="<?php echo html_escape(isset($row['rencana']) ? $row['rencana'] : ''); ?>" /></td>
                                    <td><input type="date" class="ikp-input" name="tindakan_k3rs[<?php echo (int) $i; ?>][target]" value="<?php echo html_escape(isset($row['target']) ? $row['target'] : ''); ?>" /></td>
                                    <td><input class="ikp-input" name="tindakan_k3rs[<?php echo (int) $i; ?>][wewenang]" maxlength="255" value="<?php echo html_escape(isset($row['wewenang']) ? $row['wewenang'] : ''); ?>" /></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger k3rs-remove-row" aria-label="Hapus tindakan">&times;</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="k3rs-add-tindakan">Tambah Tindakan</button>
                <small class="help">Isi jenis tindakan sebagai Perbaikan/Pencegahan dan target dengan tanggal pemenuhan.</small>
            </div>

            <div class="ikp-section">
                <p class="ikp-section-title"><span class="ikp-dot"></span>Saksi dan Pengesahan</p>
                <div class="ikp-row2">
                    <div class="ikp-field">
                        <label for="saksi_k3rs">Nama Saksi</label>
                        <input type="text" class="ikp-input" name="saksi_k3rs" id="saksi_k3rs" maxlength="255" value="<?php echo html_escape(set_value('saksi_k3rs')); ?>" />
                    </div>
                    <div class="ikp-field">
                        <label for="ketua_tim_k3rs">Ketua Tim K3RS</label>
                        <input type="text" class="ikp-input" name="ketua_tim_k3rs" id="ketua_tim_k3rs" maxlength="255" value="<?php echo html_escape(set_value('ketua_tim_k3rs')); ?>" />
                    </div>
                </div>
                <div class="ikp-field">
                    <label for="tanggal_disusun_k3rs">Tanggal Disusun</label>
                    <input type="date" class="ikp-input" name="tanggal_disusun_k3rs" id="tanggal_disusun_k3rs" value="<?php echo html_escape(set_value('tanggal_disusun_k3rs')); ?>" />
                    <?php echo form_error('tanggal_disusun_k3rs', '<span class="ikp-error">', '</span>') ?>
                </div>
                <small class="help">Mengetahui: Direktur Rumah Sakit Airlangga Jombang.</small>
            </div>
        </div>

        <div class="ikp-actions">
            <a href="<?php echo site_url('ikp') ?>" class="ikp-btn ikp-btn-secondary">Batal</a>
            <button type="submit" class="ikp-btn ikp-btn-primary"><?php echo $button ?></button>
        </div>
    </form>
</div>
<script>
(function () {
    var type = document.getElementById('jenis_pelaporan');
    var title = document.getElementById('report-form-title');
    var ikpFields = document.getElementById('ikp-report-fields');
    var cultureFields = document.getElementById('budaya-report-fields');
    var violationFields = document.getElementById('pelanggaran-report-fields');
    var k3rsFields = document.getElementById('k3rs-report-fields');
    if (!type || !ikpFields || !cultureFields || !violationFields || !k3rsFields) return;

    function updateReportType() {
        var selectedOption = type.options[type.selectedIndex];
        if (title && selectedOption) {
            title.textContent = selectedOption.getAttribute('data-form-title') || 'Form Pelaporan Kejadian';
        }
        var isCulture = type.value === 'budaya_keselamatan';
        var isViolation = type.value === 'pelanggaran';
        var isK3rs = type.value === 'insiden_k3rs';
        ikpFields.style.display = (isCulture || isViolation || isK3rs) ? 'none' : '';
        cultureFields.style.display = isCulture ? '' : 'none';
        violationFields.style.display = isViolation ? '' : 'none';
        k3rsFields.style.display = isK3rs ? '' : 'none';
        Array.prototype.forEach.call(ikpFields.querySelectorAll('input, select, textarea'), function (field) {
            field.disabled = isCulture || isViolation || isK3rs;
        });
        Array.prototype.forEach.call(cultureFields.querySelectorAll('input, select, textarea'), function (field) {
            field.disabled = !isCulture;
        });
        Array.prototype.forEach.call(violationFields.querySelectorAll('input, select, textarea'), function (field) {
            field.disabled = !isViolation;
        });
        Array.prototype.forEach.call(k3rsFields.querySelectorAll('input, select, textarea'), function (field) {
            field.disabled = !isK3rs;
        });
    }

    type.addEventListener('change', updateReportType);
    updateReportType();

    function addRow(tbodyId, buttonId) {
        var tbody = document.getElementById(tbodyId);
        var button = document.getElementById(buttonId);
        if (!tbody || !button) return;
        button.addEventListener('click', function () {
            if (tbody.rows.length >= 20) return;
            var row = tbody.rows[0].cloneNode(true);
            var index = Date.now();
            Array.prototype.forEach.call(row.querySelectorAll('input, select'), function (field) {
                field.name = field.name.replace(/\[\d+\]/, '[' + index + ']');
                field.id = '';
                if (field.type === 'checkbox' || field.type === 'radio') field.checked = false;
                else field.value = '';
            });
            tbody.appendChild(row);
        });
        tbody.addEventListener('click', function (event) {
            if (event.target.classList.contains('k3rs-remove-row') && tbody.rows.length > 1) {
                event.target.closest('tr').remove();
            }
        });
    }

    addRow('k3rs-korban-rows', 'k3rs-add-korban');
    addRow('k3rs-tindakan-rows', 'k3rs-add-tindakan');
}());

// ===== Bagikan tautan formulir =====
(function () {
    var button = document.getElementById('ikpShareButton');
    if (!button) return;

    var shareData = {
        title: 'Ayo Berani Lapor - RS Airlangga',
        text: 'Sampaikan laporan insiden, budaya keselamatan, atau pelanggaran melalui formulir resmi RS Airlangga:',
        url: window.location.origin + window.location.pathname
    };

    function openWhatsApp() {
        var message = shareData.text + ' ' + shareData.url;
        window.open('https://wa.me/?text=' + encodeURIComponent(message), '_blank', 'noopener,noreferrer');
    }

    button.addEventListener('click', function () {
        if (navigator.share) {
            navigator.share(shareData).catch(function (error) {
                if (error.name !== 'AbortError') openWhatsApp();
            });
            return;
        }
        openWhatsApp();
    });
}());

// ===== Banner "Ayo Berani Lapor": klik kartu -> pilih jenis laporan, tampilkan form =====
(function () {
    var type = document.getElementById('jenis_pelaporan');
    var form = document.getElementById('ikpForm');
    var banner = document.getElementById('ikpBanner');
    var header = document.getElementById('ikpHeader');
    var cards = document.querySelectorAll('.ikp-bn-card');
    if (!type || !form || !banner) return;

    Array.prototype.forEach.call(cards, function (card) {
        card.addEventListener('click', function () {
            type.value = card.getAttribute('data-type');
            type.dispatchEvent(new Event('change'));   // update judul + field sesuai jenis
            banner.style.display = 'none';
            if (header) header.style.display = '';
            form.style.display = '';
            (header || form).scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
}());
</script>