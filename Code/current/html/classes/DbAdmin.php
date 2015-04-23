<?php

class DbAdmin {

	//These 3 variables will need to be changed to specific case
	private $dbhandle;

	private $HOST = '127.0.0.1';
	private $ACCOUNT = 'root';
	private $PASSWORD = '';
	private $DATABASE = 'plasticcracks';

	public function __construct(){
		$this->connectdb();
	}

	//connect to the database
	public function connectdb(){			
		$this->dbhandle = mysql_connect($this->HOST, $this->ACCOUNT, $this->PASSWORD);
					
		$selected = mysql_select_db($this->DATABASE, $this->dbhandle);
	}
	
	//Totals 
	//number of accounts
	public function getTotalNumberOfAccounts(){
		$sql = "SELECT COUNT(userID) FROM user";
		$result = mysql_query($sql);

		return $result;
	}
	
	//number of projects
	public function getTotalNumberOfProjects(){
		$sql = "SELECT COUNT(projectID) FROM project";
		$result = mysql_query($sql);

		return $result;
	}
	
	//number of projects with shared users
	public function getTotalNumberOfSharedProjects(){
		$sql = "SELECT COUNT(DISTINCT projectID)FROM userProjectLookUp";
		$resultOne = mysql_query($sql);	
		$resultOne = mysql_result($resultOne, 0);

		$sql = "SELECT COUNT(projectID) FROM userProjectLookUp";
		$resultTwo = mysql_query($sql);	
		$resultTwo = mysql_result($resultTwo, 0);			

		$sql = 'SELECT projectID FROM userProjectLookUp GROUP BY projectID HAVING COUNT(*) >= 2';
		$resultThree = mysql_query($sql);	
		$resultThree = mysql_num_rows($resultThree);

		$result = $resultTwo - $resultOne - $resultThree;

		return $result;		
	}
	
	//future notifications
	public function getTotalNumberOfFutureNotifications(){
		$empty = '';
		$sql = "SELECT COUNT(reminder) FROM project WHERE reminder != '$empty'";
		$result = mysql_query($sql);

		return $result;
	}
	
	//change in state notifications
	public function getTotalNumberOfChangeInStateNotifications(){
		$sql = "SELECT COUNT(changeInStateNotificationID) FROM changeInStateNotification";
		$result = mysql_query($sql);

		return $result;
	}
	
	//Date Range
	//number of accounts
	public function getRangeNumberOfAccounts($firstDate, $secondDate){
		$sql = "SELECT COUNT(userID) FROM user WHERE ";
		$result = mysql_query($sql);
	
		return $result;			
	}
	
	//number of projects
	public function getRangeNumberOfProjects($firstDate, $secondDate){
		$sql = "SELECT COUNT(projectID) FROM project WHERE";
		$result = mysql_query($sql);	

		return $result;			
	}	
	
	//future notifications
	public function getRangeNumberOfFutureNotifications($firstDate, $secondDate){
		$sql = "SELECT COUNT(futureID) FROM futureNotification WHERE";
		$result = mysql_query($sql);	
	
		return $result;	
	}
	
	//change in state notifications
	public function getRangeNumberOfChangeInStateNotifications($firstDate, $secondDate){
		$sql = "SELECT COUNT(changeInStateNotificationID) FROM changeInStateNotification WHERE";
		$result = mysql_query($sql);	
		
		return $result;	
	}

	//drop everything from all tables (the reset)
	public function dropAll(){
		$sql = "DELETE * FROM project";
		mysql_query($sql);
		$sql = "DELETE * FROM user";
		mysql_query($sql);
		$sql = "DELETE * FROM userProjectLookup";
		mysql_query($sql);
		$sql = "DELETE * FROM weather";
		mysql_query($sql);
		$sql = "DELETE * FROM zipUpdate";
		mysql_query($sql);
		$sql = "DELETE * FROM zipcodeLog";
		mysql_query($sql);
		$sql = "DELETE * FROM changeInStateNotification";
		mysql_query($sql);
		$sql = "DELETE * FROM changeInStateNotificationLog";
		mysql_query($sql);
		$sql = "DELETE * FROM futureNotification";
		mysql_query($sql);
		$sql = "DELETE * FROM futureNotificationLog";
		mysql_query($sql);
		$sql = "DELETE * FROM projectWeatherData";
		mysql_query($sql);
		$sql = "DELETE * FROM series";
		mysql_query($sql);		
	}
}

?>
