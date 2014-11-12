<!doctype html>
<html>
	<head>
		<title>Line Chart</title>
		<script src="../javascript/Chart.js"></script>
	</head>

<?php
require_once('../../libraries/nusoap/nusoap.php');
require_once('../../libraries/simple-nws/SimpleNWS.php');
use SimpleNWS\SimpleNWS;
$evapArray = array();
$timeArray = array();
$main = Main();

	
function Main(){
	
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
		try{
				

				// request a forecast (getCurrentConditions(), getForecastForToday() or getForecastForWeek())
				$forecast = $simpleNWS->getForecastForWeek();
				$time_layouts = $forecast->getTimeLayouts();
				$hourly_temp = $forecast->getHourlyRecordedTemperature();
				$hourly_humidity = $forecast->getHourlyHumidity();
				$hourly_windspeed = $forecast->getHourlyWindSpeed();
				$hourly_cloudcover = $forecast->getHourlyCloudCover();
				
				// print the request URL
				//$requestURL = $forecast->getRequestURL();
				//$requestParts = explode('?', $requestURL);
				//echo "Requested URL:<br>", $requestParts[0], "?", $requestParts[1], "<br><br>";
			   
				// print the time layout
				$i = 0;
				foreach($time_layouts as $key => $value){
					
					if($i == 2)
					{
						foreach($time_layouts[$key] as $time)
							
							StandardCalc($time, $hourly_temp[$time], $hourly_humidity[$time], $hourly_windspeed[$time], $hourly_cloudcover[$time]);
					}
					$i++;
				}					
		}
		catch (\Exception $error){
			echo $error->getMessage();
		}
	}
}

	//Performs Calculation to determine evaporation rate. Standard Form.
	function StandardCalc($time, $hourly_temp, $hourly_humidity, $hourly_windspeed, $hourly_cloudcover){
		
		
		$evapRate = ((pow($hourly_temp, 2.5) - (($hourly_humidity / 100) * pow($hourly_temp, 2.5))) * (1 + (0.4 * $hourly_windspeed)) * pow(10, -6));
		setEvapArray($evapRate);
		setTimeArray($time);
		//print $evapRate, $hourly_temp, $hourly_humidity;
		}
		
	//Sets Global evapArray 	
	function setEvapArray($evapRate){
		global $evapArray;
		$evapArray[] = $evapRate;
	}
	function getEvapArray(){
		global $evapArray;
		return $evapArray;
	}
	
	function setTimeArray($time) 
	{
		global $timeArray;
		$timeArray[] = $time;
	}
	function getTimeArray()
	{
		global $timeArray;
		return $timeArray;
	}

?>
<body>
		<div style="width:93%" >
			<div>
				<canvas id="canvas" height="300
				" width="600"></canvas>
			</div>
		</div>


	<script>
		var evapArray = [<?php echo '"'.implode('","', getEvapArray()).'"' ?>];
		var timeArray = [<?php echo '"'.implode('","', getTimeArray()).'"' ?>];
		var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
		var lineChartData = {
			labels : timeArray,
			datasets : [
				{
					label: "Evaporation Forecast",
			fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            //pointColor: "rgba(220,220,220,1)",
            pointColor: "black",
            lineColor: "green",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
					data : evapArray
			
							
				}

			]

		}

	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myLine = new Chart(ctx).Line(lineChartData, {
			responsive: true
		});
		/*
		myLine.datasets[0].points[1].fillColor = "green";
		myLine.datasets[0].points[2].lineColor = "yellow";
		myLine.datasets[0].points[1].linecolor = "yellow";
		myLine.datasets[0].points[1].strokeColor = "green";
		myLine.datasets[0].points[1].pointColor = "yellow";
		myLine.datasets[0].points[2].fillColor = "yellow";
		myLine.datasets[0].points[2].strokeColor = "yellow";
		myLine.datasets[0].points[2].pointColor = "yellow";
		myLine.update();
		*/
	}


	</script>
	</body>
</html>







</body>
</html>