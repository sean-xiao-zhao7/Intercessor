<?php


    
    ######################################################################
    # OVERVIEW:   user registration validation after posting
    #    featuring: 
    #        - empty fields check
    #        - email address format check
    #       - day of the month check
    #       - password retype match check
    #        - duplicated username check
    ######################################################################
    require("lib/lib1.php");

    if (isset($_POST["regtest"])) {
        
        /* perhaps an array should be utilized here */
        $username = $_POST["username"];
        $password = $_POST["password"];
        $password2 = $_POST["password2"];
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $month = $_POST["month"];
        $year = $_POST["year"];
        $day = $_POST["day"];
        $email = $_POST["email"];
              
        $firstname_error = "";
        $lastname_error = "";
        $email_error = "";
        $day_error = "";
        $username_error = "";
        $password_error = "";
        $empty_error = "";
        
        /* reset the drop-down selections of birthdate */        
        
        // reset year
        $part1 = "\$selected$year";
        $part2 = "\"selected=\\\"selected\\\"\";";
        $str = $part1 . "=" . $part2;
        eval($str);
        
        // reset month
        $part1 = "\$selected$month";
        $part2 = "\"selected=\\\"selected\\\"\";";
        $str = $part1 . "=" . $part2;
        eval($str);
                
        /* the validation function to be called later */
        function validate() {

            global $firstname;
            global $lastname;
            global $email;
            global $day;
            global $username;
            global $password;        
            global $password2;
	    
            /* better switch to an array of these */
            global $firstname_error;
            global $lastname_error;
            global $email_error;
            global $day_error;
            global $username_error;
            global $password_error;
            global $empty_error;    
             
            /* check for empty fields */ 
            if (trim($username) == "" || trim($password) == "" || trim($email) == "" || trim($firstname) == "" || trim($lastname) == "" || trim($day) == "" || trim($password2) == "") {
                $empty_error = "Not all fields are filled!";
                return false;
            }            
            
            /* check the email
                The credit goes to Douglas Lovell:
                http://www.linuxjournal.com/article/9585
            */            
            if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
                $email_error = "Invalid email address!";
                return false;
            }
            
            /* checks the birth date */
            if ( ((int)$day) <= 0 || (($month == 2 && $day > 28) || $day > 31)) {
                $day_error = "Wrong day!";
                return false;
            }            
            
            /* check password */
            if ($password != $password2) {
                $password_error = "Passwords are not equal!";
                return false;
            }
                  
            /* check if the password record exists; it does not if no one has registered */
            if (!file_exists("data/record.txt")) {            
                    
                    if (!file_exists("data")) {
                        mkdir("data", 755);
                    }
                    
                    fclose(fopen("data/record.txt", "a"));

                    return true;
            }
                        
            /* checks for duplication of usernames */
            if (!checkName($username)) {
		return false;
	    }
            
            return true;
        }
        
        /* the validation begins here */

        /* record the user data to text files (upgrade to a database if able) */
        if (validate()) {
          
            /* record.txt:
		- stores the user id/password combos of all users
		- format: userid&password\n
            */
            $new_entry = $username . "&" . $password . "\n";
	    
	    $fpLock=fopen('locks/lock0.lck', 'r');
	    if (flock($fpLock, LOCK_SH)) { // shared lock
		$record = fopen("data/record.txt", "a");
		fwrite($record, $new_entry);          
		fclose($record);
		flock($fpLock, LOCK_UN);
	    }
            
            /* profile.txt
		- each user has one
		- lives in the directory named after the user id
		- stores (in this order, separated by "\n"):
		    - user id 
		    - password
		    - fullname (separated by "\")
		    - birthdate (separated by "\")
		    - email
	    */
            $name = $firstname . "/" . $lastname . "\n";
            $birthdate = $year . "/" . $month . "/" . $day . "\n";
                                   
            if (!file_exists("data/$username")) {
                mkdir("data/$username");
            }
            
            $record = fopen("data/$username/profile.txt", "a");
            fwrite($record, $username . "\n");
            fwrite($record, $password . "\n");
            fwrite($record, $name);
            fwrite($record, $birthdate);
            fwrite($record, $email);
            fclose($record);
            
	    /* get a lock for this user, for locking the messages text file */
	    
	    fclose(fopen("locks/$username.lck", 'w'));

	    /* as well as the messages text file */
	    fclose(fopen("data/$username/messages.txt", 'w'));
	    
            /* signal the new content to be displayed */
            $_REQUEST["registered"] = 1;
        }
    }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" > 
