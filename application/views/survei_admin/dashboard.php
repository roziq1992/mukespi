<div class="container-fluid sk-wrap">

<style>
	.sk-wrap {
		--sk-ink: #0f172a; --sk-ink2: #334155; --sk-muted: #64748b;
		--sk-line: #e2e8f0; --sk-blue: #2563eb; --sk-blue-dark: #1b3a5c;
		--sk-good: #10b981; --sk-warn: #f59e0b; --sk-bad: #ef4444;
		color: var(--sk-ink);
		padding-bottom: 10px;
	}
	.sk-wrap * { box-sizing: border-box; }

	/* ---------- header ---------- */
	.sk-header {
		background: linear-gradient(135deg, #102a43 0%, #1e4e79 62%, #2563eb 100%);
		color: #fff; border-radius: 14px; padding: 20px 24px;
		display: flex; justify-content: space-between; align-items: center;
		flex-wrap: wrap; gap: 14px; box-shadow: 0 6px 20px rgba(16,42,67,.18);
	}
	.sk-header h2 { margin: 0; font-size: 1.15rem; font-weight: 800; }
	.sk-header p { margin: 4px 0 0; font-size: .78rem; color: #cfe3f7; }
	.sk-header-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
	.sk-btn {
		display: inline-flex; align-items: center; gap: 6px;
		background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.32);
		color: #fff; border-radius: 9px; padding: 8px 14px;
		font-size: .78rem; font-weight: 700; text-decoration: none;
		transition: background .15s; white-space: nowrap;
	}
	.sk-btn:hover { background: rgba(255,255,255,.26); color: #fff; text-decoration: none; }
	.sk-btn.solid { background: #fff; color: var(--sk-blue-dark); border-color: #fff; }
	.sk-btn.solid:hover { background: #eaf2fb; color: var(--sk-blue-dark); }

	/* ---------- filter ---------- */
	.sk-filter {
		background: #fff; border: 1px solid var(--sk-line); border-radius: 12px;
		padding: 14px 18px; margin-top: 16px;
		display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;
		box-shadow: 0 2px 8px rgba(15,23,42,.04);
	}
	.sk-filter .f-item { display: flex; flex-direction: column; gap: 5px; }
	.sk-filter label { font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--sk-muted); }
	.sk-filter select {
		border: 1px solid var(--sk-line); border-radius: 9px;
		padding: 8px 11px; font-size: .8rem; min-width: 130px;
		background: #f8fafc; color: var(--sk-ink2);
	}
	.sk-filter select:focus { outline: none; border-color: var(--sk-blue); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
	.sk-filter .go {
		background: var(--sk-blue); color: #fff; border: none; border-radius: 9px;
		padding: 9px 18px; font-size: .8rem; font-weight: 700; cursor: pointer;
	}
	.sk-filter .go:hover { background: #1d4ed8; }
	.sk-filter .reset { font-size: .76rem; color: var(--sk-muted); text-decoration: none; padding-bottom: 9px; }
	.sk-filter .reset:hover { color: var(--sk-bad); }

	/* ---------- kpi ---------- */
	.sk-stats { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-top: 16px; }
	.sk-stat {
		background: #fff; border: 1px solid var(--sk-line); border-radius: 12px;
		padding: 15px 16px; box-shadow: 0 2px 8px rgba(15,23,42,.04);
		border-left: 4px solid var(--sk-blue);
	}
	.sk-stat .ico { font-size: .8rem; color: var(--sk-blue); margin-bottom: 8px; display: block; }
	.sk-stat .num { font-size: 1.75rem; font-weight: 800; line-height: 1; color: var(--sk-blue-dark); }
	.sk-stat .num small { font-size: .9rem; font-weight: 700; color: var(--sk-muted); }
	.sk-stat .lbl { font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; color: var(--sk-muted); margin-top: 7px; }
	.sk-stat .sub { font-size: .68rem; color: var(--sk-muted); margin-top: 4px; }
	.sk-stat.good { border-left-color: var(--sk-good); } .sk-stat.good .ico, .sk-stat.good .num { color: #059669; }
	.sk-stat.warn { border-left-color: var(--sk-warn); } .sk-stat.warn .ico { color: #d97706; } .sk-stat.warn .num { color: #b45309; }
	.sk-stat.bad  { border-left-color: var(--sk-bad); }  .sk-stat.bad .ico  { color: #dc2626; } .sk-stat.bad .num  { color: #b91c1c; }
	.sk-stat.nps  { border-left-color: #8b5cf6; }       .sk-stat.nps .ico  { color: #7c3aed; } .sk-stat.nps .num  { color: #6d28d9; }

	/* ---------- panel ---------- */
	.sk-row { display: grid; gap: 16px; margin-top: 16px; }
	.sk-row.two { grid-template-columns: 1.35fr 1fr; }
	.sk-row.half { grid-template-columns: 1fr 1fr; }
	.sk-panel { background: #fff; border: 1px solid var(--sk-line); border-radius: 12px; box-shadow: 0 2px 8px rgba(15,23,42,.04); }
	.sk-panel-head { padding: 14px 18px; border-bottom: 1px solid var(--sk-line); display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; }
	.sk-panel-head h3 { margin: 0; font-size: .88rem; font-weight: 800; color: var(--sk-blue-dark); }
	.sk-panel-head span { font-size: .7rem; color: var(--sk-muted); }
	.sk-panel-body { padding: 18px; }
	.sk-chart { position: relative; height: 300px; }
	.sk-chart.sm { height: 230px; }

	/* ---------- tabel ---------- */
	.sk-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: .8rem; }
	.sk-table thead th {
		background: #f8fafc; color: var(--sk-muted);
		font-size: .66rem; text-transform: uppercase; letter-spacing: .05em; font-weight: 800;
		padding: 10px 12px; text-align: left; border-bottom: 1px solid var(--sk-line);
	}
	.sk-table tbody td { padding: 11px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
	.sk-table tbody tr:hover { background: #f8fafc; }
	.sk-table tbody tr:last-child td { border-bottom: none; }
	.sk-aspek-cell { display: flex; align-items: center; gap: 9px; }
	.sk-aspek-ico { width: 28px; height: 28px; border-radius: 8px; background: #eff6ff; color: var(--sk-blue); display: inline-flex; align-items: center; justify-content: center; font-size: .72rem; flex-shrink: 0; }
	.sk-aspek-nm { font-weight: 700; color: var(--sk-ink); }
	.sk-aspek-ds { font-size: .68rem; color: var(--sk-muted); }
	.sk-meter { display: flex; align-items: center; gap: 8px; min-width: 130px; }
	.sk-meter-bar { flex: 1; height: 7px; background: #eef2f7; border-radius: 20px; overflow: hidden; }
	.sk-meter-fill { height: 100%; border-radius: 20px; }
	.sk-meter-val { font-weight: 800; font-size: .8rem; color: var(--sk-blue-dark); min-width: 34px; text-align: right; }

	/* ---------- badge ---------- */
	.sk-badge { display: inline-block; padding: 4px 11px; border-radius: 20px; font-size: .7rem; font-weight: 800; }
	.sk-badge.sb { background: #ecfdf5; color: #047857; }
	.sk-badge.b  { background: #eff6ff; color: #1d4ed8; }
	.sk-badge.c  { background: #fffbeb; color: #b45309; }
	.sk-badge.k  { background: #fef2f2; color: #b91c1c; }
	.sk-badge.netral { background: #f1f5f9; color: #475569; }
	.sk-badge.kritik { background: #fee2e2; color: #b91c1c; }

	/* ---------- link form ---------- */
	.sk-form-link { background: none; border: none; padding: 0; color: var(--sk-blue); font-weight: 700; font-size: .8rem; cursor: pointer; text-decoration: underline; }
	.sk-form-link:hover { color: #1d4ed8; }

	.sk-empty { text-align: center; padding: 36px 12px; color: var(--sk-muted); font-size: .8rem; }
	.sk-empty i { font-size: 1.9rem; color: #cbd5e1; display: block; margin-bottom: 10px; }

	@media (max-width: 1200px) { .sk-stats { grid-template-columns: repeat(3, 1fr); } }
	@media (max-width: 992px) { .sk-row.two, .sk-row.half { grid-template-columns: 1fr; } }
	@media (max-width: 576px) { .sk-stats { grid-template-columns: 1fr 1fr; } .sk-filter { flex-direction: column; align-items: stretch; } .sk-filter select { width: 100%; } }
</style>

<!-- ================= HEADER ================= -->
<div class="sk-header">
	<div>
		<h2><i class="fas fa-star"></i> Monitoring Survei Kepuasan Pasien</h2>
		<p>Pantau penilaian bintang, tren, dan keluhan pasien di seluruh unit</p>
	</div>
	<div class="sk-header-actions">
		<a class="sk-btn" href="<?= site_url('survei_admin/data'); ?>"><i class="fas fa-list"></i> Data Survei</a>
		<a class="sk-btn" href="<?= site_url('survei_admin/aspek'); ?>"><i class="fas fa-sliders-h"></i> Master Aspek</a>
		<a class="sk-btn" href="<?= site_url('survei_admin/ekspor') . '?' . http_build_query($f); ?>"><i class="fas fa-file-excel"></i> Ekspor</a>
		<a class="sk-btn solid" href="<?= site_url('survei'); ?>" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> Formulir Pasien</a>
	</div>
</div>

<!-- ================= FILTER ================= -->
<form method="get" action="<?= site_url('survei_admin'); ?>" class="sk-filter">
	<div class="f-item">
		<label for="f-bulan">Bulan</label>
		<select id="f-bulan" name="bulan">
			<option value="">Semua Bulan</option>
			<?php foreach ($bulan as $no => $nm): ?>
				<option value="<?= $no; ?>" <?= (isset($f['bulan']) && (int)$f['bulan'] === $no) ? 'selected' : ''; ?>><?= $nm; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="f-item">
		<label for="f-tahun">Tahun</label>
		<select id="f-tahun" name="tahun">
			<option value="">Semua Tahun</option>
			<?php foreach ($tahun as $th): ?>
				<option value="<?= $th; ?>" <?= (isset($f['tahun']) && (int)$f['tahun'] === $th) ? 'selected' : ''; ?>><?= $th; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="f-item">
		<label for="f-unit">Unit</label>
		<select id="f-unit" name="unit">
			<option value="">Semua Unit</option>
			<?php foreach ($unit as $u): ?>
				<option value="<?= (int)$u->id_unit; ?>" <?= (isset($f['id_unit']) && (int)$f['id_unit'] === (int)$u->id_unit) ? 'selected' : ''; ?>><?= html_escape($u->nm_unit); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="f-item">
		<label for="f-predikat">Kategori</label>
		<select id="f-predikat" name="predikat">
			<option value="">Semua Kategori</option>
			<?php foreach (array('Sangat Baik', 'Baik', 'Cukup', 'Buruk') as $p): ?>
				<option value="<?= $p; ?>" <?= (isset($f['predikat']) && $f['predikat'] === $p) ? 'selected' : ''; ?>><?= $p; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<button type="submit" class="go"><i class="fas fa-filter"></i> Terapkan</button>
	<?php if (!empty($f)): ?>
		<a class="reset" href="<?= site_url('survei_admin'); ?>"><i class="fas fa-times"></i> Reset</a>
	<?php endif; ?>
</form>

<!-- ================= KPI ================= -->
<div class="sk-stats">
	<div class="sk-stat">
		<i class="fas fa-users ico"></i>
		<div class="num"><?= number_format($statistik['total'], 0, ',', '.'); ?></div>
		<div class="lbl">Total Responden</div>
		<div class="sub"><?= $statistik['total'] > 0 ? 'Periode terpilih' : 'Belum ada data' ?></div>
	</div>
	<div class="sk-stat good">
		<i class="fas fa-star ico"></i>
		<div class="num"><?= number_format($statistik['rata'], 2, ',', '.'); ?><small> / 5,00</small></div>
		<div class="lbl">Rata-rata Bintang</div>
		<div class="sub">Skala 1 = Sangat Buruk s/d 5 = Sangat Baik</div>
	</div>
	<div class="sk-stat good">
		<i class="fas fa-thumbs-up ico"></i>
		<div class="num"><?= number_format($statistik['persen_tuntas'], 1, ',', '.'); ?><small>%</small></div>
		<div class="lbl">Pasien Puas</div>
		<div class="sub">Kategori Baik &amp; Sangat Baik</div>
	</div>
	<div class="sk-stat <?= $rekap_tl['menunggu'] > 0 ? 'warn' : 'good' ?>">
		<i class="<?= $rekap_tl['menunggu'] > 0 ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle' ?> ico"></i>
		<div class="num"><?= number_format($rekap_tl['menunggu'], 0, ',', '.'); ?></div>
		<div class="lbl">Perlu Tindak Lanjut</div>
		<div class="sub">
			<?= $rekap_tl['diproses']; ?> sedang diproses &middot; <?= $rekap_tl['belum_ada']; ?> belum ada tindak lanjut<?= $rekap_tl['selesai'] > 0 ? ' &middot; <b>' . $rekap_tl['selesai'] . ' selesai</b>' : '' ?>
		</div>
	</div>
	<div class="sk-stat nps">
		<i class="fas fa-bullhorn ico"></i>
		<div class="num"><?= $statistik['nps'] > 0 ? '+' : ''; ?><?= $statistik['nps']; ?></div>
		<div class="lbl">NPS</div>
		<div class="sub">Net Promoter Score (0&ndash;10)</div>
	</div>
</div>

<!-- ================= GRAFIK ================= -->
<div class="sk-row two">
	<div class="sk-panel">
		<div class="sk-panel-head">
			<h3><i class="fas fa-chart-bar"></i> Rata-rata Bintang per Aspek</h3>
			<span>Urutan dari nilai terendah (prioritas perbaikan)</span>
		</div>
		<div class="sk-panel-body">
			<?php if (empty($rekap_aspek)): ?>
				<div class="sk-empty"><i class="fas fa-chart-bar"></i>Belum ada jawaban survei pada periode ini.</div>
			<?php else: ?>
				<div class="sk-chart" style="height:<?= max(240, count($rekap_aspek) * 34); ?>px"><canvas id="skChartAspek"></canvas></div>
			<?php endif; ?>
		</div>
	</div>

	<div class="sk-panel">
		<div class="sk-panel-head">
			<h3><i class="fas fa-chart-pie"></i> Sebaran Bintang</h3>
			<span>Seluruh aspek yang dinilai</span>
		</div>
		<div class="sk-panel-body">
			<?php if (!$statistik['total']): ?>
				<div class="sk-empty"><i class="fas fa-chart-pie"></i>Belum ada data untuk ditampilkan.</div>
			<?php else: ?>
				<div class="sk-chart sm"><canvas id="skChartDonat"></canvas></div>
				<div style="margin-top:12px">
					<?php
					$warna = array(1 => '#ef4444', 2 => '#f97316', 3 => '#f59e0b', 4 => '#3b82f6', 5 => '#10b981');
					$totalSkor = array_sum($sebaran_skor);
					foreach ($sebaran_skor as $s => $jml):
						$pct = $totalSkor ? round($jml * 100 / $totalSkor, 1) : 0;
						?>
						<div style="display:flex;align-items:center;gap:9px;padding:5px 0;font-size:.76rem">
							<span style="width:9px;height:9px;border-radius:3px;background:<?= $warna[$s]; ?>;display:inline-block;flex-shrink:0"></span>
							<span style="flex:1;color:var(--sk-ink2)"><?= $jml; ?> &times; <?= $label_skor[$s]; ?></span>
							<strong><?= $pct; ?>%</strong>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="sk-row two">
	<div class="sk-panel">
		<div class="sk-panel-head">
			<h3><i class="fas fa-chart-line"></i> Tren Bulanan</h3>
			<span>Tahun <?= isset($f['tahun']) ? (int)$f['tahun'] : date('Y'); ?></span>
		</div>
		<div class="sk-panel-body">
			<div class="sk-chart"><canvas id="skChartTren"></canvas></div>
		</div>
	</div>

	<div class="sk-panel">
		<div class="sk-panel-head">
			<h3><i class="fas fa-building"></i> Peringkat Unit Layanan</h3>
			<span>10 unit terendah</span>
		</div>
		<div class="sk-panel-body" style="padding:0">
			<?php if (empty($rekap_unit)): ?>
				<div class="sk-empty"><i class="fas fa-building"></i>Belum ada data unit.</div>
			<?php else: ?>
				<table class="sk-table">
					<thead>
						<tr><th>Unit</th><th>Respon</th><th style="width:150px">Rata-rata</th></tr>
					</thead>
					<tbody>
					<?php foreach ($rekap_unit as $u): ?>
						<?php
						$rata = (float)$u->rata;
						$warna = $rata >= 4 ? '#10b981' : ($rata >= 3 ? '#3b82f6' : ($rata >= 2.5 ? '#f59e0b' : '#ef4444'));
						?>
						<tr>
							<td style="font-weight:700"><?= html_escape($u->nm_unit); ?></td>
							<td style="color:var(--sk-muted)"><?= (int)$u->jml; ?></td>
							<td>
								<div class="sk-meter">
									<div class="sk-meter-bar"><div class="sk-meter-fill" style="width:<?= $rata * 20; ?>%;background:<?= $warna; ?>"></div></div>
									<span class="sk-meter-val"><?= number_format($rata, 2, ',', '.'); ?></span>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- ================= RINCIAN ASPEK ================= -->
<div class="sk-row">
	<div class="sk-panel">
		<div class="sk-panel-head">
			<h3><i class="fas fa-list-alt"></i> Rincian Penilaian per Aspek</h3>
			<span><?= count($rekap_aspek); ?> aspek</span>
		</div>
		<div class="sk-panel-body" style="padding:0">
			<?php if (empty($rekap_aspek)): ?>
				<div class="sk-empty"><i class="fas fa-inbox"></i>Belum ada jawaban survei.</div>
			<?php else: ?>
				<table class="sk-table">
					<thead>
						<tr>
							<th>Aspek Layanan</th>
							<th>Jumlah</th>
							<th style="width:170px">Rata-rata</th>
							<th>Kategori</th>
							<th>Nilai 1&ndash;2</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($rekap_aspek as $a): ?>
						<?php
						$rata = (float)$a->rata;
						$kategori = $rata >= 4.01 ? 'Sangat Baik' : ($rata >= 3.01 ? 'Baik' : ($rata >= 2.01 ? 'Cukup' : 'Buruk'));
						$kls = $kategori === 'Sangat Baik' ? 'sb' : ($kategori === 'Baik' ? 'b' : ($kategori === 'Cukup' ? 'c' : 'k'));
						$warna = $rata >= 4 ? '#10b981' : ($rata >= 3 ? '#3b82f6' : ($rata >= 2.5 ? '#f59e0b' : '#ef4444'));
						$url_kritik = site_url('survei_admin/data') . '?' . http_build_query(array(
							'kritik' => 1,
							'bulan'  => isset($f['bulan']) ? (int) $f['bulan'] : '',
							'tahun'  => isset($f['tahun']) ? (int) $f['tahun'] : '',
							'unit'   => isset($f['id_unit']) ? (int) $f['id_unit'] : '',
						));
						?>
						<tr>
							<td>
								<div class="sk-aspek-cell">
									<span class="sk-aspek-ico"><i class="<?= html_escape($a->icon); ?>"></i></span>
									<div>
										<div class="sk-aspek-nm"><?= html_escape($a->nama_aspek); ?></div>
										<div class="sk-aspek-ds">Urutan tampil: <?= (int)$a->urutan; ?> &middot; Bobot: <?= (int)$a->bobot; ?></div>
									</div>
								</div>
							</td>
							<td><?= (int)$a->jml; ?></td>
							<td>
								<div class="sk-meter">
									<div class="sk-meter-bar"><div class="sk-meter-fill" style="width:<?= $rata * 20; ?>%;background:<?= $warna; ?>"></div></div>
									<span class="sk-meter-val"><?= number_format($rata, 2, ',', '.'); ?></span>
								</div>
							</td>
							<td><span class="sk-badge <?= $kls; ?>"><?= $kategori; ?></span></td>
							<td>
								<?php if ((int)$a->tidak_baik > 0): ?>
									<button class="sk-form-link" type="button"
											onclick="location.href='<?= $url_kritik; ?>'">
										<?= (int)$a->tidak_baik; ?> responden
									</button>
								<?php else: ?>
									<span style="color:var(--sk-muted)">0</span>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
	var FONT = { family: "Inter, system-ui, sans-serif" };
	var GRID = { color: "#eef2f7" };

	<?php
	$blnPendek = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des');
	$trenLabel = array(); $trenJml = array(); $trenRata = array();
	foreach ($tren as $t) {
		$trenLabel[] = $blnPendek[$t['bln']];
		$trenJml[] = (int) $t['jml'];
		$trenRata[] = (float) $t['rata'];
	}
	?>

	/* ---- Bar: rata-rata per aspek ---- */
	<?php if (!empty($rekap_aspek)): ?>
	var ctxAspek = document.getElementById('skChartAspek');
	if (ctxAspek) {
		var dataA = <?= json_encode(array_map(function ($a) {
			return array('n' => $a->nama_aspek, 'v' => (float) $a->rata, 'i' => (int) $a->jml);
		}, $rekap_aspek)); ?>;
		new Chart(ctxAspek, {
			type: 'bar',
			data: {
				labels: dataA.map(function (d) { return d.n; }),
				datasets: [{
					label: 'Rata-rata bintang',
					data: dataA.map(function (d) { return d.v; }),
					backgroundColor: dataA.map(function (d) {
						return d.v >= 4 ? '#10b981' : (d.v >= 3 ? '#3b82f6' : (d.v >= 2.5 ? '#f59e0b' : '#ef4444'));
					}),
					borderRadius: 6,
					maxBarThickness: 22
				}]
			},
			options: {
				indexAxis: 'y',
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: { display: false },
					tooltip: {
						callbacks: {
							afterLabel: function (c) { return 'Jumlah penilaian: ' + dataA[c.dataIndex].i; }
						}
					}
				},
				scales: {
					x: { min: 0, max: 5, ticks: { stepSize: 1, font: FONT }, grid: GRID },
					y: { ticks: { font: FONT }, grid: { display: false } }
				}
			}
		});
	}
	<?php endif; ?>

	/* ---- Donut: sebaran bintang ---- */
	<?php if ($statistik['total']): ?>
	var ctxDonat = document.getElementById('skChartDonat');
	if (ctxDonat) {
		new Chart(ctxDonat, {
			type: 'doughnut',
			data: {
				labels: <?= json_encode(array_map(function ($s) use ($label_skor) { return $label_skor[$s]; }, array_keys($sebaran_skor)), JSON_UNESCAPED_UNICODE); ?>,
				datasets: [{
					data: <?= json_encode(array_values($sebaran_skor)); ?>,
					backgroundColor: ['#ef4444', '#f97316', '#f59e0b', '#3b82f6', '#10b981'],
					borderWidth: 2,
					borderColor: '#fff'
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				cutout: '58%',
				plugins: { legend: { position: 'bottom', labels: { font: FONT, boxWidth: 12, padding: 12 } } }
			}
		});
	}
	<?php endif; ?>

	/* ---- Line: tren bulanan ---- */
	var ctxTren = document.getElementById('skChartTren');
	if (ctxTren) {
		new Chart(ctxTren, {
			data: {
				labels: <?= json_encode($trenLabel); ?>,
				datasets: [
					{
						type: 'line',
						label: 'Jumlah responden',
						data: <?= json_encode($trenJml); ?>,
						yAxisID: 'y',
						borderColor: '#2563eb',
						backgroundColor: 'rgba(37,99,235,.12)',
						fill: true,
						tension: .35,
						pointRadius: 3,
						borderWidth: 2
					},
					{
						type: 'line',
						label: 'Rata-rata bintang',
						data: <?= json_encode($trenRata); ?>,
						yAxisID: 'y1',
						borderColor: '#f59e0b',
						backgroundColor: 'rgba(245,158,11,.10)',
						fill: true,
						tension: .35,
						pointRadius: 3,
						borderWidth: 2
					}
				]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				interaction: { mode: 'index', intersect: false },
				plugins: { legend: { position: 'bottom', labels: { font: FONT, boxWidth: 12, padding: 12 } } },
				scales: {
					y: { position: 'left', beginAtZero: true, ticks: { precision: 0, font: FONT }, grid: GRID, title: { display: true, text: 'Responden', font: FONT } },
					y1: { position: 'right', min: 0, max: 5, ticks: { stepSize: 1, font: FONT }, grid: { display: false }, title: { display: true, text: 'Bintang', font: FONT } },
					x: { ticks: { font: FONT }, grid: { display: false } }
				}
			}
		});
	}
})();
</script>
