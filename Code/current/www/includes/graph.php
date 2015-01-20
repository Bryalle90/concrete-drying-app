
<div id="container"></div>
<script>
		
$(function () {

	var evapArray = [<?php echo ''.implode(',', $main->getEvapArray()).'' ?>];
	var timeArray = [<?php echo '"'.implode('","', $main->getTimeArray()).'"' ?>];
	var cArray = [<?php echo ''.implode(',', $main->getCArray()).'' ?>];
	var tArray = [<?php echo ''.implode(',', $main->getTArray()).'' ?>];
	var hArray = [<?php echo ''.implode(',', $main->getHArray()).'' ?>];
	var wArray = [<?php echo ''.implode(',', $main->getWArray()).'' ?>];
	var cCover = [<?php echo ''.implode(',', $main->getCcArray()).'' ?>];
	

	var zipCode = '<?php echo($zipcode); ?>';
    
    $('#container').highcharts({
        title: {
            text: zipCode,
           // x: -20 //center
            align: 'center'
        },
        subtitle: {
            text: 'Subtitle',
            //x: -20
            align: 'center'
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            title: {
                text: 'Evaporation Rate'
            },
           // minorGridLineWidth: 0,
           // gridLineWidth: 0,
  plotBands: [{ // Green
                from: -.8,
                to: .15,
                color: 'rgba(0, 226, 0, 0.9)',
                label: {
                    text: 'LOW RISK',
      align: 'center',
                    style: {
                        color: 'black',
      fontWeight: 'bold'
                    }
                }
            }, { // yellow
                from: .15,
                to: .2,
                color: 'yellow',
                label: {
                    text: 'MODERATE RISK',
              align: 'center',
                    style: {
                        color: 'black',
              fontWeight: 'bold'
                    }
                }
            }, { // Red
                from: .2,
                to: 5,
                color: 'red',
                label: {
                    text: 'HIGH RISK',
              align: 'center',
                    style: {
                        color: 'black',
              fontWeight: 'bold'
                    }
                }
            }]
        },
        
        
tooltip: {
            formatter: function () {
                var s = '<b>' + this.x + '</b>';

                $.each(this.points, function () {
                    s += '<br/>' + this.series.name + ': ' +
                        this.y + 'm';
                });

                return s;
            },
            shared: true
        },
                
                
                
                
                
                
                
        plotOptions: {
            spline: {
                lineWidth: 4,
                states: {
                    hover: {
                        lineWidth: 5
                    }
                },
                marker: {
                    enabled: false
                },
                pointInterval: 3600000, // one hour
                pointStart: Date.UTC(2009, 9, 6, 0, 0, 0)
            }
        },
  credits: {
      enabled: false
  },
        series: [{
            name: 'Evaporation Rate: ',
           data: evapArray
        },
        {
        name: 'Temp',
        data: tArray,
        valueSuffix: 'Fasdf'

        },
        {
        name: 'Concrete Temp',
        data: cArray
        },
        {
        name: 'Humidity',
        data: hArray
        },
        {
        name: 'Wind',
        data: wArray
        },
        {
        name: 'Cloud Cover',
        data: cCover
        }
        ]
    });
});
		</script>