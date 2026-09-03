<style>
    :root {
        --primary: #0d6efd;
        --border-color: #e2e8f0;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --bg-card: #ffffff;
        --bg-body: #f8f9fa;
    }

    .asp-read-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        padding: 20px;
        max-width: 800px;
        margin: 0 auto;
        color: var(--text-main);
    }

    .asp-read-header-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .asp-read-header-title h3 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .asp-read-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .asp-read-card h4 {
        margin: 0 0 14px 0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--primary);
        font-weight: 700;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 8px;
    }

    .asp-read-row {
        display: flex;
        flex-direction: column;
        padding: 8px 0;
        border-bottom: 1px solid #f8fafc;
        font-size: 0.9rem;
    }

    .asp-read-row:last-child {
        border-bottom: none;
    }

    @media (min-width: 640px) {
        .asp-read-row {
            flex-direction: row;
            align-items: flex-start;
        }
    }

    .asp-read-row .k {
        width: 220px;
        color: var(--text-muted);
        font-weight: 500;
        flex-shrink: 0;
        margin-bottom: 2px;
    }

    @media (min-width: 640px) {
        .asp-read-row .k {
            margin-bottom: 0;
        }
    }

    .asp-read-row .v {
        flex: 1;
        color: var(--text-main);
        white-space: pre-wrap;
        word-break: break-word;
        font-weight: 500;
    }

    .asp-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
        text-decoration: none;
        color: #fff;
        background: #6c757d;
        padding: 9px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: opacity 0.2s;
    }

    .asp-back:hover {
        opacity: 0.9;
        color: #fff;
    }
</style>

<div class="asp-read-wrap">
    <div class="asp-read-header-title">
        <h3>Detail Serah Terima Asper</h3>
        <a href="<?php echo site_url('asper') ?>" class="asp-back">&larr; Kembali</a>
    </div>

    <!-- Informasi Umum -->
    <div class="asp-read-card">
        <h4>Informasi Umum</h4>
        <div class="asp-read-row"><div class="k">Hari/Tanggal</div><div class="v"><?php echo $hari_tanggal; ?></div></div>
        <div class="asp-read-row"><div class="k">Shift</div><div class="v"><span style="background:#e2e8f0; padding:2px 8px; border-radius:4px; font-size:0.8rem;"><?php echo $shift; ?></span></div></div>
        <div class="asp-read-row"><div class="k">Ke Shift</div><div class="v"><span style="background:#e2e8f0; padding:2px 8px; border-radius:4px; font-size:0.8rem;"><?php echo $ke_shift; ?></span></div></div>
        <div class="asp-read-row"><div class="k">Unit/Divisi</div><div class="v"><?php echo $unit_divisi; ?></div></div>
    </div>

    <!-- 1. Jumlah Pasien Rawat Inap -->
    <div class="asp-read-card">
        <h4>1. Jumlah Pasien Rawat Inap</h4>
        <div class="asp-read-row">
            <div class="v" style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid #f1f5f9;"><?php echo nl2br($jumlah_pasien_ranap); ?></div>
        </div>
    </div>

    <!-- 2. Kamar Pasien MRS/KRS -->
    <div class="asp-read-card">
        <h4>2. Kamar Pasien MRS/KRS</h4>
        <div class="asp-read-row"><div class="k">Zona A (UGD, HCU, dll)</div><div class="v"><?php echo nl2br($kamar_zona_a); ?></div></div>
        <div class="asp-read-row"><div class="k">Zona B (A5-A9, MZ1-MZ4)</div><div class="v"><?php echo nl2br($kamar_zona_b); ?></div></div>
        <div class="asp-read-row"><div class="k">Zona C (VK, M1-M8)</div><div class="v"><?php echo nl2br($kamar_zona_c); ?></div></div>
        <div class="asp-read-row"><div class="k">Zona D (M9-M12, ML, R.Bayi)</div><div class="v"><?php echo nl2br($kamar_zona_d); ?></div></div>
        <div class="asp-read-row"><div class="k">Zona E (S1-S6)</div><div class="v"><?php echo nl2br($kamar_zona_e); ?></div></div>
        <div class="asp-read-row"><div class="k">Keterangan Kamar</div><div class="v" style="color:var(--text-muted);"><?php echo !empty($kamar_keterangan) ? nl2br($kamar_keterangan) : '-'; ?></div></div>
    </div>

    <!-- 3. Verbed Kamar -->
    <div class="asp-read-card">
        <h4>3. Verbed Kamar</h4>
        <div class="asp-read-row"><div class="k">Zona A (UGD, HCU, dll)</div><div class="v"><?php echo nl2br($verbed_zona_a); ?></div></div>
        <div class="asp-read-row"><div class="k">Zona B (A5-A9, MZ1-MZ4)</div><div class="v"><?php echo nl2br($verbed_zona_b); ?></div></div>
        <div class="asp-read-row"><div class="k">Zona C (VK, M1-M8)</div><div class="v"><?php echo nl2br($verbed_zona_c); ?></div></div>
        <div class="asp-read-row"><div class="k">Zona D (M9-M12, ML, R.Bayi)</div><div class="v"><?php echo nl2br($verbed_zona_d); ?></div></div>
        <div class="asp-read-row"><div class="k">Zona E (S1-S6)</div><div class="v"><?php echo nl2br($verbed_zona_e); ?></div></div>
        <div class="asp-read-row"><div class="k">Keterangan Verbed</div><div class="v" style="color:var(--text-muted);"><?php echo !empty($verbed_keterangan) ? nl2br($verbed_keterangan) : '-'; ?></div></div>
    </div>

    <!-- Operasional -->
    <div class="asp-read-card">
        <h4>Operasional</h4>
        <div class="asp-read-row"><div class="k">4. Pengadaan Linen</div><div class="v"><?php echo nl2br($pengadaan_linen); ?></div></div>
        <div class="asp-read-row"><div class="k">5. Check Unit-Unit</div><div class="v"><?php echo nl2br($check_unit); ?></div></div>
        <div class="asp-read-row"><div class="k">6. Check Stock BHP</div><div class="v"><?php echo nl2br($check_stock_bhp); ?></div></div>
    </div>

    <!-- Catatan & Tindak Lanjut -->
    <div class="asp-read-card">
        <h4>Catatan &amp; Tindak Lanjut</h4>
        <div class="asp-read-row"><div class="k">7. Permasalahan</div><div class="v"><?php echo nl2br($permasalahan); ?></div></div>
        <div class="asp-read-row"><div class="k">8. Rencana Tindak Lanjut</div><div class="v"><?php echo nl2br($rencana_tindak_lanjut); ?></div></div>
        <div class="asp-read-row"><div class="k">9. Catatan Lain-lain</div><div class="v"><?php echo nl2br($catatan_lain); ?></div></div>
    </div>

    <!-- Tanda Tangan -->
    <div class="asp-read-card">
        <h4>Tanda Tangan</h4>
        <div class="asp-read-row"><div class="k">Yang Mengoperkan</div><div class="v"><strong><?php echo $yang_mengoperkan; ?></strong></div></div>
        <div class="asp-read-row"><div class="k">Yang Menerima Operan</div><div class="v"><strong><?php echo $yang_menerima_operan; ?></strong></div></div>
        <div class="asp-read-row"><div class="k">Mengetahui</div><div class="v"><strong><?php echo $mengetahui; ?></strong></div></div>
    </div>

    <a href="<?php echo site_url('asper') ?>" class="asp-back">&larr; Kembali ke daftar</a>
</div>