CREATE TABLE IF NOT EXISTS `laporan_pelanggaran` (
  `id_laporan_pelanggaran` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_pelapor` varchar(255) NOT NULL,
  `tanggal_kejadian` date NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `nama_staf_terlibat` varchar(1000) NOT NULL,
  `uraian_kejadian` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_laporan_pelanggaran`),
  KEY `idx_laporan_pelanggaran_tanggal` (`tanggal_kejadian`),
  KEY `idx_laporan_pelanggaran_lokasi` (`lokasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
