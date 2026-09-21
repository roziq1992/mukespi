<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <h1 class="h3 mb-0 text-gray-800">Notifikasi</h1>
        <a href="<?php echo site_url('dashboard'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <?php if (!empty($notifikasi)): ?>
            <div class="list-group list-group-flush">
                <?php foreach ($notifikasi as $n): ?>
                <a href="<?php echo site_url('notifikasi/baca/' . (int)$n->id_notif); ?>" class="list-group-item list-group-item-action d-flex align-items-start" style="border-left: <?php echo $n->is_read ? '3px solid transparent' : '3px solid #4f46e5'; ?>;">
                    <i class="fas fa-bell text-primary mt-1 mr-3"></i>
                    <div style="flex:1;">
                        <div class="text-gray-800 small font-weight-bold"><?php echo html_escape($n->pesan); ?></div>
                        <small class="text-muted"><?php echo date('d M Y H:i', strtotime($n->created_at)); ?></small>
                    </div>
                    <?php if (!$n->is_read): ?><span class="badge badge-primary ml-2">Baru</span><?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-bell-slash fa-3x mb-3"></i>
                <p>Tidak ada notifikasi.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>