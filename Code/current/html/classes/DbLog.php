<?php

class DbLog{
	
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
	
	public function addView($zipcode, $type){
		$this->add($type, $zipcode, NULL);
	}

	public function addReminderSent($zipcode){
		$type = 3;
		$this->add($type, $zipcode, NULL);
	}

	public function addRiskEmailSent($zipcode, $time){
		$type = 4;
		$this->add($type, $zipcode, $time);
	}

	public function addReminderCreated($zipcode){
		$type = 5;
		$this->add($type, $zipcode, NULL);
	}

	public function addRiskCreated($zipcode, $time){
		$type = 6;
		$this->add($type, $zipcode, $time);
	}

	public function addUserCreated(){
		$type = 7;
		$this->add($type, NULL, NULL);
	}

	public function addUserVerified(){
		$type = 8;
		$this->add($type, NULL, NULL);
	}

	public function addProjectCreated($zipcode){
		$type = 9;
		$this->add($type, $zipcode, NULL);
	}
	
	private function add($type, $zip, $time){
		date_default_timezone_set('America/New_York');
		$logTime = date('Y-m-d H:i:s', strtotime('now'));
		
		$sql = "INSERT INTO log (type, zip, logTime, time)
		VALUES ('$type', '$zip', '$logTime', '$time')";
		mysql_query($sql);
	}

	//delete all logs from table
	public function deleteLogs(){
		$sql = "DELETE FROM log WHERE *";
		mysql_query($sql);
	}
	
}
?>