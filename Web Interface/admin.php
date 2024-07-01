<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Irrigation System" />
	<meta name="keywords" content="HTML5,irrigation system" />
	<meta name="author" content="Dilshan Rodrigo"  />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<title>Admin</title>
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
			<li><a href="#">MANAGEMENT</a></li>
		</ul>
	</div>
	<?php require_once ("settings.php"); // connection info ?>
	<div class="form-row">	
		<div class="form-sub-row">
			<h3>Enter Username and Password</h3>
			<form method="post">
				<div class="form-column">
					<label for="username">Username </label>
					<input type="text" name="username" id="username" />
				</div>
				<div class="form-column">
					<label for="password">Password </label>
					<input type="text" name="password" id="password" />
				</div>
				<div class="form-column">
					<input type="submit" name="svbtn" value="LOG IN" />
				</div>
			</form>
		</div>
	</div>
	<?php 
		if(isset($_POST['svbtn'])) {
			if (isset ($_POST["username"]) && isset ($_POST["password"])) {
				$username = $_POST["username"];	
				$password = $_POST["password"];

				$conn = @mysqli_connect ($host, $user, $pwd, $sql_db); // Checks if connection is successful
				if (!$conn) {
					// Display an error message
					echo "<p>Database connection failure</p>";  
				} 
				else {
					$query = "select username, password from admins_tb where username = '$username' && password = PASSWORD('$password') ";
					$result = mysqli_query ($conn, $query);

					// checks if the execution was successful
					if (!$result){
						echo "<p>Something is wrong with ", $query, "</p>";
					}
					else {
						if (mysqli_num_rows($result) <= 0){ 
							echo '<script>alert("You cannot access to the admin page.")</script>';
						}
						else {
							header("Location: adminmode.php");
						}
					}
				}
			}
		}
	?>
</body>
</html>