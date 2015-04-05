<?php

//DbChangeInStateNotification.php
//Class used to interact with the changeinstatenotifications table in our database.
//by: zach smith
//last edited: 4/4/15

require $_SERVER['DOCUMENT_ROOT']."/../libraries/password-compat/lib/password.php";

class DbChangeInStateNotification {
	
	private $dbhandle;
	
	public function __construct(){
        $this->connectdb();
    }

	//connect to the database
	public function connectdb(){			
		$this->dbhandle = mysql_connect('localhost', 'root', '');
					
		$selected = mysql_select_db("Account", $this->dbhandle);
	}

	//inserts a new notification to the table
	public function addNotification($projectID){
		$projectID = mysql_real_escape_string($projectID);
		$sql = "INSERT INTO changeInStateNotification (projectID)
		VALUES ('$projectID')";
		mysql_query($sql);		
	}

	//delete notification from table
	public function deleteNotification($futureID){		
		$futureID = mysql_real_escape_string($futureID);
		$sql = "DELETE FROM changeInStateNotification WHERE changeInStateNotificationID = '$changeInStateNotificationID'";
		mysql_query($sql);
	}	

	//gets the changeInStateNotificationID by using the project id
	public function getchangeInStateNotificationID($projectID){
		$projectID = mysql_real_escape_string($projectID);
		$sql = "SELECT changeInStateNotificationID FROM changeInStateNotification WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	//close the connection
	public function disconnectdb(){
		mysql_close($dbhandle);
	}

	public function _destruct(){
		$this->disconnectdb();
	}

}

?>