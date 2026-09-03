<style>
    :root{
        --asp-primary:#0d6efd;
        --asp-primary-dark:#0b5ed7;
        --asp-bg:#f4f6fb;
        --asp-card:#ffffff;
        --asp-border:#e2e6ee;
        --asp-text:#1f2430;
        --asp-muted:#6b7280;
        --asp-danger:#e5484d;
        --asp-radius:14px;
    }
    .asp-wrap{
        background:var(--asp-bg);
        padding:16px;
        border-radius:var(--asp-radius);
        font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
        color:var(--asp-text);
    }
    .asp-header{
        text-align:center;
        margin-bottom:18px;
        padding:20px 16px;
        background:linear-gradient(135deg,var(--asp-primary),#4f8cff);
        border-radius:var(--asp-radius);
        color:#fff;
        box-shadow:0 6px 18px rgba(13,110,253,.25);
    }
    .asp-header h2{margin:0;font-size:1.15rem;font-weight:700;line-height:1.4;}
    .asp-header p{margin:6px 0 0;font-size:.8rem;opacity:.9;}

    #message:empty{display:none;}
    #message{margin-bottom:14px;}
    #message .alert{border-radius:10px;padding:12px 14px;font-size:.85rem;}

    .asp-section{
        background:var(--asp-card);
        border-radius:var(--asp-radius);
        padding:18px 16px;
        margin-bottom:14px;
        box-shadow:0 2px 8px rgba(20,20,43,.05);
        border:1px solid var(--asp-border);
    }
    .asp-section-title{
        font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;
        color:var(--asp-primary);margin:0 0 14px;display:flex;align-items:center;gap:8px;
    }
    .asp-section-title .asp-dot{width:8px;height:8px;border-radius:50%;background:var(--asp-primary);display:inline-block;}
    .asp-section-note{font-size:.72rem;color:var(--asp-muted);margin:-8px 0 14px;}

    .asp-field{margin-bottom:16px;}
    .asp-field:last-child{margin-bottom:0;}
    .asp-field label{display:block;font-size:.82rem;font-weight:600;margin-bottom:6px;color:var(--asp-text);}
    .asp-field label .req{color:var(--asp-danger);}
    .asp-field .asp-error{color:var(--asp-danger);font-size:.75rem;margin-top:4px;display:block;}

    .asp-input,.asp-select,.asp-textarea{
        width:100%;box-sizing:border-box;padding:12px 14px;font-size:1rem;
        border:1.5px solid var(--asp-border);border-radius:10px;background:#fbfcfe;
        color:var(--asp-text);transition:border-color .15s ease, box-shadow .15s ease;
        appearance:none;-webkit-appearance:none;font-family:inherit;
    }
    .asp-select{
        background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='9' viewBox='0 0 14 9'><path d='M1 1l6 6 6-6' stroke='%236b7280' stroke-width='1.6' fill='none' fill-rule='evenodd' stroke-linecap='round' stroke-linejoin='round'/></svg>");
        background-repeat:no-repeat;background-position:right 14px center;padding-right:38px;
    }
    .asp-textarea{resize:vertical;min-height:70px;}
    .asp-input:focus,.asp-select:focus,.asp-textarea:focus{
        outline:none;border-color:var(--asp-primary);box-shadow:0 0 0 3px rgba(13,110,253,.15);background:#fff;
    }

    .asp-row2{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
    .asp-row3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;}
    @media (max-width:560px){
        .asp-row2{grid-template-columns:1fr;}
        .asp-row3{grid-template-columns:1fr;}
    }

    .asp-zona-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;}
    @media (max-width:480px){.asp-zona-grid{grid-template-columns:1fr;}}
    .asp-zona-label{font-size:.7rem;font-weight:700;color:var(--asp-muted);text-transform:uppercase;margin-bottom:4px;display:block;}

    .asp-actions{
        position:sticky;bottom:0;display:flex;gap:10px;
        padding:12px 16px calc(12px + env(safe-area-inset-bottom));
        background:var(--asp-bg);margin:0 -16px -16px;border-top:1px solid var(--asp-border);
    }
    .asp-btn{
        flex:1;text-align:center;padding:14px 16px;border-radius:12px;font-size:1rem;font-weight:700;
        border:none;cursor:pointer;text-decoration:none;display:inline-block;
    }
    .asp-btn-primary{background:var(--asp-primary);color:#fff;box-shadow:0 4px 14px rgba(13,110,253,.3);}
    .asp-btn-primary:active{background:var(--asp-primary-dark);}
    .asp-btn-secondary{background:#fff;color:var(--asp-text);border:1.5px solid var(--asp-border);}
</style>

<div class="asp-wrap">

    <div class="asp-header">
        <h2>Serah Terima Asper</h2>
        <p>Formulir Serah Terima Antar Shift</p>
    </div>

    <div id="message">
        <?php
        $msg = $this->session->userdata('message');
        if ($msg <> '') {
            echo '<div class="alert alert-info">' . $msg . '</div>';
        }
        ?>
    </div>

    <form action="<?php echo $action; ?>" method="post" id="asperForm">

        <!-- HEADER -->
        <div class="asp-section">
            <p class="asp-section-title"><span class="asp-dot"></span>Informasi Umum</p>

            <div class="asp-row2">
                <div class="asp-field">
                    <label for="hari_tanggal">Hari / Tanggal <span class="req">*</span></label>
                    <input type="date" class="asp-input" name="hari_tanggal" id="hari_tanggal" value="<?php echo $hari_tanggal; ?>" />
                    <?php echo form_error('hari_tanggal', '<span class="asp-error">', '</span>') ?>
                </div>
                <div class="asp-field">
                    <label for="unit_divisi">Unit / Divisi <span class="req">*</span></label>
                    <input type="text" class="asp-input" name="unit_divisi" id="unit_divisi" placeholder="UGD/Kamar/Ruang/Asper" value="<?php echo $unit_divisi; ?>" />
                    <?php echo form_error('unit_divisi', '<span class="asp-error">', '</span>') ?>
                </div>
            </div>

            <div class="asp-row2">
                <div class="asp-field">
                    <label for="shift">Shift <span class="req">*</span></label>
                    <select class="asp-select" name="shift" id="shift">
                        <option value="">-- Pilih Shift --</option>
                        <option value="Pagi" <?php if ($shift == "Pagi") echo "selected"; ?>>Pagi</option>
                        <option value="Siang" <?php if ($shift == "Siang") echo "selected"; ?>>Siang</option>
                        <option value="Malam" <?php if ($shift == "Malam") echo "selected"; ?>>Malam</option>
                    </select>
                    <?php echo form_error('shift', '<span class="asp-error">', '</span>') ?>
                </div>
                <div class="asp-field">
                    <label for="ke_shift">Ke Shift <span class="req">*</span></label>
                    <select class="asp-select" name="ke_shift" id="ke_shift">
                        <option value="">-- Pilih Shift --</option>
                        <option value="Pagi" <?php if ($ke_shift == "Pagi") echo "selected"; ?>>Pagi</option>
                        <option value="Siang" <?php if ($ke_shift == "Siang") echo "selected"; ?>>Siang</option>
                        <option value="Malam" <?php if ($ke_shift == "Malam") echo "selected"; ?>>Malam</option>
                    </select>
                    <?php echo form_error('ke_shift', '<span class="asp-error">', '</span>') ?>
                </div>
            </div>
        </div>

        <!-- ITEM 1: JUMLAH PASIEN -->
        <div class="asp-section">
            <p class="asp-section-title"><span class="asp-dot"></span>1. Jumlah Pasien Rawat Inap</p>
            <div class="asp-field">
                <textarea class="asp-textarea" rows="2" name="jumlah_pasien_ranap" id="jumlah_pasien_ranap" placeholder="Total jumlah pasien rawat inap saat serah terima"><?php echo $jumlah_pasien_ranap; ?></textarea>
            </div>
        </div>

        <!-- ITEM 2: KAMAR PX MRS/KRS -->
        <div class="asp-section">
            <p class="asp-section-title"><span class="asp-dot"></span>2. Kamar Pasien MRS / KRS</p>
            <p class="asp-section-note">Isi per zona, contoh: UGD:2&#10;HCU:1&#10;NICU:0</p>

            <div class="asp-zona-grid">
                <div class="asp-field">
                    <span class="asp-zona-label">Zona: UGD/HCU/PHCU/NICU/A1-A4</span>
                    <textarea class="asp-textarea" rows="4" name="kamar_zona_a" placeholder="UGD:&#10;HCU:&#10;PHCU:&#10;NICU:&#10;A1:&#10;A2:&#10;A3:&#10;A4:"><?php echo $kamar_zona_a; ?></textarea>
                </div>
                <div class="asp-field">
                    <span class="asp-zona-label">Zona: A5-A9/MZ1-MZ4</span>
                    <textarea class="asp-textarea" rows="4" name="kamar_zona_b" placeholder="A5:&#10;A6:&#10;A7:&#10;A8:&#10;A9:&#10;MZ1:&#10;MZ2:&#10;MZ3:&#10;MZ4:"><?php echo $kamar_zona_b; ?></textarea>
                </div>
                <div class="asp-field">
                    <span class="asp-zona-label">Zona: VK/M1-M8</span>
                    <textarea class="asp-textarea" rows="4" name="kamar_zona_c" placeholder="VK:&#10;M1:&#10;M2:&#10;M3:&#10;M4:&#10;M5:&#10;M6:&#10;M7:&#10;M8:"><?php echo $kamar_zona_c; ?></textarea>
                </div>
                <div class="asp-field">
                    <span class="asp-zona-label">Zona: M9-M12/ML1.1-ML2.2/R.Bayi</span>
                    <textarea class="asp-textarea" rows="4" name="kamar_zona_d" placeholder="M9:&#10;M10:&#10;M11:&#10;M12:&#10;ML1.1:&#10;ML1.2:&#10;ML2.1:&#10;ML2.2:&#10;R.Bayi:"><?php echo $kamar_zona_d; ?></textarea>
                </div>
                <div class="asp-field">
                    <span class="asp-zona-label">Zona: S1-S6</span>
                    <textarea class="asp-textarea" rows="4" name="kamar_zona_e" placeholder="S1:&#10;S2:&#10;S3:&#10;S4:&#10;S5:&#10;S6:"><?php echo $kamar_zona_e; ?></textarea>
                </div>
                <div class="asp-field">
                    <span class="asp-zona-label">Keterangan</span>
                    <textarea class="asp-textarea" rows="4" name="kamar_keterangan" placeholder="Keterangan tambahan"><?php echo $kamar_keterangan; ?></textarea>
                </div>
            </div>
        </div>

        <!-- ITEM 3: VERBED KAMAR -->
        <div class="asp-section">
            <p class="asp-section-title"><span class="asp-dot"></span>3. Verbed Kamar</p>
            <p class="asp-section-note">Isi per zona, sama seperti format di atas</p>

            <div class="asp-zona-grid">
                <div class="asp-field">
                    <span class="asp-zona-label">Zona: UGD/HCU/PHCU/NICU/A1-A4</span>
                    <textarea class="asp-textarea" rows="4" name="verbed_zona_a" placeholder="UGD:&#10;HCU:&#10;PHCU:&#10;NICU:&#10;A1:&#10;A2:&#10;A3:&#10;A4:"><?php echo $verbed_zona_a; ?></textarea>
                </div>
                <div class="asp-field">
                    <span class="asp-zona-label">Zona: A5-A9/MZ1-MZ4</span>
                    <textarea class="asp-textarea" rows="4" name="verbed_zona_b" placeholder="A5:&#10;A6:&#10;A7:&#10;A8:&#10;A9:&#10;MZ1:&#10;MZ2:&#10;MZ3:&#10;MZ4:"><?php echo $verbed_zona_b; ?></textarea>
                </div>
                <div class="asp-field">
                    <span class="asp-zona-label">Zona: VK/M1-M8</span>
                    <textarea class="asp-textarea" rows="4" name="verbed_zona_c" placeholder="VK:&#10;M1:&#10;M2:&#10;M3:&#10;M4:&#10;M5:&#10;M6:&#10;M7:&#10;M8:"><?php echo $verbed_zona_c; ?></textarea>
                </div>
                <div class="asp-field">
                    <span class="asp-zona-label">Zona: M9-M12/ML1.1-ML2.2/R.Bayi</span>
                    <textarea class="asp-textarea" rows="4" name="verbed_zona_d" placeholder="M9:&#10;M10:&#10;M11:&#10;M12:&#10;ML1.1:&#10;ML1.2:&#10;ML2.1:&#10;ML2.2:&#10;R.Bayi:"><?php echo $verbed_zona_d; ?></textarea>
                </div>
                <div class="asp-field">
                    <span class="asp-zona-label">Zona: S1-S6</span>
                    <textarea class="asp-textarea" rows="4" name="verbed_zona_e" placeholder="S1:&#10;S2:&#10;S3:&#10;S4:&#10;S5:&#10;S6:"><?php echo $verbed_zona_e; ?></textarea>
                </div>
                <div class="asp-field">
                    <span class="asp-zona-label">Keterangan</span>
                    <textarea class="asp-textarea" rows="4" name="verbed_keterangan" placeholder="Keterangan tambahan"><?php echo $verbed_keterangan; ?></textarea>
                </div>
            </div>
        </div>

        <!-- ITEM 4-6: OPERASIONAL -->
        <div class="asp-section">
            <p class="asp-section-title"><span class="asp-dot"></span>Operasional</p>

            <div class="asp-field">
                <label for="pengadaan_linen">4. Pengadaan Linen</label>
                <textarea class="asp-textarea" rows="3" name="pengadaan_linen" id="pengadaan_linen"><?php echo $pengadaan_linen; ?></textarea>
            </div>

            <div class="asp-field">
                <label for="check_unit">5. Check Unit-Unit</label>
                <small style="display:block;color:#6b7280;font-size:.72rem;margin-bottom:6px;">UGD, NS1/2/3, HCU, PHCU, NICU, VK, R.Bayi, T.Obat, pantry</small>
                <textarea class="asp-textarea" rows="3" name="check_unit" id="check_unit"><?php echo $check_unit; ?></textarea>
            </div>

            <div class="asp-field">
                <label for="check_stock_bhp">6. Check Stock BHP</label>
                <textarea class="asp-textarea" rows="3" name="check_stock_bhp" id="check_stock_bhp"><?php echo $check_stock_bhp; ?></textarea>
            </div>
        </div>

        <!-- ITEM 7-9: CATATAN & TINDAK LANJUT -->
        <div class="asp-section">
            <p class="asp-section-title"><span class="asp-dot"></span>Catatan &amp; Tindak Lanjut</p>

            <div class="asp-field">
                <label for="permasalahan">7. Permasalahan yang Terjadi</label>
                <textarea class="asp-textarea" rows="3" name="permasalahan" id="permasalahan"><?php echo $permasalahan; ?></textarea>
            </div>

            <div class="asp-field">
                <label for="rencana_tindak_lanjut">8. Rencana Tindak Lanjut</label>
                <textarea class="asp-textarea" rows="3" name="rencana_tindak_lanjut" id="rencana_tindak_lanjut"><?php echo $rencana_tindak_lanjut; ?></textarea>
            </div>

            <div class="asp-field">
                <label for="catatan_lain">9. Catatan Lain-lain</label>
                <textarea class="asp-textarea" rows="3" name="catatan_lain" id="catatan_lain"><?php echo $catatan_lain; ?></textarea>
            </div>
        </div>

        <!-- TANDA TANGAN -->
        <div class="asp-section">
            <p class="asp-section-title"><span class="asp-dot"></span>Tanda Tangan</p>

            <div class="asp-row3">
                <div class="asp-field">
                    <label for="yang_mengoperkan">Yang Mengoperkan <span class="req">*</span></label>
                    <input type="text" class="asp-input" name="yang_mengoperkan" id="yang_mengoperkan" placeholder="Nama" value="<?php echo $yang_mengoperkan; ?>" />
                    <?php echo form_error('yang_mengoperkan', '<span class="asp-error">', '</span>') ?>
                </div>
                <div class="asp-field">
                    <label for="yang_menerima_operan">Yang Menerima Operan <span class="req">*</span></label>
                    <input type="text" class="asp-input" name="yang_menerima_operan" id="yang_menerima_operan" placeholder="Nama" value="<?php echo $yang_menerima_operan; ?>" />
                    <?php echo form_error('yang_menerima_operan', '<span class="asp-error">', '</span>') ?>
                </div>
                <div class="asp-field">
                    <label for="mengetahui">Mengetahui</label>
                    <input type="text" class="asp-input" name="mengetahui" id="mengetahui" placeholder="Nama" value="<?php echo $mengetahui; ?>" />
                </div>
            </div>
        </div>

        <input type="hidden" name="id_asper" value="<?php echo $id_asper; ?>" />

        <div class="asp-actions">
            <a href="<?php echo site_url('asper') ?>" class="asp-btn asp-btn-secondary">Batal</a>
            <button type="submit" class="asp-btn asp-btn-primary"><?php echo $button ?></button>
        </div>
    </form>
</div>