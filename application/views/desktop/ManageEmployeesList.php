<?php
	include "header.php";
?>
        <script>
        $(document).ready(function(){
        	var office_id = <?=$currentOffice;?>;
        	if(!$('#office<?=$currentOffice?>').hasClass('active')){
        		$('#office<?=$currentOffice?>').addClass('active');
        	}
            var width = parseInt($('#inventBox').css('width').replace('px','')) - 60;
            console.log("width:"+width);
        	$('#EmployeeEmails').css('width',width);
        	 console.log("width:"+$('#EmployeeEmails').css('width'));
			loadEmployeeList(office_id);
			loadPendingList(office_id);
			$('#confirmInvention').click(function(){
				var emails = $('#EmployeeEmails').val().replace(' ','');
				//console.log(emails);
				if(emails == ""){
					$('#AddEmployeeAlert').show();
				}else{
					$('#AddEmployeeAlert').hide();					
					AddEmployeeList(office_id,emails);
					$('#EmployeeEmails').attr('value','');
					$('#closeInvention').trigger('click');
				}
				return false;
			});
			
			$("#logout").click(function(){
         			window.location.href = '<?=site_url("desktop/user/logout")?>';
         		});
        });
        function loadEmployeeList(office_id){
        	$.ajax({
            	url:'<?=site_url('desktop/user/getEmloyeesByOfficeId')?>/'+office_id,
            	type:'POST',
            	success:function(output){
            		$('#officeEmployees').empty();
            		$('#officeEmployees').append('<tr><th>Name</th><th>Email</th><th>Contact No</th></tr>');
            		if(!$.isEmptyObject(output)){
        				$.each(output,function(index,element){
        					$('#officeEmployees').append('<tr class="success"><td><a class = "employee" href = "#" id = "user'+index+
        					'">'+element.name+'</a><a type="button" onclick = "deleteEmployee('+office_id+',\''+element.email+'\')"'+
        					'class="close deleteEmployee" href = "#deleteEmployeeBox" data-toggle = "modal">×</a></td><td>'+element.email+'</td><td>'+element.tel+'</td></tr>');
        				});
        			}
            	}
            });
        }
        
        function loadPendingList(office_id){
        	$.ajax({
        		url:'<?=site_url('desktop/manager/getRequest')?>/'+office_id,
        		type:"POST",
        		success:function(output){
        			if(!$.isEmptyObject(output)){
	        			$('#pendingEmployees').empty();
	        			$('#pendingEmployees').append(
	        				'<tr>'+
	                			'<th>Time</th>'+
	                			'<th>Name</th>'+
	                			'<th>department</th>'+
	                			'<th>position</th>'+
	                			'<th>companyID</th>'+
	                			'<th></th>'+
	                		'</tr>');
	                	$.each(output,function(index,element){
	                		$('#pendingEmployees').append(
	                		'<tr class ="info" id = "request'+element.id+'">'+
	                			'<td>'+element.created_at+'</td>'+
	                			'<td>'+element.username+'</td>'+
	                			'<td>'+element.department+'</td>'+
	                			'<td>'+element.position+'</td>'+
	                			'<td>'+element.compID+'</td>'+
	                			'<td>'+
	                				'<a id = "approval'+element.id+'" style = "color:green;margin-left:10px;">Approve </a>'+
	                				'<a id = "reject'+element.id+'" style = "color:red;margin-left:20px;"> Reject</a>'+
	                			'</td>'+
	                		'</tr>');
	                		$('#approval'+element.id).click(function(){
	                			$.ajax({
	                				url:'<?=site_url('desktop/manager/acceptRequest')?>/'+element.id,
	                				type:'POST',
	                				success:function(output){
	                					if(output == "1"){
	                						$('#request'+element.id).remove();
	                					}else{
	                						alert(output);
	                					}
	                				}
	                			});
	                		});
	                		$('#reject'+element.id).click(function(){
	                			$.ajax({
	                				url:'<?=site_url('desktop/manager/rejectRequest')?>/'+element.id,
	                				type:'POST',
	                				success:function(output){
	                					if(output == "1"){
	                						$('#request'+element.id).remove();
	                					}else{
	                						alert(output);
	                					}
	                				}
	                			});
	                		});
	                	});	                	
	                }
        		}
        	});
        }
        function deleteEmployee(office_id,email){
        	$('#confirmDeleteEmployee').click(function(){
        		$.ajax({
	        		url:'<?=site_url('desktop/user/removeEmployeesFromOffice')?>',
	        		data:{office_id:office_id,email:email},
	        		type:"POST",
	        		success:function(output){
	        			if(output == "success"){
	        				loadEmployeeList(office_id);
	        				$('#closeDeleteEmployee').trigger('click');
	        			}
	        		}
	        	});	
        	});
        }
        
        function AddEmployeeList(office_id,emails){
        	$.ajax({
        		url:'<?=site_url('desktop/user/addEmployeesToOffice')?>',
        		data:{office_id:office_id,emails:emails},
        		type:"POST",
        		success:function(output){
        			if(output != "success"){
        				//TO DO: measure unsuccessful added emails
        				console.log(output);
        			}
        			loadEmployeeList(office_id);
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
					  <li class="active"><a href = "<?=site_url('desktop/user/manageEmployee/'.$currentOffice)?>">Employee Management</a></li>
					  <li><a href = "<?=site_url('desktop/user/manageRule/'.$currentOffice)?>">Rules Management</a></li>
					</ul>
				   	<span style = "font-size:28px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif;">Office Employees List:</span>
					<a type = "button" href = "#inventBox" class = "btn btn-primary btn-small" data-toggle="modal" style = "margin-top:-10px;margin-left:59%;">invite employees to join</a>
					<div style = "margin-top:30px;">				
							<table class = "table table-hover table-bordered" id = "officeEmployees">
                    		<tr>
                    			<th>Name</th>
                    			<th>Email</th>
                    			<th>Contact No</th>
                    		</tr>
                   			</table>
					</div>
					<hr>
				   	<span style = "font-size:28px;font-family: 'Segoe UI Light','Helvetica Neue','Segoe UI','Segoe WP',sans-serif;">Pending Employees List:</span>					
					<div style = "margin-top:30px;">				
							<table class = "table table-hover table-bordered" id = "pendingEmployees">
                    		<tr>
                    			<th>Time</th>
                    			<th>Name</th>
                    			<th>department</th>
                    			<th>position</th>
                    			<th>companyID</th>
                    			<th></th>
                    		</tr>
                   			</table>
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal hide fade" id="inventBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:40%;">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="myModalLabel"><strong>invent employees</strong></h3>
			  </div>
			  <div class="modal-body">
			     <form>
			     	<div class="control-group">
					    <label class="control-label" for="EmployeeEmails">Employees' Emails:</label>
					    <div class="controls">
					      <textarea type="text" id="EmployeeEmails" placeholder="Emails" style = "height:200px;"></textarea>
					    </div>
					    <div id = "AddEmployeeAlert" class = "alert" style = "margin-left:180px;margin-top:10px;width:263px;display:none;"><strong>Warning!</strong>&nbsp;Input Incorrectly!</div>
					</div>
			     </form>
			  </div>
			  <div class="modal-footer">
			    <button id = "closeInvention" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			    <button id = "confirmInvention" class="btn btn-primary">Confirm</button>
			  </div>
		</div>
		
		<div class="modal hide fade" id="deleteEmployeeBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style = "width:50%;">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			     <h3 id="myModalLabel"><strong>Delete Employee</strong></h3>
			  </div>
			  <div class="modal-body">
			  	Are you sure you want to delete the selected employee?
			  </div>
			  <div class="modal-footer">
			  	<button id = "confirmDeleteEmployee" class="btn btn-primary">Confirm</button>
			    <button id = "closeDeleteEmployee" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			  </div>
		</div>
	</body>
</html>
