<?php

class ybFacebook_view_class extends yb_params_class
{
	
	
	function displayStatusList($data) {
		
		//$gc1 = new ybGeneral_functions_class();
		
		$display = '';
		
		foreach($data as $value) {
			$id = $value['id'];
			$from_id = $value['from_id'];
			$from_name = $value['from_name'];
			$type = $value['type'];
			$message = $value['message'];
			$picture = $value['picture'];
			$link = $value['link'];
			$source = $value['source'];
			$name = $value['name'];
			$caption = $value['caption'];
			$description = $value['description'];
			$icon = $value['icon'];
			$created = $value['created'];
			$attribution = $value['attribution'];
			$likes_nb = $value['likes_nb'];
			$comments = $value['comments'];
			$comments_nb = $value['comments_nb'];
			$action_comment = $value['action_comment'];
			$picture_url = $value['picture_url'];
			$profile_url = $value['profile_url'];
			
			$display .= '<table id="itemBox" value="'.$id.'" border=0 style="padding-top: 0px;padding-bottom: 10px;border-bottom: 1px solid #cfcfcf;" width=100% cellpadding=0 cellspacing=0><tr>';
			
			$display .= '<td width=20 valign="top">';
			$display .= '<a href="'.$profile_url.'" title="'.$from_name.'" target="_blank"><img src="'.$picture_url.'" style="padding-right:10px;"></a>';
			$display .= '</td>';
			
			$display .= '<td valign="top">';
				
				$display .= '<table width=100% cellpadding=0 cellspacing=0><tr><td>';
				$display .= '<p style="padding-bottom:8px;"><a href="'.$profile_url.'" title="'.$from_name.'" target="_blank">'.$from_name.'</a> ';
				if($message!='') $display .= ''.$message.'';
				$display .= '</p>';
						
				if($type=='status') {
					
				}
				
				else if($type=='photo') {
					$display .= '<a href="'.$link.'" target="_blank"><img src="'.$picture.'" style="padding-right:10px;" align="left"></a>';
					if($name!='') $display .= '<a href="'.$link.'" target="_blank">'.$name.'</a><br>';
					if($caption!='') $display .= '<small><span>'.$caption.'</a></small>';
				}
				
				else if($type=='link') {
					if($picture!='') $display .= '<a href="'.$link.'" target="_blank" title="'.$name.'"><img src="'.$picture.'" style="padding-right:10px;" align="left"></a>';
					$display .= '<a href="'.$link.'" target="_blank" title="'.$caption.'">'.$name.'</a><br>';
					if($caption!='') $display .= '<small><span>'.$caption.'</a></small><br>';
					if($description!='') $display .= '<small><span>'.$description.'</a></small>';
				}
				
				else {
					
					$display .= '<div id="domImage" class="videoPlayBox" style="float:left;" >';
						$display .= '<a href="'.$link.'" target="_blank" title="'.$name.'">';
							if($picture!='') {
								$display .= '<img src="'.$picture.'" width=80 height=55 style="padding-right:10px;"/>';
								$display .= '<span class="play"></span>';
							}
						$display .= '</a>';
					$display .= '</div>';
					
					if($name!='') $display .= '<a href="'.$link.'" target="_blank">'.$name.'</a><br>';
					if($description!='') $display .= '<small><span>'.$description.'</span></small>';
				}
				$display .= '</td></tr>';
				
				$display .= '<tr><td style="padding-top:5px;">';
				if($icon!='') $display .= '<img src="'.$icon.'"> ';
				$display .= '<small>';
				$display .= '<a href="'.$action_comment.'" target="_blank" title="'.$created.'" class="grey prettyDate">'.$created.'</a>';
				//if($attribution!='') $display .= '<span> via '.$attribution.'</span>';
				$display .= '</small>';
				
				//$display .= ' - <small><a id="openCloseAddComment" href="#">Comment</a></small> - <a href="comments.php" rel="external" data-transition="slide">gogogo</a>';
				
				// ##################### //
				// START Comment Section //
				// ##################### //
				
				/*
				$commentCriteria = array();
				$commentCriteria['resourceid'] = $id;
				$commentCriteria['commentCount'] = $comments_nb;
				$commentCriteria['collapse'] = 1; //comments collapsed
				$commentCriteria['hideOnAction'] = 1;
				
				//comments display
				$display .= '<div id="domAddCommentBox" style="padding-top:10px;display:none;">';
					$display .= $this->displayAddComment($commentCriteria);
				$display .= '</div>';
				$display .= '<div style="padding-top:5px;">';
					$display .= $this->displayComments($commentCriteria);
				$display .= '</div>';
				*/
				
				// ################### //
				// END Comment Section //
				// ################### //
				
				$display .= '</td></tr></table>';
				
			$display .= '</td>';
			$display .= '</tr></table><br>';
		}
		
		return $display;
	}
}

?>