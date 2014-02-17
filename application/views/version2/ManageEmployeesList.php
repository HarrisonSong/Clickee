<?php
	include "header.php";
?>
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/ManageEmployee.css">
        <script>
        $(document).ready(function(){
        	var office_id = <?=$currentOffice;?>;
        	if(!$('#office<?=$currentOffice?>').hasClass('active')){
        		$('#office<?=$currentOffice?>').addClass('active');
        	}
            var width = parseInt($('#inventBox').css('width').replace('px','')) - 60;
			$("#ManagerNavBar li:eq(2)").addClass('active');
        	$('#EmployeeEmails').css('width',width);
			$('#AddEmployeeAlert').css('width',$('#EmployeeEmails').css('width') - 40);
			$('#incorrectEmailsAlert').css('width',$('#EmployeeEmails').css('width') - 40);

			loadEmployeeList(office_id);
			loadPendingList(office_id);
			$('#confirmInvention').click(function(){
				var emails = $('#EmployeeEmails').val().replace(' ','');
				var proceed = true;
				//console.log(emails);
				if(emails == ""){
					$('#AddEmployeeAlert').show();
					$('#incorrectEmailsAlert').hide();
					proceed = false;
				}else{
					emailList = emails.split(',');
					$.each(emailList,function(index,element){
						if(!isValidEmailAddress(element)){
							$('#AddEmployeeAlert').hide();
							$('#incorrectEmailsAlert').show();
							proceed = false;
						}
					});
				}
				if(proceed){
					$('#AddEmployeeAlert').hide();
					$('#incorrectEmailsAlert').hide();				
					AddEmployeeList(office_id,emails);
					$('#EmployeeEmails').attr('value','');
					$('#closeInvention').trigger('click');
				}
				return false;
			});
			
			$("#logout").click(function(){
         			window.location.href = '<?=site_url("desktop/user/logout")?>';
         		});
        });
        function loadEmployeeList(office_id){
        	$.ajax({
            	url:'<?=site_url('desktop/user/getEmloyeesByOfficeId')?>/'+office_id,
            	type:'POST',
            	success:function(output){
            		$('#officeEmployees').empty();
            		$('#officeEmployees').append('<tr><th>Name</th><th>Email</th><th>Contact No</th></tr>');
            		if(!$.isEmptyObject(output)){
						$('#EmployeeListContainer').empty();
						$('#EmployeeListContainer').css('padding-top','20px');
						$('#EmployeeListContainer').append('<span class="pull-left">Current employees:</span>'+
						'<table class = "table" id = "officeEmployees">'+
						'<thead>'+
						'<tr>'+
							'<th>Name</th>'+
							'<th>Email</th>'+
							'<th>Contact No</th>'+
						'</tr>'+
						'</thead>'+
						'</table>'+
						'<a type = "button" href = "#inventBox" class = "btn btn-primary btn-small btn-red" data-toggle="modal" style = "margin-top:-10px;">Add new employees</a>');
					
        				$.each(output,function(index,element){
        					$('#officeEmployees').append('<tr><td><p class = "employee" id = "user'+index+
        					'">'+element.name+'</p></td><td>'+element.email+'</td><td>'+element.tel+'<a type="button" onclick = "deleteEmployee('+office_id+',\''+element.email+'\')"'+
        					'class="close deleteEmployee" href = "#deleteEmployeeBox" data-toggle = "modal">×</a></td></tr>');
        				});
        			}
					else
					{
						$('#EmployeeListContainer').empty();
						$('#EmployeeListContainer').append('<div id="emptyOffice">Add employees to allocate permission to <font id = "privateTooltip" style = "color:#2F96B4;font-weight:bold;" >private room</font> and to allow room booking to <font id = "commonTooltip" style = "color:#2F96B4;font-weight:bold;">common room</font>.'+
															'</br><a href="#inventBox" id = "addNewEmployee" type="button" class="btn btn-primary btn-red" data-toggle="modal">Add New Employees</a></div>');
						$('#privateTooltip').tooltip({
							title:'Private room can only be used by employees with permissions allocated.'
						});
						$('#commonTooltip').tooltip({
							title:'Common room requires employees to make a booking prior to using.'
						});
						
						$('#EmployeeListContainer').css('padding-top','150px');
					}
            	}
            });
        }
        
        function loadPendingList(office_id){
        	$.ajax({
        		url:'<?=site_url('desktop/manager/getRequest')?>/'+office_id,
        		type:"POST",
        		success:function(output){
        			if(!$.isEmptyObject(output)){
						$('#PendingListContainer').empty();
						$('#PendingListContainer').append(
						'<span class="pull-left">Pending registers to join office:</span><br>'+
						'<div style = "margin-top:30px;">'+
							'<table class = "table" id = "pendingEmployees">'+
							'</table>'+
						'</div>'
						);
					
	        			$('#pendingEmployees').empty();
	        			$('#pendingEmployees').append(
							'<thead>'+
								'<tr>'+
									'<th>Name</th>'+
									'<th>Department</th>'+
									'<th>Position</th>'+
									'<th>ID</th>'+
									'<th>Time</th>'+
									'<th>Decision</th>'+
								'</tr>'+
							'</thead>'
							);
	                	$.each(output,function(index,element){
	                		$('#pendingEmployees').append(
	                		'<tr id = "request'+element.id+'">'+
	                			'<td>'+element.username+'</td>'+
	                			'<td>'+element.department+'</td>'+
	                			'<td>'+element.position+'</td>'+
	                			'<td>'+element.compID+'</td>'+
	                			'<td>'+element.created_at+'</td>'+								
	                			'<td>'+
	                				'<a id = "approval'+element.id+'" style = "color:green;margin-left:10px;">Approve </a>'+
	                				'<a id = "reject'+element.id+'" style = "color:red;margin-left:20px;"> Reject</a>'+
	                			'</td>'+
	                		'</tr>');
	                		$('#approval'+element.id).click(function(){
	                			$.ajax({
	                				url:'<?=site_url('desktop/manager/acceptRequest')?>/'+element.id,
	                				type:'POST',
	                				success:function(output){
	                					if(output == "1"){
	                						$('#request'+element.id).remove();
	                					}else{
	                						alert(output);
	                					}
										loadEmployeeList(office_id);
										loadPendingList(office_id);										
	                				}
	                			});
	                		});
	                		$('#reject'+element.id).click(function(){
	                			$.ajax({
	                				url:'<?=site_url('desktop/manager/rejectRequest')?>/'+element.id,
	                				type:'POST',
	                				success:function(output){
	                					if(output == "1"){
	                						$('#request'+element.id).remove();
	                					}else{
	                						alert(output);
	                					}
										loadEmployeeList(office_id);
										loadPendingList(office_id);
	                				}
	                			});
	                		});
	                	});	                	
	                }
					else{
						$('#PendingListContainer').empty();
					}
        		}
        	});
        }
        function deleteEmployee(office_id,email){
        	$('#confirmDeleteEmployee').click(function(){
        		$.ajax({
	        		url:'<?=site_url('desktop/user/removeEmployeesFromOffice')?>',
	        		data:{office_id:office_id,email:email},
	        		type:"POST",
	        		success:function(output){
	        			if(output == "success"){
	        				loadEmployeeList(office_id);
	        				$('#closeDeleteEmployee').trigger('click');
	        			}
	        		}
	        	});	
        	});
        }
        
        function AddEmployeeList(office_id,emails){
        	$.ajax({
        		url:'<?=site_url('desktop/user/addEmployeesToOffice')?>',
        		data:{office_id:office_id,emails:emails},
        		type:"POST",
        		success:function(output){
        			if(output != "success"){
        				//TO DO: measure unsuccessful added emails
        				console.log($('#EmployeeListContainer'));
						var emails = output.replace(',',' , ');
						$('ul.nav.nav-pills').after('<div id = "addEmployeesWarning" class = "alert">Email accounts not registered on Clickee will not be added to the list:'+
						'<p style = "font-weight:bold;font-size:15px;">'+emails+'</p><a type = "button" id = "addUnregisteredEmployees" href = "#" class = "btn btn-primary btn-small btn-red">Sending Invitations</a>'+
						'</div>');
						$('#addUnregisteredEmployees').click(function(){
							console.log('correct');
							$.ajax({
								url:'<?=site_url("desktop/user/invitationEmails")?>',
								data:{emails:output},
								type:'POST',
								success:function(data){
									if(data == "success"){
										$('#addEmployeesWarning').remove();
									}
								}
							});
						});
        			}
        			loadEmployeeList(office_id);
        		}
        	});
        }
		
		function isValidEmailAddress(emailAddress) {
			    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
			    return pattern.test(emailAddress);
			};
        </script>
	</head>
	<?php
		include "ManagerNavBar.php";
	?>
	<body>		
		<div class = "container" style = "margin-top:60px;padding-right:20px;">			
			<div class = "row-fluid">
				  <div class = "span12">
				  	<ul class="nav nav-pills">					  
					  <li class="active"><a href = "<?=site_url('desktop/manager/manageEmployee/'.$currentOffice)?>"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_043_group.png" style = "width:12px;height:12px;padding-right: 10px;padding-bottom: 3px;">Employee Management</a></li>
					  <li style="background-color:whitesmoke"><a href = "<?=site_url('desktop/manager/manageRule/'.$currentOffice)?>" ><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_045_calendar.png" style = "width:12px;height:12px;padding-right: 10px;padding-bottom: 3px">Rules Management</a></li>
					</ul>
					
					<div id="EmployeeListContainer">
					</div>
					
					<div id="PendingListContainer">
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal hide fade" id="inventBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:40%;">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel" style="text-transform:none"><strong>Add Employees</strong></h3>
			  </div>
			  <div class="modal-body">
			     <form>
			     	<div class="control-group">
					    <label class="control-label" for="EmployeeEmails">Enter emails of the employees in your office.(Separate with comma if multiple emails)</label>
					    <div class="controls">
					      <textarea type="text" id="EmployeeEmails" placeholder="Emails" style = "height:200px;"></textarea>
					    </div>
					    <div id = "AddEmployeeAlert" class = "alert" style = "margin-top:10px;display:none;"><strong>Input Incorrect!</strong></div>
						<div id = "incorrectEmailsAlert" class = "alert" style = "margin-top:10px;display:none;"><strong>Emails format is incorrect!</strong></div>

					</div>
			     </form>
			  </div>
			  <div class="modal-footer">
			    <button id = "closeInvention" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			    <button id = "confirmInvention" class="btn btn-primary">Confirm</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="deleteEmployeeBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			     <h3 id="myModalLabel"><strong>Remove Employee</strong></h3>
			  </div>
			  <div class="modal-body">
			  	Are you sure you want to remove the selected employee from the office?
			  </div>
			  <div class="modal-footer">
			  	<button id = "confirmDeleteEmployee" class="btn btn-primary">Confirm</button>
			    <button id = "closeDeleteEmployee" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
	</body>
</html>
