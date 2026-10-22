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
        padding: 22px 24px;
    }
    .di-card .card-header-custom h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
    }
    .di-card .card-header-custom p {
        margin: 4px 0 0;
        font-size: 0.85rem;
        opacity: 0.85;
    }
    .di-body {
        padding: 24px;
    }
    @media (max-width: 576px) {
        .di-body { padding: 16px; }
        .di-card .card-header-custom { padding: 16px; }
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
        margin: 22px 0 12px;
        border-bottom: 1px solid #eef0f3;
        padding-bottom: 6px;
    }
    .di-section-title:first-of-type { margin-top: 0; }
    .di-body .form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 10px 12px;
        font-size: 0.9rem;
    }
    .di-body .form-control:focus {
        border-color: #2c5f8a;
        box-shadow: 0 0 0 0.2rem rgba(44,95,138,0.15);
    }
    .di-body .form-control[readonly] {
        background: #f1f3f6;
        cursor: not-allowed;
    }
    .di-hint {
        font-size: 0.75rem;
        color: #8a94a6;
        margin-top: 5px;
    }
    .di-actions {
        margin-top: 26px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .di-actions .btn {
        border-radius: 8px;
        padding: 10px 22px;
        font-weight: 600;
    }
</style>

<div class="container-fluid">
    <div class="di-card bg-white">
        <div class="card-header-custom">
            <h2>🏢 <?php echo $button; ?> Ruang</h2>
            <p>Data lokasi / ruang penyimpanan inventaris rumah sakit</p>
        </div>

        <div class="di-body">
            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <?php
                $flash = $this->session->flashdata('message');
                if ($flash) {
                    echo '<div class="alert alert-warning">' . $flash . '</div>';
                }
            ?>

            <form action="<?php echo $action; ?>" method="post" id="formDataRuang">

                <div class="di-section-title">Identitas Ruang</div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label class="di-label" for="id_ruang">ID Ruang</label>
                        <?php if ($id_ruang_lama !== ''): ?>
                            <input type="text" class="form-control" id="id_ruang" value="<?php echo $id_ruang; ?>" readonly />
                            <input type="hidden" name="id_ruang" value="<?php echo $id_ruang_lama; ?>" />
                            <div class="di-hint">ID ruang tidak dapat diubah setelah disimpan.</div>
                        <?php else: ?>
                            <input type="text" class="form-control" name="id_ruang" id="id_ruang"
                                   placeholder="Contoh: R004" value="<?php echo $id_ruang; ?>" maxlength="50" />
                            <div class="di-hint">Kosongkan untuk membuat otomatis (<?php echo $new_id_ruang; ?>).</div>
                        <?php endif; ?>
                        <?php echo form_error('id_ruang') ?>
                    </div>
                    <div class="col-12 col-md-8">
                        <label class="di-label" for="nama_ruang">Nama Ruang</label>
                        <input type="text" class="form-control" name="nama_ruang" id="nama_ruang"
                               placeholder="Contoh: Ruang Rawat Inap Floor 3" value="<?php echo $nama_ruang; ?>" maxlength="100" />
                        <?php echo form_error('nama_ruang') ?>
                    </div>
                </div>

                <div class="di-section-title">Lokasi</div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label class="di-label" for="kode_ruang">Kode Ruang</label>
                        <input type="text" class="form-control" name="kode_ruang" id="kode_ruang"
                               placeholder="Contoh: Rawat Inap 3" value="<?php echo $kode_ruang; ?>" maxlength="50" />
                        <div class="di-hint">Jika dikosongkan, nama ruang dipakai sebagai kode.</div>
                        <?php echo form_error('kode_ruang') ?>
                    </div>
                    <div class="col-12 col-md-4 mt-3 mt-md-0">
                        <label class="di-label" for="gedung">Gedung</label>
                        <input type="text" class="form-control" name="gedung" id="gedung"
                               placeholder="Contoh: Gedung B" value="<?php echo $gedung; ?>" maxlength="50" />
                        <?php echo form_error('gedung') ?>
                    </div>
                    <div class="col-12 col-md-4 mt-3 mt-md-0">
                        <label class="di-label" for="lantai">Lantai</label>
                        <input type="text" class="form-control" name="lantai" id="lantai"
                               placeholder="Contoh: 2" value="<?php echo $lantai; ?>" />
                        <?php echo form_error('lantai') ?>
                    </div>
                </div>

                <input type="hidden" name="id_ruang_lama" value="<?php echo $id_ruang_lama; ?>" />

                <div class="di-actions">
                    <button type="submit" class="btn btn-primary"><?php echo $button; ?></button>
                    <a href="<?php echo site_url('data_inventaris/ruang'); ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
