<?php    
    session_save_path("sess"); 
    session_start();
    
    /* 
    ######################################################################
    # OVERVIEW:   profile.php:
	//- displays the information of the user.
	//- allows edit.
	//- validate edits.
    # 
    ######################################################################   
    
    /* redirect to login page if not logged in; change to token instead */
    if (!isset($_SESSION["username"])) {
	header("location:login.php");
    }
    
    require("lib/lib1.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" > 
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <title>Intercessor User Profile</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css" />
    <link rel="stylesheet" type="text/css" href="styles/form.css" />
    
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
	<h3 class="subheader">Members List</h3>
    </div>
    <div class="content">
	<p class="subheadersmall">This list displays all registered members.</p>
	<div id="subcontent" class="subcontent">
	    <table class="largeform">

		<thead><tr><th>User Id</th><th>First Name</th><th>Last Name</th><th>Birthdate</th></thead>
		
		<tbody>
		    <?php					
			$oddeven = "even";
			foreach (glob("data/*") as $username) {
			    if ($username != "data/record.txt") {
				$content = get_from_file("$username/profile.txt");
				$fullname = explode("/", $content[2]);
				
				echo "<tr>\n";
				echo "<td class='$oddeven'><span class='info'>" . $content[0] . "</span></td>\n";
				echo "<td class='$oddeven'>" . $fullname[0] . "</td>\n"; 
				echo "<td class='$oddeven'>" . $fullname[1] . "</td>\n";
				echo "<td class='$oddeven'>" . $content[3] . "</td>\n";
				//echo "<td>" . $content[4] . "</td>\n";
				echo "</tr>\n";
				
				/* alternate the 2 background colors */
				if ($oddeven == "odd") {
				    $oddeven = "even";
				} else {
				    $oddeven = "odd";
				}
			    }
			}
			unset($username);
		    ?>
		</tbody>
		
	    </table>

	</div>
    </div>	
</div> <!-- end of container -->
</body>
</html>