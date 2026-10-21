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
            $id = isset($row->id_ep) ? (int)$row->id_ep : 0;
            $id_standar = set_value('id_standar', isset($row->id_standar) ? $row->id_standar : '');
            $no_ep = set_value('no_ep', isset($row->no_ep) ? $row->no_ep : '');
            $isi_ep = set_value('isi_ep', isset($row->isi_ep) ? $row->isi_ep : '');
            $jenis_bukti = set_value('jenis_bukti', isset($row->jenis_bukti) ? $row->jenis_bukti : 'R');
            $skor_maks = set_value('skor_maks', isset($row->skor_maks) ? $row->skor_maks : 10);
            $active = set_value('active', isset($row->active) ? $row->active : 'Y');
            ?>
            <form method="post" action="<?php echo site_url('master_ep/elemen_form/' . $id); ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Standar</label>
                            <input type="text" id="cari_standar" class="form-control form-control-sm" placeholder="Cari nomor / isi standar..." autocomplete="off">
                            <select class="form-control form-control-sm mt-1" name="id_standar" id="pilih_standar" required>
                                <option value="">-- Pilih Standar --</option>
                                <?php
                                $bab_nama = array();
                                foreach ((isset($bab_list) ? $bab_list : array()) as $b) {
                                    $bab_nama[$b->bab] = $b->ket;
                                }
                                $grup_bab = null;
                                foreach ($standar_list as $s):
                                    $bab = strtoupper((string)$s->bab);
                                    if ($grup_bab !== $bab) {
                                        if ($grup_bab !== null) {
                                            echo '</optgroup>';
                                        }
                                        $judul = $bab . (!empty($bab_nama[$bab]) ? ' - ' . $bab_nama[$bab] : '');
                                        echo '<optgroup label="' . html_escape($judul) . '">';
                                        $grup_bab = $bab;
                                    }
                                    $label = $s->no_standar . ' - ' . $s->isi_standar;
                                    if ($s->active !== 'Y') {
                                        $label .= ' (Nonaktif)';
                                    }
                                    ?>
                                    <option value="<?php echo (int)$s->id_standar; ?>" data-search="<?php echo html_escape($s->bab . ' ' . $label); ?>" <?php echo ($id_standar == $s->id_standar) ? 'selected' : ''; ?>><?php echo html_escape($label); ?></option>
                                    <?php
                                endforeach;
                                if ($grup_bab !== null) {
                                    echo '</optgroup>';
                                }
                                ?>
                            </select>
                            <small class="form-text text-muted" id="info_standar"></small>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Nomor EP</label>
                            <input type="number" class="form-control" name="no_ep" value="<?php echo html_escape($no_ep); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Skor Maks</label>
                            <input type="number" class="form-control" name="skor_maks" value="<?php echo html_escape($skor_maks); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Jenis Bukti</label>
                            <select class="form-control" name="jenis_bukti">
                                <option value="R" <?php echo ($jenis_bukti === 'R') ? 'selected' : ''; ?>>R</option>
                                <option value="D" <?php echo ($jenis_bukti === 'D') ? 'selected' : ''; ?>>D</option>
                                <option value="O" <?php echo ($jenis_bukti === 'O') ? 'selected' : ''; ?>>O</option>
                                <option value="W" <?php echo ($jenis_bukti === 'W') ? 'selected' : ''; ?>>W</option>
                                <option value="S" <?php echo ($jenis_bukti === 'S') ? 'selected' : ''; ?>>S</option>
                            </select>
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

                <div class="form-group">
                    <label>Isi Elemen Penilaian</label>
                    <textarea class="form-control" name="isi_ep" rows="4" required><?php echo html_escape($isi_ep); ?></textarea>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?php echo site_url('master_ep/elemen'); ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/js/master-ep.js'); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        MasterEp.initStandarPicker({
            input: '#cari_standar',
            select: '#pilih_standar',
            info: '#info_standar'
        });
    });
</script>
