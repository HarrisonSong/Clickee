<?php
	include 'header.php';
?>
	<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/ConfigureOffice.css">
	<script>   
		var countOffice = <? if (count($offices) > 0) { echo "1"; } else { echo "0";}; ?>;
		var currentOffice = "<?=$currentOffice?>";
		
        $(document).ready(function(){
			$('#newRoomHelp').popover();
			$("#ManagerNavBar li:eq(1)").addClass('active');		
			
        	if(!$('#office<?=$currentOffice?>').hasClass('active')){
        		$('#office<?=$currentOffice?>').addClass('active');
        	}
        	if(!$('.span12 ul li').first().hasClass('active')){
        		$('.span12 ul li').first().addClass('active');
        		$('.span12 ul li').first().addClass('active');
        	}
			if(!$('.span12 div.tab-content div.tab-pane').first().hasClass('active')){
        		$('.span12 div.tab-content div.tab-pane').first().addClass('active');
        	}
        	
			/*
        	if($('.officelist').length == 0){
        		console.log('inside');
        		$('#createNewOfficeButton').trigger('click');
        	}
			*/
        	
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
	 						window.location.href = '<?=site_url('desktop/manager/configureOffice')?>/'+output;
	 						$('.addRoom').first().trigger('click');
	 					}
 					});
 				}
 				return false; 				
 			});
 			
			/*
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
			*/
 			
 			$('#addFloor').click(function(){
 				$.ajax({
 					url:"<?=site_url('desktop/user/addFloor/'.$currentOffice)?>",
 					type:"POST",
 					success:function(output){
 						if(!$.isEmptyObject(output)){
 							window.location.href = "<?php echo site_url("desktop/manager/configureOffice/".$currentOffice);?>";
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
 							$('.tab-pane.active div .addRoom').before('<a id = "room'+output+'" onclick = "getRoomInfo(\''+output+'\')" " type = "button" class = "btn btn-info privateRoom" href = "#" name = "'+roomName+'" style = "background: url(\'<?=ASSEST_URL?>desktop/img/roomIcon.png\') center center no-repeat;"><p></br>'+roomName+'</p></a>');
 							$('#closeconfirmRoomInfo').trigger('click');
 							$('#room'+output).trigger('click');
 							console.log($('#roomArea'));
 							$('#addSwitchInRoom').trigger('click');
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
 				if(SwitchNum == "" || SwitchNum <= 0 || SwitchNum > 3){
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
 				if(SwitchNum == "" || SwitchNum <= 0 || SwitchNum > 3){
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
	 							$('#addSwitchInRoom').before(
	 								'<a id = "'+SwitchID+'" onclick = "EditSwitchInfo(\''+SwitchID+'\')" type = "button" class = "btn switches" data-toggle = "modal" href = "#EditSwitch">'+
										'<div class = "clickeeTitle pull-left"><img class="clickeeLogo" src = "<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png"><span class = "clickeeID">'+'  '+SwitchID+'</span></div>'+
										'<table class = "table devicesTable">'+
										'<thead><th>Devices in control</th></thead>'+
										'<tbody></tbody></table>'+
										'</a>');
	 							if($('#roomArea a.switches').length == 6){
	 								$('#addSwitchInRoom').hide();
	 							}
								for(var i = 0; i< SwitchNum;i++){
									if($('#Device'+i).val() != ''){
										$('#'+SwitchID+" table tbody").append('<tr><td>'+$('#Device'+i).val()+'</td></tr>');
									}
								}
								var roomID = $('#roomArea').attr('roomID');
	 							$('#closeAddSwitch').trigger('click');
 							}else{
 								alert(output);
 							}
 						}
 					});
 				}
 				return false;
 			});
 			
			$('#editOfficeButton').click(function(){
				console.log('hello');
				//$('#editOfficeName').attr('value',office_name);
				//$('#editOfficeDesc').attr('value',office_description);
				$('#editOffice').modal('show');
			});
			
			$('#confirmEditOffice').click(function(){
				var office_name = $('#editOfficeName').val();
				var office_desc = $('#editOfficeDesc').val();
				var proceed = true;
				if(office_name == ""){
					$('#modifyOfficeName').addClass('error');
					$('#modifyOfficeName span').show();
					proceed = false;
				}else{
					$('#modifyOfficeName').removeClass('error');
					$('#modifyOfficeName span').hide();
				}
				if(office_desc == ""){
					$('#modifyOfficeDesc').addClass('error');
					$('#modifyOfficeDesc span').show();
					proceed = false;
				}else{
					$('#modifyOfficeDesc').removeClass('error');
					$('#modifyOfficeDesc span').hide();
				}
				if(proceed){
					$.ajax({
						url:'<?=site_url('desktop/user/editOffice')?>',
						data:{office_id:currentOffice,name:office_name,description:office_desc},
						type:'POST',
						success:function(output){
							console.log(output);
							if(output == "success"){
									$('#officeInfo h1').html(office_name);
									$('#officeInfo h3').html('<p style="text-transform:none">'+office_desc+'</p>');
									$('#editOffice').modal('hide');
							}
						}
					});
				}
			});
			
 			$('#closeAddSwitch').live('click',function(){
 				$('div.Devices').remove();
 				$('#newSwitch').attr('value','');
 				$('#numOfDevices').attr('value','0');
 			});
 			
 			$("#numOfDevices").change(function(){
				if (($(this).val()>0)&&($(this).val() <= 3)){
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
					$('#QRroomname h3').html($('input#EditRoomName').val());
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
								/*var NumOfSwitches = parseInt($('#room'+roomID+" h3").html());
								$('#room'+roomID).remove();
        						$('.tab-pane.active div .addRoom').before('<a id = "room'+roomID+'" onclick = "getRoomInfo(\''+roomID+'\')" " type = "button" class = "btn btn-info privateRoom" href = "#" name = "'+RoomName+'" style = "background: url(\'<?=ASSEST_URL?>desktop/img/roomIcon.png\') center center no-repeat;">'+RoomName+'<h3>0</h3></a>');								
								*/
								$('#room'+roomID+' p').html('</br>'+RoomName);
								$('#roomNameArea').html(RoomName);						
								$('#roomArea').attr('name',RoomName);
								$('#closeEditRoom').trigger('click');
							}
							$(window).scrollTop($(window).height()); 
						}
					});	
				}
				return false;
			});
			
			$('#printQR').live('click',function(){
				var print_window = window.open(),
				print_document = $('#QRtable').clone();
                
				print_window.document.open();
				print_window.document.write(print_document.html());
				print_window.document.close();
				print_window.print();
				print_window.close();
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
						$.each(output,function(index,element){
	 							//console.log(element);
								$.each(element,function(key,value){
									if(key != "no_ports"){
										$('#'+index+' table').append('<tr><td>'+value.name+'</td></tr>');
									}
								});
							});
					
					
						if(output == "success"){
							$("#"+SwitchID).empty();							
							$('#'+SwitchID).append(
							'<div class = "clickeeTitle pull-left"><img class="clickeeLogo" src = "<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png"><span class = "clickeeID">'+'  '+SwitchID+'</span></div><hr>'+
							'<table class = "table devicesTable">'+
							'<thead><th>Devices in control</th></thead>'+
							'</table>');
							for(var i = 1; i<= portNum;i++){
									if($('#EditDevice'+i).val() != ''){
									$('#'+SwitchID+' table').append('<tr><td>'+$('#EditDevice'+i).val()+'</td></tr>');
								}
							}
 							$('#closeEditSwitch').trigger('click');
						}
						$(window).scrollTop($(window).height()); 
					}
				});
				return false;
 			});
 			
 			$('#confirmDeleteOffice').click(function(){
 				//var office_id = $('.officelist.active').attr('id').replace('office','');
				console.log(countOffice);
				if (countOffice > 0){
					var office_id = "<?=$currentOffice?>";
					$.ajax({
						url:'<?=site_url('desktop/user/deleteOffice')?>/' + office_id,
						type:"POST",
						success:function(output){
							if(output == "success"){
								window.location.href = '<?=site_url('desktop/manager/configureOffice')?>';
							}
						}
					});
				}
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
							window.location.href = '<?=site_url('desktop/manager/configureOffice/'.$currentOffice)?>';
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
						if($('#roomArea a.switches').length == 6){
							$('#addSwitchInRoom').show();
						}
						$('#'+switch_id).remove();
						$('#closeEditSwitch').trigger('click');
					}
					$(window).scrollTop($(window).height()); 
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
						$("#RoomBodyFooter").remove();
						$("#roomGeneralGuide").remove();
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
			$('#QRroomname h3').html($('#roomArea').attr('name'));
			$('#QRImage img').attr('src','http://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=clickee.drekee.com%2Fqrcode.php%3Fid%3D'+$('#roomArea').attr('roomID'));
        }
        
        function getRoomInfo(room_id){
        		console.log(room_id);
        		var roomID = room_id;
				var roomName = $('#room'+room_id).attr('name');
				var roomType = $('#room'+room_id).attr('roomtype');
				if (roomType == 1) roomType = 'Common Room: Employees can book to use electricity in common room. Go to Manage room to see the booking items or change the room type';
				else roomType = 'Private Room: permission to use private room is only given to selected employees. Go to Manage room to add employees into this room or change the room type.'
				
				if($('#roomArea').length > 0){
					$('#roomArea').remove();
				}
				
				if($('#RoomBodyFooter').length > 0){
					$('#RoomBodyFooter').remove();
				}
				
				$('.tab-pane.active').append(
					'<div roomID = '+roomID+' name = "'+roomName+'" id = "roomArea" class="well well-small">'+
						'<div id = "roomLabel">'+
							'<span><a type = "button" class = "btn-small pull-left" onclick = "EditRoomInfo(\''+roomID+'\')" href = "#EditRoom" id = "roomButtonEdit" data-toggle = "modal">'+
									'<img src = "<?=ASSEST_URL?>desktop/img/glyphicons_258_qrcode.png" center center style = "width:18px;height:18px;"></a>'+
							'<font id = "roomNameArea">'+roomName+ '</font></span>'+
							'<div class ="pull-right">'+
								'<a href="#newRoomHelp" type = "button" class="pul-right btn-small btn-help" id = "roomButtonHelp" data-toggle = "modal"></a>'+
									//'<img src = "<?=ASSEST_URL?>desktop/img/glyphicons_194_circle_question_mark.png" center center style = "width:18px;height:18px;"></a>'+
								'<a type = "button" class = "btn-small" onclick = "updateDeleteRoomInfo((\''+roomName+'\'))" href = "#DeleteRoom" id = "roomButtonDelete" data-toggle = "modal" style="margin-right:30px">'+
									'<img src = "<?=ASSEST_URL?>desktop/img/glyphicons_197_remove.png" center center style = "width:18px;height:18px;">'+
								'</a>'+
							'</div>'+
						'</div><br>'+
						'<a type = "button" id = "addSwitchInRoom" class = "btn btn-success" href = "#AddNewSwitch" data-toggle = "modal" style = "margin:10px;width:300px;height:180px;font-size: 20px;font-family: Open Sans"><p></br><br/><br/>Add A New Clickee</p></a>'+
					'</div>'+
					'<div id="RoomBodyFooter" style="background: url(<?=ASSEST_URL?>desktop/img/roomBodyFooter.png) top left no-repeat; background-size:contain"></div>');
					$('#roomButtonHelp').tooltip({
						title:'Add or remove the Clickees for the current room'
					});
					$('#roomButtonEdit').tooltip({
						title:'Change the details of the room and print its QR Code'
					});
					$('#roomButtonDelete').tooltip({
						title:'Remove the current room'
					});
				$.ajax({
					url:'<?=site_url("desktop/user/getRoomInfo")?>/'+roomID,
					type:"POST",
					datatype:"json",
					success:function(output){
						console.log(output);
						if(!$.isEmptyObject(output)){
							$('#roomGeneralGuide').remove();
							$('#guideContainer').append('<div id="roomGeneralGuide" class="alert"><p>To manage devices and allocate employee permissions, go to Manage Office<br>'+
							'To start using Clickee, go to Control Page</p></div>');						
							
							$.each(output,function(index,element){
								$('#addSwitchInRoom').before(
									'<a id = "'+index+'" type = "button" onclick = "EditSwitchInfo(\''+index+'\')"  class = "btn switches" data-toggle = "modal" href = "#EditSwitch">'+
										'<div class = "clickeeTitle pull-left"><img class="clickeeLogo" src = "<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png"><span class = "clickeeID">'+'  '+index+'</span></div><hr>'+
										'<table class = "table devicesTable">'+
										'<thead><th>Devices in control</th></thead>'+
										'</table></a>');
								if($('#roomArea a.switches').length == 6){
	 								$('#addSwitchInRoom').hide();
	 							}
	 							//console.log(element);
								$.each(element,function(key,value){
									if(key != "no_ports"){
										$('#'+index+' table').append('<tr><td>'+value.name+'</td></tr>');
									}
								});
							}); 												
						}
						else{
							$('#roomGeneralGuide').remove();
							$('#guideContainer').append('<div id="roomGeneralGuide" class="alert"><p>Add a Clickee into a room by keying in the ID at the side of the Clickee. Also add in the name of the devices connected to the respective Clickee.</p></div>');				
						}
						$(window).scrollTop($(window).height()); 
					}
				});	
				return false;
        	}
 	</script>
 	<title>Create Or Configure Office</title>
	</head>

	<body>		
		<?php
			include 'ManagerNavBar.php';
		?>
		
		<div class = "container">			
			<div class = "row-fluid">				
				<div id="main" class = "span12">
				<? if (count($offices) > 0) { ?>
					<a  rel="tooltip" title="Remove current office" href="#deleteOffice" role="button" class="pull-right" data-toggle="modal" style = "margin-right:30px;"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_197_remove.png" style = "width:18px;height:18px;"></a>			
					<a  rel="tooltip" title="Edit current office" id = "editOfficeButton" role="button" class="pull-right" data-toggle="modal" style = "margin-right:15px;"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_280_settings.png" style = "width:18px;height:18px;"></a>								
					<div id="officeInfo">
						<h1><?=nl2br($offices[$currentOffice]["name"])?></h1>
						<br>
						<h4>
						<p style="text-transform:none">
							<?=nl2br($offices[$currentOffice]["description"])?>
						</p>
						</h4>
						<br>
					</div>
					<br>
					<a href="#newRoomHelp" role="button" class="pull-right btn-small btn-help" data-toggle="modal" style="margin-right:20px"></a>
					<a href="#deleteFloor" title="Remove the current floor" role="button" class="pull-right btn-small" data-toggle="modal" style = "<? if (count($floors) == 1) echo "display:none";?>"><img rel="tooltip" src = "<?=ASSEST_URL?>desktop/img/glyphicons_197_remove.png" style = "width:18px;height:18px;"></a>		
					<a href = "#" id = "addFloor" rel="tooltip" title="Add a new floor" role="button" class="pull-right btn-small" style = "<? if (count($floors) == 1) echo "margin-right:25px;";?>"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_190_circle_plus.png" style = "width:18px;height:18px;"></a>		
					<ul class="nav nav-tabs">
					<? $count = 1; foreach($floors as $floorId=>$floor) { ?>
					  <li id = "floorTab<?=$floorId?>"><a href="#floor<?=$floorId?>" data-toggle="tab">Floor <?=$count?></a></li>
					  <? $count++;} ?>
					</ul>
						
					<div class="tab-content">
					  <? foreach($floors as $floorId=>$rooms) { ?>
					  <div class="tab-pane" id="floor<?=$floorId?>" >
					  <label>Add new rooms into the floor before adding Clickees</label>
						<div class = "well well-small iconFloorMain" style="background-color: whitesmoke;">
						<div class="iconFloorHeader"></div>						
							<? foreach($rooms as $room) {
								if ($room['type'] == '0') {?>
									<a type = "button" id = 'room<?=$room["id"]?>' notify = "<?=$room['notify']?>" roomType = "<?=$room['type']?>" onclick = "getRoomInfo('<?=$room["id"]?>')" class = "btn btn-info privateRoom" href = "#" name = '<?=$room["name"]?>'><p></br><?=$room["name"]?></p></a>
								<? } 
								else if ($room['type'] == '1') {?>
									<a type = "button" id = 'room<?=$room["id"]?>' notify = "<?=$room['notify']?>" roomType = "<?=$room['type']?>" onclick = "getRoomInfo('<?=$room["id"]?>')" class = "btn btn-success commonRoom" href = "#" name = '<?=$room["name"]?>'><p></br><?=$room["name"]?></p></a>									
								<? }
							 } ?>
							 <!--<a type = "button" class = "btn btn-success addRoom" href = "#AddNewRoom" style = "background: url('<?=ASSEST_URL?>desktop/img/roomIconNew.png') center center no-repeat;" data-toggle = "modal">-->
							 <a type = "button" class = "btn btn-success addRoom" href = "#AddNewRoom" data-toggle = "modal">
								</br>
								<p>Add A New Room</p>
							 </a>
						<div class="iconFloorFooter"></div>
						</div>
						<div id="guideContainer"></div>
					  </div>
					  <? } ?>					  
					</div>
					<? } else {?>
					</br>
					<span style = "font-size:28px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif;">Congratulations! You can start using Clickee now</span></br>
					</br><font style="font-size:18px">Make sure that you have a Clickee with you. </br>
					For installation and set up, click <a href="<?php echo site_url("desktop/user/aboutProduct");?>">HERE</a></font>
					</br>
					<a href="#createNewOffice" id = "createNewOfficeButton" type="button" class="btn btn-primary btn-red" data-toggle="modal">Create a New Office</a>
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
					<br>
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
					    <label class="control-label" for="numOfDevices">Number Of Devices:</label>
					    <div class="controls">
					      <input type="number" id="numOfDevices" value = "0" style = "width:200px;">
					      <span class = "help-block" style = "display:none;">Input incorrectly!</span>
					      <a href="#" rel="tooltip" title="Number of devices that your Clickee controls">
							<img src = "<?=ASSEST_URL?>desktop/img/glyphicons_194_circle_question_mark.png" style = "width:18px;height:18px;padding-left:10px">
						  </a>
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
			    <h3 id="myModalLabel"><strong>Clickee Settings</strong></h3>
			  </div>
			  
			  <div class="modal-body">
				<table class="table">
					<tr>
						<td style="width: 15%"></td>
						<td>Clickee ID:</td>
						<td id = "editSwitchID"></td>
						<td style="width: 15%"></td>
					</tr>
					<tr>
						<td style="width: 15%"></td>
						<td>Number Of Devices:</td>
						<td id = "editNumOfPorts"></td>
						<td style="width: 15%"></td>
					</tr>
				</table>
			  	<form class = "form-horizontal EditSwitchForm">
				</form>
			  </div>
			  
			  <div class="modal-footer">
			  	<button id = "confirmEditSiwtch" class="btn btn-primary">confirm</button>
			  	<button id = "closeEditSwitch" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  	<button id = "deleteSwitch" class="btn btn-danger">Delete</button>
			  </div>
		</div>
		<div class="modal hide fade" id="EditRoom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" roomID="">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel" style = "text-transform:none;"><strong>Room Setting</strong></h3>
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
				<div  id="QRtable">
					<table class="table">
						<tr>
							<td style="width:30%"></td>
							<td style="width: 40%; border:dashed 1px #DDD; border-bottom:none">
								<div id="QRImage" style="margin-left:12%">
									<img src=""></img>
								</div>
							</td>
							<td style="width: 30%"></td>
						</tr>
						<tr>
							<td></td>
							<td id="QRroomname" style='text-align:center; border:dashed 1px #DDD; border-top:none;	font-weight: normal; font-family: "Segoe UI Light","Helvetica Neue","Segoe UI","Segoe WP",sans-serif; '><h4 style='text-transform:none'>Scan this QR Code to book the room</h4><h3></h3></td>
							<td></td>
						</tr>
					</table>
				</div>
			  </div>
			  
			  <div class="modal-footer">
				<button id = "printQR" class="btn btn-success">Print QR</button>	
			  	<button id = "confirmEditRoom" class="btn btn-primary">Confirm</button>
			  	<button id = "closeEditRoom" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
		<div class="modal hide fade" id="editOffice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" roomID="">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel" style = "text-transform:none;"><strong>Edit Office</strong></h3>
			  </div>
			  
			  <div class="modal-body">
			  	<form class = "form-horizontal">
			  		<div class="control-group" id = "modifyOfficeName">
					    <label class="control-label" for="editOfficeName">Office Name:<span class = "help-block" style = "display:none;">*required</span></label>
					    <div class="controls">
					      <input type="text" id="editOfficeName" style = "width:250px;" value="<?=$offices[$currentOffice]["name"]?>">
					      
					    </div>
					</div>
					<div class="control-group" id = "modifyOfficeDesc">
					    <label class="control-label" for="editOfficeDesc">Office Description: <span class = "help-block" style = "display:none;">*required<span></label>
					    <div class="controls">
					      <textarea type="text" id="editOfficeDesc" style = "width:250px;height:150px;"><?=$offices[$currentOffice]["description"]?></textarea>
					      
					    </div>
					</div>
				</form>
			  </div>
			  
			  <div class="modal-footer">
			  	<button id = "confirmEditOffice" class="btn btn-primary">Confirm</button>
			  	<button id = "closeEditOffice" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
	</body>
</html>
