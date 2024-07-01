<?php
	
	class readings{
		public $link = '';
		
		function __construct($soilmoisture, $humidity, $temperature, $waterlevel){
			$this->connect();
			$this->storeInDB($soilmoisture, $humidity, $temperature, $waterlevel);
		}
		
		function connect(){
			$this->link = mysqli_connect('localhost','s102867540','271299') or die('Cannot connect to the Data Base');
			mysqli_select_db($this->link,'s102867540_db') or die('Cannot select the Data Base');
		}
		
		function storeInDB($soilmoisture, $humidity, $temperature, $waterlevel){
			$query = "insert into readvalues_tb set soilmoisture = '$soilmoisture', humidity = '$humidity', temperature = '$temperature', waterlevel = '$waterlevel' ";
			$result = mysqli_query($this->link,$query) or die('Something is wrong with '.$query);
		}
	}
	
	if($_GET['soilmoisture'] != '' and $_GET['humidity'] != '' and $_GET['temperature'] != '' and $_GET['waterlevel'] != ''){
		$readings = new readings($_GET['soilmoisture'], $_GET['humidity'], $_GET['temperature'], $_GET['waterlevel']);
	}
?>