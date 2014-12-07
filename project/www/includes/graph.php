<!DOCTYPE html>
<html>

	<div style="width:93%" >
		<div>
			<canvas id="canvas" height="300" width="600"></canvas>
		</div>
	</div>
	<script>
		var evapArray = [<?php echo '"'.implode('","', $main->getEvapArray()).'"' ?>];
		var timeArray = [<?php echo '"'.implode('","', $main->getTimeArray()).'"' ?>];
		var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
		var lineChartData = {
			labels : timeArray,
			datasets : [
				{
					label: "Evaporation Forecast",
			fillColor: "rgba(220,220,220,0.2)",
			strokeColor: "rgba(220,220,220,1)",
			//pointColor: "rgba(220,220,220,1)",
			pointColor: "black",
			lineColor: "green",
			pointStrokeColor: "#fff",
			pointHighlightFill: "#fff",
			pointHighlightStroke: "rgba(220,220,220,1)",
					data : evapArray
			
							
				}

			]

		}

		window.onload = function(){
			var ctx = document.getElementById("canvas").getContext("2d");
			window.myLine = new Chart(ctx).Line(lineChartData, {
				responsive: true
			});
			/*
			myLine.datasets[0].points[1].fillColor = "green";
			myLine.datasets[0].points[2].lineColor = "yellow";
			myLine.datasets[0].points[1].linecolor = "yellow";
			myLine.datasets[0].points[1].strokeColor = "green";
			myLine.datasets[0].points[1].pointColor = "yellow";
			myLine.datasets[0].points[2].fillColor = "yellow";
			myLine.datasets[0].points[2].strokeColor = "yellow";
			myLine.datasets[0].points[2].pointColor = "yellow";
			myLine.update();
			*/
		}
	</script>
</html>