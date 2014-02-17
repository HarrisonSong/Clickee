<!doctype html>
<html class="no-js" lang="en">
<head>
  <? $this->load->view('mobile/header.php'); ?>
</head>
<body>
	
   <div id="container" data-role="page" > 
    <header>
    	<div class="upperMenu">
			<? $this->load->view('mobile/upperMenu.php'); ?>
		</div>
    
      	<div id="header">
        	<a href="#" class="menuButton"></a>
        	<h1>MY OFFICE</h1>
        	<h2>--**--</h2>
 		</div>
    </header>
   
	<div id="user_index_page" class="page">
		 <div class="content">
            <!--<h5 class="sectionTitle"> Personal </h5>-->
            
            <div id="pivotTabs" class="pivotTabs">
              <div id="scroller">
                <ul id="thelist">
				  <li> <a href="#" data-value="#mybuildingTab">Manage Offices</a> </li>
				  <li> <a href="#" data-value="#controlTab">Control Permission</a> </li>
                  <li> <a href="#" class="goToFirst">&laquo;</a> </li>
                </ul>
              </div>
            </div>
        </div>
		
		<div class="content">
		
			<div id="mybuildingTab" class="pivotTab" >
				<div id="officeInfoArea">	 
				</div>
            </div>
            <!-- end tab-->
			
			<div id="controlTab" class="pivotTab" >
				<div id="controlInfoArea">	 
				</div>
            </div>
            <!-- end tab-->
			
			
		</div>
 		
	</div>
    <div class="clearfix"></div>
    <? $this->load->view('mobile/footer.php'); ?>
  </div> <!--! end of #container -->




  <? $this->load->view('mobile/bottom_library.php'); ?>
<script language="javascript">
	$(document).ready(function(){
		 loadOffice();
		 loadControl();
	});
	
	function loadOffice() {
		$.getJSON('<?=site_url("mobile/user/loadAllOffices")?>', function(data) {
			$("#officeInfoArea").html("");
			var out_string = "";
			$.each(data, function(key, info) {
				out_string += '<a href ="<?=site_url("mobile/user/building_details/")?>/'+key+'"><div class="content"><div class="groupBox innerContent"><p><b>'+info.name+'</b></p><ul class="list"><li><p>'+info.description+'</p></li><li><p>Total appliances : '+info.total_appliances+'</p></li><li class="last"><p>Active appliances: '+info.total_active_appliances+'</p></li></ul></div></div></a>';
			});
			
			if (out_string == "") {
				$("#officeInfoArea").html("<p><b>There is no office!</b></p>");
			} else {
				$("#officeInfoArea").html(out_string);
			}
		});
	}
	
	function loadControl() {
		$.getJSON('<?=site_url("mobile/user/loadControl")?>', function(data) {
			$("#controlInfoArea").html("");
			
			if (data == null)
				return;
			
			var out_string = "";
			$.each(data, function(key, office) {
				out_string += '<p><b>'+office.info.name+'</b>: '+office.info.description+'</p>';
				
				$.each(office.rooms, function(room_id, room) {
					out_string += '<a href ="<?=site_url("mobile/user/room_details/")?>/'+room_id+'"><div class="content"><div class="groupBox innerContent"><p><b>'+room.name+'</b></p><ul class="list"><li><p>'+room.description+'</p></li><li><p>Total appliances : '+room.total_appliances+'</p></li><li class="last"><p>Active appliances: '+room.total_active_appliances+'</p></li></ul></div></div></a>';
				});
			});
			
			if (out_string == "") {
				$("#controlInfoArea").html("<p><b>You don't have any permission!</b></p>");
			} else {
				$("#controlInfoArea").html(out_string);
			}
		});
	}
</script>

</body>
</html>