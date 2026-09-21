<style>
.otk-penilai-card { display:flex; align-items:center; justify-content:space-between; background:#fff; border:1px solid #e4eaf2; border-radius:10px; padding:13px 16px; margin-bottom:9px; text-decoration:none!important; box-shadow:0 2px 8px rgba(23,43,77,.04); transition:.15s ease; }
.otk-penilai-card:hover { border-color:#9dbbe8; transform:translateY(-2px); }
.otk-penilai-card.active { border-color:#2563eb; background:#f5f9ff; }
.dinilai-chk { border:1px solid #e4eaf2; border-radius:8px; padding:8px 10px; margin-bottom:7px; transition:.12s ease; }
.dinilai-chk:hover { border-color:#9dbbe8; background:#f8fafc; }
.dinilai-chk input:checked ~ span { color:#2563eb; font-weight:700; }
</style>

<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <a href="<?php echo site_url('penilaian_otk'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Kelola Penilai </h1>
            <div class="small text-muted">Tetapkan penilai lalu pilih pegawai yang dinilai.</div>
        </div>
    </div>

    <div class="row">
        <!-- Daftar penilai -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow mb-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Penilai</h6>
                </div>
                <div class="card-body p-0">
                    <div class="input-group input-group-sm p-2 border-bottom">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" id="cariPenilai" class="form-control" placeholder="Cari penilai by nama / jabatan / unit..." autocomplete="off">
                    </div>
                    <div class="list-group list-group-flush" id="listPenilai">
                        <?php if (empty($penilai_list)): ?>
                        <div class="p-3 text-muted small">Belum ada penilai. Pilih penilai dari daftar pegawai di samping.</div>
                        <?php endif; ?>
                        <?php foreach ($penilai_list as $pl): ?>
                        <a class="otk-penilai-card <?php echo (int)$pl->id_pegawai === (int)$id_penilai ? 'active' : ''; ?>" href="<?php echo site_url('penilaian_otk/kelola/' . $pl->id_pegawai); ?>">
                            <div>
                                <div class="font-weight-bold"><?php echo html_escape($pl->nama); ?></div>
                                <div class="small text-muted"><?php echo html_escape($pl->jabatan); ?> &mdash; <?php echo html_escape($pl->nm_unit); ?></div>
                            </div>
                            <span class="badge badge-primary"><?php echo (int)$pl->jml_dinilai; ?> dinilai</span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Pilih penilai lain -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pilih Penilai Lain</h6>
                </div>
                <div class="card-body">
                    <select id="pickPenilai" class="form-control">
                        <option value="">-- Pilih pegawai sebagai penilai --</option>
                        <?php foreach ($all_pegawai as $pg): ?>
                        <option value="<?php echo (int)$pg->id_pegawai; ?>" <?php echo (int)$pg->id_pegawai === (int)$id_penilai ? 'selected' : ''; ?>>
                            <?php echo html_escape($pg->nama . ' | ' . $pg->jabatan . ' | ' . $pg->nm_unit); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <script>
                    document.getElementById('pickPenilai').addEventListener('change', function () {
                        if (this.value) location.href = '<?php echo site_url('penilaian_otk/kelola'); ?>/' + this.value;
                    });
                    </script>
                </div>
            </div>
        </div>

        <!-- Set yang dinilai -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Set yang Dinilai
                        <?php if ($penilai): ?>
                        &mdash; <?php echo html_escape($penilai->nama); ?>
                        <?php endif; ?>
                    </h6>
                </div>
                <?php if ($penilai): ?>
                <form method="post" action="<?php echo site_url('penilaian_otk/kelola_save'); ?>">
                    <input type="hidden" name="id_penilai" value="<?php echo (int)$id_penilai; ?>">
                    <div class="card-body pb-1">
                        <div class="alert alert-light border small py-2">Ceklis semua pegawai yang menjadi tanggungan penilaian <strong><?php echo html_escape($penilai->nama); ?></strong>. Penilai tidak dapat menilai dirinya sendiri.</div>
                        <div class="d-flex mb-3">
                            <div class="input-group input-group-sm flex-grow-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="cariDinilai" class="form-control" placeholder="Cari pegawai by nama / jabatan / unit..." autocomplete="off">
                            </div>
                            <button type="button" id="togSetOnly" class="btn btn-sm btn-outline-primary ml-2">
                                <i class="fas fa-filter"></i> Hanya yang Di-set <span id="jmlSet" class="badge badge-primary ml-1">0</span>
                            </button>
                        </div>
                        <div class="row" id="rowDinilai">
                            <?php foreach ($all_pegawai as $pg): ?>
                            <?php if ((int)$pg->id_pegawai === (int)$id_penilai) continue; ?>
                            <div class="col-md-6">
                                <label class="dinilai-chk d-block mb-0">
                                    <input type="checkbox" name="dinilai[]" value="<?php echo (int)$pg->id_pegawai; ?>"
                                        <?php echo isset($assignment_map[$id_penilai . ',' . $pg->id_pegawai]) ? 'checked' : ''; ?>>
                                    <span><?php echo html_escape($pg->nama); ?></span>
                                    <small class="d-block text-muted ml-4"><?php echo html_escape($pg->jabatan); ?> &mdash; <?php echo html_escape($pg->nm_unit); ?></small>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="resetPenilai(<?php echo (int)$id_penilai; ?>, '<?php echo html_escape(addslashes($penilai->nama)); ?>')">
                            <i class="fas fa-undo-alt"></i> Reset Semua
                        </button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Set Dinilai</button>
                    </div>
                </form>
                <?php else: ?>
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-user-plus fa-3x mb-3"></i>
                    <p>Pilih seorang penilai (dari daftar di samping atau dropdown) untuk mengatur siapa saja yang dinilai.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function resetPenilai(id, nama) {
    if (confirm('Hapus SEMUA tanggungan penilaian untuk ' + nama + '?\nTindakan ini tidak dapat dibatalkan.')) {
        location.href = '<?php echo site_url('penilaian_otk/kelola_reset'); ?>/' + id;
    }
}

// --- Cari penilai ---
var cariPenilai = document.getElementById('cariPenilai');
if (cariPenilai) {
    cariPenilai.addEventListener('input', function () {
        var q = this.value.toLowerCase().trim();
        var cards = document.querySelectorAll('#listPenilai .otk-penilai-card');
        cards.forEach(function (card) {
            var txt = card.textContent.toLowerCase();
            card.style.display = (!q || txt.indexOf(q) !== -1) ? '' : 'none';
        });
    });
}

// --- Cari yang dinilai + filter "hanya yang di-set" ---
var cariDinilai = document.getElementById('cariDinilai');
var togSetOnly = document.getElementById('togSetOnly');
var jmlSet = document.getElementById('jmlSet');

function filterDinilai() {
    var q = cariDinilai.value.toLowerCase().trim();
    var hanyaSet = togSetOnly.classList.contains('active');
    var shown = 0;
    document.querySelectorAll('#rowDinilai .col-md-6').forEach(function (col) {
        var chk = col.querySelector('input[type="checkbox"]');
        var txt = col.textContent.toLowerCase();
        var ok = (!q || txt.indexOf(q) !== -1) && (!hanyaSet || chk.checked);
        col.style.display = ok ? '' : 'none';
        if (ok) shown++;
    });
    return shown;
}

function hitungSet() {
    return document.querySelectorAll('#rowDinilai input[type="checkbox"]:checked').length;
}

if (cariDinilai && togSetOnly) {
    cariDinilai.addEventListener('input', filterDinilai);
    togSetOnly.addEventListener('click', function () {
        this.classList.toggle('active');
        filterDinilai();
    });
    jmlSet.textContent = hitungSet();
}
</script>