<div class="container-fluid p-0">

<?php if (!function_exists('plrd_stars')): ?>
<?php function plrd_stars($n)
{
    $n = (int) $n;
    $s = '';
    for ($i = 1; $i <= 5; $i++) {
        $s .= $i <= $n
            ? '<i class="fas fa-star text-warning"></i>'
            : '<i class="far fa-star text-muted"></i>';
    }
    return $s;
} ?>
<?php endif; ?>

<?php if (!function_exists('plrd_status_meta')): ?>
<?php function plrd_status_meta($st)
{
    $st = (string) $st;
    $map = array(
        'menunggu'   => array('Menunggu Validasi', 'Laporan belum diputuskan oleh Admin / HRD', '#fef3c7', '#b45309', '#fbbf24', 'fas fa-hourglass-half'),
        'divalidasi' => array('Divalidasi', 'Laporan dinyatakan valid oleh Admin / HRD', '#d1fae5', '#065f46', '#34d399', 'fas fa-check-circle'),
        'ditolak'    => array('Ditolak', 'Laporan ditolak oleh Admin / HRD', '#fee2e2', '#991b1b', '#f87171', 'fas fa-times-circle'),
    );
    return isset($map[$st]) ? $map[$st] : $map['menunggu'];
} ?>
<?php endif; ?>

