<?php

class DbProject {

	//These 3 variables will need to be changed to specific case
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
	
	//Adds to project table then adds to project lookup table
	public function addToProjectTable($projectName, $location, $ownerID, $zipcode, $time, $unit){
		$projectName = mysql_real_escape_string($projectName);
		$location = mysql_real_escape_string($location);
		$ownerID = mysql_real_escape_string($ownerID);
		$zipcode = mysql_real_escape_string($zipcode);
		$time = mysql_real_escape_string($time);
		$unit = mysql_real_escape_string($unit);
		
		//project table
		$sql = "INSERT INTO project (projectName, location, ownerID, zipcode, unit, addedTime)
		VALUES ('$projectName', '$location', '$ownerID', '$zipcode', '$unit', '$time')";
		$result = mysql_query($sql);
		
		$sql = "SELECT projectID FROM project WHERE projectName = '$projectName' AND ownerID = '$ownerID'";
		$result = mysql_query($sql);
		$numRows = mysql_num_rows($result);
		$projectID = mysql_result($result, $numRows-1);

		//userProjectLookup table
		$this->addUserToSharedProject($projectID, $ownerID);	
	}

	//Adds to additional user to project lookup table
	public function addUserToSharedProject($projectID, $userID){
		$projectID = mysql_real_escape_string($projectID);
		$userID = mysql_real_escape_string($userID);
		$sql = "INSERT INTO userProjectLookup (userID, projectID)
		VALUES ('$userID', '$projectID')";
		mysql_query($sql);
	}

	public function changeProjectName($projectID, $projectName){
		$projectID = mysql_real_escape_string($projectID);
		$projectName = mysql_real_escape_string($projectName);
		$sql = "UPDATE project SET projectName = '$projectName' WHERE projectID = '$projectID'";
		mysql_query($sql);
	}

	public function changeProjectZip($projectID, $zip){
		$projectID = mysql_real_escape_string($projectID);
		$zip = mysql_real_escape_string($zip);
		$sql = "UPDATE project SET zipcode = '$zip' WHERE projectID = '$projectID'";
		mysql_query($sql);
	}

	public function changeProjectLocation($projectID, $loc){
		$projectID = mysql_real_escape_string($projectID);
		$loc = mysql_real_escape_string($loc);
		$sql = "UPDATE project SET location = '$loc' WHERE projectID = '$projectID'";
		mysql_query($sql);
	}

	public function changeProjectUnit($projectID, $unit){
		$projectID = mysql_real_escape_string($projectID);
		$unit = mysql_real_escape_string($unit);
		$sql = "UPDATE project SET unit = '$unit' WHERE projectID = '$projectID'";
		mysql_query($sql);
	}

	public function changeProjectTime($projectID, $time){
		$projectID = mysql_real_escape_string($projectID);
		$time = mysql_real_escape_string($time);
		$sql = "UPDATE project SET addedTime = '$time' WHERE projectID = '$projectID'";
		mysql_query($sql);
	}

	//Deletes project from the lookuptable if it is shared, but deletes the project from both the 
	//lookuptable and the project table if user owns project
	public function deleteProject($projectID, $userID){
		$projectID = mysql_real_escape_string($projectID);
		$userID = mysql_real_escape_string($userID);
		$query = "DELETE FROM userProjectLookup WHERE projectID = '$projectID' AND userID = '$userID'";
		mysql_query($query);

		$ownerID = $this->getOwner($projectID);
		if($ownerID == $userID){
			$sql = "DELETE FROM project WHERE projectID = '$projectID'";
			mysql_query($sql);
			
			$sql = "DELETE FROM userProjectLookup WHERE projectID = '$projectID'";
			mysql_query($sql);
		}
	}
	
	public function checkProject($projectID){
		$projectID = mysql_real_escape_string($projectID);
		$query = "SELECT * FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($query);
		
		if (!$result || mysql_num_rows($result) == 0)
			return(false);
		return(true);		 
	}
	
	public function isUserInProject($projectID, $userID){
		$projectID = mysql_real_escape_string($projectID);
		$userID = mysql_real_escape_string($userID);
		$query = "SELECT * FROM userProjectLookup WHERE projectID = '$projectID' AND userID = '$userID'";
		$result = mysql_query($query);
		
		if (!$result || mysql_num_rows($result) == 0)
			return(false);
		return(true);		 
	}
	
	public function getUsersOfProject($projectID){
		$projectID = mysql_real_escape_string($projectID);
		$query = "SELECT * FROM userProjectLookup WHERE projectID = '$projectID'";
		$result = mysql_query($query);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['userID'];
		}
		return $array;
	}
	
	public function getProjects($userID){
		$userID = mysql_real_escape_string($userID);		
		if($projectIDarray == Null)
			return Null;
		foreach($projectIDarray as $pID){
			$projects[$pID]['zip'] = $this->getZipcode($pID);
			$projects[$pID]['name'] = $this->getName($pID);
			$projects[$pID]['location'] = $this->getLocation($pID);
			$projects[$pID]['ownerID'] = $this->getOwner($pID);
			$projects[$pID]['userIDs'] = $this->getUsersOfProject($pID);
			$projects[$pID]['unit'] = $this->getUnit($pID);
		}
		return $projects;
	}

	//Return the users projects ids
	public function getProjectIDs($userID){
		$userID = mysql_real_escape_string($userID);
		$sql = "SELECT * FROM userProjectLookup WHERE userID = '$userID'";
		$result = mysql_query($sql);	
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['projectID'];
		}
		return $array;
	}

	public function getName($projectID){
		$projectID = mysql_real_escape_string($projectID);
		$sql = "SELECT projectName FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getLocation($projectID){
		$projectID = mysql_real_escape_string($projectID);
		$sql = "SELECT location FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getZipcode($projectID){
		$projectID = mysql_real_escape_string($projectID);
		$sql = "SELECT zipcode FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getOwner($projectID){
		$projectID = mysql_real_escape_string($projectID);
		$query = "SELECT ownerID FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($query);
		$ownerID = mysql_result($result, 0);
		return $ownerID;
	}

	public function getUnit($projectID){
		$projectID = mysql_real_escape_string($projectID);
		$sql = "SELECT unit FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getTime($projectID){
		$projectID = mysql_real_escape_string($projectID);
		$sql = "SELECT addedTime FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
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