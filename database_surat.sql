-- Modul Manajemen Surat Internal & Eksternal (CodeIgniter 3)
-- Jalankan setelah tabel users tersedia.
CREATE TABLE IF NOT EXISTS surat (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  jenis ENUM('internal','eksternal','disposisi') NOT NULL,
  perihal VARCHAR(255) NOT NULL,
  tujuan VARCHAR(255) NOT NULL,
  tanggal_pengajuan DATE NOT NULL,
  keterangan TEXT NULL,
  id_pemohon BIGINT UNSIGNED NOT NULL,
  no_surat VARCHAR(100) NULL,
  file_draft VARCHAR(255) NULL,
  file_ber_nomor VARCHAR(255) NULL,
  file_final VARCHAR(255) NULL,
  kode_verifikasi VARCHAR(64) NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'Diajukan',
  alasan_tolak TEXT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  PRIMARY KEY (id), KEY idx_surat_status (status), KEY idx_surat_tanggal (tanggal_pengajuan), KEY idx_surat_pemohon (id_pemohon),
  CONSTRAINT fk_surat_pemohon FOREIGN KEY (id_pemohon) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS surat_lampiran (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, id_surat BIGINT UNSIGNED NOT NULL, nama_file VARCHAR(255) NOT NULL, path_file VARCHAR(255) NOT NULL,
  uploaded_by BIGINT UNSIGNED NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id), KEY idx_lampiran_surat (id_surat),
  CONSTRAINT fk_lampiran_surat FOREIGN KEY (id_surat) REFERENCES surat(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS surat_disposisi (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, id_surat BIGINT UNSIGNED NOT NULL, dari_user BIGINT UNSIGNED NOT NULL, ke_user BIGINT UNSIGNED NULL,
  ke_bagian VARCHAR(150) NULL, catatan TEXT NOT NULL, status VARCHAR(40) NOT NULL DEFAULT 'Menunggu Tindak Lanjut', created_at DATETIME NOT NULL,
  PRIMARY KEY (id), KEY idx_disposisi_surat (id_surat), CONSTRAINT fk_disposisi_surat FOREIGN KEY (id_surat) REFERENCES surat(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS surat_log (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, id_surat BIGINT UNSIGNED NOT NULL, id_user BIGINT UNSIGNED NOT NULL, aksi VARCHAR(80) NOT NULL,
  keterangan TEXT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id), KEY idx_log_surat (id_surat), KEY idx_log_created (created_at),
  CONSTRAINT fk_log_surat FOREIGN KEY (id_surat) REFERENCES surat(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Perbaiki instalasi lama yang membuat id log tanpa AUTO_INCREMENT.
ALTER TABLE surat_log MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
-- Perbaiki instalasi lama yang membuat id disposisi/lampiran tanpa AUTO_INCREMENT.
SET @next_disposisi_id = (SELECT COALESCE(MAX(id), 0) + 1 FROM surat_disposisi WHERE id > 0);
UPDATE surat_disposisi SET id = @next_disposisi_id WHERE id = 0;
ALTER TABLE surat_disposisi MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
SET @next_lampiran_id = (SELECT COALESCE(MAX(id), 0) + 1 FROM surat_lampiran WHERE id > 0);
UPDATE surat_lampiran SET id = @next_lampiran_id WHERE id = 0;
ALTER TABLE surat_lampiran MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
-- Pastikan ID surat baru dibuat otomatis pada instalasi lama.
SET @next_surat_id = (SELECT COALESCE(MAX(id), 0) + 1 FROM surat);
SET FOREIGN_KEY_CHECKS = 0;
UPDATE surat_log SET id_surat = @next_surat_id WHERE id_surat = 0 AND EXISTS (SELECT 1 FROM surat WHERE id = 0);
UPDATE surat_lampiran SET id_surat = @next_surat_id WHERE id_surat = 0 AND EXISTS (SELECT 1 FROM surat WHERE id = 0);
UPDATE surat_disposisi SET id_surat = @next_surat_id WHERE id_surat = 0 AND EXISTS (SELECT 1 FROM surat WHERE id = 0);
ALTER TABLE surat MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
SET FOREIGN_KEY_CHECKS = 1;

-- Kolom alasan penolakan surat (untuk instalasi lama).
ALTER TABLE surat ADD COLUMN IF NOT EXISTS alasan_tolak TEXT NULL AFTER status;
-- Kolom token verifikasi dokumen surat (QR keaslian tanda tangan).
ALTER TABLE surat ADD COLUMN IF NOT EXISTS kode_verifikasi VARCHAR(64) NULL AFTER file_final;

-- Tambahkan role direktur bila instalasi memakai role_id terpisah (sesuaikan implementasi roles Anda).
ALTER TABLE surat MODIFY status VARCHAR(50) NOT NULL DEFAULT 'Diajukan';
UPDATE surat s
INNER JOIN (
  SELECT id_surat, MAX(id) AS log_id
  FROM surat_log
  GROUP BY id_surat
) d ON d.id_surat = s.id
INNER JOIN surat_log l ON l.id = d.log_id
SET s.status = CASE
    WHEN l.aksi = 'Surat ditandatangani' THEN 'Ditandatangani'
    WHEN l.aksi = 'Surat didisposisikan' THEN 'Didisposisikan'
    WHEN l.aksi = 'Disposisi diselesaikan' THEN 'Selesai'
    ELSE s.status
  END,
  s.updated_at = NOW()
WHERE s.status = 'Diajukan';
INSERT INTO roles (id, name) SELECT 4, 'direktur' WHERE NOT EXISTS (SELECT 1 FROM roles WHERE id = 4);
INSERT INTO roles (id, name) SELECT 5, 'sekretaris' WHERE NOT EXISTS (SELECT 1 FROM roles WHERE id = 5);

-- Log penghapusan surat. Sengaja tanpa foreign key agar catatan tetap ada
-- setelah surat (beserta surat_log yang ON DELETE CASCADE) dihapus.
CREATE TABLE IF NOT EXISTS surat_hapus_log (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_surat BIGINT UNSIGNED NOT NULL,
  perihal VARCHAR(255) NOT NULL,
  jenis VARCHAR(20) NULL,
  no_surat VARCHAR(100) NULL,
  status VARCHAR(50) NULL,
  id_pemohon BIGINT UNSIGNED NULL,
  nama_pemohon VARCHAR(255) NULL,
  id_user BIGINT UNSIGNED NOT NULL,
  nama_user VARCHAR(255) NOT NULL,
  role_id BIGINT UNSIGNED NULL,
  role_nama VARCHAR(50) NULL,
  keterangan TEXT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_hapus_created (created_at),
  KEY idx_hapus_user (id_user),
  KEY idx_hapus_surat (id_surat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

