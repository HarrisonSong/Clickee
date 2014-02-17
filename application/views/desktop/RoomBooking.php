<?php
 	include "header.php";
?>
        <script>
        var office_id = '<?=$currentOffice?>';
        $(document).ready(function(){
    	getBookingRoomsByOffice(office_id);
        	if(!$('#office'+office_id).hasClass('active')){
        		$('#office'+office_id).addClass('active');
        	}
        	if(!$('.span10 ul li').first().hasClass('active')){
        		$('.span10 ul li').first().addClass('active');
        	}
			if(!$('.span10 div.tab-content div.tab-pane').first().hasClass('active')){
        		$('.span10 div.tab-content div.tab-pane').first().addClass('active');
        	}
        	$("#logout").click(function(){
         			window.location.href = '<?=site_url("desktop/user/logout")?>';
         		});	      
        });
        
        function getBookingRoomsByOffice(office_id){
        	$.ajax({
        		url:'<?=site_url('desktop/user/getBookingRoomsByOffice')?>/'+office_id,
        		type:"POST",
        		success:function(output){       			
        			if(!$.isEmptyObject(output)){
        				var roomList = {};
        				$.each(output,function(index,element){
        					roomList[element.id] = element.name;
        				});       				
        			}
        			var officeRoom = [];
		        	$.each(roomList,function(index,element){
		        		officeRoom.push(element);
		        	});		        		   
		        	console.log(officeRoom);	
		        	$( "#selectRoom" ).autocomplete({
		            	source: officeRoom
		        	});
		        	
		        	$('#selectRoomButton').click(function(){
        				var room = $('#selectRoom').val();
        				var room_id;
        				$.each(roomList,function(index,element){
        					if(element == room){
        						room_id = index;       						
        					}
        				});
						getRoomBookingInfo(room_id);
						return false;
        			});
		        }
        	});
        }
        
        function getRoomBookingInfoByDate(room_id,date){
        	$.ajax({
        		url:'<?=site_url('desktop/user/getRoomBookingInfo')?>/'+room_id+'/'+date,
        		type:'POST',
        		success:function(output){
        				var height = $('#bookingTable').attr('height');
        				$('#bookingTable').attr('min-height',height);
        				$('#bookingTable').empty(); 		
        				$('#bookingTable').append(
        					'<tr style = "background-color:white;">'+
	                     		'<th>Name</th>'+
	                     		'<th>Email</th>'+
	                     		'<th>StartTime</th>'+
	                     		'<th>EndTime</th>'+
	                     	'</tr>');		
	                    if(!$.isEmptyObject(output)){
        				$.each(output,function(index,element){
        					if(element.owning){
	        					$('#bookingTable').append(
	        						'<tr class="success" id = "booking'+element.id+'">'+
	        							'<td>'+
	        								'<a class = "bookingItems" href = "#" data-title = "Main page">'+element.user_name+'</a>'+
	        							'</td>'+
	        							'<td>'+element.email+'</td>'+
	        							'<td>'+element.start_at+'</td>'+
	        							'<td>'+
		        							element.end_at+
		        							'<a type="button" onclick = "removeBookingItem('+element.id+')" class="close deleteEmployee" href = "#deleteBookingItem" data-toggle = "modal">'+
		        								'×'+
		        							'</a>'+
		        						'</td>'+
		        					'</tr>');
	        				}else{
	        					$('#bookingTable').append(
	        						'<tr class="success">'+
	        							'<td>'+
	        								'<a class = "bookingItems" href = "#" id = "booking'+element.id+'" data-title = "Main page">'+element.user_name+'</a>'+
	        							'</td>'+
	        							'<td>'+element.email+'</td>'+
	        							'<td>'+element.start_at+'</td>'+
	        							'<td>'+
		        							element.end_at+
		        						'</td>'+
		        					'</tr>');
	        				}
        				});        				
        			}
        		}
        	});
        }
        
        function getRoomBookingInfo(room_id){
        	var date = new Date();
        	var dateString = (date.getMonth()+1)+'/'+date.getDate()+"/"+date.getFullYear();
        	console.log(dateString);
        	$.ajax({
        		url:'<?=site_url('desktop/user/getRoomBookingInfo')?>/'+room_id+'/'+dateString,
        		type:'POST',
        		success:function(output){
        			console.log(output);
        			$('#roomArea').remove();
        			$('.tab-pane.floor.active').append('<div id = "roomArea" roomID = "'+room_id+'" class = "well well-small"></div>');
        			$('#roomArea').append(
        				'<form class = "form-horizontal">'+
        					'<div class = "contrl-group" style = "margin-bottom:20px;margin-left:200px;">'+
							    '<label class="control-label" for="bookingDate"><strong>Select one date:</strong></label>'+
							    '<div class = "controls">'+
							      '<input type="text" id = "bookingDate" value = "'+dateString+'" />'+
							    '</div>'+
							    '<div id = "DateAlert" class = "alert" style = "margin-left:150px;margin-top:10px;width:240px;display:none;"><strong>Warning!</strong>&nbsp;Date should not be in past!</div>'+

							'</div>'+
							'<table class = "table table-hover table-bordered" id = "bookingTable" style = "margin-top:20px;">'+
								'<legend>booking items:</legend>'+
		        				'<tr style = "background-color:white;">'+
		                     		'<th>Name</th>'+
		                     		'<th>Email</th>'+
		                     		'<th>StartTime</th>'+
		                     		'<th>EndTime</th>'+
		                     	'</tr>'+
                     		'</table>'+
							'<div class = "contrl-group" style = "margin-bottom:20px;">'+
							    '<div class = "control">'+
							      '<table class="table">'+
									'<tr>'+
										'<td><strong>Starting time:</strong></td>'+
										'<td>'+
											'<select id="inputStartHour">'+
											'</select>&nbsp;&nbsp;hour'+
										'</td>'+
										'<td>'+
											'<select id="inputStartMin">'+
											'</select>&nbsp;&nbsp;minute'+
										'</td>'+
									'</tr>'+
									'<tr>'+
										'<td><strong>End time:</strong></td>'+
										'<td>'+
											'<select id="inputEndHour">'+
											'</select>&nbsp;&nbsp;hour'+
										'</td>'+
										'<td>'+
											'<select id="inputEndMin">'+
											'</select>&nbsp;&nbsp;minute'+
										'</td>'+
									'</tr>'+
								  '</table>'+
							    '</div>'+
							    '<div id = "bookingAlert" class = "alert" style = "margin-left:150px;width:565px;display:none;"><strong>Warning!</strong>&nbsp;Input Incorrectly!</div>'+
						    '</div>'+
						    '<button id = "submitBooking" onclick = "submitBookingRequest()" class = "btn btn-primary" style = "margin-left:90%;">Submit</button>'+
        				'</form>');
        			$('#bookingDate').change(function(){
        				var date = $(this).val();
        				getRoomBookingInfoByDate(room_id,date);
        			});
        			$( "#bookingDate" ).datepicker();
        			for(var i = 0;i<24;i++){
        				if(i<10){
        					$('#inputStartHour').append('<option value="0'+i+'">0'+i+'</option>');
        					$('#inputEndHour').append('<option value="0'+i+'">0'+i+'</option>');
        				}else{
        					$('#inputStartHour').append('<option value="'+i+'">'+i+'</option>');
        					$('#inputEndHour').append('<option value="'+i+'">'+i+'</option>');
        				}
        			}
        			$('#inputStartMin').append('<option value="00">00</option>');
        			$('#inputStartMin').append('<option value="30">30</option>');       
        			$('#inputEndMin').append('<option value="00">00</option>');       
        			$('#inputEndMin').append('<option value="30">30</option>');               			
        			if(!$.isEmptyObject(output)){
        				$.each(output,function(index,element){
        					if(element.owning){
	        					$('#bookingTable').append(
	        						'<tr class="success" id = "booking'+element.id+'">'+
	        							'<td>'+
	        								'<a class = "bookingItems" href = "#" data-title = "Main page">'+element.user_name+'</a>'+
	        							'</td>'+
	        							'<td>'+element.email+'</td>'+
	        							'<td>'+element.start_at+'</td>'+
	        							'<td>'+
		        							element.end_at+
		        							'<a type="button" onclick = "removeBookingItem('+element.id+')" class="close deleteEmployee" href = "#deleteBookingItem" data-toggle = "modal">'+
		        								'×'+
		        							'</a>'+
		        						'</td>'+
		        					'</tr>');
	        				}else{
	        					$('#bookingTable').append(
	        						'<tr class="success">'+
	        							'<td>'+
	        								'<a class = "bookingItems" href = "#" id = "booking'+element.id+'" data-title = "Main page">'+element.user_name+'</a>'+
	        							'</td>'+
	        							'<td>'+element.email+'</td>'+
	        							'<td>'+element.start_at+'</td>'+
	        							'<td>'+
		        							element.end_at+
		        						'</td>'+
		        					'</tr>');
	        				}
        				});
        			}
        		}
        	});
        }
        
        
        
        function submitBookingRequest(){
        	var startHour = $('#inputStartHour').val();
        	var startMin = $('#inputStartMin').val();
        	var endHour = $('#inputEndHour').val();
        	var endMin = $('#inputEndMin').val();
        	var room_id = $('#roomArea').attr('roomID');
        	var date = $('#bookingDate').val();
        	var today = new Date();
        	var dateString = (today.getMonth()+1)+'/'+today.getDate()+"/"+today.getFullYear();
        	var dateArray = date.split('/');
        	var proceed = true;
        	if(dateArray[2]<today.getFullYear()||((dateArray[2]==today.getFullYear())&&(dateArray[0]<(today.getMonth()+1)))||
        	    ((dateArray[2]==today.getFullYear())&&(dateArray[0]==(today.getMonth()+1))&&(dateArray[1]<today.getDate()))){
        	    	$('#DateAlert').show();
        	    	proceed = false;
        	}else{
        	    	$('#DateAlert').hide();
        	}
        	if((startHour>endHour)||((startHour == endHour)&&(startMin>endMin))){
        		$('#bookingAlert').show();
        		proceed = false;
        	}else{
        		$('#bookingAlert').hide();
        	}
        	if(proceed){
        		$.ajax({
        			url:'<?=site_url('desktop/user/bookRoom')?>',
        			data:{room_id:room_id,date:date,start_at_hh:startHour,start_at_mm:startMin,end_at_hh:endHour,end_at_mm:endMin},
        			type:'POST',
        			success:function(output){
        				if(output == 'overlap'){
        					alert('time is overlapped!');
        				}else{
        					getRoomBookingInfoByDate(room_id,date);
        				}
        			}
        		});
        	}
        };
        
       function removeBookingItem(booking_id){
       		var room_id = $('#roomArea').attr('roomID');
       		var date = $('#bookingDate').val();
        	$('#confirmDeleteBookingItem').click(function(){
        		$.ajax({
        			url:'<?=site_url('desktop/user/deleteBooking')?>/'+booking_id,
        			type:'POST',
        			success:function(output){
        				if(output == "success"){
        					$('booking'+booking_id).remove();
        					getRoomBookingInfoByDate(room_id,date);
        				}
        				$('#closeDeleteBookingItem').trigger('click');
        			}
        		});        		
        	});
        }
        </script>
    <title>Room Booking</title>    
	</head>

	<body style = "padding-top:20px;">		
		<div class="navbar navbar-fixed-top" style = "line-height: 20px;">
            <div class="navbar-inner" style = "min-height:20px;height:36px;">
                <div class="container">
                  <a class="brand" href="#"><img class="pull-left" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat></a>
                        <ul class="nav">
                            <li><a href="<?php echo site_url("desktop/user/index");?>" style = "font-size:20px;text-transform: none;">Dashboard</a></li>
                            <li class = "active"><a href="<?php echo site_url("desktop/user/booking");?>" style = "font-size:20px;text-transform: none;">Room Booking</a></li>
                            <li><a href="<?php echo site_url("desktop/user/employeeControlRoom");?>" style = "font-size:20px;text-transform: none;">Control Room</a></li>
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
					  <li style = "margin-left:-50px;"><a class = "officelist" name = '<?=$office["name"]?>' id = "office<?=$office["id"]?>" href = "<?=site_url("desktop/user/booking/".$office["id"])?>"><i class = "icon-chevron-right pull-right"></i><?=$office["name"]?></a></li>
					<? } ?>
					</ul>
				</div>
				
				
				<? if (count($office) > 0) { ?>
				<div class = "span10">
					<form class = "form-horizontal">
					  	<div class="control-group">
						    <label class="control-label" for="selectRoom">Type In Room Name:</label>
						    <div class="controls">
						      <div class="input-append">
							      <input type="text" id="selectRoom">
							      <button id = "selectRoomButton" class = "btn btn-primary btn-small" style = "height:35px;"/>Select</button>
						    	</div>
						    </div>
						</div>
					</form>
					<h3>Floor plan</h3>
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
							<a type = "button" id = 'room<?=$room["id"]?>' roomType = "<?=$room['type']?>" onclick = "getRoomBookingInfo('<?=$room["id"]?>')" class = "btn btn-info" href = "#" name = '<?=$room["name"]?>' style = "margin:10px;width:117px;height:80px;"><br><?=$room["name"]?></a>
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
	    <h3 id="myModalLabel">Confirm Change</h3>
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
	    <h3 id="myModalLabel">invent employees:</h3>
	  </div>
	  <div class="modal-body">
	  	<form class = "form-horizontal">
	  	<div class="control-group">
		    <label class="control-label" for="AddEmployee">Type In Your Email:</label>
		    <div class="controls">
		      <input type="text" id="AddEmployee" style = "width:200px;">
		      <button id = "AddEmployeeButton" class = "btn btn-success" style = "margin-left:20px;"/>Add</button>
		      <button id = "ResetEmployeeButton" class = "btn btn-danger" style = "margin-left:20px;"/>Reset</button>
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

<div class="modal hide fade" id="deleteBookingItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel">Delete booking item</h3>
	  </div>
	  <div class="modal-body">
	  	Are you sure you want to delete the selected booking item?
	  </div>
	  <div class="modal-footer">
	  	<button id = "confirmDeleteBookingItem" class="btn btn-primary">Confirm</button>
	    <button id = "closeDeleteBookingItem" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  </div>
</div>		
</body>
</html>