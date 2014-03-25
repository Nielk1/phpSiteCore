<?php
class Session {
	private $database;
	//private $mysqli;
	private $utility;
	
	public function __construct() {
		
	}
	
    /**
     * @PdInject database
     */
    public function setDatabase($database) {
		//$test = new Template();
	
        $this->database = $database;
		//$this->mysqli = $this->database->getMySQL();
    }
	
	/**
	 * @PdInject utility
	 */
	public function setUtility($utility) {
		$this->utility = $utility;
	}
	
	function sec_session_start() {
		$session_name = 'sec_session_id'; // Set a custom session name
		$secure = false; // Set to true if using https.
		$httponly = true; // This stops javascript being able to access the session id. 
	 
		ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
		session_name($session_name); // Sets the session name to the one set above.
		$cookieParams = session_get_cookie_params(); // Gets current cookies params.
		session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
		//session_name($session_name); // Sets the session name to the one set above.
		session_start(); // Start the php session
		//$_SESSION['data'] = time();
		//session_regenerate_id(true); // regenerated the session, delete the old one. // use on re-authentication
		
		//session_start();
		//session_regenerate_id(true);
		//$_SESSION['user_session']=session_id();
		//$user_session=$_SESSION['user_session'];//Try pass $user_session for encryption.You can echo it first for test
		
		
		// Make sure the session hasn't expired, and destroy it if it has
		if($this->validateSession())
		{
			// Check to see if the session is new or a hijacking attempt
			if(!$this->preventHijacking())
			{
				// Reset session data and regenerate id
				$_SESSION = array();
				$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
				$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
				$this->regenerateSession();

			// Give a 5% chance of the session id changing on any request
			}elseif(rand(1, 100) <= 5){
				$this->regenerateSession();
			}
		}else{
			$_SESSION = array();
			session_destroy();
			session_start();
		}
	}

	function regenerateSession()
	{
		// If this session is obsolete it means there already is a new id
		if(isset($_SESSION['OBSOLETE']) || $_SESSION['OBSOLETE'] == true)
			return;

		// Set current session to expire in 10 seconds
		$_SESSION['OBSOLETE'] = true;
		$_SESSION['EXPIRES'] = time() + 10;

		// Create new session without destroying the old one
		session_regenerate_id(false);

		// Grab current session ID and close both sessions to allow other scripts to use them
		$newSession = session_id();
		session_write_close();

		// Set session ID to the new one, and start it back up again
		session_id($newSession);
		session_start();

		// Now we unset the obsolete and expiration values for the session we want to keep
		unset($_SESSION['OBSOLETE']);
		unset($_SESSION['EXPIRES']);
	}
	
	protected function validateSession()
	{
		if( isset($_SESSION['OBSOLETE']) && !isset($_SESSION['EXPIRES']) )
			return false;

		if(isset($_SESSION['EXPIRES']) && $_SESSION['EXPIRES'] < time())
			return false;

		return true;
	}
	
	function login($email, $password) {
		if($retVals = $this->database->callScalarStoredProc("login_getinfo", array($email,'s'), "user_id", "username", "hash")) {
			$user_id = $retVals["user_id"];
			$username = $retVals["username"];
			$hash = $retVals["hash"];

			if($user_id != null) {
				// We check if the account is locked from too many login attempts
				//if(!$locked) {
					if(password_verify($password, $hash)) {
						// Password is correct!

						if (password_needs_rehash($hash, PASSWORD_DEFAULT, array("cost" => 10))) {
							$hash = password_hash($password, PASSWORD_DEFAULT, array("cost" => 10));
							/* Store new hash in db */
							
							
							
							
							$this->database->callNoReturnStoredProc("login_rehash", array($user_id,'i'),array($hash,'s'));
							//if($stmt = $mysqli->prepare("CALL `login_rehash`(?,?)")) {
							//	$stmt->bind_param('is', $user_id, $hash);
							//	
							//	if($stmt->execute()) { // Execute the prepared query.
							//		$stmt->free_result();
							//	}
							//}
						}
						
						$user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
						//$user_id = preg_replace("/[^0-9]+/", "", $user_id); // XSS protection as we might print this value
						//userID is set by the DB, of course it's safe
						$_SESSION['user_id'] = $user_id;
						$_SESSION['email'] = preg_replace("/[^a-zA-Z0-9_\@-]+/", "", $email);
						$username = preg_replace("/[^a-zA-Z0-9_\@-]+/", "", $username); // XSS protection as we might print this value
						$_SESSION['username'] = $username;
						//$_SESSION['login_string'] = hash('sha512', $hash.$user_browser);
						$_SESSION['login_string'] = hash('sha512', $user_browser);
						
						//$this->regenerateSession();
						
						// Login successful.
						return true;
					}else{
						// login was not successful
						return false;
					}
				//}else{
				//	// Account is locked
				//	// Send an email to user saying their account is locked
				//	return false;
				//}
			}else{
				// No user exists (obviously don't tell them this)
				return false;
			}
		} else {
			// query failed prepare?
			return false;
		}
	}

