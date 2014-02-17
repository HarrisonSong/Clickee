<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<title>CreateAccount</title>
		<meta name="description" content="" />
		<meta name="author" content="SONG QIYUE" />

		<meta name="viewport" content="width=device-width; initial-scale=1.0" />

		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="<?=ASSEST_URL?>desktop/favicon.ico" />
		<link rel="apple-touch-icon" href="<?=ASSEST_URL?>desktop/apple-touch-icon.png" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Petit+Formal+Script' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="<?=ASSEST_URL?>desktop/favicon.ico" />
		<link rel="apple-touch-icon" href="<?=ASSEST_URL?>desktop/apple-touch-icon.png" />
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/main.css">

        <script src="<?=ASSEST_URL?>desktop/js/vendor/modernizr-2.6.1-respond-1.1.0.min.js"></script>
        		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?=ASSEST_URL?>desktop/js/vendor/jquery-1.8.1.min.js"><\/script>')</script>

        <script src="<?=ASSEST_URL?>desktop/js/vendor/bootstrap.min.js"></script>

        <script src="<?=ASSEST_URL?>desktop/js/plugins.js"></script>
        <script src="<?=ASSEST_URL?>desktop/js/main.js"></script>
        <script>
        	$(document).ready(function(){
        		$('#submit').click(function(){
        			console.log("excuted");
        			var name = $('#inputName').val();
        			var email = $('#inputEmail').val();
        			var password = $('#inputPassword').val();
        			var confirmPassword = $('#confirmPassword').val();
        			var handphone = $('#inputContactNo').val();
        			var proceed = true;
        			if(name == ""){
        				$("#nameAlert").show();
        				proceed = false;
        			}else{
        				$('#nameAlert').hide();
        			}
        			if(email == ""){
        				$("#emailAlert").show();
        				proceed = false;
        			}else{
        				$('#emailAlert').hide();
        			}
        			if(password == ""||confirmPassword == ""||password != confirmPassword){
        				$("#passwordAlert").show();
        				$("#confirmAlert").show();
        				proceed = false;
        			}else{
        				$("#passwordAlert").hide();
        				$("#confirmAlert").hide();
        			}
        			if(handphone == ""){
        				$("#contactAlert").show();
        				proceed = false;
        			}else{
        				$('#contactAlert').hide();
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
        	});
        </script>
	</head>

	<body style = "padding-top:20px;padding-right:20px;">
		<h2 style = "margin-left:20px;font-family:">Axis Toggle</h2>
		<hr/>
		<div>
			<h3 style = "margin-left:40%;">Create a New Account</h3>
			<form class="form-horizontal">
			  <div class="control-group" style = "margin-left:30%;">
			    <label class="control-label" for="inputName">Name:</label>
			    <div class="controls">
			      <input type="text" id="inputName">
			    </div>
			    <div id = "nameAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:170px;display:none;"><strong>Warning!</strong>&nbsp;Input incorrectly.</div>				    
			  </div>
			  <div class="control-group" style = "margin-left:30%;">
			    <label class="control-label" for="inputEmail">Email:</label>
			    <div class="controls">
			      <input type="text" id="inputEmail">
			    </div>
			    <div id = "emailAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:170px;display:none;"><strong>Warning!</strong>&nbsp;Input incorrectly.</div>
			  </div>
			  <div class="control-group" style = "margin-left:30%;">
			    <label class="control-label" for="inputPassword">Password:</label>
			    <div class="controls">
			      <input type="password" id="inputPassword">
			    </div>
			    <div id = "passwordAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:170px;display:none;"><strong>Warning!</strong>&nbsp;Passwords are not consistent!</div>
			  </div>
			  <div class="control-group" style = "margin-left:30%;">
			    <label class="control-label" for="confirmPassword">Confirm Password:</label>
			    <div class="controls">
			      <input type="password" id="confirmPassword">
			    </div>
			    <div id = "confirmAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:170px;display:none;"><strong>Warning!</strong>&nbsp;Passwords are not consistent!</div>
			  </div>
			  <div class="control-group" style = "margin-left:30%;">
			    <label class="control-label" for="inputContactNo">Contact No:</label>
			    <div class="controls">
			      <input type="text" id="inputContactNo">
			    </div>
			    <div id = "contactAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:170px;display:none;"><strong>Warning!</strong>&nbsp;Input incorrectly.</div>
			  </div>
			      <div style = "margin-left:48%;">
			      <button id = "submit" class="btn btn-success">Create</button>
			      <a type = "button" href = "<?=site_url()?>" class="btn btn-inverse">Cancel</a>
			  </div>
			</form>
		</div>
	</body>
</html>
