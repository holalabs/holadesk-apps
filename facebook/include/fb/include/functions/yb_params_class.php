<?php

$facebook_userid = $_SESSION['fb_data']['userid'];
$facebook_access_token = $_SESSION['fb_data']['token'];

class yb_params_class {
	
	var $facebook_userid;
	var $facebook_access_token;
	
	function yb_params_class() {
		global $facebook_userid;
		global $facebook_access_token;
		
		$this->facebook_userid = $facebook_userid;
		$this->facebook_access_token = $facebook_access_token;
	}
	
}


?>