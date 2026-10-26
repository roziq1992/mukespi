<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header"><h5 class="m-0">Proses Nomor Surat</h5></div>
        <div class="card-body">
            <p><strong><?php echo html_escape($surat->perihal); ?></strong><br><?php echo html_escape($surat->tujuan); ?></p>
            <p>Status: <span class="badge badge-<?php echo surat_status_class($surat->status); ?>"><?php echo html_escape($surat->status); ?></span> <?php if ($surat->file_draft): ?><a href="<?php echo site_url('surat/download/' . $surat->id . '/draft'); ?>">Download draft</a><?php endif; ?></p>
            <div class="mb-3">
                <strong>Lampiran dari pemohon</strong>
                <?php if (!empty($lampiran)): ?>
                    <ul class="list-unstyled mt-2 mb-0">
                        <?php foreach ($lampiran as $file): ?>
                            <?php $ext = strtolower(pathinfo($file->nama_file, PATHINFO_EXTENSION)); $bisa_lihat = in_array($ext, array('pdf', 'jpg', 'jpeg', 'png'), TRUE); ?>
                            <li class="mb-1"><a class="btn btn-sm btn-light border" href="<?php echo site_url(($bisa_lihat ? 'surat/lihat_lampiran/' : 'surat/lampiran/') . $file->id); ?>"<?php echo $bisa_lihat ? ' target="_blank" rel="noopener"' : ''; ?>><i class="fas <?php echo $bisa_lihat ? 'fa-eye' : 'fa-download'; ?> text-secondary mr-1"></i><?php echo html_escape($file->nama_file); ?></a> <small class="text-muted"><?php echo $bisa_lihat ? 'Buka' : 'Unduh'; ?></small></li>
                        <?php endforeach; ?>
                    </ul>
                    <small class="form-text text-muted">File PDF/gambar dapat dibuka langsung. File Word/Excel akan diunduh karena tidak dapat dipratinjau.</small>
                <?php else: ?>
                    <div class="text-muted small mt-1">Tidak ada lampiran.</div>
                <?php endif; ?>
            </div>
            <div class="alert alert-info">
                <strong>Informasi pengisian:</strong>
                <ol class="mb-0 pl-3">
                    <li>Isi <strong>Nomor Surat</strong> secara manual atau klik <em>Generate Nomor</em>.</li>
                    <li>Unggah <strong>berkas yang sudah ditandatangani</strong> (dokumen bernomor). Untuk diteruskan ke Direktur, berkas wajib berformat PDF agar dapat direview dan ditandatangani.</li>
                    <li>Klik <strong>Simpan &amp; Teruskan ke Direktur</strong> untuk mengirim surat ke antrian Direktur.</li>
                </ol>
            </div>
            <?php $pesan = $this->session->flashdata('message'); ?>
            <?php if ($pesan): ?><div class="alert alert-danger"><?php echo html_escape(strip_tags($pesan)); ?></div><?php endif; ?>
            <form method="post" enctype="multipart/form-data" action="<?php echo site_url('surat_sekretaris/simpan/' . $surat->id); ?>">
                <div class="form-group">
                    <label>Nomor Surat</label>
                    <input name="no_surat" class="form-control" value="<?php echo html_escape($surat->no_surat); ?>" placeholder="001/RSA/INT/VIII/2026">
                    <small class="form-text text-muted">Isi manual atau klik generate otomatis.</small>
                </div>
                <div class="form-group">
                    <label>Berkas yang sudah ditandatangani / dokumen bernomor (.doc/.docx/.pdf)</label>
                    <input type="file" name="file_ber_nomor" class="form-control-file" accept=".doc,.docx,.pdf">
                    <small class="form-text text-muted">Unggah berkas yang telah ditandatangani. Untuk meneruskan ke Direktur agar dapat direview dan ditandatangani, dokumen wajib PDF.</small>
                </div>
                <?php if ($surat->file_ber_nomor): ?>
                    <p class="mb-2">Berkas bernomor saat ini: <a href="<?php echo site_url('surat/download/' . $surat->id . '/numbered'); ?>"><i class="fas fa-file-alt"></i> Unduh berkas</a></p>
                <?php endif; ?>
                <button name="generate" value="1" class="btn btn-outline-secondary">Generate Nomor</button>
                <button class="btn btn-primary" name="teruskan" value="1">Simpan &amp; Teruskan ke Direktur</button>
                <button class="btn btn-light" name="teruskan" value="0">Simpan Nomor</button>
            </form>
        </div>
    </div>
</div>
