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


<H1>Create new ToDo</H1>
<form action='../edit/' id='hidden_value' method='get'>
    <input type="hidden" id='todo' name='todo' value="newest">
</form>
<form action=''>
    Category:
    <?php
    $result = RunAQuery(getLoginData(),Query_Categories());
    if (!$result)
    return;

    echo "<select name='category' id='category'>";
    while($show = $result -> fetch_row()){
        echo "<option value='$show[0]'>$show[1]";
    }
    echo "</select>";
    ?>
    Name:
    <input type="text" placeholder="Name " id="txt">
    <input type="button" onclick="
    var success = redirectToCreateToDo(
        document.getElementById('txt').value,
        document.getElementById('category').value,
        function(){
            document.getElementById('hidden_value').submit();
        });
    if (success){
        return true;
    }
    else
        return false;
" value="create"/>
</form>