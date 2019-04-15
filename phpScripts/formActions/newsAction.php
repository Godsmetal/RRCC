<?php
require_once '../../database/data_layer.php';
require_once '../../business/business_layer.php';
$dataLayer = new data_layer();
$bizLayer = new business_layer();

if (isset($_POST['sendNoti'])){
    //$bizLayer->valAndSanNoti($_POST);
    //first make a string of deptIDs for the DB
    $viewableBy = "";
    //if its a dept head only their dept can view it.
    if ($_SESSION['authID'] == 3){
        //this sets the var going into the database to be their department
        $viewableBy = $_SESSION['deptID'];
    }
    //if its an admin then check which boxes were checked to send to
    if ($_SESSION['authID'] == 4){
        //Loop through $_POST to check which check boxes are chosen
        foreach ($_POST as $key => $value) {
            //$_POST names are set as dept_name
            if(explode("_",$key)[0] == "dept"){
                //value of the $_POST checkboxes are deptID vals
                $viewableBy .= strval($value);
            }
        }
    }
    //set the post val to pass to DB
    $_POST["viewableBy"] = $viewableBy;
    //store who notification is sent by using session var
    $_POST["sentBy"] = $_SESSION['userID'];
    //business layer stuff to deal with the attachment ($_FILES)
    if (isset($_FILES['attachment']) && $_FILES['attachment']['size'] > 0 ){
        if ($bizLayer->uploadFile($_FILES['attachment'],'noti')){
            //file successfully uploaded
            //set the post var for the attachment. This will be the path to the uploaded file
            //Putting it in POST will upload it to the DB
            $_POST['attachment'] = "assets/uploads/".$_FILES['attachment']['name'];
        }
        else {
            //echo "Error uploading file";
        }

    }
    //loop through each user to see if they should be sent the notification
    foreach ($dataLayer->getData('user', array('email','phone','deptID')) as $ind => $currUser) {
        //only send them the notification if their deptID is in the viewableBy string
        if(in_array($currUser['deptID'],str_split($viewableBy))){
            //first send email if it was chosen in the check box
            if (isset($_POST['emailCheck'])){
                $currEmail = $currUser['email'];
                //if it has an attachment send that
                if (isset($_POST['attachment'])) $bizLayer->sendEmail($currEmail,$_POST['title'],$_POST['body'],$_POST['attachment']);
                //No attachment just send title and body
                else $bizLayer->sendEmail($currEmail,$_POST['title'],$_POST['body']);
            }

            //next send the text if it was chosen
            if (isset($_POST['phoneCheck'])){
                $fullText = "\n".$_POST['title']."\n\n".$_POST['body'];
                //$bizLayer->sendText($fullText); defaults to my phone bc twillio
                //$bizLayer->sendText($fullText,$currUser['phone']);
            }
        }
        //users deptID not in viewableBy string get nothing and like it
        else{}
        }

        //if webAppCheck is set make the post val = 1;
        $_POST['webAppYN'] = isset($_POST['webAppCheck']) ? (1) : (0);
        if (strpos($_POST['surveyLink'],"http") === false && $_POST['surveyLink'] != "No Survey"){
            $_POST['surveyLink'] = "https://".$_POST['surveyLink'];
        }
        $dataLayer->createNotification($bizLayer->valAndSanNoti($_POST));
    }

    //Delete notification button was clicked
    if (isset($_POST['deleteNoti'])) {
        $dataLayer->deleteData('notification','notificationID',$_GET['id']);
    }

    //Modify Notification button was pressed
    if (isset($_POST['modifyNoti'])) {
        //if survey link is set
        if(isset($_POST['surveyLink'])){
            if (strpos($_POST['surveyLink'],"http") === false && $_POST['surveyLink'] != "No Survey"){
                $_POST['surveyLink'] = "https://".$_POST['surveyLink'];
            }
        }
        //if files is set upload it then add it to post
        if (isset($_FILES['addNotiAttachment']) && $_FILES['addNotiAttachment']['size'] > 0 ){
            if ($bizLayer->uploadFile($_FILES['addNotiAttachment'],'noti')){
                //file was uploaded. set a relative path for the DB
                $_POST['attachment'] = "assets/uploads/".$_FILES['addNotiAttachment']['name'];
                //call update including file
                $dataLayer->updateNotification($_GET['id'],$bizLayer->valAndSanNoti($_POST),1);
            }
            else {
                //echo "Error uploading file";
            }
        }
        else{
            //call update without attachment
            $dataLayer->updateNotification($_GET['id'],$bizLayer->valAndSanNoti($_POST));
        }
    }
    //Remove the attachment
    if (isset($_POST['removeNotiAttachment'])) {
        $dataLayer->removeNotiAttachment($_GET['id']);
    }

    if ($_SESSION['authID'] == 3) {
        //if they are a deptHead send them back to deptHeadNotiConsole
        header("Location: ../../views/deptHeadNotiConsole.php");
    }
    if ($_SESSION['authID'] == 4) {
        //if they are an admin send them back to adminConsole
        header("Location: ../../views/adminConsoleNews.php");
    }

    ?>
