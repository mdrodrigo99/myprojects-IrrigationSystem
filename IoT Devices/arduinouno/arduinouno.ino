#include <SoftwareSerial.h>
#include <ArduinoJson.h>
#include <dht.h>
#include <LiquidCrystal_I2C.h>

// serial communication with nodemcu
SoftwareSerial nodemcu(5,6);

// water level sensor pins
#define sensorPower 7
#define sensorPin A0

// soil moisture sensor pin
#define smPin A2

// humidity and temperature sensor pin
#define dhtPin A1

// water level indicating led pins
#define redLed 2
#define yellowLed 3
#define greenLed 4

// button pin
#define buttonPin 8

int soilmoisture = 0,humidity = 0,temperature = 0,waterlevel = 0; 
int tsoilmoisture = 0,thumidity = 0,ttemperature = 0; 

int lowThresholdWaterLevel = 30;
int highThresholdWaterLevel = 45;

dht dhtsensor;

// water pump relay pin
#define relayPin 9

bool isReceived = false;

unsigned long previousMillis1 = 0,previousMillis2 = 0;  // will store last time updated
const long interval = 300000; 

String motorStatus = "OFF";

LiquidCrystal_I2C lcd(0x27,16,2);  // set the LCD address to 0x3F for a 16 chars and 2 line display

void setup()
{
  Serial.begin(115200);
  nodemcu.begin(115200);
  pinMode(sensorPower,OUTPUT);
  pinMode(redLed,OUTPUT);
  pinMode(yellowLed,OUTPUT);
  pinMode(greenLed,OUTPUT);
  pinMode(buttonPin,INPUT);

  digitalWrite(sensorPower,LOW);
  digitalWrite(redLed,LOW);
  digitalWrite(yellowLed,LOW);
  digitalWrite(greenLed,LOW);
  delay(100);
  pinMode(relayPin,OUTPUT);
  digitalWrite(relayPin, HIGH);
  lcd.init();
  lcd.clear();         
  lcd.backlight();      // Make sure backlight is on
  lcd.setCursor(4,0);   //Set cursor to character 2 on line 0
  lcd.print("Welcome!");
  delay(100);
}

void loop()
{ 
  if(!isReceived)
  {
    Receiving_From_NodeMCU();
  }
  else
  {
    unsigned long currentMillis = millis();
    
    if (currentMillis - previousMillis1 >= interval) 
    {
      Sensor_Readings();
      delay(100);
      Water_Pump_Control();
      previousMillis1 = currentMillis;
    }
    if (currentMillis - previousMillis2 >= (interval*2)) 
    {
      Sending_To_NodeMCU();
      previousMillis2 = currentMillis;
    }
    if(digitalRead(buttonPin) == HIGH)
    {
      Sensor_Readings();
      delay(100);
      Water_Pump_Control(); 
      delay(100);   
      Sending_To_NodeMCU();  
    }
  }
}

void Sensor_Readings()
{
  Soil_Moisture_Readings();
  LCD_Display();
  delay(10);
  Humidity_And_Temperature_Readings();
  delay(10);
  Water_Level_Readings();
  delay(10);
}

void Soil_Moisture_Readings()
{
  soilmoisture = analogRead(smPin);
  soilmoisture = map(soilmoisture,0,1023,90,30);
  Serial.print("Current Soil Moisture = ");
  Serial.println(soilmoisture); 
}

void Humidity_And_Temperature_Readings()
{
  dhtsensor.read11(dhtPin);
  delay(10);
  humidity = dhtsensor.humidity;
  temperature = dhtsensor.temperature;
  
  if(isnan(humidity) || isnan(temperature))
  {
	  Serial.println("Failed to read from DHT11");
	  Humidity_And_Temperature_Readings();
  }
  else{
	Serial.print("Current Humidity = ");
	Serial.print(humidity);
	Serial.print("%  ");
	Serial.print("Temperature = ");
	Serial.print(temperature); 
	Serial.println("C  ");
	}
}

