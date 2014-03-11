<?php
class Session {
	private $_database;
	private $mysqli;
	
	public function __construct() {
		
	}
	
    /**
     * @PdInject database
     */
    public function setDatabase($database) {
		//$test = new Template();
	
        $this->_database = $database;
		$this->mysqli = $this->_database->getMySQL();
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
	
	function login($email, $password, $mysqli) {
		// Using prepared Statements means that SQL injection is not possible.
		//if ($stmt = $mysqli->prepare("CALL login(:email,:password,:id,:username,:success,:locked)")) {
		//	$stmt->bindParam(':email', $email, PDO::PARAM_STR, 50);
		//	$stmt->bindParam(':password', $password, PDO::PARAM_STR, 128);
		//	$stmt->bindParam(':id', $user_id, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT, 11);
		//	$stmt->bindParam(':username', $username, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT, 30);
		//	$stmt->bindParam(':success', $success, PDO::PARAM_BOOL|PDO::PARAM_INPUT_OUTPUT, 1);
		//	$stmt->bindParam(':locked', $locked, PDO::PARAM_BOOL|PDO::PARAM_INPUT_OUTPUT, 1);
		//	$stmt->execute(); // Execute the prepared query.

		//$hash = password_hash($password, PASSWORD_DEFAULT, array("cost" => 10));
		
		if($stmt = $mysqli->prepare("CALL `login_getinfo`(?,@id,@username,@pass)")) {
			$stmt->bind_param('s', $email);
			if($stmt->execute()) { // Execute the prepared query.
				$stmt->free_result();
				if($stmt = $mysqli->prepare("SELECT @id, @username, @pass")) {
					if($stmt->execute()) { // Execute the prepared query.
						$stmt->store_result();
						$stmt->bind_result($user_id, $username, $hash); // get variables from result.
						$stmt->fetch();

						if($user_id != null) {
							// We check if the account is locked from too many login attempts
							//if(!$locked) {
								if(password_verify($password, $hash)) {
									// Password is correct!

									if (password_needs_rehash($hash, PASSWORD_DEFAULT, array("cost" => 10))) {
										$hash = password_hash($password, PASSWORD_DEFAULT, array("cost" => 10));
										/* Store new hash in db */
										
										if($stmt = $mysqli->prepare("CALL `login_rehash`(?,?)")) {
											$stmt->bind_param('is', $user_id, $hash);
											
											if($stmt->execute()) { // Execute the prepared query.
												$stmt->free_result();
											}
										}
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
						// query execute failed?
						return false;
					}
				} else {
					// query failed prepare?
					return false;
				}
			} else {
				// query execute failed?
				return false;
			}
		} else {
			// query failed prepare?
			return false;
		}

		// Using prepared Statements means that SQL injection is not possible.
		/*if ($stmt = $mysqli->prepare("SELECT id, username, password, salt FROM members WHERE email = ? LIMIT 1")) { 
			$stmt->bind_param('s', $email); // Bind "$email" to parameter.
			$stmt->execute(); // Execute the prepared query.
			$stmt->store_result();
			$stmt->bind_result($user_id, $username, $db_password, $salt); // get variables from result.
			$stmt->fetch();
			$password = hash('sha512', $password.$salt); // hash the password with the unique salt.
	 
			if($stmt->num_rows == 1) { // If the user exists
				// We check if the account is locked from too many login attempts
				if($this->checkbrute($user_id, $mysqli) == true) { 
					// Account is locked
					// Send an email to user saying their account is locked
					return false;
				} else {
					if($db_password == $password) { // Check if the password in the database matches the password the user submitted. 
						// Password is correct!

						$user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
	 
						$user_id = preg_replace("/[^0-9]+/", "", $user_id); // XSS protection as we might print this value
						$_SESSION['user_id'] = $user_id; 
						$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // XSS protection as we might print this value
						$_SESSION['username'] = $username;
						$_SESSION['login_string'] = hash('sha512', $password.$user_browser);
						
						//$this->regenerateSession();
						
						// Login successful.
						return true;    
					} else {
						// Password is not correct
						// We record this attempt in the database
						$now = time();
						$mysqli->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
						return false;
					}
				}
			} else {
				// No user exists. 
				return false;
			}
		}*/
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
	 
			//if($stmt = $mysqli->prepare("CALL `login_gethash`(?,@pass)")) {
			//	$stmt->bind_param('i', $user_id);
			//	if($stmt->execute()) { // Execute the prepared query.
			//		$stmt->free_result();
			//		if($stmt = $mysqli->prepare("SELECT @pass")) {
			//			if($stmt->execute()) { // Execute the prepared query.
			//				$stmt->store_result();
			//				$stmt->bind_result($hash); // get variables from result.
			//				$stmt->fetch();
			//				
			//				if($hash != null) {
			//					$login_check = hash('sha512', $hash.$user_browser);
								$login_check = hash('sha512', $user_browser);
								if($login_check == $login_string) {
									// Logged In!!!!
									return true;
								} else {
									// Not logged in
									return false;
								}
			//				} else {
			//					// Not logged in
			//					return false;
			//				}
			//			} else {
			//				// Not logged in
			//				return false;
			//			}
			//		} else {
			//			// Not logged in
			//			return false;
			//		}
			//	} else {
			//		// Not logged in
			//		return false;
			//	}
			//} else {
			//	// Not logged in
			//	return false;
			//}
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