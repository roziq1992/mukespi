<style>
.ganti-pw-wrap{max-width:520px;margin:0 auto}
.ganti-pw-banner{background:linear-gradient(135deg,#7f1d1d 0%,#991b1b 60%,#b91c1c 100%);color:#fff;border-radius:14px;padding:20px 24px;margin:24px 0 20px;display:flex;align-items:center;gap:14px;box-shadow:0 10px 26px -10px rgba(153,27,27,.5)}
.ganti-pw-banner i{font-size:1.6rem}
.ganti-pw-banner b{font-size:.95rem;display:block;margin-bottom:3px}
.ganti-pw-banner span{font-size:.78rem;opacity:.9}
.ganti-pw-form{background:#fff;border:1px solid #e4eaf2;border-radius:14px;padding:26px;box-shadow:0 6px 20px -8px rgba(23,43,77,.08)}
.ganti-pw-form label{font-size:.82rem;font-weight:700;color:#334155}
.ganti-pw-form .form-control{border-color:#e4eaf2;border-radius:9px;padding:10px 14px;font-size:.88rem}
.ganti-pw-form .form-control:focus{border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.12)}
.btn-ganti-pw{background:linear-gradient(135deg,#4f46e5,#4338ca);border:none;color:#fff!important;font-weight:700;border-radius:9px;padding:10px 26px;transition:.15s;box-shadow:0 4px 12px -3px rgba(79,70,229,.4)}
.btn-ganti-pw:hover{transform:translateY(-1px);filter:brightness(1.05)}
.ganti-pw-hint{font-size:.74rem;color:#64748b;margin-top:6px}
</style>

<div class="container-fluid ganti-pw-wrap">

	<?php $flash = $this->session->flashdata('message'); ?>
	<?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

	<?php if ($wajib): ?>
	<div class="ganti-pw-banner">
		<i class="fas fa-exclamation-triangle"></i>
		<div>
			<b>Password Anda masih menggunakan kata sandi awal "admin".</b>
			<span>Untuk keamanan akun, sistem mengharuskan Anda mengganti password sebelum dapat mengakses menu lainnya.</span>
		</div>
	</div>
	<?php else: ?>
	<div class="mt-4 d-flex align-items-center justify-content-between">
		<h5 class="mb-0 text-gray-800"><i class="fas fa-key mr-1"></i> Ganti Password Akun</h5>
	</div>
	<?php endif; ?>

	<div class="ganti-pw-form">
		<form method="POST" action="<?php echo site_url('pegawai/ganti_password_action'); ?>">
			<div class="mb-3">
				<label>Password Baru</label>
				<input type="password" name="password_baru" class="form-control" placeholder="Minimal 5 karakter, jangan pakai 'admin'" required>
				<?php echo form_error('password_baru'); ?>
				<div class="ganti-pw-hint"><i class="fas fa-info-circle mr-1"></i>Minimal 5 karakter. Password "admin" tidak diperbolehkan.</div>
			</div>
			<div class="mb-4">
				<label>Konfirmasi Password Baru</label>
				<input type="password" name="password_konfirmasi" class="form-control" placeholder="Ulangi password baru" required>
				<?php echo form_error('password_konfirmasi'); ?>
			</div>
			<button type="submit" class="btn btn-ganti-pw"><i class="fas fa-save mr-1"></i> Simpan Password</button>
		</form>
	</div>
</div>