<style>
.pk-legend { font-size:.74rem; color:#718096; }
.pk-skor { min-width:90px; }
.pk-contrib { width:110px; text-align:right; font-weight:700; color:#172b4d; }
.pk-group-total { font-weight:800; color:#2563eb; }
.pk-grand-total { font-size:1.6rem; font-weight:800; color:#172b4d; }
.pk-grand-label { font-size:.7rem; text-transform:uppercase; letter-spacing:.1em; color:#718096; font-weight:800; }
.pk-progress-sub { color:#6b7280; font-size:.8rem; }
.pb-intro { font-size:.78rem; color:#6b7280; }
.pb-tile { background:#fffaf0; border:1px solid #fde68a; border-radius:10px; padding:14px 16px; height:100%; }
.pb-tile-label { font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#b45309; }
.pb-tile-num { font-size:1.7rem; font-weight:800; color:#172b4d; line-height:1.2; }
.pb-tile-sub { font-size:.75rem; color:#927238; }
.pb-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 9px; border-radius:20px; font-size:.68rem; font-weight:700; }
</style>

<?php if (!function_exists('potk_stars')): ?>
<?php function potk_stars($n)
{
    $n = (int) $n;
    $s = '';
    for ($i = 1; $i <= 5; $i++) {
        $s .= $i <= $n
            ? '<i class="fas fa-star text-warning"></i>'
            : '<i class="far fa-star text-muted"></i>';
    }
    return $s;
} ?>
<?php endif; ?>

<?php if (!function_exists('potk_badge')): ?>
<?php function potk_badge($st)
{
    $st = (string) $st;
    $map = array(
        'menunggu'   => array('#fef3c7', '#b45309', 'fas fa-hourglass-half'),
        'divalidasi' => array('#d1fae5', '#065f46', 'fas fa-check-circle'),
        'ditolak'    => array('#fee2e2', '#991b1b', 'fas fa-times-circle'),
    );
    $m = isset($map[$st]) ? $map[$st] : array('#f1f5f9', '#334155', 'fas fa-question');
    $label = $st === '' ? 'menunggu' : $st;
    return '<span class="pb-badge" style="background:' . $m[0] . ';color:' . $m[1] . ';"><i class="' . $m[2] . '"></i> ' . ucfirst($label) . '</span>';
} ?>
<?php endif; ?>

<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <a href="<?php echo isset($back_url) ? $back_url : site_url('penilaian_otk'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali ke daftar</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Formulir Penilaian Kinerja</h1>
            <div class="small text-muted mt-1">Unit pegawai dinilai: <strong><?php echo html_escape($unit ? $unit->nm_unit : '-'); ?></strong> &mdash; Periode: <strong><?php echo html_escape($periode->nama); ?></strong></div>
        </div>
        <?php if ($penilaian): ?>
            <?php if ($penilaian->status === 'selesai'): ?><span class="badge badge-success badge-pill px-3 py-2">Selesai</span>
            <?php elseif ($penilaian->status === 'ditolak'): ?><span class="badge badge-danger badge-pill px-3 py-2">Ditolak</span>
            <?php else: ?><span class="badge badge-warning badge-pill px-3 py-2">Draft</span><?php endif; ?>
        <?php endif; ?>
    </div>

    <form method="post" action="<?php echo site_url('penilaian_otk/save'); ?>">
        <input type="hidden" name="id_periode" value="<?php echo (int) $periode->id_periode; ?>">
        <input type="hidden" name="id_penilai" value="<?php echo (int) $penilai->id_pegawai; ?>">
        <input type="hidden" name="id_dinilai" value="<?php echo (int) $target->id_pegawai; ?>">
        <input type="hidden" name="bypass" id="bypassField" value="0">

        <?php if (empty($dalam_jadwal)): ?>
        <div class="alert alert-warning">
            <i class="fas fa-calendar-times"></i> <strong>Di luar jadwal input:</strong> input penilaian periode ini hanya diperbolehkan
            <?php if ($periode->input_mulai && $periode->input_selesai): ?>
            pada tanggal <strong><?php echo date('d M Y', strtotime($periode->input_mulai)); ?></strong> s/d <strong><?php echo date('d M Y', strtotime($periode->input_selesai)); ?></strong>.
            <?php else: ?>
            sesuai periode input yang ditentukan.
            <?php endif; ?>
            Centang opsi di bawah untuk mengizinkan input di luar jadwal.
        </div>
        <?php endif; ?>

        <!-- Info pegawai yang dinilai -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <div class="small text-muted">PEGAWAI YANG DINILAI</div>
                        <div class="font-weight-bold"><?php echo html_escape($target->nama); ?></div>
                        <small class="text-muted"><?php echo html_escape($target->jabatan); ?></small>
                    </div>
                    <div class="col-md">
                        <div class="small text-muted">PENILAI</div>
                        <div class="font-weight-bold"><?php echo html_escape($penilai->nama); ?></div>
                        <small class="text-muted"><?php echo html_escape($penilai->jabatan); ?></small>
                    </div>
                    <div class="col-md">
                        <div class="small text-muted">PERIODE</div>
                        <div class="font-weight-bold"><?php echo html_escape($periode->nama); ?></div>
                        <small class="text-muted">Tahun <?php echo (int) $periode->tahun; ?><br>
                        Penilaian:
                        <?php echo $periode->tanggal_mulai ? date('d M Y', strtotime($periode->tanggal_mulai)) : '-'; ?> &ndash; <?php echo $periode->tanggal_selesai ? date('d M Y', strtotime($periode->tanggal_selesai)) : '-'; ?><br>
                        Input:
                        <?php echo $periode->input_mulai ? date('d M Y', strtotime($periode->input_mulai)) : '-'; ?> &ndash; <?php echo $periode->input_selesai ? date('d M Y', strtotime($periode->input_selesai)) : '-'; ?>
                        </small>
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

        <!-- Riwayat penilaian bintang (pelaporan) -->
        <div class="card shadow mb-4 border-left-warning">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-star mr-1"></i> Penilaian Bintang (Laporan / Pengaduan Karyawan)</h6>
                <?php if ($bintang_summary_all['count'] > 0): ?>
                <button type="button" class="btn btn-sm btn-outline-warning font-weight-bold" data-toggle="modal" data-target="#riwayatBintangModal">
                    <i class="fas fa-eye mr-1"></i> Lihat Riwayat
                </button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="pb-intro mb-3">
                    <i class="fas fa-info-circle mr-1"></i> Rekapitulasi bintang dari laporan / pengaduan atas <strong><?php echo html_escape($target->nama); ?></strong>.
                    Yang dihitung hanya bintang dengan status <strong>tervalidasi</strong> oleh Admin/HRD.
                    Klik <strong>Lihat Riwayat</strong> untuk menampilkan seluruh penilaian bintang tervalidasi.
                </div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="pb-tile">
                            <div class="pb-tile-label">Rata-rata — Tahun <?php echo (int) $bintang_tahun; ?></div>
                            <div class="pb-tile-num"><?php echo number_format((float) $bintang_summary_thn['avg'], 2, ',', '.'); ?> <small class="text-muted">/ 5</small></div>
                            <div style="font-size:1rem; margin:4px 0;"><?php echo potk_stars($bintang_summary_thn['avg']); ?></div>
                            <div class="pb-tile-sub">Dari <?php echo (int) $bintang_summary_thn['count']; ?> laporan pada tahun <?php echo (int) $bintang_tahun; ?></div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="pb-tile">
                            <div class="pb-tile-label">Akumulasi Semua Bintang</div>
                            <div class="pb-tile-num"><?php echo (int) $bintang_summary_all['total']; ?> <small class="text-muted">bintang</small></div>
                            <div style="font-size:.9rem; margin:4px 0;">Rata-rata <?php echo number_format((float) $bintang_summary_all['avg'], 2, ',', '.'); ?> dari <?php echo (int) $bintang_summary_all['count']; ?> laporan</div>
                            <div class="pb-tile-sub">Akumulasi seluruh tahun penilaian</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="pb-tile">
                            <div class="pb-tile-label">Jumlah Laporan</div>
                            <div class="pb-tile-num"><?php echo (int) $bintang_summary_all['count']; ?> <small class="text-muted">laporan</small></div>
                            <div style="font-size:.9rem; margin:4px 0;"><?php echo $bintang_summary_thn['count']; ?> laporan pada tahun <?php echo (int) $bintang_tahun; ?></div>
                            <div class="pb-tile-sub">Total masuknya penilaian bintang tentang karyawan ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $total_bobot = (float) $kriteria['total_bobot'];
        $idx = 0;
        ?>
        <?php if (empty($kriteria['groups'])): ?>
        <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Kriteria penilaian untuk unit pegawai yang dinilai belum diatur. Hubungi administrator untuk mengisi kriteria &amp; bobot (menu Kriteria &amp; Bobot &mdash; Penilaian Kinerja).</div>
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
                        <?php if (empty($dalam_jadwal)): ?>
                        <div class="form-check form-check-inline mb-2 mr-3">
                            <input type="checkbox" class="form-check-input" id="bypassJadwal">
                            <label class="form-check-label small" for="bypassJadwal">Izinkan input di luar jadwal input periode</label>
                        </div>
                        <?php endif; ?>
                        <button type="submit" name="status" value="draft" class="btn btn-secondary btn-submit-penilaian"><i class="fas fa-save"></i> Simpan Draft</button>
                        <button type="submit" name="status" value="selesai" class="btn btn-success btn-submit-penilaian" onclick="return confirm('Selesaikan penilaian ini? Semua kriteria harus terisi dan nilai akhir akan dikunci.')"><i class="fas fa-check-circle"></i> Simpan &amp; Selesaikan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal riwayat bintang -->
    <div class="modal fade" id="riwayatBintangModal" tabindex="-1" role="dialog" aria-labelledby="riwayatBintangLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius:14px; overflow:hidden;">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title font-weight-bold" id="riwayatBintangLabel">
                        <i class="fas fa-star mr-2"></i> Riwayat Penilaian Bintang
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity:.9;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="px-4 py-3 bg-light border-bottom" style="font-size:.85rem; color:#6b7280;">
                        Pegawai dinilai: <strong class="text-dark"><?php echo html_escape($target->nama); ?></strong> &mdash; <?php echo html_escape($target->jabatan ?: '-'); ?>
                        &nbsp;•&nbsp; Total <?php echo (int) $bintang_summary_all['count']; ?> laporan tervalidasi (akumulasi <?php echo (int) $bintang_summary_all['total']; ?> bintang)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:60px;">No</th>
                                    <th style="width:130px;">Tanggal</th>
                                    <th style="width:150px;">Bintang</th>
                                    <th style="width:110px;">Status</th>
                                    <th>Alasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($bintang_history) > 0): $no_b = 1; ?>
                                <?php foreach ($bintang_history as $bh): ?>
                                <tr>
                                    <td class="text-muted font-weight-bold"><?php echo $no_b++; ?></td>
                                    <td><?php echo date('d M Y H:i', strtotime($bh->created_at)); ?></td>
                                    <td>
                                        <span style="white-space:nowrap;"><?php echo potk_stars($bh->bintang); ?></span>
                                        <small class="text-muted d-block"><?php echo (int) $bh->bintang; ?>/5</small>
                                    </td>
                                    <td><?php echo potk_badge($bh->status); ?></td>
                                    <td class="text-muted small" style="max-width:260px;"><?php echo html_escape($bh->alasan ?: '-'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr><td colspan="5" class="text-center text-muted py-4"><i class="fas fa-inbox mr-1"></i> Belum ada penilaian bintang.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" style="border-radius:8px;" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
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
    recompute();

    var bypassCheck = document.getElementById('bypassJadwal');
    var bypassField = document.getElementById('bypassField');
    var submitBtns = Array.prototype.slice.call(document.querySelectorAll('.btn-submit-penilaian'));
    if (bypassCheck) {
        function toggleBypass() {
            var on = bypassCheck.checked;
            bypassField.value = on ? '1' : '0';
            submitBtns.forEach(function (btn) { btn.disabled = !on; });
        }
        bypassCheck.addEventListener('change', toggleBypass);
        toggleBypass();
    }
})();
</script>