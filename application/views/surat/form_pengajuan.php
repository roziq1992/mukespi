<style>
    .eo-form { --eo-ink:#172b4d; --eo-muted:#718096; --eo-line:#e4eaf2; --eo-blue:#2563eb; color:var(--eo-ink); }
    .eo-form-hero { background:linear-gradient(135deg,#102a43,#1f4e79 65%,#2c7a9d); color:#fff; border-radius:16px; padding:26px 30px; margin-bottom:20px; }
    .eo-form-hero .eyebrow { font-size:.68rem; text-transform:uppercase; letter-spacing:.12em; opacity:.72; font-weight:800; margin-bottom:7px; }
    .eo-form-hero h1 { font-size:1.5rem; font-weight:800; margin:0 0 7px; }
    .eo-form-hero p { font-size:.82rem; opacity:.86; margin:0; }
    .eo-form-panel { background:#fff; border:1px solid var(--eo-line); border-radius:12px; box-shadow:0 5px 20px rgba(23,43,77,.06); overflow:hidden; }
    .eo-form-head { padding:17px 22px; border-bottom:1px solid var(--eo-line); }
    .eo-form-head h2 { margin:0; font-size:1rem; font-weight:800; }
    .eo-form-head p { margin:4px 0 0; font-size:.75rem; color:var(--eo-muted); }
    .eo-form-body { padding:23px 22px; }
    .eo-form .form-group label { font-size:.76rem; font-weight:800; color:#52657d; margin-bottom:7px; }
    .eo-form .form-control { border-color:#dce4ee; border-radius:8px; font-size:.82rem; min-height:39px; }
    .eo-form textarea.form-control { min-height:96px; }
    .eo-type-options { display:flex; gap:10px; }
    .eo-type-option { flex:1; position:relative; }
    .eo-type-option input { position:absolute; opacity:0; }
    .eo-type-option label { display:flex; align-items:center; gap:10px; border:1px solid var(--eo-line); border-radius:9px; padding:12px; cursor:pointer; font-size:.8rem !important; margin:0; transition:.15s ease; }
    .eo-type-option label i { width:31px; height:31px; display:flex; align-items:center; justify-content:center; border-radius:8px; background:#eef3fa; color:#5e7188; }
    .eo-type-option input:checked + label { border-color:var(--eo-blue); background:#f2f6ff; color:#1f54b5; box-shadow:0 0 0 2px rgba(37,99,235,.1); }
    .eo-type-option input:checked + label i { background:var(--eo-blue); color:#fff; }
    .eo-upload { border:1px dashed #b9c8d8; background:#f8fafc; border-radius:9px; padding:15px; height:100%; }
    .eo-upload-title { display:flex; align-items:center; gap:9px; font-size:.8rem; font-weight:800; margin-bottom:5px; }
    .eo-upload-title i { color:var(--eo-blue); font-size:1rem; }
    .eo-upload small { display:block; color:var(--eo-muted); font-size:.7rem; margin-bottom:10px; }
    .eo-form .form-control-file { font-size:.76rem; width:100%; }
    .eo-form-note { color:var(--eo-muted); font-size:.72rem; margin-top:6px; }
    .eo-form-actions { border-top:1px solid var(--eo-line); padding-top:18px; margin-top:4px; }
    .eo-form-actions .btn { border-radius:8px; font-size:.8rem; font-weight:700; padding:9px 16px; }
    @media (max-width:576px) { .eo-form-hero { padding:22px 19px; }.eo-form-hero h1{font-size:1.25rem}.eo-form-body{padding:18px 15px}.eo-type-options{flex-direction:column;gap:8px} }
</style>

<?php
$is_edit = isset($surat) && $surat;
$is_post = strtolower($this->input->method()) === 'post';
$val = function ($field, $default = '') use ($is_edit, $surat, $is_post) {
    if ($is_edit) return $is_post ? set_value($field, $default) : (isset($surat->$field) && $surat->$field !== NULL ? $surat->$field : $default);
    return set_value($field, $default);
};
$jenis_terpilih = $val('jenis');
$form_action = $is_edit ? site_url('surat/update/' . $surat->id) : site_url('surat/store');
$kembali_url = $is_edit ? site_url('surat/detail/' . $surat->id) : site_url('surat');
?>
<div class="container-fluid eo-form">
    <div class="eo-form-hero"><div class="eyebrow">E-OFFICE RSA / Surat</div><h1><?php echo $is_edit ? 'Edit Pengajuan Surat' : 'Pengajuan Surat Baru'; ?></h1><p><?php echo $is_edit ? 'Perbaiki data yang salah atau ganti dokumen yang keliru, lalu simpan perubahan.' : 'Lengkapi informasi surat dan unggah draft untuk memulai proses administrasi.'; ?></p></div>
    <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
    <?php if ($this->session->flashdata('message')): ?><div class="alert alert-danger"><?php echo $this->session->flashdata('message'); ?></div><?php endif; ?>
    <?php if ($is_edit && $surat->status === 'Ditolak'): ?><div class="alert alert-warning"><i class="fas fa-info-circle mr-1"></i> Surat ini ditolak. Setelah disimpan, pengajuan akan dikirim ulang dan diproses dari awal.</div><?php endif; ?>
    <div class="eo-form-panel"><div class="eo-form-head"><h2>Informasi Pengajuan</h2><p>Pastikan data tujuan dan perihal sudah sesuai sebelum dikirim.</p></div><div class="eo-form-body"><form method="post" action="<?php echo $form_action; ?>" enctype="multipart/form-data">
        <div class="form-group"><label>Jenis Surat</label><div class="eo-type-options"><div class="eo-type-option"><input type="radio" id="jenis_internal" name="jenis" value="internal" <?php echo $jenis_terpilih === 'internal' ? 'checked' : ''; ?> required><label for="jenis_internal"><i class="fas fa-building"></i><span><strong>Internal</strong><small class="d-block text-muted">Untuk kebutuhan dalam RS</small></span></label></div><div class="eo-type-option"><input type="radio" id="jenis_eksternal" name="jenis" value="eksternal" <?php echo $jenis_terpilih === 'eksternal' ? 'checked' : ''; ?>><label for="jenis_eksternal"><i class="fas fa-globe"></i><span><strong>Eksternal</strong><small class="d-block text-muted">Untuk pihak di luar RS</small></span></label></div><?php if (!empty($can_disposisi)): ?><div class="eo-type-option"><input type="radio" id="jenis_disposisi" name="jenis" value="disposisi" <?php echo $jenis_terpilih === 'disposisi' ? 'checked' : ''; ?>><label for="jenis_disposisi"><i class="fas fa-share"></i><span><strong>Disposisi</strong><small class="d-block text-muted">Langsung ke Direktur</small></span></label></div><?php endif; ?></div></div>
        <div class="form-row"><div class="form-group col-md-7"><label for="perihal">Perihal Surat</label><input id="perihal" name="perihal" class="form-control" required maxlength="255" placeholder="Contoh: Permohonan kerja sama" value="<?php echo html_escape($val('perihal')); ?>"></div><div class="form-group col-md-5"><label for="tanggal_pengajuan">Tanggal Pengajuan</label><input id="tanggal_pengajuan" type="date" name="tanggal_pengajuan" class="form-control" value="<?php echo html_escape($val('tanggal_pengajuan', date('Y-m-d'))); ?>" required></div></div>
        <div class="form-group"><label for="tujuan">Tujuan Surat</label><input id="tujuan" name="tujuan" class="form-control" required maxlength="255" placeholder="Nama unit, instansi, atau penerima surat" value="<?php echo html_escape($val('tujuan')); ?>"></div>
        <div class="form-group"><label for="keterangan">Keterangan Tambahan <span class="font-weight-normal text-muted">(opsional)</span></label><textarea id="keterangan" name="keterangan" class="form-control" rows="3" placeholder="Tambahkan informasi yang perlu diketahui sekretaris atau direktur"><?php echo html_escape($val('keterangan')); ?></textarea></div>
        <div class="form-row"><div class="form-group col-md-6"><div class="eo-upload"><div class="eo-upload-title"><i class="fas fa-file-upload"></i> Draft Surat <?php if (!$is_edit): ?><span class="text-danger">*</span><?php endif; ?></div><small>Format yang didukung: DOC, DOCX, PDF. Maksimal 5 MB.</small><?php if ($is_edit && $surat->file_draft): ?><div class="eo-form-note mb-2"><i class="fas fa-file-word mr-1"></i> Draft saat ini: <strong><?php echo html_escape(basename($surat->file_draft)); ?></strong><br>Kosongkan kolom di bawah jika tidak ingin mengganti draft.</div><?php endif; ?><input type="file" name="file_draft" class="form-control-file" accept=".doc,.docx,.pdf" <?php echo $is_edit ? '' : 'required'; ?>></div></div><div class="form-group col-md-6"><div class="eo-upload"><div class="eo-upload-title"><i class="fas fa-paperclip"></i> Lampiran <span class="font-weight-normal text-muted">(opsional)</span></div><small>PDF, DOC, DOCX, XLS, XLSX, JPG, PNG. Maksimal 10 MB per file.</small><input type="file" name="lampiran[]" class="form-control-file" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"></div></div></div>
        <?php if ($is_edit && !empty($lampiran)): ?><div class="form-group"><label>Lampiran Saat Ini</label><div class="eo-form-note mb-2">Centang lampiran yang ingin dihapus.</div><?php foreach ($lampiran as $file): ?><div class="custom-control custom-checkbox mb-1"><input type="checkbox" class="custom-control-input" id="hapus_lampiran_<?php echo (int) $file->id; ?>" name="hapus_lampiran[]" value="<?php echo (int) $file->id; ?>"><label class="custom-control-label font-weight-normal" for="hapus_lampiran_<?php echo (int) $file->id; ?>"><i class="fas fa-paperclip mr-1"></i><?php echo html_escape($file->nama_file); ?></label></div><?php endforeach; ?></div><?php endif; ?>
        <div class="eo-form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-<?php echo $is_edit ? 'save' : 'paper-plane'; ?> mr-1"></i> <?php echo $is_edit ? 'Simpan Perubahan' : 'Ajukan Surat'; ?></button> <a href="<?php echo $kembali_url; ?>" class="btn btn-light">Batal</a><div class="eo-form-note"><i class="fas fa-info-circle mr-1"></i><?php echo $is_edit ? 'Pengajuan hanya dapat diedit sebelum diproses sekretaris atau selama berstatus ditolak.' : (!empty($can_disposisi) ? 'Surat internal/eksternal masuk ke proses sekretaris; surat disposisi langsung diteruskan ke Direktur.' : 'Setelah diajukan, surat akan masuk ke proses sekretaris.'); ?></div></div>
    </form></div></div>
</div>
