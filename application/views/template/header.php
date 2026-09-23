<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>APPS AIRLANGGA V2 - Created By Much Roziq.S.Kom</title>

	<!-- Custom fonts for this template-->
	<link href="<?=base_url('assets/');?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<!-- Custom styles for this template-->
	<link href="<?=base_url('assets/');?>css/sb-admin-2.min.css" rel="stylesheet">
<!-- jQuery first, then Bootstrap JS -->
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>-->
	
</head>

<body id="page-top" class="sidebar-toggled">

	<!-- Page Wrapper -->
	<div id="wrapper">

		<!-- Sidebar -->
		<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

			<!-- Sidebar - Brand -->
			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=base_url('dashboard');?>">
				<div class="sidebar-brand-icon rotate-n-15">
					<i class="fas fa-laugh-wink"></i>
				</div>
				<div class="sidebar-brand-text mx-1">RS Airlangga</div>
			</a>

			<!-- Divider -->
			<hr class="sidebar-divider my-0">

			<!-- Nav Item - Dashboard -->
			<!--<li class="nav-item">-->
			<!--	<a class="nav-link" href="<?=base_url();?>index.php/dashboard">-->
			<!--		<i class="fas fa-fw fa-tachometer-alt"></i>-->
			<!--		<span>Dashboard</span></a>-->
			<!--</li>-->

			<!-- Divider -->
			<hr class="sidebar-divider">

			<?php $this->load->view('template/menu'); ?>

			<!-- Divider -->
			<hr class="sidebar-divider d-none d-md-block">

			<!-- Sidebar Toggler (Sidebar) -->
			<div class="text-center d-none d-md-inline">
				<button class="rounded-circle border-0" id="sidebarToggle"></button>
			</div>

		</ul>
		<!-- End of Sidebar -->

		<!-- Content Wrapper -->
		<div id="content-wrapper" class="d-flex flex-column">

			<!-- Main Content -->
			<div id="content">

				<!-- Topbar -->
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

					<!-- Sidebar Toggle (Topbar) -->
					<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
						<i class="fa fa-bars"></i>
					</button>

					<!-- Topbar Navbar -->
					<ul class="navbar-nav ml-auto">

						<!-- Nav Item - Notifikasi -->
						<?php
						$_uid = (int) $this->session->userdata('id');
						$_notif_unread = 0;
						if ($_uid && isset($this->notifikasi)) {
							$_notif_unread = (int) $this->notifikasi->count_unread($_uid);
						}
						?>
						<li class="nav-item dropdown no-arrow mx-1">
							<a class="nav-link" href="<?php echo site_url('notifikasi'); ?>" title="Notifikasi">
								<i class="fas fa-bell fa-fw"></i>
								<?php if ($_notif_unread > 0): ?>
								<span class="badge badge-danger badge-counter"><?php echo $_notif_unread > 99 ? '99+' : $_notif_unread; ?></span>
								<?php endif; ?>
							</a>
						</li>

						<!-- Nav Item - User Manual -->
						<li class="nav-item no-arrow mx-1">
							<a class="nav-link" href="<?php echo base_url('docs/user-manual/index.html'); ?>" title="User Manual" target="_blank">
								<i class="fas fa-book fa-fw"></i>
							</a>
						</li>

						<div class="topbar-divider d-none d-sm-block"></div>

						<!-- Nav Item - User Information -->
						<?php
						$_avatar = $this->session->userdata('avatar');
						$_avatar_src = (!empty($_avatar) && file_exists(FCPATH . $_avatar)) ? base_url($_avatar) . '?v=' . md5($_avatar) : base_url('assets/img/undraw_profile.svg');
						?>
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo html_escape($this->session->userdata('name') ?: $this->session->userdata('email')); ?> </span>
								<img class="img-profile rounded-circle" src="<?php echo $_avatar_src; ?>">
							</a>
							<!-- Dropdown - User Information -->
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
								<a class="dropdown-item" href="<?php echo site_url('profile'); ?>">
									<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
									Profile
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
									<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
									Logout
								</a>
							</div>
						</li>

					</ul>

				</nav>
				<!-- End of Topbar -->

				<!-- Begin Page Content -->
				<div class="container-fluid">

					<!-- Page Heading -->
				
				

				
