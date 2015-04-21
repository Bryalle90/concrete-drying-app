<?php

//DbSeries.php
//Class used to interact with the series table in our database.
//by: zach smith
//last edited: 4/24/15

require $_SERVER['DOCUMENT_ROOT']."/../libraries/password-compat/lib/password.php";

class DbSeries {
	
	private $dbhandle;
	
	public function __construct(){
        $this->connectdb();
    }

	//connect to the database
	public function connectdb(){			
		$this->dbhandle = mysql_connect('localhost', 'root', '');
					
		$selected = mysql_select_db("Account", $this->dbhandle);
	}

	//inserts a new series to the table
	public function addNotification($projectID, $isOriginal, $concreteTemp, $windSpeed){
		$sql = "INSERT INTO series (projectID, isOriginal, concreteTemp, windSpeed)
		VALUES ('$projectID', '$isOriginal', '$concreteTemp', '$windSpeed')";
		mysql_query($sql);		
	}

	//delete series from table
	public function deleteSeries($seriesID){
		$sql = "DELETE FROM series WHERE seriesID = '$seriesID'";
		mysql_query($sql);
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

	//close the connection
	public function disconnectdb(){
		mysql_close($dbhandle);
	}

	public function _destruct(){
		$this->disconnectdb();
	}

}

?>