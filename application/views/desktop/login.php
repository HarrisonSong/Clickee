<?php
	include "header.php";
?>
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/login.css">
        <script src="<?=ASSEST_URL?>desktop/js/login.js"></script>
        <script>
		
		function IsSmartphone(){
    		return (DetectUagent("android") || DetectUagent("ipod") || DetectUagent("ipod") || DetectUagent("symbian"));
		}

		function DetectUagent(name){
			return (navigator.userAgent.toLowerCase().search(name) > -1);
		}
		
		if (IsSmartphone()) {
			window.location.href = "<?=site_url("mobile/general/index")?>";
		}
		
        	$(document).ready(function(){
				$('.help-inline').hide();
				$('.alert').hide();
				
				$('#submitCreateAccount').click(function(){
					//console.log("excuted");
					var name = $('#inputName').val();
					var email = $('#inputEmail').val();
					var password = $('#inputPassword').val();
					var confirmPassword = $('#confirmPassword').val();
					var handphone = $('#inputContactNo').val();
					var proceed = true;
					if(name == ""){
						$('#inputNameGroup').addClass('error');
						$('#inputNameGroup .help-inline').show();
						proceed = false;
					}else{
						$('#inputNameGroup').removeClass('error');
						$('#inputNameGroup .help-inline').hide();
					}
					if(email == ""){
						$('#inputEmailGroup').addClass('error');
						$('#inputEmailGroup .help-inline').show();
						proceed = false;
					}else{
						$('#inputEmailGroup').removeClass('error');
						$('#inputEmailGroup .help-inline').hide();
					}
					if(password == ""||confirmPassword == ""||password != confirmPassword){
						$('#inputPasswordGroup').addClass('error');
						$('#inputPasswordGroup .help-inline').show();
						$('#inputConfirmPassGroup').addClass('error');
						$('#inputConfirmPassGroup .help-inline').show();
						proceed = false;
					}else{
						$('#inputConfirmPassGroup').removeClass('error');
						$('#inputConfirmPassGroup .help-inline').hide();
						$('#inputPasswordGroup').removeClass('error');
						$('#inputPasswordGroup .help-inline').hide();
					}
					if(handphone == ""){
						$('#inputContactGroup').addClass('error');
						$('#inputContactGroup .help-inline').show();
						proceed = false;
					}else{
						$('#inputContactGroup').removeClass('error');
						$('#inputContactGroup .help-inline').hide();
					}
					if(proceed){
						$.ajax({
							url:'<?=site_url("desktop/general/signup")?>',
							data:{signupUsername:name,signupEmail:email,signupPassword:password,phone_number:handphone},
							type:"POST",
							success:function(output){
								if(output == "success"){
									window.location.href = '<?=site_url('desktop/user/index')?>';
								}else{
									alert(output);
								}
							}
						});
					}
					return false;
				});
			
        		$('#login').click(function(){
        			var Email = $('#loginEmail').val();
        			var password = $('#loginPassword').val();
        			var proceed = true;
        			if(Email == ""){
        				$("#emailAlert").show();
        				proceed = false;
        			}else{
        				$('#emailAlert').hide();
        			}
					if(password == ""){
						$("#passwordAlert").show();
        				proceed = false;
					}else{
						$("#passwordAlert").hide();
					}
        			if(proceed){
	        			$.ajax({
	        				url:'<?php echo site_url("desktop/general/login");?>',
	        				type:"POST",
	        				data:{loginEmail:Email,loginPassword:password},
	        				success:function(output){
	        					if(output == "success"){
									$("#loginAlert").hide();
	        						window.location.href = '<?php echo site_url("desktop/user/index");?>'; 
	        					}
								else{
									$("#loginAlert").show();
								}
	        				}
	        			});
	        		}
	        		return false;
        		});
        	});
        </script>
        <title>Login</title>
	</head>
	
	<body>
		<div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="#"><img class="pull-right" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat></a>
					<!-- Login Starts Here -->
					<div id="loginContainer">
						<button class="btn btn-primary btn-small" id="loginButton"><span>Login</span></button>
						<div style="clear:both"></div>
						<div id="loginBox">                
							<form id="loginForm">
								<fieldset id="body">
									<fieldset>
										<label for="email">Email Address</label>
										<input type="text" name="email" id="loginEmail" />
										<span class = "help-block" id = "emailAlert" style = "color:#B94A48;font-size:18px;display:none;">incorrect input!</span>
									</fieldset>
									<fieldset>
										<label for="password">Password</label>
										<input type="password" name="password" id="loginPassword" />
										<span class = "help-block"  id = "passwordAlert" style = "color:#B94A48;font-size:18px;display:none;">incorrect input!</span>
									</fieldset>
									<fieldset>
										<label class="pull-left" for="checkbox"><input type="checkbox" id="checkbox" />Remember me<button class="btn btn-small pull-right" type="submit" id="login"/>Sign in</button></label>
									</fieldset>
									<fieldset style="padding-right:10px">
									<label style="text-align:center">Or sign in with</label>
										<a type="button" href = "<?php echo site_url("desktop/general/remote_login/gmail");?>" class="btn btn-small">Google</a>
										<a type="button" href = "<?php echo site_url("desktop/general/remote_login/yahoo");?>" class="btn btn-small">Yahoo</a>
										<a type="button" href = "<?php echo site_url("desktop/general/remote_login/fb");?>" class="btn btn-small">Facebook</a>
									<fieldset>
								</fieldset>
							</form>
						</div>
					</div>
					<!-- Login Ends Here -->
                </div><!--/.nav-collapse -->
            </div>
        </div>
		
		<div role="main" id="main">	
			<div class="container-fluid">
				<div id="title" class="row-fluid">
					<h1>Monitor, Control and Savings all in ONE click</h1>
					<h3>office and facilities management made easy</h3>
				</div>
				<div class="row-fluid">
					<div class = "span7">	
						<div class="row-fluid">
							<img class="pull-right" src="<?=ASSEST_URL?>desktop/img/ClickeeLogo.png" center center no-repeat>
						</div>
						<div class="row-fluid">
							</br></br>
							<button id = "btnIntro" class="btn btn-primary btn-red pull-right">Meet Clickee</button>
							<span class="pull-right"><h3>Experience ultimate convenience:</h3></span>
						</div>
						<!--
						<img src = "<?=ASSEST_URL?>desktop/img/login.png" style = "margin-left:15%;margin-top:-20px;width:450px;height:280px;"/>
						<h2 style = "margin-left:20%;font-family:Open Sans;font-weight:900;">Convience in your hand!</h2>
						-->
					</div>
					
					<div class = "span5">
						<div id="newAccount">
						<h3 class="pull-right">Create a New Account</h3>
						<form class="form-horizontal">
						  <div id="inputNameGroup" class="control-group">
							<label class="control-label pull-right" id="inputNameLabel" for="inputName">Name:</label>
							<div class="controls" >
							  <input type="text" id="inputName" class="pull-right">
							  <span class="help-inline pull-right">Please enter your name.</span>
							</div>
						  </div>
						  <div class="control-group" id="inputEmailGroup">
							<label class="control-label pull-right" for="inputEmail">Email:</label>
							<div class="controls">
							  <input type="text" id="inputEmail" class="pull-right">
							  <span class="help-inline pull-right">Please enter your email address.</span>
							</div>
						  </div>
						  <div class="control-group" id="inputPasswordGroup" >
							<label class="control-label pull-right" for="inputPassword">Password:</label>
							<div class="controls">
							  <input type="password" id="inputPassword" class="pull-right">
							  <span class="help-inline pull-right">Invalid Password.</span>
							</div>
						  </div>
						  <div id="inputConfirmPassGroup" class="control-group">
							<label class="control-label" for="confirmPassword" class="pull-right">Confirm Password:</label>
							<div class="controls">
							  <input type="password" id="confirmPassword" class="pull-right">
							  <span class="help-inline pull-right">Invalid  password.</span>
							</div>
						  </div>
						  <div id="inputContactGroup" class="control-group">
							<label class="control-label pull-right" for="inputContactNo">Contact No:</label>
							<div class="controls">
							  <input type="text" id="inputContactNo" class="pull-right">
							  <span class="help-inline pull-right">Please enter your handphone number.</span>
							</div>
						  </div>
							  <button id = "submitCreateAccount" class="btn btn-primary btn-small pull-right">Create</button>
						  </div>
						</form>
					</div>
						
<!--						
						<div class="group2">
							<p>If you do not have an account, sign up with us</p>
							<a type="button" href = "<?=site_url("desktop/general/createAccount")?>" class="btn btn-primary btn-block">Create New Account</a>
							</br>
							<p>Or sign in with your preferred account</p>
							<a type="button" href = "<?php echo site_url("desktop/general/remote_login/gmail");?>" class="btn btn-primary btn-block">Sign in using Google Mail</a>
							<a type="button" href = "<?php echo site_url("desktop/general/remote_login/yahoo");?>" class="btn btn-primary btn-block">Sign in using Yahoo Mail</a>
							<a type="button" href = "<?php echo site_url("desktop/general/remote_login/fb");?>" class="btn btn-primary btn-block">Sign in using Facebook</a>
						</div>
-->					 
					</div>
					
				</div>
			</div>
		</div>
	</body>
</html>
