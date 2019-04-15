<?php
session_start();

if(!isset($_SESSION['phone'])){
    //session var is not set = they are not logged in
    header("Location: ../index.php");
}

if(isset($_SESSION['authID']) && $_SESSION['authID'] != 3){
    //logged in but dont have correct access send to news page
    header("Location: news.php");
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
    <link rel='stylesheet' type='text/css' media='screen' href='/style/css/adminConsole.css'>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script type='text/javascript' src='/js/scripts.js'></script>
    <script type='text/javascript' src='/js/ajaxTarget.js'></script>
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='adminConsole' onload="addMask();">
    <!-- Header -->
    <div class='header'>
        <h1 id='title' class='centered'>Notification Console</h1>
    </div>
    <!-- News Section of Admin Console -->
    <section id='news'>

        <!-- Search Bar -->
        <div class='searchBar inputWithIcon'>
            <input type='text' placeholder='Search' onkeypress="initSearch(event,this,'news')">
            <i class="fas fa-search"></i>
        </div>

        <!-- Table that appends database entries of news articles into rows -->
        <table>
            <tr id="headerRow">
                <th></th>
                <th>Title</th>
                <th>Active</th>
                <th>Action</th>
                <th></th>
            </tr>

            <!-- Begin PHP Iterative process to dynamically create News -->

            <?php
                $pageNum = (isset($_GET['page'])) ? ($_GET['page']) : (1);
                echo $partialViews->createAdminConsoleNewsTable(array_reverse($dataLayer->getAllNotifcations()),$pageNum);
             ?>

            <!-- Begin next dynamically added rows here -->

            <!-- Add New Notification -->
            <tr class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-plus-circle'></i></td>
                <td colspan='4'>Add New Notification</td>
            </tr>
            <tr class='spacer'><td></td></tr>
            <tr class='collapsed' style="display:none">
                <td colspan='5'>
                    <!-- Form that takes user input to add a new notification -->
                    <form class="addNewForm" action="../phpScripts/formActions/newsAction.php" method="post" enctype="multipart/form-data">
                        <h2>Title</h2>
                        <input type="text" class='block inputNoIcon' name="title" required>
                        <h2>Body</h2>
                        <textarea class='block inputNoIcon' name="body"></textarea>
                        <!--<input type="text" class='block inputNoIcon' name="body" required>-->
                        <h2>Survey Link</h2>
                        <input type="text" class='block inputNoIcon' name="surveyLink">
                        <h2>Attachment</h2>
                        <div class='inputWithIcon addAttachment'>
                            <input class='block' type='file' name='attachment'/>
                            <i class="fas fa-file-upload" aria-hidden='true'></i>
                        </div>

                        <h2>Notify Via</h2>
                        <div class='checkBox'>
                            <input name= 'phoneCheck' id='cbPhone' type='checkbox'>
                            <label for='cbPhone' class='checkBoxContainer'>Phone</label>
                            <input name='emailCheck' id='cbEmail' type='checkbox'>
                            <label for='cbEmail' class='checkBoxContainer'>Email</label>
                            <input name ='webAppCheck' id='cbWebApp' type='checkbox' checked>
                            <label for='cbWebApp' class='checkBoxContainer'>Web App</label>
                        </div>
                        <input type="submit" class='block addSubmit inputNoIcon' name="sendNoti" value="Send Notification">
                    </form>
                </td>
            </tr>
        </table>
        <div class='pagination block'>
            <?php
                //set the page number. If the get var is set then use that, if not 1
                $pageNum = (isset($_GET['page'])) ? ($_GET['page']) : (1);
                echo $partialViews->makeAdminConsoleNewsPaginationLinks($pageNum,$dataLayer->getAllNotifcations());
            ?>
        </div>
    </section>


    <!-- Footer -->
    <div class='footer block'>
        <ul class='iconContainer'>
            <li class='inline'><a href="news.php"><i class="fas fa-newspaper"></i></a></li>
            <li class='inline'><a href="videos.php"><i class="fas fa-video"></i></a></li>
            <li class='inline'><a href="profile.php"><i class="fas fa-user"></i></a></li>
            <li class='inline active'><a href="deptHeadNotiConsole.php"><i class="fas fa-toolbox"></i></a></li>
        </ul>
    </div>

</body>
</html>
