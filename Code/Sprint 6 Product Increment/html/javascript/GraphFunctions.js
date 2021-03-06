

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
    

    this.fillArrays = function(aTemp, time, humidity, windSpeed, concTemp, cloudCoverage) {        
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
        return (lowerTemp);
    };

    // calculates evaporation rate for one point in time
    this.calculateEvap = function(airTemperature, humidity, windSpeed, concTemp) {
        ctemp = Math.pow(concTemp, 2.5);
        humidPercent = humidity / 100;
        atemp = Math.pow(airTemperature, 2.5);
        wspd = 1 + (0.4 * windSpeed);
        evap = (ctemp - (humidPercent * atemp)) * wspd * Math.pow(10, -6);
        if (evap < 0.00) {
            evap = 0;
        }
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
    
    // round 'num' to 'amt', cutting any trailing zeros
    this.round = function(num, amt) { 
        str1 = "e+"+amt;
        str2 = "e-"+amt;
        return +(Math.round(num + str1)  + str2);
    }
 
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
    
    this.getColor = function(numberOfSeries) {
        seriesColor = '';
        if (numberOfSeries == 0) { seriesColor = 'rgb(0, 0, 255)'; }
       else if (numberOfSeries == 1) { seriesColor = 'rgb(0, 0, 128)'; }
        else if (numberOfSeries == 2) { seriesColor = 'rgb(128, 0, 128)'; }
	else if (numberOfSeries == 3) { seriesColor = 'rgb(0,128,128)'; }
	else if (numberOfSeries == 4) { seriesColor = 'rgb(169, 169, 169)'; }
	
	else if (numberOfSeries == 5) { seriesColor = 'rgb(85, 107, 47)'; }
	else if (numberOfSeries == 6) { seriesColor = 'rgb(123, 104, 238)'; }
        else if (numberOfSeries == 7) { seriesColor = 'rgb(255, 20, 147)'; }
	else if (numberOfSeries == 8) { seriesColor = 'rgb(47, 79, 79)'; }
        else { seriesColor = 'rgba(1,1,1,1)'; }
        return seriesColor;
    };
}




