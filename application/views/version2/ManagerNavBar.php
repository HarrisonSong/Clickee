<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/ManagerNavBar.css">

<div id="ManagerNavBar" class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
		  <a class="brand" href="<?php echo site_url("desktop/manager/index");?>"><img class="pull-left" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat></a>
				<ul class="nav">
					<li><a href="<?php echo site_url("desktop/manager/index");?>">Dashboard</a></li>
					<li><a href="<?php echo site_url("desktop/manager/configureOffice");?>">Configure Office</a></li>
					<li><a href="<?php echo site_url("desktop/manager/ManageEmployee");?>">Manage Office</a></li>
					<li><a href="<?php echo site_url("desktop/manager/managerControlRoom");?>">Control Page</a></li>
				</ul>
		  <a class = "pull-right"id = "logout" tabindex="-1" href="<?=site_url("desktop/user/logout")?>">Logout</a>
		</div>
	</div>
</div>