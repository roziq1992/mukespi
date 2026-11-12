<div class="container-fluid py-3">
    <div class="card shadow">
        <div class="card-header">
            <h5 class="m-0 text-primary"><i class="fas fa-shield-alt mr-2"></i>Detail Pelaporan Budaya Keselamatan</h5>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Nama</dt>
                <dd class="col-sm-9"><?php echo html_escape($laporan->nama_pelapor); ?></dd>
                <dt class="col-sm-3">Tanggal Kejadian</dt>
                <dd class="col-sm-9"><?php echo html_escape(date('d-m-Y', strtotime($laporan->tanggal_kejadian))); ?></dd>
                <dt class="col-sm-3">Lokasi</dt>
                <dd class="col-sm-9"><?php echo html_escape($laporan->lokasi); ?></dd>
                <dt class="col-sm-3">Nama Staf Terlibat / Pengunjung</dt>
                <dd class="col-sm-9"><?php echo nl2br(html_escape($laporan->nama_staf_pengunjung)); ?></dd>
                <dt class="col-sm-3">Uraian Kejadian</dt>
                <dd class="col-sm-9"><?php echo nl2br(html_escape($laporan->uraian_kejadian)); ?></dd>
                <dt class="col-sm-3">Waktu Laporan</dt>
                <dd class="col-sm-9"><?php echo html_escape($laporan->created_at); ?></dd>
            </dl>
            <a href="<?php echo site_url('insiden/budaya_keselamatan'); ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
