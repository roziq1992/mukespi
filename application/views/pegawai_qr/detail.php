<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<meta name="theme-color" content="#0b6b63">
	<title>Verifikasi Kepegawaian — RS Airlangga Jombang</title>
	<style>
		:root {
			color-scheme: light;
			font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
			color: #17352f;
			background: #f2f7f6;
			font-synthesis: none;
			text-rendering: optimizeLegibility;
			-webkit-font-smoothing: antialiased;
		}

		* { box-sizing: border-box; }

		body {
			min-height: 100vh;
			margin: 0;
			padding: 28px 18px;
			display: grid;
			place-items: center;
			background:
				radial-gradient(ellipse at 12% 8%, rgba(35, 169, 143, .12), transparent 34%),
				radial-gradient(ellipse at 92% 90%, rgba(41, 118, 167, .10), transparent 32%),
				#f2f7f6;
		}

		.page { width: min(100%, 520px); }

		.card {
			overflow: hidden;
			border: 1px solid rgba(217, 231, 227, .9);
			border-radius: 28px;
			background: #fff;
			box-shadow: 0 28px 70px rgba(27, 67, 59, .12), 0 3px 10px rgba(27, 67, 59, .04);
		}

		.top {
			position: relative;
			overflow: hidden;
			padding: 34px 28px 38px;
			color: #fff;
			text-align: center;
			background: linear-gradient(140deg, #0b514d 0%, #087b70 58%, #19a78d 100%);
		}

		.top::before, .top::after {
			position: absolute;
			width: 190px;
			height: 190px;
			border: 1px solid rgba(255, 255, 255, .12);
			border-radius: 50%;
			content: "";
			pointer-events: none;
		}

		.top::before { top: -118px; left: -68px; }
		.top::after { right: -112px; bottom: -132px; width: 250px; height: 250px; }

		.brand, .title, .subtitle { position: relative; z-index: 1; }

		.brand {
			display: inline-flex;
			align-items: center;
			gap: 12px;
			margin-bottom: 27px;
			text-align: left;
		}

		.brand-mark {
			display: grid;
			width: 46px;
			height: 46px;
			place-items: center;
			border: 1px solid rgba(255, 255, 255, .24);
			border-radius: 15px;
			background: rgba(255, 255, 255, .13);
		}

		.brand-mark svg { width: 26px; height: 26px; }
		.brand-name { font-size: 13px; font-weight: 750; letter-spacing: .045em; }
		.brand-place { margin-top: 3px; color: rgba(255, 255, 255, .72); font-size: 11px; }
		.title { margin: 0; font-size: clamp(24px, 6vw, 30px); font-weight: 760; letter-spacing: -.035em; }
		.subtitle { margin: 9px 0 0; color: rgba(255, 255, 255, .8); font-size: 14px; }

		.content { padding: 0 30px 28px; text-align: center; }

		.status-icon {
			display: grid;
			width: 76px;
			height: 76px;
			margin: -28px auto 18px;
			position: relative;
			place-items: center;
			border: 6px solid #fff;
			border-radius: 24px;
			color: #07816f;
			background: #e7f7f1;
			box-shadow: 0 8px 20px rgba(22, 111, 92, .13);
		}

		.status-icon svg { width: 34px; height: 34px; }
		.status-icon.invalid { color: #b4534b; background: #fff0ed; }
		.eyebrow { margin: 0; color: #748982; font-size: 11px; font-weight: 750; letter-spacing: .14em; text-transform: uppercase; }
		.status-title { margin: 8px 0 0; color: #153a32; font-size: 21px; font-weight: 760; letter-spacing: -.025em; }
		.status-copy { max-width: 370px; margin: 10px auto 0; color: #647a73; font-size: 14px; line-height: 1.75; }

		.divider { height: 1px; margin: 25px 0 19px; background: #e9f0ee; }

		.trust {
			display: flex;
			align-items: flex-start;
			gap: 12px;
			padding: 14px 15px;
			border: 1px solid #e4efec;
			border-radius: 16px;
			background: #f7fbfa;
			text-align: left;
		}

		.trust-icon {
			display: grid;
			width: 34px;
			height: 34px;
			flex: 0 0 34px;
			place-items: center;
			border-radius: 11px;
			color: #087b70;
			background: #e4f3ef;
		}

		.trust-icon svg { width: 18px; height: 18px; }
		.trust-title { margin: 0; color: #244b42; font-size: 12px; font-weight: 730; }
		.trust-copy { margin: 4px 0 0; color: #71847e; font-size: 11px; line-height: 1.6; }

		.footer {
			padding: 17px 20px;
			border-top: 1px solid #edf2f0;
			color: #879891;
			font-size: 11px;
			line-height: 1.6;
			text-align: center;
		}

		@media (max-width: 420px) {
			body { padding: 18px 13px; }
			.top { padding: 27px 20px 34px; }
			.content { padding-right: 21px; padding-left: 21px; }
			.brand { margin-bottom: 23px; }
		}
	</style>
</head>
<body>
	<?php
	$terverifikasi = $pegawai && isset($pegawai->status) && strtolower((string) $pegawai->status) === 'aktif';
	?>
	<main class="page">
		<section class="card" aria-live="polite">
			<header class="top">
				<div class="brand">
					<span class="brand-mark" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none">
							<path d="M12 3v18M3 12h18" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
							<path d="M5 5h14v14H5z" stroke="currentColor" stroke-width="1.3" opacity=".75"/>
						</svg>
					</span>
					<span>
						<span class="brand-name">RS AIRLANGGA</span>
						<span class="brand-place">Jombang</span>
					</span>
				</div>
				<h1 class="title">Verifikasi Kepegawaian</h1>
				<p class="subtitle">Informasi resmi dari RS Airlangga Jombang</p>
			</header>

			<div class="content">
				<div class="status-icon <?= $terverifikasi ? '' : 'invalid' ?>" aria-hidden="true">
					<?php if ($terverifikasi): ?>
						<svg viewBox="0 0 24 24" fill="none">
							<path d="m5 12.5 4.2 4.2L19 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M12 2.8 4.4 6v5.2c0 4.7 3.2 8.3 7.6 10 4.4-1.7 7.6-5.3 7.6-10V6L12 2.8Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
						</svg>
					<?php else: ?>
						<svg viewBox="0 0 24 24" fill="none">
							<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/>
							<path d="M12 7.5v5M12 16.3h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
						</svg>
					<?php endif; ?>
				</div>

				<?php if ($terverifikasi): ?>
					<p class="eyebrow">Status verifikasi</p>
					<h2 class="status-title">Pegawai Terverifikasi</h2>
					<p class="status-copy">QR code ini valid. Pemegang QR terdaftar sebagai pegawai aktif RS Airlangga Jombang.</p>
				<?php else: ?>
					<p class="eyebrow">Status verifikasi</p>
					<h2 class="status-title">Belum Dapat Diverifikasi</h2>
					<p class="status-copy">QR code tidak valid atau status kepegawaian tidak aktif. Silakan konfirmasi langsung kepada pihak RS Airlangga Jombang.</p>
				<?php endif; ?>

				<div class="divider"></div>
				<div class="trust">
					<span class="trust-icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none">
							<path d="M12 3 5 6v5c0 4.5 2.8 7.8 7 10 4.2-2.2 7-5.5 7-10V6l-7-3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
							<path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
					<span>
						<p class="trust-title">Privasi Anda terlindungi</p>
						<p class="trust-copy">Halaman ini hanya menampilkan hasil verifikasi. Data pribadi dan informasi kepegawaian tidak ditampilkan.</p>
					</span>
				</div>
			</div>

			<footer class="footer">Verifikasi diperbarui saat QR dipindai · <?= html_escape($tgl) ?><br>Jika memerlukan konfirmasi lebih lanjut, hubungi RS Airlangga Jombang.</footer>
		</section>
	</main>
</body>
</html>
