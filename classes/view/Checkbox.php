<?php

class Checkbox
{
    public $Text;
    public $id;
    public $checked;
    public function Print($authentication){

        if (!isset($this -> id) ||
        !isset($this -> Text) ||
        !isset($this -> checked))
            return "";

        $checkbox = @"<input type='checkbox' id='entry_{$this -> id}' name='{$this -> id}' onclick=' return redirectToChecked({$this -> id},document.getElementById(\"entry_{$this -> id}\").checked,\"{$authentication}\")'";
        if ($this -> checked)
            $checkbox .= "checked";
        $checkbox .= ">";
        return $checkbox;
    }
}