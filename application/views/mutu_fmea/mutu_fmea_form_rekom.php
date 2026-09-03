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

        <h2 style="margin-top:0px">Rekomendasi</h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="analisa">Rekomendasi <?php echo form_error('rekomendasi') ?></label>
            <input type="hidden" class="form-control" name="id_indikator" id="id_indikator" placeholder="Id Indikator" value="<?php echo $this->input->get('id'); ?>" />
            <input type="hidden" class="form-control" name="judul" id="judul" placeholder="" value="<?php echo $judul; ?>" />
            <textarea class="form-control" rows="3" name="rekomendasi" id="rekomendasi" placeholder=""><?php echo $rekomendasi; ?></textarea>
        </div>
	    <input type="hidden" name="id_fmea" value="<?php echo $id_fmea; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('mutu_fmea?id='.$this->input->get('id').'&judul='.$judul) ?>" class="btn btn-default">Cancel</a>
	</form>
    </div> 
    </div> 
    </div> 
    </div> 
</div> 