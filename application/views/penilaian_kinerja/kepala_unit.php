<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <a href="<?php echo site_url('penilaian_kinerja'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Kelola Kepala Unit</h1>
            <div class="small text-muted">Tetapkan siapa kepala unit (penilai) pada tiap unit. Kepala unit berhak menilai pegawai dalam unit tersebut. Unit pegawai ditentukan dari data pegawai.</div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="get" action="<?php echo site_url('penilaian_kinerja/kepala_unit'); ?>" class="form-inline">
                <select name="unit" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">-- Pilih Unit --</option>
                    <?php foreach ($units as $u): ?>
                    <option value="<?php echo $u->id_unit; ?>" <?php echo ($id_unit && $u->id_unit == $id_unit) ? 'selected' : ''; ?>><?php echo html_escape($u->nm_unit); ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

    <?php if ($id_unit): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pegawai Unit: <span class="text-gray-800"><?php echo $unit ? html_escape($unit->nm_unit) : '-'; ?></span></h6>
        </div>
        <div class="card-body">
            <?php if (empty($members)): ?>
            <div class="alert alert-warning mb-0">
                Belum ada pegawai pada unit ini. Tetapkan <strong>Unit</strong> pegawai terlebih dahulu pada menu <strong>Data Pegawai</strong>.
            </div>
            <?php else: ?>
            <form method="post" action="<?php echo site_url('penilaian_kinerja/kepala_unit_save'); ?>">
                <input type="hidden" name="id_unit" value="<?php echo (int) $id_unit; ?>">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width:70px;">Kepala?</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Jabatan</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $m): ?>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="kepala[]" value="<?php echo (int) $m->id_pegawai; ?>" class="form-check-input" <?php echo $m->is_kepala ? 'checked' : ''; ?>>
                                </td>
                                <td class="font-weight-bold"><?php echo html_escape($m->nama); ?></td>
                                <td><?php echo html_escape($m->email); ?></td>
                                <td><?php echo html_escape($m->jabatan ?: '-'); ?></td>
                                <td><span class="badge badge-info"><?php echo html_escape($m->role_name ?: 'Pegawai'); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan Kepala Unit</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>