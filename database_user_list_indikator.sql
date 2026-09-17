-- =============================================================
-- AKSES INDIKATOR PER USER (mirip "Akses Unit User")
-- -------------------------------------------------------------
-- Tabel user_list_indikator: admin menetapkan indikator mana saja
-- yang boleh diakses oleh tiap user (1 user bisa banyak indikator).
-- - list_indikator difilter berdasarkan tabel ini untuk non-admin.
-- - Admin (role 1) & Direktur (role 4) tetap melihat semua.
-- - Idempotent: aman dijalankan berulang.
-- =============================================================

CREATE TABLE IF NOT EXISTS `user_list_indikator` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `id_indikator` INT(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_list_indikator_unique` (`user_id`,`id_indikator`),
  KEY `user_id` (`user_id`),
  KEY `id_indikator` (`id_indikator`),
  CONSTRAINT `fk_uli_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_uli_indikator` FOREIGN KEY (`id_indikator`) REFERENCES `list_indikator` (`id_indikator`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menu & hak akses halaman kelola Akses Indikator User (admin)
INSERT INTO menus (nama_menu, url, icon, sequence, is_active)
SELECT 'Akses Indikator User', 'user_list_indikator', 'fas fa-list-check', 18, 1
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE url = 'user_list_indikator');

SET @mid_uli = (SELECT id FROM menus WHERE url = 'user_list_indikator' LIMIT 1);
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 1, @mid_uli WHERE @mid_uli IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM user_access_menu WHERE role_id = 1 AND menu_id = @mid_uli);

-- =============================================================
-- BACKFILL: list_indikator.userid sudah menunjukkan pemilik/hak akses
-- indikator. Isikan ke user_list_indikator agar tiap pembuat otomatis
-- punya akses ke indikatornya.
-- - JOIN users: hanya user yang memang ada (menghindari FK error).
-- - INSERT IGNORE: aman terhadap unique key (idempotent).
-- =============================================================
INSERT IGNORE INTO user_list_indikator (user_id, id_indikator)
SELECT li.userid, li.id_indikator
FROM list_indikator li
JOIN users u ON u.id = li.userid;

-- Ringkasan hasil backfill
SELECT 'TOTAL AKSES' AS info, COUNT(*) AS jumlah FROM user_list_indikator;
SELECT 'USER AWAL' AS info, COUNT(DISTINCT user_id) AS jumlah FROM user_list_indikator;
