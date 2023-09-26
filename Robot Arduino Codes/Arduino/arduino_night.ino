
#include <QTRSensors.h>
#include "DualVNH5019MotorShield.h"
#include <Servo.h>

int SVRotDN = 30;
int SVRotUP = 160;

int SVClsCL = 28 ;
int SVClsOP = 70 ;


DualVNH5019MotorShield md; //motor controller object

#define NUM_SENSORS             8  // number of sensors used
#define NUM_SAMPLES_PER_SENSOR  4  // average 4 analog samples per sensor reading
#define EMITTER_PIN             13  // emitter is controlled by digital pin 2
QTRSensorsAnalog qtra((unsigned char[]) {0, 1, 2, 3, 4, 5, 6, 7},NUM_SENSORS, NUM_SAMPLES_PER_SENSOR, EMITTER_PIN); 

unsigned int sensorValues[NUM_SENSORS];
int minAnalog, maxAnalog;
char digitalSensorValues[NUM_SENSORS];
int deviation=0,prevDeviation=0;
float correction,recorrection,Kp,Ki,Kd,K,totalError=0,baseSpeed,rightMotorSpeed,leftMotorSpeed;
int i,j,k;


int stage = 0; //keep running step

#define fwd 29 //tray_relay1
#define rev 28 //tray_relay2

#define rot 3 //servo1
#define cls 5 //servo2


#define Mag 30 //Electro Magnet

//servo objects
Servo myservoRot;
Servo myservoCls;
Servo myservoBin;
 
int pos = 0;

String inputString = "";         // a string to hold incoming data
boolean stringComplete = false;  // whether the string is complete


void setup() {
  
  Serial.begin(115200); //serial begin
  md.init(); //initialize motor controller

  pinMode(20, INPUT); //IR sensor input
  pinMode(fwd, OUTPUT); //tray_rela1
  pinMode(rev, OUTPUT); //tray_relay2
  digitalWrite(fwd,HIGH); //relay_off
  digitalWrite(rev,HIGH); //relay_off
  
  pinMode(Mag, OUTPUT);
  digitalWrite(Mag,HIGH); //Magnet_Off

  myservoRot.attach(rot);
  myservoCls.attach(cls);
  myservoBin.attach(11);
  myservoRot.write(SVRotUP); //home_position
  myservoBin.write(150); 


  inputString.reserve(200);

  //PID values
  baseSpeed= 150;
  Kp = 0.15;
  Kd = 0;
  Ki = 0; 

  //Calibration of line following IR sensors
   qtra.calibrate(); 
   md.setM2Speed(100); //move forward
   md.setM1Speed(100);
   delay(1000);
   qtra.calibrate(); 
   
   for (int i = 0; i < 35; i++){
    md.setM2Speed((i*6.5)); 
    md.setM1Speed(-(i*6.5));
    qtra.calibrate();   
   }
   for (int i = 0; i <35; i++){ 
    md.setM1Speed(i*6.5);
    md.setM2Speed(-(i*6.5));
    qtra.calibrate();      
   }
  
   for (int i = 0; i < 35; i++){ 
    md.setM1Speed(i*6.5);
    md.setM2Speed(-(i*6.5));
    qtra.calibrate();      
  }
  for (int i =0; i <35; i++){
    md.setM2Speed(i*6.5);
    md.setM1Speed(-(i*6.5)); 
    qtra.calibrate();      
  }
   md.setM2Speed(-100); //movereverse
   md.setM1Speed(-100);
   delay(1000);
  //calibration_finish
  
}

void loop() {
  
  if (stringComplete) { //reading incoming data from ESP and search for starting command
       stage = 1; 
       inputString = "";
       stringComplete = false;
  }

 if(stage == 0){
     md.setM2Speed(0); 
     md.setM1Speed(0);  
  }
  
  if(stage == 1){
    lineFollow();   
  }

  if(stage == 2){
     md.setM2Speed(0); 
     md.setM1Speed(0);
     trayFWD(); //tray move forward
     servoAct(); //operating arm sequence
       
     delay(1500);
     md.setM2Speed(80); 
     md.setM1Speed(80);
     delay(700);
     stage = 3;
    
  }
  if(stage == 3){
    lineFollow();
        
  }

  if(stage == 4){
     md.setM2Speed(0); 
     md.setM1Speed(0);
     trayREV(); //tray move reverse
     servoAct();
       
     delay(1500);
     md.setM2Speed(80); 
     md.setM1Speed(80);
     delay(1000);
     stage = 5;
    
  }

   if(stage == 5){
    lineFollow();
        
  }
  
  if(stage == 6){
   md.setM2Speed(0); 
   md.setM1Speed(0);
   digitalWrite(Mag, LOW); //Magnet_ON
   myservoBin.write(20); 
   delay(1000);
   myservoBin.write(150); 
   Serial.println("a");
   digitalWrite(Mag,HIGH); //Magnet_OFF
   stage = 7;
        
  }
  //search for black strips using IR sensor
   if(digitalRead(20) == HIGH){
    delay(10);
    if(digitalRead(20)== HIGH){
       stage++;
     }
   }


}

void lineFollow(){

  unsigned int position = qtra.readLine(sensorValues);
  deviation = position - 3500;
  
  //***PID implementation***
  correction = (Kp*deviation)+(Ki*totalError)+(Kd*(deviation-prevDeviation));
  totalError += correction;
  prevDeviation = deviation;

  rightMotorSpeed = 150 + correction;
  leftMotorSpeed = 150 - correction;
  
  if(rightMotorSpeed<0){
    rightMotorSpeed= 0;
  }
   if(leftMotorSpeed<0){
    leftMotorSpeed= 0;
  }

 //Writing calculated values to motors
  md.setM2Speed((int(rightMotorSpeed))); //assigning motor 2 speed
  md.setM1Speed((int(leftMotorSpeed))); //assigning motor 1 speed  
    
}

void serialEvent() {
  while (Serial.available()) {
    // get the new byte:
    char inChar = (char)Serial.read();
    // add it to the inputString:
    inputString += inChar;
   
    // if the incoming character is a 'A', set a flag
    // so the main loop can do something about it:
    if (inChar == '>') {
      stringComplete = true;
    }
  }
}

void trayFWD(){

  digitalWrite(rev,HIGH);//forward
  digitalWrite(fwd,LOW);
  delay(800);
  digitalWrite(fwd,HIGH); //OFF
  digitalWrite(rev,HIGH);
  
}

void trayREV(){

  digitalWrite(rev,LOW);//forward
  digitalWrite(fwd,HIGH);
  delay(800);
  digitalWrite(fwd,HIGH); //OFF
  digitalWrite(rev,HIGH);
  
}

void servoFWDRot(int deg){ //Servo clockwise rotation
  for(int i = pos; i<=deg; i++){
    myservoRot.write(i); 
    delay(40);
  }
  pos = deg; //update current postion
}

void servoREVRot(int deg){ //Servo Anti-clockwise rotation
  pos = SVRotUP;
  for(int i = pos; i>=deg; i--){
    myservoRot.write(i); 
    delay(40);
  }
    pos = deg; //update current postion
}


void servoAct(){
        myservoCls.write(SVClsOP); //open
        delay(500);
        //servoRot(90);
        //delay(2000);
        servoREVRot(SVRotDN);
        delay(500);
        myservoCls.write(SVClsCL); //150cls 40open
        delay(500);
        servoFWDRot(SVRotUP);
        delay(1000);
        servoREVRot(SVRotDN);
        delay(500);
        myservoCls.write(SVClsOP); //150cls 90open
        delay(500);
        servoFWDRot(SVRotUP);
        delay(500);
  
}
