<style>
    .portal { --portal-ink:#172b4d; --portal-muted:#718096; --portal-line:#e4eaf2; color:var(--portal-ink); }
    .portal-hero { background:linear-gradient(135deg,#102a43 0%,#1f4e79 62%,#2c7a9d 100%); color:#fff; border-radius:16px; padding:30px; margin-bottom:22px; position:relative; overflow:hidden; }
    .portal-hero:after { content:''; position:absolute; width:240px; height:240px; border:28px solid rgba(255,255,255,.08); border-radius:50%; right:-70px; top:-95px; }
    .portal-eyebrow { font-size:.68rem; text-transform:uppercase; letter-spacing:.13em; font-weight:800; opacity:.72; margin-bottom:8px; }
    .portal-hero h1 { font-size:1.65rem; font-weight:800; margin:0 0 8px; }
    .portal-hero p { font-size:.84rem; opacity:.86; margin:0; max-width:650px; }
    .portal-section-title { display:flex; align-items:end; justify-content:space-between; margin:0 0 12px; }
    .portal-section-title h2 { font-size:1.05rem; font-weight:800; margin:0; }
    .portal-section-title span { font-size:.72rem; color:var(--portal-muted); }
    .portal-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:14px; }
    .portal-card { min-height:190px; display:flex; flex-direction:column; text-decoration:none!important; background:#fff; border:1px solid var(--portal-line); border-radius:12px; padding:19px; box-shadow:0 5px 18px rgba(23,43,77,.05); transition:transform .16s ease, box-shadow .16s ease, border-color .16s ease; }
    .portal-card:hover { transform:translateY(-3px); border-color:#9dbbe8; box-shadow:0 10px 25px rgba(23,43,77,.1); }
    .portal-icon { width:43px; height:43px; border-radius:11px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.1rem; margin-bottom:18px; }
    .portal-card h3 { color:var(--portal-ink); font-size:.94rem; font-weight:800; margin:0 0 7px; }
    .portal-card p { color:var(--portal-muted); font-size:.75rem; line-height:1.5; margin:0; }
    .portal-open { margin-top:auto; padding-top:14px; color:#2563eb; font-size:.72rem; font-weight:800; }
    .portal-open i { margin-left:5px; transition:margin .16s ease; }
    .portal-card:hover .portal-open i { margin-left:9px; }
    .portal-office .portal-icon { background:linear-gradient(135deg,#e38b27,#be6418); }
    .portal-mukespi .portal-icon { background:linear-gradient(135deg,#0f9f72,#087f5b); }
    .portal-sidokta .portal-icon { background:linear-gradient(135deg,#3b82f6,#2456b8); }
    .portal-sipardi .portal-icon { background:linear-gradient(135deg,#8b5cf6,#6337b5); }
    .portal-simonika .portal-icon { background:linear-gradient(135deg,#0891b2,#0e7490); }
    @media(max-width:1100px){.portal-grid{grid-template-columns:repeat(3,1fr)}}
    @media(max-width:650px){.portal-hero{padding:23px 19px}.portal-hero h1{font-size:1.3rem}.portal-grid{grid-template-columns:1fr 1fr;gap:10px}.portal-card{min-height:175px;padding:15px}}
    @media(max-width:420px){.portal-grid{grid-template-columns:1fr}}
</style>

<div class="container-fluid portal">
    <div class="portal-hero"><div class="portal-eyebrow">RS Airlangga / Portal Terpadu</div><h1>Selamat datang di Portal Sistem RS Airlangga</h1><p>Satu halaman untuk mengakses seluruh layanan digital mutu, dokumen, penilaian, monitoring, dan manajemen surat.</p></div>
    <div class="portal-section-title"><h2>Pilih layanan sistem</h2><span>Akses sesuai kewenangan akun Anda</span></div>
    <div class="portal-grid">
        <a class="portal-card portal-office" href="<?php echo site_url('surat'); ?>"><span class="portal-icon"><i class="fas fa-envelope-open-text"></i></span><h3>E-OFFICE RSA</h3><p>Pengajuan, penomoran, disposisi, tanda tangan, dan tracking surat.</p><span class="portal-open">Buka sistem <i class="fas fa-arrow-right"></i></span></a>
        <a class="portal-card portal-mukespi" href="<?php echo site_url('list_indikator'); ?>"><span class="portal-icon"><i class="fas fa-heartbeat"></i></span><h3>MUKESPI</h3><p>Manajemen mutu, keselamatan pasien, insiden, dan PPI.</p><span class="portal-open">Buka sistem <i class="fas fa-arrow-right"></i></span></a>
        <a class="portal-card portal-sidokta" href="<?php echo site_url('dokumen_unit'); ?>"><span class="portal-icon"><i class="fas fa-folder-open"></i></span><h3>SIDOKTA</h3><p>Sistem informasi dokumen terpadu dan akses dokumen unit.</p><span class="portal-open">Buka sistem <i class="fas fa-arrow-right"></i></span></a>
        <a class="portal-card portal-sipardi" href="<?php echo site_url('penilaian_ep'); ?>"><span class="portal-icon"><i class="fas fa-award"></i></span><h3>SIPARDI</h3><p>Penilaian akreditasi dan pemantauan eviden rumah sakit.</p><span class="portal-open">Buka sistem <i class="fas fa-arrow-right"></i></span></a>
        <a class="portal-card portal-simonika" href="<?php echo site_url('monitoring_pj'); ?>"><span class="portal-icon"><i class="fas fa-chart-line"></i></span><h3>SIMONIKA</h3><p>Monitoring penanggung jawab dan tindak lanjut kegiatan.</p><span class="portal-open">Buka sistem <i class="fas fa-arrow-right"></i></span></a>
    </div>
</div>
