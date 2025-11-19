#!/usr/bin/env python3
import lgpio
import time
import os
import sys

# --- Real-Time Mode aktivieren ---
try:
    os.sched_setscheduler(0, os.SCHED_FIFO, os.sched_param(10))
except PermissionError:
    print("Bitte mit sudo ausführen!")
    sys.exit(1)

# --- Input ---
if len(sys.argv) != 3:
    print("Usage: python send_433_pi5.py <GPIO> <CODE>")
    sys.exit(1)

gpio_pin = int(sys.argv[1])
code = sys.argv[2]

# --- Timings für Brennenstuhl RCS1000N ---
T = 350e-6  # 350 µs

def send_bit(hchip, pin, bit):
    if bit == "1":
        lgpio.gpio_write(hchip, pin, 1)
        time.sleep(T)
        lgpio.gpio_write(hchip, pin, 0)
        time.sleep(3*T)
    else:
        lgpio.gpio_write(hchip, pin, 1)
        time.sleep(3*T)
        lgpio.gpio_write(hchip, pin, 0)
        time.sleep(T)

def send_sync(hchip, pin):
    lgpio.gpio_write(hchip, pin, 1)
    time.sleep(T)
    lgpio.gpio_write(hchip, pin, 0)
    time.sleep(31*T)

def send_code(hchip, pin, code):
    for _ in range(6):     # Brennenstuhl erwartet 5–8 Wiederholungen
        for bit in code:
            send_bit(hchip, pin, bit)
        send_sync(hchip, pin)
        time.sleep(0.005)

# --- Start ---
h = lgpio.gpiochip_open(0)
lgpio.gpio_claim_output(h, gpio_pin)

print(f"Sende Code {code} auf GPIO {gpio_pin}...")

send_code(h, gpio_pin, code)

lgpio.gpiochip_close(h)

print("Fertig.")
