<?php
require_once '../../business/business_layer.php';
$bizLayer = new business_layer();
if(isset($_POST['csvUpload'])){
    //check if file is set
    if (isset($_FILES['attachment']) && $_FILES['attachment']['size'] > 0 ){
        //send file to business layer to upload with callback of csv (for some far checks)
        if ($bizLayer->uploadFile($_FILES['attachment'], 'csv')){
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
        }
        else {
            //echo "Error uploading file";
        }
    }
    header("Location: ../../views/adminConsoleCSV.php");

}
 ?>
