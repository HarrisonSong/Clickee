<!doctype html>
<html class="no-js" lang="en">
<head>
  <? $this->load->view('mobile/header.php'); ?>
  <style type="text/css">
  	#remote_login_button .ui-btn-inner { padding: 0.7em 20px;}
	#remote_login_button .ui-btn{ margin: 0.5em 5px 10px;}
  </style>
</head>
<body>
	
   <div id="container" data-role="page" > 
    <header>
    	<div class="upperMenu">
			<? $this->load->view('mobile/upperMenu.php'); ?>
		</div>
    
      	<div id="header">
        	<a href="#" class="menuButton"></a>
        	<h1>Name</h1>
        	<h2>Slogan</h2>
 		</div>
    </header>
   
	<div id="customer_page" class="page">
		 <div class="content">
            <!--<h5 class="sectionTitle"> Personal </h5>-->
            
            <div id="pivotTabs" class="pivotTabs">
              <div id="scroller">
                <ul id="thelist">
				  <li><a href="#" data-value="#loginTab"> Sign in</a></li>
				  <li><a href="#" data-value="#signUpTab"> Sign up</a></li>
				  <li> <a href="#" class="goToFirst">&laquo;</a> </li>
                </ul>
              </div>
            </div>
        </div>
		
		<div class="content">
		
				<div class="ui-widget successMessage">
                  <div class="ui-state-highlight ui-corner-all">
                    <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                    <strong>Error!</strong> <span id="error_message">Your information has been changed.</span></p>
                  </div>
                </div>
		
			<div id="loginTab" class="pivotTab" >
				
				
				<form action="<?=site_url("mobile/general/login")?>" method="POST" data-enhance="false" success-url="<?=site_url("mobile/user/index")?>" form-type="1" id="loginForm">
                <div class="groupBox">
                  <ul>
                    <li>
						Email Address:
                        <input type="email" placeholder="Email" required name="loginEmail" id="loginEmail" value="<?=$this->session->userdata('name')?>">
                    </li>
                    <li>
						Password:
                        <input type="password" placeholder="Password" required name="loginPassword" id="loginPassword" value="<?=$this->session->userdata('email')?>">
                    </li>
                  </ul>
                  <input type="button" class="button buttonStrong right" value="Login" name="buttonSubmit" onClick="loginAction()">
                  <div class="clearfix"></div>
                  
                </div>
                <!-- end group box -->
              </form>
			  <p style=" margin-top:10px; margin-bottom:10px"><br/><em>Or you can login via</em></p>
			  <div id="remote_login_button">
			 	 <a href="<?=site_url("mobile/general/remote_login/fb")?>" data-role="button">Facebook</a>
			  	<a href="<?=site_url("mobile/general/remote_login/gmail")?>" data-role="button">Gmail</a>
			  	<a href="<?=site_url("mobile/general/remote_login/yahoo")?>" data-role="button">Yahoo Mail</a>
			  </div>
            </div>
            <!-- end tab-->
			<div id="signUpTab" class="pivotTab" >
				<form action="<?=site_url("mobile/general/signup")?>" method="POST" data-enhance="false" success-url="<?=site_url("mobile/user/index")?>" form-type="1" id="signupForm">
                <div class="groupBox">
                  <ul>
				  	<li>
						Name:
                        <input type="text" placeholder="Name" required name="signupUsername" id="signupUsername" value="<?=$this->session->userdata('name')?>">
                    </li>
                    <li>
						Email:
                        <input type="email" placeholder="Email" required name="signupEmail" id="signupEmail" value="<?=$this->session->userdata('name')?>">
                    </li>
                    <li>
						Password:
                        <input type="password" placeholder="Password" required name="signupPassword" id="signupPassword" value="<?=$this->session->userdata('email')?>">
                    </li>
					<li>
						Confirm Password:
                        <input type="password" placeholder="Confirm password" required name="confirmPassword" id="confirmPassword" value="<?=$this->session->userdata('email')?>">
                    </li>
					<li>
						Phone number:
                        <input type="tel" placeholder="+6512345678" required name="phone_number" id="phone_number" value="<?=$this->session->userdata('tel')?>">
                    </li>
                  </ul>
                  <input type="hidden" value="submitted" name="submitted"/>
                  <input type="button" class="button buttonStrong right" value="Signup" name="buttonSubmit" onClick="signupAction()">
                  <div class="clearfix"></div>
                  
                </div>
            </div>
            <!-- end tab-->
			
		</div>
 		
	</div>
    <div class="clearfix"></div>

    <? $this->load->view('mobile/footer.php'); ?>
  </div> <!--! end of #container -->

  <? $this->load->view('mobile/bottom_library.php'); ?>

  <? $this->load->view('mobile/bottom_library.php'); ?>
	<script language="javascript">
	$(document).ready(function(){
		 $(':input[required]').addClass('required');
	});
	
	function loginAction() {
		var formEl = "#loginForm";
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
								location.href = $(formEl).attr("success-url");
								return;
							}
							$("#error_message").html(r);
							
							$(".successMessage").slideDown('fast');
						   $('html,body').stop().animate({
							  scrollTop: $(".successMessage").offset().top - 30
						   }, 300);
						  
						   setTimeout(function(){
							  $(".successMessage").slideUp('fast');
						   }, 6000);
						   
						}
					  })
					  return false;
		 }else{
                      return false;
         }
	}
	
	function signupAction() {
		var formEl = "#signupForm";
		$('input,textarea').each(function() {
				formValidate(this);
         });
		 if(($(formEl).find(".invalid").length) == 0){
				// Delete all placeholder text
				$('input,textarea').each(function() {
					if($(this).val() == $(this).attr('placeholder')) $(this).val('');
				});
				
				 if ($(formEl).find("#confirmPassword").length) {
					var confirmPassword = $(formEl).find("#confirmPassword").val();
					if (confirmPassword != $(formEl).find("#signupPassword").val()) {
						alert("the passwords don't match!");
						return false;
					}
				  }
				
				//now submit form via ajax
					$.ajax({
						url: $(formEl).attr("action"),
						type: $(formEl).attr("method"),
						data: $(formEl).serialize(),
						success: function(r) {
							 if (r == "success") {
								location.href = $(formEl).attr("success-url");
								return;
							}
							$("#error_message").html(r);
							
							$(".successMessage").slideDown('fast');
						   $('html,body').stop().animate({
							  scrollTop: $(".successMessage").offset().top - 30
						   }, 300);
						  
						   setTimeout(function(){
							  $(".successMessage").slideUp('fast');
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