<style>
	.plrd-page { font-family: 'Inter', system-ui, sans-serif; max-width: 860px; }
	.plrd-page * { box-sizing: border-box; }

	.plrd-toolbar { display:flex; align-items:center; gap:12px; flex-wrap:wrap; padding:0 2px 18px; }
	.plrd-title { font-size:1.15rem; font-weight:800; color:#0f172a; margin:0; }
	.plrd-btn { display:inline-flex; align-items:center; gap:7px; padding:9px 16px; border-radius:9px; font-size:.82rem; font-weight:700; border:1px solid transparent; text-decoration:none!important; transition:all .15s; }
	.plrd-btn-soft { background:#fff; color:#334155!important; border-color:#e2e8f0; }
	.plrd-btn-soft:hover { background:#f8fafc; }
	.plrd-btn-danger { background:#fef2f2; border-color:rgba(239,68,68,.25); color:#b91c1c; }
	.plrd-btn-danger:hover { background:#fee2e2; }
	.plrd-btn-success { background:#ecfdf5; border-color:rgba(16,185,129,.3); color:#065f46; }
	.plrd-btn-success:hover { background:#d1fae5; }
	.plrd-btn-primary { background:linear-gradient(135deg,#4f46e5,#4338ca); color:#fff!important; border-color:#4338ca; box-shadow:0 4px 12px -3px rgba(79,70,229,.4); }
	.plrd-btn-primary:hover { background:linear-gradient(135deg,#4338ca,#3730a3); }

	.plrd-card { background:#fff; border:1px solid #edf1f7; border-radius:14px; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 8px 24px -12px rgba(15,23,42,.08); margin-top:16px; overflow:hidden; }
	.plrd-card:first-of-type { margin-top:0; }
	.plrd-body { padding:22px 26px; }
	.plrd-card-title { font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#8b9cb4; padding:16px 26px 0; }
	.plrd-empty { color:#94a3b8; font-style:italic; font-size:.85rem; }

	.plrd-status { display:flex; align-items:center; gap:14px; padding:20px 26px; border-bottom:1px solid rgba(15,23,42,.05); }
	.plrd-status-ic { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; flex-shrink:0; }
	.plrd-status-title { font-size:1.02rem; font-weight:800; color:#0f172a; }
	.plrd-status-sub { font-size:.8rem; margin-top:2px; }
	.plrd-info-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px; }
	.plrd-info-item { background:#f8fafc; border:1px solid #edf1f7; border-radius:10px; padding:11px 14px; }
	.plrd-info-item label { display:block; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; margin-bottom:3px; }
	.plrd-info-item span { font-size:.85rem; font-weight:600; color:#0f172a; }
	.plrd-alasan-box { background:#fafbfc; border:1px solid #edf1f7; border-radius:10px; padding:14px 16px; }
	.plrd-alasan-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#7c8aa0; margin-bottom:7px; }
	.plrd-alasan-text { font-size:.9rem; line-height:1.65; color:#334155; white-space:pre-wrap; }

	.plrd-target { display:flex; align-items:center; gap:14px; background:#f8fafc; border:1px solid #edf1f7; border-radius:12px; padding:16px 18px; margin-bottom:18px; }
	.plrd-avatar { width:50px; height:50px; border-radius:50%; background:linear-gradient(135deg,#c7d2fe,#a5b4fc); color:#312e81; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.25rem; flex-shrink:0; }
	.plrd-emp { font-weight:800; color:#0f172a; font-size:1rem; }
	.plrd-sub { font-size:.8rem; color:#64748b; }
	.plrd-stars { font-size:1.3rem; white-space:nowrap; }

	.plrd-decision-form label { display:block; font-size:.8rem; font-weight:600; color:#334155; margin-bottom:6px; }
	.plrd-decision-form .form-control { border-color:#e2e8f0; border-radius:8px; padding:10px 12px; font-size:.875rem; }
	.plrd-note { font-size:.75rem; color:#64748b; margin-top:8px; }

	.plrd-sanggah-box { background:#fffbeb; border:1px solid #fde68a; border-radius:12px; padding:16px 18px; }
	.plrd-sanggah-form label { display:block; font-size:.8rem; font-weight:600; color:#334155; margin-bottom:6px; }
	.plrd-sanggah-form .form-control { border-color:#e2e8f0; border-radius:8px; padding:10px 12px; font-size:.875rem; }
	.plrd-note-lock { font-size:.75rem; color:#64748b; margin-top:8px; }

	@media (max-width:768px){
		.plrd-body { padding:18px; }
		.plrd-card-title { padding:14px 18px 0; }
		.plrd-status { padding:16px 18px; }
		.plrd-info-grid { grid-template-columns:1fr; }
	}
</style>

	<div class="plrd-page">

		<?php $flash = $this->session->flashdata('message'); ?>
		<?php if ($flash): ?><div style="margin-bottom:14px; padding:11px 16px; border-radius:10px; font-size:.85rem; font-weight:600; background:#ecfdf5; border:1px solid #a7f3d0; color:#064e3b;"><?= $flash ?></div><?php endif; ?>

		<div class="plrd-toolbar">
			<a href="<?= site_url('pelaporan') ?>" class="plrd-btn plrd-btn-soft"><i class="fas fa-arrow-left"></i> Kembali</a>
			<div class="plrd-title"><i class="fas fa-file-alt mr-2" style="color:#4f46e5;"></i> Detail Laporan</div>
			<?php if ($is_pelapor || $is_hrd): ?>
				<a href="<?= site_url('pelaporan/delete/' . $row->id_laporan) ?>" class="plrd-btn plrd-btn-danger ml-auto" onclick="return confirm('Hapus laporan ini?');"><i class="fas fa-trash"></i> Hapus</a>
			<?php endif; ?>
		</div>

		<?php $sm = plrd_status_meta($row->status); ?>

		<!-- Status Validasi (paling atas) -->
		<div class="plrd-card">
			<div class="plrd-status" style="background:<?= $sm[2] ?>;">
				<div class="plrd-status-ic" style="background:#fff; color:<?= $sm[3] ?>;"><i class="<?= $sm[5] ?>"></i></div>
				<div class="plrd-status-txt">
					<div class="plrd-status-title" style="color:<?= $sm[3] ?>;"><?= $sm[0] ?></div>
					<div class="plrd-status-sub" style="color:<?= $sm[3] ?>; opacity:.75;"><?= $sm[1] ?></div>
				</div>
				<div class="ml-auto plrd-stars" style="font-size:1.05rem;"><?= date('d M Y', strtotime($row->created_at)) ?></div>
			</div>
			<div class="plrd-body">
				<?php if (!empty($row->validasi_at)): ?>
					<div class="plrd-info-grid">
						<div class="plrd-info-item">
							<label>Diputuskan Oleh</label>
							<span><?= html_escape($row->validasi_oleh ?: '-') ?></span>
						</div>
						<div class="plrd-info-item">
							<label>Waktu</label>
							<span><?= date('d M Y H:i', strtotime($row->validasi_at)) ?></span>
						</div>
					</div>
					<div class="plrd-alasan-box">
						<div class="plrd-alasan-label"><i class="fas fa-stamp mr-1"></i> Alasan<?= $row->status === 'ditolak' ? ' Penolakan' : ' Validasi' ?></div>
						<div class="plrd-alasan-text"><?= html_escape($row->validasi_alasan ?: '-') ?></div>
					</div>
				<?php else: ?>
					<div class="plrd-empty" style="margin-bottom:12px;"><i class="fas fa-hourglass-half mr-1"></i> Laporan ini masih menunggu keputusan Admin / HRD.</div>
				<?php endif; ?>

				<?php if ($can_validasi): ?>
				<form method="POST" action="<?= site_url('pelaporan/validasi_action') ?>" class="plrd-decision-form" style="margin-top:18px; padding-top:18px; border-top:1px solid #edf1f7;">
					<input type="hidden" name="id_laporan" value="<?= (int) $row->id_laporan ?>">
					<label>Alasan Validasi / Penolakan <span class="text-danger">*</span></label>
					<textarea name="alasan_validasi" id="alasan_validasi" class="form-control" rows="3" placeholder="Tuliskan alasan keputusan Anda..."><?= html_escape($row->validasi_alasan ?: '') ?></textarea>
					<div class="d-flex flex-wrap align-items-center" style="gap:10px; margin-top:12px;">
						<button type="submit" name="keputusan" value="divalidasi" class="plrd-btn plrd-btn-success" onclick="return validasiCheck('divalidasi');"><i class="fas fa-check mr-1"></i> Validasi Laporan</button>
						<button type="submit" name="keputusan" value="ditolak" class="plrd-btn plrd-btn-danger" onclick="return validasiCheck('ditolak');"><i class="fas fa-times mr-1"></i> Tolak Laporan</button>
					</div>
					<div class="plrd-note"><i class="fas fa-info-circle mr-1"></i> Keputusan beserta alasan akan terlihat oleh pemberi nilai dan karyawan yang dinilai.</div>
				</form>
				<?php endif; ?>
			</div>
		</div>

		<!-- Detail Laporan -->
		<div class="plrd-card">
			<div class="plrd-card-title"><i class="fas fa-file-alt mr-1"></i> Detail Laporan</div>
			<div class="plrd-body">

				<div class="plrd-target">
					<div class="plrd-avatar"><?= html_escape(mb_substr($row->nama_terlapor ?: '?', 0, 1)) ?></div>
					<div>
						<div class="plrd-emp"><?= html_escape($row->nama_terlapor ?: '—') ?></div>
						<div class="plrd-sub"><?= html_escape($row->jabatan_terlapor ?: '—') ?> • <?= html_escape($row->unit_terlapor ?: '—') ?></div>
					</div>
					<div class="ml-auto plrd-stars"><?= plrd_stars($row->bintang) ?></div>
				</div>

				<div class="plrd-alasan-box" style="margin-bottom:16px;">
					<div class="plrd-alasan-label"><i class="fas fa-comment-dots mr-1"></i> Alasan / Pengaduan</div>
					<div class="plrd-alasan-text"><?= html_escape($row->alasan ?: '—') ?></div>
				</div>

				<div class="plrd-info-grid">
					<div class="plrd-info-item">
						<label>Nilai Bintang</label>
						<span><?= (int) $row->bintang ?> / 5</span>
					</div>
					<div class="plrd-info-item">
						<label>Waktu Pelaporan</label>
						<span><?= date('d M Y H:i', strtotime($row->created_at)) ?><?= !empty($row->jam) ? ' (jam ' . date('H:i', strtotime($row->jam)) . ')' : '' ?></span>
					</div>
					<div class="plrd-info-item" style="grid-column:1/-1;">
						<label>Dilaporkan Oleh</label>
						<?php if ($show_pelapor_identitas): ?>
						<span><?= html_escape($row->nama_pelapor ?: '—') ?><?= !empty($row->identitas_pelapor) ? '<br><small style="font-weight:400;color:#94a3b8;">' . html_escape($row->identitas_pelapor) . '</small>' : '' ?></span>
						<?php else: ?>
						<span><i class="fas fa-user-shield mr-1" style="color:#64748b;"></i> Identitas pelapor disembunyikan (rahasia)</span>
						<?php endif; ?>
					</div>
				</div>

			</div>
		</div>

		<!-- Sanggahan -->
		<div class="plrd-card">
			<div class="plrd-card-title"><i class="fas fa-reply mr-1"></i> Sanggahan Terlapor</div>
			<div class="plrd-body">
				<?php if (!empty($row->sanggahan)): ?>
				<div class="plrd-sanggah-box">
					<div class="plrd-alasan-label"><i class="fas fa-reply mr-1"></i> Sanggahan</div>
					<div class="plrd-alasan-text"><?= html_escape($row->sanggahan) ?></div>
					<div class="plrd-sub" style="margin-top:8px;">
						Oleh: <?= html_escape($row->sanggahan_oleh ?: 'Terlapor') ?> &mdash; <?= !empty($row->sanggahan_at) ? date('d M Y H:i', strtotime($row->sanggahan_at)) : '-' ?>
					</div>
				</div>

				<?php if ($is_terlapor): ?>
				<div class="plrd-note-lock" style="margin-top:10px;"><i class="fas fa-lock mr-1"></i> Sanggahan Anda telah terkirim dan hanya dapat diberikan <strong>satu kali</strong>, sehingga tidak dapat diubah / dikirim ulang.</div>
				<?php endif; ?>

				<?php elseif ($can_sanggah): ?>
				<div class="plrd-empty"><i class="fas fa-reply mr-1"></i> Terlapor belum memberikan sanggahan.</div>
				<?php endif; ?>

				<?php if ($can_sanggah): ?>
				<form method="POST" action="<?= site_url('pelaporan/sanggah_action') ?>" class="plrd-sanggah-form" style="<?= empty($row->sanggahan) ? '' : 'margin-top:16px; padding-top:16px; border-top:1px solid #edf1f7;' ?>">
					<input type="hidden" name="id_laporan" value="<?= (int) $row->id_laporan ?>">
					<label><?= $is_hrd && !$is_terlapor ? 'Sanggahan (atas nama terlapor)' : 'Tulis sanggahan Anda' ?></label>
					<textarea name="sanggahan" class="form-control" rows="4" placeholder="Sampaikan tanggapan / sanggahan Anda terkait laporan ini..."><?= html_escape($row->sanggahan ?: '') ?></textarea>
					<div class="d-flex align-items-center" style="gap:10px; margin-top:12px;">
						<button type="submit" class="plrd-btn plrd-btn-primary"><i class="fas fa-paper-plane mr-1"></i> Simpan Sanggahan</button>
					</div>
					<?php if ($is_terlapor): ?>
					<div class="plrd-note-lock"><i class="fas fa-shield-alt mr-1"></i> Identitas pelapor tidak akan pernah ditampilkan kepada Anda. Sanggahan hanya dapat dikirim satu kali.</div>
					<?php else: ?>
					<div class="plrd-note-lock"><i class="fas fa-info-circle mr-1"></i> Diisi oleh HRD/Admin atas nama terlapor.</div>
					<?php endif; ?>
				</form>
				<?php endif; ?>
			</div>
		</div>

	</div>

</div>

<script>
function validasiCheck(keputusan) {
	var alasan = document.getElementById('alasan_validasi').value.trim();
	if (alasan === '') {
		alert('Alasan wajib diisi sebelum ' + (keputusan === 'divalidasi' ? 'memvalidasi' : 'menolak') + ' laporan.');
		document.getElementById('alasan_validasi').focus();
		return false;
	}
	return confirm('Anda yakin untuk ' + (keputusan === 'divalidasi' ? 'MEMVALIDASI' : 'MENOLAK') + ' laporan ini?');
}
</script>