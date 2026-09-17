<div class="container-fluid p-0">

<?php if (!function_exists('plr_stars')): ?>
<?php function plr_stars($n)
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
	.plr-page { font-family: 'Inter', system-ui, sans-serif; }
	.plr-page * { box-sizing: border-box; }
	.plr-card {
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 16px;
		box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06);
		overflow: hidden;
	}
	.plr-header {
		background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
		padding: 24px 28px;
		color: #fff;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 16px;
		flex-wrap: wrap;
	}
	.plr-header-title { display: flex; align-items: center; gap: 14px; }
	.plr-header .icon {
		width: 46px; height: 46px; border-radius: 12px;
		background: rgba(255,255,255,0.12);
		border: 1px solid rgba(255,255,255,0.18);
		display: flex; align-items: center; justify-content: center;
		color: #a5b4fc; font-size: 1.1rem;
	}
	.plr-header h5 { margin: 0; font-size: 1.15rem; font-weight: 700; }
	.plr-header p { margin: 3px 0 0; font-size: 0.73rem; font-family: 'JetBrains Mono', ui-monospace, monospace; color: #a5b4fc; letter-spacing: 0.07em; text-transform: uppercase; font-weight: 600; }
	.plr-header .stat {
		background: rgba(255,255,255,0.1);
		border: 1px solid rgba(255,255,255,0.15);
		padding: 7px 14px; border-radius: 20px;
		font-size: 0.78rem; font-weight: 600; color: #e0e7ff;
	}
	.plr-header .stat .count { font-weight: 700; color: #fff; font-family: 'JetBrains Mono', ui-monospace, monospace; }

	.plr-body { padding: 24px 28px; overflow-x: auto; }

	.plr-btn {
		display: inline-flex; align-items: center; gap: 7px;
		padding: 9px 16px; border-radius: 9px; font-size: 0.82rem; font-weight: 700;
		border: 1px solid transparent; text-decoration: none !important; transition: all .15s;
	}
	.plr-btn-indigo { background: linear-gradient(135deg,#4f46e5,#4338ca); color: #fff !important; box-shadow: 0 4px 12px -3px rgba(79,70,229,.4); }
	.plr-btn-indigo:hover { background: linear-gradient(135deg,#4338ca,#3730a3); transform: translateY(-1px); }
	.plr-btn-soft { background: #fff; color: #334155 !important; border-color: #e2e8f0; }
	.plr-btn-soft:hover { background: #f8fafc; }
	.plr-btn-danger { background: #fef2f2; border-color: rgba(239,68,68,.25); color: #b91c1c; }
	.plr-btn-danger:hover { background: #fee2e2; }
	.plr-btn-eye { background: #fff; border-color: #e2e8f0; color: #334155; }
	.plr-btn-eye:hover { background: #f8fafc; }

	.plr-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.875rem; }
	.plr-table thead th {
		background: #fafafa; padding: 12px 16px; font-size: 0.72rem;
		font-family: 'JetBrains Mono', ui-monospace, monospace; font-weight: 700;
		text-transform: uppercase; letter-spacing: 0.08em; color: #64748b;
		border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;
	}
	.plr-table thead th:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; border-left: 1px solid #e2e8f0; }
	.plr-table thead th:last-child { border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-right: 1px solid #e2e8f0; }
	.plr-table tbody td { padding: 15px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
	.plr-table tbody tr:hover { background: #f8fafc; }
	.plr-table tbody tr:last-child td { border-bottom: none; }

	.plr-emp { font-weight: 700; color: #0f172a; }
	.plr-sub { font-size: 0.74rem; color: #64748b; font-weight: 500; display: block; }
	.plr-alasan { color: #475569; max-width: 340px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
	.plr-date { font-family: 'JetBrains Mono', ui-monospace, monospace; font-size: 0.75rem; color: #94a3b8; white-space: nowrap; }
	.plr-pelapor { font-size: 0.8rem; font-weight: 600; color: #4338ca; }

	.plr-empty { text-align: center; padding: 48px 16px; color: #94a3b8; }
	.plr-empty i { font-size: 2.2rem; color: #cbd5e1; margin-bottom: 10px; display: block; }

	.plr-flash:not(:empty) {
		margin-bottom: 16px; padding: 11px 16px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
		background: #ecfdf5; border: 1px solid #a7f3d0; color: #064e3b;
	}

	@media (max-width: 768px) {
		.plr-header { padding: 20px; }
		.plr-body { padding: 16px 18px; }
	}
</style>

	<div class="plr-page">

		<?php $flash = $this->session->flashdata('message'); ?>
		<div class="plr-flash"><?= $flash ?></div>

		<div class="plr-card">
			<div class="plr-header">
				<div class="plr-header-title">
					<div class="icon"><i class="fas fa-star-half-alt"></i></div>
					<div>
						<h5>Pelaporan / Pengaduan Karyawan</h5>
						<p>Penilaian sesama karyawan dengan bintang & alasan</p>
					</div>
				</div>
				<div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
					<span class="stat">Total Laporan <span class="count"><?= count($laporan) ?></span></span>
					<a href="<?= site_url('pelaporan/create') ?>" class="plr-btn plr-btn-indigo"><i class="fas fa-plus mr-1"></i> Buat Laporan</a>
				</div>
			</div>

			<div class="plr-body">
				<?php if (count($laporan) > 0): ?>
				<table class="plr-table">
					<thead>
						<tr>
							<th>No</th>
							<th>Karyawan Dinilai</th>
							<th>Bintang</th>
							<th>Alasan</th>
							<th>Tanggal</th>
							<?php if ($is_hrd): ?><th>Pelapor</th><?php endif; ?>
							<th style="text-align:center">Aksi</th>
						</tr>
					</thead>
					<tbody>
					<?php $no = 1; foreach ($laporan as $l): ?>
						<tr>
							<td style="color:#94a3b8; font-weight:600;"><?= $no++ ?></td>
							<td>
								<span class="plr-emp"><?= html_escape($l->nama_terlapor ?: '—') ?></span>
								<span class="plr-sub"><?= html_escape($l->jabatan_terlapor ?: '—') ?> • <?= html_escape($l->unit_terlapor ?: '—') ?></span>
							</td>
							<td><span style="white-space:nowrap;"><?= plr_stars($l->bintang) ?></span></td>
							<td><span class="plr-alasan" title="<?= html_escape($l->alasan) ?>"><?= html_escape($l->alasan) ?></span></td>
							<td><span class="plr-date"><?= date('d M Y', strtotime($l->created_at)) ?></span></td>
							<?php if ($is_hrd): ?>
							<td><span class="plr-pelapor"><?= html_escape($l->nama_pelapor ?: '—') ?></span></td>
							<?php endif; ?>
							<td style="text-align:center;">
								<div style="display:flex; gap:6px; justify-content:center; flex-wrap:wrap;">
									<?= anchor(site_url('pelaporan/detail/' . $l->id_laporan), '<i class="fas fa-eye"></i>', 'class="plr-btn plr-btn-eye" title="Lihat detail"') ?>
									<?= anchor(site_url('pelaporan/delete/' . $l->id_laporan), '<i class="fas fa-trash"></i>', 'class="plr-btn plr-btn-danger" title="Hapus laporan" onclick="return confirm(\'Hapus laporan ini?\');"') ?>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				<?php else: ?>
				<div class="plr-empty">
					<i class="fas fa-inbox"></i>
					Belum ada laporan / pengaduan yang tercatat.
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

</div>