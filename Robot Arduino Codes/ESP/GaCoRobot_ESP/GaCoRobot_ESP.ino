#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
 
const char* ssid = "Anuranga";
const char* password = "12345678";

 
void setup () {

 pinMode(LED_BUILTIN, OUTPUT);
digitalWrite(LED_BUILTIN, LOW);

Serial.begin(115200);
WiFi.begin(ssid, password);
 
while (WiFi.status() != WL_CONNECTED) {
 
delay(1000);
//Serial.print("Connecting..");
digitalWrite(LED_BUILTIN, LOW);
 
}
 digitalWrite(LED_BUILTIN, HIGH);
 delay(5000);
 digitalWrite(LED_BUILTIN, LOW);
}

String q = "A"; 
char p;


void loop() {

p = Serial.read();
 Serial.println(p);

if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
 
HTTPClient http;  //Declare an object of class HTTPClient
 
http.begin("http://gacorobot.000webhostapp.com/getPath.php?id=25&submit=Submit+Query");  //Specify request destination
int httpCode = http.GET();                                                               //Send the request
 
if (httpCode > 0) { //Check the returning code
 
String payload = http.getString();   //Get the request response payload

if( q == payload ){
Serial.println(">");                 //Print the response payload

 }
}
 
http.end();   //Close connection

// p = Serial.read();
// Serial.println(p);

delay(4000);    //Send a request every 10 seconds

//p = Serial.read();
 //Serial.println(p);

 if ( p == 'A' ) { //Check WiFi connection status
 
HTTPClient httpi;  //Declare an object of class HTTPClient
 
httpi.begin("http://gacorobot.000webhostapp.com/updateState.php?id=25&submit=Submit+Query");  //Specify request destination
int httipCode = httpi.GET();                                                                  //Send the request

delay(1000);

Serial.println("-----------END---------");
 
httpi.end();   //Close connection

}
 
 
}

delay(5000);   //Send a request every 10 seconds

}
