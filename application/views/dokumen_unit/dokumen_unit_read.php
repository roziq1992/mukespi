<style>
    .du-read-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
    }
    .du-read-header {
        background: linear-gradient(135deg, #2c5f8a 0%, #1b3a5c 100%);
        color: #fff;
        padding: 24px;
    }
    .du-read-header h2 {
        margin: 0 0 6px;
        font-size: 1.25rem;
        font-weight: 700;
        word-break: break-word;
    }
    .du-read-header .du-meta {
        font-size: 0.82rem;
        opacity: 0.85;
    }
    .du-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .du-badge-aktif { background: #d4edda; color: #1e7e34; }
    .du-badge-draft { background: #fff3cd; color: #856404; }
    .du-badge-arsip { background: #e2e3e5; color: #495057; }
    .du-badge-kadaluarsa { background: #f8d7da; color: #a71d2a; }
    .du-badge-current { background: rgba(255,255,255,0.2); color: #fff; margin-left: 6px; }

    .du-read-body { padding: 24px; }
    @media (max-width: 576px) { .du-read-body { padding: 16px; } }

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

    .du-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px 20px;
    }
    @media (max-width: 576px) {
        .du-info-grid { grid-template-columns: 1fr; }
    }
    .du-info-item .label {
        font-size: 0.72rem;
        color: #8a94a6;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 2px;
    }
    .du-info-item .value {
        font-size: 0.95rem;
        color: #26364a;
        font-weight: 600;
        word-break: break-word;
    }
    .du-info-item .value.muted {
        color: #8a94a6;
        font-weight: 400;
        font-style: italic;
    }

    .du-file-card {
        display: flex;
        align-items: center;
        gap: 16px;
        background: #f8fafc;
        border: 1px solid #eef0f3;
        border-radius: 10px;
        padding: 16px;
        flex-wrap: wrap;
    }
    .du-file-icon {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fff;
        flex-shrink: 0;
    }
    .du-file-icon.pdf { background: #e74c3c; }
    .du-file-icon.doc { background: #2c5f8a; }
    .du-file-icon.xls { background: #1e8449; }
    .du-file-icon.other { background: #8a94a6; }

    .du-file-info { flex: 1; min-width: 160px; }
    .du-file-info .name { font-weight: 700; color: #26364a; word-break: break-word; }
    .du-file-info .meta { font-size: 0.8rem; color: #8a94a6; margin-top: 2px; }

    .du-file-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .du-file-actions .btn { border-radius: 8px; font-weight: 600; }

    .du-actions {
        margin-top: 26px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .du-actions .btn { border-radius: 8px; padding: 10px 22px; font-weight: 600; }
</style>

<?php
    // siapkan badge status
    $status_class = 'du-badge-aktif';
    if ($status == 'draft') $status_class = 'du-badge-draft';
    elseif ($status == 'arsip') $status_class = 'du-badge-arsip';
    elseif ($status == 'kadaluarsa') $status_class = 'du-badge-kadaluarsa';

    // siapkan icon & warna berdasarkan tipe file
    $ext = strtolower(ltrim($tipe_file, '.'));
    $icon_class = 'other';
    $icon_text = 'FILE';
    if ($ext == 'pdf') { $icon_class = 'pdf'; $icon_text = 'PDF'; }
    elseif (in_array($ext, array('doc', 'docx'))) { $icon_class = 'doc'; $icon_text = 'DOC'; }
    elseif (in_array($ext, array('xls', 'xlsx'))) { $icon_class = 'xls'; $icon_text = 'XLS'; }

    // format ukuran file (bytes -> KB/MB)
    $size_text = '-';
    if (!empty($ukuran_file)) {
        $size_text = $ukuran_file >= 1048576
            ? round($ukuran_file / 1048576, 2) . ' MB'
            : round($ukuran_file / 1024, 1) . ' KB';
    }

    // format tanggal Indonesia sederhana
    function du_fmt_date($d) {
        if (empty($d) || $d == '0000-00-00') return '-';
        return date('d M Y', strtotime($d));
    }
    function du_fmt_datetime($d) {
        if (empty($d) || $d == '0000-00-00 00:00:00') return '-';
        return date('d M Y, H:i', strtotime($d));
    }
?>

<div class="container-fluid">
    <div class="du-read-card">
        <div class="du-read-header">
            <h2>📄 <?php echo $judul_dokumen; ?></h2>
            <div class="du-meta">
                <span class="du-badge <?php echo $status_class; ?>"><?php echo ucfirst($status); ?></span>
                <?php if ($is_current == 1): ?>
                    <span class="du-badge du-badge-current">Versi Terbaru (v<?php echo $versi; ?>)</span>
                <?php else: ?>
                    <span class="du-badge du-badge-current">Versi <?php echo $versi; ?> (bukan versi terbaru)</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="du-read-body">

            <div class="du-section-title">Informasi Unit &amp; Jenis Dokumen</div>
            <div class="du-info-grid">
                <div class="du-info-item">
                    <div class="label">Unit</div>
                    <div class="value"><?php echo isset($nm_unit) && $nm_unit ? $nm_unit : '<span class="muted">Tidak diketahui</span>'; ?></div>
                </div>
                <div class="du-info-item">
                    <div class="label">Jenis Dokumen</div>
                    <div class="value"><?php echo isset($nm_jenis_dokumen) && $nm_jenis_dokumen ? $nm_jenis_dokumen : '<span class="muted">Tidak diketahui</span>'; ?></div>
                </div>
            </div>

            <div class="du-section-title">Keterangan</div>
            <div class="du-info-item">
                <div class="value" style="font-weight:400;">
                    <?php echo $keterangan ? nl2br($keterangan) : '<span class="muted">Tidak ada keterangan</span>'; ?>
                </div>
            </div>

            <div class="du-section-title">File Dokumen</div>
            <div class="du-file-card">
                <div class="du-file-icon <?php echo $icon_class; ?>"><?php echo $icon_text; ?></div>
                <div class="du-file-info">
                    <div class="name"><?php echo $nama_file; ?></div>
                    <div class="meta"><?php echo strtoupper($ext); ?> &middot; <?php echo $size_text; ?></div>
                </div>
                <div class="du-file-actions">
                    <a href="<?php echo base_url($path_file); ?>" target="_blank" class="btn btn-primary btn-sm">👁️ Lihat</a>
                    <a href="<?php echo base_url($path_file); ?>" download class="btn btn-outline-secondary btn-sm">⬇️ Unduh</a>
                </div>
            </div>

            <div class="du-section-title">Masa Berlaku &amp; Riwayat</div>
            <div class="du-info-grid">
                <div class="du-info-item">
                    <div class="label">Tgl Berlaku</div>
                    <div class="value"><?php echo du_fmt_date($tgl_berlaku); ?></div>
                </div>
                <div class="du-info-item">
                    <div class="label">Tgl Kadaluarsa</div>
                    <div class="value"><?php echo du_fmt_date($tgl_kadaluarsa); ?></div>
                </div>
                <div class="du-info-item">
                    <div class="label">Diupload Oleh (ID User)</div>
                    <div class="value"><?php echo $diupload_oleh ? $diupload_oleh : '-'; ?></div>
                </div>
                <div class="du-info-item">
                    <div class="label">Dibuat</div>
                    <div class="value"><?php echo du_fmt_datetime($created_at); ?></div>
                </div>
                <div class="du-info-item">
                    <div class="label">Terakhir Diupdate</div>
                    <div class="value"><?php echo du_fmt_datetime($updated_at); ?></div>
                </div>
            </div>

            <div class="du-actions">
                <a href="<?php echo site_url('dokumen_unit/update/' . $id_dokumen) ?>" class="btn btn-warning">✏️ Edit</a>
                <a href="<?php echo site_url('dokumen_unit') ?>" class="btn btn-outline-secondary">← Kembali</a>
            </div>

        </div>
    </div>
</div>