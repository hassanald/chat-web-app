<?php
session_start();
require_once "../database/connection.php";

if (!isset($_SESSION['user'])){
    header('Location: ./front/login.php');
    exit();
}

date_default_timezone_set('Asia/Tehran');
//Y-m-d-H-i-s
//$messageData = json_decode(file_get_contents('../database/messages.json') , JSON_FORCE_OBJECT);

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
            if (!file_exists("../images/" . $_SESSION['user']->user_name)) {
                mkdir('../images/' . $_SESSION['user']->user_name);
            }
            move_uploaded_file($file['tmp_name'], "../images/" . $_SESSION['user']->user_name . "/" . date('Y-m-d-H-i-s') . "_" . $file['name']);
            //insert into json file

//            $messageData[$_SESSION['user']['user_name'] . "." . $file['name']] = [
//                "user_name" => $_SESSION['user']['user_name'],
//                "message" => $message,
//                "type" => "img",
//                "name" => $file['name'],
//                "full_path" => "images/" . $_SESSION['user']['user_name'] . "/" . date('Y-m-d-H-i-s') . "_" . $file['name'],
//                "date" => date('Y-m-d H:i:s')
//            ];

            //insert into database
            $stmtImg = $conn->prepare('INSERT INTO images (image_name , path , user_id , type) VALUES (:image_name , :path , :user_id , :type)');
            $stmtImg->bindParam(':image_name' , $file['name']);
            $path = "images/" . $_SESSION['user']->user_name . "/" . date('Y-m-d-H-i-s') . "_" . $file['name'];
            $stmtImg->bindParam(':path' , $path);
            $stmtImg->bindParam(':user_id' , $_SESSION['user']->id);
            $type = 'message';
            $stmtImg->bindParam(':type' , $type);
            $stmtImg->execute();

            $stmtImgMsg = $conn->prepare('SELECT * FROM images WHERE path = :path');
            $stmtImgMsg->bindParam(':path' , $path);
            $stmtImgMsg->execute();
            $stmtImgMsgRes = $stmtImgMsg->fetch(5);

            $stmt = $conn->prepare('INSERT INTO messages (message ,date , user_id , image_id ) VALUES (:message ,:date , :user_id , :image_id)');
            $stmt->bindParam(':message' , $message);
            $date = date('Y-m-d H:i:s');
            $stmt->bindParam(':date' , $date);
            $stmt->bindParam(':user_id' , $_SESSION['user']->id);
            $stmt->bindParam(':image_id' , $stmtImgMsgRes->id);
            $stmt->execute();
//            file_put_contents("../database/messages.json", json_encode($messageData , JSON_PRETTY_PRINT));
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
//            $messageData[$message] = [
//                "user_name" => $_SESSION['user']['user_name'],
//                "message" => $message,
//                "date" => date('Y-m-d H:i:s')
//            ];

            $stmtMsg = $conn->prepare('INSERT INTO messages (message ,date , user_id ) VALUES (:message ,:date , :user_id)');
            $stmtMsg->bindParam(':message' , $message);
            $date = date('Y-m-d H:i:s');
            $stmtMsg->bindParam(':date' , $date);
            $stmtMsg->bindParam(':user_id' , $_SESSION['user']->id);
            $stmtMsg->execute();
//            file_put_contents("../database/messages.json", json_encode($messageData , JSON_PRETTY_PRINT));
            header("location: ../index.php");
        }
    }

}

//Edit Logic
if (isset($_POST['edit_btn'])){
    $edit_message = $_POST['message'];
    $message_id = $_POST['message_id'];

//    echo "<pre>";
//    print_r($message_id);
//    echo "</pre>";
//    die();

    if (empty($message)){
        $_SESSION['error']['message'] = "Fill this field please!";
        header("location: ../index.php");
        exit();
    } elseif (strlen($message) > 100){
        $_SESSION['error']['message'] = "Your message should be less than 100 chars!";
        header("location: ../index.php");
        exit();
    }else {
//        $messageData[$edit_message] = [
//            "user_name" => $messageData[$editedMessageInfo]['user_name'],
//            "message" => $edit_message,
//            "date" => $messageData[$editedMessageInfo]['date']
//        ];
//        unset($messageData[$editedMessageInfo]);
//        file_put_contents("../database/messages.json" , json_encode($messageData , JSON_PRETTY_PRINT));

        $stmt = $conn->prepare('UPDATE messages SET message = :message WHERE id = :id');
        $stmt->bindParam(':message' , $edit_message);
        $stmt->bindParam(':id' , $message_id);
        $stmt->execute();

        header("location: ../index.php");
        exit();
    }
}


