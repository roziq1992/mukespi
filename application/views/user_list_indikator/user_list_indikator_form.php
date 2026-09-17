<style>
    .ulif-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
        max-width: 900px;
        margin: 0 auto;
    }
    .ulif-header {
        background: linear-gradient(135deg, #0e7490 0%, #164e63 100%);
        color: #fff;
        padding: 22px 24px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .ulif-avatar {
        width: 46px; height: 46px;
        border-radius: 50%;
        background: rgba(255,255,255,0.18);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.1rem;
        flex-shrink: 0;
    }
    .ulif-header h2 { margin: 0; font-size: 1.1rem; font-weight: 700; }
    .ulif-header p { margin: 2px 0 0; font-size: 0.8rem; opacity: 0.85; }

    .ulif-body { padding: 24px; }
    @media (max-width: 576px) { .ulif-body { padding: 16px; } }

    .ulif-toolbar-mini { display: flex; gap: 10px; margin-bottom: 14px; flex-wrap: wrap; align-items: center; }
    .ulif-mini-btn {
        font-size: 0.78rem;
        font-weight: 600;
        color: #0e7490;
        background: #e0f2fe;
        border: none;
        border-radius: 20px;
        padding: 5px 12px;
        cursor: pointer;
    }
    .ulif-mini-btn:hover { background: #bae6fd; }
    .ulif-search {
        margin-left: auto;
        border: 1px solid #dde3ea;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 0.82rem;
        outline: none;
        min-width: 220px;
        background: #f8fafc;
    }

    .ulif-group {
        border: 1px solid #eef0f3;
        border-radius: 12px;
        margin-bottom: 14px;
        overflow: hidden;
    }
    .ulif-group-head {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f6f9fb;
        padding: 10px 14px;
        border-bottom: 1px solid #eef0f3;
    }
    .ulif-group-head .gname { font-weight: 700; color: #164e63; font-size: 0.9rem; }
    .ulif-group-head .gcount { font-size: 0.72rem; color: #8a94a6; background: #eef2f7; border-radius: 20px; padding: 2px 9px; }
    .ulif-group-head .gtoggle {
        margin-left: auto;
        font-size: 0.74rem;
        font-weight: 600;
        color: #0e7490;
        background: transparent;
        border: 1px solid #bae6fd;
        border-radius: 20px;
        padding: 3px 10px;
        cursor: pointer;
    }
    .ulif-group-head .gtoggle:hover { background: #e0f2fe; }

    .ulif-items { padding: 12px 14px; display: flex; flex-direction: column; gap: 8px; }
    .ulif-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 6px 8px;
        border-radius: 8px;
        cursor: pointer;
    }
    .ulif-item:hover { background: #f8fafc; }
    .ulif-item input { width: 17px; height: 17px; accent-color: #0e7490; cursor: pointer; margin-top: 2px; flex-shrink: 0; }
    .ulif-item .itxt { flex: 1; }
    .ulif-item .ijudul { font-size: 0.86rem; color: #33475b; line-height: 1.35; }
    .ulif-item .imeta { font-size: 0.72rem; color: #8a94a6; }
    .ulif-item.checked { background: #ecfeff; }
    .ulif-item.checked .ijudul { color: #155e75; font-weight: 600; }

    .ulif-empty-units { text-align: center; padding: 30px; color: #8a94a6; }

    .ulif-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; }
    .ulif-btn-cancel {
        padding: 10px 20px; border-radius: 8px; font-size: 0.85rem; font-weight: 600;
        color: #8a94a6; background: #f1f3f6; border: none; text-decoration: none; display: inline-block;
    }
    .ulif-btn-cancel:hover { background: #e5e8ec; color: #33475b; text-decoration: none; }
    .ulif-btn-save {
        padding: 10px 24px; border-radius: 8px; font-size: 0.85rem; font-weight: 600;
        color: #fff; background: #0e7490; border: none;
    }
    .ulif-btn-save:hover { background: #164e63; }
</style>

<div class="container-fluid">
    <div class="ulif-card">
        <div class="ulif-header">
            <div class="ulif-avatar"><?php echo strtoupper(substr($user->name, 0, 1)) ?></div>
            <div>
                <h2><?php echo $user->name ?></h2>
                <p><?php echo $user->email ?> · Kelola akses indikator</p>
            </div>
        </div>

        <div class="ulif-body">
            <?php if ($user->role_id == 1): ?>
                <div class="ulif-empty-units">
                    ⚡ User ini adalah <strong>Admin</strong> dan otomatis memiliki akses ke semua indikator.<br>
                    Tidak perlu diatur secara manual.
                </div>
                <div class="ulif-actions">
                    <a href="<?php echo site_url('user_list_indikator'); ?>" class="ulif-btn-cancel">Kembali</a>
                </div>
            <?php else: ?>
                <form action="<?php echo site_url('user_list_indikator/manage_action'); ?>" method="post" id="ulif-form">
                    <input type="hidden" name="user_id" value="<?php echo $user->id ?>">

                    <div class="ulif-toolbar-mini">
                        <button type="button" class="ulif-mini-btn" onclick="ulifToggleAll(true)">Pilih Semua</button>
                        <button type="button" class="ulif-mini-btn" onclick="ulifToggleAll(false)">Kosongkan</button>
                        <input type="text" class="ulif-search" id="ulif-search" placeholder="Cari judul indikator..." onkeyup="ulifFilter(this.value)">
                    </div>

                    <?php if (empty($indikators_grouped)): ?>
                        <div class="ulif-empty-units">Belum ada data indikator.</div>
                    <?php else: ?>
                        <?php foreach ($indikators_grouped as $group_name => $items): ?>
                            <div class="ulif-group" data-group>
                                <div class="ulif-group-head">
                                    <span class="gname"><?php echo html_escape($group_name) ?></span>
                                    <span class="gcount"><?php echo count($items) ?></span>
                                    <button type="button" class="gtoggle" onclick="ulifToggleGroup(this, true)">Pilih grup</button>
                                    <button type="button" class="gtoggle" onclick="ulifToggleGroup(this, false)">Kosongkan</button>
                                </div>
                                <div class="ulif-items">
                                    <?php foreach ($items as $ind):
                                        $checked = in_array((int) $ind->id_indikator, $selected_indikators, true);
                                    ?>
                                        <label class="ulif-item <?php echo $checked ? 'checked' : '' ?>"
                                               data-search="<?php echo html_escape(strtolower($ind->judul . ' ' . $ind->jenis . ' ' . $ind->kelompok . ' ' . $group_name)) ?>">
                                            <input type="checkbox" name="id_indikators[]" value="<?php echo (int) $ind->id_indikator ?>"
                                                <?php echo $checked ? 'checked' : '' ?>
                                                onchange="this.closest('.ulif-item').classList.toggle('checked', this.checked)">
                                            <span class="itxt">
                                                <span class="ijudul"><?php echo html_escape($ind->judul) ?></span>
                                                <span class="imeta"><?php echo html_escape($ind->kelompok) ?><?php echo $ind->jenis ? ' · ' . html_escape($ind->jenis) : '' ?></span>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <div class="ulif-actions">
                        <a href="<?php echo site_url('user_list_indikator'); ?>" class="ulif-btn-cancel">Batal</a>
                        <button type="submit" class="ulif-btn-save">💾 Simpan Akses</button>
                    </div>
                </form>

                <script>
                    function ulifToggleGroup(btn, state) {
                        var head = btn.closest('.ulif-group-head');
                        var group = head.parentNode;
                        group.querySelectorAll('.ulif-items input[type=checkbox]').forEach(function (cb) {
                            cb.checked = state;
                            cb.closest('.ulif-item').classList.toggle('checked', state);
                        });
                    }
                    function ulifToggleAll(state) {
                        document.querySelectorAll('#ulif-form .ulif-items input[type=checkbox]').forEach(function (cb) {
                            cb.checked = state;
                            cb.closest('.ulif-item').classList.toggle('checked', state);
                        });
                    }
                    function ulifFilter(term) {
                        term = (term || '').toLowerCase().trim();
                        document.querySelectorAll('#ulif-form [data-group]').forEach(function (group) {
                            var visible = 0;
                            group.querySelectorAll('.ulif-item').forEach(function (item) {
                                var match = term === '' || item.getAttribute('data-search').indexOf(term) !== -1;
                                item.style.display = match ? '' : 'none';
                                if (match) visible++;
                            });
                            group.style.display = visible === 0 ? 'none' : '';
                        });
                    }
                </script>
            <?php endif; ?>
        </div>
    </div>
</div>
