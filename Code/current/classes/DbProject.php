<?php

//Project.php
//Class used to interact with the project table in our database.
//by: zach smith
//last edited: 3/23/15

class DbProject {

	//These 3 variables will need to be changed to specific case
	private $dbhandle;

	public function __construct(){
        $this->connectdb();
    }

	//connect to the database
	public function connectdb(){			
		$this->dbhandle = mysql_connect('localhost', 'root', '');
					
		$selected = mysql_select_db("Account", $this->dbhandle);
	}
	
	//Adds to project table then adds to project lookup table
	public function addToProjectTable($projectName, $ownerID, $zipcode, $time, $unit){
        
		//project table
		$sql = "INSERT INTO project (projectName, ownerID, zipcode, endTime, unit)
		VALUES ('$projectName', '$ownerID', '$zipcode', '$time', '$unit')";
		mysql_query($sql);	
		
		$sql = "SELECT projectID FROM project WHERE projectName = '$projectName' AND ownerID = '$ownerID'";
		$result = mysql_query($sql);
		$projectID = mysql_result($result, 0);

		//userProjectLookup table
		$this->addUserToSharedProject($projectID, $ownerID);	
	}

	//Adds to additional user to project lookup table
	public function addUserToSharedProject($projectID, $userID){
		$sql = "INSERT INTO userProjectLookup (userID, projectID)
		VALUES ('$userID', '$projectID')";
		mysql_query($sql);
	}

	public function changeProjectName($projectID, $projectName){
		$sql = "UPDATE project SET projectName = '$projectName' WHERE projectID = '$projectID'";
		mysql_query($sql);
	}

	public function changeProjectZip($projectID, $zip){
		$sql = "UPDATE project SET zipcode = '$zip' WHERE projectID = '$projectID'";
		mysql_query($sql);
	}

	public function changeProjectUnit($projectID, $unit){
		$sql = "UPDATE project SET unit = '$unit' WHERE projectID = '$projectID'";
		mysql_query($sql);
	}

	public function changeProjectTime($projectID, $time){
		$sql = "UPDATE project SET endTime = '$time' WHERE projectID = '$projectID'";
		mysql_query($sql);
	}

	//Deletes project from the lookuptable if it is shared, but deletes the project from both the 
	//lookuptable and the project table if user owns project
	public function deleteProject($projectID, $userID){
		$query = "DELETE FROM userProjectLookup WHERE projectID = '$projectID' AND ownerID = '$ownerID'";
		mysql_query($query);

		//ADD LOGIC TO DELETE PROJECT TABLE ENTRY IF NOT SHARED
		$query = "SELECT ownerID FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($query);
		$ownerID = mysql_result($result, 0);
		if($ownerID == $userID){
			$sql = "DELETE FROM project WHERE projectID = '$projectID'";
			mysql_query($sql);
			
			$sql = "DELETE FROM userProjectLookup WHERE projectID = '$projectID'";
			mysql_query($sql);
		}
	}
    
    public function getUsersOfProject($projectID){
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
        $projectIDarray = $this->getProjectIDs($userID);
        if($projectIDarray == Null)
            return Null;
        foreach($projectIDarray as $pID){
            $projects[$pID]['zip'] = $this->getZipcode($pID);
            $projects[$pID]['name'] = $this->getName($pID);
            $projects[$pID]['ownerID'] = $this->getOwner($pID);
            $projects[$pID]['userIDs'] = $this->getUsersOfProject($pID);
        }
        return $projects;
    }

	//Return the users projects ids
	public function getProjectIDs($userID){
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

	public function getOwner($projectID){
		$sql = "SELECT ownerID FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getUnit($projectID){
		$sql = "SELECT unit FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getTime($projectID){
		$sql = "SELECT endTime FROM project WHERE projectID = '$projectID'";
		$result = mysql_query($sql);
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