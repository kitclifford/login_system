<?php
include ('database.php'); 
session_start();
if (isset($_SESSION['logged_in'])){
    if ('YES' !== $_SESSION['logged_in']){
        header("Location: createprompt.php");
    }  
} else {
    header("Location: createprompt.php");
}


?> 

<html>
    <head>
        <title>Welcome</title>
        <link rel="stylesheet" href="register.css">
    </head>
    <body>
        <h2><span class='header'>Welcome</span></h2>
        <h3>You are logged in :)</h3>
        <br>
        <form action='login.php' method='post'>
            <input type='submit' name='signout' value='Sign Out'>
        </form>
    </body>
</html>