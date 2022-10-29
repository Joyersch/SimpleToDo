<?php

class Authentication extends DatabaseObject
{
    public $Name;
    public $StartTime;
    public $EndTime;
    public $PassKey;

    public function GetByKey($Key)
    {

        # query data
        $result = $this->connection->query(
            "SELECT
                Name
                , StartTime
                , EndTime
                , PassKey
            FROM Authentication
            WHERE PassKey = '$Key'
            LIMIT 0,1");

        # check if a result was given
        if (!$result)
            return false;

        # get result from query
        $row = $result->fetch_array();

        # check if the result was empty
        if (!$row)
            return false;

        # write data from query to this object
        $this->Name = $row['Name'];
        $this->StartTime = $row['StartTime'];
        $this->EndTime = $row['EndTime'];
        $this->PassKey = $row['PassKey'];
        return true;
    }

    public function GetByName($Name)
    {
        # Query Data based on the given ID;
        $result = $this->connection->query(
            "SELECT
                StartTime
                , EndTime
                , PassKey
            FROM Authentication
            WHERE Name = '$Name'
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
        $this->Name = $Name;
        $this->StartTime = $row['StartTime'];
        $this->EndTime = $row['EndTime'];
        $this->PassKey = $row['PassKey'];
        return true;
    }

    public function Add()
    {
        if (!isset($this->Name) ||
            !isset($this->StartTime) ||
            !isset($this->EndTime)
        )
            return false;

        $dateTime = new DateTime($this->StartTime);
        $StartTime = $dateTime->getTimestamp();

        $dateTime = new DateTime($this->EndTime);
        $EndTime = $dateTime->getTimestamp();

        if ($StartTime > $EndTime)
            return false;

        # Runs insert query to add data to the database
        $result = $this->connection->query(
            @"INSERT INTO Authentication (
                Name
                , StartTime
                , EndTime) 
            VALUES 
                ('{$this->Name}'
                ,'{$this->StartTime}'
                ,'{$this->EndTime}')");

        if (!$result)
            return false;

        $result = $this->connection->query(
            @"SELECT
                PassKey
            FROM Authentication
            WHERE Name ='{$this->Name}'");

        if (!$result)
            return false;

        $row = $result->fetch_array();

        # Check if the result was empty
        if (!$row)
            return false;

        $this->PassKey = $row[0];

        return true;
    }

    public function Verify()
    {
        if (!isset($this->Name) ||
            !isset($this->StartTime) ||
            !isset($this->EndTime)
        )
            return false;

        $dateTime = new DateTime();

        # query datetime as the database should manage all date related shenanigans
        $result = $this->connection->query("SELECT NOW()");

        #Check if a result was given
        if (!$result)
            return false;

        # Get result from query
        $row = $result->fetch_array();

        # Check if the result was empty
        if (!$row)
            return false;

        $dateTime = new DateTime($row[0]);
        $currentTime = $dateTime->getTimestamp();

        $dateTime = new DateTime($this->StartTime);
        $StartTime = $dateTime->getTimestamp();

        $dateTime = new DateTime($this->EndTime);
        $EndTime = $dateTime->getTimestamp();

        # checks if the current time is in the bounds of the authentication end time
        if ($currentTime > $EndTime){
            $this->Delete();
            return false;
        }

        # checks if the current time is in the bounds of the authentication start time
        if ($currentTime < $StartTime){
            $this->Delete();
            return false;
        }
        return true;
    }

    public function Delete()
    {
        # Check if an ID was set
        if (!isset($this->Name))
            return false;

        # Runs delete query and returns result
        return $this->connection->query(
            @"DELETE FROM Authentication
            WHERE Name = '{$this->Name}'");
    }
}