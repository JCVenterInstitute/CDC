<?php
class Database
{	
    private $host = "cdc-1.jcvi.org";
    private $db_name = "CDC";
    private $username = "cdc_app";
    private $password = "Guest649745f";
    public $conn;
     
    public function dbConnection()
    {
     
	$this->conn = null;    
        try
	{
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=3306;dbname=" . $this->db_name, $this->username, $this->password);
	    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        }
		catch(PDOException $exception)
	{
            echo "Connection error: " . $exception->getMessage();
        }
         
        return $this->conn;
    }
}
?>