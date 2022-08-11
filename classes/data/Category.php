<?php

class Category extends DatabaseObject
{
    public $ID;
    public $Name;

    public function GetByID($ID) {

        # Query Data based on the given ID;
        $result = $this-> connection -> query("SELECT Name FROM Category WHERE ID = $ID LIMIT 0,1");

        #Check if a result was given
        if (!$result)
            return false;

        # Get result from query
        $row = $result -> fetch_array();
        # Check if the result was empty
        if (!$row)
            return false;

        # Write data from query to this object
        $this -> ID = $ID;
        $this -> Name = $row["Name"];
        return true;
    }

    public function Add() {

        # Checks if required data is set
        if (!isset($this -> Name))
            return false;

        # Runs insert query to add data to the database
        $result = $this -> connection -> query(@"INSERT INTO Category (Name) VALUES ('{$this -> Name}')");

        if (!$result)
            return false;
        $this -> ID = $this -> connection -> query("SELECT LAST_INSERT_ID()") -> fetch_row()[0];
        return true;
    }

    public function Update(){

        # Check if ID is set
        if (!isset($this -> ID) ||
            !isset($this -> Name))
            return false;

        # Runs update
        return $this -> connection -> query(@"UPDATE Category SET Name = '{$this -> Name}' WHERE ID = {$this -> ID}");
    }

    public function Delete(){

        # Check if an ID was set
        if (!isset($this -> ID))
            return false;

        # Runs delete query and returns result
        return $this -> connection -> query(@"DELETE FROM Category where ID = {$this -> ID}");
    }

    public function GetByJSON($json){
        $array = json_decode($json, true);

        # if json did not parse return false
        if ($array === null)
            return false;

        try{
            if (isset($array["ID"]))
                $this -> ID = $array["ID"];

            if (isset($array["Name"]))
                $this -> Name = $array["Name"];
        }
        catch(Exeption $ex){
            return false;
        }
       return true;
    }

    public function PrintJSON(){
        $array = [];

        if(isset($this -> ID))
            $array["ID"] = $this -> ID;
        if (isset($this -> Name))
            $array["Name"] = $this -> Name;

        return json_encode($array);
    }

    public function GetAll(){

        $result = $this -> connection -> query(@"SELECT ID FROM Category");

        if (!$result)
            return [];

        $array = [];
        $count = 0;

        while($row = $result -> fetch_array()){
            # We need to create a new object each time as otherwise we override the old references by using GetByID! ($array will contain the same object)
            $category = new Category($this -> connection);
            $category -> GetByID($row["ID"]);
            $array[$count] = $category;
            $count++;
        }
        return $array;
    }
}