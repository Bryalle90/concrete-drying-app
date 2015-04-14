<?php

class DbWeather {

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
	
	//inserts a new weather to the table
	public function addWeather($zipcode, $evapRate, $cloudCoverage, $airTemp, $concTemp, $humidity, $windSpeed, $weatherTime){
		$zipcode = mysql_real_escape_string($zipcode);
		$evapRate = mysql_real_escape_string($evapRate);
		$cloudCoverage = mysql_real_escape_string($cloudCoverage);
		$airTemp = mysql_real_escape_string($airTemp);
		$concTemp = mysql_real_escape_string($concTemp);
		$humidity = mysql_real_escape_string($humidity);
		$windSpeed = mysql_real_escape_string($windSpeed);
		$weatherTime = mysql_real_escape_string($weatherTime);
		$sql = "INSERT INTO weather (zipcode, evapRate, cloudCoverage, airTemp, concTemp, humidity, windSpeed, weatherTime)
		VALUES ('$zipcode', '$evapRate', '$cloudCoverage', '$airTemp', '$concTemp', '$humidity', '$windSpeed', '$weatherTime')";
		mysql_query($sql);		
	}

	//delete weather from table
	public function deleteWeather($zip, $time){		
		$zip = mysql_real_escape_string($zip);
		$time = mysql_real_escape_string($time);
		$sql = "DELETE FROM weather WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}

	//delete weather from table
	public function clearZip($zip){		
		$zip = mysql_real_escape_string($zip);
		$sql = "DELETE FROM weather WHERE zipcode = '$zip'";
		mysql_query($sql);
	}
	
	public function checkZipTime($zip, $time){
		$zip = mysql_real_escape_string($zip);
		$time = mysql_real_escape_string($time);
		$zip = (int)$zip;
		$query = "SELECT * FROM weather WHERE zipcode = '$zip' AND weatherTime = '$time'";
		$result = mysql_query($query);
		$num_rows = mysql_num_rows($result);
		if($num_rows == 0)
			return false;
		return true;
	}
	
	//get evapRate
	public function getEvapRate($zipcode){
		$zipcode = mysql_real_escape_string($zipcode);
		$sql = "SELECT * FROM weather WHERE zipcode = '$zipcode'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[$row['weatherTime']] = $row['evapRate'];
		}
		return $array;
	}
	
	//get cloudCoverage
	public function getCloudCoverage($zipcode){
		$zipcode = mysql_real_escape_string($zipcode);
		$sql = "SELECT * FROM weather WHERE zipcode = '$zipcode'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[$row['weatherTime']] = $row['cloudCoverage'];
		}
		return $array;
	}
	
	//get airTemp
	public function getAirTemp($zipcode){
		$zipcode = mysql_real_escape_string($zipcode);
		$sql = "SELECT * FROM weather WHERE zipcode = '$zipcode'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[$row['weatherTime']] = $row['airTemp'];
		}
		return $array;
	}
	
	//get concTemp
	public function getConcTemp($zipcode){
		$zipcode = mysql_real_escape_string($zipcode);
		$sql = "SELECT * FROM weather WHERE zipcode = '$zipcode'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[$row['weatherTime']] = $row['concTemp'];
		}
		return $array;
	}
	
	//get humidity
	public function getHumidity($zipcode){
		$zipcode = mysql_real_escape_string($zipcode);
		$sql = "SELECT * FROM weather WHERE zipcode = '$zipcode'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[$row['weatherTime']] = $row['humidity'];
		}
		return $array;
	}
	
	//get windSpeed
	public function getWindSpeed($zipcode){
		$zipcode = mysql_real_escape_string($zipcode);
		$sql = "SELECT * FROM weather WHERE zipcode = '$zipcode'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[$row['weatherTime']] = $row['windSpeed'];
		}
		return $array;
	}
	
	//get timeArray
	public function getTimeArray($zipcode){
		$zipcode = mysql_real_escape_string($zipcode);
		$sql = "SELECT * FROM weather WHERE zipcode = '$zipcode'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['weatherTime'];
		}
		return $array;
	}
	
	//changes the evapRate
	public function changeEvapRate($zip, $time, $evapRate){
		$zip = mysql_real_escape_string($zip);
		$time = mysql_real_escape_string($time);
		$evapRate = mysql_real_escape_string($evapRate);
		$sql = "UPDATE weather SET evapRate = '$evapRate' WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}
	
	//changes the cloudCoverage
	public function changeCloudCoverage($zip, $time, $cloudCoverage){
		$zip = mysql_real_escape_string($zip);
		$time = mysql_real_escape_string($time);
		$cloudCoverage = mysql_real_escape_string($cloudCoverage);
		$sql = "UPDATE weather SET cloudCoverage = '$cloudCoverage' WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}
	
	//changes the airTemp
	public function changeAirTemp($zip, $time, $airTemp){
		$zip = mysql_real_escape_string($zip);
		$time = mysql_real_escape_string($time);
		$airTemp = mysql_real_escape_string($airTemp);
		$sql = "UPDATE weather SET airTemp = '$airTemp' WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}
	
	//changes the concTemp
	public function changeConcTemp($zip, $time, $concTemp){
		$zip = mysql_real_escape_string($zip);
		$time = mysql_real_escape_string($time);
		$concTemp = mysql_real_escape_string($concTemp);
		$sql = "UPDATE weather SET concTemp = '$concTemp' WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}
	
	//changes the humidity
	public function changeHumidity($zip, $time, $humidity){
		$zip = mysql_real_escape_string($zip);
		$time = mysql_real_escape_string($time);
		$humidity = mysql_real_escape_string($humidity);
		$sql = "UPDATE weather SET humidity = '$humidity' WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}
	
	//changes the windSpeed
	public function changeWindSpeed($zip, $time, $windSpeed){
		$zip = mysql_real_escape_string($zip);
		$time = mysql_real_escape_string($time);
		$windSpeed = mysql_real_escape_string($windSpeed);
		$sql = "UPDATE weather SET windSpeed = '$windSpeed' WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}

	//close the connection
	public function disconnectdb(){
		mysql_close($this->dbhandle);
	}

	public function _destruct(){
		$this->disconnectdb();
	}
}

?>