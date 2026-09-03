<div class="card shadow mb-4">
     <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">
        <h2 style="margin-top:0px">Monitoring PJ Aplikasi <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="nm_pj">Nama PJ <?php echo form_error('nm_pj') ?></label>
            <input type="text" class="form-control" name="nm_pj" id="nm_pj" placeholder="Nama Penanggung Jawab" value="<?php echo $nm_pj; ?>" />
        </div>
	 <div class="form-group">
    <label for="nama_aplikasi">Nama Aplikasi <?php echo form_error('nama_aplikasi') ?></label>
    <select class="form-control" name="nama_aplikasi" id="nama_aplikasi">
        <option value="">-- Pilih Aplikasi --</option>
        <?php
        $aplikasi_list = array(
            'Prognas - Sistem Informasi Rantai Pasok Alat dan Obat Kontrasepsi',
            'PROGNAS - Sistem Informasi Keluarga (SIGA)',
            'PROGNAS - Sistem Informasi HIV AIDS (SIHA)',
            'PROGNAS - Kematian Maternal Dan Perinatal (MPDN)',
            'PROGNAS - Sistem Informasi Tuberkulosis (SITB)',
            'PROGNAS – Sistem Informasi Gizi (SIGIZI)',
            'PROGNAS – Resistensi Anti Mikroba (PPRA)',
            'PELAPORAN Mutu Internal - INM IKP',
            'Integrasi ERM – Satu Sehat',
            'Pelaporan Healthcare-Associated Infections (HAIs)',
        );
        foreach ($aplikasi_list as $ap) {
            $selected = ($nama_aplikasi == $ap) ? 'selected' : '';
            echo "<option value=\"" . htmlspecialchars($ap) . "\" $selected>" . htmlspecialchars($ap) . "</option>";
        }
        ?>
    </select>
</div>
        <div class="form-row">
	        <div class="form-group col-md-6">
	            <label for="bulan">Bulan <?php echo form_error('bulan') ?></label>
	            <select class="form-control" name="bulan" id="bulan">
	                <option value="">-- Pilih Bulan --</option>
	                <?php
	                $bulan_list = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
	                foreach ($bulan_list as $b) {
	                    $selected = ($bulan == $b) ? 'selected' : '';
	                    echo "<option value=\"$b\" $selected>$b</option>";
	                }
	                ?>
	            </select>
	        </div>
	        <div class="form-group col-md-6">
	            <label for="tahun">Tahun <?php echo form_error('tahun') ?></label>
	            <input type="number" class="form-control" name="tahun" id="tahun" placeholder="2026" value="<?php echo $tahun; ?>" />
	        </div>
        </div>
        <div class="form-group">
            <label for="progres">Progres (%) <?php echo form_error('progres') ?></label>
            <input type="number" min="0" max="100" class="form-control" name="progres" id="progres" placeholder="0-100" value="<?php echo $progres; ?>" />
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea class="form-control" name="keterangan" id="keterangan" rows="3" placeholder="Catatan progres bulan ini"><?php echo $keterangan; ?></textarea>
        </div>
	    <input type="hidden" name="id_monitoring" value="<?php echo $id_monitoring; ?>" />
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
	    <a href="<?php echo site_url('monitoring_pj') ?>" class="btn btn-default">Cancel</a>
	</form>
   </div>
    </div>
    </div>
    </div>
</div>