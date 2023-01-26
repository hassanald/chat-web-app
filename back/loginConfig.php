<?php
session_start();
require_once "../helper/helper.php";

$email = $_POST['email'];
$password = $_POST['password'];

$userData = file_get_contents('../database/users.json');
$decodedUsersData = json_decode($userData , JSON_OBJECT_AS_ARRAY) ?? [];
$_SESSION['error'] = [
    'email' => '',
    'password' => ''
];

//Password Validation
if(empty($password)){
    $_SESSION['error']['password'] = 'Please fill this field.';
    redirect('login');
}elseif(strlen($password) < 4 || strlen($password) > 32){
    $_SESSION['error']['password'] = 'Your password should contain at least 4 chars and at most 32 chars!';
    redirect('login');
}

//Email Validation
if (empty($email)) {
    $_SESSION['error']['email'] = 'Please fill this field.';
    redirect('login');
}elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $_SESSION['error']['email'] = 'Invalid email!';
    redirect('login');
}elseif (array_key_exists($email , $decodedUsersData)){
    if ($decodedUsersData[$email]['password'] != $password) {
        $_SESSION['error']['password'] = "Bad credentials";
        header("location: ../front/login.php");
        exit();
    }else {
        $_SESSION['user'] = [
            "name" => $decodedUsersData[$email]['name'],
            "email" => $email,
            "role" => $decodedUsersData[$email]['role'],
            "user_name" => $decodedUsersData[$email]['user_name'],
            "status" => $decodedUsersData[$email]['status']
        ];
        header("location: ../index.php");
    }
}else {
    $_SESSION['error']['email'] = "User not found";
    header("location: ../front/login.php");
    exit();
}

?>