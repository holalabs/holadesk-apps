<?php
$criteria = stripslashes($_POST['criteria']);
$criteria = json_decode($criteria,true);
$ftype = $criteria['ftype'];
$url = $criteria['url'];


if($ftype=='home'||$ftype=='feed'||$ftype=='posts'||$url!='') {
	
	$fl1 = new ybFacebook_listener_class();
	$data = $fl1->getFacebookFeeds(array('ftype'=>$ftype, 'url'=>urldecode($url)));
	
	$previous_paging = urlencode($data['paging']['previous']);
	$next_paging = urlencode($data['paging']['next']);
	
	$data = $fl1->formatFacebookPosts($data);
	
	//display wall
	if(count($data)>0) {
		$fd1 = new ybFacebook_view_class();
		$display = $fd1->displayStatusList($data);
	}
	
	$fb_display['display'] = $display;
	$fb_display['previous_paging'] = $previous_paging;
	$fb_display['next_paging'] = $next_paging;
	$fb_display = json_encode($fb_display);
	echo $fb_display;
}

//Friends pictures
else if($ftype==12) {
	
	$tl1 = new ybFacebook_listener_class();
	$users = $tl1->getFacebookFriends();
	
	for($i=0; $i<count($users); $i++) {
		$picture = $users[$i]['picture'];
		$name = $users[$i]['name'];
		$picture = $users[$i]['picture'];
		$url = $users[$i]['url'];
		echo '<div style="border-bottom: #cfcfcf 1px solid; padding-bottom:10px;">';
		echo '<a href="'.$url.'" rel="external"><img src="'.$picture.'" style="padding-right:5px;"></a>';
		echo '<a href="'.$url.'" rel="external">'.$name.'</a>';
		echo '</div><br>';
	}
}

?>