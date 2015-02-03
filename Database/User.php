//User.php
//Class used to interact with the uesr table in our database.
//by: zach smith
//last edited: 2/2/15


class User {

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

	//inserts a new user to the table
	public function addUser($userID, $email, $userPass, $isAdmin){
		$sql = "INSERT INTO user (userID, email, userPass, isAdmin)
		VALUES ($userID, $email, $userPass, $isAdmin);

		if($dbhandle->query($sql) === TRUE) {
			//output to confirm insertion
			echo "New user added successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $dbhandle->error;
		}
	}

	//checks to see if email and password match and return userID if they do, returns NULL if not
	public function isUser($email, $userPass){
		$sql = "SELECT userID FROM user WHERE email = $email AND userPass = $userPass;
		$result = $dphandle->query($sql);
		
		if($result == NULL){
			//output for testing
			echo "No such email and password combination exist.";
			return $result;
		} else {
			return $result;
		}

	}

	//checks to see if user is admin and return true of false
	public function isUserAdmin($userID){
		$sql = "SELECT isAdmin FROM user WHERE userID = $userID;
		$result = $dphandle->query($sql);

		if($result == 'y'){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	//close the connection
	public function _destruct(){
		mysql_close($dbhandle);
	}

}
