<?php
$criteria = stripslashes($_POST['criteria']);
$criteria = json_decode($criteria,true);
$ftype = $criteria['ftype'];

/*
if($ftype==1||$ftype==2||$ftype==3) {
	$yl1 = new ybFacebook_listener_class();
	$status = $yl1->getFacebookFeeds($criteria);
	echo $status;
}
*/

if($ftype==12) {
	$post_id = $criteria['post_id'];
	$comment = $criteria['comment'];
	
	//add the comment
	$yp1 = new ybFacebook_listener_class();
	$yp1->postComment($post_id,$comment);
}

else if($ftype==13) {
	$comment = $criteria['comment'];
	
	$yp1 = new ybFacebook_listener_class();
	$fb_access_token = $yp1->facebook_access_token;
	$fb_userid = $yp1->facebook_userid;
	$fb_username = $yp1->facebook_username;
	$fb_name = $yp1->facebook_name;
	
	$profile_url = 'http://www.facebook.com/profile.php?id='.$fb_userid;
	$picture = 'https://graph.facebook.com/me/picture?access_token='.$fb_access_token;
	
	$date = time();
	$date = $date+10740;
	$date = date('Y-m-d H:i:s', $date);
	
	$fd1 = new ybFacebook_view_class();
	$criteria2['userid'] = $fb_userid;
	$criteria2['link'] = $profile_url;
	$criteria2['picture'] = $picture;
	$criteria2['name'] = $fb_name;
	$criteria2['comment'] = $comment;
	$criteria2['created'] = $date;
	echo $fd1->displaySingleCommentHTML($criteria2);
}

?>