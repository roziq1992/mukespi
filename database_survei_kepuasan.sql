-- =============================================================
-- Survei Kepuasan Pasien — migration
-- Aplikasi: mukespi (RS Airlangga) — CodeIgniter 3
-- -------------------------------------------------------------
-- Formulir diisi PASIEN secara publik (tanpa login), admin/surveyor/HRD
-- memantau & menindaklanjuti hasilnya.
-- =============================================================

-- 1. Master aspek penilaian (bintang 1..5 per aspek)
CREATE TABLE IF NOT EXISTS survei_aspek (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode        VARCHAR(30)  NOT NULL,
    nama_aspek  VARCHAR(150) NOT NULL,
    deskripsi   VARCHAR(255) NULL,
    icon        VARCHAR(100) NOT NULL DEFAULT 'fas fa-star',
    urutan      INT NOT NULL DEFAULT 0,
    bobot       TINYINT UNSIGNED NOT NULL DEFAULT 1,
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL,
    UNIQUE KEY uk_survei_aspek_kode (kode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Header satu pengisian survei (satu pasien = satu baris)
CREATE TABLE IF NOT EXISTS survei_responden (
    id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode              VARCHAR(30) NOT NULL,          -- RESP-20260131-0007
    nama              VARCHAR(150) NULL,
    nik               VARCHAR(30) NULL,
    no_rm             VARCHAR(50) NULL,
    id_unit           INT NULL,                     -- unit yang dilayani (signed, mengikuti unit.id_unit)
    tanggal_kunjungan DATE NOT NULL,
    tanggal_survei    DATETIME NOT NULL,
    umur              TINYINT UNSIGNED NULL,
    jenis_kelamin     ENUM('L','P') NULL,
    metode            ENUM('QR','Mandiri','Petugas') NOT NULL DEFAULT 'Mandiri',
    is_anonim         TINYINT(1) NOT NULL DEFAULT 0,
    jumlah_aspek      SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    skor_total        INT UNSIGNED NOT NULL DEFAULT 0,
    skor_rata         DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    predikat          VARCHAR(20) NOT NULL DEFAULT '-',
    rekomendasi       TINYINT UNSIGNED NULL,        -- skala 0..10 (NPS)
    saran             TEXT NULL,
    is_kritik         TINYINT(1) NOT NULL DEFAULT 0, -- ada aspek bernilai 1..2
    created_at        DATETIME NOT NULL,
    UNIQUE KEY uk_survei_responden_kode (kode),
    KEY idx_survei_tanggal (tanggal_survei),
    KEY idx_survei_unit (id_unit),
    KEY idx_survei_kategori (predikat),
    CONSTRAINT fk_survei_unit FOREIGN KEY (id_unit) REFERENCES unit(id_unit) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Jawaban tiap aspek (satu baris per aspek per responden)
CREATE TABLE IF NOT EXISTS survei_jawaban (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_responden BIGINT UNSIGNED NOT NULL,
    id_aspek     INT UNSIGNED NOT NULL,
    skor         TINYINT UNSIGNED NOT NULL,        -- 1..5 bintang
    created_at   DATETIME NOT NULL,
    UNIQUE KEY uk_jawaban_unik (id_responden, id_aspek),
    KEY idx_jawaban_aspek (id_aspek),
    CONSTRAINT fk_jawaban_responden FOREIGN KEY (id_responden) REFERENCES survei_responden(id) ON DELETE CASCADE,
    CONSTRAINT fk_jawaban_aspek FOREIGN KEY (id_aspek) REFERENCES survei_aspek(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Riwayat tindak lanjut admin terhadap keluhan / saran
CREATE TABLE IF NOT EXISTS survei_tindak_lanjut (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_responden BIGINT UNSIGNED NOT NULL,
    id_user      BIGINT UNSIGNED NULL,
    status       ENUM('Diproses','Selesai') NOT NULL DEFAULT 'Diproses',
    catatan      TEXT NULL,
    created_at   DATETIME NOT NULL,
    KEY idx_tl_responden (id_responden),
    CONSTRAINT fk_tl_responden FOREIGN KEY (id_responden) REFERENCES survei_responden(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Kunci anti-spam: satu fingerprint hanya boleh 1 survei per hari
CREATE TABLE IF NOT EXISTS survei_antispam (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fingerprint VARCHAR(64) NOT NULL,
    created_at  DATETIME NOT NULL,
    KEY idx_antispam_fp (fingerprint)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- SEED master aspek penilaian
-- =============================================================
INSERT INTO survei_aspek (kode, nama_aspek, deskripsi, icon, urutan, bobot, is_active) VALUES
('dokter',        'Layanan Dokter',          'Keramian, komunikasi, dan penjelasan dari dokter',                    'fas fa-user-md',            1, 3, 1),
('perawat',       'Layanan Perawat',         'Keterbukaan, responsivitas, dan bantuan perawat',                    'fas fa-user-nurse',         2, 2, 1),
('bidan',         'Layanan Kebidanan',       'Keramian dan profesionalitas pelayanan kebidanan',                    'fas fa-hand-holding-heart', 3, 1, 1),
('farmasi',       'Layanan Farmasi',         'Kecepatan dan ketepatan pelayanan pengambilan obat',                  'fas fa-pills',              4, 1, 1),
('laboratorium',  'Layanan Laboratorium',    'Ketepatan hasil dan keramahan petugas laboratorium',                   'fas fa-flask',              5, 1, 1),
('radiologi',     'Layanan Radiologi',       'Ketepatan hasil dan pelayanan radiologi',                             'fas fa-x-ray',              6, 1, 1),
('gizi',          'Layanan Gizi',            'Kualitas, variasi, dan suhu makanan',                                 'fas fa-utensils',           7, 1, 1),
('administrasi',  'Layanan Administrasi',    'Keramahan dan kecepatan pendaftaran, rekam medis, serta pembayaran', 'fas fa-file-alt',           8, 1, 1),
('sarana',        'Sarana & Prasarana',      'Kondisi, kebersihan, dan kecukupan fasilitas',                        'fas fa-hospital',           9, 1, 1),
('kebersihan',    'Kebersihan & Kenyamanan', 'Kebersihan ruang perawatan dan area tunggu',                           'fas fa-broom',             10, 1, 1),
('layanan_umum',  'Layanan Umum',           'Toilet, mushola, tombol/help desk, dan informasi rumah sakit',       'fas fa-headset',           11, 1, 1)
ON DUPLICATE KEY UPDATE nama_aspek = VALUES(nama_aspek);

-- =============================================================
-- SEED menu RBAC (idempotent — aman dijalankan berulang)
-- =============================================================
INSERT INTO menus (nama_menu, url, icon, sequence, is_active)
SELECT 'Survei Kepuasan Pasien', 'survei_admin', 'fas fa-star', 20, 1
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE url = 'survei_admin');

-- Role 1 (admin), 3 (surveior), 6 (HRD) boleh melihat hasil survei
INSERT INTO user_access_menu (role_id, menu_id)
SELECT r.role_id, m.id
FROM (SELECT 1 AS role_id UNION SELECT 3 UNION SELECT 6) r
JOIN menus m ON m.url = 'survei_admin'
WHERE NOT EXISTS (
    SELECT 1 FROM user_access_menu uam
    WHERE uam.role_id = r.role_id AND uam.menu_id = m.id
);
