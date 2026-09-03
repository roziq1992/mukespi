<?php
$steps = array(
    array('label' => 'Diajukan', 'icon' => 'fa-paper-plane'),
    array('label' => 'Diproses Sekretaris', 'icon' => 'fa-inbox'),
    array('label' => 'Sudah Diberi Nomor', 'icon' => 'fa-hashtag'),
    array('label' => 'Diteruskan ke Direktur', 'icon' => 'fa-share-square'),
    array('label' => 'Finalisasi', 'icon' => 'fa-signature')
);
$status_step = array('Diajukan' => 0, 'Diproses Sekretaris' => 1, 'Sudah Diberi Nomor' => 2, 'Diteruskan ke Direktur' => 3, 'Ditandatangani' => 4, 'Didisposisikan' => 4, 'Selesai' => 4);
$active_step = isset($status_step[$surat->status]) ? $status_step[$surat->status] : 0;
?>
<style>
    .eo-detail { --eo-ink:#172b4d; --eo-muted:#718096; --eo-line:#e4eaf2; --eo-blue:#2563eb; --eo-green:#0f9f72; --eo-orange:#e38b27; color:var(--eo-ink); }
    .eo-hero { background:linear-gradient(135deg,#102a43 0%,#1f4e79 62%,#2c7a9d 100%); border-radius:16px; padding:28px 30px; color:#fff; position:relative; overflow:hidden; margin-bottom:22px; }
    .eo-hero:after { content:''; position:absolute; right:-55px; top:-75px; width:230px; height:230px; border:28px solid rgba(255,255,255,.08); border-radius:50%; }
    .eo-kicker { text-transform:uppercase; letter-spacing:.12em; font-size:.68rem; font-weight:800; opacity:.72; margin-bottom:8px; }
    .eo-hero h1 { font-size:1.55rem; line-height:1.3; margin:0 0 14px; font-weight:800; max-width:760px; }
    .eo-hero-meta { display:flex; flex-wrap:wrap; gap:8px 22px; font-size:.82rem; opacity:.9; }
    .eo-status { display:inline-flex; align-items:center; gap:8px; background:#fff; color:var(--eo-ink); border-radius:999px; padding:7px 13px; font-size:.76rem; font-weight:800; margin-top:20px; }
    .eo-status i { color:var(--eo-green); }
    .eo-actions { position:absolute; z-index:1; right:25px; bottom:25px; }
    .eo-actions .btn { border-radius:8px; font-size:.8rem; font-weight:700; margin-left:6px; }
    .eo-panel { background:#fff; border:1px solid var(--eo-line); border-radius:12px; box-shadow:0 5px 20px rgba(23,43,77,.06); margin-bottom:20px; overflow:hidden; }
    .eo-panel-head { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:17px 20px; border-bottom:1px solid var(--eo-line); }
    .eo-panel-head h2 { margin:0; font-size:1rem; font-weight:800; }
    .eo-panel-head p { margin:3px 0 0; color:var(--eo-muted); font-size:.75rem; }
    .eo-panel-body { padding:20px; }
    .eo-tracker { display:flex; align-items:flex-start; overflow-x:auto; padding:6px 6px 12px; }
    .eo-track-step { min-width:148px; position:relative; text-align:center; flex:1; }
    .eo-track-step:not(:last-child):after { content:''; position:absolute; top:21px; left:calc(50% + 22px); right:calc(-50% + 22px); height:3px; background:var(--eo-line); }
    .eo-track-step.done:not(:last-child):after { background:var(--eo-green); }
    .eo-track-icon { position:relative; z-index:1; width:43px; height:43px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 10px; background:#f1f4f8; color:#9aa8b8; border:3px solid #fff; box-shadow:0 0 0 1px var(--eo-line); }
    .eo-track-step.done .eo-track-icon { background:var(--eo-green); color:#fff; box-shadow:0 0 0 1px var(--eo-green); }
    .eo-track-step.current .eo-track-icon { background:var(--eo-blue); color:#fff; box-shadow:0 0 0 5px rgba(37,99,235,.13); }
    .eo-track-label { display:block; font-size:.72rem; line-height:1.3; color:#8a98aa; font-weight:700; padding:0 8px; }
    .eo-track-step.done .eo-track-label,.eo-track-step.current .eo-track-label { color:var(--eo-ink); }
    .eo-info-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
    .eo-info { background:#f7f9fc; border-radius:9px; padding:13px 15px; min-height:70px; }
    .eo-info-label { display:block; font-size:.66rem; text-transform:uppercase; letter-spacing:.06em; color:var(--eo-muted); font-weight:800; margin-bottom:5px; }
    .eo-info-value { display:block; font-size:.88rem; font-weight:700; word-break:break-word; }
    .eo-file-list { display:flex; flex-wrap:wrap; gap:8px; margin:0; padding:0; list-style:none; }
    .eo-file { display:inline-flex; align-items:center; gap:8px; border:1px solid var(--eo-line); border-radius:8px; padding:9px 11px; color:#28527a; font-size:.78rem; font-weight:700; text-decoration:none; background:#fbfcfe; }
    .eo-file:hover { border-color:var(--eo-blue); color:var(--eo-blue); text-decoration:none; }
    .eo-file i { color:#e05252; font-size:1rem; }
    .eo-timeline { position:relative; padding-left:30px; }
    .eo-timeline:before { content:''; position:absolute; left:8px; top:5px; bottom:5px; width:2px; background:#dce6f0; }
    .eo-event { position:relative; padding:0 0 22px; }
    .eo-event:last-child { padding-bottom:0; }
    .eo-event:before { content:''; position:absolute; left:-27px; top:2px; width:14px; height:14px; border-radius:50%; background:var(--eo-blue); border:3px solid #dceafe; }
    .eo-event-title { font-size:.86rem; font-weight:800; }
    .eo-event-meta { color:var(--eo-muted); font-size:.72rem; margin:3px 0 5px; }
    .eo-event-note { color:#52657d; font-size:.8rem; line-height:1.5; }
    .eo-disposition { border-left:3px solid var(--eo-orange); background:#fffaf3; border-radius:0 8px 8px 0; padding:12px 14px; margin-bottom:10px; font-size:.8rem; line-height:1.5; }
    .eo-disposition strong { font-size:.82rem; }
    .eo-empty { color:var(--eo-muted); font-size:.82rem; }
    @media (max-width:768px) { .eo-hero { padding:22px 19px; } .eo-hero h1 { font-size:1.2rem; } .eo-actions { position:static; margin-top:16px; } .eo-actions .btn { margin:0 5px 0 0; } .eo-info-grid { grid-template-columns:1fr 1fr; } .eo-panel-body { padding:15px; } }
    @media (max-width:480px) { .eo-info-grid { grid-template-columns:1fr; } .eo-track-step { min-width:125px; } }
</style>

<div class="container-fluid eo-detail">
    <div class="eo-hero">
        <div class="eo-kicker">E-OFFICE RSA / Tracking Surat #<?php echo (int) $surat->id; ?></div>
        <h1><?php echo html_escape($surat->perihal); ?></h1>
        <div class="eo-hero-meta"><span><i class="fas fa-map-marker-alt mr-1"></i><?php echo html_escape($surat->tujuan); ?></span><span><i class="far fa-calendar-alt mr-1"></i><?php echo html_escape($surat->tanggal_pengajuan); ?></span><span><i class="fas fa-tag mr-1"></i><?php echo ucfirst(html_escape($surat->jenis)); ?></span></div>
        <div class="eo-status"><i class="fas fa-circle"></i><?php echo html_escape($surat->status); ?></div>
        <div class="eo-actions"><a href="<?php echo site_url('surat'); ?>" class="btn btn-light"><i class="fas fa-arrow-left"></i> Kembali</a><?php if ((int) $this->session->userdata('role_id') === 1): ?><form method="post" action="<?php echo site_url('surat/hapus/' . $surat->id); ?>" onsubmit="return confirm('Hapus surat ini beserta seluruh riwayat dan file?');" style="display:inline"><button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button></form><?php endif; ?></div>
    </div>

    <div class="eo-panel"><div class="eo-panel-head"><div><h2>Progress Pengajuan</h2><p>Perjalanan surat dari pengajuan hingga finalisasi</p></div><span class="badge badge-<?php echo surat_status_class($surat->status); ?>">Status aktif</span></div><div class="eo-panel-body"><div class="eo-tracker"><?php foreach ($steps as $index => $step): ?><div class="eo-track-step <?php echo $index < $active_step ? 'done' : ''; ?> <?php echo $index === $active_step ? 'current done' : ''; ?>"><div class="eo-track-icon"><i class="fas <?php echo $step['icon']; ?>"></i></div><span class="eo-track-label"><?php echo $step['label']; ?></span></div><?php endforeach; ?></div></div></div>

    <div class="eo-panel"><div class="eo-panel-head"><div><h2>Informasi Surat</h2><p>Ringkasan metadata dan dokumen terkait</p></div></div><div class="eo-panel-body"><div class="eo-info-grid"><div class="eo-info"><span class="eo-info-label">Nomor Surat</span><span class="eo-info-value"><?php echo html_escape($surat->no_surat ?: 'Belum bernomor'); ?></span></div><div class="eo-info"><span class="eo-info-label">Pemohon</span><span class="eo-info-value"><?php echo html_escape(isset($surat->pemohon) ? $surat->pemohon : '-'); ?></span></div><div class="eo-info"><span class="eo-info-label">Update Terakhir</span><span class="eo-info-value"><?php echo html_escape($surat->updated_at); ?></span></div></div><div class="mt-3"><span class="eo-info-label">Dokumen Surat</span><ul class="eo-file-list"><?php if ($surat->file_draft): ?><li><a class="eo-file" href="<?php echo site_url('surat/download/' . $surat->id . '/draft'); ?>"><i class="fas fa-file-word"></i> Draft</a></li><?php endif; ?><?php if ($surat->file_ber_nomor): ?><li><a class="eo-file" href="<?php echo site_url('surat/download/' . $surat->id . '/numbered'); ?>"><i class="fas fa-file-alt"></i> Bernomor</a></li><?php endif; ?><?php if ($surat->file_final): ?><li><a class="eo-file" href="<?php echo site_url('surat/download/' . $surat->id . '/final'); ?>"><i class="fas fa-file-signature"></i> Final</a></li><?php endif; ?></ul></div><?php if ($lampiran): ?><div class="mt-3"><span class="eo-info-label">Lampiran</span><ul class="eo-file-list"><?php foreach ($lampiran as $file): ?><li><a class="eo-file" href="<?php echo site_url('surat/lampiran/' . $file->id); ?>"><i class="fas fa-paperclip"></i> <?php echo html_escape($file->nama_file); ?></a></li><?php endforeach; ?></ul></div><?php endif; ?></div></div>

    <div class="row"><div class="col-lg-7"><div class="eo-panel"><div class="eo-panel-head"><div><h2>Riwayat Aktivitas</h2><p>Catatan perubahan status surat</p></div><i class="fas fa-route text-primary"></i></div><div class="eo-panel-body"><div class="eo-timeline"><?php if (!$logs): ?><div class="eo-empty">Belum ada riwayat aktivitas.</div><?php endif; ?><?php foreach ($logs as $log): ?><div class="eo-event"><div class="eo-event-title"><?php echo html_escape($log->aksi); ?></div><div class="eo-event-meta"><i class="fas fa-user-circle mr-1"></i><?php echo html_escape($log->user_name ?: 'System'); ?> <span class="mx-1">·</span> <?php echo html_escape($log->created_at); ?></div><?php if ($log->keterangan): ?><div class="eo-event-note"><?php echo html_escape($log->keterangan); ?></div><?php endif; ?></div><?php endforeach; ?></div></div></div></div><div class="col-lg-5"><div class="eo-panel"><div class="eo-panel-head"><div><h2>Disposisi</h2><p>Instruksi dan tindak lanjut</p></div><i class="fas fa-share text-warning"></i></div><div class="eo-panel-body"><?php if (!$disposisi): ?><div class="eo-empty">Belum ada disposisi.</div><?php endif; ?><?php foreach ($disposisi as $item): ?><div class="eo-disposition"><strong><?php echo html_escape($item->dari_nama); ?> &rarr; <?php echo html_escape($item->ke_nama ?: $item->ke_bagian); ?></strong><br><?php echo html_escape($item->catatan); ?><br><span class="badge badge-secondary mt-1"><?php echo html_escape($item->status); ?></span><?php if ((int) $item->ke_user === (int) $this->session->userdata('id') && $item->status !== 'Selesai'): ?><form method="post" action="<?php echo site_url('surat/disposisi/selesai/' . $item->id); ?>" class="mt-2"><button class="btn btn-sm btn-success"><i class="fas fa-check"></i> Tandai Selesai</button></form><?php endif; ?></div><?php endforeach; ?></div></div></div></div>
</div>
