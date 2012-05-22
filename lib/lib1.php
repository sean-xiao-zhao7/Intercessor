<?php

    ######################################################################
    # OVERVIEW:  library for I/O and error-checking.
    ######################################################################	
    

    ######################################################################
    # OVERVIEW:  checks for duplication of usernames
    ######################################################################	
    
    function checkName($username) {
    
	$fpLock=fopen('locks/lock0.lck', 'r');
	if (flock($fpLock, LOCK_SH)) { // shared lock
	    $record = file("data/record.txt");
	    foreach ($record as $entry) {
		/* the record has "username&password" entries */
		$entry = explode("&", $entry);
		if ($entry[0] == $username) {
		    return false;
		}
	    }
	    unset($entry);
	    flock($fpLock, LOCK_UN);
	}
	return true;
	
    }
    
    
    /* the following two functions works for profile.php */
    
    ######################################################################
    # OVERVIEW:  returns a file's content, splitted by "\n", into an array.
    # NOTE: This is similar to file(), but can be modified to explode with different characters
    ######################################################################    

    function get_from_file($path) {
	/* this reads the file, then explodes it by "\n" into an array
	*  returns the array.
	*  quite similar to the built-in "file($path)" function.
	*/
	$fpLock=fopen('locks/lock2.lck', 'r');
	if (flock($fpLock, LOCK_SH)) { // shared lock
		
	    if ($content = file_get_contents($path)) {
		$content = explode("\n", $content);
		
		flock($fpLock, LOCK_UN);		
		return $content;
	    } else {
		/* file not found; raise an exception here */
		flock($fpLock, LOCK_UN); 
		return false;
	    }
	    
	    
	}
    }
    
    ######################################################################
    # OVERVIEW:  given an array, combines the elements by "\n" into a string and outputs it to a file.
    ######################################################################  
        
    function write_to_file($path, $content) {
	/* 
	    given an array, implodes it into a string by "\n",
	    writes the string to file.
	*/
	$fpLock=fopen('locks/lock2.lck', 'r');
	if (flock($fpLock, LOCK_SH)) {
	    $content = implode($content, "\n");
	    file_put_contents($path, $content);
	    flock($fpLock, LOCK_UN); 
	}
    }
    
    function getPageToken() {

    }
    
    function isPostback(){
		$pageToken=$_REQUEST['pageToken'];
		$pageToken_stored=$_SESSION['pageToken'];
		if(isset($pageToken) && isset($pageToken_stored) && $pageToken==$pageToken_stored){
			return true;
		} else {
			return false;
		}
	}

	function isExpiredPage(){
		$pageToken=$_REQUEST['pageToken'];
		$pageToken_stored=$_SESSION['pageToken'];

		if(!isset($pageToken) && !isset($pageToken_stored)) return false;
		if(isset($pageToken) && !isset($pageToken_stored)) return true;
		if(!isset($pageToken) && isset($pageToken_stored)) return false;
		if(isset($pageToken) && isset($pageToken_stored) && $pageToken!=$pageToken_stored){
			return true;
		} else {
			return false;
		}
	}
    
?>