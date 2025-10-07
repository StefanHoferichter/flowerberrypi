from __future__ import print_function
import time
import sys
import RPi.GPIO as GPIO


def measure_distance(trigger_pin, echo_pin, temp):
    # BCM-Modus verwenden
    GPIO.setmode(GPIO.BCM)

    # Pins initialisieren
    GPIO.setup(trigger_pin, GPIO.OUT)
    GPIO.setup(echo_pin, GPIO.IN)

    # Trigger auf LOW setzen und kurz warten
    GPIO.output(trigger_pin, False)
    time.sleep(0.5)

    # 10µs Impuls senden
    GPIO.output(trigger_pin, True)
    time.sleep(0.00001)
    GPIO.output(trigger_pin, False)

    # Zeitmessung starten
    start = time.perf_counter()
    timeout = start + 1  # Timeout nach 1 Sekunde

    while GPIO.input(echo_pin) == 0 and time.perf_counter() < timeout:
        start = time.perf_counter()

    stop = start
    while GPIO.input(echo_pin) == 1 and time.perf_counter() < timeout:
        stop = time.perf_counter()

    # Differenz berechnen
    elapsed = stop - start
#    speedSound = 34300
    speedSound = (331.3 + 0.6 * temp) * 100
    distance = (elapsed * speedSound) / 2

    # Aufräumen
    GPIO.cleanup()

    return round(distance, 1)


#dist1 = measure_distance(16,26)
#dist2 = measure_distance(23,24)
#print(f"{dist1:.3f},{dist2:.3f}")



# --- Kommandozeilenargumente prüfen ---
if len(sys.argv) != 4:
    print("ERROR: python php_read_distance.py <TRIG> <ECHO> <TEMP>")
    sys.exit(1)

try:
    trig1 = int(sys.argv[1])
    echo1 = int(sys.argv[2])
    temp = int(sys.argv[3])
except ValueError:
    print("ERROR: Please use only Integers for GPIO-Pins.")
    sys.exit(1)

# --- Messen ---
dist1 = measure_distance(trig1, echo1, temp)

# --- Ausgabe ---
print(f"{dist1:.3f}")
