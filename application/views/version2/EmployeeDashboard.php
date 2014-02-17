<?php
	include "header.php";
?>
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/dashboard.css">
        <script>
         	$(document).ready(function(){         		  		
         		getOfficeList();
				getStatusList();
				$('#searchOffices').css('width',$('#department').css('width'));
				$('#updateFunction').tooltip({
					title:"Update Employee's Personal Information"
				});
				<? if ($has_office == "1") {
					if(count($offices) > 1){
					   echo "$('#employeeManageRoom').click(function(){
						   		$('#selectOffice div.modal-body ul li').first().addClass('active');
						   		$('#selectOffice').attr('triggerBy','control');
						   		$('#selectOffice').modal('show');
					   		});
							$('#booking').click(function(){
						   		$('#selectOffice div.modal-body ul li').first().addClass('active');
						   		$('#selectOffice').attr('triggerBy','booking');
						   		$('#selectOffice').modal('show');
					   		});
					   		$('#selectOffice div.modal-body ul li').click(function(){
					   			$('#selectOffice div.modal-body ul li.active').removeClass('active');
					   			$(this).addClass('active');
					   		});
					   		";
					}else{
					   echo "$('#employeeManageRoom').attr('href','".site_url('desktop/employee/employeeControlRoom')."');
					         $('#booking').attr('href','".site_url('desktop/employee/booking')."');";
					}
				}
				if($request_office != "") {
						echo "$('#joinOffice').trigger('click');";
						echo "$('#searchOffices').attr('value','".$request_office->name."');";
				}?>
				
				$('#confirmSelect').click(function(){
					var office_id = $('#selectOffice div.modal-body ul li.active').attr('id');
					var triggerby = $('#selectOffice').attr('triggerBy');
					if(triggerby == "booking"){
						window.location.href = "<?=site_url('desktop/employee/booking')?>/"+office_id;
					}else{
						window.location.href = "<?=site_url('desktop/employee/employeeControlRoom')?>/"+office_id;
					}
				});
				
         		$("#logout").click(function(){
         			window.location.href = '<?=site_url("desktop/user/logout")?>';
         		});
         	});
         	
         	function getStatusList(){
         		$.ajax({
         			url:'<?=site_url("desktop/employee/getRequest")?>',
         			type:"POST",
         			success:function(output){
         				console.log(output);
         				if(!$.isEmptyObject(output)){
     						$('#statusTable').empty();
     						$('#statusTable').append(
     							'<tr>'+
					      			'<th>Office Name</th>'+
					      			'<th>Time</th>'+
					      			'<th>Status</th>'+
					      		'</tr>'
     						);
         					$.each(output,function(index,element){
         						switch(element.status){
         							case "0":
         							case 0 :
         									$('#statusTable').append(
			         							'<tr>'+
			         								'<td>'+element.building_name+'</td>'+
			         								'<td>'+element.created_at+'</td>'+
			         								'<td style = "color:#FF9900;">pending</td>'+
			         							'</tr>');
         									break;
         							case "1":
         							case 1:
         									$('#statusTable').append(
			         							'<tr>'+
			         								'<td>'+element.building_name+'</td>'+
			         								'<td>'+element.created_at+'</td>'+
			         								'<td style = "color:success;">success</td>'+
			         							'</tr>');
         									break;
         							case "2":
         							case 2:
         									$('#statusTable').append(
			         							'<tr>'+
			         								'<td>'+element.building_name+'</td>'+
			         								'<td>'+element.created_at+'</td>'+
			         								'<td style = "color:grey;">rejected</td>'+
			         							'</tr>');
         									break;
         						}
         					});
         				}
         			}
         		});
         	}
         	
         	function getOfficeList(){
         		$.ajax({
         			url:'<?=site_url('desktop/user/ajaxOffices')?>',
         			type:'POST',
         			success:function(output){      
         				var officeList = [];			         		
		         		$.each(output,function(index,element){
		         			officeList.push(element.name);
		         		});
		         		console.log(officeList);
		         		$( "#searchOffices").autocomplete({
		            		source: officeList
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
				       	
				       	$('#confirmJoinOffice').click(function(){
		         			var office = $('#searchOffices').val();
		         			var department = $('#department').val();
		         			var position = $('#position').val();
		         			var companyID = $('#companyID').val();
		         			var proceed = true;
		         			if(office == "" ||($.inArray(office,officeList) == -1)){
		         				$('#NoOfficeAlert').show();
		         				$('#searchOfficeGroup').addClass('error');
		         				proceed = false;
		         			}else{
		         				$('#NoOfficeAlert').hide();
		         				$('#searchOfficeGroup').removeClass('error');
		         			}
		         			if(department == ""){
		         				$('#departmentAlert').show();
		         				$('#departmentGroup').addClass('error');
		         				proceed = false;
		         			}else{
		         				$('#departmentAlert').hide();
		         				$('#departmentGroup').removeClass('error');
		         			}
		         			if(position == ""){
		         				$('#positionAlert').show();
		         				$('#positionGroup').addClass('error');
		         				proceed = false;
		         			}else{
		         				$('#positionAlert').hide();
		         				$('#positionGroup').removeClass('error');
		         			}
		         			if(companyID == ""){
		         				$('#companyIDAlert').show();
		         				$('#companyIDGroup').addClass('error');
		         				proceed = false;
		         			}else{
		         				$('#companyIDAlert').hide();
		         				$('#companyIDGroup').removeClass('error');
		         			}
		         			if(proceed){
		         				var office_id;
		         				$.each(output,function(index,element){
		         					if(element.name == office){
		         						office_id = index;
		         					}
		         				});
		         				$.ajax({
		         					url:'<?=site_url('desktop/employee/request')?>',
		         					data:{office_id:office_id,department:department,position:position,compID:companyID},
		         					type:"POST",
		         					success:function(data){
		         						if(data != "1"){
		         							alert(data);
		         						}else{
											getStatusList();
		         							$('#JoinOfficeButton').modal('hide');
		         						}
		         					}
		         				});
		         			}
		         		});
		         	}
         		});
         	}         
        </script>
        <title>Employee Dashboard</title>
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
			<h3 style = "text-transform:none;">Personal Information <a data-toggle = "modal" id  = "updateFunction" href ="#UpdatePersonalInfo"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_280_settings.png" style = "width:18px;height:18px;"></a></h3>
			<dl class="dl-horizontal" style = "margin-left:-37px;">
			  <dt>Name:</dt>
			  <dd id = "nameDisplay"><?=$this->session->userdata('username')?></dd>
			  <dt>Email:</dt>
			  <dd><?=$this->session->userdata('email')?></dd>
			   <dt>Tel:</dt>
			  <dd id = "telDisplay"><?=$this->session->userdata('tel')?></dd>
			</dl>
			<hr>
		   <? if ($has_request == "0" && $has_office == "0"){ ?>
           		<div class = "alert"> Start using Clickee by joining an office. </div>
           <? } ?>
           <div class="row-fluid" id = "employee" style = "min-height:300px;">
           		<div class = "span4 dashboardTable">
					<table class="table dashboardTable">
						<tr>
							<td><h2>1</h2></td>
							<td><p style = "">Search and request to join an office.</p></td>
						</tr>
					</table>
           			<a type="button" id = "joinOffice" data-toggle = "modal" href = "#JoinOfficeButton" class="btn btn-primary btn-block"><br/><br/>Join Office</a>                   
           		</div>
           		<div class = "span4 dashboardTable" <? if ($has_office == "0"){ ?> style = "opacity:0.2;" disabled = "disabled" <?}?>>
					<table class="table dashboardTable">
						<tr>
							<td><h2>2</h2></td>
							<td><p>Click here to book a room.</p></td>
						</tr>
					</table>
           			<a type="button" id = "booking" href = "#" class="btn btn-primary btn-block"><br/><br/>Booking System</a>                   
           		</div>
           		<div class = "span4 dashboardTable" <? if ($has_office == "0"){ ?> style = "opacity:0.2;" disabled = "disabled" <? }?>>
					<table class="table dashboardTable">
						<tr>
							<td><h2>3</h2></td>
							<td><p>Click here to control the devices in assigned/booked rooms.</p>
						</tr>
					</table>
           			<a type="button" id = "employeeManageRoom" href = "#" class="btn btn-primary btn-block"><br/><br/>Control Page</a>
           		</div>
           </div>
           
           <div class="accordion" id="employeeApplyStatus" <? if($has_request == "0"){echo "style = 'display:none;'";}?>>
			  <div class="accordion-group">
			    <div class="accordion-heading">
			      <a class="accordion-toggle" data-toggle="collapse" data-parent="#employeeApplyStatus" href="#collapseOne">
			        Request Statuses
			      </a>
			    </div>
			    <div id="collapseOne" class="accordion-body collapse">
			      <div class="accordion-inner">
			      	<table class = "table" id = "statusTable">
			      		<tr>
			      			<th>Office Name</th>
			      			<th>Status</th>
			      		</tr>
			      	</table>
			      </div>
			    </div>
			  </div>
			</div>
        </div> <!-- /container -->
        
      	<div class="modal hide fade" id="JoinOfficeButton" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel" style = "text-transform: none;"><strong>Join Office</strong></h3>
		  </div>
		  <div class="modal-body">
			<form class = "form-horizontal">
				<div class="control-group" id  = "searchOfficeGroup">
				  <label class="control-label" for="searchOffices">search an office:</label>
				  <div class="controls">
				  	<input class="span2" id="searchOffices" type="text"/>				      
				    <span id = "NoOfficeAlert" class="help-inline" style = "display:none;">Office is not in the office List!</span>
				  </div>
				</div>
				<hr>
				<h4 style="padding-left:5%">Fill In Personal Info:</h4>
				<div class="control-group" id = "departmentGroup">
			  	 	<label class="control-label" for="department">department:</label>
			    	<div class="controls">
			    	  <input type="text" id="department">
			    	  <span id = "departmentAlert" class="help-inline" style = "display:none;">incorrect input!</span>
			   		</div>
			 	</div>
			  	<div class="control-group" id  = "positionGroup">
			    	<label class="control-label" for="position">position:</label>
			    	<div class="controls">
			      		<input type="text" id="position">
			      		<span id = "positionAlert" class="help-inline" style = "display:none;">incorrect input!</span>
			   		</div>
			  	</div>
			  	<div class="control-group" id = "companyIDGroup">
			    	<label class="control-label" for="companyID">company ID:</label>
			    	<div class="controls">
			      		<input type="text" id="companyID">
			      		<span id = "companyIDAlert" class="help-inline" style = "display:none;">incorrect input!</span>
			    	</div>
			  	</div>
			</form>
		  </div>
		  <div class="modal-footer">
		  	<button id = "confirmJoinOffice" class="btn btn-primary">Confirm</button>
		    <button id = "closeJoinOffice" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		  </div>
		</div>
		
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
		
		<div class="modal hide fade" id="selectOffice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel" style = "text-transform: none;"><strong>Select Office</strong></h3>
		  </div>
		  <div class="modal-body">
		  	<? if(count($offices) > 1){
		  		$countOfficeNum = 1;?>
		  		<ul class="nav nav-pills nav-stacked">
				<? foreach($offices as $office){?>		  	
			  		<li id = "<?=$office['id']?>" ><a><?=$countOfficeNum.". ".$office['name']?></a></li>
			 	<?
			 		$countOfficeNum = $countOfficeNum + 1;
				}?>	     
				<ul/>
			<?}?>
		  </div>
		  <div class="modal-footer">
		  	<button id = "confirmSelect" class="btn btn-primary">Confirm</button>
		    <button id = "closeClose" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		  </div>
		</div>
    </body>
</html>
