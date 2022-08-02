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
$entry = null;
if (isset($data['SubEntry']))
    $entry = $data['SubEntry'];
$result = RunAQuery(getLoginData(),Query_CreateSubEntry($todo,$text,$entry));