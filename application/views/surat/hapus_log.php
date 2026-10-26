<style>
    .eo-hapus { --eo-ink:#172b4d; --eo-muted:#718096; --eo-line:#e4eaf2; color:var(--eo-ink); }
    .eo-hapus-head { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:18px; }
    .eo-hapus-head h1 { font-size:1.3rem; font-weight:800; margin:0; }
    .eo-hapus-head p { margin:4px 0 0; color:var(--eo-muted); font-size:.78rem; }
    .eo-hapus-panel { background:#fff; border:1px solid var(--eo-line); border-radius:12px; box-shadow:0 5px 20px rgba(23,43,77,.06); overflow:hidden; }
    .eo-hapus-wrap { overflow-x:auto; }
    .eo-hapus-table { width:100%; min-width:960px; border-collapse:separate; border-spacing:0; }
    .eo-hapus-table th { color:#7b8a9d; background:#fbfcfe; border-bottom:1px solid var(--eo-line); font-size:.67rem; text-transform:uppercase; letter-spacing:.07em; padding:13px 14px; white-space:nowrap; }
    .eo-hapus-table td { border-bottom:1px solid #edf1f5; padding:13px 14px; vertical-align:middle; font-size:.79rem; }
    .eo-hapus-table tr:hover td { background:#fbfdff; }
    .eo-hapus-title { font-weight:800; line-height:1.35; }
    .eo-hapus-sub { color:var(--eo-muted); font-size:.72rem; margin-top:3px; }
    .eo-hapus-badge { display:inline-block; border-radius:999px; padding:5px 9px; font-size:.67rem; font-weight:800; text-transform:uppercase; background:#eef1f5; color:#536273; }
    .eo-hapus-empty { text-align:center; padding:42px 18px !important; color:var(--eo-muted); }
    .eo-hapus-empty i { display:block; font-size:2rem; color:#c5d0dd; margin-bottom:9px; }
</style>

<div class="container-fluid eo-hapus">
    <div class="eo-hapus-head">
        <div>
            <h1><i class="fas fa-history text-secondary mr-2"></i>Riwayat Hapus Surat</h1>
            <p>Catatan siapa dan kapan surat dihapus. Log ini tetap tersimpan meskipun surat sudah dihapus.</p>
        </div>
        <a href="<?php echo site_url('surat'); ?>" class="btn btn-light"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="eo-hapus-panel"><div class="eo-hapus-wrap">
        <table class="eo-hapus-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Surat</th>
                    <th>Jenis</th>
                    <th>Status Terakhir</th>
                    <th>Pemohon</th>
                    <th>Dihapus Oleh</th>
                    <th>Role</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr><td colspan="8" class="eo-hapus-empty"><i class="far fa-folder-open"></i>Belum ada riwayat penghapusan surat.</td></tr>
                <?php endif; ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><span class="eo-hapus-title"><?php echo html_escape($log->created_at); ?></span></td>
                        <td>
                            <div class="eo-hapus-title"><?php echo html_escape($log->perihal); ?></div>
                            <div class="eo-hapus-sub"><i class="fas fa-hashtag mr-1"></i><?php echo html_escape($log->no_surat ?: 'Belum bernomor'); ?> &middot; ID <?php echo (int) $log->id_surat; ?></div>
                        </td>
                        <td><span class="eo-hapus-badge"><?php echo html_escape($log->jenis ?: '-'); ?></span></td>
                        <td><span class="eo-hapus-badge"><?php echo html_escape($log->status ?: '-'); ?></span></td>
                        <td><?php echo html_escape($log->nama_pemohon ?: '-'); ?></td>
                        <td><strong><?php echo html_escape($log->nama_user ?: '-'); ?></strong></td>
                        <td><?php echo html_escape($log->role_nama ?: ('Role #' . (int) $log->role_id)); ?></td>
                        <td><span class="eo-hapus-sub"><?php echo html_escape($log->keterangan ?: '-'); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div></div>
</div>
