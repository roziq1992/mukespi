<div class="container-fluid mutu-dashboard">

<style>
	:root{
		--mutu-card-bg:#ffffff;--mutu-primary:#4f46e5;--mutu-primary-light:#eef2ff;
		--mutu-dark:#0f172a;--mutu-slate:#334155;--mutu-muted:#64748b;--mutu-border:#e2e8f0;
		--mutu-danger:#ef4444;--mutu-danger-light:#fef2f2;
		--mutu-shadow:0 10px 25px -5px rgba(15,23,42,.05),0 8px 10px -6px rgba(15,23,42,.05);
		--mutu-radius:16px;--mutu-font:'Inter',system-ui,-apple-system,sans-serif;
	}
	.mutu-dashboard{font-family:var(--mutu-font);color:var(--mutu-slate);padding:1rem 0}
	.mutu-dashboard *{box-sizing:border-box}
	.mutu-wrapper{background:var(--mutu-card-bg);border-radius:var(--mutu-radius);box-shadow:var(--mutu-shadow);border:1px solid var(--mutu-border);position:static!important;overflow:visible!important;max-width:820px}
	.mutu-header{background:linear-gradient(135deg,#1e1b4b 0%,#312e81 60%,#4338ca 100%);padding:28px 32px;position:relative;z-index:0!important;border-top-left-radius:var(--mutu-radius);border-top-right-radius:var(--mutu-radius);color:#fff}
	.mutu-header-content{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap}
	.mutu-header-title{display:flex;align-items:center;gap:16px}
	.mutu-header-icon{width:48px;height:48px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#a5b4fc;font-size:1.15rem}
	.mutu-header h5{margin:0;font-size:1.25rem;font-weight:700;color:#fff;letter-spacing:-.02em}
	.mutu-header p{margin:4px 0 0;font-size:.75rem;font-family:'JetBrains Mono',ui-monospace,monospace;color:#a5b4fc;letter-spacing:.08em;text-transform:uppercase;font-weight:600}
	.btn-mutu{background:linear-gradient(135deg,var(--mutu-primary),#4338ca);color:#fff!important;font-weight:600;border:none;border-radius:10px;padding:9px 18px;font-size:.82rem;box-shadow:0 4px 12px -3px rgba(79,70,229,.4);transition:all .15s}
	.btn-mutu:hover{background:linear-gradient(135deg,#4338ca,#3730a3);transform:translateY(-1px);color:#fff!important}
	.btn-mutu-outline{background:#fff;border:1px solid var(--mutu-border);color:var(--mutu-slate);font-weight:600;border-radius:10px;padding:9px 16px;font-size:.8rem}
	.btn-mutu-outline:hover{background:#f8fafc;color:var(--mutu-dark)}
	.btn-mutu-success{background:linear-gradient(135deg,#10b981,#059669);color:#fff!important;font-weight:600;border:none;border-radius:10px;padding:9px 16px;font-size:.8rem;box-shadow:0 4px 12px -3px rgba(16,185,129,.4);transition:all .15s}
	.btn-mutu-success:hover{background:linear-gradient(135deg,#059669,#047857);transform:translateY(-1px)}
	.mutu-body{padding:24px 28px}
	.role-card{display:flex;align-items:center;gap:16px;border:1px solid var(--mutu-border);border-radius:14px;padding:16px 18px;transition:all .15s;margin-bottom:14px}
	.role-card:hover{border-color:var(--mutu-primary);box-shadow:0 6px 18px -8px rgba(79,70,229,.25)}
	.role-icon{width:46px;height:46px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1.1rem}
	.role-icon.ic-admin{background:#fdecea;color:#c0392b}
	.role-icon.ic-user{background:var(--mutu-primary-light);color:var(--mutu-primary)}
	.role-icon.ic-default{background:#f1f5f9;color:#64748b}
	.role-name{font-weight:700;color:var(--mutu-dark);font-size:.92rem}
	.role-id{font-size:.72rem;color:var(--mutu-muted);font-family:'JetBrains Mono',ui-monospace,monospace}
	.btn-action{width:32px;height:32px;border-radius:9px;border:1px solid var(--mutu-border);background:#fff;color:var(--mutu-slate);display:inline-flex;align-items:center;justify-content:center;font-size:.78rem;transition:all .12s;margin-left:6px}
	.btn-action:hover{transform:translateY(-1px)}
	.btn-action.btn-edit:hover{background:var(--mutu-primary-light);color:var(--mutu-primary)}
	.btn-action.btn-danger:hover{background:var(--mutu-danger-light);color:#b91c1c}
	.role-actions{margin-left:auto;display:flex;align-items:center;gap:6px}
	@media(max-width:768px){.mutu-header{padding:20px}.mutu-body{padding:16px}}
</style>

<div class="mutu-wrapper">

	<div class="mutu-header">
		<div class="mutu-header-content">
			<div class="mutu-header-title">
				<div class="mutu-header-icon"><i class="fas fa-user-tag"></i></div>
				<div>
					<h5>Manajemen Role</h5>
					<p>Kelola grup akses pengguna</p>
				</div>
			</div>
			<div class="d-flex" style="gap:8px">
				<a class="btn btn-mutu-outline mr-2" href="<?= site_url('menu') ?>"><i class="fas fa-bars mr-1"></i>Menu</a>
				<a class="btn btn-mutu-outline mr-2" href="<?= site_url('users') ?>"><i class="fas fa-user-cog mr-1"></i>User</a>
				<a class="btn btn-mutu" href="<?= site_url('menu/role_create') ?>"><i class="fas fa-plus mr-1"></i>Tambah Role</a>
			</div>
		</div>
	</div>

	<div class="mutu-body">

		<?php $flash = $this->session->flashdata('message'); ?>
		<?php if ($flash): ?><div class="mb-3"><?= $flash ?></div><?php endif; ?>

		<?php foreach ($roles as $r): ?>
			<?php $is_admin = ((int)$r->id === 1); ?>
			<div class="role-card">
				<div class="role-icon <?= $is_admin ? 'ic-admin' : ((int)$r->id === 2 ? 'ic-user' : 'ic-default') ?>">
					<i class="fas <?= $is_admin ? 'fa-crown' : 'fa-user-tag' ?>"></i>
				</div>
				<div>
					<div class="role-name"><?= html_escape($r->name) ?></div>
					<div class="role-id">role_id: <?= (int) $r->id ?></div>
				</div>
				<div class="role-actions">
					<a class="btn btn-mutu-success" href="<?= site_url('menu/role_access/' . $r->id) ?>"><i class="fas fa-user-lock mr-1"></i>Set Hak Akses</a>
					<?php if (!$is_admin): ?>
						<a class="btn-action btn-edit" href="<?= site_url('menu/role_edit/' . $r->id) ?>" title="Edit"><i class="fas fa-edit"></i></a>
						<a class="btn-action btn-danger" href="<?= site_url('menu/role_delete/' . $r->id) ?>"
						   onclick="return confirm('Hapus role &quot;<?= html_escape($r->name) ?>&quot;?')" title="Hapus"><i class="fas fa-trash"></i></a>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>

	</div>
</div>

</div>