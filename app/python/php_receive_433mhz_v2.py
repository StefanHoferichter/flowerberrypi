#!/usr/bin/env python3
import pigpio
import time
import sys

GPIO = 27

# Min. Anzahl Pulse, die ein echter Brennenstuhl-Code hat
MIN_PULSES = 20

# Max Zeit ohne Puls, dann gilt Paket als fertig
PACKAGE_TIMEOUT = 0.025   # 25 ms

pi = pigpio.pi()
if not pi.connected:
    print("pigpiod not running!")
    sys.exit(1)

pi.set_mode(GPIO, pigpio.INPUT)
pi.set_pull_up_down(GPIO, pigpio.PUD_DOWN)

last_tick = None
pulses = []
collecting = False
last_pulse_time = time.time()


def edge_callback(gpio, level, tick):
    global last_tick, pulses, collecting, last_pulse_time

    now = time.time()
    if last_tick is None:
        last_tick = tick
        return

    dt = pigpio.tickDiff(last_tick, tick)  # µs
    last_tick = tick

    # Speichere nur Pulse zwischen 80 µs und 5000 µs (Noise-Filter wie RFSniffer)
    if 80 < dt < 5000:
        pulses.append(dt)
        collecting = True
        last_pulse_time = now


cb = pi.callback(GPIO, pigpio.EITHER_EDGE, edge_callback)

print("Python RFSniffer läuft… (Strg+C zum Beenden)")

try:
    while True:
        time.sleep(0.005)

        # Wenn wir gerade ein Paket sammeln und eine Pause kommt:
        if collecting and (time.time() - last_pulse_time) > PACKAGE_TIMEOUT:

            if len(pulses) >= MIN_PULSES:
                # Das macht RFSniffer: Pulse normalisieren und drucken
                print("Empfangen Paket ({} Pulse):".format(len(pulses)))
                print(pulses)

            # Reset
            pulses = []
            collecting = False

except KeyboardInterrupt:
    print("Beende…")

finally:
    cb.cancel()
    pi.stop()
