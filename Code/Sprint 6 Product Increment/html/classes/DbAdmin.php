<?php

class DbAdmin {

	//These 3 variables will need to be changed to specific case
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
	
	//Totals 
	//number of accounts	
	public function getTotalNumberOfAccounts(){
		$sql = "SELECT COUNT(type) FROM log WHERE type ='7'";
		$result = mysql_query($sql);

		return $result;
	}
	//number of verified accounts
	public function getTotalNumberOfAccountsVal(){
		$sql = "SELECT COUNT(type) FROM log WHERE type ='8'";
		$result = mysql_query($sql);

		return $result;
	}	
	
	//number of projects
	public function getTotalNumberOfProjects(){
		$sql = "SELECT COUNT(type) FROM log WHERE type = '9'";
		$result = mysql_query($sql);

		return $result;
	}	
	
	
	//future notifications	
	public function getTotalNumberOfFutureNotificationsCreated(){		
		$sql = "SELECT COUNT(type) FROM log WHERE type = '5'";
		$result = mysql_query($sql);

		return $result;
	}

	//sent future notifications
	public function getTotalNumberOfFutureNotificationsSent(){		
		$sql = "SELECT COUNT(type) FROM log WHERE type = '3'";
		$result = mysql_query($sql);

		return $result;
	}
	
	//change in state notifications
	public function getTotalNumberOfChangeInStateNotificationsCreated(){
		$sql = "SELECT COUNT(type) FROM log WHERE type = '6'";
		$result = mysql_query($sql);

		return $result;
	}

	//sent change in state notifications
	public function getTotalNumberOfChangeInStateNotificationsSent(){
		$sql = "SELECT COUNT(type) FROM log WHERE type = '4'";
		$result = mysql_query($sql);

		return $result;
	}
	//guest zip searched
	public function getZipGuest(){
		$sql = "SELECT COUNT(type) FROM log WHERE type = '0'";
		$result = mysql_query($sql);

		return $result;
	}

	//user zip searched
	public function getZipUser(){
		$sql = "SELECT COUNT(type) FROM log WHERE type = '1'";
		$result = mysql_query($sql);

		return $result;
	}

	//project zip searched
	public function getZipProject(){
		$sql = "SELECT COUNT(type) FROM log WHERE type = '2'";
		$result = mysql_query($sql);

		return $result;
	}
	
	//Date Range


	public function getTotal($array, $firstDate, $secondDate){

		$total = 0;

		$splitArrayFirst = str_split($firstDate);
		$splitArraySecond = str_split($secondDate);
		
		$yearFirst = $splitArrayFirst[0] . $splitArrayFirst[1] . $splitArrayFirst[2] . $splitArrayFirst[3];
		$monthFirst = $splitArrayFirst[5] . $splitArrayFirst[6];
		$dayFirst = $splitArrayFirst[8] . $splitArrayFirst[9];

		$yearSecond = $splitArraySecond[0] . $splitArraySecond[1] . $splitArraySecond[2] . $splitArraySecond[3];
		$monthSecond = $splitArraySecond[5] . $splitArraySecond[6];
		$daySecond = $splitArraySecond[8] . $splitArraySecond[9];
	
		$temp;

		if($yearFirst > $yearSecond){
			$temp = $yearFirst;
			$yearFirst = $yearSecond;
			$yearSecond= $temp;
			$temp = $monthFirst;
			$monthFirst = $monthSecond;
			$monthSecond= $temp;
			$temp = $dayFirst;
			$dayFirst = $daySecond;
			$daySecond= $temp;
		}else if($yearFirst == $yearSecond){
			if($monthFirst > $monthSecond){
				$temp = $yearFirst;
				$yearFirst = $yearSecond;
				$yearSecond= $temp;
				$temp = $monthFirst;
				$monthFirst = $monthSecond;
				$monthSecond= $temp;
				$temp = $dayFirst;
				$dayFirst = $daySecond;
				$daySecond= $temp;

			}else if($monthFirst == $monthSecond){
				if($dayFirst > $daySecond){
					$temp = $yearFirst;
					$yearFirst = $yearSecond;
					$yearSecond= $temp;
					$temp = $monthFirst;
					$monthFirst = $monthSecond;
					$monthSecond= $temp;
					$temp = $dayFirst;
					$dayFirst = $daySecond;
					$daySecond= $temp;
				}else{					
				}
			}else{
			}
		}else {						
		}
		

		$splitArray;

		for($i = 0; $i < count($array); $i++){

			$splitArray = str_split($array[$i]);		

			$year = $splitArray[0] . $splitArray[1] . $splitArray[2] . $splitArray[3];
			$month = $splitArray[5] . $splitArray[6];
			$day = $splitArray[8] . $splitArray[9];						

			if($yearFirst == $year || $year == $yearSecond){

				if($monthFirst == $month || $month == $monthSecond){

					if($dayFirst <= $day && $day <= $daySecond){

						$total++;

					}

				}else if($monthFirst <= $month && $month <= $monthSecond){

					$total++;	
			
				}

			}else if($yearFirst <= $year && $year <= $yearSecond){
				
				$total++;

			}
		}
		
		return $total;

		
	}

