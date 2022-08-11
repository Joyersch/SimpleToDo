<?php

class Label
{
    public $Text;

    public function Print(){
        return "<label>{$this -> Text}</label>";
    }
}