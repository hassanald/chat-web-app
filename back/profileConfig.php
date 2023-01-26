<?php
session_start();
date_default_timezone_set('Asia/Tehran');

$usersData = json_decode(file_get_contents('../database/users.json') , true);


$name = $_POST['name'] ?? "";
$about_me = $_POST['about_me'] ?? "";
if ($_FILES['image']['error'] == 0){
    $file = $_FILES['image'];
}else {
    $file = "";
}

$usersData[$_SESSION['user']['email']] = [
    'name' => $name,
    'email' => $usersData[$_SESSION['user']['email']]['email'],
    'role' => $usersData[$_SESSION['user']['email']]['role'],
    'user_name' => $usersData[$_SESSION['user']['email']]['user_name'],
    'password' => $usersData[$_SESSION['user']['email']]['password'],
    'status' => $usersData[$_SESSION['user']['email']]['status'],
    'images' => $usersData[$_SESSION['user']['email']]['images'],
    'about_me' => $about_me
];

if ($file != ""){
    if (!file_exists('../profileImage/' . $usersData[$_SESSION['user']['email']]['email'])){
        mkdir('../profileImage/' . $usersData[$_SESSION['user']['email']]['email']);
    }
    move_uploaded_file($file['tmp_name'] , '../profileImage' . '/' . $usersData[$_SESSION['user']['email']]['email']
                                                                    . '/' . date('Y_m_d_H_i_s') . '_' . $file['name']);
    $usersData[$_SESSION['user']['email']]['images'][] = [
        'image_name' => $file['name'],
        'full_name' => date('Y_m_d_H_i_s') . '_' .$file['name'],
        'full_path' => 'profileImage' . '/' . $usersData[$_SESSION['user']['email']]['email'] . '/' . date('Y_m_d_H_i_s') . '_' . $file['name'],
        'date' => date('Y-m-d H:i:s')
    ];
}

file_put_contents( '../database/users.json'  ,json_encode($usersData ,JSON_PRETTY_PRINT));






