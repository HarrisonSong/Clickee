<?php
	include "header.php";
?>
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/dashboard.css">
        <script>
        	
         	$(document).ready(function(){  
				$('#updateManagerPersonalInfo').tooltip({
					title:"Update Manager's Personal Information"
				});
         		$('#confirmUpdate').click(function(){
		       		var name = $('#name').val();
		       		var contactNo = $('#contactNo').val();
		       		var password = $('#password').val();
		       		var confirmPassword =$('#confirmPassword').val();
		       		var proceed = true;
		       		if(name == ""){
		       			$('#name').css('border-color','#B94A48');
		       			$('#nameAlert').show();
		       			proceed = false;
		       		}else{
		       			$('#name').css('border-color','#CCC');
		       			$('#nameAlert').hide();
		       		}
		       		if(contactNo == ""){
		       			$('#contactNo').css('border-color','#B94A48');
		       			$('#contactNoAlert').show();
		       			proceed = false;
		       		}else{
		       			$('#contactNo').css('border-color','#CCC');
		       			$('#contactNoAlert').hide();
		       		}
		       		if(password == ""||confirmPassword == ""||password != confirmPassword){
						$('#password').css('border-color','#B94A48');
						$('#passwordLengthAlert').hide();
						$('#passwordAlert').show();
						$('#confirmPassword').css('border-color','#B94A48');
						$('#confirmPasswordLengthAlert').hide();
						$('#confirmPasswordAlert').show();
						proceed = false;
					}else if(password.length <6){
						$('#password').css('border-color','#B94A48');
						$('#passwordLengthAlert').show();
						$('#passwordAlert').hide();
						$('#confirmPassword').css('border-color','#B94A48');
						$('#confirmPasswordLengthAlert').show();
						$('#confirmPasswordAlert').hide();
						proceed = false;
					}else{
						$('#password').css('border-color','#CCC');
						$('#passwordLengthAlert').hide();
						$('#passwordAlert').hide();
						$('#confirmPassword').css('border-color','#CCC');
						$('#confirmPasswordLengthAlert').hide();
						$('#confirmPasswordAlert').hide();
					}
					if(proceed){
						$.ajax({
							url:'<?=site_url('desktop/user/updateInfo')?>',
							data:{username:name,password:password,tel:contactNo},
							type:'POST',
							success:function(output){
								console.log(output);
								if(output == "1" || output == 1){
									$('#nameDisplay').html(name);
									$('#telDisplay').html(contactNo);
					                $('#password').attr('value','');
					                $('#confirmPassword').attr('value','');
									$('#UpdatePersonalInfo').modal('hide');
								}
							}
						});
					}		
		       	});			
         		
         		$("#logout").click(function(){
         			window.location.href = '<?=site_url("desktop/user/logout")?>';
         		});
         	});
         	
        </script>
        <title>Manager Dashboard</title>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

        <div class="navbar  navbar-fixed-top" style = "line-height:20px;">
            <div class="navbar-inner"  style = "min-height:20px;height:36px;"> 
                <div class="container">
                  <a class="brand" href="#"><img class="pull-left" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat></a>
				  <a class = "pull-right"id = "logout" tabindex="-1" href="#" style = "margin-top:8px;font-size:20px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif">Logout</a>
				</div>
            </div>
        </div>

        <div class="container content">
			<br/>
			<h3 style = "text-transform:none;">Personal Information <a data-toggle = "modal" id = "updateManagerPersonalInfo" href ="#UpdatePersonalInfo"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_280_settings.png" style = "width:18px;height:18px;"></a></h3>
			<dl class="dl-horizontal">
			  <dt>Name:</dt>
			  <dd id = "nameDisplay"><?=$this->session->userdata('username')?></dd>
			  <dt>Email:</dt>
			  <dd><?=$this->session->userdata('email')?></dd>
			  <dt>Tel:</dt>
			  <dd id = "telDisplay"><?=$this->session->userdata('tel')?></dd>
			</dl>
			<hr>
			<? if ($has_office == "0"){ ?>
           		<div class = "alert"> Start now by creating an office.</div>
           <? } else if ($has_clickee == "0") { ?>
		   		<div class = "alert">Install and set up Clickee before controlling your facilities.</div>
		   <? }?>
           <div class="row-fluid" id = "manager" style = "min-height:300px;">
                <div class="span4">
					<table class="table dashboardTable">
						<tr>
							<td><h2>1</h2></td>
							<td><p><? if ($has_office == "0") {echo "Create a new office, c";} else {echo "C";}?>ustomize rooms and sync your Clickee.</p></td>
						</tr>
					</table>
                    <a type="button" id = "createOffice" href = "<?php echo site_url("desktop/manager/configureOffice");?>" class="btn btn-success btn-block"><br/><br/><? if ($has_office == "0") {echo "Create Or ";}?>Configure Office</a>
                </div>
                <div class="span4" <? if ($has_office == "0"){ ?> style = "opacity:0.2;" disabled = "disabled" <? }?>>
					<table class="table dashboardTable">
						<tr>
							<td><h2>2</h2></td>
							<td><p>Manage office devices, add allocate employee permissions.</p></td>
						</tr>
					</table>
                	<a type="button" id = "manageOffice" href = "<?php if ($has_office == "0") { echo "#"; } else {echo site_url("desktop/manager/manageEmployee");}?>" class="btn btn-success btn-block" ><br/><br/>Manage Office</a>
                </div>
                 <div class="span4" <? if ($has_office == "0" || $has_clickee == "0"){ ?> style = "opacity:0.2;" disabled = "disabled" <? }?>>
					<table class="table dashboardTable">
						<tr>
							<td><h2>3</h2></td>
							<td><p>Customize and control your facilities.</p></td>
						</tr>				 
					</table>
                	<a type="button" id = 'managerManageRoom' href = "<?php if ($has_office == "0" || $has_clickee == "0") { echo "#"; } else {echo site_url("desktop/manager/managerControlRoom");}?>" class="btn btn-success btn-block"><br/><br/>Control Page</a>
                </div>
           </div>
        </div> <!-- /container -->
        <div class="modal hide fade" id="UpdatePersonalInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel" style = "text-transform: none;"><strong>Update Personal Information</strong></h3>
		  </div>
		  <div class="modal-body">
			<form class = "form-horizontal">
				<div class="control-group" id  = "nameGroup">
			    	<label class="control-label" for="name">Name:</label>
			    	<div class="controls">
			      		<input type="text" id="name" value = "<?=$this->session->userdata('username')?>">
			      		<span id = "nameAlert" class="help-block"  style = "color:#B94A48;display:none;">Please type in name.</span>
			   		</div>
			  	</div>
			  	<div class="control-group" id  = "contactNoGroup">
			    	<label class="control-label" for="contactNo">Contact No:</label>
			    	<div class="controls">
			      		<input type="text" id="contactNo" value = "<?=$this->session->userdata('tel')?>">
			      		<span id = "contactNoAlert" class="help-block" style = "color:#B94A48;display:none;">Please type in contact number.</span>
			   		</div>
			  	</div>
			  	<div class="control-group" id  = "passwordGroup">
			    	<label class="control-label" for="password">Password:</label>
			    	<div class="controls">
			      		<input type="password" id="password">
			      		<span id = "passwordAlert" class="help-block" style = "color:#B94A48;display:none;">Invalid Password.</span>
			      		<span id = "passwordLengthAlert" class="help-block" style = "color:#B94A48;display:none;">At least 6 characters.</span>
			   		</div>
			  	</div>
			  	<div class="control-group" id  = "confirmPasswordGroup">
			    	<label class="control-label" for="confirmPassword">Confirm Password:</label>
			    	<div class="controls">
			      		<input type="password" id="confirmPassword">
			      		<span id = "confirmPasswordAlert" class="help-block" style = "color:#B94A48;display:none;">Invalid Password.</span>
			      		<span id = "confirmPasswordLengthAlert" class="help-block" style = "color:#B94A48;display:none;">At least 6 characters.</span>
			   		</div>
			  	</div>
			</form>
		  </div>
		  <div class="modal-footer">
		  	<button id = "confirmUpdate" class="btn btn-primary">Update</button>
		    <button id = "closeUpdate" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		  </div>
		</div>
    </body>
</html>
