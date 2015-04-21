<?php

//DbFutureNotifications.php
//Class used to interact with the futurenotifications table in our database.
//by: zach smith
//last edited: 4/4/15

class DbFutureNotification {
	
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

	//inserts a new notification to the table
	public function addNotification($projectID, $futureDate){
		$createdDate = date('Y-m-d H:i:s', strtotime('now'));
		$sql = "INSERT INTO futureNotification (projectID, futureDate, createdDate)
		VALUES ('$projectID', '$futureDate', '$createdDate')";
		mysql_query($sql);		
	}

	//delete notification from table
	public function deleteNotification($futureID){		
		$sql = "DELETE FROM futureNotification WHERE futureID = '$futureID'";
		mysql_query($sql);
	}

	//changes the futureDate in the table
	public function changeName($futureID, $futureDate){
		$sql = "UPDATE futureNotification SET futureDate = '$futureDate' WHERE futureID = '$futureID'";
		mysql_query($sql);
	}

	//gets the futureID by using the project id
	public function getfutureID($projectID){
		$sql = "SELECT futureID FROM futureNotification WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	public function getFutureDate($futureID){
		$sql = "SELECT futureDate FROM futureNotification WHERE futureID = '$futureID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	public function getCreatedDate($futureID){
		$sql = "SELECT createdDate FROM futureNotification WHERE futureID = '$futureID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
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