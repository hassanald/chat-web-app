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
$file = $_FILES['file'];

$_SESSION['error'] = [
    'message' => "",
    'file' => ""
];
//Send Logic
if (isset($_POST['send'])){
    if ($file['error'] === 0) {
        $allowed = ['image/jpeg' , 'image/png'];

        if (!in_array($file['type'] , $allowed)){
            $_SESSION['error']['file'] = "Not allowed format";
            header("location: ../index.php");
            exit();
        }elseif (($file['type'] == "image/jpeg" || $file['type'] == "image/png") && $file['size'] > 3145728){
            $_SESSION['error']['file'] = "File size must be less than 3mb!!";
            header("location: ../index.php");
            exit();
        }elseif (strlen($message) > 100){
            $_SESSION['error']['message'] = "Your message should be less than 100 chars!";
            header("location: ../index.php");
            exit();
        } else {
            if (!file_exists("../images/" . $_SESSION['user']['user_name'])) {
                mkdir('../images/' . $_SESSION['user']['user_name']);
            }
            move_uploaded_file($file['tmp_name'], "../images/" . $_SESSION['user']['user_name'] . "/" . date('Y-m-d-H-i-s') . "_" . $file['name']);
            $messageData[$_SESSION['user']['user_name'] . "." . $file['name']] = [
                "user_name" => $_SESSION['user']['user_name'],
                "message" => $message,
                "type" => "img",
                "name" => $file['name'],
                "full_path" => "images/" . $_SESSION['user']['user_name'] . "/" . date('Y-m-d-H-i-s') . "_" . $file['name'],
                "date" => date('Y-m-d H:i:s')
            ];
            file_put_contents("../database/messages.json", json_encode($messageData , JSON_PRETTY_PRINT));
            header("location: ../index.php");
        }
    }else{
        if (empty($message)) {
            $_SESSION['error']['message'] = "Fill this field please!";
            header("location: ../index.php");
            exit();
        } elseif (strlen($message) > 100) {
            $_SESSION['error']['message'] = "Your message should be less than 100 chars!";
            header("location: ../index.php");
            exit();
        } else {
            $messageData[$message] = [
                "user_name" => $_SESSION['user']['user_name'],
                "message" => $message,
                "date" => date('Y-m-d H:i:s')
            ];
            file_put_contents("../database/messages.json", json_encode($messageData , JSON_PRETTY_PRINT));
            header("location: ../index.php");
        }
    }

}

//Edit Logic
if (isset($_POST['edit_btn'])){
    $edit_message = $_POST['message'];
    $editedMessageInfo = $_POST['editedMessageInfo'];

    if (empty($message)){
        $_SESSION['error']['message'] = "Fill this field please!";
        header("location: ../index.php");
        exit();
    } elseif (strlen($message) > 100){
        $_SESSION['error']['message'] = "Your message should be less than 100 chars!";
        header("location: ../index.php");
        exit();
    }else {
        $messageData[$edit_message] = [
            "user_name" => $messageData[$editedMessageInfo]['user_name'],
            "message" => $edit_message,
            "date" => $messageData[$editedMessageInfo]['date']
        ];
        unset($messageData[$editedMessageInfo]);
        file_put_contents("../database/messages.json" , json_encode($messageData , JSON_PRETTY_PRINT));
        header("location: ../index.php");
        exit();
    }
}


