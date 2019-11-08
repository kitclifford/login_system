<?php   
    include('database.php');
    session_start();

    $password_code = $_GET['password_code'];
    $clean_code = mysqli_real_escape_string($conn, $password_code);
    $error = false;
    $success = false;

    $error_message = [];

    if(isset($_SESSION)){
        if (isset($_SESSION['logged_in'])){
            if ('YES' === $_SESSION['logged_in']){
                header("Location: already_signed_in.php");
            }
        }
    }

    if($_POST) {
        $password= $_POST['password'];
        $lowercase = preg_match('/[a-z]/', $password);
        $uppercase = preg_match('/[A-Z]/', $password);
        $numbers = preg_match('/[0-9]/', $password);
        $special_chars = preg_match('/[^A-Za-z0-9]/', $password);
        if (strlen($password) === 0){
            $error = true;
            $error_message[] = 'Please enter a password. ';
        } elseif (strlen($password) < 8) {
            $error = true;
            $error_message[] = 'Please pick a longer password (at least 8 characters). ';
        }
        if (!$lowercase || !$uppercase || !$numbers || !$special_chars){
            $error = true;
            $error_message[] = 'Your password must contain at least 1 of each: uppercase letter, lowercase letter, number (0-9) and a special character. ';
        }
    }

    

    if (false === $error) {
        if ($_POST) {
            $password = $_POST['password'];
            $clean_password = mysqli_real_escape_string($conn, $password);
            if (strlen($password_code) > 0) {
                $query = "SELECT * FROM `users` WHERE `reset_email_code` = '$password_code';";
                $result = mysqli_query($conn, $query);
                
                if (mysqli_num_rows($result) > 0){
    
                    $row = mysqli_fetch_assoc($result);
                    $reset_id = $row['id'];

                    $hashed_password = password_hash($clean_password, PASSWORD_DEFAULT);

                    $password_update = "UPDATE `users` SET `password` = '$hashed_password' WHERE `id` = '$reset_id'";
                    $reset_result = mysqli_query($conn, $password_update);

                    if ($reset_result) {
                        $success = true;
                    } else {
                        $error = true;
                        $error_message[] = 'Something went wrong with the database.';
                    }
                } else {
                    $error = true;
                    $error_message[] = 'Invalid link 1.';
                }
    
            } else {
                $error = true;
                $error_message[] = 'Invalid link 2.';
            }
        } 
    }
    

?>


<html>

    <head>
        <title>Reset Password</title>
        <link rel="stylesheet" href="register.css">
    </head>
    <body>
        <h2 class='header'>Please enter your new password in the form below</h2>
        <form action = '' method='post'>
        <label class ='input' for='password'>Password:</label>
        <input class ='input' id='password' name ='password' type='password'/>
        <input class ='input' type='submit' value='Set password'/>
        </form>
    <p>Need to login instead? Click <a href='http://192.168.33.10/login_system/login.php'>here</a>.<p>
        <?php 
        if($_POST) {
            if (false === $error){
                if (true === $success) {
                    header("Location: reset_success.php");
                } else {
                    echo 'Error';
                }
            } 
            if (true === $error) {
                foreach($error_message AS $message) {
                    echo "$message </br>";
                }
            }
        }
        ?>
    </body>

</html>