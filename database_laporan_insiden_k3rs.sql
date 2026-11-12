CREATE TABLE IF NOT EXISTS `laporan_insiden_k3rs` (
  `id_laporan_insiden_k3rs` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `peralatan_kerja` varchar(255) NOT NULL DEFAULT '',
  `kerugian_aset` text NOT NULL,
  `kerugian_lingkungan` text NOT NULL,
  `kronologi` text NOT NULL,
  `penyebab_langsung` text NOT NULL,
  `penyebab_tidak_langsung` text NOT NULL,
  `kondisi_bahaya` tinyint(1) NOT NULL DEFAULT 0,
  `tindakan_bahaya` tinyint(1) NOT NULL DEFAULT 0,
  `pribadi` tinyint(1) NOT NULL DEFAULT 0,
  `pekerjaan_faktor` tinyint(1) NOT NULL DEFAULT 0,
  `kurang_prosedur` tinyint(1) NOT NULL DEFAULT 0,
  `kurang_sarana` tinyint(1) NOT NULL DEFAULT 0,
  `kurang_taat` tinyint(1) NOT NULL DEFAULT 0,
  `saksi` varchar(255) NOT NULL DEFAULT '',
  `ketua_tim` varchar(255) NOT NULL DEFAULT '',
  `tanggal_disusun` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id_laporan_insiden_k3rs`),
  KEY `idx_k3rs_tanggal` (`tanggal`),
  KEY `idx_k3rs_lokasi` (`lokasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `laporan_insiden_k3rs_korban` (
  `id_korban` int(11) NOT NULL AUTO_INCREMENT,
  `id_laporan_insiden_k3rs` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kelamin` varchar(1) NOT NULL DEFAULT '',
  `usia` varchar(30) NOT NULL DEFAULT '',
  `jabatan` varchar(255) NOT NULL DEFAULT '',
  `bagian` varchar(255) NOT NULL DEFAULT '',
  `cedera` text NOT NULL,
  `penanganan` text NOT NULL,
  `lt` int(11) DEFAULT NULL,
  `kategori` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_korban`),
  KEY `idx_k3rs_korban_laporan` (`id_laporan_insiden_k3rs`),
  CONSTRAINT `fk_k3rs_korban_laporan` FOREIGN KEY (`id_laporan_insiden_k3rs`)
    REFERENCES `laporan_insiden_k3rs` (`id_laporan_insiden_k3rs`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `laporan_insiden_k3rs_tindakan` (
  `id_tindakan` int(11) NOT NULL AUTO_INCREMENT,
  `id_laporan_insiden_k3rs` int(11) NOT NULL,
  `jenis` varchar(20) NOT NULL,
  `rencana` text NOT NULL,
  `target` date NOT NULL,
  `wewenang` varchar(255) NOT NULL,
  PRIMARY KEY (`id_tindakan`),
  KEY `idx_k3rs_tindakan_laporan` (`id_laporan_insiden_k3rs`),
  CONSTRAINT `fk_k3rs_tindakan_laporan` FOREIGN KEY (`id_laporan_insiden_k3rs`)
    REFERENCES `laporan_insiden_k3rs` (`id_laporan_insiden_k3rs`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
