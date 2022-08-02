<?php
include "db/database_functions.php";
include "db/connection.php";
include "db/queries.php";
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
if (!isset($_GET['todo']))
    return;

echo "<input type='submit' value='return' onclick='moveToPage(\"index.php\");'>";

$todo = $_GET['todo'];
$txt = "ToEdit";
if ($todo === 'newest'){
    $result = RunAQuery(getLoginData(),Query_GetNewestToDo());
    $show = $result -> fetch_row();
    $todo = $show[0];
}
$result = RunAQuery(getLoginData(),Query_Todo($todo));
$show = $result -> fetch_row();
$txt = $show[1];

DrawToDo($todo,$txt)
?>



<?php
function DrawToDo($id, $text){
    echo "<div class='todo-$id'>";
    // + 1 for delete button
    $table_depth = GetToDoDepth($id) + 1;
    echo "
    <table><tr>
    ";

    for ($i = 0; $i < $table_depth + 2;$i++){
        if ($i == 0) {
            echo "<th>$text</th>";
        }
        else if ($i == $table_depth + 1){
            echo "<th>";
            DrawSubmitDeleteToDoButton($id);
            echo "</th>";
        }
        else
            echo "<th></th>";
    }

    echo "</tr>";
    $result = RunAQuery(getLoginData(),Query_GetTopEntries($id));
    if ($result)
    while($show = $result -> fetch_row()){
        echo "<tr>";
        echo "<td></td>";
        echo "<td>";
        DrawInputButton($show[0],$show[1]);
        echo "</td>";
        for ($i = 0; $i < $table_depth - 2;$i++){
            echo "<td></td>";
        }
        echo "<td>";
        DrawSubmitEditButton($show[0]);
        echo"</td>";

        echo "<td>";
        DrawSubmitDeleteEntryButton($show[0]);
        echo "</td>";

        echo "</tr>";

        echo "<tr>";
        echo "<td></td>";
        echo "<td>";
        DrawInputNewButton($show[0]);
        echo "</td>";
        for ($i = 0; $i < $table_depth - 2;$i++){
            echo "<td></td>";
        }
        echo "<td>";
        DrawSubmitNewSubButton($id,$show[0]);
        echo"</td>";

        echo "<td>";
        echo "</td>";

        echo "</tr>";
        DrawSubEntry($id, $show[0],$table_depth);
    }
    echo "<tr>";
    echo "<td></td>";
    echo "<td>";
    DrawInputNewButton(null);
    echo "</td>";
    for ($i = 0; $i < $table_depth - 2;$i++){
        echo "<td></td>";
    }
    echo "<td>";
    DrawSubmitNewButton($id);
    echo"</td>";

    echo "<td>";
    echo "</td>";

    echo "</tr>";
    echo "
    </table>
    </div>";
}

function DrawSubEntry($todo, $top_entry,$table_depth){
    $result = RunAQuery(getLoginData(),Query_GetSubEnties($top_entry));

    if (!$result)
        return false;

    while($show = $result -> fetch_row()){
        echo "<tr>";
        $entry_depth = GetEntryDepth($show[0]);
        for ($i = 0; $i < $table_depth;$i++){
            if ($i == $entry_depth){
                echo "<td>";
                DrawInputButton($show[0],$show[1]);
                echo"</td>";
            }
            else
                echo "<td></td>";
        }

        echo "<td>";
        DrawSubmitEditButton($show[0]);
        echo"</td>";

        echo "<td>";
        DrawSubmitDeleteEntryButton($show[0]);
        echo "</td>";
        echo "</tr>";

        echo "<tr>";

        for ($i = 0; $i < $table_depth;$i++){
            if ($i == $entry_depth){
                echo "<td>";
                DrawInputNewButton($show[0]);
                echo"</td>";
            }
            else
                echo "<td></td>";
        }
        echo "<td>";
        DrawSubmitNewSubButton($todo,$show[0]);
        echo"</td>";
        echo "</tr>";

        DrawSubEntry($todo,$show[0],$table_depth);

    }

}

function DrawInputButton($entry, $text){
    echo "<input type='text' value='$text' id='entry_$entry'>";
}

function DrawInputNewButton($entry){
    echo "<input type='text' placeholder='new entry $entry' id='new_entry_$entry'>";
}

function DrawSubmitEditButton($entry){
    echo "
    <input type='submit' value='e' onclick='
     redirectToUpdateEntry(document.getElementById(\"entry_$entry\").value,$entry,reloadPage);
    '>";
}
function DrawSubmitNewSubButton($todo, $entry){

    echo "
    <input type='submit' value='c' onclick='redirectToCreateSubEntry(
        document.getElementById(\"new_entry_$entry\").value,$todo,$entry,reloadPage);'>";
}

function DrawSubmitNewButton($todo){

    echo "
    <input type='submit' value='c' onclick='redirectToCreateEntry(document.getElementById(\"new_entry_\").value,$todo,reloadPage);'>";
}

function DrawSubmitDeleteToDoButton($todo){
    echo "
    <input type='submit' value='d' onclick='redirectToDeleteToDo($todo,moveToPage(\"index.php\"));'>";
}

function DrawSubmitDeleteEntryButton($entry){
    echo "
    <input type='submit' value='d' onclick='redirectToDeleteEntry($entry,reloadPage);'>";
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