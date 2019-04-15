<?php
require_once '../database/data_layer.php';
$dataLayer = new data_layer();

$notificationID = $_GET['id'];
//join tables to get user info for people who acknowledge this
$ackUserArray = $dataLayer->getAckUserData($notificationID);

// initialize the 2d array that will become the csv
$ackCSV[0] = array('First Name','Last Name','Email','Phone','Acknowledgement Date');

//loop through each person and add them to the 2d array that wil become the file
foreach ($ackUserArray as $ind => $currUser) {
    $ackCSV[$ind+1] = array($currUser['fName'],$currUser['lName'],$currUser['email'],$currUser['phone'],$currUser['ackDate']);
}

//create timestamp to add to file name
$timestamp = date("Ymd");
//create file to write to
$fp = fopen("../assets/ackReports/AcknowledgementReport_{$timestamp}.csv", 'wrb');

//Loop though each line and write it to the file
foreach ($ackCSV as $line) {
    fputcsv($fp, $line, ',');
}
fclose($fp);


//set the file to download
$file = "../assets/ackReports/AcknowledgementReport_{$timestamp}.csv";
//get fil
$type = filetype($file);
// // Send file headers
header("Content-type: csv");
header("Content-Disposition: attachment;filename=AcknowledgementReport_{$timestamp}.csv");
header("Content-Transfer-Encoding: binary");
header('Pragma: no-cache');
header('Expires: 0');
header('Expires: 0');
// Send the file contents.
set_time_limit(0);
readfile($file);


 ?>
