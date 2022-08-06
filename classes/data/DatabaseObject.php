<?php

class DatabaseObject
{
    protected  $connection;

    public function __construct($connection){
        $this -> connection = $connection;
    }
}