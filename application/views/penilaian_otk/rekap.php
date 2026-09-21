<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <a href="<?php echo site_url('penilaian_otk'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Rekap Penilaian </h1>
        </div>
        <div class="btn-group">
            <?php if ($periode): ?>
            <a class="btn btn-success btn-sm" href="<?php echo site_url('penilaian_otk/export_excel/' . (int)$periode->id_periode); ?>"><i class="fas fa-file-excel"></i> Export Excel</a>
            <?php endif; ?>
            <button class="btn btn-primary btn-sm" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
        </div>
    </div>

    <?php if (!empty($periodes)): ?>
    <div class="card shadow mb-3">
        <div class="card-body py-2">
            <form method="get" action="<?php echo site_url('penilaian_otk/rekap'); ?>" class="form-inline">
                <label class="mr-2 small">Periode:</label>
                <select name="periode" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                    <?php foreach ($periodes as $p): ?>
                    <option value="<?php echo (int)$p->id_periode; ?>" <?php echo ($periode && (int)$p->id_periode === (int)$periode->id_periode) ? 'selected' : ''; ?>>
                        <?php echo html_escape($p->nama); ?> (<?php echo (int)$p->tahun; ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
                <span class="small text-muted">Status aktif: <strong><?php echo $periode && $periode->status === 'aktif' ? 'Ya' : 'Tidak'; ?></strong></span>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($periode && !empty($rekap)): ?>
    <?php foreach ($rekap as $id_penilai => $g): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-user-check"></i> <?php echo html_escape($g['nama']); ?>
                <div class="small text-muted"><?php echo html_escape($g['jabatan']); ?> &mdash; <?php echo html_escape($g['unit']); ?></div>
            </h6>
            <?php
            $cnt = count($g['items']);
            $done = count(array_filter($g['items'], function ($it) { return $it->status === 'selesai'; }));
            ?>
            <span class="badge badge-light"><?php echo $done; ?> / <?php echo $cnt; ?> selesai</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Pegawai Dinilai</th>
                            <th>Jabatan</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Predikat</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; foreach ($g['items'] as $it): $no++; ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td class="font-weight-bold"><?php echo html_escape($it->nama_dinilai); ?></td>
                            <td><?php echo html_escape($it->jabatan_dinilai); ?></td>
                            <td><?php echo html_escape($it->unit_dinilai); ?></td>
                            <td>
                                <?php if ($it->status === 'selesai'): ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php elseif ($it->status === 'draft'): ?>
                                    <span class="badge badge-warning">Draft</span>
                                <?php elseif ($it->status === 'ditolak'): ?>
                                    <span class="badge badge-danger">Ditolak</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Belum</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right font-weight-bold"><?php echo ($it->total_nilai !== NULL) ? number_format($it->total_nilai, 2) : '-'; ?></td>
                            <td class="text-right"><?php echo html_escape($it->predikat); ?></td>
                            <td class="text-right">
                                <?php if ($it->id_penilaian): ?>
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('penilaian_otk/detail/' . $it->id_penilaian); ?>"><i class="fas fa-eye"></i></a>
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('penilaian_otk/form/' . $it->id_penilai . '/' . $it->id_dinilai . '/' . $periode->id_periode); ?>"><i class="fas fa-edit"></i></a>
                                <?php if ($is_admin): ?>
                                <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus penilaian ini?')" href="<?php echo site_url('penilaian_otk/hapus/' . $it->id_penilaian); ?>"><i class="fas fa-trash"></i></a>
                                <?php endif; ?>
                                <?php else: ?>
                                <a class="btn btn-sm btn-primary" href="<?php echo site_url('penilaian_otk/form/' . $it->id_penilai . '/' . $it->id_dinilai . '/' . $periode->id_periode); ?>"><i class="fas fa-edit"></i> Isi</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="card shadow mb-4">
        <div class="card-body text-center text-muted py-5">
            <i class="fas fa-chart-bar fa-3x mb-3"></i>
            <p><?php echo $periode ? 'Belum ada data penilaian pada periode ini.' : 'Pilih periode penilaian untuk melihat rekap.'; ?></p>
        </div>
    </div>
    <?php endif; ?>
</div>