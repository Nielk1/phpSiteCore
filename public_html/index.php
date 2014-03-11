<?php

//Phpinfo();

error_reporting(E_ALL ^ E_NOTICE); // #1 Report all Errors
//error_reporting(0); // #2 No Error Reporting

require("lib/password.php");

define('ROOT_DIR',dirname(__FILE__).'/');

/*Directories that contain classes*/
$classesDir = array (
    ROOT_DIR.'classes/',
    //ROOT_DIR.'firephp/',
    //ROOT_DIR.'includes/'
	//ROOT_DIR.'pages/',
	ROOT_DIR.'library/',
	ROOT_DIR.'styles/default/templates/'
);

// Include all system function files here
set_include_path(implode(PATH_SEPARATOR,array_merge((array)get_include_path(), $classesDir)));

//include('sitecore.php'); // core app? maybe not



class SiteCore {
	private $classesDir;

	private $container;
	private $database;
	
	private $session;
	
	public function __construct($classesDir) {
		$this->classesDir = $classesDir;
	}
	
	public function prepare() {
		$this->session = new Session();
		$this->session->sec_session_start();
	
		$this->container = Pd_Container::get();
		
		$this->configuration = new Configuration();
		$this->container->dependencies()->set('configuration', $this->configuration);
		
		$this->database = Pd_Make::name(Database);
		$this->container->dependencies()->set('database', $this->database);
		
		//$this->session = Pd_Make::name(Session);
		//$this->session =  new Session();
		$this->container->dependencies()->set('session', $this->session);
	}
	
	public function render() {
		if(count($_GET) > 0) {
			try {
				//reset($_GET); // special pointer to first
				//$pageName = key($_GET); // key at special pointer
				$pageName = $_GET['p1'];
				if($pageName == '403_shtml') {
					echo '403 Forbidden';
				} elseif($pageName == 'Main') {
					//next($_GET);
					//$subpage = key($_GET);
					$subpage = $_GET['p2'];
					if($subpage == null) {
						header('Location:/');
						//print_r($_SERVER);
						//throw new Exception('Main is accessed from root unless there are subpaths or paramaters');
					}
				//}elseif($pageName == 'phpinfo') {
				//	phpinfo();
				} else {
					if(!preg_match('/[A-Za-z][A-Za-z0-9]*/', $pageName)) {
						throw new Exception('Invalid Pagename');
					}
					
					$pageName = "Page_" . $pageName;
					
					$page = Pd_Make::name($pageName);
					$page->render();
				}
			} catch (Exception $e) { // get specific reflection exception
				echo '404: "', $pageName, '": ', $e->getMessage(), "\n";
			}			
		} else {
			$mainPage = Pd_Make::name('Page_Main');
			$mainPage->render();
		}
		//echo('</br>');
		//print_r($_GET);
		
		//echo($_SERVER['QUERY_STRING']);
		//print_r($_SERVER);
	}
	
	public function autoload($class_name)
	{
		//global $classesDir;
		$class_name = str_replace('_', '/', $class_name);
		foreach ($this->classesDir as $directory) {
			if (file_exists($directory . $class_name . '.php')) {
				require_once ($directory . $class_name . '.php');
				return;
			}
		}
	}
}


$siteCore = new SiteCore($classesDir);
spl_autoload_register(array($siteCore, 'autoload'));
$siteCore->prepare();
$siteCore->render();
?>