<?php

class User {
 
    private $conn;
    private $table_name = "user";
 
    public $id;
    public $firstname;
    public $lastname;
    public $password;
		public $email; 		

    public function __construct($db) {
        $this->conn = $db;
    }
		
		public function isValid() {
			return !empty($this->firstname) && !empty($this->password) && !empty($this->email);
		}

		function create() {
			$query = "INSERT INTO ".$this->table_name."
								SET 
                	firstname = :firstname,
                	lastname = :lastname,
                	password = :password,
									email = :email";
			$stmt = $this->conn->prepare($query);
			
			$this->firstname=htmlspecialchars(strip_tags($this->firstname));
    	$this->lastname=htmlspecialchars(strip_tags($this->lastname));
    	$this->password=htmlspecialchars(strip_tags($this->password));
			$this->email=htmlspecialchars(strip_tags($this->email));		
		
			$stmt->bindParam(':firstname', $this->firstname);
  	  $stmt->bindParam(':lastname', $this->lastname);
	    $stmt->bindParam(':email', $this->email);

			$password_hash = password_hash($this->password, PASSWORD_BCRYPT);
	    $stmt->bindParam(':password', $password_hash);
			
			 if($stmt->execute()){
        return true;
 		   }
 				
				return false;		
		}	
		
		function emailExists() {
    	$query = "SELECT id, firstname, lastname, password
      	      FROM " . $this->table_name . "
        	    WHERE email = ?
          	  LIMIT 0,1";
 	
    	$stmt = $this->conn->prepare($query);
 	
    	$this->email=htmlspecialchars(strip_tags($this->email));
    	$stmt->bindParam(1, $this->email);
    	$stmt->execute();
 	
    	$num = $stmt->rowCount();
 	
    	if($num>0){
 	
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
?>