void Water_Level_Readings(){
  digitalWrite(sensorPower,HIGH);
  delay(10);
  waterlevel = analogRead(sensorPin);
  waterlevel = map(waterlevel,0,1023,0,100);
  digitalWrite(sensorPower,LOW);

  if(waterlevel == 0){
    Serial.println("Water Level: Empty");
    digitalWrite(redLed,LOW);
    digitalWrite(yellowLed,LOW);
    digitalWrite(greenLed,LOW);
  }
  else if(waterlevel <= lowThresholdWaterLevel){
    Serial.println("Water Level: Low");
    digitalWrite(redLed,HIGH);
    digitalWrite(yellowLed,LOW);
    digitalWrite(greenLed,LOW);
  }
  else if(waterlevel <= highThresholdWaterLevel){
    Serial.println("Water Level: Medium");
    digitalWrite(redLed,LOW);
    digitalWrite(yellowLed,HIGH);
    digitalWrite(greenLed,LOW);
    waterlevel += 10;
  }
  else if(waterlevel > highThresholdWaterLevel){
    Serial.println("Water Level: High");
    digitalWrite(redLed,LOW);
    digitalWrite(yellowLed,LOW);
    digitalWrite(greenLed,HIGH);
    waterlevel += 45;
  }
  Serial.println();
}

void Sending_To_NodeMCU()
{
  StaticJsonBuffer<500> jsonBuffer;
  JsonObject& data = jsonBuffer.createObject();
  
  // Assign collect data to Json Object
  data["soilmoisture"] = soilmoisture;
  data["humidity"] = humidity;
  data["temperature"] = temperature;
  data["waterlevel"] = waterlevel;
  delay(100);
  
  // Send data to NodeMCU
  data.printTo(nodemcu);
  jsonBuffer.clear();
}

void Receiving_From_NodeMCU()
{
  StaticJsonBuffer<500> jsonBuffer;
  JsonObject& data = jsonBuffer.parseObject(Serial);  

  if(data == JsonObject::invalid()){
    jsonBuffer.clear();
    return;
  }

  Serial.println("\n------------------------------------------");
  Serial.println("Sensors Data Received\n");
  Serial.print("Received Threshold Soil Moisture: ");
  tsoilmoisture = data["tsoilmoisture"];
  Serial.println(tsoilmoisture);
  
  Serial.print("Received Threshold Humidity: ");
  thumidity = data["thumidity"];
  Serial.println(thumidity);

  Serial.print("Received Threshold Temperature: ");
  
  ttemperature = data["ttemperature"];
  Serial.println(ttemperature);
  jsonBuffer.clear();
  Serial.println("------------------------------------------");

  isReceived = true;
} 

void LCD_Display()
{
  lcd.clear();
  lcd.setCursor(0,0);   //Set cursor to character 2 on line 0
  lcd.print("SoilMoisture:");
  lcd.print(soilmoisture);
  
  lcd.setCursor(0,1);   //Move cursor to character 2 on line 1
  lcd.print("Motor:");
  lcd.print(motorStatus);
}

void Water_Pump_Control()
{
  // check soil moisture level is less than threshold soil moisture level (soil dry) 
  if(soilmoisture < tsoilmoisture)
  {
    // check temperature is greater than threshold temperature (air dry)
    if(temperature >= ttemperature)
    {
      // check humidity is greater than threshold humidity (air is more dry)
      if(humidity >= thumidity)
      {
        Pump_Process(10000);
      }
      // (air is dry)
      else
      {
        Pump_Process(5000);
      }
    }
    else // in the night or rainy day
    {
      if(humidity >= thumidity)
      {
        Pump_Process(3000);
      }
    }
  }
}

void Pump_Process(int pTime)
{
  // check enough water available in the water tank
  if(waterlevel > 30)
  {
    motorStatus = "ON";
    LCD_Display();
    digitalWrite(relayPin, LOW); // turn on pump 
    delay(pTime);
    motorStatus = "OFF";
    LCD_Display();
    digitalWrite(relayPin, HIGH);  // turn off pump 
    delay(10);  
  }
}
