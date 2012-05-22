<?php    
    session_save_path("sess"); 
    session_start();
    
    ######################################################################
    # OVERVIEW:  main chat page
    ######################################################################    
    
    $newPageToken=mt_rand();
    $_SESSION['pageToken']=$newPageToken;
    
    /* main.php:
	the portal to chat, profile, members list and help.
    */
    
    
    /* redirect to login page if not logged in; change to token instead */
    if (!isset($_SESSION["username"])) {
	header("location:login.php");
    /* if no chatee is given, refer back to main.php */
    } else if (!isset($_REQUEST["chatee"])) {
	header("location:main.php");
    }
            
    $username = $_SESSION["username"];
    /* the person to chat with */
    $chatee = $_REQUEST["chatee"];
    $display = $chatee;
    if ($chatee == $username) {
	$display = "yourself";
    }
    
    /* clear messages.txt */
    fclose(fopen("data/$username/messages.txt", "w"));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" > 
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <title>Intercessor - The Next-Gen Online Chat</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css" />
    <script language="javascript" src="lib/chat.js"></script>
     
</head>
<!-- onload, set a periodic message retrieval (this not the best way) -->
<body onload="checkMessage('<?php echo "$username"; ?>')" >
<div class="container"> <!-- see main.css -->
    <div class="header">
	<h2 class="inter">Intercessor Beta</h2>
    </div>    
    <div class="menu">
	<ul class="nav">
	    <li><a href="main.php">Chat</a></li>
	    <li><a href="profile.php">Profile</a></li>
	    <li><a href="members.php">Members&nbsp;List</a></li>
	    <li><a href="help.php">Help</a></li>
	    <li id="listfiller">&nbsp;</li>
	    <li><a href="logout.php">Logout</a></li>
	    <li>&nbsp;</li>
	</ul>
    </div>    
    <div class="content">
	<h3 class="subheader">Chat Room</h3>
    </div>
    <div class="content">    
	<p class="subheadersmall">You are chatting with <span class="username"><?php echo $display; ?></a></span>!</p>
	<div class="subcontent">
	    
	    <form name="myForm">
		<input type="text" name="message" /> 
		<input type="button" value="Add Message" onclick="addMessage(message.value, '<?php echo $username; ?>', '<?php echo $chatee; ?>'); message.value=''; " />
		
	    </form>
	    <p>recent</p>
	    <textarea rows="2" cols="25" readonly="readonly" id="recent"></textarea>
	    <p>all messages</p>
	    <textarea rows="10" cols="25" readonly="readonly" id="messages"></textarea>

	    
	</div>
    </div>	
</div> <!-- end of container -->
</body>
</html>