-- ============================================================================
-- E-LIBRARY (Portal Penemuan Literatur) - Skema Basis Data
-- Aplikasi: RSA One / CodeIgniter 3 (database `mukespi`)
--
-- Modul ini hanya menyimpan METADATA literatur dan menautkan ke sumber
-- eksternal. Tidak ada penyimpanan file PDF pihak ketiga.
--
-- Cara pasang:
--   mysql -u root mukespi < database_elibrary.sql
--
-- Script aman diulang (idempotent): aman dijalankan berulang kali.
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 1. el_sources — katalog sumber (jurnal, repository, penerbit, dsb.)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS el_sources (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama          VARCHAR(255) NOT NULL COMMENT 'Nama sumber/jurnal',
  penerbit      VARCHAR(255) NULL COMMENT 'Penerbit / organisasi pengelola',
  deskripsi     TEXT NULL,
  homepage_url  VARCHAR(500) NULL COMMENT 'Halaman utama sumber',
  oai_pmh_url   VARCHAR(500) NULL COMMENT 'Endpoint OAI-PMH untuk harvester',
  aktif         TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL,
  updated_at    DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_el_sources_nama (nama(191)),
  KEY idx_el_sources_aktif (aktif)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- 2. el_entries — entri literatur (metadata) yang diindeks di katalog
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS el_entries (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  source_id     BIGINT UNSIGNED NULL COMMENT 'Sumber asal entri',
  judul         VARCHAR(500) NOT NULL,
  penulis       VARCHAR(500) NULL,
  abstrak       TEXT NULL,
  tahun         SMALLINT UNSIGNED NULL,
  volume        VARCHAR(50) NULL,
  nomor         VARCHAR(50) NULL,
  halaman       VARCHAR(50) NULL,
  doi           VARCHAR(255) NULL,
  nama_jurnal   VARCHAR(255) NULL COMMENT 'Nama jurnal/buku yang tercetak di publikasi',
  url_asli      VARCHAR(1000) NULL COMMENT 'URL sumber asli (tujuan "Buka di sumber")',
  kata_kunci    VARCHAR(500) NULL,
  jenis         VARCHAR(50) NOT NULL DEFAULT 'artikel' COMMENT 'artikel|buku|tesis|laporan|lainnya',
  bahasa        VARCHAR(50) NULL,
  sitasi        INT UNSIGNED NOT NULL DEFAULT 0,
  tanggal_masuk DATE NOT NULL,
  created_by    BIGINT UNSIGNED NULL,
  updated_by    BIGINT UNSIGNED NULL,
  created_at    DATETIME NOT NULL,
  updated_at    DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_el_entries_source (source_id),
  KEY idx_el_entries_tahun (tahun),
  KEY idx_el_entries_doi (doi(191)),
  KEY idx_el_entries_jurnal (nama_jurnal(191)),
  KEY idx_el_entries_judul (judul(191)),
  KEY idx_el_entries_tanggal (tanggal_masuk),
  KEY idx_el_entries_created_by (created_by),
  KEY idx_el_entries_updated_by (updated_by),
  FULLTEXT KEY ft_el_entries (judul, abstrak, penulis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- 3. el_subjects — subjek/topik untuk filter
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS el_subjects (
  id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama       VARCHAR(150) NOT NULL,
  slug       VARCHAR(150) NOT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uk_el_subjects_slug (slug),
  KEY idx_el_subjects_nama (nama)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- 4. el_entry_subjects — relasi entri <-> subjek (many-to-many)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS el_entry_subjects (
  entry_id   BIGINT UNSIGNED NOT NULL,
  subject_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (entry_id, subject_id),
  KEY idx_el_entry_subjects_subject (subject_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------------------
-- 5. el_access_logs — log akses viewed entri & klik ke sumber eksternal
--    id_user memakai user_id existing (tabel users), tidak ada tabel user baru.
--    Tidak dipasang FOREIGN KEY, mengikuti tabel yang sudah berjalan di
--    database (surat / surat_log juga tanpa FK constraint).
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS el_access_logs (
  id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  entry_id   BIGINT UNSIGNED NULL,
  user_id    BIGINT UNSIGNED NULL,
  aksi       VARCHAR(30) NOT NULL DEFAULT 'view' COMMENT 'view|buka_sumber|download',
  ip         VARCHAR(45) NULL,
  user_agent VARCHAR(255) NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_el_logs_entry (entry_id),
  KEY idx_el_logs_user (user_id),
  KEY idx_el_logs_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================================
-- SEED: subjek default (filter siap pakai, dapat ditambah admin)
-- ============================================================================
INSERT IGNORE INTO el_subjects (nama, slug, created_at) VALUES
  ('Manajemen Mutu Kesehatan',       'manajemen-mutu-kesehatan',      NOW()),
  ('Keselamatan Pasien',            'keselamatan-pasien',           NOW()),
  ('Pencegahan dan Pengendalian Infeksi', 'ppi',                   NOW()),
  ('Keperawatan',                   'keperawatan',                  NOW()),
  ('Farmasi',                       'farmasi',                      NOW()),
  ('Kebijakan Kesehatan',           'kebijakan-kesehatan',          NOW()),
  ('Kesehatan Masyarakat',          'kesehatan-masyarakat',         NOW()),
  ('Rekam Medis',                   'rekam-medis',                  NOW()),
  ('Pelayanan Pasien',              'pelayanan-pasien',             NOW()),
  ('Lainnya',                       'lainnya',                      NOW());

-- ============================================================================
-- SEED: menu E-Library di sidebar + hak akses seluruh role
-- Catatan: helper is_menu_accessible() bersifat fail-open bila url menu tidak
-- terdaftar, jadi begitu menu ini terdaftar SEMUA role wajib diberi akses
-- (browse) supaya tidak ada role yang kehilangan menu.
--
-- id menu dihitung manual (MAX(id)+1) karena tabel menus pada database yang
-- berjalan tidak memakai AUTO_INCREMENT.
-- ============================================================================
INSERT INTO menus (id, nama_menu, url, icon, sequence, is_active)
SELECT (SELECT COALESCE(MAX(m.id), 0) + 1 FROM (SELECT id FROM menus) m),
       'E-Library', 'e_library', 'fas fa-book-open', 10, 1
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE url = 'e_library');

-- grants memakai pola NOT EXISTS (tabel user_access_menu yang berjalan tidak
-- selalu punya UNIQUE KEY, jadi INSERT IGNORE bisa inserting ganda)
INSERT INTO user_access_menu (role_id, menu_id)
SELECT r.id, m.id
FROM roles r
CROSS JOIN (SELECT id FROM menus WHERE url = 'e_library') m
WHERE NOT EXISTS (
  SELECT 1 FROM user_access_menu uam
  WHERE uam.role_id = r.id AND uam.menu_id = m.id
);
