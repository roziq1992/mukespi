<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Jenis_dokumen <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Nm Jenis Dokumen <?php echo form_error('nm_jenis_dokumen') ?></label>
            <input type="text" class="form-control" name="nm_jenis_dokumen" id="nm_jenis_dokumen" placeholder="Nm Jenis Dokumen" value="<?php echo $nm_jenis_dokumen; ?>" />
        </div>
	    <div class="form-group">
            <label for="tinyint">Is Active <?php echo form_error('is_active') ?></label>
            <input type="text" class="form-control" name="is_active" id="is_active" placeholder="Is Active" value="<?php echo $is_active; ?>" />
        </div>
	    <div class="form-group">
            <label for="timestamp">Created At <?php echo form_error('created_at') ?></label>
            <input type="text" class="form-control" name="created_at" id="created_at" placeholder="Created At" value="<?php echo $created_at; ?>" />
        </div>
	    <div class="form-group">
            <label for="timestamp">Updated At <?php echo form_error('updated_at') ?></label>
            <input type="text" class="form-control" name="updated_at" id="updated_at" placeholder="Updated At" value="<?php echo $updated_at; ?>" />
        </div>
	    <input type="hidden" name="id_jenis_dokumen" value="<?php echo $id_jenis_dokumen; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('jenis_dokumen') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>