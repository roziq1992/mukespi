 <div class="card shadow mb-4">
     <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">
        <h2 style="margin-top:0px">Sertifikat <?php echo $button ?></h2>
        <!--<form action="<?php echo $action; ?>" method="post">-->
        <?php echo form_open_multipart('sertifikat/create_action');?>
	    <div class="form-group">
            <label for="varchar">Judul <?php echo form_error('judul') ?></label>
            <input type="text" class="form-control" name="judul" id="judul" placeholder="Judul" value="<?php echo $judul; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Ket <?php echo form_error('ket') ?></label>
            <input type="text" class="form-control" name="ket" id="ket" placeholder="Ket" value="<?php echo $ket; ?>" />
        </div>
	    <div class="form-group">
            <label for="date">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
        </div>
          <div class="form-group">
            <label for="file">Template Depan <?php echo form_error('file') ?></label>
            <input type="file" class="form-control" name="file1" id="file1" placeholder="file"/>
        </div>
        <div class="form-group">
            <label for="file">Template Belakang <?php echo form_error('file') ?></label>
            <input type="file" class="form-control" name="file2" id="file2" placeholder="file"/>
        </div>
	    <input type="hidden" name="id_sertifikat" value="<?php echo $id_sertifikat; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sertifikat') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
  </div> 
    </div> 
    </div> 
    </div> 
</div> 