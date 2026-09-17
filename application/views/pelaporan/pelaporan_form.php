<div class="container-fluid p-0">

<style>
	.plrf-page { font-family: 'Inter', system-ui, sans-serif; }
	.plrf-page * { box-sizing: border-box; }
	.plrf-card {
		background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
		box-shadow: 0 10px 25px -5px rgba(15,23,42,.06); overflow: hidden; max-width: 860px;
	}
	.plrf-header {
		background: linear-gradient(135deg,#1e1b4b 0%,#312e81 60%,#4338ca 100%);
		padding: 22px 28px; color: #fff; display: flex; align-items: center; gap: 14px;
	}
	.plrf-header .icon {
		width: 44px; height: 44px; border-radius: 11px; background: rgba(255,255,255,.12);
		border: 1px solid rgba(255,255,255,.18); display: flex; align-items: center; justify-content: center;
		color: #a5b4fc; font-size: 1.05rem;
	}
	.plrf-header h5 { margin: 0; font-size: 1.1rem; font-weight: 700; }
	.plrf-header p { margin: 3px 0 0; font-size: 0.72rem; font-family: 'JetBrains Mono', ui-monospace, monospace; color: #a5b4fc; letter-spacing: 0.07em; text-transform: uppercase; font-weight: 600; }
	.plrf-body { padding: 28px; }
	.plrf-section-tag {
		font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
		color: #64748b; margin: 0 0 14px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;
	}
	.plrf-form label { font-size: 0.8rem; font-weight: 600; color: #334155; }
	.plrf-form .form-control { border-color: #e2e8f0; border-radius: 8px; padding: 9px 12px; font-size: 0.875rem; }
	.plrf-form .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
	.plrf-form .text-danger { font-size: 0.75rem; font-weight: 600; }

	.plrf-stars { display: inline-flex; flex-direction: row-reverse; }
	.plrf-stars input { display: none; }
	.plrf-stars label {
		color: #d1d5db; cursor: pointer; font-size: 1.9rem; padding: 0 4px; transition: color .12s;
	}
	.plrf-stars label:hover,
	.plrf-stars label:hover ~ label,
	.plrf-stars input:checked ~ label { color: #f59e0b; }
	.plrf-stars input:checked + label { color: #f59e0b; }

	.plrf-note {
		margin-top: 6px; font-size: 0.76rem; color: #94a3b8;
	}

	.plrf-btn {
		display: inline-flex; align-items: center; gap: 7px; padding: 10px 22px; border-radius: 9px;
		font-size: 0.85rem; font-weight: 700; border: 1px solid transparent; text-decoration: none !important;
		transition: all .15s;
	}
	.plrf-btn-primary { background: linear-gradient(135deg,#4f46e5,#4338ca); color: #fff !important; box-shadow: 0 4px 12px -3px rgba(79,70,229,.4); }
	.plrf-btn-primary:hover { background: linear-gradient(135deg,#4338ca,#3730a3); transform: translateY(-1px); color: #fff !important; }
	.plrf-btn-cancel { background: #fff; border: 1px solid #e2e8f0; color: #475569; }
	.plrf-btn-cancel:hover { background: #f8fafc; color: #0f172a; }

	.plrf-flash:not(:empty) {
		margin-bottom: 16px; padding: 11px 16px; border-radius: 10px; font-size: 0.85rem; font-weight: 600;
	}
	@media (max-width: 768px) { .plrf-body { padding: 18px; } }
</style>

	<div class="plrf-page">

		<?php $flash = $this->session->flashdata('message'); ?>
		<div class="plrf-flash"><?= $flash ?></div>

		<div class="plrf-card">
			<div class="plrf-header">
				<div class="icon"><i class="fas fa-pen"></i></div>
				<div>
					<h5>Buat Laporan / Penilaian</h5>
					<p>Menilai kinerja / perilaku karyawan lain</p>
				</div>
			</div>

			<form method="POST" action="<?= $action ?>" class="plrf-form">
				<div class="plrf-body">

					<h6 class="plrf-section-tag"><i class="fas fa-user-check mr-1"></i> Karyawan yang Dinilai</h6>
					<div class="form-group">
						<label>Pilih Karyawan <span class="text-danger">*</span></label>
						<select name="id_terlapor" class="form-control" required>
							<option value="">— Pilih karyawan —</option>
							<?php foreach ($pegawai_list as $p):
								$selected = ($set_laporan_id && $set_laporan_id === (int) $p->id_pegawai) ? 'selected' : '';
								$disabled = ($is_pegawai_login && (int) $p->id_pegawai === $my_pegawai_id) ? 'disabled' : '';
								?>
								<option value="<?= (int) $p->id_pegawai ?>" <?= $selected ?> <?= $disabled ?>>
									<?= html_escape($p->nama) ?> — <?= html_escape($p->jabatan ?: '-') ?> (<?= html_escape($p->unit_kerja ?: '-') ?>)
								</option>
							<?php endforeach; ?>
						</select>
						<?= form_error('id_terlapor') ?>
					</div>

					<h6 class="plrf-section-tag" style="margin-top:20px;"><i class="fas fa-star mr-1"></i> Penilaian Bintang</h6>
					<div class="plrf-stars">
						<input type="radio" id="b5" name="bintang" value="5" checked>
						<label for="b5" title="5 bintang"><i class="fas fa-star"></i></label>
						<input type="radio" id="b4" name="bintang" value="4">
						<label for="b4" title="4 bintang"><i class="fas fa-star"></i></label>
						<input type="radio" id="b3" name="bintang" value="3">
						<label for="b3" title="3 bintang"><i class="fas fa-star"></i></label>
						<input type="radio" id="b2" name="bintang" value="2">
						<label for="b2" title="2 bintang"><i class="fas fa-star"></i></label>
						<input type="radio" id="b1" name="bintang" value="1">
						<label for="b1" title="1 bintang"><i class="fas fa-star"></i></label>
					</div>
					<div class="plrf-note">1 = sangat kurang, 5 = sangat baik</div>
					<?= form_error('bintang') ?>

					<h6 class="plrf-section-tag" style="margin-top:20px;"><i class="fas fa-comment-dots mr-1"></i> Alasan / Pengaduan</h6>
					<div class="form-group">
						<textarea name="alasan" class="form-control" rows="5" placeholder="Tuliskan alasan penilaian atau pengaduan dengan jelas..." required></textarea>
						<?= form_error('alasan') ?>
					</div>

					<div class="d-flex align-items-center" style="gap:10px; margin-top:22px; padding-top:20px; border-top:1px solid #f1f5f9;">
						<button type="submit" class="plrf-btn plrf-btn-primary"><i class="fas fa-paper-plane mr-1"></i> Kirim Laporan</button>
						<a href="<?= site_url('pelaporan') ?>" class="plrf-btn plrf-btn-cancel"><i class="fas fa-arrow-left mr-1"></i> Batal</a>
					</div>

				</div>
			</form>
		</div>
	</div>

</div>