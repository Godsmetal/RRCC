<?php
    session_start();
    require_once '../database/data_layer.php';
    require_once '../business/partialViews.php';
    $dataLayer = new data_layer();
    $partialViews = new partialViews();

    if (count($_GET)){
        $searchVal = $_GET['search'];
        $matchArray = array();
        if ($_GET['page'] == 'emp'){
            foreach ($dataLayer->getData('user',array('userID','fName','lName','activeYN','email','deptID','authID','phone')) as $ind => $currUserArray) {
                //check phone, email, fName, lName
                $currPhone = $currUserArray['phone'];
                $currEmail = $currUserArray['email'];
                $currFName = $currUserArray['fName'];
                $currLName = $currUserArray['lName'];
                if(strpos($currPhone,$searchVal) !== false) $matchArray[] = $currUserArray;
                else if(strpos($currEmail,$searchVal) !== false) $matchArray[] = $currUserArray;
                else if(strpos($currFName,$searchVal) !== false) $matchArray[] = $currUserArray;
                else if(strpos($currLName,$searchVal) !== false) $matchArray[] = $currUserArray;
            }
            echo $partialViews->createEmployeeTable($matchArray,1,1);
        }

        if($_GET['page'] == 'news'){
            foreach ($dataLayer->getData('notification',array("*")) as $ind => $currNotiArray) {
                //check title and body
                $currTitle = $currNotiArray['title'];
                $currBody = $currNotiArray['body'];
                if(strpos($currTitle,$searchVal) !== false) $matchArray[] = $currNotiArray;
                else if(strpos($currBody,$searchVal) !== false) $matchArray[] = $currNotiArray;
            }
            echo $partialViews->createAdminConsoleNewsTable($matchArray,1,1);
        }
    }
    else if (isset($_POST)){
        $userID = $_POST['userID'];
        if (isset($_POST['delete'])){
            if (isset($_POST['user'])) {
                // code...
                $dataLayer->deleteData('user','userID',$_POST['userID']);
                //
            }
            if (isset($_POST['noti'])) {
                $dataLayer->deleteData('notification','notificationID',$_POST['notificationID']);
            }
        }
        else {
            //not a delete. start by removing hyphens from phone
            $_POST['phone'] = str_replace("-","",$_POST['phone']);
            unset($_POST['userID']);
            $dataLayer->updateUser($_POST,'userID',$userID);
        }
    }
?>
