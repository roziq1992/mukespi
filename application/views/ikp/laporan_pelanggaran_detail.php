<div class="container-fluid py-3">
    <div class="card shadow">
        <div class="card-header">
            <h5 class="m-0 text-danger"><i class="fas fa-user-shield mr-2"></i>Detail Laporan Pelanggaran</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-success" role="note">
                <strong>Saluran aman:</strong> Kerahasiaan dijamin dan pelapor dilindungi dari pembalasan.
            </div>
            <dl class="row">
                <dt class="col-sm-3">Nama Pelapor</dt>
                <dd class="col-sm-9"><?php echo html_escape($laporan->nama_pelapor); ?></dd>
                <dt class="col-sm-3">Tanggal Kejadian</dt>
                <dd class="col-sm-9"><?php echo html_escape(date('d-m-Y', strtotime($laporan->tanggal_kejadian))); ?></dd>
                <dt class="col-sm-3">Lokasi</dt>
                <dd class="col-sm-9"><?php echo html_escape($laporan->lokasi); ?></dd>
                <dt class="col-sm-3">Nama Staf Terlibat</dt>
                <dd class="col-sm-9"><?php echo nl2br(html_escape($laporan->nama_staf_terlibat)); ?></dd>
                <dt class="col-sm-3">Uraian Kejadian</dt>
                <dd class="col-sm-9"><?php echo nl2br(html_escape($laporan->uraian_kejadian)); ?></dd>
                <dt class="col-sm-3">Waktu Laporan</dt>
                <dd class="col-sm-9"><?php echo html_escape($laporan->created_at); ?></dd>
            </dl>
            <a href="<?php echo site_url('insiden/pelanggaran'); ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
