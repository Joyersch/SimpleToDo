<?php
 #ToDo: Are these even used anymore?


function Query_Categories(){
    return "SELECT Category.ID, Category.Name FROM Category";
}

function Query_Todos(){
return "SELECT ToDo.ID, ToDo.Text FROM ToDo";
}

function Query_Todo($todo){
    return "SELECT ToDo.ID, ToDo.Text FROM ToDo WHERE ID = $todo";
}

function Query_TodosInCategory($Category){
    return "SELECT ToDo.ID, ToDo.Text FROM ToDo WHERE ToDo.Category = $Category";
}

function Query_GetTopEntries($ToDo){
    return "SELECT Entry.ID, Entry.Text, Entry.Done FROM Entry WHERE Entry.LinkedToDo = $ToDo AND Entry.LinkedEntry is null";
}

function Query_GetSubEnties($EntryID){
    return "SELECT Entry.ID, Entry.Text, Entry.Done FROM Entry WHERE Entry.LinkedEntry = $EntryID";
}

function Query_GetEntries($ToDo){
    return "SELECT Entry.ID, Entry.Text, Entry.Done FROM Entry WHERE Entry.LinkedToDo = $ToDo";
}

function Query_SetEntry($Entry, $Done){
return "UPDATE Entry SET Done = $Done WHERE ID = $Entry";
}

function Query_GetEntry($Entry){
    return "SELECT Entry.ID, Entry.Text, Entry.Done, Entry.LinkedToDo, Entry.LinkedEntry FROM Entry WHERE Entry.ID = $Entry";
}

function Query_CreateToDo($Text, $Category){
return "INSERT INTO ToDo (Text, Category) VALUES ('$Text', '$Category')";
}

function Query_CreateEntry($ToDo, $Text){
    return "INSERT INTO Entry (LinkedToDo,Text,Done) VALUES ($ToDo,'$Text',0)";
}
function Query_CreateSubEntry($ToDo, $Text, $Entry){
    return "INSERT INTO Entry (LinkedToDo,Text,Done,LinkedEntry) VALUES ($ToDo,'$Text',0,$Entry)";
}

function Query_UpdateEntry($Entry,$Text){
    return "UPDATE Entry SET Text = '$Text' WHERE ID = $Entry";
}

function Query_DeleteToDo($Todo){
    return "DELETE FROM ToDo WHERE ID = $Todo";
}

function Query_DeleteEntry($Entry){
    return "DELETE FROM Entry WHERE ID = $Entry";
}
function Query_GetNewestToDo(){
    return "SELECT MAX(ID) FROM ToDo";
}
?>