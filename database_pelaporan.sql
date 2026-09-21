-- ============================================================
-- Login NIK Pegawai + Mode Edit + Pelaporan/Pengaduan Karyawan
-- RS Airlangga — CodeIgniter 3
-- Jalankan: mysql -u root mukespi < database_pelaporan.sql
-- ============================================================

-- 1. Kolom password pada pegawai (untuk login via NIK)
SET @col_exists = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pegawai' AND COLUMN_NAME = 'password'
);
SET @sql = IF(
    @col_exists = 0,
    'ALTER TABLE pegawai ADD COLUMN password VARCHAR(255) DEFAULT NULL AFTER email',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 2. Tabel settings (store global mode edit data pegawai)
CREATE TABLE IF NOT EXISTS `settings` (
    `nama`  VARCHAR(100) NOT NULL,
    `nilai` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`nama`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings (nama, nilai) VALUES ('pegawai_edit_mode', 'nonaktif')
ON DUPLICATE KEY UPDATE nama = VALUES(nama);

-- 3. Tabel pelaporan / pengaduan karyawan
CREATE TABLE IF NOT EXISTS `pelaporan_karyawan` (
    `id_laporan`       int(11) NOT NULL AUTO_INCREMENT,
    `id_pelapor`       int(11) DEFAULT NULL,           -- id_pegawai pelapor (login via NIK)
    `id_user_pelapor`  bigint(20) UNSIGNED DEFAULT NULL, -- id users pelapor (login via email)
    `id_terlapor`      int(11) NOT NULL,               -- id_pegawai yang dinilai / dilaporkan
    `bintang`          tinyint(1) NOT NULL DEFAULT 5,  -- rating 1 - 5
    `alasan`           text,
    `sanggahan`        text DEFAULT NULL,              -- tanggapan / sanggahan terlapor (anonim pelapor)
    `sanggahan_at`     datetime DEFAULT NULL,
    `sanggahan_oleh`   varchar(150) DEFAULT NULL,
    `created_at`       datetime DEFAULT NULL,
    PRIMARY KEY (`id_laporan`),
    KEY `id_terlapor` (`id_terlapor`),
    KEY `id_pelapor` (`id_pelapor`),
    KEY `id_user_pelapor` (`id_user_pelapor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Role HRD (role_id = 6)
INSERT INTO roles (id, name)
SELECT 6, 'HRD'
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE id = 6);

-- 4b. Contoh akun HRD (password: 12345) — ubah/hapus sesuai kebutuhan
INSERT INTO users (name, email, password, role_id, is_active)
SELECT 'HRD Admin', 'hrd@mail.com', '$2y$10$G3izvTFuOIRwnCj9iWZxHOLbjf1dlAM94VDH5hARU.7kAjdP0D6hS', 6, 1
WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'hrd@mail.com');

-- 5. Menu Pelaporan Karyawan
INSERT INTO menus (nama_menu, url, icon, sequence, is_active)
SELECT 'Pelaporan Karyawan', 'pelaporan', 'fas fa-star-half-alt', 14, 1
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE url = 'pelaporan');

-- 6. Hak akses menu pelaporan utk Admin (1) & HRD (6)
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 1, id FROM menus WHERE url = 'pelaporan'
AND NOT EXISTS (SELECT 1 FROM user_access_menu WHERE role_id = 1 AND user_access_menu.menu_id = menus.id);

INSERT INTO user_access_menu (role_id, menu_id)
SELECT 6, id FROM menus WHERE url = 'pelaporan'
AND NOT EXISTS (SELECT 1 FROM user_access_menu WHERE role_id = 6 AND user_access_menu.menu_id = menus.id);

-- 7. Role "pegawai" (role_id = 7) — user yang dibuat dari data pegawai
INSERT INTO roles (id, name)
SELECT 7, 'pegawai'
WHERE NOT EXISTS (SELECT 1 FROM roles WHERE id = 7);

-- Hak akses default role pegawai: Portal, Data Pegawai (DATA SAYA), Pelaporan
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 7, id FROM menus WHERE url IN ('portal', 'pegawai', 'pelaporan')
AND NOT EXISTS (SELECT 1 FROM user_access_menu WHERE role_id = 7 AND user_access_menu.menu_id = menus.id);

-- 8. Menu SIASSET (data_inventaris) — supaya kartu portal bisa dibatasi per role
INSERT INTO menus (nama_menu, url, icon, sequence, is_active)
SELECT 'SIASSET (Inventaris)', 'data_inventaris', 'fas fa-boxes', 9, 1
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE url = 'data_inventaris');

-- Akses SIASSET utk semua role kecuali "pegawai" (role 7)
INSERT INTO user_access_menu (role_id, menu_id)
SELECT r.id, m.id FROM roles r CROSS JOIN menus m
WHERE m.url = 'data_inventaris' AND r.id IN (1, 2, 3, 4, 5, 6)
AND NOT EXISTS (
    SELECT 1 FROM user_access_menu uam
    WHERE uam.role_id = r.id AND uam.menu_id = m.id
);

-- 8b. Tabel notifikasi in-app (bell di header; contoh: notif pelaporan ke terlapor)
CREATE TABLE IF NOT EXISTS `notifikasi` (
    `id_notif`   BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_user`    BIGINT(20) UNSIGNED NOT NULL,
    `pesan`      TEXT DEFAULT NULL,
    `url`        VARCHAR(255) DEFAULT NULL,
    `is_read`    TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id_notif`),
    KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;