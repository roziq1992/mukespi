<div class="container-fluid mutu-dashboard">

<style>
	:root{
		--mutu-card-bg:#ffffff;--mutu-primary:#4f46e5;--mutu-primary-light:#eef2ff;
		--mutu-dark:#0f172a;--mutu-slate:#334155;--mutu-muted:#64748b;--mutu-border:#e2e8f0;
		--mutu-shadow:0 10px 25px -5px rgba(15,23,42,.05),0 8px 10px -6px rgba(15,23,42,.05);
		--mutu-radius:16px;--mutu-font:'Inter',system-ui,-apple-system,sans-serif;
	}
	.mutu-dashboard{font-family:var(--mutu-font);color:var(--mutu-slate);padding:1rem 0}
	.mutu-dashboard *{box-sizing:border-box}
	.mutu-wrapper{background:var(--mutu-card-bg);border-radius:var(--mutu-radius);box-shadow:var(--mutu-shadow);border:1px solid var(--mutu-border);position:static!important;overflow:visible!important;max-width:640px}
	.mutu-header{background:linear-gradient(135deg,#1e1b4b 0%,#312e81 60%,#4338ca 100%);padding:24px 32px;position:relative;z-index:0!important;border-top-left-radius:var(--mutu-radius);border-top-right-radius:var(--mutu-radius);color:#fff}
	.mutu-header-title{display:flex;align-items:center;gap:16px}
	.mutu-header-icon{width:46px;height:46px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#a5b4fc;font-size:1.1rem}
	.mutu-header h5{margin:0;font-size:1.2rem;font-weight:700;color:#fff;letter-spacing:-.02em}
	.mutu-header p{margin:4px 0 0;font-size:.74rem;font-family:'JetBrains Mono',ui-monospace,monospace;color:#a5b4fc;letter-spacing:.08em;text-transform:uppercase;font-weight:600}
	.mutu-body{padding:28px 32px}
	.mutu-form label{font-size:.8rem;font-weight:600;color:var(--mutu-slate)}
	.mutu-form .form-control{border-color:var(--mutu-border);border-radius:10px;padding:10px 14px;font-size:.85rem}
	.mutu-form .form-control:focus{border-color:var(--mutu-primary);box-shadow:0 0 0 3px rgba(79,70,229,.12)}
	.mutu-form .text-danger{font-size:.74rem;font-weight:600}
	.btn-mutu{background:linear-gradient(135deg,var(--mutu-primary),#4338ca);color:#fff!important;font-weight:600;border:none;border-radius:10px;padding:10px 22px;font-size:.83rem;box-shadow:0 4px 12px -3px rgba(79,70,229,.4);transition:all .15s}
	.btn-mutu:hover{background:linear-gradient(135deg,#4338ca,#3730a3);transform:translateY(-1px)}
	.btn-mutu-outline{background:#fff;border:1px solid var(--mutu-border);color:var(--mutu-slate);font-weight:600;border-radius:10px;padding:10px 20px;font-size:.8rem}
	.btn-mutu-outline:hover{background:#f8fafc;color:var(--mutu-dark)}
	@media(max-width:768px){.mutu-header{padding:20px}.mutu-body{padding:18px}}
</style>

<div class="mutu-wrapper">

	<div class="mutu-header">
		<div class="mutu-header-title">
			<div class="mutu-header-icon"><i class="fas fa-user-tag"></i></div>
			<div>
				<h5><?= $jenis ?></h5>
				<p>Formulir role pengguna</p>
			</div>
		</div>
	</div>

	<div class="mutu-body">

		<?php $flash = $this->session->flashdata('message'); ?>
		<?php if ($flash): ?><div class="mb-3"><?= $flash ?></div><?php endif; ?>

		<form method="POST" action="<?= $action ?>" class="mutu-form">
			<input type="hidden" name="id" value="<?= $role ? $role['id'] : '' ?>">

			<div class="form-group mb-4">
				<label for="name">Nama Role</label>
				<input type="text" class="form-control" id="name" name="name"
					   value="<?= set_value('name', $role ? $role['name'] : '') ?>" placeholder="Contoh: manajer, staf, auditor">
				<?= form_error('name') ?>
			</div>

			<div class="d-flex" style="gap:10px">
				<button type="submit" class="btn btn-mutu"><i class="fas fa-save mr-1"></i>Simpan</button>
				<a href="<?= site_url('menu/roles') ?>" class="btn btn-mutu-outline">Batal</a>
			</div>
		</form>

	</div>
</div>

</div>