<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <a href="<?php echo site_url('penilaian_kinerja'); ?>" class="text-gray-600 small"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="h3 mb-0 text-gray-800 mt-1">Penilai Kepala Unit</h1>
            <div class="small text-muted mt-1">Kelola pengguna yang boleh menilai Kepala Unit selain Direktur &amp; Admin.</div>
        </div>
    </div>

    <div class="alert alert-info py-2 px-3" style="font-size:.85rem;">
        <i class="fas fa-info-circle"></i> Role <strong>Admin</strong> dan <strong>Direktur</strong> otomatis boleh menilai Kepala Unit.
        Tambahkan pengguna di bawah ini untuk memberi hak tambahan (mis. <strong>Kabid Yanmed</strong>).
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus"></i> Tambah Penilai</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($user_options)): ?>
                        <p class="text-muted mb-0">Semua pengguna aktif sudah terdaftar sebagai penilai.</p>
                    <?php else: ?>
                    <form method="post" action="<?php echo site_url('penilaian_kinerja/penilai_add'); ?>">
                        <div class="form-group">
                            <label>Pengguna</label>
                            <select name="id_user" class="form-control" required>
                                <option value="">— Pilih Pengguna —</option>
                                <?php foreach ($user_options as $u): ?>
                                <option value="<?php echo (int) $u->id; ?>">
                                    <?php echo html_escape($u->name); ?> — <?php echo html_escape($u->role_name ?: '-'); ?> (<?php echo html_escape($u->email); ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Keterangan <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="catatan" class="form-control" placeholder="mis. Kabid Yanmed">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Tambahkan</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-shield"></i> Daftar Penilai Tambahan</h6>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($penilai_list)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-user-shield fa-3x mb-3"></i>
                            <p class="mb-0">Belum ada penilai tambahan.</p>
                        </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Keterangan</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($penilai_list as $p): ?>
                                <tr>
                                    <td>
                                        <div class="font-weight-bold"><?php echo html_escape($p->name); ?></div>
                                        <small class="text-muted"><?php echo html_escape($p->email); ?></small>
                                    </td>
                                    <td><span class="badge badge-info"><?php echo html_escape($p->role_name ?: '-'); ?></span></td>
                                    <td><?php echo html_escape($p->catatan ?: '-'); ?></td>
                                    <td class="text-right">
                                        <a href="<?php echo site_url('penilaian_kinerja/penilai_delete/' . $p->id_penilai_kepala); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus penilai ini?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
