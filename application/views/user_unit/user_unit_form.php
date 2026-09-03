<style>
    .uuf-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
        max-width: 640px;
        margin: 0 auto;
    }
    .uuf-header {
        background: linear-gradient(135deg, #6a3ea1 0%, #3d2266 100%);
        color: #fff;
        padding: 22px 24px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .uuf-avatar {
        width: 46px; height: 46px;
        border-radius: 50%;
        background: rgba(255,255,255,0.18);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.1rem;
        flex-shrink: 0;
    }
    .uuf-header h2 { margin: 0; font-size: 1.1rem; font-weight: 700; }
    .uuf-header p { margin: 2px 0 0; font-size: 0.8rem; opacity: 0.85; }

    .uuf-body { padding: 24px; }
    @media (max-width: 576px) { .uuf-body { padding: 16px; } }

    .uuf-toolbar-mini {
        display: flex;
        gap: 10px;
        margin-bottom: 14px;
    }
    .uuf-mini-btn {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6a3ea1;
        background: #f1e9fb;
        border: none;
        border-radius: 20px;
        padding: 5px 12px;
        cursor: pointer;
    }
    .uuf-mini-btn:hover { background: #e2d1f5; }

    .uuf-unit-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 22px;
    }
    @media (max-width: 480px) { .uuf-unit-grid { grid-template-columns: 1fr; } }

    .uuf-unit-item {
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid #eef0f3;
        border-radius: 10px;
        padding: 11px 14px;
        cursor: pointer;
        transition: 0.15s;
    }
    .uuf-unit-item:hover { border-color: #c9b3e6; background: #faf7fd; }
    .uuf-unit-item input { width: 17px; height: 17px; accent-color: #6a3ea1; cursor: pointer; }
    .uuf-unit-item label { margin: 0; font-size: 0.87rem; color: #33475b; cursor: pointer; flex: 1; }

    .uuf-unit-item.checked { border-color: #6a3ea1; background: #f1e9fb; }
    .uuf-unit-item.checked label { color: #3d2266; font-weight: 600; }

    .uuf-empty-units { text-align: center; padding: 30px; color: #8a94a6; }

    .uuf-actions { display: flex; gap: 10px; justify-content: flex-end; }
    .uuf-btn-cancel {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #8a94a6;
        background: #f1f3f6;
        border: none;
        text-decoration: none;
        display: inline-block;
    }
    .uuf-btn-cancel:hover { background: #e5e8ec; color: #33475b; text-decoration: none; }
    .uuf-btn-save {
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #fff;
        background: #6a3ea1;
        border: none;
    }
    .uuf-btn-save:hover { background: #3d2266; }
</style>

<div class="container-fluid">
    <div class="uuf-card">
        <div class="uuf-header">
            <div class="uuf-avatar"><?php echo strtoupper(substr($user->name, 0, 1)) ?></div>
            <div>
                <h2><?php echo $user->name ?></h2>
                <p><?php echo $user->email ?> · Kelola akses unit</p>
            </div>
        </div>

        <div class="uuf-body">
            <?php if ($user->role_id == 1): ?>
                <div class="uuf-empty-units">
                    ⚡ User ini adalah <strong>Admin</strong> dan otomatis memiliki akses ke semua unit.<br>
                    Tidak perlu diatur secara manual.
                </div>
                <div class="uuf-actions">
                    <a href="<?php echo site_url('user_unit'); ?>" class="uuf-btn-cancel">Kembali</a>
                </div>
            <?php else: ?>
                <form action="<?php echo site_url('user_unit/manage_action'); ?>" method="post" id="uuf-form">
                    <input type="hidden" name="user_id" value="<?php echo $user->id ?>">

                    <div class="uuf-toolbar-mini">
                        <button type="button" class="uuf-mini-btn" onclick="uufToggleAll(true)">Pilih Semua</button>
                        <button type="button" class="uuf-mini-btn" onclick="uufToggleAll(false)">Kosongkan</button>
                    </div>

                    <?php if (count($unit_list) == 0): ?>
                        <div class="uuf-empty-units">Belum ada data unit.</div>
                    <?php else: ?>
                        <div class="uuf-unit-grid">
                            <?php foreach ($unit_list as $u):
                                $checked = in_array($u->id_unit, $selected_units);
                            ?>
                                <div class="uuf-unit-item <?php echo $checked ? 'checked' : '' ?>" onclick="uufToggleItem(this)">
                                    <input type="checkbox" name="unit_ids[]" value="<?php echo $u->id_unit ?>"
                                        id="unit_<?php echo $u->id_unit ?>" <?php echo $checked ? 'checked' : '' ?>
                                        onclick="event.stopPropagation()">
                                    <label for="unit_<?php echo $u->id_unit ?>"><?php echo $u->nm_unit ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="uuf-actions">
                        <a href="<?php echo site_url('user_unit'); ?>" class="uuf-btn-cancel">Batal</a>
                        <button type="submit" class="uuf-btn-save">💾 Simpan Akses</button>
                    </div>
                </form>

                <script>
                    function uufToggleItem(el) {
                        var checkbox = el.querySelector('input[type=checkbox]');
                        checkbox.checked = !checkbox.checked;
                        el.classList.toggle('checked', checkbox.checked);
                    }
                    function uufToggleAll(state) {
                        document.querySelectorAll('#uuf-form .uuf-unit-item').forEach(function (item) {
                            var checkbox = item.querySelector('input[type=checkbox]');
                            checkbox.checked = state;
                            item.classList.toggle('checked', state);
                        });
                    }
                </script>
            <?php endif; ?>
        </div>
    </div>
</div>