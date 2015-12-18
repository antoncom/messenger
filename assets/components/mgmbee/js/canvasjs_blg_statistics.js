$(document).ready(function() {
	var chart = new CanvasJS.Chart("chartContainer",
		{
			zoomEnabled: false,
			animationEnabled: true,
			title:{
				text: "Статистика активаций промо-кодов по акциям"
			},
			axisX:{
				valueFormatString: "MM-YYYY" ,
				labelAngle: -50,
				interval: 1,
				intervalType: "month"
			},
			axisY2:{
				valueFormatString:"0",

				maximum: 100,
				interval: 10,
				interlacedColor: "#F5F5F5",
				gridColor: "#D7D7D7",
				tickColor: "#D7D7D7"
			},
			theme: "theme2",
			toolTip:{
				shared: true
			},
			legend:{
				verticalAlign: "bottom",
				horizontalAlign: "center",
				fontSize: 15,
				fontFamily: "Lucida Sans Unicode"

			},
			data: [
				{
					type: "spline",
					lineThickness:3,
					axisYType:"secondary",
					showInLegend: true,
					name: "Промо-1",
					dataPoints: [
						{ x: new Date(2015, 0), y: 10 },
						{ x: new Date(2015, 1), y: 20 },
						{ x: new Date(2015, 2), y: 80 },
					]
				},
				{
					type: "spline",
					lineThickness:3,
					showInLegend: true,
					name: "Промо-2",
					axisYType:"secondary",
					dataPoints: [
						{ x: new Date(2015, 0), y: 5 },
						{ x: new Date(2015, 1), y: 7 },
						{ x: new Date(2015, 2), y: 3 },
					]
				}




			],
			legend: {
				cursor:"pointer",
				itemclick : function(e) {
					if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
						e.dataSeries.visible = false;
					}
					else {
						e.dataSeries.visible = true;
					}
					chart.render();
				}
			}
		});

	chart.render();
});