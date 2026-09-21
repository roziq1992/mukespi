<style>
.hsl-card{background:#fff;border:1px solid #e4eaf2;border-radius:14px;overflow:hidden;box-shadow:0 6px 20px -8px rgba(23,43,77,.08);margin-bottom:20px}
.hsl-card-head{background:linear-gradient(135deg,#1f4e79,#2c7a9d);color:#fff;padding:14px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px}
.hsl-card-head h6{margin:0;font-weight:800;font-size:.92rem}
.hsl-total-big{font-size:1.5rem;font-weight:800;color:#0f9b8e;line-height:1}
.hsl-label{font-size:.62rem;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;font-weight:700}
.hsl-empty{background:#fff;border:1px solid #e4eaf2;border-radius:14px;text-align:center;padding:50px 20px;color:#64748b}
.hsl-empty i{font-size:3rem;color:#cbd5e1;margin-bottom:14px;display:block}
</style>

<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
		<h1 class="h3 mb-0 text-gray-800"><i class="fas fa-clipboard-list mr-1"></i> Hasil Penilaian Saya</h1>
		<a href="<?php echo site_url('penilaian_otk'); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
	</div>

	<?php if ($periode): ?>
	<div class="alert alert-primary py-2 px-3" style="font-size:.85rem;">
		<strong>Periode aktif:</strong> <?php echo html_escape($periode->nama); ?>
		<?php if ($periode->tanggal_mulai && $periode->tanggal_selesai): ?>
			| Penilaian: <?php echo date('d M Y', strtotime($periode->tanggal_mulai)); ?> &ndash; <?php echo date('d M Y', strtotime($periode->tanggal_selesai)); ?>
		<?php endif; ?>
		<?php if ($periode->input_mulai && $periode->input_selesai): ?>
			| Input: <?php echo date('d M Y', strtotime($periode->input_mulai)); ?> &ndash; <?php echo date('d M Y', strtotime($periode->input_selesai)); ?>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php if (empty($hasil)): ?>
	<div class="hsl-empty">
		<i class="fas fa-chart-pie"></i>
		<h5 class="font-weight-bold text-gray-700">Belum ada hasil penilaian</h5>
		<p class="mb-0">Anda belum dinilai pada periode mana pun. Hasil penilaian yang menyasar Anda akan tampil di sini.</p>
	</div>
	<?php else: ?>

	<?php
	$group_per_periode = array();
	foreach ($hasil as $h) {
		$key = $h->id_periode;
		if (!isset($group_per_periode[$key])) {
			$group_per_periode[$key] = array('nama' => $h->nama_periode, 'tahun' => $h->tahun, 'items' => array());
		}
		$group_per_periode[$key]['items'][] = $h;
	}
	?>

	<?php foreach ($group_per_periode as $g): ?>
	<div class="hsl-card">
		<div class="hsl-card-head">
			<h6><i class="fas fa-calendar-alt mr-1"></i> <?php echo html_escape($g['nama']); ?> (<?php echo (int) $g['tahun']; ?>)</h6>
			<span class="badge badge-light"><?php echo count($g['items']); ?> penilaian</span>
		</div>
		<div class="table-responsive">
			<table class="table table-hover mb-0">
				<thead class="thead-light">
					<tr>
						<th>#</th>
						<th>Penilai</th>
						<th>Tanggal Penilaian</th>
						<th>Status</th>
						<th>Total Nilai</th>
						<th>Predikat</th>
					</tr>
				</thead>
				<tbody>
					<?php $no = 0; foreach ($g['items'] as $r): $no++; ?>
					<tr>
						<td><?php echo $no; ?></td>
						<td class="font-weight-bold"><?php echo html_escape($r->nama_penilai); ?></td>
						<td><?php echo $r->tanggal_penilaian ? date('d M Y', strtotime($r->tanggal_penilaian)) : '-'; ?></td>
						<td>
							<?php if ($r->status === 'selesai'): ?>
								<span class="badge badge-success">Selesai</span>
							<?php elseif ($r->status === 'draft'): ?>
								<span class="badge badge-warning">Draft</span>
							<?php else: ?>
								<span class="badge badge-secondary"><?php echo html_escape(ucfirst($r->status)); ?></span>
							<?php endif; ?>
						</td>
						<td class="hsl-total-big"><?php echo ($r->total_nilai !== NULL) ? number_format($r->total_nilai, 2) : '-'; ?></td>
						<td><span class="badge badge-info"><?php echo html_escape($r->predikat); ?></span></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php endforeach; ?>

	<div class="text-muted small mb-4"><i class="fas fa-info-circle mr-1"></i>Halaman ini hanya menampilkan hasil penilaian untuk diri Anda sendiri.</div>
	<?php endif; ?>
</div>