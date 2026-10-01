<style>
    .upf-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
        max-width: 720px;
        margin: 0 auto;
    }
    .upf-header {
        background: linear-gradient(135deg, #6a3ea1 0%, #3d2266 100%);
        color: #fff;
        padding: 22px 24px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .upf-avatar {
        width: 46px; height: 46px; border-radius: 50%;
        background: rgba(255,255,255,0.18);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.1rem; flex-shrink: 0;
    }
    .upf-header h2 { margin: 0; font-size: 1.1rem; font-weight: 700; }
    .upf-header p { margin: 2px 0 0; font-size: 0.8rem; opacity: 0.85; }

    .upf-body { padding: 24px; }
    @media (max-width: 576px) { .upf-body { padding: 16px; } }

    .upf-legend {
        display: flex; gap: 16px; flex-wrap: wrap;
        font-size: 0.78rem; color: #5b6b80; margin-bottom: 16px;
    }
    .upf-legend span b { font-weight: 700; }

    .upf-pokja-list {
        border: 1px solid #eef0f3;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .upf-pokja-row {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px;
        border-bottom: 1px solid #f1f3f6;
        transition: background 0.15s;
    }
    .upf-pokja-row:last-child { border-bottom: none; }
    .upf-pokja-row:hover { background: #faf8fd; }
    .upf-pokja-row.checked { background: #f6f0fd; }

    .upf-pokja-bab {
        display: inline-block;
        background: #efe6fa; color: #6a3fa0;
        font-size: 0.7rem; font-weight: 800; letter-spacing: 0.04em;
        padding: 3px 10px; border-radius: 20px;
        min-width: 84px; text-align: center;
    }
    .upf-pokja-ket { flex: 1; font-size: 0.87rem; color: #33475b; }

    .upf-toggle { display: flex; gap: 8px; align-items: center; }
    .upf-toggle label {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.76rem; font-weight: 600;
        padding: 5px 11px; border-radius: 20px;
        border: 1px solid #dde3ea; color: #5b6b80;
        cursor: pointer; white-space: nowrap; margin: 0;
        transition: 0.15s;
    }
    .upf-toggle input { display: none; }
    .upf-toggle label.penilai:hover { border-color: #1e8449; color: #1e8449; }
    .upf-toggle label.lihat:hover { border-color: #6a3ea1; color: #6a3ea1; }
    .upf-toggle label.on.penilai { background: #e8f8f0; border-color: #1e8449; color: #1e8449; }
    .upf-toggle label.on.lihat   { background: #eef2f7; border-color: #8a94a6; color: #33475b; }

    .upf-actions { display: flex; gap: 10px; justify-content: flex-end; }
    .upf-btn-cancel {
        padding: 10px 20px; border-radius: 8px; font-size: 0.85rem; font-weight: 600;
        color: #8a94a6; background: #f1f3f6; border: none; text-decoration: none;
        display: inline-block;
    }
    .upf-btn-cancel:hover { background: #e5e8ec; color: #33475b; text-decoration: none; }
    .upf-btn-save {
        padding: 10px 24px; border-radius: 8px; font-size: 0.85rem; font-weight: 600;
        color: #fff; background: #6a3ea1; border: none;
    }
    .upf-btn-save:hover { background: #3d2266; }

    .upf-notice {
        text-align: center; padding: 30px; color: #8a94a6; font-size: 0.87rem;
    }

    @media (max-width: 576px) {
        .upf-pokja-row { flex-wrap: wrap; }
        .upf-pokja-ket { min-width: 100%; order: 3; }
    }
</style>

<div class="container-fluid">
    <div class="upf-card">
        <div class="upf-header">
            <div class="upf-avatar"><?php echo strtoupper(substr($user->name, 0, 1)) ?></div>
            <div>
                <h2><?php echo $user->name ?></h2>
                <p><?php echo $user->email ?> · Kelola akses pokja penilaian</p>
            </div>
        </div>

        <div class="upf-body">
            <?php if ($user->role_id == 1 || $user->role_id == 3): ?>
                <div class="upf-notice">
                    ⚡ User ini adalah <strong><?php echo $user->role_id == 1 ? 'Admin' : 'Surveior' ?></strong>
                    dan otomatis punya akses penuh ke semua pokja.<br>
                    Tidak perlu diatur secara manual.
                </div>
                <div class="upf-actions">
                    <a href="<?php echo site_url('user_pokja_ep'); ?>" class="upf-btn-cancel">Kembali</a>
                </div>
            <?php else: ?>
                <form action="<?php echo site_url('user_pokja_ep/manage_action'); ?>" method="post" id="upf-form">
                    <input type="hidden" name="id_user" value="<?php echo $user->id ?>">

                    <div class="upf-legend">
                        <span><b>Penilai</b> — isi skor, upload/hapus bukti, download dokumen</span>
                        <span><b>Lihat</b> — hanya membuka dokumen di browser</span>
                        <span><b>Kosong</b> — hanya bisa lihat halaman dalam mode baca saja</span>
                    </div>

                    <?php if (count($pokja_list) == 0): ?>
                        <div class="upf-notice">Belum ada data pokja aktif.</div>
                    <?php else: ?>
                        <div class="upf-pokja-list">
                            <?php foreach ($pokja_list as $p):
                                $id_p = intval($p->id);
                                $is_penilai = in_array($id_p, $penilai, TRUE);
                                $is_lihat   = in_array($id_p, $assigned, TRUE) && !$is_penilai;
                            ?>
                            <div class="upf-pokja-row <?php echo $is_penilai ? 'checked' : '' ?>" id="upf-row-<?php echo $id_p ?>">
                                <span class="upf-pokja-bab"><?php echo $p->bab ?></span>
                                <span class="upf-pokja-ket"><?php echo $p->ket ?></span>
                                <span class="upf-toggle">
                                    <label class="penilai <?php echo $is_penilai ? 'on' : '' ?>">
                                        <input type="checkbox" name="pokja_penilai[]" value="<?php echo $id_p ?>"
                                            id="upf-p-<?php echo $id_p ?>" <?php echo $is_penilai ? 'checked' : '' ?>>
                                        ✏️ Penilai
                                    </label>
                                    <label class="lihat <?php echo $is_lihat ? 'on' : '' ?>">
                                        <input type="checkbox" name="pokja_readonly[]" value="<?php echo $id_p ?>"
                                            id="upf-r-<?php echo $id_p ?>" <?php echo $is_lihat ? 'checked' : '' ?>>
                                        👁️ Lihat
                                    </label>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="upf-actions">
                        <a href="<?php echo site_url('user_pokja_ep'); ?>" class="upf-btn-cancel">Batal</a>
                        <button type="submit" class="upf-btn-save">💾 Simpan Akses</button>
                    </div>
                </form>

                <script>
                (function() {
                    function sync(idPokja) {
                        var cP = document.getElementById('upf-p-' + idPokja);
                        var cR = document.getElementById('upf-r-' + idPokja);
                        if (!cP || !cR) return;
                        // Penilai menang: kalau Penilai dicentang, Lihat otomatis dilepas
                        if (cP.checked) cR.checked = false;
                        else if (cR.checked) cP.checked = false;

                        cP.parentNode.classList.toggle('on', cP.checked);
                        cR.parentNode.classList.toggle('on', cR.checked);

                        var row = document.getElementById('upf-row-' + idPokja);
                        row.classList.toggle('checked', cP.checked || cR.checked);
                    }

                    document.querySelectorAll('#upf-form .upf-toggle input').forEach(function(cb) {
                        cb.addEventListener('change', function() {
                            sync(parseInt(this.value, 10));
                        });
                    });
                })();
                </script>
            <?php endif; ?>
        </div>
    </div>
</div>
