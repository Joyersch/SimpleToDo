<?php

class DisplayTable
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

    public function Print($authentication){

        $table = @"<div class='{$this -> todo -> ID}'>";
        $table .= "<table>";

        # size of the table
        $depth = $this->GetDepth();

        # generate header for table;
        $table .= "<tr>";

        # extend $depth by 1 to add checkboxes at the end
        for ($i = 0; $i < $depth + 1; $i++){
            $table .= "<th>";
            if ($i == 0)
                $table .= $this -> todo -> Text;
            if ($i == $depth){

                # create form
                $form = new Form();
                $form -> action = "./edit/";
                $form -> method = "get";

                # create input hidden todo
                $input = new Input();
                $input -> type = "hidden";
                $input -> id = "todo";
                $input -> name = "todo";
                $input -> value = $this -> todo -> ID;

                # add input to form
                $form -> container[] = $input;

                # create input edit
                $input = new Input();
                $input -> type = "submit";
                $input -> value = "edit";

                # add input to form
                $form -> container[] = $input;

                $table .= $form -> Print();
            }

            $table .= "</th>";
        }
        $table .= "</tr>";


        $entries = $this -> todo -> GetUnderlyingEntries();

        foreach($entries as $entry){
            $table .= $this -> PrintRows($entry,$depth,1,$authentication);
        }


        $table .="</table>";
        $table .= "</div>";
        return $table;
    }

    public function PrintRows($entry, $total_depth, $current_depth,$authentication){
        $row = "<tr>";
        for ($i = 0; $i < $total_depth; $i++){
            $row .= "<td>";
            if ($i == $current_depth)
            {
                # print entry data
                    $row .= $entry -> Text;
            }

            $row .= "</td>";
        }

        # add checkbox the end of entry
        $checkbox = new Checkbox();
        $row .= "<td>";
        $checkbox -> id = $entry -> ID;
        $checkbox -> Text = $entry -> Text;
        $checkbox -> checked = $entry -> Done;
        $row .= $checkbox -> Print($authentication);
        $row .= "</td>";

        $row .= "</tr>";
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