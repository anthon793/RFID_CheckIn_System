# RFID Based Check-In System

An RFID-based check-in system built with Arduino, an MFRC522 RFID reader, Node-RED, PHP, and MySQL. The Arduino reads RFID cards, sends valid check-in data over serial, Node-RED stores the data in MySQL, and the PHP dashboard displays records and user management tools.

## Overview

Traditional check-in systems often depend on manual entry, which can be slow and error-prone. This project automates check-ins by assigning RFID cards to users. When a registered card is scanned, the system grants access, logs the check-in, and makes the record available in the dashboard.

## How It Works

1. A user scans an RFID card on the MFRC522 reader.
2. The Arduino checks the card ID against the registered cards in the sketch.
3. If the card is valid, the Arduino displays access feedback, activates the servo, and sends JSON data over serial.
4. Node-RED reads the serial data, parses the JSON payload, and inserts it into MySQL.
5. The PHP dashboard displays check-in records, users, statistics, and recent activity.

## Tech Stack

- Arduino Uno
- MFRC522 RFID module
- I2C LCD display
- Servo motor
- LEDs and buzzer
- Node-RED
- PHP
- MySQL/MariaDB
- AdminLTE dashboard UI

## Hardware Components

- Arduino Uno board
- RFID MFRC522 module
- I2C LCD display
- Servo motor
- Buzzer
- Green and red LEDs
- Jumper wires
- Breadboard

## Wiring Guide

### RFID MFRC522

| MFRC522 Pin | Arduino Uno |
|-------------|-------------|
| SDA         | Digital 10  |
| SCK         | Digital 13  |
| MOSI        | Digital 11  |
| MISO        | Digital 12  |
| IRQ         | Unconnected |
| GND         | GND         |
| RST         | Digital 9   |
| 3.3V        | 3.3V        |

> Important: The MFRC522 must be powered with `3.3V`, not `5V`.

### Servo Motor

| Servo Pin | Common Wire Color      | Arduino Uno |
|-----------|------------------------|-------------|
| GND       | Brown/Black            | GND         |
| VCC       | Red                    | 5V          |
| Signal    | Orange/Yellow/White    | Digital 6   |

### I2C LCD Module

| LCD Pin | Arduino Uno |
|---------|-------------|
| GND     | GND         |
| VCC     | 5V          |
| SDA     | A4          |
| SCL     | A5          |

### Buzzer

| Buzzer Pin | Arduino Uno |
|------------|-------------|
| GND        | GND         |
| VCC        | 5V          |
| Signal     | Digital 5   |

### LEDs

| LED Pin          | Arduino Uno |
|------------------|-------------|
| Green LED GND    | GND         |
| Green LED Signal | Digital 7   |
| Red LED GND      | GND         |
| Red LED Signal   | Digital 4   |

## Arduino Setup

1. Open `RFID_checkin_system.ino` in the Arduino IDE.
2. Install the required Arduino libraries:
   - `MFRC522`
   - `LiquidCrystal_I2C`
   - `Servo`
3. Update the `users[]` array in the sketch with the card IDs, names, and matric numbers you want to register.
4. Select the correct Arduino board and COM port.
5. Upload the sketch to the Arduino Uno.

## Database Setup

1. Create a MySQL/MariaDB database named `checkin_system`.
2. Import the SQL file:

   ```sql
   sql/checkin_system.sql
   ```

3. Confirm the database credentials in `connection.php`:

   ```php
   $conn = new mysqli('localhost', 'root', '', 'checkin_system');
   ```

## PHP Dashboard Setup

If you are using XAMPP, place the project folder inside `htdocs`, then visit the project from your browser.

For PHP's built-in server:

```sh
php -S localhost:8000
```

Then open:

```text
http://localhost:8000
```

## Node-RED Setup

1. Install Node-RED globally:

   ```sh
   npm install -g node-red
   ```

2. Start Node-RED:

   ```sh
   node-red
   ```

3. Open the Node-RED editor:

   ```text
   http://localhost:1880
   ```

4. Import `flows.json` into Node-RED.
5. Confirm the serial port matches your Arduino port, for example `COM9`.
6. Confirm the MySQL node points to the `checkin_system` database.
7. Deploy the flow.

## Default Login

The sample SQL file includes demo users. Example login details may be found in `login_details.txt` if included in your local copy.

## Notes

- The Arduino sketch controls access using hardcoded card IDs.
- Node-RED handles serial input and writes check-in records to MySQL.
- The dashboard uses AJAX polling to refresh statistics and records without requiring a manual page reload.
- If no data appears in the dashboard, check the Arduino serial port, Node-RED flow status, and database connection.
