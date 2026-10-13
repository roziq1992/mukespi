-- =============================================================
-- VALIDASI DATA MUTU: NILAI VALIDASI (STANDAR)
-- -------------------------------------------------------------
-- - Menambah kolom nilai_validasi sebagai standar/acuan validasi.
-- - num  -> rata-rata capaian indikator dari inputan user
-- - demu -> capaian validator (free entry)
-- - Nilai capaian = num / demu * 100
-- - Data mutu VALID bila nilai capaian >= nilai_validasi.
-- - Idempotent: aman dijalankan berulang.
-- =============================================================

ALTER TABLE `mutu_validasi`
  ADD COLUMN IF NOT EXISTS `nilai_validasi` double(10,2) DEFAULT NULL AFTER `demu`;

SELECT 'KOLOM nilai_validasi SIAP' AS info;
