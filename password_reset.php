<?php
    include('database.php');
    session_start();
    $email = '';
    $password = '';

    $error = false;
    $success = false;

    if(isset($_SESSION)){
        if (isset($_SESSION['logged_in'])){
            if ('YES' === $_SESSION['logged_in']){
                header("Location: already_signed_in.php");
            }
        }
    }

    if ($_POST){
        //get email
        $email = $_POST['reset_email'];
        //sanitise it

        $clean_email = mysqli_real_escape_string($conn, $email);
        $email_check = "SELECT * FROM `users` WHERE `username` = '$clean_email';";


        $email_result = mysqli_query($conn, $email_check);
        if (mysqli_num_rows($email_result) > 0){
            $password_code = bin2hex(random_bytes(10));
            $reset_query = "UPDATE `users` SET `reset_email_code` = '$password_code' WHERE `username` = '$clean_email'";
            if (mysqli_query($conn, $reset_query)){

                $headers = "From: Dev Me <team@example.com>\r\n";
                $headers .= "Reply-To: Help <help@example.com>\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html;\r\n";

                $link = "<a href='http://192.168.33.10/login_system/reset.php?password_code=$password_code'>Click here!</a>";

                $message = 'Hi! </br>';
                $message .= 'Please click the following link to reset your password: ';
                $message .= "$link";
                
                //Don't need variable for email function (it returns true or false)
                // $mail = ;

                if (mail($email, 'Reset your password', $message, $headers)) {
                    $success = true;
                } else {
                    $error = true;
                }

            }
            
        }

    }
    
    
    //if email is in database
        // if not say
    //generate random email reset code
    //send code to database
    //send email with link with reset code 
    //
    //



?>


<html>
    <head>
    <title>Reset Your Password</title>
    <link rel="stylesheet" href="register.css">
    </head>
    <body>
        <h1 class='header'>Reset your password</h1>
        <form method='post'>
            <label class ='input' for='email'>Email:</label>
            <input class ='input' id='email' name='reset_email' type='text' value='<?php if($_POST) echo $email ?>'/>
            <input class ='input' type='submit' value='Reset'/>
        </form>
        <br>
        <p>Back to <a href='http://192.168.33.10/login_system/login.php'>login</a> page</p>

        <?php 
        if ($_POST) {
            if (false === $error){
                if ($success){
                    echo 'Reset link sent to your email.';
                } else {
                    echo 'Something went wrong.';
                }
            }
            if (true === $error){
                echo 'Something went wrong.';
            }
        }
        ?>
    </body>
</html>