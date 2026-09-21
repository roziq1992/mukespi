<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <a href="<?php echo site_url('penilaian_kinerja'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Kelola Periode Penilaian</h1>
        </div>
        <?php if ($is_admin): ?>
        <button class="btn btn-primary btn-sm" onclick="$('#modalPeriode').modal('show')"><i class="fas fa-plus"></i> Tambah Periode</button>
        <?php endif; ?>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Periode</th>
                            <th>Tahun</th>
                            <th>Periode Penilaian</th>
                            <th>Periode Input</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($periodes as $p): ?>
                        <tr>
                            <td class="font-weight-bold"><?php echo html_escape($p->nama); ?></td>
                            <td><?php echo (int) $p->tahun; ?></td>
                            <td>
                                <?php echo $p->tanggal_mulai ? date('d M Y', strtotime($p->tanggal_mulai)) : '-'; ?>
                                &ndash;
                                <?php echo $p->tanggal_selesai ? date('d M Y', strtotime($p->tanggal_selesai)) : '-'; ?>
                            </td>
                            <td>
                                <?php echo $p->input_mulai ? date('d M Y', strtotime($p->input_mulai)) : '-'; ?>
                                &ndash;
                                <?php echo $p->input_selesai ? date('d M Y', strtotime($p->input_selesai)) : '-'; ?>
                            </td>
                            <td>
                                <?php if ($p->status === 'aktif'): ?>
                                <span class="badge badge-success">Aktif</span>
                                <?php elseif ($p->status === 'selesai'): ?>
                                <span class="badge badge-secondary">Selesai</span>
                                <?php else: ?>
                                <span class="badge badge-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <?php if ($is_admin): ?>
                                <a href="#" class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modalPeriodeEdit"
                                   data-id="<?php echo (int) $p->id_periode; ?>"
                                   data-nama="<?php echo html_escape($p->nama); ?>"
                                   data-tahun="<?php echo (int) $p->tahun; ?>"
                                   data-mulai="<?php echo html_escape($p->tanggal_mulai); ?>"
                                   data-selesai="<?php echo html_escape($p->tanggal_selesai); ?>"
                                   data-imulai="<?php echo html_escape($p->input_mulai); ?>"
                                   data-iselesai="<?php echo html_escape($p->input_selesai); ?>"
                                   data-status="<?php echo html_escape($p->status); ?>"
                                   onclick="openEditPeriode(this)"><i class="fas fa-edit"></i> Edit</a>
                                <?php endif; ?>
                                <?php if ($is_admin && $p->status !== 'aktif'): ?>
                                <a href="<?php echo site_url('penilaian_kinerja/periode_setaktif/' . $p->id_periode); ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Aktifkan periode ini? Periode aktif sebelumnya akan otomatis diselesaikan.')"><i class="fas fa-check"></i> Aktifkan</a>
                                <?php endif; ?>
                                <?php if ($is_admin): ?>
                                <a href="<?php echo site_url('penilaian_kinerja/rekap/' . $p->id_periode); ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-chart-bar"></i> Rekap</a>
                                <a href="<?php echo site_url('penilaian_kinerja/periode_delete/' . $p->id_periode); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus periode ini? Data penilaian terkait juga akan dihapus.')"><i class="fas fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Periode -->
<div class="modal fade" id="modalPeriodeEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="<?php echo site_url('penilaian_kinerja/periode_edit'); ?>">
            <input type="hidden" name="id_periode" id="pedit_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Periode Penilaian</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Periode</label>
                        <input type="text" name="nama" id="pedit_nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="number" name="tahun" id="pedit_tahun" class="form-control" min="2000" max="2100" required>
                    </div>
                    <div class="form-row">
                        <div class="col form-group">
                            <label>Periode Penilaian &mdash; Mulai</label>
                            <input type="date" name="tanggal_mulai" id="pedit_mulai" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label>Periode Penilaian &mdash; Selesai</label>
                            <input type="date" name="tanggal_selesai" id="pedit_selesai" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col form-group">
                            <label>Periode Input &mdash; Mulai</label>
                            <input type="date" name="input_mulai" id="pedit_imulai" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label>Periode Input &mdash; Selesai</label>
                            <input type="date" name="input_selesai" id="pedit_iselesai" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="pedit_status" class="form-control">
                            <option value="draft">Draft</option>
                            <option value="aktif">Aktif</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <small class="form-text text-muted"><strong>Periode Penilaian</strong> = rentang waktu yang dinilai. <strong>Periode Input</strong> = rentang tanggal saat penilaian boleh diisi (di luar rentang tsb harus pakai bypass).</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openEditPeriode(btn) {
    document.getElementById('pedit_id').value = btn.getAttribute('data-id');
    document.getElementById('pedit_nama').value = btn.getAttribute('data-nama');
    document.getElementById('pedit_tahun').value = btn.getAttribute('data-tahun');
    document.getElementById('pedit_mulai').value = btn.getAttribute('data-mulai');
    document.getElementById('pedit_selesai').value = btn.getAttribute('data-selesai');
    document.getElementById('pedit_imulai').value = btn.getAttribute('data-imulai');
    document.getElementById('pedit_iselesai').value = btn.getAttribute('data-iselesai');
    document.getElementById('pedit_status').value = btn.getAttribute('data-status');
    $('#modalPeriodeEdit').modal('show');
}
</script>

<!-- Modal Tambah Periode -->
<div class="modal fade" id="modalPeriode" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="<?php echo site_url('penilaian_kinerja/periode_add'); ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Periode Penilaian</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Periode</label>
                        <input type="text" name="nama" class="form-control" required placeholder="contoh: Penilaian Kinerja 2025">
                    </div>
                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="number" name="tahun" class="form-control" min="2000" max="2100" value="<?php echo date('Y'); ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="col form-group">
                            <label>Periode Penilaian &mdash; Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label>Periode Penilaian &mdash; Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col form-group">
                            <label>Periode Input &mdash; Mulai</label>
                            <input type="date" name="input_mulai" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label>Periode Input &mdash; Selesai</label>
                            <input type="date" name="input_selesai" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="draft">Draft</option>
                            <option value="aktif">Aktif (periode ini langsung dipakai)</option>
                        </select>
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