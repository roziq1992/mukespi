<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Sistem Informasi Manajemen Mutu, PPI, dan Dokumen Rumah Sakit">
	<meta name="author" content="Much Roziq, S.Kom">
	<title>RS AIRLANGGA V2 - Login</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
	:root {
		--primary: #6366F1;
		--primary-dark: #4F46E5;
		--primary-glow: rgba(99, 102, 241, 0.15);
		--primary-subtle: rgba(99, 102, 241, 0.06);
		--accent: #22D3EE;
		--accent-dark: #06B6D4;
		--page-bg: #F8FAFC;
		--card-bg: #FFFFFF;
		--ink-main: #0F172A;
		--ink-secondary: #334155;
		--ink-muted: #64748B;
		--ink-faint: #94A3B8;
		--line: #E2E8F0;
		--line-focus: #C7D2FE;
		--input-bg: #F8FAFC;
		--input-hover: #FFFFFF;
		--danger: #EF4444;
		--danger-bg: #FEF2F2;
		--danger-border: rgba(239, 68, 68, 0.2);
		--success: #10B981;
		--radius-sm: 8px;
		--radius-md: 12px;
		--radius-lg: 16px;
		--radius-xl: 24px;
		--font-body: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
		--font-mono: 'JetBrains Mono', 'SF Mono', monospace;
		--shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
		--shadow-md: 0 4px 12px -2px rgba(0,0,0,0.06), 0 0 0 1px rgba(0,0,0,0.03);
		--shadow-lg: 0 12px 40px -8px rgba(0,0,0,0.1), 0 0 0 1px rgba(0,0,0,0.03);
		--shadow-xl: 0 24px 64px -12px rgba(0,0,0,0.12);
		--transition: 180ms cubic-bezier(0.4, 0, 0.2, 1);
	}

	*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
	html, body { height: 100%; }
	body {
		font-family: var(--font-body);
		color: var(--ink-main);
		background: var(--page-bg);
		min-height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
		overflow: hidden;
		position: relative;
	}

	/* Animated mesh background */
	.mesh-bg {
		position: fixed;
		inset: 0;
		z-index: 0;
		overflow: hidden;
	}
	.mesh-bg::before {
		content: '';
		position: absolute;
		width: 900px;
		height: 900px;
		border-radius: 50%;
		background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 65%);
		top: -350px;
		left: -250px;
		animation: floatA 18s ease-in-out infinite;
	}
	.mesh-bg::after {
		content: '';
		position: absolute;
		width: 700px;
		height: 700px;
		border-radius: 50%;
		background: radial-gradient(circle, rgba(34, 211, 238, 0.08) 0%, transparent 65%);
		bottom: -300px;
		right: -200px;
		animation: floatB 22s ease-in-out infinite;
	}
	@keyframes floatA {
		0%, 100% { transform: translate(0, 0) scale(1); }
		33% { transform: translate(40px, 30px) scale(1.05); }
		66% { transform: translate(-20px, 50px) scale(0.95); }
	}
	@keyframes floatB {
		0%, 100% { transform: translate(0, 0) scale(1); }
		50% { transform: translate(-30px, -40px) scale(1.08); }
	}

	/* Dot grid */
	.dot-grid {
		position: fixed;
		inset: 0;
		z-index: 0;
		background-image: radial-gradient(circle, rgba(100, 116, 139, 0.12) 1px, transparent 1px);
		background-size: 28px 28px;
		pointer-events: none;
	}

	/* ======================== MAIN SHELL ======================== */
	.auth-shell {
		position: relative;
		z-index: 1;
		display: grid;
		grid-template-columns: 1.2fr 1fr;
		width: 100%;
		max-width: 1080px;
		min-height: 640px;
		margin: 20px;
		background: var(--card-bg);
		border-radius: var(--radius-xl);
		box-shadow: var(--shadow-xl);
		overflow: hidden;
		animation: shellIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
	}
	@keyframes shellIn {
		from { opacity: 0; transform: translateY(16px) scale(0.98); }
		to { opacity: 1; transform: translateY(0) scale(1); }
	}

	/* ======================== BRAND PANEL ======================== */
	.brand-panel {
		position: relative;
		background: linear-gradient(160deg, #1E1B4B 0%, #312E81 40%, #1E1B4B 100%);
		color: #E0E7FF;
		padding: 48px 44px;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		overflow: hidden;
	}
	.brand-panel::before {
		content: '';
		position: absolute;
		inset: 0;
		background:
			radial-gradient(ellipse 400px 300px at 20% 80%, rgba(99, 102, 241, 0.2) 0%, transparent 70%),
			radial-gradient(ellipse 300px 250px at 80% 20%, rgba(34, 211, 238, 0.12) 0%, transparent 70%);
		pointer-events: none;
	}

	.brand-badge {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 5px 12px;
		border-radius: 100px;
		background: rgba(255, 255, 255, 0.07);
		border: 1px solid rgba(255, 255, 255, 0.1);
		font-family: var(--font-mono);
		font-size: 10.5px;
		font-weight: 600;
		letter-spacing: 0.1em;
		text-transform: uppercase;
		color: var(--accent);
		margin-bottom: 28px;
		backdrop-filter: blur(8px);
	}
	.brand-badge i { font-size: 10px; }

	.emblem-row {
		display: flex;
		align-items: center;
		gap: 14px;
		margin-bottom: 0;
	}
	.emblem-icon {
		width: 48px;
		height: 48px;
		border-radius: var(--radius-md);
		background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), rgba(34, 211, 238, 0.2));
		border: 1px solid rgba(255, 255, 255, 0.12);
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 22px;
		color: var(--accent);
		backdrop-filter: blur(8px);
	}
	.brand-title h1 {
		font-size: 20px;
		font-weight: 800;
		line-height: 1.2;
		margin: 0;
		color: #FFFFFF;
		letter-spacing: -0.02em;
	}
	.brand-title p {
		margin: 2px 0 0;
		font-size: 12.5px;
		color: #A5B4FC;
		font-weight: 500;
	}

	.system-intro {
		position: relative;
		z-index: 1;
		margin-top: auto;
	}
	.system-name {
		font-size: clamp(24px, 2.5vw, 32px);
		font-weight: 800;
		line-height: 1.2;
		margin: 0 0 10px;
		color: #FFFFFF;
		letter-spacing: -0.03em;
	}
	.system-name em {
		font-style: normal;
		background: linear-gradient(135deg, var(--accent), #A78BFA);
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
	}
	.system-sub {
		font-size: 13.5px;
		color: #A5B4FC;
		line-height: 1.65;
		margin: 0;
		max-width: 40ch;
	}

	.module-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 10px;
		margin-top: 24px;
		position: relative;
		z-index: 1;
	}
	.module-card {
		background: rgba(255, 255, 255, 0.04);
		border: 1px solid rgba(255, 255, 255, 0.06);
		padding: 12px 14px;
		border-radius: var(--radius-sm);
		transition: all var(--transition);
		backdrop-filter: blur(4px);
	}
	.module-card:hover {
		background: rgba(255, 255, 255, 0.08);
		border-color: rgba(99, 102, 241, 0.3);
		transform: translateY(-1px);
	}
	.module-card .mc-icon {
		font-size: 14px;
		color: var(--accent);
		margin-bottom: 6px;
		display: block;
	}
	.module-card strong {
		display: block;
		font-size: 12px;
		font-weight: 700;
		color: #F1F5F9;
		margin-bottom: 1px;
	}
	.module-card span {
		display: block;
		font-size: 10.5px;
		color: #818CF8;
		font-weight: 500;
		line-height: 1.3;
	}

	.brand-footer {
		position: relative;
		z-index: 1;
		padding-top: 20px;
		margin-top: 24px;
		border-top: 1px solid rgba(255, 255, 255, 0.08);
		display: flex;
		align-items: center;
		justify-content: space-between;
		font-family: var(--font-mono);
		font-size: 10.5px;
		color: #818CF8;
	}
	.status-pill {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		color: #C7D2FE;
	}
	.status-dot {
		width: 7px;
		height: 7px;
		border-radius: 50%;
		background: var(--success);
		box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
		animation: pulse 2s ease-in-out infinite;
	}
	@keyframes pulse {
		0%, 100% { opacity: 1; transform: scale(1); }
		50% { opacity: 0.4; transform: scale(0.8); }
	}

	/* ======================== FORM PANEL ======================== */
	.form-panel {
		background: var(--card-bg);
		padding: 48px 44px;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
	.form-wrap {
		width: 100%;
		max-width: 340px;
		animation: formIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.15s both;
	}
	@keyframes formIn {
		from { opacity: 0; transform: translateY(10px); }
		to { opacity: 1; transform: translateY(0); }
	}

	.form-logo {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-bottom: 32px;
	}
	.form-logo-icon {
		width: 36px;
		height: 36px;
		border-radius: var(--radius-sm);
		background: linear-gradient(135deg, var(--primary), var(--primary-dark));
		display: flex;
		align-items: center;
		justify-content: center;
		color: #fff;
		font-size: 16px;
		box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
	}
	.form-logo-text {
		font-size: 15px;
		font-weight: 700;
		color: var(--ink-main);
		letter-spacing: -0.01em;
	}

	.form-header { margin-bottom: 28px; }
	.form-eyebrow {
		font-family: var(--font-mono);
		font-size: 10.5px;
		font-weight: 600;
		letter-spacing: 0.1em;
		text-transform: uppercase;
		color: var(--primary);
		display: block;
		margin-bottom: 8px;
	}
	.form-header h2 {
		font-size: 24px;
		font-weight: 800;
		color: var(--ink-main);
		margin: 0 0 4px;
		letter-spacing: -0.03em;
	}
	.form-header p {
		margin: 0;
		font-size: 13.5px;
		color: var(--ink-muted);
		line-height: 1.5;
	}

	/* Flash message */
	.flash-message:not(:empty) {
		display: flex;
		align-items: center;
		gap: 10px;
		background: var(--danger-bg);
		border: 1px solid var(--danger-border);
		color: var(--danger);
		font-size: 12.5px;
		font-weight: 500;
		padding: 11px 14px;
		border-radius: var(--radius-md);
		margin-bottom: 20px;
		animation: shakeIn 0.4s ease;
	}
	@keyframes shakeIn {
		0%, 100% { transform: translateX(0); }
		20% { transform: translateX(-4px); }
		40% { transform: translateX(4px); }
		60% { transform: translateX(-2px); }
		80% { transform: translateX(2px); }
	}

	/* Fields */
	.field { margin-bottom: 18px; }
	.field label {
		display: block;
		font-size: 12.5px;
		font-weight: 600;
		color: var(--ink-secondary);
		margin-bottom: 6px;
		letter-spacing: -0.01em;
	}

	.input-shell {
		position: relative;
		display: flex;
		align-items: center;
		background: var(--input-bg);
		border: 1.5px solid var(--line);
		border-radius: var(--radius-md);
		transition: all var(--transition);
	}
	.input-shell:hover {
		border-color: var(--ink-faint);
		background: var(--input-hover);
	}
	.input-shell:focus-within {
		border-color: var(--primary);
		background: #FFFFFF;
		box-shadow: 0 0 0 3px var(--primary-glow);
	}
	.input-shell .icon-left {
		position: absolute;
		left: 13px;
		top: 50%;
		transform: translateY(-50%);
		width: 16px;
		height: 16px;
		color: var(--ink-faint);
		transition: color var(--transition);
		pointer-events: none;
	}
	.input-shell:focus-within .icon-left {
		color: var(--primary);
	}
	.input-shell input {
		width: 100%;
		border: none;
		outline: none;
		background: transparent;
		font-family: var(--font-body);
		font-size: 13.5px;
		font-weight: 500;
		color: var(--ink-main);
		padding: 12px 12px 12px 38px;
	}
	.input-shell input::placeholder {
		color: var(--ink-faint);
		font-weight: 400;
	}

	.toggle-pass {
		position: absolute;
		right: 4px;
		top: 50%;
		transform: translateY(-50%);
		background: none;
		border: none;
		cursor: pointer;
		color: var(--ink-faint);
		padding: 6px 10px;
		display: flex;
		align-items: center;
		border-radius: var(--radius-sm);
		transition: all var(--transition);
	}
	.toggle-pass:hover {
		color: var(--ink-secondary);
		background: rgba(0, 0, 0, 0.03);
	}
	.toggle-pass svg { width: 16px; height: 16px; }

	.field-error {
		display: block;
		font-size: 11.5px;
		font-weight: 600;
		color: var(--danger);
		margin-top: 5px;
	}

	/* Field row — remember + button */
	.field-row {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin: -2px 0 22px;
	}
	.remember {
		display: flex;
		align-items: center;
		gap: 8px;
		font-size: 12.5px;
		font-weight: 500;
		color: var(--ink-muted);
		cursor: pointer;
		user-select: none;
	}
	.remember input {
		appearance: none;
		-webkit-appearance: none;
		width: 16px;
		height: 16px;
		border: 1.5px solid var(--ink-faint);
		border-radius: 5px;
		cursor: pointer;
		position: relative;
		background: var(--input-bg);
		transition: all var(--transition);
	}
	.remember input:checked {
		background: var(--primary);
		border-color: var(--primary);
	}
	.remember input:checked::after {
		content: '';
		position: absolute;
		left: 4px;
		top: 1px;
		width: 4px;
		height: 8px;
		border: solid #FFF;
		border-width: 0 2px 2px 0;
		transform: rotate(45deg);
	}

	.btn-submit {
		width: 100%;
		border: none;
		border-radius: var(--radius-md);
		padding: 13px 16px;
		background: linear-gradient(135deg, var(--primary), var(--primary-dark));
		color: #FFFFFF;
		font-family: var(--font-body);
		font-weight: 700;
		font-size: 14px;
		letter-spacing: -0.01em;
		cursor: pointer;
		transition: all 200ms ease;
		box-shadow: 0 4px 14px -3px rgba(99, 102, 241, 0.4);
		position: relative;
		overflow: hidden;
	}
	.btn-submit::before {
		content: '';
		position: absolute;
		inset: 0;
		background: linear-gradient(135deg, rgba(255,255,255,0.12), transparent);
		opacity: 0;
		transition: opacity 200ms ease;
	}
	.btn-submit:hover {
		transform: translateY(-1px);
		box-shadow: 0 8px 20px -4px rgba(99, 102, 241, 0.5);
	}
	.btn-submit:hover::before { opacity: 1; }
	.btn-submit:active {
		transform: translateY(0);
		box-shadow: 0 2px 8px -2px rgba(99, 102, 241, 0.3);
	}

	.form-hr {
		border: none;
		border-top: 1px solid var(--line);
		margin: 24px 0 18px;
	}
	.form-foot {
		font-size: 12px;
		color: var(--ink-muted);
		text-align: center;
		line-height: 1.55;
	}
	.form-foot .mono {
		font-family: var(--font-mono);
		font-size: 10px;
		color: var(--ink-faint);
		display: block;
		margin-top: 5px;
		font-weight: 500;
		letter-spacing: 0.02em;
	}

	a, button, input { -webkit-tap-highlight-color: transparent; }
	:focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; }

	/* ======================== RESPONSIVE ======================== */
	@media (max-width: 960px) {
		body { align-items: flex-start; background: var(--page-bg); overflow: auto; }
		.mesh-bg, .dot-grid { display: none; }
		.auth-shell {
			grid-template-columns: 1fr;
			max-width: 480px;
			min-height: auto;
			margin: 16px auto;
			box-shadow: var(--shadow-lg);
		}
		.brand-panel { padding: 32px 28px; }
		.module-grid { grid-template-columns: 1fr; }
		.form-panel { padding: 32px 28px; }
	}

	@media (max-width: 480px) {
		.auth-shell { margin: 0; border-radius: 0; min-height: 100vh; }
		.brand-panel { padding: 24px 20px; }
		.form-panel { padding: 24px 20px; }
		.brand-footer { flex-direction: column; gap: 6px; text-align: center; }
	}
