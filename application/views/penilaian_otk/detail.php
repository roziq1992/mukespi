<style>
.pk-result { font-family:'Nunito',sans-serif; }
.pk-result .table th { font-size:.75rem; text-transform:uppercase; letter-spacing:.04em; }
.pk-total-box { background:linear-gradient(135deg,#102a43,#1f4e79); color:#fff; border-radius:12px; padding:18px 24px; }
.pk-total-box .pk-big { font-size:2rem; font-weight:800; line-height:1; }
.pk-predikat { font-size:1rem; font-weight:800; }
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
            <a href="<?php echo site_url('penilaian_otk'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Detail Penilaian Kinerja </h1>
        </div>
        <div>
            <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
        </div>
    </div>

    <div id="printArea">
        <div class="card shadow mb-3">
            <div class="card-body pk-result">
                <div class="text-center mb-3">
                    <div class="font-weight-bold" style="font-size:1.1rem;">FORMULIR PENILAIAN KINERJA </div>
                    <div class="text-muted">RS AIRLANGGA &mdash; <?php echo html_escape($penilaian->nama_periode); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md">
                        <div class="small text-muted">PEGAWAI YANG DINILAI</div>
                        <div class="font-weight-bold"><?php echo html_escape($penilaian->nama_dinilai); ?></div>
                        <small class="text-muted"><?php echo html_escape($penilaian->jabatan_dinilai); ?></small>
                    </div>
                    <div class="col-md">
                        <div class="small text-muted">UNIT KERJA</div>
                        <div class="font-weight-bold"><?php echo html_escape($penilaian->nm_unit); ?></div>
                    </div>
                    <div class="col-md">
                        <div class="small text-muted">PENILAI</div>
                        <div class="font-weight-bold"><?php echo html_escape($penilaian->nama_penilai); ?></div>
                        <small class="text-muted"><?php echo html_escape($penilaian->jabatan_penilai); ?></small>
                    </div>
                    <div class="col-md">
                        <div class="small text-muted">TANGGAL</div>
                        <div class="font-weight-bold"><?php echo $penilaian->tanggal_penilaian ? date('d M Y', strtotime($penilaian->tanggal_penilaian)) : '-'; ?></div>
                    </div>
                </div>

                <?php if (empty($groups)): ?>
                <div class="alert alert-warning">Belum ada kriteria penilaian untuk unit ini.</div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:40px;">No</th>
                                <th>Komponen / Kriteria</th>
                                <th class="text-center" style="width:80px;">Bobot</th>
                                <th class="text-center" style="width:80px;">Skor</th>
                                <th class="text-center" style="width:100px;">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0; foreach ($groups as $kelompok => $grp): ?>
                            <tr class="table-primary">
                                <td colspan="2"><strong><?php echo html_escape($kelompok); ?></strong></td>
                                <td class="text-center"><strong><?php echo number_format($grp['bobot'], 2); ?></strong></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php foreach ($grp['items'] as $it): $no++; ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><?php echo html_escape($it['kriteria']); ?></td>
                                <td class="text-center"><?php echo number_format($it['bobot'], 2); ?></td>
                                <td class="text-center"><strong><?php echo $it['skor'] > 0 ? $it['skor'] : '-'; ?></strong></td>
                                <td class="text-center"><?php echo number_format($it['kontribusi'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="table-light">
                                <td colspan="4" class="text-right font-weight-bold">SUB TOTAL: <?php echo html_escape($kelompok); ?></td>
                                <td class="text-center font-weight-bold"><?php echo number_format(array_sum(array_column($grp['items'], 'kontribusi')), 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-5">
                        <div class="pk-total-box">
                            <div class="small text-uppercase font-weight-bold" style="opacity:.75;letter-spacing:.1em;">Nilai Akhir</div>
                            <div class="pk-big"><?php echo number_format($total, 2); ?></div>
                            <div class="pk-predikat"><i class="fas fa-award"></i> <?php echo html_escape($predikat); ?></div>
                            <div class="small mt-1" style="opacity:.8;">Status: <?php echo ucfirst($penilaian->status); ?></div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <?php if (!empty($gradings)): ?>
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light"><tr><th colspan="3">KETERANGAN / PREDIKAT</th></tr></thead>
                            <tbody>
                                <?php foreach ($gradings as $g): ?>
                                <tr>
                                    <td style="width:50%;" class="small"><?php echo html_escape($g->label); ?></td>
                                    <td class="small text-center"><?php echo number_format($g->nilai_min, 0); ?> &ndash; <?php echo number_format($g->nilai_max, 0); ?></td>
                                    <td></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                        <?php if (!empty($penilaian->catatan)): ?>
                        <div class="alert alert-light border mt-2 mb-0">
                            <strong class="small">Catatan:</strong>
                            <div class="small"><?php echo nl2br(html_escape($penilaian->catatan)); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tanda tangan -->
                <div class="row mt-5">
                    <div class="col-4 text-center">
                        <div>Pegawai yang dinilai,</div>
                        <div style="height:60px;"></div>
                        <div class="font-weight-bold">( <?php echo html_escape($penilaian->nama_dinilai); ?> )</div>
                    </div>
                    <div class="col-4 text-center">
                        <div>Penilai,</div>
                        <div style="height:60px;"></div>
                        <div class="font-weight-bold">( <?php echo html_escape($penilaian->nama_penilai); ?> )</div>
                    </div>
                    <div class="col-4 text-center">
                        <div>Mengetahui,</div>
                        <div style="height:60px;"></div>
                        <div class="font-weight-bold">( Direktur )</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>