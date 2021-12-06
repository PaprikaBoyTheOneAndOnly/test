<?php

class User
{
    private $conn;

    public $id;
    public $firstname;
    public $lastname;
    public $password;
    public $email;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    public final function isValid(): bool
    {
        return !empty($this->firstname) && !empty($this->password) && !empty($this->email);
    }

    final function create(): bool
    {
        $query = "INSERT INTO user (firstname, lastname, password, email) 
                    VALUES(:firstname, :lastname, :password, :email)";
        $stmt = $this->conn->prepare($query);

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    final function emailExists(): bool
    {
        $query = "SELECT id, firstname, lastname, password
          	        FROM user 
                    WHERE email = ?
          	        LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];

            return true;
        }

        return false;
    }
}
