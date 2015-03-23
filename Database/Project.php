<?php

//Project.php
//Class used to interact with the project table in our database.
//by: zach smith
//last edited: 3/23/15

class Project {

	//These 3 variables will need to be changed to specific case
	private $dbhandle;

	public function _construct(){}

	//connect to the database
	public function connectdb(){			
		$this->dbhandle = mysql_connect('localhost', 'root', '');
					
		$selected = mysql_select_db("Account", $this->dbhandle);
	}
	
	//Adds to project table then adds to project lookup table
	public function addToProjectTable($projectName, $userID, $zipcode, $timeZone){
		//project table
		$sql = "INSERT INTO project (projectName, userID, zipcode, timeZone)
		VALUES ('$projectName', '$userID', '$zipcode', '$timeZone')";
		mysql_query($sql);	
		
		$sql = "SELECT projectID FROM project WHERE projectName = '$projectName' AND userID = '$userID'";
		$projectID = mysql_query($sql);
		$projectID = mysql_result($projectID, 0);

		//userProjectLookup table
		$this->addUserToSharedProject($projectID, $userID);	
	}

	//Adds to additional user to project lookup table
	public function addUserToSharedProject($projectID, $userID){
		$sql = "INSERT INTO userProjectLookup (userID, projectID)
		VALUES ('$userID', '$projectID')";
		mysql_query($sql);
	}

	//Adds to additional user to project lookup table
	public function changeProjectName($projectID, $projectName){
		$sql = "UPDATE project SET projectName = '$projectName' WHERE projectID = '$projectID'";
		mysql_query($sql);
	}

	//Deletes project from the lookuptable if it is shared, but deletes the project from both the 
	//lookuptable and the project table if user owns project
	public function deleteProject($projectID, $userID){
		$sql = "DELETE FROM userProjectLookup WHERE projectID = '$projectID' AND userID = '$userID'";
		mysql_query($sql);

		//ADD LOGIC TO DELETE PROJECT TABLE ENTRY IF NOT SHARED
	}

	//Return the users projects ids
	public function getUserProjects($userID){
		$sql = "SELECT projectID FROM userProjectLookup WHERE userID = '$userID'";
		$result = mysql_query($sql);		
		return $result;
	}

	public function getName($projectID){
		$sql = "SELECT projectName FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getZipcode($projectID){
		$sql = "SELECT zipcode FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getTimeZone($projectID){
		$sql = "SELECT timeZone FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}

	//close the connection
	public function _destruct(){
		mysql_close($dbhandle);
	}

}

?>
