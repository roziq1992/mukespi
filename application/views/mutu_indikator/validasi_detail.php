<div class="container-fluid mutu-page">

<style>
	.mutu-page{
		--ink:#0F1B2A;
		--paper:#F6F8F9;
		--card:#FFFFFF;
		--accent:#2F6F5E;
		--accent-dark:#234F44;
		--accent-soft:#E3EEE9;
		--gold:#C08A34;
		--gold-soft:#F6ECDA;
		--line:#E4E9EA;
		--muted:#62767C;
		--danger:#B3483D;
		--danger-soft:#F7E5E2;
		--font-body:'IBM Plex Sans','Nunito',-apple-system,sans-serif;
		--font-mono:'IBM Plex Mono',monospace;
		font-family:var(--font-body);
		color:var(--ink);
	}
	.mutu-page *{ box-sizing:border-box; }

	.mutu-card{
		background:var(--card);
		border:1px solid var(--line);
		border-radius:14px;
		box-shadow:0 16px 40px -26px rgba(15,27,42,0.35);
		overflow:hidden;
		margin-bottom:22px;
	}

	.mutu-card-head{
		position:relative;
		padding:22px 26px 20px;
		background:var(--ink);
		overflow:hidden;
	}
	.mutu-head-row{ position:relative; z-index:1; }
	.mutu-card-head .eyebrow{
		margin:0 0 4px;
		font-family:var(--font-mono);
		font-size:10.5px;
		letter-spacing:.09em;
		text-transform:uppercase;
		color:#7FA69A;
	}
	.mutu-card-head h6{
		margin:0;
		font-weight:700;
		font-size:16.5px;
		line-height:1.4;
		color:#F3F6F5;
	}
	.mutu-pulse{
		position:absolute;
		left:0; right:0; bottom:0;
		height:34px;
		opacity:.55;
		z-index:0;
	}
	.mutu-pulse svg{ width:100%; height:100%; display:block; }

	.mutu-info-bar{
		display:flex;
		align-items:center;
		gap:14px;
		flex-wrap:wrap;
		padding:16px 26px;
		border-bottom:1px solid var(--line);
		background:var(--paper);
	}
	.info-target{
		display:inline-flex;
		align-items:center;
		gap:8px;
		background:var(--gold-soft);
		color:#8A6416;
		font-weight:700;
		font-size:13px;
		padding:7px 14px;
		border-radius:999px;
		white-space:nowrap;
	}
	.info-target .num{ font-family:var(--font-mono); }
	.info-ket{ font-size:12.5px; color:var(--muted); line-height:1.5; }
	.info-ket b{ color:var(--ink); font-weight:600; }

	.summary-grid{
		display:grid;
		grid-template-columns:repeat(4, 1fr);
		gap:14px;
		padding:24px 26px 8px;
	}
	.sum-item{
		border:1px solid var(--line);
		border-radius:12px;
		background:var(--paper);
		padding:14px 16px;
	}
	.sum-item .sum-label{
		font-family:var(--font-mono);
		font-size:10.5px;
		letter-spacing:.07em;
		text-transform:uppercase;
		color:var(--muted);
		margin-bottom:6px;
	}
	.sum-item .sum-value{
		font-size:20px;
		font-weight:700;
		color:var(--ink);
		line-height:1.2;
	}
	.sum-item .sum-sub{ font-size:12px; color:var(--muted); margin-top:3px; }
	.sum-item.sum-accent{ background:var(--accent-soft); border-color:#C8DDD5; }
	.sum-item.sum-accent .sum-value{ color:var(--accent-dark); font-family:var(--font-mono); }
	.sum-item.sum-gold{ background:var(--gold-soft); border-color:#E7CE9A; }
	.sum-item.sum-gold .sum-value{ color:#8A6416; font-family:var(--font-mono); }

	table.mutu-table{
		width:100%;
		border-collapse:separate;
		border-spacing:0;
		font-size:13.5px;
	}
	table.mutu-table thead th{
		text-align:left;
		font-family:var(--font-mono);
		font-size:10.5px;
		letter-spacing:.08em;
		text-transform:uppercase;
		color:var(--muted);
		font-weight:500;
		padding:0 14px 10px;
		border-bottom:1px solid var(--line);
	}
	table.mutu-table tbody td{
		padding:13px 14px;
		border-bottom:1px solid var(--line);
		vertical-align:middle;
		color:var(--ink);
	}
	table.mutu-table tbody tr:last-child td{ border-bottom:none; }
	table.mutu-table tbody tr{ transition:background .12s; }
	table.mutu-table tbody tr:hover{ background:var(--paper); }
	.col-no{ width:44px; color:var(--muted); font-family:var(--font-mono); font-size:12.5px; }
	.txt-mono{ font-family:var(--font-mono); color:var(--muted); font-size:12.5px; }
	.num-val{ font-family:var(--font-mono); font-weight:600; }

	.badge-capaian{
		display:inline-flex;
		align-items:center;
		gap:6px;
		font-family:var(--font-mono);
		font-weight:600;
		font-size:12.5px;
		padding:5px 10px;
		border-radius:8px;
	}
	.badge-capaian.ok{ background:var(--accent-soft); color:var(--accent-dark); }
	.badge-capaian.low{ background:var(--danger-soft); color:var(--danger); }
	.badge-capaian .dot{ width:6px; height:6px; border-radius:50%; background:currentColor; flex:none; }

	.section-title{
		font-family:var(--font-mono);
		font-size:10.5px;
		letter-spacing:.08em;
		text-transform:uppercase;
		color:var(--muted);
		margin:0;
		padding:2px 26px 12px;
	}
	.empty-state{
		text-align:center;
		color:var(--muted);
		padding:40px 20px;
		font-size:13.5px;
	}
	.empty-state i{ font-size:2rem; display:block; margin-bottom:10px; color:#CBD5E1; }

	.mutu-footer{
		display:flex;
		align-items:center;
		justify-content:space-between;
		gap:14px;
		flex-wrap:wrap;
		padding:14px 26px 22px;
		border-top:1px solid var(--line);
	}
	.btn-action{
		display:inline-flex;
		align-items:center;
		gap:7px;
		font-size:13px;
		font-weight:600;
		text-decoration:none;
		padding:10px 16px;
		border-radius:8px;
		border:1px solid transparent;
		cursor:pointer;
		transition:background .15s, border-color .15s, color .15s;
	}
	.btn-primary-solid{ background:var(--accent); color:#fff; border:none; }
	.btn-primary-solid:hover{ background:var(--accent-dark); color:#fff; }
	.btn-outline{ background:#fff; color:var(--ink); border-color:var(--line); }
	.btn-outline:hover{ border-color:var(--ink); background:var(--paper); color:var(--ink); }
	.btn-ghost-gold{ background:var(--gold-soft); color:#8A6416; border-color:#E7CE9A; }
	.btn-ghost-gold:hover{ background:#F1E1BC; color:#8A6416; }

	@media (max-width: 900px){
		.summary-grid{ grid-template-columns:repeat(2, 1fr); }
	}
	@media (max-width: 560px){
		.summary-grid{ grid-template-columns:1fr; }
	}
</style>

<div class="mutu-card">

	<div class="mutu-card-head">
		<div class="mutu-head-row">
			<p class="eyebrow">Detail Validasi Mutu</p>
			<h6><?php echo html_escape($indikator ? $indikator->judul : 'Data indikator tidak ditemukan') ?></h6>
		</div>
		<div class="mutu-pulse">
			<svg viewBox="0 0 600 34" preserveAspectRatio="none">
				<polyline points="0,17 140,17 160,4 180,30 200,17 340,17 360,6 380,28 400,17 600,17"
					fill="none" stroke="#C08A34" stroke-width="1.6" />
			</svg>
		</div>
	</div>

	<div class="mutu-info-bar">
		<span class="info-target">Target <span class="num"><?php echo $target ?><?php echo ($jenis == "PPI") ? '&permil;' : '%' ?></span></span>
		<span class="info-ket">Periode validasi:
			<b class="txt-mono"><?php echo $validasi->tanggal_awal ?> &rarr; <?php echo $validasi->tanggal_akhir ?></b>
			&nbsp;&middot;&nbsp; Validator: <b><?php echo html_escape($validator ? $validator->name : '-') ?></b>
			&nbsp;&middot;&nbsp; Tanggal validasi: <b class="txt-mono"><?php echo date('d M Y H:i', strtotime($validasi->created_at)) ?></b>
		</span>
	</div>

	<div class="summary-grid">
		<div class="sum-item">
			<div class="sum-label">Numerator (num)</div>
			<div class="sum-value"><?php echo $validasi->num ?></div>
			<div class="sum-sub">Total angka pembilang pada periode</div>
		</div>
		<div class="sum-item">
			<div class="sum-label">Denumerator (demu)</div>
			<div class="sum-value"><?php echo $validasi->demu ?></div>
			<div class="sum-sub">Total angka penyebut pada periode</div>
		</div>
		<div class="sum-item sum-accent">
			<div class="sum-label">Nilai Capaian</div>
			<?php
			$capaian = 0;
			$st = $validasi->num;
			$sd = $validasi->demu;
			if ((float) $sd > 0) {
				if ($jenis <> "PPI" and $judul <> "Kepuasan Pasien") {
					$capaian = round($st / $sd * 100, 1);
				}
				if ($judul == "Kepuasan Pasien") {
					$capaian = round($st * 25, 2);
				}
				if ($jenis == "PPI") {
					$capaian = round($st / $sd * 1000, 1);
				}
			}
			?>
			<div class="sum-value"><?php echo $capaian ?><?php echo ($jenis == "PPI") ? '&permil;' : '%' ?></div>
			<div class="sum-sub"><?php echo ($capaian >= $target) ? 'Mencapai target' : 'Belum mencapai target' ?></div>
		</div>
		<div class="sum-item sum-gold">
			<div class="sum-label">Target</div>
			<div class="sum-value"><?php echo $target ?><?php echo ($jenis == "PPI") ? '&permil;' : '%' ?></div>
			<div class="sum-sub">Standar capaian indikator</div>
		</div>
	</div>

	<p class="section-title">Data Mutu pada Periode Validasi</p>

	<div class="mutu-table-wrap">
		<?php if (count($mutu_data) > 0): ?>
		<table class="mutu-table">
			<thead>
				<tr>
					<th class="col-no">No</th>
					<th>Tanggal</th>
					<th>Numerator</th>
					<th>Denumerator</th>
					<th>Capaian</th>
					<th>Target</th>
				</tr>
			</thead>
			<tbody>
				<?php $no = 0; foreach ($mutu_data as $m): $no++; ?>
				<?php
					$rc = 0;
					if ($m->num > 0) {
						if ($jenis <> "PPI" and $judul <> "Kepuasan Pasien") {
							$rc = round($m->num / $m->demu * 100, 1);
						}
						if ($judul == "Kepuasan Pasien") {
							$rc = round($m->num * 25, 2);
						}
						if ($jenis == "PPI") {
							$rc = round($m->num / $m->demu * 1000, 1);
						}
					}
					$rc_class = ($rc >= $target) ? 'ok' : 'low';
				?>
				<tr>
					<td class="col-no"><?php echo $no ?></td>
					<td class="txt-mono"><?php echo $m->tanggal ?></td>
					<td class="num-val"><?php echo $m->num ?></td>
					<td class="num-val"><?php echo $m->demu ?></td>
					<td><span class="badge-capaian <?php echo $rc_class ?>"><span class="dot"></span><?php echo $rc ?><?php echo ($jenis == "PPI") ? '&permil;' : '%'; ?></span></td>
					<td class="txt-mono"><?php echo $m->target ?>%</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php else: ?>
		<div class="empty-state">
			<i class="fa fa-database"></i>
			Tidak ada data mutu pada periode validasi ini.
		</div>
		<?php endif; ?>
	</div>

	<div class="mutu-footer">
		<a class="btn-action btn-outline" href="<?php echo site_url('mutu_indikator?id='.$validasi->id_indikator.'&judul='.urlencode($judul)) ?>"><i class="fa fa-arrow-left"></i> Kembali</a>
		<button type="button" class="btn-action btn-primary-solid" onclick="window.print()"><i class="fa fa-print"></i> Cetak Laporan</button>
	</div>

</div>

</div>