<?php
if (empty($id_sertifikat))
{
    $id_sertifikat=$this->input->get('id_sertifikat');
}
function randomString($length)
{
    $str        = "";
    $characters = '123456789';
    $max        = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $rand = mt_rand(0, $max);
        $str .= $characters[$rand];
    }
    return $str;
}

if($no_peserta==""){
$no_peserta=randomString(10);
}
?>
 <div class="card shadow mb-4">
     <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">
        <h2 style="margin-top:0px">Sertifikat_peserta <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Id Sertifikat <?php echo form_error('id_sertifikat') ?></label>
            <input type="text" class="form-control" name="id_sertifikat" id="id_sertifikat" placeholder="Id Sertifikat" value="<?php echo $id_sertifikat; ?>"  readonly="true" />
        </div>
	    <div class="form-group">
            <label for="varchar">Nm Peserta <?php echo form_error('nm_peserta') ?></label>
            <input type="text" class="form-control" name="nm_peserta" id="nm_peserta" placeholder="Nm Peserta" value="<?php echo $nm_peserta; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">No Seri <?php echo form_error('no_peserta') ?></label>
            <input type="text" class="form-control" name="no_peserta" id="no_peserta" placeholder="No Peserta"  readonly="true" value="<?php echo $no_peserta; ?>" />
        </div>
	    <input type="hidden" name="id_peserta" value="<?php echo $id_peserta; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sertifikat_peserta?id_sertifikat='.$id_sertifikat) ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
  </div> 
    </div> 
    </div> 
    </div> 
</div> 