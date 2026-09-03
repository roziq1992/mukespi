 <?php
                     $indikator=$this->db->query('SELECT 
	target,ket_num,ket_denum,ket_judul
	
FROM
	list_indikator 
where id_indikator = "'.$this->input->get('id').'"')->row();
                                    



$target=$indikator->target;
$ketnum=$indikator->ket_num;
$ketdenum=$indikator->ket_denum;
$ketjudul=$indikator->ket_judul;
// $tanggal=$this->input->get('tanggal');
?>
 <div class="card shadow mb-4">
     <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">
        <h2 style="margin-top:0px">Mutu Indikator <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="date">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
        </div>
	    
            <input type="hidden" class="form-control" name="id_indikator" id="id_indikator" placeholder="Id Indikator" value="<?php echo $id_indikator; ?>" />
             <input type="hidden" class="form-control" name="id" value="<?php echo $this->input->get('id'); ?>">
            <input type="hidden" class="form-control" name="judul" id="judul" placeholder="" value="<?php echo $this->input->get('judul'); ?>" />
            <input type="hidden" class="form-control" name="target" id="target" placeholder="Demu" value="<?php echo $target; ?>" required/>
	    <div class="form-group">
             <label for="double">Numerator <?php echo form_error('num') ?> = <?php echo $ketnum; ?></label>
            <input type="text" class="form-control" name="num" id="num" placeholder="Num" value="<?php echo $num; ?>" />
        </div>
	    <div class="form-group">
            <label for="double">Denumerator <?php echo form_error('demu') ?>  = <?php echo $ketdenum; ?></label>
            <input type="text" class="form-control" name="demu" id="demu" placeholder="Demu" value="<?php echo $demu; ?>" />
        </div>
	    <input type="hidden" name="id_mutu" value="<?php echo $id_mutu; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('Mutu_indikator?id='.$this->input->get('id',TRUE).'&judul='.$this->input->get('judul',TRUE).'&tanggal='.$this->input->get('tanggal',TRUE)) ?>" class="btn btn-default">Cancel</a>
	</form>
    </div> 
    </div> 
    </div> 
    </div> 
</div> 