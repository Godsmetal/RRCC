<?php
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$dataLayer = new data_layer();
$bizLayer = new business_layer();


if($_SERVER["REQUEST_METHOD"] == "POST"){



   $inputEmail = $_POST["email"];
   if($dataLayer->checkEmailExists($inputEmail)){
        //Yes
            //$_SERVER["ForgotPwdEmail"] = $inputEmail;
            $hashedEmail = password_hash($inputEmail,PASSWORD_DEFAULT);
            //$link = 'localhost/views/resetConfirm.php?email=' . $hashedEmail;
            //This link should be made relative to the IP Address
            $link = 'local.pearl.com/views/resetConfirm.php?email=' . $hashedEmail;
            $subject = "RRCC Account Password Reset";
            $body = "<h1>Please click the link below to reset your password</h1>";
            $body .= "<a href=$link >Reset my password</a>";


            $bizLayer->sendEmail($inputEmail, $subject, $body);
            //route to confirm page
            header("Location: forgotPwdSuccess.php");

    }   else { //NO
            //Put error message on screen saying email could not be found
            echo "<h2 color='red'>Error: Email Address Not Found.</h2>";
            //echo "<script type='text/javascript'>alert('..');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rochester Riverside Convention Center</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/forgotPwd.css'>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script type='text/javascript' src='../js/scripts.js'></script>

</head>

<body id='forgotPwdPage' class='backgroundImage'>
    <div class='container'>
        <!-- Landing Section -->
        <section id='section-landing'>
            <!-- Header -->
            <div class='header'>
                <i class="fas fa-lock" aria-hidden='true'></i>
                <h1 id='title' class='centered'>Password Reset</h1>
                <h2 class='subtitle centered'>We just need your registered email address to<br/>assist you in resetting your password</h2>
            </div>

            <!-- Send Password Form -->
            <form action= "forgotPwd.php#clicked" class='formContainer' method='POST'>
                <div class='inputWithIcon'>
                    <input class='block' id='email' type = 'email' placeholder= 'E-mail Address' name='email' required="required" autofocus>
                    <i class='fas fa-user' aria-hidden='true'></i>
                </div>
                <input class='block submit centered' id='login' type = 'submit' value= 'Send Reset Email'/>
                <a id='login' href='../index.php'>Log in</a>
            </form>
        </section>

        <!-- Password Sent Successfully Section -->
        <section id='section-success'>
            <!-- Header -->
            <div class='header'>
                <i class="fas fa-check" aria-hidden='true'></i>
                <h2 id='title' class='centered'>Reset Email<br/>Sent</h2>
                <p class='subtitle centered'>Reset email has been sent.<br/>Follow email instructions to finish<br/> the reset process</p>
                <div id='formContainer'>
                    <a class='submit success' href='../index.php'>Log In</a>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
