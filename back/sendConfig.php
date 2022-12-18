<?php
session_start();
if (!isset($_SESSION['user'])){
    header('Location: ./front/login.php');
    exit();
}

date_default_timezone_set('Asia/Tehran');
//Y-m-d-H-i-s
$messageData = json_decode(file_get_contents('../database/messages.json') , JSON_FORCE_OBJECT);
$message = $_POST['message'];

$_SESSION['error'] = [
    'message' => ""
];

if (empty($message)){
    $_SESSION['error']['message'] = "Fill this field please!";
    header("location: ../index.php");
    exit();
} elseif (strlen($message) > 100){
    $_SESSION['error']['message'] = "Your message should be less than 100 chars!";
    header("location: ../index.php");
    exit();
}else {
    $messageData[] = [
        "user_name" => $_SESSION['user']['user_name'],
        "message" => $message,
        "date" => date('Y-m-d H:i:s')
    ];
    file_put_contents("../database/messages.json" , json_encode($messageData));
    header("location: ../index.php");
    exit();
}
