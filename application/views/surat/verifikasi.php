<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex, nofollow">
	<title>Verifikasi Dokumen Surat — RS Airlangga</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

	<style>
		:root {
			--vd-primary: #1d4ed8;
			--vd-primary-dark: #1e3a8a;
			--vd-ink: #0f172a;
			--vd-ink2: #334155;
			--vd-muted: #64748b;
			--vd-line: #e2e8f0;
			--vd-bg: #f1f5f9;
		}

		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

		body {
			font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
			background: var(--vd-bg);
			color: var(--vd-ink);
			line-height: 1.55;
			-webkit-font-smoothing: antialiased;
			padding-bottom: 40px;
		}

		.vd-head {
			background: linear-gradient(135deg, #0b2b2b 0%, #1e3a8a 55%, #1d4ed8 100%);
			color: #fff;
			padding: 24px 18px 54px;
			text-align: center;
		}
		.vd-head .logo {
			width: 54px; height: 54px; margin: 0 auto 12px;
			border-radius: 16px;
			background: rgba(255,255,255,.16);
			display: flex; align-items: center; justify-content: center;
			font-size: 26px;
		}
		.vd-head h1 { font-size: 20px; font-weight: 800; letter-spacing: .2px; }
		.vd-head p { font-size: 13px; opacity: .85; margin-top: 2px; }
		.vd-badge {
			display: inline-flex; align-items: center; gap: 6px;
			margin-top: 12px; padding: 6px 14px;
			background: rgba(255,255,255,.18);
			border: 1px solid rgba(255,255,255,.35);
			border-radius: 999px; font-size: 12.5px; font-weight: 700;
		}
		.vd-badge-bad { background: rgba(220,38,38,.25); border-color: rgba(255,255,255,.5); }

		.vd-wrap { max-width: 720px; margin: -34px auto 0; padding: 0 16px; }

		.vd-card {
			background: #fff;
			border-radius: 16px;
			box-shadow: 0 18px 40px -20px rgba(15,23,42,.35);
			overflow: hidden;
		}

		.vd-strip { height: 6px; }
		.vd-strip.ok { background: linear-gradient(90deg, #16a34a, #22c55e); }
		.vd-strip.bad { background: linear-gradient(90deg, #b91c1c, #ef4444); }

		.ident {
			display: flex; gap: 16px; align-items: center;
			padding: 22px 22px 18px;
			border-bottom: 1px solid var(--vd-line);
		}
		.avatar {
			width: 68px; height: 68px; flex: 0 0 68px;
			border-radius: 18px;
			background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
			color: #fff;
			display: flex; align-items: center; justify-content: center;
			font-size: 28px;
		}
		.avatar.bad { background: linear-gradient(135deg, #94a3b8, #cbd5e1); }
		.ident h2 { font-size: 19px; font-weight: 800; line-height: 1.25; }
		.ident .sub { font-size: 13px; color: var(--vd-muted); margin-top: 2px; }

		.tag {
			display: inline-block; margin-top: 8px;
			padding: 3px 10px; border-radius: 999px;
			font-size: 11.5px; font-weight: 700; letter-spacing: .3px;
		}
		.tag-ok { background: #dcfce7; color: #15803d; }
		.tag-bad { background: #fee2e2; color: #b91c1c; }

		.cols { padding: 6px 22px 10px; }
		.row {
			display: flex; gap: 12px;
			padding: 10px 0;
			border-bottom: 1px dashed var(--vd-line);
			font-size: 14px;
		}
		.row:last-child { border-bottom: 0; }
		.row .k { flex: 0 0 40%; color: var(--vd-muted); }
		.row .v { flex: 1; font-weight: 600; word-break: break-word; }

		.msg { padding: 46px 26px; text-align: center; }
		.msg .ico { font-size: 46px; color: #cbd5e1; margin-bottom: 12px; }
		.msg h3 { font-size: 17px; font-weight: 700; margin-bottom: 6px; }
		.msg p { font-size: 14px; color: var(--vd-muted); }

		.vd-foot {
			margin-top: 16px; text-align: center;
			font-size: 11.5px; color: var(--vd-muted); line-height: 1.7;
		}
		.vd-foot code { background: #e2e8f0; padding: 1px 5px; border-radius: 4px; }
	</style>
</head>
<body>

	<div class="vd-head">
		<div class="logo"><i class="fas fa-file-signature"></i></div>
		<h1>Verifikasi Dokumen Surat</h1>
		<p>RS Airlangga &mdash; hasil pemindaian QR Code</p>
		<?php if ($surat && $sah): ?>
			<span class="vd-badge"><i class="fas fa-check-circle"></i> Dokumen Asli &amp; Terverifikasi</span>
		<?php elseif ($surat): ?>
			<span class="vd-badge vd-badge-bad"><i class="fas fa-clock"></i> Belum Ditandatangani</span>
		<?php else: ?>
			<span class="vd-badge vd-badge-bad"><i class="fas fa-times-circle"></i> Dokumen Tidak Valid</span>
		<?php endif; ?>
	</div>

	<div class="vd-wrap">
		<div class="vd-card">
			<div class="vd-strip <?= ($surat && $sah) ? 'ok' : 'bad' ?>"></div>

			<?php if ($surat): ?>
				<div class="ident">
					<div class="avatar <?= $sah ? '' : 'bad' ?>"><i class="fas fa-file-lines"></i></div>
					<div>
						<h2><?= html_escape($surat->perihal) ?></h2>
						<div class="sub">Nomor: <?= html_escape($surat->no_surat ?: 'Belum bernomor') ?></div>
						<span class="tag <?= $sah ? 'tag-ok' : 'tag-bad' ?>">
							<?= $sah ? 'TANDA TANGAN SAH' : strtoupper(html_escape($surat->status)) ?>
						</span>
					</div>
				</div>

				<div class="cols">
					<div class="row">
						<div class="k">Nomor Surat</div>
						<div class="v"><?= html_escape($surat->no_surat ?: '—') ?></div>
					</div>
					<div class="row">
						<div class="k">Perihal</div>
						<div class="v"><?= html_escape($surat->perihal) ?></div>
					</div>
					<div class="row">
						<div class="k">Tujuan</div>
						<div class="v"><?= html_escape($surat->tujuan) ?></div>
					</div>
					<div class="row">
						<div class="k">Jenis Surat</div>
						<div class="v"><?= $surat->jenis === 'eksternal' ? 'Eksternal' : 'Internal' ?></div>
					</div>
					<div class="row">
						<div class="k">Tanggal Pengajuan</div>
						<div class="v"><?= $surat->tanggal_pengajuan ? date('d/m/Y', strtotime($surat->tanggal_pengajuan)) : '—' ?></div>
					</div>
					<div class="row">
						<div class="k">Ditandatangani Oleh</div>
						<div class="v"><?= $penandatangan ? html_escape($penandatangan) : '—' ?></div>
					</div>
					<div class="row">
						<div class="k">Tanggal Tanda Tangan</div>
						<div class="v"><?= $tanggal_ttd ? date('d/m/Y H:i', strtotime($tanggal_ttd)) : '—' ?></div>
					</div>
					<div class="row">
						<div class="k">Status Dokumen</div>
						<div class="v"><?= html_escape($surat->status) ?></div>
					</div>
				</div>
			<?php else: ?>
				<div class="msg">
					<div class="ico"><i class="fas fa-file-circle-xmark"></i></div>
					<h3>Dokumen tidak valid</h3>
					<p>Kode verifikasi tidak cocok dengan data surat, atau dokumen ini tidak/belum ditandatangani melalui sistem E-OFFICE RSA. Pastikan QR dipindai dari dokumen asli.</p>
				</div>
			<?php endif; ?>
		</div>

		<div class="vd-foot">
			Halaman ini diakses tanpa login melalui <b>token</b> acak pada QR dokumen.<br>
			Dipindai pada <?= html_escape($tgl) ?> WIB.
		</div>
	</div>

</body>
</html>
