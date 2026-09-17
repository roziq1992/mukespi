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
                            <th>Periode Tanggal</th>
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
                                <?php if ($p->status === 'aktif'): ?>
                                <span class="badge badge-success">Aktif</span>
                                <?php elseif ($p->status === 'selesai'): ?>
                                <span class="badge badge-secondary">Selesai</span>
                                <?php else: ?>
                                <span class="badge badge-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
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
                            <label>Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label>Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control">
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