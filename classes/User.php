<?php

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
        if (empty($email)) {
            throw new Exception('Make sure email is not empty.');
        }

        if (count($this->getByEmail($email)) > 0) {
            throw new Exception('Email is already in use.');
        }
        
        $this->email = $email;

        return $this;
    }

    /**
     * Get the user by email
     */ 
    public function getByEmail($email)
    {
        $conn = new PDO('mysql:host=localhost;dbname=todoapp23;port=8889', "root", "root");

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
        if (empty($password)) {
            throw new Exception('Make sure password is not empty.');
        }

        if (strlen($password) < 8) {
            throw new Exception('Make sure password is 8 characters or longer.');
        }

        $options = [
            'cost' => 12
        ];
        
        $this->password = password_hash($password, PASSWORD_DEFAULT, $options);

        return $this;
    }

    public function save()
    {
        //conn
        $conn = new PDO('mysql:host=localhost;dbname=todoapp23;port=8889', "root", "root");

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