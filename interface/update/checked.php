<?php
include "../../db/database_functions.php";
include "../../db/connection.php";
include "../../db/queries.php";
include "../recieveData.php";

include "recieveData.php";

if ( isset($data['Entry']) && isset($data['State'])){
    
$state = $data['State'];
$entry = $data['Entry'];

if ($state == null) $state = '0'; 

RunAQuery(getLoginData(),Query_SetEntry($entry, $state));

}

?>