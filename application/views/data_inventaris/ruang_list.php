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
    .di-btn-add.secondary { background: #6c757d; }
    .di-btn-add.secondary:hover { background: #545b62; }

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
        cursor: pointer;
    }
    .di-search-reset {
        font-size: 0.78rem;
        color: #8a94a6;
        margin-left: 8px;
        white-space: nowrap;
        align-self: center;
        text-decoration: none;
    }
    .di-search-reset:hover { color: #2c5f8a; }

    .di-flash {
        background: #eaf3fb;
        color: #1b3a5c;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 16px;
    }
    .di-flash.is-error { background: #f8d7da; color: #a71d2a; }

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

    .di-code-chip {
        display: inline-block;
        background: #eef2f7;
        color: #33475b;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .di-badge {
        display: inline-block;
        padding: 3px 11px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .di-badge-aktif { background: #d4edda; color: #1e7e34; }
    .di-badge-nonaktif { background: #e2e3e5; color: #495057; }

    .di-actions-cell { display: flex; gap: 6px; flex-wrap: wrap; }
    .di-action-btn {
        width: 32px;
        height: 32px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        text-decoration: none;
        border: 1px solid #eef0f3;
        cursor: pointer;
        background: #fff;
        transition: all 0.2s;
    }
    .di-action-btn.edit { color: #b8860b; }
    .di-action-btn.del { color: #c0392b; }
    .di-action-btn:hover {
        background: #f1f3f6;
        text-decoration: none;
        transform: scale(1.05);
    }

    .di-empty { text-align: center; padding: 40px 16px; color: #8a94a6; }
    .di-empty .icon { font-size: 2.2rem; margin-bottom: 8px; }

    .di-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
    }
    .di-total-chip {
        background: #eef2f7;
        color: #33475b;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
    }

    .di-pagination ul { margin: 0; }
    .di-pagination .pagination { margin-bottom: 0; }

    @media (max-width: 768px) {
        .di-table thead { display: none; }
        .di-table, .di-table tbody, .di-table tr, .di-table td { display: block; width: 100%; }
        .di-table tbody tr {
            margin-bottom: 12px;
            border: 1px solid #eef0f3;
            border-radius: 10px;
            padding: 6px 4px;
        }
        .di-table tbody td {
            border: none;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 7px 12px;
            text-align: right;
        }
        .di-table tbody td::before {
            content: attr(data-label);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #8a94a6;
            font-weight: 700;
            text-align: left;
            flex: 0 0 auto;
        }
        .di-actions-cell { justify-content: flex-end; }
    }
</style>

<div class="container-fluid">
    <div class="di-list-card">
        <div class="di-list-header">
            <h2>🏢 Master Ruang</h2>
            <p>Data lokasi / ruang penyimpanan inventaris rumah sakit</p>
        </div>

        <div class="di-list-body">

            <?php
                $flash = $this->session->flashdata('message');
                if ($flash <> '') {
                    $is_error = (stripos($flash, 'tidak dapat') !== FALSE || stripos($flash, 'sudah') !== FALSE || stripos($flash, 'not found') !== FALSE);
                    echo '<div class="di-flash' . ($is_error ? ' is-error' : '') . '">' . $flash . '</div>';
                }
            ?>

            <div class="di-toolbar">
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="<?php echo site_url('data_inventaris/ruang_form'); ?>" class="di-btn-add">+ Tambah Ruang</a>
                    <a href="<?php echo site_url('data_inventaris'); ?>" class="di-btn-add secondary">← Data Inventaris</a>
                </div>

                <form action="<?php echo site_url('data_inventaris/ruang'); ?>" method="get" class="di-search-form">
                    <div class="di-search-wrap">
                        <input type="text" name="q" placeholder="Cari id, nama, kode ruang..." value="<?php echo $q; ?>">
                        <button type="submit">🔍</button>
                    </div>
                    <?php if ($q <> ''): ?>
                        <a href="<?php echo site_url('data_inventaris/ruang'); ?>" class="di-search-reset">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-responsive">
                <table class="di-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Ruang</th>
                            <th>Nama Ruang</th>
                            <th>Kode Ruang</th>
                            <th>Gedung</th>
                            <th>Lantai</th>
                            <th>Jumlah Inventaris</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($ruang_data) == 0): ?>
                            <tr>
                                <td colspan="8">
                                    <div class="di-empty">
                                        <div class="icon">🏢</div>
                                        Belum ada data ruang<?php echo $q <> '' ? ' yang cocok dengan pencarian "' . $q . '"' : ''; ?>.
                                        <br>
                                        <a href="<?php echo site_url('data_inventaris/ruang_form'); ?>" class="di-btn-add mt-3">+ Tambah Ruang</a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php
                            $no = $start;
                            foreach ($ruang_data as $ruang):
                                $no++;
                                $jumlah = (int) $ruang->jumlah_inventaris;
                            ?>
                            <tr>
                                <td data-label="No" width="50px"><?php echo $no; ?></td>
                                <td data-label="ID Ruang"><span class="di-code-chip"><?php echo $ruang->id_ruang; ?></span></td>
                                <td data-label="Nama Ruang"><?php echo $ruang->nama_ruang !== '' ? $ruang->nama_ruang : $ruang->nm_ruang; ?></td>
                                <td data-label="Kode Ruang"><?php echo $ruang->kode_ruang; ?></td>
                                <td data-label="Gedung"><?php echo $ruang->gedung !== '' ? $ruang->gedung : '-'; ?></td>
                                <td data-label="Lantai"><?php echo $ruang->lantai ? $ruang->lantai : '-'; ?></td>
                                <td data-label="Jumlah Inventaris">
                                    <?php if ($jumlah > 0): ?>
                                        <span class="di-badge di-badge-aktif"><?php echo $jumlah; ?> barang</span>
                                    <?php else: ?>
                                        <span class="di-badge di-badge-nonaktif">0 barang</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Aksi">
                                    <div class="di-actions-cell">
                                        <a href="<?php echo site_url('data_inventaris/ruang_form/' . urlencode($ruang->id_ruang)); ?>" class="di-action-btn edit" title="Edit">✏️</a>
                                        <a href="<?php echo site_url('data_inventaris/ruang_delete/' . urlencode($ruang->id_ruang)); ?>" class="di-action-btn del" title="Hapus"
                                           onclick="return confirm('Yakin hapus ruang <?php echo $ruang->id_ruang; ?>?')">🗑️</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="di-footer">
                <div>
                    <span class="di-total-chip">Total: <?php echo $total_rows; ?> ruang</span>
                </div>
                <div class="di-pagination">
                    <ul class="pagination mb-0">
                        <?php echo $pagination; ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
