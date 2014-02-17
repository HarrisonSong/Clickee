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
				
				$('#signInButton').click(function(){
					$('#signInName').focus();
				});
				$('#submitCreateAccount').click(function(){
					//console.log("excuted");
					var name = $('#signInName').val();
					var email = $('#signInEmail').val();
					var password = $('#signInPassword').val();
					var confirmPassword = $('#confirmSignInPassword').val();
					var handphone = $('#signInContactNo').val();
					var proceed = true;
					if(name == ""){
						$('#signInNameLabel').html('<strong>Name </strong><font color ="#B94A48">*required</font>');
						$('#signInName').css('border-color','#B94A48');
						proceed = false;
					}else{
						$('#signInNameLabel').html('<strong>Name<strong>');
						$('#signInName').css('border-color','#CCC');
					}
					if(email == ""){
						$('#signInEmailLabel').html('<strong>Email </strong><font color ="#B94A48">*required</font>');
						$('#signInEmail').css('border-color','#B94A48');
						proceed = false;
					}else if(!isValidEmailAddress(email)){
						$('#signInEmailLabel').html('<strong>Email </strong><font color ="#B94A48">*invalid email address</font>');
						$('#signInEmail').css('border-color','#B94A48');
						proceed = false;
					}else{
						$('#signInEmailLabel').html('<strong>Email</strong>');
						$('#signInEmail').css('border-color','#CCC');
					}
					if(password == ""||confirmPassword == ""||password != confirmPassword){
						$('#signInPasswordLabel').html('<strong>Password </strong><font color ="#B94A48">*invalid password</font>');
						$('#signInPassword').css('border-color','#B94A48');
						$('#confirmSignInPasswordLabel').html('<strong>Confirm Password </strong><font color ="#B94A48">*invalid password</font>');
						$('#confirmSignInPassword').css('border-color','#B94A48');
						proceed = false;
					}else if(password.length <6){
						$('#signInPasswordLabel').html('<strong>Password </strong><font color ="#B94A48">*at least 6 characters.</font>');
						$('#signInPassword').css('border-color','#B94A48');
						$('#confirmSignInPasswordLabel').html('<strong>Confirm Password </strong><font color ="#B94A48">*at least 6 characters.</font>');
						$('#confirmSignInPassword').css('border-color','#B94A48');
						proceed = false;
					}else{
						$('#signInPasswordLabel').html('<strong>Password</strong>');
						$('#signInPassword').css('border-color','#CCC');
						$('#confirmSignInPasswordLabel').html('<strong>Confirm Password</strong>');
						$('#confirmSignInPassword').css('border-color','#CCC');
					}
					if(handphone == ""){
						$('#signInContactNoLabel').html('<strong>Contact No </strong><font color ="#B94A48">*required</font>');
						$('#signInContactNo').css('border-color','#B94A48');
						proceed = false;
					}else{
						$('#signInContactNoLabel').html('<strong>Contact No </strong>');
						$('#signInContactNo').css('border-color','#CCC');
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
									alert('This email address has been used.');
								}
							}
						});
					}
					return false;
				});
			
				$('#loginButton').click(function(){
					$('#loginEmail').focus();
				});
        		$('#login').click(function(){
        			var Email = $('#loginEmail').val();
        			var password = $('#loginPassword').val();
        			var proceed = true;
        			if(Email == ""){
        				$('#loginEmailLabel').html('<strong>Email </strong><font color ="#B94A48">*required</font>');
						$('#loginEmail').css('border-color','#B94A48');
        				proceed = false;
        			}else{
        				$('#loginEmailLabel').html('<strong>Email</strong>');
						$('#loginEmail').css('border-color','#CCC');
        			}
					if(password == ""){
						$('#loginPasswordLabel').html('<strong>Password </strong><font color ="#B94A48">*required</font>');
						$('#loginPassword').css('border-color','#B94A48');
        				proceed = false;
					}else{
						$('#loginPasswordLabel').html('<strong>Password</strong>');
						$('#loginPassword').css('border-color','#CCC');
					}
        			if(proceed){
	        			$.ajax({
	        				url:'<?php echo site_url("desktop/general/login");?>',
	        				type:"POST",
	        				data:{loginEmail:Email,loginPassword:password},
	        				success:function(output){
	        					if(output == "success"){
	        						$('#LoginAlert').hide();
	        						window.location.href = '<?php echo site_url("desktop/user/index");?>'; 
	        					}
								else{
									$('#LoginAlert').show();
								}
	        				}
	        			});
	        		}
	        		return false;
        		});
        	});
        	
        	function isValidEmailAddress(emailAddress) {
			    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
			    return pattern.test(emailAddress);
			};
        </script>
        <title>Login</title>
	</head>
	
	<body>
		<div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container" style="width:100%; padding-left:10px">
                    <a class="brand" href="#"><img class="pull-right" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat></a>
					<!-- Login Starts Here -->
					<div id="loginContainer" style="padding-right:20px">
						<button class="btn btn-primary btn-small" id="loginButton" style = "width:91px;"><span>Login</span></button>
						<div style="clear:both"></div>
						<div id="loginBox" style="padding-right:20px">                
							<form id="loginForm">
								<fieldset id="body">
									<fieldset>
										<label for="email" id = "loginEmailLabel"><strong>Email Address</strong></label>
										<input type="text" name="email" id="loginEmail" />
									</fieldset>
									<fieldset>
										<label for="password" id = "loginPasswordLabel"><strong>Password</strong></label>
										<input type="password" name="password" id="loginPassword" />
										<span class = "help-block" id = "LoginAlert" style = "font:22px;color:#B94A48;display:none;">Login Unsuccessfully!</span>
									</fieldset>
									<fieldset>
										<button class="btn btn-small pull-right" type="submit" id="login" style = "width:91px;height:34px;">Sign In</button>
									</fieldset>
									<fieldset style="padding-right:10px">
									<label style="text-align:center">Or sign in with</label>
										<a type="button" href = "<?php echo site_url("desktop/general/remote_login/gmail");?>" class="btn btn-small">Google</a>
										<a type="button" href = "<?php echo site_url("desktop/general/remote_login/yahoo");?>" class="btn btn-small">Yahoo</a>
										<a type="button" href = "<?php echo site_url("desktop/general/remote_login/fb");?>" class="btn btn-small">Facebook</a>
									</fieldset>
								</fieldset>
							</form>
						</div>
					</div>
					<!-- Login Ends Here -->
					
					<!-- Sign up Here -->
					<div id="signInContainer" style="padding-right:20px">
						<button class="btn btn-primary btn-small" id="signInButton"><span>Sign Up</span></button>
						<div style="clear:both"></div>
						<div id="signInBox" style="padding-right:20px">                
							<form id="signInForm">
								<fieldset id="signInbody">
									<fieldset>
										<label for="signInName" id = "signInNameLabel"><strong>Name</strong></label>
										<input type="text" name="name" id="signInName" />
									</fieldset>
									<fieldset>
										<label for="signInEmail" id = "signInEmailLabel"><strong>Email</strong></label>
										<input type="text" name="email" id="signInEmail" />
									</fieldset>
									<fieldset>
										<label for="signInPassword" id = "signInPasswordLabel"><strong>Password</strong></label>
										<input type="password" name="password" id="signInPassword" />
									</fieldset>
									<fieldset>
										<label for="confirmSignInPassword" id = "confirmSignInPasswordLabel"><strong>Confirm Password<strong></label>
										<input type="password" name="confirmPassword" id="confirmSignInPassword" />
									</fieldset>
									<fieldset>
										<label for="signInContactNo" id = "signInContactNoLabel"><strong>Contact No</strong></label>
										<input type="text" name="contactNo" id="signInContactNo" />
									</fieldset>
									<fieldset>
										<button class="btn btn-small pull-right" type="submit" id="submitCreateAccount"/>Create</button>
									</fieldset>
								</fieldset>
							</form>
						</div>
					</div>
                </div><!--/.nav-collapse -->
            </div>
        </div>
		
		<div role="main" id="main">	
			<div class="container-fluid">
				<div id="title" class="row-fluid">
					<h1 style="font-size: 50px;">Monitor, Control and Save all in ONE click</h1>
					<h3>office and facilities management made easy</h3>
				</div>
				<div class="row-fluid">
						<div class = "offset2 span8 offset2">
							<img src="<?=ASSEST_URL?>desktop/img/ClickeeLogo.png">
						</div>
				</div>
						<table class="table" style = "border-width:0;">
							<thead>
								<tr>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tr style = "border-spacing:0;border-width:0;">
							<td style = "width:60%;border-top:none;"><span class = "pull-right"><h3>Experience ultimate convenience:</h3></span></td>
							<td style = "border-top:none;"><a id="btnIntro" class="btn btn-primary btn-red pull-left" href="<?php echo site_url("desktop/general/aboutProduct");?>">Meet Clickee</a></td>
							</tr>
						</table>
						<!--
						<img src = "<?=ASSEST_URL?>desktop/img/login.png" style = "margin-left:15%;margin-top:-20px;width:450px;height:280px;"/>
						<h2 style = "margin-left:20%;font-family:Open Sans;font-weight:900;">Convience in your hand!</h2>
						-->
					</div>
					
					<!--<div class = "span5">
						<div id="newAccount">
						<h3 class="pull-right">Create a New Account</h3>
						<form class="form-horizontal">
						  <div id="inputNameGroup" class="control-group">
							<div class="controls" >
							  <input type="text" id="inputName" class="pull-right">
							  <span class="help-inline pull-right">Please enter your name.</span>
							</div>
							<label class="control-label pull-right" id="inputNameLabel" for="inputName">Name:</label>
						  </div>
						  <div class="control-group" id="inputEmailGroup">
							<div class="controls">
							  <input type="text" id="inputEmail" class="pull-right">
							  <span class="help-inline pull-right">Please enter your email address.</span>
							</div>
							<label class="control-label pull-right" for="inputEmail">Email:</label>				
						  </div>
						  <div class="control-group" id="inputPasswordGroup" >
							<div class="controls">
							  <input type="password" id="inputPassword" class="pull-right">
							  <span class="help-inline pull-right">Invalid Password.</span>
							</div>
							<label class="control-label pull-right" for="inputPassword">Password:</label>							
						  </div>
						  <div id="inputConfirmPassGroup" class="control-group">
							<div class="controls">
							  <input type="password" id="confirmPassword" class="pull-right">
							  <span class="help-inline pull-right">Invalid  password.</span>
							</div>
							<label class="control-label" for="confirmPassword" class="pull-right">Confirm Password:</label>							
						  </div>
						  <div id="inputContactGroup" class="control-group">
							<div class="controls">
							  <input type="text" id="inputContactNo" class="pull-right">
							  <span class="help-inline pull-right">Please enter your handphone number.</span>
							</div>
							<label class="control-label pull-right" for="inputContactNo">Contact No:</label>							
						  </div>
							  <button id = "submitCreateAccount" class="btn btn-primary btn-small pull-right">Create</button>
						  </div>
						</form>
					</div>-->
						
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
	</body>
</html>
