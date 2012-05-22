<?php    
    session_save_path("sess"); 
    session_start();
    
    /* profile.php:
	- displays the information of the user.
	- allows edit.
	- validate edits.
    */    
    
    /* redirect to login page if not logged in; change to token instead */
    if (!isset($_SESSION["username"])) {
	header("location:login.php");
    }
    
    require("lib/lib1.php");
    
     /* The code below validates edits and records the good ones */
          
    $username = $_SESSION["username"];
    $password = $_SESSION["password"];
    $onloadFunction = "";

    if (isset($_POST)) {
	
	$new_username = $_POST["username"];
	$new_password = $_POST["password"]; 
	$new_firstname = $_POST["firstname"];
	$new_lastname = $_POST["lastname"];
	$new_year = $_POST["year"];
	$new_month = $_POST["month"];
	$new_day = $_POST["day"];
	$new_email = $_POST["email"];

	/* First case: user id was entered */
	if ($new_username != "" && $new_username != $username) {
	    
	    if (checkName($new_username)) {
		
		/* update the record.txt :
			- read the file into string		
			- explode into an array
			- find & edit the username entry
			- implode the array into a string
			- write to the file
		*/
		$content = get_from_file("data/record.txt");		
		
		$username_size = strlen($username);
		$size = sizeof($content);
		for($i = 0; $i < $size; $i += 1) {
		    if (substr($content[$i], 0, $username_size) == $username) {
			$content[$i] = $new_username . "&" . $password . "\n";
		    }
		}
		
		write_to_file("data/record.txt", $content);
		
		/* update profile.txt:
			- almost as the same as above, but we already know $content[0] is the username.		
		*/
		$content = get_from_file("data/$username/profile.txt");
		
        	$content[0] = $new_username;		
		
		write_to_file("data/$username/profile.txt", $content);	
		
		/* update data/$username directory */
		rename("data/$username", "data/$new_username");
		
		/* update $_SESSION data */
		$_SESSION["username"] = $new_username;
		$username = $new_username;
		
		$message = "Your <span class=\'info\'>User id</span> was successfully changed!";
		$onloadFunction = "onload=\"displayMessage('$message')\"";
	    } else {
		$message = "This User Id is already taken!";
		$onloadFunction = "onload=\"displayMessage('$message')\"";
	    }
	
	/* Second case: password was entered */    
	} else if ($new_password != "" && $new_password != $password) {
	    
	    /* update the record.txt :
		    - do the same as user id.
	    */
	    $content = get_from_file("data/record.txt");
	    
	    $username_size = strlen($username);
	    $size = sizeof($content);
	    for($i = 0; $i < $size; $i += 1) {
		if (substr($content[$i], 0, $username_size) == $username) {
		    $content[$i] = $username . "&" . $new_password . "\n";
		}
	    }
	    
	    write_to_file("data/record.txt", $content);
	    
	    /* update profile.txt:
		    - do the same as user id, except update $content[1].
	    */
	    $content = get_from_file("data/$username/profile.txt");
	    
	    $content[1] = $new_password;		
	    
	    write_to_file("data/$username/profile.txt", $content);
	    
	    /* update $_SESSION data */
	    $_SESSION["password"] = $new_password;
	    $password = $new_password;
		
	    $message = "Your <span class=\'info\'>Password</span> was successfully changed!";
	    $onloadFunction = "onload=\"displayMessage('$message')\"";
    
	} else if ($new_firstname != "") {

	     /* update profile.txt:
		    - do the same as user id, except only update $content[2].
	    */
	    $content = get_from_file("data/$username/profile.txt");
	    
	    // the user's real name is stored as "firstname/lastname"
	    $fullname = explode("/", $content[2]);
	    $fullname[0] = $new_firstname;
	    $fullname = implode("/", $fullname);
	    $content[2] = $fullname;
	    
	    write_to_file("data/$username/profile.txt", $content);
	    
	    $message = "Your <span class=\'info\'>firstname</span> was successfully changed!";
	    $onloadFunction = "onload=\"displayMessage('$message')\"";
	} else if ($new_lastname  != "") {
	     
	     /* update profile.txt:
		    - do the same as user id, except only update $content[2].
	    */
	    $content = get_from_file("data/$username/profile.txt");	    
	    
	    // the user's real name is stored as "firstname/lastname"
	    $fullname = explode("/", $content[2]);
	    $fullname[1] = $new_lastname;
	    $fullname = implode("/", $fullname);
	    $content[2] = $fullname;
	    
	    write_to_file("data/$username/profile.txt", $content);
	    
	    $message = "Your <span class=\'info\'>Lastname</span> was successfully changed!";
	    $onloadFunction = "onload=\"displayMessage('$message')\"";
	    
	} else if ($new_day != "") {

	    $content = get_from_file("data/$username/profile.txt");
	    if ( $new_day > 0 && $new_day < 32) {
		$birthdate = $new_year . "/" . $new_month . "/" . $new_day;
		$content[3] = $birthdate;
		write_to_file("data/$username/profile.txt", $content);

		$message = "Your <span class=\'info\'>Birthdate</span> was successfully changed!";
		$onloadFunction = "onload=\"displayMessage('$message')\"";
	    } else {
		$message = "Invalid Date of birth!";
		$onloadFunction = "onload=\"displayMessage('$message')\"";
	    } 
	} else if ($new_email != "") {
	    
	    $content = get_from_file("data/$username/profile.txt");	    
	    
	    if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $new_email)) {
		
		$content[4] = $new_email;
		write_to_file("data/$username/profile.txt", $content);
		
		$message = "Your <span class=\'info\'>E-mail Address</span> was successfully changed!";
		$onloadFunction = "onload=\"displayMessage('$message')\"";

	    } else {
	    
		$message = "Invalid Email Address!";
		$onloadFunction = "onload=\"displayMessage('$message')\"";
	    }
	}
	
    }    
    
    /* The code below displays all information from file */
    
    // get all the user's information     
	
    if ($profile = file_get_contents("data/$username/profile.txt", "r")) {
	// see register.php for what's inside profile.txt
	$profile = explode("\n", $profile);
	
	$fullname = explode("/", $profile[2]);	
	$birthdate = $profile[3];
	$email = $profile[4];
    } else {
	// profile.txt has been unintentionally deleted or is inaccessible
    }


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" > 
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <title>Intercessor User Profile</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css" />
    <link rel="stylesheet" type="text/css" href="styles/form.css" />
    <script language="javascript" src="lib/profile.js"></script>
    
