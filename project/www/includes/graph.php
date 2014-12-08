<!DOCTYPE html>
<html>
<div style="width:93% " >
<div><canvas id="canvas" height="300" width="600"></canvas> </div></div>

<script>
	var greenArr = [];
	var yellowArr = [];
	var redArr = [];
	var redColor = "rgba(255,0,0,0.04)";
	var yellowColor = "rgba(255,255,0,0.16)";
	var greenColor = "rgba(0,255,0,0.17)";
	var greenLine = .15;
	var yellowLine = .2;
	
	var scale = false;	
	var step;
	var stepWidth;
	var startValue;
	
	//var evapArray = [.04, .16, .04, .05, .03, .01, .02, .1, .1, .12, .1, .12, .1, .14, .1, .1, .1, .12, .1, .02, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1];
	//var evapArray = [.04, .07, .04, .05, .03, .01, .02, .1, .1, .12, .1, .12, .1, .04, .01, .01, .01, .02, .01, .02, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01];
	//var evapArray = [.16, .18, .18, .18, .18, .18, .18, .16, .162, .161, .164, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16];
	//var evapArray = [.3, .3, .3, .3, .46, .46, .66, .56, .76, .66, .66, .56, .46, .36, .26, .4, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56];

	var evapArray = [<?php echo '"'.implode('","', $main->getEvapArray()).'"' ?>];
	var timeArray = [<?php echo '"'.implode('","', $main->getTimeArray()).'"' ?>];
	var cArray = [<?php echo '"'.implode('","', $main->getCArray()).'"' ?>];
	var tArray = [<?php echo '"'.implode('","', $main->getTArray()).'"' ?>];
	var hArray = [<?php echo '"'.implode('","', $main->getHArray()).'"' ?>];
	var wArray = [<?php echo '"'.implode('","', $main->getWArray()).'"' ?>];
	var cCover = [<?php echo '"'.implode('","', $main->getCcArray()).'"' ?>];
	
	
	//document.write(wArray);

	var month = new Array();
	month[0] = "January";
	month[1] = "February";
	month[2] = "March";
	month[3] = "April";
	month[4] = "May";
	month[5] = "June";
	month[6] = "July";
	month[7] = "August";
	month[8] = "September";
	month[9] = "October";
	month[10] = "November";
	month[11] = "December";

	var year = [];
	var day = [];
	var hour = [];
	var tmpDay = []
	var tmphour = []
	
	var weekday = new Array(7);
	weekday[0] = "Sunday";
	weekday[1] = "Monday";
	weekday[2] = "Tuesday";
	weekday[3] = "Wednesday";
	weekday[4] = "Thursday";
	weekday[5] = "Friday";
	weekday[6] = "Saturday";

	var arrayLength = evapArray.length;
	var maxEvapRate = Math.max.apply(Math, evapArray);
	var minEvapRate = Math.min.apply(Math, evapArray);
	var range = maxEvapRate - minEvapRate;
	//document.write(timeArray[0]);
	
	for (var i=0; i<arrayLength; i++) {
		var string = timeArray[i];
		var partsArray = string.split('-');
		
		year[i] = partsArray[0];
		month[i] = ((partsArray[1]) - 1);
		day[i] = partsArray[2];
		hour[i] = partsArray[3];

		
		
		//document.write(year[i]);
		//document.write("   ");
		//document.write(month[i]);
		//document.write(" ");
		//document.write(day[i]);
		//document.write(" ");
		//document.write(hour[i]);
		//document.write("   ");
		var d = new Date(year[i], month[i], day[i], hour[i], 0, 0, 0);
		
		if (hour[i] >= 12) {
			var suffex = ' PM'
		}
		else{
			var suffex = ' AM'
		}
		
		if (hour[i] > 12) {
			hour[i] = (hour[i] - 12) + suffex;
			}
		else {
			hour[i] = hour[i] + suffex;
		}
		if (hour == '00') {
			hour[i] = 12 + suffex;
		}
		
		tmpDay[i] = weekday[d.getDay()];

	}
	var labelDate = [];
	
	labelDate[0] = tmpDay[0] + " " + hour[0]; 
	
	for (var i = 1; i < arrayLength; i++)
	{
		if (tmpDay[i] == tmpDay[i-1]){
			//document.write(tmpDay[i], tmpDay[i-1]);
			labelDate[i] = hour[i];
		}
		else{
			labelDate[i] = tmpDay[i] + " " + hour[i];
		}
	}
	
	// If everything is in the yellow
	if (minEvapRate >= greenLine && maxEvapRate <= yellowLine)
	{
		document.write("Everything in Yellow");
			scale = true;
			step = 15;
			stepWidth= .005;
			startValue = .14;
			
					for (i = 0; i < arrayLength; i++)  {
				 redArr[i] = startValue + (step * stepWidth);
			}
						for (i = 0; i < arrayLength; i++)  {
				 greenArr[i] = greenLine;
				}
			
								for (i = 0; i < arrayLength; i++)  {
				 yellowArr[i] = yellowLine;
			}

			
	}	
	
		
		//In yellow and red
		else if (minEvapRate >= greenLine && maxEvapRate >= yellowLine && minEvapRate <= yellowLine) {
		document.write("Everything in Yellow and red");
			
			
			scale = true;
			step = 15;
			//x = (maxEvapRate - minEvapRate)/step;
			x = (maxEvapRate - .12)/ step;
			stepWidth = x.toFixed(3);
			startValue = .14;
			
			
					for (i = 0; i < arrayLength; i++)  {
				 redArr[i] = startValue + (step * stepWidth);
			}

				for (i = 0; i < arrayLength; i++)  {
				 greenArr[i] = greenLine;
				}
			
								for (i = 0; i < arrayLength; i++)  {
				 yellowArr[i] = yellowLine;
			}
			
		
		}
		

		//in Yellow and Green
		else if (maxEvapRate <= yellowLine && maxEvapRate >= greenLine && minEvapRate < greenLine ) {
			document.write("Everything in Yellow and Green");
			scale = true;
			step = 15;
			x = (.21 - minEvapRate)/ step;
			stepWidth = x.toFixed(3);
			startValue = minEvapRate
			
					for (i = 0; i < arrayLength; i++)  {
				 redArr[i] = startValue + (step * stepWidth);
			}
						for (i = 0; i < arrayLength; i++)  {
				 greenArr[i] = greenLine;
				}
			
								for (i = 0; i < arrayLength; i++)  {
				 yellowArr[i] = yellowLine;
			}
			
			//redColor = "rgba(255,0,0,0.02)";
			

		
		}
		
		
		// Everything is in green
		else if (maxEvapRate < greenLine)
		{
			scale = true;
			step = 15;
			
			x = (.16 - minEvapRate)/ step;
			stepWidth = x.toFixed(3);
			startValue = minEvapRate
		document.write("Everything in Green");
		

			for (i = 0; i < arrayLength; i++)  {
				 greenArr[i] = greenLine;
			}
			
			for (i = 0; i < arrayLength; i++)  {
				 yellowArr[i] = startValue + (step * stepWidth);
			}
				
			
		}
		
		
		//If everything is in the red
		else if( minEvapRate > yellowLine)
		{
		document.write("Everything in red");
			scale = true;
			step = 15;
			x = (maxEvapRate - .15)/ step;
			stepWidth = x.toFixed(3);
			
			startValue = .15;
		
			
		document.write("Everything in red");
					for (i = 0; i < arrayLength; i++)  {
				  redArr[i] = startValue + (step * stepWidth);
			}
			
								for (i = 0; i < arrayLength; i++)  {
				 yellowArr[i] = yellowLine;
			}
		
		
}
		//Red, yellow, and Green
		else
		{
		document.write("Everything in Yellow, red, and green");		
		scale = true;
		step = 15;
		x = (maxEvapRate - minEvapRate)/ (step - 1);
		stepWidth = x.toFixed(3);
		startValue = minEvapRate;

		for (i = 0; i < arrayLength; i++)  {
		redArr[i] = startValue + (step * stepWidth);		}
		
		for (i = 0; i < arrayLength; i++)  {
			 greenArr[i] = greenLine;
		}
			
		for (i = 0; i < arrayLength; i++)  {
			 yellowArr[i] = yellowLine;
		}
	}			
		
		var lineChartData = {
			labels : labelDate,
			datasets : [
				{
					//label: "Evaporation Rate",
					fillColor: "rgba(0,0,0,.1)",
         		   strokeColor: "rgba(0,0,0,.1)",
           			 pointColor: "rgba(0,0,0,.1)",
            		pointHighlightStroke: "rgba(0,0,0,.1)",
            		showTooltip: false,
					data : evapArray		
					
				},
				{
				label: "Evaporation Rate" ,
				data : evapArray
				},
				
				{               
				//label: "Green",
				fillColor : greenColor,
            	strokeColor : "rgba(0,0,0,0)",
             	pointColor : "rgba(0,0,0,0)",
             	showTooltip: false,
				data : greenArr
				},
		
				{
                   // label: "Yellow",
                    fillColor: yellowColor,
                    strokeColor : "rgba(0,0,0,0)",
                    pointColor : "rgba(0,0,0,0)",
                    showTooltip: false,
                    data : yellowArr
                    
                },
                {
                   fillColor : redColor,
                    strokeColor : "rgba(0,0,0,0)",
                    pointColor : "rgba(0,0,0,0)",   
                    showTooltip: false,
                    data : redArr 
                },
                
                {
                
                label: "Air Temp",
                strokeColor : "rgba(0,0,0,0)",
                pointColor : "rgba(0,0,0,0)",
                
                //Get tArray
                data : tArray
				
                },
                {
               // fillColor: "rgba(220,220,220,0.15)",
                label: "Concrete Temp",
                strokeColor : "rgba(0,0,0,0)",
                pointColor : "rgba(0,0,0,0)",
                //Get conctempArray
                data : cArray
				
                },
                {
                
                label: "Humidity",
                strokeColor : "rgba(0,0,0,0)",
                pointColor : "rgba(0,0,0,0)",
                data : hArray
				
                },
                                {
                
                label: "Cloud Cover",
                strokeColor : "rgba(0,0,0,0)",
                pointColor : "rgba(0,0,0,0)",

                data : cCover
				
                },
                {
                
                label: "Wind Speed",
                strokeColor : "rgba(0,0,0,0)",
                pointColor : "rgba(0,0,0,0)",
        
                //Get windArray
                data : wArray
				
                }
			]
		}
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myLine = new Chart(ctx).Line(lineChartData, {
			responsive: true,
			scaleOverride: scale,
   			scaleSteps: step,
   			scaleStepWidth: stepWidth,
   			scaleStartValue: startValue,
   			tooltipFillColor: "#fff", 
   			tooltipFontColor: "black",
   			tooltipTitleFontColor: "black",
   			tooltipXOffset: 1,
   			multiTooltipTemplate: "<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%= value %>"
		});
	}


	</script>
</html>