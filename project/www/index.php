<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="bryalle.duckdns.org">
		<title>Home</title>
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
			if(isset($_POST['btn_enter'])){
				
				$ziplist = array($_POST['tb_zip']);
				
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
								$tTemp = $hourly_temp[$time];
								$cTemp = $hourly_temp[$time];
								$hum = $hourly_humidity[$time];
								$wTemp = $hourly_windspeed[$time];
								if(!empty($_POST['tb_ctemp'])){
									$cTemp = $_POST['tb_ctemp'];
								}
								$main->StandardCalc($time, $tTemp, $hum, $wTemp, $cTemp);
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
		
		<script src="javascript/Chart.js"></script>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>