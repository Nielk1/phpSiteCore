<?php
class Page_Admin {
	private $_database;
	private $mysqli;
	private $_session;
	
	private $isLoggedIn;
	private $permissions;
	
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
		header('X-robots-tag: noindex');
		
		$this->isLoggedIn = $this->_session->login_check($this->mysqli);
		
		if(!$this->isLoggedIn)
		{
			throw new Exception_Permission("Not logged in.");
		}else{
			$this->permissions = $this->_session->getPermissions($this->mysqli);
			$isAdmin = in_array("admin",$this->permissions); // need to be more strict? bool for that
			if(!$isAdmin)
			{
				throw new Exception_Permission("Not authorized.");
			}else{
				$subfunction = $_GET['p2'];
				$sub2 = $_GET['p3'];
				if($subfunction == null) {
					$this->renderPage();
				} else if($subfunction == 'users')
				{
					if($sub2 == null)
					{
						$this->renderToolPage('User Management',new Widget_AdminUsersPanel($this->_session,$this->_database));
					}elseif($sub == 'add'){
						$this->renderToolPage('Add User', new Widget_AdminUsers_NewUserPanel($this->_session,$this->database));
					}
				} else {
					echo ('404');
				}
			}
		}
	}
	
	private function renderPage() {
		$username = $_SESSION['username'];
		
		$view = new Template();
		
		$view->loggedIn = $this->isLoggedIn;
		$view->username = $username;
		$view->scripttags = "";
		
		$view->pageName = "Admin Functions";
		
		//$view->content = str_repeat("test page<br/>",100);
		$view->content = '<a href="admin/users">Users<a><br />';
		
		
		$masthead = new Widget_Masthead($this->isLoggedIn, $username);
		$view->masthead = $masthead->render();
		
		$view->scripttags .= $masthead->renderScript();
		
		echo $view->render('Template_Page_admin.php');
	}
	
	private function renderToolPage($pageName, $widget) {
		$username = $_SESSION['username'];
		
		$view = new Template();
		
		$view->loggedIn = $this->isLoggedIn;
		$view->username = $username;
		$view->scripttags = "";
		
		$view->navigate = '<a href="/admin">Back</a>';
		$view->pageName = $pageName;
		
		$view->content = $widget->render();
		//$view->scripttags .= $widget->renderScript();
		
		$masthead = new Widget_Masthead($this->isLoggedIn, $username);
		$view->masthead = $masthead->render();
		$view->scripttags .= $masthead->renderScript();
		
		echo $view->render('Template_Page_admin.php');
	}
}
?>
