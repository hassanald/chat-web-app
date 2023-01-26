<?php

session_start();

$userData = json_decode(file_get_contents('../database/users.json') , true);

$imageKey = $_POST['image_key'];

unlink('../' . $userData[$_SESSION['user']['email']]['images'][$imageKey]['full_path']);
unset($userData[$_SESSION['user']['email']]['images'][$imageKey]);

file_put_contents( '../database/users.json' ,json_encode($userData , JSON_PRETTY_PRINT) );