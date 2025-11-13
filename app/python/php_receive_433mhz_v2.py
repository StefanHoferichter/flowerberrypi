#!/usr/bin/env python3
import sys
import time
import pigpio

DEFAULT_GPIO = 27
GPIO_PIN = int(sys.argv[1]) if len(sys.argv) > 1 else DEFAULT_GPIO

# --- Parameter ---
MIN_PULSE = 200      # µs, alles darunter wird ignoriert
MAX_PULSE = 3500     # µs, alles darüber wird ignoriert
FRAME_GAP = 5000     # µs, Pause >5ms = Frame-Ende
MIN_PULSES = 30      # Minimum Pulse pro Telegramm

print(f"📡 Starte MX-F04 Sniffer auf GPIO {GPIO_PIN}")

pi = pigpio.pi()
if not pi.connected:
    print("❌ pigpiod nicht verbunden!")
    sys.exit(1)

pi.set_mode(GPIO_PIN, pigpio.INPUT)
pi.set_pull_up_down(GPIO_PIN, pigpio.PUD_DOWN)

last_tick = None
pulses = []

def decode_rpirf(pulses_raw):
    pulses = [p for p in pulses_raw if MIN_PULSE <= p <= MAX_PULSE]
    if len(pulses) < MIN_PULSES:
        return None
    shortest = min(pulses)
    bits = ""
    for p in pulses:
        if abs(p - shortest) < shortest * 0.4:
            bits += "0"
        elif abs(p - shortest*3) < shortest:
            bits += "1"
        else:
            return None
    if len(bits) < 10:
        return None
    return int(bits, 2), shortest, 1

def edge_callback(gpio, level, tick):
    global last_tick, pulses
    if last_tick is not None:
        diff = pigpio.tickDiff(last_tick, tick)
        if diff > FRAME_GAP:
            result = decode_rpirf(pulses)
            if result:
                code, pulselen, proto = result
                print(f"📥 Empfangen: Code={code}  Pulse={pulselen}  Proto={proto}")
            pulses = []
        else:
            if MIN_PULSE <= diff <= MAX_PULSE:
                pulses.append(diff)
    last_tick = tick

cb = pi.callback(GPIO_PIN, pigpio.EITHER_EDGE, edge_callback)

try:
    while True:
        time.sleep(0.1)
except KeyboardInterrupt:
    print("\n⏹ Beende Sniffer...")
finally:
    cb.cancel()
    pi.stop()
