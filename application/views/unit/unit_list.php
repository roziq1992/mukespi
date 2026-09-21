<style>
.uk-stat { background:#fff; border:1px solid #e4eaf2; border-radius:12px; padding:14px 16px; box-shadow:0 4px 14px rgba(23,43,77,.05); height:100%; }
.uk-stat-label { font-size:.68rem; text-transform:uppercase; letter-spacing:.08em; color:#718096; font-weight:800; }
.uk-stat-value { font-size:1.3rem; font-weight:800; color:#172b4d; margin-top:4px; }
</style>

<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Manajemen Unit Kerja</h1>
            <div class="small text-muted">Kelola master unit: tambah, ubah, dan ubah status aktif/nonaktif.</div>
        </div>
        <a href="<?php echo site_url('unit/create'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah Unit</a>
    </div>

    <div class="row mb-3">
        <div class="col-xl-3 col-md-4 mb-2">
            <div class="uk-stat">
                <div class="uk-stat-label">Total Unit</div>
                <div class="uk-stat-value"><?php echo (int) $stat['total']; ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-4 mb-2">
            <div class="uk-stat">
                <div class="uk-stat-label">Aktif</div>
                <div class="uk-stat-value text-success"><?php echo (int) $stat['aktif']; ?></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-4 mb-2">
            <div class="uk-stat">
                <div class="uk-stat-label">Nonaktif</div>
                <div class="uk-stat-value text-danger"><?php echo (int) $stat['nonaktif']; ?></div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Unit</h6>
                </div>
                <div class="col-md-6">
                    <form method="get" action="<?php echo site_url('unit'); ?>" class="d-flex">
                        <input type="text" name="q" value="<?php echo html_escape($q); ?>" class="form-control form-control-sm" placeholder="Cari nama / jenis unit...">
                        <button class="btn btn-sm btn-primary ml-2" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
            <ul class="nav nav-pills mt-3">
                <li class="nav-item">
                    <a class="nav-link small <?php echo $status === '' ? 'active' : ''; ?>" href="<?php echo site_url('unit'); ?>">Semua (<?php echo (int)$stat['total']; ?>)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link small <?php echo $status === 'aktif' ? 'active' : ''; ?>" href="<?php echo site_url('unit/index/aktif'); ?>">Aktif (<?php echo (int)$stat['aktif']; ?>)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link small <?php echo $status === 'nonaktif' ? 'active' : ''; ?>" href="<?php echo site_url('unit/index/nonaktif'); ?>">Nonaktif (<?php echo (int)$stat['nonaktif']; ?>)</a>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Unit Kerja</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Jml Pegawai</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($units)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-5">Belum ada data unit.</td></tr>
                        <?php endif; ?>
                        <?php $no = 0; foreach ($units as $u): $no++; $jml = $this->Unit_model->count_pegawai($u->id_unit); ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td class="font-weight-bold"><?php echo html_escape($u->nm_unit); ?></td>
                            <td><?php echo html_escape(str_replace('_', ' ', $u->jns_unit)); ?></td>
                            <td>
                                <?php if ($u->status === 'aktif'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo (int) $jml; ?></td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('unit/edit/' . $u->id_unit); ?>"><i class="fas fa-edit"></i> Ubah</a>
                                <?php if ($u->status === 'aktif'): ?>
                                <a class="btn btn-sm btn-outline-danger" href="<?php echo site_url('unit/status/' . $u->id_unit . '/nonaktif'); ?>" onclick="return confirm('Nonaktifkan unit &quot;<?php echo html_escape(addslashes($u->nm_unit)); ?>&quot;?')"><i class="fas fa-user-slash"></i> Nonaktif</a>
                                <?php else: ?>
                                <a class="btn btn-sm btn-outline-success" href="<?php echo site_url('unit/status/' . $u->id_unit . '/aktif'); ?>"><i class="fas fa-check"></i> Aktifkan</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>