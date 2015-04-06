<?php

//DbFutureNotifications.php
//Class used to interact with the futurenotifications table in our database.
//by: zach smith
//last edited: 4/4/15

require $_SERVER['DOCUMENT_ROOT']."/../libraries/password-compat/lib/password.php";

class DbFutureNotification {
	
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
	public function addNotification($projectID, $futureDate){
		$projectID = mysql_real_escape_string($projectID);
		$futureDate = mysql_real_escape_string($futureDate);
		$sql = "INSERT INTO futureNotification (projectID, futureDate)
		VALUES ('$projectID', '$futureDate')";
		mysql_query($sql);		
	}

	//delete notification from table
	public function deleteNotification($futureID){		
		$futureID = mysql_real_escape_string($futureID);
		$sql = "DELETE FROM futureNotification WHERE futureID = '$futureID'";
		mysql_query($sql);
	}

	//changes the futureDate in the table
	public function changeName($futureID, $futureDate){
		$futureID = mysql_real_escape_string($futureID);
		$futureDate = mysql_real_escape_string$futureDate);
		$sql = "UPDATE futureNotification SET futureDate = '$futureDate' WHERE futureID = '$futureID'";
		mysql_query($sql);
	}

	//gets the futureID by using the project id
	public function getfutureID($projectID){
		$projectID = mysql_real_escape_string($projectID);
		$sql = "SELECT futureID FROM futureNotification WHERE projectID = '$projectID'";
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