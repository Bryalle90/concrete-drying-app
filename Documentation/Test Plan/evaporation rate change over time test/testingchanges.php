<?php
ini_set('max_execution_time', 300);
require_once($_SERVER['DOCUMENT_ROOT'].'/../libraries/nusoap/nusoap.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../libraries/simple-nws/SimpleNWS.php');

$zips = array(62025, 59001, 90001);
$soapclient = new nusoap_client('http://www.weather.gov/forecasts/xml/SOAP_server/ndfdXMLserver.php?wsdl');
// instantiate graph helper class
?>
<script>
main = new Main(<?=json_encode($ziplist[0])?>, <?=$isMetric?>);
</script>
<?php

foreach($zips as $zip){
    $filename = $zip.".txt";
    $zipfile = fopen($filename, "a");
    
    $date = date('Y/m/d-H:i:s');
    fwrite( $zipfile, $date."\n" );
    
    $ziplist = array($zip);
    
    $LatLonList = $soapclient->call('LatLonListZipCode',$ziplist, 'uri:DWMLgen', 'uri:DWMLgen/LatLonListZipCode');
    $latlon = new SimpleXMLElement($LatLonList);
    $latlon = explode(',', $latlon->latLonList[0]);
    
    $simpleNWS = new SimpleNWS(floatval($latlon[0]), floatval($latlon[1]));
    
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
        
        $concTemp = pow($cTemp, 2.5);
        $humidPercent = $hum / 100;
        $airTemperature = pow($aTemp, 2.5);
        $wspd = 1 + (0.4 * $wSpd);
        $evap = ($concTemp - ($humidPercent * $airTemperature)) * $wspd * pow(10, -6);
        
        fwrite( $zipfile, $time." ".$evap."\n" );
    }
    fclose( $zipfile );
}
echo 'last refreshed: '.$date;
?>