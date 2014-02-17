  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if necessary -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="<?=ASSEST_URL?>mobile_template/js/libs/jquery-1.7.1.min.js"><\/script>')</script>
  
  <script src="<?=ASSEST_URL?>mobile_template/js/iscroll.js"></script>
  <script src="<?=ASSEST_URL?>mobile_template/js/jquery.flexslider-min.js"></script>
  <script src="<?=ASSEST_URL?>mobile_template/js/photoswipe/klass.min.js"></script>
  <script src="<?=ASSEST_URL?>mobile_template/js/photoswipe/code.photoswipe.jquery-3.0.5.min.js"></script>
  <script>
  if (window.location.hash == '#_=_') {
		window.location.hash = '';
  }
    $(document).bind("mobileinit", function(){
    	$.mobile.defaultPageTransition = 'none'; 
        $.mobile.ignoreContentEnabled = true;
		$.mobile.ajaxEnabled = false;
		fixgeometry();
    });
	
	function fixgeometry() {
		var newHeight = parseInt(window.innerHeight) ;
		$('.page').css('min-height', newHeight+'px');
	}; /* fixgeometry */	
  </script>
  <script src="<?=ASSEST_URL?>mobile_template/js/jquery.mobile-1.1.0.min.js"></script>
  <script src="<?=ASSEST_URL?>mobile_template/js/helper.js"></script>
  <script src="<?=ASSEST_URL?>mobile_template/js/script.js"></script>
  
  
  <!-- end scripts-->
