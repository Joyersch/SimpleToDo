<?php
include "../../db/database_functions.php";
include "../../db/connection.php";
include "../../db/queries.php";
include "../recieveData.php";

if (!isset($data['ToDo']))
    return false;
if (!isset($data['Text']))
    return false;
$todo = $data['ToDo'];
$text = $data['Text'];
$result = RunAQuery(getLoginData(),Query_CreateEntry($todo,$text));
if ($result){
    $result = RunAQuery(getLoginData(),"SELECT MAX(ID) FROM Entry");
    $show = $result -> fetch_row();
    echo $show[0];
}