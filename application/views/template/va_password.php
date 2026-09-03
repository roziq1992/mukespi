<style>
	.update-pwd-card {
		background: #FFFFFF;
		border: 1px solid #CBD5E1;
		border-radius: 16px;
		box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08);
		max-width: 520px;
		margin: 20px auto;
		overflow: hidden;
	}
	.update-pwd-header {
		background: linear-gradient(135deg, #1E293B, #0F172A);
		color: #FFFFFF;
		padding: 24px 28px;
		border-bottom: 1px solid rgba(255, 255, 255, 0.1);
	}
	.update-pwd-header h2 {
		font-size: 20px;
		font-weight: 700;
		margin: 0;
		color: #FFFFFF;
		letter-spacing: -0.01em;
	}
	.update-pwd-header p {
		font-size: 13px;
		color: #94A3B8;
		margin: 4px 0 0;
	}
	.update-pwd-body {
		padding: 28px;
	}
	.pwd-field {
		margin-bottom: 22px;
	}
	.pwd-field label {
		display: block;
		font-size: 13px;
		font-weight: 700;
		color: #0F172A;
		margin-bottom: 8px;
	}
	.pwd-input-shell {
		position: relative;
		display: flex;
		align-items: center;
		background: #F8FAFC;
		border: 1.5px solid #CBD5E1;
		border-radius: 10px;
		transition: all 0.2s ease;
	}
	.pwd-input-shell:focus-within {
		border-color: #0D9488;
		background: #FFFFFF;
		box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.15);
	}
	.pwd-input-shell svg.icon-left {
		width: 18px;
		height: 18px;
		margin-left: 14px;
		flex: none;
		color: #64748B;
	}
	.pwd-input-shell:focus-within svg.icon-left {
		color: #0D9488;
	}
	.pwd-input-shell input {
		width: 100%;
		border: none;
		outline: none;
		background: transparent;
		font-size: 14px;
		color: #0F172A;
		padding: 12px 14px;
	}
	.pwd-toggle-btn {
		background: none;
		border: none;
		cursor: pointer;
		color: #64748B;
		padding: 0 14px;
		display: flex;
		align-items: center;
		transition: color 0.2s;
	}
	.pwd-toggle-btn:hover { color: #0F172A; }
	.pwd-toggle-btn svg { width: 18px; height: 18px; }

	.pwd-actions {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-top: 28px;
	}
	.btn-pwd-submit {
		background: linear-gradient(135deg, #0D9488, #0F766E);
		color: #FFFFFF;
		border: none;
		border-radius: 10px;
		padding: 11px 20px;
		font-weight: 600;
		font-size: 14px;
		cursor: pointer;
		transition: all 0.2s;
		box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
	}
	.btn-pwd-submit:hover {
		transform: translateY(-1px);
		box-shadow: 0 6px 16px rgba(13, 148, 136, 0.35);
		color: #FFFFFF;
	}
	.btn-pwd-cancel {
		background: #F1F5F9;
		color: #475569;
		border: 1px solid #CBD5E1;
		border-radius: 10px;
		padding: 11px 20px;
		font-weight: 600;
		font-size: 14px;
		text-decoration: none;
		transition: all 0.2s;
		display: inline-block;
	}
	.btn-pwd-cancel:hover {
		background: #E2E8F0;
		color: #1E293B;
		text-decoration: none;
	}
</style>

<div class="update-pwd-card">
	<div class="update-pwd-header">
		<h2>Update Password</h2>
		<p>Masukkan kata sandi baru untuk akun Anda.</p>
	</div>

	<div class="update-pwd-body">
		<?php if ($this->session->flashdata('message')): ?>
			<div class="mb-3">
				<?= $this->session->flashdata('message'); ?>
			</div>
		<?php endif; ?>

		<form action="<?php echo $action; ?>" method="post">
			<div class="pwd-field">
				<label for="password">Password Baru</label>
				<div class="pwd-input-shell">
					<svg class="icon-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
						<path d="M7 11V7a5 5 0 0 1 10 0v4"/>
					</svg>
					<input type="password" name="password" id="password" placeholder="Ketik password baru..." required autocomplete="new-password" />
					<button type="button" class="pwd-toggle-btn" id="togglePwd" aria-label="Tampilkan password">
						<svg id="pwdEyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
							<circle cx="12" cy="12" r="3"/>
						</svg>
					</button>
				</div>
			</div>

			<div class="pwd-actions">
				<button type="submit" class="btn-pwd-submit"><?php echo $button ?></button> 
				<a href="<?php echo site_url('list_indikator') ?>" class="btn-pwd-cancel">Batal</a>
			</div>
		</form>
	</div>
</div>

<script>
	(function(){
		var toggle = document.getElementById('togglePwd');
		var pass = document.getElementById('password');
		var icon = document.getElementById('pwdEyeIcon');
		if(toggle && pass){
			toggle.addEventListener('click', function(){
				var isPass = pass.getAttribute('type') === 'password';
				pass.setAttribute('type', isPass ? 'text' : 'password');
				icon.innerHTML = isPass
					? '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.6 21.6 0 0 1 5.06-6.06M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.6 21.6 0 0 1-3.22 4.38M1 1l22 22"/><path d="M14.12 14.12A3 3 0 1 1 9.88 9.88"/>'
					: '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
			});
		}
	})();
</script>