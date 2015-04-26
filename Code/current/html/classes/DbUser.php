<?php

$root = $_SERVER['DOCUMENT_ROOT'] ? $_SERVER['DOCUMENT_ROOT'] : '/home/s002457/html';
require $root.'/libraries/password-compat/lib/password.php';

class DbUser {
	
	private $dbhandle;

	private $HOST = '127.0.0.1:3666';
	private $ACCOUNT = 'root';
	private $PASSWORD = 'a1b2c3';
	private $DATABASE = 'plasticcracks';

	public function __construct(){
		$this->connectdb();
	}

	//connect to the database
	public function connectdb(){
		$this->dbhandle = mysql_connect($this->HOST, $this->ACCOUNT, $this->PASSWORD);
					
		$selected = mysql_select_db($this->DATABASE, $this->dbhandle);
	}

	//inserts a new user to the table
	public function addUser($name, $email, $userPass, $isAdmin){
		$name = mysql_real_escape_string($name);
		$email = mysql_real_escape_string($email);
		$hashedPass = $this->createHash($userPass);
		$email = strtolower($email);
		date_default_timezone_set('America/New_York');
		$time = date('Y-m-d H:i:s', strtotime('now'));

		$sql = "INSERT INTO user (name, email, userPass, code, forgotCode, createdTime, isAdmin, isValidated, seenNotifMsg, forceNewPass)
		VALUES ('$name', '$email', '$hashedPass', NULL, NULL, '$time', '$isAdmin', 0, 0, 0)";
		mysql_query($sql);

		$id = $this->isUser($email);
		return($id);
	}

	//inserts a new code for a user
	private function createCode(){
		$code = md5(uniqid(rand(), true));
		return($code);
	}

	//changes the users email in the table
	public function changeCode($userID){
		$code = $this->createCode();
		$hashedCode = $this->createHash($code);
		$sql = "UPDATE user SET code = '$hashedCode' WHERE userID = '$userID'";
		mysql_query($sql);

		return($code);
	}

	private function getCode($userID){
		$sql = "SELECT code FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);

		if (!$result || !mysql_num_rows($result))
			return(Null);

		$result = mysql_result($result, 0);

		return $result;
	}

	public function checkCode($email, $code){
		$email = strtolower($email);
		$userID = $this->isUser($email);
		
		if($userID && $this->verifyHash($code, $this->getCode($userID))){
			return $userID;
		}
		return(Null);
	}

	public function removeCode($userID){
		$sql = "UPDATE user SET code = NULL WHERE userID = '$userID'";
		mysql_query($sql);
	}

	public function validate($userID){
		$this->removeCode($userID);
		$sql = "UPDATE user SET isValidated = 1 WHERE userID = '$userID'";
		mysql_query($sql);
	}
	
	public function randomPassword($len) {
		$charPool = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($charPool) - 1; //put the length -1 in cache
		for ($i = 0; $i < $len; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $charPool[$n];
		}
		return implode($pass); //turn the array into a string
	}
	
	public function resetPass($userID) {
		$newPass = $this->randomPassword(10);
		$newHash = $this->createHash($newPass);
		
		// save new hash in user table
		$sql = "UPDATE user SET userPass = '$newHash' WHERE userID = '$userID'";
		mysql_query($sql);

		$sql = "UPDATE user SET forgotCode = NULL WHERE userID = '$userID'";
		mysql_query($sql);
		
		// make sure the user must reset their password when logging in
		$sql = "UPDATE user SET forceNewPass = 1 WHERE userID = '$userID'";
		mysql_query($sql);
		
		return $newPass;
	}
	
	public function createForgotCode($userID){
		$code = $this->createCode();
		$hashedCode = $this->createHash($code);
		$sql = "UPDATE user SET forgotCode = '$hashedCode' WHERE userID = '$userID'";
		mysql_query($sql);

		return($code);
	}
	
	public function getForceNewPass($userID){
		$sql = "SELECT forceNewPass FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	private function getForgotCode($userID){
		$sql = "SELECT forgotCode FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);

		if (!$result || !mysql_num_rows($result))
			return(Null);

		$result = mysql_result($result, 0);

		return $result;
	}

	public function checkForgotCode($email, $code){
		$email = strtolower($email);
		$userID = $this->isUser($email);
		
		if($userID && $this->verifyHash($code, $this->getForgotCode($userID))){
			return $userID;
		}
		return(Null);
	}
	
	public function seenNotificationMsg($userID){
		$sql = "UPDATE user SET seenNotifMsg = 1 WHERE userID = '$userID'";
		mysql_query($sql);
	}
	
	public function unSeenNotificationMsg($userID){
		$sql = "UPDATE user SET seenNotifMsg = 0 WHERE userID = '$userID'";
		mysql_query($sql);
	}
	
	public function getSeenNotificationMsg($userID){
		$sql = "SELECT seenNotifMsg FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	//delete user from table
	public function deleteUser($userID){		
		$sql = "DELETE FROM user WHERE userID = '$userID'";
		mysql_query($sql);
	}

	//changes the users name in the table
	public function changeName($userID, $name){
		$sql = "UPDATE user SET name = '$name' WHERE userID = '$userID'";
		mysql_query($sql);
	}

	//changes the users email in the table
	public function changeEmail($userID, $email){
		$email = strtolower($email);
		$sql = "UPDATE user SET email = '$email' WHERE userID = '$userID'";
		mysql_query($sql);
	}

	//changes the users password in the table
	public function changePassword($userID, $password){
		$hashedPass = $this->createHash($password);
		$sql = "UPDATE user SET userPass = '$hashedPass' WHERE userID = '$userID'";
		mysql_query($sql);

		$sql = "UPDATE user SET forceNewPass = 0 WHERE userID = '$userID'";
		mysql_query($sql);
	}

	//changes the users to an admin
	public function makeAdmin($userID){
		$sql = "UPDATE user SET isAdmin = 'y' WHERE userID = '$userID'";
		mysql_query($sql);
	}	

	//checks to see if user is admin and return true of false
	public function isUserAdmin($userID){
		$sql = "SELECT isAdmin FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);

		if($result == 'y' || $result == 'Y'){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function getName($userID){
		$sql = "SELECT name FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getCurrentNumberOfNotifications($userID){
		$sql = "SELECT currentNumberOfNotifications FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getEmail($userID){
		$sql = "SELECT email FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getUserPass($userID){
		$sql = "SELECT userPass FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getIsAdmin($userID){
		$sql = "SELECT isAdmin FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getIsValidated($userID){
		$sql = "SELECT isValidated FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	public function verifyLogin($email, $userPass){
		$email = strtolower($email);
		$id = $this->isUser($email);
		if($id != Null){
			$hash = $this->getUserPass($id);
			if($this->verifyHash($userPass, $hash))
				return ($id);
		}
		return(Null);
	}

	//checks to see if email exists and return userID if they do, returns NULL if not
	public function isUser($email){
		$email = strtolower($email);
		$sql = "SELECT userID FROM user WHERE email = '$email'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$id = mysql_result($result, 0);
		
		return($id);
	}
	
	private function createHash($str){
		return(password_hash($str, PASSWORD_BCRYPT, array("cost" => 10)));
	}
	
	private function verifyHash($str, $hash){
		return(password_verify($str, $hash));
	}

}

?>
