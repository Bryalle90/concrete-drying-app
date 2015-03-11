<?php
/**
 * Configuration class for the SimpleNWS library
 *
 * @author Cristian Radu <code@cristianradu.com>
 * @version 1.0
 * @package SimpleNWS
 */
class Configuration
{
    /**
     * @var string constant The URL for the National Weather Service interface
     */
    const C_NWS_URL = 'http://www.weather.gov/forecasts/xml/sample_products/browser_interface/ndfdXMLclient.php?';

    /**
     * @var array The allowed values for the timeframe parameter
     */
    static $allowedTimeframeValues = array('now', 'today', 'week');


    /**
     * The minimum and maximum latitude and longitude values for the CONUS (continental United States) grid
     * The four corners are:
     *     20.191999, -121.554001
     *     20.331773,  -69.208160
     *     50.105547,  -60.885558
     *     49.939721, -130.103438
     * The minimum and maximum latitude and longitude values for the Hawaii grid
     * The four corners are:
     *     18.066780,  -161.625245   
     *     18.066780,  -153.969181
     *     23.082138,  -153.969181
     *     23.082138,  -161.625245
     * The minimum and maximum latitude and longitude values for the Alaska grid
     * The four corners are:
     *     40.530101, -178.571000 
           41.739583, -124.580487
           63.975783,  -93.689151
           61.399866,  150.190699
     * The minimum and maximum latitude and longitude values for the Guam grid
     * The four corners are:
     *     12.349884, 143.686538
           12.349884, 148.280176
           16.794486, 148.280176
           16.794486, 143.686538
     * The minimum and maximum latitude and longitude values for the Puerto Rico grid
     * The four corners are:
     *     16.977485, -68.027833
           16.977485, -63.984474
           19.544617, -63.984474
           19.544617, -68.027833
     * from: http://www.weather.gov/forecasts/xml/SOAP_server/ndfdXMLclient.php?whichClient=CornerPoints&sector=conus
     */
     
    /**
     * @var float Minimum latitude
     */
    static $minLatitude_CONUS  =   20.19;
    /**
     * @var float Maximum latitude
     */
    static $maxLatitude_CONUS  =   50.11;
    /**
     * @var float Minimum longitude
     */
    static $minLongitude_CONUS = -130.11;
    /**
     * @var float Maximum longitude
     */
    static $maxLongitude_CONUS =  -60.87;
    
    // Hawaii
    static $minLatitude_HAWAII  =   18.06;
    static $maxLatitude_HAWAII  =   23.09;
    static $minLongitude_HAWAII = -161.63;
    static $maxLongitude_HAWAII = -153.96;
    // Alaska
    static $minLatitude_ALASKA  =   40.53;
    static $maxLatitude_ALASKA  =   63.98;
    static $minLongitude_ALASKA = -178.58;
    static $maxLongitude_ALASKA =  -93.68;
    // Guam
    static $minLatitude_GUAM  =   12.34;
    static $maxLatitude_GUAM  =   16.80;
    static $minLongitude_GUAM =  143.68;
    static $maxLongitude_GUAM =  148.29;
    // Puerto Rico
    static $minLatitude_PR  =   16.97;
    static $maxLatitude_PR  =   19.55;
    static $minLongitude_PR =  -68.03;
    static $maxLongitude_PR =  -63.98;
    


    /**
     * @var string The product type being returned. This is always 'time-series'
     */
    static $productType = 'product=time-series';

    /**
     * @var array The parameters to be submitted upon request
     */
    static $requestParameters = array('maxt',  // Maximum Temperature
                                      'mint',  // Minimum Temperature
                                      'temp',  // Temperature
                                      'appt',  // Apparent Temperature
                                      'wx',    // Weather
                                      'qpf',   // Liquid Precipitation Amount
                                      'snow',  // Snowfall Amount
                                      'sky',   // Cloud Cover Amount
                                      'wspd',  // Wind Speed
                                      'rh');   // Relative Humidity


    /**
     * The hour intervals that will be averaged for morning/afternoon/evening/night weather organization
     */
    /**
     * @var array The hours that will be averaged for the morning interval
     */
    static $morningInterval   = array('08', '11');
    /**
     * @var array The hours that will be averaged for the afternoon interval
     */
    static $afternoonInterval = array('11', '14', '17');
    /**
     * @var array The hours that will be averaged for the evening interval
     */
    static $eveningInterval   = array('17', '20', '23');
    /**
     * @var array The hours that will be averaged for the night interval
     */
    static $nightInterval     = array('23', '02', '05');
    /**
     * @var array The hours that will be averaged for the full day interval
     */
    static $fullDayInterval   = array('08', '11', '14', '17', '20', '23');
}
?>
