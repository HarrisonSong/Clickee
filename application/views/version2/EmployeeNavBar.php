<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/EmployeeNavBar.css">

<div id="EmployeeNavBar" class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
		  <a class="brand" href="<?php echo site_url("desktop/employee/index");?>"><img class="pull-left" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat></a>
				<ul class="nav">
					<li><a href="<?php echo site_url("desktop/employee/index");?>">Dashboard</a></li>
					<li><a href="<?php echo site_url('desktop/employee/booking/'.$currentOffice);?>">Room Booking</a></li>
					<li><a href="<?php echo site_url('desktop/employee/employeeControlRoom/'.$currentOffice);?>">Manage Room</a></li>
				</ul>
		  <a class = "pull-right"id = "logout" tabindex="-1" href="<?=site_url("desktop/user/logout")?>">Logout</a>
		</div>
	</div>
</div>