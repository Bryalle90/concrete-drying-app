<?php
require $_SERVER['DOCUMENT_ROOT'].'/libraries/simplenws/SimpleNWS.php';
include $_SERVER['DOCUMENT_ROOT'].'/classes/DbWeather.php';
include $_SERVER['DOCUMENT_ROOT'].'/classes/DbZipUpdate.php';

class WeatherService{
	private $zipcode;
	private $latitude;
	private $longitude;
	private $time_layout;
	private $hourly_temp;
	private $hourly_concTemp;
	private $hourly_humidity;
	private $hourly_windspeed;
	private $hourly_cloudcover;
	private $hourly_evap;

	public function __construct($zip, $lat, $lon){
		$this->latitude = $lat;
		$this->longitude = $lon;
		$this->zipcode = $zip;
	}
	
	// pushes current class values to the weather table
	private function pushToDB(){		
		$weatherdb = new DbWeather();

		foreach($this->time_layout as $time){
			$aTemp = $this->hourly_temp[$time];
			$hum = $this->hourly_humidity[$time];
			$wSpd = $this->hourly_windspeed[$time];
			$cCover = $this->hourly_cloudcover[$time];
										
			// predict concrete temperature based on air temperature 
			if($aTemp < 30)
				$cTemp = 40;
			elseif($aTemp < 55)
				$cTemp = $this->hourly_temp[$time] + 10;
			elseif($aTemp < 85)
				$cTemp = $this->hourly_temp[$time] + 5;
			else
				$cTemp = $this->hourly_temp[$time];
			$this->hourly_concTemp[$time] = $cTemp;
				
			$evap = $this->calcEvap($cTemp, $hum, $aTemp, $wSpd);
			
			if($weatherdb->checkZipTime($this->zipcode, $time)){
				$weatherdb->changeEvapRate($this->zipcode, $time, $evap);
				$weatherdb->changeCloudCoverage($this->zipcode, $time, $cCover);
				$weatherdb->changeAirTemp($this->zipcode, $time, $aTemp);
				$weatherdb->changeConcTemp($this->zipcode, $time, $cTemp);
				$weatherdb->changeHumidity($this->zipcode, $time, $hum);
				$weatherdb->changeWindSpeed($this->zipcode, $time, $wSpd);
			} else {
				$weatherdb->addWeather($this->zipcode, $evap, $cCover, $aTemp, $cTemp, $hum, $wSpd, $time);
			}
		}
	}
	
	private function pullFromDB(){
		$weatherdb = new DbWeather();

		$this->time_layout = $weatherdb->getTimeArray($this->zipcode);
		$this->hourly_temp = $weatherdb->getAirTemp($this->zipcode);
		$this->hourly_concTemp = $weatherdb->getConcTemp($this->zipcode);
		$this->hourly_humidity = $weatherdb->getHumidity($this->zipcode);
		$this->hourly_windspeed = $weatherdb->getWindSpeed($this->zipcode);
		$this->hourly_cloudcover = $weatherdb->getCloudCoverage($this->zipcode);
		$this->hourly_evap = $weatherdb->getEvapRate($this->zipcode);
		
		// if any array does not exist then we force update the database from NOAA
		if($this->time_layout == Null || $this->hourly_temp == Null ||
					$this->hourly_concTemp == Null || $this->hourly_humidity == Null ||
					$this->hourly_windspeed == Null || $this->hourly_cloudcover == Null || $this->hourly_evap == Null){
						
			$this->forceUpdate();
		}
	}
	
	public function forceUpdate(){
		try{
			$simpleNWS = new SimpleNWS(floatval($this->latitude), floatval($this->longitude));
		}
		catch (Exception $error){
			throw $error;
		}
		
		$weatherdb = new DbWeather();
		$weatherdb->clearZip($this->zipcode);
		
		$forecast = $simpleNWS->getForecastForWeek();
		
		$time_layouts = $forecast->getTimeLayouts();
		$this->hourly_temp = $forecast->getHourlyRecordedTemperature();
		$this->hourly_humidity = $forecast->getHourlyHumidity();
		$this->hourly_windspeed = $forecast->getHourlyWindSpeed();
		$this->hourly_cloudcover = $forecast->getHourlyCloudCover();
		
		// get the third time layout (Every 3 hours out to 72 hours, Every 6 hours out to 168 hours)
		$i = 0;
		foreach($time_layouts as $key => $value){
			if ($i == 2)
				$k = $key;
			$i++;
		}
		$this->time_layout = $time_layouts[$k];
		
		// update the weather database
		$this->pushToDB();
	}
	
	public function getWeatherData(){	
		$updatedb = new DbZipUpdate();
		
		$timeUpdated = date($updatedb->getLastUpdated($this->zipcode));
		$timeNow = date('Y-m-d H:i:s', strtotime('now'));
		
		if($timeUpdated == Null){ // zip does not exist in table
			$updatedb->add($this->zipcode, $timeNow);
			try{
				$this->forceUpdate();
			}
			catch (Exception $error){
				throw $error;
			}
		} else { // zip exists in table
			$seconds = strtotime($timeNow) - strtotime($timeUpdated);
			$hours = $seconds / 60 /  60;
			if($hours >= 1.00){ // zip was updated over an hour ago
				$updatedb->update($this->zipcode, $timeNow);
				try{
					$this->forceUpdate();
				}
				catch (Exception $error){
					throw $error;
				}
			} else { // zip has been updated within the last hour
				$this->pullFromDB();
			}
		}
	}
	
	public function calcEvap($cTemp, $hum, $aTemp, $wSpd){
		$concTemp = pow($cTemp, 2.5);
		$humidPercent = $hum / 100;
		$airTemperature = pow($aTemp, 2.5);
		$wspd = 1 + (0.4 * $wSpd);
		$evap = ($concTemp - ($humidPercent * $airTemperature)) * $wspd * pow(10, -6);
		return($evap);
	}
	
	public function getTimeLayout(){
		return ($this->time_layout);
	}
	
	public function getHourlyAirTemp(){
		return ($this->hourly_temp);
	}
	
	public function getHourlyConcTemp(){
		return ($this->hourly_concTemp);
	}
	
	public function getHourlyWindSpeed(){
		return ($this->hourly_windspeed);
	}
	
	public function getHourlyCloudCover(){
		return ($this->hourly_cloudcover);
	}
	
	public function getHourlyHumidity(){
		return ($this->hourly_humidity);
	}
	
	public function getHourlyEvap(){
		return ($this->hourly_evap);
	}
}
?>