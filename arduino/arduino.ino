/*
  Rui Santos
  Complete project details at https://RandomNerdTutorials.com/control-esp32-esp8266-gpios-from-anywhere/
  
  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files.
  
  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
*/

#include <WiFi.h>
#include <HTTPClient.h>
#include <Arduino_JSON.h>
#include <ESP32Servo.h>
#include "DHT.h"

#define DHTPIN 27 

const char* ssid = "LEXIA DARKO";
const char* password = "Darkus0674";

//Your IP address or domain name with URL path
const char* serverName = "http://akuafo.herokuapp.com/pump/esp-outputs-action.php?action=outputs_state&board=1";

//https://pollutionwatch.000webhostapp.com/esp-outputs.php
//Update interval time set to 5 seconds
const long interval = 5000;
unsigned long previousMillis = 0;

String outputsState;

#define DHTTYPE DHT22   // DHT 22  (AM2302), AM2321

DHT dht(DHTPIN, DHTTYPE);
  // Creates an instance of the DHT object
Servo myservo; 

///////////////////////////////////////////////////
#define DHTPIN 27     // Digital pin connected to the DHT sensor
#define AOUT_PIN 32 // ESP32 pin GIOP36 (ADC0) that connects to AOUT pin of moisture sensor
#define SOUND_SPEED 0.034 // Speed of sound in air (cm/us)
#define CM_TO_INCH 0.393701  // Conversion factor from cm to inches

//Servo myservo; // create servo object

const int trigPin = 5;  // Defines the trigger pin for the ultrasonic sensor
const int echoPin = 18;  // Defines the echo pin for the ultrasonic sensor
const int relayPin = 26;
const int servoPin = 19; // pin connected to servo motor
//const int soilMoistureThresholdOn = 500; // soil moisture level when relay turns on
//const int soilMoistureThresholdOff = 700; // soil moisture level when relay turns off


long duration;  // Stores the duration of the sound wave
float distanceCm;  // Stores the distance in cm
float waterlevel;  // Stores the water level in cm

boolean automatic = false;
boolean pumpOn = false;
//int pos = 0; 
//int servoPin = 19;

///////////////////////////////////////////////////