	//created accounts
	public function getRangeNumberOfAccountsCreated($firstDate, $secondDate){
		$sql = "SELECT * FROM log WHERE type ='7' AND logTime !=''";
		$result = mysql_query($sql);

		if (!$result || !mysql_num_rows($result))
			$array[0] = 0;
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['logTime'];
		}

		$result = $this->getTotal($array, $firstDate, $secondDate);	

		return $result;			
	}
	
	//valadated accounts
	public function getRangeNumberOfAccountsVal($firstDate, $secondDate){
		$sql = "SELECT * FROM log WHERE type = '8' AND logTime != ''";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			$array[0] = 0;
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['logTime'];
		}

		$result = $this->getTotal($array, $firstDate, $secondDate);	

		return $result;			
	}
	
	//created projects
	public function getRangeNumberOfProjects($firstDate, $secondDate){
		$sql = "SELECT * FROM log WHERE type = '9' AND logTime != ''";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			$array[0] = 0;
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['logTime'];
		}

		$result = $this->getTotal($array, $firstDate, $secondDate);

		return $result;		
	}	
	
	//number of projects with shared users
	public function getTotalNumberOfSharedProjects(){		

		$sql = 'SELECT projectID FROM userProjectLookup GROUP BY projectID HAVING COUNT(*) >= 2';
		$result = mysql_query($sql);	
		$result = mysql_num_rows($result);

		return $result;		
	}

	//created future notifications
	public function getRangeNumberOfFutureNotificationsCreated($firstDate, $secondDate){
		$sql = "SELECT * FROM log WHERE type = '5' AND logTime != ''";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			$array[0] = 0;
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['logTime'];
		}

		$result = $this->getTotal($array, $firstDate, $secondDate);	

		return $result;
	}

	//sent future notifications
	public function getRangeNumberOfFutureNotificationsSent($firstDate, $secondDate){
		$sql = "SELECT * FROM log WHERE type = '3' AND logTime != ''";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			$array[0] = 0;
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['logTime'];
		}

		$result = $this->getTotal($array, $firstDate, $secondDate);	

		return $result;
	}
	
	//created future notifications
	public function getRangeNumberChangeInStateNotificationsCreated($firstDate, $secondDate){
		$sql = "SELECT * FROM log WHERE type = '6' AND logTime != ''";

		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			$array[0] = 0;
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['logTime'];
		}

		$result = $this->getTotal($array, $firstDate, $secondDate);	

		return $result;
	}

	//sent future notifications
	public function getRangeNumberNumberChangeInStateNotificationsSent($firstDate, $secondDate){
		$sql = "SELECT * FROM log WHERE type = '4' AND logTime != ''";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			$array[0] = 0;
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['logTime'];
		}

		$result = $this->getTotal($array, $firstDate, $secondDate);	

		return $result;
	}

	//guest zip searched
	public function getRangeZipGuest($firstDate, $secondDate){
		$sql = "SELECT * FROM log WHERE type = '0' AND logTime != ''";
		$result = mysql_query($sql);

		if (!$result || !mysql_num_rows($result))
			$array[0] = 0;
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['logTime'];
		}

		$result = $this->getTotal($array, $firstDate, $secondDate);

		return $result;
	}

	//user zip searched
	public function getRangeZipUser($firstDate, $secondDate){
		$sql = "SELECT * FROM log WHERE type = '1' AND logTime != ''";
		$result = mysql_query($sql);

		if (!$result || !mysql_num_rows($result))
			$array[0] = 0;
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['logTime'];
		}

		$result = $this->getTotal($array, $firstDate, $secondDate);

		return $result;
	}

	//project zip searched
	public function getRangeZipProject($firstDate, $secondDate){
		$sql = "SELECT * FROM log WHERE type = '2' AND logTime != ''";
		$result = mysql_query($sql);

		if (!$result || !mysql_num_rows($result))
			$array[0] = 0;
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['logTime'];
		}

		$result = $this->getTotal($array, $firstDate, $secondDate);

		return $result;
	}

	public function getAllEmails(){

		$sql = "SELECT * FROM user WHERE isValidated = '1'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['email'];
		}
		return $array;
	}

	//drop everything from all tables (the reset)
	public function dropAll(){
		$sql = "DELETE FROM project";
		mysql_query($sql);
		$sql = "DELETE FROM user";
		mysql_query($sql);
		$sql = "DELETE FROM userProjectLookup";
		mysql_query($sql);
		$sql = "DELETE FROM weather";
		mysql_query($sql);
		$sql = "DELETE FROM zipUpdate";
		mysql_query($sql);
		$sql = "DELETE FROM log";
		mysql_query($sql);
		$sql = "DELETE FROM changeInStateNotification";
		mysql_query($sql);
		$sql = "DELETE FROM futureNotification";
		mysql_query($sql);
		$sql = "DELETE FROM projectWeatherData";
		mysql_query($sql);
		$sql = "DELETE FROM series";
		mysql_query($sql);		
	}
}

?>
