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

<style>
	.plrd-page { font-family: 'Inter', system-ui, sans-serif; max-width: 820px; }
	.plrd-page * { box-sizing: border-box; }
	.plrd-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 10px 25px -5px rgba(15,23,42,.06); overflow:hidden; }
	.plrd-header { background:linear-gradient(135deg,#1e1b4b 0%,#312e81 60%,#4338ca 100%); padding:22px 28px; color:#fff; display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap; }
	.plrd-header-title { display:flex; align-items:center; gap:14px; }
	.plrd-header .icon { width:44px; height:44px; border-radius:11px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.18); display:flex; align-items:center; justify-content:center; color:#a5b4fc; font-size:1.05rem; }
	.plrd-header h5 { margin:0; font-size:1.1rem; font-weight:700; }
	.plrd-header .plrd-date { margin:3px 0 0; font-size:.72rem; font-family:'JetBrains Mono',ui-monospace,monospace; color:#a5b4fc; }
	.plrd-body { padding:28px; }
	.plrd-target { display:flex; align-items:center; gap:14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px 18px; margin-bottom:20px; }
	.plrd-avatar { width:52px; height:52px; border-radius:50%; background:linear-gradient(135deg,#c7d2fe,#a5b4fc); color:#312e81; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:1.3rem; flex-shrink:0; }
	.plrd-emp { font-weight:800; color:#0f172a; font-size:1rem; }
	.plrd-sub { font-size:.8rem; color:#64748b; }
	.plrd-stars { font-size:1.35rem; white-space:nowrap; margin-top:6px; }
	.plrd-alasan-box { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:18px; margin-bottom:20px; }
	.plrd-alasan-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#64748b; margin-bottom:8px; }
	.plrd-alasan-text { font-size:.92rem; line-height:1.65; color:#334155; white-space:pre-wrap; }
	.plrd-meta { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:22px; }
	.plrd-meta-item { background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:12px 14px; }
	.plrd-meta-item label { display:block; font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#94a3b8; margin-bottom:3px; }
	.plrd-meta-item span { font-size:.85rem; font-weight:600; color:#0f172a; }
	.plrd-btn { display:inline-flex; align-items:center; gap:7px; padding:9px 16px; border-radius:9px; font-size:.82rem; font-weight:700; border:1px solid transparent; text-decoration:none!important; transition:all .15s; }
	.plrd-btn-soft { background:#fff; color:#334155!important; border-color:#e2e8f0; }
	.plrd-btn-soft:hover { background:#f8fafc; }
	.plrd-btn-danger { background:#fef2f2; border-color:rgba(239,68,68,.25); color:#b91c1c; }
	.plrd-btn-danger:hover { background:#fee2e2; }
	.plrd-empty { color:#94a3b8; font-style:italic; font-size:.85rem; }
	@media (max-width:768px){ .plrd-body{padding:18px} .plrd-meta{grid-template-columns:1fr} }
</style>

	<div class="plrd-page">

		<?php $flash = $this->session->flashdata('message'); ?>
		<?php if ($flash): ?><div style="margin-bottom:16px; padding:11px 16px; border-radius:10px; font-size:.85rem; font-weight:600; background:#ecfdf5; border:1px solid #a7f3d0; color:#064e3b;"><?= $flash ?></div><?php endif; ?>

		<div class="plrd-card">
			<div class="plrd-header">
				<div class="plrd-header-title">
					<div class="icon"><i class="fas fa-file-alt"></i></div>
					<div>
						<h5>Detail Laporan</h5>
						<div class="plrd-date">Dibuat: <?= date('d M Y H:i', strtotime($row->created_at)) ?></div>
					</div>
				</div>
				<div style="display:flex; gap:8px; flex-wrap:wrap;">
					<a href="<?= site_url('pelaporan') ?>" class="plrd-btn plrd-btn-soft"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
					<?php if ($is_pelapor || $is_hrd): ?>
						<a href="<?= site_url('pelaporan/delete/' . $row->id_laporan) ?>" class="plrd-btn plrd-btn-danger" onclick="return confirm('Hapus laporan ini?');"><i class="fas fa-trash mr-1"></i> Hapus</a>
					<?php endif; ?>
				</div>
			</div>

			<div class="plrd-body">

				<div class="plrd-target">
					<div class="plrd-avatar"><?= html_escape(mb_substr($row->nama_terlapor ?: '?', 0, 1)) ?></div>
					<div>
						<div class="plrd-emp"><?= html_escape($row->nama_terlapor ?: '—') ?></div>
						<div class="plrd-sub"><?= html_escape($row->jabatan_terlapor ?: '—') ?> • <?= html_escape($row->unit_terlapor ?: '—') ?></div>
					</div>
					<div class="ml-auto plrd-stars"><?= plrd_stars($row->bintang) ?></div>
				</div>

				<div class="plrd-alasan-box">
					<div class="plrd-alasan-label"><i class="fas fa-comment-dots mr-1"></i> Alasan / Pengaduan</div>
					<div class="plrd-alasan-text"><?= html_escape($row->alasan ?: '—') ?></div>
				</div>

				<div class="plrd-meta">
					<div class="plrd-meta-item">
						<label>Nilai Bintang</label>
						<span><?= (int) $row->bintang ?> / 5</span>
					</div>
					<div class="plrd-meta-item">
						<label>Dilaporkan Oleh</label>
						<span><?= html_escape($row->nama_pelapor ?: '—') ?> <?= $is_hrd ? '<br><small style="font-weight:400;color:#94a3b8;">' . html_escape($row->identitas_pelapor ?: '') . '</small>' : '' ?></span>
					</div>
				</div>

			</div>
		</div>

	</div>

</div>