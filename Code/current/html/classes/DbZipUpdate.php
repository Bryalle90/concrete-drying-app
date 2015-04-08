<?php

class DbzipUpdate {

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

	public function getLastUpdated($zip){
		$query = "SELECT updateTime FROM zipUpdate WHERE zipcode = '$zip'";
		$result = mysql_query($query);
		if (!$result || !mysql_num_rows($result))
			return(Null);
		$time = mysql_result($result, 0);
		return $time;
	}
	
	public function update($zip, $time){
		$query = "UPDATE zipUpdate SET updateTime = '$time' WHERE zipcode = '$zip'";
		mysql_query($query);
	}
	
	public function add($zip, $time){
		$query = "INSERT INTO zipUpdate (zipcode, updateTime)
		VALUES ('$zip', '$time')";
		mysql_query($query);		
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