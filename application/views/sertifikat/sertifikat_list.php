<style>
	.sertifikat-card {
		background: #FFFFFF;
		border: 1px solid #CBD5E1;
		border-radius: 16px;
		box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
		margin-bottom: 24px;
		overflow: hidden;
	}
	.sertifikat-header {
		background: linear-gradient(135deg, #1E293B, #0F172A);
		color: #FFFFFF;
		padding: 20px 24px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-bottom: 1px solid rgba(255, 255, 255, 0.1);
	}
	.sertifikat-title-group {
		display: flex;
		align-items: center;
		gap: 12px;
	}
	.sertifikat-title-icon {
		width: 38px;
		height: 38px;
		border-radius: 10px;
		background: rgba(13, 148, 136, 0.2);
		border: 1px solid rgba(45, 212, 191, 0.3);
		display: flex;
		align-items: center;
		justify-content: center;
		color: #2DD4BF;
	}
	.sertifikat-header h2 {
		font-size: 18px;
		font-weight: 700;
		margin: 0;
		color: #FFFFFF;
	}
	.sertifikat-header p {
		font-size: 12px;
		color: #94A3B8;
		margin: 2px 0 0;
	}
	.sertifikat-body {
		padding: 24px;
	}

	/* Toolbar Actions & Search */
	.sertifikat-toolbar {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		margin-bottom: 20px;
	}
	.sertifikat-actions-left {
		display: flex;
		align-items: center;
		gap: 10px;
	}
	.btn-st-primary {
		background: linear-gradient(135deg, #0D9488, #0F766E);
		color: #FFFFFF !important;
		border: none;
		border-radius: 10px;
		padding: 9px 16px;
		font-weight: 600;
		font-size: 13.5px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		text-decoration: none !important;
		transition: all 0.2s ease;
		box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
	}
	.btn-st-primary:hover {
		transform: translateY(-1px);
		box-shadow: 0 6px 16px rgba(13, 148, 136, 0.35);
	}
	.btn-st-excel {
		background: #10B981;
		color: #FFFFFF !important;
		border: none;
		border-radius: 10px;
		padding: 9px 16px;
		font-weight: 600;
		font-size: 13.5px;
		display: inline-flex;
		align-items: center;
		gap: 8px;
		text-decoration: none !important;
		transition: all 0.2s ease;
	}
	.btn-st-excel:hover {
		background: #059669;
		transform: translateY(-1px);
	}

	.sertifikat-search-form {
		display: flex;
		align-items: center;
	}
	.sertifikat-search-shell {
		position: relative;
		display: flex;
		align-items: center;
		background: #F8FAFC;
		border: 1.5px solid #CBD5E1;
		border-radius: 10px;
		overflow: hidden;
		transition: all 0.2s;
	}
	.sertifikat-search-shell:focus-within {
		border-color: #0D9488;
		background: #FFFFFF;
		box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
	}
	.sertifikat-search-input {
		border: none;
		outline: none;
		background: transparent;
		padding: 8px 12px;
		font-size: 13.5px;
		color: #0F172A;
		min-width: 200px;
	}
	.btn-st-search {
		background: #0D9488;
		color: #FFFFFF;
		border: none;
		padding: 9px 14px;
		font-weight: 600;
		font-size: 13px;
		cursor: pointer;
		transition: background 0.2s;
	}
	.btn-st-search:hover { background: #0F766E; }
	.btn-st-reset {
		background: #E2E8F0;
		color: #475569 !important;
		border: none;
		padding: 9px 12px;
		font-size: 13px;
		font-weight: 600;
		text-decoration: none !important;
		transition: background 0.2s;
	}
	.btn-st-reset:hover { background: #CBD5E1; color: #1E293B !important; }

	/* Table Styling */
	.sertifikat-table-wrap {
		width: 100%;
		overflow-x: auto;
		border: 1px solid #E2E8F0;
		border-radius: 12px;
	}
	.sertifikat-table {
		width: 100%;
		border-collapse: collapse;
		font-size: 13.5px;
		color: #0F172A;
		text-align: left;
	}
	.sertifikat-table thead tr {
		background: #F1F5F9;
		border-bottom: 2px solid #CBD5E1;
	}
	.sertifikat-table th {
		padding: 12px 14px;
		font-weight: 700;
		color: #334155;
		text-transform: uppercase;
		font-size: 11.5px;
		letter-spacing: 0.05em;
		white-space: nowrap;
	}
	.sertifikat-table tbody tr {
		border-bottom: 1px solid #E2E8F0;
		transition: background 0.15s;
	}
	.sertifikat-table tbody tr:hover {
		background: #F8FAFC;
	}
	.sertifikat-table td {
		padding: 12px 14px;
		vertical-align: middle;
	}

	/* Action Buttons Grid */
	.st-action-btns {
		display: flex;
		align-items: center;
		gap: 6px;
	}
	.act-btn {
		height: 32px;
		border-radius: 8px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		text-decoration: none !important;
		transition: all 0.2s;
		font-size: 12.5px;
		font-weight: 600;
		padding: 0 10px;
		gap: 5px;
	}
	.act-btn-icon-only {
		width: 32px;
		padding: 0;
	}
	.act-btn-entry { background: #E0F2FE; color: #0284C7; }
	.act-btn-entry:hover { background: #0284C7; color: #FFFFFF; }
	.act-btn-update { background: #FEF3C7; color: #D97706; }
	.act-btn-update:hover { background: #D97706; color: #FFFFFF; }
	.act-btn-delete { background: #FFE4E6; color: #E11D48; }
	.act-btn-delete:hover { background: #E11D48; color: #FFFFFF; }

	/* Pagination & Footer */
	.sertifikat-footer {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		margin-top: 20px;
		padding-top: 12px;
	}
	.sertifikat-record-tag {
		background: #F1F5F9;
		color: #475569;
		padding: 6px 14px;
		border-radius: 20px;
		font-size: 12.5px;
		font-weight: 600;
		border: 1px solid #CBD5E1;
	}
	.sertifikat-pagination-wrap ul.pagination {
		margin: 0;
		display: flex;
		gap: 4px;
		list-style: none;
		padding: 0;
	}
	.sertifikat-pagination-wrap ul.pagination li a,
	.sertifikat-pagination-wrap ul.pagination li span {
		padding: 6px 12px;
		border-radius: 8px;
		border: 1px solid #CBD5E1;
		color: #334155;
		font-size: 13px;
		font-weight: 500;
		text-decoration: none;
		background: #FFFFFF;
	}
	.sertifikat-pagination-wrap ul.pagination li.active span,
	.sertifikat-pagination-wrap ul.pagination li.active a {
		background: #0D9488;
		border-color: #0D9488;
		color: #FFFFFF;
	}
</style>

<div class="container-fluid">
	<div class="sertifikat-card">
		<!-- Card Header -->
		<div class="sertifikat-header">
			<div class="sertifikat-title-group">
				<div class="sertifikat-title-icon">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M12 15l-2 5l9-5l-4-10l-10 4l3 9z"/>
						<circle cx="12" cy="8" r="6"/>
					</svg>
				</div>
				<div>
					<h2>Template Sertifikat</h2>
					<p>Kelola daftar template dan pendaftaran peserta sertifikat</p>
				</div>
			</div>
		</div>

		<!-- Card Body -->
		<div class="sertifikat-body">
			
			<!-- Flash Notification -->
			<?php if ($this->session->userdata('message')): ?>
				<div class="alert alert-info alert-dismissible fade show mb-3" role="alert" style="border-radius: 10px; font-size: 13.5px;">
					<?php echo $this->session->userdata('message'); ?>
				</div>
			<?php endif; ?>

			<!-- Toolbar (Actions & Search) -->
			<div class="sertifikat-toolbar">
				<div class="sertifikat-actions-left">
					<?php echo anchor(site_url('sertifikat/create'), '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Tambah Template', 'class="btn-st-primary"'); ?>
					<?php echo anchor(site_url('sertifikat/excel'), '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Export Excel', 'class="btn-st-excel"'); ?>
				</div>

				<form action="<?php echo site_url('sertifikat/index'); ?>" class="sertifikat-search-form" method="get">
					<div class="sertifikat-search-shell">
						<input type="text" class="sertifikat-search-input" name="q" placeholder="Cari judul sertifikat..." value="<?php echo htmlspecialchars($q ?? ''); ?>">
						<?php if (!empty($q)): ?>
							<a href="<?php echo site_url('sertifikat'); ?>" class="btn-st-reset">Reset</a>
						<?php endif; ?>
						<button class="btn-st-search" type="submit">Cari</button>
					</div>
				</form>
			</div>

			<!-- Data Table -->
			<div class="sertifikat-table-wrap">
				<table class="sertifikat-table">
					<thead>
						<tr>
							<th style="width: 50px; text-align: center;">No</th>
							<th>Judul Sertifikat</th>
							<th>Keterangan</th>
							<th style="width: 140px;">Tanggal</th>
							<th style="text-align: center; width: 220px;">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($sertifikat_data)): ?>
							<?php foreach ($sertifikat_data as $sertifikat): ?>
								<tr>
									<td style="text-align: center; font-weight: 600; color: #64748B;"><?php echo ++$start ?></td>
									<td style="font-weight: 600; color: #0F172A;"><?php echo htmlspecialchars($sertifikat->judul) ?></td>
									<td style="color: #475569;"><?php echo htmlspecialchars($sertifikat->ket) ?></td>
									<td style="white-space: nowrap; font-size: 12.5px; color: #64748B;"><?php echo htmlspecialchars($sertifikat->tanggal) ?></td>
									<td>
										<div class="st-action-btns" style="justify-content: center;">
											<a href="<?php echo site_url('sertifikat_peserta?id_sertifikat='.$sertifikat->id_sertifikat) ?>" class="act-btn act-btn-entry" title="Entry Peserta">
												<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
												Entry Peserta
											</a>
											<a href="<?php echo site_url('sertifikat/update/'.$sertifikat->id_sertifikat) ?>" class="act-btn act-btn-update act-btn-icon-only" title="Edit Data">
												<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
											</a>
											<a href="<?php echo site_url('sertifikat/delete/'.$sertifikat->id_sertifikat) ?>" class="act-btn act-btn-delete act-btn-icon-only" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
												<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
											</a>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="5" style="text-align: center; padding: 30px; color: #94A3B8;">
									Data template sertifikat tidak ditemukan.
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<!-- Footer Record & Pagination -->
			<div class="sertifikat-footer">
				<span class="sertifikat-record-tag">Total Record: <?php echo $total_rows ?></span>
				<div class="sertifikat-pagination-wrap">
					<ul class="pagination">
						<?php echo $pagination ?>
					</ul>
				</div>
			</div>

		</div>
	</div>
</div>