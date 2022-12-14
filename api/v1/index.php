<?php

# check if call contains object or authkey which is required

if (!isset($_GET["object"]) || !isset($_GET["authkey"])){
    http_response_code(403);
    exit();
}

# Pull request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method)
{
    case "GET":
    case "POST":
    case "PUT":
    case "DELETE":
        # These request methods are allowed
        break;
    default:
        http_response_code(403);
        exit();
}


# Once we reach this part, we can start processing data further

require_once "../../classes/data/DatabaseObject.php";
require_once "../../classes/data/Entry.php";
require_once "../../classes/data/ToDo.php";
require_once "../../classes/data/Category.php";
require_once "../../classes/data/Authentication.php";

require_once "../../db/database_functions.php";
require_once "../../db/connection.php";

# Open a database connection
$conn = ConnectEx(getLoginData());

# check authentication key

$authentication = new Authentication($conn);

# check if an authencication entry exists
if (!$authentication -> GetByKey($_GET["authkey"])){
    http_response_code(403);
    exit();
}

# check if authentication if still valid
if (!$authentication -> Verify()){
    $authentication -> Delete();
    http_response_code(403);
    exit();
}

# get object info
$objectdata = $_GET["object"];

$object = null;
$id = -1;

#set id if given
if (isset($_GET["id"])){
    $id = $_GET["id"];
}
# creating data object base on the given request
switch($objectdata){
    case "todos":
        $object = new Todo($conn);
        break;
    case "entries":
        $object = new Entry($conn);
         break;
    case "categories":
        $object = new Category($conn);
        break;
    default:
        http_response_code(403);
        exit();
}

switch ($method){
    case "GET":
        if ($id === -1){
            $objects = $object -> GetAll();

            $jsonArray = [];
            $count = 0;
            foreach($objects as $obj){
                # As PrintJSON only returns the code for one element, we cannot simply just echo it
                # We need to save it in an array and encode it
                # But if we were to just simple store the json in the array we would incode it as "0" : "PRINT_JSON_DATA".
                # To prevent this from happening we decode the json string to an array and save that to another array
                $jsonArray[$count] = json_decode($obj -> PrintJSON(), true);
                $count++;
            }
            echo json_encode($jsonArray);
        }
        else{
            if ($object -> GetByID($id))
                echo $object -> PrintJSON();
            else{
                http_response_code(204); # set response to bad content
                exit();
            }
        }
        break;
    case "POST":
        $jsonIN = file_get_contents('php://input');

        $object -> GetByJSON($jsonIN);

        if ($object -> Add())
            http_response_code(202); # set response to accepted
        else
            http_response_code(400); # set response to bad request
        break;
    case "PUT":
        if ($id === -1){
            http_response_code(204); # set response to bad content
            exit();
        }
        $jsonIN = file_get_contents('php://input');
        if (!$object -> GetByJSON($jsonIN)){
            http_response_code(400); # set response to bad request
            exit();
        }

        if ($id != $object -> ID){
            http_response_code(400); # set response to bad request
            exit();
        }

        if ($object -> Update())
            http_response_code(202); # set response to accepted
        else{
            http_response_code(400); # set response to bad request
            exit();
        }
        break;
    case "DELETE":
        if ($id === -1){
            http_response_code(204); # set response to bad content
            exit();
        }
        $object -> GetByID($id);
        if ($object -> Delete())
            http_response_code(202); # set response to accepted
        else
            http_response_code(400); # set response to bad request
        break;
}