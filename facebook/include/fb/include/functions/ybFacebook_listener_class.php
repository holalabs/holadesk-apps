<?php

class ybFacebook_listener_class extends yb_params_class
{
	
	function getDataFromUrl($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	function getFacebookFeeds($criteria) {
		$token = $criteria['token'];
		$userid = $criteria['userid'];
		$url = $criteria['url'];
		$ftype = $criteria['ftype'];
		
		if($userid=='') $userid = $this->facebook_userid;
		if($token=='') $token = $this->facebook_access_token;
		if($ftype=='') $ftype = 'feed'; //home, feed, posts
		
		if($url=='') $url = 'https://graph.facebook.com/'.$userid.'/'.$ftype.'?access_token='.$token;
		
		//$url = 'https://graph.facebook.com/1075910094/home?access_token=108293082585187%7C40ba3937a25b6ff627ecbc83-1075910094%7Cuvi5XP16_8rEEJTjdd_jSoZ4yCU&limit=25';
		
		$data = $this->getDataFromUrl($url);
		$data = json_decode($data,true);
		
		return $data;
	}
	
	function getFacebookObject($criteria=array()) {
		$userid = $criteria['userid'];
		//$type = $criteria['type']; //user, event, page
		
		if($userid=='') $userid = $this->facebook_userid;
		
		$url = 'https://graph.facebook.com/'.$userid;
		$content = $this->getDataFromUrl($url);
		$content = json_decode($content,true);
		
		//if($type=='event') $content = $this->formatEventObject($content);
		//else if($type=='user') $content = $this->formatUserPageGroupObject($content);
		//else if($type=='page') $content = $this->formatUserPageGroupObject($content);
		
		return $content;
	}
	
	function getFacebookObjects($criteria) {
		$ids = $criteria['ids'];
		$access_token = $criteria['access_token'];
		
		if($access_token==1) $access_token = '&access_token='.$this->facebook_access_token;
		
		$url = 'https://graph.facebook.com/'.'?ids='.$ids;
		if($access_token!='') $url .= $access_token;
		
		$content = $this->getDataFromUrl($url);
		$content = json_decode($content,true);
		
		return $content;
	}
	
	//doc: http://developers.facebook.com/docs/reference/api/event
	function getFacebookEventConnection($criteria) {
		$id = $criteria['id'];
		$connection = $criteria['connection']; //possible values: feed, attending, invited, maybe
		
		if($connection=='') $connection = 'feed';
		
		if($connection=='feed') $url = 'https://graph.facebook.com/'.$id.'/'.$connection;
		else $url = 'https://graph.facebook.com/'.$id.'/'.$connection.'?access_token='.$this->facebook_access_token;
		
		//echo $url.'<br>';
		
		//$content = @file_get_contents($url,0,null,null);
		$content = $this->getDataFromUrl($url);
		$content = json_decode($content,true);
		
		return $content;
	}
	
	function getFacebookAccounts($criteria=array()) {
		$token = $criteria['token'];
		
		if($token=='') $token = $this->facebook_access_token;
		
		$url = 'https://graph.facebook.com/me/accounts?access_token='.$token;
		//$content = @file_get_contents($url,0,null,null);
		$content = $this->getDataFromUrl($url);
		$content = json_decode($content,true);
		return $content;
	}
	
	function formatUserPageGroupObject($content) {
		
		$picture = 'http://graph.facebook.com/'.$content['id'].'/picture';
		
		if($content['first_name']!=''||$content['last_name']!='') $url = 'http://facebook.com/profile.php?id='.$content['id'];
		else if($content['owner']['name']!=''||$content['owner']['id']!='') $url = 'http://facebook.com/group.php?gid='.$content['id'];
		else if($content['fan_count']>0&&$content['fan_count']!='') $url = $content['link'];
		
		$content2['id'] = $content['id'];
		$content2['name'] = $content['name'];
		$content2['picture'] = $picture;
		$content2['url'] = $url;
		$content2['category'] = $content['category'];
		$content2['fan_count'] = $content['fan_count'];
		
		return $content2;
	}
	
	function formatEventObject($content) {
		
		$picture = 'https://graph.facebook.com/'.$content['id'].'/picture?type=square'; //square, small, large
		$url = 'http://www.facebook.com/event.php?eid='.$content['id'];
		$owner_picture = 'http://graph.facebook.com/'.$content['owner']['id'].'/picture';
		
		$event['id'] = $content['id'];
		$event['owner_name'] = $content['owner']['name'];
		$event['owner_id'] = $content['owner']['id'];
		$event['owner_picture'] = $owner_picture;
		$event['name'] = $content['name'];
		$event['description'] = $content['description'];
		$event['picture'] = $picture;
		$event['url'] = $url;
		$event['start_time'] = $content['start_time'];
		$event['end_time'] = $content['end_time'];
		$event['location'] = $content['location'];
		$event['privacy'] = $content['privacy'];
		$event['updated_time'] = $content['updated_time'];
		$event['street'] = $content['venue']['street'];
		$event['city'] = $content['venue']['city'];
		$event['state'] = $content['venue']['state'];
		$event['country'] = $content['venue']['country'];
		$event['latitude'] = $content['venue']['latitude'];
		$event['longitude'] = $content['venue']['longitude'];
		return $event;
	}
	
	/*
    * All public posts: https://graph.facebook.com/search?q=watermelon&type=post
    * People: https://graph.facebook.com/search?q=mark&type=user
    * Pages: https://graph.facebook.com/search?q=platform&type=page
    * Events: https://graph.facebook.com/search?q=conference&type=event
    * Groups: https://graph.facebook.com/search?q=programming&type=group
    * Check-ins: https://graph.facebook.com/search?type=checkin
	*/
	function searchFacebook($criteria='') {
		$q = $criteria['q'];
		$type = $criteria['type']; //possible values: post, user, page, event, group, checkin
		
		if($type=='') $type='post';
		
		$url = 'https://graph.facebook.com/search?q='.$q.'&type='.$type;
		//$content = @file_get_contents($url,0,null,null);
		$content = $this->getDataFromUrl($url);
		$content = json_decode($content,true);
		
		if($type=='post') $content2 = $this->formatFacebookPosts($content);
		elseif($type=='user') $content2 = $this->formatFacebookUsers($content);
		elseif($type=='page') $content2 = $this->formatFacebookPages($content);
		elseif($type=='event') $content2 = $this->formatFacebookEvents($content);
		elseif($type=='group') $content2 = $this->formatFacebookGroups($content);
		
		return $content2;
	}
	
	function getFacebookFriends($criteria='') {
		$name = $criteria['name'];
		
		if($name=='') $name = 'me';
		
		$url = 'https://graph.facebook.com/'.$name.'/friends?access_token='.$this->facebook_access_token;
		//$content = @file_get_contents($url,0,null,null);
		$content = $this->getDataFromUrl($url);
		$content = json_decode($content,true);
		
		$users = $this->formatFacebookUsers($content);
		
		return $users;
	}
	
	function formatFacebookPages($content) {
		for($i=0; $i<count($content['data']); $i++) {
			$id = $content['data'][$i]['id'];
			$name = $content['data'][$i]['name'];
			$category = $content['data'][$i]['category'];
			
			$picture = 'https://graph.facebook.com/'.$id.'/picture?type=square'; //square, small, large
			$url = 'http://www.facebook.com/profile.php?id='.$id;
			
			$pages[$i]['id'] = $id;
			$pages[$i]['name'] = $name;
			$pages[$i]['picture'] = $picture;
			$pages[$i]['url'] = $url;
			$pages[$i]['category'] = $category;
		}
		return $pages;
	}
	
	function formatFacebookEvents($content) {
		for($i=0; $i<count($content['data']); $i++) {
			$id = $content['data'][$i]['id'];
			$name = $content['data'][$i]['name'];
			$start_time = $content['data'][$i]['start_time'];
			$end_time = $content['data'][$i]['end_time'];
			$location = $content['data'][$i]['location'];
			
			$picture = 'https://graph.facebook.com/'.$id.'/picture?type=square'; //square, small, large
			$url = 'http://www.facebook.com/event.php?eid='.$id;
			
			$events[$i]['id'] = $id;
			$events[$i]['name'] = $name;
			$events[$i]['picture'] = $picture;
			$events[$i]['url'] = $url;
			$events[$i]['start_time'] = $start_time;
			$events[$i]['end_time'] = $end_time;
			$events[$i]['location'] = $location;
		}
		return $events;
	}
	
	function formatFacebookGroups($content) {
		for($i=0; $i<count($content['data']); $i++) {
			$id = $content['data'][$i]['id'];
			$name = $content['data'][$i]['name'];
			
			$picture = 'https://graph.facebook.com/'.$id.'/picture?type=square'; //square, small, large
			$url = 'http://www.facebook.com/group.php?gid='.$id;
			
			$groups[$i]['id'] = $id;
			$groups[$i]['name'] = $name;
			$groups[$i]['picture'] = $picture;
			$groups[$i]['url'] = $url;
		}
		return $groups;
	}
	
	function formatFacebookUsers($content) {
		for($i=0; $i<count($content['data']); $i++) {
			$id = $content['data'][$i]['id'];
			$name = $content['data'][$i]['name'];
			
			$picture = 'https://graph.facebook.com/'.$id.'/picture?type=square'; //square, small, large
			$url = 'http://www.facebook.com/profile.php?id='.$id;
			
			$users[$i]['id'] = $id;
			$users[$i]['name'] = $name;
			$users[$i]['picture'] = $picture;
			$users[$i]['url'] = $url;
		}
		return $users;
	}
	
	function postComment($post_id,$comment) {
		$f1 = new yb_params_class();
		$facebook_access_token = $f1->facebook_access_token;
		if($facebook_access_token!='') {
			$postParms = "access_token=".$facebook_access_token."&message=".$comment;
			//execute post
			$ch = curl_init('https://graph.facebook.com/'.$post_id.'/comments');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postParms);
			$result = curl_exec($ch);
			curl_close($ch);
			
		}
		return $result;
	}
	
