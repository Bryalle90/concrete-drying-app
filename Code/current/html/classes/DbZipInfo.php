<?php

class DbZipInfo {

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
	
	public function addZip($zipcode, $city, $state, $lat, $lon){
		$sql = "INSERT INTO weather (zipcode, city, state, latitude, longitude)
		VALUES ('$zipcode', '$city', '$state', '$lat', '$lon')";
		mysql_query($sql);		
	}
	
	public function checkZip($zip){
		$zip = (int)$zip;
		$query = "SELECT * FROM zipinfo WHERE zipcode = '$zip'";
		$result = mysql_query($query);
		$num_rows = mysql_num_rows($result);
		if($num_rows == 0)
			return false;
		return true;
	}
	
	public function changeCity($zip, $city){
		$sql = "UPDATE zipinfo SET city = '$city' WHERE zipcode = '$zip'";
		mysql_query($sql);
	}
	
	public function changeState($zip, $state){
		$sql = "UPDATE zipinfo SET state = '$state' WHERE zipcode = '$zip'";
		mysql_query($sql);
	}
	
	public function changeLat($zip, $lat){
		$sql = "UPDATE zipinfo SET latitude = '$lat' WHERE zipcode = '$zip'";
		mysql_query($sql);
	}
	
	public function changeLon($zip, $lon){
		$sql = "UPDATE zipinfo SET longitude = '$lon' WHERE zipcode = '$zip'";
		mysql_query($sql);
	}

	public function getCity($zip){
		$zip = (int)$zip;
		$query = "SELECT city FROM zipinfo WHERE zipcode = '$zip'";
		$result = mysql_query($query);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getState($zip){
		$zip = (int)$zip;
		$query = "SELECT state FROM zipinfo WHERE zipcode = '$zip'";
		$result = mysql_query($query);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getLat($zip){
		$zip = (int)$zip;
		$query = "SELECT latitude FROM zipinfo WHERE zipcode = '$zip'";
		$result = mysql_query($query);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}

	public function getLon($zip){
		$zip = (int)$zip;
		$query = "SELECT longitude FROM zipinfo WHERE zipcode = '$zip'";
		$result = mysql_query($query);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$result = mysql_result($result, 0);
		return $result;
	}
}

?>