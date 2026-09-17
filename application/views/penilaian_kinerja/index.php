<style>
.pk-stat { background:#fff; border:1px solid #e4eaf2; border-radius:12px; padding:16px 18px; box-shadow:0 4px 14px rgba(23,43,77,.05); height:100%; }
.pk-stat .pk-stat-label { font-size:.68rem; text-transform:uppercase; letter-spacing:.08em; color:#718096; font-weight:800; }
.pk-stat .pk-stat-value { font-size:1.5rem; font-weight:800; color:#172b4d; margin-top:4px; }
.pk-stat .pk-stat-icon { width:38px; height:38px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; color:#fff; }
.pk-unit-card { display:flex; align-items:center; justify-content:space-between; background:#fff; border:1px solid #e4eaf2; border-radius:10px; padding:14px 16px; margin-bottom:10px; text-decoration:none!important; box-shadow:0 2px 8px rgba(23,43,77,.04); transition:.15s ease; }
.pk-unit-card:hover { border-color:#9dbbe8; transform:translateY(-2px); box-shadow:0 6px 16px rgba(23,43,77,.08); }
.pk-unit-name { color:#172b4d; font-weight:800; font-size:.9rem; }
.pk-unit-desc { color:#718096; font-size:.72rem; }
</style>

<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <h1 class="h3 mb-0 text-gray-800">Penilaian Kinerja Pegawai</h1>
        <?php if ($is_admin || $is_hrd): ?>
        <div class="btn-group">
            <?php if ($is_admin): ?>
            <a href="<?php echo site_url('penilaian_kinerja/kriteria'); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-sliders-h"></i> Kriteria &amp; Bobot</a>
            <a href="<?php echo site_url('penilaian_kinerja/kepala_unit'); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-user-tie"></i> Kepala Unit</a>
            <a href="<?php echo site_url('penilaian_kinerja/penilai'); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-user-shield"></i> Penilai Kepala Unit</a>
            <?php endif; ?>
            <a href="<?php echo site_url('penilaian_kinerja/periode'); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-calendar-alt"></i> Periode</a>
            <a href="<?php echo site_url('penilaian_kinerja/rekap'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-chart-bar"></i> Rekap Penilaian</a>
        </div>
        <?php endif; ?>
    </div>

    <?php if ($periode): ?>
    <div class="alert alert-primary py-2 px-3" style="font-size:.85rem;">
        <strong>Periode aktif:</strong> <?php echo html_escape($periode->nama); ?>
        <?php if ($periode->tanggal_mulai && $periode->tanggal_selesai): ?>
            (<?php echo date('d M Y', strtotime($periode->tanggal_mulai)); ?> &ndash; <?php echo date('d M Y', strtotime($periode->tanggal_selesai)); ?>)
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-warning py-2 px-3" style="font-size:.85rem;">Belum ada periode penilaian berstatus aktif. Hubungi administrator.</div>
    <?php endif; ?>

    <?php if ($is_admin || $is_hrd): ?>
    <!-- Ringkasan untuk admin / HRD -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="pk-stat d-flex align-items-center justify-content-between">
                <div>
                    <div class="pk-stat-label">Total Unit</div>
                    <div class="pk-stat-value"><?php echo count($kepala_units) ?: '-'; ?></div>
                </div>
                <span class="pk-stat-icon bg-primary"><i class="fas fa-building"></i></span>
            </div>
        </div>
        <div class="col-12">
            <div class="alert alert-info py-2 px-3 mb-3" style="font-size:.85rem;">
                <i class="fas fa-info-circle"></i> Gunakan menu <strong>Rekap Penilaian</strong> untuk melihat hasil penilaian seluruh unit/periode.
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($kepala_units)): ?>
    <!-- Unit yang dipimpin (kepala unit) -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-tie"></i> Unit yang Anda pimpin</h6>
        </div>
        <div class="card-body">
            <?php foreach ($kepala_units as $u): ?>
            <a class="pk-unit-card" href="<?php echo site_url('penilaian_kinerja/unit/' . $u->id_unit); ?>">
                <div>
                    <div class="pk-unit-name"><i class="fas fa-building text-primary mr-2"></i><?php echo html_escape($u->nm_unit); ?></div>
                    <div class="pk-unit-desc">Klik untuk menilai pegawai pada unit ini</div>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($can_assess_kepala)): ?>
    <!-- Penilaian Kepala Unit (Direktur / Kabid / Admin) -->
    <div class="card shadow mb-4 border-left-primary">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-tie"></i> Penilaian Kepala Unit</h6>
            <?php if ($is_admin): ?>
            <a href="<?php echo site_url('penilaian_kinerja/penilai'); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-user-shield"></i> Kelola Penilai</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <a class="pk-unit-card" href="<?php echo site_url('penilaian_kinerja/kepala'); ?>">
                <div>
                    <div class="pk-unit-name"><i class="fas fa-sitemap text-primary mr-2"></i>Nilai Kepala Unit</div>
                    <div class="pk-unit-desc">Menilai seluruh kepala unit dengan kriteria unit masing-masing</div>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($penilaian_saya)): ?>
    <!-- Penilaian terhadap saya -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clipboard-list"></i> Hasil Penilaian Saya</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Periode</th>
                            <th>Unit</th>
                            <th>Penilai</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($penilaian_saya as $p): ?>
                        <tr>
                            <td><?php echo html_escape($p->nama_periode); ?></td>
                            <td><?php echo html_escape($p->nm_unit); ?></td>
                            <td><?php echo html_escape($p->nama_penilai); ?></td>
                            <td><?php echo $p->tanggal_penilaian ? date('d M Y', strtotime($p->tanggal_penilaian)) : '-'; ?></td>
                            <td>
                                <?php if ($p->status === 'selesai'): ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="font-weight-bold"><?php echo ($p->total_nilai !== NULL) ? number_format($p->total_nilai, 2) : '-'; ?></td>
                            <td class="text-right"><a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('penilaian_kinerja/detail/' . $p->id_penilaian); ?>"><i class="fas fa-eye"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($kepala_units) && empty($penilaian_saya) && empty($can_assess_kepala) && !$is_admin && !$is_hrd): ?>
    <div class="card shadow mb-4">
        <div class="card-body text-center text-muted py-5">
            <i class="fas fa-clipboard-check fa-3x mb-3"></i>
            <p>Anda belum terdaftar sebagai kepala unit maupun pegawai yang dinilai pada periode ini.</p>
            <p class="small">Jika Anda kepala unit, hubungi administrator untuk menetapkan unit pada akun Anda (menu Kelola Kepala Unit).</p>
        </div>
    </div>
    <?php endif; ?>
</div>