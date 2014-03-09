<?php
class Database {
	
	private $HOST; // The host you want to connect to.
	private $USER; // The database username.
	private $PASSWORD; // The database password. 
	private $DATABASE; // The database name.
	
	private $mysqli; // If you are connecting via TCP/IP rather than a UNIX socket remember to add the port number as a parameter.
	//private $pdo;
	
	private $_configuration;
	
	public function __construct() {
		//$this->HOST = "localhost";
		//$this->USER = "";
		//$this->PASSWORD = "";
		//$this->DATABASE = "";
	
		//$this->mysqli = new mysqli($this->HOST, $this->USER, $this->PASSWORD, $this->DATABASE);
		////$this->pdo = new PDO("mysql:host=" . $this->HOST . ";dbname=" . $this->DATABASE, $this->USER, $this->PASSWORD);
	}
	
    /**
     * @PdInject configuration
     */
    public function setConfiguration($configuration) {
		$this->_configuration = $configuration;

		$this->HOST = $this->_configuration->db_HOST;
		$this->USER = $this->_configuration->db_USER;
		$this->PASSWORD = $this->_configuration->db_PASSWORD;
		$this->DATABASE = $this->_configuration->db_DATABASE;
	
		$this->mysqli = new mysqli($this->HOST, $this->USER, $this->PASSWORD, $this->DATABASE);
    }
	
	public function getMySQL() {
		return $this->mysqli;
		//return $this->pdo;
	}
}
?>