<div class="container-fluid">

<style>
.profile-wrapper{max-width:860px;margin:0 auto}
.profile-hero{background:linear-gradient(135deg,#1e1b4b 0%,#312e81 55%,#4338ca 100%);border-radius:16px;padding:32px;color:#fff;display:flex;align-items:center;gap:24px;position:relative;overflow:hidden;box-shadow:0 12px 30px -10px rgba(49,46,129,.45);margin-bottom:24px;border:1px solid rgba(255,255,255,.06)}
.profile-hero::after{content:'';position:absolute;right:-50px;top:-70px;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(165,180,252,.18) 0%,transparent 70%);pointer-events:none}
.profile-avatar-wrap{position:relative;flex-shrink:0}
.profile-avatar-img{width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.3);box-shadow:0 4px 14px rgba(0,0,0,.25)}
.profile-avatar-placeholder{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#a5b4fc,#c7d2fe);color:#312e81;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;border:3px solid rgba(255,255,255,.3);box-shadow:0 4px 14px rgba(0,0,0,.25)}
.profile-avatar-edit-btn{position:absolute;bottom:0;right:0;width:28px;height:28px;border-radius:50%;background:#4f46e5;color:#fff;border:2px solid #fff;display:flex;align-items:center;justify-content:center;font-size:.65rem;cursor:pointer;transition:background .15s;box-shadow:0 2px 6px rgba(0,0,0,.2)}
.profile-avatar-edit-btn:hover{background:#4338ca}
.profile-info h4{margin:0 0 4px;font-size:1.35rem;font-weight:800;letter-spacing:-.02em}
.profile-info .profile-email{color:#a5b4fc;font-size:.85rem}
.profile-info .profile-role{margin-top:8px;display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:20px;font-size:.75rem;font-weight:700;color:#e0e7ff}
.profile-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 6px 20px -8px rgba(15,23,42,.08);overflow:hidden;margin-bottom:20px}
.profile-card-head{padding:16px 22px;border-bottom:1px solid #f1f5f9;font-weight:700;font-size:.95rem;color:#0f172a;display:flex;align-items:center;gap:10px}
.profile-card-head i{color:#4f46e5}
.profile-card-body{padding:24px}
.profile-form label{font-size:.82rem;font-weight:600;color:#334155}
.profile-form .form-control{border-color:#e2e8f0;border-radius:9px;padding:10px 14px;font-size:.88rem}
.profile-form .form-control:focus{border-color:#4f46e5;box-shadow:0 0 0 3px rgba(79,70,229,.12)}
.profile-form .text-danger{font-size:.76rem;font-weight:600}
.btn-profile-save{background:linear-gradient(135deg,#4f46e5,#4338ca);border:none;color:#fff!important;font-weight:700;border-radius:9px;padding:10px 24px;transition:all .15s;box-shadow:0 4px 12px -3px rgba(79,70,229,.4)}
.btn-profile-save:hover{background:linear-gradient(135deg,#4338ca,#3730a3);transform:translateY(-1px)}
.avatar-upload-area{border:2px dashed #cbd5e1;border-radius:12px;padding:20px;text-align:center;transition:all .2s;cursor:pointer;background:#f8fafc}
.avatar-upload-area:hover,.avatar-upload-area.dragover{border-color:#4f46e5;background:#eef2ff}
.avatar-upload-area input[type="file"]{display:none}
.avatar-upload-area .upload-icon{font-size:2rem;color:#a5b4fc;margin-bottom:8px}
.avatar-upload-area .upload-text{font-size:.82rem;color:#64748b;font-weight:500}
.avatar-upload-area .upload-hint{font-size:.72rem;color:#94a3b8;margin-top:4px}
.avatar-preview{width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0;margin:0 auto 12px;display:none}
.avatar-preview.show{display:block}
.profile-flash:not(:empty){margin-bottom:20px}
.profile-flash .alert{border-radius:10px;font-size:.85rem;font-weight:500;margin-bottom:0}
@media(max-width:576px){.profile-hero{flex-direction:column;text-align:center;padding:24px}.profile-card-body{padding:16px}}
</style>

<div class="profile-wrapper">

<?php $flash = $this->session->flashdata('message'); ?>
<?php if ($flash): ?>
<div class="profile-flash"><?= $flash ?></div>
<?php endif; ?>

<!-- HERO CARD -->
<div class="profile-hero">
    <div class="profile-avatar-wrap">
        <?php if (!empty($user['avatar']) && file_exists(FCPATH . $user['avatar'])): ?>
            <img class="profile-avatar-img" src="<?= base_url($user['avatar']) ?>?v=<?= time() ?>" alt="Avatar">
        <?php else: ?>
            <div class="profile-avatar-placeholder"><?= strtoupper(mb_substr($user['name'], 0, 1)) ?></div>
        <?php endif; ?>
        <label class="profile-avatar-edit-btn" for="avatarInput" title="Ganti avatar"><i class="fas fa-camera"></i></label>
    </div>
    <div class="profile-info" style="position:relative;z-index:1">
        <h4><?= html_escape($user['name']) ?></h4>
        <div class="profile-email"><?= html_escape($user['email']) ?></div>
        <div class="profile-role">
            <i class="fas fa-shield-halved"></i>
            <?= $user['role_id']==1 ? 'Administrator' : ($user['role_id']==4 ? 'Direktur' : ($user['role_id']==5 ? 'Sekretaris' : 'User')) ?>
        </div>
    </div>
</div>

<!-- EDIT PROFIL -->
<div class="profile-card">
    <div class="profile-card-head"><i class="fas fa-user-pen"></i> Edit Profil</div>
    <div class="profile-card-body">
        <form method="POST" action="<?= site_url('profile/update_action') ?>" class="profile-form">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name', $user['name']) ?>" required>
                    <?= form_error('name') ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email', $user['email']) ?>" required>
                    <?= form_error('email') ?>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-profile-save"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- UPLOAD AVATAR -->
<div class="profile-card">
    <div class="profile-card-head"><i class="fas fa-camera-retro"></i> Foto Profil</div>
    <div class="profile-card-body">
        <form method="POST" action="<?= site_url('profile/upload_avatar') ?>" enctype="multipart/form-data" id="avatarForm" class="profile-form">
            <div class="row align-items-center">
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <?php if (!empty($user['avatar']) && file_exists(FCPATH . $user['avatar'])): ?>
                        <img class="avatar-preview show" id="avatarPreview" src="<?= base_url($user['avatar']) ?>?v=<?= time() ?>" alt="Preview">
                    <?php else: ?>
                        <img class="avatar-preview" id="avatarPreview" src="" alt="Preview">
                        <div id="avatarPlaceholderLarge" style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#c7d2fe,#a5b4fc);color:#312e81;display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:800;margin:0 auto 12px;border:3px solid #e2e8f0"><?= strtoupper(mb_substr($user['name'], 0, 1)) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <div class="avatar-upload-area" id="avatarDropZone">
                        <div class="upload-icon"><i class="fas fa-cloud-arrow-up"></i></div>
                        <div class="upload-text">Klik atau seret foto ke sini</div>
                        <div class="upload-hint">Format: JPG, PNG, GIF, WebP. Maks 2MB.</div>
                        <input type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/gif,image/webp">
                    </div>
                    <div id="avatarFileName" class="mt-2" style="font-size:.78rem;color:#64748b"></div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-profile-save" id="btnUploadAvatar" disabled><i class="fas fa-upload mr-1"></i> Upload Avatar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- GANTI PASSWORD -->
<div class="profile-card">
    <div class="profile-card-head"><i class="fas fa-lock"></i> Ganti Password</div>
    <div class="profile-card-body">
        <form method="POST" action="<?= site_url('profile/change_password') ?>" class="profile-form">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Password Lama</label>
                    <input type="password" class="form-control" name="password_lama" placeholder="Masukkan password lama" required>
                    <?= form_error('password_lama') ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Password Baru</label>
                    <input type="password" class="form-control" name="password_baru" placeholder="Min 5 karakter" required>
                    <?= form_error('password_baru') ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" name="password_konfirmasi" placeholder="Ulangi password baru" required>
                    <?= form_error('password_konfirmasi') ?>
                </div>
            </div>
            <div class="mt-1">
                <button type="submit" class="btn btn-profile-save" onclick="return confirm('Yakin ingin mengubah password?')"><i class="fas fa-key mr-1"></i> Ubah Password</button>
            </div>
        </form>
    </div>
</div>

</div><!-- .profile-wrapper -->
</div><!-- .container-fluid -->

<script>
(function(){
    var dropZone=document.getElementById('avatarDropZone');
    var fileInput=document.getElementById('avatarInput');
    var preview=document.getElementById('avatarPreview');
    var fileName=document.getElementById('avatarFileName');
    var btnUpload=document.getElementById('btnUploadAvatar');
    var placeholder=document.getElementById('avatarPlaceholderLarge');

    if(!dropZone) return;

    dropZone.addEventListener('click',function(){ fileInput.click(); });

    dropZone.addEventListener('dragover',function(e){ e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave',function(){ dropZone.classList.remove('dragover'); });
    dropZone.addEventListener('drop',function(e){
        e.preventDefault();
        dropZone.classList.remove('dragover');
        if(e.dataTransfer.files.length){ fileInput.files=e.dataTransfer.files; showPreview(e.dataTransfer.files[0]); }
    });

    fileInput.addEventListener('change',function(){
        if(this.files && this.files[0]) showPreview(this.files[0]);
    });

    function showPreview(file){
        if(!file.type.match(/^image\/(jpeg|png|gif|webp)$/)){
            alert('Format file tidak didukung. Pilih JPG, PNG, GIF, atau WebP.');
            fileInput.value='';
            return;
        }
        if(file.size>2*1024*1024){
            alert('Ukuran file maksimal 2MB.');
            fileInput.value='';
            return;
        }
        var reader=new FileReader();
        reader.onload=function(e){
            preview.src=e.target.result;
            preview.classList.add('show');
            if(placeholder) placeholder.style.display='none';
        };
        reader.readAsDataURL(file);
        fileName.textContent=file.name+' ('+(file.size/1024).toFixed(1)+' KB)';
        btnUpload.disabled=false;
    }
})();
</script>
