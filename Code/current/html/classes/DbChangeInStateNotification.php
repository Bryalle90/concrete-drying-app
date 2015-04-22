<?php

//DbChangeInStateNotification.php
//Class used to interact with the changeinstatenotifications table in our database.
//by: zach smith
//last edited: 4/24/15

class DbChangeInStateNotification {
	
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
	public function addNotification($projectID, $seriesID, $time, $currentZone, $notifyZone){
		$createdDate = date('Y-m-d H:i:s', strtotime('now'));
		$sql = "INSERT INTO changeInStateNotification (projectID, seriesID, time, currentZone, notifyZone, createdDate)
		VALUES ('$projectID', '$seriesID', '$time', '$currentZone', '$notifyZone', '$createdDate')";
		mysql_query($sql);		
	}

	//delete notification from table
	public function deleteNotification($changeInStateNotificationID){
		$sql = "DELETE FROM changeInStateNotification WHERE changeInStateNotificationID = '$changeInStateNotificationID'";
		mysql_query($sql);
	}	

	//gets the changeInStateNotificationID by using the project id
	public function getchangeInStateNotificationID($projectID){
		$sql = "SELECT changeInStateNotificationID FROM changeInStateNotification WHERE projectID = '$projectID'";
		$result = mysql_query($sql);	
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['changeInStateNotificationID'];
		}
		return $array;
		}
		
	//Gets notifications for a project
	public function getNotif($projectID){
		$notifIDarray = $this->getchangeInStateNotificationID($projectID);
		if($notifIDarray == Null)
			return Null;
		foreach($notifIDarray as $pID){
			$projects[$pID]['seriesID'] = $this->getSeries($pID);
			$projects[$pID]['time'] = $this->getTime($pID);
			$projects[$pID]['currentZone'] = $this->getCurrentZone($pID);
			$projects[$pID]['notifyZone'] = $this->getNotifyZone($pID);
		}
		return $projects;
	}
	
	public function getSeries($changeInStateNotificationID){
		$sql = "SELECT seriesID FROM changeInStateNotification WHERE changeInStateNotificationID = '$changeInStateNotificationID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
		public function getTime($changeInStateNotificationID){
		$sql = "SELECT time FROM changeInStateNotification WHERE changeInStateNotificationID = '$changeInStateNotificationID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	public function getCurrentZone($changeInStateNotificationID){
		$sql = "SELECT currentZone FROM changeInStateNotification WHERE changeInStateNotificationID = '$changeInStateNotificationID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	public function getNotifyZone($changeInStateNotificationID){
		$sql = "SELECT notifyZone FROM changeInStateNotification WHERE changeInStateNotificationID = '$changeInStateNotificationID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	public function getCreatedDate($projectID, $seriesID){
		$sql = "SELECT createdDate FROM changeInStateNotification WHERE projectID = '$projectID' AND seriesID = '$seriesID'";
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