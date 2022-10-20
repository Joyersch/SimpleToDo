<?php

class Combobox
{
    public $container;
    public $name;
    public $index;
    public $onchange;
    public $id;

    public function Print()
    {
        if (!isset($this->container))
            return "";

        $container_count = count($this->container);

        if (!isset($this->name) ||
            $container_count == 0 ||
            !isset($this->index) ||
            $container_count <= $this->index)
            return "";

        $output = "<select name='{$this->name}'";

        if (isset($this->id))
            $output .= " id='{$this->id}'";

        if (isset($this->onchange)) {
            $output .= "onchange='{$this->onchange}'";
        }

        $output .= ">";


        for ($i = 0; $i < $container_count; $i++) {
            $output .= "<option value='{$this->container[$i][0]}'";
            if ($i == $this->index)
                $output .= "selected";
            $output .= "> {$this->container[$i][1]}</option>";
        }

        $output .= "</select>";
        return $output;
    }
}