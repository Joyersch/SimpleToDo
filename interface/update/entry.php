<?php
include "../../db/database_functions.php";
include "../../db/connection.php";
include "../../db/queries.php";
include "../recieveData.php";

if (!isset($data['Text']))
    return false;
if (!isset($data['Id']))
    return false;
$txt = $data['Text'];
$id = $data['Id'];
$result = RunAQuery(getLoginData(),Query_UpdateEntry($id,$txt));