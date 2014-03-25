<?php
class Utility {
	//private $configuration;
	
	public function __construct() {
		//$this->HOST = "localhost";
		//$this->USER = "";
		//$this->PASSWORD = "";
		//$this->DATABASE = "";
	
		//$this->mysqli = new mysqli($this->HOST, $this->USER, $this->PASSWORD, $this->DATABASE);
		////$this->pdo = new PDO("mysql:host=" . $this->HOST . ";dbname=" . $this->DATABASE, $this->USER, $this->PASSWORD);
	}
	
	function urlsafe_b64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_',''),$data);
		return $data;
	}

	function urlsafe_b64decode($string) {
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}
}
?>