#include "DHT.h"
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <EEPROM.h>

#define DHTTYPE DHT22
#define DHT_VCC D1
#define DHT_PIN D2

DHT dht(DHT_PIN, DHTTYPE);

void setup()
{
  //ustawienie GPIO jako output
  pinMode(DHT_VCC, OUTPUT);
  //zasilenie czujnika DHT z GPIO
  digitalWrite(DHT_VCC, HIGH);
  //uruchomienie portu szeregowego
  Serial.begin(74880);
  //inicjalizacja czujnika
  dht.begin();
  //uruchomienie EEPROM
  EEPROM.begin(512);

  Serial.print("EEPROM test: ");
  Serial.print(char(EEPROM.read(1)));
  Serial.print(char(EEPROM.read(2)));
  Serial.println(char(EEPROM.read(3)));



  //
  delay(2000);

  float humidity = dht.readHumidity();
  float temperature = dht.readTemperature();
  float battery = float(analogRead(A0)) / 1024 * 3.3;

  Serial.print("temp: ");
  Serial.println(temperature);
  Serial.print("hum: ");
  Serial.println(humidity);
  Serial.print("analog: ");
  Serial.println(battery);
  Serial.println();

  //ustaw esp w trybie klienta wifi
  WiFi.mode(WIFI_STA);
  WiFi.disconnect();
  Serial.println("Connecting to wifi");

  String ssid = "ssid";
  String password = "passwd";

  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  HTTPClient http;
  Serial.print("http.begin() = ");
  Serial.println(http.begin("http://ipv4.255.255.255/SN/comPost.php"));
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  String id = "&id=1";
  String temp = "&temperature=";
  String hum = "&humidity=";
  String bat = "&battery=";

  String httpRequestData = id + temp + String(temperature) + hum + String(humidity) + bat + String(battery)+"";
  Serial.print("httpRequestData: ");
  Serial.println(httpRequestData);

  int httpResponseCode = http.POST(httpRequestData);

  if (httpResponseCode > 0)
  {
    Serial.print("HTTP Response code: ");
    Serial.println(httpResponseCode);
  }
  else
  {
    Serial.print("Error code: ");
    Serial.println(httpResponseCode);
  }
  http.end();


  for (int i = 0; i < 1; i++)
  {
    delay(1000);
  }

  Serial.println("Sleep for 5 seconds");
  Serial.println("##########################################");
  ESP.deepSleep(5 * 1e6);
}

void loop()
{

}
