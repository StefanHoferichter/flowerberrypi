#!/usr/bin/env python3
from __future__ import print_function
import time
import sys
import lgpio


def measure_distance(trigger_pin, echo_pin, temp):
    # Chip 0 öffnen (gilt für Raspberry Pi 5)
    h = lgpio.gpiochip_open(0)

    # Trigger als OUTPUT, Echo als INPUT
    lgpio.gpio_claim_output(h, trigger_pin)
    lgpio.gpio_claim_input(h, echo_pin)

    # Trigger LOW
    lgpio.gpio_write(h, trigger_pin, 0)
    time.sleep(0.5)

    # 10 µs HIGH-Impuls
    lgpio.gpio_write(h, trigger_pin, 1)
    time.sleep(0.00001)
    lgpio.gpio_write(h, trigger_pin, 0)

    # Echo HIGH-Flanke messen
    start = time.perf_counter()
    timeout = start + 1

    while lgpio.gpio_read(h, echo_pin) == 0:
        start = time.perf_counter()
        if start > timeout:
            lgpio.gpiochip_close(h)
            return -1

    stop = start
    while lgpio.gpio_read(h, echo_pin) == 1:
        stop = time.perf_counter()
        if stop > timeout:
            lgpio.gpiochip_close(h)
            return -1

    elapsed = stop - start

    # Schallgeschwindigkeit abhängig von Temperatur
    speedSound = (331.3 + 0.6 * temp) * 100  # cm/s
    distance = (elapsed * speedSound) / 2

    # Chip schließen
    lgpio.gpiochip_close(h)

    return round(distance, 1)


# --- Kommandozeilenargumente ---
if len(sys.argv) != 4:
    print("ERROR: python3 php_read_distance_pi5.py <TRIG> <ECHO> <TEMP>")
    sys.exit(1)

try:
    trig = int(sys.argv[1])
    echo = int(sys.argv[2])
    temp = int(sys.argv[3])
except ValueError:
    print("ERROR: Please use only integers for GPIO pins.")
    sys.exit(1)

# --- Messen ---
dist = measure_distance(trig, echo, temp)

# --- Ausgabe ---
print(f"{dist:.3f}")
