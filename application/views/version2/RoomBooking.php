<?php
 	include "header.php";
?>
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/chosen.css" />
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/roomBooking.css" />
		<script src="<?=ASSEST_URL?>desktop/js/chosen.jquery.min.js" type="text/javascript"></script>
        <script>
        var office_id = '<?=$currentOffice?>';
        $(document).ready(function(){
			$("#EmployeeNavBar li:eq(1)").addClass('active');
    		getBookingRoomsByOffice(office_id);
        	$('#floortab li').first().addClass('active');
			if(!$('div.tab-content div.tab-pane').first().hasClass('active')){
        		$('div.tab-content div.tab-pane').first().addClass('active');
        	}
        	<? if ($qr_data != ""){
        			echo "getRoomBookingInfo('".$qr_data['room_id']."');";
        		}
        	?>
			$('#midnight').live('change',function(){
			if ($('#midnight').is(':checked')){
				$('#inputEndHour option[value="23"]').attr('selected', 'selected');
				$('#inputEndMin option[value="59"]').attr('selected', 'selected');
				
				$('#inputEndHour').attr('disabled','disabled');
				$('#inputEndMin').attr('disabled','disabled');
			} else {
				$('#inputEndHour option[value="23"]').removeAttr('selected');
				$('#inputEndMin option[value="59"]').removeAttr('selected');
				$('#inputEndHour option[value="00"]').attr('selected', 'selected');
				$('#inputEndMin option[value="00"]').attr('selected', 'selected');
				
				$('#inputEndHour').removeAttr('disabled');
				$('#inputEndMin').removeAttr('disabled','');
			}
		});
		
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
        				$.each(output,function(index,element){
        					$('#roomSelectArea').append('<option id = "'+element.id+'" value = "'+element.name+'">'+element.name+'</option>');
        					console.log($('#roomSelectArea'));
        				});
        				$(".chzn-select").chosen({no_results_text: "No results matched"});
        				$(".chzn-select-deselect").chosen({allow_single_deselect:true});
        				$("#roomSelectArea").chosen().change(function(){
        					var room = $('#roomSelectArea').val();
        					if(room != ""){
        						var room_id =$('#roomSelectArea option:selected').attr('id');
        						getRoomBookingInfo(room_id);
        					}							
							return false;
        				});   				
        			}
		        	
		        	/*$('#selectRoomButton').click(function(){
        				var room = $('#roomSelectArea').val();
        				var room_id =$('#roomSelectArea option:selected').attr('id');
        				
        				console.log(room_id);
        				/*$.each(roomList,function(index,element){
        					if(element == room){
        						room_id = index;       						
        					}
        				});
						getRoomBookingInfo(room_id);
						return false;
        			});*/
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
        					'<tr>'+
	                     		'<th>Name</th>'+
	                     		'<th>Email</th>'+
	                     		'<th>Start Time</th>'+
	                     		'<th>End Time</th>'+
	                     	'</tr>');		
	                    if(!$.isEmptyObject(output)){
        				$.each(output,function(index,element){
        					if(element.owning){
	        					$('#bookingTable').append(
	        						'<tr id = "booking'+element.id+'">'+
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
	        						'<tr>'+
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
        			var floor_id = $('#room'+room_id).attr('floorID');     			
        			$('#floorTab'+floor_id+' a').trigger('click');
        			$('#roomArea').remove();
        			$('.tab-pane.floor.active').append('<div id = "roomArea" roomID = "'+room_id+'" class = "well well-small"></div>');
        			$('#roomArea').append(
        				'<form class = "form-horizontal">'+
        					'<div class = "contrl-group" style = "margin-bottom:20px;">'+
							    '<label class="control-label" for="bookingDate" style = "width:200px;"><strong>List of Booking Items On:</strong></label>'+
							    '<div class = "controls">'+							      
							      '<input type="text" id = "bookingDate" value = "'+dateString+'" style = "margin-left:10px;"/>'+
								  '<span id = "DateAlert" class = "help-block" style = "margin-left:60px;color:#B94A48;display:none;">Date should not be in past!</span>'+						      
							    '</div>'+
							'</div>'+
							'<table class = "table" id = "bookingTable" style = "margin-top:20px;">'+
		        				'<tr style>'+
		                     		'<th>Name</th>'+
		                     		'<th>Email</th>'+
		                     		'<th>Start Time</th>'+
		                     		'<th>End Time</th>'+
		                     	'</tr>'+
                     		'</table>'+
						    '<a type = "button" id = "submitBooking" class = "btn btn-primary" style = "margin-left:85%;">Go Booking</button>'+
        				'</form>');
        				
        				$('#submitBooking').click(function(){
        					var date = $('#bookingDate').val();
				        	var today = new Date();
				        	var dateString = (today.getMonth()+1)+'/'+today.getDate()+"/"+today.getFullYear();
				        	var dateArray = date.split('/');
				        	if(dateArray[2]<today.getFullYear()||((dateArray[2]==today.getFullYear())&&(dateArray[0]<(today.getMonth()+1)))||
				        	    ((dateArray[2]==today.getFullYear())&&(dateArray[0]==(today.getMonth()+1))&&(dateArray[1]<today.getDate()))){
				        	    	$('#DateAlert').show();
				        	    	$('#bookingDate').css('border-color','#B94A48');
				        	}else{
				        	    	$('#DateAlert').hide();
				        	    	$('#bookingDate').css('border-color','#CCC');
				        	    	console.log($('#goBooking h3.myModalLabel'));
				        	    	$('#goBooking h3.myModalLabel').html('Booking on '+date);
				        	    	$('#goBooking').modal('show');
				        	}
        				});
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
					$('#inputEndMin').append('<option value="59">59</option>');
					$('#inputEndMin option[value="59"]').hide();
        			if(!$.isEmptyObject(output)){
        				$.each(output,function(index,element){
        					if(element.owning){
	        					$('#bookingTable').append(
	        						'<tr id = "booking'+element.id+'">'+
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
	        						'<tr>'+
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
        	var proceed = true; 
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
        					alert('This slot has been taken.');
        				}else{
        					getRoomBookingInfoByDate(room_id,date);
        					$('#goBooking').modal('hide');
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
		<?php
			include "EmployeeNavBar.php";
		?>
    	
		<div class = "container" style = "margin-top:60px;padding-right:20px;">
			<h1 style = "text-transform: none;"><?=$offices[$currentOffice]["name"]?></h1>	
			<br>
			<select data-placeholder="Choose a Room... " class="chzn-select chzn-select-deselect" id = "roomSelectArea" style="width:350px;">	
				<option id = "defaultSelection"></option>				
			</select>
			<br>
			<br>
			<!--<form class = "form-horizontal">
			  	<div class="control-group">
				    <label class="control-label" for="selectRoom">Type In Room Name:</label>
				    <div class="controls">
				      <div class="input-append">
					      <input type="text" id="selectRoom">
					      <button id = "selectRoomButton" class = "btn btn-primary btn-small" style = "height:35px;"/>Select</button>
				    	</div>
				    </div>
				</div>
			</form>-->
			<ul class="nav nav-tabs" id = "floortab">
			<? $count = 1; foreach($floors as $floorId=>$floor) { ?>
			  <li id = "floorTab<?=$floorId?>"><a href="#floor<?=$floorId?>" data-toggle="tab">Floor <?=$count?></a></li>
			  <? $count++;} ?>
			</ul>
				
			<div class="tab-content">
			  <? foreach($floors as $floorId=>$rooms) { ?>
			  <div class="tab-pane floor" id="floor<?=$floorId?>" >
				<div class = "well well-small iconFloorMain">
					<div class="iconFloorHeader"></div>
					<? foreach($rooms as $room) { ?>
					<a type = "button" id = 'room<?=$room["id"]?>' floorID = "<?=$floorId?>" roomType = "<?=$room['type']?>" onclick = "getRoomBookingInfo('<?=$room["id"]?>')" class = "btn btn-info commonRoom" href = "#" name = '<?=$room["name"]?>'><p><br><?=$room["name"]?></p></a>
					<? } ?>
				</div>
				<div class="iconFloorFooter"></div>
			  </div>
			  <? } ?>
		</div>
	</div>

<div class="modal hide fade" id="confirmchanging" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 id="myModalLabel" style = "text-transform:none;">Confirm Change</h3>
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
	    <h3 id="myModalLabel" style = "text-transform:none;">Invent Employees:</h3>
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
		    <div id = "AddEmployeeInputAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:263px;display:none;"><strong>Warning!</strong>&nbsp;Incorrect Input!</div>
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
	    <h3 id="myModalLabel" style = "text-transform:none;">Delete booking Item</h3>
	  </div>
	  <div class="modal-body">
	  	Are you sure you want to delete the selected booking item?
	  </div>
	  <div class="modal-footer">
	  	<button id = "confirmDeleteBookingItem" class="btn btn-primary">Confirm</button>
	    <button id = "closeDeleteBookingItem" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  </div>
</div>

<div class="modal hide fade" id="goBooking" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    <h3 class="myModalLabel" style = "text-transform:none;">Booking</h3>
	  </div>
	  <div class="modal-body">
	  	<form class = "form-horizontal">
	  	<div class = "contrl-group" style = "margin-bottom:20px;">
		    <div class = "control">
		      <table class="table">
				<tr>
					<td></td>
					<td><label class="control-label" for="inputStartHour">Hour</label></td>
					<td><label class="control-label" for="inputStartMinute">Minute</label></td>
				</tr>
				<tr>
					<td>Starting time:</td>
					<td>
						<select id="inputStartHour">
						</select
					</td>
					<td>
						<select id="inputStartMin">
						</select
					</td>
				</tr>
				<tr>
					<td>End time:</td>
					<td>
						<select id="inputEndHour">
						</select>
					</td>
					<td>
						<select id="inputEndMin">
						</select
					</td>
				</tr>
				<tr>
					<td colspan="3" style="padding-top:0px; border-top:none">
					<label class="pull-right" for=
					"midnight"><input type="checkbox" id="midnight" style="margin-bottom: 6px; margin-right: 6px;"/>Check if you want to set the end time at midnight</label>
					</td>
				</tr>
			  </table>
		    </div>
		    <div id = "bookingAlert" class = "alert" style = "margin-left:125px;width:435px;display:none;"><strong>Warning!</strong>&nbsp;Incorrect Input!</div>
	    </div>
	    </form>
	  </div>
	  <div class="modal-footer">
	  	<button id = "confirmBooking" class="btn btn-primary" onClick="submitBookingRequest()">Submit</button>
	    <button id = "closeBooking" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	  </div>
</div>			
</body>
</html>