<style>
:root {
    --asp-primary: #0d6efd;
    --asp-bg: #f4f6fb;
    --asp-card: #ffffff;
    --asp-border: #e2e6ee;
    --asp-text: #1f2430;
    --asp-muted: #6b7280;
    --asp-danger: #e5484d;
    --asp-radius: 12px;
}
.asp-wrap { max-width: 1100px; margin: 0 auto; padding: 16px; background: var(--asp-bg); border-radius: var(--asp-radius); }
.asp-header { background: linear-gradient(135deg, var(--asp-primary), #4f8cff); padding: 18px; border-radius: var(--asp-radius); color: #fff; text-align: center; margin-bottom: 18px; }
.asp-header h2 { margin: 0; font-size: 1.2rem; font-weight: 700; }
.asp-section { background: var(--asp-card); border-radius: var(--asp-radius); padding: 16px; margin-bottom: 14px; border: 1px solid var(--asp-border); }
.asp-section-title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: var(--asp-primary); margin: 0 0 12px; }
.asp-row2, .asp-row3 { display: grid; gap: 12px; }
.asp-row2 { grid-template-columns: 1fr 1fr; }
.asp-row3 { grid-template-columns: 1fr 1fr 1fr; }
@media (max-width: 600px) { .asp-row2, .asp-row3 { grid-template-columns: 1fr; } }
.asp-field { margin-bottom: 12px; }
.asp-field label { display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 4px; }
.asp-field label .req { color: var(--asp-danger); }
.asp-input, .asp-select, .asp-textarea { width: 100%; padding: 10px 14px; border: 1.5px solid var(--asp-border); border-radius: 8px; font-size: 0.95rem; background: #fbfcfe; box-sizing: border-box; }
.asp-input:focus, .asp-select:focus, .asp-textarea:focus { border-color: var(--asp-primary); outline: none; box-shadow: 0 0 0 3px rgba(13,110,253,0.15); }
.asp-textarea { resize: vertical; min-height: 60px; }
.asp-error { color: var(--asp-danger); font-size: 0.8rem; display: block; margin-top: 4px; }
.asp-actions { display: flex; gap: 10px; padding: 12px 0; border-top: 1px solid var(--asp-border); margin-top: 10px; }
.asp-btn { padding: 12px 20px; border-radius: 8px; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; flex: 1; }
.asp-btn-primary { background: var(--asp-primary); color: #fff; }
.asp-btn-secondary { background: #fff; color: var(--asp-text); border: 1.5px solid var(--asp-border); }

.pasien-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-top: 6px; }
@media (max-width: 700px) { .pasien-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 450px) { .pasien-grid { grid-template-columns: 1fr; } }
.pasien-item { background: #f8fafc; padding: 8px 10px; border-radius: 6px; border: 1px solid #eef2f6; }
.pasien-item label { display: block; font-size: 0.65rem; font-weight: 700; color: var(--asp-muted); text-transform: uppercase; margin-bottom: 2px; }
.pasien-item input, .pasien-item textarea { width: 100%; padding: 5px 8px; border: 1px solid var(--asp-border); border-radius: 4px; font-size: 0.82rem; background: #fff; box-sizing: border-box; }
.pasien-item input:focus, .pasien-item textarea:focus { border-color: var(--asp-primary); outline: none; }
.pasien-item textarea { min-height: 35px; resize: vertical; }

.accordion-ruang { border: 1px solid var(--asp-border); border-radius: 8px; margin-bottom: 8px; overflow: hidden; }
.accordion-ruang > summary { list-style: none; cursor: pointer; padding: 10px 14px; font-weight: 700; font-size: 0.85rem; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; }
.accordion-ruang > summary::-webkit-details-marker { display: none; }
.accordion-ruang > summary::after { content: '+'; font-size: 1.1rem; color: var(--asp-primary); }
.accordion-ruang[open] > summary::after { content: '−'; }
.accordion-ruang-body { padding: 10px 14px; }
</style>

<div class="asp-wrap">
    <div class="asp-header">
        <h2>Form Serah Terima Pasien</h2>
        <p style="margin:4px 0 0;opacity:0.9;font-size:0.85rem;">Operan Keperawatan Antar Shift</p>
    </div>

    <?php
    $msg = $this->session->userdata('message');
    if ($msg <> '') { echo '<div style="background:#d1ecf1;padding:10px 14px;border-radius:8px;margin-bottom:14px;">' . $msg . '</div>'; }
    ?>

    <form action="<?php echo $action; ?>" method="post">
        <!-- ===== HEADER ===== -->
        <div class="asp-section">
            <p class="asp-section-title">Informasi Umum</p>
            <div class="asp-row3">
                <div class="asp-field">
                    <label>Hari / Tanggal <span class="req">*</span></label>
                    <input type="date" class="asp-input" name="hari_tanggal" value="<?php echo $hari_tanggal; ?>">
                    <?php echo form_error('hari_tanggal', '<span class="asp-error">', '</span>'); ?>
                </div>
                <div class="asp-field">
                    <label>Shift Dari <span class="req">*</span></label>
                    <select class="asp-select" name="shift_dari">
                        <option value="">Pilih</option>
                        <option value="1" <?php if ($shift_dari == '1') echo 'selected'; ?>>Shift 1</option>
                        <option value="2" <?php if ($shift_dari == '2') echo 'selected'; ?>>Shift 2</option>
                        <option value="3" <?php if ($shift_dari == '3') echo 'selected'; ?>>Shift 3</option>
                    </select>
                    <?php echo form_error('shift_dari', '<span class="asp-error">', '</span>'); ?>
                </div>
                <div class="asp-field">
                    <label>Shift Ke <span class="req">*</span></label>
                    <select class="asp-select" name="shift_ke">
                        <option value="">Pilih</option>
                        <option value="1" <?php if ($shift_ke == '1') echo 'selected'; ?>>Shift 1</option>
                        <option value="2" <?php if ($shift_ke == '2') echo 'selected'; ?>>Shift 2</option>
                        <option value="3" <?php if ($shift_ke == '3') echo 'selected'; ?>>Shift 3</option>
                    </select>
                    <?php echo form_error('shift_ke', '<span class="asp-error">', '</span>'); ?>
                </div>
            </div>
            <div class="asp-row2">
                <div class="asp-field">
                    <label>Departemen / Divisi <span class="req">*</span></label>
                    <select class="asp-select" name="departemen">
                        <option value="">Pilih</option>
                        <option value="Medis" <?php if ($departemen == 'Medis') echo 'selected'; ?>>Medis</option>
                        <option value="Keperawatan" <?php if ($departemen == 'Keperawatan') echo 'selected'; ?>>Keperawatan</option>
                        <option value="Medis/Keperawatan" <?php if ($departemen == 'Medis/Keperawatan') echo 'selected'; ?>>Medis / Keperawatan</option>
                    </select>
                    <?php echo form_error('departemen', '<span class="asp-error">', '</span>'); ?>
                </div>
                <div class="asp-field">
                    <label>Total Pasien Ranap</label>
                    <input type="number" class="asp-input" name="jumlah_pasien_ranap" value="<?php echo $jumlah_pasien_ranap; ?>" min="0">
                </div>
            </div>
        </div>

        <!-- ===== JUMLAH PASIEN PER RUANG ===== -->
        <div class="asp-section">
            <p class="asp-section-title">Jumlah Pasien Per Ruang</p>
            <div class="asp-row3">
                <div class="asp-field"><label>NS 1</label><input type="number" class="asp-input" name="jp_ns1" value="<?php echo $jp_ns1; ?>" min="0"></div>
                <div class="asp-field"><label>NS 2</label><input type="number" class="asp-input" name="jp_ns2" value="<?php echo $jp_ns2; ?>" min="0"></div>
                <div class="asp-field"><label>NS 3</label><input type="number" class="asp-input" name="jp_ns3" value="<?php echo $jp_ns3; ?>" min="0"></div>
                <div class="asp-field"><label>ICU</label><input type="number" class="asp-input" name="jp_icu" value="<?php echo $jp_icu; ?>" min="0"></div>
                <div class="asp-field"><label>PICU</label><input type="number" class="asp-input" name="jp_picu" value="<?php echo $jp_picu; ?>" min="0"></div>
                <div class="asp-field"><label>NICU</label><input type="number" class="asp-input" name="jp_nicu" value="<?php echo $jp_nicu; ?>" min="0"></div>
                <div class="asp-field"><label>VK</label><input type="number" class="asp-input" name="jp_vk" value="<?php echo $jp_vk; ?>" min="0"></div>
                <div class="asp-field"><label>R. Bayi</label><input type="number" class="asp-input" name="jp_r_bayi" value="<?php echo $jp_r_bayi; ?>" min="0"></div>
                <div class="asp-field"><label>IGD</label><input type="number" class="asp-input" name="jp_igd" value="<?php echo $jp_igd; ?>" min="0"></div>
                <div class="asp-field"><label>OK</label><input type="number" class="asp-input" name="jp_ok" value="<?php echo $jp_ok; ?>" min="0"></div>
            </div>
        </div>

        <!-- ===== DATA PASIEN PER RUANG (ACCORDION) ===== -->
        <div class="asp-section">
            <p class="asp-section-title">Data Pasien Per Ruang</p>
            
            <?php
           
$ruang_list = [
    'icu' => 'ICU', 'picu' => 'PICU', 'nicu' => 'NICU',
    'arofah' => 'Arofah', 'muzd' => 'Muzd', 'mina' => 'Mina',
    'marwah' => 'Marwah', 'safa' => 'Safa', 'multazam' => 'Multazam',
    'vk' => 'VK', 'r_bayi' => 'R.Bayi', 'ok' => 'OK', 'igd' => 'IGD'
];

foreach ($ruang_list as $key => $label):
    // PASTIKAN: gunakan $data_ruang[$key] BUKAN $data_ruang[$key] 
    $data_ruang_this = isset($data_ruang[$key]) ? $data_ruang[$key] : array_fill(1, 8, ['nama_pasien' => '', 'diagnosa' => '', 'keterangan' => '']);
?>
<details class="accordion-ruang" <?php echo in_array($key, ['icu', 'arofah']) ? 'open' : ''; ?>>
    <summary><?php echo $label; ?> <span style="font-weight:400;font-size:0.75rem;color:var(--asp-muted);text-transform:none;">(isi data pasien)</span></summary>
    <div class="accordion-ruang-body">
        <div class="pasien-grid">
            <?php for ($i = 1; $i <= 8; $i++): ?>
            <div class="pasien-item">
                <label>Bed <?php echo $i; ?></label>
                <input type="text" name="data_<?php echo $key; ?>[<?php echo $i; ?>][nama_pasien]" placeholder="Nama Pasien" value="<?php echo $data_ruang_this[$i]['nama_pasien'] ?? ''; ?>">
                <input type="text" name="data_<?php echo $key; ?>[<?php echo $i; ?>][diagnosa]" placeholder="Diagnosa" value="<?php echo $data_ruang_this[$i]['diagnosa'] ?? ''; ?>" style="margin-top:4px;">
                <input type="text" name="data_<?php echo $key; ?>[<?php echo $i; ?>][keterangan]" placeholder="Keterangan (dokter/dll)" value="<?php echo $data_ruang_this[$i]['keterangan'] ?? ''; ?>" style="margin-top:4px;">
            </div>
            <?php endfor; ?>
        </div>
    </div>
</details>
<?php endforeach; ?>
        </div>

        <!-- ===== REKOMENDASI & CATATAN ===== -->
        <div class="asp-section">
            <p class="asp-section-title">Rekomendasi & Catatan</p>
            <div class="asp-field">
                <label>Rekomendasi</label>
                <textarea class="asp-textarea" name="rekomendasi" rows="3"><?php echo $rekomendasi; ?></textarea>
            </div>
            <div class="asp-field">
                <label>Catatan Khusus</label>
                <textarea class="asp-textarea" name="catatan_khusus" rows="2"><?php echo $catatan_khusus; ?></textarea>
            </div>
        </div>

        <!-- ===== PETUGAS ===== -->
        <div class="asp-section">
            <p class="asp-section-title">Petugas</p>
            <div class="asp-row3">
                <div class="asp-field">
                    <label>Perawat Shift 1 <span class="req">*</span></label>
                    <input type="text" class="asp-input" name="perawat_shift1" value="<?php echo $perawat_shift1; ?>" placeholder="Nama Perawat Shift 1">
                    <?php echo form_error('perawat_shift1', '<span class="asp-error">', '</span>'); ?>
                </div>
                <div class="asp-field">
                    <label>Perawat Shift 2 <span class="req">*</span></label>
                    <input type="text" class="asp-input" name="perawat_shift2" value="<?php echo $perawat_shift2; ?>" placeholder="Nama Perawat Shift 2">
                    <?php echo form_error('perawat_shift2', '<span class="asp-error">', '</span>'); ?>
                </div>
                <div class="asp-field">
                    <label>Mengetahui</label>
                    <input type="text" class="asp-input" name="mengetahui" value="<?php echo $mengetahui; ?>" placeholder="Nama">
                </div>
            </div>
        </div>

        <input type="hidden" name="id_operan" value="<?php echo $id_operan; ?>">
        <div class="asp-actions">
            <a href="<?php echo site_url('operan') ?>" class="asp-btn asp-btn-secondary">Batal</a>
            <button type="submit" class="asp-btn asp-btn-primary"><?php echo $button; ?></button>
        </div>
    </form>
</div>

<script>
// Auto-calculate total pasien
document.querySelectorAll('input[name^="jp_"]').forEach(function(el) {
    el.addEventListener('input', function() {
        let total = 0;
        document.querySelectorAll('input[name^="jp_"]').forEach(function(input) {
            total += parseInt(input.value) || 0;
        });
        document.querySelector('input[name="jumlah_pasien_ranap"]').value = total;
    });
});
</script>