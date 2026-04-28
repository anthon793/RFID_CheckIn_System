RFID Based Check-In system
===
RFID Based Check-In System using Arduino, RFID MFRC522 Module and Node-Red.

# Why was it built?
Traditional check-in systems often rely on manual processes that are prone to errors and time-consuming. To overcome these challenges, we developed an RFID Based Check-In System using Arduino, RFID MFRC522 Module, and Node-RED. This system automates the check-in process using RFID cards issued to users.

# What is RFID?
RFID (Radio Frequency Identification) is a technology where digital data stored in RFID tags is captured by a reader via radio waves, facilitating quick and contactless identification.

# What is Node-red
Node-RED is an open-source programming tool for wiring together hardware devices, APIs, and online services in new and interesting ways. It provides a browser-based editor that allows users to create flows by simply dragging and dropping nodes, which represent different functionalities, and connecting them together to define the flow of data.


# Components we used in the project
* Arduino Uno Board

* RFID MRFC522 module


* LCD display (20*4) with i2c lcd module


* LEDs and a Buzzer


* Node-RED (flow-based development tool)



# Components Connection With Arduino UNO

## RFID MFRC522
---------------

|Pin   |    Wiring to Arduino Uno|
|------|-------------------------|
|SDA   |    Digital 10|
|SCK   |    Digital 13|
|MOSI  |    Digital 11|
|MISO  |    Digital 12|
|IRQ   |    unconnected
|GND   |    GND
|RST   |    Digital 9
|3.3V  |    3.3V

Caution: You must power this device to 3.3V!

## Servo Motor
-----------------

|Servo Motor  |   Wiring to Arduino Uno|
|----------------|------------------------|
GND              |   GND
VCC              |   5v
Signal           |   Digital 6

## I2C module
-------------

|I2C Character LCD |  Arduino|
|------------------|---------|
GND         	  |  GND
VCC        	  |  5 V
SDA        	  |  A4
SCL         	  |  A5

## Buzzer
-------------

|Buzzer     |  Arduino|
|-----------|---------|
GND         |  GND
VCC        	|  5V
Signal      |  Digital 5


## LEDs
-------------

|LED                  |  Arduino  |
|---------------------|-----------|
Green LED GND         |  GND
Green LED Signal      |  Digital 7
Red LED GND           |  GND
Red LED Signal        |  Digital 4


# Project Setup

## PHP Setup
1. Ensure you have PHP installed on your system. You can download it from [php.net](https://www.php.net/downloads).
2. Clone the repository to your local machine:
   ```sh
   git clone <repository-url>
3. Navigate to the project directory
  cd RFID-checkin-system-using-Arduino-and-nodered
4. Start the PHP built-in server
   php -S localhost:8000
5. Open your browser and navigate to http://localhost:8000 to view the PHP application.

## Node-RED Setup
1. Install Node-RED globally using npm:
  npm install -g node-red
2. Start Node-RED
  node-red
3. Open your browser and navigate to http://localhost:1880 to access the Node-RED editor.
4. Import the Node-RED flow for the RFID check-in system:
    In the Node-RED editor, click on the menu button (three horizontal lines) in the top right corner.
    Select "Import" and then "Clipboard".
    Paste the flow JSON and click "Import".
5. Deploy the flow by clicking the "Deploy" button in the top right corner of the Node-RED editor.
