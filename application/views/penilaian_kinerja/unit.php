<style>
.pk-ava { width:40px; height:40px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#4f46e5,#312e81); color:#fff; font-weight:800; font-size:.8rem; }
</style>

<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <a href="<?php echo site_url('penilaian_kinerja'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Penilaian Kinerja &mdash; <?php echo html_escape($unit->nm_unit); ?></h1>
        </div>
        <form method="get" class="form-inline">
            <select name="periode" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                <?php foreach ($periodes as $pd): ?>
                <option value="<?php echo $pd->id_periode; ?>" <?php echo ($periode && $pd->id_periode == $periode->id_periode) ? 'selected' : ''; ?>>
                    <?php echo html_escape($pd->nama); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if (!$periode): ?>
    <div class="alert alert-warning">Belum ada periode penilaian. Hubungi administrator.</div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users"></i> Daftar Pegawai yang Dinilai</h6>
            <?php if (!empty($kepala_list)): ?>
            <small class="text-muted"><i class="fas fa-user-tie"></i> Kepala unit: <?php echo implode(', ', array_map(function($k){ return html_escape($k->nama); }, $kepala_list)); ?></small>
            <?php endif; ?>
        </div>
        <div class="card-body p-0">
            <?php if (empty($items)): ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-user-slash fa-3x mb-3"></i>
                <p>Belum ada pegawai terdaftar pada unit ini.</p>
                <p class="small">Pegawai didaftarkan pada unit lewat data pegawai (set <strong>Unit</strong> pada menu Data Pegawai), lalu kepala unit ditetapkan di <strong>Kelola Kepala Unit</strong>.</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Pegawai</th>
                            <th>Jabatan</th>
                            <th>Role</th>
                            <th>Status Penilaian</th>
                            <th>Total</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $it): $s = $it['target']; $pn = $it['penilaian']; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="pk-ava mr-2"><?php echo strtoupper(substr($s->nama, 0, 1)); ?></span>
                                    <div>
                                        <div class="font-weight-bold"><?php echo html_escape($s->nama); ?></div>
                                        <small class="text-muted"><?php echo html_escape($s->email); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo html_escape($s->jabatan ?: '-'); ?></td>
                            <td><span class="badge badge-info"><?php echo html_escape($s->role_name ?: 'Pegawai'); ?></span></td>
                            <td>
                                <?php if ($pn): ?>
                                    <?php if ($pn->status === 'selesai'): ?><span class="badge badge-success">Selesai</span>
                                    <?php else: ?><span class="badge badge-warning">Draft</span><?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Belum dinilai</span>
                                <?php endif; ?>
                            </td>
                            <td class="font-weight-bold"><?php echo $pn && $pn->total_nilai !== NULL ? number_format($pn->total_nilai, 2) : '-'; ?></td>
                            <td class="text-right">
                                <?php if ($pn): ?>
                                <a href="<?php echo site_url('penilaian_kinerja/form/' . $s->id_pegawai . '/' . $unit->id_unit . '/' . $pn->id_periode); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i> Nilai/Ubah</a>
                                <a href="<?php echo site_url('penilaian_kinerja/detail/' . $pn->id_penilaian); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                <?php else: ?>
                                <a href="<?php echo site_url('penilaian_kinerja/form/' . $s->id_pegawai . '/' . $unit->id_unit . '/' . ($periode ? $periode->id_periode : 0)); ?>" class="btn btn-sm btn-primary"><i class="fas fa-clipboard-check"></i> Mulai Nilai</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>