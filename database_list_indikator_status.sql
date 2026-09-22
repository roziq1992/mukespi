-- =============================================================
-- STATUS AKTIF / NONAKTIF pada list_indikator
-- -------------------------------------------------------------
-- - Menambah kolom status (aktif/nonaktif) pada list_indikator.
-- - Indikator lama dianggap aktif (status default 'aktif').
-- - Idempotent: aman dijalankan berulang.
-- =============================================================

SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'list_indikator'
              AND COLUMN_NAME = 'status');
SET @sql = IF(@col = 0,
  'ALTER TABLE list_indikator ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT ''aktif'' AFTER userid',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SELECT 'STATUS AKTIF' AS info, COUNT(*) AS jumlah FROM list_indikator WHERE status = 'aktif';
SELECT 'STATUS NONAKTIF' AS info, COUNT(*) AS jumlah FROM list_indikator WHERE status = 'nonaktif';