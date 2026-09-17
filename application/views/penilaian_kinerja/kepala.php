<style>
.pk-ava { width:40px; height:40px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#4f46e5,#312e81); color:#fff; font-weight:800; font-size:.8rem; }
</style>

<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <a href="<?php echo site_url('penilaian_kinerja'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Penilaian Kepala Unit</h1>
            <div class="small text-muted mt-1">Penilai: <strong><?php echo html_escape($this->session->userdata('name')); ?></strong> &mdash; kriteria mengikuti unit masing-masing kepala.</div>
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
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-tie"></i> Daftar Kepala Unit</h6>
        </div>
        <div class="card-body p-0">
            <?php if (empty($targets)): ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-user-slash fa-3x mb-3"></i>
                <p>Belum ada pegawai yang ditetapkan sebagai Kepala Unit.</p>
                <p class="small">Tetapkan kepala unit lewat menu <strong>Kepala Unit</strong> pada data pegawai.</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Kepala Unit</th>
                            <th>Unit yang Dipimpin</th>
                            <th>Kriteria Unit</th>
                            <th>Status Penilaian</th>
                            <th>Total</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($targets as $t): $pn = $t->penilaian; ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="pk-ava mr-2"><?php echo strtoupper(substr($t->nama, 0, 1)); ?></span>
                                    <div>
                                        <div class="font-weight-bold"><?php echo html_escape($t->nama); ?></div>
                                        <small class="text-muted"><?php echo html_escape($t->jabatan ?: $t->email); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-info"><?php echo html_escape($t->nm_unit ?: '-'); ?></span></td>
                            <td>
                                <?php if (!empty($t->has_kriteria)): ?>
                                    <span class="badge badge-success">Tersedia</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Belum diatur</span>
                                <?php endif; ?>
                            </td>
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
                                <?php $periode_id = $periode ? (int) $periode->id_periode : 0; ?>
                                <?php if (empty($t->has_kriteria)): ?>
                                    <button class="btn btn-sm btn-outline-secondary" disabled title="Kriteria unit belum diatur"><i class="fas fa-ban"></i> Tidak bisa dinilai</button>
                                <?php elseif ($pn): ?>
                                    <a href="<?php echo site_url('penilaian_kinerja/form/' . $t->id_pegawai . '/' . $t->id_unit . '/' . $pn->id_periode); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i> Nilai/Ubah</a>
                                    <a href="<?php echo site_url('penilaian_kinerja/detail/' . $pn->id_penilaian); ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-eye"></i></a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('penilaian_kinerja/form/' . $t->id_pegawai . '/' . $t->id_unit . '/' . $periode_id); ?>" class="btn btn-sm btn-primary"><i class="fas fa-clipboard-check"></i> Mulai Nilai</a>
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
