CREATE TABLE IF NOT EXISTS `budaya_keselamatan` (
  `id_budaya_keselamatan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama_pelapor` varchar(255) NOT NULL,
  `tanggal_kejadian` date NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `nama_staf_pengunjung` varchar(1000) NOT NULL,
  `uraian_kejadian` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_budaya_keselamatan`),
  KEY `idx_budaya_keselamatan_tanggal` (`tanggal_kejadian`),
  KEY `idx_budaya_keselamatan_lokasi` (`lokasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
