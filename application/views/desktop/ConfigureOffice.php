<?php
	include 'header.php';
?>
	<script>        	
        $(document).ready(function(){
        	if(!$('#office<?=$currentOffice?>').hasClass('active')){
        		$('#office<?=$currentOffice?>').addClass('active');
        	}
        	if(!$('.span10 ul li').first().hasClass('active')){
        		$('.span10 ul li').first().addClass('active');
        	}
			if(!$('.span10 div.tab-content div.tab-pane').first().hasClass('active')){
        		$('.span10 div.tab-content div.tab-pane').first().addClass('active');
        	}
        	
        	if($('.officelist').length == 0){
        		console.log('inside');
        		$('#createNewOfficeButton').trigger('click');
        	}
        	
        	$(".span10 a").first().click(function(){
        		var officeName = $('.officelist.active').attr('name');
        		$('#deleteOffice div.modal-body').html('Are you sure you want to delete Office "'+officeName+'"?');
        	});
        	
        	$('#officeName input').change(function(){
        		var officeName = $('#inputOffice').val();
        		if(officeName == ""){
 					$('#officeName').addClass('error');
 					$('#officeName span').show();
 				}else{
 					$('#officeName').removeClass('error');
 					$('#officeName span').hide();
 				}
        	});
        	
        	$('#floorNum').change(function(){
        		var numOfFloor = $('#floorNumber').val();
        		if(numOfFloor <= 0 ||numOfFloor == ""){
 					$('#floorNum').addClass('error');
 					$('#floorNum span').show();
 				}else{
 					$('#floorNum').removeClass('error');
 					$('#floorNum span').hide();
 				}
        	});
        	
        	$('#officeDesc').change(function(){
        		var desc = $('#inputDesc').val();
        		if(desc == ""){
 					$('#officeDesc').addClass('error');
 					$('#officeDesc span').show();
 				}else{
 					$('#officeDesc').removeClass('error');
 					$('#officeDesc span').hide();
 				}
        	});
 			$("#submitOfficeInfo").live("click",function(){
 				var officeName = $('#inputOffice').val();
 				var numOfFloor = $('#floorNumber').val();
 				var desc = $('#inputDesc').val();
 				var proceed = true;
 				if(officeName == ""){
 					$('#officeName').addClass('error');
 					$('#officeName span').show();
 					proceed = false;
 				}else{
 					$('#officeName').removeClass('error');
 					$('#officeName span').hide();
 				}
 				if(numOfFloor <= 0 ||numOfFloor == ""){
 					$('#floorNum').addClass('error');
 					$('#floorNum span').show();
 					proceed = false;
 				}else{
 					$('#floorNum').removeClass('error');
 					$('#floorNum span').hide();
 				}
 				if(desc == ""){
 					$('#officeDesc').addClass('error');
 					$('#officeDesc span').show();
 					proceed = false;
 				}else{
 					$('#officeDesc').removeClass('error');
 					$('#officeDesc span').hide();
 				}
 				if(proceed){
	 				$.ajax({
	 					url:'<?=site_url("desktop/user/createOffice")?>',
	 					data:{name:officeName,description:desc,no_floors:numOfFloor},
	 					type:"POST",
	 					success:function(output){
	 						window.location.href = '<?=site_url('desktop/user/configureOffice')?>/'+output;
	 						$('.addRoom').first().trigger('click');
	 					}
 					});
 				}
 				return false; 				
 			});
 			
 			$('.officelist').click(function(){
 				var target = $(this).attr('href');
 				var prev = $('.officelist.active').attr('href');
 				 $('.officelist.active').removeClass('active');
 				 $(this).addClass('active');
 				//console.log(prev);
 				$(prev).hide();
 				$(target).show();
 				return false;
 			});
 			
 			$('#addFloor').click(function(){
 				$.ajax({
 					url:"<?=site_url('desktop/user/addFloor/'.$currentOffice)?>",
 					type:"POST",
 					success:function(output){
 						if(!$.isEmptyObject(output)){
 							window.location.href = "<?php echo site_url("desktop/user/configureOffice/".$currentOffice);?>";
 						}
 					}
 				});
 			});
 			
 			$('#roomName').change(function(){
 				var roomName = $('#inputRoomName').val();
 				if(roomName == ""){
 					$('#roomName').addClass('error');
 					$('#roomName span').show();
 				}else{
 					$('#roomName').removeClass('error');
 					$('#roomName span').hide();
 				}
 			});
 			$('#confirmRoomInfo').live("click",function(){
 				var roomName = $('#inputRoomName').val();
 				var floorID = $('.tab-pane.active').attr('id').replace('floor','');
 				if(roomName == ""){
 					$('#roomName').addClass('error');
 					$('#roomName span').show();
 					return false;
 				}else{
 					$('#roomName').removeClass('error');
 					$('#roomName span').hide();
 					$.ajax({
 						url:'<?=site_url("desktop/user/createRoom")?>',
 						data:{name:roomName,floor_id:floorID},
 						type:"POST",
 						success:function(output){
 							$('.tab-pane.active div .addRoom').before('<a id = "room'+output+'" onclick = "getRoomInfo(\''+output+'\')" " type = "button" class = "btn btn-info" href = "#" name = "'+roomName+'" style = "margin:10px;width:117px;height:80px;">'+roomName+'<h3>0</h3></a>');
 							$('#closeconfirmRoomInfo').trigger('click');
 							$('#room'+output).trigger('click');
 							console.log($('#roomArea'));
 							$('#roomArea a').first().trigger('click');
 						}
 					});		
 				}
 				return false;
 			});
 			
 			$('#closeconfirmRoomInfo').live("click",function(){
 				$('#inputRoomName').attr('value','');
 			});
 			
 			$('#addSwitchID').change(function(){
 				var SwitchID = $('#newSwitch').val();
 				if(SwitchID == ""){
 					$('#addSwitchID').addClass('error');
 					$('#addSwitchID span').show();
 				}else{
 					$('#addSwitchID').removeClass('error');
 					$('#addSwitchID span').hide();
 				}
 			});
 			
 			$('#addNumOfDevices').change(function(){
 				var SwitchNum = $('#numOfDevices').val();
 				if(SwitchNum == "" || SwitchNum <= 0){
 					$('#addNumOfDevices').addClass('error');
 					$('#addNumOfDevices span').show();
 				}else{
 					$('#addNumOfDevices').removeClass('error');
 					$('#addNumOfDevices span').hide();
 				}
 			});
 			
 			$('#confirmSwitchInfo').live("click",function(){
 				var SwitchID = $('#newSwitch').val();
 				var SwitchNum = $('#numOfDevices').val();
 				var roomID = $('#roomArea').attr('roomID');
 				var proceed = true; 
 				if(SwitchID == ""){
 					$('#addSwitchID').addClass('error');
 					$('#addSwitchID span').show();
 					proceed = false;
 				}else{
 					$('#addSwitchID').removeClass('error');
 					$('#addSwitchID span').hide();
 				}
 				if(SwitchNum == "" || SwitchNum <= 0){
 					$('#addNumOfDevices').addClass('error');
 					$('#addNumOfDevices span').show();
 					proceed = false;
 				}else{
 					$('#addNumOfDevices').removeClass('error');
 					$('#addNumOfDevices span').hide();
 				}
 				if(proceed){
 					data = 'room_id='+roomID+'&switch_id='+SwitchID+'&no_ports='+SwitchNum;
 					for(var i = 0;i < SwitchNum;i++){
 						data = data + "&port"+(i+1)+"="+ $('#Device'+i).val();
 					}
					$.ajax({
 						url:'<?=site_url('desktop/user/addSwitch')?>',
 						data:data,
 						type:'POST',
 						success:function(output){
 							if(output == "success"){
	 							$('#roomArea').append('<a id = "'+SwitchID+'" onclick = "EditSwitchInfo(\''+SwitchID+'\')" type = "button" class = "btn btn-warning" data-toggle = "modal" href = "#EditSwitch" style = "margin:10px;"><h4>'+SwitchID+'</h4></a>');
								for(var i = 0; i< SwitchNum;i++){
									if($('#Device'+i).val() != ''){
										$('#roomArea a').last().append('<span class="label label-info">'+$('#Device'+i).val()+'</span>&nbsp;');
									}
								}
								var roomID = $('#roomArea').attr('roomID');
								var NumOfSwitches = parseInt($('#room'+roomID+" h3").html()) + 1;
								$('#room'+roomID+" h3").html(NumOfSwitches);								
	 							$('#closeAddSwitch').trigger('click');
 							}else{
 								alert(output);
 							}
 						}
 					});
 				}
 				return false;
 			});
 			
 			$('#closeAddSwitch').live('click',function(){
 				$('div.Devices').remove();
 				$('#newSwitch').attr('value','');
 				$('#numOfDevices').attr('value','0');
 			});
 			
 			$("#numOfDevices").change(function(){
 				if($(this).attr('value') > 0){
 					var numOfDevices = $(this).attr('value');
 					$('.AddSwitchForm .Devices').remove();
 					for(var i = 0; i < $(this).attr('value');i++){
 						$(this).parent().parent().parent().append('<div class="control-group Devices"><label class="control-label" for="Device'+i+'">switch'+(i+1)+':</label><div class="controls"><input type="text" id="Device'+i+'" placeholder = "name" style = "width:200px;"></div></div>');
 					}
 				}
 			});

			$('#modifyRoomName').change(function(){
				var RoomName = $('input#EditRoomName').val();
				if(RoomName == ""){
					$('#modifyRoomName').addClass('error');
					$('#modifyRoomName span').show();	
				}else{
					$('#modifyRoomName').removeClass('error');
					$('#modifyRoomName span').hide();
				}
			});

			$('#confirmEditRoom').live('click',function(){
				var RoomName = $('input#EditRoomName').val();
				var roomID = $('#roomArea').attr('roomID');
				if(RoomName == ""){
					$('#modifyRoomName').addClass('error');
					$('#modifyRoomName span').show();	
				}else{
					$('#modifyRoomName').removeClass('error');
					$('#modifyRoomName span').hide();
					$.ajax({
						url:'<?=site_url('desktop/user/editRoom')?>/' + RoomName,
						data:{room_id:roomID,name:RoomName},
						type:"POST",
						success:function(output){
							if(output == "success"){
								var NumOfSwitches = parseInt($('#room'+roomID+" h3").html());
								$('#room'+roomID).remove();
        						$('.tab-pane.active div .addRoom').before('<a id = "room'+roomID+'" onclick = "getRoomInfo(\''+roomID+'\')" " type = "button" class = "btn btn-info" href = "#" name = "'+RoomName+'" style = "margin:10px;width:117px;height:80px;">'+RoomName+'<h3>0</h3></a>');								
								$('#roomArea span').first().html('Room: '+RoomName);						
								$('#room'+roomID+" h3").html(NumOfSwitches);
								$('#roomArea').attr('name',RoomName);
								$('#closeEditRoom').trigger('click');
							}
						}
					});	
				}
				return false;
			});

 			$('#confirmEditSiwtch').live('click',function(){
 				var SwitchID = $('#editSwitchID').html();
 				var portNum = $('#editNumOfPorts').html();
 				var data = 'switch_id='+ SwitchID;
				for(var i = 1;i <= portNum;i++){
					data = data + "&port"+i+"="+ $('#EditDevice'+i).val();
				}
				$.ajax({
					url:'<?=site_url("desktop/user/editSwitch")?>',
					data:data,
					type:"POST",
					success:function(output){
						if(output == "success"){
							$("#"+SwitchID).remove();
							$('#roomArea').append('<a id = "'+SwitchID+'" onclick = "EditSwitchInfo(\''+SwitchID+'\')" type = "button" class = "btn btn-warning" data-toggle = "modal" href = "#EditSwitch" style = "margin:10px;"><h4>'+SwitchID+'</h4></a>');
							for(var i = 1; i<= portNum;i++){
									if($('#EditDevice'+i).val() != ''){
									$('#roomArea a').last().append('<span class="label label-info">'+$('#EditDevice'+i).val()+'</span>&nbsp;');
								}
							}
 							$('#closeEditSwitch').trigger('click');
						}
					}
				});
				return false;
 			});
 			
 			$('#confirmDeleteOffice').click(function(){
 				var office_id = $('.officelist.active').attr('id').replace('office','');
 				$.ajax({
					url:'<?=site_url('desktop/user/deleteOffice')?>/' + office_id,
					type:"POST",
					success:function(output){
						if(output == "success"){
							window.location.href = '<?=site_url('desktop/user/configureOffice')?>';
						}
					}
				});
				return false;
 			});
 			
 			$('#confirmDeleteFloor').click(function(){
 				var floor_id = $('.tab-pane.active').attr('id').replace('floor','');
 				$.ajax({
					url:'<?=site_url('desktop/user/deleteFloor')?>/'+floor_id,
					type:"POST",
					success:function(output){
							if(output == "success"){
							$('#closeDeleteFloor').trigger('click');
							window.location.href = '<?=site_url('desktop/user/configureOffice/'.$currentOffice)?>';
						}
					}
				});	
				return false;
 			});
 			
 			$('#confirmDeleteRoom').click(function(){
 				var roomID = $('#roomArea').attr('roomID');
 				deleteRoom(roomID);
 				return false;		
 			});
 			
 			$('#deleteSwitch').click(function(){
 				var SwitchID = $('#editSwitchID').html();		
 				deleteSwitch(SwitchID);
 				return false;
 			});
 			
 			var width = $('.span2').css('width');
 			$('.offices').css('max-width',width);
 			
 			$("#logout").click(function(){
     			window.location.href = '<?=site_url("desktop/user/logout")?>';
     		});
        });

        function updateDeleteRoomInfo(roomName){
        	$('#DeleteRoom .modal-body').html('Are you sure you want to delete "'+roomName+'"?');
        }
        
		function deleteSwitch(switch_id){
			$.ajax({
				url:'<?=site_url('desktop/user/deleteSwitch')?>/'+switch_id,
				type:"POST",
				success:function(output){
					if(output != "no such switch"){
						roomName = $('#room'+output.room_id).attr('name');
						$('#room'+output.room_id).html(roomName+'<h3>'+output.no_switches+'</h3>');
						$('#'+switch_id).remove();
						$('#closeEditSwitch').trigger('click');
					}
				}
			});
		}
		
		function deleteRoom(room_id){	
			$.ajax({	
				url:'<?=site_url("desktop/user/deleteRoom")?>/'+room_id,
				type:"POST",
				success:function(output){
					if(output == "success"){
						$("#roomArea").remove();
						$('#room'+room_id).remove();
						$('#closeDeleteRoom').trigger('click');
					}
				}		
			});
		}
						 			 			 			 			        
        function EditSwitchInfo(SwitchID){
        	console.log('SwitchID '+SwitchID);
    		$.ajax({
				url:'<?=site_url('desktop/user/getSwitch')?>/'+SwitchID,
				type:"POST",
				datatype:'json',
				success:function(output){
					$('#editSwitchID').html(SwitchID);
					if(output != "no such switch"){		
						$('#editNumOfPorts').html(output.no_ports);
						console.log($('form.EditSwitchForm'));
						$('form.EditSwitchForm').empty();
						$.each(output,function(index,element){
							if(index != 'no_ports'){
								$('form.EditSwitchForm').append('<div class="control-group Devices"><label class="control-label" for="EditDevice'+index+'">switch'+index+':</label><div class="controls"><input type="text" id="EditDevice'+index+'" value = "'+element+'" style = "width:200px;"></div></div>');
							}
						});
					}
				}
			});
        }
        
        function EditRoomInfo(room){
        	console.log($('input#EditRoomName'));
        	$('input#EditRoomName').attr('value',$('#roomArea').attr('name'));
        }
        
        function getRoomInfo(room_id){
        		console.log(room_id);
        		var roomID = room_id;
				var roomName = $('#room'+room_id).attr('name');
				if($('#roomArea').length > 0){
					$('#roomArea').remove();
				}
				$('.tab-pane.active').append(
					'<div roomID = '+roomID+' name = "'+roomName+'" id = "roomArea" class="well well-small">'+
						'<div style = "margin:20px;">'+
							'<span style = "font-size:23px;">Room: '+roomName+'</span>'+
							'<div class ="pull-right">'+
								'<a type = "button" class = "btn-small" href = "#AddNewSwitch" data-toggle = "modal">'+
									'<img src = "<?=ASSEST_URL?>desktop/img/glyphicons_190_circle_plus.png" style = "width:18px;height:18px;">'+
								'</a>'+
								'<a type = "button" class = "btn-small" onclick = "EditRoomInfo(\''+roomID+'\')" href = "#EditRoom" data-toggle = "modal">'+
									'<img src = "<?=ASSEST_URL?>desktop/img/glyphicons_280_settings.png" style = "width:18px;height:18px;">'+
								'</a>'+
								'<a type = "button" class = "btn-small" onclick = "updateDeleteRoomInfo((\''+roomName+'\'))" href = "#DeleteRoom" data-toggle = "modal">'+
									'<img src = "<?=ASSEST_URL?>desktop/img/glyphicons_197_remove.png" style = "width:18px;height:18px;">'+
								'</a>'+
							'</div>'+
						'</div>'+
						'<hr/>'+
					'</div>');
				$.ajax({
					url:'<?=site_url("desktop/user/getRoomInfo")?>/'+roomID,
					type:"POST",
					datatype:"json",
					success:function(output){
						console.log(output);
						if(!$.isEmptyObject(output)){													
							$.each(output,function(index,element){
								$('#roomArea').append('<a id = "'+index+'" type = "button" onclick = "EditSwitchInfo(\''+index+'\')"  class = "btn btn-warning" data-toggle = "modal" href = "#EditSwitch" style = "margin:10px;"><h4>'+index+'</h4></a>');
								$.each(element,function(index,element){
									if(index != "no_ports"){
										$('#roomArea a').last().append('<span class="label label-info">'+element.name+'</span>&nbsp;');
									}
								});
							}); 												
						}
					}
				});	
				return false;
        	}
 	</script>
 	<title>Create Or Configure Office</title>
	</head>

	<body style = "padding-top:20px;">		
		<div class="navbar navbar-fixed-top" style = "line-height: 20px;">
            <div class="navbar-inner" style = "min-height:20px;height:36px;">
                <div class="container">
                  <a class="brand" href="#"><img class="pull-left" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat></a>
                        <ul class="nav">
                            <li><a href="<?php echo site_url("desktop/user/index");?>" style = "font-size:20px;text-transform: none;">Dashboard</a></li>
                            <li class = "active"><a href="<?php echo site_url("desktop/user/configureOffice");?>" style = "font-size:20px;text-transform: none;">Configure Office</a></li>
                            <li><a href="<?php echo site_url("desktop/user/ManageEmployee");?>" style = "font-size:20px;text-transform: none;">Manage Office</a></li>
                            <li><a href="<?php echo site_url("desktop/user/managerControlRoom");?>" style = "font-size:20px;text-transform: none;">Manage Room</a></li>
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
					  <li style = "margin-left:-50px;"><a style="width: 158px; padding-left: 48px;" class = "officelist" name = '<?=$office["name"]?>' id = "office<?=$office["id"]?>" href = "<?=site_url("desktop/user/configureOffice/".$office["id"])?>"><i class = "icon-chevron-right pull-right"></i><?=$office["name"]?></a></li>
					<? } ?>
					</ul>
					<a href="#createNewOffice" id = "createNewOfficeButton" type="button" class="btn btn-success btn-block" data-toggle="modal" style = "margin-left:-50px;width:220px;">Create a New Office</a>
				</div>
				
				
				<div class = "span10">
				<? if (count($office) > 0) { ?>
					<span style = "font-size:28px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif;">Basic Information</span>
					<a  rel="tooltip" title="Remove current office" href="#deleteOffice" role="button" class="pull-right" data-toggle="modal" style = "margin-right:30px;"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_197_remove.png" style = "width:18px;height:18px;"></a>			
						<dl class="dl-horizontal">
						  <dt>Office Name:</dt>
						  <dd><?=$offices[$currentOffice]["name"]?></dd>
						  <dt>Description:</dt>
						  <dd><?=$offices[$currentOffice]["description"]?></dd>
						</dl>
						<hr/>
					<h3 style = "text-transform: none;">Floor Plan</h3>
					<a href="#deleteFloor" rel="tooltip" title="Remove the current floor" role="button" class="pull-right btn-small" data-toggle="modal" style = "margin-right:25px;<? if (count($floors) == 1) echo "display:none";?>"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_197_remove.png" style = "width:18px;height:18px;"></a>		
					<a href = "#" id = "addFloor" rel="tooltip" title="Add a new floor" role="button" class="pull-right btn-small" style = "<? if (count($floors) == 1) echo "margin-right:25px;";?>"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_190_circle_plus.png" style = "width:18px;height:18px;"></a>		
					<ul class="nav nav-tabs">
					<? $count = 1; foreach($floors as $floorId=>$floor) { ?>
					  <li id = "floorTab<?=$floorId?>"><a href="#floor<?=$floorId?>" data-toggle="tab">Floor <?=$count?></a></li>
					  <? $count++;} ?>
					</ul>
						
					<div class="tab-content">
					  <? foreach($floors as $floorId=>$rooms) { ?>
					  <div class="tab-pane" id="floor<?=$floorId?>" >
					  <label>Click add new room at the right to add rooms into each floor of the office</label>
						<div class = "well well-small">
							<? foreach($rooms as $room) {
								if ($room['type'] == '0') {?>
									<a type = "button" id = 'room<?=$room["id"]?>' notify = "<?=$room['notify']?>" roomType = "<?=$room['type']?>" onclick = "getRoomInfo('<?=$room["id"]?>')" class = "btn btn-info" href = "#" name = '<?=$room["name"]?>' style = "margin:10px;width:117px;height:80px;"><?=$room["name"]?><h3><?=$room["no_switches"]?></h3></a>
								<? } 
								else if ($room['type'] == '1') {?>
									<a type = "button" id = 'room<?=$room["id"]?>' notify = "<?=$room['notify']?>" roomType = "<?=$room['type']?>" onclick = "getRoomInfo('<?=$room["id"]?>')" class = "btn btn-success" href = "#" name = '<?=$room["name"]?>' style = "margin:10px;width:117px;height:80px;"><?=$room["name"]?><h3><?=$room["no_switches"]?></h3></a>									
								<? }
							 } ?>
							 <a type = "button" class = "btn btn-success addRoom" href = "#AddNewRoom" style = "margin:10px;width:117px;height:80px;" data-toggle = "modal">Add A New Room<br/><br/><i class = "icon-plus"></i></a>
						</div>
					  </div>
					  <? } ?>					  
					</div>
					<? } else {?>
					</br>
					<span style = "font-size:28px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif;">Congratulations! You can start using Clickee now</span>
					</br>You don't have any Office yet. Click Create a New Office to start adding floors, rooms and Clickee switches.
					<?}?>
				</div>
				
		</div>
	</div>
		
		<div class="modal hide fade" id="deleteOffice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel"><strong>Delete Office</strong></h3>
			  </div>
			  <div class="modal-body">
			  	Are you sure you want to delete the selected office?
			  </div>
			  <div class="modal-footer">
			  	<button id = "confirmDeleteOffice" class="btn btn-primary">Confirm</button>
			    <button id = "closeDeleteOffice" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="deleteFloor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel"><strong>Delete Floor</strong></h3>
			  </div>
			  <div class="modal-body">
			  	Are you sure you want to delete the selected floor?
			  </div>
			  <div class="modal-footer">
			  	<button id = "confirmDeleteFloor" class="btn btn-primary">Confirm</button>
			    <button id = "closeDeleteFloor" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="deleteFloor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel"><strong>Delete Floor</strong></h3>
			  </div>
			  <div class="modal-body">
			  	Are you sure you want to delete the selected floor?
			  </div>
			  <div class="modal-footer">
			  	<button id = "confirmDeleteFloor" class="btn btn-primary">Confirm</button>
			    <button id = "closeDeleteFloor" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="DeleteRoom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel"><strong>Delete Room</strong></h3>
			  </div>
			  <div class="modal-body">
			  </div>
			  <div class="modal-footer">
			  	<button id = "confirmDeleteRoom" class="btn btn-primary">Confirm</button>
			    <button id = "closeDeleteRoom" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="createNewOffice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel"><strong>Create Office</strong></h3>
			  </div>
			  
			  			  <div class="modal-body">
			  	<form class = "form-horizontal">
					<div class="control-group" id = "officeName">
					    <label class="control-label" for="inputOffice">Office Name:</label>
					    <div class="controls">
					      <input type="text" id="inputOffice" placeholder="My office" style = "width:300px;" />
					 	  <span class = "help-block" style = "display:none;">Please enter a name for your office.</span>
					    </div>
					  </div>
					  					  
					  <div class="control-group" id = "floorNum">
					    <label class="control-label" for = "floorNumber">Number of Floors:</label>
					    <div class="controls">
					      <input type="number" id="floorNumber" placeholder="1,2,3,..." style = "width:300px;"/>
					      <span class = "help-block" style = "display:none;">Please enter the number of floors in your office.</span>
					    </div>
					  </div>
					  
					  <div class="control-group" id = "officeDesc">
					    <label class="control-label" for="inputDesc">Description:</label>
					    <div class="controls">
					      <textarea type="text" id="inputDesc" placeholder="Building name, Street name, Office number..." style = "width:300px;height:200px;"></textarea>
					      <span class = "help-block" style = "display:none;">Please enter a description.</span>
					    </div>
					  </div>
				  </form>
			  </div>
			  
			  <div class="modal-footer">
			  	<button id = "submitOfficeInfo" class="btn btn-primary">Create</button>
			    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="AddNewRoom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel"><strong>Add A New Room</strong></h3>
			  </div>
			  
			  <div class="modal-body">
			  	<form class = "form-horizontal AddRoomForm">
					<div class="control-group" id = "roomName">
					    <label class="control-label" for="inputRoomName">Room Name:</label>
					    <div class="controls">
					      <input type="text" id="inputRoomName" placeholder="Name" style = "width:200px;" />
					      <span class = "help-block" style = "display:none;">Please enter a name for your room</span>
					    </div>
					  </div>
				  </form>
			  </div>
			  
			  <div class="modal-footer">
			  	<button id = "confirmRoomInfo" class="btn btn-primary">Add</button>
			    <button id = "closeconfirmRoomInfo" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="AddNewSwitch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel"><strong>Add A New Clickee</strong></h3>
			  </div>
			  
			  <div class="modal-body">
			  	<form class = "form-horizontal AddSwitchForm">
					<label>Enter the switch ID to add the switch into your current room</label>
			  		<div class="control-group" id = "addSwitchID">
					    <label class="control-label" for="newSwitch">Clickee ID:</label>
					    <div class="controls">
					      <input type="text" id="newSwitch"  style = "width:200px;">
					      <span class = "help-block" style = "display:none;">Wrong Clickee ID.</span>
						  <a href="#" rel="tooltip" title="The switch ID can be found at the side of each Clickee switch">
							<img src = "<?=ASSEST_URL?>desktop/img/glyphicons_194_circle_question_mark.png" style = "width:18px;height:18px;padding-left:10px">
						  </a>
					    </div>
					    <div id = "SwitchIDAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:263px;display:none;"><strong>Warning!</strong>&nbsp;Wrong swtich ID.</div>
					  </div>
			  		<div class="control-group" id = "addNumOfDevices">
					    <label class="control-label" for="numOfDevices">Num Of Switches:</label>
					    <div class="controls">
					      <input type="number" id="numOfDevices" value = "0" style = "width:200px;">
					      <span class = "help-block" style = "display:none;">Input incorrectly!</span>
					    </div>
					    <div id = "SwitchNumAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:263px;display:none;"><strong>Warning!</strong>&nbsp;Input incorrectly.</div>
					  </div>
				  </form>
			  </div>
			  
			  <div class="modal-footer">
			  	<button id = "confirmSwitchInfo" class="btn btn-primary">Add</button>
			    <button id = "closeAddSwitch" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="EditSwitch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel"><strong>Clickee Setting</strong></h3>
			  </div>
			  
			  <div class="modal-body">
			  	<dl class = "dl-horizontal" style = "margin-left:25px;">
		  			<dt style = "margin-bottom:10px;">Clickee ID:</dt>
		  			<dd id = "editSwitchID" style = "margin-bottom:10px;"></dd>
		  			<dt>Num Of Switches:</dt>
		  			<dd id = "editNumOfPorts"></dd>
			  	</dl>
			  	<form class = "form-horizontal EditSwitchForm">
				</form>
			  </div>
			  
			  <div class="modal-footer">
			  	<button id = "confirmEditSiwtch" class="btn btn-primary">confirm</button>
			  	<button id = "closeEditSwitch" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  	<button id = "deleteSwitch" class="btn btn-danger">Delete</button>
			  </div>
		</div>
		<div class="modal hide fade" id="EditRoom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel"><strong>Room Setting</strong></h3>
			  </div>
			  
			  <div class="modal-body">
			  	<form class = "form-horizontal EditRoomForm">
			  		<div class="control-group" id = "modifyRoomName">
					    <label class="control-label" for="EditRoomName">Room Name:</label>
					    <div class="controls">
					      <input type="text" id="EditRoomName" style = "width:200px;">
					      <span class = "help-block" style = "display:none;">Input incorrectly!</span>
					    </div>
					</div>
				</form>
			  </div>
			  
			  <div class="modal-footer">
			  	<button id = "confirmEditRoom" class="btn btn-primary">confirm</button>
			  	<button id = "closeEditRoom" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
	</body>
</html>
