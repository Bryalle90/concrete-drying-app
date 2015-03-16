//Project.php
//Class used to interact with the project table in our database.
//by: zach smith
//last edited: 3/16/15


class Weather {

	//These 3 variables will need to be changed to specific case
	private $hostname, $username, $password, $dbhandle;

	public function _construct($hostname, $username, $password){
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
	}

	//connect to the database
	public function connectdb(){			
		$dbhandle = mysql_connect($hostname, $username, $password)
			OR die("Unable to connect to MySQL");
		//output to confirm connection
		echo "Connected to MySQL<br>";
		
		$selected = mysql_select_db("Account", $dbhandle)
			OR die("Could not select");
	}

	//Adds to project table then adds to project lookup table
	public function addToProjectTable($projectID, $projectName, $userID, $zipcode, $timeZone){
		//project table
		$sql = "INSERT INTO project (projectID, projectName, userID, zipcode, timeZone)
		VALUES ($projectID, $projectName, $userID, $zipcode, $timeZone)";
		$dbhandle->query($sql);

		//userProjectLookup table
		$sql = "INSERT INTO userProjectLookup (userID, projectID)
		VALUES ($userID, $projectID)";
		$dbhandle->query($sql);
	}

	//Adds to additional user to project lookup table
	public function addUserToSharedProject($projectID, $userID){
		$sql = "INSERT INTO userProjectLookup (userID, projectID)
		VALUES ($userID, $projectID)";
		$dbhandle->query($sql);
	}

	//Adds to additional user to project lookup table
	public function changeProjectName($projectID, $projectName){
		$sql = "UPDATE project SET projectName = $projectName WHERE projectID = $projectID";
		$dbhandle->query($sql);
	}

	//Deletes project from the lookuptable if it is shared, but deletes the project from both the 
	//lookuptable and the project table
	public function deleteProject($projectID, $userID){
		$sql = "DELETE FROM userProjectLookup WHERE projectID = $projectID AND userID = $userID";
		$dbhandle->query($sql);

		//ADD LOGIC TO DELETE PROJECT TABLE ENTRY IF NOT SHARED
	}

	//Return the users projects ids
	public function getUserProjects($userID){
		$sql = "SELECT projectID FROM userProjectLookup WHERE userID = $userID";
		$result = $dphandle->query($sql);
		return $results;
	}

	public function getName($projectID){
		$sql = "SELECT name FROM project WHERE projectID = $projectID";
		$result = $dphandle->query($sql);
		return $results;
	}

	public function getZipcode($projectID){
		$sql = "SELECT zipcode FROM project WHERE projectID = $projectID";
		$result = $dphandle->query($sql);
		return $results;
	}

	public function getTimeZone($projectID){
		$sql = "SELECT timeZone FROM project WHERE projectID = $projectID";
		$result = $dphandle->query($sql);
		return $results;
	}

	//close the connection
	public function _destruct(){
		mysql_close($dbhandle);
	}

}
