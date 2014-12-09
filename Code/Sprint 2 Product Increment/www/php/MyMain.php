<?php
class Main
{
	private $_evapArray;
	private $_timeArray;
	private $_cArray;
	private $_tArray;
	private $_hArray;
	private $_wArray;
	private $_ccArray;
    public function __construct()
    {
        $this->_evapArray = array();
        $this->_timeArray = array();
        $this->_cArray = array();
        $this->_tArray = array();
        $this->_hArray = array();
        $this->_wArray = array();
        $this->_ccArray = array();
    }
	
	public function calcEvap($isMetric, $customCTemp, $time, $air_temp, $humidity, $windspd, $concrete_temp, $cloud_cover){
		if($isMetric && $customCTemp){
			$concrete_temp = $this->convertCtoF($concrete_temp);
		}
		$evapRate = ((pow($concrete_temp, 2.5) - (($humidity / 100) * pow($air_temp, 2.5))) * (1 + (0.4 * $windspd)) * pow(10, -6));
		$this->addevapArray(number_format((float)$evapRate, 3, '.',''));
		$this->addtimeArray($time);
		
		if($isMetric){
			$this->addToArraysMetric($air_temp, $humidity, $windspd, $concrete_temp, $cloud_cover);
		}else{
			$this->addToArraysStd($air_temp, $humidity, $windspd, $concrete_temp, $cloud_cover);
		}
	}
	
	private function addToArraysMetric($air_temp, $humidity, $windspeed, $concrete_temp, $cloud_cover){
		$this->addcArray($this->convertFtoC($concrete_temp));
		$this->addtArray($this->convertFtoC($air_temp));
		$this->addhArray($humidity);
		$this->addwArray($this->convertMphToKph($windspeed));
		$this->addccArray($cloud_cover);
	}
	
	private function addToArraysStd($air_temp, $humidity, $windspeed, $concrete_temp, $cloud_cover){
		$this->addcArray($concrete_temp);
		$this->addtArray($air_temp);
		$this->addhArray($humidity);
		$this->addwArray($windspeed);
		$this->addccArray($cloud_cover);
	}
	
	private function convertMphToKph($mph){
		$conversion_factor = 1.6093439987125;
		return ($mph*$conversion_factor);
	}
	
	private function convertFtoC($temp){
		$conversion_factor = 5/9;
		return (($temp-32)*$conversion_factor);
	}
	
	private function convertCtoF($temp){
		$conversion_factor = 9/5;
		return (($temp*$conversion_factor)+32);
	}
	
	private function addevapArray($evapRate){
		array_push($this->_evapArray, $evapRate);
	}
	
	public function getEvapArray(){
		return $this->_evapArray;
	}
	
	private function addtimeArray($time){
		array_push($this->_timeArray, $time);
	}
	
	public function getTimeArray(){
		return $this->_timeArray;
	}
	
	private function addcArray($concrete_temp){
		array_push($this->_cArray, $concrete_temp);
	}
	
	public function getCArray(){
		return $this->_cArray;
	}
	
	private function addtArray($air_temp){
		array_push($this->_tArray, $air_temp);
	}
	
	public function getTArray(){
		return $this->_tArray;
	}
	
	private function addhArray($humidity){
		array_push($this->_hArray, $humidity);
	}
	
	public function getHArray(){
		return $this->_hArray;
	}
	
	private function addwArray($windspeed){
		array_push($this->_wArray, $windspeed);
	}
	
	public function getWArray(){
		return $this->_wArray;
	}
	
	private function addccArray($cc){
		array_push($this->_ccArray, $cc);
	}
	
	public function getCcArray(){
		return $this->_ccArray;
	}
}
?>