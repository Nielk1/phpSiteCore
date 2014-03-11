<?php
class Widget_Masthead {
	private $loggedIn;
	private $username;
	private $email;
	//private $google;
	private $session;

	public function __construct() {
		//$this->google = new GooglePlusLogin();
	}
	
    /**
     * @PdInject session
     */
    public function setSession($session) {
        $this->session = $session;
		
		$this->loggedIn = $this->session->login_check($this->mysqli);
		$this->username = $_SESSION['username'];
		$this->email = $_SESSION['email'];
    }
	
	public function render() {
		$masthead = new Template();
		$masthead->loggedIn = $this->loggedIn;
		$masthead->username = $this->username;
		$masthead->email = $this->email;
		//$masthead->google = $this->google->render();
		return $masthead->render('Template_Widget_Masthead.php');
	}
	
	public function renderScript() {
		$mastheadScript = new Template();
		$mastheadScript->loggedIn = $this->loggedIn;
		$mastheadScript->username = $this->username;
		return $mastheadScript->render('Script_Widget_Masthead.php');// . $this->google->renderScript();
	}
}
?>