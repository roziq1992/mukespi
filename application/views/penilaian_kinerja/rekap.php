<style>
@media print {
    body * { visibility:hidden; }
    #printArea, #printArea * { visibility:visible; }
    #printArea { position:absolute; left:0; top:0; width:100%; }
    .pk-no-print { display:none !important; }
}
</style>

<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3 pk-no-print">
        <div>
            <a href="<?php echo site_url('penilaian_kinerja'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Rekap Penilaian Kinerja</h1>
        </div>
        <div>
            <button class="btn btn-outline-primary btn-sm" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
        </div>
    </div>

    <form method="get" class="pk-no-print" action="<?php echo site_url('penilaian_kinerja/rekap'); ?>">
        <div class="row mb-3">
            <div class="col-md-4">
                <select name="periode" class="form-control form-control-sm">
                    <?php foreach ($periodes as $pd): ?>
                    <option value="<?php echo $pd->id_periode; ?>" <?php echo ($periode && $pd->id_periode == $periode->id_periode) ? 'selected' : ''; ?>><?php echo html_escape($pd->nama); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <select name="unit" class="form-control form-control-sm">
                    <option value="">Semua Unit</option>
                    <?php foreach ($units as $u): ?>
                    <option value="<?php echo $u->id_unit; ?>" <?php echo ($id_unit && $u->id_unit == $id_unit) ? 'selected' : ''; ?>><?php echo html_escape($u->nm_unit); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Tampilkan</button>
            </div>
        </div>
    </form>

    <div id="printArea">
        <div class="text-center mb-3">
            <div class="font-weight-bold" style="font-size:1.05rem;">REKAP PENILAIAN KINERJA PEGAWAI RS AIRLANGGA</div>
            <div class="text-muted small"><?php echo $periode ? html_escape($periode->nama) : '-'; ?></div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th rowspan="2" class="text-center align-middle">Unit</th>
                                <th rowspan="2" class="text-center align-middle">Pegawai</th>
                                <th rowspan="2" class="text-center align-middle">Jabatan</th>
                                <th rowspan="2" class="text-center align-middle">Penilai</th>
                                <th rowspan="2" class="text-center align-middle">Tanggal</th>
                                <th colspan="2" class="text-center">Hasil</th>
                            </tr>
                            <tr>
                                <th class="text-center">Nilai</th>
                                <th class="text-center">Predikat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($rekap)): ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data penilaian pada periode ini.</td></tr>
                            <?php endif; ?>
                            <?php $first = TRUE; foreach ($rekap as $g): ?>
                                <?php foreach ($g['items'] as $i => $r): ?>
                                <tr>
                                    <?php if ($i === 0): ?>
                                    <td rowspan="<?php echo count($g['items']); ?>" class="align-middle font-weight-bold"><?php echo html_escape($g['nm_unit']); ?></td>
                                    <?php endif; ?>
                                    <td class="align-middle">
                                        <div class="font-weight-bold"><?php echo html_escape($r->nama_dinilai); ?></div>
                                        <small class="text-muted"><?php echo html_escape($r->email); ?></small>
                                    </td>
                                    <td><?php echo html_escape($r->jabatan ?: '-'); ?></td>
                                    <td><?php echo html_escape($r->nama_penilai); ?></td>
                                    <td><?php echo $r->tanggal_penilaian ? date('d M Y', strtotime($r->tanggal_penilaian)) : '-'; ?></td>
                                    <td class="text-center">
                                        <?php if ($r->status === 'selesai'): ?>
                                        <span class="font-weight-bold"><?php echo number_format($r->total_nilai, 2); ?></span>
                                        <?php else: ?>
                                        <span class="text-warning font-weight-bold"><?php echo number_format($r->total_nilai, 2); ?>*</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-primary"><?php echo html_escape($r->predikat); ?></span>
                                        <?php if ($r->status !== 'selesai'): ?><span class="small text-muted">(draft)</span><?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-6"></div>
            <div class="col-6 text-center">
                <div>Mengetahui,</div>
                <div style="height:70px;"></div>
                <div class="font-weight-bold border-top pt-2 d-inline-block px-4">Direktur RS Airlangga</div>
            </div>
        </div>
    </div>
</div>