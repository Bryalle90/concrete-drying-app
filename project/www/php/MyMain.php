<?php
class Main
{
	private $_evapArray;
	private $_timeArray;
	private $_cArray;
	private $_tArray;
	private $_hArray;
	private $_wArray;
    public function __construct()
    {
        $this->_evapArray = array();
        $this->_timeArray = array();
        $this->_cArray = array();
        $this->_tArray = array();
        $this->_hArray = array();
        $this->_wArray = array();
    }
	
	//Performs Calculation to determine evaporation rate. Standard Form.
	public function StandardCalc($time, $air_temp, $humidity, $windspeed, $concrete_temp){
		$evapRate = ((pow($concrete_temp, 2.5) - (($humidity / 100) * pow($air_temp, 2.5))) * (1 + (0.4 * $windspeed)) * pow(10, -6));
		$this->addevapArray(number_format((float)$evapRate, 3, '.',''));
		$this->addtimeArray($time);
		$this->addcArray($concrete_temp);
		$this->addtArray($air_temp);
		$this->addtArray($humidity);
		$this->addwArray($windspeed);
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
	
	private function addcArray($concrete_temp){
		array_push($this->_cArray, $concrete_temp);
	}
	
	public function getcArray(){
		return $this->_cArray;
	}
	
	private function addtArray($air_temp){
		array_push($this->_tArray, $air_temp);
	}
	
	public function gettArray(){
		return $this->_tArray;
	}
	
	private function addhArray($humidity){
		array_push($this->_hArray, $humidity);
	}
	
	public function gethArray(){
		return $this->_tArray;
	}
	private function addwArray($windspeed){
		array_push($this->_wArray, $windspeed);
	}
	
	public function getwArray(){
		return $this->_wArray;
	}
	
}
?>