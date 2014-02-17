<?php
	include "header.php";
?>
        <script>
        	
         	$(document).ready(function(){         		  		
         		getOfficeList();				
         		
         		$("#logout").click(function(){
         			window.location.href = '<?=site_url("desktop/user/logout")?>';
         		});
         	});
         	
         	function getOfficeList(){
         		$.ajax({
         			url:'<?=site_url('desktop/user/ajaxOffices')?>',
         			type:'POST',
         			success:function(output){         
         				var officeList = [];			         		
		         		$.each(output,function(index,element){
		         			officeList.push(element.name);
		         		});
		         		console.log(officeList);
		         		$( "#searchOffices").autocomplete({
		            		source: officeList
				       	});
				       	
				       	$('#confirmJoinOffice').click(function(){
		         			var office = $('#searchOffices').val();
		         			var department = $('#department').val();
		         			var position = $('#position').val();
		         			var companyID = $('#companyID').val();
		         			var proceed = true;
		         			if(office == "" ||($.inArray(office,officeList) == -1)){
		         				$('#NoOfficeAlert').show();
		         				$('#searchOfficeGroup').addClass('error');
		         				proceed = false;
		         			}else{
		         				$('#NoOfficeAlert').hide();
		         				$('#searchOfficeGroup').removeClass('error');
		         			}
		         			if(department == ""){
		         				$('#departmentAlert').show();
		         				$('#departmentGroup').addClass('error');
		         				proceed = false;
		         			}else{
		         				$('#departmentAlert').hide();
		         				$('#departmentGroup').removeClass('error');
		         			}
		         			if(position == ""){
		         				$('#positionAlert').show();
		         				$('#positionGroup').addClass('error');
		         				proceed = false;
		         			}else{
		         				$('#positionAlert').hide();
		         				$('#positionGroup').removeClass('error');
		         			}
		         			if(companyID == ""){
		         				$('#companyIDAlert').show();
		         				$('#companyIDGroup').addClass('error');
		         				proceed = false;
		         			}else{
		         				$('#companyIDAlert').hide();
		         				$('#companyIDGroup').removeClass('error');
		         			}
		         			if(proceed){
		         				//do submission
		         			}
		         		});
		         	}
         		});
         	}
        </script>
        <title>Manager Dashboard</title>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

        <div class="navbar  navbar-fixed-top" style = "line-height:20px;">
            <div class="navbar-inner"  style = "min-height:20px;height:36px;"> 
                <div class="container">
                  <a class="brand" href="#"><img class="pull-left" src="<?=ASSEST_URL?>desktop/img/ClickeeLogoSmall.png" center center no-repeat> Dashboard</a>
				  <a class = "pull-right"id = "logout" tabindex="-1" href="#" style = "margin-top:8px;font-size:20px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif">Logout</a>
				</div>
            </div>
        </div>

        <div class="container content">
			<br/>
			<h3>Personal Information</h3>
			<dl class="dl-horizontal">
			  <dt>Name:</dt>
			  <dd><?=$this->session->userdata('username')?></dd>
			  <dt>Email:</dt>
			  <dd><?=$this->session->userdata('email')?></dd>
			</dl>
			<hr>
           <div class="row-fluid" id = "manager" style = "min-height:300px;">
                <div class="span4">
					<table class="table dashboardTable">
						<tr>
							<td><h2>1</h2></td>
							<td><p>Create a new office, add in rooms and Clikee switches to the office.</p></td>
						</tr>
					</table>
                    <a type="button" id = "createOffice" href = "<?php echo site_url("desktop/user/configureOffice");?>" class="btn btn-success btn-block"><br/><br/>Create Or Configure Office</a>
                </div>
                <div class="span4">
					<table class="table dashboardTable">
						<tr>
							<td><h2>2</h2></td>
							<td><p>Manage office, add in employees and create rules for electricity usage.</p></td>
						</tr>
					</table>
                	<a type="button" id = "manageOffice" href = "<?php echo site_url("desktop/user/manageEmployee");?>" class="btn btn-success btn-block" ><br/><br/>Manage Office</a>
                </div>
                 <div class="span4">
					<table class="table dashboardTable">
						<tr>
							<td><h2>3</h2></td>
							<td><p>Control the devices in all the rooms via Clickee switches.</p></td>
						</tr>				 
					</table>
                	<a type="button" id = 'managerManageRoom' href = "<?php echo site_url("desktop/user/managerControlRoom");?>" class="btn btn-success btn-block"><br/><br/>Manage Room</a>
                </div>
           </div>
        </div> <!-- /container -->
    </body>
</html>
