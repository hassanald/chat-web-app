<?php
session_start();
$messageData = json_decode(file_get_contents('../database/messages.json') , JSON_FORCE_OBJECT);

$deleted_message =  $_POST['delete_message'];
$deleted_message_user_name =  $_POST['delete_message_user_name'];

//echo "<pre>";
//var_dump($messageData[$deleted_message_user_name .'.'. $deleted_message]);
//echo "</pre>";
//die();
if (isset($_POST['delete_message_path'])) {
    if (file_exists('../' . $_POST['delete_message_path'])) {
        unlink('../' . $_POST['delete_message_path']);
    }
    unset($messageData[$deleted_message_user_name .'.'. $deleted_message]);
    file_put_contents("../database/messages.json" , json_encode($messageData));
    header("location: ../index.php");
    exit();
}else{
    unset($messageData[$deleted_message]);
    file_put_contents("../database/messages.json", json_encode($messageData));
    header("location: ../index.php");
}

