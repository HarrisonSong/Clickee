<?php
	include "header.php";
?>
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/ManageEmployee.css">
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/managerule.css">
		<link rel="stylesheet" href="<?=ASSEST_URL?>desktop/css/timeline.css">

        <script>
		var timelineData = new Array(7);
		var office_id = <?=$currentOffice?>;		
		
		for (i = 0; i < 7; i++)
		{
			timelineData[i] = new Array(48);
			for (j = 0; j < 48; j++)
				timelineData[i][j] = 0;
		}
		//console.log('start');
		//console.log(timelineData['Thursday']);
		
        $(document).ready(function() {
			getAllRules(office_id);
			$('.alert').hide();
			$('#inputEndMin option[value="59"]').hide();
			console.log(timelineData);
			$("#ManagerNavBar li:eq(2)").addClass('active');			
		});

		function createTimelineHeader(timelineContainer){
			var timelineHeader = '<table class="table tableTimelineHeader"><tr><td class="tdEmptySpace"></td>';
			var time;
			for (i = 0; i <= 22; i = i + 2)
			{
					time = i+':00';
					timelineHeader += '<td class="tdTimelineHeaderDay">'+time+'</td>';
			}
			timelineHeader += '</tr></table>';
			
			timelineContainer.append(timelineHeader);
		}
		
		function createTimelineHeaderSqueezed(timelineContainer){
			var timelineHeader = '<table class="table tableTimelineHeader tableTimelineHeaderSqueezed"><tr><td class="tdEmptySpace"></td>';
			var time;
			
			timelineHeader += '<td class="tdTimelineHeaderDay" style="width: 21.6%">00:00</td>';
			timelineHeader += '<td class="tdTimelineHeaderDay" style="width: 21.6%">06:00</td>';
			timelineHeader += '<td class="tdTimelineHeaderDay" style="width: 21.6%">12:00</td>';
			timelineHeader += '<td class="tdTimelineHeaderDay" style="width: 14.4%">18:00</td>';
			timelineHeader += '<td class="tdTimelineHeaderDay" style="width: 7.2%">midnight</td>';
			timelineHeader += '</tr></table>';
			
			timelineContainer.append(timelineHeader);
		}
		
		function createTimelineFrame(day, timelineContainer){
			var timelineFrame = '<table class="table tableTimeline tableTimeline'+day+'">';
			timelineFrame +='<tr><td class="tdTimelineDay">'+day+'</td>';
			
			for (j = 0; j < 12; j++)
			{
				for (k = 0; k < 3; k++)
					timelineFrame += '<td class="tdTimelineSlot tAvail"></td>';
				timelineFrame += '<td class="tdTimelineSlot tAvail" style="border-right:1px solid #DDD;"></td>';
			}
			timelineFrame += '</tr></table>';
			timelineContainer.append(timelineFrame);
		}
		
		function updateTimeline(dayID, timelineFrame){
			var target = $(timelineFrame+' .tdTimelineSlot.tUnavail');
			target.removeClass("tUnavail");
			target = $(timelineFrame+' .tdTimelineSlot.tAvail');
			target.removeClass("tAvail");
			target = $(timelineFrame+' .tdTimelineSlot');
			target.addClass("tAvail");
			
			var i = 0, j, start, end;
			
			while (i< 48){
				if (timelineData[dayID][i]){
					j = i;
					start = i;
					while (timelineData[dayID][j]){
						j++;
					}
					end = j;
				
					
					for (j=start; j < end; j++)
					{
						var target = timelineFrame+' td:eq('+(j+1)+')';
						$(target).removeClass("tAvail");
						$(target).addClass('tUnavail');
						$(target).tooltip({
							title: 'Turned off from '+toTime(start/2.0)+' to '+toTime(end/2.0)+'.',
							placement: 'right',
							animation: false
						});
					}
					i = end;
				}
				i++;
			}
		}
		
		function getAllRules(office_id){
        	$.ajax({
        		url:'<?=site_url('desktop/user/ajaxRuleIdsOffice')?>/'+office_id,
        		type:"POST",
        		success:function(output){
					if (output.length != 0){
						$('#RuleContainer').empty();
						$('#RuleContainer').append('<a href="#deleteRule" role="button" class="btn pull-right btn-small" data-toggle="modal">Delete rule</a>');
						$('#RuleContainer').append('<a href="#createNewRule" role="button" class="btn btn-primary pull-right btn-small btn-red" data-toggle="modal">New rule</a>');
						$('#RuleContainer').append('<ul class="nav nav-tabs" id="ruleTabs">');
						$.each(output,function(index,element){
							$('#ruleTabs').append('<li><a href="#rule'+element.id+'" data-toggle="tab">'+element.name+'</a></li>');
						});
						$('#RuleContainer').append('</ul>');
						$('#ruleTabs a:first').addClass('active');
						
						$('#RuleContainer').append('<div class="tab-content">');
						$('#RuleContainer').append('</div>');
						$('#ruleTabs a:first').trigger('click');
					} else {
						$('#RuleContainer').empty();
						$('#RuleContainer').append('<div id="emptyRule">There is no rule set for your office yet.<br></br>'+
													'<a type = "button" href = "#createNewRule" class = "btn btn-primary btn-red" data-toggle="modal">New Rule</a></div>');
					}
        		}
        	});
        }
		
		$('#ruleTabs a').live('click',function(){
			var ruleID = $(this).attr('href').replace('#rule','');
			$('.tab-pane.ruleTab').remove();
			$('.tab-content').append('<div class="tab-pane ruleTab active" id="rule'+ruleID+'">');
			$('.tab-pane.ruleTab').append('<div class="TimelineContainer" id="ruleTimeline'+ruleID+'" style="padding-top:10px;padding-bottom:13px;">');
			$('.tab-pane.ruleTab').append('<div id="ruleTableContainer'+ruleID+'">');
			getCurrentRule(ruleID);
			$(this).tab('show');
		});
		
		function toTime(time){
			var hr = parseInt(time);
			var min = time - hr;
			if (min == 0.5) return (hr +':'+ '30');
				else if (min == 0.0) return (hr+':'+'00');
					else return ('midnight');
		}
		
		function DayofWk(dayID){
			var day;
			switch(dayID){
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
			return day;
		}
		
		function getCurrentRule(ruleID){
        	$.ajax({
        		url:'<?=site_url('desktop/user/ajaxRuleDetails')?>/'+ruleID,
        		type:'POST',
        		success:function(output){
					console.log(output);
					createTimelineHeader($('#ruleTimeline'+ruleID));
									
        			if(!$.isEmptyObject(output)){
						$('#ruleTimeline'+ruleID).before('<h4 style = "text-transform:none;">'+output.description+'</h4>');
        				$('#ruleTableContainer'+ruleID).append(
        					'<table id = "ruleTable'+ruleID+'" class="table ruleTable">'+
        						'<thead>'+
        							'<tr>'+
        								'<th>Day</th>'+
        								'<th>Start Time</th>'+
        								'<th>End Time</th>'+
										'<th></th>'+
        							'</tr>'+
        						'</thead>'+
        						'<tbody>'+
        						'</tbody>'+
        					'</table>'
        					);
							
						var last = -1;
						
        				$.each(output.slots,function(index,element){
							var dayID, day;
							dayID = index;
							day = DayofWk(dayID);
							
							//Fill the table with the rest of days							
							for (i = parseInt(last)+1; i < dayID; i++)
							{
								$('#ruleTable'+ruleID+' tbody').append(
									'<tr class="slotGroup'+i+'">'+
										'<td rowspan="1" id="tdDay'+i+'" data-slotcnt="0">'+DayofWk(i)+'<br>'+
										'<a class ="linkNewSlot" id="linkNewSlot'+i+'" href="#createNewSlot" data-toggle="modal" >Add a new time slot</a>'+
										'</td><td></td>'+
										'<td></td><td></td>'+
									'</tr>'
									);
								createTimelineFrame(DayofWk(i), $('#ruleTimeline'+ruleID));
							}

							last = dayID;
							createTimelineFrame(day, $('#ruleTimeline'+ruleID));
							
							$.each(element,function(index,slot){
								if(index == 0){
									$('#ruleTable'+ruleID+' tbody').append(
		        						'<tr class="slotGroup'+dayID+'" id="trSlot'+slot.id+'">'+
		        							'<td rowspan = '+element.length+' id="tdDay'+dayID+'" data-slotcnt="'+element.length+'">'+day+'<br>'+
											'<a class ="linkNewSlot" id="linkNewSlot'+dayID+'" href="#createNewSlot" data-toggle="modal" >Add a new time slot</a>'+
											'</td>'+
		        							'<td>'+toTime(slot.start_at)+'</td>'+
		        							'<td>'+toTime(slot.end_at)+'</td>'+
											'<td><a class="pull-right" onclick="removeTimeSlot('+slot.id+')" style="cursor:pointer"><p class="muted">Remove time slot</p></a></td>'+
		        						'</tr>'
		        						);
								}else{
									$('#ruleTable'+ruleID+' tbody').append(
		        						'<tr class="slotGroup'+dayID+'" id="trSlot'+slot.id+'">'+
		        							'<td>'+toTime(slot.start_at)+'</td>'+
		        							'<td>'+toTime(slot.end_at)+'</td>'+
											'<td><a class="pull-right" onclick="removeTimeSlot('+slot.id+')" style="cursor:pointer"><p class="muted">Remove time slot</p></a></td>'+
		        						'</tr>'
		        						);
								}
								
								for (timelineIndex = slot.start_at * 2; timelineIndex < slot.end_at * 2; timelineIndex++)
									timelineData[dayID][timelineIndex] = 1;
							});

							//console.log(timelineData);
							updateTimeline(dayID,'#ruleTimeline'+ruleID+' .tableTimeline'+day);
        				});
						//console.log('updated');
						//console.log(timelineData['Thursday']);
						
						for (i = parseInt(last)+1; i < 7; i++){
							$('#ruleTable'+ruleID+' tbody').append(
							'<tr class="slotGroup'+i+'">'+
								'<td rowspan="1" id="tdDay'+i+'" data-slotcnt="0">'+DayofWk(i)+'<br>'+
								'<a class ="linkNewSlot" id="linkNewSlot'+i+'" href="#createNewSlot" data-toggle="modal" >Add a new time slot</a>'+
								'</td><td></td>'+
								'<td></td>'+
								'<td></td>'+
							'</tr>'
							);
							createTimelineFrame(DayofWk(i), $('#ruleTimeline'+ruleID));
						}
        			}
        		}
        	});
        }

		$('.linkNewSlot').live('click',function(){
			var dayID = $(this).attr('id').replace('linkNewSlot','');
			$('#confirmNewSlot').data('dayID', dayID);
		});
		
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
						var target = $('#tdDay'+dayID);
						var rowspan = parseInt(target.attr('rowspan')) + 1;
						var slotCnt = parseInt(target.data('slotcnt')) + 1;
						target.data('slotcnt', slotCnt);
						
						if (slotCnt > 1){
							target.attr('rowspan', rowspan);
							$('.slotGroup'+dayID).last().after(
								'<tr class="slotGroup'+dayID+'" id="trSlot'+slotID+'">'+
									'<td>'+toTime(startTime)+'</td>'+
									'<td>'+toTime(endTime)+'</td>'+
									'<td><a class="pull-right" onclick="removeTimeSlot('+slotID+')" style="cursor:pointer"><p class="muted">Remove time slot</p></a></td>'+
								'</tr>'
							);
						}
						else{
							$('.slotGroup'+dayID).attr('id','trSlot'+slotID);
							target.attr('rowspan', '1');
							$('.slotGroup'+dayID).first().find('td:eq(1)').html(toTime(startTime));
							$('.slotGroup'+dayID).first().find('td:eq(2)').html(toTime(endTime));
							$('.slotGroup'+dayID).first().find('td:eq(3)').html('<a class="pull-right" onclick="removeTimeSlot('+slotID+')" style="cursor:pointer"><p class="muted">Remove time slot</p></a>');
						}
						
						var i;
						for (i = startTime * 2; i < endTime * 2; i++)
							timelineData[dayID][i] = 1;
						updateTimeline(dayID,'#ruleTimeline'+ruleID+' .tableTimeline'+DayofWk(dayID));
						$('#closeNewSlot').trigger('click');
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
							getAllRules(office_id);
							$('#ruleTabs').append('<li><a href="#rule'+ruleID+'" data-toggle="tab">'+ruleName+'</a></li>');
							$('#ruleTabs a:last').trigger('click');

							$('#ruleTabs a.active').removeClass('active');							
							$('#ruleTabs a:last').addClass('active');
							$('#RuleContainer .tab-pane.active').removeClass('active');
							$('#RuleContainer .tab-pane:last').addClass('active');
							
							$('#cancelCreateRule').trigger('click');
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
					getAllRules(office_id);
					targetTabContent.removeClass('active');
					targetTabContent.remove();

					targetTabLink.removeClass('active');
					$('#ruleTabs a:last').remove();
					$('#ruleTabs a:last').trigger('click');
					targetTabLink.remove();
					
					$('#closeDeleteRule').click();
				}
 			});
		});
		
		function removeTimeSlot(slotID){
			$.ajax({
				url:'<?=site_url("desktop/user/deleteRuleSlot")?>'+'/'+slotID,
				type:"POST",
				success:function(){
					var startTime, endTime, start, end;
				
					var target = $('#trSlot'+slotID);
					console.log(target.find('td').eq(0).attr('id'));
					if(target.find('td').eq(0).attr('id') !== undefined)
					{
						console.log(target.find('td').eq(1).html());
						startTime = target.find('td').eq(1).html().split(':');
						endTime = target.find('td').eq(2).html().split(':');
						
						var delDay = target.find('td').eq(0).html();
						var delID = target.find('td').eq(0).attr('id');
						var delRowSpan = parseInt(target.find('td').eq(0).attr('rowspan')) - 1;
						var slotCnt = parseInt(target.find('td').eq(0).data('slotcnt')) - 1;
						
						if (slotCnt > 0){
							target.next().prepend(
							'<td rowspan = '+delRowSpan+' id="'+delID+'" data-slotcnt="'+slotCnt+'">'+delDay + '</td>');
							target.remove();
							target.removeAttr('id');
						}
						else
						{
							target.find('td').eq(0).data('slotcnt',slotCnt);
							target.find('td').eq(1).html('');
							target.find('td').eq(2).html('');
							target.find('td').eq(3).html('');							
							
							target.removeAttr('id');
						}
					}
					else
					{
						console.log(target.find('td').eq(0).html());
						startTime = target.find('td').eq(0).html().split(':');
						endTime = target.find('td').eq(1).html().split(':');
					
						var firstRowDay = target;
						while (firstRowDay.find('td').eq(0).attr('id') == undefined)
							firstRowDay = firstRowDay.prev();
						var rowspan = parseInt(firstRowDay.find('td').eq(0).attr('rowspan')) - 1;
						var slotCnt = parseInt(firstRowDay.find('td').eq(0).data('slotcnt')) - 1;

						firstRowDay.find('td').eq(0).data('slotcnt',slotCnt);
						firstRowDay.find('td').eq(0).attr('rowspan',rowspan);
						target.remove();
					}
					start = parseInt(startTime[0]) + parseInt(startTime[1]) / 60;
					if (endTime[0] != 'midnight')
						end = parseInt(endTime[0]) + parseInt(endTime[1]) / 60;
					else
						end = 24.0;
					
					var i, dayID = target.find('td').eq(0).attr('id').replace('tdDay','');
					console.log(timelineData);
					for (i = start * 2; i < end * 2; i++)
						timelineData[dayID][i] = 0;
					var ruleID = $('#RuleContainer .tab-pane.active').attr('id').replace('rule','');					
					
					updateTimeline(dayID,'#ruleTimeline'+ruleID+' .tableTimeline'+DayofWk(dayID));
					
					return false;
				}
 			});
		}
		
		$("#logout").click(function(){
         			window.location.href = '<?=site_url("desktop/user/logout")?>';
         		});
		
        </script>
	</head>

	<body>
		<?php
			include "ManagerNavBar.php";
		?>
				
		<div class = "container" style = "margin-top:60px;padding-right:20px;">
			<div class = "row-fluid">
				<div class = "span12">
					<ul class="nav nav-pills">				  
					  <li style="background-color:whitesmoke"><a href = "<?=site_url('desktop/manager/manageEmployee/'.$currentOffice)?>"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_043_group.png" style = "width:12px;height:12px;padding-right: 10px;padding-bottom: 3px;">Employee Management</a></li>
					  <li  class="active"><a href = "<?=site_url('desktop/manager/manageRule/'.$currentOffice)?>"><img src = "<?=ASSEST_URL?>desktop/img/glyphicons_045_calendar.png" style = "width:12px;height:12px;padding-right: 10px;padding-bottom: 3px;">Rule Management</a></li>
					</ul>
					<div id="ruleIntro">
						<h3 style="text-transform:none">Rules for the Week</h3>
						<p>Set the timings to ensure that the devices in the private room will be off.</p>
					</div>
					<div id = "RuleContainer">

					</div>
				</div>
			</div>
		</div>
		
		<div class="modal hide fade" id="createNewRule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel" style="text-transform:none">Create a New Rule</h3>
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
			    <button id = "cancelCreateRule" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="createNewSlot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel">Create a New Rule Slot</h3>
			  </div>
  
			  <div class="modal-body">
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
								for($i=0; $i<=23; $i++) {
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
								for($i=0; $i<=23; $i++) {
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
								<option value="59">59</option>
							</select>
							</td>
						</tr>
						<tr>
							<td style="padding-top:0px; border-top:none"></td>
							<td colspan="2" style="padding-top:0px; border-top:none">
							<label class="pull-right" for="midnight"><input type="checkbox" id="midnight" style="margin-bottom: 6px; margin-right: 6px;"/>Check if you want to set the end time at midnight</label>
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
		
		<div class="modal hide fade" id="deleteRule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%; height:260px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel" style="text-transform:none">Confirm Delete Rule</h3>
			</div>
			<div class="modal-body">
				
				<h4 style=" text-transform:none">Warning!</h4>
					If the current rule is deleted, it will be removed from all the rooms that are previously applied by this rule.
					Are you sure you want to delete the current rule?
				
			</div>
			<div class="modal-footer">
				<button id = "confirmDeleteRule" class="btn btn-primary">Confirm</button>
				<button id = "closeDeleteRule" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div>
	</body>
</html>
