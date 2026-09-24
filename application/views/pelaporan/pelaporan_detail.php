<div class="container-fluid p-0 gjk-page">

<?php if (!function_exists('gjk_stars')): ?>
<?php function gjk_stars($n)
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

<?php if (!function_exists('gjk_status')): ?>
<?php function gjk_status($st)
{
    $st = (string) $st;
    $map = array(
        'menunggu'   => array('Menunggu Validasi', 'Laporan menunggu keputusan Admin / HRD', '#f59e0b', '#fff7ed', 'fas fa-hourglass-half'),
        'divalidasi' => array('Divalidasi', 'Laporan dinyatakan valid oleh Admin / HRD', '#00aa13', '#f0fdf4', 'fas fa-check-circle'),
        'ditolak'    => array('Ditolak', 'Laporan ditolak oleh Admin / HRD', '#dc2626', '#fef2f2', 'fas fa-times-circle'),
    );
    $m = isset($map[$st]) ? $map[$st] : $map['menunggu'];
    return '<span class="gjk-pill" style="color:' . $m[2] . '; background:' . $m[3] . '; border:1px solid ' . $m[2] . '33;"><i class="' . $m[4] . ' mr-1"></i>' . $m[0] . '</span>';
} ?>
<?php endif; ?>

<style>
	.gjk-page { font-family: 'Inter', system-ui, sans-serif; max-width: 760px; }
	.gjk-page * { box-sizing: border-box; }

	.gjk-toolbar { display:flex; align-items:center; gap:12px; flex-wrap:wrap; padding:0 0 16px; }
	.gjk-btn { display:inline-flex; align-items:center; gap:7px; padding:9px 15px; border-radius:999px; font-size:.82rem; font-weight:700; border:1px solid transparent; text-decoration:none!important; transition:all .15s; }
	.gjk-btn-soft { background:#fff; color:#334155!important; border-color:#e2e8f0; }
	.gjk-btn-soft:hover { background:#f8fafc; }
	.gjk-btn-danger { background:#fef2f2; border-color:rgba(239,68,68,.25); color:#b91c1c; }
	.gjk-btn-danger:hover { background:#fee2e2; }
	.gjk-btn-green { background:#00aa13; color:#fff!important; border-color:#00aa13; box-shadow:0 4px 12px -3px rgba(0,170,19,.4); }
	.gjk-btn-green:hover { background:#009411; }
	.gjk-btn-outline-green { background:#fff; color:#00aa13!important; border:1px solid #86d88f; }
	.gjk-btn-outline-green:hover { background:#f0fdf4; }
	.gjk-btn-red { background:#dc2626; color:#fff!important; border-color:#dc2626; }
	.gjk-btn-red:hover { background:#b91c1c; }

	.gjk-card { background:#fff; border:1px solid #eef1f5; border-radius:18px; box-shadow:0 1px 2px rgba(15,23,42,.04), 0 12px 30px -18px rgba(15,23,42,.12); margin-top:14px; overflow:hidden; }
	.gjk-card:first-of-type { margin-top:0; }

	.gjk-ticket-head { display:flex; align-items:center; gap:14px; padding:20px 22px; background:linear-gradient(135deg,#ffffff 0%,#f6f8fb 100%); }
	.gjk-avatar { width:54px; height:54px; border-radius:50%; background:linear-gradient(135deg,#00aa13,#00c81c); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.4rem; flex-shrink:0; box-shadow:0 6px 14px -6px rgba(0,170,19,.5); }
	.gjk-emp { font-weight:800; color:#0f172a; font-size:1.02rem; display:flex; align-items:center; gap:6px; }
	.gjk-emp .gjk-verif { color:#00aa13; font-size:.8rem; }
	.gjk-sub { font-size:.8rem; color:#64748b; }
	.gjk-ticket-meta { display:flex; align-items:center; gap:10px; flex-wrap:wrap; padding:0 22px 16px; }
	.gjk-ticket-chip { display:inline-flex; align-items:center; gap:6px; padding:5px 12px; border-radius:999px; background:#f1f5f9; font-size:.72rem; font-weight:700; color:#475569; }
	.gjk-ticket-rule { height:1px; background:linear-gradient(90deg, #eef1f5, #f6f8fb); }

	.gjk-thread { padding:20px 18px 8px; background:#f7f9fc; }
	.gjk-tl { display:flex; align-items:center; justify-content:center; gap:8px; margin:8px 0 6px; }
	.gjk-tl .line { height:1px; flex:1; max-width:60px; background:#e2e8f0; }
	.gjk-tl-chip { padding:5px 13px; border-radius:999px; background:#eef2f7; color:#64748b; font-size:.7rem; font-weight:700; letter-spacing:.02em; }

	.gjk-msg { display:flex; gap:10px; margin:10px 0; }
	.gjk-msg-in { justify-content:flex-start; }
	.gjk-msg-out { justify-content:flex-end; }
	.gjk-mini { width:34px; height:34px; border-radius:50%; background:#d9dce2; color:#53606e; display:flex; align-items:center; justify-content:center; font-size:.85rem; flex-shrink:0; margin-top:2px; }
	.gjk-msg-out.has-mini { padding-right:44px; }
	.gjk-bubble { max-width:82%; padding:12px 16px; border-radius:16px; font-size:.88rem; line-height:1.6; position:relative; }
	.gjk-in { background:#fff; border:1px solid #e7ebf0; border-top-left-radius:4px; color:#334155; box-shadow:0 4px 12px -8px rgba(15,23,42,.12); }
	.gjk-in .gjk-bubble-head { color:#94a3b8; }
	.gjk-out { background:#00aa13; color:#fff; border-top-right-radius:4px; box-shadow:0 4px 12px -6px rgba(0,170,19,.35); }
	.gjk-out .gjk-bubble-head { color:rgba(255,255,255,.8); }
	.gjk-in-sign { background:#fff; border:1px solid #e7ebf0; border-top-left-radius:4px; color:#334155; box-shadow:0 4px 12px -8px rgba(15,23,42,.12); }
	.gjk-sys { background:#eff6ff; border:1px solid #dbeafe; border-radius:14px; padding:11px 15px; font-size:.8rem; color:#1e3a8a; margin:10px 0; }
	.gjk-sys.green { background:#f0fdf4; border-color:#bbf7d0; color:#065f46; }
	.gjk-sys.red { background:#fef2f2; border-color:#fecaca; color:#991b1b; }
	.gjk-bubble-head { display:flex; align-items:center; gap:7px; font-size:.68rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; margin-bottom:5px; }
	.gjk-bubble-time { font-size:.68rem; margin-top:8px; opacity:.75; display:flex; align-items:center; gap:5px; }
	.gjk-bubble-stars { font-size:1rem; margin:2px 0 4px; }

	.gjk-composer { padding:16px 18px 20px; background:#f7f9fc; border-top:1px solid #edf1f5; }
	.gjk-composer label { display:block; font-size:.75rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:#8b9cb4; margin-bottom:8px; }
	.gjk-composer label span { color:#dc2626; }
	.gjk-composer .form-control { border:1px solid #e2e8f0; border-radius:14px; padding:12px 14px; font-size:.875rem; }
	.gjk-composer .form-control:focus { border-color:#00aa13; box-shadow:0 0 0 .2rem rgba(0,170,19,.12); }
	.gjk-note { font-size:.72rem; color:#8b9cb4; margin-top:9px; }
	.gjk-anon { display:inline-flex; align-items:center; gap:6px; background:#eef2f7; border-radius:999px; padding:4px 11px; font-size:.7rem; color:#64748b; font-weight:600; }

	@media (max-width:768px){
		.gjk-bubble { max-width:92%; }
		.gjk-msg-out.has-mini { padding-right:0; }
		.gjk-ticket-head { padding:16px; }
		.gjk-ticket-meta { padding:0 16px 14px; }
	}
</style>

	<?php $flash = $this->session->flashdata('message'); ?>
	<?php if ($flash): ?><div style="margin-bottom:12px; padding:11px 16px; border-radius:12px; font-size:.85rem; font-weight:600; background:#f0fdf4; border:1px solid #bbf7d0; color:#065f46;"><?= $flash ?></div><?php endif; ?>

	<div class="gjk-toolbar">
		<a href="<?= site_url('pelaporan') ?>" class="gjk-btn gjk-btn-soft"><i class="fas fa-arrow-left"></i> Kembali</a>
		<div style="font-weight:800; color:#0f172a; font-size:1.05rem;"><i class="fas fa-comments mr-2" style="color:#00aa13;"></i> Detail Laporan</div>
		<?php if ($is_pelapor || $is_hrd): ?>
			<a href="<?= site_url('pelaporan/delete/' . $row->id_laporan) ?>" class="gjk-btn gjk-btn-danger ml-auto" onclick="return confirm('Hapus laporan ini?');"><i class="fas fa-trash"></i> Hapus</a>
		<?php endif; ?>
	</div>

	<div class="gjk-card">
		<div class="gjk-ticket-head">
			<div class="gjk-avatar"><?= html_escape(mb_substr($row->nama_terlapor ?: '?', 0, 1)) ?></div>
			<div style="min-width:0;">
				<div class="gjk-emp"><?= html_escape($row->nama_terlapor ?: '—') ?> <i class="fas fa-check-circle gjk-verif" title="Karyawan yang dilaporkan"></i></div>
				<div class="gjk-sub"><?= html_escape($row->jabatan_terlapor ?: '—') ?> • <?= html_escape($row->unit_terlapor ?: '—') ?></div>
			</div>
			<div class="ml-auto text-right">
				<div style="font-size:.96rem; white-space:nowrap;"><?= gjk_stars($row->bintang) ?></div>
				<div class="gjk-sub" style="margin-top:3px;"><?= (int) $row->bintang ?>/5</div>
			</div>
		</div>
		<div class="gjk-ticket-meta">
			<span class="gjk-ticket-chip"><i class="fas fa-hashtag"></i> Laporan #<?= (int) $row->id_laporan ?></span>
			<span class="gjk-ticket-chip"><i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($row->created_at)) ?></span>
			<span class="gjk-ticket-chip"><i class="far fa-clock"></i> <?= !empty($row->jam) ? date('H:i', strtotime($row->jam)) : date('H:i', strtotime($row->created_at)) ?> WIB</span>
			<span class="gjk-ticket-chip gc"><i class="fas fa-star"></i> Bintang</span>
		</div>
		<div class="gjk-ticket-rule"></div>

		<div class="gjk-thread">

			<div class="gjk-tl"><span class="line"></span><span class="gjk-tl-chip"><i class="fas fa-file-alt mr-1"></i> Awal Percakapan</span><span class="line"></span></div>

			<!-- Pesan laporan (pelapor) -->
			<div class="gjk-msg gjk-msg-in">
				<div class="gjk-mini"><i class="fas fa-user-shield"></i></div>
				<div class="gjk-bubble gjk-in">
					<div class="gjk-bubble-head"><i class="fas fa-flag"></i> Laporan / Pengaduan
						<?php if ($show_pelapor_identitas): ?><span class="ml-auto" style="text-transform:none; font-weight:600;"><?= html_escape($row->nama_pelapor ?: 'Pelapor') ?></span><?php endif; ?>
					</div>
					<div class="gjk-bubble-stars"><?= gjk_stars($row->bintang) ?></div>
					<div style="white-space:pre-wrap;"><?= html_escape($row->alasan ?: '—') ?></div>
					<div class="gjk-bubble-time"><i class="far fa-clock"></i> <?= date('d M Y H:i', strtotime($row->created_at)) ?>
						<?php if (!$show_pelapor_identitas): ?><span class="ml-auto gjk-anon"><i class="fas fa-lock"></i> Identitas pelapor dirahasiakan</span><?php endif; ?>
					</div>
				</div>
			</div>

			<!-- Sanggahan (terlapor) -->
			<?php if (!empty($row->sanggahan)): ?>
			<div class="gjk-tl"><span class="line"></span><span class="gjk-tl-chip"><i class="fas fa-reply mr-1"></i> Sanggahan Terlapor</span><span class="line"></span></div>
			<div class="gjk-msg gjk-msg-out has-mini">
				<div class="gjk-bubble gjk-out">
					<div class="gjk-bubble-head"><i class="fas fa-user"></i> <?= html_escape($row->sanggahan_oleh ?: 'Terlapor') ?></div>
					<div style="white-space:pre-wrap;"><?= html_escape($row->sanggahan) ?></div>
					<div class="gjk-bubble-time"><i class="far fa-clock"></i> <?= !empty($row->sanggahan_at) ? date('d M Y H:i', strtotime($row->sanggahan_at)) : '-' ?></div>
				</div>
			</div>
			<?php endif; ?>

			<!-- Status validasi (paling bawah) -->
			<?php if (!empty($row->validasi_at)): ?>
			<div class="gjk-tl"><span class="line"></span><span class="gjk-tl-chip"><i class="fas fa-clipboard-check mr-1"></i> Keputusan Admin / HRD</span><span class="line"></span></div>
			<div class="gjk-msg gjk-msg-in">
				<div class="gjk-mini" style="background:<?= $row->status === 'divalidasi' ? '#f0fdf4; color:#00aa13' : '#fef2f2; color:#dc2626' ?>"><i class="<?= $row->status === 'divalidasi' ? 'fas fa-check' : 'fas fa-times' ?>"></i></div>
				<div class="gjk-bubble gjk-<?= $row->status === 'divalidasi' ? 'sys green' : 'sys red' ?>" style="border-radius:16px;">
					<div class="gjk-bubble-head"><?= gjk_status($row->status) ?></div>
					<div style="white-space:pre-wrap;"><?= html_escape($row->validasi_alasan ?: '-') ?></div>
					<div class="gjk-bubble-time"><i class="fas fa-user-tie"></i> <?= html_escape($row->validasi_oleh ?: 'Admin / HRD') ?> &mdash; <?= date('d M Y H:i', strtotime($row->validasi_at)) ?></div>
				</div>
			</div>
			<?php else: ?>
			<div class="gjk-tl"><span class="line"></span><span class="gjk-tl-chip"><i class="fas fa-hourglass-half mr-1"></i> Menunggu Validasi</span><span class="line"></span></div>
			<?php endif; ?>

		</div>

		<!-- Composer sanggahan -->
		<?php if ($can_sanggah): ?>
		<div class="gjk-composer" style="border-top:0;">
			<form method="POST" action="<?= site_url('pelaporan/sanggah_action') ?>">
				<input type="hidden" name="id_laporan" value="<?= (int) $row->id_laporan ?>">
				<label><?= $is_hrd && !$is_terlapor ? '<i class="fas fa-user-tie mr-1"></i> Sanggahan (atas nama terlapor)' : '<i class="fas fa-reply mr-1"></i> Balas — Tulis sanggahan Anda' ?></label>
				<textarea name="sanggahan" class="form-control" rows="3" placeholder="Tulis tanggapan / sanggahan Anda atas laporan ini..."><?= html_escape($row->sanggahan ?: '') ?></textarea>
				<div class="d-flex align-items-center justify-content-between" style="gap:10px; margin-top:12px; flex-wrap:wrap;">
					<div class="gjk-note mb-0"><?= $is_terlapor ? '<i class="fas fa-shield-alt mr-1"></i> Sanggahan hanya dapat dikirim satu kali dan identitas pelapor dirahasiakan.' : '<i class="fas fa-info-circle mr-1"></i> Diisi Admin/HRD atas nama terlapor.' ?></div>
					<button type="submit" class="gjk-btn gjk-btn-green"><i class="fas fa-paper-plane mr-1"></i> Kirim Sanggahan</button>
				</div>
			</form>
		</div>
		<?php elseif ($is_terlapor && !empty($row->sanggahan)): ?>
		<div class="gjk-composer" style="border-top:0;">
			<div class="gjk-note mb-0 text-center"><i class="fas fa-lock mr-1"></i> Sanggahan Anda telah terkirim dan hanya dapat diberikan <strong>satu kali</strong>.</div>
		</div>
		<?php endif; ?>

		<!-- Composer validasi (Admin/HRD) -->
		<?php if ($can_validasi): ?>
		<div class="gjk-composer" style="border-top:1px solid #edf1f5;">
			<form method="POST" action="<?= site_url('pelaporan/validasi_action') ?>">
				<input type="hidden" name="id_laporan" value="<?= (int) $row->id_laporan ?>">
				<label><i class="fas fa-clipboard-check mr-1"></i> Keputusan Validasi <span>*</span></label>
				<textarea name="alasan_validasi" id="alasan_validasi" class="form-control" rows="3" placeholder="Tuliskan alasan keputusan Anda..."><?= html_escape($row->validasi_alasan ?: '') ?></textarea>
				<div class="d-flex align-items-center justify-content-between" style="gap:10px; margin-top:12px; flex-wrap:wrap;">
					<div class="gjk-note mb-0"><i class="fas fa-info-circle mr-1"></i> Alasan terlihat oleh pelapor &amp; karyawan yang dinilai.</div>
					<div class="d-flex" style="gap:8px;">
						<button type="submit" name="keputusan" value="ditolak" class="gjk-btn gjk-btn-red" onclick="return validasiCheck('ditolak');"><i class="fas fa-times mr-1"></i> Tolak</button>
						<button type="submit" name="keputusan" value="divalidasi" class="gjk-btn gjk-btn-green" onclick="return validasiCheck('divalidasi');"><i class="fas fa-check mr-1"></i> Validasi</button>
					</div>
				</div>
			</form>
		</div>
		<?php endif; ?>
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