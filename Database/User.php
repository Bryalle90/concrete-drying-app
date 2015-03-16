<?php

class User {

//User.php
//Class used to interact with the uesr table in our database.
//by: zach smith
//last edited: 3/16/15
	
	private $dbhandle;	

	public function _construct(){
	}

	//connect to the database
	public function connectdb(){			
		$this->dbhandle = mysql_connect('localhost', 'root', '');
					
		$selected = mysql_select_db("Account", $this->dbhandle);
	}

	//inserts a new user to the table
	public function addUser($userID, $name, $currentNumberOfNotifications, $email, $userPass, $isAdmin){
		$sql = "INSERT INTO user (userID, name, currentNumberOfNotifications, email, userPass, isAdmin)
		VALUES ('$userID', '$name', '$currentNumberOfNotifications', '$email', '$userPass', '$isAdmin')";
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
		$sql = "UPDATE user SET userPass = '$password' WHERE userID = '$userID'";
		mysql_query($sql);
	}	
	

	//checks to see if user is admin and return true of false
	public function isUserAdmin($userID){
		$sql = "SELECT isAdmin FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);

		if($result == 'y' || $result == 'Y'){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function getName($userID){
		$sql = "SELECT name FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		return $result;
	}

	public function getCurrentNumberOfNotifications($userID){
		$sql = "SELECT currentNumberOfNotifications FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		return $result;
	}

	public function getEmail($userID){
		$sql = "SELECT email FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		return $result;
	}

	public function getUserPass($userID){
		$sql = "SELECT userPass FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		return $result;
	}

	public function getIsAdmin($userID){
		$sql = "SELECT isAdmin FROM user WHERE userID = '$userID'";
		$result = mysql_query($sql);
		return $result;
	}

	//checks to see if email and password match and return userID if they do, returns NULL if not
	//public function isUser($email, $userPass){
	//	$sql = "SELECT userID FROM user WHERE email = $email AND userPass = $userPass";
	//	$result = $dphandle->query($sql);
	//	
	//	if($result == NULL){
	//		//output for testing
	//		echo "No such email and password combination exist.";
	//		return $result;
	//	} else {
	//		return $result;
	//	}
	//
	//}

	//gets the number of entries in the database(used to set next id)
	//public function count(){
	//	$sql = "SELECT COUNT(*) FROM user";
	//	$result = $dphandle->query($sql);
	//	return $result;
	//}

	//close the connection
	public function _destruct(){
		mysql_close($dbhandle);
	}

}

?>