<head>
   
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
    <link rel="stylesheet" type="text/css" href="styles/main.css" />     
    <link rel="stylesheet" type="text/css" href="styles/form.css" />   
    <title>Intercessor User Registration</title>

</head>
<body>
<div class="container">
    <div class="header">
	<h2 class="inter">Intercessor Beta</h2>
    </div>    
    
    <div class="menu">
	<ul class="nav">
	    <li><a href="login.php">Login</a></li>	    	
	    <li><a href="help.php">Help</a></li>
	    <li>&nbsp;</li>
	</ul>
    </div>

    <div class="content">
 	<h3 class="subheader">User Registration</h3>
    </div>
    
    <div class="loginregcontent">
	<form action="register.php" method="post">
	    <table class="form">
	    <?php
		if (isset($_REQUEST["registered"])) {
	    ?>
		    <tr><td colspan="3" align="center"><p>Congratulations <span class="username"><?php echo $_POST["username"]; ?></a></span>, you have successfully registered!<br />Continue to the <a href='login.php'>login page</a></p></td></tr>
	    <?php
		} else {
	    ?>
		    <tr><td>First Name</td><td><input type="text" name="firstname" maxlength="20" value="<?php echo $_POST['firstname']; ?>" /><br /></td><td class="error"><?php echo $firstname_error; ?></td></tr>
		    <tr><td>Last Name</td><td><input type="text" name="lastname" maxlength="20" value="<?php echo $_POST['lastname']; ?>" /><br /></td><td class="error"><?php echo $lastname_error; ?></td></tr>
		    <tr><td>E-mail Address:</td><td><input type="text" name="email" maxlength="30" value="<?php echo $_POST['email']; ?>" /><br /></td><td class="error"><?php echo $email_error; ?></td></tr>
		    <tr><td>Birthdate:</td><td>
			<!-- only has years 81 to 89. Can add more later -->
			<select name="year">
			    <option value="1981" <?php echo $selected1981 ?>>1981</option>
			    <option value="1982" <?php echo $selected1982 ?>>1982</option>
			    <option value="1983" <?php echo $selected1983 ?>>1983</option>
			    <option value="1984" <?php echo $selected1984 ?>>1984</option>
			    <option value="1985" <?php echo $selected1985 ?>>1985</option>
			    <option value="1986" <?php echo $selected1986 ?>>1986</option>
			    <option value="1987" <?php echo $selected1987 ?>>1987</option>
			    <option value="1988" <?php echo $selected1988 ?>>1988</option>
			    <option value="1989" <?php echo $selected1989 ?>>1989</option>
			</select>
			<select name="month">
			    <option value="1" <?php echo $selected1 ?>>Jan</option>
			    <option value="2" <?php echo $selected2 ?>>Feb</option>
			    <option value="3" <?php echo $selected3 ?>>Mar</option>
			    <option value="4" <?php echo $selected4 ?>>Apr</option>
			    <option value="5" <?php echo $selected5 ?>>May</option>
			    <option value="6" <?php echo $selected6 ?>>Jun</option>
			    <option value="7" <?php echo $selected7 ?>>Jul</option>
			    <option value="8" <?php echo $selected8 ?>>Aug</option>
			    <option value="9" <?php echo $selected9 ?>>Sep</option>
			    <option value="10" <?php echo $selected10 ?>>Oct</option>
			    <option value="11" <?php echo $selected11 ?>>Nov</option>
			    <option value="12" <?php echo $selected12 ?>>Dec</option>
			</select>
			<input type="text" name="day" maxlength="2" size="1" value="<?php echo $_POST['day']; ?>" />                
			</td><td class="error"><?php echo $day_error; ?></td></tr>
		    <tr><td>New User Id</td><td><input type="text" name="username" maxlength="10" value="<?php echo $_POST['username']; ?>" /><br /></td><td class="error"><?php echo $username_error; ?></td></tr>
		    <tr><td>New Password:</td><td><input type="password" name="password" maxlength="10" /><br /></td><td class="error"><?php echo $password_error; ?></td></tr>
		    <tr><td>Retype Password:</td><td><input type="password" name="password2" maxlength="10" /><br /></td><td class="error"><?php echo $password_error; ?></td></tr>
		    <input type="hidden" name="regtest" value="1" />
		    <tr><td></td><td class="error"><?php echo $empty_error; ?></td></tr>
		    <tr><td></td><td><input type="submit" value="Register Now" /></td></tr>		    
	    <?php } ?>
	    </table>
	</form>
    </div>
</div>    
</body>
</html>