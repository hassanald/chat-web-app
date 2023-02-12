<?php

$username = "root";
$servername = "localhost";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=chat" , $username ,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "Connected";
}catch (PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}