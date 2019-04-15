<?php
session_start();
if(!isset($_SESSION['phone'])){
    //session var is not set = they are not logged in
    header("Location: ../index.php");
}
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
require_once '../business/partialViews.php';
$dataLayer = new data_layer();
$bizLayer = new business_layer();
$partialViews = new partialViews();
 ?>

<!DOCTYPE html>
<html>
<head>
    <title>Rochester Riverside Convention Center</title>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale = 1.0, minimum-scale = 1.0, maximum-scale = 5.0' />
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/videos.css'>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type='text/javascript' src='/js/scripts.js'></script>

</head>

<body id='videosPage'>

    <!-- Videos -->
    <div class='bodyContainer'>

         <!-- <div class='videoContainer' id=''>
             <button type="button" class="button"><i class="far fa-edit"></i></button>
             <div class='buttonOptions' style="display:none" >
                 <ul class='spaced'>
                     <li>Create New<i class="fas fa-plus-circle"></i></li>
                     <li>Edit Link<i class='fas fa-pencil-alt'></i></li>
                     <li>Delete<i class="fas fa-trash-alt"></i></li>
                 </ul>
             </div>
             <div class='overlay'>
                 <iframe src="https://www.youtube.com/embed/9Gbl-IDp1qc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
             </div>
         </div> -->

        <!-- Section to add a video (Admin Only) -->
        <div class='addVideo hidden'>
            <form method="POST" action="../phpScripts/formActions/videoAction.php">
                <i onclick= "closeVideoBox(this)" class="fas fa-times"></i>
                <h2>Create New Video</h2>
                <div class='inputWithIcon'>
                    <input class='block' type="text" name="link" placeholder='Embeded URL'/>
                    <i class="fab fa-youtube" aria-hidden="true"></i>
                </div>
                <input type="submit" class='block addSubmit inputNoIcon' name="addVid" value="Add New Video">
            </form>
        </div>

        <!-- Section to edit a video (Admin Only) -->
        <div class='editVideo hidden'>
            <form method="POST" action="../phpScripts/formActions/videoAction.php?id=">
                <i onclick= "closeVideoBox(this)" class="fas fa-times"></i>
                <h2>Edit Video</h2>
                <div class='inputWithIcon'>
                    <input class='block' type="text" name="link" placeholder='Embeded URL'/>
                    <i class="fab fa-youtube" aria-hidden="true"></i>
                </div>
                <input type="submit" class='block addSubmit inputNoIcon' name="modVid" value="Save Changes">
            </form>
        </div>

        <?php
            echo $partialViews->createVideoPage($dataLayer->getData('video',array("*")));
         ?>

    </div>

    <!-- Footer -->
        <div class='footer block'>
            <ul class='iconContainer'>
                <li class='inline'><a href="news.php"><i class="fas fa-newspaper"></i></a></li>
                <li class='inline active'><a href="videos.php"><i class="fas fa-video"></i></a></li>
                <li class='inline'><a href="profile.php"><i class="fas fa-user"></i></a></li>
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
