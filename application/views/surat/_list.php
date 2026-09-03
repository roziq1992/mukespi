<?php
$filters = isset($filters) ? $filters : array();
$status_icons = array(
    'Diajukan' => 'fa-paper-plane',
    'Diproses Sekretaris' => 'fa-inbox',
    'Sudah Diberi Nomor' => 'fa-hashtag',
    'Diteruskan ke Direktur' => 'fa-share-square',
    'Ditandatangani' => 'fa-signature',
    'Didisposisikan' => 'fa-share',
    'Selesai' => 'fa-check-circle'
);
?>
<style>
    .eo-list { --eo-ink:#172b4d; --eo-muted:#718096; --eo-line:#e4eaf2; --eo-blue:#2563eb; color:var(--eo-ink); }
    .eo-list-toolbar { background:#f7f9fc; border:1px solid var(--eo-line); border-radius:10px; padding:14px; margin-bottom:18px; }
    .eo-list-toolbar .form-control { border-color:#dce4ee; font-size:.8rem; height:38px; }
    .eo-list-toolbar .btn { height:38px; font-size:.8rem; font-weight:700; }
    .eo-table-wrap { overflow-x:auto; }
    .eo-table { width:100%; min-width:850px; border-collapse:separate; border-spacing:0; }
    .eo-table th { color:#7b8a9d; background:#fbfcfe; border-bottom:1px solid var(--eo-line); font-size:.67rem; text-transform:uppercase; letter-spacing:.07em; padding:13px 14px; white-space:nowrap; }
    .eo-table td { border-bottom:1px solid #edf1f5; padding:14px; vertical-align:middle; font-size:.8rem; }
    .eo-table tr:hover td { background:#fbfdff; }
    .eo-letter { display:flex; align-items:flex-start; gap:11px; min-width:220px; }
    .eo-letter-icon { width:35px; height:35px; flex:0 0 35px; display:flex; align-items:center; justify-content:center; border-radius:9px; background:#e8f0ff; color:var(--eo-blue); }
    .eo-letter-title { font-weight:800; color:var(--eo-ink); line-height:1.35; }
    .eo-letter-number { color:#60758d; font-size:.72rem; margin-bottom:3px; }
    .eo-letter-sub { color:var(--eo-muted); font-size:.72rem; margin-top:3px; }
    .eo-type { display:inline-block; border-radius:999px; padding:5px 9px; font-size:.67rem; font-weight:800; text-transform:uppercase; }
    .eo-type.internal { background:#e5f7f0; color:#087f5b; }
    .eo-type.eksternal { background:#fff0df; color:#b35b00; }
    .eo-status { display:inline-flex; align-items:center; gap:6px; border-radius:999px; padding:6px 10px; font-size:.69rem; font-weight:800; white-space:nowrap; }
    .eo-status.primary { background:#e8f0ff; color:#2456b8; }.eo-status.warning { background:#fff5d9; color:#986b00; }.eo-status.info { background:#e2f5fa; color:#08758c; }.eo-status.secondary { background:#eef1f5; color:#536273; }.eo-status.success { background:#e4f8ef; color:#087f5b; }.eo-status.danger { background:#fde9e8; color:#b53030; }.eo-status.dark { background:#edf0f3; color:#3e4b59; }
    .eo-update { color:#53657a; font-size:.73rem; line-height:1.5; white-space:nowrap; }
    .eo-update i { color:#9aabba; width:14px; }
    .eo-btn { border-radius:7px; font-size:.72rem; font-weight:700; white-space:nowrap; }
    .eo-empty { text-align:center; padding:42px 18px !important; color:var(--eo-muted); }
    .eo-empty i { display:block; font-size:2rem; color:#c5d0dd; margin-bottom:9px; }
    @media (max-width:768px) { .eo-list-toolbar .col-md-3,.eo-list-toolbar .col-md-2,.eo-list-toolbar .col-md-1 { flex:0 0 50%; max-width:50%; } }
    @media (max-width:480px) { .eo-list-toolbar .col-md-3,.eo-list-toolbar .col-md-2,.eo-list-toolbar .col-md-1 { flex:0 0 100%; max-width:100%; } }
</style>
<div class="eo-list">
    <form class="eo-list-toolbar form-row align-items-end" method="get">
        <div class="col-md-3 mb-2"><label class="small font-weight-bold text-muted">Pencarian</label><input class="form-control" name="q" placeholder="Perihal, tujuan, nomor" value="<?php echo html_escape(isset($filters['q']) ? $filters['q'] : ''); ?>"></div>
        <div class="col-md-2 mb-2"><label class="small font-weight-bold text-muted">Status</label><select class="form-control" name="status"><option value="">Semua status</option><?php foreach (array('Diajukan','Diproses Sekretaris','Sudah Diberi Nomor','Diteruskan ke Direktur','Ditandatangani','Didisposisikan','Selesai') as $status): ?><option value="<?php echo html_escape($status); ?>" <?php echo isset($filters['status']) && $filters['status'] === $status ? 'selected' : ''; ?>><?php echo $status; ?></option><?php endforeach; ?></select></div>
        <div class="col-md-2 mb-2"><label class="small font-weight-bold text-muted">Jenis</label><select class="form-control" name="jenis"><option value="">Semua jenis</option><option value="internal" <?php echo isset($filters['jenis']) && $filters['jenis'] === 'internal' ? 'selected' : ''; ?>>Internal</option><option value="eksternal" <?php echo isset($filters['jenis']) && $filters['jenis'] === 'eksternal' ? 'selected' : ''; ?>>Eksternal</option></select></div>
        <div class="col-md-2 mb-2"><label class="small font-weight-bold text-muted">Dari tanggal</label><input type="date" class="form-control" name="mulai" value="<?php echo html_escape(isset($filters['mulai']) ? $filters['mulai'] : ''); ?>"></div>
        <div class="col-md-2 mb-2"><label class="small font-weight-bold text-muted">Sampai tanggal</label><input type="date" class="form-control" name="sampai" value="<?php echo html_escape(isset($filters['sampai']) ? $filters['sampai'] : ''); ?>"></div>
        <div class="col-md-1 mb-2"><button class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filter</button></div>
    </form>
    <div class="eo-table-wrap"><table class="eo-table"><thead><tr><th>Surat</th><th>Jenis</th><th>Pemohon</th><th>Status</th><th>Update terakhir</th><th>Aksi</th></tr></thead><tbody>
    <?php if (!$surat): ?><tr><td colspan="6" class="eo-empty"><i class="far fa-folder-open"></i>Belum ada surat yang ditampilkan.</td></tr><?php endif; ?>
    <?php foreach ($surat as $row): $status_class = surat_status_class($row->status); $status_icon = isset($status_icons[$row->status]) ? $status_icons[$row->status] : 'fa-circle'; ?><tr><td><div class="eo-letter"><span class="eo-letter-icon"><i class="fas fa-envelope-open-text"></i></span><div><div class="eo-letter-number"><?php echo html_escape($row->no_surat ?: 'Belum bernomor'); ?></div><div class="eo-letter-title"><?php echo html_escape($row->perihal); ?></div><div class="eo-letter-sub"><i class="fas fa-location-arrow mr-1"></i><?php echo html_escape($row->tujuan); ?></div></div></div></td><td><span class="eo-type <?php echo html_escape($row->jenis); ?>"><?php echo html_escape($row->jenis); ?></span></td><td><?php echo html_escape($row->pemohon); ?></td><td><span class="eo-status <?php echo $status_class; ?>"><i class="fas <?php echo $status_icon; ?>"></i><?php echo html_escape($row->status); ?></span></td><td><div class="eo-update"><i class="far fa-clock"></i><?php echo html_escape($row->last_update ?: $row->updated_at); ?><br><i class="fas fa-user"></i><?php echo html_escape($row->last_update_by ?: '-'); ?></div></td><td><a class="btn btn-sm btn-outline-primary eo-btn" href="<?php echo site_url('surat/detail/' . $row->id); ?>"><i class="fas fa-eye"></i> Detail</a><?php if (isset($action) && $action && in_array($row->status, array('Diajukan', 'Diproses Sekretaris', 'Sudah Diberi Nomor', 'Diteruskan ke Direktur'))): ?> <a class="btn btn-sm btn-primary eo-btn" href="<?php echo site_url($action . '/' . $row->id); ?>"><i class="fas fa-arrow-right"></i> Proses</a><?php endif; ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
</div>
