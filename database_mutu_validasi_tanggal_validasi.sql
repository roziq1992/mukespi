-- Penambahan kolom tanggal validasi (tanggal pelaksanaan validasi yang diinput user)
ALTER TABLE `mutu_validasi` ADD COLUMN `tanggal_validasi` DATE NULL DEFAULT NULL AFTER `tanggal_akhir`;
