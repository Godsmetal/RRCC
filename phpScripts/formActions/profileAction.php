<?php
session_start();
require_once '../../database/data_layer.php';
$dataLayer = new data_layer();

if(isset($_POST)){
    $_POST['phone'] = str_replace("-","",$_POST['phone']);
    $dataLayer->updateUser($_POST,'userID',$_SESSION['userID']);
    header("Location: ../../views/profile.php");
}

 ?>
