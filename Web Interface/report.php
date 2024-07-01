<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Irrigation System" />
	<meta name="keywords" content="HTML5,irrigation system" />
	<meta name="author" content="Dilshan Rodrigo"  />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<title>Daily Report</title>
</head>
<body>
	<div id="vertical-nav">
		<ul>
			<li class="website-name"><a href="#" class="active-name" >Agro WebApp</a></li>
			<li><a href="index.php">Dashboard</a></li>
			<li><a href="report.php" class="active">Report</a></li>
			<li><a href="admin.php">Management</a></li>
		</ul>
	</div>
	<div style="margin-left:15.6%;padding:1px 16px;height:1000px;">
	<div id="horizontal-nav">
		<ul>
			<li><a href="#">REPORT</a></li>
		</ul>
	</div>
	<?php require_once ("settings.php"); // connection info ?>
	<?php require_once ("createtable.php"); ?>
	<?php 
		$conn = @mysqli_connect ($host, $user, $pwd, $sql_db); // Checks if connection is successful
		if (!$conn) {
			// Display an error message
			echo "<p>Database connection failure</p>";  
		} 
		else {
			$query = "select cast(dateandtime as date) AS date, soilmoisture, humidity, temperature, waterlevel, cast(dateandtime as time) AS time from readvalues_tb ORDER BY id DESC LIMIT 40";
			createTable($conn, $query);
		}
	?>
</body>
</html>