<?php
include('../include/webzone.php');

$f1 = new Facebook_class();
$cookie = $f1->getCookie();

$fb_access_token = $f1->getAccessToken();
$fb_userid = $cookie['user_id'];
$fb_expires = $cookie['expires'];

if($fb_userid!=''&&$_GET['error']!='access_denied') {
	$fb_data['userid'] = $fb_userid;
	$fb_data['token'] = $fb_access_token;
	$_SESSION['user_id'] = $fb_userid;
	$_SESSION['fb_data'] = $fb_data;
}

header('Location: '.$GLOBALS['app_url']);
?>