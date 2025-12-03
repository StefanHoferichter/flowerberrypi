@extends('flowerberrypi')
 
@section('title', 'Home')
@section('submenu')
@include ('include_system_menu')  
@endsection

@section('content')

<h1>Home</h1>


<div class="float_text">
    Welcome to FlowerBerryPi, your smart personal watering solution for flowers and plants.<br><br>
    
    FlowerBerryPi uses soil moisture sensors to monitor the soil’s moisture level. Indoor temperature is measured with a sensor, while outdoor temperature is obtained from an online weather service. The water level in the tanks is monitored using ultrasonic distance sensors.<br>
    Plants are watered either via a Gardena Vacation Watering Set controlled by Brennenstuhl remote sockets or Shelly WiFi Sockets, or via 5V water pumps operated through a relay.<br>
    FlowerBerryPi combines hardware (Raspberry Pi, sensors, PCBs, cables) with software (a web UI built on Laravel).<br><br>
    
    FlowerBerryPi requires an initial configuration. First, zones need to be defined. A zone consists of a water tank with an ultrasonic level sensor and a pump. Optionally, soil moisture sensors can be added.<br>
    The GPIO pins can be configured for each sensor. By default, the configuration matches the PCBs that can be ordered.<br><br>
    
    FlowerBerryPi runs a background job every hour to track sensor values in the database. Three times a day (9 a.m., 1 p.m., and 5 p.m.), FlowerBerryPi makes watering decisions based on soil moisture, temperature, and remaining water levels.<br><br>
    
    Currently, three watering levels exist:
    <ul>
      <li>1: no watering</li>
      <li>2: medium watering</li>
      <li>3: strong watering</li>
    </ul>
    
	Additional waterings can be triggered from the UI, and manual waterings outside of FlowerBerryPi can also be tracked.<br>
    The thresholds for temperature, water level, and soil moisture are configurable. An optional camera can take pictures of your plants three times a day.<br>
		
</div>
@endsection