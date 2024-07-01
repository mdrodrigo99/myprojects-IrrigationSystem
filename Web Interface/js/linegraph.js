$(document).ready(function(){
  $.ajax({
    url : "http://localhost/irrigationsystem/readdatafromdb.php",
    type : "GET",
    success : function(data){
		console.log(data);

		var soilmoisture = [];
		var humidity = [];
		var temperature = [];
		var dateandtime = [];
		
		var len = data.length;
		
		for(var i = len-1; i >= 0; i--) {
			soilmoisture.push(data[i].soilmoisture);
			humidity.push(data[i].humidity);
			temperature.push(data[i].temperature);
			dateandtime.push(data[i].dateandtime);
		}

		var chartdata1 = {
			labels: dateandtime,
			datasets: [
			{
				label: "humidity",
				fill: false,
				lineTension: 0.1,
				backgroundColor: "rgba(29, 202, 255, 0.75)",
				borderColor: "rgba(29, 202, 255, 1)",
				pointHoverBackgroundColor: "rgba(29, 202, 255, 1)",
				pointHoverBorderColor: "rgba(29, 202, 255, 1)",
				data: humidity
			}
			]
		};
		
		var chartdata2 = {
			labels: dateandtime,
			datasets: [
			{
				label: "Temperature",
				fill: false,
				lineTension: 0.1,
				backgroundColor: "rgba(211, 72, 54, 0.75)",
				borderColor: "rgba(211, 72, 54, 1)",
				pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
				pointHoverBorderColor: "rgba(211, 72, 54, 1)",
				data: temperature
			}
			]
		};
		
		var chartdata3 = {
			labels: dateandtime,
			datasets: [
			{
				label: "Soilmoisture",
				fill: false,
				lineTension: 0.1,
				backgroundColor: "rgba(59, 89, 152, 0.75)",
				borderColor: "rgba(59, 89, 152, 1)",
				pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
				pointHoverBorderColor: "rgba(59, 89, 152, 1)",
				data: soilmoisture
			}
			]
		};
				
		var ctx1 = $("#mycanvas1");

		var LineGraph1 = new Chart(ctx1, {
			type: 'line',
			data: chartdata1
		});
		  
		var ctx2 = $("#mycanvas2");

		var LineGraph2 = new Chart(ctx2, {
			type: 'line',
			data: chartdata2
		}); 
		
		var ctx3 = $("#mycanvas3");

		var LineGraph3 = new Chart(ctx3, {
			type: 'line',
			data: chartdata3
		});
    },
    error : function(data) {

    }
  });
});