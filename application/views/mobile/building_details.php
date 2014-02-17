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
			<a href="<?=site_url("mobile/user/offices")?>" class="homeButton" data-direction="reverse"></a>
        	<a href="#" class="menuButton"></a>
        	<h1><?=$office->name?></h1>
        	<h2>--**--</h2>
 		</div>
    </header>
   
	<div id="user_index_page" class="page">
		 <div class="content">
            <!--<h5 class="sectionTitle"> Personal </h5>-->
            
            <div id="pivotTabs" class="pivotTabs">
              <div id="scroller">
                <ul id="thelist">
				  <? $i = 0; foreach($floors as $key => $floor) { ?>
				  <li> <a href="#" data-value="#floor<?=$key?>Tab">Floor <?=$i?></a> </li>
				  <? $i++;} ?>
				  <li> <a href="#" data-value="#actionTab"> {Actions}</a> </li>
                  <li> <a href="#" class="goToFirst">&laquo;</a> </li>
                </ul>
              </div>
            </div>
        </div>
		
		<div class="content">
			<? foreach($floors as $floor_id => $floor) { ?>
			<div id="floor<?=$floor_id?>Tab" class="pivotTab" >
				<? foreach($floor as $room_id => $room) { ?>
				<a href ="<?=site_url("mobile/user/rooms/".$room_id)?>">
					<div class="content">
						<div class="groupBox innerContent" id="contentRoom<?=$room_id?>">
							<ul class="list">
								<li><p><b><?=$room["name"]?></b><br/><?=$room["description"]?></p></li>
								<li><p>Total appliances : <?=$room["no_appliance"]?></p></li>
								<li><p>Active appliances: <?=$room["active_appliance"]?></p></li>
							</ul>
						</div>
					</div>
				</a>
				<? } ?>
            </div>
			<? } ?>
            <!-- end tab-->
            <div id="actionTab" class="pivotTab" >
				Action tabs
				
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
		 $(':input[required]').addClass('required');
	});
	
	
</script>

</body>
</html>