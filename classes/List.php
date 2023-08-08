<?php

include_once(__DIR__ . "/Db.php");

class TaskList {
    private $name;

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        //check if name is not empty
        if (empty($name)) {
            throw new Exception('Make sure name is not empty.');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Get all lists
     */ 
    public function all()
    {
        $conn = Db::getConnection();

        $statement = $conn->prepare('select * from lists');
        //https://stackoverflow.com/questions/808475/how-do-i-get-a-count-of-associated-rows-in-a-left-join-in-mysql

        $result = $statement->execute();

        if ($result) {
            $lists = $statement->fetchAll(PDO::FETCH_OBJ);
        } else {
            throw new Exception('Something went wrong!');
        }

        return $lists;
    }

    public function save()
    {
        //conn
        $conn = Db::getConnection();

        //query
        $statement = $conn->prepare("insert into lists (name) values (:name)");
        
        $name = $this->getName();
        
        $statement->bindValue(":name", $name);

        $result = $statement->execute();

        //return result
        return $result;
    }
}