	// ################################
	// ################# START Comments
	
	function getFacebookComments($criteria) {
		$id = $criteria['id'];
		$url = $criteria['url'];
		
		if($url=='') {
			$url = 'https://graph.facebook.com/'.$id.'/comments?access_token='.$this->facebook_access_token.'';
		}
		$url = urldecode($url);
		
		$data = $this->getDataFromUrl($url);
		
		$data = json_decode($data,true);
		
		$previous_paging = urlencode($data['paging']['previous']);
		$next_paging = urlencode($data['paging']['next']);
		$commentsList = $this->formatFacebookComments($data);
		
		$comments['comments'] = $commentsList;
		$comments['nb'] = count($commentsList);
		$comments['previous_paging'] = $previous_paging;
		$comments['next_paging'] = $next_paging;
		
		return $comments;
	}
	
	function formatFacebookComments($data) {
		
		$gc1 = new ybGeneral_functions_class();
		
		if(count($data['data'])>0) {
			$i=count($data['data']);
			foreach($data['data'] as $value) {
				$id = $value['id'];
				$from_id = $value['from']['id'];
				$from_name = $value['from']['name'];
				$message = $value['message'];
				$created = $value['created_time'];
				
				$created = $gc1->convertIso8601DateToTimestamp($created);
				//$created = $created-14400; //14400 = 4 hours
				$created = date('Y-m-d H:i:s', $created);
				
				$profile_url = 'http://www.facebook.com/profile.php?id='.$from_id;
				$picture = 'https://graph.facebook.com/'.$from_id.'/picture?access_token='.$this->facebook_access_token;
				
				$comments[$i]['id'] = $id;
				$comments[$i]['from_id'] = $from_id;
				$comments[$i]['name'] = $from_name;
				$comments[$i]['picture'] = $picture;
				$comments[$i]['profile_url'] = $profile_url;
				$comments[$i]['message'] = $message;
				$comments[$i]['created'] = $created;
				$i--;
			}
			arsort($comments);
		}
		return $comments;
	}
	
