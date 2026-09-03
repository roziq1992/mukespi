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
        <h2 style="margin-top:0px">Data_inventaris <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Kode Inven <?php echo form_error('kode_inven') ?></label>
            <input type="text" class="form-control" name="kode_inven" id="kode_inven" placeholder="Kode Inven" value="<?php echo $kode_inven; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Nm Barang <?php echo form_error('nm_barang') ?></label>
            <input type="text" class="form-control" name="nm_barang" id="nm_barang" placeholder="Nm Barang" value="<?php echo $nm_barang; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Merek <?php echo form_error('merek') ?></label>
            <input type="text" class="form-control" name="merek" id="merek" placeholder="Merek" value="<?php echo $merek; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Tipe <?php echo form_error('tipe') ?></label>
            <input type="text" class="form-control" name="tipe" id="tipe" placeholder="Tipe" value="<?php echo $tipe; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Sn <?php echo form_error('sn') ?></label>
            <input type="text" class="form-control" name="sn" id="sn" placeholder="Sn" value="<?php echo $sn; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Jenis <?php echo form_error('jenis') ?></label>
            <input type="text" class="form-control" name="jenis" id="jenis" placeholder="Jenis" value="<?php echo $jenis; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Kondisi <?php echo form_error('kondisi') ?></label>
            <input type="text" class="form-control" name="kondisi" id="kondisi" placeholder="Kondisi" value="<?php echo $kondisi; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Id Ruang <?php echo form_error('id_ruang') ?></label>
            <input type="text" class="form-control" name="id_ruang" id="id_ruang" placeholder="Id Ruang" value="<?php echo $id_ruang; ?>" />
        </div>
	    <div class="form-group">
            <label for="decimal">Harga <?php echo form_error('harga') ?></label>
            <input type="text" class="form-control" name="harga" id="harga" placeholder="Harga" value="<?php echo $harga; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Stts <?php echo form_error('stts') ?></label>
            <input type="text" class="form-control" name="stts" id="stts" placeholder="Stts" value="<?php echo $stts; ?>" />
        </div>
	    <input type="hidden" name="id_inven" value="<?php echo $id_inven; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('data_inventaris') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>