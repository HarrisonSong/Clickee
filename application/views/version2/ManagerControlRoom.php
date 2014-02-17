<?php
	include 'header.php';
?>
		<script>
		var dayID;
		var timelineData = new Array(7);
		
		for (i = 0; i < 7; i++)
		{
			timelineData[i] = new Array(48);
			for (j = 0; j < 48; j++)
				timelineData[i][j] = 0;
		}
		</script>
		
		<script src="<?=ASSEST_URL?>desktop/js/timeline.js"></script>
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/ManagerControlRoom.css">
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/timeline.css">
        <script>
				
        var Interval;     
        var addedEmployees = [];
        var avaiableEmployees = [];
        var ruleList;  	
        $(document).ready(function(){
			$("#ManagerNavBar li:eq(3)").addClass('active');			
            var selectRoom = '<?=$selected_room?>';
			if(selectRoom == ''){
				if(!$('.span12 ul.nav-tabs li').first().hasClass('active')){
        		$('.span12 ul.nav-tabs li').first().addClass('active');
				}
				if(!$('.span12 div.tab-content div.tab-pane').first().hasClass('active')){
					$('.span12 div.tab-content div.tab-pane').first().addClass('active');
				}
			}else{
				var floor_id = $('#room'+selectRoom).attr('floorID');
				$('#floorTab'+floor_id+' a').trigger('click');
				$('#room'+selectRoom).trigger('click');
				//getRoomInfo(selectRoom);
			}
	    	<? foreach ($employees as $employee) {
	    		echo 'avaiableEmployees.push("'.$employee['email'].'");';
	    	}?>
			
	    	$.each(avaiableEmployees,function(index,element){
	    		//console.log(element);
	    		$('#AddEmployee').append('<option>'+element+'</option>');
	    	});
        	        		      	
        	Interval = setInterval(function(){
        		if($('#roomArea').length > 0){
        			var roomID = $('#roomArea').attr('roomID');
        			var height = $('#roomArea').height();
        			refreshRoomInfo(roomID);
        			$('#roomArea').css('min-height',height+'px');
        		}
        	},7000);
        	
        	$('#selectRoomType').change(function(){
        		var selectOption = $('#selectRoomType').val();
        		console.log(selectOption);
				if(selectOption == "Private"){
					$('#privateRooms').show();
					$('#commonRooms').hide();
				}else{
					$('#privateRooms').hide();
					$('#commonRooms').show();
				}
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
        					$('html, body').scrollTop($("#roomArea").offset().top-45);
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
        
        function refreshRoomInfo(room_id){
        	$.ajax({
        		url:'<?=site_url('desktop/user/getRoomInfo')?>/'+ room_id,
        		type:"POST",
        		success:function(output){
        			if(!$.isEmptyObject(output)){    
						$('.withToolTip').tooltip('hide');
						$('#roomSwitches').empty();
        				$.each(output,function(index,element){
        					$('#roomSwitches').append(
        						'<div class = "insideClikee">'+
        							'<div class = "clickeeTitle pull-left"><img class="clickeeLogo" src = "<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png"><span class = "clickeeID">'+'  '+index+'</span></div></br><hr>'+
        						'</div>');
        					$('#roomSwitches div.insideClikee').last().append(
    							'<div class = "row-fluid" id = "'+index+'">'+
    							'</div>');
        					$.each(element,function(number,device){
        						if(number != 'no_ports'){ 
        							$('#'+index).append('<h4 class = "span4 clickeeDevice" id ="'+index+number+'"></h4>');
									$('#'+index+number).append('<div class="deviceName">'+device.name+'</div>');
									$('#'+index+number).append('<p class="deviceStatus" id = "'+index+number+'status"><font></font></p>');
									$('#'+index+number).append('<a type = "button" class = "" status = "'+device.current_status+'"></a>');       			
	        						/*if(device.current_status == 1){
        								$('#'+index+'Device'+number+' a').first().show();
        								$('#'+index+'Device'+number+' a').last().hide();
	        						}else{
        								$('#'+index+'Device'+number+' a').last().show();
        								$('#'+index+'Device'+number+' a').first().hide();

	        						}*/
	        						if(device.is_error == 1){
	        							$('#'+index+number+'status font').append('<p class="disconnectedStatus">Disconnected &nbsp;</p>').css('color','red');
										$('#'+index+number+'status font p').append('<a type = "button" class="pul-right btn-small btn-help withToolTip" content="blah"></a>');
										$('#'+index+number+'status font p a').last().tooltip({
											title:'Clickee is not connected. Reset or turn clickee on to use.'
										});
	        							//$('#'+index+number+' a').css('opacity','0.2').attr('disabled','disabled');
	        							//$('#'+index+'Device'+number+'connectionStatus').html('Disconnect').css('color','red');
	        							//$('#'+index+'Device'+number).css('opacity','0.2');
			        					//$('#'+index+'Device'+number+' a').first().attr('disabled','disabled');
			        					//$('#'+index+'Device'+number+' a').last().attr('disabled','disabled');
			        				}else{
										$('#'+index+number+' a').append('<img />');
			        					if(device.current_status == 1){
        									$('#'+index+number+' a img').attr('src','<?=ASSEST_URL?>desktop/img/SwipeOn.png');        									
		        						}else{
	        								$('#'+index+number+' a img').attr('src','<?=ASSEST_URL?>desktop/img/SwipeOff.png');
		        						}
		        						$('#'+index+number+' a').click(function(){
        										changeDeviceStatus(index,number);
        								});
			        				}
        						}
        					});
        				});
        			}
					$('html, body').scrollTop($("#roomArea").offset().top-45);
        		}
        	});
        }
        
        function getRoomInfo(room_id){
        	var roomName = $('#room'+room_id).attr('name');
        	var roomType = $('#room'+room_id).attr('roomType');
        	var notification = $('#room'+room_id).attr('notify');
			console.log(room_id);
			console.log(notification);
        	$('#roomArea').remove();
			$('#roomBodyFooter').remove();
			$('.tab-pane.floor.active').append(
				'<div roomID = '+room_id+' name = "'+roomName+'" id = "roomArea" class="well well-small" style = "min-height:300px;">'+
			      '<div id="roomLabel" style="background: url(<?=ASSEST_URL?>desktop/img/roomBodyHeader.png) top left no-repeat; background-color: whitesmoke">'+
			      	'<span style = "font-size:23px;">'+roomName+'</span>'+
			      	'<ul class="nav nav-pills pull-right" style = "margin-bottom:0px;margin-right:100px;">'+
						'<li class = "active"><a href="#roomSwitches" data-toggle="tab"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_064_lightbulb.png" style = "width:12px;height:12px;padding-right: 10px;padding-bottom: 3px;">Devices</a></li>'+
						'<li><a onclick = "getEmployeeList('+room_id+')" href="#setting" data-toggle="tab"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_280_settings.png" style = "width:12px;height:12px;padding-right: 10px;padding-bottom: 3px;">Setting</a></li>'+
					'</ul>'+		      	
			      '</div>'+	
				  '<div class = "tab-content" style = "overflow:visible;">'+
				 	 '<div class="tab-pane active" id="roomSwitches"></div>'+
			     	 '<div class = "tab-pane" id = "setting">'+
						'<table class="table" id="roomSettingTb">'+
						'<tr>'+
							'<td>'+
								'<h4 style = "text-transform: none;">Room Type:</h4>'+
								'<input type="radio" id="private" class = "roomType" onchange = "roomTypeChange('+room_id+')" name = "roomType" value="private" style = "margin-top:-5px;">&nbsp;&nbsp;<span style="margin-right:7px" id = "privateLabel">Private</span>&nbsp;&nbsp;'+
								'<input type="radio" class = "roomType" name = "roomType" id="common" onchange = "roomTypeChange('+room_id+')" value="common" data-toggle = "modal" href = "#confirmchanging" style = "margin-top:-5px; margin-left:8px">&nbsp;&nbsp;<span id = "commonLabel">Common</span>'+
							'</td>'+
							'<td>'+
								'<h4 style = "text-transform: none;">Notification:</h4>'+
								'<input type = "checkbox" onchange = "changeNotificationStatus('+room_id+')" id = "notification" style = "margin-top:-5px;">&nbsp;&nbsp;Receive SMS when switch is disconnected.'+
							'</td>'+
						'</tr>'+
						'</table>'+					
						'<div class = "accordion" id = "roomSetting">'+
							'<div class="accordion-group" id="roomRule">'+
								'<div class="accordion-heading" id="roomRuleHd">'+
			      					'<a class="accordion-toggle" data-toggle="collapse" data-parent="#roomSetting" href="#collapseOne">Rule</a>'+
								 '</div>'+
								 '<div id="collapseOne" class="accordion-body collapse">'+							 	
			      					 '<div class="accordion-inner">'+
			      					  '<form class = "form-horizontal">'+
			      					 	'<div class="control-group" style = "margin-left:20%;">'+
										    '<label class="control-label" for="selectOneRule">Select Prefered Rule:</label>'+
										    '<div class="controls">'+
										      '<div class="input-append">'+
											    '<select id = "selectOneRule">'+
												'<option id = "emptyRule"> </option>'+
											    '</select>'+
											   '<a type = "button" id = "confirmSelectOneRule" href = "#" onclick = "confirmSelectRule('+room_id+')" class = "btn btn-small btn-primary" style = "height:22px;">confirm</a>'+
											  '</div>'+
										    '</div>'+
										 '</div>'+
										'</form>'+
										'<h4 style = "text-transform:none;">Current Rule applied to this room:</h4>'+
										'<div id="timelineContainer"></div>'+
										'<div id="ruleTimeline"></div>'+
			      					 '</div>'+
			    				 '</div>'+
			  				'</div>'+
			  				'<div class="accordion-group" id = "RoomEmployee">'+
							    '<div class="accordion-heading" id="roomEmployeeHd">'+
							      '<a class="accordion-toggle" data-toggle="collapse" data-parent="#roomSetting" href="#collapseTwo">Room Employees</a>'+
							    '</div>'+
							    '<div id="collapseTwo" class="accordion-body collapse">'+
							      '<div class="accordion-inner" id = "employeeList">'+
							   	    '<a href = "#roomInventBox" onclick = "ResetEmployeeInput()" type = "button" class = "btn btn-primary btn-small" data-toggle="modal">Add An Employee</a>'+						   	   
			        				'<table class = "table" id = "employeesInRoom" style = "margin-top:20px;">'+
				        				'<tr>'+
				                     		'<th>Name</th>'+
				                     		'<th>Email</th>'+
				                     		'<th>Contact No</th>'+
				                     	'</tr>'+
			                     	'</table>'+
							      '</div>'+
							    '</div>'+
							'</div>'+
							'<div class="accordion-group" id = "bookingHistory">'+
								'<div class="accordion-heading">'+
			      					'<a class="accordion-toggle" data-toggle="collapse" data-parent="#roomSetting" href="#collapseThree">Booking History</a>'+
								 '</div>'+
								 '<div id="collapseThree" class="accordion-body collapse">'+
			      					 '<div class="accordion-inner">'+
			      					 '</div>'+
			    				 '</div>'+
			  				'</div>'+
						 '</div>'+
					   '</div>'+
					'</div>'+
					'</div>'+
				'<div id="roomBodyFooter" style="background: url(<?=ASSEST_URL?>desktop/img/roomBodyFooter.png) top left no-repeat; background-color: whiteSmoke;"></div>');
			$('#privateLabel').tooltip({
				title:'Private room can only be used by employees with permissions allocated.'
			});
			$('#commonLabel').tooltip({
				title:'Common room requires employees to make a booking prior to using.'
			});
			$('ul.nav.nav-pills li a').last().click(function(){
				$('#roomLabel').css('background-image','none');
				$('#roomBodyFooter').css('background-image','none');
				$('#roomArea').css('background-image','none');
				
				$('#roomLabel').css('background-color', 'white');
				$('#roomBodyFooter').css('background-color','white');
				$('#roomArea').css('background-color','white');
				$('#roomArea').css('border','solid 1px #DDD');
				$('#roomArea').css('border-bottom','none');
				
				$('#roomArea').css('margin-bottom','0px');
				$('#roomBodyFooter').css('margin-bottom','40px');
				
				$('#roomBodyFooter').css('border','solid 1px #DDD');
				$('#roomBodyFooter').css('border-top','none');
			});
        	
			$('ul.nav.nav-pills li').first().click(function(){
				$('#roomLabel').css('background-image','url("<?=ASSEST_URL?>desktop/img/roomBodyHeader.png")');
				$('#roomBodyFooter').css('background-image','url("<?=ASSEST_URL?>desktop/img/roomBodyFooter.png")');
				$('#roomArea').css('background-image','url("<?=ASSEST_URL?>desktop/img/roomBodyMain.png")');
				
				$('#roomLabel').css('background-color', 'whitesmoke');
				$('#roomBodyFooter').css('background-color','whitesmoke');
				$('#roomArea').css('background-color','whitesmoke');
				
				$('#roomArea').css('border','none');
				$('#roomBodyFooter').css('border','none');
				
				$('#roomArea').css('margin-bottom','0px');
				$('#roomBodyFooter').css('margin-bottom','40px');
			});
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
			$.ajax({
        		url:'<?=site_url('desktop/user/getRoomInfo')?>/'+ room_id,
        		type:"POST",
        		success:function(output){
        			if(!$.isEmptyObject(output)){      				
        				$.each(output,function(index,element){
        					$('#roomSwitches').append(
        						'<div class = "insideClikee">'+
        							'<div class = "clickeeTitle pull-left"><img class="clickeeLogo" src = "<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png"><span class = "clickeeID">'+'  '+index+'</span></div></br><hr>'+
        						'</div>');
        					$('#roomSwitches div.insideClikee').last().append(
    							'<div class = "row-fluid" id = "'+index+'">'+
    							'</div>');
        					$.each(element,function(number,device){
        						if(number != 'no_ports'){ 
        							$('#'+index).append('<h4 class = "span4 clickeeDevice" id ="'+index+number+'"></h4>');
									$('#'+index+number).append('<div class="deviceName">'+device.name+'</div>');
									$('#'+index+number).append('<p class="deviceStatus" id = "'+index+number+'status"><font></font></p>');
									$('#'+index+number).append('<a type = "button" class = "" status = "'+device.current_status+'"></a>');       			
	        						/*if(device.current_status == 1){
        								$('#'+index+'Device'+number+' a').first().show();
        								$('#'+index+'Device'+number+' a').last().hide();
	        						}else{
        								$('#'+index+'Device'+number+' a').last().show();
        								$('#'+index+'Device'+number+' a').first().hide();

	        						}*/
	        						if(device.is_error == 1){
	        							$('#'+index+number+'status font').append('<p class="disconnectedStatus">Disconnected &nbsp;</p>').css('color','red');
										$('#'+index+number+'status font p').append('<a type = "button" class="pul-right btn-small btn-help withToolTip" content="blah"></a>');
										$('#'+index+number+'status font p a').last().tooltip({
											title:'Clickee is not connected. Reset or turn clickee on to use.'
										});
	        							//$('#'+index+number+' a').css('opacity','0.2').attr('disabled','disabled');
	        							//$('#'+index+'Device'+number+'connectionStatus').html('Disconnect').css('color','red');
	        							//$('#'+index+'Device'+number).css('opacity','0.2');
			        					//$('#'+index+'Device'+number+' a').first().attr('disabled','disabled');
			        					//$('#'+index+'Device'+number+' a').last().attr('disabled','disabled');
			        				}else{
										$('#'+index+number+' a').append('<img />');
			        					if(device.current_status == 1){
        									$('#'+index+number+' a img').attr('src','<?=ASSEST_URL?>desktop/img/SwipeOn.png');        									
		        						}else{
	        								$('#'+index+number+' a img').attr('src','<?=ASSEST_URL?>desktop/img/SwipeOff.png');
		        						}
		        						$('#'+index+number+' a').click(function(){
        										changeDeviceStatus(index,number);
        								});
			        				}
        						}
        					});
        				});
        			}
					console.log($('#roomArea'));
					$('html, body').scrollTop($("#roomArea").offset().top-45);
        		}
        	});			
        }
        
             
        
        function changeDeviceStatus(switch_id,port_id){
        	clearInterval(Interval);
        	var InitialStatus = $('#'+switch_id+port_id+' a').attr('status');
        	console.log(InitialStatus);
        	if(InitialStatus == 1){
				var status = 0;
			}else{
				var status = 1;
			}
        	console.log(status);
        	var device_id = switch_id+"Device"+port_id;
    		$.ajax({
        		url:'<?=site_url('desktop/user/action')?>/'+switch_id+"/"+port_id+"/"+status,
        		type:"POST",
        		success:function(output){
        			Interval = setInterval(function(){
		        		if($('#roomArea').length > 0){
		        			var roomID = $('#roomArea').attr('roomID');
		        			var height = $('#roomArea').height();
		        			refreshRoomInfo(roomID);
		        			$('#roomArea').css('min-height',height+'px');
		        		}
		        	},7000);	
        			if(output == "success"){
        				if(status == 1){
							$('#'+switch_id+port_id+' a img').attr('src','<?=ASSEST_URL?>desktop/img/SwipeOn.png');
							$('#'+switch_id+port_id+' a').attr('status',1);
							console.log($('#'+switch_id+port_id+' a').attr('status'));
						}else{
							$('#'+switch_id+port_id+' a img').attr('src','<?=ASSEST_URL?>desktop/img/SwipeOff.png');
							$('#'+switch_id+port_id+' a').attr('status',0);
							console.log($('#'+switch_id+port_id+' a').attr('status'));
						}
					}else{
						$('#ruleConstraint div.modal-body').html(output);
						$('#ruleConstraint').modal('show');
					}
        		}
    		});
        }
        
        function getEmployeeList(room_id){
        	$.ajax({
        		url:'<?=site_url('desktop/user/controlPermission')?>/'+room_id,
        		type:"POST",
        		success:function(output){  
        			$('#employeesInRoom').remove();
        			$('#employeeList').append(
        				'<table class = "table" id = "employeesInRoom" style = "margin-top:20px;">'+
	        				'<tr>'+
	                     		'<th>Name</th>'+
	                     		'<th>Email</th>'+
	                     		'<th>Contact No</th>'+
	                     	'</tr>'+
                     	'</table>');
        			if(!$.isEmptyObject(output)){					
        				$.each(output,function(index,element){
        					$('#employeesInRoom').append(
        						'<tr>'+
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
        
        function getCurrentRule(room_id){
        	$.ajax({
        		url:'<?=site_url('desktop/user/getRoomRule')?>/'+room_id,
        		type:'POST',
        		success:function(output){
        			$('#ruleTable').empty();
					$('#timelineContainer').empty();
					$('#ruleTimeline').empty();				
        			//$('html, body').scrollTop($("#roomArea").offset().top);
        			if(!$.isEmptyObject(output)){
						createTimelineHeader($('#timelineContainer'));
						for (i = 0; i < 7; i++)	{
							createTimelineFrame(DayofWk(i), $('#ruleTimeline'));
						}						
					
        				$('#collapseOne div.accordion-inner').append(
        					'<table id = "ruleTable" ruleID = "'+output.id+'" class="table">'+
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
							dayID = index;								
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
		        							'<td>'+toTime(slot.start_at)+'</td>'+
		        							'<td>'+toTime(slot.end_at)+'</td>'+
		        						'</tr>'
		        						);
								}else{
									$('#ruleTable tbody').append(
		        						'<tr>'+
		        							'<td>'+toTime(slot.start_at)+'</td>'+
		        							'<td>'+toTime(slot.end_at)+'</td>'+
		        						'</tr>'
		        						);
								}
								for (timelineIndex = slot.start_at * 2; timelineIndex < slot.end_at * 2; timelineIndex++)
									timelineData[dayID][timelineIndex] = 1;										
							});	
							updateTimeline(dayID,'#ruleTimeline .tableTimeline'+DayofWk(dayID));	
        				});
        			}
					else{
						$('#ruleTable').empty();					
						$('#timelineContainer').empty();
						$('#ruleTimeline').empty();						
					}
        			getAllRules('<?=$currentOffice?>');
					//$(window).scrollTop($(window).height()); 
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
			console.log('why');
			if($("#selectOneRule option:selected").attr('id') != "emptyRule"){
				var rule_id = $("#selectOneRule option:selected").attr('id').replace('rule','');
			}else{
				var rule_id = '';
			}
			//console.log(rule_id);
        	$.ajax({
        		url:'<?=site_url('desktop/user/setRoomRule')?>/'+room_id+'/'+rule_id,
        		type:'POST',
        		success:function(output){
					console.log('hello');
        			$('#ruleTable').empty();
					$('#timelineContainer').empty();
					$('#ruleTimeline').empty();
					if(output != '1'){
						console.log('yeah');
						createTimelineHeader($('#timelineContainer'));
						for (i = 0; i < 7; i++)	{
							createTimelineFrame(DayofWk(i), $('#ruleTimeline'));
						}						
						
						$('#ruleTable').attr('ruleID',output.id);       			
						$('#ruleTable').append(
							'<thead>'+
								'<tr>'+
									'<th>Day</th>'+
									'<th>Start Time</th>'+
									'<th>End Time</th>'+
								'</tr>'+
							'</thead>'+
							'<tbody>'+
							'</tbody>');
        				$.each(output.slots,function(index,element){
        					var day;
							dayID = index;							
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
		        							'<td>'+toTime(slot.start_at)+'</td>'+
		        							'<td>'+toTime(slot.end_at)+'</td>'+
		        						'</tr>'
		        						);
								}else{
									$('#ruleTable tbody').append(
		        						'<tr class = "info">'+
		        							'<td>'+toTime(slot.start_at)+'</td>'+
		        							'<td>'+toTime(slot.end_at)+'</td>'+
		        						'</tr>'
		        						);
								}
								for (timelineIndex = slot.start_at * 2; timelineIndex < slot.end_at * 2; timelineIndex++){
									timelineData[dayID][timelineIndex] = 1;									
								}
							});
							updateTimeline(dayID,'#ruleTimeline .tableTimeline'+DayofWk(dayID));								
        				});
						console.log('this is not empty!');
					}
					else{
						$('#ruleTable').empty();					
						$('#timelineContainer').empty();
						$('#ruleTimeline').empty();		
						console.log('this is empty!');
					}
					console.log('this should be the last stage!');
					$('html, body').scrollTop($("#roomArea").offset().top-45);
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
        			//$('html, body').scrollTop($("#roomArea").offset().top);
        			$('#bookingTable').remove();
        			$('#collapseThree div.accordion-inner').append(
        					'<table id = "bookingTable" class = "table">'+
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
        					'<tr>'+
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
					console.log('notification changed');
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
							$('#room'+room_id).removeClass('commonRoom');
							$('#room'+room_id).addClass('privateRoom');
    						$('#roomRule').show();
							$('#RoomEmployee').show();
							$('#bookingHistory').hide();
							$('#room'+room_id).removeClass('btn-success');
							$('#room'+room_id).addClass('btn-info');
							getEmployeeList(room_id);
    						getCurrentRule(room_id);	
    					}else{
							$('#room'+room_id).removeClass('privateRoom');
							$('#room'+room_id).addClass('commonRoom');
    						$('#roomRule').hide();
							$('#RoomEmployee').hide();
							$('#bookingHistory').show();
							$('#room'+room_id).removeClass('btn-info');
							$('#room'+room_id).addClass('btn-success');	
    						getBookingItem(room_id);
    					}
    				}
					$('html, body').scrollTop($("#roomArea").offset().top-45);
    			}
    		});
    		return false;
        }
        </script>
        <title>Control Room</title>
	</head>


	<body>
		<?php
			include "ManagerNavBar.php";
		?>
		<div class = "container">			
			<div class = "row-fluid">	
			<div id="main" class = "span12">
				<div id="officeInfo">
					<h1><?=$offices[$currentOffice]["name"]?></h1>
					<br>
					<h4>
					<p>
						<?=nl2br($offices[$currentOffice]["description"])?>
					</p>
					</h4>
					<br>
				</div>
				<ul class="nav nav-tabs">
				<? $count = 1; foreach($floors as $floorId=>$floor) { ?>
				  <li id = "floorTab<?=$floorId?>"><a href="#floor<?=$floorId?>" data-toggle="tab">Floor <?=$count?></a></li>
				  <? $count++;} ?>
				</ul>
					
				<div class="tab-content">
				  <? foreach($floors as $floorId=>$rooms) { ?>
				  <div class="tab-pane floor" id="floor<?=$floorId?>" >
					<div class = "well well-small iconFloorMain">
					<div class="iconFloorHeader"></div>
						<? foreach($rooms as $room) {
							if ($room['type'] == '0') {?>
								<a type = "button" id = 'room<?=$room["id"]?>' floorID = "<?=$floorId?>" notify = "<?=$room['notify']?>" roomType = "<?=$room['type']?>" onclick = "getRoomInfo('<?=$room["id"]?>')" class = "btn btn-info privateRoom" href = "#" name = '<?=$room["name"]?>'><p><br><?=$room["name"]?></p></a>
							<? } 
							else if ($room['type'] == '1') {?>
								<a type = "button" id = 'room<?=$room["id"]?>' floorID = "<?=$floorId?>" notify = "<?=$room['notify']?>" roomType = "<?=$room['type']?>" onclick = "getRoomInfo('<?=$room["id"]?>')" class = "btn btn-success commonRoom" href = "#" name = '<?=$room["name"]?>'><p><br><?=$room["name"]?></p></a>									
							<? }
						 } ?>
					<div class="iconFloorFooter"></div>						 
					</div>
				  </div>
				  <? } ?>
				</div>
			</div>
			</div>
		</div>
</div>

<div class="modal hide fade" id="confirmchanging" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel" style = "text-transform:none;"><strong>Confirm Change</strong></h3>
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
	    <h3 id="myModalLabel" style = "text-transform:none;"><strong>Delete Employee</strong></h3>
	  </div>
	  <div class="modal-body">
	  	Are you sure you want to delete the selected employee?
	  </div>
	  <div class="modal-footer">
	  	<button id = "confirmDeleteEmployee" class="btn btn-primary">Confirm</button>
	    <button id = "closeDeleteEmployee" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  </div>
</div>
<div class="modal hide fade" id="ruleConstraint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel" style = "text-transform:none;"><strong>Constraint</strong></h3>
	  </div>
	  <div class="modal-body">
	  </div>
	  <div class="modal-footer">
	    <button id = "closeDeleteEmployee" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  </div>
</div>	
</body>
</html>