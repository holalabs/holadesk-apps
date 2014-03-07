<?php
include('include/webzone.php');
include('include/presentation/header.php');
?>

<div data-role="page" data-theme="b" id="home">
	<div data-role="content" style="padding-top:0px; margin-top:0px;">
		
		<?php
		
		if($_SESSION['user_id']=='') {
			$f1 = new Facebook_class();
			$f1->displayLoginButton();
			?>
			
			<br>
			
			<?php
		}
		
		else {
			$f1 = new Facebook_class();
			$data = $f1->getUserData();
			$id = $data->id;
			$name = $data->name;
			$link = $data->link;
			$picture = 'http://graph.facebook.com/'.$id.'/picture';
			
			echo '<div data-role="header">';
				
				echo '<div style="text-align:right; border-bottom:#575757 1px solid; padding:5px;">';
					$postUrl = 'http://m.facebook.com/dialog/feed?app_id='.FACEBOOK_APP_ID.'&redirect_uri='.$GLOBALS['app_url'].'&display=touch';
					//echo ' <a href="'.$postUrl.'" data-role="button" data-icon="check" data-theme="e" rel=external>Update</a>';
					echo ' <a href="#" onclick="postView();" data-role="button" data-icon="check" data-theme="e">Post</a>';
					echo '&nbsp;';
					echo ' <a href="'.$GLOBALS['app_url'].'/account/logout.php" data-role="button" data-icon="back" data-theme="c" rel=external>Logout</a></small>';
				echo '</div>';
				
				echo '<table width="100%" style="padding:3px;"><tr>';
				
					echo '<td>';
					echo '<img src="'.$picture.'" style="align:left; vertical-align:middle; padding-right:5px; width:40px;">';
					echo $name;
					echo '</td>';
					
				echo '</tr></table>';
				
				echo '<div data-role="navbar">';
					echo '<ul>';
						echo '<li><a href="#" id="fb_displayHomeBtn" class="ui-btn-active" rel=external>Home</a></li>';
						echo '<li><a href="#" id="fb_displayFeedBtn">My wall</a></li>';
						echo '<li><a href="#" id="fb_displayPostsBtn">My posts</a></li>';
						echo '<li><a href="#" id="fb_displayFriendsBtn">Friends</a></li>';
					echo '</ul>';
				echo '</div>';
				
			echo '</div>';
			
			echo '<br>';

			echo '<iframe style="display:none; width:100%; height:400px;" seamless="seamless" id="iframe" src="'.$GLOBALS['app_url'].'/account/postview.php" allowtransparency="true" frameborder="0"></iframe>';

			echo '<br>';
			
			echo '<div id="fbStatusList"></div>';
			echo '<div id="fbStatusList_more">
			<a id="fbDisplayMoreBtn" href="#" data-role="button" data-icon="arrow-d" style="display:none;">More</a>
			</div>';
		}
		
		?>
		
	</div>
	
</div>

<?php
$f1 = new Facebook_class();
$f1->loadJsSDK();
include('include/presentation/footer.php');
?>