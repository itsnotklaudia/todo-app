<?php

include_once(__DIR__ . "/Db.php");

class User {
    private $name;
    private $email;
    private $password;

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
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        //check if email is not empty
        if (empty($email)) {
            throw new Exception('Make sure email is not empty.');
        }

        //check if email is not already in use
        //fetch all users by email
        //if the count is larger than 0 -> email is already in use
        if (count($this->getByEmail($email)) > 0) {
            throw new Exception('Email is already in use.');
        }
        
        $this->email = $email;

        return $this;
    }

    /**
     * Get all users by email (but it should only return one or none)
     */ 
    public function getByEmail($email)
    {
        $conn = Db::getConnection();

        $statement = $conn->prepare('select * from users where email = :email');
        $statement->bindValue(":email", $email);

        $result = $statement->execute();

        if ($result) {
            $users = $statement->fetchAll(PDO::FETCH_OBJ);
        } else {
            throw new Exception('Something went wrong!');
        }

        return $users;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        //check if password is not empty
        if (empty($password)) {
            throw new Exception('Make sure password is not empty.');
        }

        //check if password has 8 or more characters
        if (strlen($password) < 8) {
            throw new Exception('Make sure password is 8 characters or longer.');
        }

        $options = [
            'cost' => 12
        ];
        
        //hash the password with bcrypt
        $this->password = password_hash($password, PASSWORD_DEFAULT, $options);

        return $this;
    }

    public function save()
    {
        //conn
        $conn = Db::getConnection();

        //query
        $statement = $conn->prepare("insert into users (name, email, password) values (:name, :email, :password)");
        
        $name = $this->getName();
        $email = $this->getEmail();
        $password = $this->getPassword();
        
        $statement->bindValue(":name", $name);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $password); 

        $result = $statement->execute();

        //return result
        return $result;
    }
} 