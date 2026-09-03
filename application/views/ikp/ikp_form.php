<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <div class="card shadow mb-4">
     <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">
        <h2 style="margin-top:0px">Entry  Insiden Keselamatan Pasien</h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Nama Pasien <?php echo form_error('nm_pasien') ?></label>
            <input type="text" class="form-control" name="nm_pasien" id="nm_pasien" placeholder="Nama Pasien" value="<?php echo $nm_pasien; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">No RM <?php echo form_error('rm') ?></label>
            <input type="text" class="form-control" name="rm" id="rm" placeholder="No RM" value="<?php echo $rm; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Ruangan <?php echo form_error('ruang') ?></label>
            <input type="text" class="form-control" name="ruang" id="ruang" placeholder="Ruangan" value="<?php echo $ruang; ?>" />
        </div>
        <div class="form-group">
            <label for="varchar">Umur <?php echo form_error('kelamin') ?></label>
              <select class="form-control form-control-solid" id="umur" name="umur">
            <option <?php if($kelamin=="0-1 bulan") { echo "selected"; } ?> value="0-1 bulan">0-1 bulan</option>
            <option <?php if($kelamin=="> 1 bulan – 1 tahun") { echo "selected"; } ?> value="> 1 bulan – 1 tahun">> 1 bulan – 1 tahun</option>
            <option <?php if($kelamin=="> 1 tahun – 5 tahun") { echo "selected"; } ?> value="> 1 tahun – 5 tahun">> 1 tahun – 5 tahun</option>
            <option <?php if($kelamin=="> 5 tahun – 15 tahun") { echo "selected"; } ?> value="> 5 tahun – 15 tahun">> 5 tahun – 15 tahun</option>
            <option <?php if($kelamin=="> 15 tahun – 30 tahun") { echo "selected"; } ?> value="> 15 tahun – 30 tahun">> 15 tahun – 30 tahun</option>
            <option <?php if($kelamin=="> 30 tahun – 65 tahun > 65 tahun") { echo "selected"; } ?> value="> 30 tahun – 65 tahun > 65 tahun">> 30 tahun – 65 tahun > 65 tahun</option>
        </select>
        </div>
	    <div class="form-group">
            <label for="varchar">Kelamin <?php echo form_error('kelamin') ?></label>
              <select class="form-control form-control-solid" id="kelamin" name="kelamin">
            <option <?php if($kelamin=="Laki-laki") { echo "selected"; } ?> value="Laki-laki">Laki-laki</option>
            <option <?php if($kelamin=="Perempuan") { echo "selected"; } ?> value="Perempuan">Perempuan</option>
        </select>
        </div>
	    <div class="form-group">
            <label for="varchar">Penangung Biaya Pasien <?php echo form_error('penangung_jawab') ?></label>
            <!--<input type="text" class="form-control" name="penangung_jawab" id="penangung_jawab" placeholder="Penangung Jawab" value="<?php echo $penangung_jawab; ?>" />-->
            <select class="form-control form-control-solid" id="penangung_jawab" name="penangung_jawab">
            <option <?php if($penangung_jawab=="Pribadi/Umum") { echo "selected"; } ?> value="Pribadi/Umum">Pribadi/Umum</option>
            <option <?php if($penangung_jawab=="ASKES Pemerintah") { echo "selected"; } ?> value="ASKES Pemerintah">ASKES Pemerintah</option>
            <option <?php if($penangung_jawab=="Asuransi Swasta") { echo "selected"; } ?> value="Asuransi Swasta">Asuransi Swasta</option>
            <option <?php if($penangung_jawab=="JAMKESMAS") { echo "selected"; } ?> value="JAMKESMAS">JAMKESMAS</option>
            <option <?php if($penangung_jawab=="Perusahaan") { echo "selected"; } ?> value="Perusahaan">Perusahaan</option>
        </select>
        </div>
	    <div class="form-group">
            <label for="date">Tanggal Masuk RS<?php echo form_error('tgl_masuk') ?></label>
            <input type="date" class="form-control" name="tgl_masuk" id="tgl_masuk" placeholder="Tanggal Masuk RS" value="<?php echo $tgl_masuk; ?>" />
        </div>
	    <div class="form-group">
            <label for="time">Jam Masuk RS<?php echo form_error('jam_masuk') ?></label>
            <input type="time" class="form-control" name="jam_masuk" id="jam_masuk" placeholder="Jam Masuk RS" value="<?php echo $jam_masuk; ?>" />
        </div>
        <hr>
	    <div class="form-group">
            <label for="date">Tanggal Kejadian Insiden <?php echo form_error('tgl_kejadian') ?></label>
            <input type="date" class="form-control" name="tgl_kejadian" id="tgl_kejadian" placeholder="Tanggal Kejadian Insiden" value="<?php echo $tgl_kejadian; ?>" />
        </div>
	    <div class="form-group">
            <label for="time">Jam Kejadian <?php echo form_error('jam_kejadian') ?></label>
            <input type="time" class="form-control" name="jam_kejadian" id="jam_kejadian" placeholder="Jam Kejadian" value="<?php echo $jam_kejadian; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Insiden <?php echo form_error('insiden') ?></label>
            <input type="text" class="form-control" name="insiden" id="insiden" placeholder="Insiden" value="<?php echo $insiden; ?>" />
        </div>
	    <div class="form-group">
            <label for="krologis">Krologis <?php echo form_error('krologis') ?></label>
            <textarea class="form-control" rows="3" name="krologis" id="krologis" placeholder="Krologis"><?php echo $krologis; ?></textarea>
        </div>
	    <div class="form-group" id="picker">
            <label for="varchar">Jenis Insiden <?php echo form_error('jns_insiden') ?></label>
            <select class="form-control form-control-solid" id="jns_insiden" name="jns_insiden">
            <option <?php if($jns_insiden=="Kejadian Nyaris Cedera / KNC (Near miss)") { echo "selected"; } ?> value="Kejadian Nyaris Cedera / KNC (Near miss)">Kejadian Nyaris Cedera / KNC (Near miss)</option>
            <option <?php if($jns_insiden=="Kejadian Tidak diharapkan / KTD (Adverse Event) / Kejadian Sentinel (Sentinel Event)") { echo "selected"; } ?> value="Kejadian Tidak diharapkan / KTD (Adverse Event) / Kejadian Sentinel (Sentinel Event)">Kejadian Tidak diharapkan / KTD (Adverse Event) / Kejadian Sentinel (Sentinel Event)</option>
             <option <?php if($jns_insiden=="Kejadian tidak cidera") { echo "selected"; } ?> value="Kejadian tidak cidera">Kejadian tidak cidera</option>
              <option <?php if($jns_insiden=="Kejadian potensian seknifikas cidera") { echo "selected"; } ?> value="Kejadian potensian seknifikas cidera">Kejadian potensian seknifikas cidera</option>
        </select>
        </div>
	    <div class="form-group id="tes">
            <label for="varchar">Pelapor Pertama <?php echo form_error('pelapor_pertama') ?></label>
            <select class="form-control form-control-solid" id="pelapor_pertama" name="pelapor_pertama">
            <option <?php if($pelapor_pertama=="Karyawan : Dokter / Perawat / Petugas lainnya") { echo "selected"; } ?> value="Karyawan : Dokter / Perawat / Petugas lainnya">Kejadian Nyaris Cedera / KNC (Near miss)</option>
            <option <?php if($pelapor_pertama=="Pasien") { echo "selected"; } ?> value="Pasien">Pasien</option>
            <option <?php if($pelapor_pertama=="Keluarga/Pendamping pasien") { echo "selected"; } ?> value="Keluarga/Pendamping pasien">Keluarga/Pendamping pasien</option>
            <option <?php if($pelapor_pertama=="Pengunjung") { echo "selected"; } ?> value="Pengunjung">Pengunjung</option>
            <option <?php if($pelapor_pertama=="Lain-lain") { echo "selected"; } ?> value="Lain-lain">Lain-lain</option>
        </select>
        </div>
	    <div class="form-group">
            <label for="varchar">Insiden Terjadi Pada  <?php echo form_error('insiden_terjadipd') ?></label>
            <!--<input type="text" class="form-control" name="insiden_terjadipd" id="insiden_terjadipd" placeholder="Insiden Terjadi pada" value="<?php echo $insiden_terjadipd; ?>" />-->
            <select class="form-control form-control-solid" id="insiden_terjadipd" name="insiden_terjadipd">
            <option <?php if($insiden_terjadipd=="Pasien") { echo "selected"; } ?> value="Pasien">Pasien</option>
            <option <?php if($pelapor_pertama=="Lain-lain") { echo "selected"; } ?> value="Lain-lain">Lain-lain</option>
        </select>
        </div>
	    <div class="form-group ">
            <label for="varchar">Insiden Meyangkut <?php echo form_error('insiden_meyangkut') ?></label>
            <!--<input type="text" class="form-control" name="insiden_meyangkut" id="insiden_meyangkut" placeholder="Insiden Meyangkut" value="<?php echo $insiden_meyangkut; ?>" />-->
            <select class="form-control form-control-solid" id="insiden_meyangkut" name="insiden_meyangkut">
            <option <?php if($insiden_terjadipd=="Pasien rawat inap") { echo "selected"; } ?> value="Pasien rawat inap">Pasien</option>
            <option <?php if($pelapor_pertama=="Pasien rawat jalan") { echo "selected"; } ?> value="Pasien rawat jalan">Lain-lain</option>
             <option <?php if($pelapor_pertama=="Pasien IGD") { echo "selected"; } ?> value="Pasien IGD">Pasien IGD</option>
              <option <?php if($pelapor_pertama=="Lain-lain") { echo "selected"; } ?> value="Lain-lain">Lain-lain</option>
        </select>
        </div>
	    <div class="form-group">
            <label for="varchar">Tempat Insiden * ( Tempat pasien berada )<?php echo form_error('tempat_insiden') ?></label>
            <input type="text" class="form-control" name="tempat_insiden" id="tempat_insiden" placeholder="Tempat Insiden" value="<?php echo $tempat_insiden; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Insiden Terjadi Pada Pasien : (sesuai kasus penyakit/spesialisasi) <?php echo form_error('insiden_terjadipd2') ?></label>
             <select class="form-control form-control-solid" id="insiden_terjadipd2" name="insiden_terjadipd2">
            <option <?php if($insiden_terjadipd2=="Penyakit Dalam dan Subspesialisasinya") { echo "selected"; } ?> value="Penyakit Dalam dan Subspesialisasinya">Penyakit Dalam dan Subspesialisasinya</option>
            <option <?php if($insiden_terjadipd2=="Anak dan Subspesialisasinya") { echo "selected"; } ?> value="Anak dan Subspesialisasinya">Anak dan Subspesialisasinya</option>
             <option <?php if($insiden_terjadipd2=="Bedah dan Subspesialisasinya") { echo "selected"; } ?> value="Bedah dan Subspesialisasinya">Bedah dan Subspesialisasinya</option>
              <option <?php if($insiden_terjadipd2=="Obstetri Gynekologi dan Subspesialisasinya") { echo "selected"; } ?> value="Obstetri Gynekologi dan Subspesialisasinya">bstetri Gynekologi dan Subspesialisasinya</option>
             <option <?php if($insiden_terjadipd2=="THT dan Subspesialisasinya") { echo "selected"; } ?> value="THT dan Subspesialisasinya">HT dan Subspesialisasinya</option>
             <option <?php if($insiden_terjadipd2=="Mata dan Subspesialisasinya") { echo "selected"; } ?> value="Mata dan Subspesialisasinya">Mata dan Subspesialisasinya</option>
             <option <?php if($insiden_terjadipd2=="Saraf dan Subspesialisasinya") { echo "selected"; } ?> value="Saraf dan Subspesialisasinya">Saraf dan Subspesialisasinya</option>
             <option <?php if($insiden_terjadipd2=="Anastesi dan Subspesialisasinya") { echo "selected"; } ?> value="Anastesi dan Subspesialisasinya">Anastesi dan Subspesialisasinya</option>
              <option <?php if($insiden_terjadipd2=="Jantung dan Subspesialisasinya") { echo "selected"; } ?> value="Jantung dan Subspesialisasinya">Jantung dan Subspesialisasinya</option>
              <option <?php if($insiden_terjadipd2=="Paru dan Subspesialisasinya") { echo "selected"; } ?> value="Paru dan Subspesialisasinya">Paru dan Subspesialisasinya</option>
            <option <?php if($insiden_terjadipd2=="Kulit & Kelamin dan Subspesialisasinya") { echo "selected"; } ?> value="Kulit & Kelamin dan Subspesialisasinya">Kulit & Kelamin dan Subspesialisasinya</option>
            <option <?php if($insiden_terjadipd2=="Jiwa dan Subspesialisasinya") { echo "selected"; } ?> value="Jiwa dan Subspesialisasinya">Jiwa dan Subspesialisasinya</option>
            <option <?php if($insiden_terjadipd2=="Lain-lain") { echo "selected"; } ?> value="Lain-lain">Lain-lain</option>
        </select>
        </div>
	    <div class="form-group" >
            <label for="varchar">Unit Penyebab <?php echo form_error('unit_penyebab') ?></label>
            <input type="text" class="form-control" name="unit_penyebab" id="unit_penyebab" placeholder="Unit Penyebab" value="<?php echo $unit_penyebab; ?>" />
        </div>
	    <div class="form-group" >
            <label for="varchar">Akibat Insiden <?php echo form_error('akibat_insiden') ?></label>
            <select class="form-control form-control-solid" id="akibat_insiden" name="akibat_insiden">
            <option <?php if($akibat_insiden=="Kematian") { echo "selected"; } ?> value="Kematian">Kematian</option>
            <option <?php if($akibat_insiden=="Cedera Irreversibel/Cedera Berat Cedera Reversibel/Cedera Sedang Cedera Ringan") { echo "selected"; } ?> value="Cedera Irreversibel/Cedera Berat Cedera Reversibel/Cedera Sedang Cedera Ringan">Cedera Irreversibel/Cedera Berat Cedera Reversibel/Cedera Sedang Cedera Ringan</option>
             <option <?php if($akibat_insiden=="Tidak ada cedera") { echo "selected"; } ?> value="Pasien IGD">Tidak ada cedera</option>
        </select>
        </div>
	    <div class="form-group">
            <label for="tindakan">Tindakan <?php echo form_error('tindakan') ?></label>
            <textarea class="form-control" rows="3" name="tindakan" id="tindakan" placeholder="Tindakan"><?php echo $tindakan; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="varchar">Tindakan di lakukan Oleh <?php echo form_error('tindakan_oleh') ?></label>
          
             <select class="form-control form-control-solid" id="tindakan_oleh" name="tindakan_oleh">
            <option <?php if($tindakan_oleh=="Dokter") { echo "selected"; } ?> value="Dokter">Dokter</option>
            <option <?php if($tindakan_oleh=="Perawat") { echo "selected"; } ?> value="Perawat">Perawat</option>
             <option <?php if($tindakan_oleh=="Petugas lainnya") { echo "selected"; } ?> value="Petugas lainnya">Petugas lainnya</option>
        </select>
        </div>
	    <div class="form-group">
            <label for="varchar">Apakah kejadian yang sama pernah terjadi di Unit Kerja lain?* Apabila ya, isi bagian dibawah ini.<?php echo form_error('kejadian_terulang') ?></label>
            <select class="form-control form-control-solid" id="kejadian_terulang" name="kejadian_terulang">
            <option <?php if($kejadian_terulang=="Ya") { echo "selected"; } ?> value="Ya">Ya</option>
            <option <?php if($kejadian_terulang=="Tidak") { echo "selected"; } ?> value="Tidak">Tidak</option>
        </select>
         <label for="varchar">Apabila ya, isi bagian dibawah ini.<?php echo form_error('kejadian_terulang') ?></label>
        <textarea class="form-control" rows="3" name="ket_kejadian_terulang" id="ket_kejadian_terulang" placeholder="Ket Kejadian Terulang"><?php echo $ket_kejadian_terulang; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="ket_kejadian_terulang">Ket Kejadian Terulang <?php echo form_error('ket_kejadian_terulang') ?></label>
            <textarea class="form-control" rows="3" name="ket_kejadian_terulang" id="ket_kejadian_terulang" placeholder="Ket Kejadian Terulang"><?php echo $ket_kejadian_terulang; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="varchar">Pelapor <?php echo form_error('pelapor') ?></label>
            <input type="text" class="form-control" name="pelapor" id="pelapor" placeholder="Pelapor" value="<?php echo $pelapor; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Penerima <?php echo form_error('penerima') ?></label>
            <input type="text" class="form-control" name="penerima" id="penerima" placeholder="Penerima" value="<?php echo $penerima; ?>" />
        </div>
	    <div class="form-group">
            <label for="date">Tanggal Lapor <?php echo form_error('tgl_lapor') ?></label>
            <input type="date" class="form-control" name="tgl_lapor" id="tgl_lapor" placeholder="Tgl Lapor" value="<?php echo $tgl_lapor; ?>" />
        </div>
          <div class="form-group">
            <label for="time">Jam Lapor<?php echo form_error('jam_lapor') ?></label>
            <input type="time" class="form-control" name="jam_lapor" id="jam_lapor" placeholder="Jam Lapor" value="<?php echo $jam_lapor; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Grading Resiko <?php echo form_error('grading_resiko') ?></label>
         
            <select class="form-control form-control-solid" id="grading_resiko" name="grading_resiko">
            <option <?php if($grading_resiko=="Biru") { echo "selected"; } ?> value="Biru" >Biru</option>
            <option <?php if($grading_resiko=="Hijau") { echo "selected"; } ?> value="Hjau">Hijau</option>
             <option <?php if($grading_resiko=="Kuning") { echo "selected"; } ?> value="Kuning">Kuning</option>
             <option <?php if($grading_resiko=="Merah") { echo "selected"; } ?> value="Merah">Merah</option>
        </select>
        </div>
	    <input type="hidden" name="id_ikp" value="<?php echo $id_ikp; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('ikp') ?>" class="btn btn-default">Cancel</a>
	</form>
      </div> 
    </div> 
    </div> 
    </div> 
</div> 

<script>
$("#picker").on("change", function() {
  $("#pelapor_pertama").hide();
});
</script>