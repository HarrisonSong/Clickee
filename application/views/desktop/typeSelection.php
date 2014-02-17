<?php
	include "header.php";
?>
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
	<div class="hero-unit" style = "background-color: white;margin-bottom:-50px;margin-left:-10px;">
	  <h2 class = "success">Congratulations! You have successfully signed up for a new account!</h2>	  
	</div>
	<div class = "row-fluid">
		<h3 style = "margin-left:50px;">Change a type of account you want :</h3>
		<div class = "span6">
			<a type = "button" id = "manageButton" class = "btn btn-success btn-block" style = "height:150px;width:90%;margin:20px;margin-right:19px;margin-bottom:10px;font-size:25px;font-family:Open Sans;"><br><br>Manager</a>
			<h4 class ="offset4">
				<strong>manager instruction:<strong>
			</h4>
			<p></p>
		</div>
		<div class = "span6">
			<a type = "button" id = "employeeButton" class = "btn btn-primary btn-block" style = "height:150px;width:90%;margin:20px;margin-right:19px;margin-bottom:10px;font-size:25px;font-family:Open Sans;"><br><br>Employee</a>
			<h4 class ="offset4">
				<strong>employee instruction:<strong>
			</h4>
			<p></p>
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
	