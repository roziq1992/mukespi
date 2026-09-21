<div class="container-fluid mutu-dashboard">

<style>
	:root {
		--mutu-bg: #f8fafc;
		--mutu-card-bg: #ffffff;
		--mutu-primary: #4f46e5;
		--mutu-primary-hover: #4338ca;
		--mutu-primary-light: #eef2ff;
		--mutu-primary-border: rgba(79, 70, 229, 0.2);
		--mutu-dark: #0f172a;
		--mutu-slate: #334155;
		--mutu-muted: #64748b;
		--mutu-border: #e2e8f0;
		--mutu-border-subtle: #f1f5f9;
		--mutu-success: #10b981;
		--mutu-success-light: #ecfdf5;
		--mutu-danger: #ef4444;
		--mutu-danger-light: #fef2f2;
		--mutu-gold: #f59e0b;
		--mutu-gold-light: #fffbeb;
		--mutu-low: #94a3b8;
		--mutu-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.05);
		--mutu-radius: 16px;
		--mutu-font: 'Inter', system-ui, -apple-system, sans-serif;
		--mutu-font-mono: 'JetBrains Mono', ui-monospace, monospace;
	}

	.mutu-dashboard {
		font-family: var(--mutu-font);
		color: var(--mutu-slate);
		padding: 1rem 0;
	}

	.mutu-dashboard * { box-sizing: border-box; }

	.mutu-wrapper {
		background: var(--mutu-card-bg);
		border-radius: var(--mutu-radius);
		box-shadow: var(--mutu-shadow);
		border: 1px solid var(--mutu-border);
		position: static !important;
		overflow: visible !important;
	}

	/* HEADER */
	.mutu-header {
		background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
		padding: 28px 32px;
		position: relative;
		z-index: 0 !important;
		border-top-left-radius: var(--mutu-radius);
		border-top-right-radius: var(--mutu-radius);
		overflow: hidden;
		color: #ffffff;
	}

	.mutu-header-content {
		position: relative;
		z-index: 1;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 20px;
		flex-wrap: wrap;
	}

	.mutu-header-title {
		display: flex;
		align-items: center;
		gap: 16px;
	}

	.mutu-header-icon {
		width: 48px;
		height: 48px;
		background: rgba(255, 255, 255, 0.12);
		border: 1px solid rgba(255, 255, 255, 0.18);
		border-radius: 12px;
		display: flex;
		align-items: center;
		justify-content: center;
		color: #a5b4fc;
		font-size: 1.15rem;
	}

	.mutu-header h5 {
		margin: 0;
		font-size: 1.25rem;
		font-weight: 700;
		color: #ffffff;
		letter-spacing: -0.02em;
	}

	.mutu-header p {
		margin: 4px 0 0 0;
		font-size: 0.75rem;
		font-family: var(--mutu-font-mono);
		color: #a5b4fc;
		letter-spacing: 0.08em;
		text-transform: uppercase;
		font-weight: 600;
	}

	.mutu-header-stats {
		display: flex;
		gap: 10px;
		flex-wrap: wrap;
	}

	.stat-pill {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 14px;
		background: rgba(255, 255, 255, 0.1);
		border: 1px solid rgba(255, 255, 255, 0.15);
		border-radius: 20px;
		font-size: 0.78rem;
		font-weight: 600;
		color: #e0e7ff;
	}

	.stat-pill .count {
		font-family: var(--mutu-font-mono);
		font-weight: 700;
		color: #ffffff;
	}

	.stat-pill.s-aktif .count { color: #6ee7b7; }
	.stat-pill.s-nonaktif .count { color: #fca5a5; }

	/* TOOLBAR */
	.mutu-toolbar {
		padding: 20px 32px 0;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		flex-wrap: wrap;
	}

	.mutu-flash:not(:empty) {
		flex: 1 1 280px;
		background: var(--mutu-success-light);
		border: 1px solid #a7f3d0;
		color: #064e3b;
		padding: 10px 16px;
		border-radius: 10px;
		font-size: 0.85rem;
		font-weight: 500;
	}
	.mutu-flash.alert-danger {
		background: var(--mutu-danger-light);
		border: 1px solid #fecaca;
		color: #7f1d1d;
	}

	.mutu-search {
		margin-left: auto;
	}

	.mutu-search-box {
		display: flex;
		align-items: center;
		background: #f8fafc;
		border: 1px solid var(--mutu-border);
		border-radius: 10px;
		padding: 4px;
		transition: all 0.2s ease;
	}

	.mutu-search-box:focus-within {
		background: #ffffff;
		border-color: var(--mutu-primary);
		box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
	}

	.mutu-search-box input {
		border: none;
		background: transparent;
		outline: none;
		padding: 8px 14px;
		font-size: 0.875rem;
		color: var(--mutu-dark);
		width: 250px;
	}

	.btn-reset-search {
		padding: 6px 12px;
		color: var(--mutu-muted);
		font-size: 0.8rem;
		text-decoration: none !important;
		font-weight: 600;
		transition: color 0.15s;
	}
	.btn-reset-search:hover { color: var(--mutu-danger); }

	.btn-submit-search {
		border: none;
		background: var(--mutu-primary);
		color: #ffffff;
		font-weight: 600;
		font-size: 0.85rem;
		padding: 8px 18px;
		border-radius: 8px;
		cursor: pointer;
		transition: background 0.15s;
	}
	.btn-submit-search:hover { background: var(--mutu-primary-hover); }

	.btn-add {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 10px 18px;
		background: var(--mutu-primary);
		color: #ffffff !important;
		font-size: 0.85rem;
		font-weight: 700;
		border-radius: 10px;
		text-decoration: none !important;
		transition: all 0.15s;
		box-shadow: 0 4px 12px -3px rgba(79, 70, 229, 0.4);
	}
	.btn-add:hover {
		background: var(--mutu-primary-hover);
		transform: translateY(-1px);
	}

	/* TABLE DATA */
	.mutu-table-wrap {
		padding: 24px 32px;
		overflow-x: auto;
	}

	.mutu-table {
		width: 100%;
		border-collapse: separate;
		border-spacing: 0;
		font-size: 0.875rem;
	}

	.mutu-table thead th {
		background: #fafafa;
		padding: 12px 16px;
		font-size: 0.725rem;
		font-family: var(--mutu-font-mono);
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.08em;
		color: var(--mutu-muted);
		border-top: 1px solid var(--mutu-border);
		border-bottom: 1px solid var(--mutu-border);
	}

	.mutu-table thead th:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; border-left: 1px solid var(--mutu-border); }
	.mutu-table thead th:last-child { border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-right: 1px solid var(--mutu-border); }

	.mutu-table tbody td {
		padding: 16px;
		border-bottom: 1px solid var(--mutu-border-subtle);
		vertical-align: middle;
	}

	.mutu-table tbody tr { transition: background 0.15s; }
	.mutu-table tbody tr:hover { background: #f8fafc; }
	.mutu-table tbody tr:last-child td { border-bottom: none; }

	.col-index {
		width: 50px;
		font-family: var(--mutu-font-mono);
		color: var(--mutu-muted);
		font-weight: 600;
		font-size: 0.8rem;
	}

	.emp-cell {
		display: flex;
		align-items: center;
		gap: 12px;
	}

	.emp-avatar {
		width: 40px;
		height: 40px;
		border-radius: 50%;
		background: linear-gradient(135deg, #c7d2fe, #a5b4fc);
		color: #312e81;
		display: flex;
		align-items: center;
		justify-content: center;
		font-weight: 700;
		font-size: 0.9rem;
		flex-shrink: 0;
	}
	.emp-avatar.is-nonaktif { background: #e2e8f0; color: #64748b; }

	.emp-name {
		font-weight: 700;
		color: var(--mutu-dark);
		line-height: 1.3;
	}

	.emp-nip {
		font-family: var(--mutu-font-mono);
		font-size: 0.72rem;
		color: var(--mutu-muted);
		display: block;
		margin-top: 1px;
	}

	.txt-unit {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 4px 12px;
		background: var(--mutu-primary-light);
		color: var(--mutu-primary-hover);
		border: 1px solid var(--mutu-primary-border);
		border-radius: 20px;
		font-size: 0.75rem;
		font-weight: 700;
		white-space: nowrap;
	}

	.txt-jabatan {
		font-weight: 500;
		color: var(--mutu-slate);
	}

	.txt-lahir {
		color: var(--mutu-muted);
		font-weight: 500;
		font-size: 0.82rem;
	}

	.badge-st {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 4px 12px;
		border-radius: 20px;
		font-size: 0.75rem;
		font-weight: 700;
		white-space: nowrap;
	}
	.badge-st .dot {
		width: 6px;
		height: 6px;
		border-radius: 50%;
		background: currentColor;
	}
	.badge-st-aktif { background: var(--mutu-success-light); color: #047857; border: 1px solid rgba(16, 185, 129, 0.25); }
	.badge-st-nonaktif { background: var(--mutu-danger-light); color: #b91c1c; border: 1px solid rgba(239, 68, 68, 0.25); }

	/* Action Buttons */
	.action-flex { display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap; }

	.btn-act {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 7px 14px;
		font-size: 0.8rem;
		font-weight: 600;
		border-radius: 8px;
		text-decoration: none !important;
		transition: all 0.15s ease;
		border: 1px solid transparent;
		cursor: pointer;
	}
	.btn-act-edit { background: #ffffff; border-color: var(--mutu-border); color: var(--mutu-slate); }
	.btn-act-edit:hover { background: var(--mutu-bg); border-color: #cbd5e1; color: var(--mutu-dark); }
	.btn-act-primary { background: var(--mutu-primary); color: #ffffff !important; }
	.btn-act-primary:hover { background: var(--mutu-primary-hover); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); }
	.btn-act-danger { background: var(--mutu-danger-light); border-color: rgba(239, 68, 68, 0.25); color: #b91c1c; }
	.btn-act-danger:hover { background: #fee2e2; }
	.btn-act-success { background: var(--mutu-success-light); border-color: rgba(16, 185, 129, 0.3); color: #047857; }
	.btn-act-success:hover { background: #d1fae5; }

	/* Empty State */
	.mutu-empty-state {
		text-align: center;
		padding: 56px 20px;
		color: var(--mutu-muted);
	}
	.mutu-empty-state svg { width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 12px; }
	.mutu-empty-state p { margin: 0; font-size: 0.9rem; font-weight: 500; }

	/* FOOTER */
	.mutu-footer {
		padding: 20px 32px 24px;
		background: #fafafa;
		border-top: 1px solid var(--mutu-border);
		border-bottom-left-radius: var(--mutu-radius);
		border-bottom-right-radius: var(--mutu-radius);
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		flex-wrap: wrap;
	}

	.mutu-footer-info { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

	.stat-chip {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 16px;
		background: #ffffff;
		border: 1px solid var(--mutu-border);
		border-radius: 20px;
		font-size: 0.8rem;
		font-weight: 600;
		color: var(--mutu-slate);
	}
	.stat-chip .count { font-family: var(--mutu-font-mono); color: var(--mutu-primary); font-weight: 700; }

	.filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; }
	.filter-tab {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 7px 14px;
		border-radius: 20px;
		font-size: 0.78rem;
		font-weight: 600;
		text-decoration: none !important;
		border: 1px solid var(--mutu-border);
		color: var(--mutu-muted);
		transition: all 0.15s;
		background: #ffffff;
	}
	.filter-tab:hover { border-color: var(--mutu-primary); color: var(--mutu-primary); }
	.filter-tab.active {
		background: var(--mutu-primary);
		border-color: var(--mutu-primary);
		color: #ffffff;
	}
	.filter-tab.active:hover { color: #ffffff; }

	.mutu-pagination-container :where(a, span) {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 34px;
		height: 34px;
		padding: 0 10px;
		margin: 0 2px;
		border-radius: 8px;
		font-size: 0.8rem;
		font-weight: 600;
		text-decoration: none;
	}
	.mutu-pagination-container a { background: #ffffff; border: 1px solid var(--mutu-border); color: var(--mutu-slate); }
	.mutu-pagination-container a:hover { background: var(--mutu-primary-light); border-color: var(--mutu-primary); color: var(--mutu-primary); }
	.mutu-pagination-container span { background: var(--mutu-primary); color: #ffffff; border: 1px solid var(--mutu-primary); }
	.mutu-pagination-container ul { list-style: none; display: flex; margin: 0; padding: 0; }

	/* RESPONSIVE */
	@media (max-width: 768px) {
		.mutu-header { padding: 20px; }
		.mutu-header-content { flex-direction: column; align-items: flex-start; }
		.mutu-toolbar { padding: 16px 20px 0; flex-direction: column; align-items: stretch; }
		.mutu-search { margin-left: 0; width: 100%; }
		.mutu-search-box { flex-wrap: wrap; }
		.mutu-search-box input { width: 100%; }
		.mutu-table-wrap { padding: 16px 20px; }

		.mutu-table thead { display: none; }
		.mutu-table, .mutu-table tbody, .mutu-table tr, .mutu-table td { display: block; width: 100%; }
		.mutu-table tr { border: 1px solid var(--mutu-border); border-radius: 12px; margin-bottom: 12px; padding: 10px; background: #ffffff; }
		.mutu-table td { display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; border-bottom: none; }
		.mutu-table td::before {
			content: attr(data-label);
			font-family: var(--mutu-font-mono);
			font-size: 0.7rem;
			font-weight: 700;
			text-transform: uppercase;
			color: var(--mutu-muted);
		}
		.col-index { display: none; }
		.action-flex { justify-content: flex-end; width: 100%; }
		.mutu-footer { padding: 16px 20px; flex-direction: column; align-items: stretch; }
		.mutu-footer-info { justify-content: space-between; }
		.mutu-pagination-container { display: flex; justify-content: center; }
	}
</style>

<?php
$flash = $this->session->flashdata('message');
$prev_flash = $this->session->userdata('message');
?>

	<div class="mutu-wrapper">

		<!-- Header -->
		<div class="mutu-header">
			<div class="mutu-header-content">
				<div class="mutu-header-title">
					<div class="mutu-header-icon"><i class="fas fa-users"></i></div>
					<div>
						<h5>Data Pegawai</h5>
						<p>Manajemen Pegawai, Mutasi & Riwayat</p>
					</div>
				</div>
				<div class="mutu-header-stats">
					<span class="stat-pill">Total <span class="count"><?= $total_rows ?></span></span>
					<span class="stat-pill s-aktif">Aktif <span class="count"><?= $count_aktif ?></span></span>
					<span class="stat-pill s-nonaktif">Nonaktif <span class="count"><?= $count_nonaktif ?></span></span>
				</div>
			</div>
		</div>

		<!-- Toolbar -->
		<div class="mutu-toolbar">
			<div>
				<div class="filter-tabs">
					<a href="<?= site_url('pegawai') ?>" class="filter-tab <?= ($status_filter === '' || $status_filter === 'semua') ? 'active' : '' ?>">Semua</a>
					<a href="<?= site_url('pegawai/?status=aktif') ?>" class="filter-tab <?= $status_filter === 'aktif' ? 'active' : '' ?>">
						<i class="fas fa-check-circle"></i> Aktif
					</a>
					<a href="<?= site_url('pegawai/?status=nonaktif') ?>" class="filter-tab <?= $status_filter === 'nonaktif' ? 'active' : '' ?>">
						<i class="fas fa-user-slash"></i> Nonaktif
					</a>
				</div>
			</div>

			<div class="mutu-flash <?= (strpos((string) $flash, 'danger') !== false) ? 'alert-danger' : '' ?>" id="message">
				<?= $flash ?: ($prev_flash ?: ''); ?>
			</div>

			<a href="<?= site_url('pegawai/create') ?>" class="btn-add"><i class="fas fa-user-plus"></i> Tambah Pegawai</a>

			<?php if (!empty($is_admin)): ?>
			<a href="<?= site_url('pegawai/export_excel') ?>" class="btn-add" style="background:#059669; box-shadow:0 4px 12px -3px rgba(5,150,105,0.4);">
				<i class="fas fa-file-excel"></i> Export Excel
			</a>
			<button type="button" class="btn-add" data-toggle="modal" data-target="#importModal" style="background:#0f766e; border:0; cursor:pointer; box-shadow:0 4px 12px -3px rgba(15,118,110,0.4);">
				<i class="fas fa-file-import"></i> Import Excel
			</button>
			<?php endif; ?>

			<?php if (!empty($is_admin)): ?>
			<a href="<?= site_url('pegawai/toggle_edit_mode') ?>" class="btn-add"
			   style="<?= $edit_mode ? 'background:#059669; background:linear-gradient(135deg,#059669,#047857);' : '' ?>"
			   title="<?= $edit_mode ? 'Matikan agar karyawan tidak dapat mengubah data diri' : 'Nyalakan agar karyawan dapat mengubah data dirinya sendiri' ?>">
				<i class="fas fa-<?= $edit_mode ? 'lock' : 'lock-open' ?>"></i>
				<?= $edit_mode ? 'Nonaktifkan' : 'Aktifkan' ?> Mode Edit
			</a>
			<?php endif; ?>
		</div>

		<?php if (!empty($is_admin)): ?>
		<div class="mutu-toolbar" style="padding-top:6px;">
			<div class="stat-chip" style="<?= $edit_mode ? 'background:#ecfdf5; border-color:#a7f3d0; color:#047857;' : 'background:#f8fafc; color:#64748b;' ?>">
				<i class="fas <?= $edit_mode ? 'fa-lock-open' : 'fa-lock' ?> mr-1"></i>
				Mode Edit Karyawan: <strong><?= $edit_mode ? 'AKTIF' : 'NONAKTIF' ?></strong>
				<?php if ($edit_mode): ?><span style="font-weight:400;">— karyawan dapat mengubah data dirinya sendiri</span><?php endif; ?>
			</div>
		</div>
		<?php endif; ?>

		<!-- Search -->
		<div class="mutu-toolbar">
			<form action="<?= site_url('pegawai/index') ?>" class="mutu-search" method="get">
				<input type="hidden" name="status" value="<?= html_escape($status_filter) ?>">
				<div class="mutu-search-box">
					<input type="text" name="q" value="<?= html_escape($q) ?>" placeholder="Cari nama / NIP / jabatan / unit...">
					<?php if ($q <> ''): ?>
						<a href="<?= site_url('pegawai') ?>" class="btn-reset-search">Reset</a>
					<?php endif; ?>
					<button class="btn-submit-search" type="submit"><i class="fa fa-search mr-1"></i> Cari</button>
				</div>
			</form>
		</div>

		<!-- Table -->
		<div class="mutu-table-wrap">
			<?php if (count($pegawai_data) > 0): ?>
			<table class="mutu-table">
				<thead>
					<tr>
						<th class="col-index">No</th>
						<th>Pegawai</th>
						<th>Jenis</th>
						<th>Jabatan / Unit</th>
						<th>Tanggal Masuk</th>
						<th>Status</th>
						<th style="text-align:center">Aksi</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($pegawai_data as $p):
					$inisial = mb_substr($p->nama, 0, 1);
					?>
					<tr>
						<td class="col-index"><?= ++$start ?></td>
						<td data-label="Pegawai">
							<div class="emp-cell">
								<div class="emp-avatar <?= ($p->status === 'nonaktif') ? 'is-nonaktif' : '' ?>"><?= html_escape($inisial) ?></div>
								<div>
									<span class="emp-name"><?= html_escape($p->nama) ?></span>
									<span class="emp-nip"><?= html_escape(($p->nip ?: $p->nik) ?: '—') ?></span>
								</div>
							</div>
						</td>
						<td data-label="Jenis" class="txt-lahir"><?= html_escape($p->jenis_kelamin) ?></td>
						<td data-label="Jabatan / Unit">
							<div class="txt-jabatan mb-1"><?= html_escape($p->jabatan) ?></div>
							<span class="txt-unit"><i class="fas fa-building"></i> <?= html_escape($p->unit_kerja) ?></span>
						</td>
						<td data-label="Tanggal Masuk" class="txt-lahir">
							<?= $p->tanggal_masuk ? date('d M Y', strtotime($p->tanggal_masuk)) : '—' ?>
						</td>
						<td data-label="Status">
							<span class="badge-st <?= ($p->status === 'aktif') ? 'badge-st-aktif' : 'badge-st-nonaktif' ?>">
								<span class="dot"></span> <?= ucfirst($p->status) ?>
							</span>
							<?php if (!empty($p->status_kepegawaian)): ?>
								<span class="d-block mt-1" style="font-size:.72rem; color:#64748b; text-transform:none;"><?= html_escape($p->status_kepegawaian) ?></span>
							<?php endif; ?>
						</td>
						<td data-label="Aksi">
							<div class="action-flex">
								<?= anchor(site_url('pegawai/detail/' . $p->id_pegawai), '<i class="fas fa-eye"></i> Detail', 'class="btn-act btn-act-primary" title="Detail & Riwayat"') ?>
								<?= anchor(site_url('pegawai/update/' . $p->id_pegawai), '<i class="fas fa-edit"></i>' , 'class="btn-act btn-act-edit" title="Edit Data"') ?>
								<?php if ($p->status === 'aktif'): ?>
									<button type="button" class="btn-act btn-act-danger" data-toggle="modal" data-target="#nonaktifModal" data-id="<?= $p->id_pegawai ?>" data-nama="<?= html_escape($p->nama) ?>" title="Nonaktifkan">
										<i class="fas fa-user-slash"></i>
									</button>
								<?php else: ?>
									<button type="button" class="btn-act btn-act-success" data-toggle="modal" data-target="#aktifModal" data-id="<?= $p->id_pegawai ?>" data-nama="<?= html_escape($p->nama) ?>" title="Aktifkan Kembali">
										<i class="fas fa-user-check"></i>
									</button>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<?php else: ?>
			<div class="mutu-empty-state">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="7" r="4"/><path d="M5 21v-2a7 7 0 0 1 14 0v2"/></svg>
				<p>Belum ada data pegawai yang ditemukan.</p>
			</div>
			<?php endif; ?>
		</div>

		<!-- Footer -->
		<div class="mutu-footer">
			<div class="mutu-footer-info">
				<div class="stat-chip">Total Record: <span class="count"><?= $total_rows ?></span></div>
			</div>
			<div class="mutu-pagination-container">
				<?= $pagination ?>
			</div>
		</div>

	</div>

</div>

<!-- ===== MODAL NONAKTIFKAN ===== -->
<div class="modal fade" id="nonaktifModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<form method="POST" action="<?= site_url('pegawai/nonaktif_action') ?>" class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
			<input type="hidden" name="id_pegawai" id="nonaktif-id">
			<div class="modal-header" style="background: linear-gradient(135deg,#991b1b,#b91c1c); color:#fff;">
				<h5 class="modal-title font-weight-bold" style="font-size:1rem;">
					<i class="fas fa-user-slash mr-2"></i>Nonaktifkan Pegawai
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body" style="font-size:14px;">
				<p>Yakin ingin <strong>menonaktifkan</strong> pegawai <strong id="nonaktif-nama" class="text-danger"></strong>?</p>
				<div class="form-group mb-0">
					<label>Alasan / Keterangan</label>
					<textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: pensiun, mengundurkan diri, dikeluarkan..."></textarea>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn px-4 font-weight-bold" style="background:#dc2626; color:#fff;">Ya, Nonaktifkan</button>
			</div>
		</form>
	</div>
</div>

<!-- ===== MODAL AKTIFKAN KEMBALI ===== -->
<div class="modal fade" id="aktifModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<form method="POST" action="<?= site_url('pegawai/aktifkan_action') ?>" class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
			<input type="hidden" name="id_pegawai" id="aktif-id">
			<div class="modal-header" style="background: linear-gradient(135deg,#047857,#059669); color:#fff;">
				<h5 class="modal-title font-weight-bold" style="font-size:1rem;">
					<i class="fas fa-user-check mr-2"></i>Aktifkan Kembali
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body" style="font-size:14px;">
				<p>Yakin ingin <strong>mengaktifkan kembali</strong> pegawai <strong id="aktif-nama" class="text-success"></strong>?</p>
				<div class="form-group mb-0">
					<label>Keterangan</label>
					<textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: kembali bertugas..."></textarea>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn px-4 font-weight-bold" style="background:#059669; color:#fff;">Ya, Aktifkan</button>
			</div>
		</form>
	</div>
</div>

<!-- ===== MODAL IMPORT EXCEL ===== -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<form method="POST" action="<?= site_url('pegawai/import_excel') ?>" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
			<div class="modal-header" style="background: linear-gradient(135deg,#134e4a,#0f766e); color:#fff;">
				<h5 class="modal-title font-weight-bold" style="font-size:1rem;">
					<i class="fas fa-file-import mr-2"></i>Import Data Pegawai
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body" style="font-size:14px;">
				<div class="form-group">
					<label class="font-weight-bold">Berkas Excel / CSV</label>
					<input type="file" name="file" class="form-control-file" accept=".xlsx,.csv" required>
					<small class="text-muted d-block mt-1">Format didukung: <strong>.xlsx</strong> atau <strong>.csv</strong>. Baris pertama harus berisi judul kolom.</small>
				</div>
				<div class="alert alert-light border mb-0" style="font-size:12.5px;">
					<strong>Petunjuk:</strong>
					<ul class="mb-1 pl-3">
						<li>Gunakan tombol <em>Download Template</em> agar nama kolom sesuai.</li>
						<li>Kolom wajib: <strong>Nama</strong>. Kolom lain opsional.</li>
						<li>Data dengan NIK/NIP/Email yang sama akan <strong>diperbarui</strong>, selebihnya ditambahkan.</li>
					</ul>
					<a href="<?= site_url('pegawai/template_excel') ?>" class="font-weight-bold"><i class="fas fa-download"></i> Download Template</a>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn px-4 font-weight-bold" style="background:#0f766e; color:#fff;"><i class="fas fa-upload mr-1"></i> Proses Import</button>
			</div>
		</form>
	</div>
</div>

<script>
(function () {
	var $ = window.jQuery;
	if (!$) return;

	$('#nonaktifModal').on('show.bs.modal', function (e) {
		var btn = $(e.relatedTarget);
		$('#nonaktif-id').val(btn.data('id'));
		$('#nonaktif-nama').text(btn.data('nama'));
	});
	$('#aktifModal').on('show.bs.modal', function (e) {
		var btn = $(e.relatedTarget);
		$('#aktif-id').val(btn.data('id'));
		$('#aktif-nama').text(btn.data('nama'));
	});
})();
</script>