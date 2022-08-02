<?php
include "../../db/database_functions.php";
include "../../db/connection.php";
include "../../db/queries.php";
include "../recieveData.php";

if (!isset($data['ToDo']))
    return false;
$todo = $data['ToDo'];
$result = RunAQuery(getLoginData(),Query_DeleteToDo($todo));