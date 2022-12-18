<?php
session_start();
require_once "../helper/helper.php";

$name = $_POST['name'];
$email = $_POST['email'];
$user_name = $_POST['user_name'];
$password = $_POST['password'];

$userData = file_get_contents('../database/users.json');
$decodedUsersData = json_decode($userData , JSON_OBJECT_AS_ARRAY) ?? [];

$_SESSION['error'] = [
    'name' => '',
    'email' => '',
    'user_name' => '',
    'password' => ''
];


//Name Validation
if (empty($name)){
    $_SESSION['error']['name'] = 'Please fill this field.';
    redirect('register');
}elseif (strlen($name) < 3 || strlen($name) > 32){
    $_SESSION['error']['name'] = 'Your name should contain at least 3 chars and at most 32 chars!';
    redirect('register');
}elseif (!preg_match('/[a-zA-Z\s]/' , $name)){
    $_SESSION['error']['name'] = 'Invalid pattern!';
    redirect('register');
}

//Email Validation
if (empty($email)){
    $_SESSION['error']['email'] = 'Please fill this field.';
    redirect('register');
}elseif (array_key_exists($email , $decodedUsersData)){
    $_SESSION['error']['email'] = 'This email already exists!';
    redirect('register');
}elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $_SESSION['error']['email'] = 'Invalid email!';
    redirect('register');
}

//Username Validation
if (empty($user_name)){
    $_SESSION['error']['user_name'] = 'Please fill this field.';
    redirect('register');
}elseif (in_array($user_name , array_column($decodedUsersData , 'user_name'))){
    $_SESSION['error']['user_name'] = 'This username already exists!';
    redirect('register');
}elseif (strlen($user_name) < 3 || strlen($user_name) > 32){
    $_SESSION['error']['user_name'] = 'Your username should contain at least 3 chars and at most 32 chars!';
    redirect('register');
}elseif (!preg_match('/[a-zA-Z0-9_]/' , $user_name)){
    $_SESSION['error']['user_name'] = 'Invalid pattern!';
    redirect('register');
}

//Password Validation
if (empty($password)){
    $_SESSION['error']['password'] = 'Please fill this field.';
    redirect('register');
}elseif (strlen($password) < 4 || strlen($password) > 32){
    $_SESSION['error']['password'] = 'Your password should contain at least 4 chars and at most 32 chars!';
    redirect('register');
}

if (empty(array_filter($_SESSION['error']))){
    $decodedUsersData[$email] = [
        "name" => $name,
        "email" => $email,
        'role' => 'user',
        "user_name" => $user_name,
        "password" => $password
    ];
    $_SESSION['user'] = [
        "name" => $name,
        "email" => $email,
        'role' => 'user',
        "user_name" => $user_name,
    ];
    file_put_contents( "../database/users.json" , json_encode($decodedUsersData));
    header("location: ../index.php");
}

//if (array_key_exists($email , $decodedUsersData)){
//    $_SESSION['error'] = 'This email already exists!!';
//    header("location: ../front/register.php");
//    exit();
//}else if (array_key_exists($user_name , array_column($decodedUsersData , 'user_name'))) {
//    $_SESSION['error'] = 'This user name already exists!!';
//    header("location: ../front/register.php");
//    exit();
//} else{
//    $decodedUsersData[$email] = [
//        "name" => $name,
//        "email" => $email,
//        "user_name" => $user_name,
//        "password" => $password
//    ];
//    $_SESSION['user'] = [
//        "name" => $name,
//        "email" => $email,
//        "user_name" => $user_name,
//        "password" => $password
//    ];
//    file_put_contents( "../database/users.json" , json_encode($decodedUsersData));
//    header("location: ../index.php");
//    exit();




