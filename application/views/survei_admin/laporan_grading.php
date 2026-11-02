<div class="container-fluid sk-wrap">
<style>
	.sk-wrap { --sk-ink:#0f172a;--sk-ink2:#334155;--sk-muted:#64748b;--sk-line:#e2e8f0;--sk-blue:#2563eb;--sk-blue-dark:#1b3a5c;--sk-good:#10b981;--sk-warn:#f59e0b;--sk-bad:#ef4444; color:var(--sk-ink); padding-bottom:10px; }
	.sk-wrap * { box-sizing:border-box; }
	.sk-header { background:linear-gradient(135deg,#102a43 0%,#1e4e79 62%,#2563eb 100%); color:#fff; border-radius:14px; padding:20px 24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px; box-shadow:0 6px 20px rgba(16,42,67,.18); }
	.sk-header h2 { margin:0; font-size:1.15rem; font-weight:800; }
	.sk-header p { margin:4px 0 0; font-size:.78rem; color:#cfe3f7; }
	.sk-header-actions { display:flex; gap:8px; flex-wrap:wrap; }
	.sk-btn { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.32); color:#fff; border-radius:9px; padding:8px 14px; font-size:.78rem; font-weight:700; text-decoration:none; white-space:nowrap; }
	.sk-btn:hover { background:rgba(255,255,255,.26); color:#fff; text-decoration:none; }
	.sk-btn.solid { background:#fff; color:var(--sk-blue-dark); border-color:#fff; }
	.sk-filter { background:#fff; border:1px solid var(--sk-line); border-radius:12px; padding:14px 18px; margin-top:16px; display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap; box-shadow:0 2px 8px rgba(15,23,42,.04); }
	.sk-filter .f-item { display:flex; flex-direction:column; gap:5px; }
	.sk-filter .f-item.grow { flex:1; min-width:190px; }
	.sk-filter label { font-size:.66rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:var(--sk-muted); }
	.sk-filter select { border:1px solid var(--sk-line); border-radius:9px; padding:8px 11px; font-size:.8rem; background:#f8fafc; color:var(--sk-ink2); width:100%; }
	.sk-filter .go { background:var(--sk-blue); color:#fff; border:none; border-radius:9px; padding:9px 18px; font-size:.8rem; font-weight:700; cursor:pointer; }
	.sk-panel { background:#fff; border:1px solid var(--sk-line); border-radius:12px; box-shadow:0 2px 8px rgba(15,23,42,.04); margin-top:16px; }
	.sk-panel-head { padding:14px 18px; border-bottom:1px solid var(--sk-line); display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; }
	.sk-panel-head h3 { margin:0; font-size:.88rem; font-weight:800; color:var(--sk-blue-dark); }
	.sk-panel-head span { font-size:.74rem; color:var(--sk-muted); }
	.sk-table { width:100%; border-collapse:separate; border-spacing:0; font-size:.8rem; }
	.sk-table thead th { background:#f8fafc; color:var(--sk-muted); font-size:.66rem; text-transform:uppercase; letter-spacing:.05em; font-weight:800; padding:11px 13px; text-align:left; border-bottom:1px solid var(--sk-line); white-space:nowrap; }
	.sk-table tbody td { padding:12px 13px; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
	.sk-table tbody tr:hover { background:#f8fafc; }
	.sk-table-wrap { overflow-x:auto; }
	.sk-badge { display:inline-block; padding:4px 11px; border-radius:20px; font-size:.7rem; font-weight:800; white-space:nowrap; }
	.sk-badge.sb { background:#ecfdf5; color:#047857; }
	.sk-badge.b  { background:#eff6ff; color:#1d4ed8; }
	.sk-badge.c  { background:#fffbeb; color:#b45309; }
	.sk-badge.k  { background:#fef2f2; color:#b91c1c; }
	.sk-badge.netral { background:#f1f5f9; color:#475569; }
	.sk-badge.ok { background:#ecfdf5; color:#047857; }
	.sk-badge.late { background:#fef2f2; color:#b91c1c; }
</style>

<div class="sk-header">
	<div>
		<h2><i class="fas fa-clipboard-check"></i> Laporan Grading Keluhan Survei</h2>
		<p>Evaluasi kesesuaian waktu tindak lanjut terhadap ketentuan grading</p>
	</div>
	<div class="sk-header-actions">
		<a class="sk-btn" href="<?= site_url('survei_admin/data'); ?>"><i class="fas fa-arrow-left"></i> Data Survei</a>
	</div>
</div>

<form method="get" action="<?= site_url('survei_admin/laporan_grading'); ?>" class="sk-filter">
	<div class="f-item">
		<label for="f-bulan">Bulan</label>
		<select id="f-bulan" name="bulan">
			<option value="">Semua</option>
			<?php foreach ($bulan as $no => $nm): ?>
				<option value="<?= $no; ?>" <?= (isset($f['bulan']) && (int)$f['bulan'] === $no) ? 'selected' : ''; ?>><?= $nm; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="f-item">
		<label for="f-tahun">Tahun</label>
		<select id="f-tahun" name="tahun">
			<option value="">Semua</option>
			<?php foreach ($tahun as $th): ?>
				<option value="<?= $th; ?>" <?= (isset($f['tahun']) && (int)$f['tahun'] === (int)$th) ? 'selected' : ''; ?>><?= $th; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="f-item">
		<label for="f-unit">Unit</label>
		<select id="f-unit" name="unit">
			<option value="">Semua</option>
			<?php foreach ($unit as $u): ?>
				<option value="<?= (int)$u->id_unit; ?>" <?= (isset($f['id_unit']) && (int)$f['id_unit'] === (int)$u->id_unit) ? 'selected' : ''; ?>><?= html_escape($u->nm_unit); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="f-item">
		<label for="f-grading">Grading</label>
		<select id="f-grading" name="grading">
			<option value="">Semua</option>
			<option value="Merah" <?= (isset($f['grading']) && $f['grading'] === 'Merah') ? 'selected' : ''; ?>>Merah</option>
			<option value="Kuning" <?= (isset($f['grading']) && $f['grading'] === 'Kuning') ? 'selected' : ''; ?>>Kuning</option>
			<option value="Hijau" <?= (isset($f['grading']) && $f['grading'] === 'Hijau') ? 'selected' : ''; ?>>Hijau</option>
		</select>
	</div>
	<div class="f-item">
		<button type="submit" class="go"><i class="fas fa-filter"></i> Tampilkan</button>
	</div>
</form>

<div class="sk-panel">
	<div class="sk-panel-head">
		<h3><i class="fas fa-list"></i> Daftar Keluhan & Grading</h3>
		<span><?= count($rows); ?> data</span>
	</div>
	<div class="sk-table-wrap">
		<table class="sk-table">
			<thead>
				<tr>
					<th>Kode</th>
					<th>Responden</th>
					<th>Unit</th>
					<th>Tgl Survei</th>
					<th>Grading</th>
					<th>Tgl Tindak Lanjut</th>
					<th>Waktu Respon</th>
					<th>SLA</th>
					<th>Kesesuaian</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($rows)): ?>
					<tr><td colspan="9" style="text-align:center;padding:30px;color:var(--sk-muted)">Tidak ada data keluhan yang digrading.</td></tr>
				<?php else: foreach ($rows as $r):
					$sla_jam = array('Merah'=>24,'Kuning'=>72,'Hijau'=>168);
					$sla = isset($sla_jam[$r->grading]) ? $sla_jam[$r->grading] : NULL;
					$elapsed = NULL;
					$sesuai = NULL;
					if ($r->tanggal_tl) {
						$elapsed = (strtotime($r->tanggal_tl) - strtotime($r->tanggal_survei)) / 3600;
						if ($sla !== NULL) {
							$sesuai = $elapsed <= $sla;
						}
					}
				?>
					<tr>
						<td class="sk-kode"><?= html_escape($r->kode); ?></td>
						<td><?= $r->is_anonim ? '(Anonim)' : html_escape($r->nama ?: '-'); ?></td>
						<td><?= html_escape($r->nm_unit ?: '-'); ?></td>
						<td><?= date('d M Y H:i', strtotime($r->tanggal_survei)); ?></td>
						<td>
							<?php if ($r->grading): ?>
								<span class="sk-badge <?= $r->grading==='Merah'?'k':($r->grading==='Kuning'?'c':'sb'); ?>"><?= html_escape($r->grading); ?></span>
							<?php else: ?>
								<span class="sk-badge netral">Belum</span>
							<?php endif; ?>
						</td>
						<td><?= $r->tanggal_tl ? date('d M Y H:i', strtotime($r->tanggal_tl)) : '—'; ?></td>
						<td>
							<?php if ($elapsed !== NULL): ?>
								<?= number_format($elapsed, 1, ',', '.'); ?> jam
							<?php else: ?>
								—
							<?php endif; ?>
						</td>
						<td><?= $sla !== NULL ? $sla . ' jam' : '—'; ?></td>
						<td>
							<?php if ($sesuai === TRUE): ?>
								<span class="sk-badge ok">Sesuai</span>
							<?php elseif ($sesuai === FALSE): ?>
								<span class="sk-badge late">Tidak Sesuai</span>
							<?php else: ?>
								<span class="sk-badge netral">—</span>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; endif; ?>
			</tbody>
		</table>
	</div>
</div>
</div>