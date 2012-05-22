<?php
    session_save_path("sess");
    session_start();
    
    require("lib/lib1.php");    
      
    ######################################################################
    # OVERVIEW:  login.php:
    # this offers login; links to registration.
    ######################################################################
    
    /* redirect to menu if already logged in
        this needs a page token, which is to be added later.    
    */
    if (isExpiredPage() || isset($_SESSION["username"])) {
        header("location:main.php");
    }
    
    $newPageToken=mt_rand();
    $_SESSION['pageToken']=$newPageToken;
    
    /* compare each line of "data/record.txt" to find a match */
    function authenticate($username, $password) {
            
            if (!file_exists("data/record.txt")) {
                fclose(fopen("data/record.txt", "a"));
                return false;
            }
            
            $record = file("data/record.txt");
            foreach ($record as $entry) {
                if ($username . "&" . $password == trim($entry)) {
                    return true;
                }            
            }
            unset($entry);
            return false;
    }
    
    /* check the form's entries */
    if (isset($_POST["subtest"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        
        if ($username == "" || $password == "") {
                $name_error = "No Name/Password";
        } else {    
        
            $username = trim($username);
            $password = trim($password);
            
            if (authenticate($username, $password)) {

                $_SESSION["username"] = $username;
		$_SESSION["password"] = $password;
		
		/*//write this user to "online.txt"	    
		
		$fpLock=fopen('locks/lock1.lck', 'r');
		if (flock($fpLock, LOCK_SH)) { // shared lock
		    
		    $user = $username;
		    $array = get_from_file("online.txt");
		    $array[] = $user;
		    write_to_file("online.txt", $array);
		
		    flock($fpLock, LOCK_UN); // unlock the lock file
		}
		fclose($fpLock); */
    
                // redirect to main.php
                header("location:main.php");
            } else {
                $name_error = "Invalid Name/Password";
            }
        }
    }        
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" > 
<head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <title>Intercessor Login Page</title>
    <link rel="stylesheet" type="text/css" href="styles/main.css" />
    <link rel="stylesheet" type="text/css" href="styles/form.css" />
    
</head>
<body>    
<div class="container">    
    <div class="header">
	<h2 class="inter">Intercessor Beta</h2>
    </div>    
    
    <div class="menu">
	<ul class="nav">
	    <li><a href="register.php">Register</a></li>
	    <li><a href="help.php">Help</a></li>
	    <li>&nbsp;</li>
	</ul>
    </div>
    
    <div class="content">
 	<h3 class="subheader">User Login</h3>
    </div>

    <div class="loginregcontent">
	<form action="login.php" method="post">
	<input type="hidden" name="subtest" value="1" />
	<input type="hidden" name="pageToken" value="<?=$newPageToken ?>" />
        <table class="form">
            <tr><td>User Id:</td><td><input type="text" name="username" maxlength="10" value="<?php echo $username; ?>" /><br /></td></tr>
            <tr><td>Password:</td><td><input type="password" name="password" maxlength="10" /><br /></td></tr>
            <tr><td class="error" colspan="2"><?php echo $name_error; ?></td></tr>
            <tr><td></td><td><input type="submit" value="Login Now" /></td></tr>            
        </table>
    </form>
    </div>
</div>   
</body>
</html>