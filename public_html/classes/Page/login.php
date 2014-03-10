<?php
class Page_login {
	private $database;
	private $session;
	private $mysqli;
	
	public function __construct() {}

    /**
     * @PdInject database
     */
    public function setDatabase($database) {
        $this->database = $database;
		
		$this->mysqli = $this->database->getMySQL();
    }
	
    /**
     * @PdInject session
     */
    public function setSession($session) {
        $this->session = $session;
    }
	
	public function render() {
		$subfunction = $_GET['p2'];
		$sub2 = $_GET['p3'];
		if($subfunction == null) {
			$this->renderPage();
		} elseif($subfunction == 'login') {
			$this->doLogin();
		} elseif($subfunction == 'logout') {
			$this->doLogout();
		//} elseif($subfunction == 'plus') {
		//	$tmp = new GooglePlusLogin();
		//	echo $tmp->respond();
		} else {
			echo ('404');
		}
	}
	
	private function renderPage() {
		$isLoggedIn = $this->session->login_check($this->mysqli);
		$username = $_SESSION['username'];
		
		$view = new Template();
		
		//$view->content = str_repeat("test page<br/>",100);
		$view->loggedIn = $isLoggedIn;
		$view->username = $username;
		$view->scripttags = "";

		$masthead = new Widget_Masthead($isLoggedIn, $username);
		$view->masthead = $masthead->render();

		$view->scripttags .= $masthead->renderScript();

		echo $view->render('Template_Page_login.php');
	}
	
	private function doLogin() {
		if($sub2 == null)
		{
			if(isset($_POST['email'], $_POST['password'])) { 
			   $email = $_POST['email'];
			   $password = $_POST['password'];
			   if($this->session->login($email, $password, $this->mysqli) == true) {
				  // Login success
				  header ("location:/login"); 
				  echo 'Success: You have been logged in!';
			   } else {
				  // Login failed
				  header('Location: error/1');
			   }
			} else { 
			   // The correct POST variables were not sent to this page.
			   echo 'Invalid Request';
			}
		}
	}
	
	private function doLogout() {
		if($sub2 == null)
		{
			if($_SERVER['REQUEST_METHOD'] == 'POST') { 
				$this->session->logout();
				header ("location:/login"); 
				echo 'Success: You have been logged out!';
			} else { 
			   // The correct POST variables were not sent to this page.
			   echo 'Invalid Request';
			}
		}
	}
}
?>