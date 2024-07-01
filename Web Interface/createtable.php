<?php 

function createTable($conn, $query) {
	
	$result = mysqli_query($conn,$query) or die('Something is wrong with '.$query);
	
	if (mysqli_num_rows($result) > 0) 
	{
		echo "<div class=\"table-row-column\">\n";
		echo "<h3>Last 40 Sensor Records</h3>";
		echo "</div>";
		echo "<div class ='table-row' >\n";
		echo "<table>\n";
		echo "<tr>\n "
			."<th scope=\"col\">Date</th>\n"
			."<th scope=\"col\">Soil Moisture</th>\n"
			."<th scope=\"col\">Humidity</th>\n"
			."<th scope=\"col\">Temperature</th>\n"
			."<th scope=\"col\">Water Level</th>\n"
			."<th scope=\"col\">Time</th>\n"
			."</tr>\n";
		/*
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<tr>\n ";
			echo "<td>",$row["date"],"</td>\n ";
			echo "<td>",$row["soilmoisture"],"</td>\n ";
			echo "<td>",$row["humidity"],"</td>\n ";
			echo "<td>",$row["temperature"],"</td>\n ";
			echo "<td>",$row["waterlevel"],"</td>\n ";
			echo "<td>",$row["time"],"</td>\n ";
			echo "</tr>\n ";
		}*/
		
		$date_arr = array();
		$soilmoisture_arr = array();
		$humidity_arr = array();
		$temperature_arr = array();
		$waterlevel_arr = array();
		$time_arr = array();
		
		$i = 39;
		
		while ($row = mysqli_fetch_assoc($result)) 
		{
			$date_arr[$i] = $row["date"];
			$soilmoisture_arr[$i] = $row["soilmoisture"];
			$humidity_arr[$i] = $row["humidity"];
			$temperature_arr[$i] = $row["temperature"];
			$waterlevel_arr[$i] = $row["waterlevel"];
			$time_arr[$i] = $row["time"];
			$i--;
		}
		
		for($i = 0; $i < 40; $i++)
		{
			echo "<tr>\n ";
			echo "<td>",$date_arr[$i],"</td>\n ";
			echo "<td>",$soilmoisture_arr[$i],"</td>\n ";
			echo "<td>",$humidity_arr[$i],"</td>\n ";
			echo "<td>",$temperature_arr[$i],"</td>\n ";
			echo "<td>",$waterlevel_arr[$i],"</td>\n ";
			echo "<td>",$time_arr[$i],"</td>\n ";
			echo "</tr>\n ";
		}
		
		echo "</table>\n ";
		echo "</div>\n";
	
		mysqli_free_result ($result);
	}
}

?>