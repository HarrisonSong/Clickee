<?php include 'header.php' ?>
	<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/aboutProduct.css">

	<script>
		$('document').ready(function(){
			$('#infoManager').css('display','none');
			$('#infoEmployee').css('display','none');
		});
		
		$('#managerBtn').live('click', function(){
			$('#infoManager').css('display','block');
			$('#infoEmployee').css('display','none');
		});
		
		$('#employeeBtn').live('click',function(){
			$('#infoManager').hide();
			$('#infoEmployee').show();
		});
	</script>	
	
	<head>
    <title>About Clickee</title>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

		<div class="navbar  navbar-fixed-top" style = "line-height:20px;">
            <div class="navbar-inner"  style = "min-height:20px;height:36px;"> 
                <div class="container">
                  <a class="brand" href="<?php echo site_url("desktop/manager/index");?>"><img class="pull-left" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat> Dashboard</a>
				</div>
            </div>
        </div>

		<div id="myCarousel" class="carousel slide">
		  <!-- Carousel items -->
		  <div class="carousel-inner">
			<div class="active item">
				<table class = "table carousel-content">
					<tr>
						<td class = "carousel-img" style="text-align:center">
							<iframe width="560" height="343" src="http://www.youtube.com/embed/VeUZQ0OA1to" frameborder="0" allowfullscreen center center></iframe>
						</td>
						<td class = "carousel-wording">
							<div class="hero-unit">
							  <h1>Powerful</h1>
							  <p>Wi-Fi connected electrical wall switch that allows you to control your appliances anytime and anywhere.</p>
							</div>										
						</td>
					</tr>
				</table>
			</div>
			<div class="item">
				<table class = "table carousel-content">
					<tr>
						<td class = "carousel-img">
							<img src="<?=ASSEST_URL?>desktop/img/ClickeeLogo.png"></img>										
						</td>
						<td class = "carousel-wording">
							<div class="hero-unit">
							  <h1>Customizable</h1>
							  <p>With Clickee, you can customize how your appliances work to achieve maximum savings and convenience.</p>
							</div>													
						</td>
					</tr>
				</table>							
			</div>
			<div class="item">
				<table class = "table carousel-content">
					<tr>
						<td class = "carousel-img">
							<img src="<?=ASSEST_URL?>desktop/img/ClickeeLogo.png"></img>										
						</td>
						<td class = "carousel-wording">
							<div class="hero-unit">										
							  <h1>Affordable</h1>
							  <p>Clickee pays for itself. Install Clickee and expect to breakeven and starting saving within 3 months*!</p>
							</div>												
						</td>
					</tr>
				</table>							
			</div>
		  </div>
		  <!-- Carousel nav -->
		  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
		  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
		</div>
		
		<div class="container-fluid" id="main">
			<div class="row-fluid">
				<div class=span12">
					<ul class="nav nav-tabs" id="aboutClickeeTabs">
						<li class="active"><a href="#working"  data-toggle="tab">Working with Clickee</a></li>
						<li><a href="#convenience"  data-toggle="tab">Ultimate Convenience</a></li>
						<li><a href="#whyclickee" data-toggle="tab">Why Clickee</a></li>
						<li><a href="#capabilities" data-toggle="tab">Capabilities</a></li>
						<li><a href="#installation" data-toggle="tab">Installation</a></li>
					</ul>				
					
					<div class="tab-content">
						<div class="tab-pane active" id="working">
							<table class="table	infoTable">
							<tr>
								<td rowspan = "2" class="infoGraphic infoGraphicWordOnly" id="info0">
									<h1>Meet Clickee</h1>
								</td>
								<td colspan = "2" class="infoWording" id="infoWording1">
									<h3>the next generation electrical wall switch</h3>
									Stay connected to your office appliances and start saving on unnecessary electrical bills. Customize it well and clickee will lower your electrical bills up to 10%!
								</td>
							</tr>
							<tr>
								<td class="infoBlank">
								</td>
								<td class="infoTip" style="border-left: 5px dotted #EBEBEB; border-top: 5px dotted #EBEBEB; padding-top: 20px; padding-left: 40px;">
									<em>Clickee manages your electricity consumption unlike any other!</em>
								</td>							
							</tr>
							</table>
							<h3 style="text-align: center; padding-top: 40px">Get Clickee to work in just a few simple steps</h3>
							<table class="table	table-bordered infoTable infoTable-alt">
							<tr>
								<td colspan = "2" class="infoWording" id="infoWording1">
									<h2>Installation</h2>
									<p>If you are comfortable with installing a light fixture, you can install Clickee yourself. Three out of four people can install Clickee under 30 mintues.
									</br>
									You can also order our Clickee Installation Service and Clickee Certified Team will visit you soon!
									</p>
								</td>
								<td rowspan = "2" class="infoGraphic" id="info1">
									
								</td>								
							</tr>
							<tr>
								<td class="infoBlank">
								</td>
								<td class="infoTip" style="border-left: 5px dotted #EBEBEB; border-top: 5px dotted #EBEBEB; padding-top: 20px; padding-left: 40px;">
									<em>Install now and enjoy the convenience while saving money</em>
								</td>
							</tr>
							</table>
							
							<h3 style="text-align: center; padding-top: 40px">The ultimate solution for both manager and employee</h3>
							<h4 style="text-align:center"> Customize your Clickee to fit your role and experience the magic</h4>
							<h4 style="text-align:center">
								<button class="btn" id="managerBtn" style="background:#208080; color:white; text-shadow:none">Manager</button>
								<button class="btn" id="employeeBtn" style="background:#2E64CC; color:white; text-shadow:none">Employee</button>
							</h4>							
							
							<div id="infoManager">
								<table class="table infoTable infoTable-alt">
								<tr>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Set up Clickee</h2>
										<p>Use our website to create the layout of the rooms in your office and add Clickee into the respective rooms</p>
									</td>
									<td rowspan = "2" class="infoGraphic" id="info1">
										<img src="<?=ASSEST_URL?>desktop/img/infoGraphicSetup.png"></img>
									</td>								
								</tr>
								<tr>
									<td class="infoBlank">
									</td>
									<td class="infoTip" style="border-left: 5px dotted #EBEBEB; border-top: 5px dotted #EBEBEB; padding-top: 20px; padding-left: 40px;">
										<em>Clickee will automatically be functional once added to a room</em>
									</td>
								</tr>
								</table>

								 <table class="table infoTable">
								<tr>
									<td rowspan = "2" class="infoGraphic infoGraphicWordOnly" id="info1">
										<img src="<?=ASSEST_URL?>desktop/img/infoGraphicAutomate.png"></img>									
									</td>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Automate Management</h2>
										<p>Easily add in employees into your office and create rule to make electricity management automatic</p>
									</td>
								</tr>
								<tr>
									<td class="infoBlank">
									</td>
									<td class="infoTip" style="border-left: 5px dotted #EBEBEB; border-top: 5px dotted #EBEBEB; padding-top: 20px; padding-left: 40px;">
										<em>Electricity management at a few clicks</em>
									</td>							
								</tr>
								</table>
								
								<table class="table infoTable infoTable-alt">
								<tr>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Control Office</h2>
										<p>Separate room into two types: private and common to make  room control easy.<br>
											Apply rules into private room to make electricity saving automatic.
											Allow employees to book electricity use in private room at ease.
										</p>
									</td>
									<td rowspan = "2" class="infoGraphic" id="info1">
										<img src="<?=ASSEST_URL?>desktop/img/infoGraphicControl.png"></img>	
									</td>								
								</tr>
								<tr>
									<td class="infoBlank">
									</td>
									<td class="infoTip" style="border-left: 5px dotted #EBEBEB; border-top: 5px dotted #EBEBEB; padding-top: 20px; padding-left: 40px;">
										<em>Once a room setting is applied, no need for anymore troublesome manual controls</em>
									</td>
								</tr>
								</table>							
							</div>

							
							<div id="infoEmployee">
								<table class="table infoTable infoTable-alt">
								<tr>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Join an office</h2>
										<p>Join an office with Clickees installed to start enoying absolute convenience in using electricity.</p>
									</td>
									<td rowspan = "2" class="infoGraphic" id="info1">
										<img src="<?=ASSEST_URL?>desktop/img/infoGraphicJoin.png"></img>	
									</td>								
								</tr>
								<tr>
									<td class="infoBlank">
									</td>
									<td class="infoTip" style="border-left: 5px dotted #EBEBEB; border-top: 5px dotted #EBEBEB; padding-top: 20px; padding-left: 40px;">
										<em>No hustle for an employee</em>
									</td>
								</tr>
								</table>

								 <table class="table infoTable">
								<tr>
									<td rowspan = "2" class="infoGraphic infoGraphicWordOnly" id="info1">
										<img src="<?=ASSEST_URL?>desktop/img/infoGraphicBooking.png"></img>	
									</td>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Book a room</h2>
										<p>Use any smart device to easily book the use of electricity in a common room</p>
									</td>
								</tr>
								<tr>
									<td class="infoBlank">
									</td>
									<td class="infoTip" style="border-left: 5px dotted #EBEBEB; border-top: 5px dotted #EBEBEB; padding-top: 20px; padding-left: 40px;">
										<em>Use electricity in common rooms anywhere, anytime.</em>
									</td>							
								</tr>
								</table>
								
								<table class="table infoTable infoTable-alt">
								<tr>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Control Clickees</h2>
										<p>On and off devices from afar with Clickees.
										</p>
									</td>
									<td rowspan = "2" class="infoGraphic" id="info1">
										<img src="<?=ASSEST_URL?>desktop/img/infoGraphicControl.png"></img>	
									</td>								
								</tr>
								<tr>
									<td class="infoBlank">
									</td>
									<td class="infoTip" style="border-left: 5px dotted #EBEBEB; border-top: 5px dotted #EBEBEB; padding-top: 20px; padding-left: 40px;">
										<em>Convenience at your finger tip!</em>
									</td>
								</tr>
								</table>							
							</div>							
						</div>
						
					  <div class="tab-pane" id="convenience">
							<table class="table infoTable infoTable-alt">
								<tr>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Control your appliances from anywhere</h2>
										<p>Clickee can be controlled through any browser. No matter what Smartphone, Tablet or PC, controlling your Clickee will be a breeze!
										</p>
									</td>
									<td rowspan = "2" class="infoGraphic" id="info1">
										<img src="<?=ASSEST_URL?>desktop/img/infoGraphicAnywhere.png"></img>	
									</td>								
								</tr>
								<tr>
									<td class="infoBlank">
									</td>
									<td class="infoTip" style="border-left: 5px dotted #EBEBEB; border-top: 5px dotted #EBEBEB; padding-top: 20px; padding-left: 40px;">
										<em>Control your Clickee anytime, anywhere!</em>
									</td>
								</tr>
							</table>

							<table class="table infoTable">
								<tr>
									<td rowspan = "2" class="infoGraphic infoGraphicWordOnly" id="info0">
										<img src="<?=ASSEST_URL?>desktop/img/infoGraphic3in1.png"></img>	
									</td>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Monitor, control and save altogether</h2>
										<p>Clickee allows both managers and employees to monitor the electricity usage, control the appliances and save electricity (and money!) altogether.</p>
									</td>
								</tr>
								<tr>
									<td class="infoBlank">
									</td>
									<td class="infoTip" style="border-left: 5px dotted #EBEBEB; border-top: 5px dotted #EBEBEB; padding-top: 20px; padding-left: 40px;">
										<em>Save at zero effort!</em>
									</td>							
								</tr>
								</table>
					  </div>
					  <div class="tab-pane" id="whyclickee">
							<table class="table infoTable">
								<tr>
									<td id="info0">
										<h1>Problems</h1>
									</td>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<p>We didn't think an electrical wall switch would matter until we found out that appliances that are on stand-by or plugged in but not in use, accounts for up to 16% of average electricity bills.</p>
									</td>
								</tr>
							</table>
							<hr>
							<table class="table infoTable">
								<tr>
									<td rowspan = "2" class="infoGraphic infoGraphicWordOnly" id="info0">
										<img src="<?=ASSEST_URL?>desktop/img/infoGraphicAppliances.png"></img>										
									</td>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Increase in the number of electrical appliances</h2>
										<p>As technology advances, more electrical appliances are used in office and other facilities.
										These items include lights, projects, entertainment systems and large display screens that are left switched on for long hours</p>
									</td>
								</tr>
							</table>
							
							<table class="table infoTable infoTable-alt">
								<tr>
									<td colspan = "2" class="infoWording" id="infoWording1" style="padding-right: 5px; padding-bottom: 5px">
										<h2>Rising Electrical Bills</h2>
										<p>With the rise in fuel prices, electrical bills are rising as well. Just this year, electricity pricing has increased twice.
										<span class="pull-right" style="margin-top: 13%"><p>In Singapore: </p></span>
										</p>
									</td>
									<td rowspan = "2" class="infoGraphic" id="info1">
										
									</td>								
								</tr>
								<tr>
									<td class="infoBlank">
									</td>
									<td class="infoTip" style="border-left: 5px dotted #EBEBEB; border-top: 5px dotted #EBEBEB; padding-top: 20px; padding-left: 40px;">
									Jan 1 to Mar 31 > 2.3% <br> Apr 1 to Jun 30 > 4.3%.
									</td>
								</tr>
							</table>							
							
							<table class="table infoTable">
								<tr>
									<td class="infoGraphic infoGraphicWordOnly" id="info1">
									</td>
									<td class="infoWording" id="infoWording1">
										<h2>Negligence and insensitivity to consumption</h2>
										<p>At $0.272 per kWh, the cost of electricity seems almost negligible. Appliances are often left switched on
										unnecessarily and a huge number of users are still unaware that appliances on standby does consume electricity
										that may amount to 10% of electricity use.
										</p>
									</td>
								</tr>
							</table>
							
							<h1>Solution</h1>
							<hr>
							<table class="table infoTable">
								<tr>
									<td rowspan = "2" class="infoGraphic infoGraphicWordOnly" id="info1">
									</td>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Ease of Installation</h2>
										<p>Installation is similar to current electrical wall switches. Users can either order our Clickee Installation Service or follow
										our Installation guide to get Clickee up and running in 30mins!
										</p>
									</td>
								</tr>
							</table>
							
							<table class="table infoTable infoTable-alt">
								<tr>
									<td colspan = "2" class="infoWording" id="infoWording1" style="padding-right: 5px; padding-bottom: 5px">
										<h2>Smart Electrical Switch</h2>
										<p>Stay connected by connecting Clickee to the Cloud. Customize Clickee well and see how it can work
										for you through customizable electricity usage timings, notifications and custom rules to stop
										unnecessary spending on electricity.
										</p>
									</td>
									<td rowspan = "2" class="infoGraphic" id="info1">
										
									</td>								
								</tr>
							</table>							
							
							<table class="table infoTable">
								<tr>
									<td class="infoGraphic infoGraphicWordOnly" id="info1">
									</td>
									<td class="infoWording" id="infoWording1">
										<h2>Monitor and Control Appliances</h2>
										<p>Know the status and control your appliances through Clickee with just one click.
										Management tools and analytics are provided to cut down electricity usage and start saving!
										</p>
									</td>
								</tr>
							</table>
							
							<h1>Outcome</h1>
							<hr>
							<table class="table infoTable">
								<tr>
									<td rowspan = "2" class="infoGraphic infoGraphicWordOnly" id="info1">
									</td>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Save more than 10% on electricity bills monthly</h2>
										<p>That's about $65/ month based on average office electricity bills, not forgetting the convenience that Clickee
										provides when it comes to facility management. Forget about Smart Homes which cost hundreds to thousands typically.
										Clickee gives you what you need at only a small fraction of that price!
										</p>
									</td>
								</tr>
							</table>
							
							<table class="table infoTable infoTable-alt">
								<tr>
									<td colspan = "2" class="infoWording" id="infoWording1" style="padding-right: 5px; padding-bottom: 5px">
										<h2>Managing an office is no longer a great hassle.</h2>
										<p>Intuitively designed Dashboard gives you the power to Monitor, Control and Save, all in one click.
										</p>
									</td>
									<td rowspan = "2" class="infoGraphic" id="info1">
										
									</td>								
								</tr>
							</table>							
					  </div>
					  <div class="tab-pane" id="capabilities">
					  		<table class="table infoTable infoTable-alt">
								<tr>
									<td colspan = "2" class="infoWording" id="infoWording1" style="padding-right: 5px; padding-bottom: 5px">
										<h2>Hardware features</h2>
										<p>Connect up to 3 appliances<br>
											Ability to control your appliances both physically and through WiFi<br>
											Disable Wi-Fi to use Clickee as normal electrical wall switch<br>
										</p>
									</td>
									<td rowspan = "2" class="infoGraphic" id="info1">
										<img src="<?=ASSEST_URL?>desktop/img/hardwareFeatures.jpg"></img>										
									</td>								
								</tr>
							</table>
							
							<table class="table infoTable">
								<tr>
									<td rowspan = "2" class="infoGraphic infoGraphicWordOnly" id="info1">
									</td>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Software features</h2>
										<p>Management tools to monitor and control electricity usage<br>
											Notifications to highlight abnormalities<br>
											Features to facilitate smooth office operations.
										</p>
									</td>
								</tr>
							</table>							
					  </div>
					  <div class="tab-pane" id="installation">
							<table class="table infoTable">
								<tr>
									<td rowspan = "2" class="infoGraphic infoGraphicWordOnly" id="info1">
										<img src="<?=ASSEST_URL?>desktop/img/hardware1.jpg"></img>										
									</td>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Initialize Clickee</h2>
										<p>1)	Remove Clickee from its packging. <br>
											2)	Plug Clickee to Computer through the cable provide.<br>
											3)	Set the Wi-Fi network Name and Password (if any).<br>
											4)	Save the Settings.
										</p>
									</td>
								</tr>
							</table>					  
							
							<table class="table infoTable infoTable-alt">
								<tr>
									<td colspan = "2" class="infoWording" id="infoWording1" style="padding-right: 5px; padding-bottom: 5px">
										<h2>Mouting Clickee</h2>
										<p>5)	Turn off Circuit Breaker controlling the switch. <br>
											6)	If you have an existing Electrical Wall Switch, dismount it.<br>
													a.	Remove the outer casing.<br>
													b.	Unscrew the switch from the wall<br>
											7)	Connect the wires to the Clickee as shown.<br>
											8)	Mount Clickee.<br>
										</p>
									</td>
									<td rowspan = "2" class="infoGraphic" id="info1">
										<img src="<?=ASSEST_URL?>desktop/img/hardware.jpg"></img>										
									</td>								
								</tr>
							</table>						

							<table class="table infoTable">
								<tr>
									<td rowspan = "2" class="infoGraphic infoGraphicWordOnly" id="info1">
									</td>
									<td colspan = "2" class="infoWording" id="infoWording1">
										<h2>Initialize Clickee</h2>
										<p>10)	Assure that your Clickee is connected to the Internet. <br>
											11)	Create a Manager account to Clickee.dreekee.com<br>
											12)	Create a Office, room and Add your Clickee through Create or Configure Office.<br>
											13)	Customize your Clickee through Manage Office.<br>
											14) Control your Clickee through Manage Room.<br>
										</p>
									</td>
								</tr>
							</table>					  							
					  </div>
					</div>	
					</div>
			</div>
		</div>
    </body>
</html>
