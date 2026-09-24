-- ============================================================
-- Pelaporan Karyawan: Jam + Status Validasi (Admin/HRD)
-- RS Airlangga — CodeIgniter 3
-- Jalankan: mysql -u root mukespi < database_pelaporan_status.sql
-- ============================================================

-- Kolom waktu (jam) kejadian pada pelaporan / penilaian karyawan
ALTER TABLE pelaporan_karyawan
    ADD COLUMN `jam` TIME DEFAULT NULL AFTER `alasan`;

-- Status validasi oleh Admin/HRD: menunggu / divalidasi / ditolak
ALTER TABLE pelaporan_karyawan
    ADD COLUMN `status`           VARCHAR(20)  NOT NULL DEFAULT 'menunggu' AFTER `sanggahan_oleh`,
    ADD COLUMN `validasi_alasan`  TEXT         DEFAULT NULL AFTER `status`,
    ADD COLUMN `validasi_oleh`    VARCHAR(150) DEFAULT NULL AFTER `validasi_alasan`,
    ADD COLUMN `validasi_at`      DATETIME     DEFAULT NULL AFTER `validasi_oleh`;