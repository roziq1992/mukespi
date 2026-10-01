<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between my-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Master Data SIPARDI</h1>
            <div class="small text-muted">Kelola Pokja, Standar, dan Elemen Penilaian EP</div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pokja</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int)$stats['pokja_aktif']; ?> aktif</div>
                            <div class="small text-muted"><?php echo (int)$stats['pokja_nonaktif']; ?> nonaktif</div>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo site_url('master_ep/pokja'); ?>" class="btn btn-sm btn-primary">Kelola</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Standar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int)$stats['standar_aktif']; ?> aktif</div>
                            <div class="small text-muted"><?php echo (int)$stats['standar_nonaktif']; ?> nonaktif</div>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo site_url('master_ep/standar'); ?>" class="btn btn-sm btn-success">Kelola</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Elemen Penilaian</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int)$stats['ep_aktif']; ?> aktif</div>
                            <div class="small text-muted"><?php echo (int)$stats['ep_nonaktif']; ?> nonaktif</div>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo site_url('master_ep/elemen'); ?>" class="btn btn-sm btn-info">Kelola</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
