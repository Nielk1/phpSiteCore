<?php
class Database {
	
	private $HOST; // The host you want to connect to.
	private $USER; // The database username.
	private $PASSWORD; // The database password. 
	private $DATABASE; // The database name.
	
	private $mysqli; // If you are connecting via TCP/IP rather than a UNIX socket remember to add the port number as a parameter.
	//private $pdo;
	
	private $configuration;
	
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
		$this->configuration = $configuration;

		$this->HOST = $this->configuration->db_HOST;
		$this->USER = $this->configuration->db_USER;
		$this->PASSWORD = $this->configuration->db_PASSWORD;
		$this->DATABASE = $this->configuration->db_DATABASE;
	
		$this->mysqli = new mysqli($this->HOST, $this->USER, $this->PASSWORD, $this->DATABASE);
    }
	
	public function getMySQL() {
		return $this->mysqli;
		//return $this->pdo;
	}
	
	// call a stored procedure, array of vars (output via "name") or vals (input via array(val,bindtype))
	public function callScalarStoredProc($proc, $args) {
		$argArray = array_slice(func_get_args(), 1);
		$call = "CALL `" . $proc . "`(";
		$prefix = '';
		foreach ($argArray as $argVal)
		{
			$call .= $prefix;
			if(is_array($argVal)) {
				$call .= "?";
			} else {
				$call .= "@" . $argVal;
			}
			$prefix = ',';
		}
		$call .= ")";
		
		if($stmt = $this->mysqli->prepare($call)) {
			$selectStmt = "SELECT ";
			$prefix = '';
			$outputArgs = array();
			$paramNum = 0;
			foreach ($argArray as $argVal)
			{
				if(is_array($argVal)) {
					if($argVal[1] == 'b')
					{
						$null = NULL;
						$stmt->bind_param($argVal[1], $null);
						$stmt->send_long_data($paramNum, $argVal[0]);
					}else{
						$stmt->bind_param($argVal[1], $argVal[0]);
					}
				}else{
					$selectStmt .= $prefix . "@" . $argVal;
					$prefix = ',';
					$outputArgs[] = $argVal;
				}
				$paramNum += 1;
			}
			if($stmt->execute()) { // Execute the prepared query.
				$stmt->free_result();
				if($stmt = $this->mysqli->prepare($selectStmt)) {
					if($stmt->execute()) { // Execute the prepared query.
						//$meta = $stmt->result_metadata();
						//while ($field = $meta->fetch_field()) {
						//	$params[] = &$row[$field->name];
						//}
						foreach($outputArgs as $outputArg) {
							$params[] = &$row[$outputArg];
						}
						call_user_func_array(array($stmt, 'bind_result'), $params);
						$stmt->fetch();
						//while ($stmt->fetch()) {
						//	foreach($row as $key => $val) {
						//		$c[$key] = $val;
						//	}
						//	$result[] = $c;
						//}
						$stmt->close(); 
						
						return $row;
					}
				}
			}
		}
		return false;
	}
	
	// call a stored procedure with no return value
	public function callNoReturnStoredProc($proc, $args) {
		$argArray = array_slice(func_get_args(), 1);
		$call = "CALL `" . $proc . "`(";
		$prefix = '';
		foreach ($argArray as $argVal)
		{
			$call .= $prefix;
			if(is_array($argVal)) {
				$call .= "?";
			}else{
				$call .= "'" . $argVal . "'";
			}
			$prefix = ',';
		}
		$call .= ")";
		
		if($stmt = $this->mysqli->prepare($call)) {
			$paramNum = 0;
			foreach ($argArray as $argVal)
			{
				if(is_array($argVal)) {
					if($argVal[1] == 'b')
					{
						$stmt->bind_param($argVal[1], NULL);
						$stmt->send_long_data($paramNum, $argVal[0]);
					}else{
						$stmt->bind_param($argVal[1], $argVal[0]);
					}
				}
				$paramNum += 1;
			}
			if($stmt->execute()) { // Execute the prepared query.
				$stmt->free_result();
				
				return true;
			}
		}
		return false;
	}
}
?>