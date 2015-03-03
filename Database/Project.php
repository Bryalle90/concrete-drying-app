//Project.php
//Class used to interact with the project table in our database.
//by: zach smith
//last edited: 3/3/15


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

	

	//close the connection
	public function _destruct(){
		mysql_close($dbhandle);
	}

}
