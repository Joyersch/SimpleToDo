<?php

class EditTable
{
    private $todo;
    protected  $connection;

    public function __construct($connection){
        $this -> connection = $connection;
        $this -> todo = new ToDo($connection);
    }

    public function GetByID($id){
        return $this -> todo -> GetByID($id);
    }

    public function Print(){

        $table = @"<div class='{$this -> todo -> ID}'>";
        $table .= "<table>";

        # size of the table
        $depth = $this->GetDepth();

        # generate header for table;
        $table .= "<tr>";

        $entries = $this -> todo -> GetUnderlyingEntries();

        if(count($entries) == 0)
            $depth++;

        # extend $depth by 1 to add checkboxes at the end
        for ($i = 0; $i < $depth + 2; $i++){
            $table .= "<th>";
            if ($i == 0)
                $table .= $this -> todo -> Text;
            if ($i == $depth + 1){

                # add delete button at the end
                $input = new Input();
                $input -> type = "submit";
                $input -> value = "d";
                $input -> onclick = "redirectToDeleteToDo(64,moveToPage(\"..\"));";

                $table .= $input -> Print();
            }

            $table .= "</th>";
        }
        $table .= "</tr>";




        foreach($entries as $entry){
            $table .= $this -> PrintRows($entry,$depth,1);
        }

        #print create row
        $table .= "<tr>";

        for ($i = 0; $i < $depth; $i++){
            $table .= "<td>";
            if ($i == 1)
            {
                # print entry data
                $textbox = new Textbox();
                $textbox -> placeholder = "new entry";
                $textbox -> id = "new_entry";
                $table .= $textbox -> Print();
            }
            $table .= "</td>";
        }
        $table .= "<td>";
        $input = new Input();
        $input -> type = "submit";
        $input -> value = "c";
        $input -> onclick = "redirectToCreateEntry(document.getElementById(\"new_entry\").value,{$this -> todo -> ID},reloadPage);";

        $table .= $input -> Print();
        $table .= "</td><td></td></tr>";

        $table .="</table>";
        $table .= "</div>";
        return $table;
    }

    public function PrintRows($entry, $total_depth, $current_depth){
        $row = "<tr>";
        for ($i = 0; $i < $total_depth; $i++){
            $row .= "<td>";
            if ($i == $current_depth)
            {
                # print entry data
                $textbox = new Textbox();
                $textbox -> Text = $entry -> Text;
                $textbox -> id = "entry_" . $entry -> ID;
                $row .= $textbox -> Print();
            }

            $row .= "</td>";
        }

        $row .= "<td>";
        # add edit button
        $input = new Input();
        $input -> type = "submit";
        $input -> value = "e";
        $input -> onclick = "redirectToUpdateEntry(document.getElementById(\"entry_{$entry -> ID}\").value,{$entry -> ID},reloadPage);";

        $row .= $input -> Print();

        $row .= "</td><td>";
        # add delete button
        $input = new Input();
        $input -> type = "submit";
        $input -> value = "d";
        $input -> onclick = "redirectToDeleteEntry({$entry -> ID},reloadPage);";

        $row .= $input -> Print();
        $row .= "</td>";

        $row .= "</tr>";

        #print create row
        $row .= "<tr>";

        for ($i = 0; $i < $total_depth; $i++){
            $row .= "<td>";
            if ($i == $current_depth)
            {
                # print entry data
                $textbox = new Textbox();
                $textbox -> placeholder = "new under entry:" . $entry -> ID;
                $textbox -> id = "new_entry_" . $entry -> ID;
                $row .= $textbox -> Print();
            }
            $row .= "</td>";
        }
        $row .= "<td>";
        $input = new Input();
        $input -> type = "submit";
        $input -> value = "c";
        $input -> onclick = "redirectToCreateSubEntry(document.getElementById(\"new_entry_{$entry -> ID}\").value,{$this -> todo -> ID},{$entry -> ID},reloadPage);";

        $row .= $input -> Print();
        $row .= "</td><td></td></tr>";


        $entries = $entry -> GetUnderlyingEntries();
        if (count($entries) == 0)
            return $row;

        foreach($entries as $lower_entry){
            $row .= $this -> PrintRows($lower_entry,$total_depth, $current_depth + 1);
        }
        return $row;
    }

    public function GetDepth(){
        return $this -> innerGetDepth($this -> todo,1);
    }

    public function GetEntryDepth(){
        return $this -> innerGetDepth($this -> todo,0);
    }

    protected function innerGetDepth($object, $depth){
        $currentDepth = $depth;

        $objects = $object -> GetUnderlyingEntries();
        if (count($objects) == 0) return $depth;
        foreach($objects as $lower_object){
            $lower_depth = $this -> innerGetDepth($lower_object,$depth + 1);
            if ($lower_depth > $currentDepth) $currentDepth = $lower_depth;
        }
        return $currentDepth;
    }
}