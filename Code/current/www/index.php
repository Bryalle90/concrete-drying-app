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
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-0 col-md-1">
                </div>
                <div class="col-xs-12 col-md-10">
                    <?php
                    $ZIPPATTERN = "/\b\d{5}\b/"; // usa zip code regex
                    
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
                        
                    if(isset($_GET['zip']))
                        // if the zip code provided is valid
                        if (preg_match($ZIPPATTERN, $_GET['zip'])){
                            $ziplist = array($_GET['zip']);
                            
                            $isMetric = 0;
                            if (isset($_GET['metric']))
                                $isMetric = 1;
                            
                            // create new soap client
                            // http://graphical.weather.gov/xml/
                            $soapclient = new nusoap_client('http://www.weather.gov/forecasts/xml/SOAP_server/ndfdXMLserver.php?wsdl');

                            // get lon and lat from zip code
                            $LatLonList = $soapclient->call('LatLonListZipCode',$ziplist,
                                                           'uri:DWMLgen',
                                                           'uri:DWMLgen/LatLonListZipCode');

                            // parse lon and let from response
                            $latlon = new SimpleXMLElement($LatLonList);
                            $latlon = explode(',', $latlon->latLonList[0]);
                            
                            // use simpleNWS to get weather info from lon/lat
                            $simpleNWS = new SimpleNWS(floatval($latlon[0]), floatval($latlon[1]));
                            
                            // instantiate graph helper class
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
                                    $hum = $hourly_humidity[$time];
                                    $wSpd = $hourly_windspeed[$time];
                                    $cCover = $hourly_cloudcover[$time];
                                    
                                    // predict concrete temperature based on air temperature 
                                    if($aTemp < 30)
                                        $cTemp = 40;
                                    elseif($aTemp < 55)
                                        $cTemp = $hourly_temp[$time] + 10;
                                    elseif($aTemp < 85)
                                        $cTemp = $hourly_temp[$time] + 5;
                                    else
                                        $cTemp = $hourly_temp[$time];
                                    
                                    // fill arrays with weather data
                                    ?>
                                    <script>
                                    main.fillArrays(<?=$aTemp?>, <?php echo json_encode($time) ?>, <?=$hum?>, <?=$wSpd?>, <?=$cTemp?>, <?=$cCover?>);
                                    </script>
                                    <?php
                                }
                                
                                // create new soap client to get city, state, and timezone
                                // http://webservicex.net/uszip.asmx
                                $soapclient = new nusoap_client('http://www.webservicex.net/uszip.asmx?WSDL', true);
                                
                                // get info about zip code (try multiple times because it is unreliable)
                                $i = 0;
                                while(!isset($zipinfo) && $i < 1){
                                    $zipinfo = $soapclient->call('GetInfoByZIP', array('USZip' => $ziplist[0]));
                                    $i++;
                                }
                                $city = $zipinfo['GetInfoByZIPResult']['NewDataSet']['Table']['CITY'];
                                $state = $zipinfo['GetInfoByZIPResult']['NewDataSet']['Table']['STATE'];
                                if($state != 'GU')
                                    $tz = $zipinfo['GetInfoByZIPResult']['NewDataSet']['Table']['TIME_ZONE'];
                                else
                                    $tz = "Chamorro";
                                    
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
                            catch (\Exception $error){
                                if( $error->getMessage() == "Invalid coordinates."){
                                    echo '
                                    <div class="alert alert-danger" role="alert">
                                        invalid zipcode: Could not get coordinates from zip code
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
                    ?>
                </div>
                <div class="col-xs-0 col-md-1">
                </div>
            </div>
        </div>
    </body>
</html>