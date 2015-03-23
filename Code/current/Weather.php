<?php

//Weather.php
//Class used to interact with the weather table in our database.
//by: zach smith
//last edited: 2/23/15


class Weather {

	private $dbhandle;	

	public function _construct(){}

	//connect to the database
	public function connectdb(){			
		$this->dbhandle = mysql_connect('localhost', 'root', '');
					
		$selected = mysql_select_db("Account", $this->dbhandle);
	}
	
	//inserts a new weather to the table
	public function addWeather($projectID, $evapRate, $cloudCoverage, $airTemp, $concTemp, $humidity, $windSpeed, $weatherTime){
		$sql = "INSERT INTO weather (projectID, evapRate, cloudCoverage, airTemp, concTemp, humidity, windSpeed, weatherTime)
		VALUES ('$projectID', '$evapRate', '$cloudCoverage', '$airTemp', '$concTemp', '$humidity', '$windSpeed', '$weatherTime')";
		mysql_query($sql);		
	}

	//delete weather from table
	public function deleteWeather($weatherID){		
		$sql = "DELETE FROM weather WHERE weatherID = '$weatherID'";
		mysql_query($sql);
	}
	
	//get weatherID
	public function getWeatherID($projectID, $weatherTime){
		$sql = "SELECT weatherID FROM weather WHERE projectID = '$projectID' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get evapRate
	public function getEvapRate($projectID, $weatherTime){
		$sql = "SELECT evapRate FROM weather WHERE projectID = '$projectID' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get cloudCoverage
	public function getCloudCoverage($projectID, $weatherTime){
		$sql = "SELECT cloudCoverage FROM weather WHERE projectID = '$projectID' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get airTemp
	public function getAirTemp($projectID, $weatherTime){
		$sql = "SELECT airTemp FROM weather WHERE projectID = '$projectID' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get concTemp
	public function getConcTemp($projectID, $weatherTime){
		$sql = "SELECT concTemp FROM weather WHERE projectID = '$projectID' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get humidity
	public function getHumidity($projectID, $weatherTime){
		$sql = "SELECT humidity FROM weather WHERE projectID = '$projectID' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get windSpeed
	public function getWindSpeed($projectID, $weatherTime){
		$sql = "SELECT windSpeed FROM weather WHERE projectID = '$projectID' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//changes the evapRate
	public function changeEvapRate($projectID, $weatherTime, $evapRate){
		$sql = "UPDATE weather SET evapRate = '$evapRate' WHERE projectID = '$projectID' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}
	
	//changes the cloudCoverage
	public function changeCloudCoverage($projectID, $weatherTime, $cloudCoverage){
		$sql = "UPDATE weather SET cloudCoverage = '$cloudCoverage' WHERE projectID = '$projectID' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}
	
	//changes the airTemp
	public function changeAirTemp($projectID, $weatherTime, $airTemp){
		$sql = "UPDATE weather SET airTemp = '$airTemp' WHERE projectID = '$projectID' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}
	
	//changes the concTemp
	public function changeConcTemp($projectID, $weatherTime, $concTemp){
		$sql = "UPDATE weather SET concTemp = '$concTemp' WHERE projectID = '$projectID' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}
	
	//changes the humidity
	public function changeHumidity($projectID, $weatherTime, $humidity){
		$sql = "UPDATE weather SET humidity = '$humidity' WHERE projectID = '$projectID' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}
	
	//changes the windSpeed
	public function changeWindSpeed($projectID, $weatherTime, $windSpeed){
		$sql = "UPDATE weather SET windSpeed = '$windSpeed' WHERE projectID = '$projectID' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}

	//close the connection
	public function _destruct(){
		mysql_close($dbhandle);
	}
}

?>