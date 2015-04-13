<?php

class DbUserVerification {
	
	private $dbhandle;

	private $HOST = '127.0.0.1';
	private $ACCOUNT = 'root';
	private $PASSWORD = '';
	private $DATABASE = 'plasticcracks';

	private function checkUser($userID){
		$sql = "SELECT code FROM userVerification WHERE userID = '$userID'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
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
	public function addUser($userID){
		$code = $this->checkUser($userID);
		if(!$code){
			$sql = "INSERT INTO userVerification (userID, code)
			VALUES ('$userID', '')";
			mysql_query($sql);
		}
		$code = $this->changeCode($userID);
		return($code);
	}

	public function removeUser($userID){		
		$sql = "DELETE FROM userVerification WHERE userID = '$userID'";
		mysql_query($sql);
	}

	public function checkCode($code){
		$sql = "SELECT userID FROM userVerification WHERE code = '$code'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	//changes the users email in the table
	public function changeCode($userID){
		$code = md5(uniqid(rand(), true));
		$sql = "UPDATE userVerification SET code = '$code' WHERE userID = '$userID'";
		mysql_query($sql);
		return($code);
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
