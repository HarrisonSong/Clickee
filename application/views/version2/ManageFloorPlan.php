<?php
	include 'header.php';
?>
        <script>  
        $(document).ready(function(){
        	if(!$('#office<?=$currentOffice?>').hasClass('active')){
        		$('#office<?=$currentOffice?>').addClass('active');
        	}
        	if(!$('.span10 ul.nav-tabs li').first().hasClass('active')){
        		$('.span10 ul.nav-tabs li').first().addClass('active');
        	}
			if(!$('.span10 div.tab-content div.tab-pane').first().hasClass('active')){
        		$('.span10 div.tab-content div.tab-pane').first().addClass('active');
        	}	      	
        	var addedEmployees = [];
        	var avaiableEmployees = [];
        	var ruleList;     
	    	<? foreach ($employees as $employee) { ?>
	    		avaiableEmployees.push("<?=$employee['email']?>");
	    	<?}?>
	 
	    	$.each(avaiableEmployees,function(index,element){
	    		//console.log(element);
	    		$('#AddEmployee').append('<option>'+element+'</option>');
	    	});
        	
        	$('#AddEmployeeButton').click(function(){
        		var employeeEmail = $('#AddEmployee').val();
        		$('#AddEmployee').attr('value','');
        		if(employeeEmail.length == 0){
        			$('#AddEmployeeInputAlert').show();
        			$('#AddEmployeeCheckAlert').hide();
        		}else if($.inArray(employeeEmail, avaiableEmployees) == -1){
        			$('#AddEmployeeInputAlert').hide();
        			$('#AddEmployeeCheckAlert').show();
        		}else if($.inArray(employeeEmail,addedEmployees) == -1){
        			addedEmployees.push(employeeEmail);
        			$('#AddEmployeeInputAlert').hide();
        			$('#AddEmployeeCheckAlert').hide();
        			$('#roomInventBox .modal-body').append('<span class="label label-info" style = "margin-left:10px;">'+employeeEmail+'</span>');
        		}
        		return false;
        	});
        	
        	$('#ResetEmployeeButton').click(function(){
        		addedEmployees = [];
        		$('#AddEmployee').attr('value','');
	    		$('#roomInventBox .modal-body span').remove();
    			return false;
        	});
        	
        	$('#confirmInventEmployees').click(function(){
        		var emails = addedEmployees.join(',');
        		var roomID = $('#roomArea').attr('roomID');
        		emails = emails.replace(' ','');
        		$.ajax({
        			url:'<?=site_url('desktop/user/addPermission')?>',
        			data:{room_id:roomID,emails:emails},
        			type:"POST",
        			success:function(output){
        				if(output == "success"){
        					$('#ResetEmployeeButton').trigger('click');
        					getEmployeeList(roomID);
        					$('#closeInventEmployees').trigger('click');
        				}
        			}
        		});
        	});
        	
        	$('#confirmChangeType').click(function(){
        		$('#private').attr('checked',false);
        		$('#common').attr('checked',true);
        		var room_id = $('#roomArea').attr('roomID');
        		console.log('room_id'+room_id);
        		roomTypeChange(room_id);    		
        	});  
        	$('#closeChangeType').click(function(){
        		$('#private').attr('checked',true);
        		$('#common').attr('checked',false);
        		var room_id = $('#roomArea').attr('roomID');
        		console.log('room_id'+room_id);
        		roomTypeChange(room_id);
        	});
        	
        	$("#logout").click(function(){
     			window.location.href = '<?=site_url("desktop/user/logout")?>';
     		});
        });
        
        
        function getEmployeeList(room_id){
        	$.ajax({
        		url:'<?=site_url('desktop/user/controlPermission')?>/'+room_id,
        		type:"POST",
        		success:function(output){  
        			$('#employeesInRoom').remove();
        			$('#employeeList').append(
        				'<table class = "table table-hover table-bordered" id = "employeesInRoom" style = "margin-top:20px;">'+
	        				'<tr style = "background-color:white;">'+
	                     		'<th>Name</th>'+
	                     		'<th>Email</th>'+
	                     		'<th>Contact No</th>'+
	                     	'</tr>'+
                     	'</table>');
        			if(!$.isEmptyObject(output)){					
        				$.each(output,function(index,element){
        					$('#employeesInRoom').append(
        						'<tr class="success">'+
        							'<td>'+
        								'<a class = "employee" href = "#" id = "user'+index+'" data-title = "Main page">'+element.name+'</a>'+
        								'<a type="button" onclick = "removePermission('+room_id+',\''+element.email+'\')" class="close deleteEmployee" href = "#deleteEmployeeBox" data-toggle = "modal">×</a>'+
        							'</td>'+
        							'<td>'+element.email+'</td>'+
        							'<td>'+element.tel+'</td>'+
        						'</tr>');
        				});
        			}
        		}
        	});
        }
        
        function removePermission(room_id,email){
        	$('#confirmDeleteEmployee').click(function(){
        		$.ajax({
	        		url:'<?=site_url('desktop/user/removePermission')?>',
	        		data:{room_id:room_id,email:email},
	        		type:"POST",
	        		success:function(output){
	        			if(output == "success"){
	        				getEmployeeList(room_id);
	        				$('#closeDeleteEmployee').trigger('click');
	        			}
	        		}
	        	});
        	});	
        }
        
        function getRoomInfo(room_id){
        	var roomName = $('#room'+room_id).attr('name');
        	var roomType = $('#room'+room_id).attr('roomType');
        	var notification = $('#room'+room_id).attr('notify');
        	$('#roomArea').remove();
			$('.tab-pane.floor.active').append(
				'<div roomID = '+room_id+' name = "'+roomName+'" id = "roomArea" class="well well-small" style = "min-height:300px;">'+
			      '<div style = "margin:20px;">'+
			      	'<span style = "font-size:23px;">RoomSetting:'+roomName+'</span>'+
			      '</div>'+
			      '<div style = "margin-left:20px;">'+
					'<h4 style = "text-transform: none;">Notification:</h4>'+
					'<input type = "checkbox" onchange = "changeNotificationStatus('+room_id+')" id = "notification" style = "margin-top:-5px;">&nbsp;&nbsp;Receive notification when switch is disconnected.'+
					'<h4 style = "text-transform: none;">Room Type:</h4>'+
					'<input type="radio" id="private" class = "roomType" onchange = "roomTypeChange('+room_id+')" name = "roomType" value="private" style = "margin-top:-5px;">&nbsp;&nbsp;Private&nbsp;&nbsp;'+
					'<input type="radio" class = "roomType" name = "roomType" id="common" onchange = "roomTypeChange('+room_id+')" value="common" data-toggle = "modal" href = "#confirmchanging" style = "margin-top:-5px;">&nbsp;&nbsp;Common'+
					'<hr>'+
					'<div class = "accordion" id = "roomSetting">'+
						'<div class="accordion-group" id="roomRule">'+
							'<div class="accordion-heading" style = "background-color:white;">'+
		      					'<a class="accordion-toggle" data-toggle="collapse" data-parent="#roomSetting" href="#collapseOne">Rule</a>'+
							 '</div>'+
							 '<div id="collapseOne" class="accordion-body collapse">'+							 	
		      					 '<div class="accordion-inner">'+
		      					  '<form class = "form-horizontal">'+
		      					 	'<div class="control-group" style = "margin-left:20%;">'+
									    '<label class="control-label" for="selectOneRule">Select Rule You Prefer:</label>'+
									    '<div class="controls">'+
									      '<div class="input-append">'+
										    '<select id = "selectOneRule">'+
										    '</select>'+
										   '<button id = "confirmSelectOneRule" onclick = "confirmSelectRule('+room_id+')" class = "btn btn-primary">confirm</button>'+
										  '</div>'+
									    '</div>'+
									 '</div>'+
									'</form>'+
									'<h4 style = "text-transform:none;">Current Applied Rule:</h4>'+
		      					 '</div>'+
		    				 '</div>'+
		  				'</div>'+
		  				'<div class="accordion-group" id = "RoomEmployee">'+
						    '<div class="accordion-heading" style = "background-color:white;">'+
						      '<a class="accordion-toggle" data-toggle="collapse" data-parent="#roomSetting" href="#collapseTwo">Room Employees</a>'+
						    '</div>'+
						    '<div id="collapseTwo" class="accordion-body collapse">'+
						      '<div class="accordion-inner" id = "employeeList">'+
						   	    '<a href = "#roomInventBox" onclick = "ResetEmployeeInput()" type = "button" class = "btn btn-primary btn-small" data-toggle="modal">Add An Employee</a>'+						   	   
		        				'<table class = "table table-hover table-bordered" id = "employeesInRoom" style = "margin-top:20px;">'+
			        				'<tr style = "background-color:white;">'+
			                     		'<th>Name</th>'+
			                     		'<th>Email</th>'+
			                     		'<th>Contact No</th>'+
			                     	'</tr>'+
		                     	'</table>'+
						      '</div>'+
						    '</div>'+
						'</div>'+
						'<div class="accordion-group" id = "bookingHistory">'+
							'<div class="accordion-heading" style = "background-color:white;">'+
		      					'<a class="accordion-toggle" data-toggle="collapse" data-parent="#roomSetting" href="#collapseThree">Booking History</a>'+
							 '</div>'+
							 '<div id="collapseThree" class="accordion-body collapse">'+
		      					 '<div class="accordion-inner">'+
		      					 '</div>'+
		    				 '</div>'+
		  				'</div>'+
					 '</div>'+
				   '</div>'+
				'</div>');				
			if(roomType == 0){
				$("#private").attr('checked',true);
				$('#common').attr('checked',false);
				$('#roomRule').show();
				$('#RoomEmployee').show();
				$('#bookingHistory').hide();
				getCurrentRule(room_id);
				getEmployeeList(room_id);
			}else{
				$("#private").attr('checked',false);
				$('#common').attr('checked',true);
				$('#roomRule').hide();
				$('#RoomEmployee').hide();
				$('#bookingHistory').show();
				getBookingItem(room_id);
			}
			if(notification == 0){
				$('#notification').attr('checked',false);
			}else{
				$('#notification').attr('checked',true);
			}			
        }
        
        function getCurrentRule(room_id){
        	$.ajax({
        		url:'<?=site_url('desktop/user/getRoomRule')?>/'+room_id,
        		type:'POST',
        		success:function(output){
        			$('html, body').scrollTop($("#roomArea").offset().top);
        			$('#ruleTable').remove();
        			if(!$.isEmptyObject(output)){
        				$('#collapseOne div.accordion-inner').append(
        					'<table id = "ruleTable" ruleID = "'+output.id+'" class="table table-bordered">'+
        						'<thead>'+
        							'<tr>'+
        								'<th>Day</th>'+
        								'<th>Start Time</th>'+
        								'<th>End Time</th>'+
        							'</tr>'+
        						'</thead>'+
        						'<tbody>'+
        						'</tbody>'+
        					'</table>'
        					);
        				$.each(output.slots,function(index,element){
        					var day;
        					switch(index){
        						case 0:
        							day = "Sunday";
        							break;
        						case 1:
        							day = "Monday";
        							break;
        						case 2:
        							day = "Tuesday";
        							break;
        						case 3:
        							day = "Wednesday";
        							break;
        						case 4:
        							day = "Thursday";
        							break;
        						case 5:
        							day = "Friday";
        							break;
        						case 6:
        							day = "Saturday";
        							break;
        						case "0":
        							day = "Sunday";
        							break;
        						case "1":
        							day = "Monday";
        							break;
        						case "2":
        							day = "Tuesday";
        							break;
        						case "3":
        							day = "Wednesday";
        							break;
        						case "4":
        							day = "Thursday";
        							break;
        						case "5":
        							day = "Friday";
        							break;
        						case "6":
        							day = "Saturday";
        							break;
							}        
							$.each(element,function(index,slot){
								if(index == 0){
									$('#ruleTable tbody').append(
		        						'<tr class = "info">'+
		        							'<td rowspan = '+element.length+'>'+day+'</td>'+
		        							'<td>'+slot.start_at+':00</td>'+
		        							'<td>'+slot.end_at+':00</td>'+
		        						'</tr>'
		        						);
								}else{
									$('#ruleTable tbody').append(
		        						'<tr class = "info">'+
		        							'<td>'+slot.start_at+':00</td>'+
		        							'<td>'+slot.end_at+':00</td>'+
		        						'</tr>'
		        						);
								}
							});    					
        				});       				
        			}
        			getAllRules('<?=$currentOffice?>');
        		}
        	});
        }
        
        function getAllRules(office_id){
        	var ruleID = '';
        	ruleID = $('#ruleTable').attr('ruleID');
        	$.ajax({
        		url:'<?=site_url('desktop/user/ajaxRuleIdsOffice')?>/'+office_id,
        		type:"POST",
        		success:function(output){
       				$.each(output,function(index,element){
		       			$('#selectOneRule').append('<option id = "rule'+element.id+'">'+element.name+'</option>');
		       			$('#selectOneRule').attr('value','');
		       			$('#rule'+ruleID).attr('selected','selected');
		       		});
        		}
        	});
        }
        
        function confirmSelectRule(room_id){
        	var rule_id = $("#selectOneRule option:selected").attr('id').replace('rule','');
        	$.ajax({
        		url:'<?=site_url('desktop/user/setRoomRule')?>/'+room_id+'/'+rule_id,
        		type:'POST',
        		success:function(output){
        			$('html, body').scrollTop($("#roomArea").offset().top);
        			$('#ruleTable').remove();       			
        			$('#collapseOne div.accordion-inner').append(
        					'<table id = "ruleTable" ruleID = "'+output.id+'" class="table table-bordered">'+
        						'<thead>'+
        							'<tr>'+
        								'<th>Day</th>'+
        								'<th>Start Time</th>'+
        								'<th>End Time</th>'+
        							'</tr>'+
        						'</thead>'+
        						'<tbody>'+
        						'</tbody>'+
        					'</table>'
        					);
        				$.each(output.slots,function(index,element){
        					var day;
        					switch(index){
        						case 0:
        							day = "Sunday";
        							break;
        						case 1:
        							day = "Monday";
        							break;
        						case 2:
        							day = "Tuesday";
        							break;
        						case 3:
        							day = "Wednesday";
        							break;
        						case 4:
        							day = "Thursday";
        							break;
        						case 5:
        							day = "Friday";
        							break;
        						case 6:
        							day = "Saturday";
        							break;
        						case "0":
        							day = "Sunday";
        							break;
        						case "1":
        							day = "Monday";
        							break;
        						case "2":
        							day = "Tuesday";
        							break;
        						case "3":
        							day = "Wednesday";
        							break;
        						case "4":
        							day = "Thursday";
        							break;
        						case "5":
        							day = "Friday";
        							break;
        						case "6":
        							day = "Saturday";
        							break;
							}       
							$.each(element,function(index,slot){
								if(index == 0){
									$('#ruleTable tbody').append(
		        						'<tr class = "info">'+
		        							'<td rowspan = '+element.length+'>'+day+'</td>'+
		        							'<td>'+slot.start_at+':00</td>'+
		        							'<td>'+slot.end_at+':00</td>'+
		        						'</tr>'
		        						);
								}else{
									$('#ruleTable tbody').append(
		        						'<tr class = "info">'+
		        							'<td>'+slot.start_at+':00</td>'+
		        							'<td>'+slot.end_at+':00</td>'+
		        						'</tr>'
		        						);
								}
							});    					
        				});
        		}
        	});
        }
        function getBookingItem(room_id){
        	//console.log(room_id);
        	$.ajax({
        		url:'<?=site_url('desktop/user/getRoomBookingInfo')?>/'+room_id,
        		type:'POST',
        		success:function(output){
        			//console.log(output);
        			$('html, body').scrollTop($("#roomArea").offset().top);
        			$('#bookingTable').remove();
        			$('#collapseThree div.accordion-inner').append(
        					'<table id = "bookingTable" class = "table table-bordered">'+
        						'<thead>'+
        							'<tr>'+
        								'<th>Date</th>'+
        								'<th>Name</th>'+
        								'<th>Email</th>'+
        								'<th>Start Time</th>'+
        								'<th>End Time</th>'+
        							'</tr>'+
        						'</thead>'+
        					'</table>'
        					);  			
        			$.each(output,function(index,element){
        				$('#bookingTable').append(
        					'<tr class = "success">'+
        						'<td>'+element.date+'</td>'+
        						'<td>'+element.user_name+'</td>'+
        						'<td>'+element.email+'</td>'+
        						'<td>'+element.start_at+'</td>'+
        						'<td>'+element.end_at+'</td>'+
        					'</tr>'
        					);
        			});
        		}
        	});
        }
        
        function changeNotificationStatus(room_id){
        	if($("#notification").attr('checked')){
    			var notify = 1;
    		}else{
    			var notify = 0;
    		}
        	$.ajax({
        		url:'<?=site_url('desktop/user/changeRoomNotify')?>/'+room_id+'/'+notify,
        		type:"POST",
        		success:function(output){
					//console.log('notification changed');
        		}
        	});
        }
        
        function ResetEmployeeInput(){
			$('#ResetEmployeeButton').trigger('click');
        }
        
        function roomTypeChange(room_id){
    		if($(".roomType:checked").val() == 'private'){
    			var type = 0;
    		}else{
    			var type = 1;
    		}
    		$.ajax({
    			url:'<?=site_url('desktop/user/changeRoomType')?>/'+room_id+'/'+type,
    			type:"POST",
    			success:function(output){
    				console.log(output);
    				if(output == 'success'){    					
    					if(type == 0){   						
    						$('#roomRule').show();
							$('#RoomEmployee').show();
							$('#bookingHistory').hide();
							getEmployeeList(room_id);
    						getCurrentRule(room_id);	
    					}else{
    						$('#roomRule').hide();
							$('#RoomEmployee').hide();
							$('#bookingHistory').show();	
    						getBookingItem(room_id);
    					}
    				}
    			}
    		});
    		return false;
        }
        </script>
	</head>


	<body style = "padding-top:20px;">
		<div class="navbar navbar-fixed-top" style = "line-height: 20px;">
            <div class="navbar-inner" style = "min-height:20px;height:36px;">
                <div class="container">
                  <a class="brand" href="#"><img class="pull-left" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat></a>
                        <ul class="nav">
                            <li><a href="<?php echo site_url("desktop/user/index");?>" style = "font-size:20px;text-transform: none;">Dashboard</a></li>
                            <li><a href="<?php echo site_url("desktop/user/configureOffice");?>" style = "font-size:20px;text-transform: none;">Configure Office</a></li>
                            <li class = "active"><a href="<?php echo site_url("desktop/user/ManageEmployee");?>" style = "font-size:20px;text-transform: none;">Manage Office</a></li>
                            <li><a href="<?php echo site_url("desktop/user/managerControlRoom");?>" style = "font-size:20px;text-transform: none;">Control Room</a></li>
                        </ul>
				  <a class = "pull-right"id = "logout" tabindex="-1" href="#" style = "margin-top:8px;font-size:20px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif">Logout</a>
				</div>
            </div>
    	</div>
				
		<div class = "container" style = "margin-top:60px;padding-right:20px;">
			<div class = "row-fluid">
				<div class = "span2">
					<ul class="nav nav-tabs nav-stacked offices" style = 'margin-left:0px;'>
					<? foreach ($offices as $office) { ?>
					  <li style = "margin-left:-50px;"><a class = "officelist" name = '<?=$office["name"]?>' id = "office<?=$office["id"]?>" href = "<?=site_url("desktop/user/manageEmployee/".$office["id"])?>"><i class = "icon-chevron-right pull-right"></i><?=$office["name"]?></a></li>
					<? } ?>
					</ul>
				</div>
								
				<? if (count($office) > 0) { ?>
				<div class = "span10">					
					<ul class="nav nav-pills">					  
					  <li><a href = "<?=site_url('desktop/user/manageEmployee/'.$currentOffice)?>">Employee Management</a></li>
					  <li><a href = "<?=site_url('desktop/user/manageRule/'.$currentOffice)?>">Rule Managemeent</a></li>
					  <li class="active"><a href = "<?=site_url('desktop/user/ManageFloorPlan/'.$currentOffice)?>">Room Setting</a></li>
					</ul>
					<span style = "font-size:28px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif;">Basic Information</span>			
						<dl class="dl-horizontal">
						  <dt>Office Name:</dt>
						  <dd><?=$offices[$currentOffice]["name"]?></dd>
						  <dt>Description:</dt>
						  <dd><?=$offices[$currentOffice]["description"]?></dd>
						</dl>
						<hr/>
					<h3 style = "text-transform: none;">Floor Plan</h3>
					<ul class="nav nav-tabs">
					<? $count = 1; foreach($floors as $floorId=>$floor) { ?>
					  <li id = "floorTab<?=$floorId?>"><a href="#floor<?=$floorId?>" data-toggle="tab">Floor <?=$count?></a></li>
					  <? $count++;} ?>
					</ul>
						
					<div class="tab-content">
					  <? foreach($floors as $floorId=>$rooms) { ?>
					  <div class="tab-pane floor" id="floor<?=$floorId?>" >
						<div class = "well well-small">
							<? foreach($rooms as $room) { ?>
							<a type = "button" id = 'room<?=$room["id"]?>' notify = "<?=$room['notify']?>" roomType = "<?=$room['type']?>" onclick = "getRoomInfo('<?=$room["id"]?>')" class = "btn btn-info" href = "#" name = '<?=$room["name"]?>' style = "margin:10px;width:117px;height:80px;"><?=$room["name"]?><h3><?=$room["no_switches"]?></h3></a>
							<? } ?>
						</div>
					  </div>
					  <? } ?>
				</div>
				<? } ?>
		</div>
	</div>
