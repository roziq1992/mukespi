<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Rapikan Nomor EP</h1>
            <div class="small text-muted">
                BAB <?php echo html_escape($standar->bab); ?> &mdash;
                Standar <?php echo html_escape($standar->no_standar); ?>
            </div>
        </div>
        <a href="<?php echo site_url('master_ep/pokja_standar/' . $pokja->id); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle"></i>
        Yang dirapikan hanya <strong>nomor tampilan</strong>. <strong>Nomor resmi (no. dokumen) tidak diubah sama sekali</strong>,
        supaya tetap bisa dicocokkan ke dokumen cetak auditor.
        EP yang dinonaktifkan tidak ikut dihitung, jadi nomornya memang akan berlompong.
    </div>

    <?php if (empty($rencana)): ?>
        <div class="card shadow">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-check-circle fa-2x mb-3 text-success"></i>
                <div>Belum ada EP aktif di standar ini, tidak ada yang perlu dirapikan.</div>
            </div>
        </div>
    <?php elseif (empty($berubah)): ?>
        <div class="card shadow">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-check-circle fa-2x mb-3 text-success"></i>
                <div>Nomor tampilan sudah berurutan (1 &ndash; <?php echo count($rencana); ?>). Tidak ada yang perlu dirapikan.</div>
                <a href="<?php echo site_url('master_ep/pokja_standar/' . $pokja->id); ?>" class="btn btn-sm btn-outline-primary mt-3">
                    Kembali ke daftar Standar &amp; EP
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <h2 class="h5 mb-0">Pratinjau perubahan</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>EP</th>
                            <th>Isi EP</th>
                            <th class="text-center">Nomor resmi</th>
                            <th class="text-center">Tampilan sekarang</th>
                            <th class="text-center">Tampilan baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rencana as $r): ?>
                            <?php $is_berubah = $r['no_urut_lama'] !== $r['no_urut_baru']; ?>
                            <tr class="<?php echo $is_berubah ? 'table-warning' : ''; ?>">
                                <td><strong>EP <?php echo (int)$r['no_urut_baru']; ?></strong></td>
                                <td>
                                    <?php echo html_escape($r['isi_ep']); ?>
                                </td>
                                <td class="text-center"><code><?php echo (int)$r['no_ep']; ?></code></td>
                                <td class="text-center">
                                    <?php if ($is_berubah): ?>
                                        <del><?php echo $r['no_urut_lama'] === NULL ? '-' : (int)$r['no_urut_lama']; ?></del>
                                    <?php else: ?>
                                        <?php echo (int)$r['no_urut_baru']; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($is_berubah): ?>
                                        <strong><?php echo (int)$r['no_urut_baru']; ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">&mdash;</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-3">
                <form method="post" action="<?php echo site_url('master_ep/rapikan_urutan/' . $standar->id_standar); ?>"
                      onsubmit="return confirm('Rapikan <?php echo count($berubah); ?> nomor tampilan EP? Nomor resmi tidak diubah.');">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-magic"></i> Rapikan <?php echo count($berubah); ?> Nomor
                    </button>
                    <a href="<?php echo site_url('master_ep/pokja_standar/' . $pokja->id); ?>" class="btn btn-outline-secondary ml-2">
                        Batal
                    </a>
                </form>
            </div>
        </div>

        <div class="small text-muted">
            <strong>Catatan:</strong> yang berubah hanya label nomor tampilan. Skor, bukti, dan
            riwayat penilaian tidak tersentuh karena semuanya mengacu ke
            <code>id_ep</code>, bukan ke nomor.
        </div>
    <?php endif; ?>
</div>