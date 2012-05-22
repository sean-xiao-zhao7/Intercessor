<?php    
    session_save_path("sess"); 
    session_start();

    require("lib/lib1.php");        
    
    $newPageToken=mt_rand();
    $_SESSION['pageToken']=$newPageToken;
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" > 
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <title>Intercessor - The Next-Gen Online Chat</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css" />
     
</head>
<body>
<div class="container"> <!-- see main.css -->
    <div class="header">
	<h2 class="inter">Intercessor Beta</h2>
    </div>    
    <div class="menu">
	<ul class="nav">
	<?php 
	    /* not logged in? */
	    if (!isset($_SESSION["username"])) {
	?>
	    <li><a href="login.php">Login</a></li>
	    <li><a href="register.php">Register</a></li>
	    <li><a href="help.php">Help</a></li>
	<?php } else { ?>
	    <li><a href="main.php">Chat</a></li>
	    <li><a href="profile.php">Profile</a></li>
	    <li><a href="members.php">Members&nbsp;List</a></li>
	    <li><a href="help.php">Help</a></li>
	    <li id="listfiller">&nbsp;</li>
	    <li><a href="logout.php">Logout</a></li>
	    <li>&nbsp;</li>
	<?php } ?>
	</ul>
    </div>    
    <div class="content">
	<h3 class="subheader">Help</h3>
    </div>
    <div class="content">    
	<div class="subcontent">
	    <p>CSC309 A2

Project Name: Intercessor 1.0 
co-Author: Xiao Feng Zhao
Student #: 994752674
Date: 2/23/2010

Nature of the report: summary of issues.

1. 

TO BE COMPLETED</p>

	    <p>Intercessor 1.0 Readme File
For CSC309 A2. Arnold Rosenbloom
------------------------

Menu
1. How to install
2. Protocol
3. Additional notes

How to install
---------------

just open login.php.
For permissions, all files get 644 and folders 755

Protocol
---------

For One-To-One Chatting:

We use a 2 message files system, one for each side the chat.

Client1(C1): addMessage.php?message=<message to be sent to Client2(C2)>&recepient=<C2>
C1: chat.php redisplays the message that was sent by C1.
Server: OK <if message successfully recieved and queued for client>
Server: FAILED <if message could not be queued for client>
Client2: getMessage.php?numToGet=<number of new messages to retrieve from the service>



Addtional notes
---------------

TO BE COMPLETED</p>
	    
	</div>
    </div>	
</div> <!-- end of container -->
</body>
</html>