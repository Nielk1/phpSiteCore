<?php
class Page_Login {
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
		} elseif($subfunction == 'Login') {
			$this->doLogin();
		} elseif($subfunction == 'Logout') {
			$this->doLogout();
		} elseif($subfunction == 'Register') {
			$this->doRegister();
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

		$masthead = Pd_Make::name(Widget_Masthead);
		//$masthead = new Widget_Masthead($isLoggedIn, $username);
		$view->masthead = $masthead->render();

		$view->scripttags .= $masthead->renderScript();

		echo $view->render('Template_Page_Login.php');
	}
	
	private function doLogin() {
		if($sub2 == null)
		{
			if(isset($_POST['email'], $_POST['password'])) { 
			   $email = $_POST['email'];
			   $password = $_POST['password'];
			   if($this->session->login($email, $password, $this->mysqli) == true) {
				  // Login success
				  header ("location:/Login"); 
				  echo 'Success: You have been logged in!';
			   } else {
				  // Login failed
				  header('Location: Error/1');
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
				header ("location:/Login"); 
				echo 'Success: You have been logged out!';
			} else { 
			   // The correct POST variables were not sent to this page.
			   echo 'Invalid Request';
			}
		}
	}
	
	private function doRegister() {
		if($sub2 == null) {
			if($_SERVER['REQUEST_METHOD'] === 'POST') {
				if(isset($_POST['email'])) { 
				   $email = $_POST['email'];
				   if($this->session->register($email, $this->mysqli) == true) {
					  // Register success
					  header ("location:/Login"); 
					  echo 'Success: Registration email sent.';
				   } else {
					  // Register failed
					  header('Location: Error/1');
				   }
				} else { 
				   // The correct POST variables were not sent to this page.
				   echo 'Invalid Request';
				}
			} elseif($_SERVER['REQUEST_METHOD'] === 'GET') {
				if ( isset($_GET['token']) ) {
					if($this->session->register_token($_GET['token'], $this->mysqli) == true) {
					  // Register success
					  header ("location:/Login"); 
					  echo 'Success: Registration step 2.';
				   } else {
					  // Register failed
					  header('Location: Error/1');
				   }
				} else {
					echo 'Invalid Request';
				}
			} else {
				echo 'Invalid Request';
			}
		}
	}
}
?>
