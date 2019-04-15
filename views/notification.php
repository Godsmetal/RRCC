<?php
session_start();
if(!isset($_SESSION['phone'])){
    //session var is not set = they are not logged in
    header("Location: ../index.php");
}
require_once '../database/data_layer.php';
require_once '../business/partialViews.php';
$dataLayer = new data_layer();
$partialViews = new partialViews();

 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Rochester Riverside Convention Center</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/notification.css'>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='notifcationPage'>
    <?php
        echo $partialViews->createIndividualNotification($dataLayer->getData('notification',array('*'),'notificationID',$_GET['id']),$_GET['img']);
     ?>
    <div class='footer block'>
        <ul class='iconContainer'>
            <li class='inline active'><a href="news.php"><i class="fas fa-newspaper"></i></a></li>
            <li class='inline'><a href="videos.php"><i class="fas fa-video"></i></a></li>
            <li class='inline'><a href="profile.php"><i class="fas fa-user"></i></a></li>
            <?php
            if ($_SESSION['authID'] == 3) {
                echo '<li class="inline"><a href="deptHeadNotiConsole.php"><i class="fas fa-toolbox"></i></a></li>';
            }
            if ($_SESSION['authID'] == 4) {
                echo '<li class="inline"><a href="adminConsoleNews.php"><i class="fas fa-toolbox"></i></a></li>';
            }
             ?>
        </ul>
    </div>

</body>
</html>
