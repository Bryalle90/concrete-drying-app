<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<base href="bryalle.duckdns.org">
		<title>Home</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
		<script src="javascript/highcharts.js"></script>
		<script src="javascript/exporting.js"></script>
		<script src ="javascript/GraphFunctions.js"></script>
		
		<script src="bootstrap/js/bootstrap.min.js"></script>
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
			if(isset($_GET['tb_zip']))
                if (preg_match($ZIPPATTERN, $_GET['tb_zip'])){
                    $ziplist = array($_GET['tb_zip']);
                    
                    $isMetric = 0;
                    if (isset($_GET['cb_metric']))
                        $isMetric = 1;
                    $customCTemp = 0;
                    if (isset($_GET['tb_ctemp']) && $_GET['tb_ctemp'] != NULL)
                        $customCTemp = 1;
                        
                    // create soap client
                    // http://webservicex.net/uszip.asmx
                    $soapclient = new nusoap_client('http://www.webservicex.net/uszip.asmx?WSDL', true);
                    
                    // get info about zip code
                    $result = $soapclient->call('GetInfoByZIP', array('USZip' => $ziplist[0]));
                    $city = $result['GetInfoByZIPResult']['NewDataSet']['Table']['CITY'];
                    $state = $result['GetInfoByZIPResult']['NewDataSet']['Table']['STATE'];
                    $timezone = $result['GetInfoByZIPResult']['NewDataSet']['Table']['TIME_ZONE'];
                    
                    // decide UTC offset from time zone
                    // more info here: http://www.timetemperature.com/tzus/gmt_united_states.shtml
                    // alaska: K, hawaii: H, puerto rico: A, pacific: P, mountain: M, central: C, eastern: E
                    switch ($timezone) {
                        case 'P':
                            $offset = -8;
                            break;
                        case 'M':
                            $offset = -7;
                            break;
                        case 'C':
                            $offset = -6;
                            break;
                        case 'E':
                            $offset = -5;
                            break;
                        case 'K':
                            $offset = -9;
                            break;
                        case 'H':
                            $offset = -10;
                            break;
                        case 'A':
                            $offset = -4;
                            break;
                        default:
                            $offset = 0;
                    }
                    
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
                    <script>main = new Main(<?=$ziplist[0]?>, <?=$isMetric?>);</script>
                    <?php
                    try{
                        // request a forecast (getCurrentConditions(), getForecastForToday() or getForecastForWeek())
                        $forecast = $simpleNWS->getForecastForWeek();
                        $time_layouts = $forecast->getTimeLayouts();
                        $hourly_temp = $forecast->getHourlyRecordedTemperature();
                        $hourly_humidity = $forecast->getHourlyHumidity();
                        $hourly_windspeed = $forecast->getHourlyWindSpeed();
                        $hourly_cloudcover = $forecast->getHourlyCloudCover();
                            
                        // get the third time layout
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
                                $cTemp = $_GET['tb_ctemp'];
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
                            echo '<div class="alert alert-danger" role="alert">Please enter a valid zip code</div>';
                        }
                            
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">Please enter a valid zip code</div>';
                }
		?>
        
    </body>
</html>