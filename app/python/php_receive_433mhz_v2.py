#!/usr/bin/env python3
import pigpio
import time
import sys

GPIO_PIN = 27       # Pin für den 433 MHz Empfänger
MIN_INTERFRAME = 200000  # µs Pause = Ende eines Frames

pi = pigpio.pi()
if not pi.connected:
    sys.exit("pigpiod läuft nicht! Starte: sudo systemctl start pigpiod")

pulses = []
last_tick = 0

def cb(gpio, level, tick):
    global pulses, last_tick
    if last_tick != 0:
        pulse_len = pigpio.tickDiff(last_tick, tick)
        pulses.append(pulse_len)
    last_tick = tick

pi.set_mode(GPIO_PIN, pigpio.INPUT)
cb_handle = pi.callback(GPIO_PIN, pigpio.EITHER_EDGE, cb)

print("Sniffer läuft auf GPIO", GPIO_PIN)
print("Drücke deine Fernbedienung...")

try:
    while True:
        time.sleep(0.05)
        if pulses and (pigpio.tickDiff(last_tick, pi.get_current_tick()) > MIN_INTERFRAME):
            # Frame Ende erkannt → Code erzeugen
            frame = pulses[:]
            pulses = []

            # einfacher "Code" = Abfolge der Pulslängen
            code_str = "-".join(str(p) for p in frame)
            print("\n--- Signal empfangen ---")
            print("Pulslängen:", frame)
            print("Code:", code_str)
            print("------------------------")

except KeyboardInterrupt:
    print("\nBeende Sniffer...")
finally:
    cb_handle.cancel()
    pi.stop()
