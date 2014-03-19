<?php
class Widget_Manage_Profile {
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
		$widgetData = new Template();
		$widgetData->loggedIn = $this->loggedIn;
		$widgetData->username = $_SESSION['username'];
		//$masthead->google = $this->google->render();
		return $widgetData->render('Template_Widget_Manage_Profile.php');
	}
}
?>