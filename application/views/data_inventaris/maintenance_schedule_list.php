<style>
    .di-list-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
    }
    .di-list-header {
        background: linear-gradient(135deg, #2c5f8a 0%, #1b3a5c 100%);
        color: #fff;
        padding: 18px 24px;
    }
    .di-list-header h2 { margin: 0; font-size: 1.2rem; font-weight: 600; }
    .di-list-header p { margin: 4px 0 0; font-size: 0.8rem; opacity: 0.85; }
    .di-list-body { padding: 20px; }
    .di-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }
    .di-btn-add {
        background: #2c5f8a;
        border: none;
        color: #fff;
        border-radius: 8px;
        padding: 9px 18px;
        font-weight: 600;
        font-size: 0.88rem;
        text-decoration: none;
        display: inline-block;
    }
    .di-btn-add:hover { background: #1b3a5c; color: #fff; text-decoration: none; }
    .di-flash {
        background: #eaf3fb;
        color: #1b3a5c;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 16px;
    }
    .di-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .di-table thead th {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #8a94a6;
        font-weight: 700;
        border-bottom: 2px solid #eef0f3;
        padding: 10px 12px;
        white-space: nowrap;
    }
    .di-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #f1f3f6;
        font-size: 0.85rem;
        color: #33475b;
        vertical-align: middle;
    }
    .di-table tbody tr:hover { background: #f8fafc; }
    .badge-status {
        font-size: 0.7rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
    .badge-status.Scheduled { background: #fff3cd; color: #856404; }
    .badge-status.In-Progress { background: #cce5ff; color: #004085; }
    .badge-status.Completed { background: #d4edda; color: #1e7e34; }
    .badge-status.Cancelled { background: #f8d7da; color: #a71d2a; }
    .badge-status.Draft { background: #e2e3e5; color: #495057; }
    .priority-badge {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
    }
    .priority-badge.Kritis { background: #dc3545; color: #fff; }
    .priority-badge.Tinggi { background: #fd7e14; color: #fff; }
    .priority-badge.Sedang { background: #ffc107; color: #333; }
    .priority-badge.Rendah { background: #28a745; color: #fff; }
    .di-actions-cell { display: flex; gap: 6px; flex-wrap: wrap; }
    .di-action-btn {
        width: 32px; height: 32px;
        border-radius: 7px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.85rem;
        text-decoration: none;
        border: 1px solid #eef0f3;
    }
    .di-action-btn.edit { color: #b8860b; }
    .di-action-btn.del { color: #c0392b; }
    .di-action-btn.view { color: #2c5f8a; }
    .di-action-btn:hover { background: #f1f3f6; text-decoration: none; }
    .di-empty { text-align: center; padding: 40px 16px; color: #8a94a6; }
    .di-search-form {
        flex: 1 1 260px;
        min-width: 200px;
        display: flex;
        justify-content: flex-end;
    }
    .di-search-wrap {
        display: flex;
        border: 1px solid #dde3ea;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
        flex: 1;
        max-width: 340px;
    }
    .di-search-wrap input {
        border: none;
        background: transparent;
        padding: 9px 12px;
        flex: 1;
        font-size: 0.85rem;
        outline: none;
    }
    .di-search-wrap button {
        border: none;
        background: #2c5f8a;
        color: #fff;
        padding: 0 16px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .di-search-reset { font-size: 0.78rem; color: #8a94a6; margin-left: 8px; white-space: nowrap; align-self: center; }
</style>

<div class="container-fluid">
    <div class="di-list-card">
        <div class="di-list-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <div>
                    <h2>📅 Jadwal Pemeliharaan</h2>
                    <p>Kelola jadwal pemeliharaan aset rumah sakit</p>
                </div>
                <a href="<?php echo site_url('data_inventaris/maintenance_dashboard'); ?>" class="btn btn-light btn-sm">← Dashboard</a>
            </div>
        </div>

        <div class="di-list-body">
            <?php
                $flash = $this->session->flashdata('message');
                if ($flash <> '') {
                    echo '<div class="di-flash">' . $flash . '</div>';
                }
            ?>

            <div class="di-toolbar">
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="<?php echo site_url('data_inventaris/maintenance_schedule_form'); ?>" class="di-btn-add">+ Tambah Jadwal</a>
                    <a href="<?php echo site_url('data_inventaris/maintenance_history'); ?>" class="di-btn-add" style="background: #6c757d;">📋 Riwayat</a>
                </div>

                <form action="<?php echo site_url('data_inventaris/maintenance_schedule'); ?>" method="get" class="di-search-form">
                    <div class="di-search-wrap">
                        <input type="text" name="q" placeholder="Cari judul, kode..." value="<?php echo $q; ?>">
                        <button type="submit">🔍</button>
                    </div>
                    <?php if ($q <> ''): ?>
                        <a href="<?php echo site_url('data_inventaris/maintenance_schedule'); ?>" class="di-search-reset">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-responsive">
                <table class="di-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Inventaris</th>
                            <th>Jenis</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($data_schedule) == 0): ?>
                            <tr>
                                <td colspan="10">
                                    <div class="di-empty">
                                        <div class="icon">📅</div>
                                        Belum ada jadwal pemeliharaan.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = $start + 1; foreach ($data_schedule as $item): ?>
                            <tr>
                                <td data-label="No"><?php echo $no++; ?></td>
                                <td data-label="Judul">
                                    <strong><?php echo $item->judul; ?></strong>
                                    <div style="font-size:0.75rem;color:#8a94a6;"><?php echo substr($item->deskripsi, 0, 50) . (strlen($item->deskripsi) > 50 ? '...' : ''); ?></div>
                                </td>
                                <td data-label="Inventaris">
                                    <strong><?php echo $item->kode_inven; ?></strong>
                                    <div style="font-size:0.75rem;color:#8a94a6;"><?php echo $item->nm_barang; ?></div>
                                </td>
                                <td data-label="Jenis">
                                    <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:<?php echo $item->warna ?? '#2c5f8a'; ?>;vertical-align:middle;"></span>
                                    <?php echo $item->nama_jenis; ?>
                                </td>
                                <td data-label="Tanggal Mulai"><?php echo date('d/m/Y', strtotime($item->tanggal_mulai)); ?></td>
                                <td data-label="Tanggal Selesai"><?php echo $item->tanggal_selesai ? date('d/m/Y', strtotime($item->tanggal_selesai)) : '-'; ?></td>
                                <td data-label="Prioritas">
                                    <span class="priority-badge <?php echo str_replace(' ', '', $item->prioritas); ?>">
                                        <?php echo $item->prioritas; ?>
                                    </span>
                                </td>
                                <td data-label="Status">
                                    <span class="badge-status <?php echo str_replace(' ', '-', $item->status); ?>">
                                        <?php echo $item->status; ?>
                                    </span>
                                </td>
                                <td data-label="Petugas"><?php echo $item->petugas ?? '-'; ?></td>
                                <td data-label="Aksi">
                                    <div class="di-actions-cell">
                                        <a href="<?php echo site_url('data_inventaris/maintenance_schedule_form/'.$item->id_schedule); ?>" class="di-action-btn edit" title="Edit">✏️</a>
                                        <a href="<?php echo site_url('data_inventaris/maintenance_schedule_delete/'.$item->id_schedule); ?>" class="di-action-btn del" title="Hapus" onclick="return confirm('Yakin hapus jadwal ini?')">🗑️</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="di-footer" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-top: 18px;">
                <div>
                    <span style="background: #eef2f7; color: #33475b; font-size: 0.8rem; font-weight: 600; padding: 6px 14px; border-radius: 20px;">
                        Total: <?php echo $total_rows; ?> jadwal
                    </span>
                </div>
                <div>
                    <ul class="pagination mb-0">
                        <?php echo $pagination; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>