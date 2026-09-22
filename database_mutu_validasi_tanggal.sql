-- Validasi mutu per tanggal (per data mutu_indikator).
-- Menambah kolom id_mutu untuk mengaitkan validasi dengan 1 baris data mutu.
ALTER TABLE mutu_validasi
  ADD COLUMN id_mutu INT(11) NULL DEFAULT NULL AFTER id_indikator,
  ADD KEY idx_mutu_validasi_mutu (id_mutu);