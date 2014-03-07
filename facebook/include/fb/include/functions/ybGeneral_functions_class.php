<?php


class ybGeneral_functions_class {
	
	
	function returnTextWithActiveURL($text,$options='') {
		$target = $options['target'];
		$class = $options['class'];
		$urls = $options['urls']; //urls of the text
		
		if($target!='') $target = 'target="'.$target.'"';
		if($class!='') $class = 'class="'.$class.'"';
		
		if(!is_array($urls)) {
			$reg_exUrl = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
			preg_match_all($reg_exUrl, $text, $urls);	
		}
		
		$witness = array();
		$i=0;
		foreach($urls[0] as $value) {
			if(!in_array($value,$witness)) {
				$urlsTab[$i]['replace'] = '<a href="'.$value.'" rel="nofollow" '.$target.' '.$class.'>'.$value.'</a>';
				$urlsTab[$i]['url'] = $value;
				$i++;
			}
			$witness[] = $value;
		}
		
		for($i=0;$i<count($urlsTab);$i++) {
			$text = str_replace( $urlsTab[$i]['url'], $urlsTab[$i]['replace'], $text);
		}
		
		return $text;
	}
	
	function returnUrlsFromText($text) {
		$reg_exUrl = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		preg_match_all($reg_exUrl, $text, $urls);
		return $urls;
	}
	
	function convertIso8601DateToTimestamp($datestring) {
		// 2010-10-07T11:43:20+0000
		$date = explode('T', $datestring);
		$d1 = explode('-', $date[0]);
		$t1 = explode(':', substr($date[1],0,8));
		$timestamp = mktime($t1[0], $t1[1], $t1[2], $d1[1], $d1[2], $d1[0]);
		return $timestamp;
	}
	
	// converts "D M d H:i:s O Y" ("Tue Jul 13 17:38:21 +0000 2010") to timestamp
	// $date = "Tue Jul 13 17:38:21 +0000 2010";
	function twitterDateToTimestamp($datestring) {
	    $months = array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
	    $date = explode(' ', $datestring);
	    $time = explode(':', $date[3]);
	    // Convert to time
	    $timestamp = mktime($time[0], $time[1], $time[2], $months[$date[1]], $date[2], $date[5]);
	    return $timestamp;
	}
	
	// Sun, 31 Oct 2010 16:36:06 +0000
	function twitterDateToTimestamp2($datestring) {
	    $months = array('Jan'=>1,'Feb'=>2,'Mar'=>3,'Apr'=>4,'May'=>5,'Jun'=>6,'Jul'=>7,'Aug'=>8,'Sep'=>9,'Oct'=>10,'Nov'=>11,'Dec'=>12);
	    $date = explode(' ', $datestring);
	    $time = explode(':', $date[4]);
	    // Convert to time
	    $timestamp = mktime($time[0], $time[1], $time[2], $months[$date[2]], $date[1], $date[3]);
	    return $timestamp;
	}
	
	function cleanURLString($string) {
	    $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖØÙÚÛÜİŞßàáâãäåæçèéêëìíîïğñòóôõöøùúûıışÿ';
	    $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
	    $string = strtr($string, $a, $b);
	    $string = strtolower($string);
		//echo $string.'<br>';
	    $string = eregi_replace("[^a-z0-9]",' ',$string);
	    $string = removeSpaces($string);
	    return $string;
	}
	
	function formatDateToTimestamp($date) {
		if(strlen($date)<=10) {
			$dateTab = explode("-",$date);
			$timestamp = mktime(0, 0, 0, $dateTab[1], $dateTab[2], $dateTab[0]);
		}
		if(strlen($date)==19) {
			$time = substr($date,11,8);
			$date = substr($date,0,10);
			$dateTab = explode("-",$date);
			$timeTab = explode(":",$time);
			$timestamp = mktime($timeTab[0], $timeTab[1], $timeTab[2], $dateTab[1], $dateTab[2], $dateTab[0]);
		}
		return $timestamp;
	}
	
	/* Works out the time since the entry post, takes a an argument in unix time (seconds) */
	function time_since($original,$today='') {
	    // array of time period chunks
	    $chunks = array(
		array(60 * 60 * 24 * 365 , 'year'),
		array(60 * 60 * 24 * 30 , 'month'),
		array(60 * 60 * 24 * 7, 'week'),
		array(60 * 60 * 24 , 'day'),
		array(60 * 60 , 'hour'),
		array(60 , 'min'),
		array(1 , 'sec'),
	    );
	
	    if($today=='') $today = time(); /* Current unix time  */
	    $since = $today - $original;
	
	    // $j saves performing the count function each time around the loop
	    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
			$seconds = $chunks[$i][0];
			$name = $chunks[$i][1];
		
			// finding the biggest chunk (if the chunk fits, break)
			if (($count = floor($since / $seconds)) != 0) {
			    break;
			}
	    }
	
	    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
	
	    if ($i + 1 < $j) {
			// now getting the second item
			$seconds2 = $chunks[$i + 1][0];
			$name2 = $chunks[$i + 1][1];
		
			// add second item if its greater than 0
			if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
			    $print .= ($count2 == 1) ? ', 1 '.$name2 : " $count2 {$name2}s";
			}
	    }
	    return $print;
	}

}

?>