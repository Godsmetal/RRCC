<?php
require_once '../../database/data_layer.php';
require_once '../../business/business_layer.php';
$dataLayer = new data_layer();
$businessLayer = new business_layer();
if (isset($_GET['p'])) {
    $pageNum = $_GET['p'];
}
//add new employee button was clicked
if (isset($_POST['addEmp'])){
    //fix the phone number so it works in the DB
    $_POST["phoneNumber"] = str_replace("-","",$_POST["phoneNumber"]);
    //val and  san user input
    $_POST = $businessLayer->valAndSanUser($_POST);
    //Generate a random 10 character password
    $genPass = substr(md5(microtime()),rand(0,26),10);
    $_POST['password'] = "Password1";
    //pass in 1 becaue it is a temp pass.
    //Also pass in the auth value individually
    $dataLayer->createNewUser($_POST, 1, $_POST['authID'], $_POST['activeYN']);

    $phone = $_POST["phoneNumber"];

    //after successfully creating the user send them their password
    //address, subject, body
    $businessLayer->sendEmail($_POST['email'], "RRCC Account Created For You", "You can sign in with the Phone Number $phone and a password of Password1");
    header("Location: ../../views/adminConsoleEmployee.php");
}

//delete employee button was clicked
if (isset($_POST['deleteEmp'])) {
    $dataLayer->deleteData('user','userID',$_GET['id']);
    header("Location: ../../views/adminConsoleEmployee.php");
}

//Modify employee was clicked
if (isset($_POST['modifyEmp'])) {
    $_POST = $businessLayer->valAndSanUser($_POST);
    //During validation and sanitization the button value should be removed from post data
    //Im going to set it to null for now
    $_POST['modifyEmp'] = null;
    //fix the phone number so it works in the DB
    $_POST["phone"] = str_replace("-","",$_POST["phone"]);
    $dataLayer->updateUser($_POST,'userID',$_GET['id']);
    //var_dump($_POST);
    header("Location: ../../views/adminConsoleEmployee.php?page=$pageNum");
}
 ?>
