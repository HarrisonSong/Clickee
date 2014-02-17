<?php
	include 'header.php';
?>
        <script>
        var Interval;      	
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
        	
        	var selectOption = $('#selectRoomType').val();
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
        	});	
        	 Interval = setInterval(function(){
        		if($('#roomArea').length > 0){
        			var roomID = $('#roomArea').attr('roomID');
        			var height = $('#roomArea').height();
        			refreshRoomInfo(roomID);
        			$('#roomArea').css('min-height',height+'px');
        		}
        	},10000); 	
        	
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
        				$('#roomSwitches').empty();       				
        				$.each(output,function(index,element){
        					$('#roomSwitches').append('<div class = "span4 btn-warning" style = "margin-top:10px;margin-left:15px;background-color:#F89406;'+
        					'height:200px;-moz-border-radius: 5px;border-radius: 5px;"><h3 style = "margin-left:10px;">'+index+'</h3></div>');
        					$.each(element,function(number,device){
        						if(number != 'no_ports'){
	        						$('#roomSwitches .span4').last().append('<dl class = "dl-horizontal" style = "margin-left:-60px;"><dt style = "margin-bottom:10px;">'+
	        						device.name+'</dt><dd style = "margin-bottom:10px;"><div class="btn-group" data-toggle="buttons-radio" id = "'+index+'Device'+number+'">'+
	        						'<button class="btn btn-primary" onclick = "changeDeviceStatus(\''+index+'\','+number+',1)">ON</button><button class="btn btn-primary" onclick = "changeDeviceStatus(\''+index+'\','+number+',0)">OFF</button></div></dd>');
	        						if(device.current_status == 1){
	        							if(!$('#'+index+'Device'+number+' button').first().hasClass('active')){
	        								$('#'+index+'Device'+number+' button').first().addClass('active');
	        								$('#'+index+'Device'+number+' button').last().removeClass('active');
	        							}
	        						}else{
	        							if(!$('#'+index+'Device'+number+' button').last().hasClass('active')){
	        								$('#'+index+'Device'+number+' button').last().addClass('active');
	        								$('#'+index+'Device'+number+' button').first().removeClass('active');
	        							}
	        						}
	        						if(device.is_error == 1){
	        							$('#'+index+'Device'+number).css('opacity','0.2');
			        					$('#'+index+'Device'+number+' button').first().attr('disabled','disabled');
			        					$('#'+index+'Device'+number+' button').last().attr('disabled','disabled');
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
        	var roomType = $('#room'+room_id).attr('roomType');
        	var notification = $('#room'+room_id).attr('notify');
        	console.log('notify'+notification);
        	$('#roomArea').remove();
			$('.tab-pane.floor.active').append(
				'<div roomID = '+room_id+' name = "'+roomName+'" id = "roomArea" class="well well-small">'+
			      '<div style = "margin:20px;">'+
			      	'<span style = "font-size:23px;">RoomControl: '+roomName+'</span>'+
			      '</div>'+
					'<div id="roomSwitches" class = "row-fluid"></div>'+
				  '</div>'+
				'</div>');
        	$.ajax({
        		url:'<?=site_url('desktop/user/getRoomInfo')?>/'+ room_id,
        		type:"POST",
        		success:function(output){
        			if(!$.isEmptyObject(output)){        				
        				$.each(output,function(index,element){
        					$('#roomSwitches').append('<div class = "span4 btn-warning" style = "margin-top:10px;margin-left:15px;background-color:#F89406;'+
        					'height:200px;-moz-border-radius: 5px;border-radius: 5px;"><h3 style = "margin-left:10px;">'+index+'</h3></div>');
        					$.each(element,function(number,device){
        						if(number != 'no_ports'){
	        						$('#roomSwitches .span4').last().append('<dl class = "dl-horizontal" style = "margin-left:-60px;"><dt style = "margin-bottom:10px;">'+
	        						device.name+'</dt><dd style = "margin-bottom:10px;"><div class="btn-group" data-toggle="buttons-radio" id = "'+index+'Device'+number+'">'+
	        						'<button class="btn btn-primary" onclick = "changeDeviceStatus(\''+index+'\','+number+',1)">ON</button><button class="btn btn-primary" onclick = "changeDeviceStatus(\''+index+'\','+number+',0)">OFF</button></div></dd>');
	        						if(device.current_status == 1){
	        							if(!$('#'+index+'Device'+number+' button').first().hasClass('active')){
	        								$('#'+index+'Device'+number+' button').first().addClass('active');
	        								$('#'+index+'Device'+number+' button').last().removeClass('active');
	        							}
	        						}else{
	        							if(!$('#'+index+'Device'+number+' button').last().hasClass('active')){
	        								$('#'+index+'Device'+number+' button').last().addClass('active');
	        								$('#'+index+'Device'+number+' button').first().removeClass('active');
	        							}
	        						}
	        						if(device.is_error == 1){
	        							$('#'+index+'Device'+number).css('opacity','0.2');
			        					$('#'+index+'Device'+number+' button').first().attr('disabled','disabled');
			        					$('#'+index+'Device'+number+' button').last().attr('disabled','disabled');
			        				}
        						}
        					});
        				});
        			}
        		}
        	});
        }      
        
        function changeDeviceStatus(switch_id,port_id,status){
        	clearInterval(Interval);
        	var device_id = switch_id+"Device"+port_id;
        	if((status == 1 && !$('#'+device_id+' button').first().hasClass('active'))||
        		(status == 0 && !$('#'+device_id+' button').last().hasClass('active'))){
        			console.log('right');
	        		$.ajax({
		        		url:'<?=site_url('desktop/user/action')?>/'+switch_id+"/"+port_id+"/"+status,
		        		type:"POST",
		        		success:function(output){
		        			console.log('left');
		        			Interval = setInterval(function(){
				        		if($('#roomArea').length > 0){
				        			var roomID = $('#roomArea').attr('roomID');
				        			var height = $('#roomArea').height();
				        			refreshRoomInfo(roomID);
				        			$('#roomArea').css('min-height',height+'px');
				        		}
				        	},10000); 	
		        			if(output == "success"){
			        			if(status == 1){
	    							if(!$('#'+device_id+' button').first().hasClass('active')){
	    								$('#'+device_id+' button').first().addClass('active');
	    								$('#'+device_id+' button').last().removeClass('active');
	    							}
	    						}else{
	    							if(!$('#'+device_id+' button').last().hasClass('active')){
	    								$('#'+device_id+' button').last().addClass('active');
	    								$('#'+device_id+' button').first().removeClass('active');
	    							}
	    						}
    						}else{
    							alert(output);
    						}
		        		}
	        		});
        	}
        }
        </script>
        <title>Control Room</title>
	</head>


	<body style = "padding-top:20px;">
		<div class="navbar navbar-fixed-top" style = "line-height: 20px;">
            <div class="navbar-inner" style = "min-height:20px;height:36px;">
                <div class="container">
                  <a class="brand" href="#"><img class="pull-left" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat></a>
                        <ul class="nav">
                            <li><a href="<?php echo site_url("desktop/user/index");?>" style = "font-size:20px;text-transform: none;">Dashboard</a></li>
                            <li><a href="<?php echo site_url("desktop/user/booking");?>" style = "font-size:20px;text-transform: none;">Room Booking</a></li>
                            <li class = "active"><a href="<?php echo site_url("desktop/user/employeeControlRoom");?>" style = "font-size:20px;text-transform: none;">Control Room</a></li>
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
					  <li style = "margin-left:-50px;"><a class = "officelist" name = '<?=$office["name"]?>' id = "office<?=$office["id"]?>" href = "<?=site_url("desktop/user/employeeControlRoom/".$office["id"])?>"><i class = "icon-chevron-right pull-right"></i><?=$office["name"]?></a></li>
					<? } ?>
					</ul>
				</div>
								
				<? if (count($office) > 0) { ?>
				<div class = "span10">					
					<span style = "font-size:28px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif;">Basic Information</span>			
						<dl class="dl-horizontal">
						  <dt>Office Name:</dt>
						  <dd><?=$offices[$currentOffice]["name"]?></dd>
						  <dt>Description:</dt>
						  <dd><?=$offices[$currentOffice]["description"]?></dd>
						</dl>
						<hr/>
					<span style = "font-size:28px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif;">Floor Plan</span>
					<span style = "margin-left:40%;">
						Type of The Room:&nbsp;&nbsp;
						<select id = "selectRoomType" style = "margin-right:10px;">
						  <option selected = "selected">Private</option>
						  <option>Common</option>
						</select>
					</span>
					<ul class="nav nav-tabs">
					<? $count = 1; foreach($floors as $floorId=>$floor) { ?>
					  <li id = "floorTab<?=$floorId?>"><a href="#floor<?=$floorId?>" data-toggle="tab">Floor <?=$count?></a></li>
					  <? $count++;} ?>
					</ul>
						
					<div class="tab-content">
					  <? foreach($floors as $floorId=>$rooms) { ?>
					  <div class="tab-pane floor" id="floor<?=$floorId?>" >
						<div class = "well well-small" id = "privateRooms" style = "display:none;">
							<? foreach($rooms as $room) {
								if ($room['type'] == '0') {?>
									<a type = "button" id = 'room<?=$room["id"]?>' notify = "<?=$room['notify']?>" roomType = "<?=$room['type']?>" onclick = "getRoomInfo('<?=$room["id"]?>')" class = "btn btn-info" href = "#" name = '<?=$room["name"]?>' style = "margin:10px;width:117px;height:80px;"><?=$room["name"]?><h3><?=$room["no_switches"]?></h3></a>
								<? } 
							 } ?>
						</div>
						<div class = "well well-small" id = "commonRooms" style = "display:none;">
							<? foreach($rooms as $room) {
								if ($room['type'] == '1') {?>
									<a type = "button" id = 'room<?=$room["id"]?>' notify = "<?=$room['notify']?>" roomType = "<?=$room['type']?>" onclick = "getRoomInfo('<?=$room["id"]?>')" class = "btn btn-info" href = "#" name = '<?=$room["name"]?>' style = "margin:10px;width:117px;height:80px;"><?=$room["name"]?><h3><?=$room["no_switches"]?></h3></a>
								<? } 
							 } ?>
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
	  	<button id = "confirmChangeType" class="btn btn-primary">Confirm</button>
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