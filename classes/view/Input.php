<?php

class Input
{
    public $type;
    public $value;
    public $id;
    public $name;
    public $onclick;

    public function Print(){
        $output = "<input ";

        if (isset($this -> type))
            $output .= "type='{$this -> type}' ";

        if (isset($this -> value))
            $output .= "value='{$this -> value}' ";

        if (isset($this -> id))
            $output .= "id='{$this -> id}' ";

        if (isset($this -> name))
            $output .= "name='{$this -> name}' ";


        if (isset($this -> onclick))
            $output .= "onclick='{$this -> onclick}' ";

        $output .= ">";
        return $output;
    }
}