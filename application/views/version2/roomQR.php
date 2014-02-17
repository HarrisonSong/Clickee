<?php
	include "header.php";
?>
	<title>OR Code</title>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

        <div class="navbar  navbar-fixed-top" style = "line-height:20px;">
            <div class="navbar-inner"  style = "min-height:20px;height:36px;"> 
                <div class="container" style="width:100%; padding-left:10px">
                  <a class="brand" href="#"><img class="pull-left" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat></a>
				  <? if($this->session->userdata('logged_in') == FALSE) {?>
				  <a class = "pull-right"id = "logout" tabindex="-1" href="<?=site_url()?>" style = "margin-top:8px; margin-right:20px; font-size:20px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif">Home Page</a>
				  <? } else { ?>
				   <a class = "pull-right"id = "logout" tabindex="-1" href="<?=site_url("desktop/user/index")?>" style = "margin-top:8px; margin-right:20px; font-size:20px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif">Dashboard</a>
				  <? } ?>
				</div>
            </div>
        </div>

        <div style="margin-top:40px">
			<div style=" float:right;">
			</div>
			
			<div style="padding:10px">
			<center>
			<img src="//chart.googleapis.com/chart?chs=200x200&cht=qr&chl=clickee.drekee.com%2Fqrcode.php%3Fid%3D<?=$room->id?>" width="200" height="200" alt=""/>
			 	<h2 style="text-transform:none"><?=$room->buil_name?> -  <?=$room->name?></h2>
				<h3 style="text-transform:none">
				<? 
					switch($type) { 
						case "login":
							echo "You need to login at <a href='".site_url()."'>home page</a> to access the control page";
							break;
						case "manager":
							echo "You do not have permission to control this room";
							break;
						case "employee":
							echo "The room is not in your office. If you want to join this office, you can send the request <a href='".site_url("desktop/employee/index/".$room->building_id)."'>here</a>";
							break;
					} 
				?>
				</h3>
				</center>
			</div>


		</div>
    </body>
</html>
