<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header"><h5 class="m-0">Aksi Direktur</h5></div>
        <div class="card-body">
            <p><strong><?php echo html_escape($surat->no_surat); ?></strong><br><?php echo html_escape($surat->perihal); ?> | <?php echo html_escape($surat->tujuan); ?></p>
            <?php $pesan_validasi = $this->session->flashdata('message'); ?>
            <?php if ($pesan_validasi): ?>
                <div class="alert alert-danger" role="alert"><?php echo html_escape(strip_tags($pesan_validasi)); ?></div>
            <?php endif; ?>
            <?php if ($surat->file_ber_nomor): ?>
                <a class="btn btn-sm btn-outline-primary mb-3" href="<?php echo site_url('surat/download/' . $surat->id . '/numbered'); ?>">Download dokumen bernomor</a>
            <?php endif; ?>
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
            <?php $bisa_proses_ulang = in_array($surat->status, array('Ditandatangani', 'Didisposisikan', 'Selesai', 'Ditolak'), TRUE); ?>
            <?php if ($bisa_proses_ulang): ?>
                <div class="alert alert-warning">
                    <h6 class="alert-heading font-weight-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Surat sudah diproses</h6>
                    <p class="mb-2">Status saat ini: <strong><?php echo html_escape($surat->status); ?></strong>. Jika terjadi kesalahan tanda tangan, disposisi, atau penolakan, batalkan aksi sebelumnya lalu proses ulang surat ini.</p>
                    <p class="mb-3 small">Semua disposisi dan dokumen final dari aksi sebelumnya akan dihapus. Dokumen bernomor tetap tersimpan.</p>
                    <form method="post" action="<?php echo site_url('surat_direktur/ulang/' . $surat->id); ?>" onsubmit="return confirm('Batalkan aksi direktur sebelumnya dan proses ulang surat ini? Semua disposisi dan dokumen final lama akan dihapus.');">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-redo mr-1"></i> Proses Ulang</button>
                        <a href="<?php echo site_url('surat/detail/' . $surat->id); ?>" class="btn btn-light">Lihat Detail Surat</a>
                    </form>
                </div>
            <?php else: ?>
            <form method="post" enctype="multipart/form-data" action="<?php echo site_url('surat_direktur/simpan/' . $surat->id); ?>">
                <div id="blok_pratinjau_ttd">
                <?php if ($signature_preview): ?>
                    <div class="form-group">
                        <label>Pratinjau dan area tanda tangan PDF</label>
                        <div id="pdf_preview" data-pdf-url="<?php echo html_escape(site_url('surat_direktur/preview/' . $surat->id)); ?>" style="max-height:75vh;overflow:auto;background:#e9ecef;border:1px solid #d1d3e2;border-radius:.35rem;padding:12px;">
                            <div id="pdf_loading" class="text-muted">Memuat dokumen PDF...</div>
                            <div id="pdf_pages"></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Pratinjau dan tanda tangan hanya tersedia untuk dokumen PDF. Minta sekretaris mengunggah dokumen bernomor dalam format PDF.</div>
                <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="aksi_surat">Aksi</label>
                    <select name="aksi" id="aksi_surat" class="form-control" required>
                        <option value="ttd">Tanda Tangani</option>
                        <option value="disposisi">Disposisi</option>
                        <option value="tolak">Tolak</option>
                    </select>
                    <small class="form-text text-muted">Jika surat ditandatangani, penerima disposisi tidak ditampilkan. Pilih Disposisi untuk memilih penerima, atau Tolak bila surat tidak disetujui.</small>
                    <div class="alert alert-info mt-2 mb-0 py-2"><small><strong>Info unduhan:</strong> Tanda Tangani &rarr; dokumen final dapat diunduh pemohon. Disposisi &rarr; dokumen final dapat diunduh penerima. Tolak &rarr; pemohon tidak dapat mengunduh dokumen final.</small></div>
                </div>
                <div class="form-group" id="panel_tanda_tangan">
                    <label>Tanda tangan pada dokumen</label>
                    <input type="hidden" name="signature_data" id="signature_data">
                    <input type="hidden" name="signature_page" id="signature_page">
                    <input type="hidden" name="signature_x" id="signature_x">
                    <input type="hidden" name="signature_y" id="signature_y">
                    <input type="hidden" name="signature_width" id="signature_width">
                    <small class="form-text text-muted">Tanda tangan ini bersifat visual dan bukan tanda tangan elektronik tersertifikasi VIDA TTE.</small>
                </div>
                <div class="form-group" id="panel_verifikasi">
                    <label>QR verifikasi dokumen</label>
                    <div class="d-flex align-items-start" style="gap:12px;">
                        <canvas id="qr_verifikasi_preview" width="116" height="116" style="width:116px;height:116px;border:1px solid #d1d3e2;border-radius:.35rem;background:#fff;"></canvas>
                        <div>
                            <small class="form-text text-muted mt-0 mb-1">QR ini otomatis ditempel pada dokumen final saat surat ditandatangani. Bila dipindai, menampilkan halaman verifikasi keaslian dokumen.</small>
                            <a id="qr_verifikasi_link" href="<?php echo html_escape($verifikasi_url); ?>" target="_blank" rel="noopener" class="small d-block text-break"><?php echo html_escape($verifikasi_url); ?></a>
                        </div>
                    </div>
                    <input type="hidden" name="verification_qr" id="verification_qr">
                </div>
                <div class="form-group" id="pilihan_penerima_disposisi">
                    <label for="cari_user_disposisi">Cari tujuan disposisi</label>
                    <input type="search" id="cari_user_disposisi" class="form-control mb-2" placeholder="Cari nama, jabatan, atau email...">
                    <div id="daftar_user_disposisi" class="border rounded p-2" style="max-height: 220px; overflow-y: auto;" aria-describedby="petunjuk_disposisi">
                        <?php foreach ($users as $user): ?>
                            <?php $label_user = $user->name . ' - ' . ($user->jabatan ?: 'Jabatan belum diisi') . ' - ' . $user->email; ?>
                            <label class="user-disposisi-option mb-0 p-2 border-bottom" data-search="<?php echo html_escape($label_user); ?>" for="ke_user_<?php echo (int) $user->id; ?>">
                                <input type="checkbox" class="mr-2 ke-user-checkbox" name="ke_user[]" id="ke_user_<?php echo (int) $user->id; ?>" value="<?php echo (int) $user->id; ?>">
                                <span><?php echo html_escape($label_user); ?></span>
                            </label>
                        <?php endforeach; ?>
                        <div id="user_disposisi_kosong" class="text-muted small p-2" style="display:none;">Tidak ada pengguna yang cocok.</div>
                    </div>
                    <button type="button" id="toggle_user_disposisi" class="btn btn-link btn-sm p-0 mt-2" style="display:none;">Tampilkan semua pengguna</button>
                    <small id="petunjuk_disposisi" class="form-text text-muted">Centang satu atau lebih pengguna. Cari untuk menemukan pengguna lain.</small>
                    <small id="jumlah_penerima_disposisi" class="form-text text-primary">Belum ada penerima dipilih.</small>
                    <div id="ringkasan_penerima_disposisi" class="mt-2" aria-live="polite"></div>
                </div>
                <div class="form-group">
                    <label for="catatan_disposisi">Catatan / instruksi</label>
                    <textarea name="catatan" id="catatan_disposisi" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group" id="panel_alasan_tolak" style="display:none;">
                    <label for="alasan_tolak">Alasan penolakan</label>
                    <textarea name="alasan_tolak" id="alasan_tolak" class="form-control" rows="3" placeholder="Tulis alasan surat ditolak..."></textarea>
                    <small class="form-text text-muted">Alasan ini dikirimkan kepada pemohon sebagai pemberitahuan bahwa surat ditolak.</small>
                </div>
                <div class="form-group" id="panel_review_berkas">
                    <label>Review berkas dari sekretaris</label>
                    <div class="d-flex flex-wrap" style="gap:8px;">
                        <?php if ($signature_preview): ?>
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo site_url('surat_direktur/preview/' . $surat->id); ?>" target="_blank" rel="noopener"><i class="fas fa-search"></i> Lihat berkas (PDF)</a>
                        <?php endif; ?>
                        <?php if ($surat->file_ber_nomor): ?>
                            <a class="btn btn-sm btn-outline-secondary" href="<?php echo site_url('surat/download/' . $surat->id . '/numbered'); ?>"><i class="fas fa-download"></i> Unduh berkas bernomor</a>
                        <?php else: ?>
                            <span class="text-muted small">Belum ada berkas bernomor dari sekretaris.</span>
                        <?php endif; ?>
                    </div>
                    <small class="form-text text-muted">Buka dan review berkas yang diunggah sekretaris sebelum menandatangani, mendisposisikan, atau menolak surat.</small>
                </div>
                <div class="form-group" id="unggah_dokumen_final">
                    <label>Dokumen final / hasil TTD (.pdf, .doc, .docx, .jpg, .png)</label>
                    <small class="form-text text-muted">Opsional. Bila diunggah, dokumen ini dipakai sebagai dokumen final.</small>
                    <input type="file" name="file_final" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>
                <button class="btn btn-primary">Simpan Aksi</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/vendor/pdfjs/pdf.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/qrcode-generator.js'); ?>"></script>
