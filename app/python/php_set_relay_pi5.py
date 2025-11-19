#!/usr/bin/env python3
import sys
import lgpio

# --- Argumente prüfen ---
if len(sys.argv) != 3:
    print("ERROR: python3 php_set_relay.py <GPIO_PIN> <0|1>")
    sys.exit(1)

try:
    gpio_pin = int(sys.argv[1])
    value = int(sys.argv[2])
    if value not in (0, 1):
        raise ValueError
except ValueError:
    print("ERROR: Invalid arguments. Use: <GPIO_PIN> <0|1>")
    sys.exit(1)


# --- GPIO-Chip öffnen (0 = Hauptchip auf Raspberry Pi 5) ---
try:
    h = lgpio.gpiochip_open(0)
except Exception as e:
    print("ERROR: Cannot open /dev/gpiochip0 (sudo required?)")
    print(e)
    sys.exit(1)

# --- GPIO als OUTPUT anfordern ---
lgpio.gpio_claim_output(h, gpio_pin)

# --- Wert setzen ---
lgpio.gpio_write(h, gpio_pin, value)
print(f"GPIO {gpio_pin} gesetzt auf {value}")

# --- GPIO-Chip schließen ---
lgpio.gpiochip_close(h)
