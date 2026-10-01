-- =====================================================================
-- AKSES USER PER POKJA — MODUL PENILAIAN EP (SIPARDI)
-- ---------------------------------------------------------------------
-- Fungsi:
--   Admin menentukan user mana saja yang boleh menilai EP di tiap Pokja.
--   - is_penilai = 1  -> boleh menilai skor, upload bukti, hapus bukti, DAN download dokumen
--   - is_penilai = 0  -> hanya boleh LIHAT dokumen di browser (tidak bisa download,
--                        tidak bisa menilai skor, tidak bisa upload/hapus bukti)
--   User yang tidak ada di tabel ini: tetap bisa membuka halaman penilaian_ep
--   dan melihat dokumen, tapi read-only (menyesuaikan permintaan).
--
-- Admin (role_id = 1) dan Surveior (role_id = 3) tidak dibatasi tabel ini.
--
-- CARA PAKAI:
--   1. Import file ini ke database mukespi.
--   2. Buka menu SIPARDI > "Akses Pokja Penilaian" (khusus admin).
--   3. Pilih user, centang pokja + tandai mana yang jadi Penilai.
-- =====================================================================

CREATE TABLE IF NOT EXISTS `user_pokja_ep` (
  `id`         bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user`    bigint(20) unsigned NOT NULL,
  `id_pokja`   int(2) NOT NULL,
  `is_penilai` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_pokja` (`id_user`, `id_pokja`),
  KEY `idx_id_pokja` (`id_pokja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---------------------------------------------------------------------
-- Menu: Akses Pokja Penilaian (hanya admin yang boleh kelola)
-- CATATAN: tabel menus tidak AUTO_INCREMENT, jadi id diisi manual = max+1
-- ---------------------------------------------------------------------
INSERT INTO `menus` (`id`, `nama_menu`, `url`, `icon`, `sequence`, `is_active`)
SELECT
  (SELECT COALESCE(MAX(`id`), 0) + 1 FROM `menus`),
  'Akses Pokja Penilaian',
  'user_pokja_ep',
  'fas fa-user-shield',
  (SELECT COALESCE(MAX(`sequence`), 0) + 1 FROM `menus`),
  1
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM `menus` WHERE `url` = 'user_pokja_ep'
);

-- Beri akses ke role admin (role_id = 1) supaya tidak perlu cek manual
INSERT INTO `user_access_menu` (`role_id`, `menu_id`)
SELECT 1, m.`id`
FROM `menus` m
WHERE m.`url` = 'user_pokja_ep'
  AND NOT EXISTS (
    SELECT 1 FROM `user_access_menu` uam WHERE uam.`role_id` = 1 AND uam.`menu_id` = m.`id`
  );
