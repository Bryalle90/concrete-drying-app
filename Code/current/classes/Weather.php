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
    
    public function clearZip($zipcode){
        $query = "SELECT * FROM weather WHERE zipcode = '$zipcode'";
        $result = mysql_query($query);
        if (!$result || !mysql_num_rows($result))
            return(Null);
        $array = array();
        while ($row = mysql_fetch_array($result)) {
            $array[] = $row['weatherID'];
        }
        foreach($array as $id)
            $this->deleteWeather($id);
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
	public function changeEvapRate($weatherID, $evapRate){
		$sql = "UPDATE weather SET evapRate = '$evapRate' WHERE weatherID = '$weatherID'";
		mysql_query($sql);
	}
	
	//changes the cloudCoverage
	public function changeCloudCoverage($weatherID, $cloudCoverage){
		$sql = "UPDATE weather SET cloudCoverage = '$cloudCoverage' WHERE weatherID = '$weatherID'";
		mysql_query($sql);
	}
	
	//changes the airTemp
	public function changeAirTemp($weatherID, $airTemp){
		$sql = "UPDATE weather SET airTemp = '$airTemp' WHERE weatherID = '$weatherID'";
		mysql_query($sql);
	}
	
	//changes the concTemp
	public function changeConcTemp($weatherID, $concTemp){
		$sql = "UPDATE weather SET concTemp = '$concTemp' WHERE weatherID = '$weatherID'";
		mysql_query($sql);
	}
	
	//changes the humidity
	public function changeHumidity($weatherID, $humidity){
		$sql = "UPDATE weather SET humidity = '$humidity' WHERE weatherID = '$weatherID'";
		mysql_query($sql);
	}
	
	//changes the windSpeed
	public function changeWindSpeed($weatherID, $windSpeed){
		$sql = "UPDATE weather SET windSpeed = '$windSpeed' WHERE weatherID = '$weatherID'";
		mysql_query($sql);
	}

	//close the connection
	public function _destruct(){
		mysql_close($dbhandle);
	}
}

?>