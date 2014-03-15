<?php
class Page_Manage {
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
		
		if($subfunction == null) {
			
		} elseif($subfunction == 'Notifications') {
			$section = Pd_Make::name(Widget_Manage_Notifications);
			$view->content = $section->render();
			$view->active_section = 1;
		} elseif($subfunction == 'Profile') {
			$section = Pd_Make::name(Widget_Manage_Profile);
			$view->content = $section->render();
			$view->active_section = 2;
		} else {
			echo ('404');
		}
		
		echo $view->render('Template_Page_Manage.php');
	}
}
?>
