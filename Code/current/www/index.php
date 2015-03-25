<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="">
		<title>Home</title>
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
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
                    <?php
                    $ZIPPATTERN = "/\b\d{5}\b/"; // usa zip code regex
                    
                    // start session
                    session_start();
                
                    // show top nav bar and zipcode input
                    include $_SERVER['DOCUMENT_ROOT']."/includes/navbar.html";
                    include $_SERVER['DOCUMENT_ROOT']."/includes/enterzip.html";
                        
                    // require libraries
                    require_once($_SERVER['DOCUMENT_ROOT'].'/../libraries/nusoap/nusoap.php');
                    require_once($_SERVER['DOCUMENT_ROOT'].'/php/Noaa.php');
                        
                    if(isset($_GET['zip']))
                        // if the zip code provided is valid
                        if (preg_match($ZIPPATTERN, $_GET['zip'])){
                            $ziplist = array($_GET['zip']);
                            
                            $isMetric = 0;
                            if (isset($_GET['metric']))
                                $isMetric = 1;
                            
                            // instantiate graph helper class
                            ?>
                            <script>
                            main = new Main(<?=json_encode($ziplist[0])?>, <?=$isMetric?>);
                            </script>
                            <?php
                            
                            try{
                                $weatherService = new Noaa((int)$_GET['zip']);
                                $weatherService->update();
                            
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
                                    
                                    // fill arrays with weather data
                                    ?>
                                    <script>
                                    main.fillArrays(<?=$aTemp?>, <?=json_encode($time)?>, <?=$hum?>, <?=$wSpd?>, <?=$cTemp?>, <?=$cCover?>);
                                    </script>
                                    <?php
                                }
                            
                                // create new soap client to get city, state, and timezone
                                // http://webservicex.net/uszip.asmx
                                $soapclient = new nusoap_client('http://www.webservicex.net/uszip.asmx?WSDL', true);
                                
                                // get info about zip code
                                $zipinfo = $soapclient->call('GetInfoByZIP', array('USZip' => $ziplist[0]));
                                if($zipinfo != Null){
                                    $city = $zipinfo['GetInfoByZIPResult']['NewDataSet']['Table']['CITY'];
                                    $state = $zipinfo['GetInfoByZIPResult']['NewDataSet']['Table']['STATE'];
                                    if($state != 'GU')
                                        $tz = $zipinfo['GetInfoByZIPResult']['NewDataSet']['Table']['TIME_ZONE'];
                                    else
                                        $tz = "Chamorro";
                                } else {
                                    $city = Null;
                                    $state = Null;
                                    $tz = Null;
                                    echo '
                                    <div class="alert alert-danger" role="alert">
                                        error: request for zip info timed out, please try again
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    ';
                                }
                                
                                // fill in zip info
                                ?>
                                <script>
                                main.setCity(<?=json_encode($city)?>);
                                main.setState(<?=json_encode($state)?>);
                                main.setTimezone(<?=json_encode($tz)?>);
                                </script>
                                <?php
                
                                // draw graph
                                include $_SERVER['DOCUMENT_ROOT']."/includes/graph.html";
                                
                            }
                            catch (Exception $error){
                                if( $error->getMessage() == "Invalid coordinates."){
                                    echo '
                                    <div class="alert alert-danger" role="alert">
                                        invalid zipcode: Could not get coordinates from zip code
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    ';
                                }
                            }

                            $testObject = new Weather();
                            $testObject->connectdb();
                            echo '<pre>';
                            print_r($testObject->getWindSpeed(62258));
                            echo '</pre>';
                        } else {
                            echo '
                            <div class="alert alert-danger" role="alert">
                                Please enter 5-digit numerical zip code
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            ';
                        }
                    ?>
                </div>
                <div class="col-xs-0 col-md-0 col-lg-0"></div>
            </div>
        </div>
    </body>
</html>