<script>
(function () {
    var search = document.getElementById('cari_user_disposisi');
    var list = document.getElementById('daftar_user_disposisi');
    var count = document.getElementById('jumlah_penerima_disposisi');
    var empty = document.getElementById('user_disposisi_kosong');
    var summary = document.getElementById('ringkasan_penerima_disposisi');
    var toggle = document.getElementById('toggle_user_disposisi');
    var action = document.getElementById('aksi_surat');
    var note = document.getElementById('catatan_disposisi');
    var recipientGroup = document.getElementById('pilihan_penerima_disposisi');
    var signatureGroup = document.getElementById('panel_tanda_tangan');
    var pratinjauGroup = document.getElementById('blok_pratinjau_ttd');
    var signatureInput = document.getElementById('signature_data');
    var finalUpload = document.getElementById('unggah_dokumen_final');
    var rejectGroup = document.getElementById('panel_alasan_tolak');
    var rejectField = document.getElementById('alasan_tolak');
    var pdfPreview = document.getElementById('pdf_preview');
    var pdfPages = document.getElementById('pdf_pages');
    var pdfLoading = document.getElementById('pdf_loading');
    var signaturePageInput = document.getElementById('signature_page');
    var signatureXInput = document.getElementById('signature_x');
    var signatureYInput = document.getElementById('signature_y');
    var signatureWidthInput = document.getElementById('signature_width');
    var qrPreview = document.getElementById('qr_verifikasi_preview');
    var qrInput = document.getElementById('verification_qr');
    var verifikasiUrl = <?php echo json_encode($verifikasi_url); ?>;
    var form = action ? action.form : null;
    if (!search || !list || !count || !empty || !summary || !toggle || !action || !note || !recipientGroup || !signatureGroup || !pratinjauGroup || !signatureInput || !finalUpload || !rejectGroup || !rejectField || !form) return;

    function drawQrCanvas(canvas, text, size) {
        var qr = qrcode(0, 'M');
        qr.addData(text);
        qr.make();
        var moduleCount = qr.getModuleCount();
        var quiet = 2;
        var total = moduleCount + quiet * 2;
        var px = Math.max(1, Math.floor((size || 300) / total));
        var side = px * total;
        canvas.width = side;
        canvas.height = side;
        var context = canvas.getContext('2d');
        context.fillStyle = '#ffffff';
        context.fillRect(0, 0, side, side);
        context.fillStyle = '#111827';
        for (var row = 0; row < moduleCount; row++) {
            for (var col = 0; col < moduleCount; col++) {
                if (qr.isDark(row, col)) context.fillRect((col + quiet) * px, (row + quiet) * px, px, px);
            }
        }
    }

    function buildQrDataUrl(text, size) {
        var temp = document.createElement('canvas');
        drawQrCanvas(temp, text, size || 512);
        return temp.toDataURL('image/png');
    }

    if (window.qrcode && qrPreview && verifikasiUrl) {
        try {
            drawQrCanvas(qrPreview, verifikasiUrl, 116);
            qrPreview.style.width = '116px';
            qrPreview.style.height = '116px';
        } catch (e) {
            if (window.console && console.error) console.error(e);
        }
    }

    var options = list.querySelectorAll('.user-disposisi-option');
    var checkboxes = list.querySelectorAll('.ke-user-checkbox');
    var initialLimit = 6;
    var expanded = false;
    var activeSignature = null;
    var dragState = null;

    function signatureBounds(strokes) {
        var bounds = {left: Infinity, top: Infinity, right: -Infinity, bottom: -Infinity};
        strokes.forEach(function (stroke) {
            stroke.forEach(function (point) {
                bounds.left = Math.min(bounds.left, point.x);
                bounds.top = Math.min(bounds.top, point.y);
                bounds.right = Math.max(bounds.right, point.x);
                bounds.bottom = Math.max(bounds.bottom, point.y);
            });
        });
        return bounds;
    }

    function paintSignature(signature) {
        var context = signature.canvas.getContext('2d');
        context.clearRect(0, 0, signature.canvas.width, signature.canvas.height);
        context.lineWidth = 3;
        context.lineCap = 'round';
        context.lineJoin = 'round';
        context.strokeStyle = '#111';
        signature.strokes.forEach(function (stroke) {
            if (!stroke.length) return;
            context.beginPath();
            context.moveTo(stroke[0].x, stroke[0].y);
            stroke.slice(1).forEach(function (point) {
                context.lineTo(point.x, point.y);
            });
            if (stroke.length === 1) context.lineTo(stroke[0].x + 0.1, stroke[0].y + 0.1);
            context.stroke();
        });
    }

    function canvasPoint(canvas, event) {
        var bounds = canvas.getBoundingClientRect();
        return {
            x: (event.clientX - bounds.left) * canvas.width / bounds.width,
            y: (event.clientY - bounds.top) * canvas.height / bounds.height
        };
    }

    function attachSignatureCanvas(canvas, pageNumber) {
        var signature = {canvas: canvas, pageNumber: pageNumber, strokes: []};
        canvas.addEventListener('pointerdown', function (event) {
            if (action.value !== 'ttd' || (activeSignature && activeSignature !== signature)) return;
            var point = canvasPoint(canvas, event);
            if (activeSignature === signature && signature.strokes.length) {
                var bounds = signatureBounds(signature.strokes);
                if (point.x >= bounds.left - 16 && point.x <= bounds.right + 16 && point.y >= bounds.top - 16 && point.y <= bounds.bottom + 16) {
                    dragState = {point: point, dragging: true};
                    canvas.setPointerCapture(event.pointerId);
                    event.preventDefault();
                    return;
                }
            }
            if (activeSignature && activeSignature !== signature) return;
            activeSignature = signature;
            signature.strokes.push([point]);
            dragState = {point: point, dragging: false};
            canvas.setPointerCapture(event.pointerId);
            paintSignature(signature);
        });
        canvas.addEventListener('pointermove', function (event) {
            if (!dragState || activeSignature !== signature) return;
            var point = canvasPoint(canvas, event);
            if (dragState.dragging) {
                var bounds = signatureBounds(signature.strokes);
                var dx = point.x - dragState.point.x;
                var dy = point.y - dragState.point.y;
                if (bounds.left + dx < 0) dx = -bounds.left;
                if (bounds.right + dx > canvas.width) dx = canvas.width - bounds.right;
                if (bounds.top + dy < 0) dy = -bounds.top;
                if (bounds.bottom + dy > canvas.height) dy = canvas.height - bounds.bottom;
                signature.strokes.forEach(function (stroke) {
                    stroke.forEach(function (strokePoint) {
                        strokePoint.x += dx;
                        strokePoint.y += dy;
                    });
                });
                dragState.point = point;
            } else {
                signature.strokes[signature.strokes.length - 1].push(point);
            }
            paintSignature(signature);
        });
        function stopPointer() { dragState = null; }
        canvas.addEventListener('pointerup', stopPointer);
        canvas.addEventListener('pointercancel', stopPointer);
    }

    function clearSignatureInk() {
        var signatureCanvases = pdfPages.querySelectorAll('.signature-ink');
        Array.prototype.forEach.call(signatureCanvases, function (canvas) {
            paintSignature({canvas: canvas, strokes: []});
        });
        activeSignature = null;
        dragState = null;
        signatureInput.value = '';
        signaturePageInput.value = '';
        signatureXInput.value = '';
        signatureYInput.value = '';
        signatureWidthInput.value = '';
    }

    function renderPdfPreview() {
        if (!pdfPreview) return;
        if (!window.pdfjsLib) {
            pdfLoading.className = 'text-danger';
            pdfLoading.textContent = 'Library pratinjau PDF tidak dapat dimuat.';
            return;
        }
        window.pdfjsLib.GlobalWorkerOptions.workerSrc = '<?php echo base_url('assets/vendor/pdfjs/pdf.worker.min.js'); ?>';
        window.pdfjsLib.getDocument(pdfPreview.getAttribute('data-pdf-url')).promise.then(function (pdfDocument) {
            pdfLoading.style.display = 'none';
            var pagePromises = [];
            for (var pageNumber = 1; pageNumber <= pdfDocument.numPages; pageNumber++) {
                pagePromises.push(pdfDocument.getPage(pageNumber).then(function (page) {
                    var baseViewport = page.getViewport({scale: 1});
                    var scale = Math.min(1.5, Math.max(0.5, (pdfPreview.clientWidth - 28) / baseViewport.width));
                    var viewport = page.getViewport({scale: scale});
                    var wrapper = window.document.createElement('div');
                    wrapper.className = 'position-relative bg-white shadow-sm mx-auto mb-3';
                    wrapper.style.width = Math.floor(viewport.width) + 'px';
                    wrapper.style.maxWidth = '100%';
                    wrapper.setAttribute('data-page-number', page.pageNumber);
                    var pageCanvas = window.document.createElement('canvas');
                    pageCanvas.width = Math.floor(viewport.width);
                    pageCanvas.height = Math.floor(viewport.height);
                    pageCanvas.style.cssText = 'display:block;width:100%;height:auto;';
                    wrapper.appendChild(pageCanvas);
                    var signatureCanvas = window.document.createElement('canvas');
                    signatureCanvas.width = pageCanvas.width;
                    signatureCanvas.height = pageCanvas.height;
                    signatureCanvas.className = 'signature-ink';
                    signatureCanvas.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;touch-action:none;cursor:crosshair;';
                    wrapper.appendChild(signatureCanvas);
                    pdfPages.appendChild(wrapper);
                    attachSignatureCanvas(signatureCanvas, page.pageNumber);
                    return page.render({canvasContext: pageCanvas.getContext('2d'), viewport: viewport}).promise;
                }));
            }
            return Promise.all(pagePromises);
        }).catch(function (error) {
            pdfLoading.className = 'text-danger';
            pdfLoading.textContent = 'Pratinjau PDF gagal dimuat. Silakan muat ulang halaman atau periksa dokumennya.';
            if (window.console && console.error) console.error(error);
        });
    }

    function filterOptions() {
        var query = search.value.toLowerCase();
        var visible = 0;
        Array.prototype.forEach.call(options, function (option, index) {
            var matches = option.getAttribute('data-search').toLowerCase().indexOf(query) !== -1;
            var withinLimit = expanded || query !== '' || index < initialLimit;
            var show = matches && withinLimit;
            option.style.display = show ? 'block' : 'none';
            if (show) visible++;
        });
        empty.style.display = visible ? 'none' : 'block';
        toggle.style.display = options.length > initialLimit && query === '' ? 'inline-block' : 'none';
        toggle.textContent = expanded ? 'Tampilkan lebih sedikit' : 'Tampilkan semua pengguna (' + Math.max(0, options.length - initialLimit) + ' lainnya)';
    }

    function updateCount() {
        var selected = list.querySelectorAll('.ke-user-checkbox:checked');
        count.textContent = selected.length ? selected.length + ' penerima dipilih:' : 'Belum ada penerima dipilih.';
        summary.textContent = '';
        Array.prototype.forEach.call(selected, function (checkbox) {
            var option = checkbox.closest('.user-disposisi-option');
            var label = option.querySelector('span').textContent;
            var badge = document.createElement('span');
            badge.className = 'badge badge-primary mr-1 mb-1';
            badge.textContent = label;
            summary.appendChild(badge);
        });
    }

    function updateNoteRequirement() {
        var isDisposition = action.value === 'disposisi';
        var isReject = action.value === 'tolak';
        note.required = isDisposition;
        rejectField.required = isReject;
        rejectGroup.style.display = isReject ? '' : 'none';
        recipientGroup.style.display = isDisposition ? '' : 'none';
        signatureGroup.style.display = (isDisposition || isReject) ? 'none' : '';
        pratinjauGroup.style.display = '';
        finalUpload.style.display = isReject ? 'none' : '';
        note.previousElementSibling.textContent = isDisposition ? 'Catatan / instruksi' : (isReject ? 'Catatan (opsional)' : 'Catatan tanda tangan (opsional)');
        if (isReject) clearSignatureInk();
    }

    search.addEventListener('input', function () {
        filterOptions();
    });

    toggle.addEventListener('click', function () {
        expanded = !expanded;
        filterOptions();
    });

    action.addEventListener('change', updateNoteRequirement);
    Array.prototype.forEach.call(checkboxes, function (checkbox) {
        checkbox.addEventListener('change', updateCount);
    });
    form.addEventListener('submit', function (event) {
        if (action.value !== 'ttd') return;
        if (window.qrcode && qrInput && verifikasiUrl) {
            try { qrInput.value = buildQrDataUrl(verifikasiUrl, 512); } catch (e) { qrInput.value = ''; }
        }
        if (!activeSignature || !activeSignature.strokes.length) return;
        var bounds = signatureBounds(activeSignature.strokes);
        var padding = 8;
        var left = Math.max(0, Math.floor(bounds.left - padding));
        var top = Math.max(0, Math.floor(bounds.top - padding));
        var right = Math.min(activeSignature.canvas.width, Math.ceil(bounds.right + padding));
        var bottom = Math.min(activeSignature.canvas.height, Math.ceil(bounds.bottom + padding));
        var cropped = document.createElement('canvas');
        cropped.width = Math.max(1, right - left);
        cropped.height = Math.max(1, bottom - top);
        cropped.getContext('2d').drawImage(activeSignature.canvas, left, top, cropped.width, cropped.height, 0, 0, cropped.width, cropped.height);
        signatureInput.value = cropped.toDataURL('image/png');
        signaturePageInput.value = activeSignature.pageNumber;
        signatureXInput.value = (left / activeSignature.canvas.width).toFixed(6);
        signatureYInput.value = (top / activeSignature.canvas.height).toFixed(6);
        signatureWidthInput.value = (cropped.width / activeSignature.canvas.width).toFixed(6);
    });
    renderPdfPreview();
    filterOptions();
    updateCount();
    updateNoteRequirement();
}());
</script>