	function formatFacebookPosts($data) {
		
		$gc1 = new ybGeneral_functions_class();
		
		$i=0;
		foreach($data['data'] as $value) {
			$id = $value['id'];
			$from_id = $value['from']['id'];
			$from_name = $value['from']['name'];
			
			$type = $value['type']; //video, link, status, picture, swf
			$message = $value['message'];
			$picture = $value['picture'];
			$link = $value['link'];
			$source = $value['source']; //for videos
			$name = $value['name']; //for videos or links
			$caption = $value['caption']; //for videos (domain name url) or links
			$description = $value['description']; //for videos
			$icon = $value['icon'];
			$created = $value['created_time'];
			$likes_nb = $value['likes'];
			
			$comments = $value['comments']['data']; //(message, created_time)
			$comments_nb = $value['comments']['count'];
			$action_comment = $value['actions'][0]['link'];
			
			$picture_url = 'https://graph.facebook.com/'.$from_id.'/picture';
			$profile_url = 'http://www.facebook.com/profile.php?id='.$from_id;
			
			$created = $gc1->convertIso8601DateToTimestamp($created);
			//$today = time()-date('Z');
			//$created = $today-$created;
			//$created = $created+date('Z');
			
			//$created = $created-14400; //14400 = 4 hours
			
			//$created = $gc1->time_since($created,$today);
			$created = date('Y-m-d H:i:s', $created);
			//$created = $created.' - '.$today;
			
			$attribution = $value['attribution'];
			
			$dataList[$i]['id'] = $id;
			$dataList[$i]['from_id'] = $from_id;
			$dataList[$i]['from_name'] = $from_name;
			$dataList[$i]['type'] = $type;
			$dataList[$i]['message'] = $message;
			$dataList[$i]['picture'] = $picture;
			$dataList[$i]['link'] = $link;
			$dataList[$i]['source'] = $source;
			$dataList[$i]['name'] = $name;
			$dataList[$i]['caption'] = $caption;
			$dataList[$i]['description'] = $description;
			$dataList[$i]['icon'] = $icon;
			$dataList[$i]['created'] = $created;
			$dataList[$i]['attribution'] = $attribution;
			$dataList[$i]['likes_nb'] = $likes_nb;
			$dataList[$i]['comments'] = $comments;
			$dataList[$i]['comments_nb'] = $comments_nb;
			$dataList[$i]['action_comment'] = $action_comment;
			$dataList[$i]['picture_url'] = $picture_url;
			$dataList[$i]['profile_url'] = $profile_url;
			$i++;
		}
		return $dataList;
	}
	
	function updateFacebookStatus($criteria, $token='') {
		$fb_id = $criteria['fb_id'];
		$message = $criteria['message'];
		$link = $criteria['link'];
		$picture = $criteria['picture'];
		$name = $criteria['name'];
		$caption = $criteria['caption'];
		$description = $criteria['description'];
		$source = $criteria['source'];
		
		if($fb_id=='') $fb_id = 'me';
		
		$criteriaString = '&message='.$message;
		if($link!='') $criteriaString .= '&link='.$link;
		if($picture!='') $criteriaString .= '&picture='.$picture;
		if($name!='') $criteriaString .= '&name='.$name;
		if($caption!='') $criteriaString .= '&caption='.$caption;
		if($description!='') $criteriaString .= '&description='.$description;
		if($source!='') $criteriaString .= '&source='.$source;
		
		if($token=='') $token = $this->facebook_access_token;
		$postParms = "access_token=".$token.$criteriaString;
		
		$ch = curl_init('https://graph.facebook.com/'.$fb_id.'/feed');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postParms);
		$results = curl_exec($ch);
		curl_close($ch);
		return $results;
	}
}

?>