	function logout()
	{
		// Unset all session values
		$_SESSION = array();
		// get session parameters 
		$params = session_get_cookie_params();
		// Delete the actual cookie.
		setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		// Destroy session
		session_destroy();
		//header('Location: ./');
	}
	
	function register_token($token, $mysqli) {
		$tokenBin = $this->utility->urlsafe_b64decode($token);
		
		if($tokenBin) {
			if($retVal = $this->database->callScalarStoredProc("login_register_basic_check", array($tokenBin,'b'), "email")) {

			}
		}
		
		return false;
	}
	
	function register($email, $mysqli) {
		if($retVals = $this->database->callScalarStoredProc("login_register_basic",array($email,'s'),"token","sendMail")) {
			$token = $retVals["token"];
			$sendMail = $retVals["sendMail"];

			if($token != null) {
				if($sendMail == 1) {
					$headers = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
					$headers .= 'From: Name ' . "\r\n";
					$headers .= 'Reply-To: Name ' . "\r\n";
					$subject = 'SiteCore Registration Token';
					$message .= '<div>/Login/Register?token=' . $this->utility->urlsafe_b64encode($token) . '</div>';

					mail($email,$subject, $message, $headers);
					
					return true;
				}
			}else{
				// registration failed
				return false;
			}
		} else {
			// query failed prepare?
			return false;
		}
	}
	
	protected function preventHijacking()
	{
		if(!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent']))
			return false;

		if ($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR'])
			return false;

		if( $_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
			return false;

		return true;
	}
	
	function login_check($mysqli) {
		// Check if all session variables are set
		if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
			$user_id = $_SESSION['user_id'];
			$login_string = $_SESSION['login_string'];
			$username = $_SESSION['username'];
	 
			$user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
			// $login_check = hash('sha512', $hash.$user_browser);
			$login_check = hash('sha512', $user_browser);
			if($login_check == $login_string) {
				// Logged In!!!!
				return true;
			} else {
				// Not logged in
				return false;
			}
		} else {
			// Not logged in
			return false;
		}
	}
	
	function getPermissions($mysqli) {
		$user_id = $_SESSION['user_id'];
		$rows = array();
		
		if($stmt = $mysqli->prepare("CALL `getPermissions`(?)")) {
			$stmt->bind_param('i', $user_id);
			$stmt->execute(); // Execute the prepared query.
			$stmt->bind_result($permissionName);
			
			while($stmt->fetch()) { 
				// Do what you want with your results.
				$rows[]=$permissionName;
			}

			$stmt->close(); 

			// Now move the mysqli connection to a new result. 
			while($mysqli->next_result()) { } 
			
			//$stmt->store_result();
			//$stmt->bind_result($permissionName);
			//while($stmt->fetch()) {
			//	$rows[]=$permissionName;
			//}
			//$stmt->free_result();
		}
		
		return $rows;
	}
	
	function checkbrute($user_id, $mysqli) {
	   // Get timestamp of current time
	   $now = time();
	   // All login attempts are counted from the past 2 hours. 
	   $valid_attempts = $now - (2 * 60 * 60); 
	 
	   if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) { 
		  $stmt->bind_param('i', $user_id); 
		  // Execute the prepared query.
		  $stmt->execute();
		  $stmt->store_result();
		  // If there has been more than 5 failed logins
		  if($stmt->num_rows > 5) {
			 return true;
		  } else {
			 return false;
		  }
	   }
	}
}
?>