<?php
require_once '../database/data_layer.php';
require_once '../business/business_layer.php';
$dataLayer = new data_layer();
$bizLayer = new business_layer();

 $hashedEmail = $_GET['e'];
 $inputEmail = $_POST['email'];

if (password_verify($inputEmail,$hashedEmail)) {
    if ($_POST['newPassword'] == $_POST['newPasswordConfirm']) {
        $hashedNewPass = password_hash($_POST['newPassword'],PASSWORD_DEFAULT);
        $updateArray = array('password' => $hashedNewPass);
        $dataLayer->updateUser($updateArray, "email", $inputEmail);
        session_destroy();
        header("Location: ../index.php");
    }
    else {
        echo "New passwords did not match";
    }
}
else {
    echo "Incorrect Email address, send them back to resetConfirm with the hashed email as a get var";
}

 ?>
