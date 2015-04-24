<?php

//DbFutureNotificationLog.php
//Class used to interact with the futureNotificationLog table in our database.
//by: zach smith
//last edited: 4/24/15

class DbfutureNotificationLog {
	
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
	public function add($projectID){
		date_default_timezone_set('America/New_York');
		$time = date('Y-m-d H:i:s', strtotime('now'));
		$sql = "INSERT INTO futureNotificationLog (projectID, dateSent)
		VALUES ('$projectID', '$time')";
		mysql_query($sql);		
	}

	//delete all logs from table
	public function deleteLogs(){
		$sql = "DELETE FROM futureNotificationLog WHERE *";
		mysql_query($sql);
	}
}

?>