<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Sistem Informasi Manajemen Mutu, PPI, dan Dokumen Rumah Sakit">
	<meta name="author" content="Much Roziq, S.Kom">
	<title>RS AIRLANGGA V2 - Copyright@ 2026 Much Roziq, S.Kom | Login</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

<style>
	:root {
		--slate-panel: #1E293B;
		--slate-panel-dark: #0F172A;
		--teal: #0D9488;
		--teal-glow: rgba(13, 148, 136, 0.15);
		--page-bg: #E2E8F0;
		--card-bg: #FFFFFF;
		--ink-main: #0F172A;
		--ink-muted: #64748B;
		--line: #CBD5E1;
		--line-soft: #E2E8F0;
		--danger: #E11D48;
		--danger-bg: #FFF1F2;
		--font-body: 'Plus Jakarta Sans', -apple-system, sans-serif;
		--font-mono: 'IBM Plex Mono', monospace;
	}

	* { box-sizing: border-box; }
	html, body { margin: 0; padding: 0; height: 100%; }
	body {
		font-family: var(--font-body);
		color: var(--ink-main);
		background-color: var(--page-bg);
		min-height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
		position: relative;
		overflow-x: hidden;
	}

	/* Soft Background Decor */
	.bg-glow {
		position: fixed;
		width: 600px;
		height: 600px;
		border-radius: 50%;
		background: radial-gradient(circle, rgba(13, 148, 136, 0.12) 0%, rgba(226, 232, 240, 0) 70%);
		top: -180px;
		left: -180px;
		pointer-events: none;
		z-index: 0;
	}
	.bg-glow-2 {
		position: fixed;
		width: 500px;
		height: 500px;
		border-radius: 50%;
		background: radial-gradient(circle, rgba(30, 41, 59, 0.08) 0%, rgba(226, 232, 240, 0) 70%);
		bottom: -150px;
		right: -150px;
		pointer-events: none;
		z-index: 0;
	}

	/* Main Container */
	.auth-shell {
		position: relative;
		z-index: 1;
		display: grid;
		grid-template-columns: 1.15fr 1fr;
		width: 100%;
		max-width: 1120px;
		min-height: 660px;
		margin: 24px;
		background: var(--card-bg);
		border-radius: 20px;
		box-shadow: 0 16px 36px -10px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(203, 213, 225, 0.6);
		overflow: hidden;
	}

	/* ---------- BRAND PANEL (BALANCED MEDIUM) ---------- */
	.brand-panel {
		position: relative;
		background: linear-gradient(150deg, var(--slate-panel) 0%, #172233 60%, var(--slate-panel-dark) 100%);
		color: #F1F5F9;
		padding: 52px 48px;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		overflow: hidden;
		border-right: 1px solid rgba(255, 255, 255, 0.1);
	}

	.blueprint-grid {
		position: absolute;
		inset: 0;
		background-image: 
			linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
			linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
		background-size: 32px 32px;
		mask-image: radial-gradient(circle at 50% 50%, black, transparent 90%);
		pointer-events: none;
	}

	.brand-header { position: relative; z-index: 1; }

	.brand-badge {
		display: inline-flex;
		align-items: center;
		padding: 6px 14px;
		border-radius: 100px;
		background: rgba(255, 255, 255, 0.08);
		border: 1px solid rgba(255, 255, 255, 0.15);
		backdrop-filter: blur(8px);
		font-family: var(--font-mono);
		font-size: 11px;
		font-weight: 500;
		letter-spacing: .08em;
		text-transform: uppercase;
		color: #2DD4BF;
		margin-bottom: 28px;
	}

	.emblem-row {
		display: flex;
		align-items: center;
		gap: 16px;
		margin-bottom: 24px;
	}

	.emblem-wrapper {
		width: 52px;
		height: 52px;
		border-radius: 14px;
		background: rgba(13, 148, 136, 0.2);
		border: 1px solid rgba(45, 212, 191, 0.3);
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.emblem { width: 30px; height: 30px; }
	.emblem path, .emblem polyline {
		fill: none;
		stroke: #2DD4BF;
		stroke-width: 2;
		stroke-linecap: round;
		stroke-linejoin: round;
		stroke-dasharray: 220;
		stroke-dashoffset: 220;
		animation: draw 1.4s cubic-bezier(0.4, 0, 0.2, 1) forwards .2s;
	}
	.emblem .check {
		stroke: #FFFFFF;
		stroke-dasharray: 40;
		stroke-dashoffset: 40;
		animation: draw 0.6s ease forwards 1.1s;
	}
	@keyframes draw { to { stroke-dashoffset: 0; } }

	.brand-title h1 {
		font-size: 22px;
		font-weight: 800;
		line-height: 1.2;
		margin: 0;
		color: #FFFFFF;
		letter-spacing: -0.02em;
	}
	.brand-title p {
		margin: 2px 0 0;
		font-size: 13px;
		color: #94A3B8;
		font-weight: 500;
	}

	.system-intro {
		position: relative;
		z-index: 1;
		margin: 20px 0;
	}
	.system-name {
		font-size: clamp(26px, 2.8vw, 34px);
		font-weight: 800;
		line-height: 1.25;
		margin: 0 0 12px;
		color: #FFFFFF;
		letter-spacing: -0.03em;
	}
	.system-name em {
		font-style: normal;
		color: #2DD4BF;
	}
	.system-sub {
		font-size: 14px;
		color: #94A3B8;
		line-height: 1.6;
		margin: 0;
		max-width: 42ch;
	}
	.eoffice-note {
		display: flex;
		align-items: flex-start;
		gap: 11px;
		margin-top: 18px;
		padding: 12px 14px;
		border: 1px solid rgba(45, 212, 191, .25);
		border-radius: 10px;
		background: rgba(15, 118, 110, .18);
		max-width: 42ch;
	}
	.eoffice-note-icon {
		width: 30px;
		height: 30px;
		border-radius: 8px;
		background: #2DD4BF;
		color: #083344;
		display: flex;
		align-items: center;
		justify-content: center;
		flex: 0 0 30px;
	}
	.eoffice-note strong {
		display: block;
		font-size: 13px;
		color: #FFFFFF;
		margin-bottom: 3px;
	}
	.eoffice-note span {
		display: block;
		font-size: 11px;
		line-height: 1.5;
		color: #B6E8E2;
	}

	.module-grid {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		gap: 12px;
		margin-top: 28px;
		position: relative;
		z-index: 1;
	}
	.module-card {
		background: rgba(255, 255, 255, 0.05);
		border: 1px solid rgba(255, 255, 255, 0.08);
		padding: 12px 14px;
		border-radius: 12px;
		transition: all 0.2s ease;
	}
	.module-card:hover {
		background: rgba(255, 255, 255, 0.08);
		border-color: rgba(45, 212, 191, 0.3);
	}
	.module-card strong {
		display: block;
		font-size: 13px;
		font-weight: 700;
		color: #F8FAFC;
		margin-bottom: 2px;
	}
	.module-card span {
		display: block;
		font-size: 11px;
		color: #94A3B8;
		line-height: 1.3;
	}

	.brand-footer {
		position: relative;
		z-index: 1;
		padding-top: 24px;
		border-top: 1px solid rgba(255, 255, 255, 0.1);
		display: flex;
		align-items: center;
		justify-content: space-between;
		font-family: var(--font-mono);
		font-size: 11px;
		color: #94A3B8;
	}
	.status-pill {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		color: #CBD5E1;
	}
	.status-dot {
		width: 8px;
		height: 8px;
		border-radius: 50%;
		background: #2DD4BF;
		box-shadow: 0 0 8px rgba(45, 212, 191, 0.6);
		animation: pulse 2s infinite;
	}
	@keyframes pulse {
		0%, 100% { opacity: 1; transform: scale(1); }
		50% { opacity: 0.5; transform: scale(0.85); }
	}

	/* ---------- FORM PANEL ---------- */
	.form-panel {
		background: var(--card-bg);
		padding: 52px 48px;
		display: flex;
		flex-direction: column;
		justify-content: center;
	}
	.form-wrap {
		width: 100%;
		max-width: 360px;
		margin: 0 auto;
		animation: rise .5s ease-out both;
	}
	@keyframes rise {
		from { opacity: 0; transform: translateY(12px); }
		to { opacity: 1; transform: translateY(0); }
	}

	.form-header { margin-bottom: 32px; }
	.form-eyebrow {
		font-family: var(--font-mono);
		font-size: 11px;
		font-weight: 600;
		letter-spacing: .1em;
		text-transform: uppercase;
		color: var(--teal);
		display: block;
		margin-bottom: 6px;
	}
	.form-header h2 {
		font-size: 26px;
		font-weight: 800;
		color: var(--ink-main);
		margin: 0 0 6px;
		letter-spacing: -0.02em;
	}
	.form-header p {
		margin: 0;
		font-size: 14px;
		color: var(--ink-muted);
	}

	.flash-message:not(:empty) {
		display: flex;
		align-items: center;
		gap: 10px;
		background: var(--danger-bg);
		border: 1px solid rgba(225, 29, 72, 0.2);
		color: var(--danger);
		font-size: 13px;
		font-weight: 500;
		padding: 12px 14px;
		border-radius: 12px;
		margin-bottom: 24px;
	}

	.field { margin-bottom: 20px; }
	.field label {
		display: block;
		font-size: 13px;
		font-weight: 700;
		color: var(--ink-main);
		margin-bottom: 8px;
	}

	.input-shell {
		position: relative;
		display: flex;
		align-items: center;
		background: #F8FAFC;
		border: 1.5px solid var(--line);
		border-radius: 12px;
		transition: all 0.2s ease;
	}
	.input-shell:hover {
		border-color: #94A3B8;
		background: #FFFFFF;
	}
	.input-shell:focus-within {
		border-color: var(--teal);
		background: #FFFFFF;
		box-shadow: 0 0 0 4px var(--teal-glow);
	}
	.input-shell svg.icon-left {
		width: 18px;
		height: 18px;
		margin-left: 14px;
		flex: none;
		color: #64748B;
		transition: color 0.2s;
	}
	.input-shell:focus-within svg.icon-left {
		color: var(--teal);
	}
	.input-shell input {
		width: 100%;
		border: none;
		outline: none;
		background: transparent;
		font-family: var(--font-body);
		font-size: 14px;
		font-weight: 500;
		color: var(--ink-main);
		padding: 13px 14px;
	}
	.input-shell input::placeholder { color: #94A3B8; font-weight: 400; }

	.toggle-pass {
		background: none;
		border: none;
		cursor: pointer;
		color: #64748B;
		padding: 0 14px;
		display: flex;
		align-items: center;
		transition: color 0.2s;
	}
	.toggle-pass:hover { color: var(--ink-main); }
	.toggle-pass svg { width: 18px; height: 18px; }

	.field-error {
		display: block;
		font-size: 12px;
		font-weight: 600;
		color: var(--danger);
		margin-top: 6px;
	}

	.field-row {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin: -4px 0 24px;
	}
	.remember {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 13px;
		font-weight: 600;
		color: var(--ink-muted);
		cursor: pointer;
		user-select: none;
	}
	.remember input {
		appearance: none;
		-webkit-appearance: none;
		width: 18px;
		height: 18px;
		border: 1.5px solid #94A3B8;
		border-radius: 6px;
		cursor: pointer;
		position: relative;
		background: #F8FAFC;
		transition: all 0.2s;
	}
	.remember input:checked {
		background: var(--teal);
		border-color: var(--teal);
	}
	.remember input:checked::after {
		content: '';
		position: absolute;
		left: 5px;
		top: 2px;
		width: 4px;
		height: 8px;
		border: solid #FFFFFF;
		border-width: 0 2px 2px 0;
		transform: rotate(45deg);
	}

	.btn-submit {
		width: 100%;
		border: none;
		border-radius: 12px;
		padding: 14px 16px;
		background: linear-gradient(135deg, var(--teal), #0F766E);
		color: #FFFFFF;
		font-family: var(--font-body);
		font-weight: 700;
		font-size: 15px;
		cursor: pointer;
		transition: all 0.2s ease;
		box-shadow: 0 8px 16px -4px rgba(13, 148, 136, 0.35);
	}
	.btn-submit:hover {
		transform: translateY(-1px);
		box-shadow: 0 12px 20px -4px rgba(13, 148, 136, 0.45);
	}
	.btn-submit:active { transform: translateY(0); }

	.form-hr {
		border: none;
		border-top: 1px solid var(--line-soft);
		margin: 28px 0 20px;
	}
	.form-foot {
		font-size: 12.5px;
		color: var(--ink-muted);
		text-align: center;
		line-height: 1.5;
	}
	.form-foot .mono {
		font-family: var(--font-mono);
		font-size: 11px;
		color: #94A3B8;
		display: block;
		margin-top: 6px;
		font-weight: 500;
	}

	a, button, input { -webkit-tap-highlight-color: transparent; }
	:focus-visible { outline: 2px solid var(--teal); outline-offset: 2px; }

	/* ---------- RESPONSIVE ---------- */
	@media (max-width: 992px) {
		body { background: var(--page-bg); align-items: flex-start; }
		.bg-glow, .bg-glow-2 { display: none; }
		.auth-shell {
			grid-template-columns: 1fr;
			max-width: 500px;
			min-height: auto;
			margin: 20px auto;
			box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
			border-radius: 20px;
		}
		.brand-panel { padding: 36px 32px; }
		.module-grid { grid-template-columns: 1fr; }
		.form-panel { padding: 36px 32px; }
	}

	@media (max-width: 480px) {
		.auth-shell { margin: 0; border-radius: 0; min-height: 100vh; }
		.brand-panel { padding: 28px 24px; }
		.form-panel { padding: 28px 24px; }
	}
</style>
</head>
<body>

	<div class="bg-glow"></div>
	<div class="bg-glow-2"></div>

	<div class="auth-shell">

		<!-- ================= BRAND / CONTEXT PANEL ================= -->
		<div class="brand-panel">
			<div class="blueprint-grid" aria-hidden="true"></div>

			<div class="brand-header">
				<span class="brand-badge">Sistem Informasi Hospital V2</span>

				<div class="emblem-row">
					<div class="emblem-wrapper">
						<svg class="emblem" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<path d="M26 4 L46 11 V25 C46 37 37.5 45.5 26 48 C14.5 45.5 6 37 6 25 V11 Z"/>
							<path d="M26 16 V32 M18 24 H34" />
							<polyline class="check" points="18 26 23 31 34 18" />
						</svg>
					</div>
					<div class="brand-title">
						<h1>RS AIRLANGGA</h1>
						<p>Jombang, Jawa Timur</p>
					</div>
				</div>
			</div>

			<div class="system-intro">
				<h2 class="system-name">Mutu, PPI &amp; <em>Dokumen</em></h2>
				<p class="system-sub">Portal terpadu untuk pengelolaan indikator mutu, pencegahan infeksi, serta integrasi dokumen akreditasi rumah sakit.</p>
				<div class="eoffice-note">
					<div class="eoffice-note-icon"><i class="fas fa-envelope-open-text"></i></div>
					<div><strong>E-OFFICE RSA</strong><span>Manajemen surat internal dan eksternal untuk pengajuan, penomoran, disposisi, tanda tangan, dan pelacakan dokumen.</span></div>
				</div>

				<div class="module-grid">
					<div class="module-card">
						<strong>Manajemen Mutu</strong>
						<span>Indikator &amp; Insiden</span>
					</div>
					<div class="module-card">
						<strong>PPI &amp; Surveilans</strong>
						<span>Audit &amp; Kepatuhan</span>
					</div>
					<div class="module-card">
						<strong>Dokumen Akreditasi</strong>
						<span>SPO &amp; Regulasi</span>
					</div>
					<div class="module-card">
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

		<!-- ================= LOGIN FORM PANEL ================= -->
		<div class="form-panel">
			<div class="form-wrap">
				<div class="form-header">
					<span class="form-eyebrow">Portal Otentikasi</span>
					<h2>Selamat Datang</h2>
					<p>Silakan masuk menggunakan kredensial Anda.</p>
				</div>

				<div class="flash-message"><?= $this->session->flashdata('message'); ?></div>

				<form class="user" method="POST" action="">
					<div class="field">
						<label for="exampleInputEmail">Alamat Email</label>
						<div class="input-shell">
							<svg class="icon-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
							<input type="text" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="nama@rsairlangga.co.id" autocomplete="off" name="email" value="<?= set_value('email'); ?>">
						</div>
						<?= form_error('email', '<small class="field-error">', '</small>'); ?>
					</div>

					<div class="field">
						<label for="exampleInputPassword">Kata Sandi</label>
						<div class="input-shell">
							<svg class="icon-left" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
							<input type="password" id="exampleInputPassword" name="password" placeholder="••••••••" autocomplete="off">
							<button type="button" class="toggle-pass" id="togglePass" aria-label="Tampilkan kata sandi">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" id="eyeIcon"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
							</button>
						</div>
						<?= form_error('password', '<small class="field-error">', '</small>'); ?>
					</div>

					<div class="field-row">
						<label class="remember" for="customCheck">
							<input type="checkbox" id="customCheck">
							Ingat Sesi Saya
						</label>
					</div>

					<button type="submit" class="btn-submit">Masuk ke System</button>
				</form>

				<hr class="form-hr">
				<div class="form-foot">
					Kendalikan akses medis secara aman. Hubungi Tim IT jika kendala login.
					<span class="mono">RSA-SIM &middot; Build V2.0</span>
				</div>
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