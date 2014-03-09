<?php
class Configuration {
	
	private $options  = array();
	
	public function __construct() {
		$this->load("config/config.php");
		$this->load("config/config_custom.php");
	}
	
	private function __clone(){}
	
    /**
     * Retrieve value with constants being a higher priority
     * @param $key Array Key to get
     */
    public function __get($key)
    {
        if (isset($this->options[$key])) {
            return $this->options[$key];
        }else{
            trigger_error("Key $val does not exist", E_USER_NOTICE);
        }
    }

    /**
     * Set a new or update a key / value pair
     * @param $key Key to set
     * @param $value Value to set
     */
    public function __set($key, $value)
    {
        $this->options[$key] = $value;
    }

	private function load($config_file) {
		//$this->options = include $config_file;
		$this->options = array_merge($this->options, include $config_file);
	}
}
?>