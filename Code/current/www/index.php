<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="bryalle.duckdns.org">
		<title>Home</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
		<script src="javascript/highcharts.js"></script>
		<script src="javascript/exporting.js"></script>
		<script src="javascript/draggable-points.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</head>
	<body>
	<?php
	
		// show top nav bar and zipcode input
		include $_SERVER['DOCUMENT_ROOT']."/includes/navbar.html";
		include $_SERVER['DOCUMENT_ROOT']."/includes/enterzip.html";
			
		// require libraries
		require_once($_SERVER['DOCUMENT_ROOT'].'/../libraries/nusoap/nusoap.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/../libraries/simple-nws/SimpleNWS.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/php/MyMain.php');
		use SimpleNWS\SimpleNWS;
			
		// if enter button was pressed
		if(isset($_POST['btn_enter']) && $_POST['tb_zip'] != ""){
				

			$ziplist = array($_POST['tb_zip']);
			$zipcode = $ziplist[0];
				
			$isMetric = isset($_POST['cb_metric']);
			$customCTemp = $_POST['tb_ctemp'] != "";
				
			// create soap client
			$soapclient = new nusoap_client('http://www.weather.gov/forecasts/xml/SOAP_server/ndfdXMLserver.php?wsdl');

			// get lon and lat from zip code
			$LatLonList = $soapclient->call('LatLonListZipCode',$ziplist,
										   'uri:DWMLgen',
										   'uri:DWMLgen/LatLonListZipCode');

			$latlon = new SimpleXMLElement($LatLonList);
			$latlon = explode(',', $latlon->latLonList[0]);
				
			$simpleNWS = new SimpleNWS(floatval($latlon[0]), floatval($latlon[1]));
			$main = new Main();
				
			try{
				// request a forecast (getCurrentConditions(), getForecastForToday() or getForecastForWeek())
				$forecast = $simpleNWS->getForecastForWeek();
				$time_layouts = $forecast->getTimeLayouts();
				$hourly_temp = $forecast->getHourlyRecordedTemperature();
				$hourly_humidity = $forecast->getHourlyHumidity();
				$hourly_windspeed = $forecast->getHourlyWindSpeed();
				$hourly_cloudcover = $forecast->getHourlyCloudCover();
					
				$i = 0;
				foreach($time_layouts as $key => $value){
					if($i == 2){
						foreach($time_layouts[$key] as $time){
							$aTemp = $hourly_temp[$time];
							$cTemp = $hourly_temp[$time];
							$hum = $hourly_humidity[$time];
							$wSpd = $hourly_windspeed[$time];
							$cCover = $hourly_cloudcover[$time];
							if($customCTemp){
								$cTemp = $_POST['tb_ctemp'];
								}
							$main->calcEvap($isMetric, $customCTemp, $time, $aTemp, $hum, $wSpd, $cTemp, $cCover);
						}
					}
					$i++;
				}
				// draw graph
				include $_SERVER['DOCUMENT_ROOT']."/includes/graph.php";
			}
				
				
			catch (\Exception $error){
				if( $error->getMessage() == "Invalid latitude. Allowed values are between 20.19 and 50.11"){
					echo "<font color='red'>Please enter a valid zip code</font>";
				}
					
			}
				
		}
	?>


</body>
</html>