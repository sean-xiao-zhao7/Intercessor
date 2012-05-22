<?php

	######################################################################
	# OVERVIEW:  the message sender funciton
	# ALGORITHM/DEAILS: 
	#    - sends the message to a specific member
	######################################################################	
	
	if(isset($_REQUEST['message']) && isset($_REQUEST['recepient'])){
		
		$recepient = $_REQUEST['recepient'];		
		
		// locks the recepient's file and records the new message 
		$fpLock=fopen("../locks/$recepient.lck", 'r');
		if (flock($fpLock, LOCK_EX)) { 
	
			$message = $_REQUEST['message']; 
			
			/* add this new message to the recepient's "message inbox" */
			$fp = fopen("../data/$recepient/messages.txt", 'a');
			
			/* $message already ends with "\n" */
			fwrite($fp, $message . "\n");
			
			fclose($fp);

			flock($fpLock, LOCK_UN);
		}
		fclose($fpLock);
	}
?>