</head>
<body <?php echo $onloadFunction; ?>>
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
	<h3 class="subheader"><span class="username"><?php echo $username; ?></span>'s Profile</h3>
    </div>
    <div class="content">
	<p class="subheadersmall">Your user information is displayed below.</p>
	<div class="subcontent">
	    <!-- a form for submitting edits -->
	    <div id="message"></div>
	    <table id="userinfo" class="largeform">
		<thead><tr><th> Login Information </th></tr></thead>
		<tbody>
		<tr>
		    <td>Your user id:</td><td class="info"><?php echo $username; ?></td><td><a onclick="edit(1, 'username')">edit</a></td>
		</tr>
		<tr>
		    <td>Your password:</td><td class="info"><?php echo $password; ?></td><td><a onclick="edit(2, 'password')">edit</a></td>
		</tr>
		</tbody>
		<tr><td>&nbsp</td></tr>
		<thead><tr><th> Additional Information </th></tr></thead>
		<tbody>
		<tr>
		    <td>Your First Name:</td><td class="info"><?php echo $fullname[0]; ?></td><td><a onclick="edit(5, 'firstname')">edit</a></td>
		</tr>
		<tr>
		    <td>Your Last Name:</td><td class="info"><?php echo $fullname[1]; ?></td><td><a onclick="edit(6, 'lastname')">edit</a></td>
		</tr>	
		<tr>
		    <td>Your Birthdate:</td><td class="info"><?php echo $birthdate; ?></td><td><a onclick="edit(7, 'birthdate')">edit</a></td>
		</tr>		
		<tr>
		    <td>Your E-mail Address:</td>
		</tr>
		<tr>
		    <td></td><td class="info" align="center"><?php echo $email; ?></td><td><a onclick="edit(9, 'email')">edit</a></td>
		</tr>
		</tbody>
	    </table>

	</div>
    </div>	
</div> <!-- end of container -->
</body>
</html>