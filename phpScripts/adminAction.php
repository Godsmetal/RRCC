<?php
//This file will look at what button was clicked (form submission) in the admin console and act accordingly
session_start();
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$businessLayer = new business_layer();
$dataLayer = new data_layer();


//Sending a new notification was clicked
if (isset($_POST['sendNoti'])){
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
        if ($businessLayer->uploadFile($_FILES['attachment'],'noti')){
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
            //first send email if it was chosen
            if (isset($_POST['emailCheck'])){
                $currEmail = $currUser['email'];
                //if it has an attachment send that
                if (isset($_POST['attachment'])) $businessLayer->sendEmail($currEmail,$_POST['title'],$_POST['body'],$_POST['attachment']);
                //No attachment just send title and body
                else $businessLayer->sendEmail($currEmail,$_POST['title'],$_POST['body']);
            }

            //next send the text if it was chosen
            if (isset($_POST['phoneCheck'])){
                $fullText = "\n".$_POST['title']."\n\n".$_POST['body'];
                //$businessLayer->sendText($fullText); defaults to my phone bc twillio
                //$businessLayer->sendText($fullText,$currUser['phone']);
            }
        }
        //users deptID not in viewableBy string get nothing and like it
        else{}
    }

    //if webAppCheck is set make the post val = 1;
    $_POST['webAppYN'] = isset($_POST['webAppCheck']) ? (1) : (0);
    $dataLayer->createNotification($_POST);
    if ($_SESSION['authID'] == 3) {
        //if they are a deptHead send them back to deptHeadNotiConsole
        header("Location: deptHeadNotiConsole.php");
    }
    if ($_SESSION['authID'] == 4) {
        //if they are an admin send them back to adminConsole
        header("Location: adminConsole.php#n");
    }

}

//Delete notification button was clicked
if (isset($_POST['deleteNoti'])) {

    $dataLayer->deleteNotification($_GET['id']);
    if ($_SESSION['authID'] == 3) {
        //if they are a deptHead send them back to deptHeadNotiConsole
        header("Location: deptHeadNotiConsole.php");
    }
    if ($_SESSION['authID'] == 4) {
        //if they are an admin send them back to adminConsole
        header("Location: adminConsole.php#n");
    }
}

//Delete notification button was clicked
if (isset($_POST['modifyNoti'])) {
    $dataLayer->updateNotification($_GET['id'],$_POST);
    if ($_SESSION['authID'] == 3) {
        //if they are a deptHead send them back to deptHeadNotiConsole
        header("Location: deptHeadNotiConsole.php");
    }
    if ($_SESSION['authID'] == 4) {
        //if they are an admin send them back to adminConsole
        header("Location: adminConsole.php#n");
    }
}
//Remove the attachment
if (isset($_POST['removeNotiAttachment'])) {
    $dataLayer->removeNotiAttachment($_GET['id']);
    if ($_SESSION['authID'] == 3) {
        //if they are a deptHead send them back to deptHeadNotiConsole
        header("Location: deptHeadNotiConsole.php");
    }
    if ($_SESSION['authID'] == 4) {
        //if they are an admin send them back to adminConsole
        header("Location: adminConsole.php#n");
    }
}

//add new employee button was clicked
if (isset($_POST['addEmp'])){
    //Generate a random 10 character password
    $genPass = substr(md5(microtime()),rand(0,26),10);
    $_POST['password'] = $genPass;
    //fix the phone number so it works in the DB
    $_POST["phoneNumber"] = str_replace("-","",$_POST["phoneNumber"]);
    //pass in 1 becaue it is a temp pass.
    //Also pass in the auth value individually to make things easier
    $dataLayer->createNewUser($_POST, 1, $_POST['authID'], $_POST['activeYN']);

    $phone = $_POST["phoneNumber"];

    //after successfully creating the user send them their password
    //address, subject, body
    $businessLayer->sendEmail($_POST['email'], "RRCC Account Created For You", "You can sign in with the phoneNumber $phone and password $genPass");
    header("Location: adminConsole.php#e");

}

//delete employee button was clicked
if (isset($_POST['deleteEmp'])) {
    $dataLayer->deleteUser($_GET['id']);
    header("Location: adminConsole.php#e");
    //var_dump($_POST);
}

//Modify employee was clicked
if (isset($_POST['modifyEmp'])) {
    //During validation and sanitization the button value should be removed from post data
    //Im going to set it to null for now
    $_POST['modifyEmp'] = null;
    //fix the phone number so it works in the DB
    $_POST["phone"] = str_replace("-","",$_POST["phone"]);
    $dataLayer->updateUser($_POST,'userID',$_GET['id']);
    //var_dump($_POST);
    header("Location: adminConsole.php#e");
}

if(isset($_POST['confirmPendEmp'])){
    //send text / email saying you have been confirmed

    $dataLayer->updateUser(array('authID' => intval($_POST['pendingAuthID']) ),'userID',$_GET['id']);
    //echo "Confirm User with authID {$_POST['pendingAuthID']}";
    //echo "</br>In progress, go back to admin console via URL";
    header("Location: adminConsole.php#p");

}

if(isset($_POST['denyPendEmp'])){
    //delete from  user where id = $_GET['id']
    $dataLayer->deleteData('user','userID',$_GET['id']);
    //echo "Delete user";
    //echo "</br>In progress, go back to admin console via URL";
    header("Location: adminConsole.php#p");
}

if(isset($_POST['csvUpload'])){
    //check if file is set
    if (isset($_FILES['attachment']) && $_FILES['attachment']['size'] > 0 ){
        //send file to business layer to upload with callback of csv (for some far checks)
        if ($businessLayer->uploadFile($_FILES['attachment'], 'csv')){
            //Parse the file using php to find the differences between that and the DB users
            $fileTxt = file_get_contents('../assets/uploads/currEmpCSV.csv');
            $fileLines = explode("\n", $fileTxt);
            $dataLines = array();
            foreach ($fileLines as $currLine) {
                $good = true;
                //split the line like a csv
                $currLine = explode(",", $currLine);

                if (count($currLine) != 8)$good = false;
                //push last name, first name, email, phone
                if ($good)array_push($dataLines,array($currLine[1],$currLine[2],$currLine[4],$currLine[5]));
            }
            echo '<pre>', var_dump($dataLines), '</pre>';
            echo "it worked";
        }
        else {
            //echo "Error uploading file";
        }

    }
    header("Location: adminConsole.php#c");
}


ob_end_flush();
?>
