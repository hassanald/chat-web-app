<?php
session_start();
require_once "../database/connection.php";

//$userData = json_decode(file_get_contents('../database/users.json') , JSON_FORCE_OBJECT) ?? "";
$blocked_user = $_POST['blocked_user'];

if (isset($_POST['block'])){
    $stmtUsr = $conn->prepare('UPDATE users SET status = 1 WHERE id = :id');
    $stmtUsr->bindParam(':id' , $blocked_user);
    $stmtUsr->execute();

//    $userData[$blocked_user] = [
//        "name" => $userData[$blocked_user]['name'],
//        "email" => $userData[$blocked_user]['email'],
//        'role' => $userData[$blocked_user]['role'],
//        "user_name" => $userData[$blocked_user]['user_name'],
//        "password" => $userData[$blocked_user]['password'],
//        "status" => 1
//    ];
//    file_put_contents( "../database/users.json" , json_encode($userData));
    header("location: ../front/adminUser.php");
    exit();
}

if (isset($_POST['unblock'])){

    $stmtUsr = $conn->prepare('UPDATE users SET status = 0 WHERE id = :id');
    $stmtUsr->bindParam(':id' , $blocked_user);
    $stmtUsr->execute();

//    $userData[$blocked_user] = [
//        "name" => $userData[$blocked_user]['name'],
//        "email" => $userData[$blocked_user]['email'],
//        'role' => $userData[$blocked_user]['role'],
//        "user_name" => $userData[$blocked_user]['user_name'],
//        "password" => $userData[$blocked_user]['password'],
//        "status" => 0
//    ];
//    file_put_contents( "../database/users.json" , json_encode($userData));
    header("location: ../front/adminUser.php");
    exit();
}