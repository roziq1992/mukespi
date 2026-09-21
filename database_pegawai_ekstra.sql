-- ============================================================
-- Migrasi: Kolom tambahan pegawai dari berkas datapegawai.xlsx
-- RS Airlangga — Jalankan: mysql -u root mukespi < database_pegawai_ekstra.sql
-- Field: keluarga, NPWP, pendidikan & pengalaman, status kepegawaian
-- ============================================================

ALTER TABLE `pegawai`
  ADD COLUMN `no_npwp` varchar(50) DEFAULT NULL AFTER `no_hp`,
  ADD COLUMN `nama_keluarga` varchar(150) DEFAULT NULL AFTER `email`,
  ADD COLUMN `no_hp_keluarga` varchar(30) DEFAULT NULL AFTER `nama_keluarga`,
  ADD COLUMN `nama_anak` text DEFAULT NULL AFTER `no_hp_keluarga`,
  ADD COLUMN `kualifikasi_pendidikan` text DEFAULT NULL AFTER `nama_anak`,
  ADD COLUMN `pengalaman_kerja` text DEFAULT NULL AFTER `kualifikasi_pendidikan`,
  ADD COLUMN `pelatihan` text DEFAULT NULL AFTER `pengalaman_kerja`,
  ADD COLUMN `organisasi` text DEFAULT NULL AFTER `pelatihan`,
  ADD COLUMN `status_kepegawaian` varchar(50) DEFAULT NULL AFTER `status`;