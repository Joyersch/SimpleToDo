<?php

function Connect($servername,$username,$password,$dbname){
    if (!isset($servername) || !isset($username)|| !isset($password) || !isset($dbname)){
        return null;
    }
    else {
        $conn = new mysqli($servername, $username, $password, $dbname);
        if (!($conn->connect_error)) {

            $IsConnected = true;
            return $conn;
        } else {
            return null;
        }
    }
}

function Close($conn){
    $conn -> close();
}

function RunQuery(mysqli $conn, $query){
    if ($result = $conn -> query($query)) {
        return $result;
    }
    else{
        return null;
    }
}

function RunAQuery($connectionData, $query) {
    if (!isset($connectionData["servername"]) ||
        !isset($connectionData["username"]) ||
        !isset($connectionData["password"]) ||
        !isset($connectionData["database_name"]))
        return false;
    $conn = Connect($connectionData["servername"],$connectionData["username"],$connectionData["password"],$connectionData["database_name"]);
    $result = RunQuery($conn,$query);
    Close($conn);
    return $result;
}
