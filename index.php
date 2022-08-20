<?php
require_once "classes/data/DatabaseObject.php";
require_once "classes/data/Entry.php";
require_once "classes/data/ToDo.php";
require_once "classes/data/Category.php";
require_once "classes/data/Authentication.php";

require_once "classes/view/Form.php";
require_once "classes/view/Combobox.php";
require_once "classes/view/Label.php";
require_once "classes/view/Input.php";
require_once "classes/view/Textbox.php";
require_once "classes/view/Checkbox.php";
require_once "classes/view/DisplayTable.php";

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

# set up authentication with the database
$authentication = new Authentication($conn);

# this will set or reset the authentication key required for the api
if (!$authentication -> GetByName('ToDo') || !$authentication -> Verify()){

    $result = $conn -> query("SELECT NOW(), NOW() + INTERVAL 1 DAY");

    $row = $result -> fetch_row();

    $authentication -> StartTime = $row[0];

    $authentication -> EndTime = $row[1];

    $authentication -> Name = 'ToDo';

    $authentication -> Add();
}

# set category value
$category_value = - 1;

# get category from get
if (isset($_GET['category']) )
    $category_value = $_GET['category'];

# get category object
$category = new Category($conn);
$category -> GetByID($category_value);
# create combobox with all categories
$combo = new Combobox();
$combo -> name = "category";
# set index to 0 so if no index candidate is found, it will default to first in selection
$combo -> index = 0;
$combo -> onchange = "this.form.submit()";
# get call categories to put into the combobox
$categories = $category -> GetAll();

foreach ($categories as $cat){
    # if category_value is not set, this will set it to the first category
    if ($category_value === -1)
        $category_value = $cat -> ID;

    if ($cat -> ID === $category_value && isset($combo -> container))
        $combo -> index = count($combo -> container);

    # add category data to the combobox container
    # the combobox expects container to be an array of undefined length containing arrays with the length of 2
    $combo -> container[] = array($cat -> ID,$cat -> Name);

}

# create label
$label = new Label();
$label -> Text = "Category:";

# create form
$form = new Form();
$form -> method = "get";
$form -> action = "";

# add items to form
$form -> container[] = $label;
$form -> container[] = $combo;

#print form
echo $form -> Print();

# create new form
$form = new Form();
$form -> action = "./new/";
$form -> method = "get";

# add input
$input = new Input();
$input -> type = "submit";
$input -> value = "new";

$label = new Label();
$label -> Text = "Create new ToDo: ";

$form -> container[] = $label;
$form -> container[] = $input;

echo $form -> Print();

$todo = new ToDo($conn);
$todos = $todo -> GetAll();

foreach ($todos as $inner_todo){

    if ($inner_todo -> Category -> ID !== $category_value)
        continue;

    $table = new DisplayTable($conn);
    $table -> GetByID($inner_todo -> ID);

    echo $table -> Print($authentication -> PassKey);
}