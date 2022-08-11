<?php

class Form
{
    public $container;
    public $action;
    public $method;
    public $id;

    public function Print(){
        if (!isset($this -> container) ||
            count($this -> container) == 0 ||
            !isset($this -> action) ||
            !isset($this -> method))
            return "";

        $output = "<form action='{$this -> action}' method='{$this -> method}'";

        if (isset($this -> id))
            $output .= "id='{$this -> id}'";

        $output .= ">";

        foreach($this -> container as $item){
            $output .= $item -> Print();
        }

        $output .= "</form>";
        return $output;
    }
}