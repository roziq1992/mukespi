-- ============================================================
-- Modul Manajemen Data Pegawai — RS Airlangga
-- Jalankan: mysql -u root mukespi < database_pegawai.sql
-- ============================================================

CREATE TABLE IF NOT EXISTS `pegawai` (
  `id_pegawai` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(30) DEFAULT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `nama` varchar(150) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL DEFAULT 'Laki-laki',
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `jabatan` varchar(150) DEFAULT NULL,
  `unit_kerja` varchar(150) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pegawai`),
  UNIQUE KEY `uniq_pegawai_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pegawai_mutasi` (
  `id_mutasi` int(11) NOT NULL AUTO_INCREMENT,
  `id_pegawai` int(11) NOT NULL,
  `jenis` varchar(20) NOT NULL DEFAULT 'mutasi',
  `unit_asal` varchar(150) DEFAULT NULL,
  `unit_tujuan` varchar(150) DEFAULT NULL,
  `jabatan_asal` varchar(150) DEFAULT NULL,
  `jabatan_tujuan` varchar(150) DEFAULT NULL,
  `tanggal_mutasi` date NOT NULL,
  `keterangan` text,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_mutasi`),
  KEY `id_pegawai` (`id_pegawai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- MIGRASI: email pegawai wajib unik (idempotent)
-- ============================================================
-- Bersihkan duplikat lebih dulu (pertahankan id_pegawai terkecil, sisanya di-NULL-kan)
UPDATE pegawai p
JOIN (
  SELECT email, MIN(id_pegawai) keep_id
  FROM pegawai
  WHERE email IS NOT NULL AND email <> ''
  GROUP BY email
  HAVING COUNT(*) > 1
) d ON d.email = p.email AND p.id_pegawai <> d.keep_id
SET p.email = NULL;

SET @idx = (SELECT COUNT(*) FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pegawai' AND INDEX_NAME = 'uniq_pegawai_email');
SET @sql = IF(@idx = 0,
  'ALTER TABLE pegawai ADD UNIQUE KEY uniq_pegawai_email (email)',
  'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;