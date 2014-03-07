<!DOCTYPE html> 
<html> 
<head>
	<title>Mobile Premium Facebook Application</title>
	<meta charset="UTF-8" />
	
	<script type='text/javascript'> 
	/* <![CDATA[ */
	var App = {
		ajaxurl: "<?php echo $GLOBALS['ajax_url']; ?>", app_url: "<?php echo $GLOBALS['app_url']; ?>"
	};
	/* ]]> */
	
	if(window.location.hash) {
		window.location = App.app_url;
	}
	</script>
	
	<link rel="stylesheet" href="<?php echo $GLOBALS['app_url']; ?>/include/libs/jquery.mobile-1.1.0.min.css" />
	<link rel="stylesheet" href="<?php echo $GLOBALS['app_url']; ?>/include/css/style.css" />
	
	<script type='text/javascript'> 
	/* <![CDATA[ */
	var App = {
		ajaxurl: "<?php echo $GLOBALS['ajax_url']; ?>"
	};
	/* ]]> */
	</script>
	
	<script src="<?php echo $GLOBALS['app_url']; ?>/include/libs/jquery-1.5.2.min.js"></script>
	<script src="<?php echo $GLOBALS['app_url']; ?>/include/fb/include/js/facebook_script.js"></script>
	<script src="<?php echo $GLOBALS['app_url']; ?>/include/fb/include/js/jquery.prettydate.js"></script>
	
	<script src="<?php echo $GLOBALS['app_url']; ?>/include/libs/jquery.mobile-1.0a4.min.js"></script>
	
	<script>
	$(document).ready(function() {
		displayFacebookHome();
	});
	</script>
	
	<?php
	
	if($GLOBALS['google_analytics']!='') {
	?>
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '<?php echo $GLOBALS['google_analytics']; ?>']);
	  _gaq.push(['_trackPageview']);
	 
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
	<?php
	}
	
	?>
	
</head>

<body>