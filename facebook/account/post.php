<?php
include('../include/webzone.php');

$post = $_POST['post'];
$f20 = new Facebook_class();
$user = $f20->getUserid();
$facebook = new Facebook(array(
    'appId'  => FACEBOOK_APP_ID,
    'secret' => FACEBOOK_SECRET));

$facebook->setAccessToken($f20->getAccessToken());

if ($user) {
	$attachment = array('message' => $post);

    try {
	    // Proceed knowing you have a user who is logged in and authenticated
	    $result = $facebook->api('/me/feed/','post',$attachment);
    } catch (FacebookApiException $e) {
	    error_log($e);
	    $user = null;
    }

}
?>

<script type='text/javascript'>
	window.parent.$('div.ui-navbar').show();
	window.parent.$('div#fbStatusList').show();
	window.parent.$('div#fbStatusList_more').show();
	window.parent.$('#iframe').hide();
</script>