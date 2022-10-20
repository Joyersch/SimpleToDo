<?php

class Entry extends DatabaseObject
{
    public $ID;
    public $Text;
    public $Done;

    private $LinkedToDo;
    private $LinkedEntry;

    public function Add()
    {

        # Checks if required data is set
        if (!isset($this->Text))
            return false;


        $queryAdditionalValues = "";
        $queryAdditionalSet = "";

        # set done incase it is not set
        if (!isset($this->Done))
            $this->Done = false;

        if (isset($this->Done)) {
            $queryAdditionalValues .= ", Done";
            $queryAdditionalSet .= "," . ($this->Done ? "TRUE" : "FALSE");
        }

        if (isset($this->LinkedToDo)) {
            $queryAdditionalValues .= ", LinkedToDo";
            $queryAdditionalSet .= "," . $this->LinkedToDo;
        }

        if (isset($this->LinkedEntry)) {
            $queryAdditionalValues .= ", LinkedEntry";
            $queryAdditionalSet .= "," . $this->LinkedEntry;
        }

        # Runs insert query to add data to the database
        return $this->connection->query(
            @"INSERT INTO Entry
                (Text{$queryAdditionalValues})
            VALUES
                ('{$this->Text}'{$queryAdditionalSet})");
    }

    public function Update()
    {

        # Check if ID is set
        if (!isset($this->ID))
            return false;

        # If Done is not set we can assume it is supposed to be false
        if (!isset($this->Done))
            $this->Done = false;

        $Done = $this->Done ? "TRUE" : "FALSE";

        $queryAdditions = "";

        if (isset($this->Text))
            $queryAdditions .= @",Text = '{$this->Text}'";

        if (isset($this->LinkedToDo))
            $queryAdditions .= @",LinkedToDo = {$this->LinkedToDo}";


        if (isset($this->LinkedEntry))
            $queryAdditions .= @",LinkedEntry = {$this->LinkedEntry}";

        # Runs update
        return $this->connection->query(
            @"UPDATE Entry
            SET
                Done = {$Done}{$queryAdditions}
            WHERE ID = {$this->ID}");
    }

    public function Delete()
    {

        # Check if an ID was set
        if (!isset($this->ID))
            return false;

        # Delete all underlying entries
        $entries = $this->GetUnderlyingEntries();
        foreach ($entries as $entry)
            $entry->Delete();

        # Runs delete query and returns result
        return $this->connection->query(
            @"DELETE FROM Entry
            WHERE ID = {$this->ID}");
    }

    public function GetUnderlyingEntries(): array
    {
        if (!isset($this->ID))
            return [];

        $result = $this->connection->query(
            @"SELECT
                ID
            FROM Entry
            WHERE LinkedToDo = {$this->LinkedToDo }
              AND LinkedEntry = {$this->ID}");

        #Check if a result was given
        if (!$result)
            return [];

        $array = [];
        $count = 0;

        while ($row = $result->fetch_array()) {
            # We need to create a new object each time.
            # Otherwise we override the old references by using GetByID!
            # ($array will contain the same object)
            $Entry = new Entry($this->connection);
            $Entry->GetByID($row["ID"]);
            $array[$count] = $Entry;
            $count++;
        }
        return $array;
    }

    public function GetByID($ID)
    {

        # Query Data based on the given ID;
        $result = $this->connection->query(
            "SELECT
                Text
                , Done
                , LinkedToDo
                , LinkedEntry
            FROM Entry
            WHERE ID = $ID
            LIMIT 0,1");

        #Check if a result was given
        if (!$result)
            return false;

        # Get result from query
        $row = $result->fetch_array();

        # Check if the result was empty
        if (!$row)
            return false;

        # Write data from query to this object
        $this->ID = $ID;
        $this->Text = $row['Text'];
        $this->Done = $row['Done'];

        if (isset($row['LinkedToDo']))
            $this->LinkedToDo = $row['LinkedToDo'];

        if (isset($row['LinkedEntry']))
            $this->LinkedEntry = $row['LinkedEntry'];#
        return true;
    }

    public function GetByJSON($json)
    {
        $array = json_decode($json, true);

        # if json did not parse return false
        if ($array === null)
            return false;

        try {
            if (isset($array["ID"]))
                $this->ID = $array["ID"];

            if (isset($array["Text"]))
                $this->Text = $array["Text"];

            if (isset($array["Done"]))
                $this->Done = $array["Done"];

            if (isset($array["LinkedToDo"])) {
                $this->LinkedToDo = $array["LinkedToDo"];
            }

            if (isset($array["LinkedEntry"])) {
                $this->LinkedEntry = $array["LinkedEntry"];
            }
        } catch (Exception $ex) {
            return false;
        }
        return true;
    }

    public function PrintJSON()
    {
        $array = [];

        if (isset($this->ID))
            $array["ID"] = $this->ID;

        if (isset($this->Text))
            $array["Text"] = $this->Text;

        if (isset($this->Done))
            $array["Done"] = $this->Done;

        if (isset($this->LinkedToDo))
            $array["LinkedToDo"] = $this->LinkedToDo;

        if (isset($this->LinkedEntry))
            $array["LinkedEntry"] = $this->LinkedEntry;

        return json_encode($array);
    }

    public function GetLinkedEntry()
    {

        if (!isset($this->LinkedEntry))
            return null;

        $entry = new Entry($this->conn);
        $entry->GetByID($this->LinkedEntry);
        return $entry;
    }

    public function GetLinkedToDo()
    {

        if (!isset($this->LinkedToDo))
            return null;

        $todo = new ToDo($this->conn);
        $todo->GetByID($this->LinkedToDo);
        return $todo;
    }

    public function GetAll()
    {

        $result = $this->connection->query(@"SELECT ID FROM Entry");

        if (!$result)
            return [];

        $array = [];
        $count = 0;

        while ($row = $result->fetch_array()) {
            # We need to create a new object each time.
            # Otherwise we override the old references by using GetByID!
            # ($array will contain the same object)
            $entry = new Entry($this->connection);
            $entry->GetByID($row["ID"]);
            $array[$count] = $entry;
            $count++;
        }
        return $array;
    }
}