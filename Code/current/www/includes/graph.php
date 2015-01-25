<div id="container"></div>
<div id="drag"></div>
<div id="drop"></div>

<script>
	
$(function () {
	var evapArray = [<?php echo '' . implode(',', $main->getEvapArray()) . '';?>];
	var timeArray = [<?php echo '"' . implode('","', $main->getTimeArray()) . '"';?>];
	var cArray = [<?php echo '' . implode(',', $main->getCArray()) . '';?>];
	var tArray = [<?php echo '' . implode(',', $main->getTArray()) . '';?>];
	var hArray = [<?php echo '' . implode(',', $main->getHArray()) . '';?>];
	var wArray = [<?php echo '' . implode(',', $main->getWArray()) . '';?>];
	var cCover = [<?php echo '' . implode(',', $main->getCcArray()) . '';?>];
	var isMetric = <?php echo json_encode($isMetric);?>;
	var zipCode = '<?php echo ($zipcode);?>';
	
	var monthname = new Array();
	monthname[0] = "January";
	monthname[1] = "February";
	monthname[2] = "March";
	monthname[3] = "April";
	monthname[4] = "May";
	monthname[5] = "June";
	monthname[6] = "July";
	monthname[7] = "August";
	monthname[8] = "September";
	monthname[9] = "October";
	monthname[10] = "November";
	monthname[11] = "December";
	
	var year = [];
	var day = [];
	var hour = [];
	var month = [];
	var tmpMonth = [];
	var tmpDay = [];
	var tmphour = [];
	
	var weekday = new Array(7);
	weekday[0] = "Sun";
	weekday[1] = "Mon";
	weekday[2] = "Tues";
	weekday[3] = "Wed";
	weekday[4] = "Thur";
	weekday[5] = "Fri";
	weekday[6] = "Sat";

	var arrayLength = evapArray.length;
	var maxEvapRate = Math.max.apply(Math, evapArray);
	var minEvapRate = Math.min.apply(Math, evapArray);
	var range = maxEvapRate - minEvapRate;
	//document.write(timeArray);
	
	var yaxislabel = "Evaporation Rate";
	if (isMetric) {
		yaxislabel += " (kg/m\xB2/hr)";
	}
	else {
		yaxislabel += " (lb/ft\xB2/hr)";
	}
	
	for (var i=0; i<arrayLength; i++) {
		var string = timeArray[i];
		var partsArray = string.split('-');
		
		year[i] = partsArray[0];
		month[i] = partsArray[1];
		day[i] = partsArray[2];
		hour[i] = partsArray[3];
		

		var d = new Date(partsArray[0], ((partsArray[1]) - 1), partsArray[2], partsArray[3], 0, 0, 0);
		
		if (hour[i] > 12) {
			
			hour[i] = (hour[i] - 12) + ' PM';
			
		}
		else if (hour[i] == 0 ) {
			hour[i] = 12 + ' AM';
		}
		
		//Works
		else if (hour[i] == 12)  {
			hour[i] = 'Noon';
		}
		else{
			hour[i] += ' AM';
		}
		/*
		
		if (hour[i] > 12) {
			hour[i] = (hour[i] - 12) + suffex;
			}
		else {
			hour[i] = hour[i] + suffex;
		}

		if (hour[i] == '10') {
			
			hour[i] = 12 + suffex;
			
		}
		*/
		//month[i] = d.getMonth();
		 year[i] = d.getFullYear().toString().substr(2,2);
		// year[i] = year[i]
		tmpMonth[i] = monthname[d.getMonth()];
		tmpDay[i] = weekday[d.getDay()];
		//document.write(d.getDate() + ' '+ d.getMonth() + ' ' + d.getFullYear());
	}
	//document.write(d.getDate + ' '+ d.getMonth + ' ' + d.getFullYear);
	//document.write(hour);
	var labelDate = [];
	//labelDate = hour;
	
	labelDate[0] = month[0] + "/" + day[0] + "/" + year[0] + " " + hour[0]; 
	var subtitle = tmpMonth[0] + " " + day[0] + " - "+ tmpMonth[tmpMonth.length - 1] + " " + day[day.length - 1];
	//document.write(subtitle);

	for (var i = 1; i < arrayLength; i++)
	{
		if (tmpDay[i] == tmpDay[i-1]){
			//document.write(tmpDay[i], tmpDay[i-1]);
			//labelDate[i] = "<br>" + hour[i];
			labelDate[i] = month[i] + "/" +day[i] + "/" + year[i] + " " + hour[i];
			

		}
		else{
				

			labelDate[i] = month[i] + "/" + day[i] + "/" + year[i] + " " +hour[i];
		}
	}
	//document.write(labelDate);
    
    $('#container').highcharts({
	title: {
	    text: zipCode,
	   // x: -20 //center
	    align: 'center'
	},
	subtitle: {
	    text: subtitle,
	    //x: -20
	    align: 'center'
	},
	
	
	legend:
	{
		enabled: false
		/*
		borderWidth: 2,
		align: 'right',
		verticalAlign: 'top',
	    
		labelFormatter: function ()
		{
			return '......';
		},
	    
		useHTML: true
		*/
        },
	
	xAxis:
	{
		categories: labelDate,
		
		labels:
		{
			rotation: -45,
			style:
			{
				fontSize: '13px'
				//fontFamily: 'Verdana, sans-serif'
			},
		},
	   
	},
	
	yAxis:
	{
		gridLineDashStyle: 'longdash',
		title:
		{
			text: yaxislabel
		},
		// minorGridLineWidth: ,
		//gridLineWidth: ,
		gridLineColor: 'black',
		plotBands:
		[{ 
			// Green
			from: -.8,
			to: .15,
			color: 'rgba(0, 226, 0, 0.7)',
			label:
			{
				//zIndex: 10,
				text: 'LOW RISK',
				align: 'center',
				style:
				{
					color: 'black',
					fontSize: '18px',
					fontWeight: 'bold'
				}
	}
	    }, { // yellow
		from: .15,
		to: .2,
		color: 'rgba(255,255,0,.7)',
		label: {
		    text: 'MODERATE RISK',
	      align: 'center',
		    style: {
			color: 'black',
			fontSize: '18px',
	      fontWeight: 'bold'
		    }
		}
	    }, { // Red
		from: .2,
		to: 5,
		color: 'rgba(255, 0, 0, 0.77)',
		label: {
		    text: 'HIGH RISK',
	      align: 'center',
		    style: {
			color: 'black',
			fontSize: '18px',
	      fontWeight: 'bold'
		    }
		}
	    }]
	},
	
      

    tooltip: {
	shared: false,
	//borderColor: 'green',
	borderWidth: 2.5,
	//borderRadius: 7,
	useHTML: true,
	formatter: function() {
		
		var newSeries = this.series;
		var index = $.inArray(this.x, labelDate);
		
		if (this.y > .2) {
		//	alert ("wo");
			
			this.series.color = 'rgba(255, 0, 0, 0.8)';
		}
		else if (this.y >= .15 && this.y <= .2) {
			
			this.series.color = 'yellow';
			//'rgba(0, 226, 0, 0.7)';
		}
		else {
			
			this.series.color = 'green';
			//'rgba(255,255,0,.7)';
		}
		
		

	    var s = '<b>'+ this.x +'</b>';
	    s += '<span style="color:' + this.series.color + '"> </span>';
	   
	// If Standard
	    if (isMetric == false) {
	    //alert(i);
	    s += '<br/> <b>'+ this.series.name + ': ' + this.y +'lb/ft'+'\xB2'+'/hr </b> ';
	    s += '<br/>	\u2022 Temperature: ' + tArray[index] + '\xB0'+'F';
	    s += '<br/> \u2022 Concrete Temperature: ' + cArray[index]+ '\xB0'+'F';
	    s += '<br/>	\u2022 Wind: ' + wArray[index] + 'mph';
	    s += '<br/>	\u2022 Relative Humidity: ' + hArray[index]+ '%';
	    s += '<br/>	\u2022 Cloud Cover: ' + cCover[index] +'%';
	    
	    if (this.y > .2){
		//(evaporationRate*1000000) = 200,000
		suggestedLowConcTemp = Math.round(Math.pow(((150000)/(1+(0.4*wArray[index])))+(hArray[index]/100) * (Math.pow(tArray[index], 2.5)), .4));
		suggestedModConcTemp = Math.round(Math.pow(((200000)/(1+(0.4*wArray[index])))+(hArray[index]/100) * (Math.pow(tArray[index], 2.5)), .4));
		s+= '<br/> \u2022 Concrete temp needed for moderate risk: ' + suggestedModConcTemp + '\xB0'+'F';
		s+= '<br/> \u2022 Concrete temp needed for low risk: ' + suggestedLowConcTemp + '\xB0'+'F';
	    }
	    else if(this.y > .15){
		suggestedLowConcTemp = Math.round(Math.pow(((150000)/(1+(0.4*wArray[index])))+(hArray[index]/100) * (Math.pow(tArray[index], 2.5)), .4));
		s+= '<br/> \u2022 Concrete temp needed for low risk: ' + suggestedLowConcTemp + '\xB0'+'F';		

		//s+= '<br/> Concrete temperature can be +addValue+ and still have a low risk (ADD a show point on graph button?) ' + sug;
		//s+= '<br/> A concrete temp over +addvalue+ and their is a high risk of shrinkage crack  ';
	    }
	    /*
	    else {
		s+= '<br/> A concrete temp of +addValue+ with have a low chance of craking  ';
		s+= '<br/> A concrete temp over +addvalue+ and their is a high risk of shrinkage crack  ';

	    }
	    */
	    }
	    //else metric
	    else{
	    s += '<br/> <b>'+ this.series.name + ': ' + this.y +'kg/m'+'\xB2'+'/hr</b>';
	    s += '<br/>	\u2022 Temperature: ' + Math.round(tArray[index]) + '\xB0'+'C';
	    s += '<br/>	\u2022 Concrete Temperature: ' + Math.round(cArray[index])+ '\xB0'+'C';
	    s += '<br/>	\u2022 Wind: ' + Math.round(wArray[index]) + 'kph';
	    s += '<br/>	\u2022 Relative Humidity: ' + hArray[index] + '%';
	    s += '<br/>	\u2022 Cloud Cover: ' + cCover[index] + '%';
		
	if (this.y > .2){
		tmpAirTemp = tArray[index] * 9 / 5 + 32	
		//tmpConcTemp = cArray[index] * 9 / 5 + 32
		tmpWindSpeed = wArray[index] * 0.62137119223733
		tmpLow = Math.pow(((150000)/(1+(0.4*tmpWindSpeed)))+(hArray[index]/100) * (Math.pow(tmpAirTemp, 2.5)), .4);
		tmpModerate = Math.pow(((200000)/(1+(0.4*tmpWindSpeed)))+(hArray[index]/100) * (Math.pow(tmpAirTemp, 2.5)), .4);
		suggestedLowConcTemp = Math.round((((tmpLow - 32) * 5) / 9));
		suggestedModConcTemp = Math.round((((tmpModerate - 32) * 5) / 9));
		s+= '<br/> \u2022 Concrete temp needed for moderate risk: ' + suggestedModConcTemp + '\xB0'+'C';
		s+= '<br/> \u2022 Concrete temp needed for low risk airTemp:' +  suggestedLowConcTemp + '\xB0'+'C';
	    }
	    
	    else if(this.y > .15 ) {
		tmpAirTemp = tArray[index] * 9 / 5 + 32	
		//tmpConcTemp = cArray[index] * 9 / 5 + 32
		tmpWindSpeed = wArray[index] * 0.62137119223733
		tmpLow = Math.pow(((150000)/(1+(0.4*tmpWindSpeed)))+(hArray[index]/100) * (Math.pow(tmpAirTemp, 2.5)), .4);
		tmpModerate = Math.pow(((200000)/(1+(0.4*wArray[index])))+(hArray[index]/100) * (Math.pow(tArray[index], 2.5)), .4);
		suggestedLowConcTemp = Math.round((((tmpLow - 32) * 5) / 9));
		
		s+= '<br/> \u2022 Concrete temp needed for low risk: ' + suggestedLowConcTemp + '\xB0'+'C';
	    }
	    /*
	    else {
		s+= '<br/> A concrete temp of +addValue+ with have a low chance of craking  ';
		s+= '<br/> A concrete temp over +addvalue+ and their is a high risk of shrinkage crack  ';

	    }
	    */
	    
	    }
	    
	    return s;
	
       },
       
      // borderColor: testColor
       //shared: false
       },

    
   plotOptions: {
	series: {
		color: 'black',
	    //cursor: 'ns-resize',
	    cursor: 'pointer',
	    allowPointSelect: true,
	    point: {
		/*
		shadow: {
			color: 'black',
			width: 10,
			offsetX: 0,
			offsetY: 0,
			},
			*/
	events: {
	select: function () {

$('#divForm').html(			

this.category + " -  Evaporation Rate: <input type='text' style = 'width:40px' name= 'EvapRate' value= "
+ this.y + " readonly> Temperature: <input type='text' style = 'width:40px' name= 'temp' value= "
+ tArray[this.index] + " readonly> Concrete Temperature <input type='text' style = 'width:40px' maxlength = '5' name= 'concTemp' id = 'concTemp' value= "
+ cArray[this.index] + " autofocus>Wind Speed: <input type='text' style = 'width:40px' name= 'windspeed' value= "
+ wArray[this.index] + " readonly> Relative Humidity: <input type='text' style = 'width:40px' name= 'humidity' value= "
+ hArray[this.index] + " readonly> Cloud Cover: <input type='text' style = 'width:40px' name= 'cloudCover' value= "
+ cCover[this.index] + " readonly>"


);
$('#button').html(
	"<br> <button id = 'button'> Change Concrete Temp </button>"
);
		  //      chart.series[0].data[this.index].update(.15);

		//var chart = $('#container').highcharts();
		//chart.series[0].data[this.id].update(.3);
	},
		/*
		    drag: function (e) {
			
			if (e.newY > 1) {
			    this.y = 1;
			    return false;
			}
			
			if (e.newY < 0) {
			    this.y = 0;
			    return false;
			}
			
			$('#drag').html(
			    'Dragging <b>' + this.series.name + '</b>, <b>' + this.category + '</b> to <b>' + Highcharts.numberFormat(e.newY, 2) + '</b>');
		    },
		    drop: function () {
			$('#drop').html(
			    'In <b>' + this.series.name + '</b>, <b>' + this.category + '</b> was set to <b>' + Highcharts.numberFormat(this.y, 2) + '</b>');
			    
		    }
		    */
		}
	    },
	   // stickyTracking: false
	},
	/*
	column: {
	    stacking: 'normal'
	}
	*/
    },
	
  credits: {
      enabled: false
  },
	series: [{
	    name: 'Evaporation Rate',
	    data: evapArray,
	    draggableY: true

	}]
    }
    /*
    ,function(chart){
        
            var max = .2;
            
            $.each(chart.series[0].data,function(i,data){
                
                if(data.y > max)
                   data.update({
                        color:'red'
                    });

                
            });
        
        }
	*/
	);

$("#button").click(function() {
	var chart = $('#container').highcharts();
		
		selectedPoints = chart.getSelectedPoints();
		i = selectedPoints[0].index
		
		updatedCTemp = document.getElementById('concTemp').value
		cArray[i] = updatedCTemp;
		evapArray[i] = ((Math.pow(updatedCTemp, 2.5) - ((hArray[i] / 100) * Math.pow(tArray[i], 2.5))) * (1 + (0.4 * wArray[i])) * Math.pow(10, -6));
		chart.series[0].data[i].update(evapArray[i]);
		chart.series[0].options.color = "black";
		chart.series[0].update(chart.series[0].data[i].options);
		//chart.tooltip.refresh(chart.series[0].points[i]);
	
});


    
});

		</script>
		<div id="divForm"></div>
		<div id ="button"></div>
		
		
