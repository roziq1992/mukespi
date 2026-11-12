ALTER TABLE `sertifikat`
    MODIFY `id_sertifikat` int(5) NOT NULL AUTO_INCREMENT,
    MODIFY `sertifikat_depan` varchar(255) NOT NULL DEFAULT '',
    MODIFY `sertifikat_belakang` varchar(255) NOT NULL DEFAULT '';