</style>
</head>
<body>

	<div class="mesh-bg"></div>
	<div class="dot-grid"></div>

	<div class="auth-shell">
		<!-- ============ FORM PANEL ============ -->
		<div class="form-panel">
			<div class="form-wrap">
				<div class="form-logo">
					<div class="form-logo-icon"><i class="fas fa-hospital"></i></div>
					<span class="form-logo-text">RSA V2</span>
				</div>

				<div class="form-header">
					<span class="form-eyebrow">Masuk ke akun Anda</span>
					<h2>Selamat Datang</h2>
					<p>Masukkan kredensial untuk mengakses sistem.</p>
				</div>

				<?php if (isset($this->session) && $this->session->flashdata('message')): ?>
					<div class="flash-message"><?= $this->session->flashdata('message'); ?></div>
				<?php endif; ?>

				<form class="user" method="POST" action="">
					<div class="field">
						<label for="exampleInputEmail">Email / NIK</label>
						<div class="input-shell">
							<i class="fas fa-envelope icon-left"></i>
							<input type="text" id="exampleInputEmail" placeholder="nama@rsairlangga.co.id atau NIK" autocomplete="off" name="email" value="<?= set_value('email'); ?>">
						</div>
						<?= form_error('email', '<small class="field-error">', '</small>'); ?>
					</div>

					<div class="field">
						<label for="exampleInputPassword">Kata Sandi</label>
						<div class="input-shell">
							<i class="fas fa-lock icon-left"></i>
							<input type="password" id="exampleInputPassword" name="password" placeholder="Masukkan kata sandi" autocomplete="off">
							<button type="button" class="toggle-pass" id="togglePass" aria-label="Tampilkan kata sandi">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="eyeIcon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
							</button>
						</div>
						<?= form_error('password', '<small class="field-error">', '</small>'); ?>
					</div>

					<button type="submit" class="btn-submit">Masuk</button>
				</form>

				<hr class="form-hr">
				<div class="form-foot">
					Kendalikan akses medis secara aman. Hubungi Tim IT jika kendala login.
					<span class="mono">RSA-SIM &middot; Build V2.0</span>
				</div>
			</div>
		</div>
		<!-- ============ BRAND PANEL ============ -->
		<div class="brand-panel">
			<div class="brand-header">
				<span class="brand-badge"><i class="fas fa-hospital"></i> Sistem Informasi V2</span>

				<div class="emblem-row">
					<div class="emblem-icon"><i class="fas fa-shield-halved"></i></div>
					<div class="brand-title">
						<h1>RS AIRLANGGA</h1>
						<p>Jombang, Jawa Timur</p>
					</div>
				</div>
			</div>

			<div class="system-intro">
				<h2 class="system-name">Mutu, PPI &amp; <em>Dokumen</em></h2>
				<p class="system-sub">Portal terpadu untuk pengelolaan indikator mutu, pencegahan infeksi, serta integrasi dokumen akreditasi rumah sakit.</p>

				<div class="module-grid">
					<div class="module-card">
						<i class="fas fa-chart-line mc-icon"></i>
						<strong>Manajemen Mutu</strong>
						<span>Indikator &amp; Insiden</span>
					</div>
					<div class="module-card">
						<i class="fas fa-virus-slash mc-icon"></i>
						<strong>PPI &amp; Surveilans</strong>
						<span>Audit &amp; Kepatuhan</span>
					</div>
					<div class="module-card">
						<i class="fas fa-folder-open mc-icon"></i>
						<strong>Dokumen Akreditasi</strong>
						<span>SPO &amp; Regulasi</span>
					</div>
					<div class="module-card">
						<i class="fas fa-stethoscope mc-icon"></i>
						<strong>Audit Klinis</strong>
						<span>Temuan &amp; Perbaikan</span>
					</div>
				</div>
			</div>

			<div class="brand-footer">
				<span class="status-pill"><span class="status-dot"></span> Server Online</span>
				<span>&copy; 2026 Much Roziq, S.Kom</span>
			</div>
		</div>
	</div>

	<script>
	(function(){
		var toggle = document.getElementById('togglePass');
		var pass = document.getElementById('exampleInputPassword');
		var icon = document.getElementById('eyeIcon');
		if(toggle && pass){
			toggle.addEventListener('click', function(){
				var isPass = pass.getAttribute('type') === 'password';
				pass.setAttribute('type', isPass ? 'text' : 'password');
				toggle.setAttribute('aria-label', isPass ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
				icon.innerHTML = isPass
					? '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.6 21.6 0 0 1 5.06-6.06M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a21.6 21.6 0 0 1-3.22 4.38M1 1l22 22"/><path d="M14.12 14.12A3 3 0 1 1 9.88 9.88"/>'
					: '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
			});
		}
	})();
	</script>
</body>
</html>