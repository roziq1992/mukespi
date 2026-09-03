<div class="asp-wrap">
    <div class="asp-header">
        <h2>Dashboard Operan Keperawatan</h2>
        <p style="margin:4px 0 0;opacity:0.9;font-size:0.85rem;">Statistik Bulan <?php echo $bulan . '/' . $tahun; ?></p>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
        <div style="background:#fff;border-radius:12px;padding:16px;border:1px solid #e2e8f0;text-align:center;">
            <div style="font-size:2rem;font-weight:700;color:#0d6efd;"><?php echo $stats['total']; ?></div>
            <div style="color:#6b7280;">Total Operan</div>
        </div>
        <div style="background:#fff;border-radius:12px;padding:16px;border:1px solid #e2e8f0;text-align:center;">
            <div style="font-size:2rem;font-weight:700;color:#198754;"><?php echo $stats['rata']; ?></div>
            <div style="color:#6b7280;">Rata-rata Pasien/Hari</div>
        </div>
    </div>

    <a href="<?php echo site_url('operan') ?>" class="asp-btn asp-btn-secondary" style="display:inline-block;padding:10px 20px;border-radius:8px;text-decoration:none;background:#6c757d;color:#fff;font-weight:600;">← Kembali</a>
</div>