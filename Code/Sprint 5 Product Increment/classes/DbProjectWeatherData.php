<?php

//DbProjectWeatherData.php
//Class used to interact with the projectweatherdata table in our database.
//by: zach smith
//last edited: 4/4/15

require $_SERVER['DOCUMENT_ROOT']."/../libraries/password-compat/lib/password.php";

class DbProjectWeatherData {
	
	private $dbhandle;
	
	public function __construct(){
        $this->connectdb();
    }

	//connect to the database
	public function connectdb(){			
		$this->dbhandle = mysql_connect('localhost', 'root', '');
					
		$selected = mysql_select_db("Account", $this->dbhandle);
	}

	//inserts a new weather data entry to the table
	public function addEntry($changeInStateNotificationID, $currentRisk, $time){
		$changeInStateNotificationID = mysql_real_escape_string($changeInStateNotificationID);
		$currentRisk = mysql_real_escape_string($currentRisk);
		$time = mysql_real_escape_string($time);
		$sql = "INSERT INTO projectWeatherData (changeInStateNotificationID, currentRisk, time)
		VALUES ('$changeInStateNotificationID', '$currentRisk', '$time')";
		mysql_query($sql);		
	}

	//delete weather data entry from table
	public function deleteEntry($changeInStateNotificationID, $time){
		$changeInStateNotificationID = mysql_real_escape_string($changeInStateNotificationID);
		$time = mysql_real_escape_string($time);		
		$sql = "DELETE FROM projectWeatherData WHERE changeInStateNotificationID = '$changeInStateNotificationID' AND time = '$time'";
		mysql_query($sql);
	}	

	//gets the currentRisk using changeInStateNotificationID and time
	public function getCurretRisk($changeInStateNotificationID, $time){
		$changeInStateNotificationID = mysql_real_escape_string($changeInStateNotificationID);
		$time = mysql_real_escape_string($time);
		$sql = "SELECT currentRisk FROM projectWeatherData WHERE changeInStateNotificationID = '$changeInStateNotificationID' AND time = '$time'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//changes the currentRisk
	public function changeCurrentRisk($changeInStateNotificationID, $time, $currentRisk){
		$changeInStateNotificationID = mysql_real_escape_string($changeInStateNotificationID);
		$currentRisk = mysql_real_escape_string($currentRisk);
		$time = mysql_real_escape_string($time);
		$sql = "UPDATE projectWeatherData SET currentRisk = '$currentRisk' WHERE changeInStateNotificationID = '$changeInStateNotificationID' AND time = '$time'";
		mysql_query($sql);
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