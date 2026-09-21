<div class="container-fluid p-0">

<style>
	.pgw-card {
		border: 1px solid #e2e8f0;
		border-radius: 16px;
		box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06);
		overflow: hidden;
	}
	.pgw-card-header {
		background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
		padding: 22px 28px;
		color: #fff;
		display: flex;
		align-items: center;
		gap: 14px;
	}
	.pgw-card-header .icon {
		width: 42px;
		height: 42px;
		border-radius: 10px;
		background: rgba(255,255,255,0.12);
		border: 1px solid rgba(255,255,255,0.18);
		display: flex;
		align-items: center;
		justify-content: center;
		color: #a5b4fc;
		font-size: 1rem;
	}
	.pgw-card-header h5 { margin: 0; font-size: 1.1rem; font-weight: 700; }
	.pgw-card-header p { margin: 2px 0 0; font-size: 0.72rem; font-family: 'JetBrains Mono', ui-monospace, monospace; color: #a5b4fc; letter-spacing: 0.08em; text-transform: uppercase; font-weight: 600; }
	.pgw-card-body { padding: 28px; }
	.pgw-section-tag {
		font-size: 0.72rem;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.08em;
		color: #64748b;
		margin: 0 0 14px;
		border-bottom: 1px solid #f1f5f9;
		padding-bottom: 8px;
	}
	.pgw-form label { font-size: 0.8rem; font-weight: 600; color: #334155; }
	.pgw-form .form-control {
		border-color: #e2e8f0;
		border-radius: 8px;
		padding: 9px 12px;
		font-size: 0.875rem;
	}
	.pgw-form .form-control:focus {
		border-color: #4f46e5;
		box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
	}
	.pgw-form .text-danger { font-size: 0.75rem; font-weight: 600; }
	.pgw-btn-primary {
		background: linear-gradient(135deg, #4f46e5, #4338ca);
		border: none;
		color: #fff !important;
		font-weight: 700;
		border-radius: 9px;
		padding: 10px 22px;
		transition: all .15s;
		box-shadow: 0 4px 12px -3px rgba(79, 70, 229, 0.4);
	}
	.pgw-btn-primary:hover { background: linear-gradient(135deg, #4338ca, #3730a3); transform: translateY(-1px); }
	.pgw-btn-cancel {
		background: #fff;
		border: 1px solid #e2e8f0;
		color: #475569;
		font-weight: 600;
		border-radius: 9px;
		padding: 10px 22px;
	}
	.pgw-btn-cancel:hover { background: #f8fafc; color: #0f172a; }
</style>

	<div class="pgw-card">
		<div class="pgw-card-header">
			<div class="icon"><i class="fas fa-user-edit"></i></div>
			<div>
				<h5><?= $button ?> Data Pegawai</h5>
				<p>Formulir input / perbarui pegawai</p>
			</div>
		</div>

		<form method="POST" action="<?= $action ?>" class="pgw-form">
			<div class="pgw-card-body">

				<h6 class="pgw-section-tag"><i class="fas fa-id-card mr-1"></i> Identitas</h6>
				<div class="row">
					<div class="col-md-3 mb-3">
						<label for="nik">NIK</label>
						<input type="text" class="form-control" id="nik" name="nik" value="<?= set_value('nik', isset($nik) ? $nik : '') ?>" placeholder="Nomor Induk Kependudukan" <?= (!empty($is_pegawai_view)) ? 'readonly' : '' ?>>
						<?= form_error('nik') ?>
					</div>
					<div class="col-md-3 mb-3">
						<label for="nip">NIP</label>
						<input type="text" class="form-control" id="nip" name="nip" value="<?= set_value('nip', isset($nip) ? $nip : '') ?>" placeholder="Nomor Induk Pegawai" <?= (!empty($is_pegawai_view)) ? 'readonly' : '' ?>>
						<?= form_error('nip') ?>
					</div>
					<div class="col-md-6 mb-3">
						<label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="nama" name="nama" value="<?= set_value('nama', isset($nama) ? $nama : '') ?>" placeholder="Nama pegawai" required>
						<?= form_error('nama') ?>
					</div>
				</div>

				<div class="row">
					<div class="col-md-3 mb-3">
						<label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
						<select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
							<option value="Laki-laki" <?= set_select('jenis_kelamin', 'Laki-laki', (isset($jenis_kelamin) && $jenis_kelamin === 'Laki-laki')) ?>>Laki-laki</option>
							<option value="Perempuan" <?= set_select('jenis_kelamin', 'Perempuan', (isset($jenis_kelamin) && $jenis_kelamin === 'Perempuan')) ?>>Perempuan</option>
						</select>
					</div>
					<div class="col-md-3 mb-3">
						<label for="tempat_lahir">Tempat Lahir</label>
						<input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?= set_value('tempat_lahir', isset($tempat_lahir) ? $tempat_lahir : '') ?>" placeholder="Kota lahir">
					</div>
					<div class="col-md-3 mb-3">
						<label for="tanggal_lahir">Tanggal Lahir</label>
						<input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= set_value('tanggal_lahir', isset($tanggal_lahir) ? $tanggal_lahir : '') ?>">
					</div>
					<div class="col-md-3 mb-3">
						<label for="no_hp">No. HP</label>
						<input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= set_value('no_hp', isset($no_hp) ? $no_hp : '') ?>" placeholder="08xxxxxxxxxx">
					</div>
				</div>

				<div class="row">
					<div class="col-md-6 mb-3">
						<label for="email">Email</label>
						<input type="email" class="form-control" id="email" name="email" value="<?= set_value('email', isset($email) ? $email : '') ?>" placeholder="nama@rsairlangga.co.id">
						<?= form_error('email') ?>
					</div>
					<div class="col-md-6 mb-3">
						<label for="alamat">Alamat</label>
						<input type="text" class="form-control" id="alamat" name="alamat" value="<?= set_value('alamat', isset($alamat) ? $alamat : '') ?>" placeholder="Alamat lengkap">
					</div>
				</div>

				<div class="row">
					<div class="col-md-4 mb-3">
						<label for="no_npwp">No. NPWP</label>
						<input type="text" class="form-control" id="no_npwp" name="no_npwp" value="<?= set_value('no_npwp', isset($no_npwp) ? $no_npwp : '') ?>" placeholder="00.000.000.0-000.000">
					</div>
				</div>

				<h6 class="pgw-section-tag" style="margin-top:18px;"><i class="fas fa-users mr-1"></i> Data Keluarga</h6>
				<div class="row">
					<div class="col-md-4 mb-3">
						<label for="nama_keluarga">Nama Suami / Istri / Orang Tua</label>
						<input type="text" class="form-control" id="nama_keluarga" name="nama_keluarga" value="<?= set_value('nama_keluarga', isset($nama_keluarga) ? $nama_keluarga : '') ?>" placeholder="Nama pasangan / orang tua">
					</div>
					<div class="col-md-4 mb-3">
						<label for="no_hp_keluarga">No. HP Suami / Istri / Orang Tua</label>
						<input type="text" class="form-control" id="no_hp_keluarga" name="no_hp_keluarga" value="<?= set_value('no_hp_keluarga', isset($no_hp_keluarga) ? $no_hp_keluarga : '') ?>" placeholder="08xxxxxxxxxx">
					</div>
					<div class="col-md-4 mb-3">
						<label for="nama_anak">Nama Anak</label>
						<textarea class="form-control" id="nama_anak" name="nama_anak" rows="3" placeholder="Satu nama per baris"><?= set_value('nama_anak', isset($nama_anak) ? $nama_anak : '') ?></textarea>
					</div>
				</div>

				<h6 class="pgw-section-tag" style="margin-top:18px;"><i class="fas fa-graduation-cap mr-1"></i> Pendidikan &amp; Pengalaman</h6>
				<div class="row">
					<div class="col-md-6 mb-3">
						<label for="kualifikasi_pendidikan">Kualifikasi Pendidikan</label>
						<textarea class="form-control" id="kualifikasi_pendidikan" name="kualifikasi_pendidikan" rows="3" placeholder="Riwayat pendidikan"><?= set_value('kualifikasi_pendidikan', isset($kualifikasi_pendidikan) ? $kualifikasi_pendidikan : '') ?></textarea>
					</div>
					<div class="col-md-6 mb-3">
						<label for="pengalaman_kerja">Pengalaman Kerja</label>
						<textarea class="form-control" id="pengalaman_kerja" name="pengalaman_kerja" rows="3" placeholder="Riwayat pekerjaan"><?= set_value('pengalaman_kerja', isset($pengalaman_kerja) ? $pengalaman_kerja : '') ?></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3">
						<label for="pelatihan">Pelatihan</label>
						<textarea class="form-control" id="pelatihan" name="pelatihan" rows="3" placeholder="Pelatihan yang pernah diikuti"><?= set_value('pelatihan', isset($pelatihan) ? $pelatihan : '') ?></textarea>
					</div>
					<div class="col-md-6 mb-3">
						<label for="organisasi">Organisasi</label>
						<textarea class="form-control" id="organisasi" name="organisasi" rows="3" placeholder="Pengalaman organisasi"><?= set_value('organisasi', isset($organisasi) ? $organisasi : '') ?></textarea>
					</div>
				</div>

				<h6 class="pgw-section-tag" style="margin-top:18px;"><i class="fas fa-briefcase mr-1"></i> Kepegawaian</h6>
				<div class="row">
					<div class="col-md-3 mb-3">
						<label for="jabatan">Jabatan <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="jabatan" name="jabatan" value="<?= set_value('jabatan', isset($jabatan) ? $jabatan : '') ?>" placeholder="Contoh: Perawat, Bidan, Staf TU" <?= (!empty($is_pegawai_view)) ? 'readonly' : 'required' ?>>
						<?= form_error('jabatan') ?>
					</div>
					<div class="col-md-3 mb-3">
						<label for="unit_kerja">Unit Kerja <span class="text-danger">*</span></label>
						<select class="form-control" id="unit_kerja" name="unit_kerja" <?= (!empty($is_pegawai_view)) ? 'disabled' : 'required' ?>>
							<option value="">— Pilih Unit —</option>
							<?php foreach ($units as $u): ?>
								<option value="<?= html_escape($u->nm_unit) ?>" <?= set_select('unit_kerja', $u->nm_unit, (isset($unit_kerja) && $unit_kerja === $u->nm_unit)) ?>>
									<?= html_escape($u->nm_unit) ?>
								</option>
							<?php endforeach; ?>
						</select>
						<?php if (!empty($is_pegawai_view) && !empty($unit_kerja)): ?>
							<input type="hidden" name="unit_kerja_pegawai_tampil" value="<?= html_escape($unit_kerja) ?>">
						<?php endif; ?>
						<?= form_error('unit_kerja') ?>
					</div>
					<div class="col-md-3 mb-3">
						<label for="status_kepegawaian">Status Kepegawaian</label>
						<input type="text" class="form-control" id="status_kepegawaian" name="status_kepegawaian" list="status_kepegawaian_list" value="<?= set_value('status_kepegawaian', isset($status_kepegawaian) ? $status_kepegawaian : '') ?>" placeholder="Tetap / Kontrak / OJT" <?= (!empty($is_pegawai_view)) ? 'readonly' : '' ?>>
						<datalist id="status_kepegawaian_list">
							<option value="Tetap"></option>
							<option value="Kontrak"></option>
							<option value="OJT"></option>
							<option value="Orientasi"></option>
						</datalist>
					</div>
					<div class="col-md-3 mb-3">
						<label for="tanggal_masuk">Tanggal Masuk <span class="text-danger">*</span></label>
						<input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= set_value('tanggal_masuk', isset($tanggal_masuk) ? $tanggal_masuk : '') ?>" <?= (!empty($is_pegawai_view)) ? 'readonly' : 'required' ?>>
						<?= form_error('tanggal_masuk') ?>
					</div>
				</div>

				<h6 class="pgw-section-tag" style="margin-top:18px;"><i class="fas fa-lock mr-1"></i> Login Sistem (username: NIK)</h6>
				<div class="row">
					<div class="col-md-4 mb-3">
						<label for="password">Password Login <span class="text-muted" style="font-weight:400;">(min. 5 karakter)</span></label>
						<input type="password" class="form-control" id="password" name="password" value="<?= set_value('password', isset($password) ? $password : '') ?>" placeholder="<?= !empty($id_pegawai) ? 'Kosongkan jika tidak diganti' : 'Password untuk login via NIK' ?>" autocomplete="new-password">
						<?= form_error('password') ?>
					</div>
					<?php if (!empty($id_pegawai)): ?>
					<div class="col-md-8 mb-3 d-flex align-items-end">
						<small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Biarkan kosong untuk mempertahankan password lama.</small>
					</div>
					<?php endif; ?>
				</div>

				<input type="hidden" name="id_pegawai" value="<?= isset($id_pegawai) ? $id_pegawai : '' ?>">

				<div class="d-flex align-items-center gap-2" style="gap:10px; margin-top:22px; padding-top:20px; border-top:1px solid #f1f5f9;">
					<button type="submit" class="btn pgw-btn-primary"><i class="fas fa-save mr-1"></i> <?= $button ?></button>
					<a href="<?= site_url('pegawai') ?>" class="btn pgw-btn-cancel"><i class="fas fa-arrow-left mr-1"></i> Batal</a>
				</div>

			</div>
		</form>
	</div>

</div>