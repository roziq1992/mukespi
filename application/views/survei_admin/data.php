<div class="container-fluid sk-wrap">

<style>
	.sk-wrap {
		--sk-ink: #0f172a; --sk-ink2: #334155; --sk-muted: #64748b;
		--sk-line: #e2e8f0; --sk-blue: #2563eb; --sk-blue-dark: #1b3a5c;
		--sk-good: #10b981; --sk-warn: #f59e0b; --sk-bad: #ef4444;
		color: var(--sk-ink); padding-bottom: 10px;
	}
	.sk-wrap * { box-sizing: border-box; }

	.sk-header {
		background: linear-gradient(135deg, #102a43 0%, #1e4e79 62%, #2563eb 100%);
		color: #fff; border-radius: 14px; padding: 20px 24px;
		display: flex; justify-content: space-between; align-items: center;
		flex-wrap: wrap; gap: 14px; box-shadow: 0 6px 20px rgba(16,42,67,.18);
	}
	.sk-header h2 { margin: 0; font-size: 1.15rem; font-weight: 800; }
	.sk-header p { margin: 4px 0 0; font-size: .78rem; color: #cfe3f7; }
	.sk-header-actions { display: flex; gap: 8px; flex-wrap: wrap; }
	.sk-btn {
		display: inline-flex; align-items: center; gap: 6px;
		background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.32);
		color: #fff; border-radius: 9px; padding: 8px 14px;
		font-size: .78rem; font-weight: 700; text-decoration: none; white-space: nowrap;
	}
	.sk-btn:hover { background: rgba(255,255,255,.26); color: #fff; text-decoration: none; }
	.sk-btn.solid { background: #fff; color: var(--sk-blue-dark); border-color: #fff; }
	.sk-btn.solid:hover { background: #eaf2fb; color: var(--sk-blue-dark); }

	.sk-filter {
		background: #fff; border: 1px solid var(--sk-line); border-radius: 12px;
		padding: 14px 18px; margin-top: 16px;
		display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;
		box-shadow: 0 2px 8px rgba(15,23,42,.04);
	}
	.sk-filter .f-item { display: flex; flex-direction: column; gap: 5px; }
	.sk-filter .f-item.grow { flex: 1; min-width: 190px; }
	.sk-filter label { font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; color: var(--sk-muted); }
	.sk-filter select, .sk-filter input[type="text"] {
		border: 1px solid var(--sk-line); border-radius: 9px;
		padding: 8px 11px; font-size: .8rem; background: #f8fafc; color: var(--sk-ink2);
		width: 100%;
	}
	.sk-filter select:focus, .sk-filter input:focus { outline: none; border-color: var(--sk-blue); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
	.sk-filter .go { background: var(--sk-blue); color: #fff; border: none; border-radius: 9px; padding: 9px 18px; font-size: .8rem; font-weight: 700; cursor: pointer; }
	.sk-filter .go:hover { background: #1d4ed8; }
	.sk-filter .reset { font-size: .76rem; color: var(--sk-muted); text-decoration: none; padding-bottom: 9px; }
	.sk-filter .reset:hover { color: var(--sk-bad); }

	.sk-panel { background: #fff; border: 1px solid var(--sk-line); border-radius: 12px; box-shadow: 0 2px 8px rgba(15,23,42,.04); margin-top: 16px; }
	.sk-panel-head { padding: 14px 18px; border-bottom: 1px solid var(--sk-line); display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; }
	.sk-panel-head h3 { margin: 0; font-size: .88rem; font-weight: 800; color: var(--sk-blue-dark); }
	.sk-panel-head span { font-size: .74rem; color: var(--sk-muted); }

	.sk-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: .8rem; }
	.sk-table thead th {
		background: #f8fafc; color: var(--sk-muted);
		font-size: .66rem; text-transform: uppercase; letter-spacing: .05em; font-weight: 800;
		padding: 11px 13px; text-align: left; border-bottom: 1px solid var(--sk-line); white-space: nowrap;
	}
	.sk-table tbody td { padding: 12px 13px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
	.sk-table tbody tr:hover { background: #f8fafc; }
	.sk-table-wrap { overflow-x: auto; }

	.sk-kode { font-size: .7rem; font-weight: 800; color: var(--sk-blue); font-family: ui-monospace, monospace; }
	.sk-nama { font-weight: 700; color: var(--sk-ink); }
	.sk-meta { font-size: .7rem; color: var(--sk-muted); }

	.sk-stars-mini { font-size: .8rem; color: var(--sk-gold, #f59e0b); letter-spacing: 1px; white-space: nowrap; }
	.sk-stars-mini .off { color: #cbd5e1; }
	.sk-rata { font-size: .9rem; font-weight: 800; color: var(--sk-blue-dark); }

	.sk-badge { display: inline-block; padding: 4px 11px; border-radius: 20px; font-size: .7rem; font-weight: 800; white-space: nowrap; }
	.sk-badge.sb { background: #ecfdf5; color: #047857; }
	.sk-badge.b  { background: #eff6ff; color: #1d4ed8; }
	.sk-badge.c  { background: #fffbeb; color: #b45309; }
	.sk-badge.k  { background: #fef2f2; color: #b91c1c; }
	.sk-badge.netral { background: #f1f5f9; color: #475569; }
	.sk-badge.kritik { background: #fee2e2; color: #b91c1c; }

	.sk-saran { font-size: .74rem; color: var(--sk-ink2); max-width: 260px; }

	.sk-act { display: flex; gap: 4px; justify-content: flex-end; }
	.sk-act-btn {
		width: 30px; height: 30px; border-radius: 8px; border: 1px solid var(--sk-line);
	 background: #fff; color: var(--sk-ink2);
	 display: inline-flex; align-items: center; justify-content: center; font-size: .75rem;
	 text-decoration: none; transition: all .12s;
	}
	.sk-act-btn:hover { background: #eff6ff; color: var(--sk-blue); border-color: #bfdbfe; }
	.sk-act-btn.danger:hover { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }

	.sk-pagination-wrap { padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-top: 1px solid var(--sk-line); }
	.sk-pagination-wrap .pagination { margin: 0; }
	.sk-pagination-wrap .page-link { border-radius: 8px !important; margin: 0 2px; font-size: .78rem; color: var(--sk-ink2); border-color: var(--sk-line); }
	.sk-pagination-wrap .active .page-link { background: var(--sk-blue); border-color: var(--sk-blue); }
	.sk-count { font-size: .76rem; color: var(--sk-muted); }

	.sk-empty { text-align: center; padding: 48px 16px; color: var(--sk-muted); font-size: .84rem; }
	.sk-empty i { font-size: 2.1rem; color: #cbd5e1; display: block; margin-bottom: 12px; }

	@media (max-width: 576px) { .sk-filter { flex-direction: column; align-items: stretch; } }
</style>

<div class="sk-header">
	<div>
		<h2><i class="fas fa-list"></i> Data Survei Kepuasan Pasien</h2>
		<p><?= number_format($total_rows, 0, ',', '.'); ?> respons ditemukan berdasarkan filter yang dipilih</p>
	</div>
	<div class="sk-header-actions">
		<a class="sk-btn" href="<?= site_url('survei_admin'); ?>"><i class="fas fa-chart-line"></i> Dashboard</a>
		<a class="sk-btn" href="<?= site_url('survei_admin/aspek'); ?>"><i class="fas fa-sliders-h"></i> Master Aspek</a>
		<a class="sk-btn solid" href="<?= site_url('survei_admin/ekspor') . '?' . http_build_query(array_filter($f, function ($v) { return $v !== '' && $v !== NULL; })); ?>"><i class="fas fa-file-excel"></i> Ekspor Excel</a>
	</div>
</div>

<form method="get" action="<?= site_url('survei_admin/data'); ?>" class="sk-filter">
	<div class="f-item grow">
		<label for="f-q">Pencarian</label>
		<input type="text" id="f-q" name="q" value="<?= html_escape($q); ?>" placeholder="Nama / No. RM / NIK / kode / isi saran...">
	</div>
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
			<option value="">Semua</option>
			<?php foreach (array('Sangat Baik', 'Baik', 'Cukup', 'Buruk') as $p): ?>
				<option value="<?= $p; ?>" <?= (isset($f['predikat']) && $f['predikat'] === $p) ? 'selected' : ''; ?>><?= $p; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="f-item">
		<label for="f-kritik">Keluhan</label>
		<select id="f-kritik" name="kritik">
			<option value="">Semua</option>
			<option value="1" <?= (isset($f['is_kritik']) && (int)$f['is_kritik'] === 1) ? 'selected' : ''; ?>>Perlu tindak lanjut</option>
			<option value="0" <?= (isset($f['is_kritik']) && (int)$f['is_kritik'] === 0) ? 'selected' : ''; ?>>Tanpa keluhan</option>
		</select>
	</div>
	<button type="submit" class="go"><i class="fas fa-search"></i> Terapkan</button>
	<?php if ($q !== '' || !empty($f)): ?>
		<a class="reset" href="<?= site_url('survei_admin/data'); ?>"><i class="fas fa-times"></i> Reset</a>
	<?php endif; ?>
</form>

<div class="sk-panel">
	<div class="sk-panel-head">
		<h3>Daftar Respons</h3>
		<span>Urut dari yang terbaru</span>
	</div>

	<?php if ($this->session->flashdata('message')): ?>
		<div style="padding:14px 18px 0"><?= $this->session->flashdata('message'); ?></div>
	<?php endif; ?>

	<div class="sk-table-wrap">
		<table class="sk-table">
			<thead>
				<tr>
					<th style="width:120px">Kode</th>
					<th>Pasien</th>
					<th>Unit</th>
					<th style="width:150px">Kunjungan</th>
					<th style="width:160px">Penilaian</th>
					<th style="width:120px">Kategori</th>
					<th>Saran / Keluhan</th>
					<th style="width:90px" class="text-right">Aksi</th>
				</tr>
			</thead>
			<tbody>
			<?php if (empty($responden)): ?>
				<tr><td colspan="8">
					<div class="sk-empty">
						<i class="fas fa-inbox"></i>
						<p>Belum ada data survei yang sesuai filter.<br>
						<a href="<?= site_url('survei'); ?>" target="_blank" rel="noopener">Buka formulir pasien</a> untuk mulai mengumpulkan data.</p>
					</div>
				</td></tr>
			<?php else: foreach ($responden as $r): ?>
				<?php
				$rata = (float) $r->skor_rata;
				$kls = $r->predikat === 'Sangat Baik' ? 'sb' : ($r->predikat === 'Baik' ? 'b' : ($r->predikat === 'Cukup' ? 'c' : 'k'));
				?>
				<tr>
					<td><span class="sk-kode"><?= html_escape($r->kode); ?></span></td>
					<td>
						<div class="sk-nama"><?= $r->is_anonim ? '<i class="fas fa-user-secret"></i> (Anonim)' : html_escape($r->nama ?: '-'); ?></div>
						<div class="sk-meta">
							<?= $r->no_rm ? 'RM: ' . html_escape($r->no_rm) : 'Tanpa No. RM'; ?>
							<?php if ($r->umur): ?> &middot; <?= (int) $r->umur; ?> th<?php endif; ?>
						</div>
					</td>
					<td><span class="sk-badge netral"><?= html_escape($r->nm_unit ?: '-'); ?></span></td>
					<td>
						<div class="sk-nama" style="font-size:.76rem"><?= date('d M Y', strtotime($r->tanggal_survei)); ?></div>
						<div class="sk-meta">Kunjungan: <?= date('d M Y', strtotime($r->tanggal_kunjungan)); ?></div>
					</td>
					<td>
						<div class="sk-stars-mini">
							<?php for ($i = 1; $i <= 5; $i++): ?>
								<i class="<?= $i <= round($rata) ? 'fas fa-star' : 'far fa-star'; ?>"></i>
							<?php endfor; ?>
						</div>
						<div><span class="sk-rata"><?= number_format($rata, 2, ',', '.'); ?></span> <span class="sk-meta">/ 5,00</span></div>
					</td>
					<td>
						<span class="sk-badge <?= $kls; ?>"><?= html_escape($r->predikat); ?></span>
						<?php if ((int) $r->is_kritik === 1): ?>
							<div style="margin-top:4px"><span class="sk-badge kritik"><i class="fas fa-exclamation-triangle"></i> Keluhan</span></div>
						<?php endif; ?>
					</td>
					<td>
						<?php if (!empty($r->saran)): ?>
							<div class="sk-saran"><?= html_escape(mb_strimwidth($r->saran, 0, 90, '…')); ?></div>
						<?php else: ?>
							<span class="sk-meta">&mdash;</span>
						<?php endif; ?>
					</td>
					<td>
						<div class="sk-act">
							<a class="sk-act-btn" href="<?= site_url('survei_admin/detail/' . $r->id); ?>" title="Lihat detail"><i class="fas fa-eye"></i></a>
							<a class="sk-act-btn danger" href="<?= site_url('survei_admin/hapus/' . $r->id); ?>"
							   onclick="return confirm('Hapus data survei &quot;<?= html_escape($r->kode); ?>&quot; beserta seluruh jawabannya?')" title="Hapus">
								<i class="fas fa-trash"></i>
							</a>
						</div>
					</td>
				</tr>
			<?php endforeach; endif; ?>
			</tbody>
		</table>
	</div>

	<div class="sk-pagination-wrap">
		<span class="sk-count">
			<?php if ($total_rows > 0): ?>
				Menampilkan baris <?= $start + 1; ?> &ndash; <?= min($start + 15, $total_rows); ?> dari <?= number_format($total_rows, 0, ',', '.'); ?> data
			<?php else: ?>
				Tidak ada data
			<?php endif; ?>
		</span>
		<?= $pagination; ?>
	</div>
</div>

</div>
