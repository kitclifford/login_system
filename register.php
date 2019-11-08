<!-- Form -->
<?php

    include('database.php');
    session_start();

    $email = '';
    $password = '';
    $activation_code = '';

    $clean_email = '';
    $clean_password = '';
    $clean_activation_code = '';

    if(isset($_SESSION)){
        if (isset($_SESSION['logged_in'])){
            if ('YES' === $_SESSION['logged_in']){
                header("Location: already_signed_in.php");
            }
        }
    }

    if ($_POST){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $activation_code = bin2hex(random_bytes(10));

        $success = false;
        $error = false;
        $error_message = [];

        $clean_email = mysqli_real_escape_string($conn, $email);
        $clean_password = mysqli_real_escape_string($conn, $password);
        $clean_activation_code = mysqli_real_escape_string($conn, $activation_code);

        $lowercase = preg_match('/[a-z]/', $password);
        $uppercase = preg_match('/[A-Z]/', $password);
        $numbers = preg_match('/[0-9]/', $password);
        $special_chars = preg_match('/[^A-Za-z0-9]/', $password);

        if (strlen($email) === 0){
            $error = true;
            $error_message[] = 'Please enter an email.';
        } elseif (false === filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = true;  
            $error_message[] = 'Please enter a valid email. ';
        }


        $email_check = "SELECT * FROM `users` WHERE `username` = '$clean_email';";
        $email_result = mysqli_query($conn, $email_check);
        if (mysqli_num_rows($email_result) > 0){
            $activated_cell = mysqli_fetch_assoc($email_result);
            if ($activated_cell['activated'] === 'yes') {
                $error = true;
                $error_message[] = 'That email is already in use. ';
            } else {
                $error = true;
                $error_message[] = 'That email has been registered but not activated. Please check your inbox. ';
            }
        }

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
        
        if ($error == false) {
            
            $hashed_password = password_hash($clean_password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO `users` (`username`, `password`, `activation_code`) VALUES ('$clean_email', '$hashed_password', '$clean_activation_code');";

            $user_insert = "INSERT INTO `users` (`username`, `password`, `activation_code`) VALUES ('$clean_email', '$hashed_password', '$clean_activation_code');";

            $result = mysqli_query($conn, $user_insert);
            

                if ($result) {
                    // Put this in another conditional
                    $headers = "From: Dev Me <team@example.com>\r\n";
                    $headers .= "Reply-To: Help <help@example.com>\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html;\r\n";

                    $link = "<a href='http://192.168.33.10/login_system/activation.php?activation_code=$clean_activation_code'>Click here!</a>";

                    $message = 'Hi! </br>';
                    $message .= 'Please click the following link to confirm your email: ';
                    $message .= "$link";
                    
                    //Don't need variable for email function (it returns true or false)
                    // $mail = ;

                    if (mail($email, 'Thanks for registering', $message, $headers)) {
                        $success = true;
                    } else {
                        $error = true;
                        $error_message[] = 'Something went wrong with our database. ';
                    }
                
                } else {
                    $error = true;
                    $error_message[] = 'Something went wrong with our database. ';
                }
        }
    };
    
?>

<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" href="register.css">
    </head>
    <body>
        <h2><span class='header'>Please use the form below to register</span></h2>
        <form action = '' method='post'>
            <label class ='input' for='email'>Email:</label>
            <input class ='input' id='email' name='email' type='text' value='<?php if($_POST) echo $email ?>'/>
            <label class ='input' for='password'>Password:</label>
            <input class ='input' id='password' name ='password' type='password'/>
            <input class ='input' type='submit' value='Register'/>
        </form>
        <?php 
        if ($_POST) {
            if (false == $error){
                
                if (true == $success) {
                    echo "We will shortly send you an email confirming your registration.";
                } else {
                    echo 'Something went wrong with our database.';
                }
            }
            if (true ==$error){

                foreach($error_message AS $message) {
                    echo "$message </br>";
                }
            }
        }
        
        ?>
        <p>Already have an account? Login <a href='http://192.168.33.10/login_system/login.php'>here</a></p>
    </body>
</html>

<!-- PHP form handling DONE--> 


<!-- Check user input DONE-->


<!-- Create an activation code DONE -->


<!-- Save in database (will need to CREATE TABLE first) DONE -->


<!-- Send email DONE -->


<!-- Account creation success message DONE -->