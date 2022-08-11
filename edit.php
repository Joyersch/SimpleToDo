<?php
require_once "classes/data/DatabaseObject.php";
require_once "classes/data/Entry.php";
require_once "classes/data/ToDo.php";
require_once "classes/data/Category.php";

require_once "classes/view/Form.php";
require_once "classes/view/Combobox.php";
require_once "classes/view/Label.php";
require_once "classes/view/Input.php";
require_once "classes/view/Textbox.php";
require_once "classes/view/Checkbox.php";
require_once "classes/view/EditTable.php";

require_once "db/database_functions.php";
require_once "db/connection.php";

?>
    <script>
        <?php
        include "js/dbaccess.js";
        ?>
    </script>
    <style>
        <?php
        include "css/todo.css";
        ?>
    </style>
<?php
# open a database connection
$conn = ConnectEx(getLoginData());

# print return button
$input = new Input();
$input -> type = "submit";
$input -> value = "return";
$input -> onclick = "moveToPage(\"..\");";
echo $input -> Print();


# set todo object
$todo = new Todo($conn);

$todo_value = 0;
if (isset($_GET['todo'])){
    $todo_value = $_GET['todo'];
    if ($todo_value === 'newest'){
        $todos = $todo -> GetAll();
        $todo_value = $todos[count($todos) - 1] -> ID;
    }
}

$table = new EditTable($conn);
$table -> GetByID($todo_value);
echo $table -> Print();