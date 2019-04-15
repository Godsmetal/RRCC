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
require_once '../business/business_layer.php';
$dataLayer = new data_layer();
$bizLayer = new business_layer();
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

<body id='adminConsole' onload="initCsvListener();addMask();">
    <!-- Header -->
    <div class='header'>
        <h1 id='title' class='centered'>Administrator Console</h1>
    </div>

    <!-- Navigation -->
    <ul class='block' id='navigation'>
        <a href="adminConsoleNews.php"><li id="news_Button" class='inline'>News</li></a>
        <a href="adminConsoleEmployee.php"><li id="employee_Button" class='inline'>Employees</li></a>
        <a href="adminConsolePending.php"><li id="pending_Button" class='inline'>Pending</li></a>
        <a href="#"><li id="compare_Button" class='inline active'>Compare Employee Lists</li></a>
        <hr>
    </ul>

    <section id='compare'>
        <div class='centered'>
            <h2 class='title'>Upload CSV file to compare Active Employee List<br>with the Payroll Employee List</h2>
            <form class="" action="adminConsoleCSV.php" method="post" enctype="multipart/form-data">
                <div class='uploadContainer'>
                    <div class='inputWithIcon addAttachment'>
                        <input class='block' type='file' name='attachment'/>
                        <i class="fas fa-file-upload" aria-hidden='true'></i>
                    </div>
                    <button id='csvUpload' type="submit" name="csvUpload" value="csvUpload">Submit</button>
                </div>
            </form>
        <?php
        $makeList = false;
        $dataLines = array();
        if(isset($_POST['csvUpload'])){
            if (isset($_FILES['attachment']) && $_FILES['attachment']['size'] > 0 ){
                //send file to business layer to upload with callback of csv (for some far checks)
                if ($bizLayer->uploadFile($_FILES['attachment'], 'csv')){
                    //Parse the file using php to find the differences between that and the DB users
                    $fileTxt = file_get_contents('../assets/uploads/currEmpCSV.csv');
                    $fileLines = explode("\n", $fileTxt);
                    $csvUsers = array();
                    //Parase and transform csv to check with DB
                    foreach ($fileLines as $currLine) {
                        $good = true;
                        //split the line like a csv
                        $currLine = explode(",", $currLine);
                        if (count($currLine) != 8)$good = false;
                        //remove the hyphens from the phone
                        $currLine[5] = str_replace("-","",$currLine[5]);
                        //remove the leading 1 so it matches the DB
                        $currLine[5] = substr($currLine[5], 1);
                        //remove leading quotation marks
                        $currLine[1] = substr($currLine[1], 1);
                        //remove trailing quotation marks
                        $currLine[2] = substr($currLine[2],1,-1);
                        //push last name, first name, email, phone
                        if ($good)array_push($csvUsers,array('lname'=>$currLine[1],'fname'=>$currLine[2],'phone'=>$currLine[5]));
                        $makeList = true;
                    }
                }
                else {
                    echo "<h1>Error uploading file</h1>";
                }
            }

        }//end if

        if ($makeList) {
            //array to store users
            $csvDiff = array();
            $dbUsers = $dataLayer->getData('user',array('*'));
            //loop through all db users and check if they are in the CSV
            foreach ($dbUsers as $ind => $currDBUser) {
                $csvCheckUser = array('lname'=>$currDBUser['lName'],'fname'=>$currDBUser['fName'],'phone'=>$currDBUser['phone']);
                //in the db but not in the csv
                if (!in_array($csvCheckUser,$csvUsers)) array_push($csvDiff,$currDBUser);
            }
            if (count($csvDiff) == 0) $tableString = "<h2 class='title' id='csvSuccess'>No discrepencies found</h2>";
            else{
                $csvDiff = array();
                $dbUsers = $dataLayer->getData('user',array('*'));
                //loop through all db users and check if they are in the CSV
                foreach ($dbUsers as $ind => $currDBUser) {
                    $csvCheckUser = array('lname'=>$currDBUser['lName'],'fname'=>$currDBUser['fName'],'phone'=>$currDBUser['phone']);
                    //in the db but not in the csv
                    if (!in_array($csvCheckUser,$csvUsers)) array_push($csvDiff,$currDBUser);
                }
                $tableString =<<<END
                <div id='csvFailedListContainer'>
                <h2 class='title' id='csvFailed'>Discrepencies found</h2>
                <table><tr><th></th><th>First</th><th>Last</th><th>Action</th></tr>
END;

foreach ($csvDiff as $ind => $thisUserArray) {
    //fill in using END strings with info ftom currUser
    $currID = $thisUserArray['userID'];
    $currFName = $thisUserArray['fName'];
    $currLName = $thisUserArray['lName'];
    $currActiveYN = $thisUserArray['activeYN'];
    $currEmail = $thisUserArray['email'];
    $currDeptID = $thisUserArray['deptID'];
    $currAuthID = $thisUserArray['authID'];
    $currPhone = $thisUserArray['phone'];

    $tableString .= <<<END
    <tr class='collapsed'>
    <td><i onclick="dropDownToggle(this)" class='fas fa-chevron-circle-down'></i></td>
    <td><input type="text" name="fName" disabled value="{$currFName}"></td>
    <td><input type="text" name="lName" disabled value="{$currLName}"></td>
    <td>
    <i id='empEditButton' onclick="dropDownModify(this,'emp');" class='fas fa-pencil-alt'></i>
    <button class="hidden" id='empSaveEditButton' type= "button" name="modifyEmp" value="modifyEmp"><i class="fas fa-save" onclick='ajaxUpdate({$currID},this)'></i></button>
    </td>
    <td>
    <button type="button" name= "deleteEmp" onclick='ajaxDelete({$currID},this,'csv')' value="deleteEmp"><i class="fas fa-trash-alt"></i></button>
    </td>
    </tr>
    <tr class='spacer'><td></td></tr>
    <tr class='collapsed' style="display:none">
    <td colspan='3' class='leftUnCollapsed'>
    <h2>Active</h2>
    <select disabled name='activeYN' class='disabledDrop'>
    <option value=1>Yes</option>
    <option
END;
    if (!$currActiveYN) $tableString .= " selected ";
    $tableString .=<<<END
    value=0>No</option>
    </select>

    <h2>Department</h2>
    <select disabled name='deptID' class='disabledDrop'>
    <option
END;
    if ($currDeptID == 1) $tableString .= " selected ";
    $tableString .= <<<END
    value=1>HR</option>
    <option
END;
    if ($currDeptID == 2) $tableString .= " selected ";
    $tableString .= <<<END
    value=2>Admin</option>
    <option
END;
    if ($currDeptID == 3) $tableString .= " selected ";
    $tableString .= <<<END
    value=3>Sales</option>
    <option
END;
    if ($currDeptID == 4) $tableString .= " selected ";
    $tableString .= <<<END
    value=4>Production</option>
    <option
END;
    if ($currDeptID == 5) $tableString .= " selected ";
    $tableString .= <<<END
    value=5>Operations</option>
    <option
END;
    if ($currDeptID == 6) $tableString .= " selected ";
    $tableString .= <<<END
    value=6>Food and Beverage</option>
    <option
END;
    if ($currDeptID == 7) $tableString .= " selected ";
    $tableString .= <<<END
    value=7>Garage</option>
    </select>

    <h2>Email</h2>
    <input type="text" name="email" class='email' disabled value="{$currEmail}">

    </td>
    <td colspan='2' class='rightUnCollapsed'>
    <h2>Authorization</h2>
    <select disabled name='authID' class='disabledDrop fullWidth'>
    <option
END;
    if ($currAuthID == 2) $tableString .= " selected ";
    $tableString .= <<<END
    value=2>Employee</option>
    <option
END;
    if ($currAuthID == 3) $tableString .= " selected ";
    $tableString .= <<<END
    value=3>Depart. Head</option>
    <option
END;
    if ($currAuthID == 4) $tableString .= " selected ";
    $tableString .= <<<END
    value=4>Administrator</option>
    </select>

    <h2>Phone Number</h2>
    <input class="phoneMask" type="text" name="phone" disabled value="{$currPhone}">
    </td>
    </tr>
END;
}
$tableString .= "</table>";

            }
            echo $tableString;
        }
        ?>
            </div>
        </div>
    </section>
<!-- Footer -->
<div class='footer block'>
    <ul class='iconContainer'>
        <li class='inline'><a href="news.php"><i class="fas fa-newspaper"></i></a></li>
        <li class='inline'><a href="videos.php"><i class="fas fa-video"></i></a></li>
        <li class='inline'><a href="profile.php"><i class="fas fa-user"></i></a></li>
        <li class='inline active'><a href="adminConsoleCSV.php"><i class="fas fa-toolbox"></i></a></li>
    </ul>
</div>
</body>
</html>
