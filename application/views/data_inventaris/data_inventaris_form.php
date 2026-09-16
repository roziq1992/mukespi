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
    .di-input-group-harga {
        display: flex;
        align-items: stretch;
    }
    .di-input-group-harga .di-prefix {
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
    .di-input-group-harga .form-control {
        border-radius: 0 8px 8px 0;
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
            <h2>📦 <?php echo $button ?> Data Inventaris</h2>
            <p>Kelola data inventaris barang rumah sakit</p>
        </div>

        <div class="di-body">
            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <?php
                $flash = $this->session->flashdata('message');
                if ($flash) {
                    echo '<div class="alert alert-warning">' . $flash . '</div>';
                }
            ?>

            <form action="<?php echo $action; ?>" method="post" id="formDataInventaris">

                <div class="di-section-title">Informasi Barang</div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="di-label" for="kode_inven">Kode Inven</label>
                        <input type="text" class="form-control" name="kode_inven" id="kode_inven"
                               placeholder="Contoh: INV-0001" value="<?php echo $kode_inven; ?>" />
                        <?php echo form_error('kode_inven') ?>
                    </div>
                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <label class="di-label" for="nm_barang">Nama Barang</label>
                        <input type="text" class="form-control" name="nm_barang" id="nm_barang"
                               placeholder="Contoh: Kursi Roda" value="<?php echo $nm_barang; ?>" />
                        <?php echo form_error('nm_barang') ?>
                    </div>
                    <div class="col-12 col-md-6 mt-3">
                        <label class="di-label" for="merek">Merek</label>
                        <input type="text" class="form-control" name="merek" id="merek"
                               placeholder="Contoh: GEA" value="<?php echo $merek; ?>" />
                        <?php echo form_error('merek') ?>
                    </div>
                    <div class="col-12 col-md-6 mt-3">
                        <label class="di-label" for="tipe">Tipe</label>
                        <input type="text" class="form-control" name="tipe" id="tipe"
                               placeholder="Tipe barang" value="<?php echo $tipe; ?>" />
                        <?php echo form_error('tipe') ?>
                    </div>
                    <div class="col-12 col-md-6 mt-3">
                        <label class="di-label" for="sn">SN (Serial Number)</label>
                        <input type="text" class="form-control" name="sn" id="sn"
                               placeholder="Serial number" value="<?php echo $sn; ?>" />
                        <?php echo form_error('sn') ?>
                    </div>
                    <div class="col-12 col-md-6 mt-3">
                        <label class="di-label" for="jenis">Jenis</label>
                        <input type="text" class="form-control" name="jenis" id="jenis"
                               list="jenisOptions" placeholder="Contoh: Elektronik, Medis, Furnitur"
                               value="<?php echo $jenis; ?>" />
                        <datalist id="jenisOptions">
                            <option value="Elektronik">
                            <option value="Medis">
                            <option value="Furnitur">
                            <option value="ATK">
                        </datalist>
                        <?php echo form_error('jenis') ?>
                    </div>
                </div>

                <div class="di-section-title">Kondisi &amp; Lokasi</div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="di-label" for="kondisi">Kondisi</label>
                        <select class="form-control" name="kondisi" id="kondisi">
                            <option value="">-- Pilih Kondisi --</option>
                            <?php
                                $kondisi_opts = array('Baik', 'Rusak Ringan', 'Rusak Berat');
                                foreach ($kondisi_opts as $opt):
                            ?>
                                <option value="<?php echo $opt; ?>" <?php echo (strcasecmp($kondisi, $opt) == 0) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                            <?php endforeach; ?>
                            <?php if ($kondisi <> '' && !in_array($kondisi, $kondisi_opts)): ?>
                                <option value="<?php echo $kondisi; ?>" selected><?php echo $kondisi; ?></option>
                            <?php endif; ?>
                        </select>
                        <?php echo form_error('kondisi') ?>
                    </div>
                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <label class="di-label" for="id_ruang">Ruang</label>
                        <select class="form-control" name="id_ruang" id="id_ruang">
                            <option value="">-- Pilih Ruang --</option>
                            <?php 
                            // Get ruang data from model
                            $ruang_list = $this->Data_inventaris_model->get_all_ruang();
                            foreach ($ruang_list as $ruang):
                            ?>
                                <option value="<?php echo $ruang->id_ruang; ?>" <?php echo ($id_ruang == $ruang->id_ruang) ? 'selected' : ''; ?>>
                                    <?php echo $ruang->nama_ruang; ?> (<?php echo $ruang->kode_ruang; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo form_error('id_ruang') ?>
                    </div>
                </div>

                <div class="di-section-title">Harga &amp; Status</div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="di-label" for="harga">Harga</label>
                        <div class="di-input-group-harga">
                            <span class="di-prefix">Rp</span>
                            <input type="text" class="form-control" name="harga" id="harga"
                                   placeholder="0" value="<?php echo $harga; ?>" />
                        </div>
                        <?php echo form_error('harga') ?>
                    </div>
                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <label class="di-label" for="stts">Status</label>
                        <select class="form-control" name="stts" id="stts">
                            <option value="">-- Pilih Status --</option>
                            <?php
                                $stts_opts = array('Aktif', 'Proses', 'Rusak', 'Nonaktif');
                                foreach ($stts_opts as $opt):
                            ?>
                                <option value="<?php echo $opt; ?>" <?php echo (strcasecmp($stts, $opt) == 0) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                            <?php endforeach; ?>
                            <?php if ($stts <> '' && !in_array($stts, $stts_opts)): ?>
                                <option value="<?php echo $stts; ?>" selected><?php echo $stts; ?></option>
                            <?php endif; ?>
                        </select>
                        <?php echo form_error('stts') ?>
                    </div>
                </div>

                <input type="hidden" name="id_inven" value="<?php echo $id_inven; ?>" />

                <div class="di-actions">
                    <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                    <a href="<?php echo site_url('data_inventaris') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>