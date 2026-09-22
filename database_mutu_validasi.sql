-- =============================================================
-- VALIDASI DATA MUTU INDIKATOR
-- -------------------------------------------------------------
-- - Tabel mutu_validasi menyimpan hasil validasi data mutu
--   per indikator untuk periode tanggal_awal s.d. tanggal_akhir.
-- - Menyimpan jumlah numerator (num) & denominator (demu) hasil
--   validasi serta user yang memvalidasi (userid).
-- - Idempotent: aman dijalankan berulang.
-- =============================================================

CREATE TABLE IF NOT EXISTS `mutu_validasi` (
  `id_validasi` int(11) NOT NULL AUTO_INCREMENT,
  `id_indikator` int(11) NOT NULL,
  `tanggal_awal` date DEFAULT NULL,
  `tanggal_akhir` date DEFAULT NULL,
  `num` double(10,2) DEFAULT NULL,
  `demu` double(10,2) DEFAULT NULL,
  `userid` int(10) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_validasi`),
  KEY `idx_mutu_validasi_indikator` (`id_indikator`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SELECT 'TABEL mutu_validasi SIAP' AS info;