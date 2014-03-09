<?php
class Widget_AdminUsersPanel {
	private $_database;
	private $mysqli;
	private $_session;

	private $isLoggedIn;
	
	public function __construct($session, $database) {
		$this->_session = $session;
		$this->_database = $database;
		$this->mysqli = $this->_database->getMySQL();
	}
	
	public function render() {
		//$masthead = new Template();
		//$masthead->loggedIn = $this->loggedIn;
		//$masthead->username = $this->username;
		////$masthead->google = $this->google->render();
		//return $masthead->render('Template_Widget_Masthead.php');
		
		$this->isLoggedIn = $this->_session->login_check($this->mysqli);
		
		if(!$this->isLoggedIn)
		{
			//throw new Exception_Permission("Not logged in.");
			return "widget requires you be logged in";
		}else{
			$this->permissions = $this->_session->getPermissions($this->mysqli);
			$isAdmin = in_array("admin",$this->permissions); // need to be more strict? bool for that
			if(!$isAdmin)
			{
				//throw new Exception_Permission("Not authorized.");
				return "not authorized for this widget";
			}else{
			
				if ($result = $this->mysqli->query("SELECT m.id, m.username, m.email FROM members m")) {
					$rows = array();
					
					while($row = $result->fetch_row())
					{
						$rows[]=$row;
					}
					$result->close();
					
					$template = new Template();
					$template->userdata = $rows;
					return $template->render('Template_Widget_AdminUsersPanel.php');
				}
			}
		}
	}
	
	//public function renderScript() {
		//$mastheadScript = new Template();
		//$mastheadScript->loggedIn = $this->loggedIn;
		//$mastheadScript->username = $this->username;
		//return $mastheadScript->render('Script_Widget_Masthead.php');// . $this->google->renderScript();
	//	return '';
	//}
	
}
?>