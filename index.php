<?php
include "db/database_functions.php";
include "db/connection.php";
include "db/queries.php";

$category = 1;

if (isset($_GET['category']) )
    $category = $_GET['category'];
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

    <div class="configuration">

        <?php

        $result = RunAQuery(getLoginData(),Query_Categories());
        if (!$result)
            return;

        echo "<form action='' method='get'>
        <label>Category:</label>
        <select name='category' onchange='this.form.submit()'>
        ";
        while($show = $result -> fetch_row()){
           echo "<option value='$show[0]'";
           if ($category == $show[0])
               echo "selected";
           echo ">$show[1]</option>";
        }
        echo "<input style='visibility: hidden' type='submit' value='submit'>";
        echo "</form>";

        DrawToDo(-1,"Create new ToDo:");
        ?>
    </div>

<?php

$result = RunAQuery(getLoginData(),Query_TodosInCategory($category));
if (!$result)
    return;

while($show = $result -> fetch_row()){

    DrawToDo($show[0],$show[1]);
}
?>

<?php
function DrawToDo($id, $text){
    echo "<div class='todo-$id'>";
    $table_depth = GetToDoDepth($id);
    echo "
    <table><tr>
    ";

    for ($i = 0; $i < $table_depth + 2;$i++){
        if ($i == 0) {
            echo "<th>$text</th>";
        }
        else if ($i == $table_depth + 1){
            echo "<th>";
            if ($id == -1)
                DrawNewButton($id);
                else
                DrawEditButton($id);
            echo "</th>";
        }
        else
            echo "<th></th>";
    }

    echo "</tr>";

    $result = RunAQuery(getLoginData(),Query_GetTopEntries($id));

    if (!$result)
        return false;

    while($show = $result -> fetch_row()){
        echo "<tr>";
        echo "<td></td>";
        echo "<td>$show[1]</td>";
        for ($i = 0; $i < $table_depth - 1;$i++){
            echo "<td></td>";
        }
        echo "<td>";
        DrawCheckButton($show[0]);
        echo"</td>";
        echo "</tr>";

        DrawSubEntry($show[0],$table_depth);
    }
    echo "
    </table>
    </div>";
}

function DrawSubEntry($top_entry,$table_depth){
    $result = RunAQuery(getLoginData(),Query_GetSubEnties($top_entry));

    if (!$result)
        return false;

    while($show = $result -> fetch_row()){
        echo "<tr>";
        $entry_depth = GetEntryDepth($show[0]);
        for ($i = 0; $i < $table_depth + 1;$i++){
            if ($i == $entry_depth)
                echo "<td>$show[1]</td>";
            else
                echo "<td></td>";
        }
        echo "<td>";
        DrawCheckButton($show[0]);
        echo"</td>";
        echo "</tr>";
        DrawSubEntry($show[0],$table_depth);
    }
}

function DrawCheckButton($entry){
    echo "
    <input type='checkbox' id='entry_$entry' name='$entry' onclick=' return redirectToChecked($entry,document.getElementById(\"entry_$entry\").checked)'";
    $result = RunAQuery(getLoginData(),Query_GetEntry($entry));
    $show = $result -> fetch_row();
    if ($show[2])
        echo "checked";
    echo ">";
}

function DrawEditButton($todo){
    echo "
    <form action='edit.php' method='get'>
    <input type='hidden' id='todo' name='todo' value='$todo'>
    <input type='submit' value='edit'>
    </form>";
}

function DrawNewButton(){
    echo "
    <form action='new.php'>
    <input type='submit' value='new'>
    </form>";
}

function GetToDoDepth($id){
    $total_depth = 0;
    $result = RunAQuery(getLoginData(),Query_GetTopEntries($id));

    if (!$result)
        return false;

    $total_depth = 1;

    while($show = $result -> fetch_row()){
        $current_depth = GetEntryDepthForToDo($show[0], 1);
        if ($current_depth > $total_depth) $total_depth = $current_depth;
    }
    return $total_depth;
}

function GetEntryDepthForToDo($entry, $current_depth){
    $result = RunAQuery(getLoginData(),Query_GetSubEnties($entry, $entry));

    if (!$result)
        return false;
    $lowest_depth = $current_depth;
    while($show = $result -> fetch_row()){
         $inner_result = GetEntryDepthForToDo($show[0], $current_depth + 1);
         if (!$inner_result)
             return $current_depth;
         if ($lowest_depth < $inner_result) $lowest_depth = $inner_result;

    }
    return $lowest_depth;
}

function GetEntryDepth($entry){
    $result = RunAQuery(getLoginData(),Query_GetEntry($entry));

    if (!$result)
        return false;

    $show = $result -> fetch_row();

    $upper_entry = $show[4];

    if ($upper_entry === null){

        return 1;
    }

    return GetEntryDepth($upper_entry) + 1;
}
?>