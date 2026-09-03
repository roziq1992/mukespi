<style>
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

    #message:empty{display:none;}
    #message{
        margin-bottom:14px;
    }
    #message .alert{
        border-radius:10px;
        padding:12px 14px;
        font-size:.85rem;
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

<div class="ikp-wrap">

    <div class="ikp-header">
        <h2>Laporan Insiden Keselamatan Pasien</h2>
        <p>RS Airlangga Jombang</p>
    </div>

    <div id="message">
        <?php
        $msg = $this->session->userdata('message');
        if ($msg <> '') {
            echo '<div class="alert alert-info">'.$msg.'</div>';
        }
        ?>
    </div>

    <form action="<?php echo $action; ?>" method="post" id="ikpForm">

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
                    <option <?php if($penangung_jawab=="ASKES Pemerintah") { echo "selected"; } ?> value="ASKES Pemerintah">ASKES Pemerintah</option>
                    <option <?php if($penangung_jawab=="Asuransi Swasta") { echo "selected"; } ?> value="Asuransi Swasta">Asuransi Swasta</option>
                    <option <?php if($penangung_jawab=="JAMKESMAS") { echo "selected"; } ?> value="JAMKESMAS">JAMKESMAS</option>
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
                    <option <?php if($jns_insiden=="Kejadian Tidak diharapkan / KTD (Adverse Event) / Kejadian Sentinel (Sentinel Event)") { echo "selected"; } ?> value="Kejadian Tidak diharapkan / KTD (Adverse Event) / Kejadian Sentinel (Sentinel Event)">KTD / Kejadian Sentinel</option>
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
                    <option <?php if($akibat_insiden=="Cedera Irreversibel/Cedera Berat Cedera Reversibel/Cedera Sedang Cedera Ringan") { echo "selected"; } ?> value="Cedera Irreversibel/Cedera Berat Cedera Reversibel/Cedera Sedang Cedera Ringan">Cedera (Berat/Sedang/Ringan)</option>
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

        <div class="ikp-actions">
            <a href="<?php echo site_url('ikp') ?>" class="ikp-btn ikp-btn-secondary">Batal</a>
            <button type="submit" class="ikp-btn ikp-btn-primary"><?php echo $button ?></button>
        </div>
    </form>
</div>