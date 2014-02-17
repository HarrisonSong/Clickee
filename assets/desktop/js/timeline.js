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

function toTime(time){
	var hr = parseInt(time);
	var min = time - hr;
	if (hr == 24) return ('midnight');
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