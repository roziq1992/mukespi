<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Data Elemen Penilaian</h1>
            <div class="small text-muted">Saat dihapus, data hanya akan dinonaktifkan dan tetap tersimpan di database.</div>
        </div>
        <a href="<?php echo site_url('master_ep/elemen_form'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah EP</a>
    </div>

    <div class="row mb-3">
        <div class="col-md-3 mb-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs text-uppercase font-weight-bold text-success">Aktif</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int)$stats['aktif']; ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs text-uppercase font-weight-bold text-secondary">Nonaktif</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int)$stats['nonaktif']; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link <?php echo $status === 'aktif' ? 'active' : ''; ?>" href="<?php echo site_url('master_ep/elemen/aktif'); ?>">Aktif</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo $status === 'nonaktif' ? 'active' : ''; ?>" href="<?php echo site_url('master_ep/elemen/nonaktif'); ?>">Nonaktif</a></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <form method="get" action="<?php echo site_url('master_ep/elemen/' . $status); ?>" class="d-flex">
                        <input type="text" name="q" value="<?php echo html_escape($q); ?>" class="form-control form-control-sm" placeholder="Cari nomor / isi EP / standar...">
                        <button class="btn btn-sm btn-primary ml-2" type="submit">Cari</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Standar</th>
                            <th class="text-center">No. EP</th>
                            <th>Isi EP</th>
                            <th>Skor</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data.</td></tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($rows as $row): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo html_escape($row->no_standar); ?></td>
                                    <td>
                                        <strong><?php echo html_escape($row->no_urut !== NULL ? $row->no_urut : $row->no_ep); ?></strong>
                                        <?php if ((int)$row->no_urut !== (int)$row->no_ep): ?>
                                            <div class="small text-muted">no. resmi: <?php echo html_escape($row->no_ep); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo html_escape($row->isi_ep); ?></td>
                                    <td><?php echo (int)$row->skor_maks; ?></td>
                                    <td>
                                        <?php if ($row->active === 'Y'): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?php echo site_url('master_ep/elemen_form/' . $row->id_ep); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <?php if ($row->active === 'Y'): ?>
                                            <a href="<?php echo site_url('master_ep/elemen_delete/' . $row->id_ep); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Nonaktifkan data elemen penilaian ini? Data tidak akan dihapus permanen.')">Nonaktif</a>
                                        <?php else: ?>
                                            <a href="<?php echo site_url('master_ep/elemen_toggle/' . $row->id_ep . '/Y'); ?>" class="btn btn-sm btn-outline-success">Aktifkan</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
