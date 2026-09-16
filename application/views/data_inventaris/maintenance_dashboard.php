<style>
    .dash-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
        transition: transform 0.2s;
    }
    .dash-card:hover { transform: translateY(-3px); }
    .dash-card .card-header-custom {
        background: linear-gradient(135deg, #2c5f8a 0%, #1b3a5c 100%);
        color: #fff;
        padding: 18px 24px;
    }
    .dash-card .card-header-custom h2 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }
    .dash-card .card-header-custom p {
        margin: 4px 0 0;
        font-size: 0.8rem;
        opacity: 0.85;
    }
    .dash-body { padding: 20px; }
    .stat-box {
        background: #f8fafc;
        border-radius: 10px;
        padding: 15px 20px;
        text-align: center;
        border-left: 4px solid #2c5f8a;
    }
    .stat-box .number {
        font-size: 28px;
        font-weight: 700;
        color: #2c5f8a;
    }
    .stat-box .label {
        font-size: 0.75rem;
        color: #8a94a6;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .stat-box.warning { border-left-color: #ffc107; }
    .stat-box.success { border-left-color: #28a745; }
    .stat-box.danger { border-left-color: #dc3545; }
    .stat-box.info { border-left-color: #17a2b8; }
    .dash-section-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #8a94a6;
        font-weight: 700;
        margin: 20px 0 12px;
        border-bottom: 1px solid #eef0f3;
        padding-bottom: 8px;
    }
    .activity-item {
        padding: 10px 0;
        border-bottom: 1px solid #f1f3f6;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-item .badge-status {
        font-size: 0.7rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
    .badge-status.scheduled { background: #fff3cd; color: #856404; }
    .badge-status.in-progress { background: #cce5ff; color: #004085; }
    .badge-status.completed { background: #d4edda; color: #1e7e34; }
    .badge-status.cancelled { background: #f8d7da; color: #a71d2a; }
    .badge-status.draft { background: #e2e3e5; color: #495057; }
    .priority-badge {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
    }
    .priority-badge.kritis { background: #dc3545; color: #fff; }
    .priority-badge.tinggi { background: #fd7e14; color: #fff; }
    .priority-badge.sedang { background: #ffc107; color: #333; }
    .priority-badge.rendah { background: #28a745; color: #fff; }
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    .menu-item {
        background: #f8fafc;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        text-decoration: none;
        color: #33475b;
        transition: all 0.3s;
        border: 1px solid #eef0f3;
    }
    .menu-item:hover {
        background: #2c5f8a;
        color: #fff;
        text-decoration: none;
        transform: translateY(-3px);
    }
    .menu-item .icon { font-size: 30px; display: block; margin-bottom: 8px; }
    .menu-item .label { font-size: 0.85rem; font-weight: 600; }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="dash-card">
        <div class="card-header-custom">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <div>
                    <h2>🔧 Dashboard Maintenance Asset</h2>
                    <p>Monitoring dan manajemen pemeliharaan aset rumah sakit</p>
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <a href="<?php echo site_url('data_inventaris'); ?>" class="btn btn-light btn-sm">← Kembali</a>
                </div>
            </div>
        </div>
        
        <div class="dash-body">
            <!-- Quick Menu -->
            <div class="menu-grid">
                <a href="<?php echo site_url('data_inventaris/maintenance_schedule'); ?>" class="menu-item">
                    <span class="icon">📅</span>
                    <span class="label">Jadwal Pemeliharaan</span>
                </a>
                <a href="<?php echo site_url('data_inventaris/maintenance_history'); ?>" class="menu-item">
                    <span class="icon">📋</span>
                    <span class="label">Riwayat Pemeliharaan</span>
                </a>
                <a href="<?php echo site_url('data_inventaris/pemeliharaan'); ?>" class="menu-item">
                    <span class="icon">🔧</span>
                    <span class="label">Pemeliharaan Cepat</span>
                </a>
                <a href="<?php echo site_url('data_inventaris'); ?>" class="menu-item">
                    <span class="icon">📦</span>
                    <span class="label">Data Inventaris</span>
                </a>
            </div>

            <!-- Stats -->
            <div class="row mt-4">
                <div class="col-md-3 col-6">
                    <div class="stat-box">
                        <div class="number"><?php echo $stats['total_schedule'] ?? 0; ?></div>
                        <div class="label">Total Jadwal</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box warning">
                        <div class="number"><?php 
                            $scheduled = 0;
                            foreach ($stats['schedule_by_status'] ?? array() as $s) {
                                if ($s->status == 'Scheduled' || $s->status == 'In Progress') {
                                    $scheduled += $s->total;
                                }
                            }
                            echo $scheduled;
                        ?></div>
                        <div class="label">Aktif / Berjalan</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box success">
                        <div class="number"><?php echo $stats['total_history'] ?? 0; ?></div>
                        <div class="label">Riwayat Pemeliharaan</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-box info">
                        <div class="number">Rp <?php echo number_format($stats['total_biaya'] ?? 0, 0, ',', '.'); ?></div>
                        <div class="label">Total Biaya</div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <!-- Maintenance by Type -->
                <div class="col-md-6">
                    <div class="dash-section-title">📊 Pemeliharaan Berdasarkan Jenis</div>
                    <div style="background: #f8fafc; border-radius: 10px; padding: 15px;">
                        <?php 
                        $jenis_data = $stats['history_by_jenis'] ?? array();
                        if (count($jenis_data) > 0): 
                            $total_all = array_sum(array_column($jenis_data, 'total'));
                            foreach ($jenis_data as $j):
                                $percent = $total_all > 0 ? round(($j->total / $total_all) * 100) : 0;
                        ?>
                            <div style="margin-bottom: 8px;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                                    <span><?php echo $j->nama_jenis ?? 'Tidak Diketahui'; ?></span>
                                    <span><?php echo $j->total; ?> (<?php echo $percent; ?>%)</span>
                                </div>
                                <div style="height: 8px; background: #eef0f3; border-radius: 4px; overflow: hidden;">
                                    <div style="height: 100%; width: <?php echo $percent; ?>%; background: linear-gradient(90deg, #2c5f8a, #17a2b8); border-radius: 4px;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align:center;color:#8a94a6;padding:20px;">Belum ada data pemeliharaan</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Upcoming Schedules -->
                <div class="col-md-6">
                    <div class="dash-section-title">⏰ Jadwal Mendatang</div>
                    <div style="background: #f8fafc; border-radius: 10px; padding: 15px; max-height: 250px; overflow-y: auto;">
                        <?php 
                        $upcoming = $stats['upcoming_schedules'] ?? array();
                        if (count($upcoming) > 0): 
                            foreach ($upcoming as $u):
                        ?>
                            <div class="activity-item">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-weight: 600; font-size: 0.9rem;"><?php echo $u->judul; ?></div>
                                        <div style="font-size: 0.78rem; color: #8a94a6;">
                                            <?php echo $u->kode_inven; ?> - <?php echo $u->nm_barang; ?>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <span class="priority-badge <?php echo strtolower($u->prioritas); ?>">
                                            <?php echo $u->prioritas; ?>
                                        </span>
                                        <div style="font-size: 0.7rem; color: #8a94a6; margin-top: 3px;">
                                            <?php echo date('d/m/Y', strtotime($u->tanggal_mulai)); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align:center;color:#8a94a6;padding:20px;">Tidak ada jadwal mendatang</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="dash-section-title">🔄 Aktivitas Terakhir</div>
                    <div style="background: #f8fafc; border-radius: 10px; padding: 15px; max-height: 300px; overflow-y: auto;">
                        <?php 
                        $recent = $stats['recent_history'] ?? array();
                        if (count($recent) > 0): 
                            foreach ($recent as $r):
                        ?>
                            <div class="activity-item">
                                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                                    <div>
                                        <span style="font-weight: 600;"><?php echo $r->kode_inven; ?></span>
                                        <span style="color: #33475b; margin: 0 8px;">-</span>
                                        <span><?php echo $r->nm_barang; ?></span>
                                        <span style="color: #8a94a6; font-size: 0.8rem; margin-left: 8px;">
                                            <?php echo $r->nama_jenis ?? 'Pemeliharaan'; ?>
                                        </span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span class="badge-status <?php echo str_replace(' ', '-', strtolower($r->status ?? 'Proses')); ?>">
                                            <?php echo $r->status ?? 'Proses'; ?>
                                        </span>
                                        <span style="font-size: 0.75rem; color: #8a94a6;">
                                            <?php echo date('d/m/Y', strtotime($r->tanggal)); ?>
                                        </span>
                                    </div>
                                </div>
                                <div style="font-size: 0.8rem; color: #8a94a6; margin-top: 4px;">
                                    <?php echo substr($r->keterangan, 0, 100) . (strlen($r->keterangan) > 100 ? '...' : ''); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align:center;color:#8a94a6;padding:20px;">Belum ada aktivitas pemeliharaan</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>