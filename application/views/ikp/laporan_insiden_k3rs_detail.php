<?php
$faktor = array();
foreach (array(
    'kondisi_bahaya' => 'Kondisi Bahaya',
    'tindakan_bahaya' => 'Tindakan Bahaya',
    'pribadi' => 'Pribadi',
    'pekerjaan_faktor' => 'Pekerjaan',
    'kurang_prosedur' => 'Kurang Prosedur',
    'kurang_sarana' => 'Kurang Sarana',
    'kurang_taat' => 'Kurang Taat',
) as $field => $label) {
    if ($laporan->$field) $faktor[] = $label;
}
?>
<div class="container-fluid py-3">
    <div class="card shadow">
        <div class="card-header">
            <h5 class="m-0 text-danger"><i class="fas fa-hard-hat mr-2"></i>Detail Laporan Insiden K3RS</h5>
        </div>
        <div class="card-body">
            <h6 class="text-primary">A. Insiden</h6>
            <dl class="row">
                <dt class="col-sm-3">Tanggal / Waktu</dt><dd class="col-sm-9"><?php echo html_escape(date('d-m-Y', strtotime($laporan->tanggal)) . ' ' . substr($laporan->waktu, 0, 5)); ?></dd>
                <dt class="col-sm-3">Pekerjaan</dt><dd class="col-sm-9"><?php echo html_escape($laporan->pekerjaan); ?></dd>
                <dt class="col-sm-3">Lokasi</dt><dd class="col-sm-9"><?php echo html_escape($laporan->lokasi); ?></dd>
                <dt class="col-sm-3">Peralatan Kerja</dt><dd class="col-sm-9"><?php echo html_escape($laporan->peralatan_kerja ?: '-'); ?></dd>
                <dt class="col-sm-3">Kerugian Aset/Material</dt><dd class="col-sm-9"><?php echo nl2br(html_escape($laporan->kerugian_aset ?: '-')); ?></dd>
                <dt class="col-sm-3">Kerugian Lingkungan</dt><dd class="col-sm-9"><?php echo nl2br(html_escape($laporan->kerugian_lingkungan ?: '-')); ?></dd>
                <dt class="col-sm-3">Kronologi</dt><dd class="col-sm-9"><?php echo nl2br(html_escape($laporan->kronologi)); ?></dd>
            </dl>

            <h6 class="text-primary mt-4">B. Korban</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light"><tr><th>Nama</th><th>L/P</th><th>Usia</th><th>Jabatan</th><th>Bagian</th><th>Cedera</th><th>Penanganan</th><th>LT (hari)</th><th>Kategori</th></tr></thead>
                    <tbody>
                        <?php if (!$laporan->korban): ?><tr><td colspan="9" class="text-center text-muted">Tidak ada korban yang dicatat.</td></tr><?php endif; ?>
                        <?php foreach ($laporan->korban as $korban): ?>
                            <tr>
                                <td><?php echo html_escape($korban->nama); ?></td><td><?php echo html_escape($korban->kelamin ?: '-'); ?></td>
                                <td><?php echo html_escape($korban->usia ?: '-'); ?></td><td><?php echo html_escape($korban->jabatan ?: '-'); ?></td>
                                <td><?php echo html_escape($korban->bagian ?: '-'); ?></td><td><?php echo nl2br(html_escape($korban->cedera ?: '-')); ?></td>
                                <td><?php echo nl2br(html_escape($korban->penanganan ?: '-')); ?></td><td><?php echo $korban->lt === NULL ? '-' : (int) $korban->lt; ?></td>
                                <td><?php echo html_escape($korban->kategori ?: '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <h6 class="text-primary mt-4">C. Investigasi Kecelakaan</h6>
            <dl class="row">
                <dt class="col-sm-3">Penyebab Langsung</dt><dd class="col-sm-9"><?php echo nl2br(html_escape($laporan->penyebab_langsung ?: '-')); ?></dd>
                <dt class="col-sm-3">Penyebab Tidak Langsung</dt><dd class="col-sm-9"><?php echo nl2br(html_escape($laporan->penyebab_tidak_langsung ?: '-')); ?></dd>
                <dt class="col-sm-3">Kategori Penyebab</dt><dd class="col-sm-9"><?php echo html_escape($faktor ? implode(', ', $faktor) : '-'); ?></dd>
            </dl>

            <h6 class="text-primary mt-4">D. Perbaikan &amp; Pencegahan</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light"><tr><th>Jenis Tindakan</th><th>Rencana Tindakan</th><th>Target</th><th>Wewenang</th></tr></thead>
                    <tbody>
                        <?php if (!$laporan->tindakan): ?><tr><td colspan="4" class="text-center text-muted">Belum ada rencana tindakan.</td></tr><?php endif; ?>
                        <?php foreach ($laporan->tindakan as $tindakan): ?>
                            <tr>
                                <td><?php echo html_escape($tindakan->jenis); ?></td>
                                <td><?php echo nl2br(html_escape($tindakan->rencana)); ?></td>
                                <td><?php echo html_escape(date('d-m-Y', strtotime($tindakan->target))); ?></td>
                                <td><?php echo html_escape($tindakan->wewenang); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <h6 class="text-primary mt-4">Saksi dan Pengesahan</h6>
            <dl class="row">
                <dt class="col-sm-3">Saksi</dt><dd class="col-sm-9"><?php echo html_escape($laporan->saksi ?: '-'); ?></dd>
                <dt class="col-sm-3">Ketua Tim K3RS</dt><dd class="col-sm-9"><?php echo html_escape($laporan->ketua_tim ?: '-'); ?></dd>
                <dt class="col-sm-3">Tanggal Disusun</dt><dd class="col-sm-9"><?php echo $laporan->tanggal_disusun ? html_escape(date('d-m-Y', strtotime($laporan->tanggal_disusun))) : '-'; ?></dd>
                <dt class="col-sm-3">Mengetahui</dt><dd class="col-sm-9">Direktur Rumah Sakit Airlangga Jombang</dd>
                <dt class="col-sm-3">Waktu Laporan</dt><dd class="col-sm-9"><?php echo html_escape($laporan->created_at); ?></dd>
            </dl>
            <a href="<?php echo site_url('insiden/insiden_k3rs'); ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
