from pigpio_dht import DHT11
import pigpio
import time
import sys

# Kommandozeilenargument prüfen
if len(sys.argv) != 2:
    print("ERROR: python php_read_temp.py <GPIO_PIN>")
    sys.exit(1)


try:
    gpio_pin = int(sys.argv[1])
except ValueError:
    print("ERROR: Invalid GPIO-Pin. ")
    sys.exit(1)

pi = pigpio.pi()
if not pi.connected:
    print("ERROR: pigpio daemon not connected. run 'sudo pigpiod'.")
    exit(1)
    
# Sensor initialisieren
sensor = DHT11(gpio_pin, pi=pi)

MAX_ATTEMPTS = 5
for attempt in range(MAX_ATTEMPTS):
    result = sensor.read()
    if result['valid']:
        print(f"{result['temp_c']},{result['humidity']}")
        pi.stop()
        sys.exit(0)
    time.sleep(2)  # kurz warten vor erneutem Versuch

print("ERROR: during reading of sensor (all attempts failed).")
pi.stop()

