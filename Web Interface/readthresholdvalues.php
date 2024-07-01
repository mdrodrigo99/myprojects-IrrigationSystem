<?php 
	
	//Creating Array for JSON response
	$response = array();
	
	class writings{
		public $link = '';
		
		function __construct(){
			$this->connect();
			$this->storeInDB();
		}
		
		function connect(){
			$this->link = mysqli_connect('localhost','s102867540','271299') or die('Cannot connect to the Data Base');
			mysqli_select_db($this->link,'s102867540_db') or die('Cannot select the Data Base');
		}
		
		function storeInDB(){
			$query = "select tsoilmoisture,thumidity,ttemperature from thresholdvalues_tb";
			$result = mysqli_query($this->link,$query) or die('Something is wrong with '.$query);
			$result = mysqli_fetch_array($result);
			
			// temperoary user array
            $temparr = array();
            $temparr["tsoilmoisture"] = $result["tsoilmoisture"];
			$temparr["thumidity"] = $result["thumidity"];
			$temparr["ttemperature"] = $result["ttemperature"];
          
            $response["success"] = 1;

            $response["temparr"] = array();
			
			// Push all the items 
            array_push($response["temparr"], $temparr);
 
            // Show JSON response
            echo json_encode($response);
		}
	}
	
	$writings = new writings();

?>