void setup() {

  pinMode(trigPin, OUTPUT); // Sets the trigPin as an Output
  pinMode(echoPin, INPUT); // Sets the echoPin as an Input
  
  pinMode(relayPin, OUTPUT);
  pinMode(2, OUTPUT); 
  
  dht.begin();

  ESP32PWM::allocateTimer(0);
  ESP32PWM::allocateTimer(1);
  ESP32PWM::allocateTimer(2);
  ESP32PWM::allocateTimer(3);
  myservo.setPeriodHertz(50);// standard 50 hz servo
  myservo.attach(servoPin, 1000, 2000); // attaches the servo on pin 18 to the servo object

  Serial.begin(115200);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  
  //CONNECT TO WIFI, REMAIN IN LOOP AS LONG AS NOT CONNECTED
  while(WiFi.status() != WL_CONNECTED) {
    digitalWrite(2, 1); 
    delay(300);
    digitalWrite(2, 0); 
    delay(300);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

}

void loop() {
  unsigned long currentMillis = millis();
  
  if(currentMillis - previousMillis >= interval) { // IF 5SECONDS ELAPSES
     // Check WiFi connection status
    if(WiFi.status()== WL_CONNECTED ){ 
      outputsState = httpGETRequest(serverName);
      
      Serial.println(outputsState);
      JSONVar myObject = JSON.parse(outputsState);
  
      // JSON.typeof(jsonVar) can be used to get the type of the var
      if (JSON.typeof(myObject) == "undefined") {
        Serial.println("Parsing input failed!");
        return;
      }
    
      Serial.print("JSON object = ");
      Serial.println(myObject);
    
      // myObject.keys() can be used to get an array of all the keys in the object
      JSONVar keys = myObject.keys();
    
      for (int i = 0; i < keys.length(); i++) {
        JSONVar value = myObject[keys[i]];
        Serial.print("GPIO: ");
        Serial.print(keys[i]);
        Serial.print(" - SET to: ");
        Serial.println(value);
        
        pinMode(atoi(keys[i]), OUTPUT);
        
        int pin = atoi(keys[i]);
        int state1 = atoi(value);

        if(pin == 26 and state1 ==1)
        {
          pumpOn = false;
        }

        if(pin == 26 and state1 == 0)
        {
          pumpOn = true;
        }

        if(pin == 4 and state1 == 1)
        {
          automatic = false;
        }

        if(pin == 4 and state1 == 0)
        {
          automatic = true;
        }
                     
      }
      
      previousMillis = currentMillis;
    }
    else {
      Serial.println("WiFi Disconnected");
    }
  }

  if(not automatic)
    {
      digitalWrite(2, 0);
      if(pumpOn)
      {
        digitalWrite(relayPin, LOW); // turn on the pump
        Serial.println("pump ON");
        myservo.write(0); // rotate servo to 0 degrees
        delay(1000); 
        myservo.write(90);
        delay(1000);
        myservo.write(180);  
      }

      else
      {
         digitalWrite(relayPin, HIGH); // turn off the pump
         Serial.println("Relay OFF");
         //myservo.write(90); // rotate servo to 90 degrees
         delay(1000); 
      }
    }

    int soilmoisture = analogRead(AOUT_PIN);

    //deliver the right pulses to the ultrasonic sensor to get distance measurement
    digitalWrite(trigPin, LOW);
    delayMicroseconds(2);
    digitalWrite(trigPin, HIGH);
    delayMicroseconds(10);
    digitalWrite(trigPin, LOW);
    
    //Read the echoPin signal from the ultrasonic sensor and calculates the distance in microseconds.

    duration = pulseIn(echoPin, HIGH);
    distanceCm = duration * SOUND_SPEED/2;

    waterlevel = distanceCm * CM_TO_INCH;

    //Read the temperature and humidity from the DHT sensor.
    float humidity = dht.readHumidity();
    float temperature = dht.readTemperature();

    if (isnan(humidity) || isnan(temperature)) {
      Serial.println(F("Failed to read from DHT sensor!"));
      //return;
    }

    Serial.println("Temperature " + String(temperature));
    Serial.println("Humidity " + String(humidity));
    Serial.println("Water Level " + String(waterlevel));
    Serial.println("Soil Moisture " + String(soilmoisture));
    Serial.print("Mode: ");
    if(automatic)
    Serial.println("automatic");
    else
    Serial.println("manual");

    String serverName2 = "http://akuafo.herokuapp.com/controllers/connection2.php?humidity="+String(humidity)+"&temperature="+String(temperature)+"&soilmoisture="+String(soilmoisture)+"&waterlevel="+String(waterlevel);

    //http://pollutionwatch.000webhostapp.com/save.php

    if(WiFi.status()== WL_CONNECTED ){ 
      String response = httpGETRequest(serverName2.c_str());
      Serial.println(response);
    }

    else
      Serial.println("wifi disconnected");
/*
    if(waterlevel < 15.0){ 
      digitalWrite(relayPin, LOW);  // Turn ON the relay
      delay(2000);
      Serial.println("Water Pump ON"); 
    }

    else if(waterlevel > 20.0){  
      digitalWrite(relayPin, HIGH);  // Turn OFF the relay
      delay(2000);
      Serial.println("Water Pump OFF"); 
    }
*/   
// 3366 ---- dry
// 3170 ----
// 2800 --- wet
    //Soil Moisture Sensor
    if(automatic)
    { 
      digitalWrite(2, 1);
      if (soilmoisture >= 3300) {
        digitalWrite(relayPin, LOW); // turn on the pump
        Serial.println("pump ON");
        myservo.write(0); // rotate servo to 0 degrees
        delay(1000);
        myservo.write(90); // rotate servo to 0 degrees       
        delay(1000);
        myservo.write(180); // rotate servo to 0 degrees
        delay(1000);
        //delay(1000); 
      }
    
      if (soilmoisture <= 2900) {
          digitalWrite(relayPin, HIGH); // turn off the pump
          Serial.println("pump OFF");
          //myservo.write(90); // rotate servo to 90 degrees
          delay(1000); 
        }
    }

   delay(500);
     
}

String httpGETRequest(const char* serverName) {
  WiFiClient client;
  HTTPClient http;
    
  // Your IP address with path or Domain name with URL path 
  http.begin(client, serverName);
  
  // Send HTTP POST request
  int httpResponseCode = http.GET();
  
  String payload = "{}"; 
  
  if (httpResponseCode>0) {
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);
    payload = http.getString();
  }
  else {
    Serial.print("Error code: ");
    Serial.println(httpResponseCode);
  }
  // Free resources
  http.end();

  return payload;
}
