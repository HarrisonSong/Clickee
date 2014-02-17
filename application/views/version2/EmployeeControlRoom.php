<?php
	include 'header.php';
?>
	<script src = "<?=ASSEST_URL?>desktop/js/timeline.js"></script>
	<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/EmployeeControlRoom.css">
        <script>
        var Interval;      	
        $(document).ready(function(){
			$("#EmployeeNavBar li:eq(2)").addClass('active');
        	var select_room = <?php echo $selected_room;?>;
			if(select_room == 0){
				$('#privateRoom a').trigger('click');
			}else{
				var type = $('#room'+select_room).attr('roomType');
				console.log(type);
				$('#'+type+'Room a').trigger('click');
				$('#room'+select_room).trigger('click');
			}
        	
        	/*var selectOption = $('#selectRoomType').val();
			if(selectOption == "Private"){
				$('#privateRooms').show();
				$('#commonRooms').hide();
			}else{
				$('#privateRooms').hide();
				$('#commonRooms').show();
			}
        	
        	$('#selectRoomType').change(function(){
        		var selectOption = $('#selectRoomType').val();
				if(selectOption == "Private"){
					$('#privateRooms').show();
					$('#commonRooms').hide();
				}else{
					$('#privateRooms').hide();
					$('#commonRooms').show();
				}
				$('#roomArea').remove();
        	});	*/
        	 Interval = setInterval(function(){
        		if($('#roomArea').length > 0){
        			var roomID = $('#roomArea').attr('roomID');
        			var height = $('#roomArea').height();
        			refreshRoomInfo(roomID);
        			$('#roomArea').css('min-height',height+'px');
        		}
        	},7000);	
        	
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
									$('#'+index+number).append('<p class="deviceStatus" id = "'+index+number+'status">Status: <font></font></p>');
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
        		}
        	});
        }
        
        function getRoomInfo(room_id){
        	var roomName = $('#room'+room_id).attr('name');
			$('#roomControlWarning').remove();
        	$('#roomArea').remove();
			$('div.tab-content div.tab-pane.active').append(
				'<div roomID = '+room_id+' name = "'+roomName+'" id = "roomArea" class="well well-small">'+
			      	'<div id="roomLabel" style="background: url(<?=ASSEST_URL?>desktop/img/roomBodyHeader.png) top left no-repeat; background-color: whitesmoke; background-size: 100%">'+
			      	'<span style = "font-size:23px;">'+roomName+'</span>'+
			      '</div>'+
					'<div id="roomSwitches" class = "row-fluid"></div>'+
				  '</div>'+
				'</div>'+
				'<div id="roomBodyFooter" style="background: url(<?=ASSEST_URL?>desktop/img/roomBodyFooter.png) top left no-repeat; background-color: whitesmoke; ; background-size: 100%"></div>');
        	$.ajax({
        		url:'<?=site_url('desktop/user/getRoomInfo')?>/'+ room_id,
        		type:"POST",
        		success:function(output){
					console.log(output);
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
						$('#ruleConstraint').modal('show');
					}
        		}
    		});
        }
		
		function showWarning(date,startTime,endTime){
			var start_at = toTime(startTime);
			var end_at = toTime(endTime);
			console.log(start_at);
			$('#roomControlWarning').remove();
        	$('#roomArea').remove();
			$('div.tab-content div.tab-pane.active').append( 
				'<h4 id = "roomControlWarning" style = "text-transform:none;">Your upcoming booking is from '+start_at+' to '+end_at+' on '+date+'. Please book the room to start using now.</h4>'
			);
		}
        </script>
        <title>Control Room</title>
	</head>


	<body style = "padding-top:20px;">
		<?php
			include "EmployeeNavBar.php";
		?>
				
		<div class = "container" style = "margin-top:60px;padding-right:20px;">				
			<div id="officeInfo">
				<h1 style = "text-transform:none;"><?=$offices[$currentOffice]["name"]?></h1>
				<br>
				<h4 style = "text-transform:none;">
				<p>
					<?=nl2br($offices[$currentOffice]["description"])?>
				</p>
				</h4>
			</div>
			<br>
			
			<ul class="nav nav-tabs">
			  <li id = "privateRoom"><a href="#privateContent" data-toggle="tab">Private Room</a></li>
			  <li id = "commonRoom"><a href="#commonContent" data-toggle="tab">Common Room</a></li>
			</ul>
				
			<div class="tab-content">
				<div class = "tab-pane" id = "privateContent">
					<div class="well well-small iconFloorMain" style = "text-align:center;">
						<div class="iconFloorHeader"></div>
						<? foreach($private_rooms as $room) {?>
							<a type = "button" id = 'room<?=$room["id"]?>' notify = "<?=$room['notify']?>" roomType = "private" onclick = "getRoomInfo('<?=$room["id"]?>')" class = "btn btn-info privateRoom" href = "#" name = '<?=$room["name"]?>'><p><br><?=$room["name"]?><h3><?=$room["no_switches"]?></h3></a>
						 <?} ?>
					</div>
					<div class="iconFloorFooter"></div>
				</div>
				<div class = "tab-pane" id = "commonContent">
					<div class="well well-small iconFloorMain" style = "text-align:center;">
						<div class="iconFloorHeader"></div>
						<? foreach($booked_rooms as $room) {?>
							<a type = "button" id = 'room<?=$room["id"]?>' notify = "<?=$room['notify']?>" roomType = "common" <? if($room['is_active'] > 0){echo "onclick = 'getRoomInfo(".$room["id"].")'";}else{echo "onclick = 'showWarning(\"".$room['booking_date']."\",\"".$room['booking_start_at']."\",\"".$room['booking_end_at']."\")'";}?>  class = "btn btn-info commonRoom" href = "#" name = '<?=$room["name"]?>'><p><br><?=$room["name"]?><h3><?=$room["no_switches"]?></h3></a>
						 <?} ?>
					</div>
					<div class="iconFloorFooter"></div>
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
	  	<button id = "confirmChangeType" class="btn btn-primary">Confirm</button>
	    <button id = "closeChangeType" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  </div>
</div>

<div class="modal hide fade" id="roomInventBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel" style = "text-transform:none;"><strong>invent employees</strong></h3>
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
	    <h3 id="myModalLabel" style = "text-transform:none;"><strong>Rule Constraint</strong></h3>
	  </div>
	  <div class="modal-body">
	  	Because of the rule constraint, you cannot control the switch currently.
	  </div>
	  <div class="modal-footer">
	    <button id = "closeDeleteEmployee" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  </div>
</div>	
</body>
</html>