<?php

class Textbox
{
    public $Text;
    public $id;
    public $placeholder;

    public function Print()
    {

        if (!isset($this->id))
            return "";
        $output = @"<input type='text'";


        if (isset($this -> Text))
        $output .= "value='{$this -> Text}'";

        $output .= "id='{$this -> id}' placeholder='{$this -> placeholder}'>";

        return   $output;
    }
}