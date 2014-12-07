<?php
class Main
{
	private $_evapArray;
	private $_timeArray;
	
    public function __construct()
    {
        $this->_evapArray = array();
        $this->_timeArray = array();
    }
	
	//Performs Calculation to determine evaporation rate. Standard Form.
	public function StandardCalc($time, $hourly_temp, $hourly_humidity, $hourly_windspeed, $hourly_cloudcover){
		$evapRate = ((pow($hourly_temp, 2.5) - (($hourly_humidity / 100) * pow($hourly_temp, 2.5))) * (1 + (0.4 * $hourly_windspeed)) * pow(10, -6));
		$this->addevapArray($evapRate);
		$this->addtimeArray($time);
	}
	
	private function addevapArray($evapRate){
		array_push($this->_evapArray, $evapRate);
	}
	
	public function getevapArray(){
		return $this->_evapArray;
	}
	
	private function addtimeArray($time){
		array_push($this->_timeArray, $time);
	}
	
	public function gettimeArray(){
		return $this->_timeArray;
	}
}
?>