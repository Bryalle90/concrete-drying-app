<?php

//DbZipLog.php
//Class used to interact with the zipcodeLog table in our database.
//by: zach smith
//last edited: 4/24/15

require $_SERVER['DOCUMENT_ROOT']."/../libraries/password-compat/lib/password.php";

class DbZipLog {
	
	private $dbhandle;
	
	public function __construct(){
        $this->connectdb();
    }

	//connect to the database
	public function connectdb(){			
		$this->dbhandle = mysql_connect('localhost', 'root', '');
					
		$selected = mysql_select_db("Account", $this->dbhandle);
	}

	//inserts a new log to the table
	public function addLog($zipcode, $userType, $date){
		$sql = "INSERT INTO zipcodeLog (zipcode, userType, date)
		VALUES ('$zipcode', '$userType', '$date')";
		mysql_query($sql);		
	}

	//delete all logs from table
	public function deleteLogs(){
		$sql = "DELETE FROM zipcodeLog WHERE *";
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