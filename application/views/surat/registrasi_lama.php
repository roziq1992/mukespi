<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header"><h5 class="m-0">Registrasi Dokumen Lama (Sudah Ditandatangani)</h5></div>
        <div class="card-body">
            <p class="text-muted">Registrasi dokumen PDF yang <strong>sudah ditandatangani dan sudah bernomor</strong>. Pada form ini, nomor surat <strong>diinput manual</strong> sesuai yang tertera pada dokumen, sehingga langsung terdaftar dan sudah berkode tanpa perlu diproses di menu Proses Nomor.</p>
            <div class="alert alert-info">
                <strong>Catatan:</strong> Sistem <u>tidak</u> menggenerate nomor surat. Nomor surat wajib diisi manual pada kolom <strong>Nomor Surat</strong> di bawah ini.
            </div>
            <?php $pesan = $this->session->flashdata('message'); ?>
            <?php if ($pesan): ?><div class="alert alert-warning"><?php echo html_escape(strip_tags($pesan)); ?></div><?php endif; ?>
            <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
            <form method="post" enctype="multipart/form-data" action="<?php echo site_url('surat_sekretaris/registrasi_lama_simpan'); ?>">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Jenis Surat <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-control" required>
                            <option value="">Pilih Jenis</option>
                            <option value="internal" <?php echo set_select('jenis','internal'); ?>>Internal</option>
                            <option value="eksternal" <?php echo set_select('jenis','eksternal'); ?>>Eksternal</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Tanggal Surat/TTD <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pengajuan" class="form-control" value="<?php echo set_value('tanggal_pengajuan'); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Nomor Surat <span class="text-danger">*</span></label>
                    <input type="text" name="no_surat" class="form-control" value="<?php echo set_value('no_surat'); ?>" placeholder="Contoh: 001/RS.A/INT/X/2026" required autocomplete="off">
                    <small class="form-text text-muted">Diisi manual sesuai nomor surat yang tertera pada dokumen yang sudah ditandatangani.</small>
                </div>
                <div class="form-group">
                    <label>Perihal <span class="text-danger">*</span></label>
                    <input name="perihal" class="form-control" value="<?php echo set_value('perihal'); ?>" required>
                </div>
                <div class="form-group">
                    <label>Tujuan <span class="text-danger">*</span></label>
                    <input name="tujuan" class="form-control" value="<?php echo set_value('tujuan'); ?>" required>
                </div>
                <div class="form-group">
                    <label>Dokumen yang sudah ditandatangani (PDF) <span class="text-danger">*</span></label>
                    <input type="file" name="file_ttd" class="form-control-file" accept=".pdf" required>
                    <small class="form-text text-muted">Wajib berformat PDF. File ini akan langsung disimpan sebagai dokumen final & sudah berkode.</small>
                </div>
                <div class="form-group">
                    <label>Lampiran (Opsional)</label>
                    <input type="file" name="lampiran[]" class="form-control-file" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3"><?php echo set_value('keterangan'); ?></textarea>
                </div>
                <button class="btn btn-primary">Upload & Registrasi Langsung Berkode</button>
                <a href="<?php echo site_url('surat_sekretaris'); ?>" class="btn btn-light">Batal</a>
            </form>
        </div>
    </div>
</div>
