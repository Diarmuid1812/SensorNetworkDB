#include "src/DHT_sensor_library/DHT.h"
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <EEPROM.h>

#define DHTTYPE DHT22
#define DHT_VCC D1
#define DHT_PIN D2

#define SSID_EEPROM_ADDR 10
#define PASSWD_EEPROM_ADDR 50

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

  String ssid = "ssid";
  String password = "passwd";

  Serial.println("Waiting for wifi config");
  delay(2000);

  if (Serial.available() > 0)
  {
    String configMsg;
    configMsg = Serial.readString();

    //identyfikacja komendy nazwy sieci
    if (configMsg.substring(0, 5) == "ssid=")
    {
      ssid = configMsg.substring(5, configMsg.length());
      Serial.print("new ssid: ");
      Serial.println(ssid);

      //zapis ssid do EEPROM
      for (int i = 0; i < ssid.length(); i++)
      {
        char c = ssid[i];
        EEPROM.write(SSID_EEPROM_ADDR + i, c);
      }
      EEPROM.write(SSID_EEPROM_ADDR - 1, ssid.length());
      EEPROM.commit();


    }
    //identyfikacja komendy hasła
    if (configMsg.substring(0, 7) == "passwd=")
    {
      password = configMsg.substring(7, configMsg.length());
      Serial.print("new password: ");
      Serial.println(password);

      //zapis hasła do EEPROM
      for (int i = 0; i < password.length(); i++)
      {
        char c = password[i];
        EEPROM.write(PASSWD_EEPROM_ADDR + i, c);
      }
      EEPROM.write(PASSWD_EEPROM_ADDR - 1, password.length());
      EEPROM.commit();
    }
  }

  //odczyt ssid i hasła z EEPROM
  ssid = "";
  for (int i = 0; i < EEPROM.read(SSID_EEPROM_ADDR - 1); i++)
  {
    ssid += EEPROM.read(SSID_EEPROM_ADDR + i);
  }
  password = "";
  for (int i = 0; i < EEPROM.read(PASSWD_EEPROM_ADDR - 1); i++)
  {
    password += EEPROM.read(PASSWD_EEPROM_ADDR + i);
  }

  //odczyt wilgotności, temperatury i napięcia baterii
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

  String httpRequestData = id + temp + String(temperature) + hum + String(humidity) + bat + String(battery) + "";
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

  Serial.println("Sleep for 5 seconds");
  Serial.println("##########################################");
  ESP.deepSleep(5 * 1e6);
}

void loop()
{

}
