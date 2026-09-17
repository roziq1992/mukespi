<style>
    .uli-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
    }
    .uli-header {
        background: linear-gradient(135deg, #0e7490 0%, #164e63 100%);
        color: #fff;
        padding: 22px 24px;
    }
    .uli-header h2 { margin: 0; font-size: 1.2rem; font-weight: 700; }
    .uli-header p { margin: 4px 0 0; font-size: 0.8rem; opacity: 0.85; }

    .uli-body { padding: 22px; }
    @media (max-width: 576px) { .uli-body { padding: 14px; } }

    .uli-toolbar { display: flex; justify-content: flex-end; margin-bottom: 18px; }
    .uli-search-wrap {
        display: flex;
        border: 1px solid #dde3ea;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
        max-width: 320px;
        width: 100%;
    }
    .uli-search-wrap input {
        border: none;
        background: transparent;
        padding: 9px 12px;
        flex: 1;
        font-size: 0.85rem;
        outline: none;
    }
    .uli-search-wrap button {
        border: none;
        background: #0e7490;
        color: #fff;
        padding: 0 16px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .uli-search-reset { font-size: 0.78rem; color: #8a94a6; margin-left: 8px; align-self: center; white-space: nowrap; }

    .uli-flash {
        background: #e0f2fe;
        color: #164e63;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 16px;
    }

    .uli-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .uli-table thead th {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #8a94a6;
        font-weight: 700;
        border-bottom: 2px solid #eef0f3;
        padding: 10px 12px;
        white-space: nowrap;
    }
    .uli-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #f1f3f6;
        font-size: 0.87rem;
        color: #33475b;
        vertical-align: middle;
    }
    .uli-table tbody tr:hover { background: #f8fafc; }

    .uli-user-cell { display: flex; align-items: center; gap: 10px; }
    .uli-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0e7490, #164e63);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem;
        flex-shrink: 0;
    }
    .uli-user-name { font-weight: 600; color: #23324a; }
    .uli-user-email { font-size: 0.78rem; color: #8a94a6; }

    .uli-role-badge {
        display: inline-block;
        padding: 3px 11px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        background: #eef2f7;
        color: #33475b;
    }
    .uli-role-badge.admin { background: #fdecea; color: #c0392b; }

    .uli-count-chip {
        display: inline-block;
        background: #e0f2fe;
        color: #0e7490;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 20px;
        margin-bottom: 6px;
    }
    .uli-ind-badges { display: flex; flex-wrap: wrap; gap: 6px; max-width: 420px; }
    .uli-ind-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfeff;
        color: #155e75;
        font-size: 0.74rem;
        font-weight: 600;
        padding: 4px 8px 4px 11px;
        border-radius: 20px;
        white-space: nowrap;
        max-width: 100%;
    }
    .uli-ind-chip a { color: #67b7c9; text-decoration: none; font-weight: 800; line-height: 1; }
    .uli-ind-chip a:hover { color: #c0392b; }
    .uli-ind-more { font-size: 0.74rem; color: #8a94a6; font-style: italic; align-self: center; }
    .uli-unit-empty { font-size: 0.78rem; color: #b6bcc7; font-style: italic; }

    .uli-manage-btn {
        background: #0e7490;
        color: #fff;
        border-radius: 8px;
        padding: 7px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        display: inline-block;
    }
    .uli-manage-btn:hover { background: #164e63; color: #fff; text-decoration: none; }

    .uli-empty { text-align: center; padding: 40px 16px; color: #8a94a6; }
    .uli-empty .icon { font-size: 2.2rem; margin-bottom: 8px; }

    .uli-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
    }
    .uli-total-chip {
        background: #eef2f7;
        color: #33475b;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
    }
    .uli-pagination ul { margin: 0; }

    @media (max-width: 768px) {
        .uli-table thead { display: none; }
        .uli-table, .uli-table tbody, .uli-table tr, .uli-table td { display: block; width: 100%; }
        .uli-table tr { border: 1px solid #eef0f3; border-radius: 10px; margin-bottom: 12px; padding: 12px; }
        .uli-table td { border: none; padding: 6px 0; }
        .uli-table td::before {
            content: attr(data-label);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #8a94a6;
            display: block;
            margin-bottom: 4px;
        }
        .uli-ind-badges { max-width: none; }
    }
</style>

<div class="container-fluid">
    <div class="uli-card">
        <div class="uli-header">
            <h2>🔑 Akses Indikator User</h2>
            <p>Atur indikator mana saja yang bisa diakses tiap user</p>
        </div>

        <div class="uli-body">

            <?php
                $flash = $this->session->userdata('message');
                if ($flash <> '') {
                    echo '<div class="uli-flash">' . $flash . '</div>';
                }
            ?>

            <div class="uli-toolbar">
                <form action="<?php echo site_url('user_list_indikator/index'); ?>" method="get" style="display:flex; align-items:center;">
                    <div class="uli-search-wrap">
                        <input type="text" name="q" placeholder="Cari nama atau email..." value="<?php echo $q; ?>">
                        <button type="submit">🔍</button>
                    </div>
                    <?php if ($q <> ''): ?>
                        <a href="<?php echo site_url('user_list_indikator'); ?>" class="uli-search-reset">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-responsive">
                <table class="uli-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Indikator yang Diakses</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users_data) == 0): ?>
                            <tr>
                                <td colspan="4">
                                    <div class="uli-empty">
                                        <div class="icon">👤</div>
                                        Tidak ada user<?php echo $q <> '' ? ' yang cocok dengan pencarian "' . $q . '"' : ''; ?>.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users_data as $u):
                                $initial = strtoupper(substr($u->name, 0, 1));
                                $is_admin = ($u->role_id == 1);
                                $jml = count($u->indikators);
                            ?>
                            <tr>
                                <td data-label="User">
                                    <div class="uli-user-cell">
                                        <div class="uli-avatar"><?php echo $initial ?></div>
                                        <div>
                                            <div class="uli-user-name"><?php echo $u->name ?></div>
                                            <div class="uli-user-email"><?php echo $u->email ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Role">
                                    <span class="uli-role-badge <?php echo $is_admin ? 'admin' : '' ?>">
                                        <?php echo $is_admin ? 'Admin' : 'Role #' . $u->role_id ?>
                                    </span>
                                </td>
                                <td data-label="Indikator yang Diakses">
                                    <?php if ($is_admin): ?>
                                        <span class="uli-unit-empty">Semua indikator (Admin)</span>
                                    <?php elseif ($jml == 0): ?>
                                        <span class="uli-unit-empty">Belum ada indikator</span>
                                    <?php else: ?>
                                        <span class="uli-count-chip"><?php echo $jml ?> indikator</span>
                                        <div class="uli-ind-badges">
                                            <?php foreach (array_slice($u->indikators, 0, 6) as $ind): ?>
                                                <span class="uli-ind-chip" title="<?php echo html_escape($ind->judul) ?>">
                                                    <?php echo html_escape(mb_strimwidth($ind->judul, 0, 34, '…')) ?>
                                                    <?php if (!empty($ind->nm_unit)): ?><em>(<?php echo html_escape($ind->nm_unit) ?>)</em><?php endif; ?>
                                                    <a href="<?php echo site_url('user_list_indikator/remove_indikator/'.$u->id.'/'.$ind->id_indikator) ?>"
                                                       title="Hapus akses indikator ini"
                                                       onclick="return confirm('Hapus akses indikator ini dari <?php echo html_escape($u->name) ?>?')">×</a>
                                                </span>
                                            <?php endforeach; ?>
                                            <?php if ($jml > 6): ?>
                                                <span class="uli-ind-more">+<?php echo ($jml - 6) ?> lainnya</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Aksi">
                                    <a href="<?php echo site_url('user_list_indikator/manage/'.$u->id) ?>" class="uli-manage-btn">⚙️ Kelola</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="uli-footer">
                <span class="uli-total-chip">Total: <?php echo $total_rows ?> user</span>
                <div class="uli-pagination">
                    <ul class="pagination mb-0">
                        <?php echo $pagination ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
