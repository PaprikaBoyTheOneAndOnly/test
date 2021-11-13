<?php

class Database {
	private $host = "localhost";
  private $db_name = "sps8192";
	private $username = "root";
	private $password = "admin";
	
	public $conn;

	public function getConnection() {
		$this->conn = null;
		
		try {
			$this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name, $this->username, $this->password);
		} catch(PDOException $exception) {
			echo "Conenction error: ".$exception->getMessage();
		}
		
		return $this->conn;
	}
}
?> 
