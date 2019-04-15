<?php
require_once '../database/data_layer.php';
$dataLayer = new data_layer();

$notificationID = $_GET['id'];
$fileLink = $dataLayer->getData('notification',array('attachment'),'notificationID',$notificationID);
$fileLink =  $fileLink[0]['attachment'];

$file = "../" . $fileLink;
//assets/uploads/1.jpg
$fileName = end(explode("/",$file));

// //set the file to download
// $file = "../assets/ackReports/AcknowledgementReport_{$timestamp}.csv";
// //get fil
$type = filetype($file);
if (file_exists($file)){
    // // // Send file headers
    header("Content-type: $type");
    header("Content-Disposition: attachment;filename=$fileName");
    header("Content-Transfer-Encoding: binary");
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Expires: 0');
    // Send the file contents.
    set_time_limit(0);
    readfile($file);
}
else{
    $url = $_SERVER['HTTP_REFERER'];
    header("Location: $url");
}


 ?>