</div>

<div class="modal hide fade" id="confirmchanging" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel"><strong>Confirm Change</strong></h3>
	  </div>
	  <div class="modal-body">
	  	<div class="alert alert-block">
		  <h4>Warning!</h4>
		  After you change to type "Common", rule and employees list will be reset and removed.
		  Are you sure you want to do this change?
		</div>
	  </div>
	  <div class="modal-footer">
	  	<button id = "confirmChangeType" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Confirm</button>
	    <button id = "closeChangeType" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  </div>
</div>

<div class="modal hide fade" id="roomInventBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel"><strong>invent employees</strong></h3>
	  </div> 
	  <div class="modal-body">
	  	<form class = "form-horizontal">
	  	<div class="control-group">
		    <label class="control-label" for="AddEmployee">Select Email You Want:</label>
		    <div class="controls">
		      <div class="input-append">
			    <select id = "AddEmployee" class = "span3">
			    </select>
			   <button id = "AddEmployeeButton" class = "btn btn-success" style = "margin-top:-1px;">Add</button>
		      <button id = "ResetEmployeeButton" class = "btn btn-danger" style = "margin-top:-1px;">Reset</button>
			  </div>
		    </div>
		    <div id = "AddEmployeeInputAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:263px;display:none;"><strong>Warning!</strong>&nbsp;Input Incorrectly!</div>
			<div id = "AddEmployeeCheckAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:263px;display:none;"><strong>Warning!</strong>&nbsp;This Email does not belong to the office Email List!</div>
		</div>
    	</form>   	
	  </div>
	  
	  <div class="modal-footer">
	  	<button id = "confirmInventEmployees" data-dismiss="modal" aria-hidden="true" class="btn btn-primary">Confirm</button>
	    <button id = "closeInventEmployees" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  </div>
</div>

<div class="modal hide fade" id="deleteEmployeeBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel"><strong>Delete Employee</strong></h3>
	  </div>
	  <div class="modal-body">
	  	Are you sure you want to delete the selected employee?
	  </div>
	  <div class="modal-footer">
	  	<button id = "confirmDeleteEmployee" class="btn btn-primary">Confirm</button>
	    <button id = "closeDeleteEmployee" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  </div>
</div>	
</body>
</html>