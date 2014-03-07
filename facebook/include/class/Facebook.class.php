<?php

class Facebook_class
{
	var $cookie;
	
	function Facebook_class() {
		$this->cookie = $this->get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);
	}
	
	//http://developers.facebook.com/docs/reference/dialogs/#display
	function display_status_update($criteria=array()) {
		$app_id = $criteria['app_id'];
		$title = $criteria['title'];
		$message = $criteria['message'];
		$name = $criteria['name'];
		$link = $criteria['link'];
		$picture = $criteria['picture'];
		$caption = $criteria['caption'];
		$description = $criteria['description'];
		
		if($app_id=='') $app_id = FACEBOOK_APP_ID;
		if($title=='') $title = '';
		if($message=='') $message = '';
		if($name=='') $name = '';
		if($link=='') $link = '';
		if($picture=='') $picture = '';
		if($caption=='') $caption = '';
		if($description=='') $description = '';
		
		$random = rand(9999,9999999).rand(9999,9999999).rand(9999,9999999);
		
		$js = '
		<script>
		$(\'#fc_post_fb_update_'.$random.'\').live(\'click\', function(event) {
			event.preventDefault();
			FB.ui({
				method: \'feed\',
				message: \''.$message.'\',
				name: \''.$name.'\',
     			link: \''.$link.'\',
     			picture: \''.$picture.'\',
     			caption: \''.$caption.'\',
     			description: \''.$description.'\',
			});
		});
		
		</script>
		';
		
		$content = '<a id="fc_post_fb_update_'.$random.'" href="#" data-role="button" data-icon="check" data-theme="e" rel=external>Update my Status</a>';
		
		return $content.$js;
	}
	
	function displayLoginButton($criteria=array()) {
		echo '<a href="javascript:" onclick="fbActionConnect();" data-role="button" data-theme="e" data-icon="gear">Connect with Facebook</a>';
		/*
		$url = $GLOBALS['app_url'].'/account/connect.php';
		echo '<a href="http://www.facebook.com/dialog/oauth?client_id='.FACEBOOK_APP_ID.'&redirect_uri='.$url.'&display=touch" data-role="button" data-theme="e" data-icon="gear">Connect with Facebook</a>';
		*/
	}
	
	function getAuthPermissionUrl($criteria=array()) {
		$app_id = $criteria['app_id'];
		$redirect_url = $criteria['redirect_url'];
		$permissions = $criteria['permissions']; //publish_stream,share_item,offline_access,manage_pages
		
		if($app_id=='') $app_id = FACEBOOK_APP_ID;
		
		$url = 'https://graph.facebook.com/oauth/authorize?client_id='.$app_id.'&redirect_uri='.$redirect_url.'&scope='.$permissions;
		return $url;
	}
	
	function getUserid() {
		$cookie = $this->getCookie();
		$fb_userid = $cookie['user_id'];
		return $fb_userid;
	}
	
	function getFacebookAccounts($criteria=array()) {
		$token = $criteria['token'];
		
		if($token=='') $token = $this->facebook_access_token;
		
		$url = 'https://graph.facebook.com/me/accounts?access_token='.$token;
		$content = $this->getDataFromUrl($url);
		$content = json_decode($content,true);
		return $content;
	}
	
	function getDataFromUrl($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //to make it support SSL calls on some servers
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	function getProfilePicture() {
		$url = 'https://graph.facebook.com/'.$this->getUserid().'/picture?type=large';
		//$url = 'api.facebook.com/method/fql.query?query=SELECT pic_big FROM user WHERE uid = '.$this->getUserid();
		$url = $this->get_redirect_url($url);
		return $url;
	}
	
	function getUserData() {
		if($this->getCookie()) {
			$url = 'https://graph.facebook.com/me?access_token='.$this->getAccessToken();
			$userData = json_decode($this->getDataFromUrl($url));
			return $userData;
		}
	}
	
	function getCookie() {
		return $this->cookie;
	}
	
	function getAccessToken() {
		return $this->cookie['access_token'];
	}
	
	function loadJsSDK() {
		echo '<div id="fb-root"></div>';
		echo '<script>';
		
	    ?>
		
		function logoutFacebookUser() {
			FB.logout(function(response) {
			  window.location.reload();
			});
		}
		
		function fbActionConnect() {
			FB.login(function(response) {
				if ($.browser.opera) {
				    FB.XD._transport="postmessage";
				    FB.XD.PostMessage.init();
				}
				
				if (response.authResponse) {
					window.location = "<?=$GLOBALS['app_url']?>/account/connect.php";
				}
			}, {scope:'read_stream,publish_stream'});
		}
	    
	    <?php
		
		echo 'window.fbAsyncInit = function() {';
		echo 'FB.init({appId: '.FACEBOOK_APP_ID.', status: true, cookie: true, xfbml: true, oauth: true});';
		
		echo '};';
		  
		echo '(function() {';
			echo 'var e = document.createElement(\'script\'); e.async = true;';
		    echo 'e.src = document.location.protocol +';
		    echo '\'//connect.facebook.net/en_US/all.js\';';
		    echo 'document.getElementById(\'fb-root\').appendChild(e);';
		echo '}());';
		  
		echo '</script>';
	}
	
	function parse_signed_request($signed_request, $secret) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
		
		// decode the data
		$sig = $this->base64_url_decode($encoded_sig);
		$data = json_decode($this->base64_url_decode($payload), true);
		
		if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
			//error_log('Unknown algorithm. Expected HMAC-SHA256');
			return null;
		}
		
		// check sig
		$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
		if ($sig !== $expected_sig) {
			//error_log('Bad Signed JSON signature!');
			return null;
		}
		
		return $data;
	}
	
	function base64_url_decode($input) {
		return base64_decode(strtr($input, '-_', '+/'));
	}

	function get_facebook_cookie($app_id, $app_secret) {
	    $signed_request = $this->parse_signed_request($_COOKIE['fbsr_' . $app_id], $app_secret);
	    //$signed_request[uid] = $signed_request[user_id]; // for compatibility 
	    if (!is_null($signed_request)) {
	        $access_token_response = file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=$app_id&redirect_uri=&client_secret=$app_secret&code=$signed_request[code]");
	        parse_str($access_token_response);
	        $signed_request[access_token] = $access_token;
	        $signed_request[expires] = time() + $expires;
	    }
	    return $signed_request;
	}
	
	function get_redirect_url($url) {
		$redirect_url = null; 
	 
		$url_parts = @parse_url($url);
		if (!$url_parts) return false;
		if (!isset($url_parts['host'])) return false; //can't process relative URLs
		if (!isset($url_parts['path'])) $url_parts['path'] = '/';
	 
		$sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
		if (!$sock) return false;
	 
		$request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1\r\n"; 
		$request .= 'Host: ' . $url_parts['host'] . "\r\n"; 
		$request .= "Connection: Close\r\n\r\n"; 
		fwrite($sock, $request);
		$response = '';
		while(!feof($sock)) $response .= fread($sock, 8192);
		fclose($sock);
	 
		if (preg_match('/^Location: (.+?)$/m', $response, $matches)){
			if ( substr($matches[1], 0, 1) == "/" )
				return $url_parts['scheme'] . "://" . $url_parts['host'] . trim($matches[1]);
			else
				return trim($matches[1]);
	 
		} else {
			return false;
		}
	}
	
}

?>