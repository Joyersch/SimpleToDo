<?php
include "../../db/database_functions.php";
include "../../db/connection.php";
include "../../db/queries.php";
include "../recieveData.php";

if (isset($data['Text'])){

    $text = $data['Text'];
    
    if ($text != ''){
        if (isset($data['Category'])){
            $category = $data['Category'];
            $query = Query_CreateToDo($text,$category);
        }

        if (isset($data['ToDo'])){

            $goal = $data['ToDo'];
            
            $query = Query_CreateEntry($goal,$text);

            if (isset($data['Entry'])){
                $sub = $data['Entry'];
                $query = Query_CreateSubEntry($goal,$text,$sub);
            }
        }
        $result = RunAQuery(getLoginData(),$query);
        if (!$result)
            return -1;
        $result = RunAQuery(getLoginData(),Query_GetNewestToDo());
        $show = $result -> fetch_row();
        echo $show[0];
    }
}

?>
