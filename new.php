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

# set up authentication with the database
$authentication = new Authentication($conn);

# this will set or reset the authentication key required for the api
if (!$authentication->GetByName('ToDo') || !$authentication->Verify()) {

    $result = $conn->query("SELECT NOW(), NOW() + INTERVAL 1 DAY");

    $row = $result->fetch_row();

    $authentication->StartTime = $row[0];

    $authentication->EndTime = $row[1];

    $authentication->Name = 'ToDo';

    $authentication->Add();
}

echo "<H1>Create new ToDo</H1>";
$form = new Form();
$form->action = "../edit/";
$form->method = "get";
$form->id = "hidden_value";

$input = new Input();
$input->type = "hidden";
$input->id = "todo";
$input->name = "todo";
$input->value = "newest";

$form->container[] = $input;

echo $form->Print();

$form = new Form();
$Label = new Label();
$Label->Text = "Category: ";
echo $Label->Print();

# print combo with category selection
$category = new Category($conn);
$categories = $category->GetAll();
$combo = new Combobox();
$combo->index = 0;
$combo->name = "category";
$combo->id = "category";

foreach ($categories as $cat) {
    $combo->container[] = array($cat->ID, $cat->Name);
}

echo $combo->Print();
$Label = new Label();
$Label->Text = "Name: ";
echo $Label->Print();

$textbox = new Textbox();
$textbox->placeholder = "Name";
$textbox->id = "txt";

echo $textbox->Print();

$button = new Input();
$button->type = "button";
$button->value = "create";
$button->onclick = @"
    var success = redirectToCreateToDo(
        document.getElementById(\"txt\").value,
        document.getElementById(\"category\").value,
        \"{$authentication->PassKey}\",
        function(){
            document.getElementById(\"hidden_value\").submit();
        });
    if (success){
        return true;
    }
    else
        return false;
";

echo $button->Print();