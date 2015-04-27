<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Performance Test</title>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		
		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="bootstrap/css/theme.css" rel="stylesheet">
	</head>
	<body style="background-color: #DBDBDB">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-0 col-md-0 col-lg-0"></div>
				<div class="col-xs-12 col-md-12 col-lg-12">
				<?php
					function calculate_median($arr) {
						sort($arr);
						$count = count($arr); //total numbers in array
						$middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
						if($count % 2) { // odd number, middle is the median
							$median = $arr[$middleval];
						} else { // even number, calculate avg of 2 medians
							$low = $arr[$middleval];
							$high = $arr[$middleval+1];
							$median = (($low+$high)/2);
						}
						return $median;
					}
					
					include($_SERVER['DOCUMENT_ROOT'].'/classes/WeatherService.php');
					include($_SERVER['DOCUMENT_ROOT'].'/classes/DataService.php');
					
					$zipInfodb = new DbZipInfo();
					date_default_timezone_set('America/New_York');
					$zips = [];
					$timeConsumed = [];
					$amt = 1;
					z$amt = isset($_GET['amt']) ? $_GET['amt'] : 1;
					for ($j = 0; $j < $_GET['amt']; $j++) {
						//$zip = '00000';
						//while(!$zipInfodb->checkZip((int)$zip))
							//$zip = (string)rand(60001, 60499);
						
						$zip = 62025;
						
						// get city, state, lon, lat from zip code
						$dataService = new DataService((int)$zip);
						$city = $dataService->getCity();
						$state = $dataService->getState();
						$lat = $dataService->getLat();
						$lon = $dataService->getLon();
						
						// using lon and lat, get weather data
						$weatherService = new WeatherService((int)$zip, $lat, $lon);
						
						set_time_limit(60);
						try{
							$start = microtime(true);
							$weatherService->getWeatherData();
							$end = microtime(true);
							
							array_push($timeConsumed, round($end - $start,3)*1000);
							array_push($zips, $zip);
						}
						catch(Exception $e){
							echo '<pre>';
							echo 'error: ';
							print_r($e);
							echo '<br>';
							echo 'zip: ';
							print_r($zip);
							echo '</pre>';
						}
					}
					
					$n = count($zips);
					for ($i = 0; $i < $n; $i++) {
						echo '<pre>';
						echo 'zip: '.$zips[$i].'<br>';
						echo 'time: ';
						echo($timeConsumed[$i].'ms');
						echo '</pre>';
					}
					$avgTime = array_sum($timeConsumed) / $n;
					$medTime = calculate_median($timeConsumed);
					$min = min($timeConsumed);
					$max = max($timeConsumed);
					echo '<pre>';
					echo 'average time: ';
					echo($avgTime.'ms');
					echo '<br>';
					echo 'median time: ';
					echo($medTime.'ms');
					echo '<br>';
					echo 'min time: ';
					echo($min.'ms');
					echo '<br>';
					echo 'max time: ';
					echo($max.'ms');
					echo '</pre>';
				?>
				<div class="col-xs-0 col-md-0 col-lg-0"></div>
			</div>
		</div>
	</body>
</html>