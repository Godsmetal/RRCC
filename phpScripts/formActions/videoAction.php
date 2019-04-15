<?php
    require_once '../../database/data_layer.php';
    $dataLayer = new data_layer();

    if (!count($_GET)){
        //validate and sanitize the link right here..
        //they did not pass in an id, add a new one
        $dataLayer->addVideo($_POST['link']);

    }
    if (isset($_GET['id'])){
        //an ID was passed in, update the id
        $dataLayer->updateVideo($_POST['link'],$_GET['id']);
    }
    if (isset($_GET['delete'])){
        //delete was passed, use the id
        $dataLayer->deleteData('video','videoID',$_GET['delete']);
    }

    header("Location: ../../views/videos.php");
 ?>
