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
	public function addWeather($zipcode, $evapRate, $cloudCoverage, $airTemp, $concTemp, $humidity, $windSpeed, $weatherTime){
		$sql = "INSERT INTO weather (zipcode, evapRate, cloudCoverage, airTemp, concTemp, humidity, windSpeed, weatherTime)
		VALUES ('$zipcode', '$evapRate', '$cloudCoverage', '$airTemp', '$concTemp', '$humidity', '$windSpeed', '$weatherTime')";
		mysql_query($sql);		
	}

	//delete weather from table
	public function deleteWeather($weatherID){		
		$sql = "DELETE FROM weather WHERE weatherID = '$weatherID'";
		mysql_query($sql);
	}
	
	//get weatherID
	public function getWeatherID($zipcode, $weatherTime){
		$sql = "SELECT weatherID FROM weather WHERE zipcode = '$zipcode' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get evapRate
	public function getEvapRate($zipcode, $weatherTime){
		$sql = "SELECT evapRate FROM weather WHERE zipcode = '$zipcode' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get cloudCoverage
	public function getCloudCoverage($zipcode, $weatherTime){
		$sql = "SELECT cloudCoverage FROM weather WHERE zipcode = '$zipcode' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get airTemp
	public function getAirTemp($zipcode, $weatherTime){
		$sql = "SELECT airTemp FROM weather WHERE zipcode = '$zipcode' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get concTemp
	public function getConcTemp($zipcode, $weatherTime){
		$sql = "SELECT concTemp FROM weather WHERE zipcode = '$zipcode' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get humidity
	public function getHumidity($zipcode, $weatherTime){
		$sql = "SELECT humidity FROM weather WHERE zipcode = '$zipcode' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//get windSpeed
	public function getWindSpeed($zipcode, $weatherTime){
		$sql = "SELECT windSpeed FROM weather WHERE zipcode = '$zipcode' AND weahterTime = '$weatherTime'";
		$result = mysql_query($sql);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	//changes the evapRate
	public function changeEvapRate($zipcode, $weatherTime, $evapRate){
		$sql = "UPDATE weather SET evapRate = '$evapRate' WHERE zipcode = '$zipcode' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}
	
	//changes the cloudCoverage
	public function changeCloudCoverage($zipcode, $weatherTime, $cloudCoverage){
		$sql = "UPDATE weather SET cloudCoverage = '$cloudCoverage' WHERE zipcode = '$zipcode' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}
	
	//changes the airTemp
	public function changeAirTemp($zipcode, $weatherTime, $airTemp){
		$sql = "UPDATE weather SET airTemp = '$airTemp' WHERE zipcode = '$zipcode' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}
	
	//changes the concTemp
	public function changeConcTemp($zipcode, $weatherTime, $concTemp){
		$sql = "UPDATE weather SET concTemp = '$concTemp' WHERE zipcode = '$zipcode' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}
	
	//changes the humidity
	public function changeHumidity($zipcode, $weatherTime, $humidity){
		$sql = "UPDATE weather SET humidity = '$humidity' WHERE zipcode = '$zipcode' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}
	
	//changes the windSpeed
	public function changeWindSpeed($zipcode, $weatherTime, $windSpeed){
		$sql = "UPDATE weather SET windSpeed = '$windSpeed' WHERE zipcode = '$zipcode' AND weatherTime = '$weatherTime'";
		mysql_query($sql);
	}

	//close the connection
	public function _destruct(){
		mysql_close($dbhandle);
	}
}

?>