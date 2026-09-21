<style>
    /* ================= SIDEBAR POLISH ================= */
    .nav-icon-badge {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        color: #fff !important;
        font-size: 0.78rem;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .accordionSidebar .nav-link {
        display: flex;
        align-items: center;
    }

    /* ---- Mode sidebar di-toggle / collapse ke ikon saja ---- */
    .sidebar.toggled .nav-item .nav-link {
        text-align: center;
        justify-content: center;
    }
    .sidebar.toggled .nav-item .nav-link .nav-icon-badge {
        margin: 0 auto;
    }
    .collapse-inner .collapse-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        border-radius: 6px;
        margin: 2px 6px;
        padding: 7px 10px !important;
        width: calc(100% - 12px);
        transition: background .12s ease, color .12s ease;
    }
    .collapse-inner .collapse-item:hover {
        background: #f1f3f9;
        color: #1b3a5c;
    }
    .collapse-inner .collapse-item i {
        width: 16px;
        text-align: center;
        font-size: 0.85rem;
        opacity: 0.85;
    }
    .collapse-header {
        font-size: 0.68rem !important;
        letter-spacing: 0.04em;
        font-weight: 800 !important;
    }
    .nav-badge-admin {
        font-size: 0.6rem;
        font-weight: 800;
        background: #fdecea;
        color: #c0392b;
        border-radius: 20px;
        padding: 1px 7px;
        margin-left: auto;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* palet warna per grup */
    .icon-mukespi     { background: linear-gradient(135deg, #0f9b8e, #0c7b70); }
    .icon-dokumen      { background: linear-gradient(135deg, #2c5f8a, #1b3a5c); }
    .icon-akreditasi   { background: linear-gradient(135deg, #6a3fa0, #3d2266); }
    .icon-sertifikat   { background: linear-gradient(135deg, #c9971c, #a67c00); }
    .icon-password     { background: linear-gradient(135deg, #546e7a, #37474f); }
    .icon-akses        { background: linear-gradient(135deg, #c0392b, #922b21); }
    .icon-monitoring   { background: linear-gradient(135deg, #16a085, #0e6655); }
    .icon-pegawai      { background: linear-gradient(135deg, #4f46e5, #312e81); }

    /* 1. Paksa Modal & Backdrop Bootstrap selalu berada di z-index paling atas */
    .modal-backdrop {
        z-index: 10000 !important;
    }
    .modal {
        z-index: 10001 !important;
    }

    /* 2. Normalkan stacking context pada wrapper & header agar tidak mengunci layer */
    .mutu-wrapper {
        position: static !important;
        overflow: visible !important;
    }
    .mutu-header {
        position: relative;
        z-index: 0 !important;
    }

    /* ================= TOOLTIP MENU SIDEBAR ================= */
    .sidebar .tooltip .tooltip-inner {
        background: #1b3a5c;
        color: #fff;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
        max-width: 240px;
        text-align: left;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }
    .sidebar .tooltip.bs-tooltip-right .arrow::before,
    .sidebar .tooltip.bs-tooltip-auto[x-placement^="right"] .arrow::before {
        border-right-color: #1b3a5c;
    }
</style>

<!-- ================= PORTAL SISTEM ================= -->
<?php if (is_menu_accessible('portal')) { ?>
<li class="nav-item">
    <a class="nav-link" href="<?=base_url();?>index.php/portal" title="Portal Sistem RS Airlangga" data-tooltip="true" data-placement="right">
        <i class="nav-icon-badge icon-monitoring fas fa-th-large"></i>
        <span>Portal Sistem</span>
    </a>
</li>
<?php } ?>

<?php $pgw_role = (int) $this->session->userdata('role_id'); ?>
<?php $pid_pgw = current_pegawai_id(); ?>
<?php if ($pgw_role === 1 || $pgw_role === 6 || $pgw_role === 7 || $this->session->userdata('is_pegawai')) {?>
<!-- ================= DATA PEGAWAI ================= -->
<?php if ($pgw_role === 1 || $pgw_role === 6) { ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePegawai"
       aria-expanded="false" aria-controls="collapsePegawai"
       data-tooltip="true" data-placement="right"
       title="SIMPEG — Manajemen Data Pegawai, Mutasi & Riwayat">
        <i class="nav-icon-badge icon-pegawai fas fa-users"></i>
        <span>PEGAWAI</span>
    </a>
    <div id="collapsePegawai" class="collapse" aria-labelledby="headingPegawai" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Entry Pegawai:</h6>
            <a class="collapse-item" href="<?=base_url();?>index.php/pegawai"><i class="fas fa-users"></i> Data Pegawai</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/pegawai/create"><i class="fas fa-user-plus"></i> Tambah Pegawai</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/pegawai?status=nonaktif"><i class="fas fa-user-slash"></i> Pegawai Nonaktif</a>
        </div>
    </div>
</li>
<?php } ?>

<?php if ($pid_pgw) { ?>
<!-- ================= DATA SAYA (Pegawai) ================= -->
<li class="nav-item">
    <a class="nav-link" href="<?=base_url();?>index.php/pegawai/detail/<?= (int) $pid_pgw; ?>"
       data-tooltip="true" data-placement="right"
       title="Data diri sebagai pegawai">
        <i class="nav-icon-badge icon-pegawai fas fa-id-badge"></i>
        <span>DATA SAYA</span>
    </a>
</li>
<?php } ?>
<?php } ?>

<?php if (is_menu_accessible('pelaporan')) { ?>
<!-- ================= PELAPORAN / PENGADUAN KARYAWAN ================= -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePelaporan"
       aria-expanded="false" aria-controls="collapsePelaporan"
       data-tooltip="true" data-placement="right"
       title="Pelaporan & pengaduan karyawan — penilaian sesama karyawan">
        <i class="nav-icon-badge icon-monitoring fas fa-star-half-alt"></i>
        <span>PELAPORAN</span>
    </a>
    <div id="collapsePelaporan" class="collapse" aria-labelledby="headingPelaporan" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pelaporan Karyawan:</h6>
            <a class="collapse-item" href="<?=base_url();?>index.php/pelaporan"><i class="fas fa-list"></i> Daftar Laporan</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/pelaporan/create"><i class="fas fa-pen"></i> Buat Laporan</a>
        </div>
    </div>
</li>
<?php } ?>

<?php if (is_menu_accessible('surat')) { ?>
<!-- ================= MANAJEMEN SURAT ================= -->
<?php $surat_role = (int) $this->session->userdata('role_id'); ?>
<?php $ci = get_instance(); $ci->load->model('Surat_model'); $surat_pending = $ci->Surat_model->pending_count($surat_role === 1 ? 'admin' : ($surat_role === 5 ? 'sekretaris' : ($surat_role === 4 ? 'direktur' : 'user')), $ci->session->userdata('id')); ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSurat" aria-expanded="false" title="Manajemen Surat Internal & Eksternal">
        <i class="nav-icon-badge icon-dokumen fas fa-envelope"></i><span>E-OFFICE</span><?php if ($surat_pending > 0): ?><span class="nav-badge-admin"><?php echo $surat_pending; ?></span><?php endif; ?>
    </a>
    <div id="collapseSurat" class="collapse" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <?php if ($surat_role === 1): ?>
                <a class="collapse-item" href="<?=base_url();?>index.php/surat"><i class="fas fa-tasks"></i>Kelola Semua Surat</a>
                <a class="collapse-item" href="<?=base_url();?>index.php/surat/create"><i class="fas fa-plus"></i>Buat Pengajuan Surat</a>
            <?php elseif ($surat_role === 5): ?>
                <a class="collapse-item" href="<?=base_url();?>index.php/surat_masuk"><i class="fas fa-inbox"></i>Surat Masuk Sekretaris <?php if ($surat_pending > 0): ?><span class="nav-badge-admin"><?php echo $surat_pending; ?></span><?php endif; ?></a>
                <a class="collapse-item" href="<?=base_url();?>index.php/surat/create"><i class="fas fa-plus"></i>Buat Pengajuan Surat</a>
            <?php elseif ($surat_role === 4): ?>
                <a class="collapse-item" href="<?=base_url();?>index.php/surat_direktur"><i class="fas fa-signature"></i>TTD & Disposisi <?php if ($surat_pending > 0): ?><span class="nav-badge-admin"><?php echo $surat_pending; ?></span><?php endif; ?></a>
            <?php else: ?>
                <a class="collapse-item" href="<?=base_url();?>index.php/surat"><i class="fas fa-envelope-open-text"></i>Surat Saya <?php if ($surat_pending > 0): ?><span class="nav-badge-admin"><?php echo $surat_pending; ?></span><?php endif; ?></a>
                <a class="collapse-item" href="<?=base_url();?>index.php/surat/create"><i class="fas fa-plus"></i>Pengajuan Baru</a>
            <?php endif; ?>
        </div>
    </div>
</li>
<?php } ?>

<!-- Heading -->
<!--<div class="sidebar-heading">-->
<!--	FORM ENTRY-->
<!--</div>-->

<!-- ================= MUKESPI ================= -->
<?php if (is_menu_accessible('list_indikator')) { ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
       aria-expanded="false" aria-controls="collapseTwo"
       data-tooltip="true" data-placement="right"
       title="MUKESPI — Mutu, Keselamatan Pasien, Inseden Keselamatan Pasien & PPI">
        <i class="nav-icon-badge icon-mukespi fas fa-heartbeat"></i>
        <span>MUKESPI</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" style="">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Entry MUKESPI:</h6>
            <a class="collapse-item" href="<?=base_url();?>index.php/list_indikator"><i class="fas fa-list-alt"></i>Indikator Mutu RS</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/dashboard/allmutugrafik"><i class="fas fa-chart-line"></i> Dashboard Mutu</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/list_indikator"><i class="fas fa-clipboard-list"></i> Mutu RS</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/Ikp"><i class="fas fa-exclamation-triangle"></i> IKP</a>
            <?php if($this->session->userdata('email')=='admin@mail.com') {?>
            <a class="collapse-item" href="<?=base_url();?>index.php/cuci_tangan"><i class="fas fa-pump-soap"></i> Cuci Tangan</a>

            <h6 class="collapse-header">Report Cuci Tangan:</h6>
            <a class="collapse-item" href="<?=base_url();?>index.php/cuci_tangan/lprtunit"><i class="fas fa-user-md"></i> By Profesi</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/cuci_tangan/lprmoment"><i class="fas fa-clock"></i> By Moment</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/cuci_tangan/lprtotal"><i class="fas fa-calendar-alt"></i> By Tanggal</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/dashboard"><i class="fas fa-chart-bar"></i> By Grafik</a>
            <?php } ?>
        </div>
    </div>
</li>
<?php } ?>

<!-- ================= DOKUMEN UNIT ================= -->
<?php if (is_menu_accessible('dokumen_unit')) { ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo4"
       aria-expanded="false" aria-controls="collapseTwo"
       data-tooltip="true" data-placement="right"
       title="SIDOKTA — Sistem Informasi Dokumen Terpadu Airlangga">
        <i class="nav-icon-badge icon-dokumen fas fa-folder-open"></i>
        <span>SIDOKTA</span>
    </a>
    <div id="collapseTwo4" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" style="">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?=base_url();?>index.php/dokumen_unit"><i class="fas fa-file-alt"></i> Dokument Unit</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/dokumen_unit/dashboard"><i class="fas fa-chart-pie"></i> Dashboard Laporan</a>
            <?php if($this->session->userdata('email')=='admin@mail.com') {?>
            <a class="collapse-item" href="<?=base_url();?>index.php/User_unit"><i class="fas fa-user-lock"></i> Akses Dokument Unit</a>
            <?php } ?>
        </div>
    </div>
</li>
<?php } ?>

<!-- ================= AKREDITASI ================= -->
<?php if (is_menu_accessible('penilaian_ep')) { ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo5"
       aria-expanded="false" aria-controls="collapseTwo"
       data-tooltip="true" data-placement="right"
       title="SIPARDI - Sistem Informasi Penilaian & Akreditasi">
        <i class="nav-icon-badge icon-akreditasi fas fa-award"></i>
        <span>SIPARDI</span>
    </a>
    <div id="collapseTwo5" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" style="">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?=base_url();?>index.php/penilaian_ep"><i class="fas fa-clipboard-check"></i> Penilaian Akreditasi</a>
            <?php if($this->session->userdata('email')=='admin@mail.com') {?>
            <a class="collapse-item" href="<?=base_url();?>index.php/User_unit"><i class="fas fa-users"></i> Pokja <span class="nav-badge-admin">Admin</span></a>
            <a class="collapse-item" href="<?=base_url();?>index.php/User_unit"><i class="fas fa-list-ol"></i> Standart <span class="nav-badge-admin">Admin</span></a>
            <a class="collapse-item" href="<?=base_url();?>index.php/User_unit"><i class="fas fa-tasks"></i> Element Penilaian <span class="nav-badge-admin">Admin</span></a>
            <?php } ?>
        </div>
    </div>
</li>
<?php } ?>

<?php if (is_menu_accessible('monitoring_pj')) { ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMonitoringPj"
       aria-expanded="false" aria-controls="collapseMonitoringPj"
       data-tooltip="true" data-placement="right"
       title="SIMONIKA - Sistem Informasi Monitoring Aplikasi Kemenkes Airlangga">
        <i class="nav-icon-badge icon-monitoring fas fa-tasks"></i>
        <span>SIMONIKA</span>
    </a>
    <div id="collapseMonitoringPj" class="collapse" aria-labelledby="headingMonitoringPj" data-parent="#accordionSidebar">

        <div class="bg-white py-2 collapse-inner rounded">

            <a class="collapse-item" href="<?=base_url();?>index.php/monitoring_pj"><i class="fas fa-list-alt"></i> Data Monitoring</a>
            <!--<a class="collapse-item" href="<?=base_url();?>index.php/monitoring_pj/excel"><i class="fas fa-file-excel-o"></i> Export Excel</a>-->

                <!--<a class="nav-link" href="<?=base_url();?>index.php/monitoring_pj/dashboard">-->
                <!--    <i class="nav-icon-badge icon-monitoring fas fa-chart-bar"></i>-->
                <!--    <span>Dashboard Progres PJ</span>-->
                <!--</a>-->
             <a class="collapse-item" href="<?=base_url();?>index.php/monitoring_pj/dashboard"><i class="fas fa-list-alt"></i> Dashboard Progres PJ</a>

        </div>

    </div>
</li>
<?php } ?>

<!-- ================= PENILAIAN KINERJA ================= -->
<?php if (is_menu_accessible('penilaian_kinerja')) { ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePenilaianKinerja"
       aria-expanded="false" aria-controls="collapsePenilaianKinerja"
       data-tooltip="true" data-placement="right"
       title="Penilaian Kinerja Pegawai per Unit">
        <i class="nav-icon-badge icon-monitoring fas fa-clipboard-check"></i>
        <span>PENILAIAN KINERJA</span>
    </a>
    <div id="collapsePenilaianKinerja" class="collapse" aria-labelledby="headingPenilaianKinerja" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?=base_url();?>index.php/penilaian_kinerja"><i class="fas fa-clipboard-check"></i> Penilaian Kinerja</a>
            <?php if ((int)$this->session->userdata('role_id') == 1) { ?>
            <a class="collapse-item" href="<?=base_url();?>index.php/penilaian_kinerja/kriteria"><i class="fas fa-sliders-h"></i> Kriteria &amp; Bobot</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/penilaian_kinerja/kepala_unit"><i class="fas fa-user-tie"></i> Kepala Unit</a>
            <?php } ?>
            <?php if ((int)$this->session->userdata('role_id') == 1 || (int)$this->session->userdata('role_id') == 6) { ?>
            <a class="collapse-item" href="<?=base_url();?>index.php/penilaian_kinerja/periode"><i class="fas fa-calendar-alt"></i> Periode</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/penilaian_kinerja/rekap"><i class="fas fa-chart-bar"></i> Rekap Penilaian</a>
            <?php } ?>
        </div>
    </div>
</li>
<?php } ?>

<!-- ================= PENILAIAN KINERJA OTK ================= -->
<?php if (is_menu_accessible('penilaian_otk')) { ?>
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePenilaianOtk"
       aria-expanded="false" aria-controls="collapsePenilaianOtk"
       data-tooltip="true" data-placement="right"
       title="Penilaian Kinerja OTK — penilai -> yang dinilai">
        <i class="nav-icon-badge icon-monitoring fas fa-user-check"></i>
        <span>PENILAIAN OTK</span>
    </a>
    <div id="collapsePenilaianOtk" class="collapse" aria-labelledby="headingPenilaianOtk" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?=base_url();?>index.php/penilaian_otk"><i class="fas fa-user-check"></i> Penilaian OTK</a>
            <?php if ((int)$this->session->userdata('role_id') == 1 || (int)$this->session->userdata('role_id') == 6) { ?>
            <a class="collapse-item" href="<?=base_url();?>index.php/penilaian_otk/kelola"><i class="fas fa-user-cog"></i> Kelola Penilai</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/penilaian_otk/rekap"><i class="fas fa-chart-bar"></i> Rekap OTK</a>
            <?php } ?>
        </div>
    </div>
</li>
<?php } ?>

<?php if($this->session->userdata('email')=='admin@mail.com') {?>
<!-- ================= SERTIFIKAT ONLINE ================= -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo3"
       aria-expanded="false" aria-controls="collapseTwo3"
       data-tooltip="true" data-placement="right"
       title="Sertifikat Online — Penerbitan & Pengelolaan Sertifikat Digital">
        <i class="nav-icon-badge icon-sertifikat fas fa-certificate"></i>
        <span>SERTIFIKAT ONLINE</span>
    </a>
    <div id="collapseTwo3" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" style="">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Entry Sertifikat:</h6>
            <a class="collapse-item" href="https://api-rsa.com/mukespi/index.php/sertifikat"><i class="fas fa-award"></i> Sertifikat</a>
        </div>
    </div>
</li>
<?php } ?>

<?php if($this->session->userdata('email')=='admin@mail.com') {?>
<!--  <li class="nav-item">-->
<!--    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo5" aria-expanded="false" aria-controls="collapseTwo3">-->
<!--        <i class="fas fa-fw fa-database"></i>-->
<!--        <span>INVENTARIS</span>-->
<!--    </a>-->
<!--    <div id="collapseTwo5" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" style="">-->
<!--        <div class="bg-white py-2 collapse-inner rounded">-->
<!--            <h6 class="collapse-header">Entry Inventaris:</h6>-->
<!--            <a class="collapse-item" href="https://api-rsa.com/mukespi/index.php/data_inventaris">Data Inventaris</a>-->
<!--        </div>-->
<!--    </div>-->
<!--</li>-->
<?php } ?>

<!--<li class="nav-item">-->
<!--    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo2" aria-expanded="false" aria-controls="collapseTwo2">-->
<!--        <i class="fas fa-fw fa-file"></i>-->
<!--        <span>FEE RUJUKAN</span>-->
<!--    </a>-->
<!--    <div id="collapseTwo2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar" style="">-->
<!--        <div class="bg-white py-2 collapse-inner rounded">-->
<!--            <h6 class="collapse-header">Entry Fee Rujukan:</h6>-->
<!--            <a class="collapse-item" href="https://api-rsa.com/mukespi/index.php/x">Data Perujuk</a>-->
<!--            <a class="collapse-item" href="https://api-rsa.com/mukespi/index.php/x">Verikasi Data</a>-->
<!--            <h6 class="collapse-header">Report Fee Rujukan:</h6>-->
<!--            <a class="collapse-item" href="https://api-rsa.com/mukespi/index.php/cuci_tangan/x">By Perujuk</a>-->
<!--            <a class="collapse-item" href="https://api-rsa.com/mukespi/index.php/cuci_tangan/x">By Tanggal</a>-->
<!--            <a class="collapse-item" href="https://api-rsa.com/mukespi/index.php/x">By Grafik</a>-->
<!--        </div>-->
<!--    </div>-->
<!--</li>-->
<!--         	<li class="nav-item">-->
<!--	<a class="nav-link" href="<?=base_url();?>index.php/list_indikator">-->
<!--		<i class="fa fa-h-square"></i>-->
<!--		<span>Mutu RS</span>-->
<!--	</a>-->
<!--</li>-->
<?php if($this->session->userdata('email')=='admin@mail.com') {?>
<!--<li class="nav-item">-->
<!--	<a class="nav-link" href="<?=base_url();?>index.php/cuci_tangan">-->
<!--		<i class="fa fa-h-square"></i>-->
<!--		<span>Cuci Tangan</span>-->
<!--	</a>-->
<!--</li>-->
<!--<div class="sidebar-heading">-->
<!--	Laporan cuci tangan-->
<!--</div>-->
<!--<li class="nav-item">-->
<!--	<a class="nav-link" href="<?=base_url();?>index.php/cuci_tangan">-->
<!--		<i class="fa fa-calendar"></i>-->
<!--		<span>Laporan By Profesi</span>-->
<!--	</a>-->
<!--</li>-->
<!--<li class="nav-item">-->
<!--	<a class="nav-link" href="<?=base_url();?>index.php/cuci_tangan/lprtunit">-->
<!--		<i class="fa fa-calendar"></i>-->
<!--		<span>Laporan Cuci Tangan</span>-->
<!--	</a>-->
<!--</li>-->
<!--<li class="nav-item">-->
<!--	<a class="nav-link" href="<?=base_url();?>index.php/cuci_tangan/lprtotal">-->
<!--		<i class="fa fa-calendar"></i>-->
<!--		<span>Laporan Per Tanggal</span>-->
<!--	</a>-->
<!--</li>-->
<!--<li class="nav-item">-->
<!--	<a class="nav-link" href="<?=base_url();?>index.php/dashboard">-->
<!--		<i class="fa fa-calendar"></i>-->
<!--		<span>Grafik Cuci Tangan</span>-->
<!--	</a>-->
<!--</li>-->
<?php } ?>



<?php if($this->session->userdata('role_id')==1) {?>
<!-- ================= AKSES UNIT USER ================= -->
<!-- <li class="nav-item">
    <a class="nav-link" href="<?=base_url();?>index.php/user_unit"
       data-tooltip="true" data-placement="right"
       title="Akses Unit User — Kelola Hak Akses User per Unit">
        <i class="nav-icon-badge icon-akses fas fa-user-shield"></i>
        <span>Akses Unit User</span>
    </a>
</li> -->

<!-- ================= MANAJEMEN USER ================= -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseManajemen"
       aria-expanded="false" aria-controls="collapseManajemen"
       data-tooltip="true" data-placement="right"
       title="Manajemen — Kelola User, Menu & Hak Akses Role">
        <i class="nav-icon-badge icon-akses fas fa-user-cog"></i>
        <span>MANAJEMEN USER</span>
    </a>
    <div id="collapseManajemen" class="collapse" aria-labelledby="headingManajemen" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Pengelolaan Sistem:</h6>
            <a class="collapse-item" href="<?=site_url('users')?>"><i class="fas fa-user-cog"></i> User Management</a>
            <a class="collapse-item" href="<?=base_url();?>index.php/user_unit"><i class="fas fa-bars"></i> Akses User Unit</a>
            <a class="collapse-item" href="<?=site_url('user_list_indikator')?>"><i class="fas fa-list-check"></i> Akses Indikator User</a>
            <a class="collapse-item" href="<?=site_url('menu')?>"><i class="fas fa-bars"></i> Menu Management</a>
            <a class="collapse-item" href="<?=site_url('menu/roles')?>"><i class="fas fa-user-tag"></i> Role Management</a>
            <a class="collapse-item" href="<?=site_url('menu/role_access/1')?>"><i class="fas fa-user-lock"></i> Role &amp; Hak Akses Menu</a>
        </div>
    </div>
</li>
<?php } ?>

<div class="sidebar-card d-none d-lg-flex">
    <img class="sidebar-card-illustration mb-2" src="<?=base_url('assets/');?>img/undraw_rocket.svg" alt="...">
    <p class="text-center mb-2"><strong>Dibuat Oleh</strong> Maz Roziq</p>
    <a class="btn btn-success btn-sm" href="https://www.facebook.com/musyafir.cinta.58?locale=id_ID" target="_blank">More Info</a>
</div>

<script>
window.addEventListener('load', function () {
    var $ = window.jQuery;
    if (!$ || !$.fn.tooltip) return;

    // Init tooltip untuk semua nav-link yang punya data-tooltip="true"
    // trigger 'hover focus' -> muncul saat mouse hover ATAU saat elemen fokus (mis. navigasi keyboard/Tab)
    $('[data-tooltip="true"]').tooltip({
        placement: 'right',
        trigger: 'hover focus',
        boundary: 'window',
        container: 'body'
    });

    // Sembunyikan tooltip begitu link diklik supaya tidak nyangkut menutupi submenu
    $('[data-tooltip="true"]').on('click', function () {
        $(this).tooltip('hide');
    });
});
</script>