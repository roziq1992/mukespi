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
    .sparepart-item {
        background: #f8fafc;
        border-radius: 8px;
        padding: 10px 15px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        border: 1px solid #eef0f3;
    }
    .sparepart-item .part-info {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: center;
    }
    .sparepart-item .part-info span { 
        font-size: 0.85rem; 
    }
    .sparepart-item .part-info .part-name { 
        font-weight: 600; 
    }
    .sparepart-item .remove-part {
        color: #dc3545;
        cursor: pointer;
        font-size: 1.2rem;
        background: none;
        border: none;
        padding: 0 8px;
    }
    .sparepart-item .remove-part:hover { 
        color: #a71d2a; 
    }
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
    .required {
        color: red;
    }
    .text-danger {
        color: #dc3545;
        font-size: 0.8rem;
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
                    <h2>🔧 <?php echo isset($button) ? $button : 'Tambah'; ?> Riwayat Pemeliharaan</h2>
                    <p>Buat atau edit riwayat pemeliharaan aset</p>
                </div>
                <a href="<?php echo site_url('data_inventaris/maintenance_history'); ?>" class="btn btn-light btn-sm">← Kembali</a>
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

            <form action="<?php echo isset($action) ? $action : site_url('data_inventaris/maintenance_history_action'); ?>" method="post" id="formHistory">

                <div class="di-section-title">Informasi Pemeliharaan</div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="di-label" for="cari_inventaris">Pilih Inventaris <span class="required">*</span></label>

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
                        <label class="di-label" for="id_jenis">Jenis Pemeliharaan <span class="required">*</span></label>
                        <select class="form-control" name="id_jenis" id="id_jenis" required>
                            <option value="">-- Pilih Jenis --</option>
                            <?php if (isset($jenis_list) && is_array($jenis_list)): ?>
                                <?php foreach ($jenis_list as $j): ?>
                                    <option value="<?php echo $j->id_jenis; ?>" 
                                        <?php echo (isset($id_jenis) && $id_jenis == $j->id_jenis) ? 'selected' : ''; ?>>
                                        <?php echo $j->nama_jenis; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php echo form_error('id_jenis') ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 col-md-6">
                        <label class="di-label" for="id_schedule">Dari Jadwal (Opsional)</label>
                        <select class="form-control" name="id_schedule" id="id_schedule">
                            <option value="">-- Tidak dari jadwal --</option>
                            <?php if (isset($schedule_list) && is_array($schedule_list)): ?>
                                <?php foreach ($schedule_list as $s): ?>
                                    <option value="<?php echo $s->id_schedule; ?>" 
                                        <?php echo (isset($id_schedule) && $id_schedule == $s->id_schedule) ? 'selected' : ''; ?>>
                                        <?php echo $s->judul; ?> (<?php echo $s->kode_inven; ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php echo form_error('id_schedule') ?>
                    </div>
                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <label class="di-label" for="tanggal">Tanggal <span class="required">*</span></label>
                        <input type="date" class="form-control" name="tanggal" id="tanggal"
                               value="<?php echo isset($tanggal) ? $tanggal : date('Y-m-d'); ?>" required />
                        <?php echo form_error('tanggal') ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <label class="di-label" for="keterangan">Keterangan <span class="required">*</span></label>
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="3" 
                                  placeholder="Deskripsi pemeliharaan yang dilakukan" required><?php echo isset($keterangan) ? $keterangan : ''; ?></textarea>
                        <?php echo form_error('keterangan') ?>
                    </div>
                </div>

                <div class="di-section-title">Detail Pemeliharaan</div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label class="di-label" for="biaya">Biaya</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" name="biaya" id="biaya"
                                   placeholder="0" value="<?php echo isset($biaya) ? $biaya : ''; ?>" />
                        </div>
                        <?php echo form_error('biaya') ?>
                    </div>
                    <div class="col-12 col-md-4 mt-3 mt-md-0">
                        <label class="di-label" for="petugas">Petugas <span class="required">*</span></label>
                        <input type="text" class="form-control" name="petugas" id="petugas"
                               placeholder="Nama petugas" value="<?php echo isset($petugas) ? $petugas : ''; ?>" required />
                        <?php echo form_error('petugas') ?>
                    </div>
                    <div class="col-12 col-md-4 mt-3 mt-md-0">
                        <label class="di-label" for="durasi_jam">Durasi (Jam)</label>
                        <input type="number" step="0.5" class="form-control" name="durasi_jam" id="durasi_jam"
                               placeholder="0.5" value="<?php echo isset($durasi_jam) ? $durasi_jam : ''; ?>" />
                        <?php echo form_error('durasi_jam') ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 col-md-6">
                        <label class="di-label" for="status">Status <span class="required">*</span></label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="Proses" <?php echo (isset($status) && $status == 'Proses') ? 'selected' : ''; ?>>Proses</option>
                            <option value="Selesai" <?php echo (isset($status) && $status == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                            <option value="Batal" <?php echo (isset($status) && $status == 'Batal') ? 'selected' : ''; ?>>Batal</option>
                        </select>
                        <?php echo form_error('status') ?>
                    </div>
                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <label class="di-label" for="catatan">Catatan Tambahan</label>
                        <input type="text" class="form-control" name="catatan" id="catatan"
                               placeholder="Catatan tambahan" value="<?php echo isset($catatan) ? $catatan : ''; ?>" />
                        <?php echo form_error('catatan') ?>
                    </div>
                </div>

                <!-- Sparepart Section -->
                <div class="di-section-title">🛠️ Sparepart yang Digunakan</div>
                <div id="sparepartContainer">
                    <?php if (isset($sparepart) && is_array($sparepart) && count($sparepart) > 0): ?>
                        <?php foreach ($sparepart as $sp): ?>
                        <div class="sparepart-item" data-id="<?php echo $sp->id_sparepart; ?>">
                            <div class="part-info">
                                <span class="part-name"><?php echo $sp->nama_part; ?></span>
                                <span>Qty: <?php echo $sp->qty; ?></span>
                                <span>Rp <?php echo number_format($sp->harga_satuan, 0, ',', '.'); ?></span>
                                <span>Total: Rp <?php echo number_format($sp->total_harga, 0, ',', '.'); ?></span>
                                <?php if ($sp->keterangan): ?>
                                    <span style="color:#8a94a6;font-size:0.8rem;"><?php echo $sp->keterangan; ?></span>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="remove-part" onclick="removeSparepart(<?php echo $sp->id_sparepart; ?>)">✕</button>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color:#8a94a6;font-size:0.85rem;">Belum ada sparepart yang ditambahkan</p>
                    <?php endif; ?>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end;">
                            <div style="flex: 2; min-width: 150px;">
                                <label class="di-label">Nama Part</label>
                                <input type="text" class="form-control" id="partName" placeholder="Nama sparepart" />
                            </div>
                            <div style="width: 80px;">
                                <label class="di-label">Qty</label>
                                <input type="number" class="form-control" id="partQty" value="1" min="1" />
                            </div>
                            <div style="flex: 1; min-width: 120px;">
                                <label class="di-label">Harga</label>
                                <input type="number" class="form-control" id="partPrice" placeholder="0" />
                            </div>
                            <div style="flex: 1; min-width: 120px;">
                                <label class="di-label">Keterangan</label>
                                <input type="text" class="form-control" id="partNote" placeholder="Opsional" />
                            </div>
                            <div>
                                <label class="di-label" style="visibility:hidden;">.</label>
                                <button type="button" class="btn btn-sm btn-success" onclick="addSparepart()">+ Tambah</button>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="id_history" value="<?php echo isset($id_history) ? $id_history : ''; ?>" />
                <input type="hidden" name="sparepart_data" id="sparepartData" value="" />

                <div class="di-actions">
                    <button type="submit" class="btn btn-primary" onclick="return prepareSubmit()"><?php echo isset($button) ? $button : 'Simpan'; ?></button>
                    <a href="<?php echo site_url('data_inventaris/maintenance_history'); ?>" class="btn btn-outline-secondary">Batal</a>
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

    // -------- Validasi tambahan sebelum submit (digabung dengan prepareSubmit di bawah) --------
    document.getElementById('formHistory').addEventListener('submit', function (e) {
        if (!hiddenId.value) {
            e.preventDefault();
            setScanStatus('', '');
            alert('Silakan pilih inventaris terlebih dahulu (cari, scan barcode, atau ketik kode).');
            searchInput.focus();
        }
    });
});
</script>

<!-- ============================================ -->
<!-- JAVASCRIPT UNTUK SPAREPART (tetap dipertahankan) -->
<!-- ============================================ -->
<script>
// Store sparepart data
var spareparts = [];

<?php if (isset($sparepart) && is_array($sparepart) && count($sparepart) > 0): ?>
    <?php foreach ($sparepart as $sp): ?>
        spareparts.push({
            id: <?php echo $sp->id_sparepart; ?>,
            nama_part: '<?php echo addslashes($sp->nama_part); ?>',
            qty: <?php echo $sp->qty; ?>,
            harga_satuan: <?php echo $sp->harga_satuan; ?>,
            total_harga: <?php echo $sp->total_harga; ?>,
            keterangan: '<?php echo addslashes($sp->keterangan); ?>'
        });
    <?php endforeach; ?>
<?php endif; ?>

function addSparepart() {
    var nama = document.getElementById('partName').value.trim();
    var qty = parseInt(document.getElementById('partQty').value) || 1;
    var harga = parseFloat(document.getElementById('partPrice').value) || 0;
    var ket = document.getElementById('partNote').value.trim();
    
    if (!nama) {
        alert('Nama sparepart harus diisi!');
        return;
    }
    
    var total = qty * harga;
    
    spareparts.push({
        id: 'new_' + Date.now(),
        nama_part: nama,
        qty: qty,
        harga_satuan: harga,
        total_harga: total,
        keterangan: ket
    });
    
    renderSpareparts();
    
    // Clear fields
    document.getElementById('partName').value = '';
    document.getElementById('partQty').value = '1';
    document.getElementById('partPrice').value = '';
    document.getElementById('partNote').value = '';
}

function removeSparepart(id) {
    if (!confirm('Hapus sparepart ini?')) return;
    
    // If it's a new item (starts with 'new_'), remove from array
    if (typeof id === 'string' && id.startsWith('new_')) {
        spareparts = spareparts.filter(function(item) {
            return item.id !== id;
        });
        renderSpareparts();
    } else {
        // Delete from server
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '<?php echo site_url("data_inventaris/sparepart_delete"); ?>/' + id, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            spareparts = spareparts.filter(function(item) {
                                return item.id !== id;
                            });
                            renderSpareparts();
                        }
                    } catch(e) {
                        alert('Error menghapus sparepart');
                    }
                }
            }
        };
        xhr.send();
    }
}

