-- =============================================================
-- Modul Penilaian Kinerja Pegawai (Penilaian Kinerja) - RS Airlangga
-- Jalankan: mysql -u root mukespi < database_penilaian_kinerja.sql
-- =============================================================

-- 0. Tambahkan penanda kepala unit pada tabel user_unit (idempotent)
SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'user_unit' AND COLUMN_NAME = 'is_kepala');
SET @sql = IF(@col = 0,
  'ALTER TABLE user_unit ADD COLUMN is_kepala TINYINT(1) NOT NULL DEFAULT 0 AFTER id_unit',
  'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- 1. Periode penilaian
CREATE TABLE IF NOT EXISTS pk_periode (
  id_periode INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama VARCHAR(150) NOT NULL,
  tahun YEAR NOT NULL,
  tanggal_mulai DATE DEFAULT NULL,
  tanggal_selesai DATE DEFAULT NULL,
  status ENUM('aktif','selesai','draft') NOT NULL DEFAULT 'draft',
  created_at DATETIME DEFAULT NULL,
  updated_at DATETIME DEFAULT NULL,
  PRIMARY KEY (id_periode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Kriteria penilaian per unit (bobot dalam persen, jumlah bobot 1 unit normalnya 100)
CREATE TABLE IF NOT EXISTS pk_kriteria (
  id_kriteria INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_unit INT(11) NOT NULL,
  kelompok VARCHAR(150) NOT NULL,
  kriteria VARCHAR(255) NOT NULL,
  bobot DECIMAL(6,2) NOT NULL DEFAULT 0,
  urut INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME DEFAULT NULL,
  updated_at DATETIME DEFAULT NULL,
  PRIMARY KEY (id_kriteria),
  KEY idx_pkk_unit (id_unit)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Header penilaian (penilai = kepala unit / users.id, dinilai = users.id yang ada di unit)
CREATE TABLE IF NOT EXISTS pk_penilaian (
  id_penilaian INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_periode INT UNSIGNED NOT NULL,
  id_unit INT(11) NOT NULL,
  id_penilai BIGINT(20) UNSIGNED NOT NULL,
  id_dinilai BIGINT(20) UNSIGNED NOT NULL,
  tanggal_penilaian DATE DEFAULT NULL,
  total_nilai DECIMAL(6,2) DEFAULT NULL,
  status ENUM('draft','selesai','ditolak') NOT NULL DEFAULT 'draft',
  catatan TEXT NULL,
  created_at DATETIME DEFAULT NULL,
  updated_at DATETIME DEFAULT NULL,
  UNIQUE KEY uq_pkpen (id_periode, id_unit, id_penilai, id_dinilai),
  PRIMARY KEY (id_penilaian),
  KEY idx_pkpen_dinilai (id_dinilai),
  KEY idx_pkpen_penilai (id_penilai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Skor per kriteria pada sebuah penilaian (skala 1-5, 0 = belum diisi)
CREATE TABLE IF NOT EXISTS pk_skor (
  id_skor INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_penilaian INT UNSIGNED NOT NULL,
  id_kriteria INT UNSIGNED NOT NULL,
  skor TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (id_skor),
  UNIQUE KEY uq_pkskor (id_penilaian, id_kriteria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Predikat / grading per unit (dapat diubah lewat menu admin)
CREATE TABLE IF NOT EXISTS pk_grading (
  id_grading INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_unit INT(11) NOT NULL,
  label VARCHAR(100) NOT NULL,
  nilai_min DECIMAL(6,2) NOT NULL,
  nilai_max DECIMAL(6,2) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  urut INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id_grading),
  KEY idx_pkg_unit (id_unit)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Menu & hak akses
INSERT INTO menus (nama_menu, url, icon, sequence, is_active)
SELECT 'Penilaian Kinerja', 'penilaian_kinerja', 'fas fa-clipboard-check', 16, 1
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE url = 'penilaian_kinerja');

SET @mid = (SELECT id FROM menus WHERE url = 'penilaian_kinerja' LIMIT 1);
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 1, @mid WHERE @mid IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM user_access_menu WHERE role_id = 1 AND menu_id = @mid);
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 2, @mid WHERE @mid IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM user_access_menu WHERE role_id = 2 AND menu_id = @mid);
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 6, @mid WHERE @mid IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM user_access_menu WHERE role_id = 6 AND menu_id = @mid);

-- 7. Periode awal (tahun berjalan)
INSERT INTO pk_periode (nama, tahun, tanggal_mulai, tanggal_selesai, status, created_at, updated_at)
SELECT CONCAT('Penilaian Kinerja ', YEAR(CURDATE())), YEAR(CURDATE()),
       CONCAT(YEAR(CURDATE()), '-01-01'), CONCAT(YEAR(CURDATE()), '-12-31'), 'aktif', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM pk_periode WHERE status = 'aktif');

-- =============================================================
-- SEED KRITERIA
-- =============================================================

-- ============ FARMASI (unit 2) ============
INSERT INTO pk_kriteria (id_unit, kelompok, kriteria, bobot, urut)
SELECT 2, k, i, b, u FROM (
  SELECT '1. SIKAP KERJA' k, 'Kehadiran/absensi' i, 4.00 b, 1 u UNION ALL
  SELECT '1. SIKAP KERJA', 'Disiplin', 4.00, 2 UNION ALL
  SELECT '1. SIKAP KERJA', 'Dedikasi', 4.00, 3 UNION ALL
  SELECT '1. SIKAP KERJA', 'Kerja sama', 4.00, 4 UNION ALL
  SELECT '1. SIKAP KERJA', 'Komunikasi', 4.00, 5 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Memeriksa ketersediaan sediaan farmasi & perbekalan kesehatan di unit kerja', 1.15, 1 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Memeriksa persediaan sediaan farmasi & perbekalan kesehatan yang mendekati waktu kadaluarsa', 1.15, 2 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Mencatat permintaan obat dan alat kesehatan pada buku defekta', 1.15, 3 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Melakukan opname perbekalan farmasi setiap bulan', 1.15, 4 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Mengusulkan kebutuhan sediaan farmasi dan perbekalan kesehatan di unit kerja', 1.15, 5 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Memesan sediaan farmasi dan perbekalan kesehatan berdasarkan permintaan dari apoteker', 1.15, 6 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menerima sediaan farmasi/perbekalan kesehatan dan memeriksa kesesuaian pesanan', 1.15, 7 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Memeriksa keadaan fisik sediaan farmasi dan perbekalan kesehatan', 1.15, 8 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Mengevaluasi kualitas fisik barang', 1.15, 9 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Mengentry faktur penerimaan ke komputer dan mendokumentasikan', 1.15, 10 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Membuat bukti penerimaan', 1.15, 11 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menyimpan sediaan farmasi dan perbekalan kesehatan sesuai golongannya', 1.15, 12 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menyimpan sediaan farmasi dan perbekalan kesehatan sesuai bentuk sediaannya', 1.15, 13 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menyimpan sediaan farmasi dan perbekalan kesehatan sesuai sifat fisika dan kimia berdasarkan informasi dalam kemasan', 1.15, 14 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Melakukan pengelompokan faktur pembelian dan resep sesuai prosedur', 1.15, 15 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menyimpan faktur pembelian dan resep', 1.15, 16 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Mengelompokan resep yang akan dimusnahkan', 1.15, 17 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menyiapkan, mengisi dan menyimpan kartu stok', 1.15, 18 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Mengeluarkan Perbekalan Farmasi sesuai permintaan Ruangan', 1.15, 19 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Mendokumentasikan berkas permintaan', 1.15, 20 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Melakukan serah terima perbekalan farmasi ke ruangan', 1.15, 21 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Memeriksa Emergency Kit', 1.15, 22 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menghitung jumlah sediaan farmasi/perbekalan kesehatan', 1.15, 23 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menghitung biaya', 1.15, 24 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menginformasikan jumlah biaya', 1.15, 25 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Dokumentasi', 1.15, 26 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menghitung dosis / jumlah obat dalam resep yang akan diberikan', 1.15, 27 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menghitung harga obat dalam resep yang diberikan', 1.15, 28 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menyerahkan hasil kalkulasi pada kasir', 1.15, 29 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Melakukan pencatatan', 1.15, 30 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menerima dan memeriksa resep (Pengkajian Resep)', 1.15, 31 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Memberikan usulan pemecahan masalah terkait adanya OTT fisika/kimia', 1.15, 32 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menyiapkan sediaan farmasi sesuai prosedur', 1.15, 33 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Meracik sediaan farmasi dibawah pengawasan Apoteker/Pimpinan Unit', 1.15, 34 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menyiapkan obat & perbekalan farmasi sesuai permintaan', 1.15, 35 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menyiapkan pengemasan', 1.15, 36 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Pelaksanaan dispensing', 1.15, 37 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Melakukan pencatatan', 1.15, 38 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menulis etiket', 1.15, 39 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Pemberian etiket dan label pada kemasan', 1.15, 40 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Melakukan pengecekan etiket dan label', 1.15, 41 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Verifikasi kesesuaian resep dan obat yang diberikan', 1.15, 42 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menyerahkan obat kepada pasien yang tepat', 1.15, 43 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Memberikan Informasi tentang pemakaian obat', 1.15, 44 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Membuat dokumentasi', 1.15, 45 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menerima dan klarifikasi perintah', 1.15, 46 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menerima dan meneruskan pesan', 1.15, 47 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Menunjukan ketrampilan pribadi yang benar', 1.15, 48 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Memberikan informasi yang benar', 1.15, 49 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Melakukan rekam farmasi', 1.15, 50 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Melakukan pencatatan semua data', 1.15, 51 UNION ALL
  SELECT '2. KINERJA PELAYANAN', 'Penyimpanan dokumen', 1.15, 52 UNION ALL
  SELECT '3. MUTU PELAYANAN', 'Kesalahan pemberian obat', 10.00, 1 UNION ALL
  SELECT '3. MUTU PELAYANAN', 'Waktu Tunggu Obat', 10.00, 2
) d
WHERE NOT EXISTS (SELECT 1 FROM pk_kriteria WHERE id_unit = 2);

-- ============ LABORATORIUM (unit 6) ============
INSERT INTO pk_kriteria (id_unit, kelompok, kriteria, bobot, urut)
SELECT 6, k, i, b, u FROM (
  SELECT '1. PERILAKU' k, 'Etik & Disiplin' i, 7.50 b, 1 u UNION ALL
  SELECT '1. PERILAKU', 'Partisipasi penuh terhadap keselamatan', 7.50, 2 UNION ALL
  SELECT '1. PERILAKU', 'Saling menghormati', 7.50, 3 UNION ALL
  SELECT '1. PERILAKU', 'Komunikasi', 7.50, 4 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berkomunikasi baik dengan pasien/keluarga', 2.00, 1 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan promosi kesehatan', 2.00, 2 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan peningkatan mutu pelayanan laboratorium', 2.00, 3 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi penuh di kegiatan ilmiah', 2.00, 4 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi aktif di RTD, konsultasi tim dan kepemimpinan tim', 2.00, 5 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Pemahaman terhadap regulasi rumah sakit yg terkait tugasnya', 2.00, 6 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan tindakan aseptik', 2.00, 7 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Mengikuti kegiatan E-Learning', 2.00, 8 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menghemat sumberdaya perusahaan dan meminimalkan pemborosan', 2.00, 9 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi dalam melakukan asuhan yang efisien', 2.00, 10 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Mengedukasi pasien untuk pemeriksaan laboratorium', 2.00, 11 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan evaluasi dan dokumentasi laboratorium', 2.00, 12 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Memelihara sarana, fasilitas laboratorium, dan kebersihan ruangan', 2.00, 13 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi penyusunan pedoman dan SOP laboratorium', 2.00, 14 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Peningkatan keterampilan professional (workshop, pelatihan, magang)', 2.00, 15 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan kompetensi dasar ahli teknologi laboratorium', 3.33, 1 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan identifikasi pasien', 3.33, 2 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan proses pre analitik, analitik, post analitik', 3.33, 3 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepatuhan terhadap SPO', 3.33, 4 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepuasan pelanggan internal & eksternal', 3.33, 5 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Pelaporan waktu nilai kritis laboratorium', 3.33, 6 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Waktu tunggu laboratorium cito', 3.33, 7 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepatuhan implementasi RKK', 3.33, 8 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Pemahaman pasien terhadap edukasi petugas laboratorium', 3.33, 9 UNION ALL
  SELECT '4. MUTU UNIT', 'Kesalahan cetak hasil laboratorium', 10.00, 1
) d
WHERE NOT EXISTS (SELECT 1 FROM pk_kriteria WHERE id_unit = 6);

-- ============ RADIOLOGI (unit 7) ============
INSERT INTO pk_kriteria (id_unit, kelompok, kriteria, bobot, urut)
SELECT 7, k, i, b, u FROM (
  SELECT '1. PERILAKU' k, 'Etik & Disiplin' i, 7.50 b, 1 u UNION ALL
  SELECT '1. PERILAKU', 'Partisipasi penuh terhadap keselamatan', 7.50, 2 UNION ALL
  SELECT '1. PERILAKU', 'Saling menghormati', 7.50, 3 UNION ALL
  SELECT '1. PERILAKU', 'Komunikasi', 7.50, 4 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berkomunikasi baik dengan pasien/keluarga', 2.00, 1 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan promosi kesehatan', 2.00, 2 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan peningkatan mutu pelayanan radiologi', 2.00, 3 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi penuh di kegiatan ilmiah', 2.00, 4 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi aktif di RTD, konsultasi tim dan kepemimpinan tim', 2.00, 5 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Pemahaman terhadap regulasi rumah sakit yg terkait tugasnya', 2.00, 6 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan tindakan aseptik', 2.00, 7 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Mengikuti kegiatan E-Learning', 2.00, 8 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menghemat sumberdaya perusahaan dan meminimalkan pemborosan', 2.00, 9 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi dalam melakukan asuhan yang efisien', 2.00, 10 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Mengedukasi pasien untuk pemeriksaan radiologi', 2.00, 11 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan evaluasi dan dokumentasi radiologi', 2.00, 12 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Memelihara sarana, fasilitas radiologi dan kebersihan ruangan', 2.00, 13 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi penyusunan pedoman dan SOP radiologi', 2.00, 14 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Peningkatan keterampilan professional (workshop, pelatihan, magang)', 2.00, 15 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan kompetensi dasar ahli teknologi radiologi', 3.33, 1 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan identifikasi pasien', 3.33, 2 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepatuhan terhadap SPO', 3.33, 3 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepuasan pelanggan internal & eksternal', 3.33, 4 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepatuhan penggunaan APD bagi penunggu pasien yang berada di dalam ruangan', 3.33, 5 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Ketepatan pencetakan film radiograf sebelum diberikan ke dokter pengirim', 3.33, 6 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Frekuensi pengulangan pemeriksaan radiologi', 3.33, 7 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepatuhan implementasi RKK', 3.33, 8 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Pemahaman pasien terhadap edukasi petugas radiologi', 3.33, 9 UNION ALL
  SELECT '4. MUTU UNIT', 'Hasil Rontgen thorax rawat jalan kurang dari 3 jam', 10.00, 1
) d
WHERE NOT EXISTS (SELECT 1 FROM pk_kriteria WHERE id_unit = 7);

-- ============ PERAWAT (unit 4) ============
INSERT INTO pk_kriteria (id_unit, kelompok, kriteria, bobot, urut)
SELECT 4, k, i, b, u FROM (
  SELECT '1. PERILAKU' k, 'Etik & Disiplin' i, 7.50 b, 1 u UNION ALL
  SELECT '1. PERILAKU', 'Partisipasi penuh terhadap keselamatan', 7.50, 2 UNION ALL
  SELECT '1. PERILAKU', 'Saling menghormati', 7.50, 3 UNION ALL
  SELECT '1. PERILAKU', 'Komunikasi', 7.50, 4 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Membina hubungan terapeutik dengan pasien/keluarga', 2.00, 1 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan promosi kesehatan', 2.00, 2 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Memberikan Asuhan keperawatan/kebidanan sesuai PPK', 2.00, 3 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi penuh di pertemuan ilmiah', 2.00, 4 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan peningkatan mutu asuhan keperawatan / kebidanan', 2.00, 5 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi aktif di RTD, konsultasi tim dan kepemimpinan tim', 2.00, 6 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Ketepatan waktu memberikan asuhan keperawatan / kebidanan', 2.00, 7 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Pemahaman terhadap regulasi rumah sakit yg terkait tugasnya', 2.00, 8 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan tindakan aseptik', 2.00, 9 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menerapkan prinsip-prinsip infeksi nosokomial', 2.00, 10 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menghemat sumberdaya perusahaan dan meminimalkan pemborosan', 2.00, 11 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi dalam melakukan asuhan yang efisien', 2.00, 12 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menyiapkan klien untuk pemeriksaan diagnostik, laboratorium, pengobatan, dan tindakan keperawatan / kebidanan', 2.00, 13 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan evaluasi dan dokumentasi tindakan keperawatan / kebidanan', 2.00, 14 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Memelihara sarana, fasilitas keperawatan, dan kebersihan ruangan', 2.00, 15 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan kompetensi dasar keperawatan/kebidanan', 3.33, 1 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan kompetensi inti PK 1/2/3/4/5', 3.33, 2 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Menerapkan proses keperawatan / kebidanan kebutuhan dasar', 3.33, 3 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepatuhan terhadap SPO', 3.33, 4 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepuasan pelanggan internal & eksternal', 3.33, 5 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Pengkajian resiko pasien jatuh', 3.33, 6 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Pelaksanaan pengkajian nyeri', 3.33, 7 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepatuhan implementasi RKKK', 3.33, 8 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Pemahaman pasien terhadap edukasi perawat / bidan', 3.33, 9 UNION ALL
  SELECT '4. MUTU UNIT', 'Kepatuhan pendokumentasian assesement nyeri di rekam medis', 5.00, 1 UNION ALL
  SELECT '4. MUTU UNIT', 'Kelengkapan asessment awal keperawatan dalam 24 jam', 5.00, 2
) d
WHERE NOT EXISTS (SELECT 1 FROM pk_kriteria WHERE id_unit = 4);

-- ============ KEBIDANAN (unit 5) ============
INSERT INTO pk_kriteria (id_unit, kelompok, kriteria, bobot, urut)
SELECT 5, k, i, b, u FROM (
  SELECT '1. PERILAKU' k, 'Etik & Disiplin' i, 7.50 b, 1 u UNION ALL
  SELECT '1. PERILAKU', 'Partisipasi penuh terhadap keselamatan', 7.50, 2 UNION ALL
  SELECT '1. PERILAKU', 'Saling menghormati', 7.50, 3 UNION ALL
  SELECT '1. PERILAKU', 'Komunikasi', 7.50, 4 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Membina hubungan terapeutik dengan pasien/keluarga', 2.00, 1 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan promosi kesehatan', 2.00, 2 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Memberikan Asuhan kebidanan sesuai PPK', 2.00, 3 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi penuh di pertemuan ilmiah', 2.00, 4 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan peningkatan mutu asuhan kebidanan', 2.00, 5 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi aktif di RTD, konsultasi tim dan kepemimpinan tim', 2.00, 6 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Ketepatan waktu memberikan asuhan kebidanan', 2.00, 7 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Pemahaman terhadap regulasi rumah sakit yg terkait tugasnya', 2.00, 8 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan tindakan aseptik', 2.00, 9 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menerapkan prinsip-prinsip infeksi nosokomial', 2.00, 10 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menghemat sumberdaya perusahaan dan meminimalkan pemborosan', 2.00, 11 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berpartisipasi dalam melakukan asuhan yang efisien', 2.00, 12 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menyiapkan klien untuk pemeriksaan diagnostik, laboratorium, pengobatan, dan tindakan kebidanan', 2.00, 13 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Melakukan evaluasi dan dokumentasi tindakan kebidanan', 2.00, 14 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Memelihara sarana, fasilitas kebidanan, dan kebersihan ruangan', 2.00, 15 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan kompetensi dasar kebidanan', 3.33, 1 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan kompetensi inti PK 1/2/3/4/5', 3.33, 2 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Menerapkan proses kebidanan kebutuhan dasar', 3.33, 3 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepatuhan terhadap SPO', 3.33, 4 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepuasan pelanggan internal & eksternal', 3.33, 5 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Pengkajian resiko pasien jatuh', 3.33, 6 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Pelaksanaan pengkajian nyeri', 3.33, 7 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepatuhan implementasi RKKK', 3.33, 8 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Pemahaman pasien terhadap edukasi bidan', 3.33, 9 UNION ALL
  SELECT '4. MUTU UNIT', 'Kepatuhan pendokumentasian assesement nyeri di rekam medis', 5.00, 1 UNION ALL
  SELECT '4. MUTU UNIT', 'Kelengkapan asessment awal kebidanan dalam 24 jam', 5.00, 2
) d
WHERE NOT EXISTS (SELECT 1 FROM pk_kriteria WHERE id_unit = 5);

-- ============ REKAM MEDIS / RM (unit 9) ============
INSERT INTO pk_kriteria (id_unit, kelompok, kriteria, bobot, urut)
SELECT 9, k, i, b, u FROM (
  SELECT '1. PERILAKU' k, 'Etik & Disiplin' i, 10.00 b, 1 u UNION ALL
  SELECT '1. PERILAKU', 'Partisipasi penuh terhadap keselamatan', 10.00, 2 UNION ALL
  SELECT '1. PERILAKU', 'Saling menghormati', 10.00, 3 UNION ALL
  SELECT '1. PERILAKU', 'Komunikasi', 10.00, 4 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Mengikuti petunjuk untuk kepuasan pelanggan yang maksimal', 2.00, 1 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Membina hubungan baik dengan pelanggan internal dan eksternal', 2.00, 2 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Mampu dan bersedia untuk bekerja secara efektif dengan orang lain dalam tim', 2.00, 3 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Berkomunikasi secara efektif untuk berbagi informasi dan/atau keterampilan dengan rekan-rekan', 2.00, 4 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menghemat sumberdaya perusahaan dan meminimalkan pemborosan', 2.00, 5 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Memiliki pengetahuan tentang prosedur kerja dan persyaratan pekerjaan', 2.00, 6 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menunjukkan teknis kompetensi / keahlian di bidang spesialisasi', 2.00, 7 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menampilkan komitmen untuk bekerja', 2.00, 8 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Rencana dan mengatur kerja efektif', 2.00, 9 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Apakah proaktif dan menunjukan inisiatif', 2.00, 10 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Memiliki rasa urgensi dalam bertindak pada hal-hal pekerjaan / skala prioritas', 2.00, 11 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Menampilkan kemauan untuk belajar', 2.00, 12 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Apakah akurat, tuntas dan hati-hati dengan pekerjaan yang dilakukan', 2.00, 13 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Membantu masalah staf, rekan kerja pada hal-hal yang berhubungan dengan pekerjaan', 2.00, 14 UNION ALL
  SELECT '2. PENGEMBANGAN PROFESIONAL', 'Memiliki tingkat kehadiran yang baik', 2.00, 15 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan Proses pengambilan dokumen rekam medis', 3.33, 1 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan proses pengembalian rekam medis', 3.33, 2 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan kegiatan Assembling dokumen rekam medis rawat inap', 3.33, 3 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Kepatuhan terhadap SPO', 3.33, 4 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Melakukan Kegiatan KLPCM', 3.33, 5 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Meinput data mutu rekam medis', 3.33, 6 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Pengkodingan dokumen rawat inap', 3.33, 7 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Merakit dokumen rawat inap', 3.33, 8 UNION ALL
  SELECT '3. KINERJA KLINIS', 'Pengelolaan laporan rekam medis', 3.33, 9
) d
WHERE NOT EXISTS (SELECT 1 FROM pk_kriteria WHERE id_unit = 9);

-- ============ CSSD (unit 8) ============
INSERT INTO pk_kriteria (id_unit, kelompok, kriteria, bobot, urut)
SELECT 8, k, i, b, u FROM (
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN' k, 'Memahami tugas & tanggung jawab' i, 4.00 b, 1 u UNION ALL
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN', 'Memiliki pengetahuan dibidangnya', 3.00, 2 UNION ALL
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN', 'Menggunakan informasi dengan tepat dan benar', 3.00, 3 UNION ALL
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN', 'Mengikuti perkembangan pengetahuan', 3.00, 4 UNION ALL
  SELECT '2. KUALITAS KERJA', 'Kecermatan & ketelitian', 4.00, 1 UNION ALL
  SELECT '2. KUALITAS KERJA', 'Mematuhi peraturan & prosedur', 4.00, 2 UNION ALL
  SELECT '2. KUALITAS KERJA', 'Memilih tindakan yang tepat', 4.00, 3 UNION ALL
  SELECT '3. PRODUKTIFITAS', 'Menyelesaikan tugas secara konsisten', 4.00, 1 UNION ALL
  SELECT '3. PRODUKTIFITAS', 'Menggunakan waktu secara efisien', 3.00, 2 UNION ALL
  SELECT '3. PRODUKTIFITAS', 'Memelihara tempat kerja secara teratur sesuai fungsinya', 3.00, 3 UNION ALL
  SELECT '4. ADAPTASI & FLEXIBILITAS', 'Menyesuaikan diri dengan segala perubahan dalam lingkungan kerja', 3.00, 1 UNION ALL
  SELECT '4. ADAPTASI & FLEXIBILITAS', 'Menunjukkan hasil kerja yang baik meskipun di bawah tekanan kerja', 3.00, 2 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Mempunyai inisiatif', 4.00, 1 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Menghasilkan tindakan dan pemecahan yang inovatif', 3.00, 2 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Mengantisipasi masalah yang mungkin terjadi', 3.00, 3 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Mencari tantangan baru dan kesempatan untuk pengembangan diri', 4.00, 4 UNION ALL
  SELECT '6. KERJASAMA', 'Memelihara hubungan kerja', 3.00, 1 UNION ALL
  SELECT '6. KERJASAMA', 'Dapat bekerja sama secara tim', 3.00, 2 UNION ALL
  SELECT '6. KERJASAMA', 'Memberikan bantuan kepada orang lain', 3.00, 3 UNION ALL
  SELECT '6. KERJASAMA', 'Mengakui kesalahan sendiri dan mau belajar dari kesalahan', 3.00, 4 UNION ALL
  SELECT '7. DISIPLIN', 'Hadir secara rutin dan tepat waktu', 4.00, 1 UNION ALL
  SELECT '7. DISIPLIN', 'Bekerja secara mandiri', 3.00, 2 UNION ALL
  SELECT '7. DISIPLIN', 'Menyelesaikan tugas dan memenuhi tanggungjawab sesuai waktu yang ditentukan', 4.00, 3 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Dapat berkomunikasi secara jelas lisan & tulis', 3.00, 1 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Memberikan informasi kepada orang lain', 3.00, 2 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Dapat berinteraksi dengan orang lain dan berbagai jenis pekerjaan', 3.00, 3 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Memberikan sikap yang baik dan professional', 3.00, 4 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Mau menerima masukan dari orang lain', 3.00, 5
) d
WHERE NOT EXISTS (SELECT 1 FROM pk_kriteria WHERE id_unit = 8);

-- ============ GIZI (unit 10) ============
INSERT INTO pk_kriteria (id_unit, kelompok, kriteria, bobot, urut)
SELECT 10, k, i, b, u FROM (
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN' k, 'Memahami tugas & tanggung jawab' i, 4.00 b, 1 u UNION ALL
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN', 'Memiliki pengetahuan dibidangnya', 3.00, 2 UNION ALL
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN', 'Menggunakan informasi dengan tepat dan benar', 3.00, 3 UNION ALL
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN', 'Mengikuti perkembangan pengetahuan', 3.00, 4 UNION ALL
  SELECT '2. KUALITAS KERJA', 'Kecermatan & ketelitian', 4.00, 1 UNION ALL
  SELECT '2. KUALITAS KERJA', 'Mematuhi peraturan & prosedur', 4.00, 2 UNION ALL
  SELECT '2. KUALITAS KERJA', 'Memilih tindakan yang tepat', 4.00, 3 UNION ALL
  SELECT '3. PRODUKTIFITAS', 'Menyelesaikan tugas secara konsisten', 4.00, 1 UNION ALL
  SELECT '3. PRODUKTIFITAS', 'Menggunakan waktu secara efisien', 3.00, 2 UNION ALL
  SELECT '3. PRODUKTIFITAS', 'Memelihara tempat kerja secara teratur sesuai fungsinya', 3.00, 3 UNION ALL
  SELECT '4. ADAPTASI & FLEXIBILITAS', 'Menyesuaikan diri dengan segala perubahan dalam lingkungan kerja', 3.00, 1 UNION ALL
  SELECT '4. ADAPTASI & FLEXIBILITAS', 'Menunjukkan hasil kerja yang baik meskipun di bawah tekanan kerja', 3.00, 2 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Mempunyai inisiatif', 4.00, 1 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Menghasilkan tindakan dan pemecahan yang inovatif', 3.00, 2 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Mengantisipasi masalah yang mungkin terjadi', 3.00, 3 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Mencari tantangan baru dan kesempatan untuk pengembangan diri', 4.00, 4 UNION ALL
  SELECT '6. KERJASAMA', 'Memelihara hubungan kerja', 3.00, 1 UNION ALL
  SELECT '6. KERJASAMA', 'Dapat bekerja sama secara tim', 3.00, 2 UNION ALL
  SELECT '6. KERJASAMA', 'Memberikan bantuan kepada orang lain', 3.00, 3 UNION ALL
  SELECT '6. KERJASAMA', 'Mengakui kesalahan sendiri dan mau belajar dari kesalahan', 3.00, 4 UNION ALL
  SELECT '7. DISIPLIN', 'Hadir secara rutin dan tepat waktu', 4.00, 1 UNION ALL
  SELECT '7. DISIPLIN', 'Bekerja secara mandiri', 3.00, 2 UNION ALL
  SELECT '7. DISIPLIN', 'Menyelesaikan tugas dan memenuhi tanggungjawab sesuai waktu yang ditentukan', 4.00, 3 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Dapat berkomunikasi secara jelas lisan & tulis', 3.00, 1 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Memberikan informasi kepada orang lain', 3.00, 2 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Dapat berinteraksi dengan orang lain dan berbagai jenis pekerjaan', 3.00, 3 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Memberikan sikap yang baik dan professional', 3.00, 4 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Mau menerima masukan dari orang lain', 3.00, 5
) d
WHERE NOT EXISTS (SELECT 1 FROM pk_kriteria WHERE id_unit = 10);

-- ============ KASUBAG UMUM / SARPRAS / LINEN (unit 20) ============
INSERT INTO pk_kriteria (id_unit, kelompok, kriteria, bobot, urut)
SELECT 20, k, i, b, u FROM (
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN' k, 'Memahami tugas & tanggung jawab' i, 4.00 b, 1 u UNION ALL
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN', 'Memiliki pengetahuan dibidangnya', 3.00, 2 UNION ALL
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN', 'Menggunakan informasi dengan tepat dan benar', 3.00, 3 UNION ALL
  SELECT '1. PENGETAHUAN TENTANG PEKERJAAN', 'Mengikuti perkembangan pengetahuan', 3.00, 4 UNION ALL
  SELECT '2. KUALITAS KERJA', 'Kecermatan & ketelitian', 4.00, 1 UNION ALL
  SELECT '2. KUALITAS KERJA', 'Mematuhi peraturan & prosedur', 4.00, 2 UNION ALL
  SELECT '2. KUALITAS KERJA', 'Memilih tindakan yang tepat', 4.00, 3 UNION ALL
  SELECT '3. PRODUKTIFITAS', 'Menyelesaikan tugas secara konsisten', 4.00, 1 UNION ALL
  SELECT '3. PRODUKTIFITAS', 'Menggunakan waktu secara efisien', 3.00, 2 UNION ALL
  SELECT '3. PRODUKTIFITAS', 'Memelihara tempat kerja secara teratur sesuai fungsinya', 3.00, 3 UNION ALL
  SELECT '4. ADAPTASI & FLEXIBILITAS', 'Menyesuaikan diri dengan segala perubahan dalam lingkungan kerja', 3.00, 1 UNION ALL
  SELECT '4. ADAPTASI & FLEXIBILITAS', 'Menunjukkan hasil kerja yang baik meskipun di bawah tekanan kerja', 3.00, 2 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Mempunyai inisiatif', 4.00, 1 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Menghasilkan tindakan dan pemecahan yang inovatif', 3.00, 2 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Mengantisipasi masalah yang mungkin terjadi', 3.00, 3 UNION ALL
  SELECT '5. INISIATIF & PEMECAHAN MASALAH', 'Mencari tantangan baru dan kesempatan untuk pengembangan diri', 4.00, 4 UNION ALL
  SELECT '6. KERJASAMA', 'Memelihara hubungan kerja', 3.00, 1 UNION ALL
  SELECT '6. KERJASAMA', 'Dapat bekerja sama secara tim', 3.00, 2 UNION ALL
  SELECT '6. KERJASAMA', 'Memberikan bantuan kepada orang lain', 3.00, 3 UNION ALL
  SELECT '6. KERJASAMA', 'Mengakui kesalahan sendiri dan mau belajar dari kesalahan', 3.00, 4 UNION ALL
  SELECT '7. DISIPLIN', 'Hadir secara rutin dan tepat waktu', 4.00, 1 UNION ALL
  SELECT '7. DISIPLIN', 'Bekerja secara mandiri', 3.00, 2 UNION ALL
  SELECT '7. DISIPLIN', 'Menyelesaikan tugas dan memenuhi tanggungjawab sesuai waktu yang ditentukan', 4.00, 3 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Dapat berkomunikasi secara jelas lisan & tulis', 3.00, 1 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Memberikan informasi kepada orang lain', 3.00, 2 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Dapat berinteraksi dengan orang lain dan berbagai jenis pekerjaan', 3.00, 3 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Memberikan sikap yang baik dan professional', 3.00, 4 UNION ALL
  SELECT '8. KEMAMPUAN BERINTERAKSI', 'Mau menerima masukan dari orang lain', 3.00, 5
) d
WHERE NOT EXISTS (SELECT 1 FROM pk_kriteria WHERE id_unit = 20);

-- =============================================================
-- SEED GRADING / PREDIKAT
-- =============================================================
-- Laboratorium, Radiologi, Perawat, Kebidanan, RM
INSERT INTO pk_grading (id_unit, label, nilai_min, nilai_max, urut)
SELECT u.id_unit, g.label, g.n_min, g.n_max, g.urut
FROM unit u
JOIN (
  SELECT 'Unsatisfactory' label, 0.00 n_min, 30.00 n_max, 1 urut UNION ALL
  SELECT 'Need Improvement', 31.00, 44.00, 2 UNION ALL
  SELECT 'Fair', 45.00, 59.00, 3 UNION ALL
  SELECT 'Good', 60.00, 75.00, 4 UNION ALL
  SELECT 'Very Good', 76.00, 89.00, 5 UNION ALL
  SELECT 'Outstanding', 90.00, 100.00, 6
) g
WHERE u.id_unit IN (4,5,6,7,9)
  AND NOT EXISTS (SELECT 1 FROM pk_grading pg WHERE pg.id_unit = u.id_unit);

-- Farmasi
INSERT INTO pk_grading (id_unit, label, nilai_min, nilai_max, urut)
SELECT 2, g.label, g.n_min, g.n_max, g.urut
FROM (
  SELECT 'Sangat Kurang' label, 0.00 n_min, 50.00 n_max, 1 urut UNION ALL
  SELECT 'Kurang', 51.00, 65.00, 2 UNION ALL
  SELECT 'Cukup', 66.00, 85.00, 3 UNION ALL
  SELECT 'Baik', 86.00, 95.00, 4 UNION ALL
  SELECT 'Sangat Baik/Istimewa', 96.00, 100.00, 5
) g
WHERE NOT EXISTS (SELECT 1 FROM pk_grading WHERE id_unit = 2);

-- Umum / CSSD / Gizi / Sarpras / Linen
INSERT INTO pk_grading (id_unit, label, nilai_min, nilai_max, urut)
SELECT u.id_unit, g.label, g.n_min, g.n_max, g.urut
FROM unit u
JOIN (
  SELECT 'Tidak Memuaskan' label, 0.00 n_min, 39.00 n_max, 1 urut UNION ALL
  SELECT 'Perlu Perbaikan', 40.00, 59.00, 2 UNION ALL
  SELECT 'Sesuai Harapan', 60.00, 79.00, 3 UNION ALL
  SELECT 'Melebihi Harapan', 80.00, 99.00, 4 UNION ALL
  SELECT 'Luar Biasa', 100.00, 100.00, 5
) g
WHERE u.id_unit IN (8,10,20)
  AND NOT EXISTS (SELECT 1 FROM pk_grading pg WHERE pg.id_unit = u.id_unit);

-- =============================================================
-- PERUBAHAN: id_dinilai berbasis tabel pegawai (bukan users)
-- - Tambahkan kolom pegawai.id_unit untuk menentukan unit kerja pegawai.
-- - Seed kriteria & grading untuk unit IT (id 11) (mengikuti pola unit 20).
-- =============================================================

SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pegawai' AND COLUMN_NAME = 'id_unit');
SET @sql = IF(@col = 0,
  'ALTER TABLE pegawai ADD COLUMN id_unit INT(11) NULL AFTER unit_kerja',
  'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- Sinkronkan id_unit pegawai dari unit_kerja (nama unit) untuk data lama
UPDATE pegawai p
JOIN unit u ON u.nm_unit = p.unit_kerja COLLATE utf8mb4_general_ci
SET p.id_unit = u.id_unit
WHERE p.id_unit IS NULL;

INSERT INTO pk_kriteria (id_unit, kelompok, kriteria, bobot, urut, is_active, created_at, updated_at)
SELECT 11, kelompok, kriteria, bobot, urut, is_active, NOW(), NOW()
FROM pk_kriteria WHERE id_unit = 20
  AND NOT EXISTS (SELECT 1 FROM pk_kriteria pk WHERE pk.id_unit = 11);

INSERT INTO pk_grading (id_unit, label, nilai_min, nilai_max, urut)
SELECT 11, label, nilai_min, nilai_max, urut FROM pk_grading WHERE id_unit = 20
  AND NOT EXISTS (SELECT 1 FROM pk_grading pg WHERE pg.id_unit = 11);

-- =============================================================
-- PERUBAHAN: penanda kepala unit langsung pada data pegawai
-- - Tambahkan kolom pegawai.is_kepala (tanpa perlu menu Akses User Unit).
-- - Unit kerja pegawai cukup di-set lewat pegawai.id_unit.
-- =============================================================

SET @col = (SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pegawai' AND COLUMN_NAME = 'is_kepala');
SET @sql = IF(@col = 0,
  'ALTER TABLE pegawai ADD COLUMN is_kepala TINYINT(1) NOT NULL DEFAULT 0 AFTER id_unit',
  'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- Pindahkan penanda kepala dari user_unit lama (migrasi 1x)
UPDATE pegawai p
JOIN users u ON u.email = p.email
JOIN user_unit uu ON uu.user_id = u.id AND uu.is_kepala = 1 AND uu.id_unit = p.id_unit
SET p.is_kepala = 1;

-- =============================================================
-- PENILAIAN KEPALA UNIT (Direktur / Kabid / Admin)
-- - Direktur menilai Kepala Unit memakai KRITERIA UNIT masing-masing
--   (mis. Kepala Lab dinilai dengan kriteria unit LABORATOR).
-- - Penilai otomatis: role admin (1) & direktur (4).
-- - Penilai tambahan (mis. Kabid Yanmed / Kepala Bidang) didaftarkan admin
--   pada tabel pk_penilai_kepala.
-- Tidak perlu tabel/kolom baru pada pk_penilaian: penilaian kepala unit
-- disimpan dengan id_penilai = user penilai, id_dinilai = pegawai kepala unit,
-- id_unit = unit kepala tsb sehingga kriteria otomatis mengikuti unitnya.
-- =============================================================

CREATE TABLE IF NOT EXISTS `pk_penilai_kepala` (
  `id_penilai_kepala` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_user` BIGINT(20) UNSIGNED NOT NULL,
  `catatan` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id_penilai_kepala`),
  UNIQUE KEY `uq_pkpk_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menu & hak akses untuk halaman kelola Penilai Kepala Unit (admin)
INSERT INTO menus (nama_menu, url, icon, sequence, is_active)
SELECT 'Penilai Kepala Unit', 'penilaian_kinerja/penilai', 'fas fa-user-shield', 17, 1
WHERE NOT EXISTS (SELECT 1 FROM menus WHERE url = 'penilaian_kinerja/penilai');

SET @mid_pk = (SELECT id FROM menus WHERE url = 'penilaian_kinerja/penilai' LIMIT 1);
INSERT INTO user_access_menu (role_id, menu_id)
SELECT 1, @mid_pk WHERE @mid_pk IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM user_access_menu WHERE role_id = 1 AND menu_id = @mid_pk);
