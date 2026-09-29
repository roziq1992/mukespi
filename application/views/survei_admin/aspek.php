<div class="container-fluid sk-wrap">

<style>
	.sk-wrap {
		--sk-ink: #0f172a; --sk-ink2: #334155; --sk-muted: #64748b;
		--sk-line: #e2e8f0; --sk-blue: #2563eb; --sk-blue-dark: #1b3a5c;
		--sk-good: #10b981; --sk-warn: #f59e0b; --sk-bad: #ef4444;
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

	.sk-panel { background: #fff; border: 1px solid var(--sk-line); border-radius: 12px; box-shadow: 0 2px 8px rgba(15,23,42,.04); margin-top: 16px; }
	.sk-panel-head { padding: 14px 18px; border-bottom: 1px solid var(--sk-line); display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; }
	.sk-panel-head h3 { margin: 0; font-size: .88rem; font-weight: 800; color: var(--sk-blue-dark); }
	.sk-panel-head span { font-size: .74rem; color: var(--sk-muted); }
	.sk-panel-body { padding: 18px; }
	.sk-table-wrap { overflow-x: auto; }

	.sk-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: .8rem; }
	.sk-table thead th {
		background: #f8fafc; color: var(--sk-muted);
		font-size: .66rem; text-transform: uppercase; letter-spacing: .05em; font-weight: 800;
		padding: 11px 13px; text-align: left; border-bottom: 1px solid var(--sk-line); white-space: nowrap;
	}
	.sk-table tbody td { padding: 12px 13px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
	.sk-table tbody tr:hover { background: #f8fafc; }
	.sk-table tbody tr.nonaktif td { opacity: .55; }

	.sk-aspek-cell { display: flex; align-items: center; gap: 9px; }
	.sk-aspek-ico { width: 30px; height: 30px; border-radius: 9px; background: #eff6ff; color: var(--sk-blue); display: inline-flex; align-items: center; justify-content: center; font-size: .78rem; flex-shrink: 0; }
	.sk-aspek-nm { font-weight: 700; color: var(--sk-ink); }
	.sk-aspek-ds { font-size: .7rem; color: var(--sk-muted); }
	.sk-kode { font-size: .7rem; font-weight: 800; color: var(--sk-blue); font-family: ui-monospace, monospace; }

	.sk-badge { display: inline-block; padding: 4px 11px; border-radius: 20px; font-size: .7rem; font-weight: 800; white-space: nowrap; }
	.sk-badge.aktif { background: #ecfdf5; color: #047857; }
	.sk-badge.off { background: #f1f5f9; color: #475569; }

	.sk-act { display: flex; gap: 4px; justify-content: flex-end; }
	.sk-act-btn {
		width: 30px; height: 30px; border-radius: 8px; border: 1px solid var(--sk-line);
		background: #fff; color: var(--sk-ink2);
		display: inline-flex; align-items: center; justify-content: center; font-size: .75rem;
		text-decoration: none; transition: all .12s; padding: 0; cursor: pointer;
	}
	.sk-act-btn:hover { background: #eff6ff; color: var(--sk-blue); border-color: #bfdbfe; }
	.sk-act-btn.danger:hover { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
	.sk-act-btn.ok:hover { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }

	.sk-form label { display: block; font-size: .74rem; font-weight: 800; color: #52657d; margin-bottom: 6px; }
	.sk-form input[type="text"], .sk-form input[type="number"], .sk-form textarea, .sk-form select {
		width: 100%; border: 1px solid var(--sk-line); border-radius: 10px;
		padding: 9px 12px; font-size: .82rem; font-family: inherit; background: #f8fafc; color: var(--sk-ink2);
	}
	.sk-form textarea { min-height: 70px; resize: vertical; }
	.sk-form input:focus, .sk-form textarea:focus { outline: none; border-color: var(--sk-blue); box-shadow: 0 0 0 3px rgba(37,99,235,.12); background: #fff; }
	.sk-form-grid { display: grid; grid-template-columns: 1.4fr 1.2fr .6fr .6fr; gap: 12px; }
	.sk-form-grid .full { grid-column: 1 / -1; }
	.sk-form-actions { margin-top: 14px; display: flex; gap: 8px; align-items: center; }
	.sk-submit {
		background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; border: none;
		border-radius: 10px; padding: 10px 20px; font-size: .82rem; font-weight: 700;
		font-family: inherit; cursor: pointer;
	}
	.sk-submit:hover { background: #1e40af; }
	.sk-cancel {
		background: #f1f5f9; color: #52657d; border: none;
		border-radius: 10px; padding: 10px 16px; font-size: .82rem; font-weight: 700;
		font-family: inherit; cursor: pointer;
	}
	.sk-cancel:hover { background: #e2e8f0; }
	.sk-check { display: flex; align-items: center; gap: 7px; font-size: .8rem; color: var(--sk-ink2); margin-top: 20px; }
	.sk-check input { width: 16px; height: 16px; }

	.sk-hint { font-size: .72rem; color: var(--sk-muted); margin-top: 6px; }
	.sk-flash { margin-top: 16px; }
	.sk-empty { text-align: center; padding: 40px 16px; color: var(--sk-muted); font-size: .84rem; }
	.sk-empty i { font-size: 2rem; color: #cbd5e1; display: block; margin-bottom: 10px; }

	@media (max-width: 992px) { .sk-form-grid { grid-template-columns: 1fr 1fr; } }
	@media (max-width: 576px) { .sk-form-grid { grid-template-columns: 1fr; } }
</style>

<div class="sk-header">
	<div>
		<h2><i class="fas fa-sliders-h"></i> Master Aspek Penilaian</h2>
		<p>Atur aspek yang dinilai pasien, urutan tampil, dan ikon pada formulir</p>
	</div>
	<div class="sk-header-actions">
		<a class="sk-btn" href="<?= site_url('survei_admin'); ?>"><i class="fas fa-chart-line"></i> Dashboard</a>
		<a class="sk-btn" href="<?= site_url('survei_admin/data'); ?>"><i class="fas fa-list"></i> Data Survei</a>
		<a class="sk-btn solid" href="<?= site_url('survei'); ?>" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> Formulir Pasien</a>
	</div>
</div>

<?php if ($this->session->flashdata('message')): ?>
	<div class="sk-flash"><?= $this->session->flashdata('message'); ?></div>
<?php endif; ?>

<!-- ============ FORM TAMBAH / UBAH ============ -->
<div class="sk-panel">
	<div class="sk-panel-head">
		<h3 id="sk-form-title"><i class="fas fa-plus-circle"></i> Tambah Aspek Penilaian</h3>
		<span>Aspek aktif akan tampil pada formulir pasien</span>
	</div>
	<div class="sk-panel-body">
		<form method="POST" action="<?= site_url('survei_admin/aspek/simpan'); ?>" class="sk-form" id="sk-form-aspek">
			<input type="hidden" name="id" id="sk-id" value="0">
			<div class="sk-form-grid">
				<div>
					<label for="sk-kode">Kode Aspek</label>
					<input type="text" id="sk-kode" name="kode" maxlength="30" required placeholder="mis. dokter">
					<div class="sk-hint">Huruf/angka/strip tanpa spasi, mis. <code>laboratorium</code></div>
				</div>
				<div>
					<label for="sk-nama">Nama Aspek</label>
					<input type="text" id="sk-nama" name="nama_aspek" maxlength="150" required placeholder="mis. Layanan Dokter">
				</div>
				<div>
					<label for="sk-urutan">Urutan</label>
					<input type="number" id="sk-urutan" name="urutan" min="1" max="99" required value="1">
				</div>
				<div>
					<label for="sk-bobot">Bobot</label>
					<input type="number" id="sk-bobot" name="bobot" min="1" max="5" required value="1">
				</div>
				<div class="full">
					<label for="sk-deskripsi">Deskripsi Singkat <span style="font-weight:600;color:var(--sk-muted)">(opsional, tampil sebagai petunjuk di formulir)</span></label>
					<textarea id="sk-deskripsi" name="deskripsi" maxlength="255" placeholder="mis. Ketepatan waktu dan sikap tenaga medis dalam melayani"></textarea>
				</div>
				<div class="full">
					<label for="sk-icon">Ikon Font Awesome</label>
					<input type="text" id="sk-icon" name="icon" maxlength="100" value="fas fa-star" placeholder="mis. fas fa-user-md">
					<div class="sk-hint">Gunakan kelas Font Awesome 5, mis. <code>fas fa-user-md</code>, <code>fas fa-pills</code>, <code>fas fa-flask</code>.</div>
				</div>
			</div>
			<label class="sk-check" for="sk-active">
				<input type="checkbox" id="sk-active" name="is_active" value="1" checked> Aktif (tampilkan di formulir pasien)
			</label>
			<div class="sk-form-actions">
				<button type="submit" class="sk-submit"><i class="fas fa-save"></i> <span id="sk-submit-text">Simpan Aspek</span></button>
				<button type="button" class="sk-cancel" id="sk-cancel" style="display:none">Batal Ubah</button>
			</div>
		</form>
	</div>
</div>

<!-- ============ DAFTAR ASPEK ============ -->
<div class="sk-panel">
	<div class="sk-panel-head">
		<h3>Daftar Aspek</h3>
		<span><?= count($aspek); ?> aspek terdaftar</span>
	</div>
	<div class="sk-table-wrap">
		<table class="sk-table">
			<thead>
				<tr>
					<th style="width:60px">Urutan</th>
					<th>Aspek</th>
					<th style="width:130px">Kode</th>
					<th style="width:80px">Bobot</th>
					<th style="width:110px">Status</th>
					<th style="width:130px" class="text-right">Aksi</th>
				</tr>
			</thead>
			<tbody>
			<?php if (empty($aspek)): ?>
				<tr><td colspan="6">
					<div class="sk-empty">
						<i class="fas fa-sliders-h"></i>
						Belum ada aspek penilaian. Tambahkan minimal satu aspek melalui formulir di atas.
					</div>
				</td></tr>
			<?php else: foreach ($aspek as $a): ?>
				<tr class="<?= (int) $a->is_active === 1 ? '' : 'nonaktif'; ?>">
					<td style="font-weight:800;color:var(--sk-blue-dark)"><?= (int) $a->urutan; ?></td>
					<td>
						<div class="sk-aspek-cell">
							<span class="sk-aspek-ico"><i class="<?= html_escape($a->icon); ?>"></i></span>
							<div>
								<div class="sk-aspek-nm"><?= html_escape($a->nama_aspek); ?></div>
								<?php if (!empty($a->deskripsi)): ?>
									<div class="sk-aspek-ds"><?= html_escape($a->deskripsi); ?></div>
								<?php endif; ?>
							</div>
						</div>
					</td>
					<td><span class="sk-kode"><?= html_escape($a->kode); ?></span></td>
					<td><?= (int) $a->bobot; ?></td>
					<td>
						<?php if ((int) $a->is_active === 1): ?>
							<span class="sk-badge aktif"><i class="fas fa-check"></i> Aktif</span>
						<?php else: ?>
							<span class="sk-badge off"><i class="fas fa-ban"></i> Nonaktif</span>
						<?php endif; ?>
					</td>
					<td>
						<div class="sk-act">
							<button type="button" class="sk-act-btn" title="Ubah"
								onclick='editAspek(<?= json_encode(array(
									'id'          => (int) $a->id,
									'kode'        => $a->kode,
									'nama_aspek'  => $a->nama_aspek,
									'deskripsi'   => (string) $a->deskripsi,
									'icon'        => $a->icon,
									'urutan'      => (int) $a->urutan,
									'bobot'       => (int) $a->bobot,
									'is_active'   => (int) $a->is_active,
								)); ?>)'>
								<i class="fas fa-pen"></i>
							</button>
							<a class="sk-act-btn ok" title="<?= (int) $a->is_active === 1 ? 'Nonaktifkan' : 'Aktifkan' ?>"
								href="<?= site_url('survei_admin/aspek/toggle/' . (int) $a->id); ?>">
								<i class="fas fa-<?= (int) $a->is_active === 1 ? 'toggle-on' : 'toggle-off'; ?>"></i>
							</a>
							<a class="sk-act-btn danger" title="Hapus"
								href="<?= site_url('survei_admin/aspek/hapus/' . (int) $a->id); ?>"
								onclick="return confirm('Hapus aspek &quot;<?= html_escape($a->nama_aspek); ?>&quot;? Aspek yang sudah dipakai pada jawaban survei tidak bisa dihapus.')">
								<i class="fas fa-trash"></i>
							</a>
						</div>
					</td>
				</tr>
			<?php endforeach; endif; ?>
			</tbody>
		</table>
	</div>
</div>

</div>

<script>
function editAspek(a) {
	document.getElementById('sk-id').value = a.id;
	document.getElementById('sk-kode').value = a.kode;
	document.getElementById('sk-nama').value = a.nama_aspek;
	document.getElementById('sk-deskripsi').value = a.deskripsi;
	document.getElementById('sk-icon').value = a.icon;
	document.getElementById('sk-urutan').value = a.urutan;
	document.getElementById('sk-bobot').value = a.bobot;
	document.getElementById('sk-active').checked = (a.is_active === 1);
	document.getElementById('sk-form-title').innerHTML = '<i class="fas fa-pen"></i> Ubah Aspek Penilaian';
	document.getElementById('sk-submit-text').textContent = 'Perbarui Aspek';
	document.getElementById('sk-cancel').style.display = 'inline-block';
	window.scrollTo({ top: document.getElementById('sk-form-aspek').offsetTop - 90, behavior: 'smooth' });
}

document.getElementById('sk-cancel').addEventListener('click', function () {
	this.closest('form').reset();
	document.getElementById('sk-id').value = 0;
	document.getElementById('sk-icon').value = 'fas fa-star';
	document.getElementById('sk-urutan').value = 1;
	document.getElementById('sk-bobot').value = 1;
	document.getElementById('sk-active').checked = true;
	document.getElementById('sk-form-title').innerHTML = '<i class="fas fa-plus-circle"></i> Tambah Aspek Penilaian';
	document.getElementById('sk-submit-text').textContent = 'Simpan Aspek';
	this.style.display = 'none';
});
</script>
