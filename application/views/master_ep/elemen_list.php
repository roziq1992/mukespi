<?php
$qs = array('status' => $status);
if (trim($q) !== '') {
    $qs['q'] = $q;
}
if (!empty($filter['bab'])) {
    $qs['bab'] = $filter['bab'];
}
if (!empty($filter['id_standar'])) {
    $qs['standar'] = (int)$filter['id_standar'];
}
if (!empty($filter['jenis_bukti'])) {
    $qs['jenis_bukti'] = $filter['jenis_bukti'];
}
$qs_str = http_build_query($qs);

$qs_pill = $qs;
unset($qs_pill['status']);
$qs_pill_str = $qs_pill ? ('?' . http_build_query($qs_pill)) : '';

$url_xls = site_url('master_ep/elemen_excel') . '?' . $qs_str;
$url_csv = site_url('master_ep/elemen_excel') . '?' . $qs_str . '&format=csv';

$filter_aktif = array();
if (!empty($filter['bab'])) {
    $filter_aktif[] = 'BAB ' . $filter['bab'];
}
if (!empty($filter['id_standar'])) {
    $std_terpilih = null;
    foreach ($standar_filter_list as $s) {
        if ((int)$s->id_standar === (int)$filter['id_standar']) {
            $std_terpilih = $s;
            break;
        }
    }
    $filter_aktif[] = 'Standar ' . ($std_terpilih ? $std_terpilih->no_standar . ' - ' . $std_terpilih->isi_standar : $filter['id_standar']);
}
if (!empty($filter['jenis_bukti'])) {
    $filter_aktif[] = 'Jenis bukti ' . $filter['jenis_bukti'];
}
if (trim($q) !== '') {
    $filter_aktif[] = 'Kata kunci "' . $q . '"';
}
?>
<div class="container-fluid">
    <?php $flash = $this->session->flashdata('message'); ?>
    <?php if ($flash): ?><div class="mt-3"><?php echo $flash; ?></div><?php endif; ?>

    <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Data Elemen Penilaian</h1>
            <div class="small text-muted">Saat dihapus, data hanya akan dinonaktifkan dan tetap tersimpan di database.</div>
        </div>
        <div class="d-flex align-items-center">
            <a href="<?php echo $url_xls; ?>" class="btn btn-sm btn-success mr-2" title="Unduh data EP yang sedang difilter ke Excel">
                <i class="fas fa-file-excel"></i> Download Excel
            </a>
            <a href="<?php echo $url_csv; ?>" class="btn btn-sm btn-outline-success mr-2" title="Unduh data EP yang sedang difilter ke CSV (UTF-8)">
                <i class="fas fa-file-csv"></i> CSV
            </a>
            <a href="<?php echo site_url('master_ep/elemen_form'); ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah EP</a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3 mb-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs text-uppercase font-weight-bold text-success">Aktif</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int)$stats['aktif']; ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs text-uppercase font-weight-bold text-secondary">Nonaktif</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int)$stats['nonaktif']; ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs text-uppercase font-weight-bold text-info">Ditampilkan</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($rows); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link <?php echo $status === 'aktif' ? 'active' : ''; ?>" href="<?php echo site_url('master_ep/elemen/aktif') . $qs_pill_str; ?>">Aktif</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo $status === 'nonaktif' ? 'active' : ''; ?>" href="<?php echo site_url('master_ep/elemen/nonaktif') . $qs_pill_str; ?>">Nonaktif</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo $status === 'all' ? 'active' : ''; ?>" href="<?php echo site_url('master_ep/elemen/all') . $qs_pill_str; ?>">Semua</a></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <?php if (!empty($filter_aktif)): ?>
                        <div class="small text-muted text-right">
                            Filter:
                            <?php foreach ($filter_aktif as $i => $f): ?>
                                <span class="badge badge-info"><?php echo html_escape($f); ?></span>
                            <?php endforeach; ?>
                            <a href="<?php echo site_url('master_ep/elemen/' . $status); ?>" class="ml-1">reset</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="get" action="<?php echo site_url('master_ep/elemen/' . $status); ?>" class="form-row align-items-end">
                <div class="col-md-2 mb-2">
                    <label class="small mb-1">BAB / Pokja</label>
                    <select name="bab" id="filter_bab" class="form-control form-control-sm">
                        <option value="">Semua BAB</option>
                        <?php foreach ($bab_list as $b): ?>
                            <option value="<?php echo html_escape($b->bab); ?>" <?php echo ($filter['bab'] === $b->bab) ? 'selected' : ''; ?>><?php echo html_escape($b->bab . ' - ' . $b->ket); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small mb-1">Cari standar</label>
                    <input type="text" id="filter_cari_standar" class="form-control form-control-sm" placeholder="Nomor / isi standar..." autocomplete="off">
                    <select name="standar" id="filter_standar" class="form-control form-control-sm mt-1">
                        <option value="">Semua standar</option>
                        <?php
                        $grup_bab = null;
                        foreach ($standar_filter_list as $s):
                            $bab = strtoupper((string)$s->bab);
                            if ($grup_bab !== $bab) {
                                if ($grup_bab !== null) {
                                    echo '</optgroup>';
                                }
                                echo '<optgroup label="' . html_escape($bab) . '">';
                                $grup_bab = $bab;
                            }
                            $label = $s->no_standar . ' - ' . $s->isi_standar;
                            ?>
                            <option value="<?php echo (int)$s->id_standar; ?>" data-search="<?php echo html_escape($s->bab . ' ' . $label); ?>" <?php echo ((int)$filter['id_standar'] === (int)$s->id_standar) ? 'selected' : ''; ?>><?php echo html_escape($label); ?></option>
                            <?php
                        endforeach;
                        if ($grup_bab !== null) {
                            echo '</optgroup>';
                        }
                        ?>
                    </select>
                    <small class="form-text text-muted" id="filter_info_standar"></small>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small mb-1">Jenis Bukti</label>
                    <select name="jenis_bukti" class="form-control form-control-sm">
                        <option value="">Semua</option>
                        <?php foreach (array('R', 'D', 'O', 'W', 'S') as $jb): ?>
                            <option value="<?php echo $jb; ?>" <?php echo ($filter['jenis_bukti'] === $jb) ? 'selected' : ''; ?>><?php echo $jb; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small mb-1">Kata Kunci</label>
                    <input type="text" name="q" value="<?php echo html_escape($q); ?>" class="form-control form-control-sm" placeholder="No. EP / isi EP...">
                </div>
                <div class="col-md-2 mb-2">
                    <button class="btn btn-sm btn-primary mr-1" type="submit"><i class="fas fa-filter"></i> Terapkan</button>
                    <a href="<?php echo site_url('master_ep/elemen/' . $status); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>BAB</th>
                            <th>Standar</th>
                            <th class="text-center">No. EP</th>
                            <th>Isi EP</th>
                            <th>Skor</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data yang cocok dengan filter.</td></tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($rows as $row): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><span class="badge badge-light text-dark"><?php echo html_escape($row->bab); ?></span></td>
                                    <td><?php echo html_escape($row->no_standar); ?></td>
                                    <td>
                                        <strong><?php echo html_escape($row->no_urut !== NULL ? $row->no_urut : $row->no_ep); ?></strong>
                                        <?php if ((int)$row->no_urut !== (int)$row->no_ep): ?>
                                            <div class="small text-muted">no. resmi: <?php echo html_escape($row->no_ep); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo html_escape($row->isi_ep); ?></td>
                                    <td><?php echo (int)$row->skor_maks; ?></td>
                                    <td>
                                        <?php if ($row->active === 'Y'): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?php echo site_url('master_ep/elemen_form/' . $row->id_ep); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <?php if ($row->active === 'Y'): ?>
                                            <a href="<?php echo site_url('master_ep/elemen_delete/' . $row->id_ep); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Nonaktifkan data elemen penilaian ini? Data tidak akan dihapus permanen.')">Nonaktif</a>
                                        <?php else: ?>
                                            <a href="<?php echo site_url('master_ep/elemen_toggle/' . $row->id_ep . '/Y'); ?>" class="btn btn-sm btn-outline-success">Aktifkan</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/js/master-ep.js'); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        MasterEp.initStandarPicker({
            input: '#filter_cari_standar',
            select: '#filter_standar',
            info: '#filter_info_standar'
        });
    });
</script>
