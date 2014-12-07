<!DOCTYPE html>
<html>

		<div style="width:93%" >
			<div>
				<canvas id="canvas" height="300
				" width="600"></canvas>
			</div>
		</div>


	<script>
		var greenArr = [];
		var yellowArr = [];
		var redArr = [];
		var redColor = "rgba(255,0,0,0.04)";
		var yellowColor = "rgba(255,255,0,0.16)";
		var greenColor = "rgba(0,255,0,0.17)";
		var greenLine = .15
		var yellowLine = .2
		var scale = false;
		
		//var evapArray = [.04, .16, .04, .05, .03, .01, .02, .1, .1, .12, .1, .12, .1, .14, .1, .1, .1, .12, .1, .02, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1, .1];
		//var testData = [.04, .07, .04, .05, .03, .01, .02, .1, .1, .12, .1, .12, .1, .04, .01, .01, .01, .02, .01, .02, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01, .01];
		//var evapArray = [.16, .18, .18, .18, .18, .18, .18, .16, .162, .161, .164, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16, .16];
		//var evapArray = [.3, .3, .3, .3, .46, .46, .66, .56, .76, .66, .66, .56, .46, .36, .26, .4, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56, .56];
		var evapArray = [<?php echo '"'.implode('","', getEvapArray()).'"' ?>];
		var timeArray = [<?php echo '"'.implode('","', getTimeArray()).'"' ?>];
		var arrayLength = evapArray.length;
			var step;
			var stepWidth;
			var startValue;

		
		var maxEvapRate = Math.max.apply(Math, evapArray);
		var minEvapRate = Math.min.apply(Math, evapArray);
		document.write(maxEvapRate);
		document.write(" ");
		document.write(minEvapRate);
		document.write(" ");
		document.write(arrayLength);
		
		// If everything is in the yellow
		if (minEvapRate >= greenLine && maxEvapRate <= yellowLine)
		{
		document.write("Everything in Yellow");

			for (i = 0; i < arrayLength; i++)  {
				 yellowArr[i] = yellowLine;
			}
		}
		
		//In yellow and red
		else if (minEvapRate >= greenLine && maxEvapRate >= yellowLine && minEvapRate <= yellowLine) {
		document.write("Everything in Yellow and red");
		
			for (i = 0; i < arrayLength; i++)  {
				 yellowArr[i] = yellowLine;
			}
			for (i = 0; i < arrayLength; i++)  {
				 redArr[i] = maxEvapRate;
			}
			yellowColor = "rgba(255,255,0,0.3)";
		
		}

				//in Yellow and Green
		else if (maxEvapRate <= yellowLine && maxEvapRate >= greenLine && minEvapRate < greenLine ) {
		document.write("Everything in Yellow and Green");
					for (i = 0; i < arrayLength; i++)  {
				 greenArr[i] = greenLine;
				}
				
					for (i = 0; i < arrayLength; i++)  {
				 yellowArr[i] = yellowLine;
			}
		
		}
		
		
		// Everything is in green
		else if (maxEvapRate < greenLine)
		{
		document.write("Everything in Green");
		
			for (i = 0; i < arrayLength; i++)  {
				 greenArr[i] = greenLine;
				}
				
			scale = true;
			step = 15;
			stepWidth= .01;
			startValue = 0
			
		}
		
		
		//If everything is in the red
		else if( minEvapRate > yellowLine)
		{
		document.write("Everything in red");
			for (i = 0; i < arrayLength; i++)  {
				 redArr[i] = maxEvapRate;
			}
			redColor = "rgba(255,0,0,.1)"
		
}
		//Red, yellow, and Green
		else{
		document.write("Everything in Yellow, red, and green");
					for (i = 0; i < arrayLength; i++)  {
				 redArr[i] = maxEvapRate;
			}
						for (i = 0; i < arrayLength; i++)  {
				 greenArr[i] = greenLine;
				}
			
								for (i = 0; i < arrayLength; i++)  {
				 yellowArr[i] = yellowLine;
			}
		}
		
		
		
		var lineChartData = {
			labels : timeArray,
			datasets : [
				{
					label: "Evaporation Forecast",
					fillColor: "rgba(220,220,220,0.15)",
         		   strokeColor: "rgba(220,220,220,1)",
           			 //pointColor: "rgba(220,220,220,1)",
           			 pointColor: "black",
           			pointStrokeColor: "#fff",
            		pointHighlightFill: "#fff",
            		pointHighlightStroke: "rgba(220,220,220,1)",
					data : evapArray		
				},
				
				{               
				//label: "Green",
				fillColor : greenColor,
            	strokeColor : "rgba(0,0,0,0)",
             	pointColor : "rgba(0,0,0,0)",
				data : greenArr
				},
		
				{
                   // label: "Yellow",
                    fillColor: yellowColor,
                    strokeColor : "rgba(0,0,0,0)",
                    pointColor : "rgba(0,0,0,0)",
                    data : yellowArr
                    
                },
                {
                   fillColor : redColor,
                    strokeColor : "rgba(0,0,0,0)",
                    pointColor : "rgba(0,0,0,0)",   
                    data : redArr 
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
   			scaleStartValue: startValue
   			
			
		});
		
		
			
		
		
		
	}


	</script>
</html>