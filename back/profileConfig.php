<?php
session_start();
require_once "../database/connection.php";
date_default_timezone_set('Asia/Tehran');

//$usersData = json_decode(file_get_contents('../database/users.json') , true);

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//die();

$name = $_POST['name'] ?? "";
$about_me = $_POST['about_me'] ?? "";
if ($_FILES['image']['error'] == 0){
    $file = $_FILES['image'];
}else {
    $file = "";
}

$stmt = $conn->prepare('UPDATE users SET about_me = :about_me , name = :name WHERE id = :id');
$stmt->bindParam(':about_me' , $about_me);
$stmt->bindParam(':name' , $name);
$stmt->bindParam(':id' , $_SESSION['user']->id);
$stmt->execute();


//$usersData[$_SESSION['user']['email']] = [
//    'name' => $name,
//    'email' => $usersData[$_SESSION['user']['email']]['email'],
//    'role' => $usersData[$_SESSION['user']['email']]['role'],
//    'user_name' => $usersData[$_SESSION['user']['email']]['user_name'],
//    'password' => $usersData[$_SESSION['user']['email']]['password'],
//    'status' => $usersData[$_SESSION['user']['email']]['status'],
//    'images' => $usersData[$_SESSION['user']['email']]['images'],
//    'about_me' => $about_me
//];

if ($file != ""){
    if (!file_exists('../profileImage/' . $_SESSION['user']->email)){
        mkdir('../profileImage/' . $_SESSION['user']->email);
    }
    move_uploaded_file($file['tmp_name'] , '../profileImage' . '/' . $_SESSION['user']->email
                                                                    . '/' . date('Y_m_d_H_i_s') . '_' . $file['name']);
//    $usersData[$_SESSION['user']['email']]['images'][] = [
//        'image_name' => $file['name'],
//        'full_name' => date('Y_m_d_H_i_s') . '_' .$file['name'],
//        'full_path' => 'profileImage' . '/' . $usersData[$_SESSION['user']['email']]['email'] . '/' . date('Y_m_d_H_i_s') . '_' . $file['name'],
//        'date' => date('Y-m-d H:i:s')
//    ];

    $stmtImg = $conn->prepare('INSERT INTO images (image_name ,path , user_id , type) VALUES (:image_name , :path , :user_id , :type)');
    $stmtImg->bindParam(':image_name' , $file['name']);
    $path = 'profileImage' . '/' . $_SESSION['user']->email . '/' . date('Y_m_d_H_i_s') . '_' . $file['name'];
    $stmtImg->bindParam(':path' , $path);
    $stmtImg->bindParam(':user_id' , $_SESSION['user']->id);
    $type = 'profile';
    $stmtImg->bindParam(':type' , $type);
    $stmtImg->execute();
}

//file_put_contents( '../database/users.json'  ,json_encode($usersData ,JSON_PRETTY_PRINT));






