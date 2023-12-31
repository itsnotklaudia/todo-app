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
        //fetch the user by email
        //if the result is not empty -> email is already in use
        if (! empty($this->getByEmail($email))) {
            throw new Exception('Email is already in use.');
        }
        
        $this->email = $email;

        return $this;
    }

    /**
     * Get user by email (but it should only return one or none)
     */ 
    public function getByEmail($email)
    {
        $conn = Db::getConnection();

        $statement = $conn->prepare('select * from users where email = :email');
        $statement->bindValue(":email", $email);

        $result = $statement->execute();

        if ($result) {
            $user = $statement->fetch(PDO::FETCH_OBJ);
        } else {
            throw new Exception('Something went wrong!');
        }

        return $user;
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

    public function canLogin($email, $password)
    {
        //check if email is not empty
        if (empty($email)) {
            throw new Exception('Make sure email is not empty.');
        }

        //check if password is not empty
        if (empty($password)) {
            throw new Exception('Make sure password is not empty.');
        }

        //get the user from the database
        $user = $this->getByEmail($email);

        //if there are no results then the user does not exist
        if (empty($user)) {
            throw new Exception('User not found.');
        }

        //check if the given password matches the one of the found user
        //password_verify returns true or false
        return password_verify($password, $user->password);
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