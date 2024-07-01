#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266mDNS.h>
#include <ArduinoJson.h>
#include <SoftwareSerial.h>
SoftwareSerial nodemcu(D6,D5);
SoftwareSerial arduinouno(D7,D8);

// router details
const char* ssid = "SLT-LTE-WiFi-6317";
const char* password = "6HJT41F1FLY";

// host ip address
IPAddress server(192,168,8,101);

WiFiClient client;

int soilmoisture = 0,humidity = 0,temperature = 0,waterlevel = 0; 
String tsm, th, tt;

#define buttonPin 5

void setup() 
{
  Serial.begin(115200);
  arduinouno.begin(115200);
  nodemcu.begin(115200);
  pinMode(buttonPin,INPUT);
  Init();
}

void loop() 
{
  unsigned long currentMillis = millis();
  if(digitalRead(buttonPin) == HIGH)
  {
    Sending_To_ArduinoUno();
    delay(100);
  }
  if(currentMillis > 30000){
    if(Receiving_From_ArduinoUno())
    {
      Sending_To_Database();
    }
  }
}


void Init()
{
  // connect to WiFi network
  Serial.println();
  Serial.println();
  Serial.println("Connecting to");
  Serial.println(ssid);

  WiFi.begin(ssid,password);

  while(WiFi.status() != WL_CONNECTED)
  {
  delay(500);
  Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  delay(100);
  Receiving_From_Database();
  delay(100);    
}

void Receiving_From_Database() 
{
  Serial.println("Server started");
  Serial.print(WiFi.localIP());
  delay(500);
  Serial.println("connecting...");
  if(client.connect(server,80))
  {
    Serial.println("Connected");
    // make a HTTP request
    Serial.println("GET /irrigationsystem/readthresholdvalues.php");
    client.print("GET /irrigationsystem/readthresholdvalues.php");
    client.print(" ");
    client.print("HTTP/1.1");
    client.println();
    client.println("Host: 192.168.8.101");
    client.println("Connection: close");
    client.println(); 
    delay(100);
    String section="header";
    while (client.connected() || client.available())
    {
      if (client.available())
      {
        String line = client.readStringUntil('\r');
        //Serial.println(line);
        if (section=="header") 
		    { // headers..
          if (line=="\n") 
		      { // skips the empty space at the beginning 
            section="json";
          }
        }
        else if (section=="json") 
		    {  // print the good stuff
          section="ignore";
          String result = line.substring(1);
          // Parse JSON
          int size = result.length() + 1;
          char json[size];
          result.toCharArray(json, size);
          StaticJsonBuffer<200> jsonBuffer;
          JsonObject& json_parsed = jsonBuffer.parseObject(json);
          if (!json_parsed.success())
          {
            Serial.println("parseObject() failed");
            return;
          }
          delay(100);
          tsm = (const char*) json_parsed["temparr"][0]["tsoilmoisture"];
          th = (const char*) json_parsed["temparr"][0]["thumidity"];
          tt = (const char*) json_parsed["temparr"][0]["ttemperature"];
          jsonBuffer.clear();
		      delay(500);
          Serial.println("Sucessful");
        }
      }
    }
    client.stop();
    Serial.println("Disconnected\n");
  }
  else{
    Serial.println("connection failed");
    client.stop();
  }
}

bool Receiving_From_ArduinoUno()
{
  StaticJsonBuffer<500> jsonBuffer;
  JsonObject& data = jsonBuffer.parseObject(nodemcu);  

  if(data == JsonObject::invalid()){
    jsonBuffer.clear();
    return false;
  }

  Serial.println("\n------------------------------------------");
  Serial.println("Sensors Data Received\n");
  Serial.print("Received Soil Moisture: ");
  soilmoisture = data["soilmoisture"];
  Serial.println(soilmoisture);
  
  Serial.print("Received Humidity: ");
  humidity = data["humidity"];
  Serial.println(humidity);

  Serial.print("Received Temperature: ");
  temperature = data["temperature"];
  Serial.println(temperature);

  Serial.print("Received Water Level: ");
  waterlevel = data["waterlevel"];
  Serial.println(waterlevel);
  Serial.println("------------------------------------------");

  return true;
}

void Sending_To_ArduinoUno()
{
  StaticJsonBuffer<500> jsonBuffer;
  JsonObject& data = jsonBuffer.createObject();
  
  // Assign collect data to Json Object
  data["tsoilmoisture"] = tsm.toInt();
  data["thumidity"] = th.toInt();
  data["ttemperature"] = tt.toInt();
  delay(100);
  
  // Send data to NodeMCU
  data.printTo(arduinouno);
  jsonBuffer.clear();
}

void Sending_To_Database() 
{
  Serial.println("\nServer started");
  Serial.print(WiFi.localIP());
  delay(1000);
  Serial.println("connecting...");
  if(client.connect(server,80)){
    Serial.println("Connected");
    // make a HTTP request
    Serial.println("GET /irrigationsystem/readings.php?");
    client.print("GET /irrigationsystem/readings.php?soilmoisture=");
    client.print(soilmoisture);
    client.print("&humidity=");
    client.print(humidity);
    client.print("&temperature=");
    client.print(temperature);
    client.print("&waterlevel=");
    client.print(waterlevel);
    client.print(" ");
    client.print("HTTP/1.1");
    client.println();
    client.println("Host: 192.168.8.101");
    client.println("Connection: close");
    client.println();
    Serial.println("Sucessful");
    Serial.println("Disconnected\n");
  }
  else{
    Serial.println("connection failed");
  }
}
