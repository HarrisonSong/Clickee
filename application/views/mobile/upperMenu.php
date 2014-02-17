<div class="upperMenu">
	<div class="pagesMenu flexslider">
			<ul class="slides">
				<? if($this->session->userdata('logged_in') == TRUE) { ?>
				<li>
					<a href="<?=site_url("mobile/user/index")?>" id="linkAccount">
					  <img src="<?=ASSEST_URL?>mobile_template/img/account.png" alt="Account">
					 </a>
				</li>
				<li>
					<a href="<?=site_url("mobile/user/offices")?>" id="linkServices" class="pageLink pageLink-1-1" >
					  <div class="iconBox">
						<img src="http://rahulv.com/templates/bolt/bolt-app/img/home/icon-services.jpg" alt="Services">
					  </div>
					  <div class="titleBox">
						My Offices
					  </div>
					</a>
				</li>
				<li>
					<a href="<?=site_url("mobile/user/bookings")?>" id="linkServices" class="pageLink pageLink-1-1" >
					  <div class="iconBox">
						<img src="http://rahulv.com/templates/bolt/bolt-app/img/home/icon-services.jpg" alt="Services">
					  </div>
					  <div class="titleBox">
						My Bookings
					  </div>
					</a>
				</li>
				<li>
					<a href="<?=site_url("mobile/user/logout")?>" id="linkLogout" data-ajax="false">
					  <img src="<?=ASSEST_URL?>mobile_template/img/logout.png" alt="Logout">
					 </a>
				</li>
				<? } else { ?>
				<li>
					<a href="<?=site_url("mobile/general/index")?>" id="linkHome">
					  <img src="<?=ASSEST_URL?>mobile_template/img/home_page.png" alt="Login">
					 </a>
				</li>
				<? } ?>
				
				<li>            
					<a href="portfolio.php" id="linkPortfolio" class="pageLink pageLink-1-1 ">
					  <div class="iconBox">
						<img src="http://rahulv.com/templates/bolt/bolt-app/img/home/icon-portfolio.jpg" alt="Portfolio">
					  </div>
					  <div class="titleBox">
						Portfolio
					  </div>
					</a>
				</li>
				<li>
					<a href="blog.php" id="linkBlog" class="pageLink pageLink-1-1" >
					  <div class="iconBox">
						<img src="http://rahulv.com/templates/bolt/bolt-app/img/home/icon-blog2.jpg" alt="Blog Page">
					  </div>
					  <div class="titleBox">
						Blog
					  </div>
					</a>
				</li>
				<li>            
					<a href="contact.php" id="linkContact" class="pageLink pageLink-1-1 " >
					  <div class="iconBox">
						<img src="http://rahulv.com/templates/bolt/bolt-app/img/home/icon-contact.jpg" alt="Contact">
					  </div>
					  <div class="titleBox">
						Contact
					  </div>
					</a>
				</li>
				<li>            
					<a href="page1.php" id="linkExtra1" class="pageLink pageLink-1-1" >
					  <div class="iconBox">
						<img src="http://rahulv.com/templates/bolt/bolt-app/img/icon-yellow.png" alt="Sample Page 1">
					  </div>
					  <div class="titleBox">
						Elements
					  </div>
					</a>
				</li>
			</ul>
		</div>    
</div>