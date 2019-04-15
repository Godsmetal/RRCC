<?php
require_once '../../database/data_layer.php';
$dataLayer = new data_layer();

if(isset($_POST['confirmPendEmp'])){
    //send text / email saying you have been confirmed

    $dataLayer->updateUser(array('authID' => intval($_POST['pendingAuthID']) ),'userID',$_GET['id']);
    //echo "Confirm User with authID {$_POST['pendingAuthID']}";
    //echo "</br>In progress, go back to admin console via URL";
    header("Location: ../../views/adminConsolePending.php");
}

if(isset($_POST['denyPendEmp'])){
    //delete from  user where id = $_GET['id']
    $dataLayer->deleteData('user','userID',$_GET['id']);
    //echo "Delete user";
    //echo "</br>In progress, go back to admin console via URL";
    header("Location: ../../views/adminConsolePending.php");
}

 ?>
