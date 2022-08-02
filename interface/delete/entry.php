<?php
include "../../db/database_functions.php";
include "../../db/connection.php";
include "../../db/queries.php";
include "../recieveData.php";

if (!isset($data['Entry']))
    return false;
$entry = $data['Entry'];
$result = RunAQuery(getLoginData(),Query_DeleteEntry($entry));