<?php

require $_SERVER['DOCUMENT_ROOT'].'/libraries/password-compat/lib/password.php';

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
		$hashedPass = $this->hashPass($userPass);
		$time = date('Y-m-d H:i:s', strtotime('now'));
		$code = $this->createCode();

		$sql = "INSERT INTO user (name, email, userPass, code, createdTime, isAdmin, isValidated)
		VALUES ('$name', '$email', '$hashedPass', '$code', '$time', '$isAdmin', 0)";
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

		$sql = "UPDATE user SET code = '$code' WHERE userID = '$userID'";
		mysql_query($sql);

		return($code);
	}

	public function getCode($userID){
		$sql = "SELECT code FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);

		if (!$result || !mysql_num_rows($result))
			return(Null);

		$result = mysql_result($result, 0);

		return $result;
	}

	public function checkCode($code){
		$sql = "SELECT userID FROM user WHERE code = '$code'";
		$result = mysql_query($sql);

		if (!$result || !mysql_num_rows($result))
			return(Null);

		$userID = mysql_result($result, 0);
		return $userID;
	}

	public function removeCode($userID){
		$sql = "UPDATE user SET code = '' WHERE userID = '$userID'";
		mysql_query($sql);
	}

	public function validate($userID){
		$sql = "UPDATE user SET isValidated = 1 WHERE userID = '$userID'";
		$this->removeCode($userID);
		mysql_query($sql);
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
		$sql = "UPDATE user SET email = '$email' WHERE userID = '$userID'";
		mysql_query($sql);
	}

	//changes the users password in the table
	public function changePassword($userID, $password){
		$hashedPass = $this->hashPass($password);
		$sql = "UPDATE user SET userPass = '$hashedPass' WHERE userID = '$userID'";
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
	
	private function hashPass($pass){
		return(password_hash($pass, PASSWORD_BCRYPT, array("cost" => 10)));
	}
	
	private function verifyPass($pass, $hash){
		return(password_verify($pass, $hash));
	}

}

?>
