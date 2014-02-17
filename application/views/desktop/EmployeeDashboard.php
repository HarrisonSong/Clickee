<?php
	include "header.php";
?>
        <script>
         	$(document).ready(function(){         		  		
         		getOfficeList();
				getStatusList();
				
         		$("#logout").click(function(){
         			window.location.href = '<?=site_url("desktop/user/logout")?>';
         		});
         	});
         	
         	function getStatusList(){
         		$.ajax({
         			url:'<?=site_url("desktop/employee/getRequest")?>',
         			type:"POST",
         			success:function(output){
         				console.log(output);
         				if(!$.isEmptyObject(output)){
     						$('#statusTable').empty();
     						$('#statusTable').append(
     							'<tr>'+
					      			'<th>Office Name</th>'+
					      			'<th>Status</th>'+
					      		'</tr>'
     						);
         					$.each(output,function(index,element){
         						switch(element.status){
         							case "0":
         							case 0 :
         									$('#statusTable').append(
			         							'<tr class = "info">'+
			         								'<td>'+element.building_name+'</td>'+
			         								'<td style = "color:#FF9900;">pending</td>'+
			         							'</tr>');
         									break;
         							case "1":
         							case 1:
         									$('#statusTable').append(
			         							'<tr class = "info">'+
			         								'<td>'+element.building_name+'</td>'+
			         								'<td style = "color:success;">success</td>'+
			         							'</tr>');
         									break;
         							case "2":
         							case 2:
         									$('#statusTable').append(
			         							'<tr class = "info">'+
			         								'<td>'+element.building_name+'</td>'+
			         								'<td style = "color:grey;">rejected</td>'+
			         							'</tr>');
         									break;
         						}
         					});
         				}
         			}
         		});
         	}
         	
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
		         				var office_id;
		         				$.each(output,function(index,element){
		         					if(element.name == office){
		         						office_id = index;
		         					}
		         				});
		         				$.ajax({
		         					url:'<?=site_url('desktop/employee/request')?>',
		         					data:{office_id:office_id,department:department,position:position,comID:companyID},
		         					type:"POST",
		         					success:function(data){
		         						if(data != "1"){
		         							alert(data);
		         						}else{
		         							$('#closeJoinOffice').trigger('click');
		         						}
		         					}
		         				});
		         			}
		         		});
		         	}
         		});
         	}
        </script>
        <title>Dashboard</title>
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
           <div class="row-fluid" id = "employee" style = "min-height:300px;">
           		<div class = "span4 dashboardTable">
					<table class="table dashboardTable">
						<tr>
							<td><h2>1</h2></td>
							<td><p>choose an office to join</p></td>
						</tr>
					</table>
           			<a type="button" id = "booking" data-toggle = "modal" href = "#JoinOfficeButton" class="btn btn-primary btn-block"><br/><br/>Join Office</a>                   
           		</div>
           		<div class = "span4 dashboardTable">
					<table class="table dashboardTable">
						<tr>
							<td><h2>2</h2></td>
							<td><p>Control devices in the rooms that you are permitted to use.</p></td>
						</tr>
					</table>
           			<a type="button" id = "booking" href = "<?=site_url('desktop/user/booking')?>" class="btn btn-primary btn-block"><br/><br/>Booking</a>                   
           		</div>
           		<div class = "span4 dashboardTable">
					<table class="table dashboardTable">
						<tr>
							<td><h2>3</h2></td>
							<td><p>View the booking history and book a room in your preferred timeslot.</p>
						</tr>
					</table>
           			<a type="button" id = "employeeManageRoom" href = "<?=site_url('desktop/user/employeeControlRoom')?>" class="btn btn-primary btn-block"><br/><br/>Control Rooms</a>
           		</div>
           </div>
           
           <div class="accordion" id="employeeApplyStatus">
			  <div class="accordion-group">
			    <div class="accordion-heading">
			      <a class="accordion-toggle" data-toggle="collapse" data-parent="#employeeApplyStatus" href="#collapseOne">
			        Application Statuses
			      </a>
			    </div>
			    <div id="collapseOne" class="accordion-body collapse">
			      <div class="accordion-inner">
			      	<table class = "table table-bordered" id = "statusTable">
			      		<tr>
			      			<th>Office Name</th>
			      			<th>Status</th>
			      		</tr>
			      	</table>
			      </div>
			    </div>
			  </div>
			</div>
        </div> <!-- /container -->
        
      	<div class="modal hide fade" id="JoinOfficeButton" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    <h3 id="myModalLabel"><strong>Join Office</strong></h3>
		  </div>
		  <div class="modal-body">
			<form class = "form-horizontal">
				<div class="control-group" id  = "searchOfficeGroup">
				  <label class="control-label" for="searchOffices">search an office:</label>
				  <div class="controls">
				  	<span class="add-on"><i class="icon-search"></i></span>
				  	<input class="span2" id="searchOffices" type="text"/>				      
				    <span id = "NoOfficeAlert" class="help-inline" style = "display:none;">Office is not in the office List!</span>
				  </div>
				</div>
				<hr>
				<h4>Fill In Personal Info:</h4>
				<div class="control-group" id = "departmentGroup">
			  	 	<label class="control-label" for="department">department:</label>
			    	<div class="controls">
			    	  <input type="text" id="department">
			    	  <span id = "departmentAlert" class="help-inline" style = "display:none;">incorrect input!</span>
			   		</div>
			 	</div>
			  	<div class="control-group" id  = "positionGroup">
			    	<label class="control-label" for="position">position:</label>
			    	<div class="controls">
			      		<input type="text" id="position">
			      		<span id = "positionAlert" class="help-inline" style = "display:none;">incorrect input!</span>
			   		</div>
			  	</div>
			  	<div class="control-group" id = "companyIDGroup">
			    	<label class="control-label" for="companyID">company ID:</label>
			    	<div class="controls">
			      		<input type="text" id="companyID">
			      		<span id = "companyIDAlert" class="help-inline" style = "display:none;">incorrect input!</span>
			    	</div>
			  	</div>
			</form>
		  </div>
		  <div class="modal-footer">
		  	<button id = "confirmJoinOffice" class="btn btn-primary">Confirm</button>
		    <button id = "closeJoinOffice" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		  </div>
		</div>
    </body>
</html>
