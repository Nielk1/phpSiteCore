<?php
class Widget_Masthead {
	private $loggedIn;
	private $username;
	//private $google;

	public function __construct($loggedIn, $username) {
		$this->loggedIn = $loggedIn;
		$this->username = $username;
		//$this->google = new GooglePlusLogin();
	}
	
	public function render() {
		$masthead = new Template();
		$masthead->loggedIn = $this->loggedIn;
		$masthead->username = $this->username;
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