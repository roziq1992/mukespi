<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex, nofollow">
	<title>Cetak QR Massal — RS Airlangga</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

	<style>
		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			background: #f1f5f9; color: #0f172a; padding: 20px 16px 40px;
		}

		.top { max-width: 1180px; margin: 0 auto 16px; }
		.top h1 { font-size: 20px; font-weight: 800; }
		.top p { font-size: 13px; color: #64748b; margin-top: 2px; }

		.tools {
			max-width: 1180px; margin: 0 auto 18px;
			display: flex; gap: 8px; align-items: center; flex-wrap: wrap;
		}
		.tools form { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
		.tools input[type=text], .tools select {
			font: inherit; font-size: 14px; padding: 9px 12px;
			border: 1px solid #cbd5e1; border-radius: 9px; background: #fff;
		}
		.tools input[type=text] { min-width: 230px; }
		.tools button, .tools a {
			font: inherit; font-size: 14px; font-weight: 600;
			padding: 9px 16px; border-radius: 9px; border: 0; cursor: pointer;
			background: #0f766e; color: #fff; text-decoration: none;
		}
		.tools .ghost { background: #fff; color: #334155; border: 1px solid #cbd5e1; }
		.count { margin-left: auto; font-size: 13px; color: #475569; }

		.grid {
			max-width: 1180px; margin: 0 auto;
			display: grid; gap: 14px;
			grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
		}
		.item {
			background: #fff; border-radius: 14px; padding: 16px 12px 14px;
			text-align: center;
			box-shadow: 0 8px 22px -16px rgba(15,23,42,.4);
			page-break-inside: avoid; break-inside: avoid;
		}
		.item canvas { display: block; margin: 0 auto; image-rendering: pixelated; }
		.item .nama { font-size: 13.5px; font-weight: 700; margin-top: 10px; line-height: 1.3; }
		.item .meta { font-size: 11.5px; color: #64748b; margin-top: 2px; line-height: 1.4; }
		.item .dl {
			display: inline-block; margin-top: 9px; font-size: 11.5px; font-weight: 600;
		 color: #0f766e; text-decoration: none; border: 1px solid #99f6e4;
		 border-radius: 7px; padding: 4px 10px;
		}
		.qr-gagal { font-size: 12px; color: #b91c1c; padding: 24px 6px; }

		.empty {
			max-width: 1180px; margin: 0 auto; background: #fff; border-radius: 14px;
			padding: 46px 20px; text-align: center; color: #64748b;
		}

		.note { max-width: 1180px; margin: 18px auto 0; font-size: 12px; color: #64748b; line-height: 1.7; }

		@media print {
			body { background: #fff; padding: 0; }
			.top, .tools, .note { display: none; }
			.grid { max-width: none; gap: 8mm; grid-template-columns: repeat(3, 1fr); }
			.item { box-shadow: none; border: 1px solid #cbd5e1; }
		}
	</style>
</head>
<body>

	<div class="top">
		<h1>Cetak QR Code Pegawai</h1>
		<p>NIK menjadi kunci QR. Pindai untuk membuka data pegawai tanpa login.</p>
	</div>

	<div class="tools">
		<form action="<?= site_url('pegawai_qr/cetak_semua') ?>" method="get">
			<input type="text" name="q" value="<?= html_escape($q) ?>" placeholder="Cari nama / NIP / unit...">
			<select name="status">
				<option value="aktif" <?= $status === 'aktif' ? 'selected' : '' ?>>Aktif</option>
				<option value="nonaktif" <?= $status === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
				<option value="semua" <?= $status === 'semua' ? 'selected' : '' ?>>Semua status</option>
			</select>
			<button type="submit"><i class="fas fa-filter"></i> Terapkan</button>
		</form>
		<button type="button" onclick="window.print()"><i class="fas fa-print"></i> Cetak halaman</button>
		<a class="ghost" href="<?= site_url('pegawai') ?>"><i class="fas fa-arrow-left"></i> Data Pegawai</a>
		<span class="count">
			<?= count($daftar) ?> pegawai punya NIK
			<?php if ($lewati > 0): ?>
				&middot; <?= $lewati ?> dilewati (NIK kosong/tidak valid)
			<?php endif; ?>
		</span>
	</div>

	<?php if (count($daftar) > 0): ?>
		<div class="grid">
			<?php foreach ($daftar as $i => $row): ?>
				<?php
				$p    = $row['pegawai'];
				$id   = 'qrMassal' . $i;
				$file = 'QR_' . preg_replace('/[^A-Za-z0-9]+/', '_', $p->nama) . '.png';
				$text = site_url('pegawai_qr/t/' . $row['token']);
				?>
				<div class="item">
					<canvas id="<?= $id ?>" data-qr="<?= html_escape($text) ?>" data-ukuran="170"></canvas>
					<div class="nama"><?= html_escape($p->nama) ?></div>
					<div class="meta"><?= html_escape($p->jabatan ?: '—') ?><br><?= html_escape($p->unit_kerja ?: '—') ?></div>
					<a href="#" class="dl" data-qr-unduh data-kanvas="<?= $id ?>" data-nama="<?= html_escape($file) ?>">
						<i class="fas fa-download"></i> PNG
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<div class="empty">
			<i class="fas fa-qrcode" style="font-size:40px;color:#cbd5e1;display:block;margin-bottom:10px;"></i>
			Tidak ada pegawai yang cocok dengan filter ini.
		</div>
	<?php endif; ?>

	<div class="note">
		Setiap QR berisi tautan yang dapat dibuka tanpa login. Cetak pada kartu identitas / name tag,
		lalu simpan salinan PNG bila diperlukan untuk arsip digital.
	</div>

	<script src="<?= base_url('assets/js/qrcode-generator.js') ?>"></script>
	<script src="<?= base_url('assets/js/qri-badge.js') ?>"></script>
</body>
</html>
