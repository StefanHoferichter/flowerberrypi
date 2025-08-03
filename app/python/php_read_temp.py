from pigpio_dht import DHT11
import pigpio
import time
import sys

# Kommandozeilenargument prüfen
if len(sys.argv) != 2:
    print("❌ Verwendung: python3 script.py <GPIO_PIN>")
    print("Beispiel: python3 script.py 22")
    sys.exit(1)


try:
    gpio_pin = int(sys.argv[1])
except ValueError:
    print("❌ Ungültiger GPIO-Pin. Bitte eine Zahl angeben.")
    sys.exit(1)

pi = pigpio.pi()
if not pi.connected:
    print("❌ pigpio daemon nicht verbunden. Bitte 'sudo pigpiod' starten.")
    exit(1)
    
# Sensor initialisieren
sensor = DHT11(gpio_pin, pi=pi)

result = sensor.read()
if result['valid']:
    print(f"{result['temp_c']},{result['humidity']}")
else:
    print("❌ Fehler beim Lesen")

pi.stop()
