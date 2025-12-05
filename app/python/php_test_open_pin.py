#!/usr/bin/env python3
import pigpio
import time
import sys

# --- Argument prüfen ---
if len(sys.argv) != 2:
    sys.exit(3)  # 3 = Argumentfehler, damit PHP weiß, dass was nicht passt

try:
    PIN = int(sys.argv[1])
except ValueError:
    sys.exit(3)

pi = pigpio.pi()
if not pi.connected:
    sys.exit(4)  # 4 = pigpiod nicht erreichbar

def read_with_pull(pin, pull):
    pi.set_pull_up_down(pin, pull)
    time.sleep(0.02)
    return pi.read(pin)

# 1. Pull-Up lesen
up = read_with_pull(PIN, pigpio.PUD_UP)

# 2. Pull-Down lesen
down = read_with_pull(PIN, pigpio.PUD_DOWN)

# Entscheidung:
# 0 = extern auf GND
# 1 = extern auf 3.3V
# 2 = offen

if up == 0 and down == 0:
    print("0")
elif up == 1 and down == 1:
    print("1")
elif up == 1 and down == 0:
    print("2")
else:
    print("3")  # unerwarteter Zustand (sollte nie auftreten)
