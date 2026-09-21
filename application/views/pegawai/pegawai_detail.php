<div class="container-fluid p-0">

<style>
	.pgw-page { font-family: 'Inter', system-ui, sans-serif; }
	.pgw-page * { box-sizing: border-box; }

	.pgw-hero {
		background: linear-gradient(135deg, #1e1b4b 0%, #312e81 55%, #4338ca 100%);
		border-radius: 16px;
		padding: 28px 30px;
		color: #fff;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 20px;
		flex-wrap: wrap;
		position: relative;
		overflow: hidden;
		box-shadow: 0 12px 30px -10px rgba(49, 46, 129, 0.45);
		margin-bottom: 20px;
		border: 1px solid rgba(255,255,255,0.06);
	}
	.pgw-hero::after {
		content: '';
		position: absolute;
		right: -40px;
		top: -60px;
		width: 260px;
		height: 260px;
		border-radius: 50%;
		background: radial-gradient(circle, rgba(165, 180, 252, 0.18) 0%, transparent 70%);
		pointer-events: none;
	}
	.pgw-hero-main { display: flex; align-items: center; gap: 18px; position: relative; z-index: 1; }
	.pgw-hero-avatar {
		width: 68px;
		height: 68px;
		border-radius: 50%;
		background: linear-gradient(135deg, #a5b4fc, #c7d2fe);
		color: #312e81;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.6rem;
		font-weight: 800;
		flex-shrink: 0;
		box-shadow: 0 6px 16px rgba(0,0,0,0.2);
	}
	.pgw-hero-avatar.is-nonaktif { background: #475569; color: #cbd5e1; }
	.pgw-hero h4 { margin: 0 0 4px; font-size: 1.35rem; font-weight: 800; letter-spacing: -0.02em; }
	.pgw-hero-role { color: #a5b4fc; font-size: 0.85rem; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
	.pgw-hero-role i { color: #a5b4fc; }
	.pgw-hero-actions { display: flex; gap: 10px; position: relative; z-index: 1; flex-wrap: wrap; }

	.pgw-btn {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		padding: 9px 16px;
		border-radius: 9px;
		font-size: 0.82rem;
		font-weight: 700;
		border: 1px solid transparent;
		cursor: pointer;
		transition: all .15s;
		text-decoration: none !important;
	}
	.pgw-btn-light { background: rgba(255,255,255,0.15); color: #fff !important; border-color: rgba(255,255,255,0.2); }
	.pgw-btn-light:hover { background: rgba(255,255,255,0.25); }
	.pgw-btn-indigo { background: #4f46e5; color: #fff !important; }
	.pgw-btn-indigo:hover { background: #4338ca; box-shadow: 0 6px 14px rgba(79,70,229,0.4); }
	.pgw-btn-ghost { background: #fff; color: #334155 !important; border-color: #e2e8f0; }
	.pgw-btn-ghost:hover { background: #f8fafc; }
	.pgw-btn-red { background: #dc2626; color: #fff !important; }
	.pgw-btn-red:hover { background: #b91c1c; }
	.pgw-btn-green { background: #059669; color: #fff !important; }
	.pgw-btn-green:hover { background: #047857; }

	.pgw-grid { display: grid; grid-template-columns: 1fr 1.4fr; gap: 20px; align-items: start; }

	.pgw-card {
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 14px;
		box-shadow: 0 6px 20px -8px rgba(15, 23, 42, 0.08);
		overflow: hidden;
	}
	.pgw-card-head {
		padding: 14px 18px;
		border-bottom: 1px solid #f1f5f9;
		font-weight: 700;
		font-size: 0.9rem;
		color: #0f172a;
		display: flex;
		align-items: center;
		gap: 9px;
	}
	.pgw-card-head i { color: #4f46e5; }
	.pgw-card-body { padding: 18px; }

	/* Info grid */
	.pgw-info { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
	.pgw-info tr { border-bottom: 1px solid #f6f8fb; }
	.pgw-info tr:last-child { border-bottom: none; }
	.pgw-info td { padding: 8px 4px; vertical-align: top; }
	.pgw-info td:first-child { color: #64748b; font-weight: 500; width: 38%; }
	.pgw-info td:last-child { color: #0f172a; font-weight: 600; text-transform: capitalize; }

	.badge-st {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		padding: 4px 12px;
		border-radius: 20px;
		font-size: 0.75rem;
		font-weight: 700;
		white-space: nowrap;
	}
	.badge-st .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
	.badge-st-aktif { background: #ecfdf5; color: #047857; border: 1px solid rgba(16,185,129,0.25); }
	.badge-st-nonaktif { background: #fef2f2; color: #b91c1c; border: 1px solid rgba(239,68,68,0.25); }

	/* Timeline */
	.pgw-timeline { position: relative; padding-left: 6px; }
	.pgw-tl-item {
		position: relative;
		padding-left: 26px;
		padding-bottom: 22px;
	}
	.pgw-tl-item:last-child { padding-bottom: 4px; }
	.pgw-tl-item::before {
		content: '';
		position: absolute;
		left: 6px;
		top: 22px;
		bottom: 0;
		width: 2px;
		background: #e2e8f0;
	}
	.pgw-tl-item:last-child::before { display: none; }
	.pgw-tl-dot {
		position: absolute;
		left: 0;
		top: 2px;
		width: 14px;
		height: 14px;
		border-radius: 50%;
		border: 3px solid #fff;
		box-shadow: 0 0 0 2px #4f46e5;
		background: #4f46e5;
	}
	.pgw-tl-item t-mutasi .pgw-tl-dot { box-shadow: 0 0 0 2px #4f46e5; background: #4f46e5; }
	.pgw-tl-item t-nonaktif .pgw-tl-dot { box-shadow: 0 0 0 2px #dc2626; background: #dc2626; }
	.pgw-tl-item t-aktif .pgw-tl-dot { box-shadow: 0 0 0 2px #059669; background: #059669; }
	.pgw-tl-item t-masuk .pgw-tl-dot { box-shadow: 0 0 0 2px #d97706; background: #d97706; }

	.pgw-tl-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
		flex-wrap: wrap;
		margin-bottom: 3px;
	}
	.pgw-tl-title { font-weight: 700; font-size: 0.83rem; color: #0f172a; display: flex; align-items: center; gap: 7px; }
	.pgw-tl-date { font-family: 'JetBrains Mono', ui-monospace, monospace; font-size: 0.72rem; color: #94a3b8; font-weight: 600; }
	.pgw-tl-desc { font-size: 0.8rem; color: #475569; line-height: 1.5; }
	.pgw-tl-route {
		display: inline-flex;
		align-items: center;
		gap: 7px;
		font-size: 0.78rem;
		font-weight: 600;
		color: #334155;
		margin-top: 5px;
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		padding: 4px 10px;
		border-radius: 8px;
	}
	.pgw-tl-route .arr { color: #4f46e5; }

	.pgw-empty {
		text-align: center;
		padding: 30px 16px;
		color: #94a3b8;
		font-size: 0.85rem;
	}
	.pgw-empty i { font-size: 2rem; color: #cbd5e1; margin-bottom: 8px; display: block; }

	.pgw-flash:not(:empty) {
		margin-bottom: 16px;
		padding: 10px 16px;
		border-radius: 10px;
		font-size: 0.85rem;
		font-weight: 600;
		background: #ecfdf5;
		border: 1px solid #a7f3d0;
		color: #064e3b;
	}
	.pgw-flash.alert-danger { background: #fef2f2; border-color: #fecaca; color: #7f1d1d; }

	.modal-backdrop { z-index: 10000 !important; }
	.modal { z-index: 10001 !important; }

	@media (max-width: 900px) {
		.pgw-grid { grid-template-columns: 1fr; }
	}
</style>

	<div class="pgw-page">

		<?php
		$flash = $this->session->flashdata('message');
		$prev = $this->session->userdata('message');
		?>
		<div class="pgw-flash <?= (strpos((string) $flash, 'danger') !== false) ? 'alert-danger' : '' ?>"><?= $flash ?: ($prev ?: '') ?></div>

		<!-- HERO -->
		<div class="pgw-hero">
			<div class="pgw-hero-main">
				<div class="pgw-hero-avatar <?= ($pegawai->status === 'nonaktif') ? 'is-nonaktif' : '' ?>">
					<?= html_escape(mb_substr($pegawai->nama, 0, 1)) ?>
				</div>
				<div>
					<h4><?= html_escape($pegawai->nama) ?></h4>
					<div class="pgw-hero-role">
						<i class="fas fa-briefcase"></i> <?= html_escape($pegawai->jabatan) ?>
						&nbsp;&middot;&nbsp;
						<i class="fas fa-building"></i> <?= html_escape($pegawai->unit_kerja) ?>
						&nbsp;
						<span class="badge-st <?= ($pegawai->status === 'aktif') ? 'badge-st-aktif' : 'badge-st-nonaktif' ?>">
							<span class="dot"></span> <?= ucfirst($pegawai->status) ?>
						</span>
					</div>
				</div>
			</div>
			<div class="pgw-hero-actions">
				<?php if ($can_edit): ?>
					<?= anchor(site_url('pegawai/update/' . $pegawai->id_pegawai), '<i class="fas fa-edit"></i> Edit', 'class="pgw-btn pgw-btn-light"') ?>
				<?php endif; ?>
				<?php if (!$is_pegawai_view): ?>
					<button type="button" class="pgw-btn pgw-btn-indigo" data-toggle="modal" data-target="#mutasiModal"><i class="fas fa-exchange-alt"></i> Mutasi</button>
					<?php if ($pegawai->status === 'aktif'): ?>
						<button type="button" class="pgw-btn pgw-btn-red" data-toggle="modal" data-target="#nonaktifModal"><i class="fas fa-user-slash"></i> Nonaktifkan</button>
					<?php else: ?>
						<button type="button" class="pgw-btn pgw-btn-green" data-toggle="modal" data-target="#aktifModal"><i class="fas fa-user-check"></i> Aktifkan</button>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>

		<?php if ($is_pegawai_view): ?>
		<div style="margin-bottom:20px; padding:12px 16px; border-radius:10px; font-size:.82rem; font-weight:600;
			<?= $edit_mode ? 'background:#ecfdf5; border:1px solid #a7f3d0; color:#047857;' : 'background:#f8fafc; border:1px solid #e2e8f0; color:#64748b;' ?>">
			<i class="fas <?= $edit_mode ? 'fa-lock-open' : 'fa-lock' ?> mr-1"></i>
			Mode edit data pegawai saat ini: <strong><?= $edit_mode ? 'AKTIF' : 'NONAKTIF' ?></strong>.
			<?= $edit_mode ? 'Anda dapat mengubah data diri Anda melalui tombol Edit.' : 'Anda belum dapat mengubah data diri. Hubungi HRD/Admin jika ingin memperbaiki data.' ?>
		</div>
		<?php endif; ?>

		<div class="pgw-grid">

			<!-- INFO -->
			<div class="pgw-card">
				<div class="pgw-card-head"><i class="fas fa-id-card"></i> Data Pegawai</div>
				<div class="pgw-card-body">
					<table class="pgw-info">
						<tr><td>NIK</td><td><span class="d-block text-lowercase" style="text-transform:none;"><?= html_escape($pegawai->nik ?: '—') ?></span></td></tr>
						<tr><td>NIP</td><td><span class="d-block text-lowercase" style="text-transform:none;"><?= html_escape($pegawai->nip ?: '—') ?></span></td></tr>
						<tr><td>Jenis Kelamin</td><td><?= html_escape($pegawai->jenis_kelamin) ?></td></tr>
						<tr><td>Tempat, Tgl Lahir</td><td>
							<?= html_escape($pegawai->tempat_lahir ?: '—') ?><?= $pegawai->tanggal_lahir ? ', ' . date('d M Y', strtotime($pegawai->tanggal_lahir)) : '' ?>
						</td></tr>
						<tr><td>Alamat</td><td><span class="d-block text-lowercase" style="text-transform:none;"><?= html_escape($pegawai->alamat ?: '—') ?></span></td></tr>
						<tr><td>No. HP</td><td><span class="d-block text-lowercase" style="text-transform:none;"><?= html_escape($pegawai->no_hp ?: '—') ?></span></td></tr>
						<tr><td>No. HP Keluarga</td><td><span class="d-block text-lowercase" style="text-transform:none;"><?= html_escape($pegawai->no_hp_keluarga ?: '—') ?></span></td></tr>
						<tr><td>No. NPWP</td><td><span class="d-block text-lowercase" style="text-transform:none;"><?= html_escape($pegawai->no_npwp ?: '—') ?></span></td></tr>
						<tr><td>Email</td><td><span class="d-block text-lowercase" style="text-transform:none;"><?= html_escape($pegawai->email ?: '—') ?></span></td></tr>
						<tr><td>Nama Suami/Istri/Orang Tua</td><td><span class="d-block" style="text-transform:none;"><?= html_escape($pegawai->nama_keluarga ?: '—') ?></span></td></tr>
						<tr><td>Nama Anak</td><td><span class="d-block" style="text-transform:none; white-space:pre-line;"><?= html_escape($pegawai->nama_anak ?: '—') ?></span></td></tr>
						<tr><td>Jabatan</td><td><?= html_escape($pegawai->jabatan) ?></td></tr>
						<tr><td>Unit Kerja</td><td><?= html_escape($pegawai->unit_kerja) ?></td></tr>
						<tr><td>Status Kepegawaian</td><td><span class="d-block" style="text-transform:none;"><?= html_escape($pegawai->status_kepegawaian ?: '—') ?></span></td></tr>
						<tr><td>Tanggal Masuk</td><td><?= $pegawai->tanggal_masuk ? date('d M Y', strtotime($pegawai->tanggal_masuk)) : '—' ?></td></tr>
						<tr><td>Kualifikasi Pendidikan</td><td><span class="d-block" style="text-transform:none; white-space:pre-line;"><?= html_escape($pegawai->kualifikasi_pendidikan ?: '—') ?></span></td></tr>
						<tr><td>Pengalaman Kerja</td><td><span class="d-block" style="text-transform:none; white-space:pre-line;"><?= html_escape($pegawai->pengalaman_kerja ?: '—') ?></span></td></tr>
						<tr><td>Pelatihan</td><td><span class="d-block" style="text-transform:none; white-space:pre-line;"><?= html_escape($pegawai->pelatihan ?: '—') ?></span></td></tr>
						<tr><td>Organisasi</td><td><span class="d-block" style="text-transform:none; white-space:pre-line;"><?= html_escape($pegawai->organisasi ?: '—') ?></span></td></tr>
					</table>
				</div>
			</div>

			<!-- RIWAYAT -->
			<div class="pgw-card">
				<div class="pgw-card-head"><i class="fas fa-history"></i> Riwayat Mutasi & Status
					<span class="ml-auto badge badge-primary badge-pill" style="background:#eef2ff; color:#4338ca; font-size:.72rem;"><?= count($history) ?> catatan</span>
				</div>
				<div class="pgw-card-body">
					<?php if (count($history) > 0): ?>
					<div class="pgw-timeline">
						<?php foreach ($history as $h):
							$label = $h->jenis;
							$desc = $h->keterangan;
							$icon = 'fa-solid fa-arrows-turn-to-dots';
							if ($h->jenis === 'mutasi') { $label = 'Mutasi'; }
							elseif ($h->jenis === 'nonaktif') { $label = 'Nonaktif'; $icon = 'fas fa-user-slash'; }
							elseif ($h->jenis === 'aktif') { $label = 'Aktif Kembali'; $icon = 'fas fa-user-check'; }
							elseif ($h->jenis === 'masuk') { $label = 'Pendataan Awal'; $icon = 'fas fa-user-plus'; }
						?>
						<div class="pgw-tl-item">
							<span class="pgw-tl-dot"></span>
							<div class="pgw-tl-head">
								<span class="pgw-tl-title"><i class="<?= $icon ?>"></i> <?= $label ?></span>
								<span class="pgw-tl-date"><?= date('d M Y', strtotime($h->tanggal_mutasi)) ?></span>
							</div>

							<?php if ($h->jenis === 'mutasi'): ?>
								<div class="pgw-tl-route">
									<?= html_escape($h->unit_asal ?: '—') ?> <i class="fas fa-arrow-right arr"></i> <?= html_escape($h->unit_tujuan ?: '—') ?>
									<span class="mx-1" style="color:#cbd5e1;">|</span>
									<?= html_escape($h->jabatan_asal ?: '—') ?> <i class="fas fa-arrow-right arr"></i> <?= html_escape($h->jabatan_tujuan ?: '—') ?>
								</div>
							<?php elseif ($h->jenis === 'masuk'): ?>
								<div class="pgw-tl-route">
									<i class="fas fa-building"></i> <?= html_escape($h->unit_tujuan ?: '—') ?> &nbsp;&middot;&nbsp; <?= html_escape($h->jabatan_tujuan ?: '—') ?>
								</div>
							<?php endif; ?>

							<?php if ($desc): ?>
								<div class="pgw-tl-desc"><?= html_escape($desc) ?></div>
							<?php endif; ?>
						</div>
						<?php endforeach; ?>
					</div>
					<?php else: ?>
					<div class="pgw-empty">
						<i class="fas fa-inbox"></i>
						Belum ada riwayat mutasi untuk pegawai ini.
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

</div>

<!-- ===== MODAL MUTASI ===== -->
<div class="modal fade" id="mutasiModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<form method="POST" action="<?= site_url('pegawai/mutasi_action') ?>" class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
			<input type="hidden" name="id_pegawai" value="<?= $pegawai->id_pegawai ?>">
			<div class="modal-header" style="background: linear-gradient(135deg,#1e1b4b,#4338ca); color:#fff;">
				<h5 class="modal-title font-weight-bold" style="font-size:1rem;">
					<i class="fas fa-exchange-alt mr-2"></i>Mutasi Pegawai
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body" style="font-size:14px;">
				<div class="mb-3 p-2" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; font-size:.82rem;">
					<i class="fas fa-user mr-1 text-primary"></i> <strong><?= html_escape($pegawai->nama) ?></strong><br>
					<span class="text-muted">
						<i class="fas fa-building mr-1"></i><?= html_escape($pegawai->unit_kerja) ?>
						<span class="ml-2"><i class="fas fa-briefcase mr-1"></i><?= html_escape($pegawai->jabatan) ?></span>
					</span>
				</div>
				<div class="form-group">
					<label>Unit Tujuan <span class="text-danger">*</span></label>
					<select name="unit_tujuan" class="form-control" required>
						<option value="">— Pilih Unit —</option>
						<?php foreach ($units as $u): ?>
							<option value="<?= html_escape($u->nm_unit) ?>" <?= ($u->nm_unit === $pegawai->unit_kerja) ? 'disabled' : '' ?>>
								<?= html_escape($u->nm_unit) ?><?= ($u->nm_unit === $pegawai->unit_kerja) ? ' (unit saat ini)' : '' ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label>Jabatan Tujuan <span class="text-danger">*</span></label>
					<input type="text" name="jabatan_tujuan" class="form-control" placeholder="Jabatan baru" required>
				</div>
				<div class="form-group">
					<label>Tanggal Efektif Mutasi <span class="text-danger">*</span></label>
					<input type="date" name="tanggal_mutasi" class="form-control" value="<?= date('Y-m-d') ?>" required>
				</div>
				<div class="form-group mb-0">
					<label>Keterangan</label>
					<textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: rotasi SDM, penambahan tenaga..."></textarea>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn px-4 font-weight-bold" style="background:#4f46e5; color:#fff;">Simpan Mutasi</button>
			</div>
		</form>
	</div>
</div>

<!-- ===== MODAL NONAKTIFKAN ===== -->
<div class="modal fade" id="nonaktifModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<form method="POST" action="<?= site_url('pegawai/nonaktif_action') ?>" class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
			<input type="hidden" name="id_pegawai" value="<?= $pegawai->id_pegawai ?>">
			<div class="modal-header" style="background: linear-gradient(135deg,#991b1b,#dc2626); color:#fff;">
				<h5 class="modal-title font-weight-bold" style="font-size:1rem;">
					<i class="fas fa-user-slash mr-2"></i>Nonaktifkan Pegawai
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body" style="font-size:14px;">
				<p>Yakin ingin <strong>menonaktifkan</strong> pegawai <strong class="text-danger"><?= html_escape($pegawai->nama) ?></strong>?</p>
				<div class="form-group mb-0">
					<label>Alasan / Keterangan</label>
					<textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: pensiun, mengundurkan diri, dikeluarkan..."></textarea>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn px-4 font-weight-bold" style="background:#dc2626; color:#fff;">Ya, Nonaktifkan</button>
			</div>
		</form>
	</div>
</div>

<!-- ===== MODAL AKTIFKAN KEMBALI ===== -->
<div class="modal fade" id="aktifModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<form method="POST" action="<?= site_url('pegawai/aktifkan_action') ?>" class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
			<input type="hidden" name="id_pegawai" value="<?= $pegawai->id_pegawai ?>">
			<div class="modal-header" style="background: linear-gradient(135deg,#047857,#059669); color:#fff;">
				<h5 class="modal-title font-weight-bold" style="font-size:1rem;">
					<i class="fas fa-user-check mr-2"></i>Aktifkan Kembali
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body" style="font-size:14px;">
				<p>Yakin ingin <strong>mengaktifkan kembali</strong> pegawai <strong class="text-success"><?= html_escape($pegawai->nama) ?></strong>?</p>
				<div class="form-group mb-0">
					<label>Keterangan</label>
					<textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: kembali bertugas..."></textarea>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Batal</button>
				<button type="submit" class="btn px-4 font-weight-bold" style="background:#059669; color:#fff;">Ya, Aktifkan</button>
			</div>
		</form>
	</div>
</div>