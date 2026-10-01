-- =====================================================================
--  SIPARDI — Nomor urut tampilan untuk Elemen Penilaian (EP)
-- =====================================================================
-- Tujuan:
--  Memisahkan DUA nomor pada elemen_penilaian:
--
--    no_ep    = NOMOR RESMI dari dokumen SIPARDI / Kemenkes.
--               Angka ini TIDAK PERNAH diubah oleh aplikasi, supaya nomor
--               di aplikasi selalu bisa dicocokkan dengan dokumen cetak
--               yang dipakai auditor.
--
--    no_urut  = NOMOR URUT TAMPILAN, tanpa celah.
--               Dipakai supaya daftar EP enak dibaca, dan bisa di-rapikan
--               lewat tombol "Rapikan Nomor" di halaman Standar & EP.
--
--  Contoh kasus: Standar 4 MFK punya EP resmi no. 1, 2, 3, 4, 5.
--  EP no. 2 dinonaktifkan karena salah input. Nomor resmi TETAP 1, 3, 4, 5,
--  tapi nomor tampilan jadi EP 1, 2, 3, 4 dengan label "no. resmi: 3".
--
--  Catatan: tidak ada satupun tabel lain yang berubah, dan tidak ada data
--  yang dihapus. no_ep tidak pernah disentuh.
-- =====================================================================

-- ---------- 1. Tambah kolom no_urut ----------
-- Dicek dulu supaya file ini aman dijalankan berulang kali.
SELECT COUNT(*) AS `sudah_ada_no_urut`
  FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE()
   AND TABLE_NAME   = 'elemen_penilaian'
   AND COLUMN_NAME  = 'no_urut';

SET @s = IF(
    (SELECT COUNT(*) FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'elemen_penilaian'
        AND COLUMN_NAME = 'no_urut') > 0,
    'DO 0',
    'ALTER TABLE `elemen_penilaian`
        ADD COLUMN `no_urut` int(3) NULL DEFAULT NULL AFTER `no_ep`'
);

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ---------- 2. Index untuk pengurutan ----------
SET @s = IF(
    (SELECT COUNT(*) FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'elemen_penilaian'
        AND COLUMN_NAME = 'no_urut') > 0,
    'DO 0',
    'ALTER TABLE `elemen_penilaian`
        ADD KEY `idx_no_urut` (`id_standar`, `no_urut`)'
);

PREPARE stmt FROM @s;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ---------- 3. Backfill ----------
-- EP yang belum punya no_urut diisi sama dengan no_ep, supaya tampilan
-- sebelum dan sesudah migrasi ini tidak melompat. no_ep tidak diubah.
UPDATE `elemen_penilaian`
   SET `no_urut` = `no_ep`
 WHERE `no_urut` IS NULL;

-- ---------- 4. Verifikasi ----------
SELECT COUNT(*) AS `total_ep`,
       SUM(no_urut IS NULL) AS `tanpa_no_urut`
  FROM `elemen_penilaian`;