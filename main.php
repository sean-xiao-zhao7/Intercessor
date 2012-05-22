<?php    
    session_save_path("sess"); 
    session_start();

    require("lib/lib1.php");        
    
    $newPageToken=mt_rand();
    $_SESSION['pageToken']=$newPageToken;

    ######################################################################
    # OVERVIEW:   main.php:
    # the portal to chat, profile, members list and help.
    ######################################################################
    

    /* redirect to login page if not logged in; change to token instead */
    if (!isset($_SESSION["username"])) {
	header("location:login.php");
    }
    
    $username = $_SESSION["username"];

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
	<h3 class="subheader">Chat Room Lobby</h3>
    </div>
    <div class="content">    
	<p class="subheadersmall">Welcome <span class="username"><?php echo $username; ?></a></span>!</p>
	<div class="subcontent">
	    <p>Choose one of the members to chat with:</p>
	    <ul id="chatlist">
		<?php
			
			$array = get_from_file("data/record.txt");
				
			if (sizeof($array) == 0) {
			    echo "<li>There are no members to chat with.</li>";
			} else {
			    foreach ($array as $entry) {
				//$_SESSION["online_users"][] = $entry;
				$entry = explode("&", $entry);
			        echo "<li><a href=\"chat.php?chatee=$entry[0]\">$entry[0]</a></li>\n";
			    }
			}
		?>
	    </ul>
	</div>
    </div>	
</div> <!-- end of container -->
</body>
</html>