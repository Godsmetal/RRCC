<?php

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
        <!-- Password Sent Successfully Section -->
        <section id='section-landing'>
            <!-- Header -->
            <div class='header'>
                <i class="fas fa-lock" aria-hidden='true'></i>
                <h1 id='title' class='centered'>Password Reset Confirm</h1>
                <h2 class='subtitle centered'>An Email has been sent to you<br/>please follow the steps to assist you in resetting your password</h2>
            </div>
            <!-- Send Password Form -->
            <div class="formContainer">
                <input onclick="location.href='../index.php'" class='block submit centered' id='login' type = 'submit' value= 'Back To Login'/>
            </div>

        </section>
    </div>
</body>
</html>
