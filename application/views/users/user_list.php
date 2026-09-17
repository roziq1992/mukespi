<div class="container-fluid mutu-dashboard">

<style>
	:root {
		--mutu-bg: #f8fafc;
		--mutu-card-bg: #ffffff;
		--mutu-primary: #4f46e5;
		--mutu-primary-hover: #4338ca;
		--mutu-primary-light: #eef2ff;
		--mutu-dark: #0f172a;
		--mutu-slate: #334155;
		--mutu-muted: #64748b;
		--mutu-border: #e2e8f0;
		--mutu-success: #10b981;
		--mutu-success-light: #ecfdf5;
		--mutu-danger: #ef4444;
		--mutu-danger-light: #fef2f2;
		--mutu-gold: #f59e0b;
		--mutu-shadow: 0 10px 25px -5px rgba(15,23,42,.05), 0 8px 10px -6px rgba(15,23,42,.05);
		--mutu-radius: 16px;
		--mutu-font: 'Inter', system-ui, -apple-system, sans-serif;
		--mutu-font-mono: 'JetBrains Mono', ui-monospace, monospace;
	}

	.mutu-dashboard { font-family: var(--mutu-font); color: var(--mutu-slate); padding: 1rem 0; }
	.mutu-dashboard * { box-sizing: border-box; }

	.mutu-wrapper { background: var(--mutu-card-bg); border-radius: var(--mutu-radius); box-shadow: var(--mutu-shadow); border: 1px solid var(--mutu-border); position: static !important; overflow: visible !important; }

	.mutu-header { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%); padding: 28px 32px; position: relative; z-index: 0 !important; border-top-left-radius: var(--mutu-radius); border-top-right-radius: var(--mutu-radius); overflow: hidden; color: #fff; }
	.mutu-header-content { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
	.mutu-header-title { display: flex; align-items: center; gap: 16px; }
	.mutu-header-icon { width: 48px; height: 48px; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #a5b4fc; font-size: 1.15rem; }
	.mutu-header h5 { margin: 0; font-size: 1.25rem; font-weight: 700; color: #fff; letter-spacing: -.02em; }
	.mutu-header p { margin: 4px 0 0; font-size: .75rem; font-family: var(--mutu-font-mono); color: #a5b4fc; letter-spacing: .08em; text-transform: uppercase; font-weight: 600; }

	.btn-mutu { background: linear-gradient(135deg, var(--mutu-primary), #4338ca); color: #fff !important; font-weight: 600; border: none; border-radius: 10px; padding: 9px 18px; font-size: .82rem; box-shadow: 0 4px 12px -3px rgba(79,70,229,.4); transition: all .15s; }
	.btn-mutu:hover { background: linear-gradient(135deg, #4338ca, #3730a3); transform: translateY(-1px); color: #fff !important; }
	.btn-mutu-outline { background: #fff; border: 1px solid var(--mutu-border); color: var(--mutu-slate); font-weight: 600; border-radius: 10px; padding: 9px 16px; font-size: .8rem; }
	.btn-mutu-outline:hover { background: #f8fafc; color: var(--mutu-dark); }

	.mutu-body { padding: 24px 28px; }

	.toolbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
	.search-box { display: flex; align-items: center; background: #f8fafc; border: 1px solid var(--mutu-border); border-radius: 10px; padding: 4px 8px 4px 14px; }
	.search-box i { color: #94a3b8; font-size: .8rem; }
	.search-box input { border: none; outline: none; background: transparent; padding: 8px 10px; font-size: .82rem; width: 240px; font-family: var(--mutu-font); }
	.info-count { font-size: .78rem; color: var(--mutu-muted); font-weight: 500; }

	.table-user { width: 100%; border-collapse: separate; border-spacing: 0; font-size: .83rem; }
	.table-user thead th { background: #f8fafc; color: var(--mutu-muted); font-size: .7rem; text-transform: uppercase; letter-spacing: .05em; font-weight: 700; padding: 12px 14px; text-align: left; border-bottom: 1px solid var(--mutu-border); }
	.table-user tbody td { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
	.table-user tbody tr:hover { background: #f8fafc; }
	.table-user tbody tr.text-muted-row { opacity: .62; }

	.user-cell { display: flex; align-items: center; gap: 12px; }
	.user-avatar { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 2px solid var(--mutu-border); }
	.user-avatar-placeholder { width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #c7d2fe, #a5b4fc); color: #312e81; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .9rem; }
	.user-name { font-weight: 600; color: var(--mutu-dark); }
	.user-email { font-size: .74rem; color: var(--mutu-muted); }

	.badge-role { display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 20px; font-size: .72rem; font-weight: 700; background: var(--mutu-primary-light); color: var(--mutu-primary); }
	.badge-role.badge-admin { background: #fdecea; color: #c0392b; }
	.badge-role.badge-direktur { background: #fff7ed; color: #c2680c; }
	.badge-role.badge-sekretaris { background: #ecfeff; color: #0e7490; }
	.badge-status { display: inline-flex; align-items: center; gap: 6px; padding: 4px 11px; border-radius: 20px; font-size: .72rem; font-weight: 700; }
	.badge-active { background: var(--mutu-success-light); color: #047857; }
	.badge-inactive { background: var(--mutu-danger-light); color: #b91c1c; }
	.status-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }

	.btn-action { width: 30px; height: 30px; border-radius: 8px; border: 1px solid var(--mutu-border); background: #fff; color: var(--mutu-slate); display: inline-flex; align-items: center; justify-content: center; font-size: .75rem; transition: all .12s; margin-right: 4px; }
	.btn-action:hover { transform: translateY(-1px); }
	.btn-action.btn-edit:hover { background: var(--mutu-primary-light); color: var(--mutu-primary); border-color: var(--mutu-primary-border); }
	.btn-action.btn-toggle:hover { background: #ecfdf5; color: #047857; border-color: rgba(16,185,129,.25); }
	.btn-action.btn-danger:hover { background: var(--mutu-danger-light); color: #b91c1c; border-color: rgba(239,68,68,.25); }

	.pagination-wrap { margin-top: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
	.pagination-wrap .pagination { margin: 0; }
	.pagination .page-item .page-link { border-radius: 8px !important; margin: 0 2px; font-size: .78rem; color: var(--mutu-slate); border-color: var(--mutu-border); }
	.pagination .page-item.active .page-link { background: var(--mutu-primary); border-color: var(--mutu-primary); }

	.empty-state { text-align: center; padding: 48px 20px; color: var(--mutu-muted); }
	.empty-state i { font-size: 2.2rem; color: #cbd5e1; margin-bottom: 12px; display: block; }
	.empty-state p { font-size: .85rem; margin: 0; }

	@media (max-width: 768px) {
		.mutu-header { padding: 20px; }
		.mutu-body { padding: 16px; }
		.table-user-wrap { overflow-x: auto; }
	}
</style>

<div class="mutu-wrapper">

	<div class="mutu-header">
		<div class="mutu-header-content">
			<div class="mutu-header-title">
				<div class="mutu-header-icon"><i class="fas fa-user-cog"></i></div>
				<div>
					<h5>Manajemen User</h5>
					<p>Kelola akun pengguna sistem</p>
				</div>
			</div>
			<div class="d-flex gap-2" style="gap:8px">
				<a class="btn btn-mutu-outline mr-2" href="<?= site_url('menu/roles') ?>"><i class="fas fa-user-tag mr-1"></i>Role</a>
				<a class="btn btn-mutu" href="<?= site_url('menu/role_access/1') ?>"><i class="fas fa-user-lock mr-1"></i>Role &amp; Hak Akses</a>
			</div>
		</div>
	</div>

	<div class="mutu-body">

		<?php $flash = $this->session->flashdata('message'); ?>
		<?php if ($flash): ?>
			<div class="mb-3"><?= $flash ?></div>
		<?php endif; ?>

		<div class="toolbar">
			<form method="get" action="<?= site_url('users') ?>" class="search-box">
				<i class="fas fa-search"></i>
				<input type="text" name="q" value="<?= html_escape($q) ?>" placeholder="Cari nama / email user...">
				<button type="submit" class="btn btn-mutu" style="padding:7px 14px;font-size:.75rem">Cari</button>
			</form>
			<div class="d-flex align-items-center" style="gap:10px">
				<span class="info-count"><?= $total_rows ?> user</span>
				<a class="btn btn-mutu" href="<?= site_url('users/create') ?>"><i class="fas fa-user-plus mr-1"></i>Tambah User</a>
			</div>
		</div>

		<div class="table-user-wrap">
			<table class="table-user">
				<thead>
					<tr>
						<th style="width:28%">User</th>
						<th>Role</th>
						<th>Status</th>
						<th style="width:160px" class="text-right">Aksi</th>
					</tr>
				</thead>
				<tbody>
				<?php if (empty($users_data)): ?>
					<tr><td colspan="4">
						<div class="empty-state">
							<i class="fas fa-inbox"></i>
							<p>Tidak ada data user.</p>
						</div>
					</td></tr>
				<?php else: foreach ($users_data as $u): ?>
					<tr class="<?= ((int)$u->is_active === 0) ? 'text-muted-row' : '' ?>">
						<td>
							<div class="user-cell">
								<?php if (!empty($u->avatar) && file_exists(FCPATH . $u->avatar)): ?>
									<img class="user-avatar" src="<?= base_url($u->avatar) ?>" alt="">
								<?php else: ?>
									<div class="user-avatar-placeholder"><?= strtoupper(mb_substr($u->name, 0, 1)) ?></div>
								<?php endif; ?>
								<div>
									<div class="user-name"><?= html_escape($u->name) ?>
										<?php if ((int)$u->id === (int)$me): ?><i class="fas fa-star text-warning" style="font-size:.65rem" title="Anda"></i><?php endif; ?>
									</div>
									<div class="user-email"><?= html_escape($u->email) ?></div>
								</div>
							</div>
						</td>
						<td>
							<span class="badge-role <?= ((int)$u->role_id === 1) ? 'badge-admin' : (($u->role_id == 4) ? 'badge-direktur' : (($u->role_id == 5) ? 'badge-sekretaris' : '')) ?>">
								<i class="fas fa-user-tag"></i><?= html_escape($u->role_name ?? 'Tanpa Role') ?>
							</span>
						</td>
						<td>
							<?php if ((int)$u->is_active === 1): ?>
								<span class="badge-status badge-active"><span class="status-dot"></span>Aktif</span>
							<?php else: ?>
								<span class="badge-status badge-inactive"><span class="status-dot"></span>Nonaktif</span>
							<?php endif; ?>
						</td>
						<td class="text-right">
							<a class="btn-action btn-edit" href="<?= site_url('users/update/' . $u->id) ?>" title="Edit"><i class="fas fa-edit"></i></a>
							<?php if ((int)$u->id !== (int)$me): ?>
								<a class="btn-action btn-toggle" href="<?= site_url('users/toggle_active/' . $u->id) ?>"
								   onclick="return confirm('<?= ((int)$u->is_active === 1) ? 'Nonaktifkan' : 'Aktifkan' ?> user &quot;<?= html_escape($u->name) ?>&quot;?')"
								   title="<?= ((int)$u->is_active === 1) ? 'Nonaktifkan' : 'Aktifkan' ?>"><i class="fas fa-user-slash"></i></a>
								<a class="btn-action btn-danger" href="<?= site_url('users/delete/' . $u->id) ?>"
								   onclick="return confirm('Hapus user &quot;<?= html_escape($u->name) ?>&quot;?')" title="Hapus"><i class="fas fa-trash"></i></a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>

		<div class="pagination-wrap">
			<span class="info-count">Menampilkan mulai dari baris <?= $start + 1 ?></span>
			<?= $pagination ?>
		</div>

	</div>
</div>

</div>