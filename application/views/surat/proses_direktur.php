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
                    <label for="aksi_surat">Aksi</label>
                    <select name="aksi" id="aksi_surat" class="form-control" required>
                        <option value="ttd">Tanda Tangani</option>
                        <option value="disposisi">Disposisi</option>
                    </select>
                    <small class="form-text text-muted">Jika surat ditandatangani, penerima disposisi tidak ditampilkan. Pilih Disposisi untuk memilih penerima.</small>
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
                <div class="form-group">
                    <label>Dokumen hasil TTD (.pdf, .doc, .docx, .jpg, .png)</label>
                    <input type="file" name="file_final" class="form-control-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>
                <button class="btn btn-primary">Simpan Aksi</button>
            </form>
        </div>
    </div>
</div>
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
    if (!search || !list || !count || !empty || !summary || !toggle || !action || !note || !recipientGroup) return;

    var options = list.querySelectorAll('.user-disposisi-option');
    var checkboxes = list.querySelectorAll('.ke-user-checkbox');
    var initialLimit = 6;
    var expanded = false;

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
        note.required = isDisposition;
        recipientGroup.style.display = isDisposition ? '' : 'none';
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
    filterOptions();
    updateCount();
    updateNoteRequirement();
}());
</script>
