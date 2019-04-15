<?php
session_start();

if(!isset($_SESSION['phone'])){
    //session var is not set = they are not logged in
    header("Location: ../index.php");
}

if(isset($_SESSION['authID']) && $_SESSION['authID'] != 4){
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
    <link href='../assets/fonts/fontawesome-free-5.2.0-web/css/all.min.css' rel='stylesheet'>
</head>

<body id='adminConsole' onload="addMask();checkDesktop();">
    <!-- Header -->
    <div class='header'>
        <h1 id='title' class='centered'>Administrator Console</h1>
    </div>

    <!-- Navigation -->
    <ul class='block' id='navigation'>
        <a href="adminConsoleNews.php"><li id="news_Button" class='inline'>News</li></a>
        <a href="adminConsoleEmployee.php"><li id="employee_Button" class='inline'>Employees</li></a>
        <a href="#"><li id="pending_Button" class='inline active'>Pending</li></a>
        <a href="adminConsoleCSV.php"><li id="compare_Button" class='inline'>Compare Employee Lists</li></a>
        <hr>
    </ul>

    <section id='pending'>

        <!-- Table that appends database entries of unauthorized employees into rows -->
        <table>
            <tr>
                <th></th>
                <th>First</th>
                <th>Last</th>
                <th>Action</th>
            </tr>
            <?php
            $pageNum = (isset($_GET['page'])) ? ($_GET['page']) : (1);
            echo $partialViews->createPendingUserTable($dataLayer->getData('user',array('*'),'authID',1),$pageNum);
            ?>
        </table>

        <!-- Pagination -->
        <div class='pagination block'>
            <?php
            $pageNum = (isset($_GET['page'])) ? ($_GET['page']) : (1);
            echo $partialViews->createPendingTablePaginationLinks($dataLayer->getData('user',array('*'),'authID',1),$pageNum);
            ?>
        </div>

    </section>

<!-- Footer -->
<div class='footer block'>
    <ul class='iconContainer'>
        <li class='inline'><a href="news.php"><i class="fas fa-newspaper"></i></a></li>
        <li class='inline'><a href="videos.php"><i class="fas fa-video"></i></a></li>
        <li class='inline'><a href="profile.php"><i class="fas fa-user"></i></a></li>
        <li class='inline active'><a href="adminConsolePending.php"><i class="fas fa-toolbox"></i></a></li>
    </ul>
</div>
</body>
</html>
