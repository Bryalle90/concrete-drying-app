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

		// print the request URL
		$requestURL = $forecast->getRequestURL();
		$requestParts = explode('?', $requestURL);
		echo "Requested URL:<br>", $requestParts[0], "?", $requestParts[1], "<br><br>";
	   
	    // print the time layout
		$time_layout = $forecast->getTimeLayouts()['k-p3h-n37-3'];
		echo "time layout<br>";
		foreach($time_layout as $f)
			echo $f,"<br>";
			
		// print the weather data
		$hourly_temp = $forecast->getHourlyRecordedTemperature();
		echo "temperature<br>";
		foreach($hourly_temp as $f)
			echo $f,"<br>";
			
		$hourly_humidity = $forecast->getHourlyHumidity();
		echo "humidity<br>";
		foreach($hourly_humidity as $f)
			echo $f,"<br>";
			
		$hourly_windspeed = $forecast->getHourlyWindSpeed();
		echo "wind speed<br>";
		foreach($hourly_windspeed as $f)
			echo $f,"<br>";
			
		$hourly_cloudcover = $forecast->getHourlyCloudCover();
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