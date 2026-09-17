<style>
.pk-warn { font-size:.78rem; }
</style>

<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <a href="<?php echo site_url('penilaian_kinerja'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Kriteria &amp; Bobot Penilaian per Unit</h1>
        </div>
    </div>

    <!-- Pilih unit -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="get" action="<?php echo site_url('penilaian_kinerja/kriteria'); ?>" class="form-inline">
                <div class="input-group input-group-sm" style="max-width:420px;">
                    <select name="unit" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Pilih Unit --</option>
                        <?php foreach ($units as $u): ?>
                        <option value="<?php echo $u->id_unit; ?>" <?php echo ($id_unit && $u->id_unit == $id_unit) ? 'selected' : ''; ?>><?php echo html_escape($u->nm_unit); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <?php if ($id_unit): ?>
        <?php $tb = isset($kriteria['total_bobot']) ? (float) $kriteria['total_bobot'] : 0; ?>

        <div class="alert <?php echo ($tb <= 0) ? 'alert-warning' : (abs($tb - 100) < 0.05 ? 'alert-success' : 'alert-warning'); ?> py-2 pk-warn">
            <i class="fas fa-info-circle"></i>
            Total bobot unit <strong><?php echo html_escape(isset($kriteria['unit']) ? $kriteria['unit']->nm_unit : ''); ?></strong> = <strong><?php echo number_format($tb, 2); ?>%</strong>.
            <?php if (abs($tb - 100) >= 0.05): ?>
            Sebaiknya total bobot = <strong>100%</strong> agar nilai akhir maksimal 100 (sistem tetap menormalkan otomatis).
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Daftar kriteria -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list"></i> Daftar Kriteria</h6>
                        <button class="btn btn-sm btn-primary" onclick="$('#modalAddKriteria').modal('show')"><i class="fas fa-plus"></i> Tambah Kriteria</button>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($kriteria['groups'])): ?>
                        <div class="text-center text-muted py-5">Unit ini belum memiliki kriteria. Klik "Tambah Kriteria".</div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:45px;">No</th>
                                        <th>Kelompok / Kriteria</th>
                                        <th style="width:80px;" class="text-center">Bobot</th>
                                        <th style="width:110px;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    foreach ($kriteria['groups'] as $kelompok => $grp):
                                    ?>
                                    <tr class="bg-primary text-white" style="background:#4e73df !important;">
                                        <td colspan="2"><strong><?php echo html_escape($kelompok); ?></strong></td>
                                        <td class="text-center"><strong><?php echo number_format($grp['bobot'], 2); ?>%</strong></td>
                                        <td></td>
                                    </tr>
                                    <?php foreach ($grp['items'] as $k): $no++; ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo html_escape($k->kriteria); ?></td>
                                        <td class="text-center"><?php echo number_format((float) $k->bobot, 2); ?>%</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalEditKriteria"
                                                data-id="<?php echo (int) $k->id_kriteria; ?>"
                                                data-kelompok="<?php echo html_escape($k->kelompok); ?>"
                                                data-kriteria="<?php echo html_escape($k->kriteria); ?>"
                                                data-bobot="<?php echo (float) $k->bobot; ?>"
                                                data-urut="<?php echo (int) $k->urut; ?>"><i class="fas fa-pen"></i></button>
                                            <a class="btn btn-sm btn-outline-danger" href="<?php echo site_url('penilaian_kinerja/kriteria_delete/' . $k->id_kriteria); ?>" onclick="return confirm('Hapus kriteria ini?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Grading -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-award"></i> Predikat / Grading</h6>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo site_url('penilaian_kinerja/grading_save'); ?>">
                            <input type="hidden" name="id_unit" value="<?php echo (int) $id_unit; ?>">
                            <div class="form-row mb-2 font-weight-bold small text-muted">
                                <div class="col-6">Label</div>
                                <div class="col-3">Min</div>
                                <div class="col-3">Max</div>
                            </div>
                            <?php if (!empty($gradings)): ?>
                                <?php foreach ($gradings as $i => $g): ?>
                                <div class="form-row mb-2">
                                    <div class="col-6"><input type="text" name="label[]" value="<?php echo html_escape($g->label); ?>" class="form-control form-control-sm" required></div>
                                    <div class="col-3"><input type="number" step="0.01" name="nilai_min[]" value="<?php echo $g->nilai_min; ?>" class="form-control form-control-sm" required></div>
                                    <div class="col-3"><input type="number" step="0.01" name="nilai_max[]" value="<?php echo $g->nilai_max; ?>" class="form-control form-control-sm" required></div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="form-row mb-2">
                                    <div class="col-6"><input type="text" name="label[]" value="Baik" class="form-control form-control-sm" required></div>
                                    <div class="col-3"><input type="number" step="0.01" name="nilai_min[]" value="60" class="form-control form-control-sm" required></div>
                                    <div class="col-3"><input type="number" step="0.01" name="nilai_max[]" value="100" class="form-control form-control-sm" required></div>
                                </div>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-primary mt-1" type="submit"><i class="fas fa-save"></i> Simpan Grading</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Tambah Kriteria -->
<div class="modal fade" id="modalAddKriteria" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="<?php echo site_url('penilaian_kinerja/kriteria_add'); ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kriteria</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_unit" value="<?php echo (int) $id_unit; ?>">
                    <div class="form-group">
                        <label>Kelompok / Komponen</label>
                        <input type="text" name="kelompok" class="form-control" placeholder="contoh: 1. PERILAKU" required>
                    </div>
                    <div class="form-group">
                        <label>Kriteria / Item Penilaian</label>
                        <input type="text" name="kriteria" class="form-control" placeholder="Uraian kriteria penilaian" required>
                    </div>
                    <div class="form-row">
                        <div class="col-6 form-group">
                            <label>Bobot (%)</label>
                            <input type="number" step="0.01" name="bobot" class="form-control" value="5" required>
                        </div>
                        <div class="col-6 form-group">
                            <label>Urutan</label>
                            <input type="number" name="urut" class="form-control" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kriteria -->
<div class="modal fade" id="modalEditKriteria" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="<?php echo site_url('penilaian_kinerja/kriteria_edit'); ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kriteria</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_kriteria" id="ek_id">
                    <div class="form-group">
                        <label>Kelompok / Komponen</label>
                        <input type="text" name="kelompok" id="ek_kelompok" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Kriteria / Item Penilaian</label>
                        <input type="text" name="kriteria" id="ek_kriteria" class="form-control" required>
                    </div>
                    <div class="form-row">
                        <div class="col-6 form-group">
                            <label>Bobot (%)</label>
                            <input type="number" step="0.01" name="bobot" id="ek_bobot" class="form-control" required>
                        </div>
                        <div class="col-6 form-group">
                            <label>Urutan</label>
                            <input type="number" name="urut" id="ek_urut" class="form-control" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var m = document.getElementById('modalEditKriteria');
    m.addEventListener('show.bs.modal', function (e) {
        var b = e.relatedTarget;
        document.getElementById('ek_id').value = b.getAttribute('data-id');
        document.getElementById('ek_kelompok').value = b.getAttribute('data-kelompok');
        document.getElementById('ek_kriteria').value = b.getAttribute('data-kriteria');
        document.getElementById('ek_bobot').value = b.getAttribute('data-bobot');
        document.getElementById('ek_urut').value = b.getAttribute('data-urut');
    });
});
</script>