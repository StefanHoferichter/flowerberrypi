# FlowerBerryPi

<p align="center">
    <img src="https://www.hoferichter.net/logo/flowerberrypi_logo_homepage.png" alt="FlowerBerryPi">
</p>

Welcome to FlowerBerryPi, your smart personal watering solution for flowers and plants.

FlowerBerryPi uses soil moisture sensors to monitor the soil’s moisture level. Indoor temperature is measured with a sensor, while outdoor temperature is obtained from an online weather service. The water level in the tanks is monitored using ultrasonic distance sensors.
Plants are watered either via a Gardena Vacation Watering Set controlled by Brennenstuhl remote sockets or Shelly WiFi Sockets, or via 5V water pumps operated through a relay.
FlowerBerryPi combines hardware (Raspberry Pi, sensors, PCBs, cables) with software (a web UI built on Laravel).

FlowerBerryPi can be integrated with Home Assistant. (https://www.home-assistant.io)

FlowerBerryPi requires an initial configuration. First, zones need to be defined. A zone consists of a water tank with an ultrasonic level sensor and a pump. Optionally, soil moisture sensors can be added.
The GPIO pins can be configured for each sensor. By default, the configuration matches the PCBs that can be ordered.

FlowerBerryPi runs a background job every hour to track sensor values in the database. Three times a day (9 a.m., 1 p.m., and 5 p.m.), FlowerBerryPi makes watering decisions based on soil moisture, temperature, and remaining water levels.

Currently, three watering levels exist:

    1: no watering
    2: medium watering
    3: strong watering

Additional waterings can be triggered from the UI, and manual waterings outside of FlowerBerryPi can also be tracked.
The thresholds for temperature, water level, and soil moisture are configurable. An optional camera can take pictures of your plants three times a day.

# Prerequisites

FlowerBerryPi requires hardware and software:
*	1 Raspberry Pi Model 2, 3, 4 oder 5 with Raspberry Pi OS Bookworm (Trixie currently not supported)
*	FlowerberryPi Software (This repo, PHP-Laravel-App with MariaDB)
*	FlowerBerryPi HAT PCB for Raspberry Pi
*	FlowerberryPi Soil Moisture PCB for reading 8 moisture sensors via ADC ADS 1115
*	Sensors like DHT11, HC-SR04, moisture sensor V2.0, Raspberry Pi camera 1.3
*	additional elements
 

# Installation

The installation guide can be found at: https://www.hoferichter.net/fbp/installation_guide.php.
