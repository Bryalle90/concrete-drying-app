<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Home</title>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="javascript/highcharts.js"></script>
		<script src="javascript/exporting.js"></script>
		<script src="javascript/grouped-categories.js"></script>
		<script src="javascript/GraphFunctions.js"></script>
		
		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="bootstrap/css/theme.css" rel="stylesheet">
	</head>
	<body>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-0 col-md-0 col-lg-0"></div>
				<div class="col-xs-12 col-md-12 col-lg-12">
					
					<div align="center">
						<form class="form-inline <?php echo isset($_POST['projectID']) ? 'hidden' : '' ?>" action="/index.php" method="get">
							<div class="form-group">
								<label class="sr-only" for="zipinput">Zip Code</label>
								<input style="min-width:250px" name="zip" id="zipinput" type="zip" class="form-control popover-show" pattern="\d{5}" maxLength="5" size="5" placeholder="zip code" data-trigger="manual" data-placement="bottom" data-content="Enter the zipcode of your project to view the forcast of shrinkage crack risk.">
								<script> var graphShown = false; </script>
								<div class="input-group">
									<span class="input-group-addon">Unit</span>
									<select name="unit" class="form-control">
										<option>Standard</option>
										<option>Metric</option>
									</select>
								</div>
								<button class="btn btn-primary form-control" type="submit">Go!</button>
							</div>
						</form>
					</div>
					
					<?php
					$ZIPPATTERN = "/\b\d{5}\b/"; // usa zip code regex
					
				
					// show top nav bar and zipcode input
					include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
					
					// require classes
					require_once($_SERVER['DOCUMENT_ROOT'].'/classes/WeatherService.php');
					require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DataService.php');
						
					if(isset($_GET['zip']) && isset($_GET['unit'])){
						$zip = $_GET['zip'];
						if($zip == '00000'){
							$zipInfodb = new DbZipInfo();
							while(!$zipInfodb->checkZip((int)$zip))
								$zip = (string)rand(60001, 60499);
						}
						// if the zip code provided is valid
						if (preg_match($ZIPPATTERN, $zip)){
							
							$isMetric = $_GET['unit'] == "Metric" ? 1 : 0;
							
							// instantiate graph helper class
							?>
							<script>
							main = new Main(<?php echo json_encode($zip)?>, <?php echo $isMetric;?>);
							</script>
							<?php
							
							try{
								// get city, state, lon, lat from zip code
								$dataService = new DataService((int)$zip);
								$city = $dataService->getCity();
								$state = $dataService->getState();
								$lat = $dataService->getLat();
								$lon = $dataService->getLon();
								
								// using lon and lat, get weather data
								$weatherService = new WeatherService((int)$zip, $lat, $lon);
								$weatherService->getWeatherData();
							
								// fill javascript arrays with weather data for the graph
								$time_layout = $weatherService->getTimeLayout();
								$hourly_temp = $weatherService->getHourlyAirTemp();
								$hourly_concTemp = $weatherService->getHourlyConcTemp();
								$hourly_humidity = $weatherService->getHourlyHumidity();
								$hourly_windspeed = $weatherService->getHourlyWindSpeed();
								$hourly_cloudcover = $weatherService->getHourlyCloudCover();
								
								foreach($time_layout as $time){
									$aTemp = $hourly_temp[$time];
									$cTemp = $hourly_concTemp[$time];
									$hum = $hourly_humidity[$time];
									$wSpd = $hourly_windspeed[$time];
									$cCover = $hourly_cloudcover[$time];
									
									?>
									<script>
									main.fillArrays(<?php echo $aTemp?>, <?php echo json_encode($time)?>, <?php echo $hum?>, <?php echo $wSpd?>, <?php echo $cTemp?>, <?php echo $cCover?>);
									</script>
									<?php
								}
								
								// fill in city and state
								?>
								<script>
								main.setCity(<?php echo json_encode($city)?>);
								main.setState(<?php echo json_encode($state)?>);
								</script>
								<?php
				
								// draw graph
								include $_SERVER['DOCUMENT_ROOT']."/html/graph.html";
								
							}
							catch (Exception $error){
								if( $error->getMessage() == "Invalid coordinates."){
									echo '
									<div class="alert alert-danger" role="alert">
										Invalid zipcode: Could not get data for zip code
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									</div>
									';
								}
							}
						} else {
							echo '
							<div class="alert alert-danger" role="alert">
								Please enter 5-digit numerical zip code
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							</div>
							';
						}
					}
					?>
					<div class="footer navbar-fixed-bottom">
					</div>
				</div>
				<div class="col-xs-0 col-md-0 col-lg-0"></div>
			</div>
		</div>
	</body>
</html>