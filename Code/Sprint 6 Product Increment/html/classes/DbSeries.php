<?php

//DbSeries.php
//Class used to interact with the series table in our database.
//by: zach smith
//last edited: 4/24/15

class DbSeries {
	
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

	//inserts a new series to the table
	public function addSeries($projectID, $isOriginal, $concreteTemp, $windSpeed){
		$sql = "INSERT INTO series (projectID, isOriginal, concreteTemp, windSpeed)
		VALUES ('$projectID', '$isOriginal', '$concreteTemp', '$windSpeed')";
		mysql_query($sql);		
	}
	
	//get id of series
	public function getID($projectID, $concreteTemp, $windSpeed)
	{
		$sql = "SELECT seriesID FROM series WHERE projectID = '$projectID' AND isOriginal = 'n' AND concreteTemp = '$concreteTemp' And windSpeed = '$windSpeed'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;	
	}
	
	//Check for duplicates
	public function checkDuplicates($projectID, $concreteTemp, $windSpeed){
		$sql = "SELECT count(*) FROM series WHERE projectID = '$projectID' AND isOriginal = 'n' AND concreteTemp = '$concreteTemp' And windSpeed = '$windSpeed'";
		$result = mysql_query($sql);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;	
	}

	//delete series from table
	public function deleteSeries($seriesID){
		$sql = "DELETE FROM series WHERE seriesID = '$seriesID'";
		mysql_query($sql);
	}	
	
	public function getNumbSeries($projectID){
		$sql = "SELECT count(*) FROM series WHERE projectID = '$projectID' AND isOriginal = 'n'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	public function getOriginalSeriesID($projectID){
			$sql = "SELECT seriesID FROM series WHERE isOriginal = 'y' AND projectID = '$projectID'";
			$result = mysql_query($sql);
			if (!$result || !mysql_num_rows($result))
				return(Null);
			$result = mysql_result($result, 0);
			return $result;
	}
	
	//Gets all of the series ID's besides the original
	public function getSeriesID($projectID){
		$sql = "SELECT seriesID FROM series WHERE isOriginal = 'n' AND projectID = '$projectID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$array = array();
		while ($row = mysql_fetch_array($result)) {
			$array[] = $row['seriesID'];
		}
		return $array;
	}
	
	public function getIsOriginal($seriesID){
		$sql = "SELECT isOriginal FROM series WHERE seriesID = '$seriesID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	public function getConcreteTemp($seriesID){
		$sql = "SELECT concreteTemp FROM series WHERE seriesID = '$seriesID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
	
	public function getWindSpeed($seriesID){
		$sql = "SELECT windSpeed FROM series WHERE seriesID = '$seriesID'";
		$result = mysql_query($sql);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
}

?>