

//Constructor
function Main(zipCode, metric) {
    this.zipCode = zipCode;
    this.metric = metric;
    this.airTempArray = [];
    this.timeArray = [];
    this.humidityArray = [];
    this.windSpeedArray = [];
    this.concTempArray = [];
    this.cloudCoverArray = [];
    this.evapArray = [];

    this.fillArrays = function(customCTemp, aTemp, time, humidity, windSpeed, concTemp, cloudCoverage) {
        if(this.metric && customCTemp){
            concTemp = this.convertCtoF(concTemp);
        }
        this.addToEvapArray(this.calculateEvapArray(aTemp, humidity, windSpeed, concTemp));
        this.addTimeArray(time);
        
        if(this.metric){
            this.addToArraysMetric(aTemp, humidity, windSpeed, concTemp, cloudCoverage);
        } else {
            this.addToArraysStd(aTemp, humidity, windSpeed, concTemp, cloudCoverage);
        }
    };

    this.getLowerRiskTemp = function(limit, windSpeed, humidity, airTemp) {
        return Math.round(Math.pow(((limit*Math.pow(10,6))/(1+(0.4 * windSpeed)))+(humidity / 100) * (Math.pow(airTemp, 2.5)), 0.4));
    };

    this.calculateEvapArray = function(airTemperature, humidity, windSpeed, concTemp) {
        return ((Math.pow(concTemp, 2.5) - ((humidity / 100) * Math.pow(airTemperature, 2.5))) * (1 + (0.4 * windSpeed)) * Math.pow(10, -6));
    };
    
    this.addToArraysMetric = function(air_temp, humidity, windspeed, concrete_temp, cloud_cover){
        this.addConcreteTempArray(this.convertFtoC(concrete_temp));
        this.addAirTempArray(this.convertFtoC(air_temp));
        this.addHumidityArray(humidity);
        this.addWindSpeedArray(this.convertMphToKph(windspeed));
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




