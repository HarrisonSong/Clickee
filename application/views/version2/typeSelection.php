<?php
	include "header.php";
?>
	<style>
		.btn-success{
			background-color: #208080;
		}
		
		.btn-success:hover, .btn-success:active, .btn-success.active, .btn-success.disabled {
			background-color: #00ABAB;
		}
	</style>
	<script>
		$(document).ready(function(){
			$('#manageButton').click(function(){
				console.log('manager');
				$('#manager').submit();
			});
			$('#employeeButton').click(function(){
				console.log('employee');
				$('#employee').submit();
			});
		});
	</script>
	<title>type selection</title>
</header>
<body>
	<div class="hero-unit" style = "background-color: white;margin-bottom:-50px;margin-left:-10px;padding-top:10px;">
	  <h2 class = "success" style = "text-transform: none;margin-left:-15px;">Hi <?=$name?>, your account has been created.</h2>	  
	</div>
	<h3 style = "margin-left:35px;text-transform: none;">Please select an account type before moving on:</h3>
	<div class = "row-fluid">
		<div class = "span6">
			<a type = "button" id = "manageButton" class = "btn btn-success btn-block" style = "height:150px;width:90%;margin:20px;margin:20px 19px 10px 35px;font-size:25px;font-family:Open Sans;"><br><br>Manager</a>
			<div style = "margin-left:35px;">
				<p style="width:96%">If you are the facility manager, please select the "Manager" type to set up "Clickee" in your office. (Please install Clickee before setting up. Click <a href="<?php echo site_url("desktop/user/aboutProduct");?>" target = "_blank">HERE</a> for more details.)</p>
			</div>
		</div>
		<div class = "span6">
			<a type = "button" id = "employeeButton" class = "btn btn-primary btn-block" style = "height:150px;width:90%;margin:20px;margin-right:19px;margin-bottom:10px;font-size:25px;font-family:Open Sans;"><br><br>Employee</a>
			<div style = "margin-left:20px;">
				<p style="width:94%">Click here for an account to access the devices in your office. (The Clickee needs to be successfully set up in your office by facility manager.)</p>
			</div>
		</div>
	</div>
	<form style = "display:none;" id = "manager" action = "" method = "post">
		<input type = "hidden" name = "type" value = "manager">
	</form>
	<form style = "display:none;" id = "employee" action = "" method = "post">
		<input type = "hidden" name = "type" value = "employee">
	</form>
	
</body>
</html>
	