import pigpio
import time

GPIO_PIN = 27  # Dein Empfänger GPIO
MIN_PULSES = 6  # Mindestens 6 pulse-Paare (12 Pulswerte) für ein gültiges Paket

# Toleranzen für alte und neue Brennenstuhl FBs
SHORT_MIN = 150
SHORT_MAX = 700
LONG_MIN = 800
LONG_MAX = 3000
SYNC_MIN = 2000  # µs Pause, ab der neues Paket erkannt wird

pi = pigpio.pi()
if not pi.connected:
    raise RuntimeError("pigpiod läuft nicht!")

last_tick = None
pulse_times = []
collecting = False


def classify_pulse(p):
    if SHORT_MIN <= p <= SHORT_MAX:
        return "S"
    elif LONG_MIN <= p <= LONG_MAX:
        return "L"
    else:
        return None


def process_packet(timings):
    # Prüfen, ob genug Pulse da sind
    if len(timings) < MIN_PULSES * 2:
        return

    # Puls-Paare in Bits umwandeln
    bits = ""
    for i in range(0, len(timings) - 1, 2):
        a = classify_pulse(timings[i])
        b = classify_pulse(timings[i + 1])

        if a == "S" and b == "L":
            bits += "0"
        elif a == "L" and b == "S":
            bits += "1"
        else:
            # Ungültiges Paar → Paket verwerfen
            return

    if len(bits) < 12:
        return

    try:
        code = int(bits, 2)
        print(f"Received {code}")
    except ValueError:
        return


def edge_callback(gpio, level, tick):
    global last_tick, pulse_times, collecting

    if last_tick is None:
        last_tick = tick
        return

    dt = pigpio.tickDiff(last_tick, tick)
    last_tick = tick

    # Sync-Erkennung: Langes Gap = neues Paket
    if dt >= SYNC_MIN:
        if pulse_times:
            process_packet(pulse_times[:])
        pulse_times = []
        collecting = True
        return

    if collecting:
        pulse_times.append(dt)


cb = pi.callback(GPIO_PIN, pigpio.EITHER_EDGE, edge_callback)

print("RFSniffer-Clone gestartet. Drücke eine Taste auf der Fernbedienung...")

try:
    while True:
        time.sleep(1)
except KeyboardInterrupt:
    pass
finally:
    cb.cancel()
    pi.stop()
