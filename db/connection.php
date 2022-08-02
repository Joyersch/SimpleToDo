<?php
function getLoginData(){
    $servername = "192.168.0.242";
    $username = "newtodo";
    $password = "newtodopw";
    $database_name = "ToDoMk2";

    $arr = array(
        "servername" => $servername,
        "username" => $username,
        "password" => $password,
        "database_name" => $database_name,
        );
    return $arr;
}

?>
