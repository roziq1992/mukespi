<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex, nofollow">
	<title>Terima Kasih — Survei Kepuasan Pasien RS Airlangga</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

	<style>
		:root {
			--sv-primary: #2563eb;
			--sv-success: #10b981;
			--sv-ink: #0f172a;
			--sv-ink2: #334155;
			--sv-muted: #64748b;
			--sv-line: #e2e8f0;
		}
		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

		body {
			font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
			background: linear-gradient(135deg, #102a43 0%, #1e4e79 100%);
			min-height: 100vh;
			display: flex; align-items: center; justify-content: center;
			padding: 24px 16px;
			color: var(--sv-ink);
			-webkit-font-smoothing: antialiased;
		}

		.tk-card {
			background: #fff;
			border-radius: 22px;
			box-shadow: 0 26px 60px -14px rgba(2, 20, 40, .5);
			max-width: 520px; width: 100%;
			padding: 38px 34px 32px;
			text-align: center;
			position: relative; overflow: hidden;
		}
		.tk-card::before {
			content: '';
			position: absolute; top: 0; left: 0; right: 0; height: 6px;
			background: linear-gradient(90deg, #10b981, #2563eb);
		}

		.tk-icon {
			width: 84px; height: 84px; margin: 0 auto 20px;
			border-radius: 50%;
			background: linear-gradient(135deg, #d1fae5, #a7f3d0);
			color: #059669;
			display: flex; align-items: center; justify-content: center;
			font-size: 2.2rem;
			animation: tkPop .45s cubic-bezier(.34,1.56,.64,1);
		}
		@keyframes tkPop { from { transform: scale(.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }

		.tk-card h1 { font-size: 1.3rem; font-weight: 800; letter-spacing: -.02em; }
		.tk-card > p { font-size: .86rem; color: var(--sv-muted); margin-top: 8px; }

		.tk-kode {
			display: inline-block; margin-top: 18px;
			background: #f1f5f9; border: 1px dashed #cbd5e1;
			border-radius: 10px; padding: 10px 20px;
			font-size: .82rem; font-weight: 700; color: var(--sv-ink2);
			font-family: 'JetBrains Mono', ui-monospace, monospace;
		}
		.tk-kode span { color: var(--sv-muted); font-weight: 600; display: block; font-size: .66rem; text-transform: uppercase; letter-spacing: .1em; margin-bottom: 3px; }

		.tk-ringkas {
			margin-top: 22px; padding: 18px;
			background: #f8fafc; border: 1px solid var(--sv-line); border-radius: 14px;
		}
		.tk-ringkas h2 { font-size: .74rem; text-transform: uppercase; letter-spacing: .1em; color: var(--sv-muted); font-weight: 800; margin-bottom: 10px; }
		.tk-big { font-size: 2.3rem; font-weight: 800; line-height: 1; color: var(--sv-primary); }
		.tk-stars { font-size: 1.1rem; color: var(--sv-gold, #f59e0b); margin: 8px 0 4px; }
		.tk-ket { font-size: .8rem; color: var(--sv-ink2); font-weight: 700; }

		.tk-badge {
			display: inline-block; margin-top: 12px;
			padding: 6px 16px; border-radius: 20px;
			font-size: .74rem; font-weight: 800;
		}
		.tk-badge.sb { background: #ecfdf5; color: #047857; }
		.tk-badge.b  { background: #eff6ff; color: #1d4ed8; }
		.tk-badge.c  { background: #fffbeb; color: #b45309; }
		.tk-badge.k  { background: #fef2f2; color: #b91c1c; }

		.tk-note {
			margin-top: 22px; padding: 14px 16px;
			background: #eff6ff; border-left: 3px solid var(--sv-primary);
			border-radius: 0 10px 10px 0;
			text-align: left; font-size: .78rem; color: #1e40af;
		}
		.tk-note b { color: #1e3a8a; }

		.tk-actions { margin-top: 24px; display: flex; gap: 10px; }
		.tk-btn {
			flex: 1;
			display: inline-flex; align-items: center; justify-content: center; gap: 8px;
			border-radius: 11px; padding: 12px 18px;
			font-size: .84rem; font-weight: 700; font-family: inherit;
			text-decoration: none; cursor: pointer; border: 1px solid transparent;
			transition: all .15s;
		}
		.tk-btn-primary { background: var(--sv-primary); color: #fff; box-shadow: 0 8px 18px -6px rgba(37,99,235,.5); }
		.tk-btn-primary:hover { background: #1d4ed8; transform: translateY(-2px); color: #fff; }
		.tk-btn-ghost { background: #fff; border-color: var(--sv-line); color: var(--sv-ink2); }
		.tk-btn-ghost:hover { background: #f8fafc; color: var(--sv-ink); }

		.tk-foot { margin-top: 20px; font-size: .7rem; color: var(--sv-muted); }
	</style>
</head>
<body>

<div class="tk-card">

	<?php if ($kode): ?>
		<div class="tk-icon"><i class="fas fa-check"></i></div>
		<h1>Terima Kasih!</h1>
		<p>Masukan Anda telah kami terima dan akan menjadi bahan perbaikan pelayanan RS Airlangga.</p>

		<div class="tk-kode">
			<span>Nomor Survei</span>
			<?= html_escape($kode); ?>
		</div>

		<?php if ($rata > 0): ?>
			<div class="tk-ringkas">
				<h2>Penilaian Anda</h2>
				<div class="tk-big"><?= number_format((float) $rata, 1, ',', '.'); ?></div>
				<div class="tk-stars">
					<?php for ($i = 1; $i <= 5; $i++): ?>
						<i class="<?= $i <= round((float) $rata) ? 'fas fa-star' : 'far fa-star'; ?>"></i>
					<?php endfor; ?>
				</div>
				<div class="tk-ket"><?= html_escape($predikat); ?></div>
				<span class="tk-badge <?= $predikat === 'Sangat Baik' ? 'sb' : ($predikat === 'Baik' ? 'b' : ($predikat === 'Cukup' ? 'c' : 'k')); ?>">
					<?= html_escape($predikat); ?>
				</span>
			</div>
		<?php endif; ?>

		<div class="tk-note">
			<i class="fas fa-bell"></i>
			<b>Butuh bantuan lebih lanjut?</b> Sampaikan saran Anda langsung kepada
			Bagian Layanan Pasien, atau melalui loket informasi utama RS Airlangga.
		</div>
	<?php else: ?>
		<div class="tk-icon" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#1d4ed8">
			<i class="fas fa-clipboard-check"></i>
		</div>
		<h1>Belum Ada Survei Diterima</h1>
		<p>Halaman ini menampilkan hasil setelah Anda mengisi dan mengirim formulir survei.</p>
	<?php endif; ?>

	<div class="tk-actions">
		<a class="tk-btn tk-btn-primary" href="<?= site_url('survei'); ?>">
			<i class="fas fa-star"></i> Isi Survei
		</a>
		<button type="button" class="tk-btn tk-btn-ghost" onclick="window.print()">
			<i class="fas fa-print"></i> Cetak
		</button>
	</div>

	<div class="tk-foot">&copy; <?= date('Y'); ?> RS Airlangga Jombang &mdash; Survei Kepuasan Pasien</div>

</div>

</body>
</html>
