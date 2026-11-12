 <div class="card shadow mb-4">
     <div class="card-body">
            <div class="table-responsive">
                <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="col-sm-12">
        <h2 style="margin-top:0px">Sertifikat <?php echo $button ?></h2>
        <?php if ($this->session->flashdata('message')): ?>
            <?php echo $this->session->flashdata('message'); ?>
        <?php endif; ?>
        <?php echo form_open_multipart($action); ?>
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
            <label for="template_depan">Template Depan</label>
            <select class="form-control" name="template_depan" id="template_depan">
                <?php foreach ($front_templates as $filename => $label): ?>
                    <?php $preview = strpos($filename, 'uploads/') === 0 ? base_url($filename) : base_url('sertifikat/' . $filename); ?>
                    <option value="<?php echo html_escape($filename); ?>" data-preview="<?php echo html_escape($preview); ?>" <?php echo set_select('template_depan', $filename, $filename === $selected_front); ?>>
                        <?php echo html_escape($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php $front_preview = strpos($selected_front, 'uploads/') === 0 ? base_url($selected_front) : base_url('sertifikat/' . $selected_front); ?>
            <img id="preview_template_depan" src="<?php echo html_escape($front_preview); ?>" alt="Preview template depan" style="display:block;max-width:100%;max-height:240px;margin-top:10px">
            <label for="file1" style="margin-top:10px">Atau upload template depan (JPG/PNG, maks. 8 MB)</label>
            <input type="file" class="form-control-file" name="file1" id="file1" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
            <small class="form-text text-muted">Jika diunggah, file ini akan menggantikan pilihan template di atas.</small>
            <small class="form-text text-muted">Gunakan gambar lanskap; nama, judul, tanggal, dan nomor seri akan ditambahkan otomatis pada posisi standar.</small>
        </div>
        <div class="form-group">
            <label for="template_belakang">Template Belakang</label>
            <select class="form-control" name="template_belakang" id="template_belakang">
                <?php foreach ($back_templates as $filename => $label): ?>
                    <?php $preview = strpos($filename, 'uploads/') === 0 ? base_url($filename) : base_url('sertifikat/' . $filename); ?>
                    <option value="<?php echo html_escape($filename); ?>" data-preview="<?php echo html_escape($preview); ?>" <?php echo set_select('template_belakang', $filename, $filename === $selected_back); ?>>
                        <?php echo html_escape($label); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php $back_preview = strpos($selected_back, 'uploads/') === 0 ? base_url($selected_back) : base_url('sertifikat/' . $selected_back); ?>
            <img id="preview_template_belakang" src="<?php echo html_escape($back_preview); ?>" alt="Preview template belakang" style="display:block;max-width:100%;max-height:240px;margin-top:10px">
            <label for="file2" style="margin-top:10px">Atau upload template belakang (JPG/PNG, maks. 8 MB)</label>
            <input type="file" class="form-control-file" name="file2" id="file2" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
            <small class="form-text text-muted">Jika diunggah, file ini akan menggantikan pilihan template di atas.</small>
            <small class="form-text text-muted">Gunakan gambar lanskap; nomor seri dan barcode akan ditambahkan otomatis.</small>
        </div>
	    <input type="hidden" name="id_sertifikat" value="<?php echo $id_sertifikat; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('sertifikat') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
  </div> 
  <script>
      (function () {
          document.getElementById('template_depan').addEventListener('change', function () {
              document.getElementById('preview_template_depan').src = this.options[this.selectedIndex].getAttribute('data-preview');
          });
          document.getElementById('template_belakang').addEventListener('change', function () {
              document.getElementById('preview_template_belakang').src = this.options[this.selectedIndex].getAttribute('data-preview');
          });
      }());
  </script>
    </div> 
    </div> 
    </div> 
</div> 