<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex, nofollow">
	<title>QR Code Pegawai — RS Airlangga</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

	<style>
		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			background: #f1f5f9; color: #0f172a; padding: 24px 16px 40px;
		}

		.bar {
			max-width: 420px; margin: 0 auto 16px;
			display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;
		}
		.bar button, .bar a {
			font: inherit; font-size: 14px; font-weight: 600;
			padding: 10px 18px; border-radius: 10px; border: 0; cursor: pointer;
			background: #0f766e; color: #fff; text-decoration: none;
		}
		.bar .ghost { background: #fff; color: #334155; border: 1px solid #cbd5e1; }

		.card {
			max-width: 420px; margin: 0 auto;
			background: #fff; border-radius: 18px;
			box-shadow: 0 18px 40px -22px rgba(15,23,42,.4);
			padding: 26px 24px 24px; text-align: center;
		}
		.card .rs { font-size: 11.5px; font-weight: 700; letter-spacing: 1.4px; text-transform: uppercase; color: #64748b; }
		.card h1 { font-size: 18px; font-weight: 800; margin-top: 2px; }
		.card .hr { height: 3px; width: 54px; background: #0f766e; border-radius: 3px; margin: 12px auto 18px; }

		.qr-box { display: inline-block; padding: 12px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; }
		canvas { display: block; image-rendering: pixelated; }
		.qr-gagal { font-size: 13px; color: #b91c1c; padding: 30px 10px; }

		.nama { font-size: 19px; font-weight: 800; margin-top: 16px; line-height: 1.3; }
		.jab { font-size: 13.5px; color: #475569; margin-top: 2px; }
		.tag {
			display: inline-block; margin-top: 10px; padding: 3px 12px; border-radius: 999px;
			font-size: 11.5px; font-weight: 700;
		}
		.tag-aktif { background: #dcfce7; color: #15803d; }
		.tag-nonaktif { background: #fee2e2; color: #b91c1c; }

		.kv {
			margin-top: 16px; padding-top: 14px; border-top: 1px dashed #cbd5e1;
			display: grid; grid-template-columns: 1fr 1fr; gap: 10px; text-align: left;
		}
		.kv div span { display: block; font-size: 10.5px; letter-spacing: .6px; text-transform: uppercase; color: #94a3b8; }
		.kv div strong { font-size: 13.5px; letter-spacing: .5px; }

		.hint { max-width: 420px; margin: 14px auto 0; text-align: center; font-size: 12px; color: #64748b; line-height: 1.7; }
		.hint b { color: #334155; }

		.token {
			margin-top: 14px; font-size: 11px; color: #94a3b8; word-break: break-all;
		}
		.token code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: #475569; }

		@media print {
			body { background: #fff; padding: 0; }
			.bar, .hint { display: none; }
			.card { box-shadow: none; border: 1px solid #cbd5e1; max-width: none; }
		}
	</style>
</head>
<body>

	<?php if ($pegawai): ?>
		<div class="bar">
			<button type="button" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
			<button type="button" data-qr-unduh data-kanvas="qrPegawai"
					data-nama="QR_<?= html_escape(preg_replace('/[^A-Za-z0-9]+/', '_', $pegawai->nama)) ?>.png">
				<i class="fas fa-download"></i> Unduh PNG
			</button>
			<a class="ghost" target="_blank" rel="noopener"
			   href="<?= site_url('pegawai_qr/t/' . $token) ?>">
				<i class="fas fa-eye"></i> Lihat hasil scan
			</a>
			<a class="ghost" href="<?= site_url('pegawai_qr/token_baru/' . $pegawai->nik) ?>"
			   onclick="return confirm('Buat QR baru? QR yang lama akan langsung tidak berlaku.');">
				<i class="fas fa-rotate"></i> Buat QR baru
			</a>
			<a class="ghost" href="<?= site_url('pegawai/detail/' . $pegawai->id_pegawai) ?>">
				<i class="fas fa-arrow-left"></i> Kembali
			</a>
		</div>

		<div class="card">
			<div class="rs">RS Airlangga</div>
			<h1>Identitas Pegawai</h1>
			<div class="hr"></div>

			<div class="qr-box">
				<canvas id="qrPegawai" data-qr="<?= html_escape($qr_text) ?>" data-ukuran="300"></canvas>
			</div>

			<div class="nama"><?= html_escape($pegawai->nama) ?></div>
			<div class="jab"><?= html_escape($pegawai->jabatan ?: '—') ?>
				<?= $pegawai->unit_kerja ? ' &middot; ' . html_escape($pegawai->unit_kerja) : '' ?>
			</div>
			<span class="tag <?= $pegawai->status === 'nonaktif' ? 'tag-nonaktif' : 'tag-aktif' ?>">
				<?= $pegawai->status === 'nonaktif' ? 'NONAKTIF' : 'AKTIF' ?>
			</span>

			<div class="kv">
				<div>
					<span>NIK</span>
					<strong><?= html_escape($pegawai->nik) ?></strong>
				</div>
				<div>
					<span>NIP / NRK</span>
					<strong><?= html_escape($pegawai->nip ?: '—') ?></strong>
				</div>
			</div>

			<div class="token">Token QR: <code><?= html_escape($token) ?></code></div>
		</div>

		<div class="hint">
			QR memuat tautan ber-token, <b>bukan NIK</b> — NIK tidak terbaca dari URL saat dipindai.<br>
			Tautan ini dapat dibuka tanpa login. Bila kartu hilang, gunakan <b>Buat QR baru</b> agar
			QR lama seketika tidak berlaku.
		</div>
	<?php else: ?>
		<div class="card">
			<div class="rs">RS Airlangga</div>
			<h1>QR Code Pegawai</h1>
			<div class="hr"></div>
			<div class="qr-gagal"><i class="fas fa-circle-exclamation"></i> <?= html_escape($error) ?></div>
			<p style="margin-top:16px;">
				<a href="<?= site_url('pegawai') ?>" style="font-size:14px;color:#0f766e;">Kembali ke Data Pegawai</a>
			</p>
		</div>
	<?php endif; ?>

	<script src="<?= base_url('assets/js/qrcode-generator.js') ?>"></script>
	<script src="<?= base_url('assets/js/qri-badge.js') ?>"></script>
</body>
</html>
