<?php

require $_SERVER['DOCUMENT_ROOT'].'/libraries/password-compat/lib/password.php';

class DbUser {
	
	private $dbhandle;

	private $HOST = '127.0.0.1:3666';
	private $ACCOUNT = 'root';
	private $PASSWORD = 'a1b2c3';
	private $DATABASE = 'plasticcracks';
	
	private function hashPass($pass){
		return(password_hash($pass, PASSWORD_BCRYPT, array("cost" => 10)));
	}
	
	private function verifyPass($pass, $hash){
		return(password_verify($pass, $hash));
	}

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
		$userPass = mysql_real_escape_string($userPass);
		$isAdmin = mysql_real_escape_string($isAdmin);
		$hashedPass = $this->hashPass($userPass);
		$sql = "INSERT INTO user (name, email, userPass, isAdmin, isValidated)
		VALUES ('$name', '$email', '$hashedPass', '$isAdmin', 0)";
		mysql_query($sql);		
		return($this->isUser($email));
	}

	//delete user from table
	public function deleteUser($userID){
		$userID = mysql_real_escape_string($userID);		
		$sql = "DELETE FROM user WHERE userID = '$userID'";
		mysql_query($sql);
	}

	//changes the users name in the table
	public function changeName($userID, $name){
		$userID = mysql_real_escape_string($userID);
		$name = mysql_real_escape_string($name);
		$sql = "UPDATE user SET name = '$name' WHERE userID = '$userID'";
		mysql_query($sql);
	}

	//changes the users email in the table
	public function changeEmail($userID, $email){
		$userID = mysql_real_escape_string($userID);
		$email = mysql_real_escape_string($email);
		$sql = "UPDATE user SET email = '$email' WHERE userID = '$userID'";
		mysql_query($sql);
	}

	//changes the users password in the table
	public function changePassword($userID, $password){
		$userID = mysql_real_escape_string($userID);
		$password = mysql_real_escape_string($password);
		$hashedPass = $this->hashPass($password);
		$sql = "UPDATE user SET userPass = '$hashedPass' WHERE userID = '$userID'";
		mysql_query($sql);
	}	

	public function validate($userID){
		$userID = mysql_real_escape_string($userID);
		$sql = "UPDATE user SET isValidated = 1 WHERE userID = '$userID'";
		mysql_query($sql);
	}
	

	//checks to see if user is admin and return true of false
	public function isUserAdmin($userID){
		$userID = mysql_real_escape_string($userID);
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
		$userID = mysql_real_escape_string($userID);
		$sql = "SELECT name FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getCurrentNumberOfNotifications($userID){
		$userID = mysql_real_escape_string($userID);
		$sql = "SELECT currentNumberOfNotifications FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getEmail($userID){
		$userID = mysql_real_escape_string($userID);
		$sql = "SELECT email FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getUserPass($userID){
		$userID = mysql_real_escape_string($userID);
		$sql = "SELECT userPass FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getIsAdmin($userID){
		$userID = mysql_real_escape_string($userID);
		$sql = "SELECT isAdmin FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getIsValidated($userID){
		$userID = mysql_real_escape_string($userID);
		$sql = "SELECT isValidated FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	public function verifyLogin($email, $userPass){
		$email = mysql_real_escape_string($email);
		$userPass = mysql_real_escape_string($userPass);
		$id = $this->isUser($email);
		if($id != Null){
			$hash = $this->getUserPass($id);
			if($this->verifyPass($userPass, $hash))
				return ($id);
		}
		return(Null);
	}

	//checks to see if email exists and return userID if they do, returns NULL if not
	public function isUser($email){
		$email = mysql_real_escape_string($email);
		$sql = "SELECT userID FROM user WHERE email = '$email'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$id = mysql_result($result, 0);
		
		return($id);
	}

	//close the connection
	public function disconnectdb(){
		mysql_close($this->dbhandle);
	}

	public function _destruct(){
		$this->disconnectdb();
	}

}

?>
