

//Constructor
function Main(zipCode, metric) {
    this.zipCode = zipCode;
    this.metric = metric;
    this.city = "";
    this.state = "";
    this.timezone = "";
    this.airTempArray = [];
    this.timeArray = [];
    this.humidityArray = [];
    this.windSpeedArray = [];
    this.concTempArray = [];
    this.cloudCoverArray = [];
    this.evapArray = [];
    

    this.fillArrays = function(customCTemp, aTemp, time, humidity, windSpeed, concTemp, cloudCoverage) {
        // if we want metric and the user specified a custom concrete temperature
        if(this.metric && customCTemp){
            concTemp = this.convertCtoF(concTemp);
        }
        
        // calculate evaporation rate and add it to the evap array
        this.addToEvapArray(this.calculateEvap(aTemp, humidity, windSpeed, concTemp));
        
        // add the time to the time array
        this.addTimeArray(time);
        
        // if we are using metric, convert the variables to metric values
        if(this.metric){
            this.addToArraysMetric(aTemp, humidity, windSpeed, concTemp, cloudCoverage);
        } else {
            this.addToArraysStd(aTemp, humidity, windSpeed, concTemp, cloudCoverage);
        }
    };

    // determines what concrete temperature is needed to change the risk to 'limit'
    this.getLowerRiskTemp = function(limit, windSpeed, humidity, airTemp) {
        atemp = Math.pow(airTemp, 2.5);
        humidPercent = humidity / 100;
        wspd = 1 + (0.4 * windSpeed);
        lim = limit*Math.pow(10,6);
        lowerTemp = Math.pow((lim/wspd) + humidPercent * atemp, 0.4)
        return (Math.round(lowerTemp));
    };

    // calculates evaporation rate for one point in time
    this.calculateEvap = function(airTemperature, humidity, windSpeed, concTemp) {
        ctemp = Math.pow(concTemp, 2.5);
        humidPercent = humidity / 100;
        atemp = Math.pow(airTemperature, 2.5);
        wspd = 1 + (0.4 * windSpeed);
        evap = (ctemp - (humidPercent * atemp)) * wspd * Math.pow(10, -6)
        return (evap);
    };
    
    this.addToArraysMetric = function(air_temp, humidity, windspeed, concrete_temp, cloud_cover){
        this.addAirTempArray(this.convertFtoC(air_temp));
        this.addHumidityArray(humidity);
        this.addWindSpeedArray(this.convertMphToKph(windspeed));
        this.addConcreteTempArray(this.convertFtoC(concrete_temp));
        this.addCloudCoverArray(cloud_cover);
    };
    
    this.addToArraysStd = function(air_temp, humidity, windspeed, concrete_temp, cloud_cover){
        this.addConcreteTempArray(concrete_temp);
        this.addAirTempArray(air_temp);
        this.addHumidityArray(humidity);
        this.addWindSpeedArray(windspeed);
        this.addCloudCoverArray(cloud_cover);
    };
    
    // Conversion functions
    this.convertMphToKph = function(mph){
        conversion_factor = 1.6093439987125;
        return (mph*conversion_factor);
    };
    
    this.convertKphToMph = function(kph){
        conversion_factor = 0.62137119223733;
        return (kph*conversion_factor);
    };
    
    this.convertFtoC = function(temp){
        conversion_factor = 5/9;
        return ((temp-32)*conversion_factor);
    };
    
    this.convertCtoF = function(temp){
        conversion_factor = 9/5;
        return ((temp*conversion_factor)+32);
    };
 
    // getters/setters
    this.setCity = function(city){
        this.city = city;
    };
    
    this.setState = function(state){
        this.state = state;
    };
    
    this.getCity = function(){
        return this.city;
    };
    
    this.getState = function(){
        return this.state;
    };
    
    this.setTimezone = function(timezone){
        this.timezone = timezone;
    };
    
    this.getTimezone = function(){
        return this.timezone;
    };
    
    this.isMetric = function(){
        return this.metric;
    };
    
    this.getZipCode = function() {
        return this.zipCode;
    };

    this.addToEvapArray = function(evapRate) {
        this.evapArray.push(evapRate);
    };
    
    this.getEvapArray = function(){
        return this.evapArray;
    };

    this.addAirTempArray = function(aTemp) {
        this.airTempArray.push(aTemp);
    };

    this.getAirTempArray = function() {
        return this.airTempArray;
    };
    this.addConcreteTempArray = function(concTemp) {
        this.concTempArray.push(concTemp);
    };

    this.getConcreteTempArray = function() {
        return this.concTempArray;
    };

    this.addTimeArray = function(time) {
        this.timeArray.push(time);
    };

    this.getTimeArray = function() {
        return this.timeArray;
    };

    this.addHumidityArray = function(hum) {
        this.humidityArray.push(hum);
    };

    this.getHumidityArray = function() {
        return this.humidityArray;
    };

    this.addWindSpeedArray = function(windSpeed) {
        this.windSpeedArray.push(windSpeed);
    };

    this.getWindSpeedArray = function() {
        return this.windSpeedArray;
    };

    this.addCloudCoverArray = function(cloudCover) {
        this.cloudCoverArray.push(cloudCover);
    };

    this.getCloudCoverArray = function() {
        return this.cloudCoverArray;
    };
}




