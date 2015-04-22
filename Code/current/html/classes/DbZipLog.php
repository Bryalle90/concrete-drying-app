<?php

//DbZipLog.php
//Class used to interact with the zipcodeLog table in our database.
//by: zach smith
//last edited: 4/24/15

require $_SERVER['DOCUMENT_ROOT']."/../libraries/password-compat/lib/password.php";

class DbZipLog {
	
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
		mysql_close($this->dbhandle);
	}

	public function __destruct(){
		$this->disconnectdb();
	}

}

?>