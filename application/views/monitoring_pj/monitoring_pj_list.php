<div class="container-fluid mutu-dashboard">

<style>
	.modal-backdrop { z-index: 1060 !important; }
	.modal { z-index: 1070 !important; }

	:root {
		--mutu-bg: #f8fafc;
		--mutu-card-bg: #ffffff;
		--mutu-primary: #3b82f6;
		--mutu-primary-hover: #1d4ed8;
		--mutu-primary-light: #eff6ff;
		--mutu-dark: #0f172a;
		--mutu-slate: #334155;
		--mutu-muted: #64748b;
		--mutu-border: #e2e8f0;
		--mutu-border-subtle: #f1f5f9;
		--mutu-success: #10b981;
		--mutu-success-light: #ecfdf5;
		--mutu-gold: #f59e0b;
		--mutu-gold-light: #fffbeb;
		--mutu-danger: #ef4444;
		--mutu-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.05);
		--mutu-radius: 16px;
		--mutu-font: 'Inter', system-ui, -apple-system, sans-serif;
		--mutu-font-mono: 'JetBrains Mono', ui-monospace, monospace;
	}

	.mutu-dashboard { font-family: var(--mutu-font); color: var(--mutu-slate); padding: 1rem 0; }
	.mutu-dashboard * { box-sizing: border-box; }

	.mutu-wrapper {
		background: var(--mutu-card-bg);
		border-radius: var(--mutu-radius);
		box-shadow: var(--mutu-shadow);
		border: 1px solid var(--mutu-border);
		position: relative;
		z-index: auto;
	}

	.mutu-header {
		background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
		padding: 28px 32px;
		position: relative;
		z-index: 1;
		border-top-left-radius: var(--mutu-radius);
		border-top-right-radius: var(--mutu-radius);
		overflow: hidden;
		color: #ffffff;
	}

	.mutu-header-content { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
	.mutu-header-title { display: flex; align-items: center; gap: 16px; }
	.mutu-header-icon {
		width: 48px; height: 48px;
		background: rgba(255, 255, 255, 0.12);
		border: 1px solid rgba(255, 255, 255, 0.18);
		border-radius: 12px;
		display: flex; align-items: center; justify-content: center;
		color: #60a5fa;
	}
	.mutu-header-icon svg { width: 24px; height: 24px; }
	.mutu-header h5 { margin: 0; font-size: 1.25rem; font-weight: 700; color: #ffffff; letter-spacing: -0.02em; }
	.mutu-header p { margin: 4px 0 0 0; font-size: 0.75rem; font-family: var(--mutu-font-mono); color: #94a3b8; letter-spacing: 0.08em; text-transform: uppercase; font-weight: 600; }

	.mutu-header-wave { position: absolute; bottom: 0; left: 0; right: 0; height: 40px; opacity: 0.15; z-index: 0; pointer-events: none; }
	.mutu-header-wave svg { width: 100%; height: 100%; }

	.mutu-toolbar { padding: 20px 32px 0; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }

	.mutu-flash:not(:empty) {
		flex: 1 1 300px;
		background: var(--mutu-gold-light);
		border: 1px solid #fde68a;
		color: #92400e;
		padding: 10px 16px;
		border-radius: 10px;
		font-size: 0.85rem;
		font-weight: 500;
	}

	.mutu-search { margin-left: auto; }
	.mutu-search-box { display: flex; align-items: center; background: #f8fafc; border: 1px solid var(--mutu-border); border-radius: 10px; padding: 4px; transition: all 0.2s ease; }
	.mutu-search-box:focus-within { background: #ffffff; border-color: var(--mutu-primary); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
	.mutu-search-box input { border: none; background: transparent; outline: none; padding: 8px 14px; font-size: 0.875rem; color: var(--mutu-dark); width: 250px; }

	.btn-reset-search { padding: 6px 12px; color: var(--mutu-muted); font-size: 0.8rem; text-decoration: none !important; font-weight: 600; transition: color 0.15s; }
	.btn-reset-search:hover { color: var(--mutu-danger); }

	.btn-submit-search { border: none; background: var(--mutu-primary); color: #ffffff; font-weight: 600; font-size: 0.85rem; padding: 8px 18px; border-radius: 8px; cursor: pointer; transition: background 0.15s; }
	.btn-submit-search:hover { background: var(--mutu-primary-hover); }

	.mutu-table-wrap { padding: 24px 32px; overflow-x: auto; }
	.mutu-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.875rem; }
	.mutu-table thead th {
		background: #fafafa; padding: 12px 16px; font-size: 0.725rem; font-family: var(--mutu-font-mono);
		font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--mutu-muted);
		border-top: 1px solid var(--mutu-border); border-bottom: 1px solid var(--mutu-border);
	}
	.mutu-table thead th:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; border-left: 1px solid var(--mutu-border); }
	.mutu-table thead th:last-child { border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-right: 1px solid var(--mutu-border); }
	.mutu-table tbody td { padding: 16px; border-bottom: 1px solid var(--mutu-border-subtle); vertical-align: middle; }
	.mutu-table tbody tr { transition: background 0.15s; }
	.mutu-table tbody tr:hover { background: #f8fafc; }
	.mutu-table tbody tr:last-child td { border-bottom: none; }

	.col-index { width: 50px; font-family: var(--mutu-font-mono); color: var(--mutu-muted); font-weight: 600; font-size: 0.8rem; }

	.badge-kelompok {
		display: inline-flex; align-items: center; padding: 4px 12px;
		background: var(--mutu-primary-light); color: var(--mutu-primary-hover);
		border: 1px solid rgba(59, 130, 246, 0.2); border-radius: 20px;
		font-size: 0.75rem; font-weight: 700; white-space: nowrap;
	}

	.txt-jenis-unit { color: var(--mutu-muted); font-weight: 500; }
	.txt-judul-indikator { font-weight: 600; color: var(--mutu-dark); line-height: 1.45; }

	.gauge-ring {
		position: relative; width: 46px; height: 46px; border-radius: 50%;
		display: flex; align-items: center; justify-content: center;
		background: conic-gradient(var(--mutu-primary) calc(var(--pct) * 1%), #e2e8f0 0);
	}
	.gauge-ring::after { content: ''; position: absolute; inset: 5px; border-radius: 50%; background: #ffffff; }
	.gauge-text { position: relative; z-index: 2; font-family: var(--mutu-font-mono); font-size: 0.7rem; font-weight: 700; color: var(--mutu-dark); }

	.action-flex { display: flex; align-items: center; justify-content: center; gap: 8px; }
	.btn-act { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; font-size: 0.8rem; font-weight: 600; border-radius: 8px; text-decoration: none !important; transition: all 0.15s ease; border: 1px solid transparent; }
	.btn-act-edit { background: #ffffff; border-color: var(--mutu-border); color: var(--mutu-slate); }
	.btn-act-edit:hover { background: var(--mutu-bg); border-color: #cbd5e1; color: var(--mutu-dark); }
	.btn-act-data { background: var(--mutu-dark); color: #ffffff !important; }
	.btn-act-data:hover { background: #1e293b; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15); }

	.mutu-empty-state { text-align: center; padding: 56px 20px; color: var(--mutu-muted); }
	.mutu-empty-state svg { width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 12px; }
	.mutu-empty-state p { margin: 0; font-size: 0.9rem; font-weight: 500; }

	.mutu-footer { padding: 20px 32px 24px; background: #fafafa; border-top: 1px solid var(--mutu-border); border-bottom-left-radius: var(--mutu-radius); border-bottom-right-radius: var(--mutu-radius); display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
	.mutu-footer-info { display: flex; align-items: center; gap: 12px; }
	.stat-chip { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: #ffffff; border: 1px solid var(--mutu-border); border-radius: 20px; font-size: 0.8rem; font-weight: 600; color: var(--mutu-slate); }
	.stat-chip .count { font-family: var(--mutu-font-mono); color: var(--mutu-primary); font-weight: 700; }

	.btn-export-excel { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: var(--mutu-success-light); border: 1px solid rgba(16, 185, 129, 0.3); color: #047857 !important; font-size: 0.8rem; font-weight: 700; border-radius: 20px; text-decoration: none !important; transition: all 0.15s; }
	.btn-export-excel:hover { background: #d1fae5; }

	.mutu-pagination-container :where(a, span) { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 34px; padding: 0 10px; margin: 0 2px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-decoration: none; }
	.mutu-pagination-container a { background: #ffffff; border: 1px solid var(--mutu-border); color: var(--mutu-slate); }
	.mutu-pagination-container a:hover { background: var(--mutu-primary-light); border-color: var(--mutu-primary); color: var(--mutu-primary); }
	.mutu-pagination-container span { background: var(--mutu-primary); color: #ffffff; border: 1px solid var(--mutu-primary); }
	.mutu-pagination-container ul { list-style: none; display: flex; margin: 0; padding: 0; }

	@media (max-width: 768px) {
		.mutu-header { padding: 20px; }
		.mutu-toolbar { padding: 16px 20px 0; flex-direction: column; align-items: stretch; }
		.mutu-search { margin-left: 0; width: 100%; }
		.mutu-search-box { flex-wrap: wrap; }
		.mutu-search-box input { width: 100%; }
		.mutu-table-wrap { padding: 16px 20px; }
		.mutu-table thead { display: none; }
		.mutu-table, .mutu-table tbody, .mutu-table tr, .mutu-table td { display: block; width: 100%; }
		.mutu-table tr { border: 1px solid var(--mutu-border); border-radius: 12px; margin-bottom: 12px; padding: 10px; background: #ffffff; }
		.mutu-table td { display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; border-bottom: none; }
		.mutu-table td::before { content: attr(data-label); font-family: var(--mutu-font-mono); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--mutu-muted); }
		.col-index { display: none; }
		.action-flex { justify-content: flex-end; width: 100%; }
		.mutu-footer { padding: 16px 20px; flex-direction: column; align-items: stretch; }
		.mutu-footer-info { justify-content: space-between; }
		.mutu-pagination-container { display: flex; justify-content: center; }
	}
</style>

	<div class="mutu-wrapper">

		<!-- Header -->
		<div class="mutu-header">
			<div class="mutu-header-content">
				<div class="mutu-header-title">
					<div class="mutu-header-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19V5a1 1 0 0 1 1-1h9l6 6v9a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1z"/><path d="M14 4v6h6"/><path d="M9 15l2 2 4-4"/></svg>
					</div>
					<div>
						<h5>Monitoring PJ Aplikasi</h5>
						<p>Penanggung Jawab & Progres Bulanan</p>
					</div>
				</div>
			</div>
			<div class="mutu-header-wave">
				<svg viewBox="0 0 600 34" preserveAspectRatio="none">
					<polyline points="0,17 140,17 160,4 180,30 200,17 340,17 360,6 380,28 400,17 600,17" fill="none" stroke="#60a5fa" stroke-width="2" />
				</svg>
			</div>
		</div>

		<!-- Toolbar -->
		<div class="mutu-toolbar">
			<div class="mutu-flash" id="message">
				<?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
			</div>

			<form action="<?php echo site_url('Monitoring_pj/index'); ?>" class="mutu-search" method="get">
				<div class="mutu-search-box">
					<input type="text" name="q" value="<?php echo $q; ?>" placeholder="Cari PJ / aplikasi / bulan...">
					<?php if ($q <> '') { ?>
						<a href="<?php echo site_url('Monitoring_pj'); ?>" class="btn-reset-search">Reset</a>
					<?php } ?>
					<button class="btn-submit-search" type="submit"><i class="fa fa-search mr-1"></i> Cari</button>
				</div>
			</form>
		</div>

		<!-- Table -->
		<div class="mutu-table-wrap">
			<?php if (count($monitoring_pj_data) > 0) { ?>
			<table class="mutu-table">
				<thead>
					<tr>
						<th class="col-index">No</th>
						<th>Nama PJ</th>
						<th>Nama Aplikasi</th>
						<th>Periode</th>
						<th>Progres</th>
						<th style="text-align:center">Aksi</th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach ($monitoring_pj_data as $monitoring_pj)
				{
					?>
					<tr>
						<td class="col-index"><?php echo ++$start ?></td>
						<td data-label="Nama PJ">
							<span class="badge-kelompok"><?php echo $monitoring_pj->nm_pj ?></span>
						</td>
						<td data-label="Nama Aplikasi" class="txt-judul-indikator">
							<?php echo $monitoring_pj->nama_aplikasi ?>
						</td>
						<td data-label="Periode" class="txt-jenis-unit">
							<?php echo $monitoring_pj->bulan . ' ' . $monitoring_pj->tahun ?>
						</td>
						<td data-label="Progres">
							<div class="gauge-ring" style="--pct: <?php echo (int) $monitoring_pj->progres ?>;">
								<span class="gauge-text"><?php echo $monitoring_pj->progres ?>%</span>
							</div>
						</td>
						<td data-label="Aksi">
							<div class="action-flex">
								<?php
								echo anchor(site_url('Monitoring_pj/update/'.$monitoring_pj->id_monitoring), '<i class="fa fa-edit"></i> Update', 'class="btn-act btn-act-edit"');
								?>
								<?php
								echo anchor(site_url('Monitoring_pj/delete/'.$monitoring_pj->id_monitoring), '<i class="fa fa-trash"></i> Hapus', 'class="btn-act btn-act-data" onclick="return confirm(\'Yakin hapus data ini?\')"');
								?>
							</div>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php } else { ?>
			<div class="mutu-empty-state">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 19V5a1 1 0 0 1 1-1h9l6 6v9a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1z"/><path d="M14 4v6h6"/></svg>
				<p>Belum ada data monitoring yang ditemukan.</p>
			</div>
			<?php } ?>
		</div>

		<!-- Footer -->
		<div class="mutu-footer">
			<div class="mutu-footer-info">
				<div class="stat-chip">Total Record: <span class="count"><?php echo $total_rows ?></span></div>
				<?php echo anchor(site_url('Monitoring_pj/create'), '<i class="fa fa-plus"></i> Tambah Data', 'class="btn-export-excel"'); ?>
				<?php echo anchor(site_url('Monitoring_pj/excel'), '<i class="fa fa-file-excel-o"></i> Export Excel', 'class="btn-export-excel"'); ?>
			</div>
			<div class="mutu-pagination-container">
				<?php echo $pagination ?>
			</div>
		</div>

	</div>

</div>