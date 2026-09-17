<div class="container-fluid mutu-dashboard">

<style>
	:root{
		--mutu-card-bg:#ffffff;--mutu-primary:#4f46e5;--mutu-primary-light:#eef2ff;--mutu-primary-border:rgba(79,70,229,.2);
		--mutu-dark:#0f172a;--mutu-slate:#334155;--mutu-muted:#64748b;--mutu-border:#e2e8f0;
		--mutu-success:#10b981;--mutu-success-light:#ecfdf5;--mutu-gold:#f59e0b;--mutu-gold-light:#fffbeb;
		--mutu-shadow:0 10px 25px -5px rgba(15,23,42,.05),0 8px 10px -6px rgba(15,23,42,.05);
		--mutu-radius:16px;--mutu-font:'Inter',system-ui,-apple-system,sans-serif;
	}
	.mutu-dashboard{font-family:var(--mutu-font);color:var(--mutu-slate);padding:1rem 0}
	.mutu-dashboard *{box-sizing:border-box}
	.mutu-wrapper{background:var(--mutu-card-bg);border-radius:var(--mutu-radius);box-shadow:var(--mutu-shadow);border:1px solid var(--mutu-border);position:static!important;overflow:visible!important;max-width:920px}
	.mutu-header{background:linear-gradient(135deg,#1e1b4b 0%,#312e81 60%,#4338ca 100%);padding:28px 32px;position:relative;z-index:0!important;border-top-left-radius:var(--mutu-radius);border-top-right-radius:var(--mutu-radius);color:#fff}
	.mutu-header-content{position:relative;z-index:1;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap}
	.mutu-header-title{display:flex;align-items:center;gap:16px}
	.mutu-header-icon{width:48px;height:48px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#a5b4fc;font-size:1.15rem}
	.mutu-header h5{margin:0;font-size:1.25rem;font-weight:700;color:#fff;letter-spacing:-.02em}
	.mutu-header h5 small{display:block;font-size:.78rem;font-weight:500;color:#a5b4fc;margin-top:2px}
	.mutu-header p{margin:4px 0 0;font-size:.75rem;font-family:'JetBrains Mono',ui-monospace,monospace;color:#a5b4fc;letter-spacing:.08em;text-transform:uppercase;font-weight:600}

	.btn-mutu{background:linear-gradient(135deg,var(--mutu-primary),#4338ca);color:#fff!important;font-weight:600;border:none;border-radius:10px;padding:9px 18px;font-size:.82rem;box-shadow:0 4px 12px -3px rgba(79,70,229,.4);transition:all .15s}
	.btn-mutu:hover{background:linear-gradient(135deg,#4338ca,#3730a3);transform:translateY(-1px);color:#fff!important}
	.btn-mutu-outline{background:#fff;border:1px solid var(--mutu-border);color:var(--mutu-slate);font-weight:600;border-radius:10px;padding:9px 16px;font-size:.8rem}
	.btn-mutu-outline:hover{background:#f8fafc;color:var(--mutu-dark)}

	.mutu-body{padding:24px 28px}

	.role-picker{display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:22px;padding:14px 16px;background:#f8fafc;border:1px solid var(--mutu-border);border-radius:12px}
	.role-picker label{margin:0;font-size:.8rem;font-weight:700;color:var(--mutu-slate)}
	.role-picker select{border:1px solid var(--mutu-border);border-radius:9px;padding:8px 12px;font-size:.85rem;outline:none;background:#fff}
	.role-picker select:focus{border-color:var(--mutu-primary);box-shadow:0 0 0 3px rgba(79,70,229,.12)}
	.info-pill{display:inline-flex;align-items:center;gap:6px;font-size:.72rem;font-weight:700;padding:4px 12px;border-radius:20px}
	.info-pill.ic-admin{background:#fdecea;color:#c0392b}
	.info-pill.ic-count{background:var(--mutu-primary-light);color:var(--mutu-primary)}

	.menu-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
	.menu-check{position:relative}
	.menu-check input{position:absolute;opacity:0;pointer-events:none}
	.menu-check-box{display:flex;align-items:center;gap:12px;padding:14px 15px;border:1.5px solid var(--mutu-border);border-radius:12px;cursor:pointer;transition:all .13s;background:#fff;min-height:56px}
	.menu-check-box:hover{border-color:#cbd5e1;background:#f8fafc}
	.menu-check input:checked + .menu-check-box{border-color:var(--mutu-primary);background:var(--mutu-primary-light);box-shadow:0 6px 16px -8px rgba(79,70,229,.4)}
	.menu-check-icon{width:38px;height:38px;border-radius:10px;background:#f1f5f9;color:var(--mutu-slate);display:flex;align-items:center;justify-content:center;font-size:.9rem;flex-shrink:0;transition:all .13s}
	.menu-check input:checked + .menu-check-box .menu-check-icon{background:var(--mutu-primary);color:#fff}
	.menu-check-name{font-size:.84rem;font-weight:600;color:var(--mutu-dark);line-height:1.25}
	.menu-check-url{font-size:.7rem;color:var(--mutu-muted);font-family:'JetBrains Mono',ui-monospace,monospace;margin-top:2px}
	.menu-check-tick{margin-left:auto;width:22px;height:22px;border-radius:50%;border:2px solid #cbd5e1;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.6rem;flex-shrink:0;transition:all .13s}
	.menu-check input:checked + .menu-check-box .menu-check-tick{background:var(--mutu-primary);border-color:var(--mutu-primary)}

	.save-bar{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-top:24px;padding-top:20px;border-top:1px solid #f1f5f9}
	.admin-note{font-size:.76rem;color:#92400e;background:var(--mutu-gold-light);border:1px solid rgba(245,158,11,.3);padding:9px 14px;border-radius:10px}

	@media(max-width:600px){.menu-grid{grid-template-columns:1fr}.mutu-header{padding:20px}.mutu-body{padding:16px}}
</style>

<div class="mutu-wrapper">

	<div class="mutu-header">
		<div class="mutu-header-content">
			<div class="mutu-header-title">
				<div class="mutu-header-icon"><i class="fas fa-user-lock"></i></div>
				<div>
					<h5>Role &amp; Hak Akses Menu <small>Role: <?= html_escape($role['name']) ?></small></h5>
					<p>Centang menu yang boleh diakses role ini</p>
				</div>
			</div>
			<div class="d-flex" style="gap:8px">
				<a class="btn btn-mutu-outline mr-2" href="<?= site_url('menu/roles') ?>"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
				<a class="btn btn-mutu-outline mr-2" href="<?= site_url('users') ?>"><i class="fas fa-user-cog mr-1"></i>User</a>
			</div>
		</div>
	</div>

	<div class="mutu-body">

		<?php $flash = $this->session->flashdata('message'); ?>
		<?php if ($flash): ?><div class="mb-3"><?= $flash ?></div><?php endif; ?>

		<form method="POST" action="<?= site_url('menu/role_access_save') ?>" id="accessForm">

			<div class="role-picker">
				<label for="roleSelect">Pilih Role:</label>
				<select id="roleSelect" onchange="if(this.value)location.href='<?= site_url('menu/role_access/') ?>'+this.value;">
					<?php foreach ($roles as $r): ?>
						<option value="<?= $r->id ?>" <?= ((int)$r->id === (int)$active_role_id) ? 'selected' : '' ?>><?= html_escape($r->name) ?></option>
					<?php endforeach; ?>
				</select>
				<span class="info-pill ic-count"><span id="checkedCount"><?= count($access_ids) ?></span> dari <?= count($menus) ?> menu dipilih</span>
				<?php if ((int)$role['id'] === 1): ?>
					<span class="info-pill ic-admin" style="margin-left:auto"><i class="fas fa-crown"></i>Admin selalu akses semua menu</span>
				<?php endif; ?>
			</div>

			<input type="hidden" name="role_id" value="<?= (int) $role['id'] ?>">

			<div class="menu-grid">
				<?php foreach ($menus as $m): ?>
					<?php $checked = in_array((int) $m->id, $access_ids); ?>
					<label class="menu-check">
						<input type="checkbox" name="menu_ids[]" value="<?= $m->id ?>"
							   <?= $checked ? 'checked' : '' ?> class="menu-chk">
						<span class="menu-check-box">
							<span class="menu-check-icon"><i class="<?= html_escape($m->icon) ?>"></i></span>
							<span>
								<span class="menu-check-name"><?= html_escape($m->nama_menu) ?></span>
								<div class="menu-check-url"><?= html_escape($m->url) ?></div>
							</span>
							<span class="menu-check-tick"><i class="fas fa-check"></i></span>
						</span>
					</label>
				<?php endforeach; ?>
			</div>

			<div class="save-bar">
				<?php if ((int)$role['id'] === 1): ?>
					<div class="admin-note"><i class="fas fa-info-circle mr-1"></i>Role admin mengakses semua menu secara otomatis, pengaturan di sini tidak mengurangi aksesnya.</div>
				<?php else: ?>
					<div class="info-pill ic-count"><i class="fas fa-check-circle"></i>Pilih menu lalu simpan</div>
				<?php endif; ?>
				<div class="d-flex" style="gap:8px">
					<button type="button" class="btn btn-mutu-outline mr-2" onclick="toggleAll(true)">Pilih Semua</button>
					<button type="button" class="btn btn-mutu-outline mr-2" onclick="toggleAll(false)">Kosongkan</button>
					<button type="submit" class="btn btn-mutu"><i class="fas fa-save mr-1"></i>Simpan Hak Akses</button>
				</div>
			</div>

		</form>

	</div>
</div>

</div>

<script>
(function () {
    var chks = Array.prototype.slice.call(document.querySelectorAll('.menu-chk'));
    var counter = document.getElementById('checkedCount');
    function refresh() {
        var n = chks.filter(function (c) { return c.checked; }).length;
        if (counter) counter.textContent = n;
    }
    chks.forEach(function (c) { c.addEventListener('change', refresh); });
    window.toggleAll = function (state) { chks.forEach(function (c) { c.checked = state; }); refresh(); };
})();
</script>