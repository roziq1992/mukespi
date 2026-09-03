<style>
	.ct-card {
		background: #FFFFFF;
		border: 1px solid #CBD5E1;
		border-radius: 16px;
		box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
		margin-bottom: 24px;
		overflow: hidden;
	}
	.ct-header {
		background: linear-gradient(135deg, #1E293B, #0F172A);
		color: #FFFFFF;
		padding: 20px 24px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		border-bottom: 1px solid rgba(255, 255, 255, 0.1);
	}
	.ct-title-group {
		display: flex;
		align-items: center;
		gap: 12px;
	}
	.ct-title-icon {
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
	.ct-header h2 {
		font-size: 18px;
		font-weight: 700;
		margin: 0;
		color: #FFFFFF;
	}
	.ct-header p {
		font-size: 12px;
		color: #94A3B8;
		margin: 2px 0 0;
	}
	.ct-body {
		padding: 24px;
	}

	/* Toolbar Actions & Search */
	.ct-toolbar {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		margin-bottom: 20px;
	}
	.ct-actions-left {
		display: flex;
		align-items: center;
		gap: 10px;
	}
	.btn-ct-primary {
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
	.btn-ct-primary:hover {
		transform: translateY(-1px);
		box-shadow: 0 6px 16px rgba(13, 148, 136, 0.35);
	}
	.btn-ct-excel {
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
	.btn-ct-excel:hover {
		background: #059669;
		transform: translateY(-1px);
	}

	.ct-search-form {
		display: flex;
		align-items: center;
	}
	.ct-search-shell {
		position: relative;
		display: flex;
		align-items: center;
		background: #F8FAFC;
		border: 1.5px solid #CBD5E1;
		border-radius: 10px;
		overflow: hidden;
		transition: all 0.2s;
	}
	.ct-search-shell:focus-within {
		border-color: #0D9488;
		background: #FFFFFF;
		box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
	}
	.ct-search-input {
		border: none;
		outline: none;
		background: transparent;
		padding: 8px 12px;
		font-size: 13.5px;
		color: #0F172A;
		min-width: 200px;
	}
	.btn-ct-search {
		background: #0D9488;
		color: #FFFFFF;
		border: none;
		padding: 9px 14px;
		font-weight: 600;
		font-size: 13px;
		cursor: pointer;
		transition: background 0.2s;
	}
	.btn-ct-search:hover { background: #0F766E; }
	.btn-ct-reset {
		background: #E2E8F0;
		color: #475569 !important;
		border: none;
		padding: 9px 12px;
		font-size: 13px;
		font-weight: 600;
		text-decoration: none !important;
		transition: background 0.2s;
	}
	.btn-ct-reset:hover { background: #CBD5E1; color: #1E293B !important; }

	/* Table Styling */
	.ct-table-wrap {
		width: 100%;
		overflow-x: auto;
		border: 1px solid #E2E8F0;
		border-radius: 12px;
	}
	.ct-table {
		width: 100%;
		border-collapse: collapse;
		font-size: 13.5px;
		color: #0F172A;
		text-align: left;
	}
	.ct-table thead tr {
		background: #F1F5F9;
		border-bottom: 2px solid #CBD5E1;
	}
	.ct-table th {
		padding: 12px 14px;
		font-weight: 700;
		color: #334155;
		text-transform: uppercase;
		font-size: 11.5px;
		letter-spacing: 0.05em;
		white-space: nowrap;
	}
	.ct-table tbody tr {
		border-bottom: 1px solid #E2E8F0;
		transition: background 0.15s;
	}
	.ct-table tbody tr:hover {
		background: #F8FAFC;
	}
	.ct-table td {
		padding: 12px 14px;
		vertical-align: middle;
	}

	/* Status & Badges */
	.badge-ct {
		display: inline-flex;
		align-items: center;
		padding: 4px 10px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 600;
		line-height: 1;
	}
	.badge-ct-success { background: #DCFCE7; color: #15803D; border: 1px solid #A7F3D0; }
	.badge-ct-danger { background: #FFE4E6; color: #BE123C; border: 1px solid #FECDD3; }
	.badge-ct-info { background: #E0F2FE; color: #0369A1; border: 1px solid #BAE6FD; }

	/* Action Buttons Grid */
	.ct-action-btns {
		display: flex;
		align-items: center;
		gap: 6px;
	}
	.act-btn {
		width: 30px;
		height: 30px;
		border-radius: 8px;
		display: flex;
		align-items: center;
		justify-content: center;
		text-decoration: none !important;
		transition: all 0.2s;
	}
	.act-btn-read { background: #E0F2FE; color: #0284C7; }
	.act-btn-read:hover { background: #0284C7; color: #FFFFFF; }
	.act-btn-update { background: #FEF3C7; color: #D97706; }
	.act-btn-update:hover { background: #D97706; color: #FFFFFF; }
	.act-btn-delete { background: #FFE4E6; color: #E11D48; }
	.act-btn-delete:hover { background: #E11D48; color: #FFFFFF; }

	/* Pagination & Footer */
	.ct-footer {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		margin-top: 20px;
		padding-top: 12px;
	}
	.ct-record-tag {
		background: #F1F5F9;
		color: #475569;
		padding: 6px 14px;
		border-radius: 20px;
		font-size: 12.5px;
		font-weight: 600;
		border: 1px solid #CBD5E1;
	}
	.ct-pagination-wrap ul.pagination {
		margin: 0;
		display: flex;
		gap: 4px;
		list-style: none;
		padding: 0;
	}
	.ct-pagination-wrap ul.pagination li a,
	.ct-pagination-wrap ul.pagination li span {
		padding: 6px 12px;
		border-radius: 8px;
		border: 1px solid #CBD5E1;
		color: #334155;
		font-size: 13px;
		font-weight: 500;
		text-decoration: none;
		background: #FFFFFF;
	}
	.ct-pagination-wrap ul.pagination li.active span,
	.ct-pagination-wrap ul.pagination li.active a {
		background: #0D9488;
		border-color: #0D9488;
		color: #FFFFFF;
	}
</style>

<div class="container-fluid">
	<div class="ct-card">
		<!-- Card Header -->
		<div class="ct-header">
			<div class="ct-title-group">
				<div class="ct-title-icon">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
					</svg>
				</div>
				<div>
					<h2>Data Audit Cuci Tangan</h2>
					<p>Monitoring kepatuhan dan surveilans kebersihan tangan medis</p>
				</div>
			</div>
		</div>

		<!-- Card Body -->
		<div class="ct-body">
			
			<!-- Flash Notification -->
			<?php if ($this->session->userdata('message')): ?>
				<div class="alert alert-info alert-dismissible fade show mb-3" role="alert" style="border-radius: 10px; font-size: 13.5px;">
					<?php echo $this->session->userdata('message'); ?>
				</div>
			<?php endif; ?>

			<!-- Toolbar (Actions & Search) -->
			<div class="ct-toolbar">
				<div class="ct-actions-left">
					<?php echo anchor(site_url('cuci_tangan/create'), '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Tambah Data', 'class="btn-ct-primary"'); ?>
					<?php echo anchor(site_url('cuci_tangan/excel'), '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Export Excel', 'class="btn-ct-excel"'); ?>
				</div>

				<form action="<?php echo site_url('cuci_tangan/index'); ?>" class="ct-search-form" method="get">
					<div class="ct-search-shell">
						<input type="text" class="ct-search-input" name="q" placeholder="Cari nama, unit, dll..." value="<?php echo htmlspecialchars($q ?? ''); ?>">
						<?php if (!empty($q)): ?>
							<a href="<?php echo site_url('cuci_tangan'); ?>" class="btn-ct-reset">Reset</a>
						<?php endif; ?>
						<button class="btn-ct-search" type="submit">Cari</button>
					</div>
				</form>
			</div>

			<!-- Data Table -->
			<div class="ct-table-wrap">
				<table class="ct-table">
					<thead>
						<tr>
							<th style="width: 50px; text-align: center;">No</th>
							<th>Nama</th>
							<th>Profesi</th>
							<th>Unit</th>
							<th style="text-align: center;">Kesempatan</th>
							<th style="text-align: center;">Cuci Tangan</th>
							<th>Jenis Cuci</th>
							<th>Tanggal</th>
							<th>Moment</th>
							<th style="text-align: center; width: 120px;">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($cuci_tangan_data)): ?>
							<?php foreach ($cuci_tangan_data as $cuci_tangan): ?>
								<tr>
									<td style="text-align: center; font-weight: 600; color: #64748B;"><?php echo ++$start ?></td>
									<td style="font-weight: 600; color: #0F172A;"><?php echo htmlspecialchars($cuci_tangan->nm) ?></td>
									<td><span class="badge-ct badge-ct-info"><?php echo htmlspecialchars($cuci_tangan->profesi) ?></span></td>
									<td><?php echo htmlspecialchars($cuci_tangan->nm_unit) ?></td>
									<td style="text-align: center; font-weight: 600;"><?php echo htmlspecialchars($cuci_tangan->kesempatan) ?></td>
									<td style="text-align: center;">
										<?php 
											$ct_val = strtolower($cuci_tangan->cucitangan);
											if (in_array($ct_val, ['ya', 'ya/melakukan', '1', 'sudah'])):
										?>
											<span class="badge-ct badge-ct-success">Ya</span>
										<?php else: ?>
											<span class="badge-ct badge-ct-danger"><?php echo htmlspecialchars($cuci_tangan->cucitangan) ?></span>
										<?php endif; ?>
									</td>
									<td><?php echo htmlspecialchars($cuci_tangan->ketcuci) ?></td>
									<td style="white-space: nowrap; font-size: 12.5px; color: #64748B;"><?php echo htmlspecialchars($cuci_tangan->tanggal) ?></td>
									<td><?php echo htmlspecialchars($cuci_tangan->nm_moment) ?></td>
									<td>
										<div class="ct-action-btns" style="justify-content: center;">
											<a href="<?php echo site_url('cuci_tangan/read/'.$cuci_tangan->id) ?>" class="act-btn act-btn-read" title="Lihat Detail">
												<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
											</a>
											<a href="<?php echo site_url('cuci_tangan/update/'.$cuci_tangan->id) ?>" class="act-btn act-btn-update" title="Edit Data">
												<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
											</a>
											<a href="<?php echo site_url('cuci_tangan/delete/'.$cuci_tangan->id) ?>" class="act-btn act-btn-delete" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
												<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
											</a>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="10" style="text-align: center; padding: 30px; color: #94A3B8;">
									Data tidak ditemukan.
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<!-- Footer Record & Pagination -->
			<div class="ct-footer">
				<span class="ct-record-tag">Total Record: <?php echo $total_rows ?></span>
				<div class="ct-pagination-wrap">
					<ul class="pagination">
						<?php echo $pagination ?>
					</ul>
				</div>
			</div>

		</div>
	</div>
</div>