<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../libraries/nusoap/nusoap.php');
include 'DbZipInfo.php';

class DataService{
    
    private $zipcode;
    private $city;
    private $state;
    private $latitude;
    private $longitude;
    
    public function __construct($zip){
        $this->zipcode = $zip;
        
        $infodb = new DbZipInfo();
        
        if(!$infodb->checkZip($this->zipcode))
            $this->forceUpdate();
        $this->city = $infodb->getCity($zip);
        $this->state = $infodb->getState($zip);
        $this->latitude = $infodb->getLat($zip);
        $this->longitude = $infodb->getLon($zip);        
    }
    
    public function forceUpdate(){
        $infodb = new DbZipInfo();
        
        // get the longitude and latitude for a zipcode
        $soapclient = new nusoap_client('http://www.weather.gov/forecasts/xml/SOAP_server/ndfdXMLserver.php?wsdl');
        try{
            
            $LatLonList = $soapclient->call('LatLonListZipCode', array($this->zipcode), 'uri:DWMLgen', 'uri:DWMLgen/LatLonListZipCode');
            $latlon = new SimpleXMLElement($LatLonList);
            $latlon = explode(',', $latlon->latLonList[0]);
            
            $latitude = $latlon[0];
            $longitude = $latlon[1];
        }
        catch (Exception $error){
            $latitude = Null;
            $longitude = Null;
        }
        
        // get info about zip code
        // create new soap client to get city, state, and timezone
        // http://webservicex.net/uszip.asmx
        $soapclient = new nusoap_client('http://www.webservicex.net/uszip.asmx?WSDL', true);
        try{
            $zipinfo = $soapclient->call('GetInfoByZIP', array('USZip' => $this->zipcode));
            if($zipinfo != Null && $zipinfo['GetInfoByZIPResult'] != Null && 
                        $zipinfo['GetInfoByZIPResult']['NewDataSet'] != Null && 
                        $zipinfo['GetInfoByZIPResult']['NewDataSet']['Table'] != Null && 
                        $zipinfo['GetInfoByZIPResult']['NewDataSet']['Table']['CITY'] != Null && 
                        $zipinfo['GetInfoByZIPResult']['NewDataSet']['Table']['STATE'] != Null)
            {
                $city = $zipinfo['GetInfoByZIPResult']['NewDataSet']['Table']['CITY'];
                $state = $zipinfo['GetInfoByZIPResult']['NewDataSet']['Table']['STATE'];
            } else {
                $city = Null;
                $state = Null;
            }
        }
        catch (Exception $error){
            $city = Null;
            $state = Null;
        }
        
        if($infodb->checkZip($this->zipcode)){
            if($city != Null)
                $infodb->changeCity($this->zipcode, $city);
            if($state != Null)
                $infodb->changeState($this->zipcode, $state);
            if($latitude != Null)
                $infodb->changeLat($this->zipcode, $latitude);
            if($longitude != Null)
                $infodb->changeLon($this->zipcode, $longitude);
        } else {
            $infodb->addZip($this->zipcode, $city, $state, $latitude, $longitude);
        }
    }
    
    public function getCity(){
        return ($this->city);
    }
    
    public function getState(){
        return ($this->state);
    }
    
    public function getLat(){
        return ($this->latitude);
    }
    
    public function getLon(){
        return ($this->longitude);
    }
}
?>