<div class="container-fluid py-3">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0 text-danger"><i class="fas fa-hard-hat mr-2"></i>Laporan Insiden K3RS</h5>
            <span class="badge badge-danger">Total: <?php echo (int) $total_rows; ?></span>
        </div>
        <div class="card-body">
            <?php $message = $this->session->flashdata('message'); ?>
            <?php if ($message): ?>
                <div class="alert alert-info"><?php echo html_escape($message); ?></div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-md-6 mb-2">
                    <a href="<?php echo site_url('insiden/create?jenis_pelaporan=insiden_k3rs'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Buat Laporan
                    </a>
                </div>
                <div class="col-md-6">
                    <form method="get" action="<?php echo site_url('insiden/insiden_k3rs'); ?>">
                        <div class="input-group">
                            <input type="search" class="form-control" name="q" value="<?php echo html_escape($q); ?>" placeholder="Cari pekerjaan, lokasi, atau kronologi...">
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
                        <tr><th>No</th><th>Tanggal</th><th>Waktu</th><th>Pekerjaan</th><th>Lokasi</th><th>Peralatan</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporan)): ?>
                            <tr><td colspan="7" class="text-center text-muted">Belum ada laporan insiden K3RS.</td></tr>
                        <?php else: ?>
                            <?php foreach ($laporan as $row): ?>
                                <tr>
                                    <td><?php echo ++$start; ?></td>
                                    <td><?php echo html_escape(date('d-m-Y', strtotime($row->tanggal))); ?></td>
                                    <td><?php echo html_escape(substr($row->waktu, 0, 5)); ?></td>
                                    <td><?php echo html_escape($row->pekerjaan); ?></td>
                                    <td><?php echo html_escape($row->lokasi); ?></td>
                                    <td><?php echo html_escape($row->peralatan_kerja); ?></td>
                                    <td class="text-nowrap">
                                        <a class="btn btn-sm btn-info" href="<?php echo site_url('insiden/insiden_k3rs_detail/' . (int) $row->id_laporan_insiden_k3rs); ?>" title="Detail"><i class="fas fa-eye"></i></a>
                                        <form method="post" action="<?php echo site_url('insiden/hapus_insiden_k3rs/' . (int) $row->id_laporan_insiden_k3rs); ?>" class="d-inline" onsubmit="return confirm('Hapus laporan ini?');">
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
