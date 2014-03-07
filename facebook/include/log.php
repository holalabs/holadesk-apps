<?php 
	function logToFile($msg) { 
		// open file 
		$fd = fopen("~/on/holalabs.apps/facebook/my.log", "a"); 
		// write string 
		fwrite($fd, $msg . "\n"); 
		// close file 
		fclose($fd); 
	}
?>