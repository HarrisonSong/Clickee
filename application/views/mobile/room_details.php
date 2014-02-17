<!doctype html>
<html class="no-js" lang="en">
<head>
  <? $this->load->view('mobile/header.php'); ?>
  <style type="text/css">
  	
  </style>
</head>
<body>
	
   <div id="container" data-role="page" > 
    <header>
    	<div class="upperMenu">
			<? $this->load->view('mobile/upperMenu.php'); ?>
		</div>
    
      	<div id="header">
			<a href="<?=site_url("mobile/user/building_details/".$room->building_id)?>" class="homeButton" data-direction="reverse"></a>
        	<a href="#" class="menuButton"></a>
        	<h1>Room</h1>
        	<h2><?=$room->name?></h2>
 		</div>
    </header>
   
	<div id="user_index_page" class="page">
		 <div class="content">
            <!--<h5 class="sectionTitle"> Personal </h5>-->
            
            <div id="pivotTabs" class="pivotTabs">
              <div id="scroller">
                <ul id="thelist">
				  <li> <a href="#" data-value="#appliancesTab">Appliances</a> </li>
				  <li> <a href="#" data-value="#rulesTab">Rules</a> </li>
				  <li> <a href="#" data-value="#permissionTab">Permissions</a> </li>
                  <li> <a href="#" class="goToFirst">&laquo;</a> </li>
                </ul>
              </div>
            </div>
        </div>
		
		<div class="content">
		
			<div id="appliancesTab" class="pivotTab">
				<div id="appli_area">
				</div>
            </div>
            <!-- end tab-->
			
			<div id="rulesTab" class="pivotTab" >
            </div>
            <!-- end tab-->
			
			<div id="permissionTab" class="pivotTab" >
				Booking here
            </div>
            <!-- end tab-->
			
        
		</div>
 		
	</div>
    <div class="clearfix"></div>
    <? $this->load->view('mobile/footer.php'); ?>
  </div> <!--! end of #container -->




  <? $this->load->view('mobile/bottom_library.php'); ?>
<script language="javascript">
	var myInterval;
	var refreshTime = 5; //second
	$(document).ready(function(){
		loadRoomInfo();
		myInterval = setInterval(function(){
        	loadRoomInfo()
        },refreshTime*1000); 
	});
	
	function loadRoomInfo() {
		$.getJSON('<?=site_url("mobile/user/getRoomInfo")?>/'+<?=$room_id?>, function(data) {
			$("#appli_area").html("");
			var out_string = "";
			if (data != null) {
				$.each(data, function(key, info) {
					var no_ports = info.no_ports;
					for(i = 1; i <= no_ports; i++) {
						if (typeof info[i] == "undefined") {
							//
						} else {
							var device_select = '';
							
							if (info[i].is_error == "1") {
								device_select = " -- not connected --";
							} else {
								if (info[i].current_status == '1') {
									device_select = '<select name="device_slider" id="device_'+key+'_'+i+'" data-role="slider" switch_id = "'+key+'" port_id = "'+i+'"><option value="0">Off</option><option value="1" selected= "selected">On</option></select>';
								} else {
									device_select = '<select name="device_slider" id="device_'+key+'_'+i+'" data-role="slider" switch_id = "'+key+'" port_id = "'+i+'"><option value="0" selected= "selected">Off</option><option value="1" >On</option></select>';
								}
							}
							out_string += '<tr><td width="50%" style="vertical-align:middle"><b>'+info[i].name+'</b></td><td width="50%">'+device_select+'</td></tr>';
						}
					}
				});
				
			}
			
			if (out_string == "") {
				$("#appli_area").html("no appliances on this room");
			} else {
				$("#appli_area").html('<table width="100%" border="0">'+out_string+"</table>");
				$("#appli_area").last().addClass('last');
			}
			
			$("select[name='device_slider']").slider();
			$("select[name='device_slider']").change(function(event) {
				toggerDev($(this));
			});
		});
		
		
	}
	
	function toggerDev(self) {
		
		clearInterval(myInterval);
		
		var value = self.val();
		$.get('<?=site_url("desktop/user/action")?>/'+self.attr("switch_id")+'/'+self.attr("port_id")+'/'+value, function(data) {
			if (data != "success") {
				var reverse_value = '"'+(value+1)%2+'"';
				self.val(reverse_value);
				self.slider('refresh');
				alert(data);
			}
			myInterval = setInterval(function(){
        		loadRoomInfo()
        	},refreshTime*1000); 
		});
		
		
	}
</script>

</body>
</html>