<?php

    include('database.php');

    $activation_code = $_GET['activation_code'];
    $clean_code = mysqli_real_escape_string($conn, $activation_code);
    $error = false;

    if (strlen($activation_code > 0)){
        // Query
        $query = "SELECT * FROM `users` WHERE `activation_code` = '$clean_code';";
        // Get the user with this activation code
        $result = mysqli_query($conn, $query);
        // If there is someone with this code
        if (mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            $activated_id = $row['id'];
            //If already activated
            if ($row['activated'] == 'yes') {
                echo '<p class="para">Your account is already registered! </br> Please sign in <a href="http://192.168.33.10/login_system/login.php">here</a>.</p>';
                //Otherwise activate the account 
            } else {
                $update = "UPDATE `users` SET `activated` = 'yes' WHERE `id` = '$activated_id'";
                if (mysqli_query($conn, $update)) {
                    echo '<p class="para">Congratulations, you are registered! </br> Please sign in <a href="http://192.168.33.10/login_system/login.php">here</a>.</p>';
                } else {
                    $error = true;
                }
            }            
        } else {
            $error = true;
        }
        if (true === $error){
            echo 'Error, could not activate your account.';
        }
        // Change activated_id value of activated to yes
    }

?>

<html>
    <head>
        <title>Activation</title>
        <link rel="stylesheet" href="register.css">
    </head>
    <body>
        
    </body>
</html>