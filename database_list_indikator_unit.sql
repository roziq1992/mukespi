-- =============================================================
-- RELASI list_indikator <-> UNIT & "Akses Unit User"
-- -------------------------------------------------------------
-- - 1 indikator terikat ke 1 unit (list_indikator.id_unit).
-- - User (Akses Unit User / tabel user_unit) melihat indikator dari
--   semua unit yang di-assign ke dirinya. 1 user boleh banyak unit.
-- - Admin (role 1) & Direktur (role 4) melihat semua indikator.
-- - list_indikator.userid tetap disimpan sebagai pencatat/pembuat.
-- - Idempotent: aman dijalankan berulang.
-- =============================================================

-- 1) Tambah kolom id_unit bila belum ada
SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'list_indikator'
              AND COLUMN_NAME = 'id_unit');
SET @sql = IF(@col = 0,
  'ALTER TABLE list_indikator ADD COLUMN id_unit INT(11) DEFAULT NULL AFTER jenis',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 2) Index untuk filter per unit
SET @idx = (SELECT COUNT(*) FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'list_indikator'
              AND INDEX_NAME = 'idx_list_indikator_unit');
SET @sql = IF(@idx = 0,
  'ALTER TABLE list_indikator ADD KEY idx_list_indikator_unit (id_unit)',
  'SELECT 1');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 3) Auto-map data lama: jenis yang persis sama dengan nama unit
UPDATE list_indikator
SET id_unit = (
    SELECT u.id_unit FROM unit u
    WHERE UPPER(TRIM(CONVERT(u.nm_unit USING utf8mb4))) COLLATE utf8mb4_general_ci
        = UPPER(TRIM(CONVERT(list_indikator.jenis USING utf8mb4))) COLLATE utf8mb4_general_ci
    LIMIT 1
)
WHERE id_unit IS NULL;

-- 4) Auto-map alias/sinonim jenis -> unit
UPDATE list_indikator SET id_unit = 6  WHERE id_unit IS NULL AND UPPER(TRIM(jenis)) = 'LABORATORIUM';  -- -> LABORAT
UPDATE list_indikator SET id_unit = 9  WHERE id_unit IS NULL AND UPPER(TRIM(jenis)) = 'REKAM MEDIS';   -- -> RM
UPDATE list_indikator SET id_unit = 19 WHERE id_unit IS NULL AND UPPER(TRIM(jenis)) = 'RAJAL';         -- -> RAWAT JALAN

-- 5) Sisa yang belum ter-map (isi id_unit lewat form Update)
SELECT 'BELUM TER-MAP' AS info, jenis, COUNT(*) AS jumlah
FROM list_indikator
WHERE id_unit IS NULL
GROUP BY jenis
ORDER BY jenis;
