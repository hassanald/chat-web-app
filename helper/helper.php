<?php

const FRONT = "../front/";
function redirect(string $location){
    $location = FRONT . $location . ".php";
    if (file_exists($location)){
        header("location: $location");
        return true;
    }
    return false;
}