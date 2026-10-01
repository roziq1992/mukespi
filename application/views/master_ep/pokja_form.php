<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="card shadow mb-4 mt-3">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?php echo $title; ?></h6>
        </div>
        <div class="card-body">
            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <?php
            $id = isset($row->id) ? (int)$row->id : 0;
            $bab = set_value('bab', isset($row->bab) ? $row->bab : '');
            $ket = set_value('ket', isset($row->ket) ? $row->ket : '');
            $active = set_value('active', isset($row->active) ? $row->active : 'Y');
            ?>
            <form method="post" action="<?php echo site_url('master_ep/pokja_form/' . $id); ?>">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>BAB</label>
                            <input type="text" class="form-control" name="bab" value="<?php echo html_escape($bab); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" class="form-control" name="ket" value="<?php echo html_escape($ket); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="active">
                                <option value="Y" <?php echo ($active === 'Y') ? 'selected' : ''; ?>>Aktif</option>
                                <option value="N" <?php echo ($active === 'N') ? 'selected' : ''; ?>>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?php echo site_url('master_ep/pokja'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
