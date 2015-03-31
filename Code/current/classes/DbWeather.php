<?php

//Weather.php
//Class used to interact with the weather table in our database.
//by: zach smith
//last edited: 2/23/15


class DbWeather {

	private $dbhandle;	

	public function __construct(){
        $this->connectdb();
    }

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
	public function deleteWeather($zip, $time){		
		$sql = "DELETE FROM weather WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}

	//delete weather from table
	public function clearZip($zip){		
		$sql = "DELETE FROM weather WHERE zipcode = '$zip'";
		mysql_query($sql);
	}
    
    public function checkZipTime($zip, $time){
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
		$sql = "UPDATE weather SET evapRate = '$evapRate' WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}
	
	//changes the cloudCoverage
	public function changeCloudCoverage($zip, $time, $cloudCoverage){
		$sql = "UPDATE weather SET cloudCoverage = '$cloudCoverage' WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}
	
	//changes the airTemp
	public function changeAirTemp($zip, $time, $airTemp){
		$sql = "UPDATE weather SET airTemp = '$airTemp' WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}
	
	//changes the concTemp
	public function changeConcTemp($zip, $time, $concTemp){
		$sql = "UPDATE weather SET concTemp = '$concTemp' WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}
	
	//changes the humidity
	public function changeHumidity($zip, $time, $humidity){
		$sql = "UPDATE weather SET humidity = '$humidity' WHERE zipcode = '$zip' AND weatherTime = '$time'";
		mysql_query($sql);
	}
	
	//changes the windSpeed
	public function changeWindSpeed($zip, $time, $windSpeed){
		$sql = "UPDATE weather SET windSpeed = '$windSpeed' WHERE zipcode = '$zip' AND weatherTime = '$time'";
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