<?php
	session_save_path("../sess");
	session_start();
	
	require("../lib/lib1.php");	
	
	######################################################################
	# OVERVIEW:  the message getter funciton
	# ALGORITHM/DEAILS: 
	#    - check if any new message has arrived for the user
	######################################################################	
	
	if(!isset($_SESSION['seen_so_far'])){
		$_SESSION['seen_so_far'] = 0;
	}
	
	/* you, the user */
	$user = $_REQUEST["user"];

	$fpLock=fopen("../locks/$user.lck", 'r');
	if (flock($fpLock, LOCK_SH)) { 
		$array = file("../data/$user/messages.txt");
		flock($fpLock, LOCK_UN);
	}
	fclose($fpLock);

	// return any new messages
	for($i=$_SESSION['seen_so_far'];$i<sizeof($array);$i++){
		echo "$array[$i]\n";
	}
	$_SESSION['seen_so_far']=sizeof($array);
?>
