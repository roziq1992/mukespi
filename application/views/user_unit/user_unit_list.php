<style>
    .uu-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
    }
    .uu-header {
        background: linear-gradient(135deg, #6a3ea1 0%, #3d2266 100%);
        color: #fff;
        padding: 22px 24px;
    }
    .uu-header h2 { margin: 0; font-size: 1.2rem; font-weight: 700; }
    .uu-header p { margin: 4px 0 0; font-size: 0.8rem; opacity: 0.85; }

    .uu-body { padding: 22px; }
    @media (max-width: 576px) { .uu-body { padding: 14px; } }

    .uu-toolbar {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 18px;
    }
    .uu-search-wrap {
        display: flex;
        border: 1px solid #dde3ea;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
        max-width: 320px;
        width: 100%;
    }
    .uu-search-wrap input {
        border: none;
        background: transparent;
        padding: 9px 12px;
        flex: 1;
        font-size: 0.85rem;
        outline: none;
    }
    .uu-search-wrap button {
        border: none;
        background: #6a3ea1;
        color: #fff;
        padding: 0 16px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .uu-search-reset { font-size: 0.78rem; color: #8a94a6; margin-left: 8px; align-self: center; white-space: nowrap; }

    .uu-flash {
        background: #f1e9fb;
        color: #3d2266;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 16px;
    }

    .uu-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .uu-table thead th {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #8a94a6;
        font-weight: 700;
        border-bottom: 2px solid #eef0f3;
        padding: 10px 12px;
        white-space: nowrap;
    }
    .uu-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #f1f3f6;
        font-size: 0.87rem;
        color: #33475b;
        vertical-align: middle;
    }
    .uu-table tbody tr:hover { background: #f8fafc; }

    .uu-user-cell { display: flex; align-items: center; gap: 10px; }
    .uu-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6a3ea1, #3d2266);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem;
        flex-shrink: 0;
    }
    .uu-user-name { font-weight: 600; color: #23324a; }
    .uu-user-email { font-size: 0.78rem; color: #8a94a6; }

    .uu-role-badge {
        display: inline-block;
        padding: 3px 11px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        background: #eef2f7;
        color: #33475b;
    }
    .uu-role-badge.admin { background: #fdecea; color: #c0392b; }

    .uu-unit-badges { display: flex; flex-wrap: wrap; gap: 6px; max-width: 320px; }
    .uu-unit-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1e9fb;
        color: #5b3593;
        font-size: 0.76rem;
        font-weight: 600;
        padding: 4px 8px 4px 11px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .uu-unit-chip a {
        color: #a084c9;
        text-decoration: none;
        font-weight: 800;
        line-height: 1;
    }
    .uu-unit-chip a:hover { color: #c0392b; }
    .uu-unit-empty { font-size: 0.78rem; color: #b6bcc7; font-style: italic; }

    .uu-manage-btn {
        background: #6a3ea1;
        color: #fff;
        border-radius: 8px;
        padding: 7px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        display: inline-block;
    }
    .uu-manage-btn:hover { background: #3d2266; color: #fff; text-decoration: none; }

    .uu-empty { text-align: center; padding: 40px 16px; color: #8a94a6; }
    .uu-empty .icon { font-size: 2.2rem; margin-bottom: 8px; }

    .uu-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
    }
    .uu-total-chip {
        background: #eef2f7;
        color: #33475b;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
    }
    .uu-pagination ul { margin: 0; }

    @media (max-width: 768px) {
        .uu-table thead { display: none; }
        .uu-table, .uu-table tbody, .uu-table tr, .uu-table td { display: block; width: 100%; }
        .uu-table tr {
            border: 1px solid #eef0f3;
            border-radius: 10px;
            margin-bottom: 12px;
            padding: 12px;
        }
        .uu-table td {
            border: none;
            padding: 6px 0;
        }
        .uu-table td::before {
            content: attr(data-label);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #8a94a6;
            display: block;
            margin-bottom: 4px;
        }
        .uu-unit-badges { max-width: none; }
    }
</style>

<div class="container-fluid">
    <div class="uu-card">
        <div class="uu-header">
            <h2>🔑 Akses Unit User</h2>
            <p>Atur unit mana saja yang bisa diakses tiap user</p>
        </div>

        <div class="uu-body">

            <?php
                $flash = $this->session->userdata('message');
                if ($flash <> '') {
                    echo '<div class="uu-flash">' . $flash . '</div>';
                }
            ?>

            <div class="uu-toolbar">
                <form action="<?php echo site_url('user_unit/index'); ?>" method="get" style="display:flex; align-items:center;">
                    <div class="uu-search-wrap">
                        <input type="text" name="q" placeholder="Cari nama atau email..." value="<?php echo $q; ?>">
                        <button type="submit">🔍</button>
                    </div>
                    <?php if ($q <> ''): ?>
                        <a href="<?php echo site_url('user_unit'); ?>" class="uu-search-reset">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-responsive">
                <table class="uu-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Unit yang Diakses</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users_data) == 0): ?>
                            <tr>
                                <td colspan="4">
                                    <div class="uu-empty">
                                        <div class="icon">👤</div>
                                        Tidak ada user<?php echo $q <> '' ? ' yang cocok dengan pencarian "' . $q . '"' : ''; ?>.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users_data as $u):
                                $initial = strtoupper(substr($u->name, 0, 1));
                                $is_admin = ($u->role_id == 1);
                            ?>
                            <tr>
                                <td data-label="User">
                                    <div class="uu-user-cell">
                                        <div class="uu-avatar"><?php echo $initial ?></div>
                                        <div>
                                            <div class="uu-user-name"><?php echo $u->name ?></div>
                                            <div class="uu-user-email"><?php echo $u->email ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Role">
                                    <span class="uu-role-badge <?php echo $is_admin ? 'admin' : '' ?>">
                                        <?php echo $is_admin ? 'Admin' : 'Role #' . $u->role_id ?>
                                    </span>
                                </td>
                                <td data-label="Unit yang Diakses">
                                    <?php if ($is_admin): ?>
                                        <span class="uu-unit-empty">Semua unit (Admin)</span>
                                    <?php elseif (count($u->units) == 0): ?>
                                        <span class="uu-unit-empty">Belum ada unit</span>
                                    <?php else: ?>
                                        <div class="uu-unit-badges">
                                            <?php foreach ($u->units as $unit): ?>
                                                <span class="uu-unit-chip">
                                                    <?php echo $unit->nm_unit ?>
                                                    <a href="<?php echo site_url('user_unit/remove_unit/'.$u->id.'/'.$unit->id_unit) ?>"
                                                       title="Hapus akses unit ini"
                                                       onclick="return confirm('Hapus akses unit &quot;<?php echo $unit->nm_unit ?>&quot; dari <?php echo $u->name ?>?')">×</a>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Aksi">
                                    <a href="<?php echo site_url('user_unit/manage/'.$u->id) ?>" class="uu-manage-btn">⚙️ Kelola</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="uu-footer">
                <span class="uu-total-chip">Total: <?php echo $total_rows ?> user</span>
                <div class="uu-pagination">
                    <ul class="pagination mb-0">
                        <?php echo $pagination ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>