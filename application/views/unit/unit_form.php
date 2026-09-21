<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <a href="<?php echo site_url('unit'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1"><?php echo $unit ? 'Ubah Unit Kerja' : 'Tambah Unit Kerja'; ?></h1>
        </div>
    </div>

    <div class="card shadow mb-4" style="max-width:680px;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo $unit ? 'Edit ' . html_escape($unit->nm_unit) : 'Data Unit Baru'; ?></h6>
        </div>
        <div class="card-body">
            <?php echo form_open($unit ? 'unit/edit/' . $unit->id_unit : 'unit/create'); ?>

                <div class="form-group">
                    <label for="nm_unit">Nama Unit Kerja <span class="text-danger">*</span></label>
                    <input type="text" name="nm_unit" id="nm_unit" class="form-control" value="<?php echo set_value('nm_unit', $unit ? $unit->nm_unit : ''); ?>" placeholder="cth: IT, IGD, FARMASI" required>
                    <?php echo form_error('nm_unit', '<small class="text-danger">', '</small>'); ?>
                </div>

                <div class="form-group">
                    <label for="jns_unit">Jenis Unit <span class="text-danger">*</span></label>
                    <select name="jns_unit" id="jns_unit" class="form-control">
                        <?php $jns = set_value('jns_unit', $unit ? $unit->jns_unit : ''); ?>
                        <?php foreach (array('Medis', 'Non_Medis', 'Management', 'Penunjang_Medis') as $o): ?>
                        <option value="<?php echo $o; ?>" <?php echo $jns === $o ? 'selected' : ''; ?>><?php echo str_replace('_', ' ', $o); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo form_error('jns_unit', '<small class="text-danger">', '</small>'); ?>
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-control">
                        <?php $st = set_value('status', $unit ? $unit->status : 'aktif'); ?>
                        <option value="aktif" <?php echo $st === 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="nonaktif" <?php echo $st === 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                    </select>
                    <?php echo form_error('status', '<small class="text-danger">', '</small>'); ?>
                    <small class="form-text text-muted">Unit nonaktif tidak tampil pada pilihan form lain (penilaian, indikator, dll).</small>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>