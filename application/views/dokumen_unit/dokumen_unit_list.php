<style>
    .du-list-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
    }
    .du-list-header {
        background: linear-gradient(135deg, #2c5f8a 0%, #1b3a5c 100%);
        color: #fff;
        padding: 22px 24px;
    }
    .du-list-header h2 { margin: 0; font-size: 1.2rem; font-weight: 700; }
    .du-list-header p { margin: 4px 0 0; font-size: 0.8rem; opacity: 0.85; }

    .du-list-body { padding: 22px; }
    @media (max-width: 576px) { .du-list-body { padding: 14px; } }

    .du-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }
    .du-btn-add {
        background: #2c5f8a;
        border: none;
        color: #fff;
        border-radius: 8px;
        padding: 9px 18px;
        font-weight: 600;
        font-size: 0.88rem;
        white-space: nowrap;
    }
    .du-btn-add:hover { background: #1b3a5c; color: #fff; text-decoration: none; }

    .du-search-form {
    flex: 1 1 100%;
    min-width: 220px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
}
.du-search-form .du-filter-select { flex: 0 1 auto; }
.du-search-form .du-search-wrap {
    flex: 1 1 220px;
    min-width: 200px;
}
    .du-search-wrap {
        display: flex;
        border: 1px solid #dde3ea;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
        flex: 1;
    }
    .du-search-wrap input {
        border: none;
        background: transparent;
        padding: 9px 12px;
        flex: 1;
        font-size: 0.85rem;
        outline: none;
    }
    .du-search-wrap button {
        border: none;
        background: #2c5f8a;
        color: #fff;
        padding: 0 16px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .du-search-reset { font-size: 0.78rem; color: #8a94a6; margin-left: 8px; white-space: nowrap; }

    .du-flash {
        background: #eaf3fb;
        color: #1b3a5c;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 16px;
    }

    .du-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .du-table thead th {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #8a94a6;
        font-weight: 700;
        border-bottom: 2px solid #eef0f3;
        padding: 10px 12px;
        white-space: nowrap;
    }
    .du-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #f1f3f6;
        font-size: 0.87rem;
        color: #33475b;
        vertical-align: middle;
    }
    .du-table tbody tr:hover { background: #f8fafc; }

    .du-unit-chip {
        display: inline-block;
        background: #eef2f7;
        color: #33475b;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        white-space: nowrap;
    }

    .du-file-cell { display: flex; align-items: center; gap: 8px; }
    .du-file-icon-sm {
        width: 30px; height: 30px;
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.6rem; font-weight: 800; color: #fff; flex-shrink: 0;
    }
    .du-file-icon-sm.pdf { background: #e74c3c; }
    .du-file-icon-sm.doc { background: #2c5f8a; }
    .du-file-icon-sm.xls { background: #1e8449; }
    .du-file-icon-sm.other { background: #8a94a6; }
    .du-file-name { max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    .du-badge {
        display: inline-block;
        padding: 3px 11px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .du-badge-aktif { background: #d4edda; color: #1e7e34; }
    .du-badge-draft { background: #fff3cd; color: #856404; }
    .du-badge-arsip { background: #e2e3e5; color: #495057; }
    .du-badge-kadaluarsa { background: #f8d7da; color: #a71d2a; }

    .du-actions-cell { display: flex; gap: 6px; flex-wrap: wrap; }
    .du-action-btn {
        width: 32px; height: 32px;
        border-radius: 7px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.85rem;
        text-decoration: none;
        border: 1px solid #eef0f3;
    }
    .du-action-btn.view { color: #2c5f8a; }
    .du-action-btn.edit { color: #b8860b; }
    .du-action-btn.del { color: #c0392b; }
    .du-action-btn:hover { background: #f1f3f6; text-decoration: none; }

    .du-empty { text-align: center; padding: 40px 16px; color: #8a94a6; }
    .du-empty .icon { font-size: 2.2rem; margin-bottom: 8px; }

    .du-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
    }
    .du-total-chip {
        background: #eef2f7;
        color: #33475b;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
    }
    .du-btn-excel {
        background: #1e8449;
        color: #fff;
        border-radius: 8px;
        padding: 7px 14px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    .du-btn-excel:hover { background: #166638; color: #fff; text-decoration: none; }

    .du-pagination ul { margin: 0; }

    /* Mobile: ubah tabel jadi kartu */
    @media (max-width: 768px) {
        .du-table thead { display: none; }
        .du-table, .du-table tbody, .du-table tr, .du-table td { display: block; width: 100%; }
        .du-table tr {
            border: 1px solid #eef0f3;
            border-radius: 10px;
            margin-bottom: 12px;
            padding: 12px;
        }
        .du-table td {
            border: none;
            padding: 6px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        .du-table td::before {
            content: attr(data-label);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #8a94a6;
            flex-shrink: 0;
        }
        .du-table td:first-child { display: none; }
        .du-file-name { max-width: 140px; }
        .du-actions-cell { justify-content: flex-end; width: 100%; }
    }
    .du-filter-select {
    border: 1px solid #dde3ea;
    border-radius: 8px;
    padding: 8px 10px;
    font-size: 0.85rem;
    background: #f8fafc;
    color: #33475b;
    min-width: 140px;
}
</style>

<div class="container-fluid">
    <div class="du-list-card">
        <div class="du-list-header">
            <h2>📁 Dokumen Unit</h2>
            <p>Kelola seluruh dokumen per unit rumah sakit</p>
        </div>

        <div class="du-list-body">

            <?php
                $flash = $this->session->userdata('message');
                if ($flash <> '') {
                    echo '<div class="du-flash">' . $flash . '</div>';
                }
            ?>

  <div class="du-toolbar">
    <a href="<?php echo site_url('dokumen_unit/create'); ?>" class="du-btn-add">+ Tambah Dokumen</a>

  <form action="<?php echo site_url('dokumen_unit/index'); ?>" method="get" class="du-search-form">
        <select name="id_unit" class="du-filter-select" onchange="this.form.submit()">
            <option value="">Semua Unit</option>
            <?php foreach ($unit2 as $u): ?>
                <option value="<?php echo $u->id_unit ?>" <?php echo ($id_unit == $u->id_unit) ? 'selected' : '' ?>>
                    <?php echo $u->nm_unit ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- BARU: filter Unit Dokumen -->
        <select name="id_unit_doc_ref" id="flt_unit_doc_ref" class="du-filter-select"
                onchange="document.getElementById('flt_jenis_dokumen').value=''; this.form.submit()">
            <option value="">Semua Unit Dokumen</option>
            <?php foreach ($unit_dok2 as $u): ?>
                <option value="<?php echo $u->id_unit_doc ?>" <?php echo ($id_unit_doc_ref == $u->id_unit_doc) ? 'selected' : '' ?>>
                    <?php echo $u->nm_unit_doc ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Jenis Dokumen: hanya terisi & aktif kalau Unit Dokumen sudah dipilih -->
        <select name="id_jenis_dokumen" id="flt_jenis_dokumen" class="du-filter-select"
                onchange="this.form.submit()" <?php echo ($id_unit_doc_ref == '') ? 'disabled' : ''; ?>>
            <option value="">
                <?php echo ($id_unit_doc_ref == '') ? 'Pilih Unit Dokumen dulu' : 'Semua Jenis'; ?>
            </option>
            <?php foreach ($jenis_dokumen2 as $j): ?>
                <option value="<?php echo $j->id_jenis_dokumen ?>" <?php echo ($id_jenis_dokumen == $j->id_jenis_dokumen) ? 'selected' : '' ?>>
                    <?php echo $j->nm_jenis_dokumen ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="status_berlaku" class="du-filter-select" onchange="this.form.submit()">
            <option value="berlaku" <?php echo ($status_berlaku == 'berlaku') ? 'selected' : '' ?>>Masih Berlaku</option>
            <option value="tidak_berlaku" <?php echo ($status_berlaku == 'tidak_berlaku') ? 'selected' : '' ?>>Tidak Berlaku / Kadaluarsa</option>
            <option value="" <?php echo ($status_berlaku == '') ? 'selected' : '' ?>>Semua Status</option>
        </select>

        <div class="du-search-wrap">
            <input type="text" name="q" placeholder="Cari judul, file..." value="<?php echo $q; ?>">
            <button type="submit">🔍</button>
        </div>

        <?php if ($q <> '' || $id_unit <> '' || $id_unit_doc_ref <> '' || $id_jenis_dokumen <> '' || $status_berlaku <> 'berlaku'): ?>
            <a href="<?php echo site_url('dokumen_unit'); ?>" class="du-search-reset">Reset</a>
        <?php endif; ?>
    </form>
</div>

            <div class="table-responsive">
                <table class="du-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Unit</th>
                            <th>Jenis Dokumen</th>
                            <th>Judul Dokumen</th>
                            <th>File</th>
                            <th>Versi</th>
                            <th>Status</th>
                            <th>Berlaku</th>
                            <th>Kadaluarsa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($dokumen_unit_data) == 0): ?>
                            <tr>
                                <td colspan="10">
                                    <div class="du-empty">
                                        <div class="icon">🗂️</div>
                                        Belum ada dokumen<?php echo $q <> '' ? ' yang cocok dengan pencarian "' . $q . '"' : ''; ?>.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dokumen_unit_data as $dokumen_unit):

                                $status_class = 'du-badge-aktif';
                                if ($dokumen_unit->status == 'draft') $status_class = 'du-badge-draft';
                                elseif ($dokumen_unit->status == 'arsip') $status_class = 'du-badge-arsip';
                                elseif ($dokumen_unit->status == 'kadaluarsa') $status_class = 'du-badge-kadaluarsa';

                                $ext = strtolower(ltrim($dokumen_unit->tipe_file, '.'));
                                $icon_class = 'other'; $icon_text = 'FILE';
                                if ($ext == 'pdf') { $icon_class = 'pdf'; $icon_text = 'PDF'; }
                                elseif (in_array($ext, array('doc','docx'))) { $icon_class = 'doc'; $icon_text = 'DOC'; }
                                elseif (in_array($ext, array('xls','xlsx'))) { $icon_class = 'xls'; $icon_text = 'XLS'; }
                            ?>
                            <tr>
                                <td data-label="No" width="50px"><?php echo ++$start ?></td>
                                <td data-label="Unit"><span class="du-unit-chip"><?php echo $dokumen_unit->nm_unit ?></span></td>
                                <td data-label="Jenis Dokumen"><?php echo $dokumen_unit->nm_jenis_dokumen ?></td>
                                <td data-label="Judul Dokumen"><?php echo $dokumen_unit->judul_dokumen ?></td>
                                <td data-label="File">
                                    <div class="du-file-cell">
                                        <div class="du-file-icon-sm <?php echo $icon_class; ?>"><?php echo $icon_text; ?></div>
                                        <span class="du-file-name" title="<?php echo $dokumen_unit->nama_file ?>"><?php echo $dokumen_unit->nama_file ?></span>
                                    </div>
                                </td>
                                <td data-label="Versi">v<?php echo $dokumen_unit->versi ?></td>
                                <td data-label="Status"><span class="du-badge <?php echo $status_class; ?>"><?php echo ucfirst($dokumen_unit->status) ?></span></td>
                                <td data-label="Berlaku"><?php echo $dokumen_unit->tgl_berlaku ? date('d M Y', strtotime($dokumen_unit->tgl_berlaku)) : '-' ?></td>
                              <?php
    $lewat_tanggal = ($dokumen_unit->tgl_kadaluarsa && strtotime($dokumen_unit->tgl_kadaluarsa) < strtotime(date('Y-m-d')));
?>
<td data-label="Kadaluarsa" <?php echo $lewat_tanggal ? 'style="color:#c0392b;font-weight:700;"' : ''; ?>>
    <?php echo $dokumen_unit->tgl_kadaluarsa ? date('d M Y', strtotime($dokumen_unit->tgl_kadaluarsa)) : '-' ?>
</td>
                                <td data-label="Aksi">
                                    <div class="du-actions-cell">
                                        <a href="<?php echo site_url('dokumen_unit/read/'.$dokumen_unit->id_dokumen) ?>" class="du-action-btn view" title="Lihat">👁️</a>
                                        <a href="<?php echo site_url('dokumen_unit/update/'.$dokumen_unit->id_dokumen) ?>" class="du-action-btn edit" title="Edit">✏️</a>
                                        <a href="<?php echo site_url('dokumen_unit/delete/'.$dokumen_unit->id_dokumen) ?>" class="du-action-btn del" title="Hapus" onclick="return confirm('Yakin hapus dokumen ini?')">🗑️</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="du-footer">
                <div>
                    <span class="du-total-chip">Total: <?php echo $total_rows ?> dokumen</span>
                    <a href="<?php echo site_url('dokumen_unit/excel'); ?>" class="du-btn-excel">⬇️ Export Excel</a>
                </div>
                <div class="du-pagination">
                    <ul class="pagination mb-0">
                        <?php echo $pagination ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>