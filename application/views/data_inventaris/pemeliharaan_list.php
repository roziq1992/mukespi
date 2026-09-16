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
        padding: 22px 24px;
    }
    .di-list-header h2 { margin: 0; font-size: 1.2rem; font-weight: 700; }
    .di-list-header p { margin: 4px 0 0; font-size: 0.8rem; opacity: 0.85; }
    .di-list-body { padding: 22px; }
    @media (max-width: 576px) { .di-list-body { padding: 14px; } }
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
        white-space: nowrap;
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
        font-size: 0.72rem;
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
        font-size: 0.87rem;
        color: #33475b;
        vertical-align: middle;
    }
    .di-table tbody tr:hover { background: #f8fafc; }
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
    .di-action-btn:hover { background: #f1f3f6; text-decoration: none; }
    .di-empty { text-align: center; padding: 40px 16px; color: #8a94a6; }
    .di-badge-aktif { background: #d4edda; color: #1e7e34; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .di-badge-proses { background: #fff3cd; color: #856404; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .di-badge-batal { background: #f8d7da; color: #a71d2a; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .di-search-form {
        flex: 1 1 260px;
        min-width: 220px;
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
            <h2>🔧 Data Pemeliharaan</h2>
            <p>Kelola data pemeliharaan inventaris barang</p>
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
                    <a href="<?php echo site_url('data_inventaris/pemeliharaan_form'); ?>" class="di-btn-add">+ Tambah Pemeliharaan</a>
                    <a href="<?php echo site_url('data_inventaris'); ?>" class="di-btn-add" style="background: #6c757d;">← Kembali ke Inventaris</a>
                </div>

                <form action="<?php echo site_url('data_inventaris/pemeliharaan'); ?>" method="get" class="di-search-form">
                    <div class="di-search-wrap">
                        <input type="text" name="q" placeholder="Cari kode, nama barang..." value="<?php echo $q; ?>">
                        <button type="submit">🔍</button>
                    </div>
                    <?php if ($q <> ''): ?>
                        <a href="<?php echo site_url('data_inventaris/pemeliharaan'); ?>" class="di-search-reset">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-responsive">
                <table class="di-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Inven</th>
                            <th>Nama Barang</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Biaya</th>
                            <th>Petugas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($data_pemeliharaan) == 0): ?>
                            <tr>
                                <td colspan="9">
                                    <div class="di-empty">
                                        <div class="icon">🔧</div>
                                        Belum ada data pemeliharaan.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $no = $start + 1;
                            foreach ($data_pemeliharaan as $item): 
                            ?>
                            <tr>
                                <td data-label="No"><?php echo $no++; ?></td>
                                <td data-label="Kode Inven"><strong><?php echo $item->kode_inven; ?></strong></td>
                                <td data-label="Nama Barang"><?php echo $item->nm_barang; ?></td>
                                <td data-label="Tanggal"><?php echo date('d/m/Y', strtotime($item->tanggal)); ?></td>
                                <td data-label="Keterangan"><?php echo substr($item->keterangan, 0, 50) . (strlen($item->keterangan) > 50 ? '...' : ''); ?></td>
                                <td data-label="Biaya">Rp <?php echo number_format($item->biaya, 0, ',', '.'); ?></td>
                                <td data-label="Petugas"><?php echo $item->petugas; ?></td>
                                <td data-label="Status">
                                    <span class="di-badge-<?php echo strtolower($item->status); ?>">
                                        <?php echo $item->status; ?>
                                    </span>
                                </td>
                                <td data-label="Aksi">
                                    <div class="di-actions-cell">
                                        <a href="<?php echo site_url('data_inventaris/pemeliharaan_form/'.$item->id_pemeliharaan); ?>" class="di-action-btn edit" title="Edit">✏️</a>
                                        <a href="<?php echo site_url('data_inventaris/pemeliharaan_delete/'.$item->id_pemeliharaan); ?>" class="di-action-btn del" title="Hapus" onclick="return confirm('Yakin hapus data pemeliharaan ini?')">🗑️</a>
                                        <a href="javascript:void(0)" onclick="openTrackingModal('<?php echo $item->id_inven; ?>')" 
   class="di-action-btn tracking" title="Lihat Tracking" style="color:#17a2b8;">📊</a>
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
                        Total: <?php echo $total_rows; ?> data
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
<!-- ============================================ -->
<!-- MODAL TRACKING MAINTENANCE -->
<!-- ============================================ -->
<div class="modal fade" id="trackingModal" tabindex="-1" role="dialog" aria-labelledby="trackingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2c5f8a 0%, #1b3a5c 100%); color: #fff;">
                <h5 class="modal-title" id="trackingModalLabel">
                    <span id="trackingIcon">📊</span> Tracking Maintenance - <span id="trackingTitle">Loading...</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="trackingModalBody">
                <div style="text-align:center;padding:40px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p style="margin-top:15px;color:#8a94a6;">Memuat data maintenance...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Tracking Modal Styles */
    .di-action-btn.tracking {
        color: #17a2b8;
    }
    .di-action-btn.tracking:hover {
        background: #17a2b8;
        color: #fff;
    }
    
    .tracking-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 10px;
        margin-bottom: 20px;
    }
    .tracking-stat-box {
        background: #f8fafc;
        border-radius: 10px;
        padding: 12px 15px;
        text-align: center;
        border: 1px solid #eef0f3;
    }
    .tracking-stat-box .stat-number {
        font-size: 22px;
        font-weight: 700;
        color: #2c5f8a;
    }
    .tracking-stat-box .stat-label {
        font-size: 0.7rem;
        color: #8a94a6;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .tracking-stat-box.success .stat-number { color: #28a745; }
    .tracking-stat-box.warning .stat-number { color: #ffc107; }
    .tracking-stat-box.danger .stat-number { color: #dc3545; }
    .tracking-stat-box.info .stat-number { color: #17a2b8; }
    
    .tracking-timeline {
        position: relative;
        padding-left: 30px;
        max-height: 400px;
        overflow-y: auto;
    }
    .tracking-timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #eef0f3;
    }
    .tracking-item {
        position: relative;
        padding: 12px 0 12px 20px;
        border-bottom: 1px solid #f1f3f6;
    }
    .tracking-item:last-child { border-bottom: none; }
    .tracking-item::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 16px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #2c5f8a;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #2c5f8a;
    }
    .tracking-item.completed::before { background: #28a745; box-shadow: 0 0 0 2px #28a745; }
    .tracking-item.proses::before { background: #ffc107; box-shadow: 0 0 0 2px #ffc107; }
    .tracking-item.cancelled::before { background: #dc3545; box-shadow: 0 0 0 2px #dc3545; }
    .tracking-item.scheduled::before { background: #17a2b8; box-shadow: 0 0 0 2px #17a2b8; }
    
    .tracking-item .item-date {
        font-size: 0.75rem;
        color: #8a94a6;
        font-weight: 600;
    }
    .tracking-item .item-title {
        font-weight: 600;
        color: #33475b;
    }
    .tracking-item .item-badge {
        font-size: 0.7rem;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: 600;
    }
    .tracking-item .item-badge.completed { background: #d4edda; color: #1e7e34; }
    .tracking-item .item-badge.proses { background: #fff3cd; color: #856404; }
    .tracking-item .item-badge.cancelled { background: #f8d7da; color: #a71d2a; }
    .tracking-item .item-badge.scheduled { background: #cce5ff; color: #004085; }
    
    .tracking-empty {
        text-align: center;
        padding: 30px 16px;
        color: #8a94a6;
    }
    .tracking-empty .icon { font-size: 40px; margin-bottom: 10px; }
    
    .tracking-info-card {
        background: #f8fafc;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
    }
    .tracking-info-card .info-label { 
        color: #8a94a6; 
        font-weight: 600;
        font-size: 0.8rem;
    }
    .tracking-info-card .info-value { 
        color: #33475b; 
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .tab-track {
        border-bottom: 2px solid #eef0f3;
        margin-bottom: 20px;
    }
    .tab-track .nav-link {
        border: none;
        color: #8a94a6;
        font-weight: 600;
        padding: 10px 20px;
        cursor: pointer;
    }
    .tab-track .nav-link.active {
        color: #2c5f8a;
        border-bottom: 2px solid #2c5f8a;
        background: transparent;
    }
    .tab-track .nav-link:hover { color: #2c5f8a; }
    
    .priority-badge {
        font-size: 0.7rem;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: 600;
        color: #fff;
    }
    .priority-badge.Kritis { background: #dc3545; }
    .priority-badge.Tinggi { background: #fd7e14; }
    .priority-badge.Sedang { background: #ffc107; color: #333; }
    .priority-badge.Rendah { background: #28a745; }
</style>

<script>
// Define base URL
var baseUrl = '<?php echo base_url(); ?>';
var siteUrl = '<?php echo site_url(); ?>';

console.log('Pemeliharaan List - Tracking Modal loaded');
console.log('Site URL:', siteUrl);

/**
 * Open tracking modal for inventory item
 */
function openTrackingModal(id) {
    console.log('Opening tracking modal for ID:', id);
    
    if (!id) {
        alert('ID inventaris tidak valid');
        return;
    }
    
    // Check if jQuery is loaded
    if (typeof jQuery === 'undefined') {
        alert('jQuery tidak ditemukan. Silakan refresh halaman.');
        return;
    }
    
    // Show modal with loading state
    jQuery('#trackingModal').modal('show');
    document.getElementById('trackingTitle').textContent = 'Loading...';
    document.getElementById('trackingModalBody').innerHTML = `
        <div style="text-align:center;padding:40px;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p style="margin-top:15px;color:#8a94a6;">Memuat data maintenance untuk ID: ${id}</p>
        </div>
    `;
    
    // Build URL
    var url = siteUrl + '/data_inventaris/get_maintenance_tracking/' + id;
    console.log('Fetching from URL:', url);
    
    // Fetch data via AJAX
    jQuery.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        timeout: 30000,
        success: function(response) {
            console.log('Response received:', response);
            
            if (response.status === 'success') {
                renderTrackingModal(response);
            } else {
                document.getElementById('trackingModalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <strong>Error:</strong> ${response.message || 'Gagal memuat data'}
                    </div>
                `;
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.error('Response:', xhr.responseText);
            
            var errorMsg = 'Gagal menghubungi server.';
            if (xhr.status === 404) {
                errorMsg = 'Endpoint tidak ditemukan (404). Pastikan URL benar.';
            } else if (xhr.status === 500) {
                errorMsg = 'Terjadi error pada server (500). Cek log error.';
            } else if (status === 'timeout') {
                errorMsg = 'Waktu koneksi habis. Silakan coba lagi.';
            }
            
            document.getElementById('trackingModalBody').innerHTML = `
                <div class="alert alert-danger">
                    <strong>Error:</strong> ${errorMsg}
                    <br><br>
                    <button class="btn btn-sm btn-primary" onclick="openTrackingModal('${id}')">🔄 Coba Lagi</button>
                    <button class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
                    <br><br>
                    <small class="text-muted">Detail: ${error}</small>
                    <br>
                    <small class="text-muted">URL: ${url}</small>
                </div>
            `;
        }
    });
}

/**
 * Render tracking modal with data
 */
function renderTrackingModal(data) {
    console.log('Rendering tracking modal...');
    
    var inv = data.inventaris || {};
    var stats = data.stats || {};
    var history = data.history || [];
    var quickMaintenance = data.quick_maintenance || [];
    var schedules = data.schedules || [];
    var upcoming = data.upcoming_schedules || [];
    
    // Set title
    document.getElementById('trackingTitle').textContent = (inv.kode_inven || '') + ' - ' + (inv.nm_barang || '');
    document.getElementById('trackingIcon').textContent = '📊';
    
    var html = '';
    
    // ============================================
    // 1. INVENTORY INFO
    // ============================================
    html += `
        <div class="tracking-info-card">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:5px 20px;">
                <div><span class="info-label">Kode Inven:</span> <span class="info-value">${inv.kode_inven || '-'}</span></div>
                <div><span class="info-label">Nama Barang:</span> <span class="info-value">${inv.nm_barang || '-'}</span></div>
                <div><span class="info-label">Merek:</span> <span class="info-value">${inv.merek || '-'}</span></div>
                <div><span class="info-label">Jenis:</span> <span class="info-value">${inv.jenis || '-'}</span></div>
                <div><span class="info-label">Kondisi:</span> <span class="info-value">${inv.kondisi || '-'}</span></div>
                <div><span class="info-label">Ruang:</span> <span class="info-value">${inv.id_ruang || '-'}</span></div>
                <div><span class="info-label">Status:</span> <span class="info-value">${inv.stts || '-'}</span></div>
                <div><span class="info-label">Harga:</span> <span class="info-value">Rp ${formatNumber(inv.harga)}</span></div>
            </div>
        </div>
    `;
    
    // ============================================
    // 2. STATISTICS
    // ============================================
    var totalMaintenance = stats.total_maintenance || 0;
    var totalCost = stats.total_cost || 0;
    var statusCounts = stats.status_counts || { Proses: 0, Selesai: 0, Batal: 0 };
    var upcomingCount = stats.upcoming_count || 0;
    
    html += `
        <div class="tracking-stats">
            <div class="tracking-stat-box">
                <div class="stat-number">${totalMaintenance}</div>
                <div class="stat-label">Total Maintenance</div>
            </div>
            <div class="tracking-stat-box success">
                <div class="stat-number">${statusCounts.Selesai || 0}</div>
                <div class="stat-label">Selesai</div>
            </div>
            <div class="tracking-stat-box warning">
                <div class="stat-number">${statusCounts.Proses || 0}</div>
                <div class="stat-label">Proses</div>
            </div>
            <div class="tracking-stat-box info">
                <div class="stat-number">${upcomingCount}</div>
                <div class="stat-label">Jadwal Mendatang</div>
            </div>
            <div class="tracking-stat-box">
                <div class="stat-number">Rp ${formatNumber(totalCost)}</div>
                <div class="stat-label">Total Biaya</div>
            </div>
        </div>
    `;
    
    // ============================================
    // 3. LAST MAINTENANCE
    // ============================================
    if (stats.last_maintenance) {
        var lm = stats.last_maintenance;
        var statusClass = lm.status ? lm.status.toLowerCase() : 'proses';
        var source = lm.nama_jenis ? '📋 Maintenance' : '⚡ Pemeliharaan Cepat';
        html += `
            <div style="background:#eaf3fb;border-radius:8px;padding:10px 15px;margin-bottom:15px;font-size:0.85rem;border-left:4px solid #2c5f8a;">
                <strong>🕐 Maintenance Terakhir (${source}):</strong> 
                ${formatDate(lm.tanggal)} - ${lm.keterangan || 'Tidak ada keterangan'}
                <span class="item-badge ${statusClass}">${lm.status || 'Proses'}</span>
                ${lm.biaya ? `<span style="margin-left:10px;color:#28a745;">💰 Rp ${formatNumber(lm.biaya)}</span>` : ''}
                ${lm.petugas ? `<span style="margin-left:10px;color:#8a94a6;">👤 ${lm.petugas}</span>` : ''}
            </div>
        `;
    }
    
    // ============================================
    // 4. TABS
    // ============================================
    var totalHistory = history.length + quickMaintenance.length;
    
    html += `
        <ul class="nav nav-tabs tab-track" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tabHistory" role="tab" onclick="activateTab('tabHistory')">
                    📋 Riwayat (${totalHistory})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tabQuickMaintenance" role="tab" onclick="activateTab('tabQuickMaintenance')">
                    ⚡ Pemeliharaan Cepat (${quickMaintenance.length})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tabSchedules" role="tab" onclick="activateTab('tabSchedules')">
                    📅 Jadwal (${schedules.length})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tabUpcoming" role="tab" onclick="activateTab('tabUpcoming')">
                    ⏰ Mendatang (${upcoming.length})
                </a>
            </li>
        </ul>
    `;
    
    // ============================================
    // 5. TAB CONTENT
    // ============================================
    html += `<div class="tab-content">`;
    
    // ---------- TAB: HISTORY (Gabungan) ----------
    html += `<div class="tab-pane fade show active" id="tabHistory" role="tabpanel">`;
    
    // Gabungkan history dan quick maintenance
    var allHistory = [...history, ...quickMaintenance];
    // Sort by date
    allHistory.sort(function(a, b) {
        return new Date(b.tanggal) - new Date(a.tanggal);
    });
    
    if (allHistory.length > 0) {
        html += `<div class="tracking-timeline">`;
        allHistory.forEach(function(item) {
            var statusClass = item.status ? item.status.toLowerCase() : 'proses';
            var isQuick = item.id_pemeliharaan ? true : false;
            var sourceIcon = isQuick ? '⚡' : '📋';
            var sourceLabel = isQuick ? 'Pemeliharaan Cepat' : 'Maintenance';
            
            html += `
                <div class="tracking-item ${statusClass}">
                    <div class="item-date">${formatDate(item.tanggal)} <span style="font-size:0.7rem;color:#8a94a6;">${sourceIcon} ${sourceLabel}</span></div>
                    <div class="item-title">${item.keterangan || 'Pemeliharaan'}</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:4px;">
                        <span class="item-badge ${statusClass}">${item.status || 'Proses'}</span>
                        ${item.biaya ? `<span style="font-size:0.8rem;color:#28a745;">💰 Rp ${formatNumber(item.biaya)}</span>` : ''}
                        ${item.petugas ? `<span style="font-size:0.8rem;color:#8a94a6;">👤 ${item.petugas}</span>` : ''}
                        ${item.durasi_jam ? `<span style="font-size:0.8rem;color:#8a94a6;">⏱ ${item.durasi_jam} jam</span>` : ''}
                        ${item.nama_jenis ? `<span style="font-size:0.8rem;color:#8a94a6;">🏷️ ${item.nama_jenis}</span>` : ''}
                    </div>
                    ${item.catatan ? `<div style="font-size:0.8rem;color:#6c757d;margin-top:4px;">📝 ${item.catatan}</div>` : ''}
                </div>
            `;
        });
        html += `</div>`;
    } else {
        html += `
            <div class="tracking-empty">
                <div class="icon">📋</div>
                <p>Belum ada riwayat maintenance untuk aset ini</p>
            </div>
        `;
    }
    html += `</div>`;
    
    // ---------- TAB: QUICK MAINTENANCE ----------
    html += `<div class="tab-pane fade" id="tabQuickMaintenance" role="tabpanel">`;
    if (quickMaintenance.length > 0) {
        html += `<div class="tracking-timeline">`;
        quickMaintenance.forEach(function(item) {
            var statusClass = item.status ? item.status.toLowerCase() : 'proses';
            html += `
                <div class="tracking-item ${statusClass}">
                    <div class="item-date">${formatDate(item.tanggal)} <span style="font-size:0.7rem;color:#8a94a6;">⚡ Pemeliharaan Cepat</span></div>
                    <div class="item-title">${item.keterangan || 'Pemeliharaan Cepat'}</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:4px;">
                        <span class="item-badge ${statusClass}">${item.status || 'Proses'}</span>
                        ${item.biaya ? `<span style="font-size:0.8rem;color:#28a745;">💰 Rp ${formatNumber(item.biaya)}</span>` : ''}
                        ${item.petugas ? `<span style="font-size:0.8rem;color:#8a94a6;">👤 ${item.petugas}</span>` : ''}
                    </div>
                </div>
            `;
        });
        html += `</div>`;
    } else {
        html += `
            <div class="tracking-empty">
                <div class="icon">⚡</div>
                <p>Belum ada pemeliharaan cepat untuk aset ini</p>
            </div>
        `;
    }
    html += `</div>`;
    
    // ---------- TAB: SCHEDULES ----------
    html += `<div class="tab-pane fade" id="tabSchedules" role="tabpanel">`;
    if (schedules.length > 0) {
        html += `<div class="tracking-timeline">`;
        schedules.forEach(function(item) {
            var statusClass = item.status ? item.status.toLowerCase().replace(' ', '-') : 'scheduled';
            html += `
                <div class="tracking-item ${statusClass}">
                    <div class="item-date">${formatDate(item.tanggal_mulai)} ${item.tanggal_selesai ? '→ ' + formatDate(item.tanggal_selesai) : ''}</div>
                    <div class="item-title">${item.judul || 'Jadwal Maintenance'}</div>
                    <div style="font-size:0.8rem;color:#6c757d;margin-top:2px;">${item.deskripsi || ''}</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:4px;">
                        <span class="item-badge ${statusClass}">${item.status || 'Scheduled'}</span>
                        <span class="priority-badge ${item.prioritas || 'Sedang'}">${item.prioritas || 'Sedang'}</span>
                        ${item.estimasi_biaya ? `<span style="font-size:0.8rem;color:#17a2b8;">💰 Rp ${formatNumber(item.estimasi_biaya)}</span>` : ''}
                        ${item.petugas ? `<span style="font-size:0.8rem;color:#8a94a6;">👤 ${item.petugas}</span>` : ''}
                        ${item.nama_jenis ? `<span style="font-size:0.8rem;color:#8a94a6;">🏷️ ${item.nama_jenis}</span>` : ''}
                    </div>
                </div>
            `;
        });
        html += `</div>`;
    } else {
        html += `
            <div class="tracking-empty">
                <div class="icon">📅</div>
                <p>Belum ada jadwal maintenance untuk aset ini</p>
            </div>
        `;
    }
    html += `</div>`;
    
    // ---------- TAB: UPCOMING ----------
    html += `<div class="tab-pane fade" id="tabUpcoming" role="tabpanel">`;
    if (upcoming.length > 0) {
        html += `<div class="tracking-timeline">`;
        upcoming.forEach(function(item) {
            var startDate = new Date(item.tanggal_mulai);
            var today = new Date();
            var diffTime = startDate - today;
            var daysLeft = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            var daysText = daysLeft > 0 ? `${daysLeft} hari lagi` : 'Hari ini';
            if (daysLeft < 0) daysText = `${Math.abs(daysLeft)} hari terlambat`;
            
            html += `
                <div class="tracking-item scheduled">
                    <div class="item-date">${formatDate(item.tanggal_mulai)} ${daysLeft <= 3 ? '🔴' : '🟢'} ${daysText}</div>
                    <div class="item-title">${item.judul || 'Jadwal Maintenance'}</div>
                    <div style="font-size:0.8rem;color:#6c757d;margin-top:2px;">${item.deskripsi || ''}</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:4px;">
                        <span class="item-badge scheduled">${item.status || 'Scheduled'}</span>
                        <span class="priority-badge ${item.prioritas || 'Sedang'}">${item.prioritas || 'Sedang'}</span>
                        ${item.petugas ? `<span style="font-size:0.8rem;color:#8a94a6;">👤 ${item.petugas}</span>` : ''}
                    </div>
                </div>
            `;
        });
        html += `</div>`;
    } else {
        html += `
            <div class="tracking-empty">
                <div class="icon">✅</div>
                <p>Tidak ada jadwal maintenance mendatang</p>
            </div>
        `;
    }
    html += `</div>`;
    
    html += `</div>`; // End tab-content
    
    // ============================================
    // 6. ACTION BUTTONS
    // ============================================
    var invId = inv.id_inven || '';
    html += `
        <div style="display:flex;gap:10px;margin-top:20px;flex-wrap:wrap;border-top:1px solid #eef0f3;padding-top:15px;">
            <a href="${siteUrl}/data_inventaris/maintenance_history_form?inven_id=${invId}" class="btn btn-sm btn-primary">+ Tambah Riwayat</a>
            <a href="${siteUrl}/data_inventaris/maintenance_schedule_form?inven_id=${invId}" class="btn btn-sm btn-success">+ Tambah Jadwal</a>
            <a href="${siteUrl}/data_inventaris/pemeliharaan_form?inven_id=${invId}" class="btn btn-sm btn-warning">+ Pemeliharaan Cepat</a>
            <a href="${siteUrl}/data_inventaris/print_barcode_local/${invId}" target="_blank" class="btn btn-sm btn-info">📱 Cetak Barcode</a>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    `;
    
    document.getElementById('trackingModalBody').innerHTML = html;
    console.log('Modal rendered successfully');
}

/**
 * Activate tab manually
 */
function activateTab(tabId) {
    var tabs = document.querySelectorAll('.tab-track .nav-link');
    tabs.forEach(function(tab) {
        tab.classList.remove('active');
    });
    
    var panes = document.querySelectorAll('.tab-pane');
    panes.forEach(function(pane) {
        pane.classList.remove('show', 'active');
    });
    
    var clickedTab = document.querySelector('.tab-track .nav-link[href="#' + tabId + '"]');
    if (clickedTab) {
        clickedTab.classList.add('active');
    }
    
    var pane = document.getElementById(tabId);
    if (pane) {
        pane.classList.add('show', 'active');
    }
}

/**
 * Format date
 */
function formatDate(dateStr) {
    if (!dateStr) return '-';
    try {
        var d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;
        return String(d.getDate()).padStart(2, '0') + '/' + 
               String(d.getMonth() + 1).padStart(2, '0') + '/' + 
               d.getFullYear();
    } catch(e) {
        return dateStr;
    }
}

/**
 * Format number with thousand separator
 */
function formatNumber(num) {
    if (!num) return '0';
    return String(num).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// ============================================
// EVENT LISTENERS
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('trackingModal');
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('trackingModalBody').innerHTML = `
                <div style="text-align:center;padding:40px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p style="margin-top:15px;color:#8a94a6;">Memuat data maintenance...</p>
                </div>
            `;
            document.getElementById('trackingTitle').textContent = 'Loading...';
        });
    }
});

console.log('Pemeliharaan List - openTrackingModal function defined:', typeof openTrackingModal);
</script>