function renderSpareparts() {
    var container = document.getElementById('sparepartContainer');
    container.innerHTML = '';
    
    if (spareparts.length === 0) {
        container.innerHTML = '<p style="color:#8a94a6;font-size:0.85rem;">Belum ada sparepart yang ditambahkan</p>';
        return;
    }
    
    var html = '';
    for (var i = 0; i < spareparts.length; i++) {
        var sp = spareparts[i];
        html += '<div class="sparepart-item">';
        html += '    <div class="part-info">';
        html += '        <span class="part-name">' + sp.nama_part + '</span>';
        html += '        <span>Qty: ' + sp.qty + '</span>';
        html += '        <span>Rp ' + formatNumber(sp.harga_satuan) + '</span>';
        html += '        <span>Total: Rp ' + formatNumber(sp.total_harga) + '</span>';
        if (sp.keterangan) {
            html += '        <span style="color:#8a94a6;font-size:0.8rem;">' + sp.keterangan + '</span>';
        }
        html += '    </div>';
        html += '    <button type="button" class="remove-part" onclick="removeSparepart(\'' + sp.id + '\')">✕</button>';
        html += '</div>';
    }
    
    container.innerHTML = html;
}

function formatNumber(num) {
    if (!num) return '0';
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function prepareSubmit() {
    if (!document.getElementById('id_inven').value) {
        alert('Silakan pilih inventaris terlebih dahulu (cari, scan barcode, atau ketik kode).');
        return false;
    }

    // Store sparepart data in hidden field
    var data = spareparts.map(function(sp) {
        return {
            nama_part: sp.nama_part,
            qty: sp.qty,
            harga_satuan: sp.harga_satuan,
            total_harga: sp.total_harga,
            keterangan: sp.keterangan
        };
    });
    document.getElementById('sparepartData').value = JSON.stringify(data);
    return true;
}

// Auto calculate total preview
document.addEventListener('DOMContentLoaded', function() {
    var qtyInput = document.getElementById('partQty');
    var priceInput = document.getElementById('partPrice');
    
    if (qtyInput && priceInput) {
        qtyInput.addEventListener('change', function() {
            var qty = parseInt(this.value) || 1;
            var price = parseFloat(priceInput.value) || 0;
            // Just preview, no need to display
        });
        priceInput.addEventListener('change', function() {
            var price = parseFloat(this.value) || 0;
            var qty = parseInt(qtyInput.value) || 1;
            // Just preview, no need to display
        });
    }
});

console.log('Maintenance History Form loaded successfully');
console.log('Spareparts count:', spareparts.length);
</script>