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
	.mutu-wrapper{background:var(--mutu-card-bg);border-radius:var(--mutu-radius);box-shadow:var(--mutu-shadow);border:1px solid var(--mutu-border);position:static!important;overflow:visible!important;max-width:720px}
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
	.icon-preview{width:42px;height:42px;border-radius:12px;background:var(--mutu-primary-light);color:var(--mutu-primary);display:inline-flex;align-items:center;justify-content:center;font-size:1rem;margin-left:10px}
	.btn-mutu{background:linear-gradient(135deg,var(--mutu-primary),#4338ca);color:#fff!important;font-weight:600;border:none;border-radius:10px;padding:10px 22px;font-size:.83rem;box-shadow:0 4px 12px -3px rgba(79,70,229,.4);transition:all .15s}
	.btn-mutu:hover{background:linear-gradient(135deg,#4338ca,#3730a3);transform:translateY(-1px)}
	.btn-mutu-outline{background:#fff;border:1px solid var(--mutu-border);color:var(--mutu-slate);font-weight:600;border-radius:10px;padding:10px 20px;font-size:.8rem}
	.btn-mutu-outline:hover{background:#f8fafc;color:var(--mutu-dark)}
	.onoff{display:flex;align-items:center;gap:8px}
	.onoff input{width:16px;height:16px;accent-color:var(--mutu-primary)}
	@media(max-width:768px){.mutu-header{padding:20px}.mutu-body{padding:18px}}
</style>

<div class="mutu-wrapper">

	<div class="mutu-header">
		<div class="mutu-header-title">
			<div class="mutu-header-icon"><i class="fas fa-bars"></i></div>
			<div>
				<h5><?= $jenis ?></h5>
				<p>Formulir menu sidebar</p>
			</div>
		</div>
	</div>

	<div class="mutu-body">

		<?php $flash = $this->session->flashdata('message'); ?>
		<?php if ($flash): ?><div class="mb-3"><?= $flash ?></div><?php endif; ?>

		<form method="POST" action="<?= $action ?>" class="mutu-form">
			<input type="hidden" name="id" value="<?= $menu ? $menu['id'] : '' ?>">

			<div class="form-group mb-3">
				<label for="nama_menu">Nama Menu</label>
				<input type="text" class="form-control" id="nama_menu" name="nama_menu"
					   value="<?= set_value('nama_menu', $menu ? $menu['nama_menu'] : '') ?>" placeholder="Contoh: MUKESPI (Mutu & PPI)">
				<?= form_error('nama_menu') ?>
			</div>

			<div class="form-group mb-3">
				<label for="url">URL / Alamat Menu</label>
				<input type="text" class="form-control" id="url" name="url"
					   value="<?= set_value('url', $menu ? $menu['url'] : '') ?>" placeholder="Contoh: list_indikator">
				<small class="text-muted">Segment pertama dari alamat halaman (mis. <code>pegawai</code>, <code>surat</code>, <code>dokumen_unit</code>).</small>
				<?= form_error('url') ?>
			</div>

			<div class="form-group mb-3">
				<label for="icon">Ikon (FontAwesome)</label>
				<div class="d-flex align-items-center">
					<input type="text" class="form-control" id="icon" name="icon"
						   value="<?= set_value('icon', $menu ? $menu['icon'] : 'fas fa-circle') ?>" placeholder="fas fa-heartbeat">
					<span class="icon-preview" id="iconPreview"><i class="fas fa-circle"></i></span>
				</div>
				<small class="text-muted">Pilih ikon dari <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a>.</small>
				<?= form_error('icon') ?>
			</div>

			<div class="form-group mb-3">
				<label for="sequence">Urutan (sequence)</label>
				<input type="number" class="form-control" id="sequence" name="sequence"
					   value="<?= set_value('sequence', $menu ? $menu['sequence'] : 0) ?>" min="0">
				<small class="text-muted">Semakin kecil, semakin atas posisinya di sidebar.</small>
				<?= form_error('sequence') ?>
			</div>

			<div class="form-group mb-4">
				<label>Status</label>
				<label class="onoff">
					<input type="checkbox" name="is_active" value="1" <?= set_checkbox('is_active', '1', (!$menu || (int)$menu['is_active'] === 1)) ?>>
					<span style="font-size:.84rem;font-weight:500">Aktif (tampil di daftar akses)</span>
				</label>
			</div>

			<div class="d-flex" style="gap:10px">
				<button type="submit" class="btn btn-mutu"><i class="fas fa-save mr-1"></i>Simpan</button>
				<a href="<?= site_url('menu') ?>" class="btn btn-mutu-outline">Batal</a>
			</div>
		</form>

	</div>
</div>

<script>
var iconInput = document.getElementById('icon');
var iconPreview = document.getElementById('iconPreview');
if (iconInput) {
    iconInput.addEventListener('input', function () {
        iconPreview.innerHTML = '<i class="' + this.value.trim() + '"></i>';
    });
}
</script>

</div>