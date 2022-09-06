<?php

session_start();

if(isset( $_SESSION["admin_id"]))
{ 
    session_destroy();
    session_start();
    $_SESSION["success"] = "Admin logged-out successfully!";
}


header("location: login.php");
exit;

?>