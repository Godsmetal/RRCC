<?php
// require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
// $dataLayer = new data_layer();
$bizLayer = new business_layer();
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
    <script type='text/javascript' src='/js/scripts.js'></script>
</head>

<body id='forgotPwdPage' class='backgroundImage'>

        <?php
        $hashedEmail = $_GET['email'];
        ?>
        <div class='container'>
            <section id='reset-confirm'>
                 <form class='formContainer' method='POST' action="../phpScripts/resetPassword.php?e=<?php echo $hashedEmail?>">
                    <h3>Type in your email and new password below to confirm password reset</h3>
                    <div class='inputWithIcon'>
                        <input class='block' id='email' type = 'email'  name='email' placeholder= 'Email Address' required="required" autofocus>
                        <i class='fas fa-user' aria-hidden='true'></i>
                    </div>

                    <div class='inputWithIcon'>
                        <input onkeyup="confirmPassword()" class='block' id='password' type = 'password' placeholder= 'Password' name='newPassword' required="required">
                        <i class='fas fa-key pwIcon' aria-hidden='true'></i>
                    </div>
                    <div class='inputWithIcon'>
                        <input onkeyup="confirmPassword()" class='block' id='passwordConfirm' type = 'password' placeholder= 'Confirm Password' name='newPasswordConfirm' required="required">
                        <i class='fas fa-key pwIcon' aria-hidden='true'></i>
                    </div>
                    <input class='block submit centered' type = 'submit' value= 'Confirm'/>
                </form>
            </section>
        </div>
</body>
</html>
