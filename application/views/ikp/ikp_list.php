<!-- Custom CSS Modern Overlay untuk SB Admin (Fixed Z-Index Modal) -->
<!-- Custom CSS Modern Overlay untuk SB Admin (Fixed Menu & Modal Overlay) -->
<style>
    /* 1. LAYER TERATAS: Modal & Backdrop */
    .modal-backdrop {
        z-index: 10050 !important;
    }
    .modal {
        z-index: 10060 !important;
    }

    /* 2. LAYER MENENGAH: Navbar, Sidebar, & Dropdown Menu Admin */
    .navbar, 
    .topbar, 
    .sidebar, 
    .dropdown-menu {
        z-index: 1030 !important;
    }

    /* 3. LAYER DASAR: Card & Table Container */
    .ikp-card {
        border: none !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06) !important;
        background: #ffffff;
        position: relative;
        z-index: 0 !important; /* Dibuat 0 agar selalu di bawah Navbar & Sidebar */
        overflow: visible !important; /* Mencegah menu dropdown terpotong */
    }
    
    .ikp-card-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }

    .ikp-card-title {
        color: #ffffff;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: 0.3px;
        margin: 0;
    }

    .ikp-top-bar {
        padding: 1rem 0;
        margin-bottom: 1rem;
    }

    /* Modern Search Input */
    .ikp-search-group .form-control {
        border-radius: 30px 0 0 30px !important;
        border: 1px solid #e3e6f0;
        padding-left: 18px;
        font-size: 0.875rem;
        box-shadow: none !important;
    }

    .ikp-search-group .form-control:focus {
        border-color: #4e73df;
    }

    .ikp-search-group .btn-search {
        border-radius: 0 30px 30px 0 !important;
        padding: 0.375rem 1.25rem;
    }

    /* Modern Table Design */
    .table-responsive {
        border-radius: 12px;
        overflow-x: auto;
        position: relative;
        z-index: 0 !important; /* Layer dasar di bawah menu */
    }

    .ikp-table {
        border-collapse: separate !important;
        border-spacing: 0;
        width: 100%;
        margin-bottom: 1.5rem !important;
    }

    .ikp-table thead th {
        background-color: #f8fafc;
        color: #4a5568;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 14px 16px;
        border-top: 1px solid #edf2f7 !important;
        border-bottom: 2px solid #edf2f7 !important;
        border-right: none !important;
        border-left: none !important;
        white-space: nowrap;
    }

    .ikp-table tbody td {
        padding: 12px 16px;
        vertical-align: middle !important;
        border-top: 1px solid #f1f5f9 !important;
        border-bottom: none !important;
        border-right: none !important;
        border-left: none !important;
        font-size: 0.85rem;
        color: #2d3748;
        white-space: nowrap;
    }

    .ikp-table tbody tr:hover {
        background-color: #f8fafc;
        transition: all 0.15s ease-in-out;
    }

    /* Sticky Columns dengan Layer Rendah */
    .ikp-table th:nth-child(1), .ikp-table td:nth-child(1) {
        position: sticky;
        left: 0;
        z-index: 1;
        background-color: #fff;
    }
    .ikp-table th:nth-child(2), .ikp-table td:nth-child(2) {
        position: sticky;
        left: 60px; /* Lebar kolom No */
        z-index: 1;
        background-color: #fff;
        box-shadow: 4px 0 8px rgba(0,0,0,0.03);
    }
    .ikp-table thead th:nth-child(1), .ikp-table thead th:nth-child(2) {
        background-color: #f8fafc;
        z-index: 2;
    }

    /* Badges & Buttons */
    .badge-pill-custom {
        padding: 6px 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-block;
    }

    .badge-risk-merah { background: #ffe2e5; color: #f64e60; }
    .badge-risk-kuning { background: #fff4de; color: #ffa800; }
    .badge-risk-hijau { background: #c9f7f5; color: #1bc5bd; }
    .badge-risk-biru { background: #e1f0ff; color: #3699ff; }
    .badge-default { background: #f3f6f9; color: #7e8299; }

    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        line-height: 32px;
        text-align: center;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
        transition: transform 0.1s ease;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid py-3">

    <div class="card ikp-card">
        <!-- Card Header -->
        <div class="ikp-card-header">
            <h6 class="ikp-card-title">
                <i class="fas fa-notes-medical mr-2"></i>Data IKP (Insiden Keselamatan Pasien)
            </h6>
            <span class="badge badge-light px-3 py-2" style="border-radius: 20px; font-weight: 600; color: #224abe;">
                Total: <?php echo $total_rows ?> Record
            </span>
        </div>

        <div class="card-body p-4">
            <!-- Filter & Action Controls -->
            <div class="row align-items-center mb-3">
                <div class="col-md-6 mb-2 mb-md-0 d-flex align-items-center gap-2">
                    <?php echo anchor(site_url('ikp/create'), '<i class="fas fa-plus mr-1"></i> Tambah Data', 'class="btn btn-primary btn-rounded px-4 shadow-sm" style="border-radius: 20px;"'); ?>
                    <?php echo anchor(site_url('ikp/excel'), '<i class="fas fa-file-excel mr-1"></i> Export Excel', 'class="btn btn-success btn-rounded px-3 ml-2 shadow-sm" style="border-radius: 20px;"'); ?>
                </div>
                <div class="col-md-6">
                    <form action="<?php echo site_url('ikp/index'); ?>" method="get">
                        <div class="input-group ikp-search-group">
                            <input type="text" class="form-control" name="q" placeholder="Cari data pasien, insiden..." value="<?php echo $q; ?>">
                            <div class="input-group-append">
                                <?php if ($q <> ''): ?>
                                    <a href="<?php echo site_url('ikp'); ?>" class="btn btn-outline-secondary px-3" style="border-radius: 0;">Reset</a>
                                <?php endif; ?>
                                <button class="btn btn-primary btn-search" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table ikp-table">
                    <thead>
                        <tr>
                            <th width="60px">No</th>
                            <th>Nm Pasien</th>
                            <th>RM</th>
                            <th>Ruang</th>
                            <th>Kelamin</th>
                            <th>Penanggung Jawab</th>
                            <th>Tgl Masuk</th>
                            <th>Jam Masuk</th>
                            <th>Tgl Kejadian</th>
                            <th>Jam Kejadian</th>
                            <th>Insiden</th>
                            <th>Kronologis</th>
                            <th>Jns Insiden</th>
                            <th>Pelapor Pertama</th>
                            <th>Insiden Terjadi Pd</th>
                            <th>Insiden Menyangkut</th>
                            <th>Tempat Insiden</th>
                            <th>Insiden Terjadi Pd 2</th>
                            <th>Unit Penyebab</th>
                            <th>Akibat Insiden</th>
                            <th>Tindakan</th>
                            <th>Tindakan Oleh</th>
                            <th>Kejadian Terulang</th>
                            <th>Ket Kejadian Terulang</th>
                            <th>Pelapor</th>
                            <th>Penerima</th>
                            <th>Tgl Lapor</th>
                            <th>Grading Risiko</th>
                            <th class="text-center" style="min-width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ikp_data)): ?>
                            <tr>
                                <td colspan="29" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i> Tidak ada data ditemukan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ikp_data as $ikp): ?>
                                <tr>
                                    <td width="60px" class="font-weight-bold text-muted"><?php echo ++$start ?></td>
                                    <td class="font-weight-bold text-primary"><?php echo $ikp->nm_pasien ?></td>
                                    <td><span class="badge badge-default"><?php echo $ikp->rm ?></span></td>
                                    <td><?php echo $ikp->ruang ?></td>
                                    <td>
                                        <span class="badge-pill-custom <?php echo (strtolower($ikp->kelamin) == 'l' || strtolower($ikp->kelamin) == 'laki-laki') ? 'badge-risk-biru' : 'badge-risk-merah'; ?>">
                                            <?php echo $ikp->kelamin ?>
                                        </span>
                                    </td>
                                    <td><?php echo $ikp->penangung_jawab ?></td>
                                    <td><?php echo $ikp->tgl_masuk ?></td>
                                    <td><?php echo $ikp->jam_masuk ?></td>
                                    <td><?php echo $ikp->tgl_kejadian ?></td>
                                    <td><?php echo $ikp->jam_kejadian ?></td>
                                    <td><?php echo $ikp->insiden ?></td>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($ikp->krologis); ?>">
                                        <?php echo $ikp->krologis ?>
                                    </td>
                                    <td><?php echo $ikp->jns_insiden ?></td>
                                    <td><?php echo $ikp->pelapor_pertama ?></td>
                                    <td><?php echo $ikp->insiden_terjadipd ?></td>
                                    <td><?php echo $ikp->insiden_meyangkut ?></td>
                                    <td><?php echo $ikp->tempat_insiden ?></td>
                                    <td><?php echo $ikp->insiden_terjadipd2 ?></td>
                                    <td><?php echo $ikp->unit_penyebab ?></td>
                                    <td><?php echo $ikp->akibat_insiden ?></td>
                                    <td><?php echo $ikp->tindakan ?></td>
                                    <td><?php echo $ikp->tindakan_oleh ?></td>
                                    <td>
                                        <span class="badge-pill-custom <?php echo (strtolower($ikp->kejadian_terulang) == 'ya') ? 'badge-risk-kuning' : 'badge-default'; ?>">
                                            <?php echo $ikp->kejadian_terulang ?>
                                        </span>
                                    </td>
                                    <td><?php echo $ikp->ket_kejadian_terulang ?></td>
                                    <td><?php echo $ikp->pelapor ?></td>
                                    <td><?php echo $ikp->penerima ?></td>
                                    <td><?php echo $ikp->tgl_lapor ?></td>
                                    <td>
                                        <?php 
                                            $risk_class = 'badge-default';
                                            $grading = strtolower($ikp->grading_resiko);
                                            if (strpos($grading, 'merah') !== false) $risk_class = 'badge-risk-merah';
                                            elseif (strpos($grading, 'kuning') !== false) $risk_class = 'badge-risk-kuning';
                                            elseif (strpos($grading, 'hijau') !== false) $risk_class = 'badge-risk-hijau';
                                            elseif (strpos($grading, 'biru') !== false) $risk_class = 'badge-risk-biru';
                                        ?>
                                        <span class="badge-pill-custom <?php echo $risk_class; ?>">
                                            <?php echo $ikp->grading_resiko ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo site_url('ikp/read/'.$ikp->id_ikp); ?>" class="btn btn-action btn-info text-white" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo site_url('ikp/update/'.$ikp->id_ikp); ?>" class="btn btn-action btn-warning text-white" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo site_url('ikp/delete/'.$ikp->id_ikp); ?>" onclick="javascript: return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="btn btn-action btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination & Info -->
            <div class="row align-items-center mt-3">
                <div class="col-md-6 text-muted font-weight-bold" style="font-size: 0.85rem;">
                    Menampilkan total <?php echo $total_rows ?> data
                </div>
                <div class="col-md-6 text-right">
                    <?php echo $pagination ?>
                </div>
            </div>

        </div>
    </div>

</div>