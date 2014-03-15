<?php
class Page_Main {
	private $_database;
	private $mysqli;
	private $_session;
	
	public function __construct() {}

    /**
     * @PdInject database
     */
    public function setDatabase($database) {
        $this->_database = $database;
		$this->mysqli = $this->_database->getMySQL();
    }
	
	/**
     * @PdInject session
     */
    public function setSession($session) {
        $this->_session = $session;
    }
	
	public function render() {
		$isLoggedIn = $this->_session->login_check($this->mysqli);
		$username = $_SESSION['username'];
		
		$view = new Template();
		
		$view->content = str_repeat("test page<br/>",100);
		$view->loggedIn = $isLoggedIn;
		$view->username = $username;
		$view->scripttags = "";
		
		$masthead = Pd_Make::name(Widget_Masthead);
		//$masthead = new Widget_Masthead($isLoggedIn, $username);
		$view->masthead = $masthead->render();
		
		$view->scripttags .= $masthead->renderScript();
		
		echo $view->render('Template_Page_Main.php');
	}
}
?>
