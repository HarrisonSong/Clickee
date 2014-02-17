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
        	<h1>MY ACCOUNT</h1>
        	<h2>--**--</h2>
 		</div>
    </header>
   
	<div id="user_index_page" class="page">
		 <div class="content">
            <!--<h5 class="sectionTitle"> Personal </h5>-->
            
            <div id="pivotTabs" class="pivotTabs">
              <div id="scroller">
                <ul id="thelist">
				  <li> <a href="#" data-value="#notifyTab">Notifications</a> </li>
				  <li> <a href="#" data-value="#infoTab">Account Info</a> </li>
                  <li> <a href="#" class="goToFirst">&laquo;</a> </li>
                </ul>
              </div>
            </div>
        </div>
		
		<div class="content">
			
			<div id="notifyTab" class="pivotTab" >
				Booking here
            </div>
            <!-- end tab-->
			
            <div id="infoTab" class="pivotTab" >
				<div class="ui-widget successMessage" id="updateSuccessMessage">
                  <div class="ui-state-highlight ui-corner-all">
                    <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                    <span id="updateFormmessage"><strong>Success!</strong> Your information has been changed.</span></p>
                  </div>
                </div>
               
              <form action="<?=site_url("mobile/user/update_info")?>" method="POST" data-enhance="false" form-type="2" id="updateForm">
                <div class="groupBox">
                  <ul>
                    <li>
						Name:
                        <input type="text" placeholder="Name" required name="updateName" id="updateName" value="<?=$this->session->userdata('username')?>">
                    </li>
                    <li>
						Email:
                        <input type="email" placeholder="Email" required name="updateEmail" id="updateEmail" value="<?=$this->session->userdata('email')?>">
                    </li>
                    <li>
						Phone number:
                        <input type="tel" placeholder="+651234567" name="updatePhone" id="updatePhone" value="<?=$this->session->userdata('tel')?>">
                    </li>
                  </ul>
                  <input type="button" class="button buttonStrong right" value="Update" name="buttonSubmit" onClick="updateAction()">
                  <div class="clearfix"></div>
                  
                </div>
                <!-- end group box -->
              </form>
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
	
	function updateAction() {
		var formEl = "#updateForm";
		$('input,textarea').each(function() {
				formValidate(this);
         });
		 if(($(formEl).find(".invalid").length) == 0){
				// Delete all placeholder text
				$('input,textarea').each(function() {
					if($(this).val() == $(this).attr('placeholder')) $(this).val('');
				});
				//now submit form via ajax
					$.ajax({
						url: $(formEl).attr("action"),
						type: $(formEl).attr("method"),
						data: $(formEl).serialize(),
						success: function(r) {
							 if (r == "success") {
								$("#updateFormmessage").html("<strong>Success!</strong> Your information has been changed.");
							} else {
								$("#updateFormmessage").html("<strong>Error!</strong> "+r);
							}
							
							$("#updateSuccessMessage").slideDown('fast');
						   $('html,body').stop().animate({
							  scrollTop: $("#updateSuccessMessage").offset().top - 30
						   }, 300);
						  
						   setTimeout(function(){
							  $("#updateSuccessMessage").slideUp('fast');
						   }, 6000);
						   
						}
					  })
					  return false;
		 }else{
                      return false;
         }
	}
	
</script>

</body>
</html>