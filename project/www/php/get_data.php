<?php
require_once('../../libraries/nusoap/nusoap.php');
require_once('../../libraries/simple-nws/SimpleNWS.php');
use SimpleNWS\SimpleNWS;

if(isset($_POST['submit'])){
	$ziplist = array($_POST['zip']);
	
	// Define new object and specify location of wsdl file.
	$soapclient = new nusoap_client('http://www.weather.gov/forecasts/xml/SOAP_server/ndfdXMLserver.php?wsdl');

	// Get lon and lat from zip code
	$LatLonList = $soapclient->call('LatLonListZipCode',$ziplist,
							   'uri:DWMLgen',
							   'uri:DWMLgen/LatLonListZipCode');

	$latlon = new SimpleXMLElement($LatLonList);
	$latlon = explode(',', $latlon->latLonList[0]);
	
	// instantiate the library
	$simpleNWS = new SimpleNWS(floatval($latlon[0]), floatval($latlon[1]));
	
	try
	{
		// request a forecast (getCurrentConditions(), getForecastForToday() or getForecastForWeek())
		$forecast = $simpleNWS->getForecastForWeek();
		$time_layouts = $forecast->getTimeLayouts();
		$hourly_temp = $forecast->getHourlyRecordedTemperature();
		$hourly_humidity = $forecast->getHourlyHumidity();
		$hourly_windspeed = $forecast->getHourlyWindSpeed();
		$hourly_cloudcover = $forecast->getHourlyCloudCover();

		// print the request URL
		$requestURL = $forecast->getRequestURL();
		$requestParts = explode('?', $requestURL);
		echo "Requested URL:<br>", $requestParts[0], "?", $requestParts[1], "<br><br>";
	   
		
	    // print the time layout
		echo "time layout<br>";
		$i = 0;
		foreach($time_layouts as $key => $value){
			if($i == 2){
				echo $key,"<br>";
				foreach($time_layouts[$key] as $time)
					echo $time,"<br>";
			}
			$i++;
		}
			
		// print the weather data
		echo "temperature<br>";
		foreach($hourly_temp as $f)
			echo $f,"<br>";
			
		echo "humidity<br>";
		foreach($hourly_humidity as $f)
			echo $f,"<br>";
			
		echo "wind speed<br>";
		foreach($hourly_windspeed as $f)
			echo $f,"<br>";
			
		echo "cloud cover<br>";
		foreach($hourly_cloudcover as $f)
			echo $f,"<br>";
	}
	catch (\Exception $error)
	{
		echo $error->getMessage();
	}


}
?>

</body>
</html>