-- ============================================================
--  Data Ruang + Perbaikan Modul SIASSET (Data Inventaris)
--  RS Airlangga - Much Roziq
-- ============================================================
--  Pakai:
--    mysql -u root mukespi < database_data_ruang.sql
--
--  Script ini aman diulang (idempotent): setiap perubahan kolom
--  dicek dulu lewat information_schema sebelum dieksekusi.
-- ============================================================

-- ---------------------------------------------------------
--  0. BACKUP data sebelum diubah
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `data_ruang_bak_20261022` AS SELECT * FROM `data_ruang`;
CREATE TABLE IF NOT EXISTS `data_inventaris_bak_20261022` AS SELECT * FROM `data_inventaris`;

DROP PROCEDURE IF EXISTS `mig_data_ruang`;

DELIMITER $$
CREATE PROCEDURE `mig_data_ruang`()
BEGIN
  DECLARE v_cnt INT DEFAULT 0;

  -- =========================================================
  --  1. data_ruang : pastikan semua kolom yang dipakai aplikasi ada
  --     nm_ruang   -> dipakai grafik inventaris per ruang & rekap
  --     nama_ruang -> dipakai dropdown form inventaris
  --     kode_ruang -> kode ringkas ruang (IGD, ICU, ...)
  --     gedung / lantai -> penanda lokasi
  -- =========================================================
  SELECT COUNT(*) INTO v_cnt FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'data_ruang' AND COLUMN_NAME = 'nm_ruang';
  IF v_cnt = 0 THEN
    ALTER TABLE `data_ruang` ADD COLUMN `nm_ruang` VARCHAR(255) NOT NULL DEFAULT '' AFTER `id_ruang`;
  END IF;

  SELECT COUNT(*) INTO v_cnt FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'data_ruang' AND COLUMN_NAME = 'kode_ruang';
  IF v_cnt = 0 THEN
    ALTER TABLE `data_ruang` ADD COLUMN `kode_ruang` VARCHAR(50) NOT NULL DEFAULT '' AFTER `nm_ruang`;
  END IF;

  SELECT COUNT(*) INTO v_cnt FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'data_ruang' AND COLUMN_NAME = 'nama_ruang';
  IF v_cnt = 0 THEN
    ALTER TABLE `data_ruang` ADD COLUMN `nama_ruang` VARCHAR(100) NOT NULL DEFAULT '' AFTER `kode_ruang`;
  END IF;

  SELECT COUNT(*) INTO v_cnt FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'data_ruang' AND COLUMN_NAME = 'gedung';
  IF v_cnt = 0 THEN
    ALTER TABLE `data_ruang` ADD COLUMN `gedung` VARCHAR(50) NOT NULL DEFAULT '' AFTER `nama_ruang`;
  END IF;

  SELECT COUNT(*) INTO v_cnt FROM information_schema.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'data_ruang' AND COLUMN_NAME = 'lantai';
  IF v_cnt = 0 THEN
    ALTER TABLE `data_ruang` ADD COLUMN `lantai` INT(11) NOT NULL DEFAULT 0 AFTER `gedung`;
  END IF;

  -- Samakan nm_ruang dengan nama_ruang (data lama nm_ruang kosong semua)
  UPDATE `data_ruang`
     SET `nm_ruang` = `nama_ruang`
   WHERE (`nm_ruang` IS NULL OR `nm_ruang` = '')
     AND `nama_ruang` IS NOT NULL AND `nama_ruang` <> '';

  -- =========================================================
  --  2. data_inventaris.id_ruang -> VARCHAR(50)
  --     Sebelumnya INT, sedangkan id_ruang pada data_ruang berupa
  --     kode varchar (R001, R002, ...). Akibatnya ruang yang baru
  --     ditambahkan selalu tersimpan sebagai 0 dan tidak pernah
  --     ketemu saat JOIN.
  -- =========================================================
  ALTER TABLE `data_inventaris`
    MODIFY COLUMN `id_ruang` VARCHAR(50) NOT NULL DEFAULT '';

  UPDATE `data_inventaris` SET `id_ruang` = 'R001' WHERE `id_ruang` = '1';
  UPDATE `data_inventaris` SET `id_ruang` = ''      WHERE `id_ruang` = '0';

  -- =========================================================
  --  3. data_inventaris.id_inven -> AUTO_INCREMENT
  --     Sebelumnya NOT NULL tanpa auto increment, sehingga setiap
  --     INSERT selalu menghasilkan id_inven = 0 (duplicate key).
  -- =========================================================
  ALTER TABLE `data_inventaris`
    MODIFY COLUMN `id_inven` INT(10) NOT NULL AUTO_INCREMENT;

  -- =========================================================
  --  4. data_inventaris.stts -> VARCHAR(20)
  --     Form memakai kode status: Aktif / Proses / Rusak / Nonaktif
  --     (sebelumnya INT sehingga tersimpan 0 semua).
  -- =========================================================
  ALTER TABLE `data_inventaris`
    MODIFY COLUMN `stts` VARCHAR(20) NOT NULL DEFAULT 'Aktif';

  UPDATE `data_inventaris` SET `stts` = 'Aktif' WHERE `stts` = '0' OR `stts` = '';

  -- =========================================================
  --  5. Index pendukung pencarian
  -- =========================================================
  SELECT COUNT(*) INTO v_cnt FROM information_schema.STATISTICS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'data_inventaris' AND INDEX_NAME = 'idx_id_ruang';
  IF v_cnt = 0 THEN
    ALTER TABLE `data_inventaris` ADD INDEX `idx_id_ruang` (`id_ruang`);
  END IF;

  SELECT COUNT(*) INTO v_cnt FROM information_schema.STATISTICS
   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'data_ruang' AND INDEX_NAME = 'idx_kode_ruang';
  IF v_cnt = 0 THEN
    ALTER TABLE `data_ruang` ADD INDEX `idx_kode_ruang` (`kode_ruang`);
  END IF;
END$$
DELIMITER ;

CALL `mig_data_ruang`();
DROP PROCEDURE `mig_data_ruang`;
