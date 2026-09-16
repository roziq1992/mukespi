<style>
    .di-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .di-card .card-header-custom {
        background: linear-gradient(135deg, #2c5f8a 0%, #1b3a5c 100%);
        color: #fff;
        padding: 18px 24px;
    }
    .di-card .card-header-custom h2 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }
    .di-card .card-header-custom p {
        margin: 4px 0 0;
        font-size: 0.8rem;
        opacity: 0.85;
    }
    .di-body {
        padding: 20px;
    }
    .di-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #33475b;
        margin-bottom: 6px;
        display: block;
    }
    .di-section-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #8a94a6;
        font-weight: 700;
        margin: 20px 0 12px;
        border-bottom: 1px solid #eef0f3;
        padding-bottom: 6px;
    }
    .di-body .form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 10px 12px;
        font-size: 0.9rem;
        width: 100%;
    }
    .di-body .form-control:focus {
        border-color: #2c5f8a;
        box-shadow: 0 0 0 0.2rem rgba(44,95,138,0.15);
    }
    .di-actions {
        margin-top: 20px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .di-actions .btn {
        border-radius: 8px;
        padding: 10px 22px;
        font-weight: 600;
    }
    .priority-badge {
        font-size: 0.7rem;
        padding: 2px 10px;
        border-radius: 20px;
        font-weight: 600;
        color: #fff;
    }
    .priority-badge.Kritis { background: #dc3545; }
    .priority-badge.Tinggi { background: #fd7e14; }
    .priority-badge.Sedang { background: #ffc107; color: #333; }
    .priority-badge.Rendah { background: #28a745; }
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
    }
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffc107;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .input-group {
        display: flex;
        align-items: stretch;
    }
    .input-group .input-group-text {
        display: flex;
        align-items: center;
        padding: 0 12px;
        background: #eef2f7;
        border: 1px solid #ced4da;
        border-right: none;
        border-radius: 8px 0 0 8px;
        font-weight: 600;
        color: #33475b;
        font-size: 0.9rem;
    }
    .input-group .form-control {
        border-radius: 0 8px 8px 0;
    }

    /* ================= SCAN & CARI ASET ================= */
    .di-scan-group { position: relative; }
    .di-scan-group .input-group .form-control {
        border-radius: 8px 0 0 8px;
    }
    .btn-scan {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        background: #2c5f8a;
        color: #fff;
        border: 1px solid #2c5f8a;
        border-radius: 0 8px 8px 0;
        padding: 0 16px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-scan:hover { background: #1b3a5c; }
    .di-selected-chip {
        display: none;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        background: #e8f4ee;
        border: 1px solid #b7e0c9;
        color: #1e6b45;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .di-selected-chip .clear-chip {
        margin-left: auto;
        cursor: pointer;
        color: #1e6b45;
        opacity: 0.7;
        font-weight: 800;
    }
    .di-selected-chip .clear-chip:hover { opacity: 1; }
    .di-suggestions {
        display: none;
        position: absolute;
        z-index: 40;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ced4da;
        border-top: none;
        border-radius: 0 0 8px 8px;
        max-height: 220px;
        overflow-y: auto;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .di-suggestions .sugg-item {
        padding: 9px 12px;
        cursor: pointer;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f3f6;
    }
    .di-suggestions .sugg-item:last-child { border-bottom: none; }
    .di-suggestions .sugg-item:hover,
    .di-suggestions .sugg-item.active { background: #eef4fa; }
    .di-suggestions .sugg-code {
        display: inline-block;
        font-weight: 700;
        color: #2c5f8a;
        margin-right: 6px;
    }
    .di-suggestions .sugg-empty {
        padding: 10px 12px;
        font-size: 0.82rem;
        color: #8a94a6;
    }

    /* Modal scan barcode (tanpa dependensi Bootstrap JS) */
    .scan-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        z-index: 9998;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .scan-modal-backdrop.show { display: flex; }
    .scan-modal-box {
        background: #fff;
        border-radius: 14px;
        width: 100%;
        max-width: 420px;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(0,0,0,0.3);
    }
    .scan-modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        background: #1b3a5c;
        color: #fff;
    }
    .scan-modal-head h5 { margin: 0; font-size: 1rem; font-weight: 700; }
    .scan-modal-head .close-scan {
        background: none;
        border: none;
        color: #fff;
        font-size: 1.2rem;
        cursor: pointer;
        line-height: 1;
        opacity: 0.85;
    }
    .scan-modal-head .close-scan:hover { opacity: 1; }
    .scan-modal-body { padding: 14px; }
    #barcodeReader { width: 100%; border-radius: 10px; overflow: hidden; background: #000; min-height: 260px; }
    .scan-hint { font-size: 0.78rem; color: #8a94a6; text-align: center; margin-top: 10px; }
    .scan-status { font-size: 0.82rem; text-align: center; margin-top: 8px; font-weight: 600; min-height: 18px; }
    .scan-status.ok { color: #1e6b45; }
    .scan-status.err { color: #dc3545; }
    .btn-manual-input {
        width: 100%;
        margin-top: 10px;
        background: #eef2f7;
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 9px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #33475b;
        cursor: pointer;
    }
    .btn-manual-input:hover { background: #e2e7ee; }
</style>

<div class="container-fluid">
    <div class="di-card bg-white">
        <div class="card-header-custom">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <div>
                    <h2>📅 <?php echo isset($button) ? $button : 'Tambah'; ?> Jadwal Pemeliharaan</h2>
                    <p>Buat atau edit jadwal pemeliharaan aset</p>
                </div>
                <a href="<?php echo site_url('data_inventaris/maintenance_schedule'); ?>" class="btn btn-light btn-sm">← Kembali</a>
            </div>
        </div>

        <div class="di-body">
            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <?php
                $flash = $this->session->flashdata('message');
                if ($flash) {
                    echo '<div class="alert alert-warning">' . $flash . '</div>';
                }
            ?>

            <form action="<?php echo isset($action) ? $action : site_url('data_inventaris/maintenance_schedule_action'); ?>" method="post" id="formJadwalPemeliharaan">

                <div class="di-section-title">Informasi Jadwal</div>
                <div class="row">
                    <div class="col-12">
                        <label class="di-label" for="judul">Judul Pemeliharaan <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" name="judul" id="judul"
                               placeholder="Contoh: Servis AC Ruang Operasi" 
                               value="<?php echo isset($judul) ? $judul : ''; ?>" required />
                        <?php echo form_error('judul') ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 col-md-6">
                        <label class="di-label" for="cari_inventaris">Pilih Inventaris <span style="color:red;">*</span></label>

                        <div class="di-scan-group">
                            <div class="input-group">
                                <input type="text" class="form-control" id="cari_inventaris"
                                       placeholder="Ketik nama/kode barang, atau tekan Scan"
                                       autocomplete="off">
                                <button type="button" class="btn-scan" id="btnScanBarcode">
                                    <i class="fas fa-barcode"></i> Scan
                                </button>
                            </div>
                            <div class="di-suggestions" id="inventarisSuggestions"></div>

                            <div class="di-selected-chip" id="selectedChip">
                                <i class="fas fa-check-circle"></i>
                                <span id="selectedChipText"></span>
                                <span class="clear-chip" id="clearSelectedChip" title="Hapus pilihan">&times;</span>
                            </div>
                        </div>

                        <!-- Nilai sebenarnya yang dikirim ke server -->
                        <input type="hidden" name="id_inven" id="id_inven" value="<?php echo isset($id_inven) ? $id_inven : ''; ?>" required />
                        <?php echo form_error('id_inven') ?>
                    </div>
                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <label class="di-label" for="id_jenis">Jenis Pemeliharaan <span style="color:red;">*</span></label>
                        <select class="form-control" name="id_jenis" id="id_jenis" required>
                            <option value="">-- Pilih Jenis --</option>
                            <?php if (isset($jenis_list) && is_array($jenis_list)): ?>
                                <?php foreach ($jenis_list as $j): ?>
                                    <option value="<?php echo $j->id_jenis; ?>" 
                                        <?php echo (isset($id_jenis) && $id_jenis == $j->id_jenis) ? 'selected' : ''; ?>>
                                        <?php echo $j->nama_jenis; ?> (<?php echo $j->kode_jenis; ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php echo form_error('id_jenis') ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <label class="di-label" for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" 
                                  placeholder="Deskripsi detail pemeliharaan"><?php echo isset($deskripsi) ? $deskripsi : ''; ?></textarea>
                        <?php echo form_error('deskripsi') ?>
                    </div>
                </div>

                <div class="di-section-title">Waktu &amp; Prioritas</div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="di-label" for="tanggal_mulai">Tanggal Mulai <span style="color:red;">*</span></label>
                        <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai"
                               value="<?php echo isset($tanggal_mulai) ? $tanggal_mulai : date('Y-m-d'); ?>" required />
                        <?php echo form_error('tanggal_mulai') ?>
                    </div>
                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <label class="di-label" for="tanggal_selesai">Tanggal Selesai (Estimasi)</label>
                        <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai"
                               value="<?php echo isset($tanggal_selesai) ? $tanggal_selesai : date('Y-m-d', strtotime('+7 days')); ?>" />
                        <?php echo form_error('tanggal_selesai') ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 col-md-4">
                        <label class="di-label" for="prioritas">Prioritas <span style="color:red;">*</span></label>
                        <select class="form-control" name="prioritas" id="prioritas" required>
                            <option value="Rendah" <?php echo (isset($prioritas) && $prioritas == 'Rendah') ? 'selected' : ''; ?>>Rendah</option>
                            <option value="Sedang" <?php echo (isset($prioritas) && $prioritas == 'Sedang') ? 'selected' : ''; ?>>Sedang</option>
                            <option value="Tinggi" <?php echo (isset($prioritas) && $prioritas == 'Tinggi') ? 'selected' : ''; ?>>Tinggi</option>
                            <option value="Kritis" <?php echo (isset($prioritas) && $prioritas == 'Kritis') ? 'selected' : ''; ?>>Kritis</option>
                        </select>
                        <?php echo form_error('prioritas') ?>
                    </div>
                    <div class="col-12 col-md-4 mt-3 mt-md-0">
                        <label class="di-label" for="status">Status <span style="color:red;">*</span></label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="Draft" <?php echo (isset($status) && $status == 'Draft') ? 'selected' : ''; ?>>Draft</option>
                            <option value="Scheduled" <?php echo (isset($status) && $status == 'Scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                            <option value="In Progress" <?php echo (isset($status) && $status == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Completed" <?php echo (isset($status) && $status == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?php echo (isset($status) && $status == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        <?php echo form_error('status') ?>
                    </div>
                    <div class="col-12 col-md-4 mt-3 mt-md-0">
                        <label class="di-label" for="estimasi_biaya">Estimasi Biaya</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="estimasi_biaya" id="estimasi_biaya"
                                   placeholder="0" value="<?php echo isset($estimasi_biaya) ? $estimasi_biaya : ''; ?>" />
                        </div>
                        <?php echo form_error('estimasi_biaya') ?>
                    </div>
                </div>

                <div class="di-section-title">Petugas</div>
                <div class="row">
                    <div class="col-12">
                        <label class="di-label" for="petugas">Petugas / Tim</label>
                        <input type="text" class="form-control" name="petugas" id="petugas"
                               placeholder="Nama petugas atau tim" 
                               value="<?php echo isset($petugas) ? $petugas : ''; ?>" />
                        <?php echo form_error('petugas') ?>
                    </div>
                </div>

                <input type="hidden" name="id_schedule" value="<?php echo isset($id_schedule) ? $id_schedule : ''; ?>" />

                <div class="di-actions">
                    <button type="submit" class="btn btn-primary"><?php echo isset($button) ? $button : 'Simpan'; ?></button>
                    <a href="<?php echo site_url('data_inventaris/maintenance_schedule'); ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODAL SCAN BARCODE ================= -->
<div class="scan-modal-backdrop" id="scanModalBackdrop">
    <div class="scan-modal-box">
        <div class="scan-modal-head">
            <h5><i class="fas fa-barcode"></i> Scan Barcode Aset</h5>
            <button type="button" class="close-scan" id="closeScanModal">&times;</button>
        </div>
        <div class="scan-modal-body">
            <div id="barcodeReader"></div>
            <div class="scan-status" id="scanStatus"></div>
            <p class="scan-hint">Arahkan kamera ke barcode/kode batang pada label aset.</p>
            <button type="button" class="btn-manual-input" id="btnStopUseInput">
                Kamera tidak jalan? Ketik/scan manual di kolom pencarian
            </button>
        </div>
    </div>
</div>

<!-- Data inventaris untuk pencarian & pencocokan barcode di sisi browser -->
<script id="inventarisData" type="application/json">
<?php
    $inventaris_js = array();
    if (isset($inventaris_list) && is_array($inventaris_list)) {
        foreach ($inventaris_list as $inv) {
            $inventaris_js[] = array(
                'id'   => $inv->id_inven,
                'kode' => $inv->kode_inven,
                'nama' => $inv->nm_barang,
            );
        }
    }
    echo json_encode($inventaris_js);
?>
</script>

<!-- Library scan barcode via kamera (dimuat lazy, hanya dipanggil saat tombol Scan diklik) -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var inventarisList = JSON.parse(document.getElementById('inventarisData').textContent || '[]');

    var searchInput   = document.getElementById('cari_inventaris');
    var hiddenId       = document.getElementById('id_inven');
    var suggestionsBox = document.getElementById('inventarisSuggestions');
    var chip           = document.getElementById('selectedChip');
    var chipText        = document.getElementById('selectedChipText');
    var clearChipBtn    = document.getElementById('clearSelectedChip');

    var scanBtn        = document.getElementById('btnScanBarcode');
    var scanBackdrop    = document.getElementById('scanModalBackdrop');
    var closeScanBtn     = document.getElementById('closeScanModal');
    var btnStopUseInput  = document.getElementById('btnStopUseInput');
    var scanStatus       = document.getElementById('scanStatus');

    var html5QrCode = null;

    // -------- Pra-isi kalau mode edit (sudah ada id_inven) --------
    function findById(id) {
        if (!id) return null;
        for (var i = 0; i < inventarisList.length; i++) {
            if (String(inventarisList[i].id) === String(id)) return inventarisList[i];
        }
        return null;
    }
    function findByKode(kode) {
        var k = (kode || '').trim().toLowerCase();
        if (!k) return null;
        for (var i = 0; i < inventarisList.length; i++) {
            if ((inventarisList[i].kode || '').toLowerCase() === k) return inventarisList[i];
        }
        return null;
    }

    function selectItem(item) {
        hiddenId.value = item.id;
        searchInput.value = '';
        chipText.textContent = item.kode + ' — ' + item.nama;
        chip.style.display = 'flex';
        hideSuggestions();
    }

    function clearSelection() {
        hiddenId.value = '';
        chip.style.display = 'none';
        chipText.textContent = '';
        searchInput.value = '';
        searchInput.focus();
    }

    var preselected = findById(hiddenId.value);
    if (preselected) {
        selectItem(preselected);
    }

    clearChipBtn.addEventListener('click', clearSelection);

    // -------- Autocomplete cari nama / kode --------
    function renderSuggestions(list, query) {
        suggestionsBox.innerHTML = '';
        if (list.length === 0) {
            var empty = document.createElement('div');
            empty.className = 'sugg-empty';
            empty.textContent = 'Tidak ada aset yang cocok dengan "' + query + '"';
            suggestionsBox.appendChild(empty);
        } else {
            list.slice(0, 25).forEach(function (item) {
                var row = document.createElement('div');
                row.className = 'sugg-item';
                row.innerHTML = '<span class="sugg-code">' + item.kode + '</span>' + item.nama;
                row.addEventListener('click', function () { selectItem(item); });
                suggestionsBox.appendChild(row);
            });
        }
        suggestionsBox.style.display = 'block';
    }
    function hideSuggestions() {
        suggestionsBox.style.display = 'none';
    }

    searchInput.addEventListener('input', function () {
        var q = searchInput.value.trim().toLowerCase();
        if (!q) { hideSuggestions(); return; }

        var matches = inventarisList.filter(function (item) {
            return (item.kode || '').toLowerCase().indexOf(q) !== -1 ||
                   (item.nama || '').toLowerCase().indexOf(q) !== -1;
        });
        renderSuggestions(matches, q);
    });

    // Enter = anggap input dari scanner genggam (keyboard wedge) -> cocokkan persis dengan kode
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            var match = findByKode(searchInput.value);
            if (match) {
                selectItem(match);
            } else {
                var q = searchInput.value.trim().toLowerCase();
                var partial = inventarisList.filter(function (item) {
                    return (item.kode || '').toLowerCase().indexOf(q) !== -1 ||
                           (item.nama || '').toLowerCase().indexOf(q) !== -1;
                });
                if (partial.length === 1) {
                    selectItem(partial[0]);
                } else {
                    renderSuggestions(partial, searchInput.value);
                }
            }
        }
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.di-scan-group')) hideSuggestions();
    });

    // -------- Scan barcode via kamera --------
    function setScanStatus(msg, type) {
        scanStatus.textContent = msg || '';
        scanStatus.className = 'scan-status' + (type ? ' ' + type : '');
    }

    function openScanModal() {
        scanBackdrop.classList.add('show');
        setScanStatus('Mengaktifkan kamera...', '');

        if (typeof Html5Qrcode === 'undefined') {
            setScanStatus('Gagal memuat pustaka scanner. Cek koneksi internet, atau ketik/scan manual di kolom pencarian.', 'err');
            return;
        }

        html5QrCode = new Html5Qrcode('barcodeReader');
        var config = {
            fps: 10,
            qrbox: { width: 260, height: 140 },
            formatsToSupport: [
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.EAN_13,
                Html5QrcodeSupportedFormats.EAN_8,
                Html5QrcodeSupportedFormats.QR_CODE
            ]
        };

        html5QrCode.start(
            { facingMode: 'environment' },
            config,
            function (decodedText) {
                var match = findByKode(decodedText);
                if (match) {
                    setScanStatus('Aset ditemukan: ' + match.nama, 'ok');
                    selectItem(match);
                    setTimeout(closeScanModal, 700);
                } else {
                    setScanStatus('Kode "' + decodedText + '" tidak ada di data inventaris. Coba lagi.', 'err');
                }
            },
            function () { /* diabaikan: dipanggil terus tiap frame yang tidak terbaca */ }
        ).catch(function (err) {
            setScanStatus('Tidak bisa mengakses kamera (' + err + '). Pastikan izin kamera diberikan, atau ketik/scan manual di kolom pencarian.', 'err');
        });
    }

    function closeScanModal() {
        scanBackdrop.classList.remove('show');
        setScanStatus('', '');
        if (html5QrCode) {
            html5QrCode.stop().then(function () {
                html5QrCode.clear();
                html5QrCode = null;
            }).catch(function () {
                html5QrCode = null;
            });
        }
    }

    scanBtn.addEventListener('click', openScanModal);
    closeScanBtn.addEventListener('click', closeScanModal);
    btnStopUseInput.addEventListener('click', function () {
        closeScanModal();
        searchInput.focus();
    });

    // -------- Validasi sebelum submit --------
    document.getElementById('formJadwalPemeliharaan').addEventListener('submit', function (e) {
        if (!hiddenId.value) {
            e.preventDefault();
            setScanStatus('', '');
            alert('Silakan pilih inventaris terlebih dahulu (cari, scan barcode, atau ketik kode).');
            searchInput.focus();
        }
    });
});
</script>