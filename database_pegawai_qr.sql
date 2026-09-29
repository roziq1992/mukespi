-- =============================================================
-- QR Code Data Pegawai - token tanpa NIK di URL
-- Aplikasi: mukespi (RS Airlangga) - CodeIgniter 3
-- -------------------------------------------------------------
-- NIK tidak lagi muncul pada URL hasil pemindaian. Setiap pegawai
-- mendapat satu token acak 32 hex; token inilah yang di-encode
-- menjadi QR dan menjadi kunci pencarian data.
--
-- Token dapat dihapus (regenerate) sehingga QR lama langsung
-- tidak berlaku lagi.
-- =============================================================

CREATE TABLE IF NOT EXISTS pegawai_qr_token (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nik         VARCHAR(30) NOT NULL,             -- NIK pemilik QR (16 digit)
    token       CHAR(32)    NOT NULL,             -- token acak, isi QR
    created_at  DATETIME    NOT NULL,
    UNIQUE KEY uk_pegawai_qr_token (token),
    KEY idx_pegawai_qr_nik (nik)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
