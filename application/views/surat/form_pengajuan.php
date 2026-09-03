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

<div class="container-fluid eo-form">
    <div class="eo-form-hero"><div class="eyebrow">E-OFFICE RSA / Surat</div><h1>Pengajuan Surat Baru</h1><p>Lengkapi informasi surat dan unggah draft untuk memulai proses administrasi.</p></div>
    <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
    <?php if ($this->session->flashdata('message')): ?><div class="alert alert-danger"><?php echo $this->session->flashdata('message'); ?></div><?php endif; ?>
    <div class="eo-form-panel"><div class="eo-form-head"><h2>Informasi Pengajuan</h2><p>Pastikan data tujuan dan perihal sudah sesuai sebelum dikirim.</p></div><div class="eo-form-body"><form method="post" action="<?php echo site_url('surat/store'); ?>" enctype="multipart/form-data">
        <div class="form-group"><label>Jenis Surat</label><div class="eo-type-options"><div class="eo-type-option"><input type="radio" id="jenis_internal" name="jenis" value="internal" required><label for="jenis_internal"><i class="fas fa-building"></i><span><strong>Internal</strong><small class="d-block text-muted">Untuk kebutuhan dalam RS</small></span></label></div><div class="eo-type-option"><input type="radio" id="jenis_eksternal" name="jenis" value="eksternal"><label for="jenis_eksternal"><i class="fas fa-globe"></i><span><strong>Eksternal</strong><small class="d-block text-muted">Untuk pihak di luar RS</small></span></label></div></div></div>
        <div class="form-row"><div class="form-group col-md-7"><label for="perihal">Perihal Surat</label><input id="perihal" name="perihal" class="form-control" required maxlength="255" placeholder="Contoh: Permohonan kerja sama"></div><div class="form-group col-md-5"><label for="tanggal_pengajuan">Tanggal Pengajuan</label><input id="tanggal_pengajuan" type="date" name="tanggal_pengajuan" class="form-control" value="<?php echo date('Y-m-d'); ?>" required></div></div>
        <div class="form-group"><label for="tujuan">Tujuan Surat</label><input id="tujuan" name="tujuan" class="form-control" required maxlength="255" placeholder="Nama unit, instansi, atau penerima surat"></div>
        <div class="form-group"><label for="keterangan">Keterangan Tambahan <span class="font-weight-normal text-muted">(opsional)</span></label><textarea id="keterangan" name="keterangan" class="form-control" rows="3" placeholder="Tambahkan informasi yang perlu diketahui sekretaris atau direktur"></textarea></div>
        <div class="form-row"><div class="form-group col-md-6"><div class="eo-upload"><div class="eo-upload-title"><i class="fas fa-file-upload"></i> Draft Surat <span class="text-danger">*</span></div><small>Format yang didukung: DOC, DOCX, PDF. Maksimal 5 MB.</small><input type="file" name="file_draft" class="form-control-file" accept=".doc,.docx,.pdf" required></div></div><div class="form-group col-md-6"><div class="eo-upload"><div class="eo-upload-title"><i class="fas fa-paperclip"></i> Lampiran <span class="font-weight-normal text-muted">(opsional)</span></div><small>PDF, DOC, DOCX, XLS, XLSX, JPG, PNG. Maksimal 10 MB per file.</small><input type="file" name="lampiran[]" class="form-control-file" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"></div></div></div>
        <div class="eo-form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-paper-plane mr-1"></i> Ajukan Surat</button> <a href="<?php echo site_url('surat'); ?>" class="btn btn-light">Batal</a><div class="eo-form-note"><i class="fas fa-info-circle mr-1"></i>Setelah diajukan, surat akan masuk ke proses sekretaris.</div></div>
    </form></div></div>
</div>
