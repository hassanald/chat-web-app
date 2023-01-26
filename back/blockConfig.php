<?php
session_start();
$userData = json_decode(file_get_contents('../database/users.json') , JSON_FORCE_OBJECT) ?? "";
$blocked_user = $_POST['blocked_user'];

if (isset($_POST['block'])){
    $userData[$blocked_user] = [
        "name" => $userData[$blocked_user]['name'],
        "email" => $userData[$blocked_user]['email'],
        'role' => $userData[$blocked_user]['role'],
        "user_name" => $userData[$blocked_user]['user_name'],
        "password" => $userData[$blocked_user]['password'],
        "status" => 1
    ];
    file_put_contents( "../database/users.json" , json_encode($userData));
    header("location: ../front/adminUser.php");
    exit();
}

if (isset($_POST['unblock'])){
    $userData[$blocked_user] = [
        "name" => $userData[$blocked_user]['name'],
        "email" => $userData[$blocked_user]['email'],
        'role' => $userData[$blocked_user]['role'],
        "user_name" => $userData[$blocked_user]['user_name'],
        "password" => $userData[$blocked_user]['password'],
        "status" => 0
    ];
    file_put_contents( "../database/users.json" , json_encode($userData));
    header("location: ../front/adminUser.php");
    exit();
}