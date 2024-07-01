<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Irrigation System" />
	<meta name="keywords" content="HTML5,irrigation system" />
	<meta name="author" content="Dilshan Rodrigo"  />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<title>Admin_Mode Page</title>
</head>
<body>
	<div id="vertical-nav">
		<ul>
			<li class="website-name"><a href="#" class="active-name" >Agro WebApp</a></li>
			<li><a href="index.php">Dashboard</a></li>
			<li><a href="report.php">Report</a></li>
			<li><a href="admin.php" class="active">Management</a></li>
		</ul>
	</div>
	<div style="margin-left:15.6%;padding:1px 16px;height:1000px;">
	<div id="horizontal-nav">
		<ul>
			<li><a href="#">ADMIN MODE</a></li>
		</ul>
	</div>
	<?php require_once ("settings.php"); // connection info ?>
	<div class="form-row">	
		<div class="form-sub-row">
		<h3>Configure Threshold Values</h3>
			<form method="post">
				<div class="form-column">
					<label for="planttype">Plant Type </label>
					<input type="text" name="planttype" id="planttype" />
				</div>
				<div class="form-column">	
					<label for="tsoilmoisture">Threshold Soil Moisture </label>
					<input type="text" name="tsoilmoisture" id="tsoilmoisture" />
				</div>
				<div class="form-column">
					<label for="thumidity">Threshold Humitidy </label>
					<input type="text" name="thumidity" id="thumidity" />
				</div>
				<div class="form-column">
					<label for="ttemperature">Threshold Temperature </label>
					<input type="text" name="ttemperature" id="ttemperature" />
				</div>
				<div class="form-column">
				<input type="submit" name="btn" value="PROCESS" />
				</div>
			</form>
		</div>
	</div>
	<?php 
		if(isset($_POST['btn'])) {
			
				$planttype = $_POST["planttype"];	
				$tsoilmoisture = $_POST["tsoilmoisture"];
				$thumidity = $_POST["thumidity"];
				$ttemperature = $_POST["ttemperature"];

				$conn = @mysqli_connect ($host, $user, $pwd, $sql_db); // Checks if connection is successful
				if (!$conn) {
					// Display an error message
					echo "<p>Database connection failure</p>";  
				} 
				else {
					$query = "select plantid,planttype from thresholdvalues_tb";
					$result = mysqli_query ($conn, $query);
					if (!$result){
						echo "<p>Something is wrong with ", $query, "</p>";
					}
					else {
						if (mysqli_num_rows($result) > 0){ 
							$query = "DELETE FROM thresholdvalues_tb";
							$result = mysqli_query ($conn, $query);
							if (!$result){
								echo "<p>Something is wrong with ", $query, "</p>";
							}
						}
						$query = "INSERT INTO thresholdvalues_tb (planttype,tsoilmoisture,thumidity,ttemperature) VALUES ('$planttype','$tsoilmoisture','$thumidity','$ttemperature')";
						$result = mysqli_query ($conn, $query);
						if (!$result){
							echo "<p>Something is wrong with ", $query, "</p>";
						}
						else {
							header("Location: adminmode.php");
						}
					}
				}
			
		}
	?>
</body>
</html>