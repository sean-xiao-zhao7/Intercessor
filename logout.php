<?php
    session_save_path("sess");
    session_start();
    
    require("lib/lib1.php");
    
    //exclude this user from "online.txt"
    
     $username = $_SESSION["username"];
   
     $array = get_from_file("online.txt");
    
     for ($i = 0; $i < sizeof($array); $i += 1) {
          if ($array[$i] == $username) {
               unset($array[$i]);
          }          
     }
     
     write_to_file("online.txt", $array);
     
    session_destroy();
    header("location:login.php");
?>
