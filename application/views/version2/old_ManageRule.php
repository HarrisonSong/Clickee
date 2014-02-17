<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<title>Manage Rules</title>
		<meta name="description" content="" />
		<meta name="author" content="SONG QIYUE" />

		<meta name="viewport" content="width=device-width; initial-scale=1.0" />

		<link rel="shortcut icon" href="<?=ASSEST_URL?>desktop/favicon.ico" />
		<link rel="apple-touch-icon" href="<?=ASSEST_URL?>desktop/apple-touch-icon.png" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Petit+Formal+Script' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="<?=ASSEST_URL?>desktop/favicon.ico" />
		<link rel="apple-touch-icon" href="<?=ASSEST_URL?>desktop/apple-touch-icon.png" />
        <link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/metro-bootstrap.css">
        <link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/dashboard.css">
        <link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/main.css">
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/timeline.css">
		 <style>
            .officelist.active{
            	color:white;
            	background-color:#08C;
            }
        </style>
        <script src="<?=ASSEST_URL?>desktop/js/vendor/modernizr-2.6.1-respond-1.1.0.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?=ASSEST_URL?>desktop/js/vendor/jquery-1.8.1.min.js"><\/script>')</script>

        <script src="<?=ASSEST_URL?>desktop/js/vendor/bootstrap.min.js"></script>

        <script src="<?=ASSEST_URL?>desktop/js/plugins.js"></script>
        <script src="<?=ASSEST_URL?>desktop/js/main.js"></script>
        <script>
        $(document).ready(function() {
        	console.log($('#office<?=$currentOffice?>'));
        	$('#office<?=$currentOffice?>').addClass('active');
			$('.alert').hide();
			$('#ruleTabs a:first').addClass('active');
			$('#ruleTabs a:first').tab('show');
			//alert($('#ruleTabs a.active').attr('href'));
			/*
			$('#ruleTabs a[data-toggle="tab"]').click(function (e) {
			});
			
			$('#ruleTabs a[data-toggle="tab"]').on('shown', function (e) {
			  //e.relatedTarget.removeClass('active');
			  //e.target.addClass('active');
			  var dummy = e.target;
			  alert(dummy.attr('id'));
			});
			*/
			$('.tab-pane').each(function(){
				var ruleID = $(this).attr('id').replace('rule','');
				
				var timelineHeader = '<table class="table tableTimelineHeader"><tr><td class="tdEmptySpace"></td>';
				var time;
				for (i = 0; i <= 22; i = i + 2)
				{
						time = i+':00';
						timelineHeader += '<td class="tdTimelineHeaderDay">'+time+'</td>';
				}
				timelineHeader += '</tr></table>';
				
				$('#ruleTimeline'+ruleID).append(timelineHeader);
				
				var DaysOfWeek = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
				for (day=0; day <6; day++)
				{
					var timelineFrame = '<table class="table tableTimelineMain" id="timelineMain'+day+'">';
					timelineFrame +='<tr><td class="tdTimelineMainDay">'+DaysOfWeek[day]+'</td>';

					for (i = 0; i < 12; i++)
					{
						for (j = 0; j < 3; j++)
							timelineFrame += '<td class="tdTimelineSlot"></td>';
						timelineFrame += '<td class="tdTimelineSlot" style="border-right:1px solid #DDD;"></td>';
					}
					timelineFrame += '</tr></table>';
					$('#ruleTimeline'+ruleID).append(timelineFrame);
				}
				
				redrawMainTimeline();
			});
		});
		
		function redrawMainTimeline()
		{
			for (day=0; day <6; day++)
			{
				numberOfSlots = $('#ruleDay'+day).data('slotsnumber');
				
				var timelineData = [];
				var timelineMessage = [];
				for (slot=0; slot < numberOfSlots; slot++)
				{
					timelineData[slot*2] = $('#StartDay'+day+'Slot'+slot).data('timestart');
					//console.log(slot*2+'   '+timelineData[slot*2]);
					timelineData[slot*2+1] = $('#EndDay'+day+'Slot'+slot).data('timeend');
					//console.log((slot*2+1)+'   '+timelineData[slot*2+1]);
					timelineMessage[slot*2] = $('#StartDay'+day+'Slot'+slot).text();
					timelineMessage[slot*2+1] = $('#EndDay'+day+'Slot'+slot).text();
				}

				/*
				for (slot=0; slot < timelineData.length; slot++)
					console.log(slot+'   '+timelineData[slot]);
				*/
				drawTimeline(timelineMessage,timelineData, 'timelineMain'+day);
			}
		}
		
		function drawTimeline(timelineMessage,timelineData,timelineFrame){
			var target = $('#'+timelineFrame+' .tdTimelineSlot.tUnavail');
			//console.log('#'+timelineFrame+' .tdTimelineSlot.tUnavail');
			//console.log($(target));
			target.removeClass("tUnavail");
			target = $('#'+timelineFrame+' .tdTimelineSlot.tAvail');
			target.removeClass("tAvail");
			target = $('#'+timelineFrame+' .tdTimelineSlot');
			target.addClass("tAvail");
			
			for (i=0; i< timelineData.length; i=i+2)
			{
				for (j=timelineData[i]; j < timelineData[i+1]; j++)
				{
					var target = '#'+timelineFrame+' td:eq('+(j+1)+')';
					$(target).removeClass("tAvail");
					//console.log($(target));
					$(target).addClass('tUnavail');
					$(target).tooltip({
						title: 'Turned off from '+timelineMessage[i]+' to '+timelineMessage[i+1]+'.',
						placement: 'right',
						animation: false
					});
				}
			}
		}
		
		$('.linkNewSlot').live('click',function(){
			$('#confirmNewSlot').data('dayID', $(this).attr('id').replace('linkNewSlot',''));
			$('#confirmNewSlot span').text($('#createNewSlot').data('dayID'));
		});
		
		$('#confirmNewSlot').live('click',function(){
		 	var officeID = <?=$currentOffice?>;
			var dayID = $('#confirmNewSlot').data('dayID');
			var ruleID = $('#RuleContainer .tab-pane.active').attr('id').replace('rule','');
			var startHr = $('#inputStartHour option:selected').val();
			var startMin = $('#inputStartMin option:selected').val();
			var endHr = $('#inputEndHour option:selected').val();
			var endMin = $('#inputEndMin option:selected').val();
			
			var valid = 1;
			
			var startTime = parseFloat(startHr) + parseFloat(startMin)/60;
			var endTime = parseFloat(endHr) + parseFloat(endMin)/60;
			if (startTime >= endTime)
				valid = 0;
				
			if (!valid)
				$("#wrongTimeSlotInput").show();
			else
				$.ajax({
					url:'<?=site_url("desktop/user/addRuleSlot")?>',
					data:{rule_id:ruleID, day:dayID, start_at_hh:startHr, start_at_mm:startMin, end_at_hh:endHr, end_at_mm:endMin},
					type:"POST",
					success:function(slotID){
						redrawMainTimeline();
						$('#closeNewSlot').trigger('click');
						window.location.href = '<?=site_url('desktop/user/manageRule')?>/'+officeID;
					}
				});
		});
		
		$("#confirmCreateRule").live("click",function(){
 				var officeID = <?=$currentOffice?>;
				var ruleName = $('#inputRuleName').val();
 				var ruleDesc = $('#inputRuleDesc').val();
 				var proceed = true;
				
 				if(ruleName == ""){
 					$('#ruleNameAlert').show();
 					proceed = false;
 				}else{
 					$('#ruleNameAlert').hide();
 				}
 				
 				if(proceed){
	 				$.ajax({
	 					url:'<?=site_url("desktop/user/addRuleInfo")?>',
	 					data:{name:ruleName,description:ruleDesc,office_id:officeID},
	 					type:"POST",
	 					success:function(ruleID){
	 						window.location.href = '<?=site_url('desktop/user/manageRule')?>/'+officeID;
	 					}
 					});
					
 				}
 				return false; 				
 			});
		
		$("#confirmDeleteRule").live("click", function(){
			var targetTabContent = $(".tab-pane.ruleTab.active");
			
			var targetTabLink = $("#ruleTabs li.active");
		
			var ruleID = targetTabContent.attr('id').replace('rule','');
			$.ajax({
				url:'<?=site_url("desktop/user/deleteRule")?>'+'/'+ruleID,
				type:"POST",
				success:function(){
					targetTabContent.removeClass('active');
					targetTabContent.next().addClass('active');
					targetTabContent.hide("400",function(){$(this).remove();});
					
					targetTabLink.removeClass('active');
					targetTabLink.next().addClass('active');
					targetTabLink.hide("400",function(){$(this).remove();});

					$('#closeDeleteRule').click();
				}
 			});
 			$("#logout").click(function(){
         			window.location.href = '<?=site_url("desktop/user/logout")?>';
         		});
		});
		
		function removeTimeSlot(slotID){
			$.ajax({
				url:'<?=site_url("desktop/user/deleteRuleSlot")?>'+'/'+slotID,
				type:"POST",
				success:function(){
					var target = $('#trSlot'+slotID);
					target.remove();
					//target.hide("fast",function(){$(this).remove();});
					redrawMainTimeline();
					return false;
				}
 			});
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
                            <li class = "active"><a href="<?php echo site_url("desktop/user/manageEmployee");?>" style = "font-size:20px;text-transform: none;">Manage Office</a></li>
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
					  <li style = "margin-left:-50px;"><a class = "officelist" name = '<?=$office["name"]?>' id = "office<?=$office["id"]?>" href = "<?=site_url("desktop/user/manageEmployee/".$office["id"])?>"><i class = "icon-chevron-right pull-right"></i><?=$office["name"]?></a></li>
					<? } ?>
					</ul>
				</div>
				
				<div class = "span10">
					<ul class="nav nav-pills">				  
					  <li><a href = "<?=site_url('desktop/user/manageEmployee/'.$currentOffice)?>">Employee Management</a></li>
					  <li  class="active"><a href = "<?=site_url('desktop/user/manageRule/'.$currentOffice)?>">Rule Management</a></li>
					</ul>
					<h3>Create a rule</h3>
					</script>
					<p>Set the period of the day of the week when the electricity in the room is set to be off</p>
					<div>
						<a href="#deleteRule" role="button" class="btn pull-right btn-small" data-toggle="modal">Delete rule</a>
						<a href="#createNewRule" role="button" class="btn btn-primary pull-right btn-small" data-toggle="modal">New rule</a>
					</div>
					<div id = "RuleContainer">
						<ul class="nav nav-tabs" id="ruleTabs">
						<?php
							foreach($rules as $rule){
								$ruleName = $rule->name;
								$ruleID = $rule->id;
						?>
						<li><a href="#rule<?=$rule->id?>" data-toggle="tab"><?=$rule->name?></a></li>
						<?}?>
						</ul>
						
						<div class="tab-content">
						<?php
							foreach($rules as $rule){
								$ruleName = $rule->name;
								$ruleID = $rule->id;
								
								$DaysOfWeek = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
								
								echo '<div class="tab-pane ruleTab" id="rule'.$ruleID.'">';
								echo '<div class="TimelineContainer" id="ruleTimeline'.$ruleID.'" style="padding-top:10px;padding-bottom:13px;">';
								echo '</div>';
								echo '<div id="ruleTable'.$ruleID.'">';
								echo '<table class="table">';
								echo '<tbody>';
									$slots = $rule->slots;
									for ($j = 0; $j <=6 ; $j++)
									{
										echo '<tr>';
										echo '<td id="ruleDay'.$j.'" data-slotsnumber="'.count($slots[$j]).'" style="width:20%">'.$DaysOfWeek[$j].'</br><a class ="linkNewSlot" id="linkNewSlot'.$j.'" href="#createNewSlot" data-toggle="modal" >Add a new time slot</a></td>';
										echo '<td style="width:30%"></td><td style="width:30%"></td><td style="width:20%"></td></tr>';
										
										$k = 0;
										foreach ($slots[$j] as $slot)
										{
											$startTimeRaw = intval(($slot->start_at)*2);
											$endTimeRaw = intval(($slot->end_at)*2);
											$slotID = $slot->id;
											$startTime = $slot->getStartTime();
											$endTime = $slot->getEndTime();
											echo'<tr id="trSlot'.$slotID.'"><td style="width:20%; border-top:none"></td><td id="StartDay'.$j.'Slot'.$k.'" style="width:30%;border-top:none" data-timeStart="'.$startTimeRaw.'">'.$startTime.'</td>';
											echo'<td id="EndDay'.$j.'Slot'.$k.'" style="width:30%; border-top:none" data-timeEnd="'.$endTimeRaw.'">'.$endTime.'</td>';
											echo'<td style="border-top:none; width:20%"><a class="pull-right" onclick="removeTimeSlot('.$slotID.')" style="cursor:pointer"><p class="muted">Remove time slot</p></a></td></tr>';
											$first = 1;
											$k++;
										}
									}
								echo '</tbody></table></div></div>';
							}
						?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal hide fade" id="createNewRule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel">Create a New Rule</h3>
			  </div>
			  
			  <div class="modal-body">
			  	<form class = "form-horizontal">
					<div class="control-group">
					    <label class="control-label" for="inputRuleName">Rule Name:</label>
					    <div class="controls">
					      <input type="text" id="inputRuleName" placeholder="Name" style = "width:300px;" />
					    </div>
					    <div id = "ruleNameAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:263px;display:none;"><strong>Warning!</strong>&nbsp;Please enter a name for the rule.</div>
					  </div>
					  
					  <div class="control-group">
					    <label class="control-label" for="inputRuleDesc">Description:</label>
					    <div class="controls">
					      <textarea type="text" id="inputRuleDesc" placeholder="Description" style = "width:300px;height:100px;"></textarea>
					    </div>
					  </div>
				  </form>
			  </div>
			  
			  <div class="modal-footer">
			  	<button id = "confirmCreateRule" class="btn btn-primary">Create</button>
			    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="createNewSlot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel">Create a New Rule Slot</h3>
			  </div>
			  
			  <div class="modal-body">
				<span></span>
			  	<form class = "form-horizontal">
					<div class="control-group">
						<table class="table">
						<tr>
							<td></td>
							<td><label class="control-label" for="inputStartHour">Hour</label></td>
							<td><label class="control-label" for="inputStartMinute">Minute</label></td>
						</tr>
						<tr>
							<td>Starting time:</td>
							<td><select id="inputStartHour">
							<?php
								for($i=0; $i<=24; $i++) {
									$time_val = ($i < 10) ? '0'.strval($i) : strval($i);
							?>
									<option value="<?=$time_val?>"><?=$time_val?></option>
							<?
								}
							?>
							</select>
							</td>
							<td><select id="inputStartMin">
							<?
								for($i=0; $i<=30; $i=$i+30) {
									$time_val = ($i < 10) ? '00' : '30';?>
									<option value="<?=$time_val?>"><?=$time_val?></option>
							<?
								}
							?>
							</select>
							</td>
						</tr>
						<tr>
							<td>Ending time:</td>
							<td><select id="inputEndHour">
							<?php
								for($i=0; $i<=24; $i++) {
									$time_val = ($i < 10) ? '0'.strval($i) : strval($i);
							?>
									<option value="<?=$time_val?>"><?=$time_val?></option>
							<?
								}
							?>
							</select>
							</td>
							<td><select id="inputEndMin">
							<?php
								for($i=0; $i<=30; $i=$i+30) {
									$time_val = ($i < 10) ? '00' : '30';
							?>
									<option value="<?=$time_val?>"><?=$time_val?></option>
							<?
								}
							?>
							</select>
							</td>
						</tr>
						</table>
					</div>
					<div class="alert alert-block" id="wrongTimeSlotInput">
					<h4>Cannot create the time slot</h4>
						Please check your input
					</div>
				  </form>
			  </div>
			  
			  <div class="modal-footer">
			  	<button id = "confirmNewSlot" class="btn btn-primary">Create</button>
			    <button id = "closeNewSlot"class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="deleteRule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Confirm Delete Rule</h3>
			</div>
			<div class="modal-body">
				<div class="alert alert-block">
				<h4>Warning!</h4>
					If the current rule is deleted, it will be removed from all the rooms that are previously applied by this rule.
					Are you sure you want to delete the current rule?
				</div>
			</div>
			<div class="modal-footer">
				<button id = "confirmDeleteRule" class="btn btn-primary">Confirm</button>
				<button id = "closeDeleteRule" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div>
	</body>
</html>
