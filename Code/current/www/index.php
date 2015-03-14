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
		<script src ="javascript/GraphFunctions.js"></script>
        
		<!-- Bootstrap core CSS -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap theme -->
		<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="bootstrap/css/theme.css" rel="stylesheet">
	</head>
	<body>
		<?php
            $ZIPPATTERN = "/\b\d{5}\b/"; // us zip code regex
            
			// start session
			session_start();
            
			// if user is logged in
            $loggedin = 0;
            if (isset($_SESSION['user']))
                $loggedin = 1;
		
			// show top nav bar and zipcode input
			include $_SERVER['DOCUMENT_ROOT']."/includes/navbar.html";
			include $_SERVER['DOCUMENT_ROOT']."/includes/enterzip.html";
				
			// require libraries
			require_once($_SERVER['DOCUMENT_ROOT'].'/../libraries/nusoap/nusoap.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/../libraries/simple-nws/SimpleNWS.php');
				
			// if the zip code is valid
			if(isset($_GET['zip']))
                if (preg_match($ZIPPATTERN, $_GET['zip'])){
                    $ziplist = array($_GET['zip']);
                    
                    $isMetric = 0;
                    if (isset($_GET['cb_metric']))
                        $isMetric = 1;
                    $customCTemp = 0;
                    if (isset($_GET['ctemp']) && $_GET['ctemp'] != NULL)
                        $customCTemp = 1;
                    
                    // create new soap client
                    // http://graphical.weather.gov/xml/
                    $soapclient = new nusoap_client('http://www.weather.gov/forecasts/xml/SOAP_server/ndfdXMLserver.php?wsdl');

                    // get lon and lat from zip code
                    $LatLonList = $soapclient->call('LatLonListZipCode',$ziplist,
                                                   'uri:DWMLgen',
                                                   'uri:DWMLgen/LatLonListZipCode');

                    $latlon = new SimpleXMLElement($LatLonList);
                    $latlon = explode(',', $latlon->latLonList[0]);
                    $simpleNWS = new SimpleNWS(floatval($latlon[0]), floatval($latlon[1]));
                    
                    ?>
                    <script>
                    main = new Main(<?=json_encode($ziplist[0])?>, <?=$isMetric?>);
                    </script>
                    <?php
                    try{
                        // request a forecast (getCurrentConditions(), getForecastForToday() or getForecastForWeek())
                        $forecast = $simpleNWS->getForecastForWeek();
                        $time_layouts = $forecast->getTimeLayouts();
                        $hourly_temp = $forecast->getHourlyRecordedTemperature();
                        $hourly_humidity = $forecast->getHourlyHumidity();
                        $hourly_windspeed = $forecast->getHourlyWindSpeed();
                        $hourly_cloudcover = $forecast->getHourlyCloudCover();
                        
                        // create new soap client
                        // http://webservicex.net/uszip.asmx
                        $soapclient = new nusoap_client('http://www.webservicex.net/uszip.asmx?WSDL', true);
                        
                        // get info about zip code
                        $result = $soapclient->call('GetInfoByZIP', array('USZip' => $ziplist[0]));
                        $city = $result['GetInfoByZIPResult']['NewDataSet']['Table']['CITY'];
                        $state = $result['GetInfoByZIPResult']['NewDataSet']['Table']['STATE'];
                        if($state != 'GU')
                            $tz = $result['GetInfoByZIPResult']['NewDataSet']['Table']['TIME_ZONE'];
                        else
                            $tz = "Chamorro";
                        ?>
                        <script>
                        main.setCity(<?=json_encode($city)?>);
                        main.setState(<?=json_encode($state)?>);
                        main.setTimezone(<?=json_encode($tz)?>);
                        </script>
                        <?php
			
                        // get the third time layout (Every 3 hours out to 72 hours, Every 6 hours out to 168 hours)
                        $i = 0;
                        foreach($time_layouts as $key => $value){
                            if ($i == 2)
                                $k = $key;
                            $i++;
                        }
                        
                        // fill the javascript weather arrays with values from simpleNWS
                        foreach($time_layouts[$k] as $time){
                            $aTemp = $hourly_temp[$time];
                            $cTemp = $hourly_temp[$time];
                            $hum = $hourly_humidity[$time];
                            $wSpd = $hourly_windspeed[$time];
                            $cCover = $hourly_cloudcover[$time];
                            if($customCTemp){
                                $cTemp = $_GET['ctemp'];
                            }
                            ?>
                            <script>
                            main.fillArrays(<?=$customCTemp?>, <?=$aTemp?>, <?php echo json_encode($time) ?>, <?=$hum?>, <?=$wSpd?>, <?=$cTemp?>, <?=$cCover?>);
                            </script>
                            <?php
                        }
                        
                        // draw graph
                        include $_SERVER['DOCUMENT_ROOT']."/includes/graph.html";
                    }
                    catch (\Exception $error){
                        if( $error->getMessage() == "Invalid coordinates."){
                            echo '<div class="alert alert-danger" role="alert">invalid zipcode: Could not get coordinates from zip code</div>';
                        }
                            
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">Please enter 5-digit numerical zip code</div>';
                }
		?>
        
    </body>
</html>