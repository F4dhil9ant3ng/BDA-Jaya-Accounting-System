<!DOCTYPE html>
<html>
<head>
	<title><?php if(!empty($title)){echo $title.' |';}?> Sistem Akuntansi BD-Jaya</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.css')?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootflat.css')?>">	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/dina.css')?>">
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js')?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/icheck.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.fs.selecter.min.js')?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.fs.stepper.min.js')?>"></script>
	<?php
	if(!empty($script)){echo $script;}
	?>
</head>
<body style="font-size:12px">
	<nav style="border-radius:0" class="navbar navbar-default navbar-inverse" role="navigation">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Sistem Keuangan BDA-JAYA</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#">Home</a></li>
				<!-- <li><a href="#">Bantuan</a></li> -->
			</ul>
			<?php if(!empty($this->session->userdata('gudang_logged_in'))){ ?>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><strong>Gudang : </strong><?php echo $this->session->userdata('nama');?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<!-- <li><a href="#">Edit Profile</a></li> -->
						<li><a href="<?php echo site_url('login/logout')?>">Logout</a></li>
					</ul>
				</li>
			</ul>
			<?php } else if(!empty($this->session->userdata('kasir_logged_in'))){ ?>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><strong>Kasir : </strong><?php echo $this->session->userdata('nama');?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<!-- <li><a href="#">Edit Profile</a></li> -->
						<li><a href="<?php echo site_url('login/logout')?>">Logout</a></li>
					</ul>
				</li>
			</ul>
			<?php } else if(!empty($this->session->userdata('admin_logged_in'))){ ?>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><strong>Admin : </strong><?php echo $this->session->userdata('nama');?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<!-- <li><a href="#">Edit Profile</a></li> -->
						<li><a href="<?php echo site_url('admin/logout')?>">Logout</a></li>
					</ul>
				</li>
			</ul> 
			<?php } ?>		
		</div><!-- /.navbar-collapse -->
	</nav>