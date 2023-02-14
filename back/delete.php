<?php
session_start();
require_once "../database/connection.php";

//$messageData = json_decode(file_get_contents('../database/messages.json') , JSON_FORCE_OBJECT);

$deleted_message_id =  $_POST['delete_message_id'];
$deletet_message_image_path = $_POST['delete_message_path'] ?? '';

if ($deletet_message_image_path != '') {
    if (file_exists('../' . $deletet_message_image_path)) {
        unlink('../' . $deletet_message_image_path);
    }
//    unset($messageData[$deleted_message_user_name .'.'. $deleted_message]);
//    file_put_contents("../database/messages.json" , json_encode($messageData));

    $stmtDlt = $conn->prepare('DELETE FROM messages WHERE id = :id');
    $stmtDlt->bindParam(':id' , $deleted_message_id);
    $stmtDlt->execute();

    header("location: ../index.php");
    exit();
}else{
//    unset($messageData[$deleted_message]);
//    file_put_contents("../database/messages.json", json_encode($messageData));
    $stmtDlt = $conn->prepare('DELETE FROM messages WHERE id = :id');
    $stmtDlt->bindParam(':id' , $deleted_message_id);
    $stmtDlt->execute();

    header("location: ../index.php");
}

