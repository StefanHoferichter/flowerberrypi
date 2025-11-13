#!/usr/bin/env python3
from rpi_rf import RFDevice
import time
import sys

# === CONFIGURATION ===
DEFAULT_GPIO = 17
DEFAULT_CODE = 1315153
PULSE_LENGTH = 340     # Optimierte Pulsbreite
PROTOCOL = 1           # Protokoll

# === ARGUMENTE ===
GPIO_PIN = int(sys.argv[1]) if len(sys.argv) > 1 else DEFAULT_GPIO
OUTLET_CODE = int(sys.argv[2]) if len(sys.argv) > 2 else DEFAULT_CODE

# === FUNCTIONS ===
def send_code(code):
    print(f"Steckdose {OUTLET_CODE} steuern auf GPIO {GPIO_PIN}")
    rfdevice.tx_code(code, PROTOCOL, PULSE_LENGTH)
    time.sleep(0.1)  # kurze Pause

# === MAIN ===
if __name__ == "__main__":
    rfdevice = RFDevice(GPIO_PIN)
    rfdevice.enable_tx()
    try:
        # Test: einschalten und ausschalten
        send_code(OUTLET_CODE)
    finally:
        rfdevice.cleanup()
        print("GPIO-Sender sauber beendet")
