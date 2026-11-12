<div class="container-fluid py-3">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0 text-primary"><i class="fas fa-shield-alt mr-2"></i>Pelaporan Budaya Keselamatan</h5>
            <span class="badge badge-primary">Total: <?php echo (int) $total_rows; ?></span>
        </div>
        <div class="card-body">
            <?php $message = $this->session->flashdata('message'); ?>
            <?php if ($message): ?>
                <div class="alert alert-info"><?php echo html_escape($message); ?></div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6 mb-2">
                    <a href="<?php echo site_url('insiden/create?jenis_pelaporan=budaya_keselamatan'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Buat Laporan
                    </a>
                </div>
                <div class="col-md-6">
                    <form method="get" action="<?php echo site_url('insiden/budaya_keselamatan'); ?>">
                        <div class="input-group">
                            <input type="search" class="form-control" name="q" value="<?php echo html_escape($q); ?>" placeholder="Cari nama, lokasi, atau uraian...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal Kejadian</th>
                            <th>Lokasi</th>
                            <th>Staf Terlibat / Pengunjung</th>
                            <th>Uraian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporan)): ?>
                            <tr><td colspan="7" class="text-center text-muted">Belum ada laporan budaya keselamatan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($laporan as $row): ?>
                                <tr>
                                    <td><?php echo ++$start; ?></td>
                                    <td><?php echo html_escape($row->nama_pelapor); ?></td>
                                    <td><?php echo html_escape(date('d-m-Y', strtotime($row->tanggal_kejadian))); ?></td>
                                    <td><?php echo html_escape($row->lokasi); ?></td>
                                    <td><?php echo html_escape($row->nama_staf_pengunjung); ?></td>
                                    <td><?php echo html_escape(mb_strimwidth($row->uraian_kejadian, 0, 100, '...')); ?></td>
                                    <td class="text-nowrap">
                                        <a class="btn btn-sm btn-info" href="<?php echo site_url('insiden/budaya_keselamatan_detail/' . (int) $row->id_budaya_keselamatan); ?>" title="Detail"><i class="fas fa-eye"></i></a>
                                        <form method="post" action="<?php echo site_url('insiden/hapus_budaya_keselamatan/' . (int) $row->id_budaya_keselamatan); ?>" class="d-inline" onsubmit="return confirm('Hapus laporan ini?');">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $pagination; ?>
        </div>
    </div>
</div>
