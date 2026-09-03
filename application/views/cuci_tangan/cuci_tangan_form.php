 <div class="card shadow mb-4">
     <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">
        <h2 style="margin-top:0px">Form Cuci tangan</h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Nama <?php echo form_error('nm') ?></label>
            <input type="text" class="form-control" name="nm" id="nm" placeholder="Nama" value="<?php echo $nm; ?>" />
        </div>
	    <div class="form-group">
        <div class="mb-3">
           <label for="exampleFormControlSelect1">Pilih profesi</label><select class="form-control form-control-solid" id="profesi" name="profesi">
            <option <?php if($profesi=="Dokter") { echo "selected"; } ?> value="Dokter">Dokter</option>
            <option <?php if($profesi=="Perawat") { echo "selected"; } ?> value="Perawat">Perawat</option>
            <option <?php if($profesi=="Bidan") { echo "selected"; } ?> value="Bidan">Bidan</option>
            <option <?php if($profesi=="Penunjang Medis") { echo "selected"; } ?> value="Penunjang Medis">Penunjang Medis</option>
        </select>
       </div>
        </div>
	    <div class="form-group">
            <div class="mb-3">
           <label for="exampleFormControlSelect1">Pilih unit</label><select class="form-control form-control-solid" id="unit" name="unit">
           <?php
            foreach ($unit2 as $unit2)
            { ?>
           <option <?php if($unit==$unit2->id_unit) { echo "selected"; } ?>  value="<?php echo $unit2->id_unit ?>"><?php echo $unit2->nm_unit ?></option>
          <?php } ?>
        </select>
       </div>
       
        </div>
	    <div class="form-group"> 
        <div class="mb-3">
           <label for="exampleFormControlSelect1">Pilih kesempatan</label><select class="form-control form-control-solid" id="kesempatan" name="kesempatan">
            <option <?php if($kesempatan==1) { echo "selected"; } ?>>1</option>
            <option <?php if($kesempatan==2) { echo "selected"; } ?>>2</option>
            <option <?php if($kesempatan==3) { echo "selected"; } ?>>3</option>
            <option <?php if($kesempatan==4) { echo "selected"; } ?>>4</option>
            <option <?php if($kesempatan==5) { echo "selected"; } ?>>5</option>
            <option <?php if($kesempatan==6) { echo "selected"; } ?>>6</option>
            <option <?php if($kesempatan==7) { echo "selected"; } ?>>7</option>
            <option <?php if($kesempatan==8) { echo "selected"; } ?>>8</option>
            <option <?php if($kesempatan==9) { echo "selected"; } ?>>9</option>
            <option <?php if($kesempatan==10) { echo "selected"; } ?>>10</option>
            <option <?php if($kesempatan==11) { echo "selected"; } ?>>11</option>
            <option <?php if($kesempatan==12) { echo "selected"; } ?>>12</option>
            <option <?php if($kesempatan==13) { echo "selected"; } ?>>13</option>
            <option <?php if($kesempatan==14) { echo "selected"; } ?>>14</option>
            <option <?php if($kesempatan==15) { echo "selected"; } ?>>15</option>
            <option <?php if($kesempatan==16) { echo "selected"; } ?>>16</option>
            <option <?php if($kesempatan==17) { echo "selected"; } ?>>17</option>
            <option <?php if($kesempatan==18) { echo "selected"; } ?>>18</option>
            <option <?php if($kesempatan==19) { echo "selected"; } ?>>19</option>
            <option <?php if($kesempatan==20) { echo "selected"; } ?>>20</option>
        </select>
        </div>
        </div>
	    <div class="form-group">
        <div class="mb-3">
            <label for="exampleFormControlSelect1">Cuci Tangan</label><select class="form-control form-control-solid" id="cucitangan" name="cucitangan">
            <option <?php if($cucitangan=="Ya") { echo "selected"; } ?>>Ya</option>
            <option  <?php if($cucitangan=="Tidak") { echo "selected"; } ?>>Tidak</option>
</select>
            </div>
            </div>
	    <div class="form-group">
            <div class="mb-3">
            <label for="exampleFormControlSelect1">Jenis Cuci</label><select class="form-control form-control-solid" id="ketcuci" name="ketcuci">
            <option <?php if($ketcuci=="-") { echo "selected"; } ?>>-</option>
            <option <?php if($ketcuci=="H-RUB") { echo "selected"; } ?>>H-RUB</option>
            <option <?php if($ketcuci=="H-WASH") { echo "selected"; } ?>>H-WASH</option>
            </select>   
        </div>
        </div>
	    <div class="form-group">
            <label for="date">Tanggal <?php echo form_error('tanggal') ?></label>
            <input type="date" class="form-control" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" />
        </div>
	    <div class="form-group">
            <label for="exampleFormControlSelect1">Pilih Moment</label><select class="form-control form-control-solid" id="moment" name="moment">
           <?php
            foreach ($moment2 as $moment2)
            { ?>
           <option <?php if($moment==$moment2->id_moment) { echo "selected"; } ?> value="<?php echo $moment2->id_moment ?>"><?php echo $moment2->nm_moment?></option>
          <?php } ?>
        </select>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('cuci_tangan') ?>" class="btn btn-default">Batal</a>
   </div> 
    </div> 
  
</div> 