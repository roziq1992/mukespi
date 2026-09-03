 <?php
 $indikator=$this->db->query('SELECT judul,
	target,ket_num,ket_denum,ket_judul
	
FROM
	list_indikator 
where id_indikator = "'.$this->input->get('id').'"')->row();
                                    



$target=$indikator->target;
$ketnum=$indikator->ket_num;
$ketdenum=$indikator->ket_denum;
$ketjudul=$indikator->ket_judul;
$judul=$indikator->judul;
 ?>
 <div class="card shadow mb-4">
     <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">

        <h2 style="margin-top:0px"> <?php echo $button ?> Laporan Analisa</h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="date">Tanggal Awal <?php echo form_error('tanggal1') ?></label>
            <input type="date" class="form-control" name="tanggal1" id="tanggal1" placeholder="Tanggal1" value="<?php echo $tanggal1; ?>" />
        </div>
	    <div class="form-group">
            <label for="date">Tanggal Akhir <?php echo form_error('tanggal2') ?></label>
            <input type="date" class="form-control" name="tanggal2" id="tanggal2" placeholder="Tanggal2" value="<?php echo $tanggal2; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Indikator <?php echo form_error('id_indikator') ?></label>
            <input type="hidden" class="form-control" name="id_indikator" id="id_indikator" placeholder="Id Indikator" value="<?php echo $this->input->get('id'); ?>" />
            <input type="text" class="form-control" name="judul" id="judul" placeholder="" value="<?php echo $judul; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Tahun Periode <?php echo form_error('tahun_periode') ?></label>
            <!--<input type="text" class="form-control" name="tahun_periode" id="tahun_periode" placeholder="Tahun Periode" value="<?php echo $tahun_periode; ?>" />-->
        </label><select class="form-control form-control-solid" id="tahun_periode" name="tahun_periode">
            <option >2022</option>
            <option >2023</option>
             <option >2024</option>
            <option >2025</option>
            <option selected>2026</option>
        </select>
        </div>
	    <div class="form-group">
            <label for="int">Periode Lapor <?php echo form_error('periode_lapor') ?></label>
            <!--<input type="text" class="form-control" name="periode_lapor" id="periode_lapor" placeholder="Periode Lapor" value="<?php echo $periode_lapor; ?>" />-->
       <select class="form-control form-control-solid" id="periode_lapor" name="periode_lapor">
            <option >1</option>
            <option >2</option>
            <option >3</option>
            <option >4</option>
            <option >5</option>
            <option >6</option>
            <option >7</option>
            <option >8</option>
            <option >9</option>
            <option >10</option>
            <option >11</option>
            <option >12</option>
        </select>
        </div>
	    <div class="form-group">
            <label for="int">Target <?php echo form_error('target') ?></label>
            <input type="text" class="form-control" name="target" id="target" placeholder="Target" value="<?php echo $target; ?>" />
        </div>
	    <div class="form-group">
            <label for="analisa">Analisa <?php echo form_error('analisa') ?></label>
            <textarea class="form-control" rows="3" name="analisa" id="analisa" placeholder="Analisa"><?php echo $analisa; ?></textarea>
        </div>
	    <input type="hidden" name="id_fmea" value="<?php echo $id_fmea; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('mutu_fmea?id='.$id_indikator.'&judul='.$judul) ?>" class="btn btn-default">Cancel</a>
	</form>
    </div> 
    </div> 
    </div> 
    </div> 
</div> 