<?php
require_once '../database/data_layer.php';
$dataLayer = new data_layer();

//GET var is set. check against the DB
if (count($_GET)){
    //phone was passed in from ajax call
    if(isset($_GET['phone'])){
        //reformat phone number to check against DB
        $dlPhone = str_replace("-","",$_GET['phone']);
        //do a select looking for the passed in phone
        $checkArray = $dataLayer->getData('user',array("userID"),'phone',$dlPhone);
    }
    //
    // //email was passed in from ajax call
    if(isset($_GET['email'])){
        $checkArray = $dataLayer->getData('user',array("userID"),'email',$_GET['email']);
    }
    // //return count of rows found with that phone or email
    // //if count != 0 then its already taken
    echo count($checkArray);
}

//Post is set. create the user..
else{
    $dataLayer->createNewUser($_POST);
}

 ?>
