<?php
session_start();
if(!isset($_SESSION['phone'])){
    //session var is not set = they are not logged in
    header("Location: ../index.php");
}
require_once '../database/data_layer.php';
$dataLayer = new data_layer();
//create a row to show acknowledgement
$dataLayer->createNotiAck($_SESSION['phone'],$_GET['id']);
//set vars so they can be passed in the url
$id = $_GET['id'];
$img = $_GET['img'];
header("Location: ../views/notification.php?id={$id}&img={$img}");

 ?>
