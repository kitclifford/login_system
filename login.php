<!-- 
    1. Check the email is on the database
    2. Check the account is activated
    3. Check the password is correct
 -->

<?php

    include('database.php');
    session_start();
    $email = '';
    $password = '';
    $error = false;

    $hashed_password = '';


    
    if (isset($_POST['signout'])) {
        // log out on database

            $user = $_SESSION['user'];
            $log_out = "UPDATE `users` SET `logged_in` = 'no' WHERE `username` = '$user'";
            mysqli_query($conn, $log_out);

            //clear the sesh

            $_SESSION['logged_in'] = 'NO';
            session_destroy();
    }


    if (isset($_SESSION['logged_in'])){
        if ('YES' === $_SESSION['logged_in']){
            header("Location: already_signed_in.php");
        }
    }
    

    if (isset($_POST['email']) || isset($_POST['password'])) {
    
        $email = $_POST['email'];
        $clean_email = mysqli_real_escape_string($conn, $email);
        $password = $_POST['password'];
        $clean_password = mysqli_real_escape_string($conn, $password);
        $email_check = "SELECT * FROM `users` WHERE `username` = '$clean_email';";
        $result = mysqli_query($conn, $email_check);
        // var_dump($result);
        if (mysqli_num_rows($result) > 0){

            $activated_check = "SELECT `activated` FROM `users` WHERE `username` = '$clean_email';";
            $activated_result = mysqli_query($conn, $activated_check);
            $activated_cell = mysqli_fetch_assoc($activated_result);
            
            if ($activated_cell['activated'] === 'yes'){

                $hashed_password = password_hash($clean_password, PASSWORD_DEFAULT);
                $password_check = "SELECT `password` FROM `users` WHERE `username` = '$clean_email';";
                $password_result = mysqli_query($conn, $password_check);
                $password_cell = mysqli_fetch_assoc($password_result);
                if (password_verify($clean_password, $password_cell['password'])){

                    $log_in = "UPDATE `users` SET `logged_in` = 'yes' WHERE `username` = '$clean_email'";
                    mysqli_query($conn, $log_in);
                    session_start();
                    $_SESSION['logged_in'] = 'YES';
                    $_SESSION['user']= $clean_email;
                    header("Location: index.php");
                    
                } else {
                    $error = true; 
                }

            } else {
                $error = true;   
            }
        
        } else {
            $error = true;   
        }

    }
?>

<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="register.css">
    </head>
    <body>
        <h2><span class='header'>Login</span></h2>
        <form method='post'>
            <label class ='input' for='email'>Email:</label>
            <input class ='input' id='email' name='email' type='text' value='<?php if($_POST) echo $email ?>'/>
            <label class ='input' for='password'>Password:</label>
            <input class ='input' id='password' name ='password' type='password'/>
            <input class ='input' type='submit' value='Login'/>
        </form>
        <br>
        <p>Need to create an account? Register <a href='http://192.168.33.10/login_system/register.php'>here</a></p>
        <br>
        <p>Forgotten your password? Reset it <a href='http://192.168.33.10/login_system/password_reset.php'>here</a></p>
        <?php
        if($_POST) {
            if (true === $error) {
                echo 'Error! Please check your details.';
            }
        }
        
        ?>
    </body>
</html>