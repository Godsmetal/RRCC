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
//require_once '../business/business_layer.php';
require_once '../business/partialViews.php';

$partialViews = new partialViews();

$dataLayer = new data_layer();
//$bizLayer = new business_layer();
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

<body id='adminConsole' onload="addMask();checkDesktop();">
    <!-- Header -->
    <div class='header'>
        <h1 id='title' class='centered'>Administrator Console</h1>
    </div>

    <!-- Navigation -->
    <ul class='block' id='navigation'>
        <a href="adminConsoleNews.php"><li id="news_Button" class='inline'>News</li></a>
        <a href="#"><li id="employee_Button" class='inline active'>Employees</li></a>
        <a href="adminConsolePending.php"><li id="pending_Button" class='inline'>Pending</li></a>
        <a href="adminConsoleCSV.php"><li id="compare_Button" class='inline'>Compare Employee Lists</li></a>
        <hr>
    </ul>

    <section id='employees'>
        <!-- Search Bar -->
        <div class='searchBar inputWithIcon'>
            <input type='text' placeholder='Search' onkeypress="initSearch(event,this,'emp')">
            <i class="fas fa-search"></i>
        </div>

        <!-- Table that appends database entries of employees into rows -->
        <table>
            <tr id="headerRow">
                <th></th>
                <th>First</th>
                <th>Last</th>
                <th>Action</th>
            </tr>

            <?php
            //set the page number. If the get var is set then use that, if not 1
            $pageNum = (isset($_GET['page'])) ? ($_GET['page']) : (1);
            echo $partialViews->createEmployeeTable($dataLayer->getAllUsers(),$pageNum);

            ?>
            <!-- Add New Employee -->
            <tr class='collapsed'>
                <td><i onclick="dropDownToggle(this)" class='fas fa-plus-circle'></i></td>
                <td colspan='6'>Add New Employee</td>
            </tr>

            <tr class='spacer'><td></td></tr>
            <tr class='collapsed' style="display:none">
                <td colspan='6'>
                    <!-- Form that takes user input to add a new employee
                    * Make sure to automatically set a temporary password and send via phone # and email
                -->
                <form class="addNewForm" action="../phpScripts/formActions/employeeAction.php" method="post">

                    <!-- Input Fields -->
                    <h2>First Name</h2>
                    <div class='inputWithIcon'>
                        <input class='block' id='fName' type = 'text' placeholder= 'First Name' name='fName' required="required">
                        <i class='fas fa-address-card' aria-hidden='true'></i>
                    </div>
                    <h2>Last Name</h2>
                    <div class='inputWithIcon'>
                        <input class='block' id='fName' type = 'text' placeholder= 'Last Name' name='lName' required="required">
                        <i class='fas fa-address-card' aria-hidden='true'></i>
                    </div>
                    <!-- Add phone mask -->
                    <h2>Phone</h2>
                    <div class='inputWithIcon'>
                        <input class='block' id='phoneNumber' type = 'text' placeholder= 'Phone Number' name='phoneNumber' required="required">
                        <i class='fas fa-phone' aria-hidden='true'></i>
                    </div>
                    <h2>Email</h2>
                    <div class='inputWithIcon'>
                        <input class='block' id='email' type = 'text' placeholder= 'E-mail Address' name='email' required="required">
                        <i class='fas fa-user' aria-hidden='true'></i>
                    </div>
                    <h2>Active</h2>
                    <div class='inputWithIcon'>
                        <select class='block inputWithIcon' id='active' name='activeYN' required="required">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <i class='fas fa-flag' aria-hidden='true'></i>
                    </div>
                    <h2>Authorization</h2>
                    <div class='inputWithIcon'>
                        <select class='block inputWithIcon' id='auth' name='authID' required="required">
                            <option value="2">Employee</option>
                            <option value="3">Department Head</option>
                            <option value="4">Administrator</option>
                        </select>
                        <i class='fas fa-users' aria-hidden='true'></i>
                    </div>
                    <h2>Department</h2>
                    <div class='inputWithIcon'>
                        <select class='block inputWithIcon' id='dept' name='deptID' required="required">
                            <option value="" disabled selected>Department</option>
                            <option value="1">HR</option>
                            <option value="2">Admin</option>
                            <option value="3">Sales</option>
                            <option value="4">Production</option>
                            <option value="5">Operations</option>
                            <option value="6">Food and Beverage</option>
                            <option value="7">Garage</option>
                        </select>
                        <i class='fas fa-building' aria-hidden='true'></i>
                    </div>

                    <!-- Form submit -->
                    <input type="submit" class='block addSubmit inputNoIcon' name="addEmp" value="Add Employee">
                </form>
            </td>
        </tr>

    </table>

    <!-- Pagination -->
    <div class='pagination block'>
        <?php
        //set the page number. If the get var is set then use that, if not 1
        $pageNum = (isset($_GET['page'])) ? ($_GET['page']) : (1);
        echo $partialViews->createEmployeeTablePaginationLinks($dataLayer->getAllUsers(),$pageNum);
        ?>
    </div>

    </section>
<!-- Footer -->
<div class='footer block'>
    <ul class='iconContainer'>
        <li class='inline'><a href="news.php"><i class="fas fa-newspaper"></i></a></li>
        <li class='inline'><a href="videos.php"><i class="fas fa-video"></i></a></li>
        <li class='inline'><a href="profile.php"><i class="fas fa-user"></i></a></li>
        <li class='inline active'><a href="adminConsoleEmployee.php"><i class="fas fa-toolbox"></i></a></li>
    </ul>
</div>
</body>
</html>
