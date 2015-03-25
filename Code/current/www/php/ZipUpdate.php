<?php
class ZipUpdate {

	private $dbhandle;	

	public function _construct(){}

	//connect to the database
	public function connectdb(){			
		$this->dbhandle = mysql_connect('localhost', 'root', '');
					
		$selected = mysql_select_db("Account", $this->dbhandle);
	}

	public function getLastUpdated($zip){
		$query = "SELECT updateTime FROM zipupdate WHERE zipcode = '$zip'";
		$result = mysql_query($query);
        if (!$result || !mysql_num_rows($result))
            return(Null);
		$time = mysql_result($result, 0);
		return $time;
	}
	
	public function update($zip, $time){
		$query = "UPDATE zipupdate SET updateTime = '$time' WHERE zipcode = '$zip'";
		mysql_query($query);
	}
	
	public function add($zip, $time){
		$query = "INSERT INTO zipupdate (zipcode, updateTime)
		VALUES ('$zip', '$time')";
		mysql_query($query);		
	}

	//close the connection
	public function _destruct(){
		mysql_close($dbhandle);
	}
}

?>