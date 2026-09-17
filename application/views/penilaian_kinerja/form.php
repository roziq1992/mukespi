<style>
.pk-legend { font-size:.74rem; color:#718096; }
.pk-skor { min-width:90px; }
.pk-contrib { width:110px; text-align:right; font-weight:700; color:#172b4d; }
.pk-group-total { font-weight:800; color:#2563eb; }
.pk-grand-total { font-size:1.6rem; font-weight:800; color:#172b4d; }
.pk-grand-label { font-size:.7rem; text-transform:uppercase; letter-spacing:.1em; color:#718096; font-weight:800; }
.pk-progress-sub { color:#6b7280; font-size:.8rem; }
</style>

<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <a href="<?php echo isset($back_url) ? $back_url : site_url('penilaian_kinerja/unit/' . $unit->id_unit . ($periode ? '/' . $periode->id_periode : '')); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali ke daftar</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Formulir Penilaian Kinerja</h1>
            <div class="small text-muted mt-1">Unit: <strong><?php echo html_escape($unit->nm_unit); ?></strong> &mdash; Periode: <strong><?php echo html_escape($periode->nama); ?></strong></div>
        </div>
        <?php if ($penilaian): ?>
            <?php if ($penilaian->status === 'selesai'): ?><span class="badge badge-success badge-pill px-3 py-2">Selesai</span>
            <?php else: ?><span class="badge badge-warning badge-pill px-3 py-2">Draft</span><?php endif; ?>
        <?php endif; ?>
    </div>

    <form method="post" action="<?php echo site_url('penilaian_kinerja/save'); ?>">
        <input type="hidden" name="id_periode" value="<?php echo (int) $periode->id_periode; ?>">
        <input type="hidden" name="id_unit" value="<?php echo (int) $unit->id_unit; ?>">
        <input type="hidden" name="id_dinilai" value="<?php echo (int) $target->id_pegawai; ?>">

        <!-- Info pegawai yang dinilai -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <div class="small text-muted">PEGAWAI YANG DINILAI</div>
                        <div class="font-weight-bold"><?php echo html_escape($target->nama); ?></div>
                        <small class="text-muted"><?php echo html_escape($target->email); ?></small>
                    </div>
                    <div class="col-md">
                        <div class="small text-muted">PENILAI <?php echo (!empty($mode) && $mode === 'kepala') ? '(DIREKTUR / KABID)' : '(KEPALA UNIT)'; ?></div>
                        <div class="font-weight-bold"><?php echo html_escape($this->session->userdata('name')); ?></div>
                        <small class="text-muted"><?php echo html_escape($unit->nm_unit); ?></small>
                    </div>
                    <div class="col-md">
                        <div class="small text-muted">PERIODE</div>
                        <div class="font-weight-bold"><?php echo html_escape($periode->nama); ?></div>
                        <small class="text-muted">Tahun <?php echo (int) $periode->tahun; ?></small>
                    </div>
                </div>
                <hr>
                <div class="pk-legend">
                    <strong>Skala Penilaian:</strong>
                    <span class="badge badge-success">5 = Sangat Baik</span>
                    <span class="badge badge-primary">4 = Baik</span>
                    <span class="badge badge-info">3 = Cukup</span>
                    <span class="badge badge-warning">2 = Kurang</span>
                    <span class="badge badge-danger">1 = Sangat Kurang</span>
                </div>
            </div>
        </div>

        <?php
        $total_bobot = (float) $kriteria['total_bobot'];
        $idx = 0;
        ?>
        <?php if (empty($kriteria['groups'])): ?>
        <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Kriteria penilaian untuk unit <strong><?php echo html_escape($unit->nm_unit); ?></strong> belum diatur. Hubungi administrator untuk mengisi kriteria &amp; bobot.</div>
        <?php endif; ?>
        <?php foreach ($kriteria['groups'] as $kelompok => $grp): ?>
        <?php $gkey = 'g' . md5($kelompok); ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo html_escape($kelompok); ?></h6>
                <div class="pk-legend">Bobot kelompok: <strong><?php echo number_format($grp['bobot'], 2); ?>%</strong></div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:50px;">No</th>
                                <th>Kriteria</th>
                                <th style="width:90px;">Bobot</th>
                                <th style="width:200px;">Skor (1-5)</th>
                                <th style="width:130px;">Kontribusi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grp['items'] as $k): $idx++; ?>
                            <tr>
                                <td><?php echo $idx; ?></td>
                                <td><?php echo html_escape($k->kriteria); ?></td>
                                <td class="pk-bobot" data-bobot="<?php echo (float) $k->bobot; ?>"><?php echo number_format((float) $k->bobot, 2); ?></td>
                                <td>
                                    <select class="form-control form-control-sm pk-skor" name="skor[<?php echo (int) $k->id_kriteria; ?>]" data-kriteria="k<?php echo (int) $k->id_kriteria; ?>" data-grup="<?php echo $gkey; ?>">
                                        <option value="">-</option>
                                        <?php for ($s = 1; $s <= 5; $s++): ?>
                                        <option value="<?php echo $s; ?>" <?php echo (isset($skor_map[$k->id_kriteria]) && (int) $skor_map[$k->id_kriteria] === $s) ? 'selected' : ''; ?>><?php echo $s; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td class="pk-contrib" id="c<?php echo (int) $k->id_kriteria; ?>">0.00</td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="table-light">
                                <td colspan="4" class="text-right font-weight-bold">SUB TOTAL KELOMPOK</td>
                                <td class="pk-contrib pk-group-total" data-group="<?php echo $gkey; ?>">0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Catatan -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Catatan / Komentar</h6>
            </div>
            <div class="card-body">
                <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan, komentar, atau tindak lanjut atas hasil penilaian (opsional)."><?php echo $penilaian ? html_escape($penilaian->catatan) : ''; ?></textarea>
            </div>
        </div>

        <!-- Total & aksi -->
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="pk-grand-label">Total Nilai Akhir</div>
                        <div><span class="pk-grand-total" id="grandTotal">0.00</span> <span class="text-muted">/ 100</span></div>
                        <div class="pk-progress-sub"><span id="filledCount">0</span> / <?php echo $idx; ?> kriteria terisi</div>
                        <div class="progress mt-2" style="height:8px;"><div class="progress-bar" id="progressBar" style="width:0%"></div></div>
                    </div>
                    <div class="col-md-8 text-md-right">
                        <button type="submit" name="status" value="draft" class="btn btn-secondary"><i class="fas fa-save"></i> Simpan Draft</button>
                        <button type="submit" name="status" value="selesai" class="btn btn-success" onclick="return confirm('Selesaikan penilaian ini? Semua kriteria harus terisi dan nilai akhir akan dikunci.')"><i class="fas fa-check-circle"></i> Simpan &amp; Selesaikan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    var totalBobot = <?php echo (float) $total_bobot > 0 ? number_format($total_bobot, 2, '.', '') : 1; ?>;
    var totalItems = <?php echo (int) $idx; ?>;

    var selects = Array.prototype.slice.call(document.querySelectorAll('.pk-skor'));

    function skorValue(sel) { return sel.value === '' || sel.value === null ? 0 : parseInt(sel.value, 10); }

    function recompute() {
        var grand = 0, filled = 0, groups = {};
        selects.forEach(function (sel) {
            var row = sel.closest('tr');
            var bobotEl = row.querySelector('.pk-bobot');
            var bobot = parseFloat(bobotEl.getAttribute('data-bobot'));
            var skor = skorValue(sel);
            var key = sel.getAttribute('data-grup');
            var contrib = (skor / 5) * (bobot / totalBobot) * 100;

            var contribCell = document.getElementById('c' + sel.getAttribute('data-kriteria').replace(/^k/, ''));
            if (contribCell) contribCell.textContent = contrib.toFixed(2);

            if (!groups[key]) groups[key] = 0;
            groups[key] += contrib;
            grand += contrib;
            if (skor > 0) filled++;
        });

        Object.keys(groups).forEach(function (key) {
            var cell = document.querySelector('.pk-group-total[data-group="' + key + '"]');
            if (cell) cell.textContent = groups[key].toFixed(2);
        });

        document.getElementById('grandTotal').textContent = grand.toFixed(2);
        document.getElementById('filledCount').textContent = filled;
        var pct = Math.max(0, Math.min(100, Math.round(grand)));
        var bar = document.getElementById('progressBar');
        bar.style.width = pct + '%';
        bar.className = 'progress-bar' + (filled === totalItems && totalItems > 0 ? ' bg-success' : '');
    }

    selects.forEach(function (sel) { sel.addEventListener('change', recompute); });
    recompute(); // tampilkan nilai tersimpan saat halaman dimuat
})();
</script>