<head>
    <!-- Tambahkan CSS & JS SweetAlert2 di bagian <head> atau sebelum penutup </body> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #0d6efd;
            --success: #198754;
            --danger: #dc3545;
            --gray-bg: #f8f9fa;
            --border-color: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        .asp-list-wrap {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            color: var(--text-main);
        }

        .asp-list-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .asp-list-head h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .asp-toolbar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            width: 100%;
        }

        @media (min-width: 768px) {
            .asp-toolbar {
                width: auto;
            }
        }

        .asp-search {
            display: flex;
            gap: 8px;
            flex: 1;
        }

        @media (min-width: 768px) {
            .asp-search {
                flex: initial;
            }
        }

        .asp-search input {
            padding: 9px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            width: 100%;
            transition: border-color 0.2s;
        }

        .asp-search input:focus {
            border-color: var(--primary);
        }

        .asp-btn {
            padding: 9px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        .asp-btn:hover {
            opacity: 0.9;
        }

        .asp-btn-primary { background: var(--primary); color: #fff; }
        .asp-btn-success { background: var(--success); color: #fff; }
        .asp-btn-secondary { background: #6c757d; color: #fff; }

        /* Flash Message */
        .asp-alert {
            padding: 12px 16px;
            border-radius: 8px;
            background: #e0f2fe;
            color: #0369a1;
            margin-bottom: 16px;
            font-size: 0.9rem;
            border: 1px solid #bae6fd;
        }

        /* Container Desktop Table */
        .asp-table-container {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            overflow: hidden;
            display: none;
        }

        @media (min-width: 992px) {
            .asp-table-container {
                display: block;
            }
        }

        .asp-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            text-align: left;
        }

        .asp-table th {
            background: var(--gray-bg);
            color: var(--text-muted);
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .asp-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
        }

        .asp-table tbody tr:last-child td {
            border-bottom: none;
        }

        .asp-table tbody tr:hover {
            background: #f8fafc;
        }

        /* Mobile Card View */
        .asp-mobile-cards {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        @media (min-width: 992px) {
            .asp-mobile-cards {
                display: none;
            }
        }

        .asp-card-item {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }

        .asp-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.85rem;
        }

        .asp-card-date {
            font-weight: 700;
            color: var(--primary);
        }

        .asp-card-body {
            font-size: 0.88rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-bottom: 12px;
        }

        .asp-card-field span {
            display: block;
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .asp-card-field strong {
            color: var(--text-main);
            font-size: 0.85rem;
        }

        .asp-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .asp-card-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
        }

        .btn-action-sm {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-detail { background: #e0f2fe; color: #0369a1; }
        .btn-edit { background: #fef3c7; color: #92400e; }
        .btn-delete { background: #fee2e2; color: #991b1b; }

        .asp-pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        .asp-paging {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 20px;
}

.asp-paging a,
.asp-paging span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    background: #fff;
    transition: all 0.15s;
}

.asp-paging a:hover {
    background: #e0f2fe;
    color: #0369a1;
    border-color: #bae6fd;
}

.asp-paging span.cur {
    background: var(--primary);
    color: #fff;
    border-color: transparent;
}

.asp-paging span.nav a,
.asp-paging span.nav {
    color: var(--primary);
    font-weight: 600;
    border-color: var(--border-color);
}
/* Pagination */
.asp-pagination { margin-top: 20px; display: flex; justify-content: center; }

.asp-pagination p { display: flex; flex-wrap: wrap; gap: 4px; align-items: center; margin: 0; }

.asp-pagination p a,
.asp-pagination p b {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    border: 1px solid var(--border-color);
    background: #fff;
    color: var(--text-muted);
    transition: all 0.15s;
}

.asp-pagination p a:hover {
    background: #e0f2fe;
    color: #0369a1;
    border-color: #bae6fd;
}

.asp-pagination p b {
    background: var(--primary);
    color: #fff;
    border-color: transparent;
}
    </style>
</head>
<body>

<div class="asp-list-wrap">
    <!-- Header & Toolbar -->
    <div class="asp-list-head">
        <h3>Serah Terima Asper</h3>
        <div class="asp-toolbar">
            <form class="asp-search" method="get">
                <input type="text" name="q" placeholder="Cari tanggal, unit, shift..." value="<?php echo htmlspecialchars($q); ?>">
                <button type="submit" class="asp-btn asp-btn-secondary">Cari</button>
            </form>
            <a href="<?php echo site_url('asper/create') ?>" class="asp-btn asp-btn-primary">+ Tambah</a>
            <!--<a href="<?php echo site_url('asper/excel') ?>" class="asp-btn asp-btn-success">Export Excel</a>-->
            <a href="<?php echo site_url('asper/dashboard') ?>" class="asp-btn asp-btn-success">Grafik</a>
        </div>
    </div>

    <!-- Flash Message -->
    <?php
    $msg = $this->session->userdata('message');
    if ($msg <> '') {
        echo '<div class="asp-alert">' . $msg . '</div>';
    }
    ?>

    <!-- 1. TAMPILAN DESKTOP (TABEL) -->
    <div class="asp-table-container">
        <table class="asp-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Shift</th>
                    <th>Ke Shift</th>
                    <th>Unit/Divisi</th>
                    <th>Pengoper</th>
                    <th>Penerima</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = $start + 1; foreach ($asper_data as $row): 
                $formatted_date = date_create($row->hari_tanggal)->format('d-m-Y');
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo $formatted_date; ?></strong></td>
                    <td><span class="badge" style="background:#e2e8f0; padding:3px 8px; border-radius:4px; font-size:0.8rem;"><?php echo htmlspecialchars($row->shift); ?></span></td>
                    <td><?php echo htmlspecialchars($row->ke_shift); ?></td>
                    <td><?php echo htmlspecialchars($row->unit_divisi); ?></td>
                    <td><?php echo htmlspecialchars($row->yang_mengoperkan); ?></td>
                    <td><?php echo htmlspecialchars($row->yang_menerima_operan); ?></td>
                    <td style="text-align: right;">
                        <div class="asp-actions" style="justify-content: flex-end;">
                            <a href="<?php echo site_url('asper/read/' . $row->id_asper) ?>" class="btn-action-sm btn-detail">Detail</a>
                            <a href="<?php echo site_url('asper/update/' . $row->id_asper) ?>" class="btn-action-sm btn-edit">Edit</a>
                            <button type="button" onclick="confirmDelete('<?php echo site_url('asper/delete/' . $row->id_asper) ?>', '<?php echo $formatted_date; ?>', '<?php echo htmlspecialchars($row->unit_divisi); ?>')" class="btn-action-sm btn-delete" style="border:none;">Hapus</button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($asper_data)): ?>
                <tr><td colspan="8" style="text-align:center; padding:30px; color:var(--text-muted);">Tidak ada data ditemukan.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 2. TAMPILAN MOBILE (CARD LIST) -->
    <div class="asp-mobile-cards">
        <?php $no_m = $start + 1; foreach ($asper_data as $row): 
            $formatted_date = date_create($row->hari_tanggal)->format('d-m-Y');
        ?>
            <div class="asp-card-item">
                <div class="asp-card-header">
                    <span class="asp-card-date">#<?php echo $no_m++; ?> &bull; <?php echo $formatted_date; ?></span>
                    <span style="background:#e2e8f0; padding:2px 8px; border-radius:4px; font-size:0.75rem; font-weight:600;"><?php echo htmlspecialchars($row->shift); ?> &rarr; <?php echo htmlspecialchars($row->ke_shift); ?></span>
                </div>
                <div class="asp-card-body">
                    <div class="asp-card-field">
                        <span>Unit / Divisi</span>
                        <strong><?php echo htmlspecialchars($row->unit_divisi); ?></strong>
                    </div>
                    <div class="asp-card-field">
                        <span>Yang Mengoperkan</span>
                        <strong><?php echo htmlspecialchars($row->yang_mengoperkan); ?></strong>
                    </div>
                    <div class="asp-card-field" style="grid-column: span 2;">
                        <span>Yang Menerima Operan</span>
                        <strong><?php echo htmlspecialchars($row->yang_menerima_operan); ?></strong>
                    </div>
                </div>
                <div class="asp-card-footer">
                    <a href="<?php echo site_url('asper/read/' . $row->id_asper) ?>" class="btn-action-sm btn-detail">Detail</a>
                    <a href="<?php echo site_url('asper/update/' . $row->id_asper) ?>" class="btn-action-sm btn-edit">Edit</a>
                    <button type="button" onclick="confirmDelete('<?php echo site_url('asper/delete/' . $row->id_asper) ?>', '<?php echo $formatted_date; ?>', '<?php echo htmlspecialchars($row->unit_divisi); ?>')" class="btn-action-sm btn-delete" style="border:none;">Hapus</button>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($asper_data)): ?>
            <div style="text-align:center; padding:30px; color:var(--text-muted); background:#fff; border:1px solid var(--border-color); border-radius:12px;">Tidak ada data ditemukan.</div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="asp-pagination"><?php echo $pagination; ?></div>
</div>

<script>
function confirmDelete(deleteUrl, tanggal, unit) {
    Swal.fire({
        title: 'Hapus Data Operan?',
        html: `Anda akan menghapus data tanggal <b>${tanggal}</b> untuk Unit <b>${unit}</b>.<br><small style="color:#64748b;">Tindakan ini tidak dapat dibatalkan!</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = deleteUrl;
        }
    });
}
</script>

</body>