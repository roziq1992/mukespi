 <div class="card shadow mb-4">
     <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">

        <h2 style="margin-top:0px">List Indikator <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Kelompok <?php echo form_error('kelompok') ?></label>
            <input type="text" class="form-control" name="kelompok" id="kelompok" placeholder="Kelompok" value="<?php echo $kelompok; ?>"  />
        </div>
	    <div class="form-group">
            <label for="varchar">Jenis <?php echo form_error('jenis') ?></label>
            <input type="text" class="form-control" name="jenis" id="jenis" placeholder="Jenis" value="<?php echo $jenis; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Judul <?php echo form_error('judul') ?></label>
            <input type="text" class="form-control" name="judul" id="judul" placeholder="Judul" value="<?php echo $judul; ?>" />
        </div>
        <div class="form-group">
            <label for="varchar">Target <?php echo form_error('target') ?></label>
            <input type="text" class="form-control" name="target" id="target" placeholder="Target" value="<?php echo $target; ?>" />
        </div>
          <div class="form-group">
            <label for="varchar">Ket Numerator <?php echo form_error('target') ?></label>
            <input type="text" class="form-control" name="num" id="num" placeholder="denum" value="<?php echo $num; ?>" />
        </div>
         <div class="form-group">
            <label for="varchar">Ket Denumerator <?php echo form_error('target') ?></label>
            <input type="text" class="form-control" name="denum" id="denum" placeholder="denum" value="<?php echo $denum; ?>" />
        </div>
        <div class="form-group">
            <label for="varchar">Ket Indikator <?php echo form_error('ket_judul') ?></label>
            <input type="text" class="form-control" name="ketjudul" id="ketjudul" placeholder="ketjudul" value="<?php echo $ket_judul; ?>" />
        </div>
         <div class="form-group">
            <label for="varchar">User </label>
            <select class="form-control form-control-solid" id="user" name="user">
         <?php  foreach ($users as $users)
            { ?>
           <option  value="<?php echo $users->id ?>"><?php echo $users->name?></option>
          <?php } ?>
            </select>
        </div>
	    <input type="hidden" name="id_indikator" value="<?php echo $id_indikator; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('list_indikator') ?>" class="btn btn-default">Cancel</a>
	</form>
   </div> 
    </div> 
    </div> 
    </div> 
</div> 