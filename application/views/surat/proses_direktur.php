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
            <form method="post" enctype="multipart/form-data" action="<?php echo site_url('surat_direktur/simpan/' . $surat->id); ?>">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="ttd_luar_sistem" name="ttd_luar_sistem" value="1" checked>
                        <label class="custom-control-label" for="ttd_luar_sistem">Direktur tidak menandatangani di sistem (tanda tangan dilakukan di luar sistem)</label>
                    </div>
                    <small class="form-text text-muted">Centang jika surat akan ditandatangani di luar sistem. Pratinjau dan area tanda tangan akan disembunyikan.</small>
                </div>
                <div id="blok_pratinjau_ttd">
                <?php if ($signature_preview): ?>
                    <div class="form-group">
                        <label>Pratinjau dan area tanda tangan PDF</label>
                        <div id="pdf_preview" data-pdf-url="<?php echo html_escape(site_url('surat_direktur/preview/' . $surat->id)); ?>" style="max-height:75vh;overflow:auto;background:#e9ecef;border:1px solid #d1d3e2;border-radius:.35rem;padding:12px;">
                            <div id="pdf_loading" class="text-muted">Memuat dokumen PDF...</div>
                            <div id="pdf_pages"></div>
                        </div>
                        <small class="form-text text-muted">Bubuhkan tanda tangan langsung pada halaman PDF. Seret tanda tangan untuk mengatur posisinya; untuk pindah halaman, hapus lalu bubuhkan kembali pada halaman yang diinginkan.</small>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Pratinjau dan tanda tangan hanya tersedia untuk dokumen PDF. Minta sekretaris mengunggah dokumen bernomor dalam format PDF.</div>
                <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="aksi_surat">Aksi</label>
                    <select name="aksi" id="aksi_surat" class="form-control" required>
                        <option value="ttd" <?php echo !$signature_preview ? 'disabled' : ''; ?>>Tanda Tangani</option>
                        <option value="disposisi" <?php echo !$signature_preview ? 'selected' : ''; ?>>Disposisi</option>
                    </select>
                    <small class="form-text text-muted">Jika surat ditandatangani, penerima disposisi tidak ditampilkan. Pilih Disposisi untuk memilih penerima.</small>
                </div>
                <div class="form-group" id="panel_tanda_tangan">
                    <label>Tanda tangan pada dokumen</label>
                    <input type="hidden" name="signature_data" id="signature_data">
                    <input type="hidden" name="signature_page" id="signature_page">
                    <input type="hidden" name="signature_x" id="signature_x">
                    <input type="hidden" name="signature_y" id="signature_y">
                    <input type="hidden" name="signature_width" id="signature_width">
                    <button type="button" id="hapus_tanda_tangan" class="btn btn-sm btn-outline-secondary">Hapus tanda tangan</button>
                    <small class="form-text text-muted">Tanda tangan ini bersifat visual dan bukan tanda tangan elektronik tersertifikasi VIDA TTE.</small>
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
                <div class="form-group" id="unggah_dokumen_final">
                    <label>Dokumen hasil TTD (.pdf, .doc, .docx, .jpg, .png)</label>
                    <input type="file" name="file_final" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>
                <button class="btn btn-primary">Simpan Aksi</button>
            </form>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/vendor/pdfjs/pdf.min.js'); ?>"></script>
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
    var ttdLuarSistem = document.getElementById('ttd_luar_sistem');
    var pratinjauGroup = document.getElementById('blok_pratinjau_ttd');
    var ttdOption = action ? action.querySelector('option[value="ttd"]') : null;
    var ttdOptionDefaultDisabled = ttdOption ? ttdOption.disabled : false;
    var signatureInput = document.getElementById('signature_data');
    var clearSignatureButton = document.getElementById('hapus_tanda_tangan');
    var finalUpload = document.getElementById('unggah_dokumen_final');
    var pdfPreview = document.getElementById('pdf_preview');
    var pdfPages = document.getElementById('pdf_pages');
    var pdfLoading = document.getElementById('pdf_loading');
    var signaturePageInput = document.getElementById('signature_page');
    var signatureXInput = document.getElementById('signature_x');
    var signatureYInput = document.getElementById('signature_y');
    var signatureWidthInput = document.getElementById('signature_width');
    var form = action ? action.form : null;
    if (!search || !list || !count || !empty || !summary || !toggle || !action || !note || !recipientGroup || !signatureGroup || !ttdLuarSistem || !pratinjauGroup || !signatureInput || !clearSignatureButton || !finalUpload || !form) return;

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

    clearSignatureButton.addEventListener('click', clearSignatureInk);

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
        var luarSistem = ttdLuarSistem.checked;
        note.required = isDisposition;
        recipientGroup.style.display = isDisposition ? '' : 'none';
        signatureGroup.style.display = (isDisposition || luarSistem) ? 'none' : '';
        pratinjauGroup.style.display = luarSistem ? 'none' : '';
        finalUpload.style.display = isDisposition ? '' : 'none';
        if (ttdOption) ttdOption.disabled = luarSistem ? false : ttdOptionDefaultDisabled;
        if (!luarSistem && ttdOptionDefaultDisabled && action.value === 'ttd') action.value = 'disposisi';
        note.previousElementSibling.textContent = isDisposition ? 'Catatan / instruksi' : 'Catatan tanda tangan (opsional)';
        if (luarSistem) clearSignatureInk();
    }

    search.addEventListener('input', function () {
        filterOptions();
    });

    toggle.addEventListener('click', function () {
        expanded = !expanded;
        filterOptions();
    });

    action.addEventListener('change', updateNoteRequirement);
    ttdLuarSistem.addEventListener('change', function () {
        if (ttdLuarSistem.checked) {
            updateNoteRequirement();
            action.value = 'ttd';
        }
        updateNoteRequirement();
    });
    Array.prototype.forEach.call(checkboxes, function (checkbox) {
        checkbox.addEventListener('change', updateCount);
    });
    form.addEventListener('submit', function (event) {
        if (action.value !== 'ttd' || ttdLuarSistem.checked) return;
        if (!activeSignature || !activeSignature.strokes.length) {
            event.preventDefault();
            alert('Bubuhkan tanda tangan langsung pada halaman PDF terlebih dahulu.');
            return;
        }
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
    if (ttdLuarSistem.checked) action.value = 'ttd';
}());
</script>
