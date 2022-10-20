<?php

class ToDo extends DatabaseObject
{
    public $ID;
    public $Text;
    public $Category;

    public function Add()
    {
        # Checks if required data is set
        if (!isset($this->Text) ||
            !isset($this->Category))
            return false;

        # Checks if the Category object has an ID set as it is required!
        if (!isset($this->Category->ID))
            return false;

        # Runs insert query to add data to the database
        $result = $this->connection->query(
            @"INSERT INTO ToDo
                (Text
                , Category)
            VALUES
                ('{$this->Text}'
                , {$this->Category->ID})");

        # Check if the query run successfully
        if (!$result)
            return false;

        $this->ID = $this->connection->query("SELECT LAST_INSERT_ID()")->fetch_row()[0];
        return true;
    }

    public function Update()
    {

        # Check if ID and Text is set
        if (!isset($this->ID) ||
            !isset($this->Text))
            return false;

        # Check if Category is set and has an ID
        if (!isset($this->Category))
            return false;
        else if (!isset($this->Category->ID))
            return false;

        # Runs update
        return $this->connection->query(
            @"UPDATE ToDo
            SET
                Text = '{$this->Text}'
                , Category = {$this->Category->ID}
            WHERE ID = {$this->ID}");
    }

    public function Delete()
    {

        # Check if an ID was set
        if (!isset($this->ID))
            return false;

        # Delete all underlying Entries
        $entries = $this->GetUnderlyingEntries();
        foreach ($entries as $entry)
            $entry->Delete();

        # Runs delete query and returns result
        return $this->connection->query(
            @"DELETE FROM ToDo
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
            WHERE LinkedToDo = {$this->ID}
              AND LinkedEntry IS NULL");

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
                ,Category
            FROM ToDo
            WHERE ID = $ID LIMIT 0,1");

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
        $category = new Category($this->connection);
        if ($category->GetByID($row['Category']))
            $this->Category = $category;
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

            if (isset($array["Category"])) {
                $category = new Category($this->connection);
                if (!($category->GetByID($array["Category"]))) {
                    return;
                }

                $this->Category = $category;
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

        if (isset($this->Category))
            $array["Category"] = $this->Category->ID;

        return json_encode($array);
    }

    public function GetAll()
    {

        $result = $this->connection->query(@"SELECT ID FROM ToDo");

        if (!$result)
            return [];

        $array = [];
        $count = 0;

        while ($row = $result->fetch_array()) {
            # We need to create a new object each time.
            # Otherwise we override the old references by using GetByID!
            # ($array will contain the same object)
            $todo = new ToDo($this->connection);
            $todo->GetByID($row["ID"]);
            $array[$count] = $todo;
            $count++;
        }
        return $array;
    }
}