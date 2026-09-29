<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex, nofollow">
	<title>Survei Kepuasan Pasien — RS Airlangga</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

	<style>
		:root {
			--sv-primary: #2563eb;
			--sv-primary-dark: #1d4ed8;
			--sv-gold: #f59e0b;
			--sv-ink: #0f172a;
			--sv-ink2: #334155;
			--sv-muted: #64748b;
			--sv-line: #e2e8f0;
			--sv-danger: #ef4444;
			--sv-success: #10b981;
			--sv-bg: #f8fafc;
		}

		*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

		body {
			font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
			background: var(--sv-bg);
			color: var(--sv-ink);
			line-height: 1.55;
			-webkit-font-smoothing: antialiased;
		}

		/* ================= HEADER ================= */
		.sv-head {
			background: linear-gradient(135deg, #102a43 0%, #1e4e79 60%, #2563eb 100%);
			color: #fff;
			padding: 26px 20px 62px;
			text-align: center;
			position: relative;
			overflow: hidden;
		}
		.sv-head::after {
			content: '';
			position: absolute;
			width: 260px; height: 260px;
			border: 34px solid rgba(255,255,255,.07);
			border-radius: 50%;
			right: -90px; top: -110px;
		}
		.sv-head-inner { position: relative; z-index: 1; max-width: 760px; margin: 0 auto; }
		.sv-logo {
			width: 54px; height: 54px;
			border-radius: 16px;
			background: rgba(255,255,255,.14);
			border: 1px solid rgba(255,255,255,.22);
			display: inline-flex; align-items: center; justify-content: center;
			font-size: 1.4rem; color: #bfdbfe;
			margin-bottom: 12px;
		}
		.sv-eyebrow {
			font-size: .68rem; text-transform: uppercase; letter-spacing: .14em;
			font-weight: 700; color: #93c5fd; margin-bottom: 6px;
		}
		.sv-head h1 { font-size: 1.35rem; font-weight: 800; letter-spacing: -.02em; }
		.sv-head p { font-size: .82rem; color: #dbeafe; margin-top: 6px; }

		/* ================= LAYOUT ================= */
		.sv-wrap { max-width: 760px; margin: -44px auto 0; padding: 0 16px 48px; position: relative; z-index: 2; }

		.sv-card {
			background: #fff;
			border: 1px solid var(--sv-line);
			border-radius: 16px;
			box-shadow: 0 10px 30px -8px rgba(15,23,42,.1);
			padding: 24px 24px 26px;
			margin-bottom: 18px;
		}
		.sv-card-title {
			display: flex; align-items: center; gap: 10px;
			font-size: .95rem; font-weight: 800; color: var(--sv-ink);
			padding-bottom: 14px; margin-bottom: 18px;
			border-bottom: 1px solid var(--sv-line);
		}
		.sv-card-title i {
			width: 30px; height: 30px; border-radius: 9px;
			background: #eff6ff; color: var(--sv-primary);
			display: inline-flex; align-items: center; justify-content: center;
			font-size: .8rem; flex-shrink: 0;
		}
		.sv-step {
			margin-left: auto;
			font-size: .62rem; font-weight: 800; letter-spacing: .1em;
			text-transform: uppercase; color: var(--sv-muted);
			background: #f1f5f9; padding: 4px 10px; border-radius: 20px;
		}

		/* ================= FORM ================= */
		.sv-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
		.sv-field { margin-bottom: 15px; }
		.sv-field label {
			display: block;
			font-size: .76rem; font-weight: 700; color: #475569;
			margin-bottom: 6px;
		}
		.sv-field label .req { color: var(--sv-danger); }
		.sv-field input[type="text"],
		.sv-field input[type="number"],
		.sv-field input[type="date"],
		.sv-field select,
		.sv-field textarea {
			width: 100%;
			border: 1px solid #cbd5e1;
			border-radius: 10px;
			padding: 10px 13px;
			font-size: .85rem;
			font-family: inherit;
			color: var(--sv-ink);
			background: #fff;
			transition: border-color .15s, box-shadow .15s;
		}
		.sv-field textarea { min-height: 96px; resize: vertical; }
		.sv-field input:focus, .sv-field select:focus, .sv-field textarea:focus {
			outline: none;
			border-color: var(--sv-primary);
			box-shadow: 0 0 0 3px rgba(37,99,235,.12);
		}
		.sv-hint { font-size: .7rem; color: var(--sv-muted); margin-top: 5px; }
		.sv-error { display: block; font-size: .7rem; color: var(--sv-danger); font-weight: 600; margin-top: 5px; }

		.sv-check {
			display: flex; align-items: flex-start; gap: 9px;
			background: #f8fafc; border: 1px solid var(--sv-line);
			border-radius: 10px; padding: 12px 14px;
			font-size: .78rem; color: var(--sv-ink2);
		}
		.sv-check input { width: 16px; height: 16px; margin-top: 2px; accent-color: var(--sv-primary); flex-shrink: 0; }
		.sv-check b { color: var(--sv-ink); }

		/* honeypot — disembunyikan dari pengguna */
		.sv-trap { position: absolute; left: -9999px; opacity: 0; height: 0; overflow: hidden; }

		/* ================= PENILAIAN BINTANG ================= */
		.sv-aspek {
			border: 1px solid var(--sv-line);
			border-radius: 12px;
			padding: 14px 15px;
			margin-bottom: 11px;
			background: #fff;
			transition: border-color .15s, background .15s;
		}
		.sv-aspek:hover { border-color: #bfdbfe; background: #fbfdff; }
		.sv-aspek.invalid { border-color: #fecaca; background: #fef2f2; }

		.sv-aspek-head { display: flex; align-items: flex-start; gap: 11px; margin-bottom: 10px; }
		.sv-aspek-icon {
			width: 32px; height: 32px; border-radius: 9px;
			background: #eff6ff; color: var(--sv-primary);
			display: inline-flex; align-items: center; justify-content: center;
			font-size: .82rem; flex-shrink: 0;
		}
		.sv-aspek-name { font-size: .86rem; font-weight: 700; color: var(--sv-ink); }
		.sv-aspek-desc { font-size: .72rem; color: var(--sv-muted); margin-top: 2px; }

		.sv-stars { display: flex; align-items: center; gap: 3px; flex-wrap: wrap; }
		.sv-stars input { position: absolute; opacity: 0; width: 0; height: 0; }
		.sv-stars label {
			font-size: 1.55rem;
			color: #cbd5e1;
			cursor: pointer;
			padding: 0 3px;
			line-height: 1;
			transition: color .12s, transform .12s;
			user-select: none;
		}
		.sv-stars label:hover,
		.sv-stars label:hover ~ label { color: #fcd34d; }
		.sv-stars input:checked + label,
		.sv-stars input:checked + label ~ label { color: var(--sv-gold); }
		.sv-stars input:focus-visible + label { outline: 2px solid var(--sv-primary); outline-offset: 2px; border-radius: 4px; }
		.sv-stars label:active { transform: scale(1.18); }

		.sv-keterangan {
			margin-left: auto;
			font-size: .72rem; font-weight: 700;
			color: var(--sv-muted);
			background: #f1f5f9;
			padding: 4px 11px; border-radius: 20px;
			min-width: 104px; text-align: center;
		}

		/* ================= REKOMENDASI ================= */
		.sv-nps { display: flex; gap: 6px; flex-wrap: wrap; }
		.sv-nps input { position: absolute; opacity: 0; width: 0; height: 0; }
		.sv-nps label {
			min-width: 38px; padding: 9px 0;
			border: 1px solid #cbd5e1; border-radius: 9px;
			text-align: center; font-size: .82rem; font-weight: 700;
			color: var(--sv-ink2); cursor: pointer;
			transition: all .12s;
		}
		.sv-nps label:hover { border-color: var(--sv-primary); background: #eff6ff; }
		.sv-nps input:checked + label {
			background: var(--sv-primary); border-color: var(--sv-primary); color: #fff;
		}
		.sv-nps-scale { display: flex; justify-content: space-between; font-size: .68rem; color: var(--sv-muted); margin-top: 6px; }

		/* ================= BUTTON ================= */
		.sv-submit {
			width: 100%;
			background: linear-gradient(135deg, #2563eb, #1d4ed8);
			color: #fff; border: none; border-radius: 12px;
			padding: 14px 20px;
			font-size: .92rem; font-weight: 800; font-family: inherit;
			cursor: pointer;
			box-shadow: 0 8px 20px -6px rgba(37,99,235,.5);
			transition: transform .15s, box-shadow .15s;
		}
		.sv-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 26px -6px rgba(37,99,235,.6); }
		.sv-submit:disabled { opacity: .6; cursor: not-allowed; transform: none; }

	.sv-foot { text-align: center; font-size: .72rem; color: var(--sv-muted); margin-top: 22px; }
	.sv-alert { padding: 12px 15px; border-radius: 10px; font-size: .8rem; font-weight: 600; margin-bottom: 16px; }
	.sv-alert-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
	.sv-alert-warning { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
	.sv-alert-success { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
	.sv-alert .sv-close { float: right; cursor: pointer; font-weight: 800; opacity: .6; }
	.sv-alert .sv-close:hover { opacity: 1; }

		.sv-progress {
			position: sticky; top: 0; z-index: 5;
			background: #fff; border: 1px solid var(--sv-line);
			border-radius: 12px; padding: 11px 15px; margin-bottom: 18px;
			box-shadow: 0 2px 10px rgba(15,23,42,.05);
		}
		.sv-progress-top { display: flex; justify-content: space-between; font-size: .72rem; font-weight: 700; color: var(--sv-ink2); margin-bottom: 7px; }
		.sv-progress-top span:last-child { color: var(--sv-muted); }
		.sv-progress-bar { height: 7px; background: #e2e8f0; border-radius: 20px; overflow: hidden; }
		.sv-progress-fill { height: 100%; width: 0; background: linear-gradient(90deg, #f59e0b, #f97316); border-radius: 20px; transition: width .25s ease; }

		@media (max-width: 576px) {
			.sv-row { grid-template-columns: 1fr; gap: 0; }
			.sv-head { padding: 22px 16px 54px; }
			.sv-head h1 { font-size: 1.1rem; }
			.sv-card { padding: 18px 16px 20px; }
			.sv-stars label { font-size: 1.35rem; padding: 0 2px; }
			.sv-keterangan { min-width: 88px; font-size: .66rem; }
			.sv-nps label { min-width: 100%; }
		}
	</style>
</head>
<body>

<div class="sv-head">
	<div class="sv-head-inner">
		<div class="sv-logo"><i class="fas fa-heart-pulse"></i></div>
		<div class="sv-eyebrow">RS Airlangga — Jombang</div>
		<h1>Survei Kepuasan Pasien</h1>
		<p>Bantu kami meningkatkan kualitas pelayanan. Pengisian hanya membutuhkan waktu 2 menit.</p>
	</div>
</div>

<div class="sv-wrap">

	<?php
$sv_pesan = $this->session->flashdata('message');
$kelas_alert = (stripos((string) $sv_pesan, 'alert-success') !== FALSE) ? 'sv-alert-success'
	: ((stripos((string) $sv_pesan, 'alert-danger') !== FALSE) ? 'sv-alert-danger' : 'sv-alert-warning');
?>
<?php if ($sv_pesan): ?>
	<div class="sv-alert <?= $kelas_alert; ?>"><?= $sv_pesan; ?></div>
<?php endif; ?>

	<form method="POST" action="<?= site_url('survei/kirim'); ?>" id="svForm">

		<!-- honeypot -->
		<div class="sv-trap" aria-hidden="true">
			<label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
		</div>

		<!-- ============ IDENTITAS ============ -->
		<div class="sv-card">
			<div class="sv-card-title">
				<i class="fas fa-id-card"></i> Identitas Kunjungan
				<span class="sv-step">Langkah 1</span>
			</div>

			<div class="sv-row">
				<div class="sv-field">
					<label for="nama">Nama Pasien <span class="req" style="color:var(--sv-muted);font-weight:500">(opsional)</span></label>
					<input type="text" id="nama" name="nama" maxlength="150"
						   value="<?= set_value('nama'); ?>" placeholder="Nama lengkap (boleh dikosongkan)">
				</div>
				<div class="sv-field">
					<label for="no_rm">No. Rekam Medis</label>
					<input type="text" id="no_rm" name="no_rm" maxlength="50"
						   value="<?= set_value('no_rm'); ?>" placeholder="Nomor rekam medis">
				</div>
			</div>

			<div class="sv-row">
				<div class="sv-field">
					<label for="nik">NIK</label>
					<input type="text" id="nik" name="nik" maxlength="30" inputmode="numeric"
						   value="<?= set_value('nik'); ?>" placeholder="16 digit NIK">
				</div>
				<div class="sv-field">
					<label for="umur">Umur</label>
					<input type="number" id="umur" name="umur" min="1" max="120"
						   value="<?= set_value('umur'); ?>" placeholder="Tahun">
				</div>
			</div>

			<div class="sv-row">
				<div class="sv-field">
					<label for="jenis_kelamin">Jenis Kelamin</label>
					<select id="jenis_kelamin" name="jenis_kelamin">
						<option value="">-- Tidak menyebutkan --</option>
						<option value="L" <?= set_select('jenis_kelamin', 'L'); ?>>Laki-laki</option>
						<option value="P" <?= set_select('jenis_kelamin', 'P'); ?>>Perempuan</option>
					</select>
				</div>
				<div class="sv-field">
					<label for="id_unit">Unit / Layanan yang Dikunjungi</label>
					<select id="id_unit" name="id_unit">
						<option value="">-- Pilih unit --</option>
						<?php foreach ($unit as $u): ?>
							<option value="<?= (int) $u->id_unit; ?>" <?= set_select('id_unit', (string) $u->id_unit); ?>>
								<?= html_escape($u->nm_unit); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="sv-field">
				<label for="tanggal_kunjungan">Tanggal Kunjungan / Perawatan <span class="req">*</span></label>
				<input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" required
					   max="<?= date('Y-m-d'); ?>"
					   value="<?= set_value('tanggal_kunjungan', date('Y-m-d')); ?>">
				<?= form_error('tanggal_kunjungan'); ?>
			</div>

			<div class="sv-check">
				<input type="checkbox" id="is_anonim" name="is_anonim" value="1" <?= set_checkbox('is_anonim', '1'); ?>>
				<label for="is_anonim" style="margin:0">
					<b>Kirim secara anonim.</b> Nama, NIK, dan nomor rekam medis tidak akan disimpan
					ke dalam sistem.
				</label>
			</div>
		</div>

		<!-- ============ PENILAIAN BINTANG ============ -->
		<div class="sv-card">
			<div class="sv-card-title">
				<i class="fas fa-star"></i> Penilaian Layanan
				<span class="sv-step">Langkah 2</span>
			</div>

			<div class="sv-progress">
				<div class="sv-progress-top">
					<span>Progres pengisian</span>
					<span id="svCounter">0 / <?= count($aspek); ?> aspek</span>
				</div>
				<div class="sv-progress-bar"><div class="sv-progress-fill" id="svFill"></div></div>
			</div>

			<p style="font-size:.76rem;color:var(--sv-muted);margin-bottom:14px">
				Berikan <b>1 bintang</b> (Sangat Buruk) sampai <b>5 bintang</b> (Sangat Baik) untuk setiap aspek.
			</p>

			<?php foreach ($aspek as $a): ?>
				<div class="sv-aspek" data-aspek="<?= (int) $a->id; ?>">
					<div class="sv-aspek-head">
						<span class="sv-aspek-icon"><i class="<?= html_escape($a->icon); ?>"></i></span>
						<div style="flex:1">
							<div class="sv-aspek-name"><?= html_escape($a->nama_aspek); ?></div>
							<?php if (!empty($a->deskripsi)): ?>
								<div class="sv-aspek-desc"><?= html_escape($a->deskripsi); ?></div>
							<?php endif; ?>
						</div>
						<span class="sv-keterangan" data-keterangan>Belum dinilai</span>
					</div>
					<div class="sv-stars">
						<?php for ($s = 5; $s >= 1; $s--): ?>
							<input type="radio" id="s<?= (int) $a->id; ?>_<?= $s; ?>"
								   name="skor[<?= (int) $a->id; ?>]" value="<?= $s; ?>"
								   data-label="<?= html_escape($label[$s]); ?>">
							<label for="s<?= (int) $a->id; ?>_<?= $s; ?>" title="<?= html_escape($label[$s]); ?>">
								<i class="fas fa-star"></i>
							</label>
						<?php endfor; ?>
					</div>
					<?= form_error('skor[' . $a->id . ']'); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- ============ REKOMENDASI & SARAN ============ -->
		<div class="sv-card">
			<div class="sv-card-title">
				<i class="fas fa-comments"></i> Rekomendasi &amp; Saran
				<span class="sv-step">Langkah 3</span>
			</div>

			<div class="sv-field">
				<label>Seberapa besar kemungkinan Anda merekomendasikan RS Airlangga kepada kerabat atau teman?</label>
				<div class="sv-nps">
					<?php for ($n = 0; $n <= 10; $n++): ?>
						<input type="radio" id="nps<?= $n; ?>" name="rekomendasi" value="<?= $n; ?>">
						<label for="nps<?= $n; ?>"><?= $n; ?></label>
					<?php endfor; ?>
				</div>
				<div class="sv-nps-scale"><span>0 = Tidak mungkin</span><span>10 = Sangat mungkin</span></div>
				<?= form_error('rekomendasi'); ?>
			</div>

			<div class="sv-field">
				<label for="saran">Saran, Kritik, atau Keluhan Anda</label>
				<textarea id="saran" name="saran" maxlength="1000"
						  placeholder="Tuliskan pengalaman Anda — saran ini akan kami tindaklanjutkan."><?= set_value('saran'); ?></textarea>
				<div class="sv-hint">Maksimal 1000 karakter. <?= strlen((string) set_value('saran')) ?> karakter terpakai.</div>
			</div>

			<button type="submit" class="sv-submit" id="svSubmit">
				<i class="fas fa-paper-plane"></i> Kirim Survei
			</button>
		</div>

	</form>

	<div class="sv-foot">
		<p><i class="fas fa-lock"></i> Data Anda dipakai hanya untuk peningkatan pelayanan RS Airlangga.</p>
		<p style="margin-top:6px">&copy; <?= date('Y'); ?> RS Airlangga &mdash; Survei Kepuasan Pasien</p>
	</div>

</div>

<script>
(function () {
	var labels = <?= json_encode(array_values(Survei_model::$label_skor), JSON_UNESCAPED_UNICODE); ?>;
	var inputs = document.querySelectorAll('.sv-stars input[type="radio"]');
	var total  = document.querySelectorAll('.sv-aspek').length;
	var fill   = document.getElementById('svFill');
	var counter= document.getElementById('svCounter');
	var form   = document.getElementById('svForm');
	var submit = document.getElementById('svSubmit');

	function refresh() {
		var done = 0;
		document.querySelectorAll('.sv-aspek').forEach(function (wrap) {
			var checked = wrap.querySelector('input[type="radio"]:checked');
			var ket = wrap.querySelector('[data-keterangan]');
			if (checked) {
				done++;
				ket.textContent = labels[parseInt(checked.value, 10) - 1];
				wrap.classList.remove('invalid');
			} else {
				ket.textContent = 'Belum dinilai';
			}
		});
		counter.textContent = done + ' / ' + total + ' aspek';
		fill.style.width = (total ? (done / total * 100) : 0) + '%';
	}

	inputs.forEach(function (i) { i.addEventListener('change', refresh); });
	refresh();

	form.addEventListener('submit', function (e) {
		var totalAspek = document.querySelectorAll('.sv-aspek').length;
		var terisi = document.querySelectorAll('.sv-aspek input:checked').length;
		if (terisi < totalAspek) {
			e.preventDefault();
			document.querySelectorAll('.sv-aspek').forEach(function (w) {
				w.classList.toggle('invalid', !w.querySelector('input:checked'));
			});
			var firstKosong = document.querySelector('.sv-aspek.invalid');
			if (firstKosong) {
				window.scrollTo({ top: firstKosong.offsetTop - 80, behavior: 'smooth' });
			}
			alert('Mohon beri bintang untuk semua aspek layanan sebelum mengirim.');
			return;
		}
		submit.disabled = true;
		submit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
	});
})();
</script>

</body>
</html>
