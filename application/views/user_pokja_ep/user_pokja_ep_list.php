<style>
    .up-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.08);
        overflow: hidden;
        background: #fff;
    }
    .up-header {
        background: linear-gradient(135deg, #6a3ea1 0%, #3d2266 100%);
        color: #fff;
        padding: 22px 24px;
    }
    .up-header h2 { margin: 0; font-size: 1.2rem; font-weight: 700; }
    .up-header p { margin: 4px 0 0; font-size: 0.8rem; opacity: 0.85; }

    .up-body { padding: 22px; }
    @media (max-width: 576px) { .up-body { padding: 14px; } }

    .up-toolbar { display: flex; justify-content: flex-end; margin-bottom: 18px; }
    .up-search-wrap {
        display: flex;
        border: 1px solid #dde3ea;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
        max-width: 320px;
        width: 100%;
    }
    .up-search-wrap input {
        border: none; background: transparent; padding: 9px 12px;
        flex: 1; font-size: 0.85rem; outline: none;
    }
    .up-search-wrap button {
        border: none; background: #6a3ea1; color: #fff;
        padding: 0 16px; font-size: 0.85rem; font-weight: 600;
    }
    .up-search-reset { font-size: 0.78rem; color: #8a94a6; margin-left: 8px; align-self: center; white-space: nowrap; }

    .up-flash {
        background: #f1e9fb; color: #3d2266; padding: 10px 14px;
        border-radius: 8px; font-size: 0.85rem; margin-bottom: 16px;
    }
    .up-info {
        background: #fff8e6; color: #8a6100; border-left: 4px solid #d4a017;
        padding: 10px 14px; border-radius: 8px; font-size: 0.82rem; margin-bottom: 16px;
    }

    .up-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .up-table thead th {
        font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.03em;
        color: #8a94a6; font-weight: 700; border-bottom: 2px solid #eef0f3;
        padding: 10px 12px; white-space: nowrap;
    }
    .up-table tbody td {
        padding: 12px; border-bottom: 1px solid #f1f3f6;
        font-size: 0.87rem; color: #33475b; vertical-align: middle;
    }
    .up-table tbody tr:hover { background: #f8fafc; }

    .up-user-cell { display: flex; align-items: center; gap: 10px; }
    .up-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: linear-gradient(135deg, #6a3ea1, #3d2266);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem; flex-shrink: 0;
    }
    .up-user-name { font-weight: 600; color: #23324a; }
    .up-user-email { font-size: 0.78rem; color: #8a94a6; }

    .up-role-badge {
        display: inline-block; padding: 3px 11px; border-radius: 20px;
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        background: #eef2f7; color: #33475b;
    }
    .up-role-badge.admin { background: #fdecea; color: #c0392b; }
    .up-role-badge.surveior { background: #e8f0fd; color: #1a4fa0; }

    .up-pokja-badges { display: flex; flex-wrap: wrap; gap: 6px; max-width: 340px; }
    .up-pokja-chip {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.76rem; font-weight: 600;
        padding: 4px 8px 4px 11px; border-radius: 20px; white-space: nowrap;
    }
    .up-pokja-chip.penilai { background: #e8f8f0; color: #1e8449; }
    .up-pokja-chip.lihat   { background: #eef2f7; color: #5b6b80; }
    .up-pokja-chip b { font-weight: 800; }
    .up-pokja-chip a { color: inherit; opacity: 0.55; text-decoration: none; font-weight: 800; line-height: 1; }
    .up-pokja-chip a:hover { opacity: 1; color: #c0392b; }
    .up-pokja-empty { font-size: 0.78rem; color: #b6bcc7; font-style: italic; }

    .up-manage-btn {
        background: #6a3ea1; color: #fff; border-radius: 8px;
        padding: 7px 16px; font-size: 0.8rem; font-weight: 600;
        white-space: nowrap; display: inline-block;
    }
    .up-manage-btn:hover { background: #3d2266; color: #fff; text-decoration: none; }

    .up-empty { text-align: center; padding: 40px 16px; color: #8a94a6; }
    .up-empty .icon { font-size: 2.2rem; margin-bottom: 8px; }

    .up-footer {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-top: 18px;
    }
    .up-total-chip {
        background: #eef2f7; color: #33475b; font-size: 0.8rem;
        font-weight: 600; padding: 6px 14px; border-radius: 20px;
    }
    .up-pagination ul { margin: 0; }

    @media (max-width: 768px) {
        .up-table thead { display: none; }
        .up-table, .up-table tbody, .up-table tr, .up-table td { display: block; width: 100%; }
        .up-table tr {
            border: 1px solid #eef0f3; border-radius: 10px; margin-bottom: 12px; padding: 12px;
        }
        .up-table td { border: none; padding: 6px 0; }
        .up-table td::before {
            content: attr(data-label); font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; color: #8a94a6; display: block; margin-bottom: 4px;
        }
        .up-pokja-badges { max-width: none; }
    }
</style>

<div class="container-fluid">
    <div class="up-card">
        <div class="up-header">
            <h2>🔑 Akses Pokja Penilaian (SIPARDI)</h2>
            <p>Atur user mana saja yang boleh menilai EP pada tiap Pokja</p>
        </div>

        <div class="up-body">

            <?php
                $flash = $this->session->userdata('message');
                if ($flash <> '') {
                    echo '<div class="up-flash">' . $flash . '</div>';
                }
            ?>

            <div class="up-info">
                <strong>Penilai</strong> = boleh isi skor, upload bukti, hapus bukti, dan download dokumen.
                <strong>Lihat saja</strong> = hanya boleh membuka dokumen di browser (tanpa download, tanpa menilai).
                User yang tidak di-set tetap bisa melihat halaman penilaian_ep dalam mode baca saja.
                Admin dan Surveior otomatis punya akses penuh ke semua pokja.
            </div>

            <div class="up-toolbar">
                <form action="<?php echo site_url('user_pokja_ep/index'); ?>" method="get" style="display:flex; align-items:center;">
                    <div class="up-search-wrap">
                        <input type="text" name="q" placeholder="Cari nama atau email..." value="<?php echo $q; ?>">
                        <button type="submit">🔍</button>
                    </div>
                    <?php if ($q <> ''): ?>
                        <a href="<?php echo site_url('user_pokja_ep'); ?>" class="up-search-reset">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="table-responsive">
                <table class="up-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Pokja yang Diakses</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users_data) == 0): ?>
                            <tr>
                                <td colspan="4">
                                    <div class="up-empty">
                                        <div class="icon">👤</div>
                                        Tidak ada user<?php echo $q <> '' ? ' yang cocok dengan pencarian "' . $q . '"' : ''; ?>.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users_data as $u):
                                $initial   = strtoupper(substr($u->name, 0, 1));
                                $role_name = '';
                                if ($u->role_id == 1) $role_name = 'admin';
                                elseif ($u->role_id == 3) $role_name = 'surveior';
                                $is_admin = ($u->role_id == 1);
                            ?>
                            <tr>
                                <td data-label="User">
                                    <div class="up-user-cell">
                                        <div class="up-avatar"><?php echo $initial ?></div>
                                        <div>
                                            <div class="up-user-name"><?php echo $u->name ?></div>
                                            <div class="up-user-email"><?php echo $u->email ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Role">
                                    <span class="up-role-badge <?php echo $role_name ?>">
                                        <?php echo $role_name <> '' ? ucfirst($role_name) : 'Role #' . $u->role_id ?>
                                    </span>
                                </td>
                                <td data-label="Pokja yang Diakses">
                                    <?php if ($is_admin || $u->role_id == 3): ?>
                                        <span class="up-pokja-empty">
                                            <?php echo $is_admin ? 'Semua pokja (Admin)' : 'Semua pokja (Surveior)' ?>
                                        </span>
                                    <?php elseif (count($u->pokja) == 0): ?>
                                        <span class="up-pokja-empty">Baca saja semua pokja</span>
                                    <?php else: ?>
                                        <div class="up-pokja-badges">
                                            <?php foreach ($u->pokja as $p): ?>
                                                <span class="up-pokja-chip <?php echo intval($p->is_penilai) === 1 ? 'penilai' : 'lihat' ?>">
                                                    <b><?php echo $p->bab ?></b>
                                                    <?php echo intval($p->is_penilai) === 1 ? 'Penilai' : 'Lihat' ?>
                                                    <a href="<?php echo site_url('user_pokja_ep/remove_pokja/' . $u->id . '/' . $p->id) ?>"
                                                       title="Hapus akses pokja ini"
                                                       onclick="return confirm('Hapus akses &quot;<?php echo $p->bab ?>&quot; dari <?php echo $u->name ?>?')">&times;</a>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Aksi">
                                    <a href="<?php echo site_url('user_pokja_ep/manage/' . $u->id) ?>" class="up-manage-btn">⚙️ Kelola</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="up-footer">
                <span class="up-total-chip">Total: <?php echo $total_rows ?> user</span>
                <div class="up-pagination">
                    <ul class="pagination mb-0">
                        <?php echo $pagination ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
