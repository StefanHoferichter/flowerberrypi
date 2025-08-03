import pigpio
import sys

# --- Argumente prüfen ---
if len(sys.argv) != 3:
    print("❌ Verwendung: python3 script.py <GPIO_PIN> <0|1>")
    print("Beispiel: python3 script.py 21 1")
    sys.exit(1)

try:
    gpio_pin = int(sys.argv[1])
    value = int(sys.argv[2])
    if value not in (0, 1):
        raise ValueError
except ValueError:
    printf(f"❌ Ungültige Eingabe {value}. Bitte verwende 0 oder 1 als Wert.")
    sys.exit(1)

# --- Verbindung zum pigpio-Daemon ---
pi = pigpio.pi()
if not pi.connected:
    print("❌ pigpiod nicht verbunden. Starte ihn mit: sudo pigpiod")
    sys.exit(1)

# --- Pin konfigurieren und Wert setzen ---
pi.set_mode(gpio_pin, pigpio.OUTPUT)
pi.write(gpio_pin, value)
print(f"✅ GPIO {gpio_pin} gesetzt auf {value}")

# --- Verbindung schließen ---
pi.stop()
