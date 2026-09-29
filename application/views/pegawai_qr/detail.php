<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex, nofollow">
	<title>Data Pegawai — RS Airlangga</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

	<style>
		:root {
			--qr-primary: #0f766e;
			--qr-primary-dark: #115e59;
			--qr-ink: #0f172a;
			--qr-ink2: #334155;
			--qr-muted: #64748b;
			--qr-line: #e2e8f0;
			--qr-bg: #f1f5f9;
		}

		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

		body {
			font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
			background: var(--qr-bg);
			color: var(--qr-ink);
			line-height: 1.55;
			-webkit-font-smoothing: antialiased;
			padding-bottom: 40px;
		}

		/* ================= HEADER ================= */
		.qr-head {
			background: linear-gradient(135deg, #0b2b2b 0%, #0f766e 55%, #14b8a6 100%);
			color: #fff;
			padding: 24px 18px 54px;
			text-align: center;
		}
		.qr-head .logo {
			width: 54px; height: 54px; margin: 0 auto 12px;
			border-radius: 16px;
			background: rgba(255,255,255,.16);
			display: flex; align-items: center; justify-content: center;
			font-size: 26px;
		}
		.qr-head h1 { font-size: 20px; font-weight: 800; letter-spacing: .2px; }
		.qr-head p { font-size: 13px; opacity: .85; margin-top: 2px; }
		.qr-ok-badge {
			display: inline-flex; align-items: center; gap: 6px;
			margin-top: 12px; padding: 6px 14px;
			background: rgba(255,255,255,.18);
			border: 1px solid rgba(255,255,255,.35);
			border-radius: 999px; font-size: 12.5px; font-weight: 700;
		}
		.qr-bad-badge { background: rgba(220,38,38,.25); border-color: rgba(255,255,255,.5); }

		/* ================= WRAPPER ================= */
		.qr-wrap { max-width: 720px; margin: -34px auto 0; padding: 0 16px; }

		.qr-card {
			background: #fff;
			border-radius: 16px;
			box-shadow: 0 18px 40px -20px rgba(15,23,42,.35);
			overflow: hidden;
		}

		/* ================= IDENTITAS ================= */
		.ident {
			display: flex; gap: 16px; align-items: center;
			padding: 22px 22px 18px;
			border-bottom: 1px solid var(--qr-line);
		}
		.avatar {
			width: 68px; height: 68px; flex: 0 0 68px;
			border-radius: 18px;
			background: linear-gradient(135deg, #0f766e, #14b8a6);
			color: #fff;
			display: flex; align-items: center; justify-content: center;
			font-size: 28px; font-weight: 800;
		}
		.avatar.off { background: linear-gradient(135deg, #94a3b8, #cbd5e1); }
		.ident h2 { font-size: 19px; font-weight: 800; line-height: 1.25; }
		.ident .sub { font-size: 13px; color: var(--qr-muted); margin-top: 2px; }

		.tag {
			display: inline-block; margin-top: 8px;
			padding: 3px 10px; border-radius: 999px;
			font-size: 11.5px; font-weight: 700; letter-spacing: .3px;
		}
		.tag-aktif { background: #dcfce7; color: #15803d; }
		.tag-nonaktif { background: #fee2e2; color: #b91c1c; }

		/* ================= KOLOM ================= */
		.cols { padding: 6px 22px 10px; }
		.row {
			display: flex; gap: 12px;
			padding: 10px 0;
			border-bottom: 1px dashed var(--qr-line);
			font-size: 14px;
		}
		.row:last-child { border-bottom: 0; }
		.row .k { flex: 0 0 38%; color: var(--qr-muted); }
		.row .v { flex: 1; font-weight: 600; word-break: break-word; }
		.row .v.mono { letter-spacing: .6px; }
		.row .v small { display: block; font-weight: 400; color: var(--qr-muted); font-size: 12.5px; }

		/* ================= RIWAYAT ================= */
		.blk { padding: 16px 22px 20px; border-top: 6px solid var(--qr-bg); }
		.blk h3 {
			font-size: 12px; font-weight: 800; letter-spacing: .8px;
			text-transform: uppercase; color: var(--qr-muted); margin-bottom: 10px;
		}
		.hist { list-style: none; }
		.hist li {
			position: relative; padding: 0 0 14px 18px;
			border-left: 2px solid var(--qr-line);
			font-size: 13px;
		}
		.hist li:last-child { border-left-color: transparent; padding-bottom: 0; }
		.hist li::before {
			content: ''; position: absolute; left: -6px; top: 4px;
			width: 10px; height: 10px; border-radius: 50%;
			background: var(--qr-primary); border: 2px solid #fff;
			box-shadow: 0 0 0 1px var(--qr-primary);
		}
		.hist .tgl { color: var(--qr-muted); font-size: 12px; }
		.hist .ket { color: var(--qr-ink2); }

		/* ================= KOSONG / ERROR ================= */
		.msg { padding: 46px 26px; text-align: center; }
		.msg .ico { font-size: 46px; color: #cbd5e1; margin-bottom: 12px; }
		.msg h3 { font-size: 17px; font-weight: 700; margin-bottom: 6px; }
		.msg p { font-size: 14px; color: var(--qr-muted); }

		.qr-foot {
			margin-top: 16px; text-align: center;
			font-size: 11.5px; color: var(--qr-muted); line-height: 1.7;
		}
		.qr-foot code { background: #e2e8f0; padding: 1px 5px; border-radius: 4px; }

		@media print {
			body { background: #fff; }
			.qr-head { display: none; }
			.qr-wrap { margin: 0; max-width: none; }
			.qr-card { box-shadow: none; border: 1px solid var(--qr-line); }
			.qr-foot { display: none; }
		}
	</style>
</head>
<body>

	<div class="qr-head">
		<div class="logo"><i class="fas fa-hospital"></i></div>
		<h1>Data Pegawai</h1>
		<p>RS Airlangga &mdash; hasil pemindaian QR Code</p>
		<?php if ($pegawai): ?>
			<span class="qr-ok-badge"><i class="fas fa-check-circle"></i> Data ditemukan</span>
		<?php else: ?>
			<span class="qr-ok-badge qr-bad-badge"><i class="fas fa-times-circle"></i> Data tidak ditemukan</span>
		<?php endif; ?>
	</div>

	<div class="qr-wrap">
		<div class="qr-card">
			<?php if ($pegawai): ?>
				<?php
				$inisial  = mb_strtoupper(mb_substr($pegawai->nama, 0, 1));
				$off      = ($pegawai->status === 'nonaktif');
				?>
				<div class="ident">
					<div class="avatar <?= $off ? 'off' : '' ?>"><?= html_escape($inisial) ?></div>
					<div>
						<h2><?= html_escape($pegawai->nama) ?></h2>
						<div class="sub"><?= html_escape($pegawai->jabatan ?: 'Jabatan belum diisi') ?></div>
						<span class="tag <?= $off ? 'tag-nonaktif' : 'tag-aktif' ?>">
							<?= $off ? 'NONAKTIF' : 'AKTIF' ?>
						</span>
					</div>
				</div>

				<div class="cols">
					<div class="row">
						<div class="k">NIK</div>
						<div class="v mono"><?= html_escape($pegawai->nik) ?></div>
					</div>
					<div class="row">
						<div class="k">NIP / NRK</div>
						<div class="v mono"><?= html_escape($pegawai->nip ?: '—') ?></div>
					</div>
					<div class="row">
						<div class="k">Jenis Kelamin</div>
						<div class="v"><?= html_escape($pegawai->jenis_kelamin) ?></div>
					</div>
					<div class="row">
						<div class="k">Tempat, Tgl Lahir</div>
						<div class="v">
							<?= html_escape($pegawai->tempat_lahir ?: '—') ?><?php if ($pegawai->tanggal_lahir): ?>,
							<?= date('d/m/Y', strtotime($pegawai->tanggal_lahir)) ?><?php endif; ?>
						</div>
					</div>
					<div class="row">
						<div class="k">Unit Kerja</div>
						<div class="v"><?= html_escape($pegawai->unit_kerja ?: '—') ?></div>
					</div>
					<div class="row">
						<div class="k">Status Kepegawaian</div>
						<div class="v"><?= html_escape($pegawai->status_kepegawaian ?: '—') ?></div>
					</div>
					<div class="row">
						<div class="k">Tanggal Masuk</div>
						<div class="v">
							<?= $pegawai->tanggal_masuk ? date('d/m/Y', strtotime($pegawai->tanggal_masuk)) : '—' ?>
						</div>
					</div>
					<div class="row">
						<div class="k">No. HP</div>
						<div class="v"><?= html_escape($pegawai->no_hp ?: '—') ?></div>
					</div>
					<div class="row">
						<div class="k">Email</div>
						<div class="v"><?= html_escape($pegawai->email ?: '—') ?></div>
					</div>
					<div class="row">
						<div class="k">Kualifikasi</div>
						<div class="v">
							<small><?= $pegawai->kualifikasi_pendidikan ? nl2br(html_escape($pegawai->kualifikasi_pendidikan)) : 'Belum diisi' ?></small>
						</div>
					</div>
				</div>

				<div class="blk">
					<h3>Catatan Verifikasi</h3>
					<ul class="hist">
						<li>
							<span class="tgl"><?= $pegawai->tanggal_masuk ? date('d/m/Y', strtotime($pegawai->tanggal_masuk)) : '—' ?></span><br>
							<span class="ket">Bergabung dengan RS Airlangga<?= $pegawai->unit_kerja ? ' &mdash; ' . html_escape($pegawai->unit_kerja) : '' ?>.</span>
						</li>
						<li>
							<span class="tgl"><?= $pegawai->updated_at ? date('d/m/Y', strtotime($pegawai->updated_at)) : '—' ?></span><br>
							<span class="ket">Data terakhir diperbarui pada sistem.</span>
						</li>
						<li>
							<span class="tgl"><?= html_escape($tgl) ?></span><br>
							<span class="ket">QR Code ini dipindai tanpa login &mdash; jangan dibagikan kepada pihak lain.</span>
						</li>					</ul>
				</div>
			<?php else: ?>
				<div class="msg">
					<div class="ico"><i class="fas fa-user-slash"></i></div>
					<h3>Data tidak ditemukan</h3>
					<p><?= html_escape($error) ?></p>
				</div>
			<?php endif; ?>
		</div>

		<div class="qr-foot">
			Informasi ini dapat diakses tanpa login, namun tautannya berupa <b>token</b> acak &mdash;
			bukan NIK, sehingga tidak bisa ditebak atau dibaca dari alamat halaman.<br>
			Kolom lain pada data pegawai (alamat lengkap, NPWP, data keluarga) tidak ditampilkan di sini.
		</div>
	</div>

</body>
</html>
