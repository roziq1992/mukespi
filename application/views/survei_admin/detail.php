<div class="container-fluid sk-wrap">

<style>
	.sk-wrap {
		--sk-ink: #0f172a; --sk-ink2: #334155; --sk-muted: #64748b;
		--sk-line: #e2e8f0; --sk-blue: #2563eb; --sk-blue-dark: #1b3a5c;
		--sk-good: #10b981; --sk-warn: #f59e0b; --sk-bad: #ef4444; --sk-gold: #f59e0b;
		color: var(--sk-ink); padding-bottom: 10px;
	}
	.sk-wrap * { box-sizing: border-box; }

	.sk-header {
		background: linear-gradient(135deg, #102a43 0%, #1e4e79 62%, #2563eb 100%);
		color: #fff; border-radius: 14px; padding: 20px 24px;
		display: flex; justify-content: space-between; align-items: center;
		flex-wrap: wrap; gap: 14px; box-shadow: 0 6px 20px rgba(16,42,67,.18);
	}
	.sk-header h2 { margin: 0; font-size: 1.1rem; font-weight: 800; }
	.sk-header p { margin: 4px 0 0; font-size: .78rem; color: #cfe3f7; }
	.sk-header-actions { display: flex; gap: 8px; flex-wrap: wrap; }
	.sk-btn {
		display: inline-flex; align-items: center; gap: 6px;
		background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.32);
		color: #fff; border-radius: 9px; padding: 8px 14px;
		font-size: .78rem; font-weight: 700; text-decoration: none; white-space: nowrap;
	}
	.sk-btn:hover { background: rgba(255,255,255,.26); color: #fff; text-decoration: none; }
	.sk-btn.solid { background: #fff; color: var(--sk-blue-dark); border-color: #fff; }
	.sk-btn.solid:hover { background: #eaf2fb; color: var(--sk-blue-dark); }

	.sk-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 16px; margin-top: 16px; }
	@media (max-width: 992px) { .sk-grid { grid-template-columns: 1fr; } }

	.sk-panel { background: #fff; border: 1px solid var(--sk-line); border-radius: 12px; box-shadow: 0 2px 8px rgba(15,23,42,.04); }
	.sk-panel-head { padding: 14px 18px; border-bottom: 1px solid var(--sk-line); display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; }
	.sk-panel-head h3 { margin: 0; font-size: .88rem; font-weight: 800; color: var(--sk-blue-dark); }
	.sk-panel-head span { font-size: .74rem; color: var(--sk-muted); }
	.sk-panel-body { padding: 18px; }

	/* ringkasan nilai */
	.sk-score-box { display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
	.sk-score-num { font-size: 2.9rem; font-weight: 800; line-height: 1; color: var(--sk-blue-dark); }
	.sk-score-num small { font-size: 1rem; color: var(--sk-muted); font-weight: 700; }
	.sk-score-stars { font-size: 1.35rem; color: var(--sk-gold); letter-spacing: 2px; margin: 6px 0; }
	.sk-badge { display: inline-block; padding: 5px 13px; border-radius: 20px; font-size: .74rem; font-weight: 800; }
	.sk-badge.sb { background: #ecfdf5; color: #047857; }
	.sk-badge.b  { background: #eff6ff; color: #1d4ed8; }
	.sk-badge.c  { background: #fffbeb; color: #b45309; }
	.sk-badge.k  { background: #fef2f2; color: #b91c1c; }
	.sk-badge.netral { background: #f1f5f9; color: #475569; }
	.sk-badge.kritik { background: #fee2e2; color: #b91c1c; }

	/* info list */
	.sk-info { width: 100%; border-collapse: collapse; font-size: .8rem; }
	.sk-info td { padding: 8px 0; border-bottom: 1px dashed #eef2f7; vertical-align: top; }
	.sk-info tr:last-child td { border-bottom: none; }
	.sk-info td:first-child { color: var(--sk-muted); width: 145px; font-weight: 600; }
	.sk-info td:last-child { color: var(--sk-ink2); font-weight: 700; }

	/* daftar aspek */
	.sk-aspek-list { display: flex; flex-direction: column; gap: 9px; }
	.sk-aspek {
		display: flex; align-items: center; gap: 12px;
		border: 1px solid var(--sk-line); border-radius: 11px; padding: 12px 14px;
		background: #fff; flex-wrap: wrap;
	}
	.sk-aspek.low { border-color: #fecaca; background: #fef7f7; }
	.sk-aspek-ico { width: 32px; height: 32px; border-radius: 9px; background: #eff6ff; color: var(--sk-blue); display: inline-flex; align-items: center; justify-content: center; font-size: .8rem; flex-shrink: 0; }
	.sk-aspek.low .sk-aspek-ico { background: #fee2e2; color: #b91c1c; }
	.sk-aspek-nm { flex: 1; min-width: 160px; }
	.sk-aspek-nm b { font-size: .84rem; color: var(--sk-ink); display: block; }
	.sk-aspek-nm span { font-size: .7rem; color: var(--sk-muted); }
	.sk-aspek-stars { font-size: 1rem; color: var(--sk-gold); letter-spacing: 1.5px; white-space: nowrap; }
	.sk-aspek-stars .off { color: #cbd5e1; }
	.sk-aspek-val { font-size: .78rem; font-weight: 800; color: var(--sk-blue-dark); min-width: 96px; text-align: right; }

	/* saran */
	.sxn-saran {
		background: #fffbeb; border-left: 4px solid var(--sk-warn);
		border-radius: 0 11px 11px 0; padding: 15px 17px;
		font-size: .84rem; color: #78350f; line-height: 1.6; white-space: pre-line;
	}
	.sxn-saran.kosong { background: #f8fafc; border-left-color: #cbd5e1; color: var(--sk-muted); font-style: italic; }

	/* history */
	.sk-history { list-style: none; padding: 0; margin: 0; }
	.sk-history li { position: relative; padding: 0 0 16px 24px; border-left: 2px solid var(--sk-line); }
	.sk-history li:last-child { border-left-color: transparent; padding-bottom: 0; }
	.sk-history li::before {
		content: ''; position: absolute; left: -7px; top: 3px;
		width: 12px; height: 12px; border-radius: 50%;
		background: var(--sk-blue); border: 2px solid #fff;
	}
	.sk-history li.selesai::before { background: var(--sk-good); }
	.sk-history .tanggal { font-size: .7rem; color: var(--sk-muted); }
	.sk-history .status { font-size: .76rem; font-weight: 800; }
	.sk-history .catatan { font-size: .8rem; color: var(--sk-ink2); margin-top: 3px; white-space: pre-line; }
	.sk-history .oleh { font-size: .7rem; color: var(--sk-muted); }

	/* form */
	.sk-form label { display: block; font-size: .74rem; font-weight: 800; color: #52657d; margin-bottom: 6px; }
	.sk-form select, .sk-form textarea {
		width: 100%; border: 1px solid var(--sk-line); border-radius: 10px;
		padding: 10px 13px; font-size: .82rem; font-family: inherit; background: #f8fafc; color: var(--sk-ink2);
	}
	.sk-form textarea { min-height: 100px; resize: vertical; }
	.sk-form select:focus, .sk-form textarea:focus { outline: none; border-color: var(--sk-blue); box-shadow: 0 0 0 3px rgba(37,99,235,.12); background: #fff; }
	.sk-form-actions { margin-top: 14px; display: flex; gap: 8px; }
	.sk-submit {
		background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; border: none;
		border-radius: 10px; padding: 10px 20px; font-size: .82rem; font-weight: 700;
		font-family: inherit; cursor: pointer;
	}
	.sk-submit:hover { background: #1e40af; }

	.sk-flash { margin-top: 16px; }
</style>

<div class="sk-header">
	<div>
		<h2><i class="fas fa-file-alt"></i> Detail Survei <span style="font-family:ui-monospace,monospace;font-size:.9rem"><?= html_escape($row->kode); ?></span></h2>
		<p>Diisi <?= date('d M Y H:i', strtotime($row->tanggal_survei)); ?> &middot; metode: <?= html_escape($row->metode); ?></p>
	</div>
	<div class="sk-header-actions">
		<a class="sk-btn" href="<?= site_url('survei_admin/data'); ?>"><i class="fas fa-arrow-left"></i> Kembali</a>
		<a class="sk-btn" href="<?= site_url('survei_admin/data?kritik=1'); ?>"><i class="fas fa-exclamation-triangle"></i> Semua Keluhan</a>
		<a class="sk-btn solid" href="<?= site_url('survei_admin/ekspor?bulan=' . (int) date('n', strtotime($row->tanggal_survei)) . '&tahun=' . (int) date('Y', strtotime($row->tanggal_survei))); ?>"><i class="fas fa-file-excel"></i> Ekspor</a>
	</div>
</div>

<?php
// flashdata harus dibaca satu kali saja: pada pembacaan pertama CI3
// menghapusnya, sehingga pembacaan kedua selalu menghasilkan NULL.
$flash = $this->session->flashdata('message');
?>
<?php if ($flash): ?>
	<div class="sk-flash"><?= $flash; ?></div>
<?php endif; ?>

<div class="sk-grid">

	<!-- ============ KOLOM KIRI ============ -->
	<div>
		<div class="sk-panel">
			<div class="sk-panel-head">
				<h3><i class="fas fa-star"></i> Ringkasan Penilaian</h3>
				<span><?= (int) $row->jumlah_aspek; ?> aspek dinilai</span>
			</div>
			<div class="sk-panel-body">
				<div class="sk-score-box">
					<div>
						<div class="sk-score-num"><?= number_format((float) $row->skor_rata, 2, ',', '.'); ?><small> / 5,00</small></div>
						<div class="sk-score-stars">
							<?php for ($i = 1; $i <= 5; $i++): ?>
								<i class="<?= $i <= round((float) $row->skor_rata) ? 'fas fa-star' : 'far fa-star'; ?>"></i>
							<?php endfor; ?>
						</div>
						<span class="sk-badge <?= $row->predikat === 'Sangat Baik' ? 'sb' : ($row->predikat === 'Baik' ? 'b' : ($row->predikat === 'Cukup' ? 'c' : 'k')); ?>"><?= html_escape($row->predikat); ?></span>
						<?php if ((int) $row->is_kritik === 1): ?>
							<span class="sk-badge kritik" style="margin-left:5px"><i class="fas fa-exclamation-triangle"></i> Ada Keluhan</span>
						<?php endif; ?>
					</div>
					<div style="margin-left:auto;text-align:right">
						<div style="font-size:1.8rem;font-weight:800;color:var(--sk-blue-dark);line-height:1">
							<?= $row->rekomendasi === NULL ? '&mdash;' : (int) $row->rekomendasi; ?>
						</div>
						<div class="sk-meta" style="font-size:.7rem;color:var(--sk-muted);margin-top:4px">Rekomendasi 0&ndash;10</div>
					</div>
				</div>
			</div>
		</div>

		<div class="sk-panel" style="margin-top:16px">
			<div class="sk-panel-head">
				<h3><i class="fas fa-list-check"></i> Nilai per Aspek Layanan</h3>
				<span>Beri bintang 1 (Sangat Buruk) &ndash; 5 (Sangat Baik)</span>
			</div>
			<div class="sk-panel-body">
				<?php if (empty($jawaban)): ?>
					<div style="text-align:center;padding:30px;color:var(--sk-muted);font-size:.82rem">Tidak ada rincian jawaban.</div>
				<?php else: ?>
					<div class="sk-aspek-list">
						<?php foreach ($jawaban as $j):
							$skor = (int) $j->skor;
							$rendah = $skor <= 2;
							?>
							<div class="sk-aspek <?= $rendah ? 'low' : ''; ?>">
								<span class="sk-aspek-ico"><i class="<?= html_escape($j->icon); ?>"></i></span>
								<div class="sk-aspek-nm">
									<b><?= html_escape($j->nama_aspek); ?></b>
									<?php if ($rendah): ?><span>Perlu perhatian</span><?php endif; ?>
								</div>
								<div class="sk-aspek-stars">
									<?php for ($i = 1; $i <= 5; $i++): ?>
										<i class="<?= $i <= $skor ? 'fas fa-star' : 'far fa-star off'; ?>"></i>
									<?php endfor; ?>
								</div>
								<div class="sk-aspek-val"><?= $skor; ?> &middot; <?= html_escape($label_skor[$skor]); ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="sk-panel" style="margin-top:16px">
			<div class="sk-panel-head">
				<h3><i class="fas fa-comment-dots"></i> Saran / Keluhan Pasien</h3>
			</div>
			<div class="sk-panel-body">
				<div class="sxn-saran <?= empty($row->saran) ? 'kosong' : ''; ?>">
					<?= empty($row->saran) ? 'Pasien tidak memberikan saran atau keluhan.' : html_escape($row->saran); ?>
				</div>
			</div>
		</div>
	</div>

	<!-- ============ KOLOM KANAN ============ -->
	<div>
		<div class="sk-panel">
			<div class="sk-panel-head">
				<h3><i class="fas fa-id-card"></i> Identitas Responden</h3>
			</div>
			<div class="sk-panel-body">
				<table class="sk-info">
					<tr><td>Status Data</td><td>
						<?php if ((int) $row->is_anonim === 1): ?>
							<span class="sk-badge kritik"><i class="fas fa-user-secret"></i> Anonim</span>
						<?php else: ?>
							<span class="sk-badge sb"><i class="fas fa-user"></i> Teridentifikasi</span>
						<?php endif; ?>
					</td></tr>
					<tr><td>Nama</td><td><?= $row->is_anonim ? '— (anonim)' : html_escape($row->nama ?: '—'); ?></td></tr>
					<tr><td>No. Rekam Medis</td><td><?= html_escape($row->no_rm ?: '—'); ?></td></tr>
					<tr><td>NIK</td><td><?= html_escape($row->nik ?: '—'); ?></td></tr>
					<tr><td>Umur / Jenis Kelamin</td><td>
						<?= $row->umur ? (int) $row->umur . ' tahun' : '—'; ?>
						<?php if ($row->jenis_kelamin): ?> &middot; <?= $row->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'; ?><?php endif; ?>
					</td></tr>
					<tr><td>Unit Dilayani</td><td><?= html_escape($row->nm_unit ?: '—'); ?></td></tr>
					<tr><td>Tanggal Kunjungan</td><td><?= date('d M Y', strtotime($row->tanggal_kunjungan)); ?></td></tr>
					<tr><td>Tanggal Survei</td><td><?= date('d M Y H:i', strtotime($row->tanggal_survei)); ?></td></tr>
					<tr><td>Metode Pengisian</td><td><?= html_escape($row->metode); ?></td></tr>
				</table>
			</div>
		</div>

		<div class="sk-panel" style="margin-top:16px">
			<div class="sk-panel-head">
				<h3><i class="fas fa-tasks"></i> Tindak Lanjut</h3>
				<span><?= count($tindak); ?> catatan</span>
			</div>
			<div class="sk-panel-body">
				<?php
				// $tindak sudah diurutkan dari yang terbaru, jadi index 0 = status terkini.
				$sekarang = empty($tindak) ? '' : $tindak[0]->status;
				$warna = $sekarang === 'Selesai'
					? 'background:#ecfdf5;border:1px solid #a7f3d0'
					: ($sekarang === 'Diproses' ? 'background:#eff6ff;border:1px solid #bfdbfe' : 'background:#f8fafc;border:1px solid #e2e8f0');
				?>
				<div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;padding:11px 14px;margin-bottom:14px;border-radius:10px;<?= $warna ?>">
					<span style="font-size:.72rem;font-weight:800;letter-spacing:.4px;text-transform:uppercase;color:#64748b">Status Tindak Lanjut</span>
					<?php if ($sekarang !== ''): ?>
						<span class="sk-badge <?= $sekarang === 'Selesai' ? 'sb' : 'b' ?>" style="font-size:.74rem"><?= html_escape($sekarang) ?></span>
						<span style="font-size:.74rem;color:#64748b">
							terakhir diubah <?= date('d M Y H:i', strtotime($tindak[0]->created_at)) ?><?= $tindak[0]->nama_user ? ' oleh ' . html_escape($tindak[0]->nama_user) : '' ?>
						</span>
					<?php else: ?>
						<span class="sk-badge netral" style="font-size:.74rem">Belum ada</span>
					<?php endif; ?>
				</div>
				<?php if (empty($tindak)): ?>
					<div style="font-size:.78rem;color:var(--sk-muted);margin-bottom:14px">
						<i class="fas fa-info-circle"></i> Belum ada tindak lanjut untuk data survei ini.
					</div>
				<?php else: ?>
					<ul class="sk-history" style="margin-bottom:16px">
						<?php foreach ($tindak as $t): ?>
							<li class="<?= $t->status === 'Selesai' ? 'selesai' : ''; ?>">
								<div class="tanggal"><?= date('d M Y H:i', strtotime($t->created_at)); ?></div>
								<div class="status">
									<span class="sk-badge <?= $t->status === 'Selesai' ? 'sb' : 'b'; ?>"><?= html_escape($t->status); ?></span>
								</div>
								<?php if (!empty($t->catatan)): ?><div class="catatan"><?= html_escape($t->catatan); ?></div><?php endif; ?>
								<div class="oleh">oleh <?= html_escape($t->nama_user ?: 'sistem'); ?></div>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<form method="POST" action="<?= site_url('survei_admin/tindak_lanjut'); ?>" class="sk-form">
					<input type="hidden" name="id_responden" value="<?= (int) $row->id; ?>">
					<div style="margin-bottom:12px">
						<label for="tl-status">Status</label>
						<select id="tl-status" name="status" required>
							<option value="Diproses" <?= ($sekarang !== 'Selesai') ? 'selected' : '' ?>>Diproses &mdash; sedang ditindaklanjuti</option>
							<option value="Selesai" <?= ($sekarang === 'Selesai') ? 'selected' : '' ?>>Selesai &mdash; sudah ada perbaikan</option>
						</select>
					</div>
					<div style="margin-bottom:6px">
						<label for="tl-catatan">Catatan Tindak Lanjut <span style="color:var(--sk-bad)">*</span></label>
						<textarea id="tl-catatan" name="catatan" maxlength="1000" placeholder="Contoh: Keluhan sudah diteruskan ke Bagian Keperawatan, akan ditindaklanjuti pada tanggal ..."></textarea>
					</div>
					<div class="sk-form-actions">
						<button type="submit" class="sk-submit"><i class="fas fa-save"></i> Simpan Tindak Lanjut</button>
					</div>
				</form>
			</div>
		</div>
	</div>

</div>

</div>
