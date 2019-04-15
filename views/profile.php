<?php
session_start();
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$dataLayer = new data_layer();
$bizLayer = new business_layer();
if(!isset($_SESSION['phone'])){
    //session var is not set = they are not logged in
    header("Location: ../index.php");
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("location: ../index.php");
}
 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Rochester Riverside Convention Center</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/profile.css'>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script type='text/javascript' src='/js/scripts.js'></script>
</head>

<body onload="addMask()"id='profilePage' class='backgroundImage'>

    <div class='container'>
        <!-- Landing Section -->
        <section id='create-form'>
            <!-- Header -->
            <div class='header'>
                <h1 class='title centered'>Hello, <?php echo $_SESSION['name'] ?></h1>
                <h2 class='subtitle centered'>You may view/update any account<br/>information here</h2>
                <a id='help' href='<?php
                if ($_SESSION['authID'] == 2) echo "../documentation/RRCC_UserDocs.html";
                if ($_SESSION['authID'] == 3) echo "../documentation/RRCC_DeptHeadDocs.html";
                if ($_SESSION['authID'] == 4) echo "../documentation/RRCC_AdminDocs.html";
                 ?>' target="_blank">Need Help?</a>

            </div>

            <!-- User Input -->
            <form class='formContainer' action='../phpScripts/formActions/profileAction.php' method='POST'>
                <div class="formStep">
                    <div class='inputWithIcon'>
                        <input class='block' id='phoneNumber' type = 'text' placeholder= 'Phone Number' name='phone' value="<?php echo $dataLayer->getData('user',array('phone'),'userID',$_SESSION['userID'])[0]['phone'];?>" autofocus>
                        <i class='fas fa-phone' aria-hidden='true'></i>
                    </div>

                    <div class='inputWithIcon'>
                        <input class='block' id='email' type = 'text' placeholder= 'E-mail Address' name='email' value="<?php echo $dataLayer->getData('user',array('email'),'userID',$_SESSION['userID'])[0]['email'];?>"  autofocus>
                        <i class='fas fa-user' aria-hidden='true'></i>
                    </div>

                    <div class='inputWithIcon'>
                        <a href="forgotPwd.php">
                            <input class='block' id='password' type = 'button' value="Click to Reset Password">
                            <i class='fas fa-key pwIcon' aria-hidden='true'></i>
                        </a>
                    </div>
                    <input class='block submit centered' id='save' type = 'submit' value= 'Save Changes' onclick=""/>
                </div>
            </form>

            <!-- Logout -->
            <form action="profile.php" method="post">
                <div class='logoutContainer'>
                    <button type= 'submit' class='block submit centered' name= "logout" id='logout'>Logout</button>
                </div>
            </form>

        </section>
    </div>

    <!-- Footer -->
    <div class='footer block'>
        <ul class='iconContainer'>
            <li class='inline'><a href="news.php"><i class="fas fa-newspaper"></i></a></li>
            <li class='inline'><a href="videos.php"><i class="fas fa-video"></i></a></li>
            <li class='inline active'><a href="profile.php"><i class="fas fa-user"></i></a></li>
            <?php
            if ($_SESSION['authID'] == 4) {
                echo '<li class="inline"><a href="adminConsoleNews.php"><i class="fas fa-toolbox"></i></a></li>';
            }
            if ($_SESSION['authID'] == 3) {
                echo '<li class="inline"><a href="deptHeadNotiConsole.php"><i class="fas fa-toolbox"></i></a></li>';
            }
             ?>
        </ul>
    </div>

</body>
</html>
