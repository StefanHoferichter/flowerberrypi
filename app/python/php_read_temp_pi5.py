#!/usr/bin/env python3
import time
import board
import adafruit_dht
import sys

# Kommandozeilenargument prüfen
if len(sys.argv) != 2:
    print("ERROR: python3 read_temp_pi5.py <GPIO_PIN>")
    sys.exit(1)

gpio_pin = int(sys.argv[1])
pin = getattr(board, f"D{gpio_pin}")

sensor = adafruit_dht.DHT11(pin)

MAX_ATTEMPTS = 5
for attempt in range(MAX_ATTEMPTS):
    try:
        temperature = sensor.temperature
        humidity = sensor.humidity
        if temperature is not None and humidity is not None:
            print(f"{temperature},{humidity}")
            sensor.exit()   # <<< WICHTIG!!!
            sys.exit(0)
    except Exception:
        pass
    time.sleep(2)

sensor.exit()  # <<< WICHTIG!!!
print("ERROR: during reading of sensor (all attempts failed).")
sys.exit(1)
