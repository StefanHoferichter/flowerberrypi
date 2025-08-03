from __future__ import print_function
import time
import sys
import RPi.GPIO as GPIO


def measure_distance(trigger_pin, echo_pin):
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
    start = time.time()
    timeout = start + 1  # Timeout nach 1 Sekunde

    while GPIO.input(echo_pin) == 0 and time.time() < timeout:
        start = time.time()
    while GPIO.input(echo_pin) == 1 and time.time() < timeout:
        stop = time.time()

    # Differenz berechnen
    elapsed = stop - start
    speedSound = 34300
    distance = (elapsed * speedSound) / 2

    # Aufräumen
    GPIO.cleanup()

    return round(distance, 1)


#dist1 = measure_distance(16,26)
#dist2 = measure_distance(23,24)
#print(f"{dist1:.3f},{dist2:.3f}")



# --- Kommandozeilenargumente prüfen ---
if len(sys.argv) != 3:
    print("❌ Verwendung: python3 script.py <TRIG> <ECHO>")
    sys.exit(1)

try:
    trig1 = int(sys.argv[1])
    echo1 = int(sys.argv[2])
except ValueError:
    print("❌ Bitte nur Ganzzahlen für GPIO-Pins verwenden.")
    sys.exit(1)

# --- Messen ---
dist1 = measure_distance(trig1, echo1)

# --- Ausgabe ---
print(f"{dist1:.3f}")
