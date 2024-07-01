$(document).ready(function() {
  $.ajax({
    url : "http://localhost/irrigationsystem/readdatafromdb.php",
    type : "GET",
    success : function(data){
      console.log(data);

    var soilmoisture = [];
	var humidity = [];
	var temperature = [];
	var waterlevel = [];
		
	var len = data.length;
	
	// gauge graph for soilmoisture
	soilmoisture.push(data[0].soilmoisture);
	soilmoisture.push(100 - data[0].soilmoisture);
	
	// gauge graph for humidity
	humidity.push(data[0].humidity);
	humidity.push(100 - data[0].humidity);
	
	// gauge graph for temperature
	temperature.push(data[0].temperature);
	temperature.push(100 - data[0].temperature);
	
	// gauge graph for waterlevel
	waterlevel.push(data[0].waterlevel);
	waterlevel.push(100 - data[0].waterlevel);

    var ctx1 = $("#doughnut-chartcanvas-1");
    var ctx2 = $("#doughnut-chartcanvas-2");
	var ctx3 = $("#doughnut-chartcanvas-3");
	var ctx4 = $("#doughnut-chartcanvas-4");

	var data1 = {
		labels : ["Soil Moisture"],
		datasets : [
		{
			label : "Current Soil Moisture",
			data: soilmoisture,
			backgroundColor: ["rgba(140, 223, 255, 0.2)"],
			borderColor: ["rgba(59, 147, 255,1)"],
			borderWidth: 1
		}
		]
	};
	
	var data2 = {
		labels : ["Humidity"],
		datasets : [
		{
			label : "Current Humidity",
			data: humidity,
			backgroundColor: ["rgba(140, 223, 255, 0.2)"],
			borderColor: ["rgba(59, 147, 255,1)"],
			borderWidth: 1
		}
		]
	};
	
	var data3 = {
		labels : ["Temperature"],
		datasets : [
		{
			label : "Current Temperature",
			data: temperature,
			backgroundColor: ["rgba(140, 223, 255, 0.2)"],
			borderColor: ["rgba(59, 147, 255,1)"],
			borderWidth: 1
		}
		]
	};
	
	var data4 = {
		labels : ["Water Level"],
		datasets : [
		{
			label : "Current Water Level",
			data: waterlevel,
			backgroundColor: ["rgba(140, 223, 255, 0.2)"],
			borderColor: ["rgba(59, 147, 255,1)"],
			borderWidth: 1
		}
		]
	};

    var options = {
		maintainAspectRatio: false,
		circumference: Math.PI + 1,
		rotation: -Math.PI - 0.5,
		cutoutPercentage: 64,
        legend : {
			display : true,
			position : "bottom"
        }
    };

    var chart1 = new Chart( ctx1, {
		type : "doughnut",
		data : data1,
        options : options
    });
	
	var chart2 = new Chart( ctx2, {
		type : "doughnut",
		data : data2,
        options : options
    });
	
	var chart3 = new Chart( ctx3, {
		type : "doughnut",
		data : data3,
        options : options
    });
	
	var chart4 = new Chart( ctx4, {
		type : "doughnut",
		data : data4,
        options : options
    });

    },
    error : function(data) {
      console.log(data);
    }
  });

});