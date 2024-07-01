<!DOCTYPE html>

<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Irrigation System" />
	<meta name="keywords" content="HTML5,irrigation system" />
	<meta name="author" content="Dilshan Rodrigo"  />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css" />
    <title>Dashboard</title>
</head>
<body>
	<!-- navigation bar (vertical) -->
	<div id="vertical-nav">
		<ul>
			<li class="website-name"><a href="#" class="active-name" >Agro WebApp</a></li>
			<li><a href="index.php" class="active">Dashboard</a></li>
			<li><a href="report.php">Report</a></li>
			<li><a href="admin.php">Management</a></li>
		</ul>
	</div>
	<div style="margin-left:15.6%;padding:1px 16px;height:1000px;">

	<!-- navigation bar (horizontal) -->
	<div id="horizontal-nav">
		<ul>
			<li><a href="#">DASHBOARD</a></li>
		</ul>
	</div>	

	<!-- Three columns for gauge graphs -->
	<div class="label-row">
	  	<div class="label-column" style="margin-right: 25px;">
	  		<h3>Current Soil Moisture</h3>
	  	</div>
	  	<div class="label-column" style="margin-right: 25px;">
	  		<h3>Current Humidity</h3>
	  	</div>
  		<div class="label-column" style="margin-right: 25px;">
	  		<h3>Current Temperature</h3>	
  		</div>
		<div class="label-column">
	  		<h3>Current Water Level</h3>	
  		</div>
	</div>
	<div class="row">
	  	<div class="column" style="margin-right: 25px;">
	  		<canvas id="doughnut-chartcanvas-1"></canvas>
	  	</div>
	  	<div class="column" style="margin-right: 25px;">
	    	<canvas id="doughnut-chartcanvas-2"></canvas>
	  	</div>
	  	<div class="column" style="margin-right: 25px;">
	    	<canvas id="doughnut-chartcanvas-3"></canvas>
	  	</div>
		<div class="column">
	    	<canvas id="doughnut-chartcanvas-4"></canvas>
	  	</div>
	</div>
	<div class="line-graph-column">
		<h3>Soil Moisture Sensor Records</h3>
	</div>
	<div class="line-graph">
		<canvas id="mycanvas3"></canvas>
	</div>
	<div class="line-graph-column">
		<h3>Humidity Sensor Records</h3>
	</div>
	<div class="line-graph">
		<canvas id="mycanvas1"></canvas>
	</div>
	<div class="line-graph-column">
		<h3>Temperature Sensor Records</h3>
	</div>
	<div class="line-graph">
		<canvas id="mycanvas2"></canvas>
	</div>
	
	<!-- javascript -->
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/Chart.min.js"></script>
    <script type="text/javascript" src="js/linegraph.js"></script>
	<script type="text/javascript" src="js/gaugegraph.js"></script>
</body>
</html>