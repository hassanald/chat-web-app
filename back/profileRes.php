<?php
session_start();

$usersData = json_decode(file_get_contents('../database/users.json') , true);

header('Content-Type: Application/json');
print_r(json_encode(['view' => renderView(['loggedInUser' => $usersData[$_SESSION['user']['email']] ] , '../front/profileContent.php')]));

function renderView($data , $path){
    extract($data);
    ob_start();
    include $path;
    $result = ob_get_contents();

    ob_clean();

    return $result;
}
