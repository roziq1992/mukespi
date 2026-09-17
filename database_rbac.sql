-- =============================================================
-- Manajemen User & Role Menu Akses — migration
-- Aplikasi: mukespi (RS Airlangga) — CodeIgniter 3
-- =============================================================

-- 1. kolom is_active pada users (soft-disable login)
ALTER TABLE users ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER avatar;

-- 2. tabel menus
CREATE TABLE IF NOT EXISTS menus (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_menu   VARCHAR(255) NOT NULL,
    url         VARCHAR(255) NOT NULL DEFAULT '#',
    icon        VARCHAR(100) NOT NULL DEFAULT 'fas fa-circle',
    sequence    INT NOT NULL DEFAULT 0,
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. tabel user_access_menu (role -> menu)
CREATE TABLE IF NOT EXISTS user_access_menu (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id     BIGINT(20) UNSIGNED NOT NULL,
    menu_id     INT UNSIGNED NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_role_menu (role_id, menu_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- SEED menus (item sidebar root)
-- =============================================================
INSERT INTO menus (nama_menu, url, icon, sequence, is_active) VALUES
('Portal Sistem',              'portal',                      'fas fa-th-large',       1, 1),
('Data Pegawai',               'pegawai',                     'fas fa-users',          2, 1),
('E-Office (Surat)',           'surat',                       'fas fa-envelope',       3, 1),
('MUKESPI (Mutu & PPI)',       'list_indikator',              'fas fa-heartbeat',      4, 1),
('SIDOKTA (Dokumen Unit)',     'dokumen_unit',                'fas fa-folder-open',    5, 1),
('SIPARDI (Akreditasi)',       'penilaian_ep',                'fas fa-award',          6, 1),
('SIMONIKA (Monitoring)',      'monitoring_pj',               'fas fa-tasks',          7, 1),
('Sertifikat Online',          'sertifikat',                  'fas fa-certificate',    8, 1),
('Edit Password',              'list_indikator/vapassword',   'fas fa-key',            9, 1),
('Akses Unit User',            'user_unit',                   'fas fa-user-shield',   10, 1),
('User Management',            'users',                       'fas fa-user-cog',      11, 1),
('Menu Management',            'menu',                        'fas fa-bars',          12, 1),
('Role & Hak Akses Menu',      'menu/role_access',            'fas fa-user-lock',     13, 1);

-- =============================================================
-- SEED user_access_menu
-- Admin (role 1): SEMUA menu
-- =============================================================
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 1, id FROM menus WHERE is_active = 1;

-- Role 2 (user)   : portal, mukespi, sidokta, sipardi, simonika, edit password
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 2, id FROM menus WHERE url IN
('portal','list_indikator','dokumen_unit','penilaian_ep','monitoring_pj','list_indikator/vapassword');

-- Role 3 (Surveior) : portal, sipardi, edit password
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 3, id FROM menus WHERE url IN
('portal','penilaian_ep','list_indikator/vapassword');

-- Role 4 (Direktur): portal, e-office, edit password
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 4, id FROM menus WHERE url IN
('portal','surat','list_indikator/vapassword');

-- Role 5 (Sekretaris): portal, e-office, edit password
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 5, id FROM menus WHERE url IN
('portal','surat','list_indikator/vapassword');