<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Plastic Crack Risk Calculator</title>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
		<script src="libraries/bootstrap/js/bootstrap.min.js"></script>
		<script src="javascript/highcharts.js"></script>
		<script src="javascript/exporting.js"></script>
		<script src="javascript/grouped-categories.js"></script>
		<script src="javascript/GraphFunctions.js"></script>
		
		<!-- Bootstrap core CSS -->
		<link href="libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="libraries/bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="libraries/bootstrap/css/theme.css" rel="stylesheet">
		<?php session_start(); ?>
	</head>
	<body>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12">
					<?php 
						// show top nav bar and zipcode input
						include $_SERVER['DOCUMENT_ROOT']."/html/navbar.html";
					?>
					<div class="center-block row">
						<div class="col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
							<div class="alert alert-danger" id="alertFailInvalidZip" role="alert" hidden="true">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								Invalid zipcode: Could not get data for zip code
							</div>
							<div class="alert alert-danger" id="alertFailZipLength" role="alert" hidden="true">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								Please enter 5-digit numerical zip code
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-3 col-md-6">
							<form class="form-inline <?php echo isset($_POST['projectID']) ? 'hidden' : '' ?>" action="/index.php" method="get">
								<div align="center">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon">Unit</span>
											<select name="unit" class="form-control">
												<option>Standard</option>
												<option>Metric</option>
											</select>
										</div>
										<label class="sr-only" for="zipinput">Zip Code</label>
										<div class="input-group">
											<input style="min-width:250px" name="zip" id="zipinput" type="zip" class="form-control popover-show" pattern="\d{5}" maxLength="5" size="5" placeholder="zip code" data-trigger="manual" data-placement="bottom" data-content="Enter the zipcode of your project to view a shrinkage crack risk forcast">
											<span class="input-group-btn">
												<button class="btn btn-primary" type="submit">Go!</button>
											</span>
										</div>
										<script> var graphShown = false; </script>
									</div>
								</div>
							</form>
						</div>
						<div class="col-xs-offset-3 col-sm-offset-0 col-md-offset-0 col-lg-offset-0 col-xs-6 col-sm-3 col-md-3 col-lg-2 <?php echo (isset($_GET['zip']) || isset($_SESSION['id'])) ? 'hidden' : '' ?>">
							<div class="well well-sm row popover-show" data-trigger="manual" data-placement="bottom" data-content="Sign in to save projects and more!">
								<h4 class="text-center">Sign in</h4>
								<?php
									include $_SERVER['DOCUMENT_ROOT']."/html/login.html";
								?>
							</div>
						</div>
					</div>
					
					<?php
					$ZIPPATTERN = "/\b\d{5}\b/"; // usa zip code regex
					$GUEST = 0;
					$USER = 1;
					$PROJECT = 2;
					
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
							?><script> main = new Main(<?php echo json_encode($zip)?>, <?php echo $isMetric;?>); </script><?php
							
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
								
								?>
								<script>
								// fill in city and state
								main.setCity(<?php echo json_encode($city)?>);
								main.setState(<?php echo json_encode($state)?>);
								// clear zipcode popover
								var graphShown = true;
								</script>
								<?php
								
								// add to log
								require_once($_SERVER['DOCUMENT_ROOT'].'/classes/DbLog.php');
								if(isset($_POST['projectID']))
									$userType = $PROJECT;
								elseif(isset($_SESSION['id']))
									$userType = $USER;
								else
									$userType = $GUEST;
								$logger = new DbLog();
								$logger->addView($zip, $userType);
				
								// draw graph
								include $_SERVER['DOCUMENT_ROOT']."/html/graph.html";
								?>
								<div class="footer navbar-fixed-bottom">
									<p class="text-center"><font color="red">The results provided by the calculator are intended for educational and informational purposes only.</font></p>
								</div>
								<?php
								
							}
							catch (Exception $error){
								if( $error->getMessage() == "Invalid coordinates."){
									?><script>$('#alertFailInvalidZip').show()</script><?php
								}
							}
						} else {
							?><script>$('#alertFailZipLength').show()</script><?php
						}
					}
					?>
				</div>
			</div>
		</div>
		
		
		<script>
			if(!graphShown){
				$('.popover-show').popover('show');
				$('.popover-show').focus(
					function() {
						$('#zipinput').popover('hide');
					}
				);
				$('#zipinput').focusout(
					function() {
						if(!$('#zipinput').val()){
							$('#zipinput').popover('show');
						}
					}
				);
				$('.hidezippop').focus(
					function() {
						$('.popover-show').popover('hide');
					}
				);
			}
		</script>
		
	</body>
	
	
</html>