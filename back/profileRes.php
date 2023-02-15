<?php
session_start();
require_once "../database/connection.php";

//$usersData = json_decode(file_get_contents('../database/users.json') , true);
$stmtImg = $conn->prepare("SELECT * FROM images WHERE id = :id AND type = 'profile' ");
$stmtImg->bindParam(':id', $_SESSION['user']->id);
$stmtImg->execute();
$stmtImgRes = $stmtImg->fetchAll(5);

header('Content-Type: Application/json');
print_r(json_encode(['view' => renderView(['loggedInUserImage' => $stmtImgRes , 'loggedInUser' => $_SESSION['user'] ] , '../front/profileContent.php')]));

function renderView($data , $path){
    extract($data);
    ob_start();
    include $path;
    $result = ob_get_contents();

    ob_clean();

    return $result;
}
